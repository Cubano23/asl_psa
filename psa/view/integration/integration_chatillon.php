<?php

function integration_chatillon($fichier){

	echo 'test'; exit;

	$fich="./log/Compte-rendu integration ".$_SESSION["cabinet"]." ".date("d-m-Y").".xls";
	$workbook =& new writeexcel_workbookbig($fich); // on lui passe en paramètre le chemin de notre fichier
	
	$worksheet_ok =& $workbook->addworksheet("données intégrées");
	$worksheet_ok->write("A1", "Id dans asalée");
	$worksheet_ok->write("B1", "n° dossier");
	$worksheet_ok->write("C1", "date");
	$worksheet_ok->write("D1", "valeur");
	$worksheet_ok->write("E1", "type examen");

	$lok=1;
	
	$worksheet_bis =& $workbook->addworksheet("données déjà présentes");
	$worksheet_bis->write("A1", "Id dans asalée");
	$worksheet_bis->write("B1", "n° dossier");
	$worksheet_bis->write("C1", "date dans export");
	$worksheet_bis->write("D1", "valeur dans export");
	$worksheet_bis->write("E1", "date dans asalée");
	$worksheet_bis->write("F1", "valeur dans asalée");
	$worksheet_bis->write("G1", "type examen");

	$lbis=1;
	
	$worksheet_ko =& $workbook->addworksheet("données non intégrées");
	$worksheet_ko->write("A1", "Id dans asalée");
	$worksheet_ko->write("B1", "n° dossier");
	$worksheet_ko->write("C1", "date");
	$worksheet_ko->write("D1", "valeur");
	$worksheet_ko->write("E1", "type examen");
	$worksheet_ko->write("F1", "Raison non intégration");

	$lko=1;
	$fp=fopen("$fichier", "r");
	
	$zones=array("numero", "date", "type", "val");
	$zone=0;
	$numero=$date=$type=$val="";
	
	while(!feof($fp)){
		$car=fread($fp, 1);

		if($car=="\t"){//on change de zone de texte
			$zone++;
		}
		elseif($car=="\n"){
			$zone=0;
			
			if(($type=="PA_A1C         %")||($type=="hHbA1c%	")){//Il s'agit d'un examen HBA1c => intégration dans une table temporaire
			
				$date=explode("/", $date);
				if($date[0]<10){
					$date[0]="0".$date[0];
				}
				if($date[1]<10){
					$date[1]="0".$date[1];
				}
				if($date[2]<10){
					$date[2]="200".$date[2];
				}
				elseif($date[2]<15){
					$date[2]="20".$date[2];
				}
				else{
					$date[2]="19".$date[2];
				}
				
				if(strpos($val, "%")!==false){//% trouvé en fin de ligne=> suppression de % et des espaces
					$val=str_replace("%", "", $val);
					$val=str_replace(" ", "", $val);
				}
				
				$date=$date[2]."-".$date[1]."-".$date[0];
				$req="SELECT id from dossier WHERE cabinet='".$_SESSION["cabinet"]."' ".
					 "and numero='N$numero'";
				$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
				if(mysql_num_rows($res)==1){
					list($id)=mysql_fetch_row($res);
				}
				else{
					$id="";
				}
				
				if($id!=""){//Le dossier est reconnu
					$dateexam=explode("-", $date);
					$date_avant=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]-15, $dateexam[0]));
					$date_apres=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]+15, $dateexam[0]));

					$req2="SELECT dHBA, ResHBA from suivi_diabete where dossier_id='$id' ".
						  "and dhba>'$date_avant' and dhba<'$date_apres'";
					$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
					
					if(mysql_num_rows($res2)==0){//La donnée n'est pas présente dans asalée
						$dsuivi=date("Y-m-d", mktime(1, 1, 1, date("m")  , date("d"), date("Y")));
						$req2="SELECT dsuivi from suivi_diabete where dossier_id='$id' and dsuivi='$dsuivi'";
						$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
						$i=0;
						
						while(mysql_num_rows($res2)==1){
							$i++;
							$dsuivi=date("Y-m-d", mktime(1, 1, 1, date("m")  , date("d")-$i, date("Y")));
							$req2="SELECT dsuivi from suivi_diabete where dossier_id='$id' and dsuivi='$dsuivi'";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
						}
						
						$req2="SELECT type, risques, hta, arte, neph, coro, reti, ".
							  "neur from suivi_diabete WHERE dossier_id='$id' ".
							  "and type >0 order by dsuivi DESC limit 0, 1";
						$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
						
						list($type, $risques, $hta, $arte, $neph, $coro, $reti, $neur)=mysql_fetch_row($res);
						
						$req2="INSERT INTO suivi_diabete SET dossier_id='$id', ".
							  "dsuivi='$dsuivi', dHBA='$date', resHBA='$val', ".
							  "suivi_type='4,a', type='$type', risques='$risques', ".
							  "hta='$hta', arte='$arte', neph='$neph', coro='$coro', ".
							  "reti='$reti', neur='$neur'";
						$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
						
						//Sauvegarde dans le compte-rendu
						$lok++;
						$worksheet_ok->write("A$lok", $id);
						$worksheet_ok->write("B$lok", "N$numero");
						$worksheet_ok->write("C$lok", $date);
						$worksheet_ok->write("D$lok", $val);
						$worksheet_ok->write("E$lok", "HBA1c");
					}
					else{//Sauvegarde dans le compte-rendu
						$lbis++;
						list($dHBA, $ResHBA)=mysql_fetch_row($res2);
						$worksheet_bis->write("A$lbis", "$id");
						$worksheet_bis->write("B$lbis", "N$numero");
						$worksheet_bis->write("C$lbis", "$date");
						$worksheet_bis->write("D$lbis", $val);
						$worksheet_bis->write("E$lbis", $dHBA);
						$worksheet_bis->write("F$lbis", $ResHBA);
						$worksheet_bis->write("G$lbis", "HBA1c");
					}
				}
				else{//Dossier non reconnu => écriture dans le compte-rendu
					$lko++;
					$worksheet_ko->write("A$lko", "");
					$worksheet_ko->write_string("B$lko", "$numero");
					$worksheet_ko->write("C$lko", "$date");
					$worksheet_ko->write("D$lko", $val);
					$worksheet_ko->write("E$lko", "HBA1c");
					$worksheet_ko->write("F$lko", "Dossier non trouvé dans asalée");
				}
			}
			if(($type=="Poids")||($type=="Poids ")||($type=="Poids=")){//Il s'agit d'un examen Poids => intégration dans la table liste_exam
				if($val>1000){
					$val=$val/100;
				}
				$date=explode("/", $date);
				if($date[0]<10){
					$date[0]="0".$date[0];
				}
				if($date[1]<10){
					$date[1]="0".$date[1];
				}
				if($date[2]<10){
					$date[2]="200".$date[2];
				}
				elseif($date[2]<15){
					$date[2]="20".$date[2];
				}
				else{
					$date[2]="19".$date[2];
				}
				
				$date=$date[2]."-".$date[1]."-".$date[0];
				
				if($date>"2004-03-31"){
					$req="SELECT id from dossier WHERE cabinet='".$_SESSION["cabinet"]."' ".
						 "and numero='N$numero'";
					$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
					if(mysql_num_rows($res)==1){
						list($id)=mysql_fetch_row($res);
					}
					else{
						$id="";
					}
					
					if($id!=""){//Le dossier est reconnu
						$dateexam=explode("-", $date);
						$date_avant=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]-15, $dateexam[0]));
						$date_apres=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]+15, $dateexam[0]));

						$req2="SELECT date_exam, resultat1 from liste_exam where id='$id' ".
							  "and date_exam>'$date_avant' and date_exam<'$date_apres' ".
							  "and type_exam='poids'";
						$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
						
						if(mysql_num_rows($res2)==0){//La donnée n'est pas présente dans asalée
							$req2="INSERT INTO liste_exam SET id='$id', ".
								  "date_exam='$date', resultat1='$val', ".
								  "type_exam='poids'";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
							
							//Sauvegarde dans le compte-rendu
							$lok++;
							$worksheet_ok->write("A$lok", $id);
							$worksheet_ok->write("B$lok", "N$numero");
							$worksheet_ok->write("C$lok", $date);
							$worksheet_ok->write("D$lok", $val);
							$worksheet_ok->write("E$lok", "Poids");
						}
						else{//Sauvegarde dans le compte-rendu
							$lbis++;
							list($date_exam, $resultat1)=mysql_fetch_row($res2);
							$worksheet_bis->write("A$lbis", "$id");
							$worksheet_bis->write("B$lbis", "N$numero");
							$worksheet_bis->write("C$lbis", "$date");
							$worksheet_bis->write("D$lbis", $val);
							$worksheet_bis->write("E$lbis", $date_exam);
							$worksheet_bis->write("F$lbis", $resultat1);
							$worksheet_bis->write("G$lbis", "Poids");
						}
					}
					else{//Dossier non reconnu => écriture dans le compte-rendu
						$lko++;
						$worksheet_ko->write("A$lko", "");
						$worksheet_ko->write_string("B$lko", "$numero");
						$worksheet_ko->write("C$lko", "$date");
						$worksheet_ko->write("D$lko", $val);
						$worksheet_ko->write("E$lko", "Poids");
						$worksheet_ko->write("F$lko", "Dossier non trouvé dans asalée");
					}
				}
			}

			if(($type=="Ta s")||($type=="Ta s ")){//Il s'agit d'un examen systole => intégration dans la table liste_exam
				$date=explode("/", $date);
				if($date[0]<10){
					$date[0]="0".$date[0];
				}
				if($date[1]<10){
					$date[1]="0".$date[1];
				}
				if($date[2]<10){
					$date[2]="200".$date[2];
				}
				elseif($date[2]<15){
					$date[2]="20".$date[2];
				}
				else{
					$date[2]="19".$date[2];
				}
				
				$date=$date[2]."-".$date[1]."-".$date[0];
				
				if($date>"2004-03-31"){
					$req="SELECT id from dossier WHERE cabinet='".$_SESSION["cabinet"]."' ".
						 "and numero='N$numero'";
					$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
					if(mysql_num_rows($res)==1){
						list($id)=mysql_fetch_row($res);
					}
					else{
						$id="";
					}
					
					if($id!=""){//Le dossier est reconnu
						$dateexam=explode("-", $date);
						$date_avant=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]-15, $dateexam[0]));
						$date_apres=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]+15, $dateexam[0]));

						$req2="SELECT date_exam, resultat1 from liste_exam where id='$id' ".
							  "and date_exam>'$date_avant' and date_exam<'$date_apres' ".
							  "and type_exam='systole'";
						$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
						
						if(mysql_num_rows($res2)==0){//La donnée n'est pas présente dans asalée
							$req2="INSERT INTO liste_exam SET id='$id', ".
								  "date_exam='$date', resultat1='$val', ".
								  "type_exam='systole'";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
							
							//Sauvegarde dans le compte-rendu
							$lok++;
							$worksheet_ok->write("A$lok", $id);
							$worksheet_ok->write("B$lok", "N$numero");
							$worksheet_ok->write("C$lok", $date);
							$worksheet_ok->write("D$lok", $val);
							$worksheet_ok->write("E$lok", "Systole");
						}
						else{//Sauvegarde dans le compte-rendu
							$lbis++;
							list($date_exam, $resultat1)=mysql_fetch_row($res2);
							$worksheet_bis->write("A$lbis", "$id");
							$worksheet_bis->write("B$lbis", "N$numero");
							$worksheet_bis->write("C$lbis", "$date");
							$worksheet_bis->write("D$lbis", $val);
							$worksheet_bis->write("E$lbis", $date_exam);
							$worksheet_bis->write("F$lbis", $resultat1);
							$worksheet_bis->write("G$lbis", "Systole");
						}
					}
					else{//Dossier non reconnu => écriture dans le compte-rendu
						$lko++;
						$worksheet_ko->write("A$lko", "");
						$worksheet_ko->write_string("B$lko", "$numero");
						$worksheet_ko->write("C$lko", "$date");
						$worksheet_ko->write("D$lko", $val);
						$worksheet_ko->write("E$lko", "Systole");
						$worksheet_ko->write("F$lko", "Dossier non trouvé dans asalée");
					}
				}
			}

			if(($type=="Ta d")||($type=="Ta d ")){//Il s'agit d'un examen diastole => intégration dans la table liste_exam
				$date=explode("/", $date);
				if($date[0]<10){
					$date[0]="0".$date[0];
				}
				if($date[1]<10){
					$date[1]="0".$date[1];
				}
				if($date[2]<10){
					$date[2]="200".$date[2];
				}
				elseif($date[2]<15){
					$date[2]="20".$date[2];
				}
				else{
					$date[2]="19".$date[2];
				}
				
				$date=$date[2]."-".$date[1]."-".$date[0];
				
				if($date>"2004-03-31"){
					$req="SELECT id from dossier WHERE cabinet='".$_SESSION["cabinet"]."' ".
						 "and numero='N$numero'";
					$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
					if(mysql_num_rows($res)==1){
						list($id)=mysql_fetch_row($res);
					}
					else{
						$id="";
					}
					
					if($id!=""){//Le dossier est reconnu
						$dateexam=explode("-", $date);
						$date_avant=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]-15, $dateexam[0]));
						$date_apres=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]+15, $dateexam[0]));

						$req2="SELECT date_exam, resultat1 from liste_exam where id='$id' ".
							  "and date_exam>'$date_avant' and date_exam<'$date_apres' ".
							  "and type_exam='diastole'";
						$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
						
						if(mysql_num_rows($res2)==0){//La donnée n'est pas présente dans asalée
							$req2="INSERT INTO liste_exam SET id='$id', ".
								  "date_exam='$date', resultat1='$val', ".
								  "type_exam='diastole'";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
							
							//Sauvegarde dans le compte-rendu
							$lok++;
							$worksheet_ok->write("A$lok", $id);
							$worksheet_ok->write("B$lok", "N$numero");
							$worksheet_ok->write("C$lok", $date);
							$worksheet_ok->write("D$lok", $val);
							$worksheet_ok->write("E$lok", "Diastole");
						}
						else{//Sauvegarde dans le compte-rendu
							$lbis++;
							list($date_exam, $resultat1)=mysql_fetch_row($res2);
							$worksheet_bis->write("A$lbis", "$id");
							$worksheet_bis->write("B$lbis", "N$numero");
							$worksheet_bis->write("C$lbis", "$date");
							$worksheet_bis->write("D$lbis", $val);
							$worksheet_bis->write("E$lbis", $date_exam);
							$worksheet_bis->write("F$lbis", $resultat1);
							$worksheet_bis->write("G$lbis", "Diastole");
						}
					}
					else{//Dossier non reconnu => écriture dans le compte-rendu
						$lko++;
						$worksheet_ko->write("A$lko", "");
						$worksheet_ko->write_string("B$lko", "$numero");
						$worksheet_ko->write("C$lko", "$date");
						$worksheet_ko->write("D$lko", $val);
						$worksheet_ko->write("E$lko", "Diastole");
						$worksheet_ko->write("F$lko", "Dossier non trouvé dans asalée");
					}
				}
			}

			if(($type=="LDL")||($type=="LDL cholesterol")||($type=="LDL/HDL ( N<3.50)")||($type=="PA_LDL cal. si tri<3,4")){//Il s'agit d'un examen LDL => intégration dans la table liste_exam
				$date=explode("/", $date);
				if($date[0]<10){
					$date[0]="0".$date[0];
				}
				if($date[1]<10){
					$date[1]="0".$date[1];
				}
				if($date[2]<10){
					$date[2]="200".$date[2];
				}
				elseif($date[2]<15){
					$date[2]="20".$date[2];
				}
				else{
					$date[2]="19".$date[2];
				}
				
				$date=$date[2]."-".$date[1]."-".$date[0];
				
				if($date>"2004-03-31"){
					$req="SELECT id from dossier WHERE cabinet='".$_SESSION["cabinet"]."' ".
						 "and numero='N$numero'";
					$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
					if(mysql_num_rows($res)==1){
						list($id)=mysql_fetch_row($res);
					}
					else{
						$id="";
					}
					
					if($id!=""){//Le dossier est reconnu
						$dateexam=explode("-", $date);
						$date_avant=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]-15, $dateexam[0]));
						$date_apres=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]+15, $dateexam[0]));

						$req2="SELECT date_exam, resultat1 from liste_exam where id='$id' ".
							  "and date_exam>'$date_avant' and date_exam<'$date_apres' ".
							  "and type_exam='LDL'";
						$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
						
						if(mysql_num_rows($res2)==0){//La donnée n'est pas présente dans asalée
							$req2="INSERT INTO liste_exam SET id='$id', ".
								  "date_exam='$date', resultat1='$val', ".
								  "type_exam='LDL'";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
							
							//Sauvegarde dans le compte-rendu
							$lok++;
							$worksheet_ok->write("A$lok", $id);
							$worksheet_ok->write("B$lok", "N$numero");
							$worksheet_ok->write("C$lok", $date);
							$worksheet_ok->write("D$lok", $val);
							$worksheet_ok->write("E$lok", "LDL");
						}
						else{//Sauvegarde dans le compte-rendu
							$lbis++;
							list($date_exam, $resultat1)=mysql_fetch_row($res2);
							$worksheet_bis->write("A$lbis", "$id");
							$worksheet_bis->write("B$lbis", "N$numero");
							$worksheet_bis->write("C$lbis", "$date");
							$worksheet_bis->write("D$lbis", $val);
							$worksheet_bis->write("E$lbis", $date_exam);
							$worksheet_bis->write("F$lbis", $resultat1);
							$worksheet_bis->write("G$lbis", "LDL");
						}
					}
					else{//Dossier non reconnu => écriture dans le compte-rendu
						$lko++;
						$worksheet_ko->write("A$lko", "");
						$worksheet_ko->write_string("B$lko", "$numero");
						$worksheet_ko->write("C$lko", "$date");
						$worksheet_ko->write("D$lko", $val);
						$worksheet_ko->write("E$lko", "LDL");
						$worksheet_ko->write("F$lko", "Dossier non trouvé dans asalée");
					}
				}
			}

			if(($type=="hdl cholesterol")||($type=="PA_HDL g/L")){//Il s'agit d'un examen HDL => intégration dans la table liste_exam
				$date=explode("/", $date);
				if($date[0]<10){
					$date[0]="0".$date[0];
				}
				if($date[1]<10){
					$date[1]="0".$date[1];
				}
				if($date[2]<10){
					$date[2]="200".$date[2];
				}
				elseif($date[2]<15){
					$date[2]="20".$date[2];
				}
				else{
					$date[2]="19".$date[2];
				}
				
				$date=$date[2]."-".$date[1]."-".$date[0];
				
				if($date>"2004-03-31"){
					$req="SELECT id from dossier WHERE cabinet='".$_SESSION["cabinet"]."' ".
						 "and numero='N$numero'";
					$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
					if(mysql_num_rows($res)==1){
						list($id)=mysql_fetch_row($res);
					}
					else{
						$id="";
					}
					
					if($id!=""){//Le dossier est reconnu
						$dateexam=explode("-", $date);
						$date_avant=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]-15, $dateexam[0]));
						$date_apres=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]+15, $dateexam[0]));

						$req2="SELECT date_exam, resultat1 from liste_exam where id='$id' ".
							  "and date_exam>'$date_avant' and date_exam<'$date_apres' ".
							  "and type_exam='HDL'";
						$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
						
						if(mysql_num_rows($res2)==0){//La donnée n'est pas présente dans asalée
							$req2="INSERT INTO liste_exam SET id='$id', ".
								  "date_exam='$date', resultat1='$val', ".
								  "type_exam='HDL'";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
							
							//Sauvegarde dans le compte-rendu
							$lok++;
							$worksheet_ok->write("A$lok", $id);
							$worksheet_ok->write("B$lok", "N$numero");
							$worksheet_ok->write("C$lok", $date);
							$worksheet_ok->write("D$lok", $val);
							$worksheet_ok->write("E$lok", "HDL");
						}
						else{//Sauvegarde dans le compte-rendu
							$lbis++;
							list($date_exam, $resultat1)=mysql_fetch_row($res2);
							$worksheet_bis->write("A$lbis", "$id");
							$worksheet_bis->write("B$lbis", "N$numero");
							$worksheet_bis->write("C$lbis", "$date");
							$worksheet_bis->write("D$lbis", $val);
							$worksheet_bis->write("E$lbis", $date_exam);
							$worksheet_bis->write("F$lbis", $resultat1);
							$worksheet_bis->write("G$lbis", "HDL");
						}
					}
					else{//Dossier non reconnu => écriture dans le compte-rendu
						$lko++;
						$worksheet_ko->write("A$lko", "");
						$worksheet_ko->write_string("B$lko", "$numero");
						$worksheet_ko->write("C$lko", "$date");
						$worksheet_ko->write("D$lko", $val);
						$worksheet_ko->write("E$lko", "HDL");
						$worksheet_ko->write("F$lko", "Dossier non trouvé dans asalée");
					}
				}
			}
			
			if(($type=="choles.total*")||($type=="PA_CHOLEST.TOTAL g/L")){//Il s'agit d'un examen chol total => intégration dans la table liste_exam
				$date=explode("/", $date);
				if($date[0]<10){
					$date[0]="0".$date[0];
				}
				if($date[1]<10){
					$date[1]="0".$date[1];
				}
				if($date[2]<10){
					$date[2]="200".$date[2];
				}
				elseif($date[2]<15){
					$date[2]="20".$date[2];
				}
				else{
					$date[2]="19".$date[2];
				}
				
				$date=$date[2]."-".$date[1]."-".$date[0];
				
				if($date>"2004-03-31"){
					$req="SELECT id from dossier WHERE cabinet='".$_SESSION["cabinet"]."' ".
						 "and numero='N$numero'";
					$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
					if(mysql_num_rows($res)==1){
						list($id)=mysql_fetch_row($res);
					}
					else{
						$id="";
					}
					
					if($id!=""){//Le dossier est reconnu
						$dateexam=explode("-", $date);
						$date_avant=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]-15, $dateexam[0]));
						$date_apres=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]+15, $dateexam[0]));

						$req2="SELECT date_exam, resultat1 from liste_exam where id='$id' ".
							  "and date_exam>'$date_avant' and date_exam<'$date_apres' ".
							  "and type_exam='Chol'";
						$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
						
						if(mysql_num_rows($res2)==0){//La donnée n'est pas présente dans asalée
							$req2="INSERT INTO liste_exam SET id='$id', ".
								  "date_exam='$date', resultat1='$val', ".
								  "type_exam='Chol'";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
							
							//Sauvegarde dans le compte-rendu
							$lok++;
							$worksheet_ok->write("A$lok", $id);
							$worksheet_ok->write("B$lok", "N$numero");
							$worksheet_ok->write("C$lok", $date);
							$worksheet_ok->write("D$lok", $val);
							$worksheet_ok->write("E$lok", "cholestérol total");
						}
						else{//Sauvegarde dans le compte-rendu
							$lbis++;
							list($date_exam, $resultat1)=mysql_fetch_row($res2);
							$worksheet_bis->write("A$lbis", "$id");
							$worksheet_bis->write("B$lbis", "N$numero");
							$worksheet_bis->write("C$lbis", "$date");
							$worksheet_bis->write("D$lbis", $val);
							$worksheet_bis->write("E$lbis", $date_exam);
							$worksheet_bis->write("F$lbis", $resultat1);
							$worksheet_bis->write("G$lbis", "Cholestérol total");
						}
					}
					else{//Dossier non reconnu => écriture dans le compte-rendu
						$lko++;
						$worksheet_ko->write("A$lko", "");
						$worksheet_ko->write_string("B$lko", "$numero");
						$worksheet_ko->write("C$lko", "$date");
						$worksheet_ko->write("D$lko", $val);
						$worksheet_ko->write("E$lko", "Cholestérol total");
						$worksheet_ko->write("F$lko", "Dossier non trouvé dans asalée");
					}
				}
			}
			
			if(($type=="triglycerides*")||($type=="PA: TRIGLY.   g/L")){//Il s'agit d'un examen triglycerides => intégration dans la table liste_exam
				$date=explode("/", $date);
				if($date[0]<10){
					$date[0]="0".$date[0];
				}
				if($date[1]<10){
					$date[1]="0".$date[1];
				}
				if($date[2]<10){
					$date[2]="200".$date[2];
				}
				elseif($date[2]<15){
					$date[2]="20".$date[2];
				}
				else{
					$date[2]="19".$date[2];
				}
				
				$date=$date[2]."-".$date[1]."-".$date[0];
				
				if($date>"2004-03-31"){
					$req="SELECT id from dossier WHERE cabinet='".$_SESSION["cabinet"]."' ".
						 "and numero='N$numero'";
					$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
					if(mysql_num_rows($res)==1){
						list($id)=mysql_fetch_row($res);
					}
					else{
						$id="";
					}
					
					if($id!=""){//Le dossier est reconnu
						$dateexam=explode("-", $date);
						$date_avant=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]-15, $dateexam[0]));
						$date_apres=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]+15, $dateexam[0]));

						$req2="SELECT date_exam, resultat1 from liste_exam where id='$id' ".
							  "and date_exam>'$date_avant' and date_exam<'$date_apres' ".
							  "and type_exam='triglycerides'";
						$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
						
						if(mysql_num_rows($res2)==0){//La donnée n'est pas présente dans asalée
							$req2="INSERT INTO liste_exam SET id='$id', ".
								  "date_exam='$date', resultat1='$val', ".
								  "type_exam='triglycerides'";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
							
							//Sauvegarde dans le compte-rendu
							$lok++;
							$worksheet_ok->write("A$lok", $id);
							$worksheet_ok->write("B$lok", "N$numero");
							$worksheet_ok->write("C$lok", $date);
							$worksheet_ok->write("D$lok", $val);
							$worksheet_ok->write("E$lok", "triglycerides");
						}
						else{//Sauvegarde dans le compte-rendu
							$lbis++;
							list($date_exam, $resultat1)=mysql_fetch_row($res2);
							$worksheet_bis->write("A$lbis", "$id");
							$worksheet_bis->write("B$lbis", "N$numero");
							$worksheet_bis->write("C$lbis", "$date");
							$worksheet_bis->write("D$lbis", $val);
							$worksheet_bis->write("E$lbis", $date_exam);
							$worksheet_bis->write("F$lbis", $resultat1);
							$worksheet_bis->write("G$lbis", "triglycerides");
						}
					}
					else{//Dossier non reconnu => écriture dans le compte-rendu
						$lko++;
						$worksheet_ko->write("A$lko", "");
						$worksheet_ko->write_string("B$lko", "$numero");
						$worksheet_ko->write("C$lko", "$date");
						$worksheet_ko->write("D$lko", $val);
						$worksheet_ko->write("E$lko", "triglycerides");
						$worksheet_ko->write("F$lko", "Dossier non trouvé dans asalée");
					}
				}
			}

			if(($type=="Pouls")){//Il s'agit d'un examen pouls => intégration dans la table liste_exam
				$date=explode("/", $date);
				if($date[0]<10){
					$date[0]="0".$date[0];
				}
				if($date[1]<10){
					$date[1]="0".$date[1];
				}
				if($date[2]<10){
					$date[2]="200".$date[2];
				}
				elseif($date[2]<15){
					$date[2]="20".$date[2];
				}
				else{
					$date[2]="19".$date[2];
				}
				
				$date=$date[2]."-".$date[1]."-".$date[0];
				
				if($date>"2004-03-31"){
					$req="SELECT id from dossier WHERE cabinet='".$_SESSION["cabinet"]."' ".
						 "and numero='N$numero'";
					$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
					if(mysql_num_rows($res)==1){
						list($id)=mysql_fetch_row($res);
					}
					else{
						$id="";
					}
					
					if($id!=""){//Le dossier est reconnu
						$dateexam=explode("-", $date);
						$date_avant=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]-15, $dateexam[0]));
						$date_apres=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]+15, $dateexam[0]));

						$req2="SELECT date_exam, resultat1 from liste_exam where id='$id' ".
							  "and date_exam>'$date_avant' and date_exam<'$date_apres' ".
							  "and type_exam='pouls'";
						$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
						
						if(mysql_num_rows($res2)==0){//La donnée n'est pas présente dans asalée
							$req2="INSERT INTO liste_exam SET id='$id', ".
								  "date_exam='$date', resultat1='$val', ".
								  "type_exam='pouls'";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
							
							//Sauvegarde dans le compte-rendu
							$lok++;
							$worksheet_ok->write("A$lok", $id);
							$worksheet_ok->write("B$lok", "N$numero");
							$worksheet_ok->write("C$lok", $date);
							$worksheet_ok->write("D$lok", $val);
							$worksheet_ok->write("E$lok", "pouls");
						}
						else{//Sauvegarde dans le compte-rendu
							$lbis++;
							list($date_exam, $resultat1)=mysql_fetch_row($res2);
							$worksheet_bis->write("A$lbis", "$id");
							$worksheet_bis->write("B$lbis", "N$numero");
							$worksheet_bis->write("C$lbis", "$date");
							$worksheet_bis->write("D$lbis", $val);
							$worksheet_bis->write("E$lbis", $date_exam);
							$worksheet_bis->write("F$lbis", $resultat1);
							$worksheet_bis->write("G$lbis", "pouls");
						}
					}
					else{//Dossier non reconnu => écriture dans le compte-rendu
						$lko++;
						$worksheet_ko->write("A$lko", "");
						$worksheet_ko->write_string("B$lko", "$numero");
						$worksheet_ko->write("C$lko", "$date");
						$worksheet_ko->write("D$lko", $val);
						$worksheet_ko->write("E$lko", "pouls");
						$worksheet_ko->write("F$lko", "Dossier non trouvé dans asalée");
					}
				}
			}

			if(($type=="PA_CREATININ.SG.mg/L")||($type=="Creatinine")){//Il s'agit d'un examen creat => intégration dans la table liste_exam
				$date=explode("/", $date);
				if($date[0]<10){
					$date[0]="0".$date[0];
				}
				if($date[1]<10){
					$date[1]="0".$date[1];
				}
				if($date[2]<10){
					$date[2]="200".$date[2];
				}
				elseif($date[2]<15){
					$date[2]="20".$date[2];
				}
				else{
					$date[2]="19".$date[2];
				}
				
				$date=$date[2]."-".$date[1]."-".$date[0];
				
				if($date>"2004-03-31"){
					$req="SELECT id from dossier WHERE cabinet='".$_SESSION["cabinet"]."' ".
						 "and numero='N$numero'";
					$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
					if(mysql_num_rows($res)==1){
						list($id)=mysql_fetch_row($res);
					}
					else{
						$id="";
					}
					
					if($id!=""){//Le dossier est reconnu
						$dateexam=explode("-", $date);
						$date_avant=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]-15, $dateexam[0]));
						$date_apres=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]+15, $dateexam[0]));

						$req2="SELECT date_exam, resultat1 from liste_exam where id='$id' ".
							  "and date_exam>'$date_avant' and date_exam<'$date_apres' ".
							  "and type_exam='creat'";
						$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
						
						if(mysql_num_rows($res2)==0){//La donnée n'est pas présente dans asalée
							$req2="INSERT INTO liste_exam SET id='$id', ".
								  "date_exam='$date', resultat1='$val', ".
								  "type_exam='creat'";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
							
							//Sauvegarde dans le compte-rendu
							$lok++;
							$worksheet_ok->write("A$lok", $id);
							$worksheet_ok->write("B$lok", "N$numero");
							$worksheet_ok->write("C$lok", $date);
							$worksheet_ok->write("D$lok", $val);
							$worksheet_ok->write("E$lok", "créatinine");
						}
						else{//Sauvegarde dans le compte-rendu
							$lbis++;
							list($date_exam, $resultat1)=mysql_fetch_row($res2);
							$worksheet_bis->write("A$lbis", "$id");
							$worksheet_bis->write("B$lbis", "N$numero");
							$worksheet_bis->write("C$lbis", "$date");
							$worksheet_bis->write("D$lbis", $val);
							$worksheet_bis->write("E$lbis", $date_exam);
							$worksheet_bis->write("F$lbis", $resultat1);
							$worksheet_bis->write("G$lbis", "créatinine");
						}
					}
					else{//Dossier non reconnu => écriture dans le compte-rendu
						$lko++;
						$worksheet_ko->write("A$lko", "");
						$worksheet_ko->write_string("B$lko", "$numero");
						$worksheet_ko->write("C$lko", "$date");
						$worksheet_ko->write("D$lko", $val);
						$worksheet_ko->write("E$lko", "créatinine");
						$worksheet_ko->write("F$lko", "Dossier non trouvé dans asalée");
					}
				}
			}
			
			if(($type=="Glycémie")||($type=="gly. à jeun *")||($type=="PA_GLY/JN FLUOR  g/L")){//Il s'agit d'un examen glycémie => intégration dans la table liste_exam
				$date=explode("/", $date);
				if($date[0]<10){
					$date[0]="0".$date[0];
				}
				if($date[1]<10){
					$date[1]="0".$date[1];
				}
				if($date[2]<10){
					$date[2]="200".$date[2];
				}
				elseif($date[2]<15){
					$date[2]="20".$date[2];
				}
				else{
					$date[2]="19".$date[2];
				}
				
				$date=$date[2]."-".$date[1]."-".$date[0];
				
				if($date>"2004-03-31"){
					$req="SELECT id from dossier WHERE cabinet='".$_SESSION["cabinet"]."' ".
						 "and numero='N$numero'";
					$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
					if(mysql_num_rows($res)==1){
						list($id)=mysql_fetch_row($res);
					}
					else{
						$id="";
					}
					
					if($id!=""){//Le dossier est reconnu
						$dateexam=explode("-", $date);
						$date_avant=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]-15, $dateexam[0]));
						$date_apres=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]+15, $dateexam[0]));

						$req2="SELECT date_exam, resultat1 from liste_exam where id='$id' ".
							  "and date_exam>'$date_avant' and date_exam<'$date_apres' ".
							  "and type_exam='glycemie'";
						$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
						
						if(mysql_num_rows($res2)==0){//La donnée n'est pas présente dans asalée
							$req2="INSERT INTO liste_exam SET id='$id', ".
								  "date_exam='$date', resultat1='$val', ".
								  "type_exam='glycemie'";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
							
							//Sauvegarde dans le compte-rendu
							$lok++;
							$worksheet_ok->write("A$lok", $id);
							$worksheet_ok->write("B$lok", "N$numero");
							$worksheet_ok->write("C$lok", $date);
							$worksheet_ok->write("D$lok", $val);
							$worksheet_ok->write("E$lok", "glycémie");
						}
						else{//Sauvegarde dans le compte-rendu
							$lbis++;
							list($date_exam, $resultat1)=mysql_fetch_row($res2);
							$worksheet_bis->write("A$lbis", "$id");
							$worksheet_bis->write("B$lbis", "N$numero");
							$worksheet_bis->write("C$lbis", "$date");
							$worksheet_bis->write("D$lbis", $val);
							$worksheet_bis->write("E$lbis", $date_exam);
							$worksheet_bis->write("F$lbis", $resultat1);
							$worksheet_bis->write("G$lbis", "glycémie");
						}
					}
					else{//Dossier non reconnu => écriture dans le compte-rendu
						$lko++;
						$worksheet_ko->write("A$lko", "");
						$worksheet_ko->write_string("B$lko", "$numero");
						$worksheet_ko->write("C$lko", "$date");
						$worksheet_ko->write("D$lko", $val);
						$worksheet_ko->write("E$lko", "glycémie");
						$worksheet_ko->write("F$lko", "Dossier non trouvé dans asalée");
					}
				}
			}
			
			if(($type=="monofilament")){//Il s'agit d'un examen monofilament => intégration dans la table liste_exam
				$date=explode("/", $date);
				if($date[0]<10){
					$date[0]="0".$date[0];
				}
				if($date[1]<10){
					$date[1]="0".$date[1];
				}
				if($date[2]<10){
					$date[2]="200".$date[2];
				}
				elseif($date[2]<15){
					$date[2]="20".$date[2];
				}
				else{
					$date[2]="19".$date[2];
				}
				
				$date=$date[2]."-".$date[1]."-".$date[0];
				
				if($date>"2004-03-31"){
					$req="SELECT id from dossier WHERE cabinet='".$_SESSION["cabinet"]."' ".
						 "and numero='N$numero'";
					$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
					if(mysql_num_rows($res)==1){
						list($id)=mysql_fetch_row($res);
					}
					else{
						$id="";
					}
					
					if($id!=""){//Le dossier est reconnu
						$dateexam=explode("-", $date);
						$date_avant=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]-15, $dateexam[0]));
						$date_apres=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]+15, $dateexam[0]));

						$req2="SELECT date_exam, resultat1 from liste_exam where id='$id' ".
							  "and date_exam>'$date_avant' and date_exam<'$date_apres' ".
							  "and type_exam='monofil'";
						$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
						
						if(mysql_num_rows($res2)==0){//La donnée n'est pas présente dans asalée
							$req2="INSERT INTO liste_exam SET id='$id', ".
								  "date_exam='$date', ".
								  "type_exam='monofil'";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
							
							//Sauvegarde dans le compte-rendu
							$lok++;
							$worksheet_ok->write("A$lok", $id);
							$worksheet_ok->write("B$lok", "N$numero");
							$worksheet_ok->write("C$lok", $date);
							// $worksheet_ok->write("D$lok", $val);
							$worksheet_ok->write("E$lok", "monofil");
						}
						else{//Sauvegarde dans le compte-rendu
							$lbis++;
							list($date_exam, $resultat1)=mysql_fetch_row($res2);
							$worksheet_bis->write("A$lbis", "$id");
							$worksheet_bis->write("B$lbis", "N$numero");
							$worksheet_bis->write("C$lbis", "$date");
							// $worksheet_bis->write("D$lbis", $val);
							$worksheet_bis->write("E$lbis", $date_exam);
							// $worksheet_bis->write("F$lbis", $resultat1);
							$worksheet_bis->write("G$lbis", "monofil");
						}
					}
					else{//Dossier non reconnu => écriture dans le compte-rendu
						$lko++;
						$worksheet_ko->write("A$lko", "");
						$worksheet_ko->write_string("B$lko", "$numero");
						$worksheet_ko->write("C$lko", "$date");
						// $worksheet_ko->write("D$lko", $val);
						$worksheet_ko->write("E$lko", "monofil");
						$worksheet_ko->write("F$lko", "Dossier non trouvé dans asalée");
					}
				}
			}
			
			if(($type=="etat des pieds")){//Il s'agit d'un examen des pieds => intégration dans la table liste_exam
				$date=explode("/", $date);
				if($date[0]<10){
					$date[0]="0".$date[0];
				}
				if($date[1]<10){
					$date[1]="0".$date[1];
				}
				if($date[2]<10){
					$date[2]="200".$date[2];
				}
				elseif($date[2]<15){
					$date[2]="20".$date[2];
				}
				else{
					$date[2]="19".$date[2];
				}
				
				$date=$date[2]."-".$date[1]."-".$date[0];
				
				if($date>"2004-03-31"){
					$req="SELECT id from dossier WHERE cabinet='".$_SESSION["cabinet"]."' ".
						 "and numero='N$numero'";
					$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
					if(mysql_num_rows($res)==1){
						list($id)=mysql_fetch_row($res);
					}
					else{
						$id="";
					}
					
					if($id!=""){//Le dossier est reconnu
						$dateexam=explode("-", $date);
						$date_avant=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]-15, $dateexam[0]));
						$date_apres=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]+15, $dateexam[0]));

						$req2="SELECT date_exam, resultat1 from liste_exam where id='$id' ".
							  "and date_exam>'$date_avant' and date_exam<'$date_apres' ".
							  "and type_exam='pied'";
						$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
						
						if(mysql_num_rows($res2)==0){//La donnée n'est pas présente dans asalée
							$req2="INSERT INTO liste_exam SET id='$id', ".
								  "date_exam='$date', ".
								  "type_exam='pied'";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
							
							//Sauvegarde dans le compte-rendu
							$lok++;
							$worksheet_ok->write("A$lok", $id);
							$worksheet_ok->write("B$lok", "N$numero");
							$worksheet_ok->write("C$lok", $date);
							// $worksheet_ok->write("D$lok", $val);
							$worksheet_ok->write("E$lok", "examen des pieds");
						}
						else{//Sauvegarde dans le compte-rendu
							$lbis++;
							list($date_exam, $resultat1)=mysql_fetch_row($res2);
							$worksheet_bis->write("A$lbis", "$id");
							$worksheet_bis->write("B$lbis", "N$numero");
							$worksheet_bis->write("C$lbis", "$date");
							// $worksheet_bis->write("D$lbis", $val);
							$worksheet_bis->write("E$lbis", $date_exam);
							// $worksheet_bis->write("F$lbis", $resultat1);
							$worksheet_bis->write("G$lbis", "examen des pieds");
						}
					}
					else{//Dossier non reconnu => écriture dans le compte-rendu
						$lko++;
						$worksheet_ko->write("A$lko", "");
						$worksheet_ko->write_string("B$lko", "$numero");
						$worksheet_ko->write("C$lko", "$date");
						// $worksheet_ko->write("D$lko", $val);
						$worksheet_ko->write("E$lko", "examen des pieds");
						$worksheet_ko->write("F$lko", "Dossier non trouvé dans asalée");
					}
				}
			}

			$numero=$date=$type=$val="";
		}
		else{//Tous les caractères "normaux"
			if($zone==0){
				$numero.=$car;
			}
			if($zone==1){
				$date=$date.$car;
			}
			if($zone==2){
				$type=$type.$car;
			}
			if($zone==3){
				if((is_numeric($car))||($car==".")){
					$val=$val.$car;
				}
			}
		}
	}

	$workbook->close();
	
	return($fich);
}