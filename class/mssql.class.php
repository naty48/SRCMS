<?php
class mssql
{
	private $aDatabases = array();
	private $sqlHandle;
	
	function __construct($szHostname, $szUsername, $szPassword,$aDatabases)
	{
			$this -> aDatabases = $aDatabases;
			$this -> connect($szHostname, $szUsername, $szPassword);
	}
	
	private function connect($szHostname, $szUsername, $szPassword)
	{
		$this -> sqlHandle = mssql_connect($szHostname, $szUsername, $szPassword);
		if($this -> sqlHandle)
		{
			$this -> changeDB('acc'); //default db
		}
		else 	die("Could not open database connection");
	}
	
	public function changeDB($szDB)
	{
		switch($szDB)
		{
			case('acc'):
			mssql_select_db($this -> aDatabases[0], $this -> sqlHandle);
			break;
			
			case('shard'):
			mssql_select_db($this -> aDatabases[1], $this -> sqlHandle);
			break;
			
			case('log'):
			mssql_select_db($this -> aDatabases[2], $this -> sqlHandle);
			break;
			
			default:
				die("Invalid value for mssql -> changeDB [$szDB]");
			break;
		}
	}
	
	public function exec($szQuery)
	{
		return mssql_query($szQuery);
	}
	
	public function getRow($szQuery)
	{
		$hQuery = $this -> exec($szQuery);
		$result = mssql_fetch_row($hQuery);
		return $result[0];
	}
	
	public function fetchRow($szQuery)
	{
		$hQuery = $this -> exec($szQuery);
		$result = mssql_fetch_row($hQuery);
		return $result;
	}
	
	public function fetchArray($szQuery)
	{
		$hQuery = $this -> exec($szQuery);
		$result = mssql_fetch_array($hQuery);
		return $result;
	}
	
	public function numRows($szQuery)
	{
		$hQuery = $this -> exec($szQuery);
		$result = mssql_num_rows($hQuery);
		return $result;
	}
}
?>