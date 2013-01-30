<?php
if(isset($_GET['username']) && security::isSecureString($_GET['username'], 3))
{
	$userData = core::$sql -> fetchArray("select * from srcms_userprofiles where JID='".user::accountJIDbyUsername($_GET['username'])."'");
	
	if($userData['ispublic'] == '1')
	{
		user::viewProfile($_GET['username']); //send msg there too
	}
	
	else 
	{
		user::viewProfile($_GET['username']); //send msg there too
	}

}
else echo "No username specified, or username contains forbidden symbols.<br/>";

?>