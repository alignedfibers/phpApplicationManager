<?php

if(isset($_POST['submit']) && isset($_POST['from']) && isset($_POST['to']))
{
	$suber = $_POST['from'];
	$subee = $_POST['to'];
	$suberKey;
	$subeeKey;
//echo $subee;
$selQryFrom = "SELECT lock_key FROM ratiatable WHERE k_name ='".$suber."'";
$selQryTo = "SELECT lock_key FROM ratiatable WHERE k_name ='".$subee."'";
	dbAdapter::opnDb();
	if($rs1 = dbAdapter::exeQuery($selQryFrom)){
		if(mysql_num_rows($rs1) == 1){
		$row = mysql_fetch_array($rs1, MYSQL_ASSOC);
		$suberKey = $row['lock_key'];
		//echo $suberKey;	
		}else{echo "integrity issue";}
	
	}else{ echo "FAILED SELECT";}

	dbAdapter::opnDb();
	if($rs2 = dbAdapter::exeQuery($selQryTo)){
		if(mysql_num_rows($rs2) == 1){
		$row = mysql_fetch_array($rs2, MYSQL_ASSOC);
		$subeeKey = $row['lock_key'];
		//echo $subeeKey;
		echo "you have a row <br/>";
		}else{echo "integrity issue";}
	
	}else{ echo "FAILED SELECT";}
    //echo "User Has submitted the form and entered this name : <b> $name </b>";
    //echo "<br>You can use the following form again to enter a new name.";
echo $suberKey."<br/>";
echo $subeeKey."<br/>";
echo md5($suberKey.$subeeKey)."<br/>";
echo "THIS SHOULD DISPLAYz2";
$access_key = md5($suberKey.$subeeKey);
$inSubQry = "INSERT INTO subscriptions (fk_host_k_name,fk_app_k_name,access_key) VALUES('".$_POST['from']."','".$_POST['to']."','".$access_key."')";
echo $inSubQry;
	dbAdapter::opnDb();
	if(dbAdapter::exeQuery($inSubQry)){
		echo "insert success";	
	}else{ echo mysql_error()."insert failed";}


}

$posSubSel = "SELECT k_name FROM ratiatable";

dbAdapter::opnDb();
if($rs = dbAdapter::exeQuery($posSubSel)){
	//print_r($rs);
	//$row = mysql_fetch_array($rs, MYSQL_ASSOC);
	//print_r($row);
	$optionsbox = "";
	$count = 0;
	while ($row = mysql_fetch_assoc($rs)) 
	{
    		$rows[] = $row['k_name'];
	$optionsbox = $optionsbox."<option value='".$row['k_name']."'>".$row['k_name']."</option>\n"; 
	$count++;
	}

	$optionsbox = $optionsbox."</select>";
	//print_r($row);
	//print_r($rows);
	//echo $optionsbox;
}
?>
<html>
<form action="<?php htmlentities($_SERVER['PHP_SELF']) ?>" method="post">

<?php echo "<select name='from'>".$optionsbox; ?>
<?php echo "<select name='to'>".$optionsbox; ?>
<input name="submit" type="submit" value="addsubscription" style="border:1px solid #999999; background-color:#CCCCCC; font-size:10px"/>

</form>
</html>





