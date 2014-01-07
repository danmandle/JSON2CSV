<?php 

	
	function arrayToCSV($fields = array(), $delimiter = ',', $enclosure = '"'){
	    $str = '';
	    $escape_char = '\\';
	    $children = array();
	    foreach ($fields as $value) {

	      if(gettype($value) == 'array'){
	    	$children[] = $value;
	    	continue;
	      }

	      if (strpos($value, $delimiter) !== false ||
	          strpos($value, $enclosure) !== false ||
	          strpos($value, "\n") !== false ||
	          strpos($value, "\r") !== false ||
	          strpos($value, "\t") !== false ||
	          strpos($value, ' ') !== false) {
	        $str2 = $enclosure;
	        $escaped = 0;
	        $len = strlen($value);
	        for ($i=0;$i<$len;$i++) {



	          if ($value[$i] == $escape_char) {
	            $escaped = 1;
	          } else if (!$escaped && $value[$i] == $enclosure) {
	            $str2 .= $enclosure;
	          } else {
	            $escaped = 0;
	          }
	          $str2 .= $value[$i];
	        }
	        $str2 .= $enclosure;
	        $str .= $str2.$delimiter;
	      } else {
	      	if(count($value)==0){
	      		
	      		$str .="novalue".$delimiter;
	      	}else{
				$str .= $value.$delimiter;
	      	}        
	      }
	    }

	    foreach ($children as $value) {
	    	
	    	$str .=arrayToCSV($value);
	    }


	   // $str = substr($str,0,-1);
	   
	    return $str;
    }

?>