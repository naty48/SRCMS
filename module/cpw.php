<?php
	if(isset($_GET['uid']))
	{
		if(security::isSecureString($_GET['uid'], 3))
				{
				$uid = $_GET['uid'];
			if(core::$sql -> numRows("select * from PW_Restore where RandomPASS = '$uid'") == 0)
			{
				echo 'this UID is incorrect or have been changed , request a new UID.';
			} else {
				if(isset($_POST['submit']))
				{
				//process data
				if(!security::isSecureString($_POST['password_new'], 3)) $errors[] = "Password [new] contains forbidden symbols";
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
				if(user::RestorePass($_GET['uid'], $_POST['password_new']))
				{
				echo "Password changed successfully. <br/>";
				misc::redirect('?pg=news', 1);
				}
				else
				{
				echo "Invalid old password specified.<br/>";
				misc::back();
				}
				}
				}
				else core::$ucp -> ForgotpwForm();
				}
	} } else {
	echo 'Entry is invalid.';
	}
?>