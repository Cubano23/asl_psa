<?php

    
            


function integration_bouille($fichier_ex, $fichier_pat){
   $inputKey = pack("H*","E49F211F72FDA17B3420DEADEA99ADF5");
   $fname = "Compte-rendu integration ".$_SESSION["cabinet"]." ".date("d-m-Y"); 
   $f = hash_hmac ( "md5" , $fname, $inputKey );

                                
	$fichier_log= "./log/".$fname.".".$f.".xls";
//  "./log/Compte-rendu integration ".$_SESSION["cabinet"]." ".date("d-m-Y").".xls";
	$workbook =& new writeexcel_workbookbig($fichier_log); // on lui passe en paramètre le chemin de notre fichier
	
	
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

	//1- intégration des patients dans la table des patients telle que présente dans le logiciel
	
	$fichier=file("$fichier_pat");
	$fichier=$fichier[0];
	
	$fichier=str_replace('INSERT INTO patients ("nom_dossier", "prenom", "intitule", "date_naissance", "rue", "adr2", "code_postal", "ville", "telephone", "profession", "antecedents", "pense_bete", "nom_patient", "poids", "id_praticien", "divers", "hospitalise", "sexe", "num_secu", "mutuelle", "dem_alerte", "nom_jeune_fille", "lieu_naissance", "revoir_le", "taille", "convoc_auto", "initiales_convo", "num_dossier", "telephone_bur", "type_dern_recet", "privee", "derniere_regle", "debut_grossesse", "email", "derniererecette_e", "code_suivi_bilan", "banque", "x_id_palm", "adresse_habituelle", "mere_nom", "mere_profession", "mere_adresse_1", "mere_adresse_2", "mere_cp", "mere_ville", "mere_tel_dom", "mere_tel_prof", "pere_nom", "pere_profession", "pere_adresse_1", "pere_adresse_2", "pere_cp", "pere_ville", "pere_tel_dom", "pere_tel_prof", "fratrie", "nombre_enfants", "jumeaux_triplet", "date_modifcation", "telephone_3", "alerte_agenda", "medecin_traitant", "id_unique", "time_stamp", "motcle_medoc_txt", "antecedent_wr", "id_clinique", "id_dossier_clinique", "naissance_approximative", "allaitement")', "INSERT INTO patients (nom_dossier, prenom, intitule, date_naissance, rue, adr2, code_postal, ville, telephone, profession, antecedents, pense_bete, nom_patient, poids, id_praticien, divers, hospitalise, sexe, num_secu, mutuelle, dem_alerte, nom_jeune_fille, lieu_naissance, revoir_le, taille, convoc_auto, initiales_convo, num_dossier, telephone_bur, type_dern_recet, privee, derniere_regle, debut_grossesse, email, derniererecette_e, code_suivi_bilan, banque, x_id_palm, adresse_habituelle, mere_nom, mere_profession, mere_adresse_1, mere_adresse_2, mere_cp, mere_ville, mere_tel_dom, mere_tel_prof, pere_nom, pere_profession, pere_adresse_1, pere_adresse_2, pere_cp, pere_ville, pere_tel_dom, pere_tel_prof, fratrie, nombre_enfants, jumeaux_triplet, date_modifcation, telephone_3, alerte_agenda, medecin_traitant, id_unique, time_stamp, motcle_medoc_txt, antecedent_wr, id_clinique, id_dossier_clinique, naissance_approximative, allaitement)", $fichier);
	$fichier=str_replace(", E'", ", '", $fichier);
	
	$fichier=explode("INSERT INTO patients ", $fichier);
	
	foreach($fichier as $fich){
		if($fich!=""){
			$fich="INSERT INTO patients ".$fich;
			$res=mysql_query($fich) or die("erreur SQL:".mysql_error()."<br />$fich");
		}
	}

	
	//2- intégration des biologies dans la table des résultats telle que présente dans le logiciel
	
	$fichier=file("$fichier_ex");
	$fichier=$fichier[0];
	//ajout rid imagerie 20-03-2014 EA
	$fichier=str_replace('INSERT INTO courriers_bilans ("date_doc", "nom_doc", "text_doc", "ordre", "indic", "initiales", "date_resultat", "labo_angio", "couleur", "id_unique", "correspondants", "rid_patient", "time_stamp", "type_doc", "zone_plug", "suivi_etat", "rid_praticien", "chemin", "sous_dossier", "rid_imagerie")', "INSERT INTO courriers_bilans (date_doc, nom_doc, text_doc, ordre, indic, initiales, date_resultat, labo_angio, couleur, id_unique, correspondants, rid_patient, time_stamp, type_doc, zone_plug, suivi_etat, rid_praticien, chemin, sous_dossier, rid_imagerie)", $fichier);
	// $fichier=str_replace(", E'", ", '", $fichier);
	
	$fichier=explode("INSERT INTO courriers_bilans ", $fichier);
	
	foreach($fichier as $fich){
		if($fich!=""){
			$fich="INSERT INTO courriers_bilans ".$fich;
			$res=mysql_query($fich) or die("erreur SQL:".mysql_error()."<br />$fich");
		}
	}

	
	$mots=array("HEMOGLOBINE A1C"=>"HBA1c", 
				"HB A1 C"=>"HBA1c", 
				"HDL Cholest,rol"=>"HDL", 
				"H.D.L.  Cholestéro"=>"HDL", 
				"TOTAL CHOLESTEROL"=>"Chol",
				"CHOLESTEROL TOTAL"=>"Chol",
				"TRIGLYCERIDES"=>"triglycerides", 
				"CHOLESTEROL LDL"=>"LDL", 
				"L.D.L.  Cholestéro"=>"LDL", 
				"GLYCEMIE"=>"glycemie", 
				"CREATININE"=>"creat", 
				"CREATININEMIE"=>"creat", 
				"Protéinurie"=>"proteinurie", 
				"PROT UR"=>"proteinurie", 
				"MICROALBUMINURIE"=>"albu", 
				"POTASSIUM"=>"kaliemie");
	
	//3- Recherche des examens pour intégration
	
	$req="SELECT profession, text_doc, date_resultat from courriers_bilans, patients where ".
		 "courriers_bilans.rid_patient = patients.id_unique";
	$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

	while(list($numero, $texte_doc, $date_resultat)=mysql_fetch_row($res)){
		$req2="SELECT id from dossier WHERE cabinet='".$_SESSION["cabinet"]."' ".
			 "and numero='$numero'";

		$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
	

		list($id)=mysql_fetch_row($res2);
		$dateexam=explode("-", $date_resultat);
		foreach($mots as $exam=>$code){
			if(strpos($texte_doc, $exam)!==false){//Le mot clé est indiqué dans le texte du document
				$test=substr($texte_doc, strpos($texte_doc, $exam), 200);//On regarde les 200 caractères suivants
				$test=str_replace(",", ".", $test);
				$test=str_replace("*", "", $test);
				$test=explode(" ", $test);

				$resultat=0;
				$integ=0;
				
				foreach($test as $valeur){
					if(($integ==0)&&($resultat==0)&&(is_numeric($valeur))&&($valeur!="")){//Il s'agit de la valeur de l'exam
				
						if($id==""){//dossier non trouvé dans asalée
							$lko++;
							$worksheet_ko->write("A$lko", "");
							$worksheet_ko->write_string("B$lko", "$numero");
							$worksheet_ko->write("C$lko", "$date_resultat");
							$worksheet_ko->write("D$lko", $valeur);
							$worksheet_ko->write("E$lko", $mots[$exam]);
							$worksheet_ko->write("F$lko", "Patient non trouvé dans asalée");
						}
						else{
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
									$worksheet_ko->write("C$lko", "$date_resultat");
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
										  "date_exam='$date_resultat', resultat1='$valeur', ".
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
									$worksheet_ok->write("C$lok", $date_resultat);
									$worksheet_ok->write("D$lok", $valeur);
									$worksheet_ok->write("E$lok", $mots[$exam]);
								}
							}
							else{//Sauvegarde dans le compte-rendu
								$lbis++;
								list($date_exam, $resultat1)=mysql_fetch_row($res2);
								$worksheet_bis->write("A$lbis", "$id");
								$worksheet_bis->write("B$lbis", "$numero");
								$worksheet_bis->write("C$lbis", "$date_resultat");
								$worksheet_bis->write("D$lbis", $valeur);
								$worksheet_bis->write("E$lbis", $date_exam);
								$worksheet_bis->write("F$lbis", $resultat1);
								$worksheet_bis->write("G$lbis", $mots[$exam]);
							}
						}
							
						$integ=1;
						
					}
				}
			}

		}
		
	}


	$workbook->close();
	
	$req="TRUNCATE TABLE patients";
	$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

	$req="TRUNCATE TABLE courriers_bilans";
	$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

	
//EA 26-07-2014
		$archive2 = new PclZip($fichier_log.".zip");
		if ($archive2->create($fichier_log) == 0) {
			    return ($fichier_log);
		}
    unlink($fichier_log);   
		return($fichier_log.".zip");	
     
  
}