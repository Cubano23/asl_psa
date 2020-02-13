<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/SuiviDiabete.php");
	
	class SuiviDiabeteMapper extends SelfManagedMapper{
		
		function getForeignKey(){
			return "dossier_id";
		}
		
		function getKeysMap(){
			return array("dossier_id"=>"dossier_id","dsuivi"=>"dsuivi");
		}
			
		function getTableName(){
			return "suivi_diabete";
		}
	
		function getLedgerName(){
			return "SuiviDiabeteMapper";
		}
	
		function getObject(){
			return new SuiviDiabete();
		}
		
  	function getObjectsByCabinet($cabinet){
		$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
				$this->getTableName().".dossier_id = dossier.id AND dossier.actif='oui' GROUP BY ".
				"dossier.numero ORDER BY dossier.numero";

   
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

		function getFindListQuery($object){
			$propertiesArray = get_object_vars($object);
			if(is_null($propertiesArray)) return false;	
			return "select * from ".$this->getTableName()." where dossier_id=".$propertiesArray["dossier_id"];
		}
		
		function getIncompletedExamsType4($cabinet){
			$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
				$this->getTableName().".dossier_id = dossier.id and (suivi_type & 1) and ".
				"((dHBA is NULL or dHBA = '0000-00-00') or (ResHBA is NULL or ResHBA = '')) ".
				"AND dossier.actif='oui' ".
				"ORDER BY dossier.numero, dsuivi DESC";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result); 
		} 

		function getDebut($dossier){
			$query =  "select date_format(min(date_debut), '%m/%Y') from ".$this->getTableName()." where ".
				$this->getTableName().".dossier_id = ".$dossier->id.
				" AND date_debut>'1900-01-01' ". 
				"GROUP BY dossier_id";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			
			list($date_debut)=mysql_fetch_row($result);
			return $date_debut; 
		} 

		function get10ans($dossier){
			$query =  "select max(diab10ans) as diab10ans from ".$this->getTableName()." where ".
				$this->getTableName().".dossier_id = ".$dossier->id.
				" GROUP BY dossier_id";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			
			list($diab10ans)=mysql_fetch_row($result);
			return $diab10ans; 
		} 

		function getIncompletedExamsTypeS($cabinet){
			$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
				$this->getTableName().".dossier_id = dossier.id and (suivi_type & 2) and ".
				"((dExaFil is NULL or dExaFil = '0000-00-00') or (ExaFil is NULL or ExaFil = '' ".
				"or ExaFil = 'nsp') or (dExaPieds is NULL or dExaPieds = '0000-00-00') or ".
				"(ExaPieds is NULL or ExaPieds = '') or (ExaPieds = 'nsp')) AND dossier.actif='oui' ".
				"ORDER BY dossier.numero, dsuivi DESC";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result); 
		} 


