<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("bean/Frais.php");
require_once("bean/ControlerParams.php");
require_once("persistence/FraisMapper.php");
require_once("GenericControler.php");
require_once("tools/formulas.php");
require_once("lib/ComposerLibs/vendor/autoload.php");

require_once ("Config.php");
$config = new Config();
require_once($config->rest_path . '/GetCabsAndLogins.php');

class FraisControler{

    var $mappingTable;
    var $config;

    function FraisControler() {
        $this->config = new Config();
        $this->mappingTable =
            array(
                "URL_NEW"=>"view/frais/newfrais.php",
                "URL_AFTER_CREATE"=>"view/frais/viewfraisaftercreate.php",
                "URL_ON_CALLBACK_FAIL"=>"view/");
    }

    function dForward($param,$url,$message=NULL,$resetRequest=false){
        forward($this->getForward($param,$url),$message,$resetRequest);
    }

    function getForward($param,$url){
        if($param == PARAM_STAND_ALONE)
            return $this->mappingTable[$url."_STA"];
        else
            return $this->mappingTable[$url];
    }

    function start(){

        // variables inherited from ActionControler
        global $account;
        global $objects;
        global $param;
        global $Frais;

        if(array_key_exists("Frais",$objects))
            $Frais = $objects["Frais"];

        $ledgerFactory = new LedgerFactory();
        $ledger = $ledgerFactory->getLedger("Controler","CongesControler");

        //Create connection factory
        $cf = new ConnectionFactory();

        //create mappers
        $FraisMapper = new FraisMapper($cf->getConnection());

        $ledger->writeArray(I,"Start","Control Parameters = ",$param);


        switch($param->action){


            case ACTION_NEW:

                $Frais=new Frais();
                forward($this->mappingTable["URL_NEW"]);
                break;


            case ACTION_SAVE:
                exitIfNull($Frais);

                $errors = $Frais->check();

                if(count($errors) != 0)
                    $this->dForward($param->param3,"URL_NEW",$errors);

                $upfile="";
                if (!empty($_FILES['pj']['name']))
                {

                    $piece=1;

                    $pj = $_FILES['pj']['tmp_name'];
                    $pj_name = $_FILES['pj']['name'];
                    $pj_size = $_FILES['pj']['size'];
                    $pj_type = $_FILES['pj']['type'];
                    $pj_error = $_FILES['pj']['error'];


                    $remplacement=array("é"=>"e",
                        "è"=>"e",
                        "ë"=>"e",
                        "ê"=>"e",
                        "à"=>"a",
                        "ä"=>"a",
                        "â"=>"a",
                        "á"=>"a",
                        "î"=>"i",
                        "ï"=>"i",
                        "í"=>"i",
                        "ì"=>"i",
                        "ú"=>"u",
                        "ù"=>"u",
                        "ü"=>"u",
                        "û"=>"u",
                        "ô"=>"o",
                        "ö"=>"o",
                        "ó"=>"o",
                        "ò"=>"o",
                        "ç"=>"c",
                        " "=>"_");

                    foreach($remplacement as $rech=>$rempl){
                        $pj_name=str_replace($rech, $rempl, $pj_name);
                    }

                    if ($pj_error>0)
                    {
                        $piece=0;

                        switch ($pj_error)
                        {
                            case 2: echo 'La pièce jointe dépasse la taille maximum admise'; break;
                            case 3: echo 'Pièce jointe partiellement téléchargé, recommencez plus tard';break;
                            case 4: echo "la pièce jointe n'a pas été tèléchargé, recommencez ultérieurement"; break;
                            default: echo "problème lors du téléchargement de la pièce jointe"; break;
                        }
                        exit;
                    }

                    //$upfile='/var/data/home/informed/www/_files/notes_de_frais/'.$pj_name;
                    $newDateString = date('Y-m-d_H-i-s');
                    $upfile = $this->config->files_path . '/notes_de_frais/Frais_'. $newDateString .'_login_'. $_SESSION["id.login"] .'_cab_'. $_SESSION["cabinet"] .'_'.$pj_name;

                    $constant=explode(".", $pj_name);

                    $ext=$constant[count($constant)-1];
                    $extLower = strtolower($ext);
                    if ($extLower != "pdf" && $extLower != "png" && $extLower != "jpg" && $extLower != "jpeg")
                        forward($this->mappingTable["URL_NEW"],"La pièce jointe n'est pas au bon format. Formats permis : pdf, png, jpg, jpeg");

                    /*$const="";
                    $point="";
                    for($i=0;$i<count($constant)-1;$i++){
                        $const=$const.$point.$constant[$i];
                        $point=".";
                    }
                    $i=1;
                    while(file_exists($upfile)){
                        //$upfile='/var/data/home/informed/www/_files/notes_de_frais/'.$const.".$i.".$ext;
                        $upfile = $this->config->files_path .'/notes_de_frais/'. $const .". $i .". $ext;
                        $i++;
                    }*/

                    if (is_uploaded_file($pj))
                    {
                        if (!move_uploaded_file($pj, $upfile))
                        {
                            $piece=0;
                            echo 'problème : impossible de télécharger la pièce jointe.';
                            exit;
                        }
                    }

                }

                $Frais->date_demande=date("YmdHis");
                #$infirmiere=$FraisMapper->getInfirmiere($account);
                $infirmiere = utf8_decode($_SESSION['id.prenom'].' '.$_SESSION['id.nom']);
                $Frais->infirmiere = $infirmiere;
                $Frais->inf_login = $_SESSION['id.login'];
                $Frais->pj=$upfile;

                #var_dump($Frais);
                #$infosInf = GetInfosByLogin($_SESSION['cabinet'], $status);

                #var_dump($_SESSION['id.email']);exit;

                $result = $FraisMapper->findObject($Frais->beforeSerialisation($account));

                if($result == false){
                    if($FraisMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");
                    $result = $FraisMapper->createObject($Frais->beforeSerialisation($account));
                    if($result == false) forward(URL_CONTROLER_PERSISTENCE_ERROR,"erreur lors de la création");


                    if ($Frais->autreNature != null || $Frais->autreNature != '')
                    {
                        $corps="<html><body>Demande de remboursement de frais réalisée le ".date("d/m/Y")." par $Frais->infirmiere : <br><br>".
                            "Date des frais : $Frais->date_frais<br>".
                            "Nature des frais : $Frais->nature ~> $Frais->autreNature<br>".
                            "Motif : $Frais->motif<br>".
                            "Le cas échéant, montant en euros : $Frais->montant ?<br>".
                            "Le cas échéant, autre unité de calcul : $Frais->autre_calcul<br>";
                    }
                    else
                    {
                        $corps="<html><body>Demande de remboursement de frais réalisée le ".date("d/m/Y")." par $Frais->infirmiere : <br><br>".
                            "Date des frais : $Frais->date_frais<br>".
                            "Nature des frais : $Frais->nature<br>".
                            "Motif : $Frais->motif<br>".
                            "Le cas échéant, montant en euros : $Frais->montant ?<br>".
                            "Le cas échéant, autre unité de calcul : $Frais->autre_calcul<br>";
                    }

                    $sujet="Demande de remboursement de frais";

                    /*
                     * NEW MAILER
                     */
                    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
                    try
                    {
                        //Recipients
                        $mail->setFrom('contact@asalee.fr', 'Portail PSA');
                        $mail->addAddress('gestion@asalee.fr');
                        $mail->addCC('jl.fievre@medsyn.fr');
                        $mail->addCC($_SESSION['id.email']);

                        if($piece==1)
                            $mail->addAttachment($upfile, $pj_name);

                        //Content
                        $mail->isHTML(true);                                  // Set email format to HTML
                        $mail->Subject = $sujet;
                        $mail->Body    = $corps;

                        $mail->send();
                    }
                    catch (Exception $e)
                    {
                        error_log('Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
                    }

                    forward($this->mappingTable["URL_AFTER_CREATE"]);
                }
                else
                {
                    $result = $FraisMapper->updateObject($Frais->beforeSerialisation($account));
                    if($result == false) {
                        if($FraisMapper->lastError != NOTHING_UPDATED){
                            forward(URL_CONTROLER_PERSISTENCE_ERROR,"update failed");
                        }
                    }

                    if ($Frais->autreNature != null || $Frais->autreNature != '')
                    {
                        $corps="<html><body>Demande de remboursement de frais réalisée le ".date("d/m/Y")." par $Frais->infirmiere : <br><br>".
                            "Date des frais : $Frais->date_frais<br>".
                            "Nature des frais : $Frais->nature ~> $Frais->autreNature<br>".
                            "Motif : $Frais->motif<br>".
                            "Le cas échéant, montant en euros : $Frais->montant ?<br>".
                            "Le cas échéant, autre unité de calcul : $Frais->autre_calcul<br>";
                    }
                    else
                    {
                        $corps="<html><body>Demande de remboursement de frais réalisée le ".date("d/m/Y")." par $Frais->infirmiere : <br><br>".
                            "Date des frais : $Frais->date_frais<br>".
                            "Nature des frais : $Frais->nature<br>".
                            "Motif : $Frais->motif<br>".
                            "Le cas échéant, montant en euros : $Frais->montant ?<br>".
                            "Le cas échéant, autre unité de calcul : $Frais->autre_calcul<br>";
                    }

                    $sujet="Demande de remboursement de frais";

                    /*
                     * NEW MAILER
                     */
                    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
                    try
                    {
                        //Recipients
                        $mail->setFrom('contact@asalee.fr', 'Portail PSA');
                        $mail->addAddress('gestion@asalee.fr');
                        $mail->addCC('jl.fievre@medsyn.fr');
                        $mail->addCC($_SESSION['id.email']);

                        if($piece==1)
                            $mail->addAttachment($upfile, $pj_name);

                        //Content
                        $mail->isHTML(true);                                  // Set email format to HTML
                        $mail->Subject = $sujet;
                        $mail->Body    = $corps;

                        $mail->send();
                    }
                    catch (Exception $e)
                    {
                        error_log('Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
                    }

                    forward($this->mappingTable["URL_AFTER_UPDATE"]);
                }

                break;

            default:
                echo("ACTION IS NULL");
                break;
        }
    }

}
?> 

