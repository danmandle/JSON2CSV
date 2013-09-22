<?php

if((isset($_FILES["file"]["type"]) && $_FILES["file"]["type"] != NULL)
	|| (isset($_POST['json']) && $_POST['json'] != NULL)
	|| (isset($argv[1]))){

	require_once('json2csv.class.php');
	$JSON2CSV = new JSON2CSVutil;

	if(isset($argv[1])){
		$shortopts = "f::";  // Required value
		$longopts  = array("file::","dest::");
		$arguments = getopt($shortopts, $longopts);

		if(isset($arguments["dest"])){
			$filepath = $arguments["dest"];
		}
		else{
			$filepath = "JSON2.CSV";
		}

		$JSON2CSV->savejsonFile2csv($arguments["file"], $filepath);
	}
	elseif(($_FILES["file"]["type"]) != NULL){
		$JSON2CSV->JSONfromFile($_FILES["file"]["tmp_name"]);
		$JSON2CSV->browserDL(str_replace("json", "csv", $_FILES["file"]["name"]));
	}
	elseif($_POST['json'] != NULL){
		$JSON2CSV->readJSON($_POST['json']);
		$JSON2CSV->browserDL("JSON.CSV");
	}
}
else{
	?>

<html>
<body>

<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
<label for="json">JSON data:</label>
<textarea name="json" cols="70" rows="20">

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