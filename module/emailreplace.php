<?php
		if (isset($_POST['replacemail1'])) {
	
		if(!security::isSecureString($_SESSION['username'], 3)) $errors[] = "Username field contains forbidden symbols";
		if(!security::isSecureString($_POST['email'], 2))	 $errors[] = "Email field contains forbidden symbols";
		if(!security::isCorrectEmail($_POST['email']))		 $errors[] = "Invalid email address";

		if(count($errors) > 0)
		{
			for($i = 0; $i < count($errors); $i++)
			{
				echo $errors[$i].".<br/>";
			}
		}
		else
		{
		
		core::$sql -> changeDB('acc');
		$user = $_SESSION['username'];
		$email = $_POST['email'];
		if (empty($_SESSION['username']) || empty($_POST['email'])) {
			echo 'Error :';
			echo '<br />';
			echo "You left some fields blank! <a href = '?pg=forgot'>go back and try again!</a>";
			unset($_POST['replacemail1']);
		} else {
			$check = core::$sql -> numRows("select Name from TB_User where StrUserID = '$user' and Email = '$email'");
			if ($check !== 1) {
				echo 'Error :';
				echo '<br />';
				echo "User with following email/password doesn't exist! <a href = '?pg=forgot'>go back and try again!</a>";
				unset($_POST['replacemail1']);
			} else {
				$title = "Your Email Change Link!";
				$getrandom = misc::genRandomString();
				$datetime = gmDate('Y-m-d H:i:s');
				$content = "HolySro Email Change Link : http://holysro.com/?pg=cem&uid=$getrandom \n Get inside to change your Email \n if you didnt request it , please ignore this mail.!";
				mail($email, "[HolySro Email Change] ".$title, $content."\nEmail sent from: www.holysro.com");
				core::$sql -> changeDB('acc');
				$ZsCheck = core::$sql -> numRows("select UserID from Email_Change where UserID = '$user'");
				if ($ZsCheck == 1) {
				core::$sql -> exec("update Email_Change set RandomPASS ='$getrandom' ,createtime = '$datetime',ipaddr = '$_SERVER[REMOTE_ADDR]' where UserID = '$user'");
				} else {
				core::$sql -> exec("insert into Email_Change(UserID,RandomPASS,createtime,ipaddr) values('$user','$getrandom','$datetime','$_SERVER[REMOTE_ADDR]')");
				}
				echo "instructions to Email Change sent to your mailbox [ $email ] - please check your mailbox! <br /> In case you haven't received the email from us - check your spam folder! <br /><a href='?pg=index'>Return to main page</a>";
				unset($_POST['replacemail1']);
				misc::redirect("?pg=news", 2);
			}
		}
	} } else {
		echo 'Change your email:';
		echo '<br />';
		echo '<br />';		
		echo '<form action="" method="post">';
		echo 'Your Username:'.$_SESSION['username'];
		echo '<br /><br />';
		echo 'Enter your email:<br />';
		echo '<input  autocomplete="off" onfocus="clearText(this);" style="background: rgba(0,0,0,0.5);width:220px;border-radius: 6px 6px 6px 6px;" type="text" maxlength="32" name="email" placeholder="name@domain.ltd" />';
		echo '<br />';
		echo '<br />';
		echo '<input  class="button" type="submit" name="replacemail1" value="Send Me Link To Mail" />';
		echo '</form>';
	}

?>