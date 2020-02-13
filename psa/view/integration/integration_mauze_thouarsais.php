<?php

function integration_mauze_thouarsais($fichier_ex, $fichier_pat){

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

	//1- intégration des patients dans une table des patients avec 
	//nom/prénom/ville/id/dnaiss/tel
	
	$fp=fopen("$fichier_pat", "r");

	$lko=1;
	// $fp=fopen("$fichier_pat", "r");
	$debut=0;

	while($ligne=fgets($fp)){

		$ligne=explode("\t", $ligne);
		$nom=$ligne[0];
		$prenom=$ligne[1];
		$dnaiss=$ligne[2];
		$id=$ligne[4];
		$id=str_replace("\r\n", "", $id);
		
		if(($id!="")&&($id>0)){
			$req="SELECT nom, prenom, dnaiss from pat_mediclic ".
				 "where numero='$id' and cabinet='".$_SESSION["cabinet"]."'";
			$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
			
			if(mysql_num_rows($res)==0){//Le même n° de patient n'a pas été trouvé
				$req2="INSERT INTO pat_mediclic SET numero='$id', ".
					  "nom='".addslashes(stripslashes($nom))."', prenom='".
					  addslashes(stripslashes($prenom))."', ".
					  "dnaiss='$dnaiss', cabinet='".$_SESSION["cabinet"]."'";
					   // echo $req2;
				$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
			}
			else{
				list($nom_double, $prenom_double, $dnaiss_double)=mysql_fetch_row($res);
				if(!isset($doublons[$id][$nom_double.$prenom_double.$dnaiss_double])){
					$doublons[$id][$nom_double.$prenom_double.$dnaiss_double]=array($nom_double, $prenom_double, $dnaiss_double);
				}
				$doublons[$id][$nom.$prenom.$dnaiss]=array($nom, $prenom, $dnaiss);
			}
		}
					
		
	}

	if(isset($doublons)){
		foreach($doublons as $id=>$var){
			$req="DELETE FROM pat_mediclic ".
				 "where numero='$id' and cabinet='".$_SESSION["cabinet"]."'";
			$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
		}
	}

	$zone=0;
	$numero=$date=$type=$val="";
	
	$mots=array("HbA1c"=>"HBA1c",
				"HDL Cholesterol"=>"HDL",
				// "Cholesterol Total"=>"Chol",
				"Triglycerides"=>"triglycerides",
				"LDL Cholesterol"=>"LDL",
				"Glycemie à jeun"=>"glycemie",
				"Créatinine"=>"creat",
				"Protéinurie"=>"proteinurie", 
				"Potassium"=>"kaliemie");

	$remplace=array("HDL"=>" g/l",
					"LDL"=>" g/l");
					

	
	$zones=array("nom", "prenom", "dnaiss", "date", 
				 "type", "val");

	$lko=1;
	$fp=fopen("$fichier_ex", "r");
	$zone=0;

	foreach($zones as $var){
		if($var!=""){
			$$var="";
		}
	}
	
	while($ligne=fgets($fp)){

		$ligne=explode("\t", $ligne);
		$nom=$ligne[0];
		$prenom=$ligne[1];
		$dnaiss=$ligne[2];
		$date=$ligne[3];
		$type=$ligne[4];
		$val=$ligne[5];
		$zone=0;
		$nom=str_replace("\"", "", $nom);
		$prenom=str_replace("\"", "", $prenom);
		$dnaiss=str_replace("\"", "", $dnaiss);
		$date=str_replace("\"", "", $date);
		$type=str_replace("\"", "", $type);
		$val=str_replace("\"", "", $val);
		$val=str_replace("\r", "", $val);
		$val=str_replace("\n", "", $val);
		
		$req="SELECT numero from pat_mediclic WHERE nom='".addslashes(stripslashes($nom))."' ".
			 "and prenom='".addslashes(stripslashes($prenom))."' ".
			 "and dnaiss='$dnaiss' and cabinet='".$_SESSION["cabinet"]."'";
		$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
		
		//Le patient est identifié
		if(mysql_num_rows($res)==1){

			list($numero)=mysql_fetch_row($res);
			if(isset($mots[$type])){//Il s'agit d'un examen reconnu => intégration dans une table temporaire
				
				$val=str_replace(",", ".", $val);
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
		}
		else{
			$lko++;
			$worksheet_ko->write("A$lko", "");
			$worksheet_ko->write_string("B$lko", "$nom $prenom $dnaiss");
			$worksheet_ko->write("C$lko", "$date");
			$worksheet_ko->write("D$lko", $val);
			$worksheet_ko->write("E$lko", $type);
			$worksheet_ko->write("F$lko", "Numéro de dossier non indiqué dans le fichier patient");
		}
			
	}

	$workbook->close();
	
	$req="DELETE from pat_mediclic WHERE cabinet='".$_SESSION["cabinet"]."'";
	$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

	if(isset($doublons)){
		echo "Liste des patients en doublon : <br>";
		echo "<table border='1'><tr><td>numéro dossier</td><td>Nom</td><td>Prénom</td><td>Date de naissance</td></Tr>";

		foreach($doublons as $id=>$tab){
			echo "<tr><td rowspan='".count($tab)."'>$id</Td>";
			$tr="";
			foreach($tab as $liste){
				echo $tr."<td>".$liste[0]."</td><td>".$liste[1]."</Td><td>".$liste[2]."</td></Tr>";
				$tr="<tr>";
			}
		}
		
		echo "</table><br><br>";
	}
	return($fich);
}