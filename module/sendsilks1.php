<?php

core::$sql -> changeDB("acc");

function check_pers($per,$total){
//  $num = ceil($snum);
$total = ceil($total);
$num = ceil($total * $per) / 100 ;
return ($num);
} 
if (isset($_POST['sendsilk'])) {

$useridnum = security::secure($_POST['name']);
$amount = security::secure($_POST['amount']);
if (empty($_POST['amount']) || (empty($_POST['name']) )) {
echo 'Error:<br />';
echo "You left some fields blank! <a href = '?pg=forgot'>go back and try again!</a>";
unset($_POST['sendsilk']);
}
core::$sql -> changeDB("acc");
$checkcs = core::$sql -> numRows("SELECT * FROM TB_User WHERE StrUserID = '".$useridnum."'");
if ($checkcs != 1) {
echo '
Error
<br />
<form method="POST">
<label><span style="color: red;font-weight:bold;font-size: medium;"> character name !!! </span></label>
<br />
';
} else {
core::$sql -> changeDB("acc");
$checkID = core::$sql -> exec("SELECT * FROM TB_User WHERE StrUserID = '".($_SESSION['username'])."'");
while ($row = core::$sql -> fetchArray($checkID)) {
$IDs = $row['JID'];
}
core::$sql -> changeDB("acc");
$querys = core::$sql -> exec("select * from SK_Silk where JID = '".$IDs."'"));
while ($row = core::$sql -> fetchArray($querys)) {
$silk_own = $row['silk_own'];
}
if (($_POST['amount']) > $silk_own) {

echo 'Error :';
echo '<br />';
echo "You do not have $amount silk";
} else {
if (($_POST['amount']) < 10) {

echo '
Error
<form method="POST">
<label><span style="color: red;font-weight:bold;font-size: medium;"> You can`t send less than 10 silk !!! </span></label>
';
} else {
if (($_POST['name']) == ($_SESSION['username'])) {

echo '
Error:
<form method="POST">
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
if (check_($amount)) {

echo '
Error
<br />
<form   method="POST">
<label><span style="color: red;font-weight:bold;font-size: medium;">$amount is an odd number ... No single number can be written so that a discount of 10% of the figure</span></label>
';

} else {
core::$sql -> changeDB("acc");
$per = $amount;
$num = 10;
$nsba100 = check_pers($per,$num); 
$checkID = core::$sql -> exec("SELECT * FROM TB_User WHERE StrUserID = '".$useridnum."'");
while ($row = core::$sql -> fetchArray($checkID)) {
$IDs = $row['JID'];
}
core::$sql -> changeDB("acc");
$checkIID = core::$sql -> exec("SELECT * FROM TB_User WHERE StrUserID = '".($_SESSION['username'])."'");
while ($row = core::$sql -> fetchArray($checkIID)) {
$IIDs = $row['JID'];
core::$sql -> changeDB("acc");
core::$sql -> exec("update SK_Silk set silk_own = silk_own - '".$amount."' WHERE JID = '".$IIDs."'");
core::$sql -> exec("update SK_Silk set silk_own = silk_own + '".$amount."' - '".$nsba100."' WHERE JID = '".$IDs."'");
}
echo '
successfully
<form   method="POST">
<label><span style="color: green;font-weight:bold;font-size: medium;">Send '.$amount.' silk successfully</span></label>
';
}
}
}
}
}
}
else {
core::$sql -> changeDB("acc");
$checkID = core::$sql -> exec("SELECT * FROM TB_User WHERE StrUserID = '".($_SESSION['username'])."'");
while ($row = core::$sql -> fetchArray($checkID)) {
$IDs = $row['JID'];
}
core::$sql -> changeDB("acc");
$querys = core::$sql -> exec("select * from SK_Silk where JID = '".$IDs."'"));
while ($row = core::$sql -> fetchArray($querys)) {
$silk_own = $row['silk_own'];
}
echo '
Send Silk System
<form name="registerform" id="formID" method="post" autocomplete="off" >

<span style="color: #FF0000;font-weight:bold; font-size:  15px; ">	10%</span> <span style="font-weight:bold; font-size:  15px; ">will be deducted from the Silk sender</span><br /><br />
<table width="70%" style="text-align:center;align:center;margin-left:15%;">
<tr>
<td ><br /><p style="font-size : 13px;"> Enter your Silk :</p></td>
<td ><br /><p style="font-size : 13px;"><input class="validate[required,custom[onlyNumberSp],minSize[0],maxSize[14],ajax[ajaxUserCallPhpSilk]] textboxt" type="text" maxlength="16" style="border-radius: 6px 6px 6px 6px;"  name="amount" value=""  /></p></td>
</tr>
<tr>
<td ><br /><p style="font-size : 13px;"> Send To (Username) :</p></td>
<td ><br /><p style="font-size : 13px;"><input type="text" maxlength="16" style="border-radius: 6px 6px 6px 6px;"  name="name" value="" class="validate[required,minSize[4],maxSize[16]] textboxt"  /></p></td>
</tr>
</table>


<table>			<td ><br/>	<input style="height:32px;vertical-align:middle;margin-left: 150px;" class="submitButton" type="submit" value="send silk" name="sendsilk" /></td>
</table>

</form>			

';
}
?>