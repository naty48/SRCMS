<?php
	if(isset($_GET['uid']))
	{
		if(security::isSecureString($_GET['uid'], 3))
				{
				$uid = $_GET['uid'];
			if(core::$sql -> numRows("select * from Email_Change where RandomPASS = '$uid'") == 0)
			{
				echo 'this UID is incorrect or have been changed , request a new UID.';
			} else {
				if(isset($_POST['submit']))
				{
				//process data
				if(!security::isSecureString($_POST['email_first'], 2)) $errors[] = "Password [new] contains forbidden symbols";
				if(!security::isSecureString($_POST['email_first'], 2)) $errors[] = "Password [new] contains forbidden symbols";
				if(!filter_var($_POST['email_first'], FILTER_VALIDATE_EMAIL)) $errors[] = "Email is invalid.";
				if(strlen($_POST['email_first']) > 54)	$errors[] = "Email too long";
				if(strlen($_POST['email_first']) < 10)	$errors[] = "Email too short";
				if($_POST['email_first'] !== $_POST['email_verify']) $errors[] = "Emails does not match!.";

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
				if(user::ChangeMail($_GET['uid'], $_POST['email_first']))
				{
				echo "Email changed successfully. <br/>";
				misc::redirect('?pg=news', 1);
				}
				else
				{
				echo "Invalid old password specified.<br/>";
				misc::back();
				}
				}
				}
				else core::$ucp -> showChangeEmailForm();
				}
	} } else {
	echo 'Entry is invalid.';
	}
?>