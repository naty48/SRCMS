<?php
class misc
{
	public static function getDateTime()
	{
		return gmDate('Y-m-d H:i:s');
	}
	
	 public static	function applyAttributesToText($text)
	{
		$text = ereg_replace(":)", "<img src='img/smileys/01.gif'>", $text);
		$text = ereg_replace("\:\(", "<img src='img/smileys/02.gif'>", $text);
		$text = ereg_replace(":D", "<img src='img/smileys/03.gif'>", $text);
		$text = ereg_replace("8)", "<img src='img/smileys/04.gif'>", $text);
		$text = ereg_replace(":O", "<img src='img/smileys/05.gif'>", $text);
		$text = ereg_replace(";)", "<img src='img/smileys/06.gif'>", $text);
		$text = ereg_replace("\;\(", "<img src='img/smileys/07.gif'>", $text);
		$text = ereg_replace("\:\|", "<img src='img/smileys/08.gif'>", $text);
		$text = ereg_replace("\:\P", "<img src='img/smileys/09.gif'>", $text);
		$text = ereg_replace(":x", "<img src='img/smileys/23.gif'>", $text);
		$text = ereg_replace(":@", "<img src='img/smileys/18.gif'>", $text);
		$text = ereg_replace(":s", "<img src='img/smileys/20.gif'>", $text);
		$text = ereg_replace("\(nod\)", "<img src='img/smileys/32.gif'>", $text);
		$text = ereg_replace("\(shake\)", "<img src='img/smileys/33.gif'>", $text);
		$text = ereg_replace("\(wait\)", "<img src='img/smileys/26.gif'>", $text);
		$text = ereg_replace("\(finger\)", "<img src='img/smileys/44.gif'>", $text);
		$text = ereg_replace("\(rock\)", "<img src='img/smileys/46.gif'>", $text);
		
		$text = preg_replace("#\[url\](http:\/\/[\w-_\.]*\.{1}\w{2,}[\w-_\/\.\?=]*)\[\/url\]#isU", '<a href="\\1"">\\1</a>', $text);
		$text = preg_replace("#\[b\](.*?)\[\/b\]#si", '<b>\\1</b>', $text);
		$text = preg_replace("#\[u\](.*?)\[\/u\]#si", '<u>\\1</u>', $text);
		$text = preg_replace("#\[code\](.*?)\[\/code\]#si", '<table id=\'table-3\' border=\'1\' cellpadding=\'0\' cellspacing=\'0\'> <td width=\'700\'><pre>\\1</pre></td></table>', $text);

		
		return $text;
	}
	
	public static function genRandomString() {
	$length = 35;
	$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
	$string = '';    
	for ($p = 0; $p < $length; $p++) {
		$string .= $characters[mt_rand(0, strlen($characters))];
	}
	return $string;
	}
	
	public static function textAttributeList($areaName)
	{
	echo "
		<script type=\"text/javascript\" language=\"javascript\">
				function addElement(val)
				{
					var commentBoxElem = document.getElementById(\"$areaName\");
					commentBoxElem.value += val;
				}
				</script>
				
		<img src='img/smileys/01.gif' onclick='addElement(\":)\")'>
		<img src='img/smileys/02.gif' onclick='addElement(\":(\")'>
		<img src='img/smileys/03.gif' onclick='addElement(\":D\")'>
		<img src='img/smileys/04.gif' onclick='addElement(\"8)\")'>
		<img src='img/smileys/05.gif' onclick='addElement(\":O\")'>
		<img src='img/smileys/06.gif' onclick='addElement(\";)\")'>
		<img src='img/smileys/07.gif' onclick='addElement(\";(\")'>
		<img src='img/smileys/08.gif' onclick='addElement(\":|\")'>
		<img src='img/smileys/09.gif' onclick='addElement(\":P\")'>
		
		<img src='img/smileys/23.gif' onclick='addElement(\";(\")'>
		<img src='img/smileys/18.gif' onclick='addElement(\":@\")'>
		<img src='img/smileys/20.gif' onclick='addElement(\":S\")'>
		
		<img src='img/smileys/32.gif' onclick='addElement(\"(nod)\")'>
		<img src='img/smileys/33.gif' onclick='addElement(\"(shake)\")'>
		<img src='img/smileys/26.gif' onclick='addElement(\"(wait)\")'>
		
		
		<img src='img/smileys/44.gif' onclick='addElement(\"(finger)\")'>
		<img src='img/smileys/46.gif' onclick='addElement(\"(rock)\")'>
		<br/>
		<input type='button' value='Link' onclick='addElement(\"[url][/url]\")'>
		<input type='button' value='Code' onclick='addElement(\"[code][/code]\")'>
		<input type='button' value='Underline' onclick='addElement(\"[u][/u]\")'>
		<input type='button' value='Bold' onclick='addElement(\"[b][/b]\")'><br/>
		
		";
	}
	
	//redirects user to url
	public static function redirect($url, $nTime = 0)
	{
		echo "<meta http-equiv='Refresh' content='$nTime; url=$url'>";
	}
	
	//shows javascript messagebox
	public static function showAlert($text)
	{
		echo "<script>alert(\"$text\")</script>";
	}
	
	//go back
	public static function back()
	{
		echo "<input type='button' onclick='history.go(-1)' value='Back'>";
	}
	
	public static function getOnlinePlayersCount()
	{
		global $mssql;
		global $core;
		core::$sql -> changeDB("acc");
		return core::$sql -> getRow("SELECT top 1 nUserCount FROM _ShardCurrentUser WHERE nShardID = '".$core -> aConfig['shardID']."' ORDER BY nID desc");
	}
	
	
	public static function getCampDataByID($nID)
	{
		core::$sql -> changeDB("shard");
		$saData = array(array());
		$saTemp = core::$sql -> exec("select * from _TrainingCamp where ID='$nID'");
		$saTemp = mssql_fetch_array($saTemp);
		
		$saData['GraduateCount'] = $saTemp['GraduateCount'];
		$saData['Comment'] = $saTemp['Comment'];
		$saData['CommentTitle'] = $saTemp['CommentTitle'];
		
		$szOwnerName = core::$sql -> getRow("select CharName from _TrainingCampMember where CampID='$nID' and MemberClass='0'");
		$saData['OwnerName'] = $szOwnerName;
		
		
		if(empty($saData['GraduateCount'])) $saData['GraduateCount'] = "<b>None</b>";
		if(empty($saData['OwnerName'])) $saData['OwnerName'] = "<b>None</b>";
		return $saData;
	}
}
?>