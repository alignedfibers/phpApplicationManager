<?php
////global runstat::root;
////runstat::$nohf=true;
//echo '>>>'.$GLOBALS[runstat].root.'<<<';
header("Content-type: image/png");
//if(isset($_GET['from']) && $_GET['from'] == 'app' && isset($_GET['name'])){
//echo "set stat";
runstat::$nohf=true;
//if(runstat::$nohf == 0){
readfile("/var/www/afootprint.png");
//}
//}*/
//runstat::respond();
////echo "just like the rest";
?>



