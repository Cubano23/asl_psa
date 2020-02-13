<?php
require_once("global/config.php");
require_once("bean/Biologie.php");
require_once("bean/CardioVasculaireDepart.php");
require_once("bean/EvaluationInfirmier.php");
require_once("bean/EvalContinue.php");
require_once("bean/SuiviDiabete.php");
require_once("bean/ControlerParams.php");
require_once("persistence/BiologieMapper.php");
require_once("persistence/CardioVasculaireDepartMapper.php");
require_once("persistence/EvaluationInfirmierMapper.php");
require_once("persistence/SuiviDiabeteMapper.php");
require_once("persistence/EvalContinueMapper.php");
require_once("GenericControler.php");
require_once("bean/AutreConsultCardio.php");
require_once("persistence/AutreConsultCardioMapper.php");
require_once("bean/Epices.php");
require_once("persistence/EpicesMapper.php");
require_once("bean/OutdateReference.php");

class ConsultDiabeteControler{

    var $mappingTable;

    function ConsultDiabeteControler() {
        $this->mappingTable =
            array(
                "URL_MANAGE"=>"view/diabete/consult/manageconsultdiabete.php",
                "URL_NEW"=>"view/diabete/consult/newconsultdiabete.php",
                "URL_AFTER_CREATE"=>"view/diabete/consult/viewconsultdiabeteaftercreate.php",
                "URL_AFTER_UPDATE"=>"view/diabete/consult/viewconsultdiabeteaftercreate.php",
                "URL_AFTER_FIND_VIEW"=>"view/diabete/consult/viewconsultdiabete.php",
                "URL_AFTER_FIND_EDIT"=>"view/diabete/consult/newconsultdiabete.php",
                "URL_AFTER_DELETE"=>new ControlerParams("ConsultDiabeteControler",ACTION_MANAGE,true),
                "URL_AFTER_LIST"=>"view/diabete/consult/listconsultdiabete.php",
                "URL_AFTER_FIND_LIST_DOSSIER"=>"view/diabete/consult/listconsultdiabetebydossier.php",
                "URL_ON_CALLBACK_FAIL"=>"view/");
    }


