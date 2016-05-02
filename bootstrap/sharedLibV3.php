<?php
//Last modified 9/21/15
class sharedLib{

protected static function clntSess(){return (isset($_COOKIE['SESSINF']['USERHAND'],$_COOKIE['SESSINF']['SESSHAND'],$_COOKIE['SESSINF']['CLIENTKEY']) ? true : false);}
protected static function replaceDollarSign($symbol){}


}

?>
