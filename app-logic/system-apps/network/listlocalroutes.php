<?php
echo "display something different";
/*********************************************
 *When runstat::host == networks
 *[Display full listing] - filtered per user and user-agent
 * (ie. When you goto networks/ or applications/ or profiles/
 * a full list of all networks, applications or profiles appear) 
 * To do this we use a "callback" to runstat where it searches
 * for an alternate entry of networks - we already completed
 * security and can inject a callback at beginning of default
 * files to check for alternates for the current host request
 * this is a default file for a network formerly organization
 * 
 * purpose, overview, about, motive
 *********************************************/
echo 'Doing something';
if(runstat::$isTop == false){
//echo "istop";
$qry1 = "SELECT fk_app_k_name FROM subscriptions WHERE fk_host_k_name ='".runstat::$host."'";
$qry2 = "SELECT request FROM maps WHERE ratia_name ='".runstat::$accessor."'";
//echo $qry1;
dbAdapter::opnDb();
//echo 'you are here2';
if($rs1 = dbAdapter::exeQuery($qry2)){
	//echo 'you are here3';
	if(mysql_num_rows($rs1) >= 1){
		//echo 'you are here4';
		$row = mysql_fetch_array($rs1, MYSQL_ASSOC);
		$ulsubs = $ulsubs."<li><a href='/".runstat::$baseurl.'/'.runstat::$host.'/'.runstat::$accessor.$row['request']."'>".$row['request']."</a></li>";
		while ($row = mysql_fetch_assoc($rs1)) {
		//$workhost = ($row['fk_app_k_name'] == runstat::$host ? '' : runstat::$host);
		//$workhost = ($workhost != '' ? runstat::$baseurl.'/'.$workhost : 'switch');
$ulsubs = $ulsubs."<li><a href='/".runstat::$baseurl.'/'.runstat::$host.'/'.runstat::$accessor.$row['request']."'>".$row['request']."</a></li>"; 
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
