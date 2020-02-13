<?php

function integration_chatillon($fichier, $fichier_name){

	$upfile="./log/chatillon/".$fichier_name;

	if (is_uploaded_file($fichier))
	{
		if (!move_uploaded_file($fichier, $upfile))
		{
			echo "problème : impossible de télécharger le fichier des biologies";
			exit;
		}
	}

	//dézippage du fichier
include('pclzip.lib.php');

$archive = new PclZip($upfile);

if ($archive->extract(PCLZIP_OPT_PATH, "./log/chatillon/") == 0) {
    die("Error : ".$archive->errorInfo(true));
}

if (($list = $archive->listContent()) == 0) {
    die("Error : ".$archive->errorInfo(true));
}
  
$fichier="./log/chatillon/".$list[0]["filename"];


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
	$fp=fopen("$fichier", "r");
	
	$zones=array("numero", "date", "type", "val");
	$zone=0;
	$numero=$date=$type=$val="";
	
	$mots=array("PA_A1C         %"=>"HBA1c",
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
 //EA 27-02-2014

	 "LDL calculé"=>"LDL",
	 "LDL Direct :"=>"LDL",
	 "LDL Direct:"=>"LDL"
				);

	$date_seule=array("pied",
					  "monofil");
					  
	$remplace=array("HDL"=>" g/l",
					"LDL"=>" g/l");
	$numeriques=array("HBA1c",
				"poids",
				"systole",
				"diastole",
				"LDL",
				"HDL",
				"Chol",
				"triglycerides",
				"pouls",
				"creat",
				"glycemie");
	// Lire jusqu'à fin de fichier
  $currentnb = "";
  $newnb=0;				
  $dcreat = "2010-12-31"; //initilaisation
  $dnew = date ("Y-m-d", mktime(0,0,0,date("m")-2,date("d"),date("Y"))); //le nouveau sur les deux derniers mois
  $dancien =date ("Y-m-d", mktime(0,0,0,date("m")-12,date("d"),date("Y"))); //les anciens sont sur les douze derniers mois
  $today = date("Y-m-d"); 
	while($ligne=fgets($fp))
  {
		$ligne=str_replace("\n", "", $ligne);
		$ligne=str_replace("\r", "", $ligne);
		
		$ligne=explode("\t", $ligne);
		
		$numero=$ligne[0];
		$date=$ligne[1];
		$type=$ligne[2];
		$val=$ligne[3];

		
		if(isset($mots[$type])){//Il s'agit d'un examen reconnu => intégration dans une table temporaire
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
			elseif($date[2]<45){  //EA 29-10-2013  15 en 45
				$date[2]="20".$date[2];
			}
			else{
				$date[2]="19".$date[2];
			}
			
			$date=$date[2]."-".$date[1]."-".$date[0];

  
// prendre       
			if($date>"2004-03-31")
      {
        if($numero!=$currentnb)//ne faire requête que sur nouveau numero
        {
				  $req="SELECT id, dcreat from dossier WHERE cabinet='".$_SESSION["cabinet"]."' ".
					  "and numero='N$numero'";
				  $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
				  if(mysql_num_rows($res)==1)
          {
					 //list($id)=mysql_fetch_row($res);
            $theresult=mysql_fetch_array($res);
            $id = $theresult['id'];
            $dcreat=$theresult['dcreat'];
            if ($dcreat> $dnew)
                $newnb = 1;
            else
                $newnb=0;
				  }
				  else{
					   $id="";                                                   
				  }
          $currentnb = $numero;
        }
				
				if($id!="")
        {//Le dossier est reconnu
         // faire intégration uniquement si
         // nouveau numéro ou date >2012-08-01
        
        
        
         if(    
              ( ($newnb==0)&&  ($date>=$dancien)  )
            || ( $newnb==1  )
            )
        
           {
					$dateexam=explode("-", $date);
					$date_avant=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]-15, $dateexam[0]));
					$date_apres=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]+15, $dateexam[0]));

					$val_fichier=$val;
					$val=str_replace("g/l", "", $val);
					$val=str_replace("mg/l", "", $val);
					$val=str_replace(",", ".", $val);
					$val=str_replace(" ", "", $val);
					$val=str_replace("%", "", $val);
					$val=str_replace("\r", "", $val);
					$val=str_replace("\n", "", $val);
					
					$req2="SELECT date_exam, resultat1 from liste_exam where id='$id' ".
						  "and date_exam>'$date_avant' and date_exam<'$date_apres' ".
						  "and type_exam='".$mots[$type]."'";
					$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
					
					if(mysql_num_rows($res2)==0){//La donnée n'est pas présente dans asalée
						/*if(isset($remplace[$type])){//L'unité est indiquée dans la valeur
							$val=str_replace($remplace[$type], "", $val);
						}*/
						if((in_array($mots[$type], $numeriques))&&(!is_numeric($val))){//valeur non numérique pour une valeur qui doit être numérique
							$lko++;
							$worksheet_ko->write("A$lko", "");
							$worksheet_ko->write_string("B$lko", "$numero");
							$worksheet_ko->write("C$lko", "$date");
							$worksheet_ko->write("D$lko", $val_fichier);
							$worksheet_ko->write("E$lko", $mots[$type]);
							$worksheet_ko->write("F$lko", "donnée mal formatée");
						}
						else{
						
							if(in_array($mots[$type], $date_seule)){
								$req2="INSERT INTO liste_exam SET id='$id', ".
									  "date_exam='$date', ".
									  "type_exam='".$mots[$type]."'";
							}
							elseif($mots[$type]=="albu"){
								if($val>20){
									$val=1;
								}
								else{
									$val=0;
								}
								
								$req2="INSERT INTO liste_exam SET id='$id', ".
									  "date_exam='$date', resultat1='$val', ".
									  "type_exam='".$mots[$type]."'";
							}
							else{
								$req2="INSERT INTO liste_exam SET id='$id', ".
									  "date_exam='$date', resultat1='$val', ".
									  "type_exam='".$mots[$type]."'";
							}
								  // echo $req2;
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
							
							//Sauvegarde dans le compte-rendu
							$lok++;
							if($mots[$type]=="albu"){
								if($val==1){
									$val="positif";
								}
								else{
									$val="négatif";
								}
							}
							$worksheet_ok->write("A$lok", $id);
							$worksheet_ok->write("B$lok", "$numero");
							$worksheet_ok->write("C$lok", $date);
							$worksheet_ok->write("D$lok", $val);
							$worksheet_ok->write("E$lok", $mots[$type]);
						}
					}
					else{//Sauvegarde dans le compte-rendu
						if((!in_array($mots[$type], $numeriques))||(is_numeric($val))){//valeur non numérique pour une valeur qui doit être numérique
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
         }      // n'intégrer que les nouveaux ou anciens patients mais récents EA 16-08-2013
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

	$workbook->close();
	unlink($upfile);
	unlink($fichier);
	
	return($fich);
}