<?php
header("Content-type: image/png"); 
if(isset($_GET['from']) && $_GET['from'] == 'app' && isset($_GET['name'])){
	if(file_exists(runstat::$root."/org-data/system/palacegate/images/".$_GET['name'])){
	runstat::$nohf=true;
	readfile(runstat::$root."/org-data/system/palacegate/images/".$_GET['name']);
	//echo runstat::$root."/sdat/system/images/apphash.png";
	}else{
		if(file_exists(runstat::$root."/org-data/system/palacegate/images/404.png")){
			readfile(runstat::$root."/org-data/system/palacegate/images/404.png");
		}else{self::errorevent("STOP","No Image Send 404");}	
	}
}
//runstat::respond();
//echo "just like the rest";
?>

