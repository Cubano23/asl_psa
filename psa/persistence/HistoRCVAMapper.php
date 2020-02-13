<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/HistoRCVA.php");
	
	class HistoRCVAMapper extends SelfManagedMapper{
	
		function getForeignKey(){
			return "id";
		}
	
		function getKeysMap(){
			return array("id"=>"id","date"=>"date");
		}
		
		function getTableName(){
			return "cardio_vasculaire_depart";
		}
	
		function getLedgerName(){
			return "HistoRCVAMapper";
		}
	
		function getObject(){
			return new HistoRCVA();
		}
		
 		function ListeExams($HistoRCVA, $dossier){
			$liste=array();
			
			if($HistoRCVA->type_exam=="poids"){
				$query =  "select resultat1, date_exam ".
						"from liste_exam ".
						"where id='$dossier->id' and date_exam>'0000-00-00' ".
						"and type_exam='poids' group by date_exam ".
						"order by date_exam DESC";
				$result = $this->findAnyRows($query);
				
				while(list($poids, $dpoids)=mysql_fetch_row($result)){
					$liste[]=array("date"=>$dpoids, "valeur"=>$poids);
				}
			}
			if($HistoRCVA->type_exam=="choltot"){
				$query =  "select chol, dchol ".
						"from ".$this->getTableName()." ".
						"where id='$dossier->id' and dchol>'0000-00-00' group by dchol ".
						"order by dchol DESC";
				$result = $this->findAnyRows($query);
				
				while(list($chol, $dchol)=mysql_fetch_row($result)){
					$liste[]=array("date"=>$dchol, "valeur"=>$chol);
				}
			}
			if($HistoRCVA->type_exam=="HDL"){
				$query =  "select HDL, dHDL ".
						"from ".$this->getTableName()." ".
						"where id='$dossier->id' and dHDL>'0000-00-00' group by dHDL ".
						"order by dHDL DESC";
				$result = $this->findAnyRows($query);
				
				while(list($HDL, $dHDL)=mysql_fetch_row($result)){
					$liste[]=array("date"=>$dHDL, "valeur"=>$HDL);
				}
			}
			if($HistoRCVA->type_exam=="LDL"){
				$query =  "select LDL, dLDL ".
						"from ".$this->getTableName()." ".
						"where id='$dossier->id' and dLDL>'0000-00-00' group by dLDL ".
						"order by dLDL DESC";
				$result = $this->findAnyRows($query);
				
				while(list($LDL, $dLDL)=mysql_fetch_row($result)){
					$liste[]=array("date"=>$dLDL, "valeur"=>$LDL);
				}
			}
			if($HistoRCVA->type_exam=="trigly"){
				$query =  "select triglycerides, dtriglycerides ".
						"from ".$this->getTableName()." ".
						"where id='$dossier->id' and dtriglycerides>'0000-00-00' group by dtriglycerides ".
						"order by dtriglycerides DESC";
				$result = $this->findAnyRows($query);
				
				while(list($triglycerides, $dtriglycerides)=mysql_fetch_row($result)){
					$liste[]=array("date"=>$dtriglycerides, "valeur"=>$triglycerides);
				}
			}
			if($HistoRCVA->type_exam=="gly"){
				$query =  "select glycemie, dgly ".
						"from ".$this->getTableName()." ".
						"where id='$dossier->id' and dgly>'0000-00-00' group by dgly ".
						"order by dgly DESC";
				$result = $this->findAnyRows($query);
				
				while(list($glycemie, $dgly)=mysql_fetch_row($result)){
					$liste[]=array("date"=>$dgly, "valeur"=>$glycemie);
				}
			}
			if($HistoRCVA->type_exam=="creat"){
				$query =  "select Creat, dCreat ".
						"from ".$this->getTableName()." ".
						"where id='$dossier->id' and dCreat>'0000-00-00' group by dCreat ".
						"order by dCreat DESC";
				$result = $this->findAnyRows($query);
				
				while(list($Creat, $dCreat)=mysql_fetch_row($result)){
					$liste[]=array("date"=>$dCreat, "valeur"=>$Creat);
				}
			}
			if($HistoRCVA->type_exam=="kal"){
				$query =  "select kaliemie, dkaliemie ".
						"from ".$this->getTableName()." ".
						"where id='$dossier->id' and dkaliemie>'0000-00-00' group by dkaliemie ".
						"order by dkaliemie DESC";
				$result = $this->findAnyRows($query);
				
				while(list($kaliemie, $dkaliemie)=mysql_fetch_row($result)){
					$liste[]=array("date"=>$dkaliemie, "valeur"=>$kaliemie);
				}
			}
			if($HistoRCVA->type_exam=="prot"){
				$query =  "select proteinurie, dproteinurie ".
						"from ".$this->getTableName()." ".
						"where id='$dossier->id' and dproteinurie>'0000-00-00' group by dproteinurie ".
						"order by dproteinurie DESC";
				$result = $this->findAnyRows($query);
				
				while(list($proteinurie, $dproteinurie)=mysql_fetch_row($result)){
					if($proteinurie==1){
						$proteinurie="Positive";
					}
					else{
						$proteinurie="Négative";
					}
					$liste[]=array("date"=>$dproteinurie, "valeur"=>$proteinurie);
				}
			}
			if($HistoRCVA->type_exam=="hemat"){
				$query =  "select hematurie, dhematurie ".
						"from ".$this->getTableName()." ".
						"where id='$dossier->id' and dhematurie>'0000-00-00' group by dhematurie ".
						"order by dhematurie DESC";
				$result = $this->findAnyRows($query);
				
				while(list($hematurie, $dhematurie)=mysql_fetch_row($result)){
					if($hematurie==1){
						$hematurie="Positive";
					}
					else{
						$hematurie="Négative";
					}
					$liste[]=array("date"=>$dhematurie, "valeur"=>$hematurie);
				}
			}
			if($HistoRCVA->type_exam=="foeil"){
				$query =  "select dFond ".
						"from ".$this->getTableName()." ".
						"where id='$dossier->id' and dFond>'0000-00-00' group by dFond ".
						"order by dFond DESC";
				$result = $this->findAnyRows($query);
				
				while(list($dFond)=mysql_fetch_row($result)){
					$liste[]=array("date"=>$dFond, "valeur"=>"");
				}
			}
			if($HistoRCVA->type_exam=="fcoeur"){
				$query =  "select pouls, dpouls ".
						"from ".$this->getTableName()." ".
						"where id='$dossier->id' and dpouls>'0000-00-00' group by dpouls ".
						"order by dpouls DESC";
				$result = $this->findAnyRows($query);
				
				while(list($pouls, $dpouls)=mysql_fetch_row($result)){
					$liste[]=array("date"=>$dpouls, "valeur"=>$pouls);
				}
			}
 			if($HistoRCVA->type_exam=="hta"){
				$query =  "select resultat1, date_exam ".
						"from liste_exam ".
						"where id='$dossier->id' and date_exam>'0000-00-00' ".
						"and type_exam='systole' group by date_exam ".
						"order by date_exam DESC";
				$result = $this->findAnyRows($query);
				
				while(list($TaSys, $dTA)=mysql_fetch_row($result)){
					$query2 =  "select resultat1 ".
							"from liste_exam ".
							"where id='$dossier->id' and date_exam='$dTA' ".
							"and type_exam='diastole' group by date_exam ".
							"order by date_exam DESC";
					$result2 = $this->findAnyRows($query2);
					list($TaDia)=mysql_fetch_row($result2);
					
					$TA="$TaSys/$TaDia";
					$liste[]=array("date"=>$dTA, "valeur"=>$TA);
				}
			}
 			if($HistoRCVA->type_exam=="ecg"){
				$query =  "select dECG ".
						"from ".$this->getTableName()." ".
						"where id='$dossier->id' and dECG>'0000-00-00' group by dECG ".
						"order by dECG DESC";
				$result = $this->findAnyRows($query);
				
				while(list($dECG)=mysql_fetch_row($result)){
					$liste[]=array("date"=>$dECG, "valeur"=>"");
				}
			}
 			if($HistoRCVA->type_exam=="exam_cardio"){
				$query =  "select exam_cardio ".
						"from ".$this->getTableName()." ".
						"where id='$dossier->id' and exam_cardio>'0000-00-00' group by exam_cardio ".
						"order by exam_cardio DESC";
				$result = $this->findAnyRows($query);
				
				while(list($exam_cardio)=mysql_fetch_row($result)){
					$liste[]=array("date"=>$exam_cardio, "valeur"=>"");
				}
			}
   
			if($result == false) return false;
			return $liste;
		}
		
	
	}
?>
