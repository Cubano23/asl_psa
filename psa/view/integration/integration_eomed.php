<?php


function integration_eomed($fichier){
error_reporting(E_ALL);

include('pclzip.lib.php');
ini_set("auto_detect_line_endings", true);
	//init dico
	$mots=array("HbA1c"=>"HBA1c",
				"HB A1C"=>"HBA1c",
				"HBA1C"=>"HBA1c",
				"Hémoglobine Glycosylée"=>"HBA1c",
				"HDL Cholesterol"=>"HDL", 
				"LDL cholesterol"=>"LDL", 
				"LDL Cholesterol"=>"LDL",  
				"Cholesterol Total"=>"Chol", 
				"creatinine"=>"creat", 
				"Creatinine"=>"creat",
				"Cholesterol Total"=>"Chol", 
				"creatinine"=>"creat", 
				"Creatininemie"=>"creat",
				"Glycemie Ã  jeun"=>"glycemie",  
				"Glycemie"=>"glycemie",
				"Kaliemie"=>"kaliemie",
				"Potassium"=>"kaliemie",
				"Microalbuminurie"=>"albu",
				"Triglycerides"=>"triglycerides",
				"Poids"=>"poids",
        
//EA 10-02-2017        
        "poids"=>"poids",
        "systole"=>"systole",
        "diastole"=>"diastole",
				"HDL"=>"HDL", 
				"LDL"=>"LDL", 
        "Chol"=>"Chol",
        "triglycerides"=>"triglycerides",
        "Creat"=>"creat",
        "glycemie"=>"glycemie",
        "kaliemie"=>"kaliemie",
        "Pouls"=>"pouls",
        "systole"=>"systole",
        "diastole_automesure"=>"diastole_auto",
        "systole_automesure"=>"systole_auto",
        "pieds"=>"pieds",
        "monofil"=>"monofil",
        "ECG"=>"ECG",
        "oeil"=>"oeil",
        "dentiste"=>"dentiste"
        
				);

	//objet process integration
	$Processeur = new ProcessIntegration($mots);

	$fp=fopen("$fichier", "r");
	$Processeur->isMysqlDate = 1;
	while($ligne=fgets($fp)){
	 	
   $ligne = str_replace("\r", "", $ligne);

   
	 	$ligne=explode("\t", $ligne);
		$numero=$ligne[0];
		$date=$ligne[1];
		$type=$ligne[2];
		$val=$ligne[3];
		$unite=$ligne[4];

		$Processeur->Process($ligne, $numero, $date, $type, $val, $unite);
	}
	fclose($fp);
	return ($Processeur->End());
	
}