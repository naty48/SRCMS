<?php
$userRank = core::$sql->getRow("select whois from srcms_userprofiles where JID='".user::accountJIDbyUsername($_SESSION[username])."'");
if($userRank != "admin")
{
	echo "NOT ADMIN";
	return;
}

switch($_GET['act'])
{
	case('news'):
		if(!isset($_GET['subact']))
		{
			echo "<a href='?pg=admin&act=news&subact=add'><b>Add</b></a><br/>
				  <a href='?pg=admin&act=news&subact=del'><b>Delete</b></a><br/>
				  <a href='?pg=admin&act=news&subact=edit'><b>Edit</b></a><br/>";
		}
		else
		{
			switch($_GET['subact'])
			{
				case('add'):
					if(!isset($_POST['submit']) && !isset($_POST['title']))
					{
						echo "<table id='table-3' border='1' cellpadding='0' cellspacing='0'>
								<form method='post'>
									<td>Title</td><td><input type='text' name='title'></td><tr/>
									<td>Text</td><td><textarea id = 'textBox' name='textBox' rows='5' cols='100'>Type your message here</textarea></td><tr/>
									<td></td><td><input type='submit' name='submit' value='Add'></td>
								</form>
							  </table><br/>
							  
							 <script>CKEDITOR.replace( 'textBox' );</script>
							  
							  ";
					}
					else
					{
						$szTitle = security::toHTML($_POST['title']);
						//$szText = misc::applyAttributesToText($_POST['textBox']);
						$szText = stripslashes(security::toHTML($_POST['textBox']));
						
						core::$sql -> exec("insert into srcms_news(title,content,author,time) values('$szTitle','$szText','$_SESSION[username]','".misc::getDateTime()."')");
						echo "News article added.<br/>";
						misc::redirect('?pg=admin&act=news', 1);
					}
				break;
				
				case('del'):
						if(isset($_GET['id']))
						{
							$nID = (int)$_GET['id'];
							if(core::$sql -> numRows("select * from srcms_news where id='$nID'") > 0)
							{
								core::$sql -> exec("delete from srcms_news where id='$nID'");
								core::$sql -> exec("delete from srcms_newscomments where newsID='$nID'");
								echo "Article deleted.<br/>";
								misc::redirect('?pg=admin&act=news&subact=del',1);
								break;
							}
							else
							{
								echo "You can't delete article that does not exist<br/>";
							}
						}
						
						if(!isset($_POST['submit']) && !isset($_GET['id']))
						{
							echo "<table id='table-3' border='1' cellpadding='0' cellspacing='0'>
									<td>ID</td><td>Title</td><td>Author</td><td>Time</td><td>Action</td><tr/>";
							$hNewsArticles = core::$sql -> exec("select * from srcms_news");
							if(core::$sql -> numRows("select * from srcms_news") == 0)
							{
								echo "No news added yet<br/>";
							}
							else
							{
								while($row = mssql_fetch_array($hNewsArticles))
								{
									echo "<td>$row[id]</td><td>$row[title]</td><td>$row[author]</td><td>$row[time]</td><td><a href='?pg=admin&act=news&subact=del&id=$row[id]'>Delete</a><tr/>";
								}
								
							
							}
							echo "</table>";
						}
				break;
				
				case('edit'):
						if(!isset($_POST['submit']) && !isset($_GET['id']))
						{
							echo "<table id='table-3' border='1' cellpadding='0' cellspacing='0'>
									<td>ID</td><td>Title</td><td>Author</td><td>Time</td><td>Action</td><tr/>";
								
								$hNewsArticles = core::$sql -> exec("select * from srcms_news");
								if(mssql_num_rows($hNewsArticles) == 0)
								{
									echo "No news added yet<br/>";
								}
								else
								{
									while($row = mssql_fetch_array($hNewsArticles))
									{
										echo "<td>$row[id]</td><td>$row[title]</td><td>$row[author]</td><td>$row[time]</td><td><a href='?pg=admin&act=news&subact=edit&id=$row[id]'>Edit</a></td><tr/>";
									}
								}
								echo "</table>";
						}
						else
						{
							$nID = (int)$_GET['id'];
							if(core::$sql -> numRows("select * from srcms_news where id='$nID'") == 0)
							{
								echo "You can't edit article that does not exist<br/>";
							}
							else
							{
								if($_POST['submit'] != 'Save')
								{
									$hArticleData = core::$sql -> exec("select * from srcms_news where id='$nID'");
									$hArticleData = mssql_fetch_array($hArticleData);
									echo "<table id='table-3' border='1' cellpadding='0' cellspacing='0'>
											<form method='post'>
												<td>Title</td><td><input type='text' name='title' value='$hArticleData[title]'></td><tr/>
												<td>Text</td><td>
												<textarea id = 'textBox' name='textBox' rows='5' cols='100'>
												$hArticleData[content]
												</textarea></td><tr/>
												<td></td><td><input type='submit' name='submit' value='Save'></td>
											</form>
											</table>
											<script>CKEDITOR.replace( 'textBox' );</script>
											
											";
								}
								else
								{
									$szTitle = security::toHTML($_POST['title']);
									$szText = stripslashes(security::toHTML($_POST['textBox']));
									core::$sql -> exec("update srcms_news set title='$szTitle',content='$szText' where id='$nID'");
									echo "News article edited<br/>";
									misc::redirect('?pg=admin&act=news&subact=edit', 1);
								}
							}
						}
				break;
				
				
				default:
					echo "Unknown subaction";
					break;
			}
			echo "<br/>";misc::back();
		}
	break;
	
	case('dl'):
		if(!isset($_GET['subact']))
		{
			echo "<a href='?pg=admin&act=dl&subact=add'><b>Add</b></a><br/>
				  <a href='?pg=admin&act=dl&subact=del'><b>Delete</b></a><br/>
				  <a href='?pg=admin&act=dl&subact=edit'><b>Edit</b></a><br/>";
		}
		else
		{
			switch($_GET['subact'])
			{
				case('add'):
					if(!isset($_POST['submit']) && !isset($_POST['link']))
					{
						echo "<table id='table-3' border='1' cellpadding='0' cellspacing='0'>
								<form method='post'>
									<td>Name</td><td><input type='text' name='name'></td><tr/>
									<td>Link</td><td><input type='text' name='link'></td><tr/>
									<td>Description</td><td><input type='text' name='description'></td><tr/>
									<td></td><td><input type='submit' name='submit' value='Add'></td>
								</form>
								</table>
							 ";
							 
					}
					else
					{
						$szName = security::toHTML($_POST['name']);
						$szDesc = security::toHTML($_POST['description']);
						
						if(!security::isValidUrl($_POST['link']))
						{
							misc::back();
							echo "Invalid URL<br/>";
							break;
						}
						else
						{
							core::$sql -> exec("insert into srcms_downloads(name,link,description) values('$szName','$_POST[link]','$szDesc')");
							echo "Successfully added link to downloads.<br/>";
							misc::redirect('?pg=admin&act=dl',1);
						}
					}
				break;
				
				case('del'):
					if(!isset($_POST['submit']) && !isset($_GET['id']))
					{
						echo "<table id='table-3' border='1' cellpadding='0' cellspacing='0'>
								<td>ID</td><td>Name</td><td>Link</td><td>Description</td><td>Delete</td><tr/>
							 ";
							 
							 $hLinkList = core::$sql -> exec("select * from srcms_downloads");
							 if(mssql_num_rows($hLinkList) > 0)
							 {
								while($row = mssql_fetch_array($hLinkList))
								{
									echo "<td>$row[id]</td><td>$row[name]</td><td>$row[link]</td><td>$row[description]</td><td><a href='?pg=admin&act=dl&subact=del&id=$row[id]'>Delete</a></td><tr/>";
								}
							 }
							 else
							 {
								echo "No links added yet.<br/>";
							 }
							 
						echo "</table>";
					}
					else
					{
						$nID = (int)$_GET['id'];
						if(core::$sql -> numRows("select * from srcms_downloads where id='$nID'") > 0)
						{
							core::$sql -> exec("delete from srcms_downloads where id='$nID'");
							echo "Link successfully deleted.<br/>";
							misc::redirect('?pg=admin&act=dl', 1);
						}
						else
						{
							echo "You are trying to delete link with ID that was not found in database<br/>";
						}	
					
					}
				break;
				
				case('edit'):
					if(!isset($_POST['submit']) && !isset($_GET['id']))
					{
						echo "<table id='table-3' border='1' cellspacing='0' cellpadding='0'>
								<td>ID</td><td>Name</td><td>Link</td><td>Description</td><td>Edit</td><tr/>";
								
								$hLinks = core::$sql -> exec("select * from srcms_downloads");
								if(mssql_num_rows($hLinks) > 0)
								{
									while($row = mssql_fetch_array($hLinks))
									{
										echo "<td>$row[id]</td><td>$row[name]</td><td>$row[link]</td><td>$row[description]</td><td><a href='?pg=admin&act=dl&subact=edit&id=$row[id]'>Edit</td><tr/>";
									}
								}
								else
								{
									echo "No links added yet.<br/>";
									
									break;
								}

						echo "</table>";
					}
					else
					{
						$nID = (int)$_GET['id'];
						$hLinkData = core::$sql -> exec("select * from srcms_downloads where id='$nID'");
						if(mssql_num_rows($hLinkData) > 0)
						{	
							if(!isset($_POST['link']))
							{
								$hArray = mssql_fetch_array($hLinkData);
								echo "<table id='table-3' border='1' cellspacing='0' cellpadding='0'>
										<form method='post'>
										<td>Name</td><td>Link</td><td>Description</td><tr/>
										<td>$hArray[name]</td><td>$hArray[link]</td><td>$hArray[description]</td><tr/>
										<td><input type='submit' name='submit' value='Save'></td>
										</form>
										</table>
										";
							}
							else
							{
								if(!security::isValidUrl($_POST['link']))
								{
									echo "Invalid URL<br/>";
									misc::back();
								}
								else
								{
									$szName = misc::toHTML($_POST['name']);
									$szDesc = misc::toHTML($_POST['description']);
									core::$sql -> exec("update srcms_downloads set name='$szName',description='$szDesc',link='$_POST[link]' where id='$nID'");
									echo "Link successfully edited<br/>";
									misc::redirect('?pg=admin&act=dl', 1);
								}
							}
						}
						else
						{
							echo "Record with ID you requested was not found in database.<br/>";
							misc::back();
							break;
						}
					}
					
				break;
				default:echo "Unknown subaction"; break;
			}
			echo "<br/>";
			misc::back();
		}
	break;
	
	case('settings'):
		if($_POST['submit'] != 'Save')
		{
			echo "<table id='table-3' border='1' cellpadding='0' cellspacing='0'>
			<form method='post'>
			<td>ValueName</td><td>Value</td><tr/>";
			
			$hSettings = core::$sql -> exec("select * from srcms_settings");
			while($row = mssql_fetch_array($hSettings))
			{
				echo "<td>$row[valueName]</td><td><input type='text' name='$row[valueName]' value='$row[value]'></td><tr/>";
			}
			
			echo "<td></td><td><input type='submit' name='submit' value='Save'></td></form></table>";
		}
		else
		{
			foreach($_POST as $nElement => $nElementValue)
			{
					core::$sql -> exec("update srcms_settings set value='$nElementValue' where valueName='$nElement'");
			}
			
			echo "Settings saved.<br/>";
			misc::redirect('?pg=admin&act=settings', 1);
			
		}
	break;
	
	case('epin'):
		if(!isset($_GET['subact']))
		{
			echo "<a href='?pg=admin&act=epin&subact=gen'><b>Generate new epin code</b></a><br/>
				  <a href='?pg=admin&act=epin&subact=lookup'><b>Lookup codes</b></a>";
		}
		else
		{
			switch($_GET['subact'])
			{
				case('gen'):
					if(!isset($_POST['submit']))
					{
						echo "	<table id='table-3' border='1' cellpadding='0' cellspacing='0'>
									<form method='post'>
									<td>Silk amount</td>
									
									<td><input type='text' name='silkAmount' value='10'></td>
									</table><br/>
									<input type='submit' name='submit' value='Generate new epin'>
								</form>";
					}
					else
					{
						$nRandCode = rand(1000000000,2000000000);
						if(core::$sql -> numRows("select * from srcms_epin where code='$nRandCode'") == 0)
						{
							$nSilk = (int)$_POST['silkAmount'];
							core::$sql -> exec("insert into srcms_epin(code,silkAmount) values('$nRandCode','$nSilk')");
							echo "Epin generated, CODE: <b>$nRandCode</b><br/>";
						}
						else
						{
							echo "Please, re-generate code.<br/>";
							misc::back();
							break;
						}
					}
				break;
				
				case('lookup'):
					if(core::$sql -> numRows("select * from srcms_epin") > 0)
					{
						echo "<table id='table-3' border='1' cellpadding='0' cellspacing='0'>
						<td>Code</td><td>Silk</td><tr/>";
						
						$hCodesData = core::$sql -> exec("select * from srcms_epin");
						
						while($row = mssql_fetch_array($hCodesData))
						{
							echo "<td>$row[code]</td><td>$row[silkAmount]</td><tr/>";
						}
						echo "</table>";
					}
					else
					{
						echo "Please, generate some epin codes first<br/>";
					}
				break;
				
				default:echo "Unknown subaction<br/>";break;
			}
			
			echo "<br/>";
			misc::back();
		}
	break;
	
	
	default:
	echo "Unknown action";
	break;
}
?>