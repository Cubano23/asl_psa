<?php

function integration_chef1($fichier){

	$fich="./log/Compte-rendu integration ".$_SESSION["cabinet"]." ".date("d-m-Y").".xls";
	$workbook =& new writeexcel_workbookbig($fich); // on lui passe en paramètre le chemin de notre fichier
	
	$worksheet_ok =& $workbook->addworksheet("données intégrées");
	$worksheet_ok->write("A1", "Id dans asalée");
	$worksheet_ok->write("B1", "n° dossier");
	$worksheet_ok->write("C1", "date");
	$worksheet_ok->write("D1", "valeur");
	$worksheet_ok->write("E1", "type examen");

	$lok=1;

	$worksheet_ko =& $workbook->addworksheet("données non intégrées");
	$worksheet_ko->write("A1", "Id dans asalée");
	$worksheet_ko->write("B1", "n° dossier");
	$worksheet_ko->write("C1", "date");
	$worksheet_ko->write("D1", "valeur");
	$worksheet_ko->write("E1", "type examen");
	$worksheet_ko->write("F1", "Raison non intégration");

	$lko=1;
	$fp=fopen("$fichier", "r");
	
	$numero=$date=$type=$val="";
	
	$mots=array("Cholestérol H.D.L."=>"HDL",
				"CHOLESTEROL HDL..........: (g/l)"=>"HDL",
				"Cholestérol L.D.L."=>"LDL",
				"CHOLESTEROL LDL .........: (g/l)"=>"LDL",
				"HDL"=>"HDL",
				"HDL CHOLESTEROL (g/l)"=>"HDL",
				"HDL CI8200"=>"HDL",
				"LDL"=>"LDL",
				"LDL CHOLESTEROL (g/l)"=>"LDL",
				"POIDS"=>"poids",
				"Poids"=>"poids",
				"Hb GLYCOSYLEE (%)"=>"HBA1c",
				"Hémoglobine A1C"=>"HBA1c",
				"CREATININE CI8200"=>"creat",
				"GLYCEMIE A JEUN FLUOR"=>"glycemie",
				"TRIGLYCERIDES CI8200"=>"triglycerides",
				"TRIGLYCERIDES (g/l)"=>"triglycerides",
				"CHOLESTEROL (g/l)"=>"Chol",
				"GLYCEMIE A JEUN (g/l)"=>"glycemie",
				"Créatinine"=>"creat",
				"Glycémie"=>"glycemie",
				"Cholestérol"=>"Chol");

	$remplace=array("HDL"=>" g/l",
					"LDL"=>" g/l");
					
	$rapport=array("Chol", "LDL", "HDL", "glycemie", "HBA1c");
					
	while($ligne=fgets($fp)){
		$ligne=str_replace("\n", "", $ligne);
		$ligne=str_replace("\r", "", $ligne);
		
		$ligne=explode("\t", $ligne);
		
		$numero=$ligne[0];
		$date=$ligne[1];
		$type=$ligne[2];
		$val=$ligne[3];
		
			
			if(isset($mots[$type])){//Il s'agit d'un examen reconnu => intégration dans une table temporaire
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
				elseif($date[2]<45){
					$date[2]="20".$date[2];
				}
				else{
					$date[2]="19".$date[2];
				}
				
				$date=$date[2]."-".$date[1]."-".$date[0];
				if($date>"2004-03-31"){
					$req="SELECT id from dossier WHERE cabinet='".$_SESSION["cabinet"]."' ".
						 "and numero='$numero'";
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
								  "and type_exam='".$mots[$type]."'";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
							
							if(mysql_num_rows($res2)==0){//La donnée n'est pas présente dans asalée
								if(isset($remplace[$type])){//L'unité est indiquée dans la valeur
									$val=str_replace($remplace[$type], "", $val);
								}
								
								$req2="INSERT INTO liste_exam SET id='$id', ".
									  "date_exam='$date', resultat1='$val', ".
									  "type_exam='".$mots[$type]."'";
									  // echo $req2;
								$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
								
								//Sauvegarde dans le compte-rendu
								$lok++;
								$worksheet_ok->write("A$lok", $id);
								$worksheet_ok->write("B$lok", "$numero");
								$worksheet_ok->write("C$lok", $date);
								$worksheet_ok->write("D$lok", $val);
								$worksheet_ok->write("E$lok", $mots[$type]);
							}

					}
					else{//Dossier non reconnu => écriture dans le compte-rendu
						if(in_array($mots[$type], $rapport)){
							$lko++;
							$worksheet_ko->write("A$lko", "");
							$worksheet_ko->write_string("B$lko", "$numero");
							$worksheet_ko->write("C$lko", "$date");
							$worksheet_ko->write("D$lko", $val);
							$worksheet_ko->write("E$lko", $mots[$type]);
							$worksheet_ko->write("F$lko", "Dossier non trouvé dans asalée");
						}
					}
				}
			}
			
			$numero=$date=$type=$val="";
	}

	$workbook->close();
	
	return($fich);
}