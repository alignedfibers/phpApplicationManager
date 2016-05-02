<?php
//Last modified 9/21/15
class blockit{

	public static final function blocknocookies(){
//Three use cases
//has garbage cookie, vc = false OR vc not set then test passed - proceed normal execution
//no garbage cookie, vc is set AND vc = true then test stopped - client cookies disabled
//no garbage cookie, vc = false OR vc not set then test failed - begintest redirect
$xy=(isset($_GET['vc'])&&$_GET['vc']?(isset($_COOKIE['lang'])?'pass':'stop'):(isset($_COOKIE['lang'])?'pass':'fail'));
		if($xy=='pass'){return;}
		if($xy=='fail'){ echo "cookietest failed";
		setcookie("lang","en",time()+3600*24,"/");
		header("location:".$_SERVER['REQUEST_URI']."?vc=true");
		}//runstat::$noCookies = true;
		if($xy=='stop'){runstat::errorevent("STOP","Not excepting users with out cookies");}
	}

	public function blocknojscript(){
	}

}
?>
