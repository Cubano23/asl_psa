<?php

require_once("bean/TroubleCognitif.php");
require_once("bean/OutdateReference.php");
require_once("bean/EvaluationInfirmier.php");
require_once("bean/EvalContinue.php");
require_once("persistence/TroubleCognitifMapper.php");
require_once("persistence/EvalContinueMapper.php");
require_once("persistence/EvaluationInfirmierMapper.php");
require_once("persistence/ConnectionFactory.php");

class TroubleCognitifControler {

    var $mappingTable;

    function TroubleCognitifControler() {
        $this->mappingTable = array(
//			"URL_MANAGE_INCOMPLETE"=>"view/troublecognitif/managesuividiabeteincomplet.php",
            "URL_MANAGE_OUTDATED"=>"view/troublecognitif/managealertetroublecognitif.php",
            "URL_MANAGE_PRE_CREATE"=>"view/troublecognitif/managetroublecognitifprecreate.php",
            "URL_MANAGE_CREATE"=>"view/troublecognitif/managetroublecognitifcreate.php",
            "URL_MANAGE_CONSULT"=>"view/troublecognitif/managetroublecognitif.php",
            "URL_NEW"=>"view/troublecognitif/newtroublecognitif.php",
//			"URL_AFTER_CREATE"=>new ControlerParams("SuiviDiabeteControler",ACTION_MANAGE,true,PARAM_PRE_CREATE),
//			"URL_AFTER_UPDATE"=>new ControlerParams("SuiviDiabeteControler",ACTION_MANAGE,true,PARAM_PRE_CREATE),
            "URL_AFTER_CREATE"=>"view/troublecognitif/viewtroublecognitifaftercreate.php",
            "URL_AFTER_UPDATE"=>"view/troublecognitif/viewtroublecognitifaftercreate.php",

            /*"URL_AFTER_FIND_VIEW"=>"view/diabete/suivi/viewsuividiabetesystematique.php",
              "URL_AFTER_FIND_VIEW_4MOIS"=>"view/diabete/suivi/viewsuividiabete4mois.php",
              "URL_AFTER_FIND_VIEW_SEMESTRIEL"=>"view/diabete/suivi/viewsuividiabetesemestriel.php",
              "URL_AFTER_FIND_VIEW_ANNUEL"=>"view/diabete/suivi/viewsuividiabeteannuel.php", */
            "URL_AFTER_FIND_EDIT"=>"view/troublecognitif/newtroublecognitif.php",

//			"URL_AFTER_LIST_BY_CABINET"=>"view/troublecognitif/listtroublecognitifbycabinet.php",
            "URL_AFTER_FIND_VIEW"=>"view/troublecognitif/viewtroublecognitif.php",
            "URL_AFTER_FIND_LIST_DOSSIER"=>"view/troublecognitif/listtroublecognitifbydossier.php",
            "URL_AFTER_LIST_OUTDATED"=>"view/troublecognitif/listtroublecognitifalerte.php",
            "URL_AFTER_LIST"=>"view/troublecognitif/listtroublecognitif.php",
            /*"URL_AFTER_LIST_INCOMPLETE_SYSTEMATIQUE"=>"view/diabete/suivi/listsuividiabeteincompletsystematique.php",
              "URL_AFTER_LIST_INCOMPLETE_4MOIS"=>"view/diabete/suivi/listsuividiabete4moisincomplet.php",
              "URL_AFTER_LIST_INCOMPLETE_SEMESTRIEL"=>"view/diabete/suivi/listsuividiabeteincompletsemestriel.php",
              "URL_AFTER_LIST_INCOMPLETE_ANNUEL"=>"view/diabete/suivi/listsuividiabeteincompletannuel.php",
              "URL_AFTER_DELETE"=>new ControlerParams("TroubleCognitifControler",ACTION_MANAGE,true,PARAM_ANY)); */
            "URL_AFTER_DELETE"=>"view/troublecognitif/managetroublecognitifprecreate.php");
    }

