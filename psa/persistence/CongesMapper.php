
<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/Conges.php");
	
	class CongesMapper extends SelfManagedMapper{
	
		function getForeignKey(){
			return "";
		}
	
		function getKeysMap(){
			return array("id"=>"id");
		}
		
		function getTableName(){
			return "conges";
		}
	
		function getLedgerName(){
			return "CongesMapper";
		}
	
		function getObject(){
			return new Conges();
		}
		
		function getFindListQuery($object){
			$propertiesArray = get_object_vars($object);
			if(is_null($propertiesArray)) return false;
			return "select * from ".$this->getTableName()." where id=".$propertiesArray["id"];
		}
		
		/**
		 * normalement no need car on va chercher le nom de l'infirmiere dans la session
		 * @param  [type] $account [description]
		 * @return [type]          [description]
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
			$infirmiere=explode(" ", $infirmiere, 2);
			
			$conges=new Conges();
			$conges->nom=$infirmiere[1];
			$conges->prenom=$infirmiere[0];
			return $conges;
		}

		//Recherche du mail de l'infirmière
		//normalement pas besoin car on récupere dans la session depuis mai 2015
	 	function getEmail($account){
			// print_r($account);die;
			$query =  "select courriel from account where cabinet='$account->cabinet'";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			$count = 0;
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			// print_r($row);die;
			$email=$row["courriel"];

			return $email;
		}


	}
?>
