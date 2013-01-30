<?php
class ucp
{
	public static function showLoginForm()
	{
	echo "
	<table border='0' cellpadding='0' cellspacing='0'>
	<form method='post'>
	<input placeholder='Username' name='username' autocomplete='off' class='login_input' type='text'><br> 
	<input placeholder='Password' name='password' autocomplete='off' class='login_input' style='margin-top: -1px;' type='password'><br> 
	<input value='login' name='submit' class='loginbtnwidth' style='width:100%;margin-top: 4px;' type='submit'><br> 
	</form>
	</table>
	<br />
	<center>
	<a href='?pg=reg' class='button small green'>&nbsp;&nbsp;Register Now !&nbsp;&nbsp;</a>
	<a href='?pg=forgotpw' class='button small gray'>Forgot Password ?</a>
	</center>		
	";
	}
	
	public function showSendWebMsgForm()
	{
	echo "
	<br/><b>Send private message</b><br/>
	<form method='post'>
	<input type='text' name='recvName' value='Receiver username'><br/>
	<input type='text' name='msgTitle' value='Message title'><br/>";

	echo "
	<textarea id='privMsgSendTextBox' name='msgText' rows='2' cols='100'>Type your message here</textarea><br/>
	<input type='submit' name='submit' value='Send'>
	</form>
	<script>CKEDITOR.replace( 'msgText' );</script>
	";
	}
	
	public function showChangepwForm()
	{
	echo "
	<table id='table-3' border='0' cellpadding='0' cellspacing='0'>
	<form method='post'>
	<td>Old password</td><td><input type='password' autocomplete='off' name='password_old' maxlength='32'></td><tr/>
	<td>New password</td><td><input type='password' autocomplete='off' name='password_new' maxlength='32'></td><tr/>
	<td>Confirm New password</td><td><input type='password' name='password_new_confirm' maxlength='32'></td><tr/>
	<td></td><td><input type='submit' name='submit' value='Change'></td>
	</form>
	</table>
	";
	}
	
	public function showChangeEmailForm()
	{
	echo "
	<table id='table-3' border='0' cellpadding='0' cellspacing='0'>
	<form method='post'>
	<td>New Email</td><td><input type='text' autocomplete='off' name='email_first' maxlength='32'></td><tr/>
	<td>New Email Verify</td><td><input type='text' autocomplete='off' name='email_verify' maxlength='32'></td><tr/>
	<td></td><td><input type='submit' name='submit' value='Change'></td>
	</form>
	</table>
	";
	}
	
	
	public function ForgotpwForm()
	{
	echo "
	<table id='table-3' border='0' cellpadding='0' cellspacing='0'>
	<form method='post'>
	<td>New password</td><td><input type='password' name='password_new' maxlength='32'></td><tr/>
	<td>Confirm New password</td><td><input type='password' name='password_new_confirm' maxlength='32'></td><tr/>
	<td></td><td><input type='submit' name='submit' value='Change'></td>
	</form>
	</table>
	";
	}
	
	
	public function showProfileForm($szUsername)
	{
	$userData = core::$sql -> fetchArray("select * from srcms_userprofiles where JID='".user::accountJIDbyUsername($szUsername)."'");

	$genderSelector = null;
	$publicProfileSelector = null;
	if($userData['gender'] == '0') 
	{
	$genderSelector="<option value='0' selected>Male</option>
	<option value='1'>Female</option>";
	}
	else
	{
	$genderSelector="<option value='0'>Male</option>
	<option value='1' selected>Female</option>";
	}

	if($userData['ispublic'] == '1')
	{
	$publicProfileSelector = "<option value='1' selected>Yes</option>
	<option value='0'>No</option>";
	}
	else
	{
	$publicProfileSelector = "<option value='1'>Yes</option>
	<option value='0' selected>No</option>";
	}

	echo "
	<table id='table-3' border='0' cellpadding='0' cellspacing='0'>
	<form method='post'>
	<td>Username</td><td>$_SESSION[username]</td><tr/>
	<td>Gender</td>
	<td>
	<select name='gender'>
	$genderSelector
	</select>
	</td>
	<tr/>
	<td>Avatar url</td><td><input type='text' name='avatar' value='$userData[avatar]'></td><tr/>
	<td>Avatar</td><td><img src='$userData[avatar]'></img></td><tr/>
	<td>Skype</td><td><input type='text' name='skype' value='$userData[skype]'></td><tr/>
	<td>MSN</td><td><input type='text' name='msn' value='$userData[msn]'></td><tr/>
	<td>Show profile to public</td><td>
	<select name='ispublic'>
	$publicProfileSelector
	</select>
	</td>
	";
	if($core -> aConfig['allowRefferals'] == 1)
	{
	echo "<tr/><td>Refferal link</td><td>
	<a href='".$core -> aConfig['url']."?pg=reg&ref=$_SESSION[username]'>".$core -> aConfig['url']."?pg=reg&ref=$_SESSION[username]</a></td>";
	}
	echo "
	<tr/>
	<td></td><td><input type='submit' name='submit' value='Save'></td>

	</form>

	</table>
	";
	}
}
?>
