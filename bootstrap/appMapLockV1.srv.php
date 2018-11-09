<?php
class appMapLock {
        protected final function __construct() {
                $this->init();
        }

        /**
         *     SEE singleton_factory ON GIT , GNU LICENCE
         * Called within __construct(), override if necessary.
         */
        protected final function init() { }

        public static final function getinstance() {
                $className = get_called_class();
                if (!isset(self::$__runstatObjects[$className])) {
                        self::$__runstatObjects[$className] = new $className();
                        return self::$__runstatObjects[$className];
                }else{

                runstat::errorevent("STOP","Sorry only one instance allowed");

                return;
                }
        }

		
	public static function request($json_req){
	//url, urlbase,

		$req = json_decode($json_req, true)

		if ( substr_count($req->url, '?') > 1){runstat::errorevent('STOP','URI MAY ONLY CONTAIN ONE ?');}

		$req->url = strtok($req->url, '?');

		if (strlen($req->urlbase) > 0){
			if ( 0==strncmp( $req->urlbase,req->url,strlen( $req->urlbase) ) ){
				$uri_array = mb_substr( $req->url , strlen($req->urlbase)); 
			}else{runstat::errorevent('STOP','Base directory does not match URI');}
		} //drop base_part
		
		$uri_array = mb_split('/',self::$calledUrl,4);
		$return_obj = new array();
		
		if ($access[1] == ""){
			//MAIN ENTRY PROVIDER";
                        $return_obj['provider'] = "palacegate"; 
                        $return_obj['application'] = "rootAppGreeting"; 
		}
		elseif($access[2] == ""){
			//DELEGATING PROVIDER - SELF PRESENTING
                        $return_obj['provider'] = $uri_array[1];
                        $return_obj['application'] = $uri_array[1]; //This is a reference to load the providers root application
                        $return_obj['reqmap'] = "/".$uri_array[2];
		}else{
			//DELEGATING PROVIDER  - APPLICATION ACCESS
						$return_obj['provider'] = $uri_array[1];
                        $return_obj['application'] = $uri_array[2]; //WHERE RATIANAME = $access[2] //1:2 =$
                        $return_obj['reqmap'] = "/".$access[3];
                }

$subQry = "SELECT access_key FROM subscriptions where fk_host_k_name = '".$return_obj['provider']."' AND fk_app_k_name = '".$return_obj['application']."'";
				var $accessKey = "";
                dbAdapter::opnDb();
                if($subrs = dbAdapter::exeQuery($subQry)){
                        if(mysql_num_rows($subrs) == 1){
                                $row = mysql_fetch_array($subrs, MYSQL_ASSOC);
                               $accessKey = $row['access_key'];
                        }
                        elseif(mysql_num_rows($subrs) == 0){
                                self::$accessKey = null;
                        }
                        else{
                               self::errorevent("STOP","integrity issue, only one sub allowed");
                        }
                }
				
				
$qry = "SELECT appmaps.basedir, namestore1.lock_key AS lock_key1,namestore2.lock_key AS lock_key2 FROM appmaps, namestore AS namestore1, namestore AS namestore2 WHERE appmaps.appname ='".dbAdapter::clnQry(return_obj['application'])."' AND namestore1.k_name='".return_obj['provider']."' AND namestore2.k_name='".return_obj['application']."'";

                $rs = null;
				dbAdapter::opnDb();
                if ( $rs = dbAdapter::exeQuery($qry ) ) {
				
                        if ( mysql_num_rows($rs) == 1 ) {
						
                                $row = mysql_fetch_array( $rs , MYSQL_ASSOC ) ;
								$return_obj['app_open_basdir'] = $row['basedir'];
								
								if ($accessKey != md5($row['lock_key1'].$row['lock_key2'] ) ) {
									runstat::errorevent("STOP", "SORRY YOU ARE NOT SUBSCRIBED TO THIS APP");
								}
						
                        }elseif(mysql_num_rows($rs) > 1){
                                runstat::errorevent("STOP","integrity issue, more than one resource map");

						}else{
                	        runstat::errorevent("STOP","401 not found");
                        }

                }else{
                        runstat::errorevent("STOP", mysql_error()." No resource record recieved <br/>".$qry);
                }
		return;
	}
}
?>