/*		function getIncompletedExamsTypeA($cabinet){
			$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
				$this->getTableName().".dossier_id = dossier.id and (suivi_type & 4) and ".
				"((dChol is NULL or dChol = '0000-00-00') or (HDL is NULL or HDL = '') or ".
				"(dLDL is NULL or dLDL = '0000-00-00') or (LDL is NULL or LDL = '') or ".
				"(dCreat is NULL or dCreat = '0000-00-00') or (dAlbu is NULL or dAlbu = '0000-00-00') ".
				"or (dFond is NULL or dFond = '0000-00-00') or (dECG is NULL or dECG = '0000-00-00'))";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result); 
		} 
*/

		function getIncompletedExamsTypeA($cabinet){
			$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
				$this->getTableName().".dossier_id = dossier.id and ((suivi_type & 4)OR (suivi_type & 2)) and ".
				"((dChol is NULL or dChol = '0000-00-00') or (HDL is NULL or HDL = '') or ".
				"(dLDL is NULL or dLDL = '0000-00-00') or (LDL is NULL or LDL = '') or ".
				"(".$this->getTableName().".dCreat is NULL or ".$this->getTableName().".dCreat = '0000-00-00') or (dAlbu is NULL or dAlbu = '0000-00-00') ".
				"or (dFond is NULL or dFond = '0000-00-00') or (dECG is NULL or dECG = '0000-00-00') OR ".
				"(dExaFil is NULL or dExaFil = '0000-00-00') or (ExaFil is NULL or ExaFil = '' ".
				"or ExaFil = 'nsp') or (dExaPieds is NULL or dExaPieds = '0000-00-00') or ".
				"(ExaPieds is NULL or ExaPieds = '') or (ExaPieds = 'nsp') or (dentiste='0000-00-00')) AND dossier.actif='oui' ".
				"ORDER BY dossier.numero, dsuivi DESC";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result);
		}


		function getIncompletedExams($cabinet){
			$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
				$this->getTableName().".dossier_id = dossier.id  and ((poids is NULL or poids = '') or ".
				"(ADO is NULL) or (TaSys is NULL or TaSys = '') or (TaDia is NULL or TaDia = '') or ".
				"(TA_mode is NULL) or (TA_mode = '')) AND dossier.actif='oui' ".
				"ORDER BY dossier.numero, dsuivi DESC";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result); 
		} 
		
		function getcompletedExamsType4($cabinet){
			$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
				$this->getTableName().".dossier_id = dossier.id and (suivi_type & 1) and ".
				"(dHBA is not NULL) and (dHBA != '0000-00-00') and (ResHBA is not NULL) and (ResHBA != '') AND dossier.actif='oui' ".
				"ORDER BY dossier.numero, dsuivi DESC";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result); 
		} 

		function getcompletedExamsTypeS($cabinet){
			$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
				$this->getTableName().".dossier_id = dossier.id and (suivi_type & 2) and ".
				"(dExaFil is not NULL) and (dExaFil != '0000-00-00') and (ExaFil is not NULL) and (ExaFil != '') ".
				"and (ExaFil != 'nsp') and (dExaPieds is not NULL) and (dExaPieds != '0000-00-00') and ".
				"(ExaPieds is not NULL) and (ExaPieds != '') and (ExaPieds != 'nsp') AND dossier.actif='oui' ".
				"ORDER BY dossier.numero, dsuivi DESC";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result); 
		} 


/*		function getcompletedExamsTypeA($cabinet){
			$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
				$this->getTableName().".dossier_id = dossier.id and (suivi_type & 4) and ".
				"(dChol is not NULL) and (dChol != '0000-00-00') and (HDL is not NULL) and (HDL != '') and ".
				"(dLDL is not NULL) and (dLDL != '0000-00-00') and (LDL is not NULL) and (LDL != '') and ".
				"(dCreat is not NULL) and (dCreat != '0000-00-00') and (dAlbu is not NULL) and (dAlbu != '0000-00-00') ".
				"and (dFond is not NULL) and (dFond != '0000-00-00') and (dECG is not NULL) and (dECG != '0000-00-00')";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result); 
		} 
*/

		function getcompletedExamsTypeA($cabinet){
			$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
				$this->getTableName().".dossier_id = dossier.id and ((suivi_type & 4) OR (suivi_type & 2)) and ".
				"(dChol is not NULL) and (dChol != '0000-00-00') and (HDL is not NULL) and (HDL != '') and ".
				"(dLDL is not NULL) and (dLDL != '0000-00-00') and (LDL is not NULL) and (LDL != '') and ".
				"(".$this->getTableName().".dCreat is not NULL) and (".$this->getTableName().".dCreat != '0000-00-00') and (dAlbu is not NULL) and (dAlbu != '0000-00-00') ".
				"and (dFond is not NULL) and (dFond != '0000-00-00') and (dECG is not NULL) and (dECG != '0000-00-00') and ".
				"(dExaFil is not NULL) and (dExaFil != '0000-00-00') and (ExaFil is not NULL) and (ExaFil != '') ".
				"and (ExaFil != 'nsp') and (dExaPieds is not NULL) and (dExaPieds != '0000-00-00') and ".
				"(ExaPieds is not NULL) and (ExaPieds != '') and (ExaPieds != 'nsp') AND dossier.actif='oui' ".
				"ORDER BY dossier.numero, dsuivi DESC";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getcompletedExams($cabinet){
			$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
				$this->getTableName().".dossier_id = dossier.id  and (poids is not NULL) and (poids != '') and ".
				"(ADO is not NULL) and (TaSys is not NULL) and (TaSys != '') and (TaDia is not NULL) and (TaDia != '') and ".
				"(TA_mode is not NULL) and (TA_mode !='') AND dossier.actif='oui' ".
				"ORDER BY dossier.numero, dsuivi DESC";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result); 
		} 

		function gettousExamsType4($cabinet){
			$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
				$this->getTableName().".dossier_id = dossier.id and (suivi_type & 1) AND dossier.actif='oui' ".
				"ORDER BY dossier.numero, dsuivi DESC";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result); 
		} 

		function gettousExamsTypeS($cabinet){
			$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
				$this->getTableName().".dossier_id = dossier.id and (suivi_type & 2) AND dossier.actif='oui' ".
				"ORDER BY dossier.numero, dsuivi DESC";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result); 
		} 


