<?php
//Last modified 9/21/15
class runstat extends sharedLib{

        /**
        GLOBAL PROPERTIES
        **/
        public static $client = null;   ///format: userhandle@orgname.{pub,stage}
        public static $accessor = null; ///resource provider, userhandle@orgname:appname

        public static $statelessId = null;  ///identify connection
        public static $usesChannel = false; ///indicate resource only, no system menu
        public static $statefullId = null;  ///identify TCP/IP socket connection
        public static $runcontinued = false;///indicate run has broken - REMOVE

        public static $resource;                ///locate resource
        public static $clientKey = null;        ///              in >>>>
        public static $sessionHandle = null;    ///<<<<out
        public static $isGuest = null;          ///says: I am not persistent

        public static $isSearchEngine;          ///should set engine name as user, public a$
        public static $calledUrl;               /// -REMOVE and use global var
        public static $reqMap;
        public static $resumeRun;       		///resume last run, update stateless_id..
        public static $updStatelessId;  		///use local working var -REMOVE
        public static $userHandle;
        public static $benchType;            	///can be {update_bench,new_bench}
        public static $isChannel;
        public static $accessKey;
        public static $host;
        public static $root;
        public static $isTop;
        public static $nohf = true;
        public static $baseurl = 'switch';

        /****
         *Instance Creation Methods 
         **/

        protected final function __construct() {
                $this->init();
        }

        /**
         *     SEE singleton_factory ON GIT , GNU LICENCE
         * Called within __construct(), override if neccessary.
         */
        protected final function init() { }

        /*public static final function getinstance() {
                $className = get_called_class();
                if (!isset(self::$__runstatObjects[$className])) {
                        self::$__runstatObjects[$className] = new $className();
                        return self::$__runstatObjects[$className];
                }else{

                self::errorevent("STOP","Sorry only one instance allowed");

                return;
                }
        }*/

        public static function gethead(){
                ob_clean();
				include("/home/angular-phonecat/test/app-logic/system-apps/crumb/head.htm");
                $contents = ob_get_contents();
                ob_clean();
                return $contents;
        }

        public static function gettail(){
                ob_clean();
                include(self::$root."/home/angular-phonecat/test/app-logic/system-apps/crumb/tail.htm");
                $contents = ob_get_contents();
                ob_clean();
                return $contents;
        }

