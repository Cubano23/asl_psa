<?php

require_once ("Config.php");
$config = new Config();
require_once ('lib/ComposerLibs/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Exception;

class ProcessIntegration
{
    var $cabinet;
    var $spreadsheet;
    var $spreadsheet_ok;
    var $spreadsheet_bis;
    var $spreadsheet_ko;
    var $fich;
    var $lok;
    var $lbis;
    var $lko;
    var $mots;
    var $equivalences;
    var $unites;
    var $currentnb;
    var $id;
    var $isMysqlDate;
    var $minValues;
    var $maxValues;
    var $beginDate;
    var $fp;
    var $mots2 = array(); //EA 26-01-2015
    var $logpath; //EA 20-08-2016


    var $config;

    function AnonymizeFilename( $fname)
    {
        $inputKey = pack("H*","E49F211F72FDA17B3420DEADEA99ADF5");
        $f = hash_hmac ( "md5" , $fname, $inputKey );

        return $fname.".".$f;

    }

    function __construct($mots)
    {

        $this->config = new Config();
        $conffile = $this->config->files_path ."/asalee.ini";
        $conf = parse_ini_file ( $conffile, true );
        $this->logpath = $conf["DIRECTORIES"]["integrations"];
        $this->logpath ="./log"; //cct pour faire des tests

        $this->cabinet = $_SESSION["cabinet"];
        date_default_timezone_set('Europe/Berlin'); //EA 22-04-2014
        $this->fich=$this->logpath."/".
            $this->AnonymizeFilename("Compte-rendu-integration ".$this->cabinet." ".date("d-m-Y")).".xlsx";



        ini_set('memory_limit', '2048M');

        try
        {
            $this->spreadsheet = new Spreadsheet();
            $this->spreadsheet->removeSheetByIndex(0);

            //Preparation du fichier de compte-rendu
            $this->spreadsheet_ok = $this->spreadsheet->createSheet();
            $this->spreadsheet_ok->setTitle("données intégrées");
            $this->spreadsheet_ok->setCellValue("A1", "Id dans Asalée");
            $this->spreadsheet_ok->setCellValue("B1", "numéro dossier");
            $this->spreadsheet_ok->setCellValue("C1", "date");
            $this->spreadsheet_ok->setCellValue("D1", "valeur");
            $this->spreadsheet_ok->setCellValue("E1", "type examen");

            $this->lok=1;

            $this->spreadsheet_bis = $this->spreadsheet->createSheet();
            $this->spreadsheet_bis->setTitle("données existantes");
            $this->spreadsheet_bis->setCellValue("A1", "Id dans Asalée");
            $this->spreadsheet_bis->setCellValue("B1", "numéro dossier");
            $this->spreadsheet_bis->setCellValue("C1", "date dans export");
            $this->spreadsheet_bis->setCellValue("D1", "valeur dans export");
            $this->spreadsheet_bis->setCellValue("E1", "date dans Asalée");
            $this->spreadsheet_bis->setCellValue("F1", "valeur dans Asalée");
            $this->spreadsheet_bis->setCellValue("G1", "type examen");

            $this->lbis=1;

            $this->spreadsheet_ko = $this->spreadsheet->createSheet();
            $this->spreadsheet_ko->setTitle("données non intégrées");
            $this->spreadsheet_ko->setCellValue("A1", "Id dans Asalée");
            $this->spreadsheet_ko->setCellValue("B1", "numéro dossier");
            $this->spreadsheet_ko->setCellValue("C1", "date");
            $this->spreadsheet_ko->setCellValue("D1", "valeur");
            $this->spreadsheet_ko->setCellValue("E1", "type examen");
            $this->spreadsheet_ko->setCellValue("F1", "Raison non intégration");
        }
        catch (Exception $e)
        {
            error_log("***** ERREUR LORS DE L'INTÉGRATION : " . $e->getMessage());
        }

      
        //error_log("fini creation");
        $this->lko=1;
        $this->mots = $mots;
        $this->isMysqlDate = 0;

        //Coefficients pour passer des mmol au mg
        $this->equivalences=array(
            "Chol"=>2.58,
            "HDL"=>2.58,
            "LDL"=>2.58,
            "creat"=>8.85,
            "glycemie"=>5.56,
            "triglycerides"=>1.14
        );

        //Liste des unités é remplacer
        $this->unites=array("Chol"=>array("mmol/l"),
            "LDL"=>array("mmol/l"),
            "HDL"=>array("mmol/l"),
            "creat"=>array("µmol/l", "mmol/l" ), //06-01-2014 EAOUAD
            "glycemie"=>array("mmol/l"),
            "triglycerides"=>array("mmol/l"),
            "HBA1c"=>array("mmol/mol", "mmol/molifcc") //EAOUAD 27-01-2015

        );

        $this->currentnb="";

        $this->minValues=array(
            // "Chol"=>1.4,
            "LDL"=>0,
            // "HDL"=>0.35,
            "creat"=>0,
            // "glycemie"=w
            "HBA1c"=> 1,
            "systole"=>50,
            "diastole"=>15
            // "triglycerides"=>0.5
        );

        $this->maxValues=array(
            // "Chol"=>2,
            "LDL"=>10,
            // "HDL"=>0.6,
            "creat"=>100,
            // "glycemie"=>1.05,
            "HBA1c"=> 20,
            "systole"=>300,
            "diastole"=>150
            // "triglycerides"=>1.5
        );

        $this->beginDate="2004-01-01";


        //Canonize
        // => déplacement des traces dans répertoire _files EA 20-08-2016
        $this->fp = fopen($this->logpath."/slug.txt", "a+");

        foreach ($mots as $key => $value)
        {
            $k =canonize($key);
            $this->mots2[$k] = $value;
            //canonize
            // fprintf($this->fp, "%s: %s=>%s\n", $key, $k, $value);
        }
        $this->mots = $this->mots2;
    }
    function TransformDate($date)
    {

        $mois = array(
            "JAN"=>"01", "FEV"=>"02", "MAR"=>"03", "AVR"=>"04", "MAI"=>"05", "JUN"=>"06",
            "JUI"=>"07", "AOU"=>"08", "SEP"=>"09", "OCT"=>"10", "NOV"=>"11", "DEC"=>"12",

            "janvier"=>"01", "fevrier"=>"02", "mars"=>"03", "avril"=>"04", "mai"=>"05", "juin"=>"06",
            "juillet"=>"07", "aout"=>"08", "septembre"=>"09", "octobre"=>"10", "novembre"=>"11",
            "decembre"=>"12",

            "juil"=>"07", "JUL"=>"07", //28-07-2016 EA
            "JUIN"=>"06", //22-06-2017 EA
            "JUIL"=>"07" //11-07-2017 EA
        );

        if (strpos($date, "/") !== false) //la date est au format jj/mm/aaaa
        {
            $date=explode("/", $date);
            $date=$date[2]."-".$date[1]."-".$date[0];
        }
        else //date au format jj mois_en_lettres annee
        {
            $date=explode(" ", $date);
            if(isset($date[1]))
            {
                $date[1]=str_replace("é", "e", $date[1]);
                $date[1]=str_replace("û","u", $date[1]);
            }
            $m = $date[0];
            if(strlen($m)==1)
            {
                $m = '0'.$m;
            }

            if(isset($mois[$date[1]])){
                $date=$date[2]."-".$mois[$date[1]]."-".$m; //date[0]; EA 20-03-2018
            }
            else{
                $date="";
            }
        }
        $date=str_replace(" ", "", $date);
        $date=str_replace("\r", "", $date);
        $date=str_replace("\n", "", $date);
        $date=str_replace("\t", "", $date);

        return $date;
    }
    function TranslateAlbu($exam, $valeur)
    {
        if( ($exam=="albu") && (!is_numeric($valeur)))
        {
            if(substr($valeur,0,1)=="<")
                $valeur = 0.5;
        }
        return $valeur;
    }
    function isLitteral($exam)
    {
        $rc = false;

        if
        (
            ($exam=="ECG")||
            ($exam=="pieds")||
            ($exam=="monofil")||
            ($exam=="oeil")||
            ($exam=="dentiste")
        )
            $rc = true;

        return $rc;
    }

    function CheckSyntax($numero, $date, $exam, $valeur, $unite)
    {
        $ph=0;
        $err="OK";

        while (($ph <10) && ($err=="OK"))
        {
            switch($ph)
            {
                case 0:
                    if($numero == "")
                        $err = "Dossier Vide";
                    break;
                case 1:
                    if($date == "")
                        $err = "Date Vide";
                    break;

                case 2:
                    if($valeur == "")
                        $err = "Aucune valeur indiquée";
                    break;
                case 3:
                    // EA 10-02-2017
                    // EA 22-06-2017
                    if ((!$this->isLitteral($exam)) && (!is_numeric($valeur)))
                        $err = "Résultat non conforme";
                    break;
            }
            $ph++;
        }
        return $err;
    }
    function TranslateValues($numero, $date, $exam, $val, $unite)
    {
        $valeur = $val;
        if($exam == "albu")
        {
            if($valeur<20){
                $valeur=0;
            }
            else{
                $valeur=1;
            }
        }
        else
        {
            $unite = strtolower($unite);
            if(isset($this->unites[$exam]) && (in_array($unite,$this->unites[$exam]))) //On est sur un examen dans la mauvaise unité=> convertir
            {
                if($exam=="HBA1c")
                    $valeur=round(($valeur+24)/11, 1);
                else
                    $valeur=round($valeur/$this->equivalences[$exam], 2);
            }
        }
        return $valeur;
    }
    function CheckValuesLimits($numero, $date, $exam, $val, $unite)
    {
        $err="OK";

        if(isset($this->minValues[$exam]))
        {
            $minValue = $this->minValues[$exam];
            if($val<$minValue)
                $err = "Valeur Hors Limite";
        }
        if(isset($this->maxValues[$exam]))
        {
            $maxValue = $this->maxValues[$exam];
            if($val>$maxValue)
                $err = "Valeur Hors Limite";
        }

        return $err;
    }

    // La date au format mysql

    function Process($ligne, $numero, $date, $type, $val, $unite)
    {
        $phase=0;
        $erreur="OK";

        if($this->isMysqlDate==0)
            $date = $this->TransformDate($date);

        $val=str_replace(",", ".", $val);
        $val=str_replace(" ", "", $val);
        $numero=str_replace(" ", "", $numero);
        $numero=str_replace("!ESPACE!", " ", $numero); // Echappement pour blancs par exemple frontenay
        $cabinet = $this->cabinet;
        //error_log($numero." ". $date." ".$type." ". $val." ". $unite);

        $valeur = $val;
        $exam = $type;

        if($date<$this->beginDate)
            $erreur="NO";

        $k =canonize($type);
        // fprintf($this->fp, "%s:%s\n", $type, $k);
        $type = $k;

        if(isset($this->mots[$type])){
            $exam = $this->mots[$type];
        }
        else
        {
            $erreur="NO";
            if
            (
                (stripos($exam, "a1c") !== false) ||
                (stripos($exam, "glyc") !== false) ||
                (stripos($exam, "albu") !== false) ||
                (stripos($exam, "ldl") !== false) ||
                (stripos($exam, "hdl") !== false)
            )
            {
                $val2=round(($val+24)/11, 1);
                fprintf($this->fp, "%s ;%s;%s; %s \n", $exam, $val, $unite, $val2);
            }
        }
        if($type=="n-a")
            $erreur="NO";

        while ( ($phase <5) && ($erreur=="OK"))
        {
            switch ($phase)
            {
                case 0: // Analyse syntaxique
                    $val = $this->TranslateAlbu($exam, $val); // Cas <5
                    $erreur = $this->CheckSyntax($numero, $date, $exam, $val, $unite);
                    break;
                case 1: //Translation Valeurs
                    $val = $valeur = $this->TranslateValues($numero, $date, $exam, $val, $unite);
                    break;
                case 2: //Analyse limites
                    $erreur = $this->CheckValuesLimits($numero, $date, $exam, $val, $unite);
                    break;
                case 3:
                    //error_log($numero." ". $date." ".$type." ". $val." ". $unite);
                    if($numero!=$this->currentnb)//ne faire requéte que sur nouveau numero
                    {
                        $req="SELECT id from dossier WHERE cabinet='$cabinet' ".
                            "and numero='$numero'";
                        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

                        if(mysql_num_rows($res)==1)
                        {
                            list($id)=mysql_fetch_row($res);
                            $this->id = $id;
                        }
                        else
                        {
                            $id="";
                            $erreur= "Dossier Inconnu";

                        }
                    }
                    else
                        $id = $this->id;
                    break;
                case 4:
                    $this->id = $id;
                    $this->currentb = $numero;
                    $dateexam=explode("-", $date);
                    date_default_timezone_set('Europe/Berlin'); //EA 22-04-2014
                    $date_avant=date("Y-m-d", mktime(1, 1, 1, $dateexam[1] , $dateexam[2]-15, $dateexam[0]));
                    $date_apres=date("Y-m-d", mktime(1, 1, 1, $dateexam[1] , $dateexam[2]+15, $dateexam[0]));
                    if(($val>0)||($exam=="albu") || $this->isLitteral($exam) )
                    {
                        $req2="SELECT date_exam, resultat1 from liste_exam where id='$id' ".
                            "and date_exam>'$date_avant' and date_exam<'$date_apres' ".
                            "and type_exam='".$exam."'";
                        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

                        if(mysql_num_rows($res2)==0)//La donnee n'est pas presente dans asalee
                        {
                            $req2="INSERT INTO liste_exam SET id='$id', ".
                                "date_exam='$date', resultat1='$val', ".
                                "type_exam='".$exam."'";

                            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

                            //Sauvegarde dans le compte-rendu
                            try
                            {
                                $this->lok++;
                                $this->spreadsheet_ok->setCellValue("A$this->lok", $id);
                                $this->spreadsheet_ok->setCellValue("B$this->lok", "$numero");
                                $this->spreadsheet_ok->setCellValue("C$this->lok", $date);
                                $this->spreadsheet_ok->setCellValue("D$this->lok", $val);
                                $this->spreadsheet_ok->setCellValue("E$this->lok", $exam);
                            }
                            catch (Exception $e)
                            {
                                error_log("***** ERREUR LORS DE L'INTÉGRATION : " . $e->getMessage());
                            }
                        }
                        else
                        {
                            try
                            {
                                $this->lbis++;
                                list($date_exam, $resultat1)=mysql_fetch_row($res2);
                                $resultat1 = str_replace("=", "", $resultat1);
                                $this->spreadsheet_bis->setCellValue("A$this->lbis", "$id");
                                $this->spreadsheet_bis->setCellValue("B$this->lbis", "$numero");
                                $this->spreadsheet_bis->setCellValue("C$this->lbis", "$date");
                                $this->spreadsheet_bis->setCellValue("D$this->lbis", $val);
                                $this->spreadsheet_bis->setCellValue("E$this->lbis", $date_exam);
                                $this->spreadsheet_bis->setCellValue("E$this->lbis", $resultat1);
                                $this->spreadsheet_bis->setCellValue("E$this->lbis", $exam);
                            }
                            catch (Exception $e)
                            {
                                error_log("***** ERREUR LORS DE L'INTÉGRATION : " . $e->getMessage());
                            }
                        }
                    }
                    break;
                default:
                    break;
            }
            $phase++;
        }

        if(($erreur!="OK")&&($erreur!="NO"))
        {
            try
            {
                $this->lko++;
                $this->spreadsheet_ko->setCellValue("A$this->lko", "");
                $this->spreadsheet_ko->setCellValue("A$this->lko", $id);
                $this->spreadsheet_ko->setCellValue("B$this->lko", "$numero");
                $this->spreadsheet_ko->setCellValue("C$this->lko", "$date");
                $this->spreadsheet_ko->setCellValue("D$this->lko", $val);
                $this->spreadsheet_ko->setCellValue("E$this->lko", $exam);
                $this->spreadsheet_ko->setCellValue("E$this->lko", $erreur);
            }
            catch (Exception $e)
            {
                error_log("***** ERREUR LORS DE L'INTÉGRATION : " . $e->getMessage());
            }
        }
    }

    function End()
    {
        //CAnonize
        fclose($this->fp);

        $fileName = $this->logpath."/".
            $this->AnonymizeFilename("Compte-rendu-integration ".
                $this->cabinet." ".date("d-m-Y")).".xlsx";

        try
        {
            $writer = new Xlsx($this->spreadsheet);
            $writer->save($fileName);
        }
        catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e)
        {
            error_log("***** ERREUR LORS DE L'INTÉGRATION : " . $e->getMessage());
        }

        //EA 20-02-2014
        $archive2 = new PclZip($this->fich.".zip");

        if ($archive2->create($this->fich, PCLZIP_OPT_REMOVE_ALL_PATH) == 0) {

            return ($this->fich);
        }
        unlink($this->fich); //EA 26-03-2014

        return($this->fich.".zip");

       
        /* $zipname = 'archive.zip';
        $zip = new ZipArchive;
        $zip->open($zipname, ZipArchive::CREATE);

        $zip->addFile($fileName);

        $zip->close();

        return($zipname); */
    }
}
