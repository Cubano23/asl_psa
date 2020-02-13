
<?php
	require_once("SelfManagedMapper.php");
	require_once("bean/Biologie.php");

	class BiologieMapper extends SelfManagedMapper{

		function getForeignKey(){
			return "id";
		}

		function getKeysMap(){
			return array("id"=>"id","numero"=>"numero");
		}

		function getTableName(){
			return "liste_exam";
		}

		function getLedgerName(){
			return "BiologieMapper";
		}

		function getObject(){
			return new Biologie();
		}

		function getFindListQuery($object){
			$propertiesArray = get_object_vars($object);
			if(is_null($propertiesArray)) return false;
			return "select * from ".$this->getTableName()." where id=".$propertiesArray["id"];
		}

	 	function getObjectsByCabinet($cabinet){
			$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
					$this->getTableName().".".$this->getForeignKey()." = dossier.id";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			$rowsList = "";
			$count = 0;
			while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
				$rowsList[$count] = $row;
				$count = $count + 1;
			}

			return $rowsList;
		}

		//Retrouver le dernier exam réalisé avant une date donnée pour un dossier
		//pour un type d'exam
	 	function findExam($dsuivi, $id, $type){
			$query =  "select date_format(date_exam, '%d/%m/%Y') as date_exam, ".
					"resultat1, resultat2, numero, date_exam as dexam ".
				    "from ".$this->getTableName()." where id='$id' and ".
					"date_exam<='$dsuivi' and type_exam='$type' ".
					"order by dexam DESC limit 0, 1";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			$rowsList = "";
			$count = 0;
			$row = mysql_fetch_array($result, MYSQL_ASSOC);

			return $row;
		}

		//Recherche si un examen a déjà été saisi à la même date et renvoie les infos correspondantes
	 	function findExamSaisi($Exam){
			$query =  "select date_exam, resultat1, resultat2, numero ".
				    "from ".$this->getTableName()." where id='$Exam->id' and ".
					//"type_exam='$Exam->type_exam' ".
					"date_exam='$Exam->date_exam' and type_exam='$Exam->type_exam' ".
					"order by date_exam DESC limit 0, 1";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			$rowsList = "";
			$count = 0;
			$row = mysql_fetch_array($result, MYSQL_ASSOC);

			return $row;
		}

		function getFindDernierExamQuery($object){
			return "select * from ".$this->getTableName()." where id ='$object->id' ORDER BY `date` DESC limit 0,1";

		}

		function ListeExams($Biologie){
			$liste=array();

			$query =  "select date_exam, resultat1, resultat2 ".
					"from ".$this->getTableName()." ".
					"where id='$Biologie->id' and date_exam>'0000-00-00' ".
					"and type_exam='$Biologie->type_exam' ".
					"group by date_exam ".
					"order by date_exam DESC";
			$result = $this->findAnyRows($query);

      if($result == false) return false;   //EA déplacement du test avant le fetch 22-12-2014

			while(list($date_exam, $resultat1, $resultat2)=mysql_fetch_row($result)){
				$liste[]=array("date_exam"=>$date_exam, "resultat1"=>$resultat1, "resultat2"=>$resultat2);
			}

			
			return $liste;
		}

		function ListeTousExams($dossier){
			$liste=array();

			$query =  "select * ".
					"from ".$this->getTableName()." ".
					"where id='$dossier->id' and date_exam>'0000-00-00' ".
					"GROUP by type_exam, date_exam ".
					"order by type_exam, date_exam";
			$result = $this->findAnyRows($query);

      if($result == false) return false;    //EA déplacement du test avant le fetch 22-12-2014

			while(list($id, $numero, $date_exam, $resultat1, $resultat2)=mysql_fetch_row($result)){
				$liste[]=new Biologie($id, $numero, $date_exam, $resultat1, $resultat2);
			}

			
			return $liste;
		}

		function getExam($dossier, $exam){
			// $liste=array();

			$query =  "select * ".
					"from ".$this->getTableName()." ".
					"where id='$dossier->id' and numero='$exam->numero' ";
			$result = $this->findAnyRows($query);

      if($result == false) return false;    //EA déplacement du test avant le fetch 22-12-2014
      
			list($id, $numero, $date_exam, $resultat1, $resultat2)=mysql_fetch_row($result);
			$retour=new Biologie($id, $numero, $date_exam, $resultat1, $resultat2);


			//if($result == false) return false;
			return $retour;
		}

		function ListeDiastole($id){
			$liste=array();

			$query =  "select date_exam, resultat1 ".
					"from ".$this->getTableName()." ".
					"where id='$id' and date_exam>'0000-00-00' ".
					"and type_exam='diastole' ".
					"group by date_exam ".
					"order by date_exam DESC";
			$result = $this->findAnyRows($query);
      if($result == false) return false;    //EA déplacement du test avant le fetch 22-12-2014
			while(list($date_exam, $resultat1)=mysql_fetch_row($result)){
				$liste[$date_exam]=$resultat1;
			}

			//if($result == false) return false;
			return $liste;
		}

		function getObjectsByDossier($cabinet, $dossier){
			$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
					$this->getTableName().".id = dossier.id AND dossier.numero='$dossier' ".
					"ORDER by date";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			$rowsList = "";
			$count = 0;
			while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
				$rowsList[$count] = $row;
				$count = $count + 1;
			}

			return $rowsList;
		}
	}
?>
