<?php 

	$monthsArray=array("01"=>"Janvier","02"=>"Février","03"=>"Mars","04"=>"Avril","05"=>"Mai","06"=>"Juin","07"=>"Juillet","08"=>"Août","09"=>"Septembre","10"=>"Octobre","11"=>"Novembre","12"=>"Décembre");
	
	function dateToMysqlDate($date,$account=""){
		if(is_null($date)) return NULL;
		if($date == "") return NULL;
		if(substr_count($date,"/")!=2) return NULL;
		list($day,$month,$year) = explode("/",$date);			
		$newDate = $year."-".$month."-".$day;
		return $newDate;
	}
	
	function mysqlDateTodate($date,$account=""){
		if(is_null($date)) return NULL;
		if($date=="0000-00-00") return NULL;
		if($date=="") return NULL;		
//EA03-10-2013		list($year,$month,$day) = explode("-",$date);	
		$itemList = explode("-",$date);
		$nb = count($itemList);
/*		if($nb!=3)
		{

			error_log("count:".strval($nb)."\n", 3, "/home/asalee/my-dates.log");
			error_log("year:".$year." month:".$month." day:".$day. "\n", 3, "/home/asalee/my-dates.log");

		}*/
		$month="";
		$day="";
		$year="";
		switch($nb)
		{
			case 2:
				list($year,$month) =	$itemList;	
				break;
			case 1:
				$year =	$itemList[0];	
				break;
			case 0:
				break;
			default:
				list($year,$month,$day) =	$itemList;	
				break;

		}

		$newDate = "$day/$month/$year";

//		error_log("newdate2:".$newDate."\n", 3, "/home/asalee/my-dates.log");

		return $newDate;
	}
	
	function compare($date1,$date2){
		if($date1 == $date2) return 0;		
		list($day1,$month1,$year1) = explode("/",$date1);
		list($day2,$month2,$year2) = explode("/",$date2);		
		$year = $year1 - $year2;
		if($year != 0) return $year;
		$month = $month1 - $month2;
		if($month != 0) return $month;
		$day = $day1 - $day2;
		if($day != 0) return $day;
		return 0;		
	}
	
	function getLowerDate($dateArray){
		if(count($dateArray) == 0) return false;
		$date = $dateArray[0];
		if(count($dateArray) == 1){
			if(is_null($date)) return false;
			if($date == "") return false;
		 	return $date;
		 }
		for($i=0;$i<count($dateArray);$i++){
			if(is_null($dateArray[$i])) continue;
			if($dateArray[$i] == "") continue;
			if(compare($date,$dateArray[$i])>0)
				$date = $dateArray[1];;
		}
		return $date;
	}
	
	function increaseDateBy($date,$day=0,$month=0,$year=0){
		if(is_null($date)) return "";
		if($date == "") return "";
		list($dayn,$monthn,$yearn) = explode("/",$date);
		$datenum = mktime(0,0,0,$monthn+$month,$dayn+$day,$yearn+$year);
		return date("d/m/Y",$datenum);
	}
	

	function getAge($date){
		list($D,$M,$Y) = explode("/",$date);
		$age = date('Y') - $Y;
		return $age;
	}
	
	function getDiffInMonth($date1,$date2){
		
		if(substr_count($date1,"/")!=2) return 0;
		if(substr_count($date2,"/")!=2) return 0;
		
		list($D1,$M1,$Y1) = explode("/",$date1); 
		list($D2,$M2,$Y2) = explode("/",$date2); 
		
		// compute the difference
		$dYears  = $Y1 - $Y2;
		$dMonths = $M1 - $M2;
		$dDays = $D1 - $D2;
		
		$diff = 365 *($dYears);
		$diff = $diff + (30 * $dMonths);
		$diff = $diff + $dDays;
		
		return round($diff/30);
	}
	
	function getDayAndMonthName($date){
		global $monthsArray;
		if(is_null($date)) return;
		if(substr_count($date,"/")!=2) return 0;
		list($day,$month,$year) = explode("/",$date);
		return $day." ".$monthsArray[$month];
	}
	
	function isValidDate($date){
		if(!isDate($date)) return false;
		$currentDate = date("d/m/Y");
		if(compare($date,$currentDate) > 0) return false;
		return true;
	}

	function isValidDateFuture($date, $date_depart){
		if(!isDate($date)) return false;
		if(isDate($date_depart))
		{
			if(compare($date,$date_depart) > 0) return true;
		}
		return false;
	}


	function isDate($date){
		if(is_null($date)) return false;
		if(substr_count($date,"/")!=2) return false;
		list($day,$month,$year) = explode("/",$date);
		return  checkdate ($month,$day,$year);		
	}
?>
