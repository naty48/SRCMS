<?php
class user
{
//todo: blablal
	public static function logout()
	{
		if(isset($_SESSION['username']))
		{
			session_destroy();
			return true;
		}
		else return false;
	}
	
	public static function login($szUsername, $szPassword)
	{
		core::$sql -> changeDB("acc");
		if(core::$sql -> numRows("select * from TB_User where StrUserID='$szUsername' and password='".md5($szPassword)."' ") > 0)
		{
			$_SESSION['username'] = strtolower($szUsername); //fix
			return true;
		}
		else
		return false;
	}
	
	public static function getUserAvatarUrl($szUsername)
	{
		$nJID = user::accountJIDbyUsername($szUsername);
		return core::$sql -> getRow("select avatar from srcms_userprofiles where JID='$nJID'");
	}
	
	
	public static function accountJIDbyUsername($szUsername)
	{
		core::$sql -> changeDB("acc");
		return (core::$sql -> getRow("select JID from TB_User where StrUserID='$szUsername'"));
	}
	
	public static function usernamyByJID($nJID)
	{
		core::$sql -> changeDB("acc");
		return (core::$sql -> getRow("select StrUserID from TB_User where JID='$nJID'"));
	}
	
	public static function charIDsByUsername($szUsername)
	{
		$result = array();
		core::$sql -> changeDB("shard");
		
		$nJID = core::$sql -> getRow("select JID from _AccountJID where AccountID='$szUsername'");
		$hQuery = mssql_query("select CharID from _User where UserJID='$nJID'");
		$i = 0;
		while($row = mssql_fetch_row($hQuery))
		{
			$result[$i] = $row[0];
			$i++;
		}
		return $result;
	}
	
	public static function AccountByCharName128($szUsername)
	{
	core::$sql -> changeDB("shard");
	$sID = core::$sql -> getRow("select CharID from _Char WHERE CharName16 = '$szUsername'");
	$sJID = core::$sql -> getRow("select UserJID from _User where CharID = '$sID'");
	return $sJID;
	}
	
	public static function usernameByCharname($szCharname)
	{
		core::$sql -> changeDB("shard");
		$nCharID = char::charIDByCharname($szCharname);
		
		$nJID = core::$sql -> getRow("select UserJID from _User where CharID='$nCharID'");
		
		
		core::$sql -> changeDB("acc");
		return core::$sql -> getRow("select StrUserID from TB_User where JID='$nJID'");
	}
	
	public static function changePassword($szUsername, $szOldPassword, $szNewPassword)
	{
		core::$sql -> changeDB("acc");
		if(core::$sql -> numRows("select * from TB_User where StrUserID='$szUsername' and password='".md5($szOldPassword)."'") > 0)
		{
			core::$sql -> exec("update TB_User set password='".md5($szNewPassword)."' where StrUserID='$szUsername'");
			return true;
		}
		else return false;
	}
	
	public static function RestorePass($szUid, $szNewPassword)
	{
		core::$sql -> changeDB("acc");
		$szUsernameNew = core::$sql -> getRow("select UserID from PW_Restore where RandomPASS = '$szUid'");
		if(core::$sql -> numRows("select * from PW_Restore where RandomPASS = '$szUid'") > 0)
		{
			core::$sql -> exec("update TB_User set password='".md5($szNewPassword)."' where StrUserID='$szUsernameNew'");
			core::$sql -> exec("delete from PW_Restore where RandomPASS = '$szUid'");
			return true;
		}
		else return false;
	}
		
	public static function ChangeMail($szUid, $szNewMail)
	{
		core::$sql -> changeDB("acc");
		$szUsernameNew = core::$sql -> getRow("select UserID from Email_Change where RandomPASS = '$szUid'");
		if(core::$sql -> numRows("select * from Email_Change where RandomPASS = '$szUid'") > 0)
		{
			core::$sql -> exec("update TB_User set Email='$szNewMail' where StrUserID='$szUsernameNew'");
			core::$sql -> exec("delete from Email_Change where RandomPASS = '$szUid'");
			return true;
		}
		else return false;
	}
	
