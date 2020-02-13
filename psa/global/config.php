<?php
error_reporting(E_ALL);
//globals config


##DIABETE ####
$_ENV['liste_exam_diabete'] = array("creat", "albu", "fond", "ECG", 
					  		"dent", "pied", "monofil", "poids", "systole", "antiDiabetiqueOraux",
					  		"diastole", "type_tension", "HDL", "LDL", "HBA1c","triglycerides","kaliemie");
					
//Recherche de la liste des examens pour visualisation des examens passés
$_ENV['liste_examRD_diabete'] = array("poids"=>"dPoids", 
									  "antiDiabetiqueOraux"=>"dantiDiabetiqueOraux",
									  "systole"=>"dtension", 
									  "diastole"=>"dtension", 
									  "type_tension"=>"dtension", 
									  "dent"=>"dentiste", 
									  "HDL"=>"dChol", 
									  "LDL"=>"dLDL",
									  "monofil"=>"dExaFil",
									  "pied"=>"dExaPieds",
									  "creat"=>"dCreat",
									  "albu"=>"dAlbu",
									  "fond"=>"dFond",
									  "ECG"=>"dECG",
									  "HBA1c"=>"dHBA",
									  "triglycerides"=>"dTriglycerides",
									  "kaliemie"=>"dKaliemie"
									  );

										  
//Récupération des données saisie dans les examens pour les remettre dans le suivi diabète
$_ENV['liste_exam_saisie_diabete'] = array("creat"=>array("val"=>"Creat", "date"=>"dCreat", "val2"=>"iCreat"), 
									  "albu"=>array("val"=>"iAlbu", "date"=>"dAlbu"), 
									  "fond"=>array("val"=>"iFond", "date"=>"dFond"), 
									  "ECG"=>array("val"=>"iECG", "date"=>"dECG"), 
									  "dent"=>array("val"=>"", "date"=>"dentiste"), 
									  "pied"=>array("val"=>"", "date"=>"dExaPieds"), 
									  "monofil"=>array("val"=>"", "date"=>"dExaFil"),
									  "poids"=>array("val"=>"poids", "date"=>"dPoids"),
									  "HDL"=>array("val"=>"HDL", "date"=>"dChol"),
									  "LDL"=>array("val"=>"LDL", "date"=>"dLDL"),
									  "triglycerides"=>array("val"=>"triglycerides", "date"=>"dTriglycerides"),
									  "kaliemie"=>array("val"=>"kaliemie", "date"=>"dKaliemie"),								  
									  "HBA1c"=>array("val"=>"ResHBA", "date"=>"dHBA")
									  );		

										 

############

####CANCER ######

$liste_exam_cancer = array("creat", "albu", "fond", "ECG", 
					  "dent", "pied", "monofil", "poids", "systole", 
					  "diastole", "type_tension", "HDL", "LDL", "HBA1c");



?>