/*		function gettousExamsTypeA($cabinet){
			$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
				$this->getTableName().".dossier_id = dossier.id and (suivi_type & 4)";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result); 
		} 
*/

		function gettousExamsTypeA($cabinet){
			$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
				$this->getTableName().".dossier_id = dossier.id and ((suivi_type & 4) OR (suivi_type & 2)) AND dossier.actif='oui' ".
				"ORDER BY dossier.numero, dsuivi DESC";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result);
		}


		function gettousExams($cabinet){
			$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
				$this->getTableName().".dossier_id = dossier.id  AND dossier.actif='oui' ".
				"ORDER BY dossier.numero, dsuivi DESC";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result); 
		} 

		
		function getExpiredExamsType4($cabinet,$period=0){
			$period += 4;
			$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
			$this->getTableName().".dossier_id = dossier.id and (suivi_type & 1) and (dHBA is not NULL and ".
			"DATE_ADD(dHBA, INTERVAL ".$period." MONTH) <= CURDATE()) AND dossier.actif='oui'";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result);
		} 

		function getExpiredExamsTypeS($cabinet,$period=0){
			$period += 6;
			$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
			$this->getTableName().".dossier_id = dossier.id and (suivi_type & 2) and ((dExaFil is not NULL ".
			"and DATE_ADD(dExaFil, INTERVAL ".$period." MONTH) <= CURDATE()) or (dExaPieds is not NULL and ".
			"DATE_ADD(dExaPieds, INTERVAL 6 MONTH) <= CURDATE())) AND dossier.actif='oui'";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result);
		} 


