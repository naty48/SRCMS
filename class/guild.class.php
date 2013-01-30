<?php
class guild
{
	public static function guildNameByID($nGuildID)
	{
		
		return core::$sql -> getRow("select Name from _Guild where ID='$nGuildID'");
	}
}
?>