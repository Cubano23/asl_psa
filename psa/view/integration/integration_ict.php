<?php

use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Exception;

require_once ('lib/ComposerLibs/vendor/autoload.php');

function integration_ict($fichier){

    include('pclzip.lib.php');
    include('reader.php');
    // $data = new Spreadsheet_Excel_Reader();
    $reader = new Xls();

    try // Ajout de la gestion d'exception excel EA 22-01-2018
    {
        $data = $reader->load($fichier);

        $zones = array("numero", "date", "type", "val");
        $zone = 0;
        $numero = $date = $type = $val= "";

        $mots = array (
            "HbA1c"=>"HBA1c",
            "HDL"=>"HDL", //val => milimol/litre
            "LDL"=>"LDL", //val => milimol/litre
            "CT"=>"Chol", //val => milimol/litre
            "Créatinine"=>"creat", //val => ?mol/litre
            "Glycémie"=>"glycemie",  //val mmol. 1mmol=0.18g/l => arrondi 2 chiffres
            "Kaliémie (potassium)"=>"kaliemie",
            "Microalbuminurie"=>"albu",
            "TG"=>"triglycerides", //val => milimol
            "Systole"=>"systole",
            "Diastole"=>"diastole",
            "Pouls"=>"pouls",
            "Poids"=>"poids",
        );

        $Processeur = new ProcessIntegration($mots);

        $i=7;
        $cab = $_SESSION["cabinet"];

        while ($data->getSheet(0)->cellExistsByColumnAndRow(1, $i) && ($data->getSheet(0)->getCellByColumnAndRow(1, $i)->getValue() != "")){

            $date = $data->getSheet(0)->getCellByColumnAndRow(1, $i)->getValue();
            $val = $data->getSheet(0)->getCellByColumnAndRow(2, $i)->getValue();
            $unite = $data->getSheet(0)->getCellByColumnAndRow(3, $i)->getValue();
            $type = $data->getSheet(0)->getCellByColumnAndRow(4, $i)->getValue();
            $numero = $data->getSheet(0)->getCellByColumnAndRow(5, $i)->getValue();

            /*		$val = str_replace("?", "", $val);
                    $val = str_replace("'", "", $val);
                */

            // 02-06-2014 N . $numero pour chatillon
            if(strtolower($cab)=="frontenay")
                $numero = $numero."!ESPACE!I";

            $Processeur->Process($ligne, $numero, $date, $type, $val, $unite);

            $i++;
        }
    }
    catch (Exception $e)
    {
        error_log("***** ERREUR LORS DE L'INTÉGRATION : " . $e->getMessage());
    }

    unlink($fichier);

    return($Processeur->End());
    //return($fich);
}
