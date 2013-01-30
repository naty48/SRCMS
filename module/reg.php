<?php
global $core;
	if(isset($_GET['ref']))
	{
		if(security::isSecureString($_GET['ref'], 3) && $core -> aConfig['allowRefferals'] == 1)
		{
			$_SESSION['ref'] = $_GET['ref'];
		}
	}
	
	if(isset($_POST['submit']) && $_POST['submit'] != 'login')
	{
		$errors = array();
		if(strlen($_POST['username']) > 16) $errors[] = "Username too long";
		if(strlen($_POST['username']) < 3)	$errors[] = "Username too short";
		if(strlen($_POST['pass1']) > 32)	$errors[] = "Password [1] too long";
		if(strlen($_POST['pass1']) < 6) 	$errors[] = "Password [1] too short";
		if(strlen($_POST['pass2']) > 32)	$errors[] = "Password [2] too long";
		if(strlen($_POST['pass2']) < 6)		$errors[] = "Password [2] too short";
		if(strlen($_POST['email']) > 54)	$errors[] = "Email too long";
		if(strlen($_POST['email']) < 10)	$errors[] = "Email too short";
		
		if(!security::isSecureString($_POST['username'], 3)) $errors[] = "Username field contains forbidden symbols";
		if(!security::isSecureString($_POST['pass1'], 3))	 $errors[] = "Password [1] field contains forbidden symbols";
		if(!security::isSecureString($_POST['pass2'] ,3))	 $errors[] = "Password [2] field contains forbidden symbols";
		if(!security::isSecureString($_POST['email'], 2))	 $errors[] = "Email field contains forbidden symbols";
		if(!security::isCorrectEmail($_POST['email']))		 $errors[] = "Invalid email address";
		if($_POST['pass1'] != $_POST['pass2']) 				 $errors[] = "Password fields dosent match";
		
		
		if(count($errors) > 0)
		{
			for($i = 0; $i < count($errors); $i++)
			{
				echo $errors[$i].".<br/>";
			}
		}
		else
		{
			
			if(user::accountExists($_POST['username']) == 1)
			{
				echo "This username is already taken.";
			}
			else
			{
				core::$sql -> exec("insert into TB_User(StrUserID,password,sec_content,sec_primary, email) values('$_POST[username]','".md5($_POST['pass1'])."','3','3','$_POST[email]')");
				$nJID = user::accountJIDbyUsername($_POST['username']);
				$szAvatarDefault = $core -> aConfig['url']."img/noavatar.png";
				core::$sql -> exec("insert into srcms_userprofiles(JID,gender,skype,msn,avatar,whois, ispublic) values('$nJID','0','None','None','$szAvatarDefault','user','1')");
				core::$sql -> exec("insert into SK_Silk(JID,silk_own,silk_gift,silk_point) values('$nJID','".$core -> aConfig['startSilk']."','0','0')");
				
				if(isset($_SESSION['ref']))
				{
					if($_SESSION['ref'] == $_SESSION['username'])
					{
						echo "<br/>You can't be refferer for your own account (but account created).<br/>";
						return;
					}
					$reffererJID = user::accountJIDbyUsername($_SESSION['ref']);
					if($reffererJID > 0)
					{
						$nRefIPs = core::$sql -> numRows("select * from srcms_refferals where IP='".$_SERVER[REMOTE_ADDR]."'");
						if($nRefIPs < $core -> aConfig['maxRefAccIP'])
						{
							$datetime = gmDate('Y-m-d H:i:s');
							core::$sql -> exec("insert into srcms_refferals(reffererJID,invitedUserJID,time,ip) values('$reffererJID','$nJID','$datetime','$_SERVER[REMOTE_ADDR]')");
							unset($_SESSION['ref']);
						}
					}
				}
				$sName = $core -> aConfig['serverName'];
				mail($_POST['email'],"Thanks for registering at $sName","Thanks for registering at $sName, we really hope you will have a great fun playing here.","From:noreply $sName");
				echo "Account successfully registered.";
				misc::redirect("?pg=news", 2);
			}
		}
	}
	else
	{
		//todo:add ajax validator??
		echo "
				<table id='table-3' border='0' cellpadding='0' cellspacing='0'>
				<form method='post'>
					<td align='center'>Username</td><td align='center'><input type='text' name='username' autocomplete='off' maxlength='16'></td><td align='center'>Your account username</td><tr/>
					<td align='center'>Password</td><td align='center'><input type='password' name='pass1' autocomplete='off' maxlength='32'></td><td align='center'>Your password</td><tr/>
					<td align='center'>Password</td><td align='center'><input type='password' name='pass2' autocomplete='off' maxlength='32'></td><td align='center'>Your password again</td><tr/>
					<td align='center'>Email</td><td align='center'><input type='text' name='email' autocomplete='off' maxlength='54'></td><td align='center'>Valid email address</td><tr/>
					
					<td></td><td align='center'><input type='submit' name='submit' value='Register'></td><td></td>
				</form>
				</table>
			 ";
	}
?>