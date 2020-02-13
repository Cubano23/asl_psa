<?php

function integration_axisante_notzipped($fichier)
{
    require_once('pclzip.lib.php');
		$mots=array(
    
        "Cholestérol H.D.L."=>"HDL",
				"CHOLESTEROL HDL..........: (g/l)"=>"HDL",
				"Cholestérol L.D.L."=>"LDL",
				"CHOLESTEROL LDL .........: (g/l)"=>"LDL",
				"HDL"=>"HDL",
				"HDL CHOLESTEROL (g/l)"=>"HDL",
				"HDL CI8200"=>"HDL",
				"LDL"=>"LDL",
				"LDL CHOLESTEROL (g/l)"=>"LDL",
				"POIDS"=>"poids",
				"Poids"=>"poids",
				"Hb GLYCOSYLEE (%)"=>"HBA1c",
				"Hémoglobine A1C"=>"HBA1c",
				"CREATININE CI8200"=>"creat",
				"GLYCEMIE A JEUN FLUOR"=>"glycemie",
				"TRIGLYCERIDES CI8200"=>"triglycerides",
				"TRIGLYCERIDES (g/l)"=>"triglycerides",
				"CHOLESTEROL (g/l)"=>"Chol",
				"GLYCEMIE A JEUN (g/l)"=>"glycemie",
				"Créatinine"=>"creat",
				"Glycémie"=>"glycemie",
				"Cholestérol"=>"Chol",
        
        
        "PA_A1C         %"=>"HBA1c",
				"hHbA1c%	"=>"HBA1c",
        "HbA1c :"=>"HBA1c",	
        "Hémoglobine a1c"=>"HBA1c",	
        "Hemoglobine glycosylee"=>"HBA1c",	
        "hHbA1c%"=>"HBA1c",	
        "PA_A1C         %"=>"HBA1c",	

				"Ta s"=>"systole",
				"Ta s "=>"systole",
        "Tension S"=>"systole",
        "Tension D"=>"diastole",
				"Ta d"=>"diastole",
				"Ta d "=>"diastole",
				"LDL"=>"LDL",
				"LDL cholesterol"=>"LDL",
				"LDL/HDL ( N<3.50)"=>"LDL",
				"PA_LDL cal. si tri<3,4"=>"LDL",
				"hdl cholesterol"=>"HDL",
				"PA_HDL g/L"=>"HDL",
				"choles.total*"=>"Chol",
				"PA_CHOLEST.TOTAL g/L"=>"Chol",
				"triglycerides*"=>"triglycerides",
				"PA: TRIGLY.   g/L"=>"triglycerides",
        "Triglycérides"=>"triglycerides",
        "Triglycerides"=>"triglycerides",
        	

				"Poids"=>"poids",
				"Poids "=>"poids",
				"Poids="=>"poids",
        "Taille"=>"taille",	
				"Pouls"=>"pouls",
        "Pouls repos"=>"pouls",	

				"PA_CREATININ.SG.mg/L"=>"creat",
				"Creatinine"=>"creat",

				"Glycémie"=>"glycemie",
				"gly. à jeun *"=>"glycemie",
				"PA_GLY/JN FLUOR  g/L"=>"glycemie",
        "Estimation Gly"=>"glycemie",
        "Estimation Gly g/l"=>"glycemie",
        "Glycemie"=>"glycemie",
        "Glycémie"=>"glycemie",
        "PA_T 00:GLY SG g/L"=>"glycemie",
        
				"monofilament"=>"monofil",
				"etat des pieds"=>"pied",
				"microalbumine ec"=>"albu",
				"PA: MICRO ALBUMINURIE"=>"albu",
        "Microalbumine"=>"albu",
				
        "PA_POTASSIUM SG mmol/L"=>"kaliemie",
        "POTASSIUM mmol/L"=>"kaliemie",	
       
        
        "HdL"=>"HDL",
        "Hdl(0.9-1.7mmol)"=>"HDL",
        "PA: HDL g/L"=>"HDL",
        "PA_HDL g/L"=>"HDL",
        "LDL (1.15-1.75)"=>"LDL",
        "PA_LDL cal. si tri<3,4"=>"LDL",
        "PA_LDL g/L"=>"LDL",
        "PA_CHOLEST.TOTAL g/L"=>"Chol",
        
        //ea ajout pour pmx 25-04-2014
        "Cholestérol HDL"=>"HDL",
        "Cholestérol total"=>"Chol",
        "Créatinine"=>"creat",
        "creatinine"=>"creat",
        "Glycémie à jeun"=>"glycemie",
        "Potassium"=>"kaliemie",
        "Resultat microalbumine"=>"albu",
        "MICRO-ALBUMINURIE"=>"albu",
        "Resultat hba1c"=>"HBA1c",
        "HbA1c"=>"HBA1c",
        "Hba1c"=>"HBA1c",
        "HDL cholesterol"=>"HDL",
        "HDL CHOLESTEROL"=>"HDL",
        "Cholesterol hdl"=>"HDL",
        "Hdl"=>"HDL",

	// ea ajout 14-06-2014        
	"LDL calculé"=>"LDL",
	"Triglycérides :"=>"triglycerides",
	"Cholestérol total"=>"Chol",
	"Cholestérol  HDL "=>"HDL",
	"Glycémie à jeun"=>"glycemie",


        0
        
        );




	//objet process integration
	$Processeur = new ProcessIntegration($mots);

	$fp=fopen("$fichier", "r");

	$ligne=fgets($fp);
  // 02-06-2014 N pour chatillon
   	 $cab = $_SESSION["cabinet"];

  
	   while($ligne=fgets($fp)){

 		  $ligne=str_replace("\n", "", $ligne);
		  $ligne=str_replace("\r", "", $ligne);
		  $ligne=explode("\t", $ligne);
      
		
   		$numero=$ligne[0];
  		$date=$ligne[1];
	   	$type=$ligne[2];
	 	  $val=$ligne[3];
      $unite="";
    
	   	$date=explode("/", $date);
		  if($date[0]<10){
					   $date[0]="0".$date[0];
				}
				if($date[1]<10){
					$date[1]="0".$date[1];
				}
				if($date[2]<10){
					$date[2]="200".$date[2];
				}
				elseif($date[2]<45){
					$date[2]="20".$date[2];
				}
				else{
					$date[2]="19".$date[2];
				}

        $date=$date[0]."/".$date[1]."/".$date[2];
        $valeur = explode(" ", $val);
        $val = $valeur[0];
        if(isset($valeur[1]))
            $unite =  $valeur[1];
        // 02-06-2014 N . $numero pour chatillon
        if(strtolower($cab)=="chatillon")
            $numero = "N".$numero;
		    $Processeur->Process($ligne, $numero, $date, $type, $val, $unite);

	 }
 
	fclose($fp);
	return ($Processeur->End());


}

function integration_axisante($fichier, $fichier_name)
{

	 $cab = $_SESSION["cabinet"];
   $path=  "./log/".$cab."/";
   if(!is_dir ( $path ))
             mkdir($path);
	 $upfile=$path.$fichier_name;

	 if (is_uploaded_file($fichier))
	 {
		if (!move_uploaded_file($fichier, $upfile))
		{
			echo "problème : impossible de télécharger le fichier des biologies";
			exit;
	 	}
	 }

  require_once('pclzip.lib.php');
  $archive = new PclZip($upfile);

  if ($archive->extract(PCLZIP_OPT_PATH, $path) == 0) {
      die("Error : ".$archive->errorInfo(true));
  }

  if (($list = $archive->listContent()) == 0) {
    die("Error : ".$archive->errorInfo(true));
  }
  
  $fichier=$path.$list[0]["filename"];

  return(integration_axisante_notzipped($fichier) );
}