<?php
header("Content-type: image/png"); 
if(isset($_GET['from']) && $_GET['from'] == 'app' && isset($_GET['name'])){
//echo "set stat";
runstat::$nohf=true;
readfile(runstat::$root."/sdat/system/images/".$_GET['name']);
//echo runstat::$root."/sdat/system/images/apphash.png";
}
//runstat::respond();
//echo "just like the rest";
?>

