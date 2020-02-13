<?php

function integration_chambery2($fichier){

	$fich="./log/Compte-rendu integration ".$_SESSION["cabinet"]." ".date("d-m-Y").".xls";
	$workbook =& new writeexcel_workbookbig($fich); // on lui passe en param�tre le chemin de notre fichier
	
	$worksheet_ok =& $workbook->addworksheet("donn�es int�gr�es");
	$worksheet_ok->write("A1", "Id dans asal�e");
	$worksheet_ok->write("B1", "n� dossier");
	$worksheet_ok->write("C1", "date");
	$worksheet_ok->write("D1", "valeur");
	$worksheet_ok->write("E1", "type examen");

	$lok=1;

	
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
	
	$mots=array("ALBUMINURIE"=>"albu",
				"CHOLESTEROL"=>"Chol",
				"Cholest�rol�mie"=>"Chol",
				"CREATININE"=>"creat",
				"Cr�atinin�mie mg/l"=>"creat",
				"E.C.G. CARDIO"=>"ECG",
				"FREQUENCE CARDIAQUE"=>"pouls",
				"GLYCEMIE"=>"glycemie",
				"HbA1c"=>"HBA1c",
				"HDL-cholest�rol"=>"HDL",
				"LDL-cholest�rol"=>"LDL",
				"Micro-albuminurie"=>"albu",
				"POIDS"=>"poids",
				"TA Max"=>"systole",
				"TA Min"=>"diastole",
				"POIDS"=>"poids",
				"TRIGLYCERIDES"=>"triglycerides",
				"Triglyc�rid�mie"=>"triglycerides");
				
	$remplace=array("HDL"=>" g/l",
					"LDL"=>" g/l");
					
	$debut=1;
	$recherche=0;
	$date1=$date2="";
	
	while($ligne=fgets($fp)){
		$ligne=str_replace("\n", "", $ligne);
		$ligne=str_replace("\r", "", $ligne);
		
		if($debut==1){//premi�re ligne du fichier => 
					  //il s'agit d'un n� de dossier => on enregistre le n� de dossier
					  //ensuite, on repart sur la boucle "normale" pour continuer � lire le fichier
					  //ligne par ligne.
			$numero=$ligne;
			$debut=0;
			
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
		else{//on a pass� la 1�re ligne => on analyse les lignes suivantes
			 //Le d�clenchement de l'analyse (recherche r�elle des donn�es 
			 //sera lorsque la ligne "****LAB****" sera trouv�e
			 //Lorsque la ligne est trouv�e, on est sur un fichier HPRIM d'affichage des r�sultats
			 //et des prescription. Les r�sultats ont un premier champ "RES"
			 //La fin de l'analyse pour un dossier se produit en rencontrant
			 //la ligne "****FIN****" => tout se passe alors comme si on recommen�ait 
			 //le fichier au d�but avec un nouveau num�ro de dossier
			
			if($ligne=="****LAB****"){//D�but on est sur un exam de Labo => on enclenche 
									  //la recherche pour la suite
				$recherche=1;
			}
			if($ligne=="****FIN****"){//Fin d'un dossier => on r�initialise les param�tres
									  //pour pouvoir lire le nouveau dossier
				$recherche=0;
				$debut=1;
				$date1=$date2="";
			}
			
			if(is_date_chambery2($ligne)){
				if($date1==""){
					$date1=$ligne;
				}
				else{
					$date2=$ligne;
				}
			}
			
			if($recherche==1){//On est sur les r�sultat du labo
							  //On continue de lire ligne par ligne, 
							  //on explose la ligne par "|"
							  //si le premier champ est "RES" alors c'est qu'il y a des 
							  //donn�es � int�grer
				
				$tab_ligne=explode("|", $ligne);
				
				if($tab_ligne[0]=="RES"){
					$exam=$tab_ligne[1];
					$valeur=$tab_ligne[4];
					$normal=$tab_ligne[3];
					if(isset($tab_ligne[5])){
						$unite=$tab_ligne[5];
					}
					else{
						$unite="";
					}
					
					if(($date2=="")&&($date1!="")){
						$date=$date1;
					}
					elseif(($date1=="")&&($date2!="")){
						$date=$date2;
					}
					elseif(($date1!="")&&($date1>$date2)){
						$date=$date1;
					}
					elseif(($date2!="")&&($date1<$date2)){
						$date=$date2;
					}
					else{//Pas de date trouv�e
						$date="";
					}
					
					if(isset($mots[$exam])){//Il s'agit d'un exam � int�grer
						if($date==""){
							if(($mots[$exam]=="HBA1c")||
							   (($mots[$exam]=="HDL")&&($valeur<0.4))||
							   (($mots[$exam]=="LDL")&&($valeur>1.6))||
							   (($mots[$exam]=="glycemie")&&($valeur>1.1))){
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
								
								$date=explode("/", $date);
								$date=$date[2]."-".$date[1]."-".$date[0];
								$dateexam=explode("-", $date);
								$date_avant=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]-15, $dateexam[0]));
								$date_apres=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]+15, $dateexam[0]));
								
								$req2="SELECT date_exam, resultat1 from liste_exam where id='$id' ".
									  "and date_exam>'$date_avant' and date_exam<'$date_apres' ".
									  "and type_exam='".$mots[$exam]."'";
								$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
								
								if(mysql_num_rows($res2)==0){//La donn�e n'est pas pr�sente dans asal�e
									$req2="INSERT INTO liste_exam SET id='$id', ".
										  "date_exam='$date', resultat1='$valeur', ".
										  "type_exam='".$mots[$exam]."'";
										  // echo $req2."<br>";
									$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
									
									//Sauvegarde dans le compte-rendu
									$lok++;
									$worksheet_ok->write("A$lok", $id);
									$worksheet_ok->write("B$lok", "$numero");
									$worksheet_ok->write("C$lok", $date);
									$worksheet_ok->write("D$lok", $valeur);
									$worksheet_ok->write("E$lok", $mots[$exam]);
								}
							}
							else{//Le dossier n'est pas reconnu=> affichage dans le rapport d'erreur
								if(($mots[$exam]=="HBA1c")||
								   (($mots[$exam]=="HDL")&&($valeur<0.4))||
								   (($mots[$exam]=="LDL")&&($valeur>1.6))||
								   (($mots[$exam]=="glycemie")&&($valeur>1.1))){
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
			}
		}


	}

	$workbook->close();
	
	return($fich);
}

function is_date_chambery2($date){
	if(!preg_match('`^([0-9]{1,2})(/|-)([0-9]{1,2})(/|-)([0-9]{2}|[0-9]{4})$`',$date, $reg)) {
		return false;
	}

	if (!checkdate($reg[3],$reg[1],$reg[5])) {
		return false;
	}

	if( $reg[5] <= 1880) {
		return false;
    }

	return true;
}