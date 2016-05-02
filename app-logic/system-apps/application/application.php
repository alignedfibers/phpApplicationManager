<?php
if(runstat::$isTop == 0){
//echo "istop";
$qry1 = "SELECT k_name FROM ratiatable WHERE type ='sysapp'";
//echo $qry1;
dbAdapter::opnDb();
if($rs1 = dbAdapter::exeQuery($qry1)){
	if(mysql_num_rows($rs1) >= 1){
		$row = mysql_fetch_array($rs1, MYSQL_ASSOC);
		//$workhost = ($row['fk_app_k_name'] == runstat::$host ? '' : runstat::$host);
		//$ulsubs = $ulsubs."<li><a href='".$workhost."/".$row['fk_app_k_name']."'>".$row['fk_app_k_name']."</a></li>";
		while ($row = mysql_fetch_assoc($rs1)) {
		$appname = $row['k_name'];
$ulsubs = $ulsubs."<li><a href=".runstat::$baseurl.'/".$appname."/".$row['k_name']."'>".$row['k_name']."</a></li>"; 
		}
		
	}
}else{echo mysql_error();}
?>
<html>
<body>
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
</body>
</html>
<?php }else{ echo "this prints if isTop == true and should never happen because there should not be subscriptions to organizations, an organization can have members and observers Not subscribers - they can also be bookmarked the same way a ppl or org entry bookmarks anything through the bookmark app.."; } ?>
