<?php

function integration_espagnac1($fichier){

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

	include('reader.php');
	$data = new Spreadsheet_Excel_Reader();
	$data->read($fichier);
	
	$zones=array("numero", "date", "type", "val");
	$zone=0;
	$numero=$date=$type=$val="";
	
	$mots=array("Hémoglobine glycosylée"=>"HBA1c",
				"Hémoglobine glyquée"=>"HBA1c",
				"Cholestérolémie HDL"=>"HDL", //val => milimol/litre
				"Cholestérolémie LDL"=>"LDL", //val => milimol/litre
				"Cholestérolémie totale"=>"Chol", //val => milimol/litre
				"Créatininémie"=>"creat", //val => µmol/litre
				"Glycémie à jeun"=>"glycemie", //val mmol. 1mmol=0.18g/l => arrondi 2 chiffres
				"Kaliémie"=>"kaliemie",
				"Microalbuminurie"=>"albu",
				"Microalbuminurie des 24h"=>"albu",
				"Triglycéridémie"=>"triglycerides" //val => milimol
				);


	
	$equivalence=array("HDL"=>0.387596899,
					   "LDL"=>0.387596899,
					   "Chol"=>0.387596899,
					   "glycemie"=>0.18,
					   "creat"=>0.113,
					   "triglycerides"=>0.877192982);
/*	$remplace=array("HDL"=>" g/l",
					"LDL"=>" g/l");
	*/
	$i=6;
		
	while(isset($data->sheets[0]['cells'][$i][1])&&($data->sheets[0]['cells'][$i][1]!="")){

		$numero=$data->sheets[0]['cells'][$i][3];
		$date=$data->sheets[0]['cells'][$i][4];
		$type=$data->sheets[0]['cells'][$i][5];
		$val=$data->sheets[0]['cells'][$i][8];
		
		// echo $numero." $date $type $val<br><br>";
		$val=str_replace(",", ".", $val);
		$numero=str_replace(".", "", $numero);

		if(isset($mots[$type])){//Il s'agit d'un examen reconnu 
			$date=explode("/", $date);
			
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

					if(($mots[$type]!="HBA1c")||($val>=4)){
						$req2="SELECT date_exam, resultat1 from liste_exam where id='$id' ".
							  "and date_exam>'$date_avant' and date_exam<'$date_apres' ".
							  "and type_exam='".$mots[$type]."'";
						$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
						
						if(mysql_num_rows($res2)==0){//La donnée n'est pas présente dans asalée
							if(isset($remplace[$mots[$type]])){//L'unité est indiquée dans la valeur
								$val=str_replace($remplace[$mots[$type]], "", $val);
							}
							
							if(isset($equivalence[$mots[$type]])){
								$val=round($val*$equivalence[$mots[$type]], 2);
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
						else{//Sauvegarde dans le compte-rendu
							$lbis++;
							list($date_exam, $resultat1)=mysql_fetch_row($res2);
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