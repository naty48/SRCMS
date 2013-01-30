<?php
/*error_reporting(0);*/
require_once('config.inc.php');
require_once('core/core.inc.php');
global $core;
$core = new core();
core::$sql-> changeDB('acc');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $core -> aConfig['webTitle']; ?></title>
	<link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="buttons/buttons.css" />
	<link rel="stylesheet" href="css/style_002.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="style.css">
	<!--[if IE]>
	<link rel="stylesheet" href="css/iefix.css">
	<![endif]-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/password_strength_plugin.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript" src="js/slideshow.js"></script>
	<script type="text/javascript" src="js/snowstorm.js"></script>
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="keywords" content="silkroad,online,server,private,stable,low,rate,psro,sro,perfect,great,holysro,100">
	<meta charset="utf-8">
</head>

<body> 
	<script type="text/javascript" src="js/tooltip.js"></script>
<div id="fb-root"></div>
<script>
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>

<div class="header">
	<div class="navtop">
    		<div class="navtop_content">
				<!-- Left Side Menu Start -->
				<div class="navtop_left">
				
				<div class="navtop_name">
				<p class="namelink">Index</p>
				<p class="description news">News</p>
				</div>
				
				<div class="navtop_name">
					<?php if(!isset($_SESSION['username']))
					{
					echo "
					<p class='namelink'>Registration</p>
					<p class='description introduction'>Login For Donation Menu</p>
					";
					} else {
					echo "
					<p class='namelink'>Donate</p>
					<p class='description infomation'>Buy Silks</p>
					";
					}
					?>
				</div>
				
				<div class="navtop_name">
				<p class="namelink">Ranking</p>
				<p class="description library">Users Ranks</p>
				</div>
				
				</div>
				<!-- Left Side Menu End -->
				<a href="index.php"><img width="217px" src="logo.png" border="none"></a>
				<!-- Right Side Menu Start -->
				<div class="navtop_right">
				
				<div class="navtop_name">
				<p class="namelink">Forum</p>
				<p class="description club">Community</p>
				</div>
				
				<div class="navtop_name">
				<p class="namelink">Guides *Soon*</p>
				<p class="description infomation">Info About Sro</p>
				</div>
				
				<div class="navtop_name">
				<p class="namelink">Downloads</p>
				<p class="description quest">Version [CHANGE HERE]</p>
				</div>
				
				</div>
				<!-- Right Side Menu End -->
				
				<!-- Sub Menus Start -->
            	<div class="navtop_menu">
				<div class="navtop_menu_left">
					<div class="navtop_menu_col">
					<ul class="menulist">
					<li><a href="./?pg=news">News</a></li>
					</ul>
					</div>
					
					<div class="navtop_menu_col">
					<ul class="menulist">
					<?php if(!isset($_SESSION['username']))
					{
					echo "
					<li><a href='./?pg=reg'>Registration</a></li>";
					} else {
					echo "
					<li><a href='./?pg=shop'>Paypal</a></li>
					";
					}
					?>
					</ul>
					</div>
					
					<div class="navtop_menu_col">
					<ul class="menulist">
		<li><a href='?pg=rank&type=char'><span>Top Characters</span></a></li>
		<li><a href='?pg=rank&type=set_plus'><span>Best Item</span></a></li>
		<li><a href='?pg=rank&type=guild'><span>Top Guilds</span></a></li>
		<li><a href='?pg=rank&type=unique'><span>Unique Kills</span></a></li>
		<li><a href='?pg=rank&type=honor'><span>Honor Rank</span></a></li>
		 <li><a href='?pg=rank&type=job'><span>Job Rankings</span></a>
		<li><a href='?pg=rank&type=search_char'><span>Search Player</span></a></li>
		<li><a href='?pg=rank&type=search_guild'><span>Search Guild</span></a></li>
					</ul>
					</div>
				</div>
			<!-- /.navtop_menu_left -->      
			<div class="navtop_menu_right">
				<div class="navtop_menu_col">
					<ul class="menulist">
						<li><a href="./?pg=dl">Client Download</a></li>
						<li><a href="./?pg=drivers">GPU Drivers</a></li>
					</ul>
				</div>
				<div class="navtop_menu_col">
					<ul class="menulist">
						<li><a href="#">Starters</a></li>
						<li><a href="#">Uniques</a></li>
						<li><a href="#">Quests</a></li>
						<li><a href="#">Item Mall</a></li>
					</ul>
				</div>
				<div class="navtop_menu_col">
					<ul class="menulist">
						<li><a href="#">Forum</a></li>
						<li><a href="#">Support System</a></li>
					</ul>								
				</div>
			</div><!-- /.navtop_menu_right -->
		</div><!-- /.navtop_menu -->
		</div><!-- /.navtop_content -->
	</div><!-- /.navtop -->
