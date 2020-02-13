<?php 
	function getDoubleArrayElement($array,$index,$key){
		$subArray = $array[$index];
		return $subArray[$key];		
	}
	
	function walkDoubleArray($array){
		foreach($array as $key=>$val){
			echo("$key   :");			
			print_r($val);
			echo("\n");
		}
	}
	
	// Transform a set into an array 26-09-2013 split => explode
	function setToArray($value){
		return explode(",",$value);
	}
	
	//Transform an array into a set
	function arrayToSet($array){
		$set = "";
		for($i=0;$i<count($array);$i++){
			$set = $set.$array[$i].",";
		}
		$set = substr($set,0,strlen($set)-1);
		return $set;
	}
	
	/**
	* @return Returns the array sorted as required
	* @param $aryData Array containing data to sort
	* @param $strIndex Name of column to use as an index
	* @param $strSortBy Column to sort the array by
	* @param $strSortType String containing either asc or desc [default to asc]
	* @desc Naturally sorts an array using by the column $strSortBy
	*/
	function array_natsort($aryData, $strIndex, $strSortBy, $strSortType=false){
		// if the parameters are invalid
		if (!is_array($aryData) || !$strIndex || !$strSortBy)
			// return the array
			return $aryData;

		// create our temporary arrays
		$arySort = $aryResult = array();

		// loop through the array
		foreach ($aryData as $aryRow)
		{	// set up the value in the array
			$arySort[$aryRow[$strIndex]] = $aryRow[$strSortBy];
		}
		
			
		
			// if the sort type is descending
			if ($strSortType=="desc"){
				// reverse the array
				arsort($arySort);
			}
			else{
				// apply the natural sort
				natsort($arySort);
			}
				

		// loop through the sorted and original data
		foreach ($arySort as $arySortKey => $arySorted)
			foreach ($aryData as $aryOriginal)
			// if the key matches
			if (($aryOriginal[$strIndex]==$arySortKey) && (strlen($aryOriginal[$strIndex])==strlen($arySortKey)))
			{// add it to the output array
				array_push($aryResult, $aryOriginal);
			}


		// return the return
		return $aryResult;
	}

	function arrayOfobjects_natsort($aryData, $propIndex, $propSortBy, $strSortType=false){
		// if the parameters are invalid
		if (!is_array($aryData) || !$propIndex || !$propSortBy)
		// return the array
		return $aryData;
		
		// create our temporary arrays
		$arySort = $aryResult = array();
		
		// loop through the array
		foreach ($aryData as $object){
			// set up the value in the array						
			$arySort[$object->$propIndex] = $object->$propSortBy;
		}
		

		// if the sort type is descending
			if ($strSortType=="desc"){
				// reverse the array
				arsort($arySort);
			}
			else{
				// apply the natural sort
				natsort($arySort);
			}
		
		// loop through the sorted and original data
		foreach ($arySort as $arySortKey => $arySorted)
			foreach ($aryData as $objOriginal)
			// if the key matches
				if (($objOriginal->$propIndex == $arySortKey) && (strlen($objOriginal->$propIndex)==strlen($arySortKey)))
				// add it to the output array
					array_push($aryResult, $objOriginal);
					
		// return the return
		return $aryResult;
	}


?>
