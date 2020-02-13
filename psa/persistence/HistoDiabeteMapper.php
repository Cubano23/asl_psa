<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/HistoDiabete.php");
	
	class HistoDiabeteMapper extends SelfManagedMapper{
	
		function getForeignKey(){
			return "id";
		}
	
		function getKeysMap(){
			return array("id"=>"id","date"=>"date");
		}
		
		function getTableName(){
			return "suivi_diabete";
		}
	
		function getLedgerName(){
			return "HistoDiabeteMapper";
		}
	
		function getObject(){
			return new HistoDiabete();
		}
		
 		function ListeExams($HistoDiabete, $dossier){
			$liste=array();

			if($HistoDiabete->type_exam=="poids"){
				$query =  "select poids, dpoids ".
						"from ".$this->getTableName()." ".
						"where dossier_id='$dossier->id' and dpoids>'0000-00-00' group by dpoids ".
						"order by dpoids DESC";
				$result = $this->findAnyRows($query);
				
				while(list($poids, $dpoids)=mysql_fetch_row($result)){
					$liste[]=array("date"=>$dpoids, "valeur"=>$poids);
				}
			}
			if($HistoDiabete->type_exam=="HDL"){
				$query =  "select HDL, dChol ".
						"from ".$this->getTableName()." ".
						"where dossier_id='$dossier->id' and dChol>'0000-00-00' group by dChol ".
						"order by dChol DESC";
				$result = $this->findAnyRows($query);
				
				while(list($HDL, $dHDL)=mysql_fetch_row($result)){
					$liste[]=array("date"=>$dHDL, "valeur"=>$HDL);
				}
			}
			if($HistoDiabete->type_exam=="LDL"){
				$query =  "select LDL, dLDL ".
						"from ".$this->getTableName()." ".
						"where dossier_id='$dossier->id' and dLDL>'0000-00-00' group by dLDL ".
						"order by dLDL DESC";
				$result = $this->findAnyRows($query);
				
				while(list($LDL, $dLDL)=mysql_fetch_row($result)){
					$liste[]=array("date"=>$dLDL, "valeur"=>$LDL);
				}
			}
			if($HistoDiabete->type_exam=="hba"){
				$query =  "select ResHBA, dHBA ".
						"from ".$this->getTableName()." ".
						"where dossier_id='$dossier->id' and dHBA>'0000-00-00' group by dHBA ".
						"order by dHBA DESC";
				$result = $this->findAnyRows($query);
				
				while(list($hba, $dhba)=mysql_fetch_row($result)){
					$liste[]=array("date"=>$dhba, "valeur"=>$hba);
				}
			}
			if($HistoDiabete->type_exam=="Creat"){
				$query =  "select Creat, dCreat ".
						"from ".$this->getTableName()." ".
						"where dossier_id='$dossier->id' and dCreat>'0000-00-00' group by dCreat ".
						"order by dCreat DESC";
				$result = $this->findAnyRows($query);
				
				while(list($Creat, $dCreat)=mysql_fetch_row($result)){
					$liste[]=array("date"=>$dCreat, "valeur"=>$Creat);
				}
			}
			if($HistoDiabete->type_exam=="albu"){
				$query =  "select iAlbu, dAlbu ".
						"from ".$this->getTableName()." ".
						"where dossier_id='$dossier->id' and dAlbu>'0000-00-00' group by dAlbu ".
						"order by dAlbu DESC";
				$result = $this->findAnyRows($query);
				
				while(list($Albu, $dAlbu)=mysql_fetch_row($result)){
					
					if($Albu==1){
						$Albu="Positif";
					}
					else{
						$Albu="Négatif";
					}
					$liste[]=array("date"=>$dAlbu, "valeur"=>$Albu);
				}
			}
			if($HistoDiabete->type_exam=="Foeil"){
				$query =  "select iFond, dFond ".
						"from ".$this->getTableName()." ".
						"where dossier_id='$dossier->id' and dFond>'0000-00-00' group by dFond ".
						"order by dFond DESC";
				$result = $this->findAnyRows($query);
				
				while(list($iFond, $dFond)=mysql_fetch_row($result)){
					if($iFond==1){
						$iFond="Positif";
					}
					else{
						$iFond="Négatif";
					}
					$liste[]=array("date"=>$dFond, "valeur"=>"$iFond");
				}
			}
			if($HistoDiabete->type_exam=="dentiste"){
				$query =  "select dentiste ".
						"from ".$this->getTableName()." ".
						"where dossier_id='$dossier->id' and dentiste>'0000-00-00' group by dentiste ".
						"order by dentiste DESC";
				$result = $this->findAnyRows($query);
				
				while(list($dentiste)=mysql_fetch_row($result)){
					$liste[]=array("date"=>$dentiste, "valeur"=>"");
				}
			}
			if($HistoDiabete->type_exam=="ExaPieds"){
				$query =  "select dExaPieds ".
						"from ".$this->getTableName()." ".
						"where dossier_id='$dossier->id' and dExaPieds>'0000-00-00' group by dExaPieds ".
						"order by dExaPieds DESC";
				$result = $this->findAnyRows($query);
				
				while(list($dExaPieds)=mysql_fetch_row($result)){
					$liste[]=array("date"=>$dExaPieds, "valeur"=>"");
				}
			}
			if($HistoDiabete->type_exam=="ExaFil"){
				$query =  "select dExaFil ".
						"from ".$this->getTableName()." ".
						"where dossier_id='$dossier->id' and dExaFil>'0000-00-00' group by dExaFil ".
						"order by dExaFil DESC";
				$result = $this->findAnyRows($query);
				
				while(list($dExaFil)=mysql_fetch_row($result)){
					$liste[]=array("date"=>$dExaFil, "valeur"=>"");
				}
			}
 			if($HistoDiabete->type_exam=="tension"){
				$query =  "select TaSys, TaDia, dtension ".
						"from ".$this->getTableName()." ".
						"where dossier_id='$dossier->id' and dtension>'0000-00-00' group by dtension ".
						"order by dtension DESC";
				$result = $this->findAnyRows($query);
				
				while(list($TaSys, $TaDia, $dTA)=mysql_fetch_row($result)){
					$TA="$TaSys/$TaDia";
					$liste[]=array("date"=>$dTA, "valeur"=>$TA);
				}
			}
 			if($HistoDiabete->type_exam=="ECG"){
				$query =  "select iECG, dECG ".
						"from ".$this->getTableName()." ".
						"where dossier_id='$dossier->id' and dECG>'0000-00-00' group by dECG ".
						"order by dECG DESC";
				$result = $this->findAnyRows($query);
				
				while(list($iECG, $dECG)=mysql_fetch_row($result)){
					if($iECG==1){
						$iECG="Positif";
					}
					else{
						$iECG="Négatif";
					}
					$liste[]=array("date"=>$dECG, "valeur"=>"$iECG");
				}
			}
   
			if($result == false) return false;
			return $liste;
		}
		
	
	}
?>
