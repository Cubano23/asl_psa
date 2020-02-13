<?php

function integration_touaregs($fichier, $fichier_name){

	$upfile="./log/touaregs/".$fichier_name;

	if (is_uploaded_file($fichier))
	{
		if (!move_uploaded_file($fichier, $upfile))
		{
			echo "probleme : impossible de telecharger le fichier des biologies";
			exit;
		}
	}

include('pclzip.lib.php');

$archive = new PclZip($upfile);

if ($archive->extract(PCLZIP_OPT_PATH, "./log/touaregs/") == 0) {
    die("Error : ".$archive->errorInfo(true));
}

if (($list = $archive->listContent()) == 0) {
    die("Error : ".$archive->errorInfo(true));
}
  
$fichier="./log/touaregs/".$list[0]["filename"];

	$fich="./log/Compte-rendu integration ".$_SESSION["cabinet"]." ".date("d-m-Y").".xls";
	$workbook =& new writeexcel_workbookbig($fich); // on lui passe en parametre le chemin de notre fichier
	
	$worksheet_ok =& $workbook->addworksheet("donnees integrees");
	$worksheet_ok->write("A1", "Id dans asalee");
	$worksheet_ok->write("B1", "n° dossier");
	$worksheet_ok->write("C1", "date");
	$worksheet_ok->write("D1", "valeur");
	$worksheet_ok->write("E1", "type examen");

	$lok=1;
	
	$worksheet_bis =& $workbook->addworksheet("donnees deja presentes");
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
	$fp=fopen("$fichier", "r");
	
	$zones=array("numero", "date", "type", "val");
	$zone=0;
	$numero=$date=$type=$val="";
	
	$mots=synonymes();// initialisation des synonymes dans Utils.php
	$fauxamis=fauxamis();// initialisation des fauxamis dans Utils.php

	$remplace=array("HDL"=>" g/l",
					"LDL"=>" g/l");
					
	$rapport=array("Chol", "LDL", "HDL", "triglycerides", "HBA1c", "glycemie", "albu", "creat", "kaliemie", "systole", "diastole");

	$minimol=array("mmol/L", "mmol/l", "umol/L", "umol/l", "mM/mM");
					
	$debut=1;
	$recherche=0;
	$date1=$date2="";
	$record=$fauxamiscond=$controleCreat=$controleHBA1c=FALSE;	

	
	while($ligne=fgets($fp)){
		$ligne=str_replace("\n", "", $ligne);
		$ligne=str_replace("\r", "", $ligne);
		// print_r($ligne);die;
		$tab_ligne=explode(";", $ligne);

		if(preg_match("/<form>/", $ligne)){
			$record=TRUE;
		}
		if(preg_match("/<\/form>/", $ligne)){
			$record=FALSE;
		}
		

		$colone1=slugify($tab_ligne[0]);
			#if($record){
			#	echo '@'.$colone1.'@<br/>';
			#}


		if(in_array($colone1, $fauxamis)){
			$fauxamiscond=FALSE;
		}
		else{
			$fauxamiscond=TRUE;
		}

		if( ($mots[$colone1]=='creat' && $tab_ligne[2]>100) || ($mots[$colone1]=='creat' && in_array($tab_ligne[6], $minimol)) ){
		 	$controleCreat=FALSE;
		}
		 else{
		 	$controleCreat=TRUE;
		}

		if( $mots[$colone1]=='HBA1c' && in_array($tab_ligne[6], $minimol) ){
			$controleHBA1c=FALSE;
		}
		else{
			$controleHBA1c=TRUE;
		}




		if( ($record) && ($fauxamiscond) && ($controleCreat) && ($controleHBA1c) ) {

		
			if(isset($tab_ligne[1])&&($tab_ligne[1]=="BIOLOGIE")){//On est sur une ligne "titre"
														  //on recupere alors le n° dossier et date exam
				$numero=$tab_ligne[0];
				$date=$tab_ligne[2];
				$date=explode("/", $date);
				$date=$date[2]."-".$date[1]."-".$date[0];
				$dateexam=explode("-", $date);

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
			
			if((count($tab_ligne)>2)&&(isset($mots[$colone1]))){//On est sur un mot cle => on cherche a integrer
				$exam=$colone1;
				$valeur=str_replace(',','.',$tab_ligne[2]);
				$normal=$tab_ligne[3];

				
				if($date==""){
					if(in_array($mots[$exam], $rapport)){
						$lko++;
						$worksheet_ko->write("A$lko", "$id");
						$worksheet_ko->write_string("B$lko", "$numero");
						$worksheet_ko->write("C$lko", "$date");
						$worksheet_ko->write("D$lko", $valeur);
						$worksheet_ko->write("E$lko", $mots[$exam]);
						$worksheet_ko->write("F$lko", "Date d'examen non trouvee");
					}
				}
				else{
					if($id!=""){//Le dossier est reconnu
						if($date>"2008-01-01"){
							if($mots[$exam]=="ECG"){
								$valeur="";
							}

							$date_avant=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]-15, $dateexam[0]));
							$date_apres=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]+15, $dateexam[0]));
							
							$req2="SELECT date_exam, resultat1 from liste_exam where id='$id' ".
								  "and date_exam>'$date_avant' and date_exam<'$date_apres' ".
								  "and type_exam='".$mots[$exam]."'";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
							
							if(mysql_num_rows($res2)==0){//La donnee n'est pas presente dans asalee
								if($valeur==""){
									if(in_array($mots[$exam], $rapport)){
										$lko++;
										$worksheet_ko->write("A$lko", "$id");
										$worksheet_ko->write_string("B$lko", "$numero");
										$worksheet_ko->write("C$lko", "$date");
										$worksheet_ko->write("D$lko", $valeur);
										$worksheet_ko->write("E$lko", $mots[$exam]);
										$worksheet_ko->write("F$lko", "Aucune valeur indiquee");
									}
								}
								else{
									if(($mots[$exam]!="ECG")&&(!is_numeric($valeur))){
										$lko++;
										$worksheet_ko->write("A$lko", "$id");
										$worksheet_ko->write_string("B$lko", "$numero");
										$worksheet_ko->write("C$lko", "$date");
										$worksheet_ko->write("D$lko", $valeur);
										$worksheet_ko->write("E$lko", $mots[$exam]);
										$worksheet_ko->write("F$lko", "Resultat non conforme");
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
										$worksheet_ok->write("C$lok", $date);
										$worksheet_ok->write("D$lok", $valeur);
										$worksheet_ok->write("E$lok", $mots[$exam]);
									}
								}
							}
							else{//Sauvegarde dans le compte-rendu
								$lbis++;
								list($date_exam, $resultat1)=mysql_fetch_row($res2);
								$worksheet_bis->write("A$lbis", "$id");
								$worksheet_bis->write("B$lbis", "$numero");
								$worksheet_bis->write("C$lbis", "$date");
								$worksheet_bis->write("D$lbis", $valeur);
								$worksheet_bis->write("E$lbis", $date_exam);
								$worksheet_bis->write("F$lbis", $resultat1);
								$worksheet_bis->write("G$lbis", $mots[$exam]);
							}
						}
					}
					else{//Le dossier n'est pas reconnu=> affichage dans le rapport d'erreur
						if($date>"2008-01-01"){
							if(in_array($mots[$exam], $rapport)){
								$lko++;
								$worksheet_ko->write("A$lko", "");
								$worksheet_ko->write_string("B$lko", "$numero");
								$worksheet_ko->write("C$lko", "$date");
								$worksheet_ko->write("D$lko", $valeur);
								$worksheet_ko->write("E$lko", $mots[$exam]);
								$worksheet_ko->write("F$lko", "Dossier non trouve dans asalee");
							}
						}
					}
				}
			}
			else{//On verifie si le premier mot est un mot cle pour verifier les scan de feuilles
				if($date>"2008-01-01"){
					foreach($mots as $exam=>$code){
						if(strpos($ligne, $exam)!==false){//Le mot cle est indique dans la ligne
							// echo $ligne."<br>";
							$test=str_replace($exam, "", $ligne);
							$test=str_replace(",", ".", $test);
							$test=explode(" ", $test);
							$resultat=0;
							$integ=0;
							
							foreach($test as $valeur){
								if(($integ==0)&&($resultat==0)&&(is_numeric($valeur))&&($valeur!="")){//Il s'agit de la valeur de l'exam
									if($date==""){
										if(in_array($mots[$exam], $rapport)){
											$lko++;
											$worksheet_ko->write("A$lko", "$id");
											$worksheet_ko->write_string("B$lko", "$numero");
											$worksheet_ko->write("C$lko", "$date");
											$worksheet_ko->write("D$lko", $valeur);
											$worksheet_ko->write("E$lko", $mots[$exam]);
											$worksheet_ko->write("F$lko", "Date d'examen non trouvee");
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
											
											if(mysql_num_rows($res2)==0){//La donnee n'est pas presente dans asalee
												if(($mots[$exam]!="ECG")&&(!is_numeric($valeur))){
													$lko++;
													$worksheet_ko->write("A$lko", "$id");
													$worksheet_ko->write_string("B$lko", "$numero");
													$worksheet_ko->write("C$lko", "$date");
													$worksheet_ko->write("D$lko", $valeur);
													$worksheet_ko->write("E$lko", $mots[$exam]);
													$worksheet_ko->write("F$lko", "Resultat non conforme");
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
													$worksheet_ok->write("C$lok", $date);
													$worksheet_ok->write("D$lok", $valeur);
													$worksheet_ok->write("E$lok", $mots[$exam]);
												}
											}
											else{//Sauvegarde dans le compte-rendu
												$lbis++;
												list($date_exam, $resultat1)=mysql_fetch_row($res2);
												$worksheet_bis->write("A$lbis", "$id");
												$worksheet_bis->write("B$lbis", "$numero");
												$worksheet_bis->write("C$lbis", "$date");
												$worksheet_bis->write("D$lbis", $valeur);
												$worksheet_bis->write("E$lbis", $date_exam);
												$worksheet_bis->write("F$lbis", $resultat1);
												$worksheet_bis->write("G$lbis", $mots[$exam]);
											}
										}
										else{//Le dossier n'est pas reconnu=> affichage dans le rapport d'erreur
											if(in_array($mots[$exam], $rapport)){
												$lko++;
												$worksheet_ko->write("A$lko", "");
												$worksheet_ko->write_string("B$lko", "$numero");
												$worksheet_ko->write("C$lko", "$date");
												$worksheet_ko->write("D$lko", $valeur);
												$worksheet_ko->write("E$lko", $mots[$exam]);
												$worksheet_ko->write("F$lko", "Dossier non trouve dans asalee");
											}
										}
									}
									
									$integ=1;
									
								}
							}
						}
					}
				}
			}

		}// fin du if($record)


	}

	$workbook->close();
	
	unlink($upfile);
	unlink($fichier);
	return($fich);
}