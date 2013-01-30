<?php
/* 
 * srcms news module [YOU SHALL NOT PASSSS!!!!1]
 * By Chernobyl
 */
 
 
global $core;
if(isset($_GET['del']))
{
	$nCommentID = (int)$_GET['del'];
	$isAdmin = core::$sql -> getRow("select whois from srcms_userprofiles where JID='".user::accountJIDbyUsername($_SESSION['username'])."'");
	
	if(core::$sql -> numRows("select * from srcms_newscomments where id='$nCommentID' and author='$_SESSION[username]'") > 0 || $isAdmin == "admin")
	{
		core::$sql -> exec ("delete from srcms_newscomments where id='$nCommentID'");
		misc::redirect("?pg=news&comment=$_GET[backid]",0);
	}
	else echo "<br/><br/>You can't delete comment that does not belong to you.";
}


if(!isset($_GET['comment']))
{
	$hQuery = core::$sql -> exec("select * from srcms_news order by id desc");
	echo "
		<div id='m-midd'>
		<div id='pn_title'>Home > News</div>
	";
	while($row = mssql_fetch_array($hQuery))
	{	
		$nComments = core::$sql -> numRows("select * from srcms_newscomments where newsID='$row[id]'");
		$szAvatarUrl = user::getUserAvatarUrl($row['author']);
		$dateee = gmDate('Y-m-d H:i:s');
		$nComments = core::$sql -> getRow("select count(*) from srcms_newscomments where newsID='$row[id]'");
		$userRank = core::$sql->getRow("select whois from srcms_userprofiles where JID='".user::accountJIDbyUsername($row['author'])."'");
		$szUserRank = user::getRankText($userRank);
		$row['content'] =  security::fromHTML($row['content']);
		$row['content'] =  misc::applyAttributesToText($row['content']);
		$datetime = strtotime($row['time']);
		$mssqldate = date("d-m-y", $datetime);
		
		echo "
<div id='post_home'>
<div class='post_title'>
";
if(strtotime($row['time']) > strtotime('last week')) {
echo "<a href='#' class='cat'>New</a>";
} else {
echo "<a href='#' class='cat'></a>";
}
echo "
<a href='?pg=news&comment=$row[id]' class='news_title'>$row[title]</a>
<span class='date'>posted by  <a href='?pg=viewprofile&username=$row[author]'>$row[author]</a>  at $mssqldate  | <a href='?pg=news&comment=$row[id]' class='news_title'>Comments ($nComments)</a> </span>
</div>
<a href='#expand' class='xpand'><img src='images/expand.png'></a>
<div class='post_content'>$row[content]<br /><br /></div>
</div>
			";
	}
		echo "
</div>
	";
}
else
{
	$nID = (int)$_GET['comment']; //cast to int >:)
	if(!isset($_GET['page'])) $_GET['page'] = 1;

	if(core::$sql -> numRows("select * from srcms_news where id='$nID'") > 0)
	{
		$getTitle = core::$sql -> getRow("select title from srcms_news where id='$nID'");
		echo "
		<table id='table-3' border='0' cellpadding='0' cellspacing='0'>
		<tbody><tr><td valign='top' style='padding-left:15px'>
		Commenting news : $getTitle
		</td>
		</tr></table>
		";
		
		
		
		
		
		$hGetComments = core::$sql -> exec("select * from srcms_newscomments where newsID='$nID'");
		
		$commentData = array();
		$a = 0;
		
		while($row = mssql_fetch_array($hGetComments))
		{
			$commentData[$a] = array($row['id'],$row['text'],$row['author'],$row['time']);
			$a++;
		}
		$_GET['page'] = (int)$_GET['page'];
		if(!isset($_GET['page'])) $_GET['page'] = 1;
			for($i = (($_GET['page'] - 1) * 10); $i < (($_GET['page']) * 10);$i++)
			{
				$nCommentID = $commentData[$i][0];
				$szText = $commentData[$i][1];
				$szText = misc::applyAttributesToText($szText);
				$szText = security::fromHTML($szText);
				$szAuthor = $commentData[$i][2];
				$szTime = $commentData[$i][3];
				if(strlen($szText) == 0) break;
				$szAvatar = user::getUserAvatarUrl($szAuthor);
				
				$commentPanelLinks = "";
				
				$isCommentOwner = core::$sql->getRow("select whois from srcms_userprofiles where JID='".user::accountJIDbyUsername($szAuthor)."'");
				
				$isAdmin = core::$sql -> getRow("select whois from srcms_userprofiles where JID='".user::accountJIDbyUsername($_SESSION['username'])."'");
				
				if($szAuthor == $_SESSION['username'] || $isAdmin == "admin")
				{
					$commentPanelLinks = "<a href='?pg=news&del=$nCommentID&backid=$nID'><b>Delete</b></a>";
				}
				
				
				$szRank = user::getRankText($isCommentOwner);
			
				echo "
				
				
				
				<img src='$szAvatar'></img>
				<table id='table-3' border='0' cellpadding='0' cellspacing='0'>
				<td valign='top' style='padding-left:15px' width='700'>
				<br/>posted by  <a href='?pg=viewprofile&username=$szAuthor'>[ $szRank ] $szAuthor </a>  at $szTime </a>
				<hr>
				<br/>$szText<br/>
				<hr>
				<font align='right'>$commentPanelLinks</font>
				</td>
				</table>
				<hr/>
				";
			}
		

		if($_GET['page'] != 1) echo "<a href='?pg=news&comment=$nID&page=".($_GET['page'] - 1)."'><</a>";
		
		$nPages = 0;
		for($a = 1,$i = 0; $i < count($commentData); $i++)
		{
		if($i % 10 == 0)
		{
			echo "<a href='?pg=news&comment=$nID&page=$a'>$a</a>&nbsp;";
			$a++;
			$nPages++;
		}
		}
		
		if($_GET['page'] < $nPages) echo "<a class='pageblue' href='?pg=news&comment=$nID&page=".($_GET['page'] + 1)."'>></a>";
		
		if(isset($_SESSION['username']))
		{
			if(!isset($_POST['submit']))
			{
				echo " 
					<br/>
						<form method='post'>
							<textarea id = 'commentTextBox' name='commentText' rows='5' cols='100'>Type your message here</textarea><br/>
							<input type='submit' name='submit' value='Submit'>
						</form>
				<script>CKEDITOR.replace( 'commentText' );</script>
					 ";
			}
			else
			{

				//$cleanText = misc::applyAttributesToText($_POST['commentText']);
				$cleanText = stripslashes(security::toHTML($_POST['commentText']));
				
				if(strlen($cleanText) < $core -> aConfig['minNewsCommentLen'] || strlen($cleanText) > $core -> aConfig['maxNewsCommentLen'])
				{
					echo "<br/>Your message is too short or too long. It has to be at least <b>".$core -> aConfig['minNewsCommentLen']."</b> 
					symbols long, your one is just <b>".strlen($cleanText)."</b> symbols long. Max length is ".$core -> aConfig['maxNewsCommentLen'].".<br/>";
					misc::back(); 
				}
				else
				{
				$datetime = misc::getDateTime();
					core::$sql -> exec("insert into srcms_newscomments(newsID, author, text, time) values('$nID','$_SESSION[username]', '$cleanText', '$datetime')");
					echo "<br/><br/><b>Your comment has been successfully added</b>";
					misc::redirect("?pg=news&comment=$nID", 1);
				}
			}
		}	else echo "<br/><br/>You must be logged in to post comments";
	}
	else
	echo "<br/>You can't comment news article that doesn't exist.";
}
?>