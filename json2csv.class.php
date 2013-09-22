<?php

require_once('arrayToCSV.php');

class JSON2CSVutil{
	
	public $dataArray;
	public $isNested = FALSE;

	function readJSON($JSONdata){

		$this->dataArray = json_decode($JSONdata,1);
		$this->prependColumnNames();
		return $this->dataArray;
	}

	private function isNested($array){
		foreach($array as $data){
			if(is_array($data)){
				return TRUE;
			}	
		}
		return FALSE;
	}


	private function getAllKeys($array)
	{
	    $result = array();
	    $children = array();
	    foreach($array as $sub=>$value) {
	    	if(gettype($value)=='array'){	  
	    		$children[] = $value;
	    		//$result = array_merge($result,$this->getAllKeys($value));
	    	}else{
	    		$result [] = $sub;
	    	}	        
	    }

	    foreach ($children as $key => $value) {
	    	$result = array_merge($result,$this->getAllKeys($value));
	    }
	    return $result;
	}

	private function prependColumnNames(){
		$keys = array();
		foreach ($this->dataArray as $key => $value) {	
			
			if($this->isNested($value)){
				$keys[] = $this->getAllKeys($this->dataArray[$key]);
				break;
			}
		}

		if(count($keys)==0){
			$keys[] = $this->getAllKeys($this->dataArray[0]);
		}

		$this->dataArray = array_merge($keys, $this->dataArray);
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
			  fwrite($output,arrayToCSV($fields)."\n");
			}
		}
	}


	function JSONfromFile($file){
		$this->dataArray = json_decode(file_get_contents($file),1);
		$this->prependColumnNames();
		return $this->dataArray;
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

	function savejsonFile2csv($file, $destFile){
		$this->JSONfromFile($file);
		$this->save2CSV($destFile);
	}

}

?>