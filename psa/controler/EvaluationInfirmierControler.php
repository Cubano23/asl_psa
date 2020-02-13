<?php

require_once("bean/EvaluationInfirmier.php");
require_once("persistence/EvaluationInfirmierMapper.php");
require_once("bean/ControlerParams.php");
require_once("GenericControler.php");
require_once("bean/EvalContinue.php");
require_once("persistence/EvalContinueMapper.php");
require_once("bean/Epices.php");
require_once("persistence/EpicesMapper.php");
require_once("bean/GroupesDossiers.php");
require_once('lib/UUID.php');

class EvaluationInfirmierControler extends GenericControler{

    var $mappingTable;

    function EvaluationInfirmierControler() {
        $this->mappingTable =
            array(
                "URL_MANAGE"=>"manageevaluationinfirmier",
                "URL_NEW"=>"view/evaluation/newevaluationinfirmier.php",
                "URL_AFTER_CREATE"=>"view/evaluation/viewevaluationinfirmieraftercreate.php",
                "URL_AFTER_UPDATE"=>"view/evaluation/viewevaluationinfirmieraftercreate.php",
                "URL_AFTER_FIND_VIEW"=>"view/evaluation/viewevaluationinfirmier.php",
                "URL_AFTER_FIND_EDIT"=>"view/evaluation/newevaluationinfirmier.php",
                "URL_AFTER_DELETE"=>new ControlerParams("EvaluationInfirmierControler",ACTION_MANAGE,true),
                "URL_AFTER_LIST"=>"listevaluationinfirmier",
                "URL_AFTER_FIND_LIST_DOSSIER"=>"listevaluationinfirmierbydossier",
                "URL_AFTER_FIND_LIST_DOSSIER_TOOLTIP"=>"view/evaluation/listevaluationinfirmierbydossiertooltip.php",
                "URL_AFTER_FIND_LIST_DOSSIER_TOOLTIP2"=>"view/evaluation/listevaluationinfirmierbydossiertooltip2.php",
                "URL_ON_CALLBACK_FAIL"=>"view/");
    }


