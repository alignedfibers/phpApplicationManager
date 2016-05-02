<?PHP
//error_reporting(E_ALL);
///ini_set("display_errors", 1);
//Last modified 9/21/15
	/**
	 *Global functions.
	 ****/
	function callback($buffer){return $buffer;}

	function get_execution_time(){ static $microtime_start = null;
		if($microtime_start === null){
			$microtime_start = microtime(true);
			return 0.0;
	    	}
	    return microtime(true) - $microtime_start;
	}
	get_execution_time();
	$realRoot = __DIR__;
	$mynuts = "nothing";
	/********************************************
	Include the whole application library
	*********************************************/
	require_once($realRoot.'/bootstrap/configV3.php');
	//require_once('../includes/transport_objects.php');
	require_once($realRoot.'/bootstrap/dbAdaptV3.php');
	//require_once('/home/myguy/websites/webtools/jav_tools/includes/rsa.php');
	require_once($realRoot.'/bootstrap/sharedLibV3.php');
	require_once($realRoot.'/bootstrap/blockitV3.php');
	require_once($realRoot.'/bootstrap/runstatV3-2.php');
	require_once($realRoot.'/bootstrap/moosessionV3.php');
	//require_once($realRoot.'/includes/validate.php');
	//require_once('/home/myguy/websites/webtools/jav_tools/includes/circuitV1.php');
	//require_once('../includes/encryptionclass.php');
	runstat::start();
	runstat::safeinclude();
	runstat::respond();
?>