    function start(){

        // variables inherited from ActionControler
        global $account;
        global $objects;
        global $param;
        global $dossier;
        global $CardioVasculaireDepart;
        global $outDateReference;
        global $suiviDiabete;
        global $AutreConsultCardio;
        global $EvaluationInfirmier;
        global $EvalContinue;
        global $Epices;
        global $suiviDiabeteList;


        // $liste_exam=array("creat", "albu", "fond", "ECG",
        // 				  "dent", "pied", "monofil", "poids", "systole",
        // 				  "diastole", "type_tension", "HDL", "LDL", "HBA1c");

        foreach($_ENV['liste_exam_diabete']  as $exam){
            global $$exam;
        }

        if(array_key_exists("outDateReference",$objects))
            $outDateReference = $objects["outDateReference"];

        global $dossier;
        if(array_key_exists("dossier",$objects))
            $dossier = $objects["dossier"];

        if(array_key_exists("CardioVasculaireDepart",$objects))
            $CardioVasculaireDepart = $objects["CardioVasculaireDepart"];

        if(array_key_exists("AutreConsultCardio",$objects))
            $AutreConsultCardio = $objects["AutreConsultCardio"];

        if(array_key_exists("EvaluationInfirmier",$objects))
            $EvaluationInfirmier = $objects["EvaluationInfirmier"];

        if(array_key_exists("EvalContinue",$objects))
            $EvalContinue = $objects["EvalContinue"];

        if(array_key_exists("suiviDiabete",$objects))
            $suiviDiabete = $objects["suiviDiabete"];

        foreach($_ENV['liste_exam_diabete'] as $exam){
            if(array_key_exists($exam,$objects))
                $$exam = $objects[$exam];
        }

        if(array_key_exists("outDateReference",$objects))
            $outDateReference = $objects["outDateReference"];

        if(array_key_exists("Epices",$objects))
            $Epices = $objects["Epices"];


        $ledgerFactory = new LedgerFactory();
        $ledger = $ledgerFactory->getLedger("Controler","ConsultDiabeteControler");

        //Create connection factory
        $cf = new ConnectionFactory();

        //create mappers
        $CardioVasculaireDepartMapper = new CardioVasculaireDepartMapper($cf->getConnection());
        $EvaluationInfirmierMapper = new EvaluationInfirmierMapper($cf->getConnection());
        $EvalContinueMapper = new EvalContinueMapper($cf->getConnection());
        $AutreConsultCardioMapper = new AutreConsultCardioMapper($cf->getConnection());
        $suiviDiabeteMapper = new SuiviDiabeteMapper($cf->getConnection());
        $dossierMapper = new DossierMapper($cf->getConnection());
        $EpicesMapper = new EpicesMapper($cf->getConnection());
        $BiologieMapper = new BiologieMapper($cf->getConnection());

        $ledger->writeArray(I,"Start","Control Parameters = ",$param);


        switch($param->action){
            case ACTION_MANAGE:
                $dossier = new Dossier();
                $EvaluationInfirmier = new EvaluationInfirmier();
                $EvaluationInfirmier->date= date("d/m/Y");

                forward($this->mappingTable["URL_MANAGE"]);
                break;


            case ACTION_NEW:
                exitIfNull($dossier);
                exitIfNull($EvaluationInfirmier);
                exitIfNullOrEmpty($EvaluationInfirmier->date);

                if(!isValidDate($EvaluationInfirmier->date)){
                    forward($this->mappingTable["URL_MANAGE"],"La date de consultation est invalide");
                }

                $dossier = checkDossierActif($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);

                $EvaluationInfirmier->id = $dossier->id;

                global $suiviDiabete;
                $suiviDiabete=new suiviDiabete();
                $suiviDiabete->suivi_type=array("a", "4");
                $suiviDiabete->dossier_id = $dossier->id;

                // $liste_exam=array("creat", "albu", "fond", "ECG",
                // 				  "dent", "pied", "monofil", "poids", "systole",
                // 				  "diastole", "type_tension", "HDL", "LDL");

                foreach($_ENV['liste_exam_diabete']  as $exam){
                    $$exam=new Biologie();
                    $$exam->id=$dossier->id;
                    $$exam->type_exam=$exam;
                }

                $result = $suiviDiabeteMapper->findObjects($suiviDiabete);
                if($result == false){

                    if($suiviDiabeteMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
                }
                else {
                    $suiviDiabeteList = $result;
                    for($i=0;$i<count($suiviDiabeteList);$i++){
                        $suiviDiabeteList[$i] = $suiviDiabeteList[$i]->afterDeserialisation($account);
                    }
                }

                $result = $suiviDiabeteMapper->findObject($suiviDiabete->beforeSerialisation($account));
                if($result == false){
                    if($suiviDiabeteMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");
                }
                else
                    forward($this->mappingTable["URL_MANAGE_CREATE"],"Cet enregistrement existe dejà");

                global $dernier_suivi;

                $dernier_suivi = new SuiviDiabete();

                $result = $suiviDiabeteMapper->getdernierExams($dossier->id);
                if($result == false){
                    if($suiviDiabeteMapper->lastError == BAD_MATCH) $result=0;
                    else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
                }

                if($result!=0){
                    $resultdiab = $result[0];


                    if(!isset($resultdiab)){
                        $resultdiab['dHBA']=$resultdiab['dExaFil']=$resultdiab['dExaPieds']='0000-00-00';
                        $resultdiab['dChol']=$resultdiab['dLDL']=$resultdiab['dCreat']='0000-00-00';
                        $resultdiab['dAlbu']=$resultdiab['dFond']=$resultdiab['dECG']='0000-00-00';
                        $resultdiab['dPoids']=$resultdiab['dtension']=$resultdiab['dentiste']='0000-00-00';
                        $resultdiab['dTriglycerides']=$resultdiab['dKaliemie']='0000-00-00';
                    }

                    $dernier_suivi->dHBA=$resultdiab["dHBA"];
                    $dernier_suivi->dExaFil=$resultdiab["dExaFil"];
                    $dernier_suivi->dExaPieds=$resultdiab["dExaPieds"];
                    $dernier_suivi->dChol=$resultdiab["dChol"];
                    $dernier_suivi->dLDL=$resultdiab["dLDL"];
                    $dernier_suivi->dCreat=$resultdiab["dCreat"];
                    $dernier_suivi->dAlbu=$resultdiab["dAlbu"];
                    $dernier_suivi->dFond=$resultdiab["dFond"];
                    $dernier_suivi->dECG=$resultdiab["dECG"];
                    $dernier_suivi->dTriglycerides=$resultdiab["dTriglycerides"];
                    $dernier_suivi->dKaliemie=$resultdiab["dKaliemie"];

                    $dernier_suivi->dsuivi=$resultdiab["dsuivi"];
                    $dernier_suivi->dPoids=$resultdiab["dPoids"];
                    $dernier_suivi->dtension=$resultdiab["dtension"];
                    $dernier_suivi->dentiste=$resultdiab["dentiste"];

                    $result = $suiviDiabeteMapper->getsystematique($dossier->id, $dernier_suivi->dsuivi);
                    $result= $result[0];

                    $suiviDiabete->hta = $result["hta"];
                    $suiviDiabete->arte = $result["arte"];
                    $suiviDiabete->neph = $result["neph"];
                    $suiviDiabete->coro = $result["coro"];
                    $suiviDiabete->reti = $result["reti"];
                    $suiviDiabete->neur = $result["neur"];
                    $suiviDiabete->equilib = $result["equilib"];
                    $suiviDiabete->lipide = $result["lipide"];
                    $suiviDiabete->type = $result["type"];


                    // $liste_exam=array("poids"=>"dPoids",
                    // 				  "systole"=>"dtension",
                    // 				  "HDL"=>"dChol",
                    // 				  "LDL"=>"dLDL",
                    // 				  "monofil"=>"dExaFil",
                    // 				  "pied"=>"dateExaPieds",
                    // 				  "creat"=>"dCreat",
                    // 				  "albu"=>"dAlbu",
                    // 				  "fond"=>"dFond",
                    // 				  "HBA1c"=>"dHBA",
                    // 				  "ecg"=>"dECG");

                    foreach($_ENV['liste_examRD_diabete']  as $exam=>$champ){
                        $result=$BiologieMapper->findExam(date("Y-m-d"), $dossier->id, $exam);
                        $dernier_suivi->$champ=$result["dexam"];
                    }

                    $result = $suiviDiabeteMapper->getHBA($dossier->id, $dernier_suivi->dHBA);
                    $result = $result[0];
                    $dernier_suivi->ResHBA = $result["ResHBA"];


                    $dernier_suivi = $dernier_suivi->afterDeserialisation($account);
                }

                $suiviDiabete->date_debut=$suiviDiabeteMapper->getDebut($dossier);
                $suiviDiabete->diab10ans=$suiviDiabeteMapper->get10ans($dossier);

                $result = $EvaluationInfirmierMapper->findObject($EvaluationInfirmier->beforeSerialisation($account));
                if($result == false){
                    if($EvaluationInfirmierMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"dossier non trouvé");
                }
                else{
                    forward($this->mappingTable["URL_MANAGE"],"Une consultation a déjà été enregistrée pour le jour indiqué pour ce patient. Vous ne pouvez créer deux consultations le même jour.<br>
Vous pouvez par contre modifier le contenu de la consultation enregistrée ce jour là. Pour cela, cliquez sur le bouton modifier.");
                }

                $result = $AutreConsultCardioMapper->Liste_consult($dossier->id);

                global $ListConsult;
                $ListConsult=array();

                if($result){
                    foreach($result as $tab){
                        $ListConsult[$tab["date"]]=$tab["date_affiche"];
                    }
                }

                $dernierExam = new EvaluationInfirmier();

                $cle=$EvaluationInfirmierMapper->getForeignKey();
                $dernierExam->$cle = $EvaluationInfirmier->id;

                $dernierExam = $EvaluationInfirmierMapper->findDernierExam($dernierExam);

                $EvaluationInfirmier->aspects_limitant=$dernierExam->aspects_limitant;
                $EvaluationInfirmier->aspects_facilitant=$dernierExam->aspects_facilitant;
                $EvaluationInfirmier->objectifs_patient=$dernierExam->objectifs_patient;
                $EvaluationInfirmier->type_consultation=array("suivi_diab");
                // $AutreConsultCardio->type_consultation=array("rcva");
                global $EvalContinue;
                $EvalContinue = new EvalContinue();
                $EvalContinue->id=$EvaluationInfirmier->id;

                global $Epices;
                $Epices = new Epices();
                $Epices->id=$EvaluationInfirmier->id;

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
                exitIfNull($EvaluationInfirmier);
                exitIfNullOrEmpty($EvaluationInfirmier->date);
                $dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);

                $EvaluationInfirmier->id = $dossier->id;
                $EvaluationInfirmier->id_utilisateur = $EvaluationInfirmier->getUserIdByLogin($_SESSION['id.login']);
                $EvaluationInfirmier->id_cabinet = $EvaluationInfirmier->getCabIdByCab($_SESSION['cabinet']);

                $errors = $EvaluationInfirmier->check();
                if(count($errors) !=0) forward($this->mappingTable["URL_NEW"],$errors);

                $Epices->id=$EvaluationInfirmier->id;
                $Epices->date=$EvaluationInfirmier->date;
                $result = $EpicesMapper->findObject($Epices->beforeSerialisation($account));

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

                $result = $EvaluationInfirmierMapper->findObject($EvaluationInfirmier->beforeSerialisation($account));


                if($result==false){
                    $result=$EvaluationInfirmierMapper->createObject($EvaluationInfirmier->beforeSerialisation($account));

                    if($result == false){
                        if($EvaluationInfirmierMapper->lastError!= NOTHING_UPDATED)
                            forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la création de la consultation");
                    }
                }
                else{

                    $result = $EvaluationInfirmierMapper->updateObject($EvaluationInfirmier->beforeSerialisation($account));
                    if($result == false){
                        if($EvaluationInfirmierMapper->lastError!= NOTHING_UPDATED)
                            forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour de la consultation");
                    }
                }

                if($EvalContinue->date!=""){//Eval continue renseignée
                    $EvalContinue->id=$EvaluationInfirmier->id;
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

                    if(isset($objects["EvalContinue$i"])){
                        $$EvalContinuei = $objects["EvalContinue$i"];
                        // print_r($$EvalContinuei);die;
                        $$EvalContinuei->id=$EvaluationInfirmier->id;
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

                // $suiviDiabete->dtension=$systole->date_exam;
                // $suiviDiabete->TaSys=$systole->resultat1;
                // $suiviDiabete->TaDia=$diastole->resultat1;
                // $suiviDiabete->TA_mode=$type_tension->resultat1;

                // $liste_exam=array("creat"=>array("val"=>"Creat", "date"=>"dCreat", "val2"=>"iCreat"),
                // 				  "albu"=>array("val"=>"iAlbu", "date"=>"dAlbu"),
                // 				  "fond"=>array("val"=>"", "date"=>"dFond"),
                // 				  "ECG"=>array("val"=>"", "date"=>"dECG"),
                // 				  "dent"=>array("val"=>"", "date"=>"dentiste"),
                // 				  "pied"=>array("val"=>"", "date"=>"dExaPieds"),
                // 				  "monofil"=>array("val"=>"", "date"=>"dExaFil"),
                // 				  "poids"=>array("val"=>"poids", "date"=>"dPoids"),
                // 				  "HDL"=>array("val"=>"HDL", "date"=>"dChol"),
                // 				  "LDL"=>array("val"=>"LDL", "date"=>"dLDL"),
                // 				  "HBA1c"=>array("val"=>"ResHBA", "date"=>"dHBA")
                // 				  );


                // foreach($_ENV['liste_exam_saisie_diabete'] as $exam=>$vals){
                // 	if($vals["date"]!=""){
                // 		$suiviDiabete->$vals["date"]=$$exam->date_exam;
                // 	}
                // 	$suiviDiabete->$vals["val"]=$$exam->resultat1;
                // 	if(isset($vals["val2"])){
                // 		$suiviDiabete->$vals["val2"]=$$exam->resultat2;
                // 	}
                // 	$$exam->id=$dossier->id;
                // }

                // if(($suiviDiabete->dPoids!='')||($suiviDiabete->dHBA!='')||($suiviDiabete->dExaFil!='')||
                //    ($suiviDiabete->dExaPieds!='')||($suiviDiabete->dChol!='')||($suiviDiabete->dLDL!='')||
                //    ($suiviDiabete->dCreat!='')||($suiviDiabete->dAlbu!='')||($suiviDiabete->dFond!='')||
                //    ($suiviDiabete->dECG!='')||($suiviDiabete->dentiste!='')||($suiviDiabete->dtension!=''))
                // {
                //    // Des données sont enregistrées dans le suivi diabète=>on enregistre
                //    // echo "ok";
                // 	$suiviDiabete->suivi_type=array("a", "4");
                // 	$suiviDiabete->dossier_id=$EvaluationInfirmier->id;
                // 	$suiviDiabete->dsuivi=$EvaluationInfirmier->date;
                // 	$result = $suiviDiabeteMapper->findObject($suiviDiabete->beforeSerialisation($account));

                // 	if($suiviDiabete->date_debut!=''){
                // 		$suiviDiabete->date_debut="01/".$suiviDiabete->date_debut;
                // 	}

                // 	if($result == false){
                // 		if($suiviDiabeteMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");
                // 		$result = $suiviDiabeteMapper->createObject($suiviDiabete->beforeSerialisation($account));
                // 		if($result == false) forward(URL_CONTROLER_PERSISTENCE_ERROR,"erreur lors de la création du suivi diabète");

                // 		if($systole->date_exam!=""){
                // 			$diastole->date_exam=$systole->date_exam;
                // 			$type_tension->date_exam=$systole->date_exam;

                // 			$result = $BiologieMapper->findExamSaisi($systole->beforeSerialisation($account));

                // 			$maj=1;
                // 			if($result!==false){//Un examen a été trouvé.
                // 				if($result["resultat1"]!=$systole->resultat1){//Le poids est différent=> il faut faire une maj
                // 					$systole->numero=$result["numero"];
                // 				}
                // 				else{//L'exam enregistré est identique=> pas de maj
                // 					$maj=0;
                // 				}
                // 			}

                // 			if($maj==1){
                // 				$result = $BiologieMapper->findObject($systole->beforeSerialisation($account));


                // 				if($result==false){//Aucun systole créé avec le même identifiant
                // 					$result = $BiologieMapper->createObject($systole->beforeSerialisation($account));
                // 				}
                // 				else{//Déjà un systole créé avec le même identifiant=>maj
                // 					$result = $BiologieMapper->updateObject($systole->beforeSerialisation($account));
                // 				}
                // 			}

                // 			$result = $BiologieMapper->findExamSaisi($diastole->beforeSerialisation($account));

                // 			$maj=1;
                // 			if($result!==false){//Un examen a été trouvé.
                // 				if($result["resultat1"]!=$diastole->resultat1){//Le poids est différent=> il faut faire une maj
                // 					$diastole->numero=$result["numero"];
                // 				}
                // 				else{//L'exam enregistré est identique=> pas de maj
                // 					$maj=0;
                // 				}
                // 			}

                // 			if($maj==1){
                // 				$result = $BiologieMapper->findObject($diastole->beforeSerialisation($account));

                // 				if($result==false){//Aucun diastole créé avec le même identifiant
                // 					$result = $BiologieMapper->createObject($diastole->beforeSerialisation($account));
                // 				}
                // 				else{//Déjà un diastole créé avec le même identifiant=>maj
                // 					$result = $BiologieMapper->updateObject($diastole->beforeSerialisation($account));
                // 				}
                // 			}

                // 			$result = $BiologieMapper->findExamSaisi($type_tension->beforeSerialisation($account));

                // 			$maj=1;
                // 			if($result!==false){//Un examen a été trouvé.
                // 				if($result["resultat1"]!=$type_tension->resultat1){//Le poids est différent=> il faut faire une maj
                // 					$type_tension->numero=$result["numero"];
                // 				}
                // 				else{//L'exam enregistré est identique=> pas de maj
                // 					$maj=0;
                // 				}
                // 			}

                // 			if($maj==1){
                // 				$result = $BiologieMapper->findObject($type_tension->beforeSerialisation($account));

                // 				if($result==false){//Aucun HDL créé avec le même identifiant
                // 					$result = $BiologieMapper->createObject($type_tension->beforeSerialisation($account));
                // 				}
                // 				else{//Déjà un HDL créé avec le même identifiant=>maj
                // 					$result = $BiologieMapper->updateObject($type_tension->beforeSerialisation($account));
                // 				}
                // 			}
                // 		}



                // 		foreach($_ENV['liste_exam_saisie_diabete'] as $exam=>$vals){
                // 			if($$exam->date_exam!=""){
                // 				$result = $BiologieMapper->findExamSaisi($$exam->beforeSerialisation($account));

                // 				$maj=1;
                // 				if($result!==false){//Un examen a été trouvé.
                // 					if($result["resultat1"]!=$$exam->resultat1){//Le poids est différent=> il faut faire une maj
                // 						$$exam->numero=$result["numero"];
                // 					}
                // 					else{//L'exam enregistré est identique=> pas de maj
                // 						$maj=0;
                // 					}
                // 				}

                // 				if($maj==1){
                // 					$result = $BiologieMapper->findObject($$exam->beforeSerialisation($account));

                // 					if($result==false){//Aucun poids créé avec le même identifiant
                // 						$result = $BiologieMapper->createObject($$exam->beforeSerialisation($account));
                // 					}
                // 					else{//Déjà un poids créé avec le même identifiant=>maj
                // 						$result = $BiologieMapper->updateObject($$exam->beforeSerialisation($account));
                // 					}
                // 				}
                // 			}
                // 		}

                // 		$suiviDiabete->date_debut=str_replace("01/", "", $suiviDiabete->date_debut);

                // 	}//if($result == false){
                // 	else{
                // 		$result = $suiviDiabeteMapper->updateObject($suiviDiabete->beforeSerialisation($account));

                // 		if($result == false) {
                // 			if($suiviDiabeteMapper->lastError != NOTHING_UPDATED)
                // 				forward(URL_CONTROLER_PERSISTENCE_ERROR,"update failed");
                // 		}
                // 		$suiviDiabete->date_debut=str_replace("01/", "", $suiviDiabete->date_debut);

                // 		if($systole->date_exam!=""){
                // 			$diastole->date_exam=$systole->date_exam;
                // 			$type_tension->date_exam=$systole->date_exam;

                // 			$result = $BiologieMapper->findExamSaisi($systole->beforeSerialisation($account));

                // 			$maj=1;
                // 			if($result!==false){//Un examen a été trouvé.
                // 				if($result["resultat1"]!=$systole->resultat1){//Le poids est différent=> il faut faire une maj
                // 					$systole->numero=$result["numero"];
                // 				}
                // 				else{//L'exam enregistré est identique=> pas de maj
                // 					$maj=0;
                // 				}
                // 			}

                // 			if($maj==1){
                // 				$result = $BiologieMapper->findObject($systole->beforeSerialisation($account));

                // 				if($result==false){//Aucun systole créé avec le même identifiant
                // 					$result = $BiologieMapper->createObject($systole->beforeSerialisation($account));
                // 				}
                // 				else{//Déjà un systole créé avec le même identifiant=>maj
                // 					$result = $BiologieMapper->updateObject($systole->beforeSerialisation($account));
                // 				}
                // 			}

                // 			$result = $BiologieMapper->findExamSaisi($diastole->beforeSerialisation($account));

                // 			$maj=1;
                // 			if($result!==false){//Un examen a été trouvé.
                // 				if($result["resultat1"]!=$diastole->resultat1){//Le poids est différent=> il faut faire une maj
                // 					$diastole->numero=$result["numero"];
                // 				}
                // 				else{//L'exam enregistré est identique=> pas de maj
                // 					$maj=0;
                // 				}
                // 			}

                // 			if($maj==1){
                // 				$result = $BiologieMapper->findObject($diastole->beforeSerialisation($account));

                // 				if($result==false){//Aucun diastole créé avec le même identifiant
                // 					$result = $BiologieMapper->createObject($diastole->beforeSerialisation($account));
                // 				}
                // 				else{//Déjà un diastole créé avec le même identifiant=>maj
                // 					$result = $BiologieMapper->updateObject($diastole->beforeSerialisation($account));
                // 				}
                // 			}

                // 			$result = $BiologieMapper->findExamSaisi($type_tension->beforeSerialisation($account));

                // 			$maj=1;
                // 			if($result!==false){//Un examen a été trouvé.
                // 				if($result["resultat1"]!=$type_tension->resultat1){//Le poids est différent=> il faut faire une maj
                // 					$type_tension->numero=$result["numero"];
                // 				}
                // 				else{//L'exam enregistré est identique=> pas de maj
                // 					$maj=0;
                // 				}
                // 			}

                // 			if($maj==1){
                // 				$result = $BiologieMapper->findObject($type_tension->beforeSerialisation($account));

                // 				if($result==false){//Aucun HDL créé avec le même identifiant
                // 					$result = $BiologieMapper->createObject($type_tension->beforeSerialisation($account));
                // 				}
                // 				else{//Déjà un HDL créé avec le même identifiant=>maj
                // 					$result = $BiologieMapper->updateObject($type_tension->beforeSerialisation($account));
                // 				}
                // 			}
                // 		}



                // 		foreach($_ENV['liste_exam_saisie_diabete'] as $exam=>$vals){
                // 			if($$exam->date_exam!=""){
                // 				$result = $BiologieMapper->findExamSaisi($$exam->beforeSerialisation($account));

                // 				$maj=1;
                // 				if($result!==false){//Un examen a été trouvé.
                // 					if($result["resultat1"]!=$$exam->resultat1){//Le poids est différent=> il faut faire une maj
                // 						$$exam->numero=$result["numero"];
                // 					}
                // 					else{//L'exam enregistré est identique=> pas de maj
                // 						$maj=0;
                // 					}
                // 				}

                // 				if($maj==1){
                // 					$result = $BiologieMapper->findObject($$exam->beforeSerialisation($account));

                // 					if($result==false){//Aucun poids créé avec le même identifiant
                // 						$result = $BiologieMapper->createObject($$exam->beforeSerialisation($account));
                // 					}
                // 					else{//Déjà un poids créé avec le même identifiant=>maj
                // 						$result = $BiologieMapper->updateObject($$exam->beforeSerialisation($account));
                // 					}
                // 				}
                // 			}
                // 		}

                // 	}
                // }
                forward($this->mappingTable["URL_AFTER_CREATE"]);


                break;

            case ACTION_DELETE:

                exitIfNull($dossier);
                exitIfNull($EvaluationInfirmier);
                exitIfNullOrEmpty($EvaluationInfirmier->date);

                $dossier = checkDossier($dossier,$dossierMapper,$account->cabinet);

                $EvaluationInfirmier->id = $dossier->id;

                $result = $EvaluationInfirmierMapper->deleteObject($EvaluationInfirmier->beforeSerialisation($account));
                if($result == false){
                    if($EvaluationInfirmierMapper->lastError != NOTHING_DELETED) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Delete object caused an error");
                }


                forward($this->mappingTable["URL_AFTER_DELETE"]);


            case ACTION_LIST:
                set_time_limit(1200); //EA
                switch($param->param1){
                    case PARAM_LIST_BY_DOSSIER:
                        $result = $AutreConsultCardioMapper->getObjectsByDossier($account->cabinet, $dossier->numero);
                        if($result == false){
                            if($AutreConsultCardioMapper->lastError == BAD_MATCH) forward(new ControlerParams($param->controler,ACTION_MANAGE,true),"Pas d'enregistrements trouvés");
                            else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
                        }
                        global $rowsList;
                        $rowsList = array_natsort($result,"numero","numero");

                        forward($this->mappingTable["URL_AFTER_FIND_LIST_DOSSIER"]);

                    default:
                        $result = $AutreConsultCardioMapper->getObjectsByCabinet($account->cabinet);
                        if($result == false){
                            if($AutreConsultCardioMapper->lastError == BAD_MATCH) forward(new ControlerParams($param->controler,ACTION_MANAGE,true),"Pas d'enregistrements trouvés");
                            else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
                        }
                        global $rowsList;
                        $rowsList = array_natsort($result,"numero","numero");

                        global $depart;
                        $depart=false;

                        forward($this->mappingTable["URL_AFTER_LIST"]);
                }


            case ACTION_FIND://En ACTION_FIND : visualisation de données

                if(!$param->isParam1Valid())
                    forward(URL_CONTROLER_PARAMS_ERROR,"Invalid param1");

                exitIfNull($dossier);
                exitIfNull($EvaluationInfirmier);
                exitIfNullOrEmpty($EvaluationInfirmier->date);

                $dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);
                $EvaluationInfirmier->id = $dossier->id;
                $result = $EvaluationInfirmierMapper->findObject($EvaluationInfirmier->beforeSerialisation($account));


                if($result == false)
                {
                    if($EvaluationInfirmierMapper->lastError == BAD_MATCH)
                        forward($this->mappingTable["URL_MANAGE"],"Cet enregistrement n'existe pas");
                    else
                        forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
                }
                $EvaluationInfirmier = $result->afterDeserialisation($account);


                global $EvalContinue;
                $EvalContinue = new EvalContinue();
                $EvalContinue->id=$EvaluationInfirmier->id;

                global $Epices;
                $Epices = new Epices();
                $Epices->id=$dossier->id;;
                $Epices->date=$EvaluationInfirmier->date;
                $result = $EpicesMapper->findObject($Epices->beforeSerialisation($account));
                $Epices = $result;

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

                if($param->param1 == PARAM_EDIT)
                {

                    // echo '<pre>';var_dump($suiviDiabete->dsuivi;);echo '</pre>';exit;
                    // $dossier = checkDossier($dossier,$dossierMapper,$account->cabinet);
                    // $suiviDiabete->dossier_id = $dossier->id;

                    // //Recherche du suivi diabète demandé
                    // $result = $suiviDiabeteMapper->findObject($suiviDiabete->beforeSerialisation($account));
                    // echo '<pre>';var_dump($result);echo '</pre>';


                    // global $suiviDiabete;

                    // $suiviDiabete=new suiviDiabete();
                    // $suiviDiabete->suivi_type=array("a", "4");
                    // $suiviDiabete->dossier_id = $dossier->id;
                    // $dsuivi = $EvaluationInfirmier->date;


                    // foreach($_ENV['liste_exam_diabete'] as $exam){
                    // 	$result=$BiologieMapper->findExam($dsuivi, $dossier->id, $exam);
                    // 	$$exam=new Biologie();
                    // 	$$exam->id=$dossier->id;
                    // 	$$exam->type_exam=$exam;
                    // 	$$exam->numero=$res["numero"];
                    // }

                    // $result = $suiviDiabeteMapper->findObjects($suiviDiabete);



                    // if($result == false){
                    // 	if($suiviDiabeteMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
                    // }
                    // else {
                    // 	$suiviDiabeteList = $result;
                    // 	for($i=0;$i<count($suiviDiabeteList);$i++){
                    // 		$suiviDiabeteList[$i] = $suiviDiabeteList[$i]->afterDeserialisation($account);
                    // 	}
                    // }


                    // global $dernier_suivi;
                    // $dernier_suivi = new SuiviDiabete();
                    // $result = $suiviDiabeteMapper->getdernierExams($dossier->id);


                    // if($result == false){
                    // 	if($suiviDiabeteMapper->lastError == BAD_MATCH) $result=0;
                    // 	else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
                    // }

                    // if($result!=0){
                    // 	$resultdiab = $result[0];


                    // 	if(!isset($resultdiab)){
                    // 		$resultdiab['dHBA']=$resultdiab['dExaFil']=$resultdiab['dExaPieds']='0000-00-00';
                    // 		$resultdiab['dChol']=$resultdiab['dLDL']=$resultdiab['dCreat']='0000-00-00';
                    // 		$resultdiab['dAlbu']=$resultdiab['dFond']=$resultdiab['dECG']='0000-00-00';
                    // 		$resultdiab['dPoids']=$resultdiab['dtension']=$resultdiab['dentiste']='0000-00-00';
                    // 		$resultdiab['dKaliemie']=$resultdiab['dTriglycerides']='0000-00-00';
                    // 	}

                    // 	$dernier_suivi->dHBA=$resultdiab["dHBA"];
                    // 	$dernier_suivi->dExaFil=$resultdiab["dExaFil"];
                    // 	$dernier_suivi->dExaPieds=$resultdiab["dExaPieds"];
                    // 	$dernier_suivi->dChol=$resultdiab["dChol"];
                    // 	$dernier_suivi->dLDL=$resultdiab["dLDL"];
                    // 	$dernier_suivi->dCreat=$resultdiab["dCreat"];
                    // 	$dernier_suivi->dAlbu=$resultdiab["dAlbu"];
                    // 	$dernier_suivi->dFond=$resultdiab["dFond"];
                    // 	$dernier_suivi->dECG=$resultdiab["dECG"];
                    // 	$dernier_suivi->dsuivi=$resultdiab["dsuivi"];
                    // 	$dernier_suivi->dPoids=$resultdiab["dPoids"];
                    // 	$dernier_suivi->dtension=$resultdiab["dtension"];
                    // 	$dernier_suivi->dentiste=$resultdiab["dentiste"];
                    // 	$dernier_suivi->dTriglycerides=$resultdiab["dTriglycerides"];
                    // 	$dernier_suivi->dKaliemie=$resultdiab["dKaliemie"];

                    // 	$result = $suiviDiabeteMapper->getsystematique($dossier->id, $dernier_suivi->dsuivi);
                    // 	$result = $result[0];


                    // 	$suiviDiabete->hta = $result["hta"];
                    // 	$suiviDiabete->arte = $result["arte"];
                    // 	$suiviDiabete->neph = $result["neph"];
                    // 	$suiviDiabete->coro = $result["coro"];
                    // 	$suiviDiabete->reti = $result["reti"];
                    // 	$suiviDiabete->neur = $result["neur"];
                    // 	$suiviDiabete->equilib = $result["equilib"];
                    // 	$suiviDiabete->lipide = $result["lipide"];
                    // 	#$suiviDiabete->type = $result["type"];

                    // 	// $liste_exam=array("poids"=>"dPoids",
                    // 	// 				  "systole"=>"dtension",
                    // 	// 				  "HDL"=>"dChol",
                    // 	// 				  "LDL"=>"dLDL",
                    // 	// 				  "monofil"=>"dExaFil",
                    // 	// 				  "pied"=>"dateExaPieds",
                    // 	// 				  "creat"=>"dCreat",
                    // 	// 				  "albu"=>"dAlbu",
                    // 	// 				  "fond"=>"dFond",
                    // 	// 				  "ecg"=>"dECG");

                    // 	foreach($_ENV['liste_examRD_diabete'] as $exam=>$champ){
                    // 		$result=$BiologieMapper->findExam(date("Y-m-d"), $dossier->id, $exam);
                    // 		$dernier_suivi->$champ=$result["dexam"];
                    // 	}



                    //   $dernier_suivi = $dernier_suivi->afterDeserialisation($account);
                    //   #echo '<pre>';var_dump($dernier_suivi);echo '</pre>';
                    // }

                    // $suiviDiabete->date_debut=$suiviDiabeteMapper->getDebut($dossier);
                    // $suiviDiabete->diab10ans=$suiviDiabeteMapper->get10ans($dossier);

                    forward($this->mappingTable["URL_AFTER_FIND_EDIT"]);
                }// fin if $param->param1 == PARAM_EDIT
                else
                    forward($this->mappingTable["URL_AFTER_FIND_VIEW"]);

                break;

            case ACTION_CONSULT_EVT:
                echo htmlentities("Développement en cours");
                /*				print_r($_POST);
                                print_r($_GET);*/
                break;

            default:
                echo("ACTION IS NULL");
                break;
        }
    }

}
?> 