</div><!-- /.header -->
<div id="sct_menu">
	<div class='clear'></div>		
	</div>

	<div id="wrapper"> 
	<div id="leftcontent">
	<div class="main_view"> 
	<div class="window"> 
	<div style="width: 2680px;" class="image_reel">
	<!-- Image Rotator : Start | If you want to add more images or remove , edit following lines -->
	<a href="http://holysro.com/" target="_blank"><img src="rotator/banner-1.png" alt="" border="none"></a>
	<a href="http://holysro.com/" target="_blank"><img src="rotator/banner-2.png" alt="" border="none"></a>
	<a href="http://holysro.com/" target="_blank"><img src="rotator/banner-3.png" alt="" border="none"></a>
	<a href="http://holysro.com/" target="_blank"><img src="rotator/banner-4.png" alt="" border="none"></a> 
	</div> </div> <div style="display: block;" class="paging"> 
	<a class="active" href="#" rel="1">1</a>
	<a href="#" rel="2">2</a>
	<a href="#" rel="3">3</a>
	<a href="#" rel="4">4</a>
	<!-- Image Rotator : END -->
	</div></div>

	<div class="box_two">
	<div class="box_two_title">Welcome to HolySro</div>
	<?php $core -> showMainContent(); ?>
	</div> 
	
	<div id="footer">
