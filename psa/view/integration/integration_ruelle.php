<?php

function integration_ruelle($fichier, $fichier_name){


	$upfile="./log/ruelle/".$fichier_name;

	if (is_uploaded_file($fichier))
	{
		if (!move_uploaded_file($fichier, $upfile))
		{
			echo "probl�me : impossible de t�l�charger le fichier des biologies";
			exit;
		}
	}

include('pclzip.lib.php');

$archive = new PclZip($upfile);

if ($archive->extract(PCLZIP_OPT_PATH, "./log/ruelle/") == 0) {
    die("Error : ".$archive->errorInfo(true));
}

if (($list = $archive->listContent()) == 0) {
    die("Error : ".$archive->errorInfo(true));
}
  
$fichier="./log/ruelle/".$list[0]["filename"];

	$fich="./log/Compte-rendu integration ".$_SESSION["cabinet"]." ".date("d-m-Y").".xls";
	$workbook =& new writeexcel_workbookbig($fich); // on lui passe en param�tre le chemin de notre fichier
	
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
	
	$zones=array("numero", "date", "type", "val");
	$zone=0;
	$numero=$date=$type=$val="";
	
	$mots=array("MICROALBUMINURIE"=>"albu",
				"MICROALBUMINURIE EN MG/L"=>"albu",
				"MICRO-ALBUMINURIE"=>"albu",
				"CHOLESTEROL total"=>"Chol",
				"CHOLESTEROL TOTAL"=>"Chol",
				"CHOLESTEROL total:"=>"Chol",
				"CHOLESTEROL total:"=>"Chol",
				"CHOLESTEROL des HDL"=>"HDL",
				"CHOLESTEROL des HDL:"=>"HDL",
				"H.D.L"=>"HDL",
				"HDL"=>"HDL",
				"CHOLESTEROL des LDL"=>"LDL",
				"CHOLESTEROL des LDL: 9"=>"LDL",
				"LDL"=>"LDL",
				"L.D.L"=>"LDL",
				"CREATININE"=>"creat",
				"CREATININE:"=>"creat",
				"Cr�atinin�mie."=>"creat",
				"GLYCEMIE � jeun"=>"glycemie",
				"GLYCEMIE a jeun:"=>"glycemie",
				"H�moglobine glycosyl�e A1c"=>"HBA1c",
				"HEMOGLOBINE GLYCOSYLEE HBA1c"=>"HBA1c",
				"HB GLYQUEE A1c"=>"HBA1c",
				"HEMOGLOBINE GLYQUEE"=>"HBA1c",
				"H�moglobine glycosyl�e (HbA1c)"=>"HBA1c",
				"POTASSIUM:"=>"kaliemie",
				"POTASSIUM"=>"kaliemie",
				"Potassium"=>"kaliemie",
				"TRIGLYCERIDES:"=>"triglycerides",
				"TRIGLYCERIDES"=>"triglycerides",


// ajout tallud
				"HB A1C"=>"HBA1c",
				"HHBA1C%"=>"HBA1c",
				"PA_A1C         %"=>"HBA1c",
				"PA_A1C%"=>"HBA1c",
				"PA_A1C %"=>"HBA1c", //EA 06-11
				"HbA1c :"=>"HBA1c",
				"POIDS"=>"poids",
				"PAS/PAD"=>"tension",
				"PA_LDL CAL. SI TRI<3"=>"LDL",
				"PA_LDL G/L"=>"LDL",
				"LDL CHOLESTEROL"=>"LDL",
				"HDL CHOLESTEROL"=>"HDL",
				"PA: HDL G/L"=>"HDL",
				"PA_HDL G/L"=>"HDL",
				"PA_CHOLEST.TOTAL G/L"=>"Chol",
				"CHOLESTEROL"=>"Chol",
				"CHOLES.TOTAL*"=>"Chol",
				"PA_CHOLEST.TOTAL G/L"=>"Chol",
//				"TRIGLYCERIDES"=>"triglycerides",
				"PA: TRIGLY.   G/L"=>"triglycerides",
				"POULS"=>"pouls",
				"CREATININEMIE"=>"creat",
				"PA_CREATININ.SG.MG/L"=>"creat",
//				"CREATININE"=>"creat",
				"KALIEMIE"=>"kaliemie",
				"PA_POTASSIUM SG MMOL"=>"kaliemie",
				"GLYCEMIE"=>"glycemie",
				"PA_GLY/JN FLUOR  G/L"=>"glycemie",
				"PA_ALBUMINEMIE G/L"=>"albu",
				"PA: MICRO ALBUMINURI"=>"albu",
				"HEMATURIE"=>"hematurie",
//EA 15-11-2013
				"Microalbumine:"=>"albu",
				"Microalbumine :"=>"albu",
				"Cr�atinine :"=>"creat",
				"Cr�atinine:"=>"creat",
				"Cholest�rol HDL:"=>"HDL",
				"Cholest�rol HDL :"=>"HDL",
				"Triglyc�rides:"=>"triglycerides",
				"Triglyc�rides :"=>"triglycerides",
// EA 02-04-2014
 				"LDL Cholesterol"=>"LDL",
        "LDL Calcul�:"=>"LDL",
        "LDL calcul� :"=>"LDL",
        "LDL Direct :"=>"LDL",        
				"HDL Cholesterol"=>"HDL",
        "HBA1C"=>"HBA1c",
        "HBA1c"=>"HBA1c",
        "Triglycerides"=>"triglycerides",
        "Glycemie a jeun"=>"glycemie",
        "Hemoglobine HbA1c"=>"HBA1c",
        "HEMOGLOBINE GLYCOSYLEE HbA1c"=>"HBA1c",
        "MICROALBINURIE"=>"albu"

);

	$remplace=array("HDL"=>" g/l",
					"LDL"=>" g/l");
	
  	//Coefficients pour passer des mmol au mg
	$equivalences=array("Chol"=>2.58,
				  "HDL"=>2.58,
				  "LDL"=>2.58,
				  "creat"=>8.85,
				  "glycemie"=>5.56,
				  "triglycerides"=>1.14
				  );
				  
	//Liste des unit�s � remplacer
	$unites=array("Chol"=>array("mmol/L", "mmol/l"),
				  "LDL"=>array("mmol/L"),
				  "HDL"=>array("mmol/L"),
				  "creat"=>array("�mol/L","�mol/l" ), //06-01-2014 EAOUAD
				  "glycemie"=>array("mmol/L"),
				  "triglycerides"=>array("mmol/L"));
  
  				
	$rapport=array("Chol", "LDL", "HDL", "triglycerides", "HBA1c", "glycemie");
					
	$debut=1;
	$recherche=0;
	$date1=$date2="";
	
	while($ligne=fgets($fp)){
  	$ligne=str_replace("\n", "", $ligne);
		$ligne=str_replace("\r", "", $ligne);
		// print_r($ligne);die;
		$tab_ligne=explode(";", $ligne);
		
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
		
		if((count($tab_ligne)>2)&&(isset($mots[$tab_ligne[0]]))){//On est sur un mot cl� => on cherche � int�grer
			$exam=$tab_ligne[0];
			$valeur=$tab_ligne[2];
			$normal=$tab_ligne[3];
      $unite="";
      if(isset  ($tab_ligne[6]))
                $unite = $tab_ligne[6];

/*      if(isset($mots[$exam])   )
      {
      error_log ( $mots[$exam].":". $valeur."\n" );
      }
  */    



//EA 31-03-2014

			if(isset($unites[$mots[$exam]])&&(in_array($unite,$unites[$mots[$exam]])))
      {//On est sur un examen dans la mauvaise unit�=>� convertir
				$valeur=round($valeur/$equivalences[$mots[$exam]], 2);
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
					if($date>"2008-01-01"){
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

  /*  
		else{//On v�rifie si le premier mot est un mot cl� pour v�rifier les scan de feuilles
			if($date>"2008-01-01"){
				foreach($mots as $exam=>$code){
					if(strpos($ligne, $exam)!==false){//Le mot cl� est indiqu� dans la ligne
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
*/

	}

	$workbook->close();
	
	unlink($upfile);
	unlink($fichier);
//EA 20-02-2014
	$archive2 = new PclZip($fich.".zip");

	if ($archive2->create($fich) == 0) {
	    return ($fich);
	}
  unlink($fich);   //EA 26-03-2014
	return($fich.".zip");


}

