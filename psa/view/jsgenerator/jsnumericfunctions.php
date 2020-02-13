<?php
function isPositiveNumeric(){?>
	function  isPositiveNumeric( strValue ) {	
		var strTemp = strValue;
		strTemp = trimAll(strTemp);
		// return if value is empty
		if(strTemp.length == 0) return false;
		//check for positive numeric characters
		var objRegExp  =  /(\d\d*\.\d*$)|(\d\d*$)|(\.\d\d*$)/;
		return objRegExp.test(strTemp);
	}
	<?php } ?>
	
	