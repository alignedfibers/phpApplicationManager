<?php
//Last modified 9/21/15
class moosession extends sharedLib{

	public static $startTime;    //`datetime session was started`
	public static $lastAccessed; //`datetime session was last updated`
	//public $updated;      `datetime after this update from php may not match that in DB`

	public static function startsess(){
		if(class_exists('runstat')&&class_exists('sharedLib')){
		self::guest();
		self::verifyout(self::hashin());
		}else{//this is an actual exception.
		}
	return;
	}

	/** 
	 *lets test and make you a session
	 ***/
	private static function guest(){
		if(parent::clntSess()){return;}// oops I overwrote your auth, e2fsk
		$userHandle = self::createguestname();
		$sessionId = md5(uniqid(microtime().runstat::$accessor.runstat::$client));

                /**THESE ARE NEEDED TO MATCH RUNNERS TO A SESSION CERTIFICATE**/
		runstat::$sessionHandle = md5($sessionId);
		runstat::$clientKey = md5(uniqid(microtime().$userHandle));
		//and should be in the cookie so we really don't need them if
		//we redirect correctly
		/**************************************************************/
		$clientId = md5(runstat::$clientKey);

//UGLY UGLY QUERY ----
$inQry = "INSERT INTO sessions (run_state,k_user_handle,k_sess_id,start_client,start_pro,start_time,accessed_time,is_guest) VALUES(2,'".dbAdapter::clnQry($clientId)."','".dbAdapter::clnQry($sessionId)."','".dbAdapter::clnQry($userHandle.runstat::$client)."','".dbAdapter::clnQry(runstat::$accessor)."',NOW(),NOW(),1)";//run_state = 1 for ready

		dbAdapter::opnDb();
		if(dbAdapter::exeQuery($inQry)){
			//echo "Inserted Guest Success";
		}else{
			runstat::errorevent("STOP",mysql_error()." ".__FILE__." session insert failed");
		} //`$runstat::saferedirect($_SERVER['REQUEST_URI'],false,true,"run state 2 session guest")`

		setcookie("SESSINF[USERHAND]",$userHandle,time()+3600*24,"/");
		setcookie("SESSINF[CLIENTKEY]",runstat::$clientKey,time()+3600*24,"/");
	   	setcookie("SESSINF[SESSHAND]",runstat::$sessionHandle,time()+3600*24,"/") ;
		setcookie("SESSINF[TIME]",$mymicro,time()+3600*24,"/");
		runstat::$userHandle = $userHandle;
		runstat::sessionredirect();
	}

	/***
	 *SESSION NEEDS A COUPLE OF HELPERS
	 *****/

	private function createguestname(){
	$sqlString = "SELECT count_integer FROM counters WHERE k_count_name = 'guestSessions'";
	dbAdapter::opnDb();
	if($rs = dbAdapter::exeQuery($sqlString)){
		if(mysql_num_rows($rs) == 1){
		$row = mysql_fetch_array($rs, MYSQL_ASSOC);
		$updatedCount = $row['count_integer']+1;
	$upQry="UPDATE counters SET count_integer=".$updatedCount." WHERE k_count_name='guestSessions'";
		if(!dbAdapter::exeQuery($upQry)){runstat::errorevent("STOP","cant update guest count");}
		}else{
		runstat::errorevent("STOP","k_count_name:guestSessions does not exist in table counters");
		}
	}else{runstat::errorevent("STOP",mysql_error()." SQL Error");}
	return "guestHandle".$updatedCount;
	}
	/***
	 * END GUEST SESS HELP
         ***/

	/*******
	 *RETRIEVE SESSION ROW & RETURN TO VERIFY
	 **********/
	private static final function hashin(){
		runstat::$clientKey = $_COOKIE['SESSINF']['CLIENTKEY'];
		$clientId = md5($_COOKIE['SESSINF']['CLIENTKEY']);

//QUERIES DO NOT LOOK NEAT
$qry = "SELECT k_sess_id,start_time,accessed_time,killed,is_guest FROM web_tools.sessions WHERE k_user_handle = '".dbAdapter::clnQry($clientId)."'";

		dbAdapter::opnDb();
		$rs = null;
		if($rs = dbAdapter::exeQuery($qry)){
			if(mysql_num_rows($rs) == 1){
				$row = mysql_fetch_array($rs, MYSQL_ASSOC);
				if($row['killed'] == 1){
					runstat::errorevent("STOP","Your session as been deactivated");
				}
				self::$startTime = $row['start_time'];
				self::$lastAccessed = $row['accessed_time'];
				runstat::$isGuest = $row['is_guest'];
				//`echo $row['k_sess_id'];`
			return $row['k_sess_id'];
			}elseif(mysql_num_rows($rs)>1){
	      runstat::errorevent("STOP"," integrity issue, more than one session for that identity");
			}else{
			runstat::errorevent("STOP",__LINE__." ".__FILE__." session not found ".$qry);
			}
		}else{
		runstat::errorevent("STOP",__LINE__." ".__FILE__." no result set ".mysql_error()." ".$qry);
		}

	}

	/***
	 *Make sure sessions match
	 ******/
	private static final function verifyout($out1){
		$out = md5($out1);
		runstat::$sessionHandle = $_COOKIE['SESSINF']['SESSHAND'];
		if($_COOKIE['SESSINF']['SESSHAND'] == $out){
	 $qry="UPDATE sessions SET accessed_time=NOW(),run_state=1 WHERE k_sess_id='".$out1."'";
			dbAdapter::opnDb();
			if(dbAdapter::exeQuery($qry)){
			}else{
			runstat::errorevent("STOP",mysql_error());
			}
		return;
		}else{runstat::errorevent("STOP","cant verify".$out." ".$_COOKIE['SESSINF']['SESSHAND']);}
	}
	/***
	 *USER SWITCHES
	 *******/

	public function switchUser($signedAuthority, $uHandle ){
	//We use rsa signature to ensure data recieved in public function is legit
	//This will create a serialized remnant from the object, containing
	//log time, handle, authenticationCert, sessionHandle reset cookies and be
	//the main identifing component used for everything, it will not contain
	//the key to the session..
	}
	public function getSigningKey(){
	}
	/***
	 *
	 *****/


}
?>
