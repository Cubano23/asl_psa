
<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/Frais.php");
	
	class FraisMapper extends SelfManagedMapper{
	
		function getForeignKey(){
			return "";
		}
	
		function getKeysMap(){
			return array("id"=>"id");
		}
		
		function getTableName(){
			return "frais";
		}
	
		function getLedgerName(){
			return "fraisMapper";
		}
	
		function getObject(){
			return new Frais();
		}
		
		function getFindListQuery($object){
			$propertiesArray = get_object_vars($object);
			if(is_null($propertiesArray)) return false;
			return "select * from ".$this->getTableName()." where id=".$propertiesArray["id"];
		}
		
		/**
		 * Recherche du nom de l'infirmière
		 * normalement plus utlisé en mai 2015 car on recupere le nom de l'infirmiere dans la session/habilitations
		 */
		
	 	function getInfirmiere($account){
			// print_r($account);die;
			$query =  "select infirmiere from account where cabinet='$account->cabinet'";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			$count = 0;
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			// print_r($row);die;
			$infirmiere=$row["infirmiere"];
			return $infirmiere;
		}


	}
?>
