<?php


function integration_mediclic($fichier){
  include('pclzip.lib.php');


	$mots=array("HbA1c"=>"HBA1c",
				"HB A1C"=>"HBA1c",
				"HBA1C"=>"HBA1c",
				"H�moglobine Glycosyl�e"=>"HBA1c",
				"HDL Cholesterol"=>"HDL", 
				"LDL cholesterol"=>"LDL", 
				"LDL Cholesterol"=>"LDL",  
				"Cholesterol Total"=>"Chol", 
				"creatinine"=>"creat", 
				"Creatinine"=>"creat",
				"Cholesterol Total"=>"Chol", 
				"creatinine"=>"creat", 
				"Creatininemie"=>"creat",
				"Glycemie � jeun"=>"glycemie",  
				"Glycemie"=>"glycemie",
				"Kaliemie"=>"kaliemie",
				"Potassium"=>"kaliemie",
				"Microalbuminurie"=>"albu",
				"Triglycerides"=>"triglycerides",
        "CHOLESTEROL TOTAL"=>"Chol", 
        "LDL CHOLESTEROL"=>"LDL", 
        "HDL CHOLESTEROL"=>"HDL",
        "CREATININE"=>"creat",
        "TRIGLYCERIDES"=>"triglycerides",
        "Systole"=>"systole", 
				"Diastole"=>"diastole",
        "H�moglobine glyqu�e"=>"HBA1c",
        "LDL Chol par calcul"=>"LDL" 
				);

	//objet process integration
	$Processeur = new ProcessIntegration($mots);

	$fp=fopen("$fichier", "r");

	$ligne=fgets($fp);
	$ligne=explode("\t", $ligne);
	$type=$ligne[10];

	while($ligne=fgets($fp)){
		
		$ligne=explode("\t", $ligne);
		$numero=$ligne[9];
		$val=$ligne[10];
		$date=$ligne[11];
		$unite ="";	
		
		$Processeur->Process($ligne, $numero, $date, $type, $val, $unite);
 // error_log($numero." "." ".$date);
	}

	fclose($fp);
	return ($Processeur->End());
}