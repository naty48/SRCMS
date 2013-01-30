<?php
class char
{
	public static function charnameByCharID($charID)
	{
		
		return (core::$sql -> getRow("select CharName16 from _Char where CharID='$charID'"));
	}
	
	public static function charIDByCharname($charName)
	{
		
		core::$sql -> changeDB("shard");
		return (core::$sql -> getRow("select CharID from _Char where CharName16='$charName'"));
	}
	
	public static function isCharNaked($szCharname)
	{
		
		core::$sql -> changeDB('shard');
		$nCharID = char::charIDByCharname($szCharname);
		$hQuery = core::$sql -> exec("select * from _Inventory where CharID='$nCharID'");
		$nSlot = 0;
		$bResult = true;
		while($row = mssql_fetch_array($hQuery))
		{
			if($row['ItemID'] != '0') 
			{
				$bResult = false;
				break;
			}
			if($nSlot == 12) break;
			$nSlot++;
		}
		return $bResult;
	}
	
	public static function charNamesByIDs($charIDs)
	{
		
		$result = array();
		core::$sql -> changeDB("shard");
		$i = 0;
		foreach($charIDs as $nCharID)
		{
			$result[$i] = core::$sql -> getRow("select CharName16 from _Char where CharID='$nCharID'");
			$i++;
		}
		return $result;
	}
	
	public static function getCharCount($szUsername)
	{
		
		$nJID = user::accountJIDbyUsername($szUsername);
		core::$sql -> changeDB("shard");
		return (core::$sql -> getRow("select count(*) from _User where UserJID='$nJID'"));
	}
	
	public static function getCharItems($szCharname)
	{
		
		$result = array();
		$nCharID = core::$sql -> getRow("select CharID from _Char where CharName16='$szCharname'");
		if($nCharID > 0)
		{
			$hCharItemIDs = core::$sql -> exec("select ItemID from _Inventory where CharID='$nCharID'");
			while($row = mssql_fetch_array($hCharITemIDs))
			{
				$result[] = $row['ItemID'];
			}
		}
		
		return $result;
	}
	
	public static function jobTypeByID($jobID)
	{
		switch($jobID)
		{
			case(1):
			return "<img src='img/trader-icon.png' alt='Trader'/> Trader";
			case(2):
			return "<img src='img/thief-icon.png' alt='Thief'/>  Thief";
			case(3):
			return "<img src='img/hunter-icon.png' alt='Hunter'/> Hunter";
			default:return "Nothing";
			break;
		}
	}
}
?>