<?php
function integration_chambery1($fichier, $fichier_name){


	$upfile="./log/chambery1/".$fichier_name;

	if (is_uploaded_file($fichier))
	{
		if (!move_uploaded_file($fichier, $upfile))
		{
			echo "probl�me : impossible de t�l�charger le fichier des biologies";
			exit;
		}
	}

//d�zippage du fichier
include('pclzip.lib.php');

$archive = new PclZip($upfile);

if ($archive->extract(PCLZIP_OPT_PATH, "./log/chambery1/") == 0) {
    die("Error : ".$archive->errorInfo(true));
}

if (($list = $archive->listContent()) == 0) {
    die("Error : ".$archive->errorInfo(true));
}
  
$fichier="./log/chambery1/".$list[0]["filename"];

	$fich="./log/Compte-rendu integration ".$_SESSION["cabinet"]." ".date("d-m-Y").".xls";
	$workbook =& new writeexcel_workbookbig($fich); // on lui passe en param�tre le chemin de notre fichier
	
	//pr�paration des feuilles de compte-rendu et �criture des lignes de titre
	$worksheet_ok =& $workbook->addworksheet("donn�es int�gr�es");
	$worksheet_ok->write("A1", "Id dans asal�e");
	$worksheet_ok->write("B1", "n� dossier");
	$worksheet_ok->write("C1", "date");
	$worksheet_ok->write("D1", "valeur");
	$worksheet_ok->write("E1", "type examen");

	$lok=1;
	
	$worksheet_bis =& $workbook->addworksheet("donn�es d�j� pr�sentes");
	$worksheet_bis->write("A1", "Id dans asal�e");
	$worksheet_bis->write("B1", "n� dossier");
	$worksheet_bis->write("C1", "date dans export");
	$worksheet_bis->write("D1", "valeur dans export");
	$worksheet_bis->write("E1", "date dans asal�e");
	$worksheet_bis->write("F1", "valeur dans asal�e");
	$worksheet_bis->write("G1", "type examen");

	$lbis=1;
	
	$worksheet_ko =& $workbook->addworksheet("donn�es non int�gr�es");
	$worksheet_ko->write("A1", "Id dans asal�e");
	$worksheet_ko->write("B1", "n� dossier");
	$worksheet_ko->write("C1", "date");
	$worksheet_ko->write("D1", "valeur");
	$worksheet_ko->write("E1", "type examen");
	$worksheet_ko->write("F1", "Raison non int�gration");

	$lko=1;
	$fp=fopen("$fichier", "r");
	
	$numero=$date=$type=$val="";
	

	$mots=synonymes();// initialisation des synonymes dans Utils.php
	$fauxamis=fauxamis();// initialisation des fauxamis dans Utils.php

	
	//Liste des examens � inclure dans le rapport
	$rapport=array("Chol", "LDL", "HDL", "triglycerides", "HBA1c", "glycemie");
	
	//Coefficients pour passer des mmol au mg
	$equivalences=array("Chol"=>2.58,
						  "HDL"=>2.58,
						  "LDL"=>2.58,
						  "creat"=>8.85,
						  "glycemie"=>5.56,
						  "triglycerides"=>1.14,
						  "HBA1c"=>1
						  );
				  
	//Liste des unit�s � remplacer
	$unites=array("Chol"=>array("mmol/L", "mmol/l"),
				  "LDL"=>array("mmol/L"),
				  "HDL"=>array("mmol/L"),
				  "creat"=>array("�mol/L"),
				  "glycemie"=>array("mmol/L"),
				  "triglycerides"=>array("mmol/L"));
	$debut=1;
	$recherche=0;
	$date1=$date2="";
	$record=$fauxamiscond=$controleCreat=$controleLDL=FALSE;	
	
	while($ligne=fgets($fp)){
		$ligne=str_replace("\n", "", $ligne);
		$ligne=str_replace("\r", "", $ligne);

		$tab_ligne=explode(";", $ligne);


		if(preg_match("/<form>/", $ligne)){
			$record=TRUE;
		}
		if(preg_match("/<\/form>/", $ligne)){
			$record=FALSE;
		}
		

		$colone1=slugify($tab_ligne[0]);
		// if($record){
		// 	echo $colone1.'<br/>';
		// }	


		if(in_array($colone1, $fauxamis)){
			$fauxamiscond=FALSE;
		}
		else{
			$fauxamiscond=TRUE;
		}

		if($mots[$colone1]=='creat' && $tab_ligne[2]>200){
		 	$controleCreat=FALSE;
		}
		 else{
		 	$controleCreat=TRUE;
		}
		if($mots[$colone1]=='LDL' && $tab_ligne[2]==0){
		 	$controleLDL=FALSE;
		}
		 else{
		 	$controleLDL=TRUE;
		}


		if( ($record) && ($fauxamiscond) && ($controleCreat) && ($controleLDL)) {
		
			if(isset($tab_ligne[1])&&($tab_ligne[1]=="BIOLOGIE")){//On est sur une ligne "titre"
														  //on r�cup�re alors le n� dossier et date exam
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
			
			if((count($tab_ligne)>2)&&(isset($mots[$colone1]))){//On est sur un mot cl� => on cherche � int�grer
				$exam=$colone1;
				$valeur=$tab_ligne[2];
				$normal=$tab_ligne[3];
				$unite=$tab_ligne[6];

				if(isset($unites[$mots[$colone1]])&&(in_array($unite,$unites[$mots[$colone1]]))){//On est sur un examen dans la mauvaise unit�=>� convertir
					$valeur=round($valeur/$equivalences[$mots[$colone1]], 2);
				}
				
				if($date==""){
					if(in_array($mots[$exam], $rapport)){
						$lko++;
						$worksheet_ko->write("A$lko", "$id");
						$worksheet_ko->write_string("B$lko", "$numero");
						$worksheet_ko->write("C$lko", "$date");
						$worksheet_ko->write("D$lko", $valeur);
						$worksheet_ko->write("E$lko", $mots[$exam]);
						$worksheet_ko->write("F$lko", "Date d'examen non trouv�e");
					}
				}
				else{
					if($id!=""){//Le dossier est reconnu
						if($date>"2011-01-01"){
							if($mots[$exam]=="ECG"){
								$valeur="";
							}

							$date_avant=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]-15, $dateexam[0]));
							$date_apres=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]+15, $dateexam[0]));
							
							$req2="SELECT date_exam, resultat1 from liste_exam where id='$id' ".
								  "and date_exam>'$date_avant' and date_exam<'$date_apres' ".
								  "and type_exam='".$mots[$exam]."'";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
							
							if(mysql_num_rows($res2)==0){//La donn�e n'est pas pr�sente dans asal�e
								if($valeur==""){
									if(in_array($mots[$exam], $rapport)){
										$lko++;
										$worksheet_ko->write("A$lko", "$id");
										$worksheet_ko->write_string("B$lko", "$numero");
										$worksheet_ko->write("C$lko", "$date");
										$worksheet_ko->write("D$lko", $valeur);
										$worksheet_ko->write("E$lko", $mots[$exam]);
										$worksheet_ko->write("F$lko", "Aucune valeur indiqu�e");
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
										$worksheet_ko->write("F$lko", "R�sultat non conforme");
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
						if($date>"2011-01-01"){
							if(in_array($mots[$exam], $rapport)){
								$lko++;
								$worksheet_ko->write("A$lko", "");
								$worksheet_ko->write_string("B$lko", "$numero");
								$worksheet_ko->write("C$lko", "$date");
								$worksheet_ko->write("D$lko", $valeur);
								$worksheet_ko->write("E$lko", $mots[$exam]);
								$worksheet_ko->write("F$lko", "Dossier non trouv� dans asal�e");
							}
						}
					}
				}
			}
			else{//On v�rifie si le premier mot est un mot cl� pour v�rifier les scan de feuilles
				if($date>"2011-01-01"){
					foreach($mots as $exam=>$code){
						if(strpos($ligne, $exam)!==false){//Le mot cl� est indiqu� dans la ligne
							if($code=="albu"){//Dans le cas de l'albu, il faut regarder les lignes suivantes pour avoir le r�sultat
								while((strpos($ligne, "mg/L")===false)&&(strpos($ligne, "mg/l")===false)){
									$ligne=fgets($fp);
								}
								
							}

							if($code=="HBA1c"){//Dans le cas de l'HBA1c, il faut regarder les lignes suivantes pour avoir le r�sultat
								while(strpos($ligne, "%")===false){
									$ligne=fgets($fp);
								}
								
							}
							
							$conversion=0;
							if(isset($unites[$code])){
								foreach($unites[$code] as $u){
									if(strpos($ligne, $u)!==false){
										$conversion=1;
									}
								}
							}
							
							$test=str_replace($exam, "", $ligne);
							$test=str_replace(",", ".", $test);
							$test=explode(" ", $test);
							$resultat=0;
							$integ=0;
							
							foreach($test as $valeur){
								if(($integ==0)&&($resultat==0)&&(is_numeric($valeur))&&($valeur!="")){//Il s'agit de la valeur de l'exam
									if($conversion==1){
										$valeur=round($valeur/$equivalences[$code], 2);
									}
									
									if($date==""){
										if(in_array($mots[$exam], $rapport)){
											$lko++;
											$worksheet_ko->write("A$lko", "$id");
											$worksheet_ko->write_string("B$lko", "$numero");
											$worksheet_ko->write("C$lko", "$date");
											$worksheet_ko->write("D$lko", $valeur);
											$worksheet_ko->write("E$lko", $mots[$exam]);
											$worksheet_ko->write("F$lko", "Date d'examen non trouv�e");
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
											
											if(mysql_num_rows($res2)==0){//La donn�e n'est pas pr�sente dans asal�e
												if(($mots[$exam]!="ECG")&&(!is_numeric($valeur))){
													$lko++;
													$worksheet_ko->write("A$lko", "$id");
													$worksheet_ko->write_string("B$lko", "$numero");
													$worksheet_ko->write("C$lko", "$date");
													$worksheet_ko->write("D$lko", $valeur);
													$worksheet_ko->write("E$lko", $mots[$exam]);
													$worksheet_ko->write("F$lko", "R�sultat non conforme");
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
												$worksheet_ko->write("F$lko", "Dossier non trouv� dans asal�e");
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

