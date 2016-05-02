<?php
echo "time to map add"; 
echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";

if(isset($_POST['submit']) && isset($_POST['accessor']) && isset($_POST['reqpath']) && isset($_POST['actualpath']))
{
	$isTop = (isset($_POST['istop']) ? 1 : 0);
	$isChannel = (isset($_POST['ischannel']) ? 1 : 0);
	$needsHandler = (isset($_POST['needshandler']) ? 1 : 0);

	$qry2 = "INSERT INTO maps (ratia_name,request,resource,date_added,date_updated,is_channeled,needs_handler,is_top) VALUES('".$_POST['accessor']."','".$_POST['reqpath']."','".$_POST['actualpath']."',NOW(),NOW(),".$isChannel.",".$needsHandler.",".$isTop.")";
	dbAdapter::opnDb();
	if(dbAdapter::exeQuery($qry2)){
		echo "map insert success";
	}else{echo mysql_error();}


}else{echo "not all is set";}

$qry1 = "SELECT k_name, type FROM ratiatable";
dbAdapter::opnDb();
if($rs1 = dbAdapter::exeQuery($qry1)){
	$optionsbox = "";
	$count = 0;
	while ($row = mysql_fetch_assoc($rs1)) 
	{
    		$rows[] = $row['k_name'];
$optionsbox = $optionsbox."<option value='".$row['k_name']."'>".$row['k_name']." ".$row['type']."</option>"; 
	$count++;
	}
}else{ echo mysql_error()."badqry";}
?>
<html>
<body>
<form action="<?php htmlentities($_SERVER['PHP_SELF']) ?>" method="post">
<label for="istop">Is a host namespace:</label>
<input type="checkbox" name="istop" value="true" /><br/>
<label for="ischannel">Is a channel: </label>
<input type="checkbox" name="ischannel" value="true" /><br/>
<label for="needshandler">Needs Handler: </label>
<input type="checkbox" name="ischannel" value="true" /><br/>
<label for="accessor">accessor: </label>
<?php echo "<select name='accessor'>".$optionsbox."</select>"; ?><br/>
<label for="reqpath">Requested Path : ("without host or accessor just /"): </label><br/>
<input type="text" name="reqpath" value="" size="100" /><br/>
<label for="actualpath">Actual Path:</label><br/>
<input type="text" name="actualpath" value="" size="100" /><br/>
<input name="submit" type="submit" value="addmap" style="border:1px solid #999999; background-color:#CCCCCC; font-size:10px"/>
</form>
<body>
</html>