	public static function addSilk($szUsername, $nAmount)
	{
		core::$sql -> changeDB("acc");
		$nJID = user::accountJIDbyUsername($szUsername);
		if($nJID > 0)
		{
			if(core::$sql -> numRows("select * from SK_Silk where JID='$nJID'") > 0)
			{
				core::$sql -> exec("update SK_Silk set silk_own=silk_own+$nAmount where JID='$nJID'");
				return true;
			}
			else
			{
				core::$sql -> exec("insert into SK_Silk(JID,silk_own,silk_gift,silk_point) values('$nJID','$nAmount','0','0')");
				return true;
			}
		}
		else return false; //no user with such JID found
	}
	
	
	public static function silkgift($szSenderCharacter, $nAmount, $szReciverCharacter)
	{
		core::$sql -> changeDB("acc");
		$nJID = user::accountJIDbyUsername($szUsername);
		if($nJID > 0)
		{
			if(core::$sql -> numRows("select * from SK_Silk where JID='$nJID'") > 0)
			{
				core::$sql -> exec("update SK_Silk set silk_own=silk_own+$nAmount where JID='$nJID'");
				return true;
			}
			else
			{
				core::$sql -> exec("insert into SK_Silk(JID,silk_own,silk_gift,silk_point) values('$nJID','$nAmount','0','0')");
				return true;
			}
		}
		else return false; //no user with such JID found
	}
	
	public static function getSilkByUsername($szUsername)
	{
		core::$sql -> changeDB("acc");
		$nJID = user::accountJIDbyUsername($szUsername);
	
		
		$nSilk = core::$sql -> getRow("select silk_own from SK_Silk where JID='$nJID'");
		
		if(core::$sql -> numRows("select * from SK_Silk where JID='$nJID'") == 0)
		{
			core::$sql -> exec("insert into SK_Silk(JID,silk_own,silk_gift,silk_point) values('$nJID','0','0','0')");
			return 0;
		}
		return $nSilk;
	}
	
	
	
	public static function accountExists($username)
	{
		core::$sql -> changeDB("acc");
		return core::$sql -> numRows("select * from TB_User where StrUserID='$username'");
	//	if($result == 1) return true;
		//return false;
	}
	
	public static function sendWebPrivMsg($to, $from, $title, $text)
	{
		global $core;
		
					$msgTitle = security::toHTML($title);
					$msgText = security::toHTML($text);
					$senderJID = user::accountJIDbyUsername($from);
					$receiverJID = user::accountJIDbyUsername($to);
					if($senderJID != $receiverJID)
					{
						if(core::$sql -> numRows("select * from srcms_privatemessages where receiver='$receiverJID'") >= $core -> aConfig['maxPrivMsg']) 
						{
							echo "Receiver inbox is full.<br/>";
						}
						else
						{
							if(strlen($msgTitle) < $core -> aConfig['minPrivMsgTitleLen'] || strlen($msgTitle) > $core -> aConfig['maxPrivMsgTitleLen']
							|| strlen($msgText) < $core -> aConfig['minPrivMsgBodyLen'] || strlen($msgText) > $core -> aConfig['maxPrivMsgBodyLen'])
							{
								echo "Message text, or title is too long or too short. Minimum title length is ".$core -> aConfig['minPrivMsgTitleLen']." and ".$core -> aConfig['maxPrivMsgTitleLen']."symbols
								maximum. Message content minimum length is ".$core -> aConfig['minPrivMsgBodyLen']." and ".$core -> aConfig['maxPrivMsgBodyLen']." symbols maximum.<br/>";
								misc::back();
							}
							else
							{
								$datetime = misc::getDateTime();
								core::$sql -> exec("insert into srcms_privatemessages(sender,receiver, title, msg, viewed, time) values('$senderJID', '$receiverJID', '$msgTitle', '$msgText', '0', '$datetime')");
								echo "<br/>Message sent.<br/>";
								misc::redirect("?pg=ucp&act=mailbox", 2);
							}
						}
					}
					else 
					{
						echo "You can't send message to yourself.<br/>";
						misc::back();
					}
	}
	
