<?php

/**
 * Created by Codelobster
 * User: Gisgo
 * Date: 22-11-2018
 * Time: 14:40
 */ 
	require_once("SelfManagedMapper.php");
	require_once("bean/Rib.php");
	
	class RibMapper extends SelfManagedMapper{
	
		function getForeignKey(){
			return "";
		}
	
		function getKeysMap(){
			return array("id"=>"id");
		}
		
		function getTableName(){
			return "rib";
		}
	
		function getLedgerName(){
			return "ribMapper";
		}
	
		function getObject(){
			return new Rib();
		}
		
		function getFindListQuery($object){
			$propertiesArray = get_object_vars($object);
			if(is_null($propertiesArray)) return false;
			return "select * from ".$this->getTableName()." where id=".$propertiesArray["id"];
		}
		
		
	 	


	}
?>
