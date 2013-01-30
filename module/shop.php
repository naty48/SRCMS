	<?php
	global $core;
	$servername = $core -> aConfig['serverName'];
	$getsiteurl = $_SERVER['SERVER_NAME'];
	$username = $_SESSION['username'];
	?>
	<table width='100%' height='422' border='1' align='center' cellpadding='0' cellspacing='0'>
	<tbody>
	<form name='_xclick' action='https://www.paypal.com/cgi-bin/webscr' method='post'>
	<input type='hidden' name='cmd' value='_xclick' />
	<input type='hidden' name='business' value='amit91@gmail.com' />
	<input type='hidden' name='currency_code' value='USD' />
	<?php
	echo "<input type='hidden' id='input' name='custom' value='$username' />
	<input type='hidden' name='item_name' value='Online Goods - Virtual Points in [$servername] User : $username '/>
	Hello <font style='color:red;'>$username </font>, Please Choose the Amount of Silks: <br />
	<select style='color:red;background: rgba(0,0,0,4.0);width:250px;' name='amount'>
	<option style='color:red' value='10' name='1 x 500 Silk (USD 10.00)'>500 Silk (USD 10.00)
	<option style='color:red' value='15' name='1 x 750 Silk (USD 15.00)'>750 Silk (USD 15.00)
	<option style='color:red' value='20' name='1 x 1000 Silk (USD 20.00)'>1000 Silk (USD 20.00)
	<option style='color:red' value='25' name='1 x 1250 Silk (USD 25.00)'>1250 Silk (USD 25.00)
	<option style='color:red' value='30' name='1 x 1700 Silk (USD 30.00)'>1700 Silk (USD 30.00)
	<option style='color:red' value='50' name='1 x 3000 Silk (USD 50.00)'>3000 Silk (USD 50.00)
	</select>
	<input type='hidden' name='return' value='http://$getsiteurl/?pg=news'>
	<input type='hidden' name='notify_url' value='http://$getsiteurl/module/ipn.php'>
	<input style='width: 99px;vertical-alignt:midle;border:none;' type='image' src='http://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif' name='submit' alt='PayPal - The safer, easier way to pay online!' />
	</form>
	<br /><br />
	<b>Before you continue with the silks charge you must agree that you wont ChargeBack and also that you read those Terms of Use :</b><br />
	<textarea dir='ltr' style='width:650px;height:500px;color:white;text-align:left;padding-top:5px; padding-left:5px;'  READONLY style='background:transparent; color:white; border:none;'>
	";
	include("terms.txt");
	echo "</textarea></tbody></table>";
	?>