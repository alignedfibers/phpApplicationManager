<?php
if(isset($_POST['submit']) && isset($_POST['newname']) && isset($_POST['type']))
{
echo 'after if POST is set';
	$isTop = (isset($_POST['istop']) ? 1 : 0);
	$name = $_POST['newname'];
	//$isTop = $_POST['istop'];
echo $isTop;
	$lockKey = md5(uniqid(microtime()));
	dbAdapter::opnDb();
	$qry = "INSERT INTO ratiatable (k_name,lock_key,type,is_top) VALUES('".$name."','".$lockKey."','".$_POST['type']."',".$isTop.")";
	if(dbAdapter::exeQuery($qry)){
	echo "INSERT SUCCESS"; echo $lockKey;
	
	}else{ echo "insertfailed";}
    //echo "User Has submitted the form and entered this name : <b> $name </b>";
    //echo "<br>You can use the following form again to enter a new name.";
}

/*$ts = "This is my test $ tring !@##$%^&^&*()_-+=.,/;'";
if(valid::alphanum('xyz')){
echo "true";}else{ echo "false";}

echo $is_val."<br/>";
echo $ts."<br/>";*/
?>

<html>
<form action="<?php htmlentities($_SERVER['PHP_SELF']) ?>" method="post">
<label for="istop">Is a host namespace: </label>
<input type="checkbox" name="istop" value="true" /><br/>
<label for="newname">Unique Name: </label>
<input type="text" name="newname" value="" size="20" /><br/>
<label for="type">Type: </label>
<select name='type'>
<option value="sapp">system application</option>
<option value="papp">profile application</option>
<option value="oapp">organization application</option>
<option value="org">organization</option>
<option value="pro">profile</option>
<option value="sorg">system organization</option>
</select><<<< pretty much a default root template<br/>
<input name="submit" type="submit" value="addname" style="border:1px solid #999999; background-color:#CCCCCC; font-size:10px"/>
</form>
</html>

