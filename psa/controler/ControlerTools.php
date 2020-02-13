<?php 
	require_once("TemplateConfig.php");

	define("REQUEST_MESSAGE","REQUEST_MESSAGE");
	
	
	function resetRequest(){
		$_REQUEST = array();
		$_POST = array();
		$_GET = array();
	}
	
	function forward($url,$message=NULL,$resetRequest=false){
		if($resetRequest) resetRequest();		
		if(is_null($message) && array_key_exists("REQUEST_MESSAGE",$_REQUEST)) $message = $_REQUEST["REQUEST_MESSAGE"];
		if(is_null($url)) exit;
		if(is_a($url,"ControlerParams")) {
			global $param;
			$param = $url;			
			if($param->resetRequest == true) resetRequest();
			$_REQUEST["REQUEST_MESSAGE"] = $message;
			if(!is_null($param->message)) $_REQUEST["REQUEST_MESSAGE"] = $param->message;
			require_once($param->controler.".php");
			$forwardControler = new $param->controler();
			$forwardControler->start();
			exit;
		}
		else {
			$_REQUEST["REQUEST_MESSAGE"] = $message;			
			$template = getTemplate($url);
			if($template == false)	include($url);
			else $template->display();
			exit;
		}

	}

?>
