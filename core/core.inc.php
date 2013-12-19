<?php
class core
{
	public $aConfig = array(array());
	
	//classes
	public static $sec;
	public static $sql;
	public static $cfg;
	public static $user;
	public static $misc;
	public static $ucp;
	public static $guild;
	public static $char;
	
	
	function __construct()
	{
		session_start();
		//$this -> _loadClasses();
		$this -> initCore();
		$this -> initSettings();
		$this -> setIniVariables();
	}
	
	private function setIniVariables()
	{
		ini_set("default_socket_timeout", 5);
		ini_set("output_buffering", "On");
	}
	private function initCore()
	{
		global $cfg;
		
		$this -> _loadClasses();
		//config file
		
		if(file_exists("config.inc.php"))
		{
			require_once('config.inc.php');
		}
		else die("core -> initCore -> Could not find config.inc.php");
		
		
		//classes
	
		
		//common classes initialization, todo: while(true) { secks(); } ???
		self::$sql = new mssql($cfg['sqlHost'],$cfg['sqlUser'],$cfg['sqlPass'], $cfg['aDatabases']);
		
		//settings
		$this -> initSettings();
	}
	
	
	private function _loadClasses()
	{
		include 'class/misc.class.php';
		include 'class/user.class.php';
		include 'class/char.class.php';
		include 'class/mssql.class.php';
		include 'class/security.class.php';
		include 'class/ucp.class.php';
		include 'class/guild.class.php';
		include 'config.inc.php';
		//include 'class/advancedshop.class.php';
		
		
		self::$sec = new security();
		self::$sql = new mssql($cfg['sqlHost'],$cfg['sqlUser'],$cfg['sqlPass'], $cfg['aDatabases']);
		self::$user = new user();	
		self::$char = new char();
		self::$guild = new guild();
		self::$ucp = new ucp();
		self::$misc = new misc();
		//self::$advancedshop = new shop();
		

	}
	
	private function initSettings()
	{
	
		self::$sql -> changeDB('acc');

		$hQuery = self::$sql -> exec("select * from srcms_settings");
		while($row = mssql_fetch_array($hQuery))
		{
			$this -> aConfig[$row['valueName']] = $row['value'];
		}

	}
	

	public function showMainContent()
	{
		switch($_GET['pg'])
		{
			case('news'):
			require_once('module/news.php');
			break;

			case('reg'):
			require_once('module/reg.php');
			break;
			
			case('dl'):
			require_once('module/dl.php');
			break;

			case('forums'):
			require_once('module/forums.php');
			break;

			case('team'):
			require_once('module/team.php');
			break;

			case('stats'):
			require_once('module/stats.php');
			break;
			
			case('drivers'):
			require_once('module/drivers.php');
			break;
			
			case('forgotpw'):
			require_once('module/forgotpw.php');
			break;
			
			case('cpw'):
			require_once('module/cpw.php');
			break;

			case('cem'):
			require_once('module/cem.php');
			break;

			case('ucp'):
			require_once('module/ucp.php');
			break;
			
			case('acp'):
			require_once('module/acp.php');
			break;
			
			case('rank'):
			require_once('module/rank.php');
			break;
			
			case('viewprofile'):
			require_once('module/viewprofile.php');
			break;
			
			case('donate'):
			require_once('module/donate.php');
			break;
			
			case('admin'):
			require_once('module/admin.php');
			break;
			
			case('index'):
			require_once('module/news.php');
			break;
			
			// Modules that must being connected for 
			
			case('shop'):
				if(isset($_SESSION['username'])) {
					require_once('module/shop.php');
				} else {
					echo '<script> alert("You must be logged in in order to use that option.")</script>';
					echo '<a href="index.php"><font color=red">Go to homepage and login in order to continue.</font></a>';
				}
			break;
		
			case('emailreplace'):
				if(isset($_SESSION['username'])) {
					require_once('module/emailreplace.php');
				} else {
					echo '<script> alert("You must be logged in in order to use that option.")</script>';
					echo '<a href="index.php"><font color=red">Go to homepage and login in order to continue.</font></a>';
				}
			break;
			
			case('sendsilk'):
				if(isset($_SESSION['username'])) {
					require_once('module/sendsilks.php');
				} else {
					echo '<script> alert("You must be logged in in order to use that option.")</script>';
					echo '<a href="index.php"><font color=red">Go to homepage and login in order to continue.</font></a>';
				}
			break;

			
			default:
			require_once('module/news.php');
			break;
		}
	}
}
?>
