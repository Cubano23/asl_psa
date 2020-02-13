<?php
function integration_segonzac($fichier){

	$fich="./log/Compte-rendu integration ".$_SESSION["cabinet"]." ".date("d-m-Y").".xls";
	$workbook =& new writeexcel_workbookbig($fich); // on lui passe en paramètre le chemin de notre fichier
	
	//Preparation du fichier de compte-rendu
	$worksheet_ok =& $workbook->addworksheet("donnees integrees");
	$worksheet_ok->write("A1", "Id dans asalee");
	$worksheet_ok->write("B1", "n° dossier");
	$worksheet_ok->write("C1", "date");
	$worksheet_ok->write("D1", "valeur");
	$worksheet_ok->write("E1", "type examen");

	$lok=1;
	
	$worksheet_bis =& $workbook->addworksheet("donnees dejà presentes");
	$worksheet_bis->write("A1", "Id dans asalee");
	$worksheet_bis->write("B1", "n° dossier");
	$worksheet_bis->write("C1", "date dans export");
	$worksheet_bis->write("D1", "valeur dans export");
	$worksheet_bis->write("E1", "date dans asalee");
	$worksheet_bis->write("F1", "valeur dans asalee");
	$worksheet_bis->write("G1", "type examen");

	$lbis=1;
	
	$worksheet_ko =& $workbook->addworksheet("donnees non integrees");
	$worksheet_ko->write("A1", "Id dans asalee");
	$worksheet_ko->write("B1", "n° dossier");
	$worksheet_ko->write("C1", "date");
	$worksheet_ko->write("D1", "valeur");
	$worksheet_ko->write("E1", "type examen");
	$worksheet_ko->write("F1", "Raison non integration");

	$lko=1;
	
	// $mots=array("HbA1c"=>"HBA1c",
	// 			"HB A1C"=>"HBA1c",
	// 			"HBA1C"=>"HBA1c",
	// 			"HDL Cholesterol"=>"HDL", 
	// 			"LDL cholesterol"=>"LDL", 
	// 			"LDL Cholesterol"=>"LDL",  
	// 			"Cholesterol Total"=>"Chol", 
	// 			"creatinine"=>"creat", 
	// 			"Creatinine"=>"creat",
	// 			"Cholesterol Total"=>"Chol", 
	// 			"creatinine"=>"creat", 
	// 			"Creatininemie"=>"creat",
	// 			"Glycemie à jeun"=>"glycemie",  
	// 			"Glycemie"=>"glycemie",
	// 			"Kaliemie"=>"kaliemie",
	// 			"Potassium"=>"kaliemie",
	// 			"Microalbuminurie"=>"albu",
	// 			"Triglycerides"=>"triglycerides"
	// 			);
	$mots=synonymes();// initialisation des synonymes dans Utils.php
	$fauxamis=fauxamis();// initialisation des fauxamis dans Utils.php
	$fauxamiscond=FALSE;	

	$mois=array("JAN"=>"01", "FEV"=>"02", "MAR"=>"03", "AVR"=>"04", "MAI"=>"05", "JUN"=>"06", 
				"JUI"=>"07", "AOU"=>"08", "SEP"=>"09", "OCT"=>"10", "NOV"=>"11", "DEC"=>"12");

	$fp=fopen("$fichier", "r");
	$ligne=fgets($fp);
	$ligne=explode("\t", $ligne);
	$type=slugify($ligne[10]);
	$type=str_replace("é", "e", $type);
	
	if(in_array($type, $fauxamis)){
		$fauxamiscond=FALSE;
	}
	else{
		$fauxamiscond=TRUE;
	}

	while($ligne=fgets($fp)){
		
		$ligne=explode("\t", $ligne);
		$numero=$ligne[9];
		$val=$ligne[10];
		$date=$ligne[11];
	
		$val=str_replace(",", ".", $val);
		$val=str_replace(" ", "", $val);
		$numero=str_replace(" ", "", $numero);
		
		if($fauxamiscond){
			if(strpos($date, "/")!==false){//la date est au format jj/mm/aaaa
				
				$date=explode("/", $date);
				
				$date=$date[2]."-".$date[1]."-".$date[0];
			}
			else{//date au format jj mois_en_lettres annee
				$date=explode(" ", $date);
				
				if(isset($mois[$date[1]])){
					$date=$date[2]."-".$mois[$date[1]]."-".$date[0];
				}
				else{
					$date="";
				}
			}

			$date=str_replace(" ", "", $date);
			$date=str_replace("\r", "", $date);
			$date=str_replace("\n", "", $date);
			$date=str_replace("\t", "", $date);

			if($date!=""){
				if($numero!=0){
					$req="SELECT id from dossier WHERE cabinet='".$_SESSION["cabinet"]."' ".
						 "and numero='$numero'";
					$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
					
					if(mysql_num_rows($res)==1){
						list($id)=mysql_fetch_row($res);
					}
					else{
						$id="";
					}
				}
				else{
					$id="";
					#$numero=$ligne[1]." ".$ligne[2];//ici on recup le nom et prenom du patient, donc on commente
					$numero = "Pas de numero";
				}
				
				if($id!=""){//Le dossier est reconnu

					$dateexam=explode("-", $date);
					$date_avant=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]-15, $dateexam[0]));
					$date_apres=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]+15, $dateexam[0]));
					
					if(($val>0)||($mots[$type]=="albu")){
						$req2="SELECT date_exam, resultat1 from liste_exam where id='$id' ".
							  "and date_exam>'$date_avant' and date_exam<'$date_apres' ".
							  "and type_exam='".$mots[$type]."'";
						$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
						
						if(mysql_num_rows($res2)==0){//La donnee n'est pas presente dans asalee
								
							$req2="INSERT INTO liste_exam SET id='$id', ".
								  "date_exam='$date', resultat1='$val', ".
								  "type_exam='".$mots[$type]."'";
								  // echo $req2."<br>";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
							
							//Sauvegarde dans le compte-rendu
							$lok++;
							$worksheet_ok->write("A$lok", $id);
							$worksheet_ok->write("B$lok", "$numero");
							$worksheet_ok->write("C$lok", $date);
							$worksheet_ok->write("D$lok", $val);
							$worksheet_ok->write("E$lok", $mots[$type]);
						}
						else{//Sauvegarde dans le compte-rendu
							$lbis++;
							
							list($date_exam, $resultat1)=mysql_fetch_row($res2);
							
							$resultat1=str_replace("=", "", $resultat1);
							$worksheet_bis->write("A$lbis", "$id");
							$worksheet_bis->write("B$lbis", "$numero");
							$worksheet_bis->write("C$lbis", "$date");
							$worksheet_bis->write("D$lbis", $val);
							$worksheet_bis->write("E$lbis", $date_exam);
							$worksheet_bis->write("F$lbis", $resultat1);
							$worksheet_bis->write("G$lbis", $mots[$type]);
						}
					}
				}
				else{//Dossier non reconnu => ecriture dans le compte-rendu

					$lko++;
					$worksheet_ko->write("A$lko", "");
					$worksheet_ko->write_string("B$lko", "$numero");
					$worksheet_ko->write("C$lko", "$date");
					$worksheet_ko->write("D$lko", $val);
					$worksheet_ko->write("E$lko", $mots[$type]);
					
					#if(!is_numeric($numero)){
						#$worksheet_ko->write("F$lko", "Pas de numero pour le patient");
					#}
					#else{
						$worksheet_ko->write("F$lko", "Dossier non trouve dans asalee");
					#}
				}
			}
		}	

	}

	$workbook->close();
	
	return($fich);
}