<?php
if(($_FILES["file"]["type"] != NULL) || ($_POST['json'] != NULL)){

	require_once('json2csv.class.php');
	$JSON2CSV = new JSON2CSVutil;

	if($_FILES["file"]["type"] != NULL){
		$JSON2CSV->JSONfromFile($_FILES["file"]["tmp_name"]);
	}
	elseif($_POST['json'] != NULL){
		$JSON2CSV->readJSON($_POST['json']);
	}
	$JSON2CSV->browserDL("JSON2.CSV");
}
else{
	?>

<html>
<body>

<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
<label for="json">JSON data:</label>
<textarea name="json" cols="150" rows="40">

</textarea>
<br />
<label for="file">Filename:</label>
<input type="file" name="file" id="file" /> 
<br />
<input type="submit" name="submit" value="Convert!" />
</form>

</body>
</html>
<?php
}
?>