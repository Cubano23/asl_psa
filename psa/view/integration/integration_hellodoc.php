<?php


function integration_hellodoc($fichier, $fichier_name){


    $currentDir = getcwd();
    $uploadDirectory = "/log/";

    $cab = $_SESSION["cabinet"];
    $path = $currentDir . $uploadDirectory . $cab . "/";

    if(!is_dir ( $path )) {
        mkdir($path);
    }
    $upfile=$path.$fichier_name;

    if (is_uploaded_file($fichier))
    {
        if (!move_uploaded_file($fichier, $upfile))
        {
            echo "probl�me : impossible de t�l�charger le fichier des biologies";
            exit;
        }
    }

    include('pclzip.lib.php');
    $archive = new PclZip($upfile);
    if ($archive->extract(PCLZIP_OPT_PATH, $path) == 0) {
        die("Error : ".$archive->errorInfo(true));
    }

    if (($list = $archive->listContent()) == 0) {
        die("Error : ".$archive->errorInfo(true));
    }

    $fichier=$path.$list[0]["filename"];





    $mots=array(

//albumine
        "MICROALBUMINURIE"=>"albu",
        "MICROALBUMINURIE "=>"albu",      //06-11-2014
        "MICROALBUMINURIE EN MG/L"=>"albu",
        "MICRO-ALBUMINURIE"=>"albu",
        "MICROALBINURIE"=>"albu",
        "microalbuminurie/24h"=>"albu",
        "Dosage microalbuminurie"=>"albu",
        "Microalbuminurie"=>"albu",
        "MICROALBUMINURIE 24h (+)"=>"albu",
        "MICRO"=>"albu",
        "MICRO/24H"=>"albu",
        "Microalbumine:"=>"albu",
        "Microalbumine :"=>"albu",
        "PA_ALBUMINEMIE G/L"=>"albu",
        "PA: MICRO ALBUMINURI"=>"albu",
        "MICROALBUMINURIE . . . . . . . . ."=>"albu",//EA 04-06-2014
//Cholest�rol        
        "CHOLESTEROL total"=>"Chol",
        "CHOLESTEROL TOTAL"=>"Chol",
        "CHOLESTEROL total:"=>"Chol",
//				"CHOLESTEROL total:"=>"Chol",
        "PA_CHOLEST.TOTAL G/L"=>"Chol",
        "CHOLESTEROL"=>"Chol",
        "CHOLES.TOTAL*"=>"Chol",
        "PA_CHOLEST.TOTAL G/L"=>"Chol",
//				"CHOLESTEROL TOTAL"=>"Chol",
        "Cholest�rol"=>"Chol",
        "Cholest�rol total"=>"Chol",

//HDL        
        "CHOLESTEROL des HDL"=>"HDL",
        "CHOLESTEROL des HDL:"=>"HDL",
        "H.D.L"=>"HDL",
        "HDL"=>"HDL",
        "PA: HDL G/L"=>"HDL",
        "PA_HDL G/L"=>"HDL",
        "Cholest�rol HDL:"=>"HDL",
        "Cholest�rol HDL :"=>"HDL",
        "HDL Cholesterol"=>"HDL",
        "Cholest�rol HDL"=>"HDL",
        "H.D.L CHOLESTEROL"=>"HDL",
        "H.D.L. Cholest�rol"=>"HDL",
        "CHOLESTEROL H.D.L"=>"HDL",
        "CHOLESTEROL H.D.L."=>"HDL",
        "HDL CHOLESTEROL"=>"HDL",
//				"HDL CHOLESTEROL"=>"HDL",
        "H.D.L.________"=>"HDL", //EA 23-12-2014 
        "CHOLESTEROL HDL *"=>"HDL", //EA 30-12-2014
//LDL        
        "CHOLESTEROL des LDL"=>"LDL",
        "CHOLESTEROL des LDL: 9"=>"LDL",
        "LDL"=>"LDL",
        "L.D.L"=>"LDL",
        "LDL Cholesterol"=>"LDL",
        "LDL Calcul�:"=>"LDL",
        "LDL calcul� :"=>"LDL",
        "LDL Direct :"=>"LDL",
        "L.D.L. Cholest�rol"=>"LDL",
        "Cholest�rol LDL"=>"LDL",
        "CALCUL DU LDL-CHOLESTEROL"=>"LDL",
        "CHOLESTEROL LDL Calcul�"=>"LDL",
        "CHOLESTEROL LDL Calcule"=>"LDL",
        "CHOLESTEROL LDL Mesure"=>"LDL",
        "CHOLESTEROL L.D.L"=>"LDL",
        "PA_LDL CAL. SI TRI<3"=>"LDL",
        "PA_LDL G/L"=>"LDL",
        "LDL CHOLESTEROL"=>"LDL",
        "LDL CHOLESTEROL(calcule)"=>"LDL",  //EA 04-06-2014
        "LDL CHOLESTEROL(calcul�)"=>"LDL",  //EA 04-06-2014
        "LDL Cholest�rol"=>"LDL",  //EA 04-06-2014
        "LDL CHOLESTEROL(mesure)"=>"LDL",  //EA 04-06-2014
        "LDL CHOLESTEROL(mesur�)"=>"LDL",  //EA 04-06-2014
        "CHOLESTEROL L.D.L."=>"LDL",
        "L.D.L CHOLESTEROL"=>"LDL",
        "LDL Calcul�_______"=>"LDL", //EA 23-12-2014
        "LDL calcul� *"=>"LDL", //EA 30-12-2014   
        //Cr�atinine
        "CREATININE"=>"creat",
        "CREATININE:"=>"creat",
        "Cr�atinin�mie."=>"creat",
        "Cr�atinine mg/l"=>"creat",
        "Creatinine sanguine"=>"creat",
        "Creatinine Serique"=>"creat",
        "CREATININE SERIQUE"=>"creat",
        "CREATININE s�rique"=>"creat",
        "Cr�atinine S�rique"=>"creat",
        "Cr�atinine"=>"creat",
//				"CREATININE"=>"creat",
        "Cr�atinine :"=>"creat",
        "Cr�atinine:"=>"creat",
        "CREATININEMIE"=>"creat",
        "PA_CREATININ.SG.MG/L"=>"creat",
        "Creatinine"=>"creat",
        "clairance creatinine"=>"creat",      //25-06-2014
        "clairance creatinine "=>"creat",      //25-06-2014
        "CREATININE *"=>"creat",      //30-06-2014
//Glyc�mie        
        "GLYCEMIE � jeun"=>"glycemie",
        "GLYCEMIE a jeun:"=>"glycemie",
        "GLYCEMIE A JEUN"=>"glycemie",
        "GLYCEMIE A JEUN "=>"glycemie", //06-11-2014
        "GLYCEMIE"=>"glycemie",
        "Glyc�mie"=>"glycemie",
        "GLYCEMIE a jeun"=>"glycemie",
        "GLYCEMIE"=>"glycemie",
        "PA_GLY/JN FLUOR  G/L"=>"glycemie",
        "Glycemie a jeun"=>"glycemie",
        "Glyc�mie � jeun."=>"glycemie",
//HBA1C        
        "H�moglobine glycosyl�e A1c"=>"HBA1c",
        "HEMOGLOBINE GLYCOSYLEE HBA1c"=>"HBA1c",
        "HB GLYQUEE A1c"=>"HBA1c",
        "HEMOGLOBINE GLYQUEE"=>"HBA1c",
        "H�moglobine glycosyl�e (HbA1c)"=>"HBA1c",
        "HB A1C"=>"HBA1c",
        "HHBA1C%"=>"HBA1c",
        "PA_A1C         %"=>"HBA1c",
        "PA_A1C%"=>"HBA1c",
        "PA_A1C %"=>"HBA1c", //EA 06-11
        "HbA1c :"=>"HBA1c",
        "Hemoglobine HbA1c"=>"HBA1c",
        "HEMOGLOBINE GLYCOSYLEE HbA1c"=>"HBA1c",
        "H�moglobine glycosyl�e"=>"HBA1c",
        "HEMOGLOBINE GLYCOSYLEE"=>"HBA1c",
        "DOSAGE DE L'HEMOGLOBINE A1c"=>"HBA1c",
        "HBA1C"=>"HBA1c",
        "HBA1c"=>"HBA1c",
        "HbA1c"=>"HBA1c",
        "HbA1C"=>"HBA1c",
        "H�moglobine glycosyl�e A1c"=>"HBA1c",
        "H�moglobine Glycosyl�e HbA1c"=>"HBA1c",
        "HEMOGLOBINE A1C"=>"HBA1c", //30-04-2014                                                  
        "HEMOGLOBINE GLYQUEE (Hb A1c)"=>"HBA1c", //04-06-2014
        "Glycemie moyenne estimee"=>"HBA1c", //04-06-2014
        "HBA1c par H.P.L.C. :"=>"HBA1c", //EA 23-12-2014
        "HEMOGLOBINE GLYCOSYLEE(*)"=>"HBA1c", //EA 23-12-2014
        "HBA1c par H.P.L.C*. :"=>"HBA1c", //EA 30-12-2014
//Kali�mie        
        "POTASSIUM:"=>"kaliemie",
        "POTASSIUM"=>"kaliemie",
        "Potassium"=>"kaliemie",
        "PA_POTASSIUM SG MMOL"=>"kaliemie",
//				"POTASSIUM"=>"kaliemie",
        "Kaliemie"=>"kaliemie",
        "Kali�mie"=>"kaliemie",
        "KALIEMIE"=>"kaliemie",
        "POTASSIUM________"=>"kaliemie", //EA 23-12-2014 

//Triglyc�rides        
        "TRIGLYCERIDES:"=>"triglycerides",
        "TRIGLYCERIDES"=>"triglycerides",
        "Triglyc�rides:"=>"triglycerides",
        "Triglyc�rides :"=>"triglycerides",
        "Triglyc�rides"=>"triglycerides",
//				"TRIGLYCERIDES"=>"triglycerides",
        "Triglycerides"=>"triglycerides",
        "Triglicerides"=>"triglycerides",
        "PA: TRIGLY.   G/L"=>"triglycerides",
//        "Triglycerides"=>"triglycerides",
        "TRIGLYCERIDES_______"=>"triglycerides", //EA 23-12-2014
        "TRIGLYCERIDES *"=>"triglycerides", //EA 30-12-2014
//Divers
        "POIDS"=>"poids",
        "PAS/PAD"=>"tension",
        "Fond d'Oeil"=>"fond",
        "ECG"=>"ECG",
        "POULS"=>"pouls",
        "HEMATURIE"=>"hematurie",
        "Systole"=>"systole",
        "Diastole"=>"diastole",
        "PROTEINURIE"=>"proteinurie",


//EA 27-01-2015
        "glyc�mie (g/L) :"=>"glycemie",
        "cholest�rol total (g/L) :"=>"Chol",
        "Triglyc�rides (g/L) :"=>"triglycerides",
        "HDL-cholest�rol (g/L) :"=>"HDL",
        "LDL calcul� (g/L) :"=>"LDL",
        "HbA1c mmol/mol IFCC"=>"HBA1c",
        "HEMOGLOBINE A1c (HbA1c)"=>"HBA1c",
//EA 28-01-2015         
        "h�moglobine glyqu�e A1c"=>"HBA1c",
        "HBA1C (IFCC):"=>"HBA1c",
        "H�moglobine Glyqu�e HbA1c"=>"HBA1c",
        "HbA1c (mmol/mol d'h�moglobine IFCC):"=>"HBA1c",
        "mmol HbA1c/mol Hb (UI IFCC)"=>"HBA1c",
        "HEMOGLOBINE GLYQUEE (HbA1c) :"=>"HBA1c",
        "H�moglobine A1C"=>"HBA1c",
        "HbgA1c"=>"HBA1c",
        "HEMO GLYCOSYLEE HBA1c"=>"HBA1c",
        "HEMO GLYCO HBA1C"=>"HBA1c",
        "ou en mmol Hb A1c/mol Hb (UI IFCC)"=>"HBA1c",
        "Hemoglobine HbA1c unite IFCC"=>"HBA1c",
        "HB glycosyl�e %"=>"HBA1c",
        "HB glycosyl�e %"=>"HBA1c",
        "h�moglobine glqyu�e A1c"=>"HBA1c",
        "Hg A1c"=>"HBA1c",

//EA 10-02-2015        
        "GLYCEMIE @ jeun"=>"glycemie",
        "� Albuminurie"=>"albu",
        "Glyc�mie � jeun g/L"=>"glycemie",
        "Triglyc�rid�mie"=>"triglycerides",
        "Triglycerides en g/l"=>"triglycerides",
//EA 11-02-2015        
        "Microalbuminurie des 24 h"=>"albu",

//EA 20-04-2015
        "Cholest�rol LDL calc"=>"LDL",
        "Cholest�rol LDL dos"=>"LDL",
        "CHOLEST�ROL LDL CALC"=>"LDL",
        "H�moglobine glycosyl�e A1c"=>"HBA1c",
        "H�moglobine A1C"=>"HBA1c",
        "H�MOGLOBINE A1C"=>"HBA1c",
        "Triglyc�rides"=>"triglycerides",
        "Cholest�rol"=>"Chol",
        "Cr�atinine"=>"creat",
        "Glyc�mie=>"=>"glycemie",
        "Cholest�rol LDL calc"=>"LDL",

//EA 28-12-2015 
        "Cholest�rol SI"=>"Chol",
        "LDL calcul�"=>"LDL",
        "RAPPORT MIA/CRU"=>"albu",
        "L.D.L. Cholest�rol calcul�"=>"LDL",
        "L.D.L. CHOLESTEROL calcul�"=>"LDL",
        "H.D.L. CHOLESTEROL"=>"HDL",
//fin

        0

    );


    //objet process integration
    $Processeur = new ProcessIntegration($mots);

    $fp=fopen("$fichier", "r");
    $numero="";
    $date="";
    $type="";

    while($ligne=fgets($fp))
    {

        $ligne=str_replace("\n", "", $ligne);
        $ligne=str_replace("\r", "", $ligne);
        $ligne=explode(";", $ligne);

        if(isset($ligne[1])&&($ligne[1]=="BIOLOGIE"))
        {//On est sur une ligne "titre"
            //on r�cup�re alors le n� dossier et date exam
            $numero=$ligne[0];
            if(isset($ligne[2]))
                $date=$ligne[2];
        }
        else
        {
            if(count($ligne)>2)
            {//On est sur un mot cl� => on cherche � int�grer
                $type=$ligne[0];
                $val=$ligne[2];
                $unite="";
                if(isset  ($ligne[6]))
                    $unite = $ligne[6];
                $Processeur->Process($ligne, $numero, $date, $type, $val, $unite);

            }
        }
    }
    fclose($fp);

    unlink($upfile);
    unlink($fichier);
    return($Processeur->End());


}