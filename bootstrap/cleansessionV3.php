<?php
//Last modified 9/21/15
$qry = "DELETE FROM web_tools.sessions WHERE (accessed_time < NOW() - INTERVAL 1800 SECOND AND run_state = 1) OR (start_time < NOW() - INTERVAL 30 SECOND AND run_state = 2) OR killed = 1 ;";

dbAdapter::opnDb;

if(dbAdapter::exeQry($qry)){
	
}
?>