/*		function getExpiredExamsTypeA($cabinet,$period=0){
			$period += 12;
			$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".$this->getTableName().".
			dossier_id = dossier.id and (suivi_type & 4) and ((dChol is not NULL and DATE_ADD(dChol, INTERVAL ".$period.
			" MONTH) <= CURDATE()) or (dLDL is not NULL and DATE_ADD(dLDL, INTERVAL 12 MONTH) <= CURDATE())  or (dCreat is ".
			"not NULL and DATE_ADD(dCreat, INTERVAL 12 MONTH) <= CURDATE()) or (dAlbu is not NULL and DATE_ADD(dAlbu, ".
			"INTERVAL 12 MONTH) <= CURDATE()) or (dFond is not NULL and DATE_ADD(dFond, INTERVAL 12 MONTH) <= CURDATE())".
			" or (dECG is not NULL and DATE_ADD(dECG, INTERVAL 12 MONTH) <= CURDATE()))";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result);
		} 
*/

		function getExpiredExamsTypeA($cabinet,$period=0){
			$period += 12;
			$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
			$this->getTableName().".dossier_id = dossier.id and ((dChol is not NULL and ".//((suivi_type & 4)OR(suivi_type & 2)) and 
			"DATE_ADD(dChol, INTERVAL ".$period." MONTH) <= CURDATE()) or (dLDL is not NULL and DATE_ADD(dLDL, ".
			"INTERVAL $period MONTH) <= CURDATE())  or (".$this->getTableName().".dCreat is not NULL and DATE_ADD(".$this->getTableName().".dCreat, INTERVAL $period MONTH) ".
			"<= CURDATE()) or (dAlbu is not NULL and DATE_ADD(dAlbu, INTERVAL $period MONTH) <= CURDATE()) or ".
			"(dFond is not NULL and DATE_ADD(dFond, INTERVAL $period MONTH) <= CURDATE()) or (dECG is not NULL and ".
			"DATE_ADD(dECG, INTERVAL $period MONTH) <= CURDATE()) OR (dExaFil is not NULL ".
			"and DATE_ADD(dExaFil, INTERVAL ".$period." MONTH) <= CURDATE()) or (dExaPieds is not NULL and ".
			"DATE_ADD(dExaPieds, INTERVAL $period MONTH) <= CURDATE()) or (dentiste is not NULL and ".
			"DATE_ADD(dentiste, INTERVAL ".$period." MONTH) <= CURDATE())) AND dossier.actif='oui'";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		// Return expired exams from any type
		function getExpiredExams($cabinet,$period=0){
			$query =  "select dossier_id, max(dsuivi) as dsuivi, max(dHBA) as dHBA, ResHBA, max(dExaFil) as dExaFil, ExaFil, max(dExaPieds)".
					  " as dExaPieds, ExaPieds, max(dChol) as dChol, iChol, HDL, HDLc, max(dLDL) as dLDL, iLDL, LDLc, ".
					  "LDL, max(".$this->getTableName().".dCreat) as dCreat, Creat, CreatC, iCreat, max(dAlbu) as dAlbu, iAlbu, Albu, AlbuC, ".
					  "max(dFond) as dFond, iFond, max(dECG) as dECG, iECG, max(dentiste) as dentiste, suivi_type, poids, ".
					  "max(dPoids) as dPoids, Regime, InsulReq, ADO, ".//insertulReq
					  "TaSys, TaDia, TA_mode, max(dtension) as dtension, risques, nbrtabac, hta, arte, neph, coro, reti, neur, equilib, tension, lipide, ".
					  "mesure_ADO, insuline, mesure_hta, hypl, phys, diet, taba, etp, max(sortie) as sortie, date_debut, diab10ans, ".
					  $this->getTableName().".dmaj, type, ".
					  "id, cabinet, numero, dnaiss, sexe, taille, actif, dossier.dmaj ".
					  "from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".$this->getTableName().
					  ".dossier_id = dossier.id  AND dossier.actif='oui' "./*and ".
					  "((suivi_type & 1) and (dHBA is not NULL and DATE_ADD(dHBA, INTERVAL 4 MONTH) <= DATE_ADD(CURDATE(), ".
					  "INTERVAL $period MONTH)) or (suivi_type & 2) and ((dExaFil is not NULL and DATE_ADD(dExaFil, ".
					  "INTERVAL 12 MONTH) <= DATE_ADD(CURDATE(), INTERVAL $period MONTH)) or (dExaPieds is not NULL and ".
					  "DATE_ADD(dExaPieds, INTERVAL 12 MONTH) <= CURDATE())) or ".
					  "(suivi_type & 4) and ((dChol is not NULL and DATE_ADD(dChol, INTERVAL 12 MONTH) <= DATE_ADD(CURDATE(), ".
					  "INTERVAL $period MONTH)) or (dLDL is not NULL and DATE_ADD(dLDL, INTERVAL 12 MONTH) <= CURDATE())  ".
					  "or (dCreat is not NULL and DATE_ADD(dCreat, INTERVAL 12 MONTH) <= CURDATE()) or (dAlbu is not NULL ".
					  "and DATE_ADD(dAlbu, INTERVAL 12 MONTH) <= CURDATE()) or (dFond is not NULL and DATE_ADD(dFond, ".
					  "INTERVAL 12 MONTH) <= CURDATE()) or (dECG is not NULL and DATE_ADD(dECG, INTERVAL 12 MONTH) <= ".
					  "CURDATE())))*/" GROUP by numero order by numero";//echo $query;
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}	

		function getdernierRappel($dossier_id, $dsuivi){
		    $query = "SELECT dossier_id, sortie ".
					 "from ".$this->getTableName()." where dossier_id='$dossier_id' AND dsuivi='$dsuivi'";

			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		
		function getHBA1GraphData($cabinetArray,$startMonth,$endMonth,$startYear,$endYear){
			$query = "select cabinet,dHBA,resHBA,MONTH(dHBA) 'mHBA',YEAR(dHBA) 'yHBA',count(dHBA) 'total',round(sum(resHBA)".
					 "/count(dHBA),2) 'av' from dossier,suivi_diabete where dossier.id=suivi_diabete.dossier_id and ".
					 "(suivi_type & 1) and (dHBA is not NULL) and (dHBA >0) and dHBA>='$startYear-$startMonth-00' and ".
					 "dHBA<='$endYear-$endMonth-00'";
			if(is_array($cabinetArray)){
				$query = $query." and (";
				foreach($cabinetArray as $cabinet){
					 $query = $query." cabinet = '$cabinet' or";
				}
				$query = substr($query,0,strlen($query)-2);
				$query = $query.")";
			}
			else
			    if (!empty($cabinetArray))
					$query=$query." and (cabinet='$cabinetArray') ";
			$query = $query." group by mHBA, yHBA ORDER BY dHBA";
//EA mysql5  enlever les ' '  des noms des colonnes      
			$result = $this->findAnyRows($query);
			
			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getHBA2GraphData1($cabinetArray,$startMonth,$endMonth,$startYear,$endYear){
			$query = "select avg(resHBA) 'av', std(resHBA) 'std' , count(*) 'cnt' from dossier,suivi_diabete where ".
					 "dossier.id=suivi_diabete.dossier_id and (suivi_type & 1) and (dHBA is not NULL) and (dHBA >0) and ".
					 "dHBA>='$startYear-$startMonth-00' and dHBA<='$endYear-$endMonth-00'";
			if(is_array($cabinetArray)){
				$query = $query." and (";
				foreach($cabinetArray as $cabinet){
					 $query = $query." cabinet = '$cabinet' or";
				}
				$query = substr($query,0,strlen($query)-2);
				$query = $query.")";
			}			
			else
			    if (!empty($cabinetArray))
					$query=$query." and (cabinet='$cabinetArray') ";

			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		
		function getHBA2GraphData2($cabinetArray,$startMonth,$endMonth,$startYear,$endYear,$step,$min){
			$query = "select floor((resHBA-$min)/$step) AS borne ,count(*) 'cnt' from dossier,suivi_diabete where ".
					 "dossier.id=suivi_diabete.dossier_id and (suivi_type & 1) and (dHBA is not NULL) and (dHBA >0) and ".
					 "dHBA>='$startYear-$startMonth-00' and dHBA<='$endYear-$endMonth-00'";
			if(is_array($cabinetArray)){
				$query = $query." and (";
				foreach($cabinetArray as $cabinet){
					 $query = $query." cabinet = '$cabinet' or";
				}
				$query = substr($query,0,strlen($query)-2);
				$query = $query.")";
			}			
			else
			    if (!empty($cabinetArray))
					$query=$query." and (cabinet='$cabinetArray') ";

			$query = $query." group by borne ";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		
		function getHBA2GraphDataMediane($cabinetArray,$startMonth,$endMonth,$startYear,$endYear,$nb){
			$query = "select resHBA from dossier,suivi_diabete where dossier.id=suivi_diabete.dossier_id and (suivi_type & 1)".
					 " and (dHBA is not NULL) and (dHBA >0) and dHBA>='$startYear-$startMonth-00' and dHBA<=".
					 "'$endYear-$endMonth-00'";
			if(is_array($cabinetArray)){
				$query = $query." and (";
				foreach($cabinetArray as $cabinet){
					 $query = $query." cabinet = '$cabinet' or";
				}
				$query = substr($query,0,strlen($query)-2);
				$query = $query.")";
			}			
			else
			    if (!empty($cabinetArray))
					$query=$query." and (cabinet='$cabinetArray') ";

			$query = $query." order by resHBA limit ".round($nb/2).",1";
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		function getdernierExams($id){
			$query=	  " select dossier_id, max(dsuivi) as dsuivi, max(dHBA) as dHBA, max(dExaFil) as dExaFil, max(dExaPieds)".
					  " as dExaPieds, max(dChol) as dChol, max(dLDL) as dLDL, max(dCreat) as dCreat, max(dAlbu) as dAlbu, ".
					  "max(dFond) as dFond, max(dECG) as dECG, max(dPoids) as dPoids, max(dtension) as dtension, ".
					  "max(dentiste) as dentiste from ".$this->getTableName().
					  " where dossier_id='$id' group by dossier_id";

			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		//Retourne la valeur du dernier poids enregistré
		function getsystematique($id, $dsuivi)
		{
			$query =  "select hta, arte, neph, coro, reti, neur, equilib, ".
					  "tension, lipide, type ".
					  "from ".$this->getTableName()." where ".
					  "dossier_id = '$id' and type>'0' order by dsuivi DESC ".
					  "limit 0, 1";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		//Retourne la valeur du dernier poids
		function getPoids($id, $dPoids){
			$query =  "select poids ".
					  "from ".$this->getTableName()." where ".
					  "dossier_id = '$id'  AND dPoids='$dPoids' ";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		//retourne les valeurs de la dernière tension
		function getTension($id, $dtension){
			$query =  "select TaSys, TaDia, TA_mode ".
					  "from ".$this->getTableName()." where ".
					  "dossier_id = '$id'  AND dtension='$dtension' ";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		//Retourne la valeur du dernier HBA enregistré
		function getHBA($id, $dHBA)
		{
			$query =  "select ResHBA ".
					  "from ".$this->getTableName()." where ".
					  "dossier_id = '$id'  AND dHBA='$dHBA' ";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		//Retourne la valeur du dernier Examen Fil enregistré
		function getExaFil($id, $dExaFil)
		{
			$query =  "select ExaFil ".
					  "from ".$this->getTableName()." where ".
					  "dossier_id = '$id'  AND dExaFil='$dExaFil' ";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		//Retourne la valeur du dernier Examen Pieds enregistré
		function getExaPieds($id, $dExaPieds)
		{
			$query =  "select ExaPieds ".
					  "from ".$this->getTableName()." where ".
					  "dossier_id = '$id'  AND dExaPieds='$dExaPieds' ";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}

		//Retourne la valeur du dernier Chol enregistré
		function getHDL($id, $dChol)
		{
			$query =  "select iChol, HDL, HDLc ".
					  "from ".$this->getTableName()." where ".
					  "dossier_id = '$id'  AND dChol='$dChol' ";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		//Retourne la valeur du dernier LDL enregistré
		function getLDL($id, $dLDL)
		{
			$query =  "select iLDL, LDLc, LDL ".
					  "from ".$this->getTableName()." where ".
					  "dossier_id = '$id'  AND dLDL='$dLDL' ";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		//Retourne la valeur du dernier Créat enregistré
		function getCreat($id, $dCreat)
		{
			$query =  "select Creat, CreatC, iCreat ".
					  "from ".$this->getTableName()." where ".
					  "dossier_id = '$id'  AND dCreat='$dCreat' ";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		//Retourne la valeur du dernier Albu enregistré
		function getAlbu($id, $dAlbu)
		{
			$query =  "select iAlbu, Albu, AlbuC ".
					  "from ".$this->getTableName()." where ".
					  "dossier_id = '$id'  AND dAlbu='$dAlbu' ";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		//Retourne la valeur du dernier Fond enregistré
		function getFond($id, $dFond)
		{
			$query =  "select iFond ".
					  "from ".$this->getTableName()." where ".
					  "dossier_id = '$id'  AND dFond='$dFond' ";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
		
		//Retourne la valeur du dernier ECG enregistré
		function getECG($id, $dECG)
		{
			$query =  "select iECG ".
					  "from ".$this->getTableName()." where ".
					  "dossier_id = '$id'  AND dECG='$dECG' ";
			$result = $this->findAnyRows($query);

			if($result == false) return false;
			return $this->buildRowArray($result);
		}
	}

?>
