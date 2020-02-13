<?php 
	require_once("bean/Account.php");	
	require_once("Mapper.php");
	
	class AccountMapper extends Mapper{
	
		function getLedgerName(){
			return "AccountMapper";
		}
	
		function getInsertQuery($account){		
		}
	
		function getUpdateQuery($account){		
		}
		
		function getFindQuery($account){
			return "select * from account ".
	//			"where cabinet='$account->cabinet' and password ='$account->password'";
				"where cabinet='$account->cabinet' ";
		}
	
		function getFindListQUery($dossier){		
		}
	
		function doLoadObject($row){
			$account = new Account($row["cabinet"], $row["password"], $row["contact"]);
			return $account;
		}
		
		function getnomcab($account){
			$query =  "select nom_cab, infirmiere from account WHERE cabinet='".$account->cabinet."'";

			$result = $this->findAnyRows($query);
			if($result == false) return false;
			$rowsList = "";
			$count = 0;
			$row = mysql_fetch_array($result, MYSQL_ASSOC);

			return $row;
		}


		/**
		 * récupération des infos cabinet dans la table account avec l'identifiant du cabinet
		 * @param  string $cabinet identifiant du cabinet
		 * @return [type]          [description]
		 */
		function getFullInfosByCab($cabinet){
			$query =  "select * from account WHERE cabinet='".$cabinet."'";
			$result = mysql_query($query);
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			return $row;
		}

		/**
		 * load tous les cabinets avec toutes les infos
		 * @param  [type] $FiltreLogiciel [description]
		 * @param  [type] $filtreLocalisation [description]
		 * @return [type]          [description]
		 */
		function listeAllCabs($filtreLogiciel = false, $filtreLocalisation = false,$order = false){
			if($filtreLogiciel){
				$condLogiciel = " AND logiciel = '$filtreLogiciel' ";
			}
			
			if($filtreLocalisation!='()' && $filtreLocalisation){
				/* pierre : 13/09/17 : modificarion recheche planning pour prise en compte dom-tom = départemens sur 3 chiffres */
				if(strpos($filtreLocalisation, ",") === FALSE) {
					$str_search = substr($filtreLocalisation, 1, strlen($filtreLocalisation) -2);
					$condLocalisation = " AND code_postal LIKE '".$str_search."%'";
				}
				else {
					$str_search = substr($filtreLocalisation, 1, strlen($filtreLocalisation) -2);
					$temp = explode(',', $str_search);
					$condLocalisation = " AND (";
					for($i = 0; $i < count($temp); $i++) {
						$condLocalisation .= "code_postal LIKE '".$temp[$i]."%' OR ";
					}
					$condLocalisation = substr($condLocalisation, 0, strlen($condLocalisation) - 3) . ")";
				}
				//echo "@@" . $condLocalisation . "@@";
				/* fin pierre 13/09/17 */
				
				//$condLocalisation = " AND SUBSTR(code_postal,1,2) IN $filtreLocalisation ";
			}

			if($order){
				$condOrder = " order by $order ";
			}
			else{
				$condOrder =" order by nom_complet ";
			}

			$query =  "select * from account WHERE 1 $condLogiciel $condLocalisation $condOrder ";
			$result = mysql_query($query);
			#echo $query;
			while($row = mysql_fetch_assoc($result)){
				$rowsList[] = $row;
			}
			#var_dump(count($rowsList));
			return $rowsList;
		}

		/**
		 * listing des logiciels de la table account pour faire un menu déroulant select...
		 * @return [type] [description]
		 */
		function listeDistinctLogiciels(){
			$query =  "select distinct(logiciel) from account WHERE logiciel!='' order by logiciel";
			$result = mysql_query($query);

			while($row = mysql_fetch_assoc($result)){
				$rowsList[] = $row;
			}
			return $rowsList;
		}
		
		

	}
?>
