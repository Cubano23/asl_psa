<?php

require_once("bean/CardioVasculaireDepart.php");
require_once("bean/EvaluationInfirmier.php");
require_once("bean/ControlerParams.php");
require_once("persistence/CardioVasculaireDepartMapper.php");
require_once("persistence/EvaluationInfirmierMapper.php");
require_once("GenericControler.php");
require_once("bean/AutreConsultCardio.php");
require_once("persistence/AutreConsultCardioMapper.php");

class SuiviINRControler{

    var $mappingTable;

    function SuiviINRControler() {
        $this->mappingTable =
            array(
                "URL_MANAGE"=>"view/cardiovasculaire/suiviinr/managesuiviinr.php",
                "URL_NEW"=>"view/cardiovasculaire/suiviinr/newsuiviinr.php",
                "URL_AFTER_CREATE"=>"view/cardiovasculaire/suiviinr/viewsuiviinraftercreate.php",
                "URL_AFTER_UPDATE"=>"view/cardiovasculaire/suiviinr/viewsuiviinraftercreate.php",
                "URL_AFTER_FIND_VIEW"=>"view/cardiovasculaire/suiviinr/viewsuiviinr.php",
                "URL_AFTER_FIND_EDIT"=>"view/cardiovasculaire/suiviinr/newsuiviinr.php",
                "URL_AFTER_DELETE"=>new ControlerParams("suiviinrCardioControler",ACTION_MANAGE,true),
                "URL_AFTER_LIST"=>"view/cardiovasculaire/suiviinr/listsuiviinr.php",
                "URL_AFTER_FIND_LIST_DOSSIER"=>"view/cardiovasculaire/suiviinr/listsuiviinrbydossier.php",
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
        global $AutreConsultCardio;

        if(array_key_exists("outDateReference",$objects))
            $outDateReference = $objects["outDateReference"];

        global $dossier;
        if(array_key_exists("dossier",$objects))
            $dossier = $objects["dossier"];

        if(array_key_exists("CardioVasculaireDepart",$objects))
            $CardioVasculaireDepart = $objects["CardioVasculaireDepart"];

        if(array_key_exists("AutreConsultCardio",$objects))
            $AutreConsultCardio = $objects["AutreConsultCardio"];

        $ledgerFactory = new LedgerFactory();
        $ledger = $ledgerFactory->getLedger("Controler","SuiviINRControler");

        //Create connection factory
        $cf = new ConnectionFactory();

        //create mappers
        $CardioVasculaireDepartMapper = new CardioVasculaireDepartMapper($cf->getConnection());
        $EvaluationInfirmierMapper = new EvaluationInfirmierMapper($cf->getConnection());
        $AutreConsultCardioMapper = new AutreConsultCardioMapper($cf->getConnection());
        $dossierMapper = new DossierMapper($cf->getConnection());

        $ledger->writeArray(I,"Start","Control Parameters = ",$param);


        switch($param->action){
            case ACTION_MANAGE:
                $dossier = new Dossier();
                $AutreConsultCardio = new AutreConsultCardio();
                $AutreConsultCardio->date= date("d/m/Y");

                forward($this->mappingTable["URL_MANAGE"]);
                break;


            case ACTION_NEW:
                exitIfNull($dossier);
                exitIfNull($AutreConsultCardio);
                exitIfNullOrEmpty($AutreConsultCardio->date);

                if(!isValidDate($AutreConsultCardio->date)){
                    forward($this->mappingTable["URL_MANAGE"],"La date de consultation est invalide");
                }

                $dossier = checkDossierActif($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);

                global $suividiab;

                $suividiab=$CardioVasculaireDepartMapper->getsuividiab($dossier->id);

                $CardioVasculaireDepart = new CardioVasculaireDepart();
                $CardioVasculaireDepart->id = $dossier->id;
                $CardioVasculaireDepart->date = $AutreConsultCardio->date;

                $AutreConsultCardio->id = $dossier->id;

                $result = $AutreConsultCardioMapper->findObject($AutreConsultCardio->beforeSerialisation($account));
                if($result == false){
                    if($AutreConsultCardioMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"dossier non trouvé");
                }
                else{
                    forward($this->mappingTable["URL_MANAGE"],"Une consultation de suivi RCVA a déjà été enregistrée pour le jour indiqué pour ce patient. Vous ne pouvez créer deux consultations le même jour.<br>
Vous pouvez par contre modifier le contenu de la consultation enregistrée ce jour là. Pour cela, cliquez sur le bouton modifier.");
                }


                $CardioVasculaireDepart=$CardioVasculaireDepart->beforeSerialisation($account);

                $result2 = $CardioVasculaireDepartMapper->getdernierExamsRCVA($dossier->id);
                if($result2 == false){
                    if($CardioVasculaireDepartMapper->lastError == BAD_MATCH) $result2=0;
                    else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
                }


                $glycemie = $CardioVasculaireDepartMapper->getGlycemieDiab($dossier->id);
                if(($result2!=0)||($glycemie!=false)){
                    $resultRCVA = $result2[0];

                    if(!isset($resultRCVA)){
                        $resultRCVA['dpoids']=$resultRCVA['dTA']=$resultRCVA['dCreat']=$resultRCVA['dproteinurie']='0000-00-00';
                        $resultRCVA['dhematurie']=$resultRCVA['dgly']=$resultRCVA['dkaliemie']=$resultRCVA['dHDL']='0000-00-00';
                        $resultRCVA['dLDL']=$resultRCVA['dFond']=$resultRCVA['dECG']=$resultRCVA['exam_cardio']='0000-00-00';
                        $resultRCVA['darret']=$resultRCVA['dpouls']=$resultRCVA['dsokolov']=$resultRCVA['dChol']='0000-00-00';
                        $resultRCVA['dtriglycerides']='0000-00-00';
                        $autre_exam="non";
                    }
                    else{
                        $autre_exam="oui";
                    }

                    if(($resultRCVA['dpoids']>'0000-00-00'))
                    {

                        $poids= $CardioVasculaireDepartMapper->getPoidsRCVA($dossier->id, $resultRCVA['dpoids']);
                        $CardioVasculaireDepart->dpoids=$resultRCVA['dpoids'];
                        $poids=$poids[0];
                        $CardioVasculaireDepart->poids=$poids['poids'];
                    }

                    if(($resultRCVA['dTA']>'0000-00-00'))
                    {
                        $tension= $CardioVasculaireDepartMapper->getTensionRCVA($dossier->id, $resultRCVA['dTA']);
                        $CardioVasculaireDepart->dTA=$resultRCVA['dTA'];

                        $tension=$tension[0];
                        $CardioVasculaireDepart->TaSys=$tension['TaSys'];
                        $CardioVasculaireDepart->TaDia=$tension['TaDia'];
                        $CardioVasculaireDepart->HTA=$tension['HTA'];
                        $CardioVasculaireDepart->TA_mode=$tension['TA_mode'];

                    }

                    if(($resultRCVA['exam_cardio']>'0000-00-00'))
                    {
                        $CardioVasculaireDepart->exam_cardio=$resultRCVA['exam_cardio'];
                    }

                    if(($resultRCVA['dCreat']>'0000-00-00'))
                    {
                        $creat= $CardioVasculaireDepartMapper->getCreatRCVA($dossier->id, $resultRCVA['dCreat']);
                        $CardioVasculaireDepart->dCreat=$resultRCVA['dCreat'];

                        $creat=$creat[0];
                        $CardioVasculaireDepart->Creat=$creat['Creat'];
                    }

                    if(($resultRCVA['dproteinurie']>'0000-00-00'))
                    {
                        $proteinurie= $CardioVasculaireDepartMapper->getProteinurieRCVA($dossier->id, $resultRCVA['dproteinurie']);
                        $CardioVasculaireDepart->dproteinurie=$resultRCVA['dproteinurie'];
                        $proteinurie=$proteinurie[0];
                        $CardioVasculaireDepart->proteinurie=$proteinurie['proteinurie'];
                    }

                    if(($resultRCVA['dhematurie']>'0000-00-00'))
                    {
                        $hematurie= $CardioVasculaireDepartMapper->getHematurieRCVA($dossier->id, $resultRCVA['dhematurie']);
                        $CardioVasculaireDepart->dhematurie=$resultRCVA['dhematurie'];
                        $hematurie=$hematurie[0];
                        $CardioVasculaireDepart->hematurie=$hematurie['hematurie'];
                    }


                    if(($resultRCVA['dgly']>'0000-00-00')||($glycemie!=false))
                    {
                        if($glycemie==false){
                            $glycemie= $CardioVasculaireDepartMapper->getGlycemieRCVA($dossier->id, $resultRCVA['dgly']);
                            $CardioVasculaireDepart->dgly=$resultRCVA['dgly'];
                            $glycemie=$glycemie[0];
                            $CardioVasculaireDepart->glycemie=$glycemie['glycemie'];
                        }
                        else{
                            $glycemie=$glycemie[0];
                            if($glycemie['dgly']>$resultRCVA['dgly']){
                                $CardioVasculaireDepart->glycemie=$glycemie['glycemie'];
                                $CardioVasculaireDepart->dgly=$glycemie['dgly'];
                            }
                            else{
                                $glycemie= $CardioVasculaireDepartMapper->getGlycemieRCVA($dossier->id, $resultRCVA['dgly']);
                                $CardioVasculaireDepart->dgly=$resultRCVA['dgly'];
                                $glycemie=$glycemie[0];
                                $CardioVasculaireDepart->glycemie=$glycemie['glycemie'];
                            }
                        }

                    }

                    if(($resultRCVA['dkaliemie']>'0000-00-00'))
                    {
                        $kaliemie= $CardioVasculaireDepartMapper->getKaliemieRCVA($dossier->id, $resultRCVA['dkaliemie']);
                        $CardioVasculaireDepart->dkaliemie=$resultRCVA['dkaliemie'];
                        $kaliemie=$kaliemie[0];
                        $CardioVasculaireDepart->kaliemie=$kaliemie['kaliemie'];
                    }

                    if(($resultRCVA['dHDL']>'0000-00-00'))
                    {
                        $HDL= $CardioVasculaireDepartMapper->getHDLRCVA($dossier->id, $resultRCVA['dHDL']);
                        $CardioVasculaireDepart->dHDL=$resultRCVA['dHDL'];
                        $HDL=$HDL[0];
                        $CardioVasculaireDepart->HDL=$HDL['HDL'];
                    }

                    if(($resultRCVA['dLDL']>'0000-00-00'))
                    {
                        $LDL= $CardioVasculaireDepartMapper->getLDLRCVA($dossier->id, $resultRCVA['dLDL']);
                        $CardioVasculaireDepart->dLDL=$resultRCVA['dLDL'];
                        $LDL=$LDL[0];
                        $CardioVasculaireDepart->LDL=$LDL['LDL'];
                    }

                    if(($resultRCVA['dFond']>'0000-00-00'))
                    {
                        $CardioVasculaireDepart->dFond=$resultRCVA['dFond'];
                    }

                    if(($resultRCVA['dECG']>'0000-00-00'))
                    {
                        $CardioVasculaireDepart->dECG=$resultRCVA['dECG'];
                    }

                    if($resultRCVA['darret']>'0000-00-00'){
                        $CardioVasculaireDepart->darret=$resultRCVA['darret'];
                    }

                    if($resultRCVA['dsokolov']>'0000-00-00'){
                        $CardioVasculaireDepart->dsokolov=$resultRCVA['dsokolov'];
                        $sokolov= $CardioVasculaireDepartMapper->getSokolov($dossier->id, $resultRCVA['dsokolov']);

                        $sokolov=$sokolov[0];
                        $CardioVasculaireDepart->sokolov=$sokolov['sokolov'];
                        $CardioVasculaireDepart->surcharge_ventricule=$sokolov['surcharge_ventricule'];
                    }

                    if($resultRCVA['dChol']>'0000-00-00'){
                        $CardioVasculaireDepart->dChol=$resultRCVA['dChol'];
                        $Chol= $CardioVasculaireDepartMapper->getChol($dossier->id, $resultRCVA['dChol']);

                        $Chol=$Chol[0];
                        $CardioVasculaireDepart->Chol=$Chol['Chol'];
                    }

                    if($resultRCVA['dtriglycerides']>'0000-00-00'){
                        $CardioVasculaireDepart->dtriglycerides=$resultRCVA['dtriglycerides'];
                        $triglycerides= $CardioVasculaireDepartMapper->getTriglycerides($dossier->id, $resultRCVA['dtriglycerides']);

                        $triglycerides=$triglycerides[0];
                        $CardioVasculaireDepart->triglycerides=$triglycerides['triglycerides'];
                    }

                    if($resultRCVA['dpouls']>'0000-00-00'){
                        $CardioVasculaireDepart->dpouls=$resultRCVA['dpouls'];
                        $pouls= $CardioVasculaireDepartMapper->getPouls($dossier->id, $resultRCVA['dpouls']);

                        $pouls=$pouls[0];
                        $CardioVasculaireDepart->pouls=$pouls['pouls'];
                    }

                    if($autre_exam=="oui"){
                        $autres= $CardioVasculaireDepartMapper->getAntecedants($dossier->id);

                        if($autres!=""){
                            foreach($autres as $autre){
                                $CardioVasculaireDepart->antecedants=$autre['antecedants'];
                            }
                        }

                        $autres= $CardioVasculaireDepartMapper->getTraitement($dossier->id);

                        if($autres!=""){
                            foreach($autres as $autre){
                                $CardioVasculaireDepart->traitement=split(',',$autre['traitement']);
                                $CardioVasculaireDepart->dosage=$autre['dosage'];
                                $CardioVasculaireDepart->activite=$autre['activite'];
                            }
                        }

                        $autres= $CardioVasculaireDepartMapper->getHypertenseur3($dossier->id);

                        if($autres!=""){
                            foreach($autres as $autre){
                                $CardioVasculaireDepart->hypertenseur3=$autre['hypertenseur3'];
                            }
                        }

                        $autres= $CardioVasculaireDepartMapper->getAutomes($dossier->id);

                        if($autres!=""){
                            foreach($autres as $autre){
                                $CardioVasculaireDepart->automesure=$autre['automesure'];
                            }
                        }

                        $autres= $CardioVasculaireDepartMapper->getDiuretique($dossier->id);

                        if($autres!=""){
                            foreach($autres as $autre){
                                $CardioVasculaireDepart->diuretique=$autre['diuretique'];
                            }
                        }

                        $autres= $CardioVasculaireDepartMapper->getHVG($dossier->id);

                        if($autres!=""){
                            foreach($autres as $autre){
                                $CardioVasculaireDepart->HVG=$autre['HVG'];
                            }
                        }

                        $autres= $CardioVasculaireDepartMapper->getTabac($dossier->id);

                        if($autres!=""){
                            foreach($autres as $autre){
                                $CardioVasculaireDepart->tabac=$autre['tabac'];
                            }
                        }

                        $autres= $CardioVasculaireDepartMapper->getAlcool($dossier->id);

                        if($autres!=""){
                            foreach($autres as $autre){
                                $CardioVasculaireDepart->alcool=$autre['alcool'];
                            }
                        }

                    }


                }

                $CardioVasculaireDepart = $CardioVasculaireDepart->afterDeserialisation($account);

                $result = $AutreConsultCardioMapper->Liste_consult($dossier->id);

                global $ListConsult;
                $ListConsult=array();

                if($result){
                    foreach($result as $tab){
                        $ListConsult[$tab["date"]]=$tab["date_affiche"];
                    }
                }

                // $AutreConsultCardio->type_consultation=array("rcva");
                forward($this->mappingTable["URL_NEW"]);

                break;

            case ACTION_SAVE:
                exitIfNull($dossier);
                exitIfNull($CardioVasculaireDepart);
                exitIfNull($AutreConsultCardio);
                exitIfNullOrEmpty($AutreConsultCardio->date);
                $dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);

                $AutreConsultCardio->id=$dossier->id;

                $CardioVasculaireDepart->id = $dossier->id;
                $CardioVasculaireDepart->date=$AutreConsultCardio->date;

                $errors = $CardioVasculaireDepart->check();
                $errors2 = $AutreConsultCardio->check();

                if(count($errors2)!=0){
                    foreach($errors2 as $erreur){
                        $errors[]=$erreur;
                    }
                }

                if(count($errors) !=0) forward($this->mappingTable["URL_NEW"],$errors);

                $result = $CardioVasculaireDepartMapper->findObject($CardioVasculaireDepart->beforeSerialisation($account));

                if($result == false){

                    if(($CardioVasculaireDepartMapper->lastError != BAD_MATCH)&&($CardioVasculaireDepartMapper->lastError!=PARAM)) forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");

                    $CardioVasculaireDepart = $CardioVasculaireDepartMapper->purgeexam($CardioVasculaireDepart->beforeSerialisation($account));

                    if($CardioVasculaireDepart!==false){
                        $result = $CardioVasculaireDepartMapper->createObject($CardioVasculaireDepart);

                        if($result == false){
                            if($CardioVasculaireDepartMapper->lastError!= NOTHING_UPDATED)
                                forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour des examens");
                        }
                    }

                    $result = $AutreConsultCardioMapper->findObject($AutreConsultCardio->beforeSerialisation($account));

                    if($result==false){
                        $result=$AutreConsultCardioMapper->createObject($AutreConsultCardio->beforeSerialisation($account));

                        if($result == false){
                            if($AutreConsultCardioMapper->lastError!= NOTHING_UPDATED)
                                forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la création de la consultation");
                        }
                        $ei = new EvaluationInfirmier();
                        $EvaluationInfirmier=new EvaluationInfirmier($AutreConsultCardio->id, $AutreConsultCardio->date,
                            $ei->getUserIdByLogin($_SESSION['id.login']), $ei->getCabIdByCab($_SESSION['cabinet']),
                            $AutreConsultCardio->degre_satisfaction, $AutreConsultCardio->duree,
                            $AutreConsultCardio->points_positifs, $AutreConsultCardio->points_ameliorations,
                            $AutreConsultCardio->type_consultation, $AutreConsultCardio->ecg_seul,
                            $AutreConsultCardio->ecg,
                            $AutreConsultCardio->monofil, $AutreConsultCardio->exapied,
                            $AutreConsultCardio->hba, $AutreConsultCardio->tension,
                            $AutreConsultCardio->spirometre,
                            $AutreConsultCardio->spirometre_seul,
                            $AutreConsultCardio->t_cognitif,
                            $AutreConsultCardio->autre, $AutreConsultCardio->prec_autre);
                        $result=$EvaluationInfirmierMapper->createObject($EvaluationInfirmier->beforeSerialisation($account));
                    }
                    else{
                        $result = $AutreConsultCardioMapper->updateObject($AutreConsultCardio->beforeSerialisation($account));
                        if($result == false){
                            if($AutreConsultCardioMapper->lastError!= NOTHING_UPDATED)
                                forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour de la consultation");
                        }
                        $ei = new EvaluationInfirmier();
                        $EvaluationInfirmier=new EvaluationInfirmier($AutreConsultCardio->id, $AutreConsultCardio->date,
                            $ei->getUserIdByLogin($_SESSION['id.login']), $ei->getCabIdByCab($_SESSION['cabinet']),
                            $AutreConsultCardio->degre_satisfaction, $AutreConsultCardio->duree,
                            $AutreConsultCardio->points_positifs, $AutreConsultCardio->points_ameliorations,
                            $AutreConsultCardio->type_consultation, $AutreConsultCardio->ecg_seul,
                            $AutreConsultCardio->ecg,
                            $AutreConsultCardio->monofil, $AutreConsultCardio->exapied,
                            $AutreConsultCardio->hba, $AutreConsultCardio->tension,
                            $AutreConsultCardio->spirometre,
                            $AutreConsultCardio->spirometre_seul,
                            $AutreConsultCardio->t_cognitif,
                            $AutreConsultCardio->autre, $AutreConsultCardio->prec_autre);
                        $result=$EvaluationInfirmierMapper->updateObject($EvaluationInfirmier->beforeSerialisation($account));
                    }
                    forward($this->mappingTable["URL_AFTER_CREATE"]);

                }
                else{
                    $result = $CardioVasculaireDepartMapper->updateObject($CardioVasculaireDepart->beforeSerialisation($account));

                    if($result == false) {
                        if($CardioVasculaireDepartMapper->lastError != NOTHING_UPDATED)
                            forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour");
                    }

                    $result = $AutreConsultCardioMapper->findObject($AutreConsultCardio->beforeSerialisation($account));

                    if($result==false){
                        $result=$AutreConsultCardioMapper->createObject($AutreConsultCardio->beforeSerialisation($account));
                        if($result == false) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la création de la consultation");
                        $ei = new EvaluationInfirmier();
                        $EvaluationInfirmier=new EvaluationInfirmier($AutreConsultCardio->id, $AutreConsultCardio->date,
                            $ei->getUserIdByLogin($_SESSION['id.login']), $ei->getCabIdByCab($_SESSION['cabinet']),
                            $AutreConsultCardio->degre_satisfaction, $AutreConsultCardio->duree,
                            $AutreConsultCardio->points_positifs, $AutreConsultCardio->points_ameliorations,
                            $AutreConsultCardio->type_consultation, $AutreConsultCardio->ecg_seul,
                            $AutreConsultCardio->ecg,
                            $AutreConsultCardio->monofil, $AutreConsultCardio->exapied,
                            $AutreConsultCardio->hba, $AutreConsultCardio->tension,
                            $AutreConsultCardio->spirometre,
                            $AutreConsultCardio->spirometre_seul,
                            $AutreConsultCardio->t_cognitif,
                            $AutreConsultCardio->autre, $AutreConsultCardio->prec_autre);
                        $result=$EvaluationInfirmierMapper->createObject($EvaluationInfirmier->beforeSerialisation($account));
                    }
                    else{
                        $result = $AutreConsultCardioMapper->updateObject($AutreConsultCardio->beforeSerialisation($account));
                        if($result == false){
                            if($AutreConsultCardioMapper->lastError!= NOTHING_UPDATED)
                                forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour de la consultation");
                            $ei = new EvaluationInfirmier();
                            $EvaluationInfirmier=new EvaluationInfirmier($AutreConsultCardio->id, $AutreConsultCardio->date,
                                $ei->getUserIdByLogin($_SESSION['id.login']), $ei->getCabIdByCab($_SESSION['cabinet']),
                                $AutreConsultCardio->degre_satisfaction, $AutreConsultCardio->duree,
                                $AutreConsultCardio->points_positifs, $AutreConsultCardio->points_ameliorations,
                                $AutreConsultCardio->type_consultation, $AutreConsultCardio->ecg_seul,
                                $AutreConsultCardio->ecg,
                                $AutreConsultCardio->monofil, $AutreConsultCardio->exapied,
                                $AutreConsultCardio->hba, $AutreConsultCardio->tension,
                                $AutreConsultCardio->spirometre,
                                $AutreConsultCardio->spirometre_seul,
                                $AutreConsultCardio->t_cognitif,
                                $AutreConsultCardio->autre, $AutreConsultCardio->prec_autre);

                            $result=$EvaluationInfirmierMapper->updateObject($EvaluationInfirmier->beforeSerialisation($account));
                        }
                    }
                    forward($this->mappingTable["URL_AFTER_CREATE"]);

                }
                break;

            case ACTION_DELETE:

                exitIfNull($dossier);
                exitIfNull($AutreConsultCardio);
                exitIfNullOrEmpty($AutreConsultCardio->date);

                $dossier = checkDossier($dossier,$dossierMapper,$account->cabinet);

                $AutreConsultCardio->id = $dossier->id;

                $result = $AutreConsultCardioMapper->deleteObject($AutreConsultCardio->beforeSerialisation($account));
                if($result == false){
                    if($AutreConsultCardioMapper->lastError != NOTHING_DELETED) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Delete object caused an error");
                }

                $EvaluationInfirmier=new EvaluationInfirmier();
                $EvaluationInfirmier->id = $dossier->id;
                $EvaluationInfirmier->date=$AutreConsultCardio->date;

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


            case ACTION_FIND:
                if(!$param->isParam1Valid())
                    forward(URL_CONTROLER_PARAMS_ERROR,"Invalid param1");

                exitIfNull($dossier);

                exitIfNull($AutreConsultCardio);
                exitIfNullOrEmpty($AutreConsultCardio->date);
                $dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);

                global $suividiab;

                $suividiab=$CardioVasculaireDepartMapper->getsuividiab($dossier->id);

                $AutreConsultCardio->id = $dossier->id;
                $result = $AutreConsultCardioMapper->findObject($AutreConsultCardio->beforeSerialisation($account));

                if($result == false)
                {
                    if($AutreConsultCardioMapper->lastError == BAD_MATCH)
                        forward($this->mappingTable["URL_MANAGE"],"Cet enregistrement n'existe pas");
                    else
                        forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
                }
                $AutreConsultCardio = $result->afterDeserialisation($account);

                if($param->param1 == PARAM_EDIT)
                {
                    $CardioVasculaireDepart = new CardioVasculaireDepart();
                    $CardioVasculaireDepart->id = $dossier->id;
                    $CardioVasculaireDepart->date = $AutreConsultCardio->date;

                    $CardioVasculaireDepart=$CardioVasculaireDepart->beforeSerialisation($account);

                    $result2 = $CardioVasculaireDepartMapper->getdernierExamsRCVA($dossier->id);
                    if($result2 == false){
                        if($CardioVasculaireDepartMapper->lastError == BAD_MATCH) $result2=0;
                        else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
                    }


                    $glycemie = $CardioVasculaireDepartMapper->getGlycemieDiab($dossier->id);
                    if(($result2!=0)||($glycemie!=false)){
                        $resultRCVA = $result2[0];

                        if(!isset($resultRCVA)){
                            $resultRCVA['dpoids']=$resultRCVA['dTA']=$resultRCVA['dCreat']=$resultRCVA['dproteinurie']='0000-00-00';
                            $resultRCVA['dhematurie']=$resultRCVA['dgly']=$resultRCVA['dkaliemie']=$resultRCVA['dHDL']='0000-00-00';
                            $resultRCVA['dLDL']=$resultRCVA['dFond']=$resultRCVA['dECG']=$resultRCVA['exam_cardio']='0000-00-00';
                            $resultRCVA['darret']=$resultRCVA['dpouls']=$resultRCVA['dsokolov']=$resultRCVA['dChol']='0000-00-00';
                            $resultRCVA['dtriglycerides']='0000-00-00';
                            $autre_exam="non";
                        }
                        else{
                            $autre_exam="oui";
                        }

                        if(($resultRCVA['dpoids']>'0000-00-00'))
                        {

                            $poids= $CardioVasculaireDepartMapper->getPoidsRCVA($dossier->id, $resultRCVA['dpoids']);
                            $CardioVasculaireDepart->dpoids=$resultRCVA['dpoids'];
                            $poids=$poids[0];
                            $CardioVasculaireDepart->poids=$poids['poids'];
                        }

                        if(($resultRCVA['dTA']>'0000-00-00'))
                        {
                            $tension= $CardioVasculaireDepartMapper->getTensionRCVA($dossier->id, $resultRCVA['dTA']);
                            $CardioVasculaireDepart->dTA=$resultRCVA['dTA'];

                            $tension=$tension[0];
                            $CardioVasculaireDepart->TaSys=$tension['TaSys'];
                            $CardioVasculaireDepart->TaDia=$tension['TaDia'];
                            $CardioVasculaireDepart->HTA=$tension['HTA'];
                            $CardioVasculaireDepart->TA_mode=$tension['TA_mode'];

                        }

                        if(($resultRCVA['exam_cardio']>'0000-00-00'))
                        {
                            $CardioVasculaireDepart->exam_cardio=$resultRCVA['exam_cardio'];
                        }

                        if(($resultRCVA['dCreat']>'0000-00-00'))
                        {
                            $creat= $CardioVasculaireDepartMapper->getCreatRCVA($dossier->id, $resultRCVA['dCreat']);
                            $CardioVasculaireDepart->dCreat=$resultRCVA['dCreat'];

                            $creat=$creat[0];
                            $CardioVasculaireDepart->Creat=$creat['Creat'];
                        }

                        if(($resultRCVA['dproteinurie']>'0000-00-00'))
                        {
                            $proteinurie= $CardioVasculaireDepartMapper->getProteinurieRCVA($dossier->id, $resultRCVA['dproteinurie']);
                            $CardioVasculaireDepart->dproteinurie=$resultRCVA['dproteinurie'];
                            $proteinurie=$proteinurie[0];
                            $CardioVasculaireDepart->proteinurie=$proteinurie['proteinurie'];
                        }

                        if(($resultRCVA['dhematurie']>'0000-00-00'))
                        {
                            $hematurie= $CardioVasculaireDepartMapper->getHematurieRCVA($dossier->id, $resultRCVA['dhematurie']);
                            $CardioVasculaireDepart->dhematurie=$resultRCVA['dhematurie'];
                            $hematurie=$hematurie[0];
                            $CardioVasculaireDepart->hematurie=$hematurie['hematurie'];
                        }


                        if(($resultRCVA['dgly']>'0000-00-00')||($glycemie!=false))
                        {
                            if($glycemie==false){
                                $glycemie= $CardioVasculaireDepartMapper->getGlycemieRCVA($dossier->id, $resultRCVA['dgly']);
                                $CardioVasculaireDepart->dgly=$resultRCVA['dgly'];
                                $glycemie=$glycemie[0];
                                $CardioVasculaireDepart->glycemie=$glycemie['glycemie'];
                            }
                            else{
                                $glycemie=$glycemie[0];
                                if($glycemie['dgly']>$resultRCVA['dgly']){
                                    $CardioVasculaireDepart->glycemie=$glycemie['glycemie'];
                                    $CardioVasculaireDepart->dgly=$glycemie['dgly'];
                                }
                                else{
                                    $glycemie= $CardioVasculaireDepartMapper->getGlycemieRCVA($dossier->id, $resultRCVA['dgly']);
                                    $CardioVasculaireDepart->dgly=$resultRCVA['dgly'];
                                    $glycemie=$glycemie[0];
                                    $CardioVasculaireDepart->glycemie=$glycemie['glycemie'];
                                }
                            }

                        }

                        if(($resultRCVA['dkaliemie']>'0000-00-00'))
                        {
                            $kaliemie= $CardioVasculaireDepartMapper->getKaliemieRCVA($dossier->id, $resultRCVA['dkaliemie']);
                            $CardioVasculaireDepart->dkaliemie=$resultRCVA['dkaliemie'];
                            $kaliemie=$kaliemie[0];
                            $CardioVasculaireDepart->kaliemie=$kaliemie['kaliemie'];
                        }

                        if(($resultRCVA['dHDL']>'0000-00-00'))
                        {
                            $HDL= $CardioVasculaireDepartMapper->getHDLRCVA($dossier->id, $resultRCVA['dHDL']);
                            $CardioVasculaireDepart->dHDL=$resultRCVA['dHDL'];
                            $HDL=$HDL[0];
                            $CardioVasculaireDepart->HDL=$HDL['HDL'];
                        }

                        if(($resultRCVA['dLDL']>'0000-00-00'))
                        {
                            $LDL= $CardioVasculaireDepartMapper->getLDLRCVA($dossier->id, $resultRCVA['dLDL']);
                            $CardioVasculaireDepart->dLDL=$resultRCVA['dLDL'];
                            $LDL=$LDL[0];
                            $CardioVasculaireDepart->LDL=$LDL['LDL'];
                        }

                        if(($resultRCVA['dFond']>'0000-00-00'))
                        {
                            $CardioVasculaireDepart->dFond=$resultRCVA['dFond'];
                        }

                        if(($resultRCVA['dECG']>'0000-00-00'))
                        {
                            $CardioVasculaireDepart->dECG=$resultRCVA['dECG'];
                        }

                        if($resultRCVA['darret']>'0000-00-00'){
                            $CardioVasculaireDepart->darret=$resultRCVA['darret'];
                        }

                        if($resultRCVA['dsokolov']>'0000-00-00'){
                            $CardioVasculaireDepart->dsokolov=$resultRCVA['dsokolov'];
                            $sokolov= $CardioVasculaireDepartMapper->getSokolov($dossier->id, $resultRCVA['dsokolov']);

                            $sokolov=$sokolov[0];
                            $CardioVasculaireDepart->sokolov=$sokolov['sokolov'];
                            $CardioVasculaireDepart->surcharge_ventricule=$sokolov['surcharge_ventricule'];
                        }

                        if($resultRCVA['dChol']>'0000-00-00'){
                            $CardioVasculaireDepart->dChol=$resultRCVA['dChol'];
                            $Chol= $CardioVasculaireDepartMapper->getChol($dossier->id, $resultRCVA['dChol']);

                            $Chol=$Chol[0];
                            $CardioVasculaireDepart->Chol=$Chol['Chol'];
                        }

                        if($resultRCVA['dtriglycerides']>'0000-00-00'){
                            $CardioVasculaireDepart->dtriglycerides=$resultRCVA['dtriglycerides'];
                            $triglycerides= $CardioVasculaireDepartMapper->getTriglycerides($dossier->id, $resultRCVA['dtriglycerides']);

                            $triglycerides=$triglycerides[0];
                            $CardioVasculaireDepart->triglycerides=$triglycerides['triglycerides'];
                        }

                        if($resultRCVA['dpouls']>'0000-00-00'){
                            $CardioVasculaireDepart->dpouls=$resultRCVA['dpouls'];
                            $pouls= $CardioVasculaireDepartMapper->getPouls($dossier->id, $resultRCVA['dpouls']);

                            $pouls=$pouls[0];
                            $CardioVasculaireDepart->pouls=$pouls['pouls'];
                        }

                        if($autre_exam=="oui"){
                            $autres= $CardioVasculaireDepartMapper->getAntecedants($dossier->id);

                            if($autres!=""){
                                foreach($autres as $autre){
                                    $CardioVasculaireDepart->antecedants=$autre['antecedants'];
                                }
                            }

                            $autres= $CardioVasculaireDepartMapper->getTraitement($dossier->id);

                            if($autres!=""){
                                foreach($autres as $autre){
                                    $CardioVasculaireDepart->traitement=split(',',$autre['traitement']);
                                    $CardioVasculaireDepart->dosage=$autre['dosage'];
                                    $CardioVasculaireDepart->activite=$autre['activite'];
                                }
                            }

                            $autres= $CardioVasculaireDepartMapper->getHypertenseur3($dossier->id);

                            if($autres!=""){
                                foreach($autres as $autre){
                                    $CardioVasculaireDepart->hypertenseur3=$autre['hypertenseur3'];
                                }
                            }

                            $autres= $CardioVasculaireDepartMapper->getAutomes($dossier->id);

                            if($autres!=""){
                                foreach($autres as $autre){
                                    $CardioVasculaireDepart->automesure=$autre['automesure'];
                                }
                            }

                            $autres= $CardioVasculaireDepartMapper->getDiuretique($dossier->id);

                            if($autres!=""){
                                foreach($autres as $autre){
                                    $CardioVasculaireDepart->diuretique=$autre['diuretique'];
                                }
                            }

                            $autres= $CardioVasculaireDepartMapper->getHVG($dossier->id);

                            if($autres!=""){
                                foreach($autres as $autre){
                                    $CardioVasculaireDepart->HVG=$autre['HVG'];
                                }
                            }

                            $autres= $CardioVasculaireDepartMapper->getTabac($dossier->id);

                            if($autres!=""){
                                foreach($autres as $autre){
                                    $CardioVasculaireDepart->tabac=$autre['tabac'];
                                }
                            }

                            $autres= $CardioVasculaireDepartMapper->getAlcool($dossier->id);

                            if($autres!=""){
                                foreach($autres as $autre){
                                    $CardioVasculaireDepart->alcool=$autre['alcool'];
                                }
                            }

                        }


                    }

                    $CardioVasculaireDepart = $CardioVasculaireDepart->afterDeserialisation($account);
                    forward($this->mappingTable["URL_AFTER_FIND_EDIT"]);
                }
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
