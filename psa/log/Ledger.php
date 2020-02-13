<?php 

require_once ("Config.php");

/* Ledging levels */
define("N",0); // No error
define("I",1); // Information
define("W",2); // Warning
define("E",3); // Error

/* Ledging labels */
$LOG_LABELS = array(0=>"NO LOG",I=>"INFO",W=>"WARN",E=>"ERR");

class Ledger{
	
	var $layerName;
	var $moduleName;
	var $logLevel;
	
	var $config;

	function Ledger($layerName,$moduleName,$logLevel){
        $config = new Config();
		//$conffile="/home/informed/www/_files/asalee.ini";
		$conffile= $config->files_path ."/asalee.ini";
    $conf =  parse_ini_file ( $conffile, true );
		$this->layerName = $config->files_path . "/log" . "/".$layerName;
		$this->moduleName = $moduleName;
		$this->logLevel = $logLevel;
	}
	
	function writeEntry($str){
		//open file. leave on error
		$file = fopen($this->layerName.".log","a");
		if($file == false) return;
		fwrite($file,$str."\n");
		fclose($file);	
	}
	
	function write($level,$operationName,$message){	
		global $LOG_LABELS;
		
		if($level <= 0) return; // Nothing to log if level == 0
		if($level > 3) return;  // Log level is out of range
		if($level > $this->logLevel) return; // Level is above log level
		date_default_timezone_set('Europe/Paris' );
		// Build message
		$logMessage= date("r")." ".$LOG_LABELS[$level]." ".$this->moduleName." ".$operationName." ".$message;
		
		//open file. leave on error
		$file = fopen($this->layerName.".log","a"); # or die("impossible d'ouvrir ".realpath($this->layerName.".log")); # GIGIGI
		if($file == false) return;
		
		//write message and close file
		fwrite($file,$logMessage."\n");
		fclose($file);		
	}
	
	function writeArray($level,$operationName,$message,$array){	
		global $LOG_LABELS;
		
		if($level <= 0) return; // Nothing to log if level == 0
		if($level > 3) return;  // Log level is out of range
		if($level > $this->logLevel) return; // Level is above log level
		date_default_timezone_set('Europe/Paris');
		// Build message
		$logMessage= date("r")." ".$LOG_LABELS[$level]." ".$this->moduleName." ".$operationName." ".$message;
		
		//open file. leave on error
		$file = fopen($this->layerName.".log","a");
		if($file == false) return;
		
		//write message and close file
		fwrite($file,$logMessage." ");

		$str = "";
		foreach ($array as $name => $value){
			$str = $str." ".$name."=".$value;
		}
		fwrite($file,$str."\n");
		fclose($file);		
	}

	function writeArrayOfObjects($level,$operationName,$message,$arrayOfObjects){	
		global $LOG_LABELS;
		
		if($level <= 0) return; // Nothing to log if level == 0
		if($level > 3) return;  // Log level is out of range
		if($level > $this->logLevel) return; // Level is above log level
		date_default_timezone_set('Europe/Paris');
		// Build message
		$str= date("r")." ".$LOG_LABELS[$level]." ".$this->moduleName." ".$operationName." ".$message;
		if(!isset($arrayOfObjects)) $str = $str."NULL";
		else
		foreach ($arrayOfObjects as $name => $value){
//EA 24-04-2014      
    $zclass="????";
     if(is_string($value))
        $zclass = "string"; 
    if(is_object($value))
      $zclass = get_class($value);
// fin des modifs avec $zclass       
    	$str = $str." ".$name." is a ".$zclass."{";
			if($value == NULL or !isset($value) or !is_object($value)) $str = $str."NULL";
			else
			foreach ($value as $attName => $attValue){
				$str = $str."$attName = $attValue ";
			}
			$str = $str."} ";
		}
		$this->writeEntry($str);
	}
}

?>
