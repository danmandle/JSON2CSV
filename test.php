<?php 

	ini_set('memory_limit', '-1');


	require_once('json2csv.class.php');
	$JSON2CSV = new JSON2CSVutil;


	$file= 'test.json';

	

	$JSON2CSV->dataArray = json_decode(file_get_contents($file),1);
	$JSON2CSV->prependColumnNames();
	//return $this->dataArray;

	$CSVname ="babji.csv";

	if($JSON2CSV->isItNested() || !is_array($JSON2CSV->dataArray)){
		echo "<h1>JSON is either invalid or you encountered an edge case I missed. Please let me know at arelangi@gmail.com<h1>";
	}
	else{
		header("Content-Type: text/csv; charset=utf-8");
		header("Content-Disposition: attachment; filename=$CSVname");

		$output = fopen('php://output', 'w');

		foreach ($JSON2CSV->dataArray as $fields) {
		  fwrite($output,arrayToCSV($fields)."\n");
		}
	}

?>