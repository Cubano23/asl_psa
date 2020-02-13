<?php
  //$baseLog=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
//error_log("antoine Logger ------------------ ".$baseLog, 0);
//error_log("antoine Logger ------------------ ".$_SERVER['PHP_SELF'], 0);
//require_once("$baseLog/WebService/LogAccess.php");
require_once("LogAccess.php");

//require_once("/home/informed/www/informed79/WebService/LogAccess.php");
//error_log("antoine Logger ------------------  require 1 done", 0);

//  require_once("$baseLog/WebService/GetUserId.php");
require_once("GetUserId.php");

//require_once("/home/informed/www/informed79/WebService/GetUserId.php");
//error_log("antoine Logger ------------------  require 2 done", 0);

  $answerLog="OK";	
	$authLog = GetUserId( $answerLog);
  $UserIDLog = $authLog->Authentifier;
//error_log("antoine Logger ------------------  User ID Logged", 0);

?>
