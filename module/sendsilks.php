<?php
function check_pers ($per,$total){
  //  $num = ceil($snum);
    $total = ceil($total);
    $num = ceil($total * $per) / 100 ;
    return ($num);
} 
	if (isset($_POST['sendsilk'])) {

	//	$user = $sec->secure($_POST['username']);
		$email = security::secure($_POST['name']);
		$amont = security::secure($_POST['amont']);
		//$check = core::$sql -> exec("SELECT * FROM TB_User WHERE StrUserID = '".$user."'");
		if (empty($_POST['amont']) || (empty($_POST['name']) )) {
			echo 'Error:';
			echo '<br />';
			echo "You left some fields blank! <a href = '?pg=forgot'>go back and try again!</a>";
			unset($_POST['sendsilk']);
					}


			if(user::accountExists($email) == 1) {
						echo '
						Error
						<br />
						<form class="TSro" method="POST">
						<label><span style="color: red;font-weight:bold;font-size: medium;"> character name !!! </span></label>
						';
					} else {
						$IDs = user::accountJIDbyUsername($_SESSION['username']);
						$silk_own = user::getSilkByUsername($IDs);
			if (($_POST['amont']) > $silk_own) {
					echo 'Error :';
					echo '<br />';
					echo "You do not have $amont silk";
					} else {
			if (($_POST['amont']) < 10) {
			
					echo '
		   Error
		   <br />
		    <form  class="TSro" method="POST">
			<label><span style="color: red;font-weight:bold;font-size: medium;"> You can`t send less than 10 silk !!! </span></label>
			';
					} else {
					if (($_POST['name']) == ($_SESSION['username'])) {
			
										echo '
		    <div class="top">Error</div>
			<br />
		    <form   class="TSro" method="POST">
			<label>You can`t send to <span style="color: red;font-weight:bold;font-size: medium;"> '.($_SESSION['username']).'</span></label>
			';
					} else {
					function check_($val)
						{
						if( $val % 2 == 0 )
							{
						return false;
						}
						else
						{
						return true;
						}
					}
					if (check_($amont)) {
			
						echo '<div id="content">
		    <div class="top">Error</div>
		    <div class="content">
		    <div id="content" class="content-inner">
		    <form   class="TSro" method="POST">
			<div class="reg">
			<label><span style="color: red;font-weight:bold;font-size: medium;">$amont is an odd number ... No single number can be written so that a discount of 10% of the figure</span></label>
			</div>
			</div>
			</div>
			<div class="bottom"></div>
			</div>
			</div>
			';
					} else {
					$per = $amont;
						$num = 10;
						$nsba100 = check_pers($per,$num); 
						$checkID = user::accountJIDbyUsername($email);
						$silk_own = user::getSilkByUsername($IDs);
						 while ($row = mssql_fetch_array($checkID)) {
						$IDs = $row['JID'];
						}
						$checkID = user::accountJIDbyUsername($_SESSION['username']);
						 while ($row = mssql_fetch_array($checkID)) {
						$IIDs = $row['JID'];
						core::$sql -> exec("update SK_Silk set silk_own = silk_own - '".$amont."' WHERE JID = '".$IIDs."'");
						core::$sql -> exec("update SK_Silk set silk_own = silk_own + '".$amont."' - '".$nsba100."' WHERE JID = '".$IDs."'");
						}
							echo '
							sent successfully !
							<br />
							<form  class="TSro" method="POST">
							<br />
							<label><span style="color: green;font-weight:bold;font-size: medium;">'.$amont.' silks sent successfully</span></label>
							';
					}
				}
			}
		}
	}
}
	 else {
		//}	
	//	$user = $sec->secure($_POST['username']);
		//$check = core::$sql -> exec("SELECT * FROM TB_User WHERE StrUserID = '".$user."'");
						$checkID = core::$sql -> exec("SELECT * FROM TB_User WHERE StrUserID = '".($_SESSION['username'])."'");
						 while ($row = mssql_fetch_array($checkID)) {
						$IDs = $row['JID'];
						}
						$silk_own = user::getSilkByUsername($IDs);
		echo '
			Send Silk system .
			<form class="TSro" name="registerform" id="formID" method="post" autocomplete="off" >
						<span style="color: #FF0000;font-weight:bold; font-size:  15px; ">	10%</span> <span style="font-weight:bold; font-size:  15px; ">will be deducted from the Silk sender</span><br /><br />

											<table width="70%" style="text-align:center;align:center;margin-left:15%;">
				<tr>
					<td >		<br /><p style="font-size : 13px;"> Enter your Silk :</p></td>
					<td >		<br /><p style="font-size : 13px;"><input  type="text" maxlength="16" style="border-radius: 6px 6px 6px 6px;"  name="amont" value=""  /></p></td>
				</tr>
				<tr>
					<td >		<br /><p style="font-size : 13px;"> Send To (Username) :</p></td>
					<td >		<br /><p style="font-size : 13px;"><input type="text" maxlength="16" style="border-radius: 6px 6px 6px 6px;"  name="name" value=""  /></p></td>
				</tr>
			</table>

		
				<table>
				<td >
				<br/><input style="height:32px;vertical-align:middle;margin-left: 150px;" class="submitButton" type="submit" value="send silk" name="sendsilk" />
				</td>
				</table>
';
}
?>