<p align="center"><font>Coded by Chernobyl &amp; Naty48 | Designed by Naty48.</font><br>
<font>&reg;2012 HolySro All Rights Reserved</font>

	</div>
	
	</div> 
	<div id="rightcontent"> 

	<div class="box_one"> 
	<div class="box_one_title">Account Management</div> 
	<form action="/" method="post">
	<?php if(isset($_SESSION['username']))
	{
	$szAvatarUrl = user::getUserAvatarUrl($_SESSION['username']);
	echo "<center><img src='$szAvatarUrl'></img>
	<br />Hello <font color='red'> ".$_SESSION['username']." </font>-  You have [<font color='red'><b>".user::getSilkByUsername($_SESSION['username'])."</b></font>] silk.<br/>";
	echo "
<table id='table-3' border='0' cellpadding='0' cellspacing='0'>
		<tr>
		";
	if($core -> aConfig['allowChangePw'] == 1) echo "<td><a href='./?pg=ucp&act=changepw'>Change password</a></td>";
	if($core -> aConfig['allowListChars'] == 1) echo "<td><a href='./?pg=ucp&act=mychars'>My characters</a></td>";
		echo "
		</tr>
		<tr>
		";
	if($core -> aConfig['allowMyProfile'] == 1) echo "<td><a href='./?pg=ucp&act=myprofile'>My profile</a></td>";
	if($core -> aConfig['allowRefferals'] == 1) echo "<td><a href='./?pg=ucp&act=refferals'>My refferals</a></td>";
		echo "
		</tr>
		<tr>
		";
	if($core -> aConfig['allowEpinSystem'] == 1) echo "<td><a href='./?pg=ucp&act=epin'>Use EPIN</a><br></td>";
												echo "<td><a href='./?pg=emailreplace'>Change Email</a></td>";
	if($core -> aConfig['allowMailbox'] == 1) 
	{
	echo "<a href='./?pg=ucp&act=mailbox'>Mailbox ";
	$myJID = user::accountJIDbyUsername($_SESSION['username']);

	$nMsgCountUnread =core::$sql -> numRows("select * from srcms_privatemessages where receiver='$myJID' and viewed='0'");
	$nMsgCountRead = core::$sql -> numRows("select * from srcms_privatemessages where receiver='$myJID' and viewed='1'");
	$nMsgCount = core::$sql -> numRows("select * from srcms_privatemessages where receiver='$myJID'");

	$msgText = "";

	if($nMsgCountUnread > 0)
	{
	$msgText = "[<b>$nMsgCount / ".$core -> aConfig['maxPrivMsg']."]</a></b>";
	}
	else $msgText = "[$nMsgCount / ".$core -> aConfig['maxPrivMsg']."]</a>";
	}

	echo "$msgText
	<br />	<td><a href='./?pg=ucp&act=logout'>Logout</a></td>
		</tr>
</table>
	";
	}
	else
	{
	if($_POST['submit'] != 'login')
	{
	ucp :: showLoginForm();
	}
	else
	{
	//process login

	if(security::isSecureString($_POST['username'], 3) == false) $errors[] = "Username contains forbidden symbols";
	if(security::isSecureString($_POST['password'], 3) == false) $errors[] = "Password contains forbidden symbols";
	if(strlen($_POST['username']) > 16) $errors[] = "Username too long";
	if(strlen($_POST['username']) < 3)	$errors[] = "Username too short";
	if(strlen($_POST['password']) > 32) $errors[] = "Password too long";
	if(strlen($_POST['password']) < 6)	$errors[] = "Password too short";


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
	if(user::login($_POST['username'], $_POST['password']))
	{
	echo "<b>Successfully logged in.</b><br/>";
	misc::redirect("?pg=news", 1);
	}
	else
	{
	echo "Invalid username, or password.<br/>";
	misc::back();
	}
	}
	}

	}
	?>
	</div> 

	
	
	<?php 
	if(user::isAdmin($_SESSION['username']))
	{
	echo "	  
	<div class='box_one'>
	<div class='box_one_title'>Admin panel</div>
	<a href='?pg=admin&act=news'>Edit/add/remove news</a><br />
	<a href='?pg=admin&act=dl'>Edit/add/remove downloads</a><br />
	<a href='?pg=admin&act=settings'>Edit settings</a><br />
	<a href='?pg=admin&act=epin'>Epin system</a><br />
	</div>";
	}
	?>
	
	



	<div class="box_one">
	<div class="box_one_title">Fortress Status:</div>
	<table width="100%"><tbody><tr><td><span class="yellow_text">
	<?php
		core::$sql -> changeDB("shard");
		$hJanganData = core::$sql -> fetchArray("select * from _SiegeFortress where FortressID='1'");
		$hBanditData = core::$sql -> fetchArray("select * from _SiegeFortress where FortressID='3'");
		$hHotanData = core::$sql -> fetchArray("select * from _SiegeFortress where FortressID='6'");

		if($hJanganData['Introduction'] == null) $hJanganData['Introduction'] = "<b>Nothing</b>";
		if($hBanditData['Introduction'] == null) $hBanditData['Introduction'] = "<b>Nothing</b>";
		if($hHotanData['Introduction'] == null) $hHotanData['Introduction'] = "<b>Nothing</b>";

		if($hJanganData['TaxRatio'] == null) $hJanganData['TaxRatio'] = "Nothing";
		if($hBanditData['TaxRatio'] == null) $hBanditData['TaxRatio'] = "Nothing";
		if($hHotanData['TaxRatio'] == null) $hHotanData['TaxRatio'] = "Nothing";

		echo "	<table id='table-3' border='0'>

		<tr>
		<td align='center'></td>
		<td align='center' style='font-weight:bold;font-size:11px'>Fortress</td>
		<td align='center' style='font-weight:bold;font-size:11px'>Guild</td>
		<td align='center' style='font-weight:bold;font-size:11px'>Tax</td>
		</tr>

		<tr>
		<td align='center'><img src='img/fort-jangan.png' /></td>
		<td align='center' style='font-size:11px'>Jangan: </td>
		<td align='center' style='font-size:11px'>".guild::guildNameByID($hJanganData['GuildID'])."</td>
		<td align='center' style='font-weight:bold;font-size:11px'>Tax: $hJanganData[TaxRatio]%</td>
		</tr>

		<tr>
		<td align='center'><img src='img/fort-hotan.png' /></td>
		<td align='center' style='font-size:11px'>Hotan: </td>
		<td align='center' style='font-size:11px'>".guild::guildNameByID($hHotanData['GuildID'])."</td>
		<td align='center' style='font-weight:bold;font-size:11px'>Tax: $hHotanData[TaxRatio]%</td>
		</tr>

		<tr>
		<td align='center'><img src='img/fort-bandit.png' /></td>
		<td align='center' style='font-size:11px'>Bandit: </td>
		<td align='center' style='font-size:11px'>".guild::guildNameByID($hBanditData['GuildID'])."</td>
		<td align='center' style='font-weight:bold;font-size:11px'>Tax: $hBanditData[TaxRatio]%</td>
		</tr>
		</table>";
	?>
	</span></td></tr></tbody></table></div>


		<!-- Server Info Box Start -->
		<script type="text/javascript">
		$(".xpand").click(
		function () {
		$(this).next().slideToggle(200);
		});

		</script>
		
		<script type="text/javascript"> 
		//<!-- 
		$(document).ready(function() {$(".w2bslikebox").hover(function() {$(this).stop().animate({right: "0"}, "medium");}, function() {$(this).stop().animate({right: "-250"}, "medium");}, 500);}); 
		//--> 
		</script> 
		<style type="text/css"> 
		.w2bslikebox{background: url("img/serverstatsbutton.png") no-repeat scroll left center transparent !important;display: block;float: right;height: 270px;padding: 0 5px 0 46px;width: 245px;z-index: 99999;position:fixed;right:-250px;top:20%;} 
		.w2bslikebox div{border:none;position:relative;display:block;} 
		.w2bslikebox span{bottom: 12px;font: 8px "lucida grande",tahoma,verdana,arial,sans-serif;position: absolute;right: 6px;text-align: right;z-index: 99999;} 
		.w2bslikebox span a{color: #808080;text-decoration:none;} 
		.w2bslikebox span a:hover{text-decoration:underline;} 
		</style>

		
		<div class="w2bslikebox" style="">
		<div class="box_one">
		<div class="box_one_title">Server info:</div>
		<?php require_once('module/stats_menu.php'); ?>		
		</div>
		</div> 
		
		<!-- Server Info Box End -->


		<!-- FaceBook Box Start -->
		
		<div class="box_one">
		<div class="box_one_title">Facebook</div>
<div class="fb-like-box" data-href="http://www.facebook.com/platform" data-width="281" data-show-faces="true" data-colorscheme="dark" data-stream="false" data-header="false"></div>
		</div> 

		<!-- FaceBook Box End -->
		
		<!-- Google Ads Box Start -->
		
		<div class="box_one">
		<div class="box_one_title">Advertisement</div>
		<a href="http://ebx7.net/" target="_blank"><img src="img/ebx7_banner.png" /></a>
		</div> 
		
		<!-- Google Ads Box End -->


	</div> 
	</div> 
	</body>
	</html>