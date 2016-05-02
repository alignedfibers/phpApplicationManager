<?php
/*********************************************
 *When runstat::host == networks
 *[Display full listing] - filtered per user and user-agent
 * (ie. When you goto networks/ or applications/ or profiles/
 * a full list of all networks, applications or profiles appear) 
 * To do this we use a "callback" to runstat where it searches
 * for an alternate entry of networks - we already completed
 * security and can inject a callback at begining of default
 * files to check for alternates for the current host request
 * this is a default file for a network formerly organization
 * 
 * purpose, overview, about, motive
 *********************************************/
//echo $_SERVER['is_special']."<br/>";
//select all from subscriptions
/*if(runstat::$host == 'organizations'){
echo "special case"; //loads the public version ie. "alternate"
//this will tell runstat to reload a default page for this request
exit;
}*/

if(runstat::$isTop == true){
//echo "istop";
$qry1 = "SELECT fk_app_k_name FROM subscriptions WHERE fk_host_k_name ='".runstat::$host."'";
//echo $qry1;
dbAdapter::opnDb();
if($rs1 = dbAdapter::exeQuery($qry1)){
	if(mysql_num_rows($rs1) >= 1){
		$row = mysql_fetch_array($rs1, MYSQL_ASSOC);
		$workhost = ($row['fk_app_k_name'] == runstat::$host ? '' : runstat::$host);
		$workhost = ($workhost != '' ? 'switch/'.$workhost : 'switch'); 
//echo ">>>>>>>>>>>>>>>".$workhost.runstat::$baseurl;
//echo "+++++".$workhost."++++";
//echo runstat::$baseurl."/".$row['fk_app_k_name'];
		$ulsubs = "";
		$ulsubs = $ulsubs."<li><a href='/".$workhost."/".$row['fk_app_k_name']."'>".$row['fk_app_k_name']."</a></li>";
		while ($row = mysql_fetch_assoc($rs1)) {
		$workhost = ($row['fk_app_k_name'] == runstat::$host ? '' : runstat::$host);
		$workhost = ($workhost != '' ? runstat::$baseurl.'/'.$workhost : 'switch');
$ulsubs = $ulsubs."<li><a href='/".$workhost."/".$row['fk_app_k_name']."'>".$row['fk_app_k_name']."</a></li>"; 
		}
		
	}
}else{echo mysql_error();}
?>
<script>
function classone(custNam, custNum){
	this.memberCustNam = custNam;
	this.memberCustNum = custNum;

	this.funcWriteName = function writeName(){
		window.alert("Public Function "+"Fname="+this.memberCustNam+this.memberCustNum);
		//testGlobal();
	}
	
	this.funcWriteNumber = function writeNumber(){
		window.alert(this.memberCustNum);
	
	}
}
var arrayone = [];
arrayone['class1'] = classone;

var testObj = new arrayone['class1']("shawn", "7154108630");
	//testObj.funcWriteName();
	//testObj.funcWriteNumber();
</script>
<ul>
<?php echo $ulsubs; ?>
</ul>

<?php }else{ echo "this prints if isTop != true and should never happen because there should not be subscriptions to organizations, an organization can have members and observers Not subscribers - they can also be bookmarked the same way a ppl or org entry bookmarks anything through the bookmark app.."; } ?>