	private static function quickmap(){
		//Not a finished product
		self::$calledUrl = strtok($_SERVER['REQUEST_URI'], '?');
		$testChr = strrchr(self::$calledUrl,'root');
		$base = '/switch';
		if (strncmp($base,self::$calledUrl,strlen($base))==0){}
		$newstr = mb_substr(self::$calledUrl , strlen($base));
		strncmp("xybc","a3234",0);
		$access = mb_split('/',self::$calledUrl,5);
		$diff = array_shift($access);
		if ($access[1] == ""){
			// default consumer namespace -- Main landing
                        self::$host = "palacegate"; //$access[0]
                        self::$client = $_COOKIE['SESSINF']['USERHAND']."@palacegate";
                        self::$accessor = "palacegate";//$access[0]
                        self::$reqMap = "/"; //gets system index
                        
                        self::$isTop = 1;

		}
        elseif($access[2] == ""){
			// subscribing consumer namespace -- Index of subscribed
                        self::$host = $access[1];
                        self::$client = $_COOKIE['SESSINF']['USERHAND']."@".$access[1]; // 1$
                        self::$accessor = $access[1]; 
                        self::$reqMap="/".$access[2];
                        self::$isTop=1;
        }else{
			// provider namespace -- routing is handed over to provider application
                        self::$host = $access[1];
                        self::$client = $_COOKIE['SESSINF']['USERHAND']."@".$access[1];
                        self::$accessor = $access[2]; 
                        self::$reqMap="/".$access[3];
                        self::$isTop=0;
        }

$subQry = "SELECT access_key FROM subscriptions where fk_host_k_name = '".self::$host."' AND fk_app_k_name = '".self::$accessor."'";

                dbAdapter::opnDb();
				
                if($subrs = dbAdapter::exeQuery($subQry)){
                        if(mysql_num_rows($subrs) == 1){
                                $row = mysql_fetch_array($subrs, MYSQL_ASSOC);
                                self::$accessKey = $row['access_key'];
                        }
                        elseif(mysql_num_rows($subrs) == 0){
                                self::$accessKey = null;
                        }
                        else{
                               self::errorevent("STOP","integrity issue, only one sub allowed");
                        }
                }

				$qry = "SELECT maps.resource,maps.is_channeled,ratia1.lock_key AS lock_key1,ratia2.lock_key AS lock_key2 FROM maps, ratiatable AS ratia1, ratiatable AS ratia2 WHERE maps.request ='".dbAdapter::clnQry(self::$reqMap)."' AND ratia1.k_name='".self::$host."' AND maps.ratia_name='".self::$accessor."' AND ratia2.k_name='".self::$accessor."' AND maps.is_top = ".self::$isTop;

                dbAdapter::opnDb();
                $rs = null;
                if($rs = dbAdapter::exeQuery($qry)){
                        if(mysql_num_rows($rs) == 1){
							$row = mysql_fetch_array($rs, MYSQL_ASSOC);
							self::$resource = $row['resource'];
							//echo self::$resource;
							if(self::$accessKey != md5($row['lock_key1'].$row['lock_key2'])){
								self::errorevent("STOP", "SORRY YOU ARE NOT SUBSCRIBED TO THIS APP");
							}
                        }elseif(mysql_num_rows($rs) > 1){
                                self::errorevent("STOP","integrity issue, more than one resource map");

						}else{
							self::errorevent("STOP","401 not found");
						}

                }else{
					self::errorevent("STOP", mysql_error()." No resource record recieved <br/>".$qry);
                }
	
		return;

	}

	private static function logrunstart(){

		$sli = md5(uniqid(microtime().self::$client.self::$accessor));
		
		//UGLY QUERY
		$selQry = "SELECT last_ip FROM running WHERE stateless_id ='".$_COOKIE['RUNNERS'][self::$client.":".self::$accessor]."' AND client='".self::$client."'"; 
		
		dbAdapter::opnDb();
		if($rs = dbAdapter::exeQuery($selQry)){
			if(mysql_num_rows($rs) == 1){
				self::$benchType = "update_bench_finish"; //remove
				
				$updQry="UPDATE running SET stateless_id='".dbAdapter::clnQry($sli)."',last_ip='".dbAdapter::clnQry($_SERVER['REMOTE_ADDR'])."',is_broken='1',update_bench_start=NOW(),update_agent='".dbAdapter::clnQry(htmlentities($_SERVER['HTTP_USER_AGENT']))."',update_referer='".dbAdapter::clnQry($_SERVER['HTTP_REFERER'])."' WHERE stateless_id ='".dbAdapter::clnQry($_COOKIE['RUNNERS'][self::$client.":".self::$accessor])."'";

				dbAdapter::opnDb();
				if(dbAdapter::exeQuery($updQry)){
					self::$resumeRun = true;
				}else{
					self::errorevent("STOP",mysql_error());
				}
			}elseif(mysql_num_rows($rs) == 0){
				self::$benchType = "finish_bench";
				$insQry="INSERT INTO running (client_ip,client,provider,query,referer,agent,has_errors,stateless_id,start_bench,URI_CALLED) VALUES('".dbAdapter::clnQry($_SERVER['REMOTE_ADDR'])."','".dbAdapter::clnQry(self::$client)."','".dbAdapter::clnQry(self::$accessor)."','".htmlentities($_SERVER['QUERY_STRING'])."','".$_SERVER['HTTP_REFERER']."','".$_SERVER['HTTP_USER_AGENT']."', 0,'".dbAdapter::clnQry($sli)."', NOW(), '".$_SERVER['REQUEST_URI']."')";
				dbAdapter::opnDb();
				if(!dbAdapter::exeQuery($insQry)){
					self::errorevent("STOP"," sql error ".mysql_error());
				}else{ 
					
				}
			}elseif(mysql_num_rows($rs) > 1){
				                             
				self::errorevent("STOP","Integrity issue more than one run with that id");
			}else{
				
				self::errorevent("STOP", mysql_error());
			}

		}else{
			self::errorevent("STOP"," sql error ".mysql_error());
		}
        	self::$statelessId = $sli;//make sure any further updates use new id
		return;
	}

