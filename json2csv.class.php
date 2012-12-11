<?php

class JSON2CSVutil{
	
	public $dataArray;

	function readJSON($JSONdata){
		$this->dataArray = json_decode($JSONdata,1);
		return $this->dataArray;
	}

	function JSONfromFile($file){
		$this->dataArray = json_decode(file_get_contents($file),1);
		return $this->dataArray;
	}

	function save2CSV($file){
		$fileIO = fopen($file, 'w+');
		foreach ($this->dataArray as $fields) {
		    fputcsv($fileIO, $fields);
		}
		fclose($fileIO);
	}
	function browserDL($CSVname){
		header("Content-Type: text/csv; charset=utf-8");
		header("Content-Disposition: attachment; filename=$CSVname");

		$output = fopen('php://output', 'w');

		foreach ($this->dataArray as $fields) {
		    fputcsv($output, $fields);
		}
	}

	function savejson2csv($JSONdata, $file){
		$this->readJSON($JSONdata);
		$this->save2CSV($file);
	}

	function savejsonFile2csv($JSONdata, $destFile){
		$this->JSONfromFile($file);
		$this->save2CSV($destFile);
	}

}

?>