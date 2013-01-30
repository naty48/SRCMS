<?php
class security
{
	function __construct()
	{
		$this -> checkGetVariables();
		$this -> checkPostVariables();
		$this -> checkSessionVariables();
	}
	
	private function checkGetVariables()
	{
		//todo: log "SQLI" attempts
		if(count($_GET) > 0)
		{
			foreach($_GET as $nElement => $nValue)
			{
				if(is_array($nValue))
				{
					die("GET variable nElement can't be array");
				}
				
				$nValue = security::toHTML($nValue);
			}
		}
	}
	
	public static function secure($data) {
	if ( !isset($data) or empty($data) ) return '';
	if ( is_numeric($data) ) return $data;
	$non_displayables = array('/%0[0-8bcef]/', '/%1[0-9a-f]/', '/[\x00-\x08]/', '/\x0b/', '/\x0c/', '/[\x0e-\x1f]/');
	foreach ( $non_displayables as $regex )
	$data = preg_replace( $regex, '', $data );
	$data = str_replace("'", "''", $data );
	return $data;
	}
	
	
	private function checkPostVariables()
	{
		if(count($_POST) > 0)
		{
			foreach($_POST as $nElement => $nValue)
			{
				if(is_array($nValue))
				{
					die("POST variable nElement can't be array");
				}
				
				$nValue = security::toHTML($nValue);
			}
		}
	}
	
	private function checkSessionVariables()
	{
		if(count($_SESSION) > 0)
		{
			foreach($_SESSION as $nElement => $nValue)
			{
				if(is_array($nValue))
				{
					die("SESSION variable nElement can't be array");
				}
				
				$nValue = security::toHTML($nValue);
			}
		}
	}
	
	public static function isValidUrl($szUrl)
	{
		return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $szUrl);
	}
	
	public static function isCorrectEmail($szMailAddress)
	{
		if(filter_var($szMailAddress, FILTER_VALIDATE_EMAIL)) 
			return true;
			else return false;
	}
	
	public static function isSecureString($str, $secLevel)
	{
		$pattern = null;
		
		//todo: implement first level pattern
			switch($secLevel)
			{
				case(1):
					$pattern = "#[^a-zA-Z0-9@~`!@#$%&*()_-+=\[\]{}\|\\:;\"\'<,>.]#";
				break;
				
				case(2):
					$pattern = "#[^a-zA-Z0-9@._\-]#";
				break;

				case(3):
					$pattern = "#[^a-zA-Z0-9_\-]#";
				break;
				
				default: die("security -> isSecureString -> Unknown string security level [$secLevel]");
				break;
			}
           //$pattern = "#[^a-zA-Z0-9_\-]#";
               if(preg_match($pattern,$str))
					return false;
                    else return true;
	}
	
	
	public static function toHTML($str)
	{
			$newvar = stripslashes($str);
			$newvar = str_replace("<script>", "[xss attempt]", $newvar);
			$newvar = str_replace("</script>", "[xss attempt]", $newvar);
			//$newvar = str_replace("</script>", "[xss attempt]", "[xss attempt]");
			//$newvar = str_replace("\n","",$newvar);
			//$newvar = str_replace(";","&#59;",$newvar);
			$newvar = str_replace("%","&#37;",$newvar);
			$newvar = str_replace("'","&#39;",$newvar);
			$newvar = str_replace(",","&#44;",$newvar);
			//$newvar = str_replace(".","&#46;",$newvar);
			//$newvar = str_replace(":","&#58;",$newvar);
			$newvar = str_replace("`","&#96;",$newvar);
			$newvar = str_replace("<", "&#60;", $newvar);
			$newvar = str_replace(">", "&#62;", $newvar);
			$newvar = str_replace("\"", "&#34;", $newvar);
			return $newvar;
	}	
	
	public static function fromHTML($str)
	{
		$newvar = stripslashes($str);
			//$newvar = str_replace(";","&#59;",$newvar);
			$newvar = str_replace("&#37;","%",$newvar);
			$newvar = str_replace("&#39;","'",$newvar);
			$newvar = str_replace("&#44;",",",$newvar);
			//$newvar = str_replace(".","&#46;",$newvar);
			//$newvar = str_replace(":","&#58;",$newvar);
			$newvar = str_replace("&#96;","`",$newvar);
			$newvar = str_replace("&#60;", "<", $newvar);
			$newvar = str_replace("&#62;", ">", $newvar);
			$newvar = str_replace("&#34;", "\"", $newvar);
			return $newvar;
	}
}
?>