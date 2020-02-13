<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("bean/Conges.php");
require_once("bean/ControlerParams.php");
require_once("persistence/CongesMapper.php");
require_once("GenericControler.php");
require_once("tools/formulas.php");
require_once("lib/ComposerLibs/vendor/autoload.php");

class CongesControler{

    var $mappingTable;

    function CongesControler() {
        $this->mappingTable =
            array(
                "URL_NEW"=>"view/conges/newconges.php",
                "URL_AFTER_CREATE"=>"view/conges/viewcongesaftercreate.php",
                "URL_ON_CALLBACK_FAIL"=>"view/");
    }


    function start(){

        // variables inherited from ActionControler
        global $account;
        global $objects;
        global $param;
        global $Conges;

        if(array_key_exists("Conges",$objects))
            $Conges = $objects["Conges"];

        $ledgerFactory = new LedgerFactory();
        $ledger = $ledgerFactory->getLedger("Controler","CongesControler");

        //Create connection factory
        $cf = new ConnectionFactory();

        //create mappers
        $CongesMapper = new CongesMapper($cf->getConnection());

        $ledger->writeArray(I,"Start","Control Parameters = ",$param);


        switch($param->action){


            case ACTION_NEW:

                $Conges=$CongesMapper->getInfirmiere($account);

                forward($this->mappingTable["URL_NEW"]);
                break;


            case ACTION_SAVE:
                exitIfNull($Conges);

                $Conges->date_demande=date("YmdHis");

                #$email_inf= $CongesMapper->getEmail($account);
                $email_inf = $_SESSION['id.email'];
                $Conges->inf_login = $_SESSION['id.login'];

                $result = $CongesMapper->findObject($Conges->beforeSerialisation($account));


                if($result == false){
                    if($CongesMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");
                    if(strtotime(str_replace('/', '-', $Conges->date_debut)) > strtotime(str_replace('/', '-', $Conges->date_fin))) forward($this->mappingTable["URL_NEW"],"La date de reprise doit être supérieure à celle de départ !");
                    $result = $CongesMapper->createObject($Conges->beforeSerialisation($account));
                    if($result == false) forward(URL_CONTROLER_PERSISTENCE_ERROR,"erreur lors de la création");


                    $entete="From: \"Portail PSA\" <contact@asalee.fr>\n".
                        "Reply-To: \"Portail PSA\" <contact@asalee.fr>\n".
//								"Return-Path : aderville@isas.fr\n".
                        "Cc: \" Amaury Derville \" <aderville@asalee.fr>, $email_inf\n".
                        // "Cc: contact@asalee.fr\n".
                        //"Bcc: xguillon@asalee.fr\n".
                        "MIME-Version: 1.0\n".
                        "Content-Type: text/html";
                    $destinataire="\" JL FI&Eagrave;VERE \" <jl.fievre@medsyn.fr>";

                    $nature=array(""=>"",
                        "paye"=>"Congés payés",
                        "sanssolde"=>"Congés sans solde",
                        "autres"=>"autres");

                    $corps="<html><body>Demande de congés réalisée le ".date("d/m/Y")." : <br><br>".
                        "Nom de l'infirmière : $Conges->nom<br>".
                        "Prénom de l'infirmière : $Conges->prenom<br>".
                        "Date du 1er jour d'absence : $Conges->date_debut<br>".
                        "Date du dernier jour d'absence : $Conges->date_fin<br>".
                        "Nature du congé : ".$nature[$Conges->nature];

                    if($Conges->nature=="autres"){
                        $corps.=" : $Conges->prec";
                    }
                    /*
                     * NEW MAILER
                     */
                    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
                    try
                    {
//                        error_log("----- JE SUIS DANS LE BLOC TRY POUR TENTER UN ENVOIE DE MAIL-----");
                        //Recipients
                        $mail->setFrom('contact@asalee.fr', 'Portail PSA');
                        $mail->addAddress('jl.fievre@medsyn.fr', 'JL FIÈVERE');
                        $mail->addCC('aderville@asalee.fr', 'Amaury Derville');
                        $mail->addCC($email_inf);

                        //Content
                        $mail->isHTML(true);                                  // Set email format to HTML
                        $mail->Subject = 'Demande de congés';
                        $mail->Body    = $corps;

                        $mail->send();
                    }
                    catch (Exception $e)
                    {
                        error_log('Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
                    }

                    forward($this->mappingTable["URL_AFTER_CREATE"]);
                }
                else{
                    $result = $CongesMapper->updateObject($Conges->beforeSerialisation($account));
                    if($result == false) {
                        if($CongesMapper->lastError != NOTHING_UPDATED){
                            forward(URL_CONTROLER_PERSISTENCE_ERROR,"update failed");
                        }
                    }

                    $entete="From: \"Portail PSA\" <contact@asalee.fr>\n".
                        "Reply-To: \"Portail PSA\" <contact@asalee.fr>\n".
//								"Return-Path : aderville@isas.fr\n".
                        // "Cc: contact@asalee.fr.fr\n".
                        "Cc: \" Amaury Derville \" <aderville@asalee.fr>, $email_inf\n".
                        //"Bcc: xguillon@asalee.fr\n".
                        "MIME-Version: 1.0\n".
                        "Content-Type: text/html";
                    $destinataire="\" JL FI&Eagrave;VERE \" <jl.fievre@medsyn.fr>";

                    $nature=array(""=>"",
                        "paye"=>"Congés payés",
                        "sanssolde"=>"Congés sans solde",
                        "autres"=>"autres");

                    $corps="<html><body>Demande de congés réalisée le ".date("d/m/Y")." : <br><br>".
                        "Nom de l'infirmière : $Conges->nom<br>".
                        "Prénom de l'infirmière : $Conges->prenom<br>".
                        "Date du 1er jour d'absence : $Conges->date_debut<br>".
                        "Date du dernier jour d'absence : $Conges->date_fin<br>".
                        "Nature du congé : ".$nature[$Conges->nature];

                    if($Conges->nature=="autres"){
                        $corps.=" : $Conges->prec";
                    }

                    /*
                     * NEW MAILER
                     */
                    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
                    try
                    {
//                        error_log("----- JE SUIS DANS LE BLOC TRY POUR TENTER UN ENVOIE DE MAIL-----");
                        //Recipients
                        $mail->setFrom('contact@asalee.fr', 'Portail PSA');
                        $mail->addAddress('jl.fievre@medsyn.fr', 'JL FIÈVERE');
                        $mail->addCC('aderville@asalee.fr', 'Amaury Derville');
                        $mail->addCC($email_inf);

                        //Content
                        $mail->isHTML(true);                                  // Set email format to HTML
                        $mail->Subject = 'Demande de congés';
                        $mail->Body    = $corps;

                        $mail->send();
                    }
                    catch (Exception $e)
                    {
//                        error_log('Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
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
