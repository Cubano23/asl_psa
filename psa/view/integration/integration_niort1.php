<?php

function integration_niort1($fichier){


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
	
	$mots=array("Microalbumine urinai"=>"albu",
				"CHOLESTEROL TOTAL (g/l)"=>"Chol",
				"CHOLESTEROL"=>"Chol",
				"HDL Cholestérol (g/l)"=>"HDL",
				"LDL Cholestérol (g/l)"=>"LDL",
				"CREATININE (mg/l)"=>"creat",
				"CREATININE"=>"creat",
				"GLYCEMIE à jeun (g/l)"=>"glycemie",
				"GLYCEMIE"=>"glycemie",
				"HEMOGLOBINE A1C (%)"=>"HBA1c",
				"H‚moglobine glycosyl‚e."=>"HBA1c",
				"Hémoglobine glycosylée"=>"HBA1c",
				"hba1c"=>"HBA1c",
				"HbA1c"=>"HBA1c",
				"HBA1C"=>"HBA1c",
				"POTASSIUM"=>"kaliemie",
				"TRIGLYCERIDES"=>"triglycerides");

	$remplace=array("HDL"=>" g/l",
					"LDL"=>" g/l");
					
	$rapport=array("Chol", "LDL", "HDL", "triglycerides", "HBA1c", "glycemie", "kaliemie");
					
	$debut=1;
	$recherche=0;
	$date1=$date2=$feuille="";
	
	$ligne=fgets($fp);
	
	while($ligne=fgets($fp)){
		$ligne=str_replace("\n", "", $ligne);
		$ligne=str_replace("\r", "", $ligne);
		// print_r($ligne);die;
		$tab_ligne=explode(";", $ligne);
		
		if(isset($tab_ligne[10])&&($feuille!="")){
			if($date_exam>"2004-03-31"){
				$feuille=explode("<br>", $feuille);
				
				$dossier=$feuille[count($feuille)-1];
				
				$dossier=explode(";", $dossier);
				$dossier=$dossier[1];
				$dossier=str_replace(" ", "", $dossier);
				$dossier=str_replace("\n", "", $dossier);
				$dossier=str_replace("\r", "", $dossier);
				
				if($dossier!=""){//N° de dossier renseigné
					$req="SELECT id from dossier where cabinet='".$_SESSION["cabinet"]."' and numero='$dossier'";
					$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
					
					list($id)=mysql_fetch_row($res);
					
					foreach($feuille as $l){
						foreach($mots as $exam=>$code){
							if(strpos($l, $exam)!==false){//Le mot clé est indiqué dans la ligne
								// echo $ligne."<br>";
								$test=str_replace($exam, "", $l);
								$test=str_replace(",", ".", $test);
								$test=explode(" ", $test);
								$resultat=0;
								$integ=0;
								
								foreach($test as $valeur){
									if(($integ==0)&&($resultat==0)&&(is_numeric($valeur))&&($valeur!="")){//Il s'agit de la valeur de l'exam
										if($date_exam==""){
											if(in_array($mots[$exam], $rapport)){
												$lko++;
												$worksheet_ko->write("A$lko", "$id");
												$worksheet_ko->write_string("B$lko", "$dossier");
												$worksheet_ko->write("C$lko", "$date_exam");
												$worksheet_ko->write("D$lko", $valeur);
												$worksheet_ko->write("E$lko", $mots[$exam]);
												$worksheet_ko->write("F$lko", "Date d'examen non trouvée");
											}
										}
										else{
											if($id!=""){//Le dossier est reconnu
												if($mots[$exam]=="ECG"){
													$valeur="";
												}

												$date_avant=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]-15, $dateexam[0]));
												$date_apres=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]+15, $dateexam[0]));
												
												$req2="SELECT date_exam, resultat1 from liste_exam where id='$id' ".
													  "and date_exam>'$date_avant' and date_exam<'$date_apres' ".
													  "and type_exam='".$mots[$exam]."'";
												$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
												
												if(mysql_num_rows($res2)==0){//La donnée n'est pas présente dans asalée
													if(($mots[$exam]!="ECG")&&(!is_numeric($valeur))){
														$lko++;
														$worksheet_ko->write("A$lko", "$id");
														$worksheet_ko->write_string("B$lko", "$dossier");
														$worksheet_ko->write("C$lko", "$date_exam");
														$worksheet_ko->write("D$lko", $valeur);
														$worksheet_ko->write("E$lko", $mots[$exam]);
														$worksheet_ko->write("F$lko", "Résultat non conforme");
													}
													else{
														if($mots[$exam]=="albu"){
															$val=$valeur;
															if($valeur<20){
																$valeur=0;
															}
															else{
																$valeur=1;
															}
														}
														$req2="INSERT INTO liste_exam SET id='$id', ".
															  "date_exam='$date_exam', resultat1='$valeur', ".
															  "type_exam='".$mots[$exam]."'";
															  // echo $req2."<br>$numero - $ligne<br><br>";
														$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
														
														if($mots[$exam]=="albu"){
															$valeur=$val;
														}
														
														//Sauvegarde dans le compte-rendu
														$lok++;
														$worksheet_ok->write("A$lok", $id);
														$worksheet_ok->write("B$lok", "$dossier");
														$worksheet_ok->write("C$lok", $date_exam);
														$worksheet_ok->write("D$lok", $valeur);
														$worksheet_ok->write("E$lok", $mots[$exam]);
													}
												}
												else{//Sauvegarde dans le compte-rendu
													$lbis++;
													list($date_ex, $resultat1)=mysql_fetch_row($res2);
													$worksheet_bis->write("A$lbis", "$id");
													$worksheet_bis->write("B$lbis", "$dossier");
													$worksheet_bis->write("C$lbis", "$date_ex");
													$worksheet_bis->write("D$lbis", $valeur);
													$worksheet_bis->write("E$lbis", $date_exam);
													$worksheet_bis->write("F$lbis", $resultat1);
													$worksheet_bis->write("G$lbis", $mots[$exam]);
												}
											}
											else{//Le dossier n'est pas reconnu=> affichage dans le rapport d'erreur
												// if(in_array($mots[$exam], $rapport)){
													$lko++;
													$worksheet_ko->write("A$lko", "");
													$worksheet_ko->write_string("B$lko", "$dossier");
													$worksheet_ko->write("C$lko", "$date_exam");
													$worksheet_ko->write("D$lko", $valeur);
													$worksheet_ko->write("E$lko", $mots[$exam]);
													$worksheet_ko->write("F$lko", "Dossier non trouvé dans asalée");
												// }
											}
										}
										
										$integ=1;
										
									}
								}
							}
						}
					}


				}
			}
		}
	
		if(isset($tab_ligne[12])){//Une ligne avec tout y compris le n° dossier
			//Date de l'exam en position 10
			
			$date_exam=$tab_ligne[10];
			if($date_exam!=""){
				$date_exam=explode("/", $date_exam);
				$dateexam=$date_exam;
				$date_exam=$date_exam[2]."-".$date_exam[1]."-".$date_exam[0];
			}
			$examen=$tab_ligne[11];

			if($date_exam>"2004-03-31"){
				
				$numero=$tab_ligne[12];

				$req="SELECT id from dossier WHERE cabinet='".$_SESSION["cabinet"]."' ".
					 "and numero='$numero'";
				$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
				if(mysql_num_rows($res)==1){
					list($id)=mysql_fetch_row($res);
				}
				else{
					$id="";
				}
				
				foreach($mots as $exam=>$code){
					if(strpos($examen, $exam)!==false){//Le mot clé est indiqué dans la ligne
						// echo $ligne."<br>";
						$test=str_replace($exam, "", $l);
						$test=str_replace(",", ".", $test);
						$test=explode(" ", $test);
						$resultat=0;
						$integ=0;
						
						foreach($test as $valeur){
							if(($integ==0)&&($resultat==0)&&(is_numeric($valeur))&&($valeur!="")){//Il s'agit de la valeur de l'exam
								if($date_exam==""){
									if(in_array($mots[$exam], $rapport)){
										$lko++;
										$worksheet_ko->write("A$lko", "$id");
										$worksheet_ko->write_string("B$lko", "$dossier");
										$worksheet_ko->write("C$lko", "$date_exam");
										$worksheet_ko->write("D$lko", $valeur);
										$worksheet_ko->write("E$lko", $mots[$exam]);
										$worksheet_ko->write("F$lko", "Date d'examen non trouvée");
									}
								}
								else{
									if($id!=""){//Le dossier est reconnu
										if($mots[$exam]=="ECG"){
											$valeur="";
										}

										$date_avant=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]-15, $dateexam[0]));
										$date_apres=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]+15, $dateexam[0]));
										
										$req2="SELECT date_exam, resultat1 from liste_exam where id='$id' ".
											  "and date_exam>'$date_avant' and date_exam<'$date_apres' ".
											  "and type_exam='".$mots[$exam]."'";
										$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
										
										if(mysql_num_rows($res2)==0){//La donnée n'est pas présente dans asalée
											if(($mots[$exam]!="ECG")&&(!is_numeric($valeur))){
												$lko++;
												$worksheet_ko->write("A$lko", "$id");
												$worksheet_ko->write_string("B$lko", "$numero");
												$worksheet_ko->write("C$lko", "$date_exam");
												$worksheet_ko->write("D$lko", $valeur);
												$worksheet_ko->write("E$lko", $mots[$exam]);
												$worksheet_ko->write("F$lko", "Résultat non conforme");
											}
											else{
												if($mots[$exam]=="albu"){
													$val=$valeur;
													if($valeur<20){
														$valeur=0;
													}
													else{
														$valeur=1;
													}
												}
												$req2="INSERT INTO liste_exam SET id='$id', ".
													  "date_exam='$date', resultat1='$valeur', ".
													  "type_exam='".$mots[$exam]."'";
													  // echo $req2."<br>$numero - $ligne<br><br>";
												$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
												
												if($mots[$exam]=="albu"){
													$valeur=$val;
												}
												
												//Sauvegarde dans le compte-rendu
												$lok++;
												$worksheet_ok->write("A$lok", $id);
												$worksheet_ok->write("B$lok", "$numero");
												$worksheet_ok->write("C$lok", $date_exam);
												$worksheet_ok->write("D$lok", $valeur);
												$worksheet_ok->write("E$lok", $mots[$exam]);
											}
										}
										else{//Sauvegarde dans le compte-rendu
											$lbis++;
											list($date_ex, $resultat1)=mysql_fetch_row($res2);
											$worksheet_bis->write("A$lbis", "$id");
											$worksheet_bis->write("B$lbis", "$numero");
											$worksheet_bis->write("C$lbis", "$date_exam");
											$worksheet_bis->write("D$lbis", $valeur);
											$worksheet_bis->write("E$lbis", $date_ex);
											$worksheet_bis->write("F$lbis", $resultat1);
											$worksheet_bis->write("G$lbis", $mots[$exam]);
										}
									}
									else{//Le dossier n'est pas reconnu=> affichage dans le rapport d'erreur
										// if(in_array($mots[$exam], $rapport)){
											$lko++;
											$worksheet_ko->write("A$lko", "");
											$worksheet_ko->write_string("B$lko", "$numero");
											$worksheet_ko->write("C$lko", "$date_exam");
											$worksheet_ko->write("D$lko", $valeur);
											$worksheet_ko->write("E$lko", $mots[$exam]);
											$worksheet_ko->write("F$lko", "Dossier non trouvé dans asalée");
										// }
									}
								}
								
								$integ=1;
								
							}
						}
					}
				}
			}

					$feuille="";
		}
		elseif(isset($tab_ligne[10])){//Ligne de titre avec la date de l'exam
			$date_exam=$tab_ligne[10];
			$date_exam=explode("/", $date_exam);
			$dateexam=$date_exam;
			$date_exam=$date_exam[2]."-".$date_exam[1]."-".$date_exam[0];
			$feuille=$tab_ligne[11];
		}
		else{
			$feuille=$feuille."<br>".$ligne;
		}
		

	}

	$workbook->close();
	
	unlink($upfile);
	unlink($fichier);
	return($fich);
}