    function start() {
        // $this->genericControler("EvaluationInfirmierControler","evaluationInfirmier","EvaluationInfirmier","EvaluationInfirmierMapper",$this->mappingTable);
        // function    genericControler($EvaluationInfirmierControler,$objectName,$objectClass,$mapperClass,$mappingTable,$callbackObject=""){

        // variables inherited from ActionControler
        global $account;
        global $objects;
        global $param;
        global $EvalContinue;
        global $Epices;

        global $outDateReference;
        if(array_key_exists("outDateReference",$objects))
            $outDateReference = $objects["outDateReference"];

        global $dossier;
        if(array_key_exists("dossier",$objects))
            $dossier = $objects["dossier"];

        global $evaluationInfirmier;
        if(array_key_exists("evaluationInfirmier",$objects))
            $evaluationInfirmier = $objects["evaluationInfirmier"];

        if(array_key_exists("EvalContinue",$objects))
            $EvalContinue = $objects["EvalContinue"];

        if(array_key_exists("Epices",$objects))
            $Epices = $objects["Epices"];


        // declare global variables that might be usefull for the view
        global $currentObjectName;
        global $currentObjectClass;
        global $signature;
        $signature = $this->getSignature();$signature;
        $currentObjectName = "evaluationInfirmier";
        $currentObjectClass = "evaluationInfirmier";

        // create ledger for this controler
        $ledgerFactory = new LedgerFactory();
        $ledger = $ledgerFactory->getLedger("Controler","EvaluationInfirmierControler");

        //Create connection factory
        $cfactory = new ConnectionFactory();

        //create mappers
        $dossierMapper = new DossierMapper($cfactory->getConnection());
        $evaluationInfirmierMapper = new EvaluationInfirmierMapper($cfactory->getConnection());
        $EvalContinueMapper = new EvalContinueMapper($cfactory->getConnection());
        $EpicesMapper = new EpicesMapper($cfactory->getConnection());


        switch($param->action){
            case ACTION_MANAGE:
                $dossier = new Dossier();
                $evaluationInfirmier = new evaluationInfirmier();
                $evaluationInfirmier->date= date("d/m/Y");

                forward($this->mappingTable["URL_MANAGE"]);
                break;

            case ACTION_FIND:
                if(!$param->isParam1Valid())
                    forward(URL_CONTROLER_PARAMS_ERROR,"Invalid param1");
                exitIfNull($dossier);
                exitIfNull($evaluationInfirmier);
                exitIfNullOrEmpty($evaluationInfirmier->date);

                $dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);

                $evaluationInfirmier->id = $dossier->id;
                $result = $evaluationInfirmierMapper->findObject($evaluationInfirmier->beforeSerialisation($account));

                if($result == false)
                {
                    if($evaluationInfirmierMapper->lastError == BAD_MATCH)
                        forward($this->mappingTable["URL_MANAGE"],"Cet enregistrement n'existe pas");
                    else
                        forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
                }
                $evaluationInfirmier = $result->afterDeserialisation($account);

                global $EvalContinue;
                $EvalContinue = new EvalContinue();
                $EvalContinue->id=$evaluationInfirmier->id;

                global $Epices;
                $Epices = new Epices();
                $Epices->id=$evaluationInfirmier->id;
                $Epices->date=$evaluationInfirmier->date;
                $result = $EpicesMapper->findObject($Epices->beforeSerialisation($account));
                $Epices = $result;
                #echo '<pre>';var_dump($Epices);echo '</pre>';


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

                if($param->param1 == PARAM_EDIT){
                    forward($this->mappingTable["URL_AFTER_FIND_EDIT"]);
                }
                else
                    forward($this->mappingTable["URL_AFTER_FIND_VIEW"]);
                break;

            case ACTION_NEW:
                exitIfNull($dossier);
                exitIfNull($evaluationInfirmier);
                exitIfNullOrEmpty($evaluationInfirmier->date);

                if(!isValidDate($evaluationInfirmier->date))
                    forward($this->mappingTable["URL_MANAGE"],"La date du dépistage est invalide");
                $dossier = checkDossierActif($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);

                $evaluationInfirmier->id = $dossier->id;

                $result = $evaluationInfirmierMapper->findObject($evaluationInfirmier->beforeSerialisation($account));

                if($result == false){
                    if($evaluationInfirmierMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"dossier non trouvé");
                }
                else
                    forward($this->mappingTable["URL_MANAGE"],"Cet enregistrement existe dejà, Appuyer sur Modifier");

                global $dernierExam;
                $dernierExam = new evaluationInfirmier();

                $cle=$evaluationInfirmierMapper->getForeignKey();
                $dernierExam->$cle = $evaluationInfirmier->$cle;

                $dernierExam = $evaluationInfirmierMapper->findDernierExam($dernierExam);

                if($dernierExam!==false){
                    $dernierExam = $dernierExam->afterDeserialisation($account);
                }

                global $EvalContinue;
                $EvalContinue = new EvalContinue();
                $EvalContinue->id=$evaluationInfirmier->id;

                global $Epices;
                $Epices = new Epices();
                $Epices->id=$evaluationInfirmier->id;

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
//$ledger->write(I,"Start","Action Save");
                exitIfNull($dossier);
                exitIfNull($evaluationInfirmier);
                exitIfNullOrEmpty($evaluationInfirmier->date);
                // $dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$mappingTable["URL_MANAGE"]);

                $evaluationInfirmier->id_utilisateur = $evaluationInfirmier->getUserIdByLogin($evaluationInfirmier->id_utilisateur);
                $evaluationInfirmier->id_cabinet = $evaluationInfirmier->getCabIdByCab($evaluationInfirmier->id_cabinet);
                $evaluationInfirmier->id = $dossier->id;
				//$ledger->write(I,"Start","1");

                $errors = $evaluationInfirmier->check();
                if(count($errors) !=0) forward($this->mappingTable["URL_NEW"],$errors);
                global $Epices;
                $Epices = new Epices();
                $Epices->id=$evaluationInfirmier->id;
                $Epices->date=$evaluationInfirmier->date;
				//$ledger->write(I,"Start","22");
                $ledger->write(I,"Start","22:".$Epices->date);

                //====> sur modif début du code plantant

                $result = $EpicesMapper->findObject($Epices->beforeSerialisation($account));
                $ledger->write(I,"Start","30");

                if($result==false){
                    $result=$EpicesMapper->createObject($Epices->beforeSerialisation($account));

                    if($result == false){
                        if($EpicesMapper->lastError!= NOTHING_UPDATED)
                            forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la création du questionnaire complémentaire");
                    }
                }
                else{
                    $result = $EpicesMapper->updateObject($Epices->beforeSerialisation($account));
                    if($result == false){
                        if($EpicesMapper->lastError!= NOTHING_UPDATED)
                            forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour du questionnaire complémentaire");
                    }
                }
//<=============== fin du code
                $result = $dossierMapper->updateObject($dossier->beforeSerialisation($account));
                $result = $evaluationInfirmierMapper->findObject($evaluationInfirmier->beforeSerialisation($account));

                if($result == false){

                    if(($evaluationInfirmierMapper->lastError != BAD_MATCH)&&($objectMapper->lastError!=PARAM)) forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");

                    if ($evaluationInfirmier->duree > 480)
                    	forward($this->mappingTable["URL_NEW"], "La durée de consultation ne peut excéder 480 minutes");

                    $result = $evaluationInfirmierMapper->createObject($evaluationInfirmier->beforeSerialisation($account));

                    if($result == false) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la création");

                    if($EvalContinue->date!=""){//Eval continue renseignée
                        $EvalContinue->id=$evaluationInfirmier->id;
                        $result = $EvalContinueMapper->findObject($EvalContinue->beforeSerialisation($account));

                        if($result==false){
                            $result=$EvalContinueMapper->createObject($EvalContinue->beforeSerialisation($account));

                            if($result == false){
                                if($EvalContinueMapper->lastError!= NOTHING_UPDATED)
                                    forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la création de la consultation");
                            }
                        }
                        else{
                            $result = $EvalContinueMapper->updateObject($EvalContinue->beforeSerialisation($account));
                            if($result == false){
                                if($EvalContinueMapper->lastError!= NOTHING_UPDATED)
                                    forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour de la consultation");
                            }
                        }

                    }
                    $ledger->write(I,"Start","3");

                    global $liste_eval_continue;
                    $liste_eval_continue = $EvalContinueMapper->getObjectsByDossier($account->cabinet, $dossier->numero);

                    foreach($liste_eval_continue as $i=>$eval){
                        $EvalContinuei="EvalContinue$i";
                        global $$EvalContinuei;
                        $$EvalContinuei = $objects["EvalContinue$i"];
                        // print_r($_POST);print_r($$EvalContinuei);die;

                        if($$EvalContinuei){
                            $$EvalContinuei->id=$evaluationInfirmier->id;
                            $result = $EvalContinueMapper->findObject($$EvalContinuei->beforeSerialisation($account));

                            if($result==false){
                                $result=$EvalContinueMapper->createObject($$EvalContinuei->beforeSerialisation($account));

                                if($result == false){
                                    if($EvalContinueMapper->lastError!= NOTHING_UPDATED)
                                        forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la création de la consultation");
                                }
                            }
                            else{
                                $result = $EvalContinueMapper->updateObject($$EvalContinuei->beforeSerialisation($account));
                                if($result == false){
                                    if($EvalContinueMapper->lastError!= NOTHING_UPDATED)
                                        forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour de la consultation");
                                }
                            }
                        }

                    }

                    global $liste_eval_continue;
                    $liste_eval_continue = $EvalContinueMapper->getObjectsByDossier($account->cabinet, $dossier->numero);
                    forward($this->mappingTable["URL_AFTER_CREATE"]);
                }
                else{

                    if ($evaluationInfirmier->duree > 480)
                        forward($this->mappingTable["URL_NEW"], "La durée de consultation ne peut excéder 480 minutes");

                    $result = $evaluationInfirmierMapper->updateObject($evaluationInfirmier->beforeSerialisation($account));

                    if($result == false) {
                        if($evaluationInfirmierMapper->lastError != NOTHING_UPDATED)
                            forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour");
                    }

                    if($EvalContinue->date!=""){//Eval continue renseignée
                        $EvalContinue->id=$evaluationInfirmier->id;
                        $result = $EvalContinueMapper->findObject($EvalContinue->beforeSerialisation($account));

                        if($result==false){
                            $result=$EvalContinueMapper->createObject($EvalContinue->beforeSerialisation($account));

                            if($result == false){
                                if($EvalContinueMapper->lastError!= NOTHING_UPDATED)
                                    forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la création de la consultation");
                            }
                        }
                        else{
                            $result = $EvalContinueMapper->updateObject($EvalContinue->beforeSerialisation($account));
                            if($result == false){
                                if($EvalContinueMapper->lastError!= NOTHING_UPDATED)
                                    forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour de la consultation");
                            }
                        }

                    }
                    global $liste_eval_continue;
                    $liste_eval_continue = $EvalContinueMapper->getObjectsByDossier($account->cabinet, $dossier->numero);

                    foreach($liste_eval_continue as $i=>$eval){
                        $EvalContinuei="EvalContinue$i";
                        global $$EvalContinuei;
                        $$EvalContinuei = $objects["EvalContinue$i"];
                        // print_r($$EvalContinuei);die;
                        if($$EvalContinuei){
                            $$EvalContinuei->id=$evaluationInfirmier->id;
                            $result = $EvalContinueMapper->findObject($$EvalContinuei->beforeSerialisation($account));

                            if($result==false){
                                $result=$EvalContinueMapper->createObject($$EvalContinuei->beforeSerialisation($account));

                                if($result == false){
                                    if($EvalContinueMapper->lastError!= NOTHING_UPDATED)
                                        forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la création de la consultation");
                                }
                            }
                            else{
                                $result = $EvalContinueMapper->updateObject($$EvalContinuei->beforeSerialisation($account));
                                if($result == false){
                                    if($EvalContinueMapper->lastError!= NOTHING_UPDATED)
                                        forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour de la consultation");
                                }
                            }
                        }

                    }

                    global $liste_eval_continue;
                    $liste_eval_continue = $EvalContinueMapper->getObjectsByDossier($account->cabinet, $dossier->numero);
                    forward($this->mappingTable["URL_AFTER_UPDATE"]);
                }

                break;


            case ACTION_DELETE:
                exitIfNull($dossier);
                exitIfNull($evaluationInfirmier);
                exitIfNullOrEmpty($evaluationInfirmier->date);
                $dossier = checkDossier($dossier,$dossierMapper,$account->cabinet);
                $evaluationInfirmier->id = $dossier->id;
                $result = $evaluationInfirmierMapper->deleteObject($evaluationInfirmier->beforeSerialisation($account));
                if($result == false){
                    if($evaluationInfirmierMapper->lastError != NOTHING_DELETED) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Delete object caused an error");
                }
                forward($this->mappingTable["URL_AFTER_DELETE"]);



            case ACTION_LIST:

                set_time_limit(1200);//ea
                switch($param->param1){

                    #var_dump($param->param1);exit;

                    case PARAM_LIST_BY_DOSSIER:
                        $result = $evaluationInfirmierMapper->getObjectsByDossier($account->cabinet, $dossier->numero);
                        if($result == false){
                            if($evaluationInfirmierMapper->lastError == BAD_MATCH) forward(new ControlerParams($param->controler,ACTION_MANAGE,true),"Pas d'enregistrements trouvés");
                            else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
                        }

                        global $rowsList;

                        $rowsList = array_natsort($result,"numero","numero","desc");

                        forward($this->mappingTable["URL_AFTER_FIND_LIST_DOSSIER"]);

                    case PARAM_LIST_BY_DOSSIER_TOOLTIP:


                        $result = $evaluationInfirmierMapper->getObjectsByDossier($account->cabinet, $dossier->numero);

                        if($result == false){
                            if($evaluationInfirmierMapper->lastError == BAD_MATCH) forward($this->mappingTable["URL_AFTER_FIND_LIST_DOSSIER_TOOLTIP"],"Pas d'enregistrements trouvés");
                            else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
                        }
                        global $rowsList;

                        $rowsList = array_reverse(array_natsort($result,"numero","numero","desc"));
                        // echo '<pre>';
                        // var_dump($rowsList);
                        // echo '</pre>';exit;

                        forward($this->mappingTable["URL_AFTER_FIND_LIST_DOSSIER_TOOLTIP"]);

                    case PARAM_LIST_BY_DOSSIER_TOOLTIP2:


                        $result = $evaluationInfirmierMapper->getObjectsByDossier($account->cabinet, $dossier->numero);

                        if($result == false){
                            if($evaluationInfirmierMapper->lastError == BAD_MATCH) forward($this->mappingTable["URL_AFTER_FIND_LIST_DOSSIER_TOOLTIP"],"Pas d'enregistrements trouvés");
                            else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
                        }
                        global $rowsList;

                        $rowsList = array_reverse(array_natsort($result,"numero","numero","desc"));
                        // echo '<pre>';
                        // var_dump($rowsList);
                        // echo '</pre>';exit;

                        forward($this->mappingTable["URL_AFTER_FIND_LIST_DOSSIER_TOOLTIP2"]);
                    case OLD:
                        //default:
                        $result = $evaluationInfirmierMapper->getObjectsByCabinet($account->cabinet);
                        if($result == false){
                            if($evaluationInfirmierMapper->lastError == BAD_MATCH) forward(new ControlerParams($param->controler,ACTION_MANAGE,true),"Pas d'enregistrements trouvés");
                            else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
                        }
                        global $rowsList;
                        $rowsList = array_natsort($result,"numero","numero","desc");

                        forward($this->mappingTable["URL_AFTER_LIST"]);

                    default:
                        $result = $evaluationInfirmierMapper->getEvaluationsByCabinetAndDistinctDossier($account->cabinet, 'date');
                        //echo "<pre>"; var_dump($result); exit();
                        if($result == false){
                            if($evaluationInfirmierMapper->lastError == BAD_MATCH) forward(new ControlerParams($param->controler,ACTION_MANAGE,true),"Pas d'enregistrements trouvés");
                            else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
                        }
                        global $rowsList;
                        $rowsList = $result;//array_natsort($result,"numero","numero","desc");

                        forward($this->mappingTable["URL_AFTER_LIST"]);
                }




            default:
                echo("ACTION IS NULL");
                break;
        }
    }

    /**
     * récuparation des données du formulaire de consultation collective et enregsitrement des datas pour chacun des dossiers
     * @return [type] [description]
     */
    function traiteFormEvaluationCollective(){

        $evaluationInfirmier = new EvaluationInfirmier();

        #var_dump($_POST);
        $evaluation = new stdClass();
        $evaluation->degre_satisfaction = $_POST['degre_satisfaction'];
        $evaluation->duree = $_POST['duree'];
        $evaluation->date = $_POST['date'];
        $evaluation->id_utilisateur = $evaluationInfirmier->getUserIdByLogin($_POST['id_utilisateur']);
        $evaluation->id_cabinet = $evaluationInfirmier->getCabIdByCab($_POST['id_cabinet']);
        $evaluation->consult_domicile= $_POST['consult_domicile'];
        $evaluation->consult_tel= $_POST['consult_tel'];
        $evaluation->consult_collective= $_POST['consult_collective'];

        foreach($_POST['type_consultation'] as $type){
            $typeC .=$type.',';
        }
        $evaluation->type_consultation = substr($typeC,0,-1);

        $evaluation->dep_diab = $_POST['dep_diab'];
        $evaluation->suivi_diab = $_POST['suivi_diab'];
        $evaluation->rcva = $_POST['rcva'];
        $evaluation->bpco = $_POST['bpco'];
        $evaluation->cognitif = $_POST['cognitif'];
        $evaluation->sevrage_tabac = $_POST['sevrage_tabac'];
        $evaluation->automesure = $_POST['automesure'];
        $evaluation->hemocult = $_POST['hemocult'];
        $evaluation->sein = $_POST['sein'];
        $evaluation->colon = $_POST['colon'];
        $evaluation->uterus = $_POST['uterus'];
        $evaluation->autres = $_POST['autres'];
#		$evaluation->test = $_POST['test'];
        $evaluation->prec_autres = addslashes($_POST['prec_autres']);

        $evaluation->points_positifs = addslashes($_POST['points_positifs']);
        $evaluation->points_ameliorations = addslashes($_POST['points_ameliorations']);
        $evaluation->aspects_limitant = addslashes($_POST['aspects_limitant']);
        $evaluation->aspects_facilitant = addslashes($_POST['aspects_facilitant']);
        $evaluation->objectifs_patient = addslashes($_POST['objectifs_patient']);

        $evaluation->idgroupe = $_POST['idgroupe'];
        $evaluation->dossiers = $_POST['dossiers'];

        $uuid = new UUID();
        $evaluation->uuid_collectif = $uuid->v4().'@'.$evaluation->idgroupe;
        #var_dump($evaluation);exit;

        $evaluation->error = self::controlEvaluation($evaluation);

        return $evaluation;

    }

    /**
     * découpe une évaluation collective en plusieurs dossiers afin de faire l'insertion individuelle
     * @param  object $evaluation évaluation telle que saisie dans le formulaire
     * @return [type]             [description]
     */
    function recordEvaluationCollective($evaluation){

        // dans le groupe il faut récupérer les dossiers pour récupérer les ID des dossiers
        $groupe = GroupesDossiers::getGroupeById($evaluation->idgroupe);
        $groupeDossiersTab = json_decode($groupe['dossiers'],true);
        #var_dump($groupeTab);
        $tab = array();
        foreach($groupeDossiersTab as $key=>$value){
            #var_dump($value);
            $allDossiersTab[key($value)] = $value[key($value)];
        }
        #var_dump($tab);

        $evaluation->dossiers = str_replace(" ","",$evaluation->dossiers);
        $dossiersTab = explode(',',$evaluation->dossiers);// on fait un tableau des différentes évaluations

        #var_dump($dossiersTab);

        $response = array();
        foreach($dossiersTab as $key){ // $key est ne N° de dossier pour l'infirmière mais on stocke en base l'id_dossier qui est dans la table dossier
            #var_dump($groupeTab[$key]);
            $dossier_id = $allDossiersTab[$key];
            #echo '<p>'.$key.' => '.$dossier_id.'</p>';
            // pour chaque dossier on fait l'insert en base et on renvoie la réponse
            $evaluation->dossier_id = $dossier_id;
            $response[$key] = EvaluationInfirmierMapper::add($evaluation);

        }

        return $response;

    }

    /**
     * vérification des données d'une évaluation notamment pour les consultations collectives
     * @param  [type] $evaluation [description]
     * @return [type]             [description]
     */
    static function controlEvaluation($evaluation){

        $retour = array();

        if(!UtilityControler::validDate($evaluation->date)){
            array_push($retour,"La date de consultation ($evaluation->date) est incorrecte ou nulle.");
        }

        if($evaluation->type_consultation==''){
            array_push($retour,"Il faut au moins un type de consultation.");
        }

        if($evaluation->idgroupe==''){
            array_push($retour,"Le groupe n'est pas d&eacute;fini.");
        }

        return $retour;
    }


    /**
     * [HistoConsultCollectiveByGroup description]
     * @param [type] $idgroupe [description]
     */
    static function HistoConsultCollectiveByGroup($idgroupe){

        $listeConsultations = EvaluationInfirmierMapper::listConsultCollectiveByGroup($idgroupe);

        #var_dump($listeConsultations); 
        return $listeConsultations;

    }

}
?> 
