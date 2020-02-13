<?php

function integration_crossway($fichier){

  include('pclzip.lib.php');
  $cab = $_SESSION["cabinet"];
  
	include('reader.php');
	$data = new Spreadsheet_Excel_Reader();
	$data->read($fichier);
	
	$zones=array("numero", "date", "type", "val");
	$zone=0;
	$numero=$date=$type=$val="";
	
	$mots=array("Hémoglobine glycosylée"=>"HBA1c",
				"Hémoglobine glyquée"=>"HBA1c",
        "Hémoglobine glyquée (HbA1c)"=>"HBA1c", //EA 22-03-2016
				"Cholestérolémie HDL"=>"HDL", //val => milimol/litre
        "Cholesterol&eacute;mie HDL"=>"HDL", //val => milimol/litre
				"Cholestérolémie LDL"=>"LDL", //val => milimol/litre
        "Cholestérol&eacute;mie LDL"=>"LDL", //val => milimol/litre
				"Cholestérolémie totale"=>"Chol", //val => milimol/litre
        "Cholest&eacute;rol&eacute;mie totale"=>"Chol", //val => milimol/litre
				"Créatininémie"=>"creat", //val => œmol/litre
				"Glycémie à jeun"=>"glycemie", //val mmol. 1mmol=0.18g/l => arrondi 2 chiffres
				"Kaliémie"=>"kaliemie",
        "Kaliemie"=>"kaliemie",
        "Potassium"=>"kaliemie",
				"Microalbuminurie"=>"albu",
				"Microalbuminurie des 24h"=>"albu",
				"Triglycéridémie"=>"triglycerides", //val => milimol
        "Systole"=>"systole", 
				"Diastole"=>"diastole",
        
        
        0
         
				);

	
      $unites=array("Chol"=>"mmol/l",
				            "LDL"=>"mmol/l",
				            "HDL"=>"mmol/l",
				            "creat"=>"œmol/l" ,
				            "glycemie"=>"mmol/l",
				            "triglycerides"=>"mmol/l");

	//objet process integration
	$Processeur = new ProcessIntegration($mots);
  
	$i=6;

	while(isset($data->sheets[0]['cells'][$i][1])&&($data->sheets[0]['cells'][$i][1]!=""))
  {

		$numero=$data->sheets[0]['cells'][$i][3];
		$date=$data->sheets[0]['cells'][$i][4];
		$type=$data->sheets[0]['cells'][$i][5];
		$val=$data->sheets[0]['cells'][$i][8];
    $unite="";
    
	   if(isset($mots[$type]))
     {
          $exam = $mots[$type];
          if(isset($unites[$exam]))
            $unite=$unites[$exam];  
     
     };
    	
		// echo $numero." $date $type $val<br><br>";
		$numero=str_replace(".", "", $numero);
    if($cab=="collet")
            $numero=substr($numero,4);
//    error_log($numero."/".$unite);
    $Processeur->Process($ligne, $numero, $date, $type, $val, $unite);
    

		$i++;

	}


	 unlink($fichier);
	 return($Processeur->End());

  }

?>