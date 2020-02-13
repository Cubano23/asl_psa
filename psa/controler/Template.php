<?php
	class Template{
		
		var $templateURL;
		var $templateParamsMap;
		
		function Template($templateURL,$templateParamsMap){
			$this->templateURL = $templateURL;
			$this->templateParamsMap = $templateParamsMap;
		}
		
		function display(){					
			global $map;
			$map = $this->templateParamsMap;
			include($this->templateURL);
		}
		
	}
?>