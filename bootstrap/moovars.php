<?php	
	/**
	 *Public vars from the boot strap
	 **/
	public static $client = null;   ///format: userhandle@orgname.{pub,stage}
	public static $accessor = null; ///resource provider, userhandle@orgname:appname

	public static $statelessId = null;  ///identify connection
	public static $statefullId = null;  ///identify TCP/IP socket connection
 	public static $runcontinued = false;///indicate run has broken - REMOVE

	public static $resource;                ///locate resource 
	public static $clientKey = null;        ///             md5 in >>>>
	public static $sessionHandle = null;    ///<<<<md5 out  
	public static $isGuest = null;       	///says: I am not persistent
	
	public static $isSearchEngine;		///should set engine name as user, public access
	public static $calledUrl;		/// -REMOVE and use local var.. maybe need it
	public static $reqMap;
	public static $resumeRun;	///resume last run, update stateless_id..done auto -REMOVE
	public static $updStatelessId;	/// use local working var -REMOVE
	public static $userHandle;
	public static $benchType;            ///can be {update_bench,new_bench}
	public static $isChannel;  
	public static $accessKey; 
	public static $host; 
	public static $root;
	public static $isTop;
	public static $nohf = false;
?>