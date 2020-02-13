<?php

function integration_argenton($fichier){

	$fich="./log/Compte-rendu integration ".$_SESSION["cabinet"]." ".date("d-m-Y").".xls";
	$workbook =& new writeexcel_workbookbig($fich); // on lui passe en paramètre le chemin de notre fichier
	
	//Préparation du fichier de compte-rendu
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

	include('reader.php');
	$data = new Spreadsheet_Excel_Reader();
	$data->read($fichier);
	
	$zones=array("date","val","unite","type","numero");
	$zone=0;
	$numero=$date=$type=$val="";
	
	$mots=array("HbA1c"=>"HBA1c",
				"HDL"=>"HDL", //val => milimol/litre
				"LDL"=>"LDL", //val => milimol/litre
				"CT"=>"Chol", //val => milimol/litre
				"Créatinine"=>"creat", //val => µmol/litre
				"Glycémie"=>"glycemie",  //val mmol. 1mmol=0.18g/l => arrondi 2 chiffres
				"Kaliémie (potassium)"=>"kaliemie",
				"Microalbuminurie"=>"albu",
				"TG"=>"triglycerides", //val => milimol
				"Systole"=>"systole", 
				"Diastole"=>"diastole", 
				"Pouls"=>"pouls", 
				"Poids"=>"poids", 
				);

	
	$mois=array("janvier"=>"01", "fevrier"=>"02", "mars"=>"03", "avril"=>"04", "mai"=>"05", "juin"=>"06",
				"juillet"=>"07", "aout"=>"08", "septembre"=>"09", "octobre"=>"10", "novembre"=>"11",
				"decembre"=>"12");
	
	$i=7;
		
	while(isset($data->sheets[0]['cells'][$i][1])&&($data->sheets[0]['cells'][$i][1]!="")){

		$date=$data->sheets[0]['cells'][$i][1];
		$val=$data->sheets[0]['cells'][$i][2];
		$unite=$data->sheets[0]['cells'][$i][3];
		$type=$data->sheets[0]['cells'][$i][4];
		$numero=$data->sheets[0]['cells'][$i][5];
		
		$val=str_replace("=", "", $val);

		// echo "num : $numero date: $date type ! $type val :$val<br><br>";
		$val=str_replace(",", ".", $val);

		if(isset($mots[$type])){//Il s'agit d'un examen reconnu 
			$date=explode(" ", $date);
			
			$date[1]=str_replace("é", "e", $date[1]);
			$date[1]=str_replace("û", "u", $date[1]);
			
			$date=$date[2]."-".$mois[$date[1]]."-".$date[0];
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
					
					//on veut filtrer systole entre 50 et 300, diastole  entre 15 et 150 
					// ajout du test si non numérique EA09-08-2013
					if( (($mots[$type]=="systole")&&(($val<50)||($val>300))) || (($mots[$type]=="diastole")&&(($val<15)||($val>150))) || (!is_numeric($val))  ){
						$lko++;
						$worksheet_ko->write("A$lko", $id); //EA09-08-2013 remplacer "" par $id
						$worksheet_ko->write_string("B$lko", "$numero");
						$worksheet_ko->write("C$lko", "$date");
						$worksheet_ko->write("D$lko", $val);
						$worksheet_ko->write("E$lko", $mots[$type]);
						$worksheet_ko->write("F$lko", "Valeur suspecte à vérifier");

					}else{

						$dateexam=explode("-", $date);
						$date_avant=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]-15, $dateexam[0]));
						$date_apres=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]+15, $dateexam[0]));

						if(($mots[$type]!="HBA1c")||($val>=4))/* || (($mots[$type]=="Systole") && ($val<300)) || (($mots[$type]=="Systole") && ($val>50)))*/{
						
							$req2="SELECT date_exam, resultat1 from liste_exam where id='$id' ".
								  "and date_exam>'$date_avant' and date_exam<'$date_apres' ".
								  "and type_exam='".$mots[$type]."'";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
							
							if(mysql_num_rows($res2)==0){//La donnée n'est pas présente dans asalée
									
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
							else{//Sauvegarde dans le compte-rendu -> données déjà présentes
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
				}
				else{//Dossier non reconnu => écriture dans le compte-rendu
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
		
		$i++;

	}

	$workbook->close();
	
	return($fich);
}