        public static function sessionredirect(){
                self::$client = self::$userHandle.self::$client;

setcookie("RUNNERS[".self::$client.':'.self::$accessor."]",self::$statelessId,time()+3600*24,"/");

$updQry = "UPDATE running SET client='".self::$client."', finish_bench=NOW(), session_handle='".self::$sessionHandle."' , uses_channel=1 WHERE stateless_id='".self::$statelessId."'";
                dbAdapter::opnDb();
                if(!dbAdapter::exeQuery($updQry)){
                	self::errorevent("STOP",mysql_error()." could not update runner ".__FILE__.__LINE__);
                }
        header("location:".runstat::$calledUrl);
        }

        /***
         *Main Run Handles
         **/
        public static final function start(){
                ob_start("callback");
                self::sysinit(); //install automation is run from this function if not already configured
				blockit::blocknocookies();
				self::quickmap();
				mooSession::startsess();
				self::logrunstart();
	        return;
        }

		/**************** Jail the loaded script / expose needed functionality ************
		function safeinclude($filename)
			{
				//This line takes all the global variables, and sets their scope within the function:
				foreach ($GLOBALS as $key => $val) { global $$key; }
					//Pre-Processing here: validate filename input, determine full path of file, 
					//check that file exists, 
	`				if ($exists==true) { include("$file"); }
		return $exists;
		}*/
		
		public static function safeinclude(){
			$lookFor = '/home/angular-phonecat/test'.self::$resource;
			if(file_exists($lookFor)){
				include($lookFor);
			}else{ 
			echo 'notfound';
				//self::errorevent("STOP","Script does not exist in location requested ".__FILE__.__LINE__);
				//self::errorevent("STOP","Script does not exist in location requested ".__FILE__.__LINE__);
			}
		return;	
		}

        public static function sysinit(){
                if(config::$installed){return;}
                self::loadinit();
        }
        public static function errorevent($action,$err){
                ob_clean();
                
                //`$this->testSecondaryFunc();`
                ob_end_flush();
                exit;
        }
        private static function loadinit(){
                ob_clean();
                include("/initialization/init.php");
                ob_end_flush();
                exit;
        }

        private static function appendbuffer(){}
        private static function updaterrun(){}

        public static function finish(){
                self::appendbuffer(); 
                self::updaterun(); 
        }

        public static final function respond(){

$updQry = "UPDATE running SET resource='".self::$resource."',uses_channel='".self::$usesChannel."',session_handle='".self::$sessionHandle."',".self::$benchType."=NOW(),client_key='".self::$clientKey."' WHERE stateless_id ='".self::$statelessId."'";

                dbAdapter::opnDb();
                if(!dbAdapter::exeQuery($updQry)){
                	self::errorevent("",mysql_error()." problem running final qry to update run ".$updQry);
                }
setcookie("RUNNERS[".self::$client.':'.self::$accessor."]",self::$statelessId,time()+3600*24,"/");
		
			if(self::$nohf != true){
			$oldbuff = ob_get_contents();
			$topbuff = self::gethead();
			$endbuff = self::gettail();
			$newbuff = $topbuff.$oldbuff.$endbuff;
			echo $newbuff;
			}
			exit;
        }
        /**
         * Object cloning is disallowed.
         */
        protected final function __clone() { }

        /**
         * Object serializing is disallowed.
         */
        public final function __sleep() {
                throw new SystemException('Serializing of Singletons is not allowed');
                // Turret: I'm different
        }


}

?>
