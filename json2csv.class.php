<?php

class JSON2CSVutil{
	
	public $dataArray;
	public $isNested = FALSE;

	function readJSON($JSONdata){
		$this->dataArray = json_decode($JSONdata,1);
		$this->prependColumnNames();
		return $this->dataArray;
	}

	function JSONfromFile($file){
		$this->dataArray = json_decode(file_get_contents($file),1);
		$this->prependColumnNames();
		return $this->dataArray;
	}

	private function prependColumnNames(){
		foreach(array_keys($this->dataArray[0]) as $key){
		    $keys[0][$key] = $key;
		}
		$this->dataArray = array_merge($keys, $this->dataArray);
	}

	function save2CSV($file){
		if($this->isItNested() || !is_array($this->dataArray)){
			echo "JSON is either invalid or has nested elements.";
		}
		else{
			$fileIO = fopen($file, 'w+');
			foreach ($this->dataArray as $fields) {
			    fputcsv($fileIO, $fields);
			}
			fclose($fileIO);
		}
	}

	function flatten2CSV($file){
		$fileIO = fopen($file, 'w+');
		foreach ($this->dataArray as $items) {
			$flatData = array();
			$fields = new RecursiveIteratorIterator(new RecursiveArrayIterator($items));
			foreach($fields as $value) {
  				array_push($flatData, $value);
			}
			fputcsv($fileIO, $flatData, ";", '"');
		}
		fclose($fileIO);
	}

	function browserDL($CSVname){
		if($this->isItNested() || !is_array($this->dataArray)){
			echo "<h1>JSON is either invalid or has nested elements.</h1>";
		}
		else{
			header("Content-Type: text/csv; charset=utf-8");
			header("Content-Disposition: attachment; filename=$CSVname");

			$output = fopen('php://output', 'w');

			foreach ($this->dataArray as $fields) {
			    fputcsv($output, $fields);
			}
		}
	}

	function flattenDL($CSVname){
		header("Content-Type: text/csv; charset=utf-8");
		header("Content-Disposition: attachment; filename=$CSVname");
		$output = fopen("php://output", "w");
		foreach ($this->dataArray as $items) {
			$flatData = array();
			$fields = new RecursiveIteratorIterator(new RecursiveArrayIterator($items));
			foreach($fields as $value) {
  				array_push($flatData, $value);
			}
			fputcsv($output, $flatData, ";", '"');
		}
	}

	private function isItNested(){
		foreach($this->dataArray as $data){
			if(is_array($data)){
				$isNested = TRUE;
				break 1;
			}	
		}
		return $this->isNested;
	}

	function savejson2csv($JSONdata, $file){
		$this->readJSON($JSONdata);
		$this->save2CSV($file);
	}

	function flattenjson2csv($JSONdata, $file){
		$this->readJSON($JSONdata);
		$this->flatten2CSV($file);
	}

	function savejsonFile2csv($file, $destFile){
		$this->JSONfromFile($file);
		$this->save2CSV($destFile);
	}

	function flattenjsonFile2csv($file, $destFile){
		$this->JSONfromFile($file);
		$this->flatten2CSV($destFile);
	}

}

?>