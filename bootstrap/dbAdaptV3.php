<?PHP
//Last modified 9/21/15
class dbAdapter{
private static $conn='';
private static $connOpen = false;
private static $logOpen = false;

	protected final function __construct() {
	}

	public static function makeWebTwo(){
		if (mysql_query("CREATE DATABASE gateor",self::$conn))
		{
		   echo "Database created";
		}
		else
		{
		   echo "Error creating database: " . mysql_error();
		}
	}

	public static function opnDb(){
		if(self::$connOpen == false){
			self::$conn = mysql_connect(config::$dbServer,config::$dbUser,config::$dbPassword);
			self::$connOpen =  true;
			return true;
		}else{
			return true;
		}
	}

	public static function clsDb(){
	}

	public static function chngDb(){

	}

	public static function clnQry($string){

		if (phpversion() >= '4.3.0'){
			$string = mysql_real_escape_string($string);
		}
		else{
			$string = mysql_real_escape_string($string);
		}
	return $string;
	}

	public static function exeQuery($qStr){
		mysql_select_db(config::$systemDbSchema, self::$conn);
		$rs = mysql_query($qStr);
		return $rs;
	}

	public static function exeQ($qStr){
		mysql_select_db("gateor", self::$conn);
		$rs = mysql_query($qStr);
		return $rs;
	}

	protected final function __clone() { }

	public final function __sleep() {
		throw new SystemException('Serializing of Singletons is not allowed');
		// Turret: I'm different
	}


}


?>