	public static function isAdmin($szUsername)
	{
		core::$sql -> changeDB('acc');
		$bResult = false;
		if(core::$sql -> getRow("select whois from srcms_userprofiles where JID='".user::accountJIDbyUsername($szUsername)."'") == 'admin')
		{
			$bResult = true;
		}
		return $bResult;
	}
	
	public static function getRankText($szRank)
	{
		$szRes = "";
		switch($szRank)
		{
			case('user'):
				$szRes = "Normal User";
			break;
				
			case('admin'):
				$szRes = "Administrator";
			break;
			
			default:$szRes = "Unknown rank"; break;
		}
		
		return $szRes;
	}
	public static function viewProfile($szUsername)
	{
		$userData = core::$sql -> fetchArray("select * from srcms_userprofiles where JID='".user::accountJIDbyUsername($szUsername)."'");
		
		$gender = null;
		if($userData['gender'] == '0') 
		{
			$gender="Male";
		}
		else
		{
			$gender = "Female";
		}
		
		$szRank = core::$sql->getRow("select whois from srcms_userprofiles where JID='".user::accountJIDbyUsername($szUsername)."'");
		
		$szRank = user::getRankText($szRank);
	
		echo "
				<table id='table-3' border='1' cellpadding='0' cellspacing='0'>
					<form method='post'>
					<td>Username</td><td>$szUsername</td><tr/>
					<td>Rank</td><td>$szRank</td><tr/>
					<td>Gender</td><td>$gender</td><tr/>
					<td>Avatar</td><td><img src='$userData[avatar]'></img></td><tr/>
					<td>Skype</td><td>$userData[skype]</td><tr/>
					<td>MSN</td><td>$userData[msn]</td><tr/>			
					</form>
				</table>
				";
		global $core;
		
		if($core -> aConfig['allowShowCharOwner'] == 1)
		{
		//list characters
		$nChars = char::getCharCount($szUsername);
		if($nChars > 0)
		{
			$naChars = user::charIDsByUsername($szUsername);
			$naCharNames = char::charNamesByIDs($naChars);
			
			echo "<br/><br/><b>Characters on account</b><table id='table-3' border='1'><td>Char name</td><tr/>";
			foreach($naCharNames as $nElem)
			{
				echo "<td><a href='?pg=rank&type=char&name=$nElem'>$nElem</a></td><tr/>";
			}
			echo "</table>";
			
		
		} else echo "<br/>This user has no characters.<br/>";
		
				if($_SESSION['username'] == $szUsername)
				{
					return;
				}
				
		}
				if($core -> aConfig['allowMailbox'] == 1) 
				{
				
					if(isset($_SESSION['username']))
					{
						if(!isset($_POST['submit']))
						{
							echo "<br/><b>Send private message</b><br/>
							<form method='post'>
							To: <b>$_GET[username]</b><br/><br/>
							<input type='text' name='msgTitle' value='Message title'><br/>";
							echo "
							<br/><textarea id='sendUserPrivMsgTextBox' name='msgText' rows='2' cols='100'>Type your message here</textarea><br/>
							<input type='submit' name='submit' value='Send'>
							</form>
							<script>CKEDITOR.replace( 'msgText' );</script>
								 ";
						}
						else user::sendWebPrivMsg($szUsername, $_SESSION['username'], $_POST['msgTitle'], $_POST['msgText']);
					
					
					} else echo "You must be logged in in order to send private messages.<br/>";
				}
	}
	
}
?>