    function start() {
        // variables inherited from ActionControler
        global $account;
        global $objects;
        global $param;
        global $dossier;
        global $TroubleCognitif;
        global $outDateReference;
        global $EvaluationInfirmier;
        global $EvalContinue;
//			global $suiviDiabeteList;



        if(array_key_exists("dossier",$objects))
            $dossier = $objects["dossier"];
        if(array_key_exists("TroubleCognitif",$objects))
            $TroubleCognitif = $objects["TroubleCognitif"];
        if(array_key_exists("outDateReference",$objects))
            $outDateReference = $objects["outDateReference"];

        if(array_key_exists("EvaluationInfirmier",$objects))
            $EvaluationInfirmier = $objects["EvaluationInfirmier"];

        if(array_key_exists("EvalContinue",$objects))
            $EvalContinue = $objects["EvalContinue"];
        // create ledger for this controler
        $ledgerFactory = new LedgerFactory();
        $ledger = $ledgerFactory->getLedger("Controler","TroubleCognitifControler");

        //Create connection factory
        $cf = new ConnectionFactory();

        //create mappers
        $EvaluationInfirmierMapper = new EvaluationInfirmierMapper($cf->getConnection());
        $EvalContinueMapper = new EvalContinueMapper($cf->getConnection());
        $TroubleCognitifMapper = new TroubleCognitifMapper($cf->getConnection());
        $dossierMapper = new DossierMapper($cf->getConnection());

        $ledger->writeArray(I,"Start","Control Parameters = ",$param);
        switch($param->action){
            case ACTION_MANAGE:
                if(!$param->isParam1Valid()) forward(URL_CONTROLER_PARAMS_ERROR,"Invalid param1");
                if($param->param1 != PARAM_CREATE){
                    $dossier = new Dossier();
                }
                $TroubleCognitif = new TroubleCognitif();
                $TroubleCognitif->date= date("d/m/Y");
                switch($param->param1){
                    case PARAM_ANY:
                        forward($this->mappingTable["URL_MANAGE_CONSULT"]);
                    case PARAM_INCOMPLETE:
                        forward($this->mappingTable["URL_MANAGE_INCOMPLETE"]);
                    case PARAM_OUTDATED:
                        $outDateReference = new OutDateReference();
                        forward($this->mappingTable["URL_MANAGE_OUTDATED"]);
                    case PARAM_CREATE:
                        exitIfNull($dossier);
                        $dossier = checkDossierActif($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE_PRE_CREATE"]);
                        $EvaluationInfirmier = new EvaluationInfirmier();
                        $EvaluationInfirmier->date= date("d/m/Y");

                        forward($this->mappingTable["URL_MANAGE_CREATE"]);
                    case PARAM_PRE_CREATE:
                        forward($this->mappingTable["URL_MANAGE_PRE_CREATE"]);
                }

                break;

            case ACTION_FIND:
                if(!$param->isParam1Valid()) forward(URL_CONTROLER_PARAMS_ERROR,"Invalid param1");
                exitIfNull($dossier);
                exitIfNull($TroubleCognitif);
                exitIfNullOrEmpty($TroubleCognitif->date);

                $dossier = checkDossier($dossier,$dossierMapper,$account->cabinet);
                $TroubleCognitif->id = $dossier->id;
                $EvaluationInfirmier=new EvaluationInfirmier();
                $EvaluationInfirmier->id = $dossier->id;
                $EvaluationInfirmier->date = $TroubleCognitif->date;

                $result = $EvaluationInfirmierMapper->findObject($EvaluationInfirmier->beforeSerialisation($account));
                if($result == false){
                    if($EvaluationInfirmierMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"dossier non trouv?");
                }
                else{
                    $EvaluationInfirmier=$result;
                }

                if(!$result){
                    $dernierExam = new EvaluationInfirmier();

                    $cle=$EvaluationInfirmierMapper->getForeignKey();
                    $dernierExam->$cle = $EvaluationInfirmier->id;

                    $dernierExam = $EvaluationInfirmierMapper->findDernierExam($dernierExam);

                    $EvaluationInfirmier->aspects_limitant=$dernierExam->aspects_limitant;
                    $EvaluationInfirmier->aspects_facilitant=$dernierExam->aspects_facilitant;
                    $EvaluationInfirmier->objectifs_patient=$dernierExam->objectifs_patient;
                    $EvaluationInfirmier->type_consultation=array("cognitif");
                }
                // $AutreConsultCardio->type_consultation=array("rcva");
                global $EvalContinue;
                $EvalContinue = new EvalContinue();
                $EvalContinue->id=$EvaluationInfirmier->id;

                global $liste_eval_continue;

                $liste_eval_continue = $EvalContinueMapper->getObjectsByDossier($account->cabinet, $dossier->numero);

                foreach($liste_eval_continue as $i=>$eval){
                    $EvalContinuei="EvalContinue$i";
                    global $$EvalContinuei;

                    $$EvalContinuei = new EvalContinue($eval["id"], $eval["numero_eval"],
                        $eval["date"], $eval["suivi"],
                        $eval["causes"], $eval["terminologie"],
                        $eval["comprendre_traitement"],
                        $eval["appliquer_traitement"],
                        $eval["risques"], $eval["gravite"],
                        $eval["mesures"], $eval["appliquer"],
                        $eval["connaitre_equilibre"],
                        $eval["appliquer_equilibre"],
                        $eval["activite"], $eval["autre"]);
                    $$EvalContinuei=$$EvalContinuei->afterDeserialisation($account);
                }

                $result = $TroubleCognitifMapper->findObject($TroubleCognitif->beforeSerialisation($account));
                if($result == false){
                    if($TroubleCognitifMapper->lastError == BAD_MATCH) forward($this->mappingTable["URL_MANAGE_CONSULT"],"Pas d'enregistrements trouv?s");
                    else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
                }
                $TroubleCognitif = $result->afterDeserialisation($account);

                if($param->param1 == PARAM_EDIT) {

                    $result = $TroubleCognitifMapper->findObjects($TroubleCognitif);
                    if($result == false){
                        if($TroubleCognitifMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
                    }
                    else {
                        $TroubleCognitifList = $result;
                        for($i=0;$i<count($TroubleCognitifList);$i++){
                            $TroubleCognitifList[$i] = $TroubleCognitifList[$i]->afterDeserialisation($account);
                        }
                    }
                    forward($this->mappingTable["URL_AFTER_FIND_EDIT"]);
                }
                else
                {
                    forward($this->mappingTable["URL_AFTER_FIND_VIEW"]);

                }
                break;

            case ACTION_NEW:
                exitIfNull($dossier);
                exitIfNull($TroubleCognitif);
                exitIfNullOrEmpty($TroubleCognitif->date);
                exitIfNull($EvaluationInfirmier);

                $dossier = checkDossier($dossier,$dossierMapper,$account->cabinet);

                $TroubleCognitif->id = $dossier->id;
                $EvaluationInfirmier->id = $dossier->id;
                $EvaluationInfirmier->date = $TroubleCognitif->date;

                $result = $TroubleCognitifMapper->findObjects($TroubleCognitif);
                if($result == false){

                    if($TroubleCognitifMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
                }
                else {
                    $TroubleCognitifList = $result;
                    for($i=0;$i<count($TroubleCognitifList);$i++){
                        $TroubleCognitifList[$i] = $TroubleCognitifList[$i]->afterDeserialisation($account);
                    }
                }

                $result = $TroubleCognitifMapper->findObject($TroubleCognitif->beforeSerialisation($account));
                if($result == false){
                    if($TroubleCognitifMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");
                }
                else
                    forward($this->mappingTable["URL_MANAGE_CREATE"],"Cet enregistrement existe dej?");

                $result = $EvaluationInfirmierMapper->findObject($EvaluationInfirmier->beforeSerialisation($account));
                if($result == false){
                    if($EvaluationInfirmierMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"dossier non trouv?");
                }
                else{
                    $EvaluationInfirmier=$result;
                }

                if(!$result){
                    $dernierExam = new EvaluationInfirmier();

                    $cle=$EvaluationInfirmierMapper->getForeignKey();
                    $dernierExam->$cle = $EvaluationInfirmier->id;

                    $dernierExam = $EvaluationInfirmierMapper->findDernierExam($dernierExam);

                    $EvaluationInfirmier->aspects_limitant=$dernierExam->aspects_limitant;
                    $EvaluationInfirmier->aspects_facilitant=$dernierExam->aspects_facilitant;
                    $EvaluationInfirmier->objectifs_patient=$dernierExam->objectifs_patient;
                    $EvaluationInfirmier->type_consultation=array("cognitif");
                }
                // $AutreConsultCardio->type_consultation=array("rcva");
                global $EvalContinue;
                $EvalContinue = new EvalContinue();
                $EvalContinue->id=$EvaluationInfirmier->id;

                global $liste_eval_continue;

                $liste_eval_continue = $EvalContinueMapper->getObjectsByDossier($account->cabinet, $dossier->numero);

                foreach($liste_eval_continue as $i=>$eval){
                    $EvalContinuei="EvalContinue$i";
                    global $$EvalContinuei;

                    $$EvalContinuei = new EvalContinue($eval["id"], $eval["numero_eval"],
                        $eval["date"], $eval["suivi"],
                        $eval["causes"], $eval["terminologie"],
                        $eval["comprendre_traitement"],
                        $eval["appliquer_traitement"],
                        $eval["risques"], $eval["gravite"],
                        $eval["mesures"], $eval["appliquer"],
                        $eval["connaitre_equilibre"],
                        $eval["appliquer_equilibre"],
                        $eval["activite"], $eval["autre"]);
                    $$EvalContinuei=$$EvalContinuei->afterDeserialisation($account);
                }

                forward($this->mappingTable["URL_NEW"]);
                break;

            case ACTION_SAVE:
                exitIfNull($dossier);
                exitIfNull($TroubleCognitif);
                exitIfNullOrEmpty($TroubleCognitif->date);
                exitIfNull($EvaluationInfirmier);
                exitIfNullOrEmpty($EvaluationInfirmier->date);
                // $dossier = checkDossier($dossier,$dossierMapper,$account->cabinet);


                $TroubleCognitif->id = $dossier->id;
                $EvaluationInfirmier->id=$dossier->id;
                $EvaluationInfirmier->date=$TroubleCognitif->date;
                $EvaluationInfirmier->id_utilisateur = $EvaluationInfirmier->getUserIdByLogin($_SESSION['id.login']);
                $EvaluationInfirmier->id_cabinet = $EvaluationInfirmier->getCabIdByCab($_SESSION['cabinet']);

                $errors = $TroubleCognitif->check($dossier);
                if(count($errors) !=0) forward($this->mappingTable["URL_NEW"],$errors);

                $errors = $EvaluationInfirmier->check($dossier);
                if(count($errors) !=0) forward($this->mappingTable["URL_NEW"],$errors);

                $result = $EvaluationInfirmierMapper->findObject($EvaluationInfirmier->beforeSerialisation($account));

                if($result==false){
                    $result=$EvaluationInfirmierMapper->createObject($EvaluationInfirmier->beforeSerialisation($account));

                    if($result == false){
                        if($EvaluationInfirmierMapper->lastError!= NOTHING_UPDATED)
                            forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la cr?ation de la consultation");
                    }
                }
                else{
                    $result = $EvaluationInfirmierMapper->updateObject($EvaluationInfirmier->beforeSerialisation($account));
                    if($result == false){
                        if($EvaluationInfirmierMapper->lastError!= NOTHING_UPDATED)
                            forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise ? jour de la consultation");
                    }
                }

                if($EvalContinue->date!=""){//Eval continue renseign?e
                    $EvalContinue->id=$EvaluationInfirmier->id;
                    $result = $EvalContinueMapper->findObject($EvalContinue->beforeSerialisation($account));

                    if($result==false){
                        $result=$EvalContinueMapper->createObject($EvalContinue->beforeSerialisation($account));

                        if($result == false){
                            if($EvalContinueMapper->lastError!= NOTHING_UPDATED)
                                forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la cr?ation de la consultation");
                        }
                    }
                    else{
                        $result = $EvalContinueMapper->updateObject($EvalContinue->beforeSerialisation($account));
                        if($result == false){
                            if($EvalContinueMapper->lastError!= NOTHING_UPDATED)
                                forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise ? jour de la consultation");
                        }
                    }

                }

                global $liste_eval_continue;
                $liste_eval_continue = $EvalContinueMapper->getObjectsByDossier($account->cabinet, $dossier->numero);
                foreach($liste_eval_continue as $i=>$eval){
                    $EvalContinuei="EvalContinue$i";
                    global $$EvalContinuei;

                    if(isset($objects["EvalContinue$i"])){
                        $$EvalContinuei = $objects["EvalContinue$i"];
                        // print_r($$EvalContinuei);die;
                        $$EvalContinuei->id=$EvaluationInfirmier->id;
                        $result = $EvalContinueMapper->findObject($$EvalContinuei->beforeSerialisation($account));

                        if($result==false){
                            $result=$EvalContinueMapper->createObject($$EvalContinuei->beforeSerialisation($account));

                            if($result == false){
                                if($EvalContinueMapper->lastError!= NOTHING_UPDATED)
                                    forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la cr?ation de la consultation");
                            }
                        }
                        else{
                            $result = $EvalContinueMapper->updateObject($$EvalContinuei->beforeSerialisation($account));
                            if($result == false){
                                if($EvalContinueMapper->lastError!= NOTHING_UPDATED)
                                    forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise ? jour de la consultation");
                            }
                        }
                    }

                }

                global $liste_eval_continue;

                $liste_eval_continue = $EvalContinueMapper->getObjectsByDossier($account->cabinet, $dossier->numero);

                $result = $dossierMapper->updateObject($dossier->beforeSerialisation($account));
                $result = $TroubleCognitifMapper->findObject($TroubleCognitif->beforeSerialisation($account));

                if($result == false){
                    if($TroubleCognitifMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");
                    $result = $TroubleCognitifMapper->createObject($TroubleCognitif->beforeSerialisation($account));
                    if($result == false) forward(URL_CONTROLER_PERSISTENCE_ERROR,"erreur lors de la cr?ation");
                    forward($this->mappingTable["URL_AFTER_CREATE"]);
                }
                else{
                    $result = $TroubleCognitifMapper->updateObject($TroubleCognitif->beforeSerialisation($account));
                    if($result == false) {
                        if($TroubleCognitifMapper->lastError != NOTHING_UPDATED)
                            forward(URL_CONTROLER_PERSISTENCE_ERROR,"update failed");
                    }
                    forward($this->mappingTable["URL_AFTER_UPDATE"]);
                }
                break;

            case ACTION_DELETE:
                exitIfNull($dossier);
                exitIfNull($TroubleCognitif);
                exitIfNullOrEmpty($TroubleCognitif->date);

                $dossier = checkDossier($dossier,$dossierMapper,$account->cabinet);
                $TroubleCognitif->id = $dossier->id;
                $result = $TroubleCognitifMapper->deleteObject($TroubleCognitif->beforeSerialisation($account));
                if($result == false){
                    if($TroubleCognitifMapper->lastError != NOTHING_DELETED) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Delete object caused an error");
                }
                forward($this->mappingTable["URL_AFTER_DELETE"]);

            case ACTION_LIST:
                set_time_limit(1200); //EA
                global $rowsList;
                switch($param->param1){
                    case PARAM_LIST_BY_DOSSIER:
                        $result = $TroubleCognitifMapper->getObjectsByDossier($account->cabinet, $dossier->numero);
                        if($result == false){
                            if($TroubleCognitifMapper->lastError == BAD_MATCH) forward(new ControlerParams($param->controler,ACTION_MANAGE,true),"Pas d'enregistrements trouv?s");
                            else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
                        }

                        $rowsList = array_natsort($result,"numero","numero");
                        forward($this->mappingTable["URL_AFTER_FIND_LIST_DOSSIER"]);

                    case PARAM_OUTDATED:
                        exitIfNull($outDateReference);
                        $result = $TroubleCognitifMapper->getExpiredExams($account->cabinet,$outDateReference->period);
                        if($result == false){
                            if($TroubleCognitifMapper->lastError == BAD_MATCH) forward($this->mappingTable["URL_MANAGE_OUTDATED"],"Pas d'enregistrements trouv?s");
                            else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
                        }
                        else
                            if(count($result)==0) forward($this->mappingTable["URL_MANAGE_OUTDATED"],"Pas d'enregistrements trouv?s");

                        $rowsList = array_natsort($result,"numero","numero");


                        for($i=0;$i<count($rowsList);$i++){
                            $result = $TroubleCognitifMapper->getdernierRappel($rowsList[$i]['id'], $rowsList[$i]['date']);

                            if($result == false){
                                if($TroubleCognitifMapper->lastError == BAD_MATCH) $result=0;
                                else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
                            }


                            if($result!=0)
                            {
                                $date_rappel = array_natsort($result,"id","id");

                                $date_rappel=$date_rappel[0];

                                if($rappel!=''){
                                    $rowsList[$i]['date_rappel']=$date_rappel['date_rappel'];
                                }
                                $rowsList[$i]['sortir_rappel']=$date_rappel['sortir_rappel'];

                            }

                        }



                        forward($this->mappingTable["URL_AFTER_LIST_OUTDATED"]);
                        break;

                    default:
                        $result = $TroubleCognitifMapper->getObjectsByCabinet($account->cabinet);
                        if($result == false){
                            if($TroubleCognitifMapper->lastError == BAD_MATCH) forward(new ControlerParams($param->controler,ACTION_MANAGE,true, PARAM_PRE_CREATE),"Pas d'enregistrements trouv?s");
                            else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
                        }

                        $rowsList = array_natsort($result,"numero","numero");


                        forward($this->mappingTable["URL_AFTER_LIST"]);
                }

                break;

            default:
                echo("ACTION IS NULL");
                break;
        }
    }
}

?>
