<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("bean/PoserQuestion.php");
require_once("bean/ControlerParams.php");
require_once("persistence/PoserQuestionMapper.php");
require_once("persistence/ConnectionFactory.php");
require_once("persistence/AccountMapper.php");
require_once("lib/ComposerLibs/vendor/autoload.php");

class PoserQuestionControler{

    var $mappingTable;

    function PoserQuestionControler() {
        $this->mappingTable =
            array(
//			"URL_MANAGE"=>"view/cancercolon/managedepistagecolon.php",
            "URL_NEW"=>"view/poserquestion/newposerquestion.php",
//			"URL_AFTER_CREATE"=>new ControlerParams("DepistageCancerColonControler",ACTION_MANAGE,true),
//			"URL_AFTER_UPDATE"=>new ControlerParams("DepistageCancerColonControler",ACTION_MANAGE,true),
            "URL_AFTER_CREATE"=>"view/poserquestion/viewposerquestionaftercreate.php",
            "URL_AFTER_UPDATE"=>"view/poserquestion/viewposerquestionaftercreate.php",
//			"URL_AFTER_FIND_VIEW"=>"view/cancercolon/viewdepistagecolon.php",
//			"URL_AFTER_FIND_EDIT"=>"view/cancercolon/newdepistagecolon.php",
//			"URL_AFTER_DELETE"=>new ControlerParams("DepistageCancerColonControler",ACTION_MANAGE,true),
//			"URL_AFTER_LIST"=>"view/cancercolon/listdepistagecolon.php",
//			"URL_AFTER_FIND_LIST_DOSSIER"=>"view/cancercolon/listdepistagecolonbydossier.php",
            "URL_VIEW"=>"view/poserquestion/viewposerquestion.php",
            "URL_ON_CALLBACK_FAIL"=>"view/");

    }


    function start() {
        // variables inherited from ActionControler
        global $account;
        global $objects;
        global $param;
        global $PoserQuestion;

        if(array_key_exists("PoserQuestion",$objects))
            $PoserQuestion = $objects["PoserQuestion"];


        // create ledger for this controler
        $ledgerFactory = new LedgerFactory();
        $ledger = $ledgerFactory->getLedger("Controler","PoserQuestionControler");

        //Create connection factory
        $cf = new ConnectionFactory();

        //create mappers
        $PoserQuestionMapper = new PoserQuestionMapper($cf->getConnection());

        $ledger->writeArray(I,"Start","Control Parameters = ",$param);
        switch($param->action){
            case ACTION_MANAGE:

                break;

            case ACTION_FIND:
                $PoserQuestion=New PoserQuestion();
//					$PoserQuestion->cabinet = $account->cabinet;
//					$result = $PoserQuestionMapper->findObject($PoserQuestion->beforeSerialisation($account));

//					$PoserQuestion = $result->afterDeserialisation($account);

                forward($this->mappingTable["URL_NEW"]);

                break;



            case ACTION_SAVE:
                error_log("----- BLOC ACTION SAVE -----");
                exitIfNull($PoserQuestion);

                $errors = $PoserQuestion->check();
                if(count($errors) !=0) forward($this->mappingTable["URL_NEW"],$errors);


                //Enregistrement en base des questions posées
                $accountMapper = new AccountMapper($cf->getConnection());
                $inf= $accountMapper->getnomcab($account);

                $PoserQuestion->infirmiere=$inf["infirmiere"];
                $PoserQuestion->infirmiere = utf8_decode($_SESSION['id.prenom'].' '.$_SESSION['id.nom']);
                #$PoserQuestion->mailInfirmiere = utf8_decode($_SESSION['id.email']);
                $PoserQuestion->cabinet=$inf["nom_cab"];
                $PoserQuestion->date=date("d/m/Y");

                $result = $PoserQuestionMapper->createObject($PoserQuestion->beforeSerialisation($account));

                if($result == false) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la création");

                $loginInfirmiere = $_SESSION['id.login'];
                $email = $PoserQuestionMapper->get_email($loginInfirmiere);

                if($email != false)
                {
                    error_log("----- CC NON VIDE -----");
                    $corps="<html><body>Vous avez posé la question suivante, nous vous répondrons dans les plus brefs délais<br><br>".
                        nl2br(stripslashes($PoserQuestion->corps))."</body></html>";

                    /*
                 	 * NEW MAILER
                 	 */
                    $mail = new PHPMailer(true);
                    try
                    {
                        error_log("----- BLOC TRY DU MAILER -----");
                        //Recipients
                        $mail->SMTPDebug = 2;
                        $mail->setFrom('contact@asalee.fr', 'Portail PSA');
                        $mail->addCC($email, $PoserQuestion->infirmiere);
                        $mail->addAddress('supportPSA@asalee.fr', "Support Asalée");

                        //Content
                        $mail->isHTML(true);
                        $mail->Subject = "Portail PSA - contactez le support : ".stripslashes($PoserQuestion->titre);
                        $mail->Body    = $corps;

                        $mail->send();
                        error_log("----- MAIL SENT -----");
                    }
                    catch (Exception $e)
                    {
                        error_log("----- ERROR -----\n" . $e->getMessage());
                        error_log('Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
                    }

                }

                forward($this->mappingTable["URL_AFTER_UPDATE"]);

                break;


            case ACTION_LIST:
                global $rowsList;
                $rowsList=array();
                $rowsList = $PoserQuestionMapper->GetQuestion();

                if((count($rowsList)==0)||($rowsList=="")||($rowsList==false)){
                    forward($this->mappingTable["URL_VIEW"],"Pas d'enregistrements trouvés");
                }

                forward($this->mappingTable["URL_VIEW"]);

                break;

            default:
                echo("ACTION IS NULL");
                break;
        }


    }
}
?> 
