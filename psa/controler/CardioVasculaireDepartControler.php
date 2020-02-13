<?php

require_once("bean/Biologie.php");
require_once("bean/CardioVasculaireDepart.php");
// require_once("bean/ControlerParams.php");
require_once("bean/OutdateReference.php");
require_once("bean/DepistageAOMI.php");
require_once("persistence/BiologieMapper.php");
require_once("persistence/CardioVasculaireDepartMapper.php");
require_once("persistence/ConnectionFactory.php");
// require_once("GenericControler.php");

class CardioVasculaireDepartControler{

    var $mappingTable;

    function CardioVasculaireDepartControler() {
        $this->mappingTable =
            array(
                "URL_MANAGE"=>"view/cardiovasculaire/managecardiovasculairedepart.php",
                "URL_MANAGE_OUTDATED"=>"view/cardiovasculaire/managealertecardio.php",
                "URL_NEW"=>"view/cardiovasculaire/newcardiovasculairedepart.php",
                "URL_NEW_ORGA"=>"view/cardiovasculaire/newcardiovasculairedepartv2.php",
                "URL_MANAGE_COMPLEMENT"=>"view/cardiovasculaire/managecardiovasculairedepartcomplement.php",
                "URL_NEW_COMPLEMENT"=>"view/cardiovasculaire/newcardiovasculairedepartcomplement.php",
                "URL_NEW_COMPLEMENT_ORGA"=>"view/cardiovasculaire/newcardiovasculairedepartcomplementv2.php",
                "URL_MANAGE_DEPART"=>"view/cardiovasculaire/managecardiovasculairedepartphoto.php",
                "URL_VIEW_DEPART"=>"view/cardiovasculaire/viewcardiovasculairedepartphoto.php",
                "URL_AFTER_CREATE"=>"view/cardiovasculaire/viewcardiovasculairedepartaftercreate.php",
                "URL_AFTER_UPDATE"=>"view/cardiovasculaire/viewcardiovasculairedepartaftercreate.php",
                "URL_AFTER_FIND_VIEW"=>"view/cardiovasculaire/viewcardiovasculairedepart.php",
                "URL_AFTER_FIND_EDIT"=>"view/cardiovasculaire/newcardiovasculairedepart.php",
                "URL_AFTER_DELETE"=>new ControlerParams("CardioVasculaireDepartControler",ACTION_MANAGE,true),
                "URL_AFTER_LIST"=>"view/cardiovasculaire/listcardiovasculairedepart.php",
                "URL_AFTER_LIST_DEPART"=>"view/cardiovasculaire/listcardiovasculairedepartphoto.php",
                "URL_AFTER_LIST_SPIRO"=>"view/cardiovasculaire/listcardiovasculairespiro.php",
                "URL_AFTER_LIST_OUTDATED"=>"view/cardiovasculaire/listcardioalerte.php",
                "URL_AFTER_FIND_LIST_DOSSIER"=>"view/cardiovasculaire/listcardiovasculairedepartbydossier.php",
                "URL_AFTER_COMPLETUDE"=>"view/cardiovasculaire/completude.php",
//			"URL_MANAGE_OUTDATED"=>"view/hypertension/managealertehypertension.php",
//			"URL_AFTER_LIST_OUTDATED"=>"view/hypertension/listhypertensionalerte.php",
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
        global $CardioVasculaireDepartList;
        global $poids;
        global $spirometrie;
        global $systole;
        global $diastole;
        global $type_tension;
        global $LDL;
        global $HDL;

        global $depistageAOMI;
        global $depistage_aomi;
        global $liste_historique;

        $dep_aomi = new DepistageAOMI();

        $liste_exam=array("Chol", "triglycerides", "creat", "kaliemie",
            "proteinurie", "hematurie", "fond", "ECG",
            "pouls", "glycemie");

        foreach($liste_exam as $exam){
            global $$exam;
        }

        if(array_key_exists("outDateReference",$objects))
            $outDateReference = $objects["outDateReference"];

        global $dossier;
        if(array_key_exists("dossier",$objects))
            $dossier = $objects["dossier"];

        if(array_key_exists("CardioVasculaireDepart",$objects))
            $CardioVasculaireDepart = $objects["CardioVasculaireDepart"];

        if(array_key_exists("poids",$objects))
            $poids = $objects["poids"];

        if(array_key_exists("spirometrie",$objects))
            $spirometrie = $objects["spirometrie"];

        if(array_key_exists("systole",$objects))
            $systole = $objects["systole"];

        if(array_key_exists("diastole",$objects))
            $diastole = $objects["diastole"];

        if(array_key_exists("type_tension",$objects))
            $type_tension = $objects["type_tension"];

        if(array_key_exists("HDL",$objects))
            $HDL = $objects["HDL"];

        if(array_key_exists("LDL",$objects))
            $LDL = $objects["LDL"];

        //AOMI récupère de la vue
        if(array_key_exists("DepistageAOMI",$objects))
            $depistageAOMI = $objects["DepistageAOMI"];

        foreach ($liste_exam as $exam){
            if(array_key_exists($exam,$objects))
                $$exam = $objects[$exam];
        }

        $ledgerFactory = new LedgerFactory();
        $ledger = $ledgerFactory->getLedger("Controler","CardioVasculaireDepartControler");

        //Create connection factory
        $cf = new ConnectionFactory();

        //create mappers
        $CardioVasculaireDepartMapper = new CardioVasculaireDepartMapper($cf->getConnection());
        $dossierMapper = new DossierMapper($cf->getConnection());
        $BiologieMapper = new BiologieMapper($cf->getConnection());

        $ledger->writeArray(I,"Start","Control Parameters = ",$param);


        switch($param->action){
            case ACTION_MANAGE:
                $dossier = new Dossier();
                $CardioVasculaireDepart = new CardioVasculaireDepart();
                $CardioVasculaireDepart->date= date("d/m/Y");
                $poids = new Biologie();
                $spirometrie = new Biologie();
                $systole = new Biologie();
                $diastole = new Biologie();
                $type_tension = new Biologie();
                $LDL = new Biologie();
                $HDL = new Biologie();
                $liste_exam=array("Chol", "triglycerides", "creat", "kaliemie",
                    "proteinurie", "hematurie", "fond", "ECG",
                    "pouls", "glycemie");

                foreach($liste_exam as $exam){
                    $$exam = new Biologie();
                }

                global $complement;
                $complement=false;
                global $orga;
                $orga=false;

                if(!$param->isParam1Valid()){
                    forward($this->mappingTable["URL_MANAGE"]);
                }
                else
                {
                    switch($param->param1){
                        case PARAM_OUTDATED:
                            $outDateReference = new OutDateReference();
                            forward($this->mappingTable["URL_MANAGE_OUTDATED"]);

                        case PARAM_EDIT:
                            $complement=true;
                            if(isset($param->param2)&&($param->param2==PARAM_DEPART)){
                                $orga=true;
                            }
                            forward($this->mappingTable["URL_MANAGE_COMPLEMENT"]);

                        case PARAM_VIEW:
                            forward($this->mappingTable["URL_MANAGE_DEPART"]);

                        default:
                            forward($this->mappingTable["URL_MANAGE"]);
                    }

                }
                break;

            case ACTION_FIND:
                if(!$param->isParam1Valid())
                    forward(URL_CONTROLER_PARAMS_ERROR,"Invalid param1");

                exitIfNull($dossier);

                if($param->param1 == PARAM_DEPART){
                    $CardioVasculaireDepart = new CardioVasculaireDepart();
                    $dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE_DEPART"]);

                    $poids= $CardioVasculaireDepartMapper->getPremierPoids($dossier->id);
                    $poids=$poids[0];
                    $CardioVasculaireDepart->dpoids=$poids['dpoids'];
                    $CardioVasculaireDepart->poids=$poids['poids'];

                    $spirometrie= $CardioVasculaireDepartMapper->getPremierSpirometrie($dossier->id);
                    $spirometrie=$spirometrie[0];
                    $CardioVasculaireDepart->spirometrie_date=$spirometrie['spirometrie_date'];
                    $CardioVasculaireDepart->spirometrie_rapport_VEMS_CVF=$spirometrie['spirometrie_rapport_VEMS_CVF'];
                    $CardioVasculaireDepart->spirometrie_status=$spirometrie['spirometrie_status'];

                    $tension= $CardioVasculaireDepartMapper->getPremierTension($dossier->id);
                    $tension=$tension[0];
                    $CardioVasculaireDepart->dTA=$tension['dTA'];
                    $CardioVasculaireDepart->TaSys=$tension['TaSys'];
                    $CardioVasculaireDepart->TaDia=$tension['TaDia'];
                    $CardioVasculaireDepart->HTA=$tension['HTA'];


                    $exam_cardio= $CardioVasculaireDepartMapper->getPremierExamCardio($dossier->id);
                    $exam_cardio=$exam_cardio[0];
                    $CardioVasculaireDepart->exam_cardio=$exam_cardio['exam_cardio'];

                    $creat= $CardioVasculaireDepartMapper->getPremierCreat($dossier->id);
                    $creat=$creat[0];
                    $CardioVasculaireDepart->dCreat=$creat['dCreat'];
                    $CardioVasculaireDepart->Creat=$creat['Creat'];


                    $proteinurie= $CardioVasculaireDepartMapper->getPremierProteinurie($dossier->id);
                    $proteinurie=$proteinurie[0];
                    $CardioVasculaireDepart->dproteinurie=$proteinurie['dproteinurie'];
                    $CardioVasculaireDepart->proteinurie=$proteinurie['proteinurie'];

                    $hematurie= $CardioVasculaireDepartMapper->getPremierHematurie($dossier->id);
                    $hematurie=$hematurie[0];
                    $CardioVasculaireDepart->dhematurie=$hematurie['dhematurie'];
                    $CardioVasculaireDepart->hematurie=$hematurie['hematurie'];

                    $glycemie= $CardioVasculaireDepartMapper->getPremierGlycemie($dossier->id);
                    $glycemie=$glycemie[0];
                    $CardioVasculaireDepart->dgly=$glycemie['dgly'];
                    $CardioVasculaireDepart->glycemie=$glycemie['glycemie'];


                    $kaliemie= $CardioVasculaireDepartMapper->getPremierKaliemie($dossier->id);
                    $kaliemie=$kaliemie[0];
                    $CardioVasculaireDepart->dkaliemie=$kaliemie['dkaliemie'];
                    $CardioVasculaireDepart->kaliemie=$kaliemie['kaliemie'];

                    $HDL= $CardioVasculaireDepartMapper->getPremierHDL($dossier->id);
                    $HDL=$HDL[0];
                    $CardioVasculaireDepart->dHDL=$HDL['dHDL'];
                    $CardioVasculaireDepart->HDL=$HDL['HDL'];

                    $LDL= $CardioVasculaireDepartMapper->getPremierLDL($dossier->id);
                    $LDL=$LDL[0];
                    $CardioVasculaireDepart->dLDL=$LDL['dLDL'];
                    $CardioVasculaireDepart->LDL=$LDL['LDL'];



                    $fond= $CardioVasculaireDepartMapper->getPremierFond($dossier->id);
                    $fond=$fond[0];
                    $CardioVasculaireDepart->dFond=$fond['dFond'];

                    $ECG= $CardioVasculaireDepartMapper->getPremierECG($dossier->id);
                    $ECG=$ECG[0];
                    $CardioVasculaireDepart->dECG=$ECG['dECG'];


                    $sokolov= $CardioVasculaireDepartMapper->getPremierSokolov($dossier->id);
                    $sokolov=$sokolov[0];
                    $CardioVasculaireDepart->dsokolov=$sokolov['dsokolov'];
                    $CardioVasculaireDepart->sokolov=$sokolov['sokolov'];
                    $CardioVasculaireDepart->surcharge_ventricule=$sokolov['surcharge_ventricule'];


                    $Chol= $CardioVasculaireDepartMapper->getPremierChol($dossier->id);
                    $Chol=$Chol[0];
                    $CardioVasculaireDepart->dChol=$Chol['dChol'];
                    $CardioVasculaireDepart->Chol=$Chol['Chol'];


                    $triglycerides= $CardioVasculaireDepartMapper->getPremierTriglycerides($dossier->id);
                    $triglycerides=$triglycerides[0];
                    $CardioVasculaireDepart->dtriglycerides=$triglycerides['dtriglycerides'];
                    $CardioVasculaireDepart->triglycerides=$triglycerides['triglycerides'];


                    $pouls= $CardioVasculaireDepartMapper->getPremierPouls($dossier->id);
                    $pouls=$pouls[0];
                    $CardioVasculaireDepart->dpouls=$pouls['dpouls'];
                    $CardioVasculaireDepart->pouls=$pouls['pouls'];


                    $autres= $CardioVasculaireDepartMapper->getPremierAutreExam($dossier->id);

                    $autres= $CardioVasculaireDepartMapper->getTabac($dossier->id);

                    foreach($autres as $autre){
                        $CardioVasculaireDepart->tabac=$autre['tabac'];
                        $CardioVasculaireDepart->darret=$autre['darret'];
                        $CardioVasculaireDepart->nbrtabac=$autre['nbrtabac'];
                    }



                    foreach($autres as $autre){
                        if(($CardioVasculaireDepart->antecedants!='oui')&&($CardioVasculaireDepart->antecedants!='non')){
                            if(($autre['antecedants']=='oui')||($autre['antecedants']=='non')){
                                $CardioVasculaireDepart->antecedants=$autre['antecedants'];
                            }
                        }

                        if($CardioVasculaireDepart->traitement==array()){
                            $CardioVasculaireDepart->traitement=explode(',',$autre['traitement']);
                            $CardioVasculaireDepart->dosage=$autre['dosage'];
                        }

                        if(($CardioVasculaireDepart->hypertenseur3!='oui')&&($CardioVasculaireDepart->hypertenseur3!='non')){
                            if(($autre['hypertenseur3']=='oui')||($autre['hypertenseur3']=='non')){
                                $CardioVasculaireDepart->hypertenseur3=$autre['hypertenseur3'];
                            }
                        }

                        if(($CardioVasculaireDepart->automesure!='oui')&&($CardioVasculaireDepart->automesure!='non')){
                            if(($autre['automesure']=='oui')||($autre['automesure']=='non')){
                                $CardioVasculaireDepart->automesure=$autre['automesure'];
                            }
                        }

                        if(($CardioVasculaireDepart->diuretique!='oui')&&($CardioVasculaireDepart->diuretique!='non')){
                            if(($autre['diuretique']=='oui')||($autre['diuretique']=='non')){
                                $CardioVasculaireDepart->diuretique=$autre['diuretique'];
                            }
                        }

                        if(($CardioVasculaireDepart->HVG!='oui')&&($CardioVasculaireDepart->HVG!='non')){
                            if(($autre['HVG']=='oui')||($autre['HVG']=='non')){
                                $CardioVasculaireDepart->HVG=$autre['HVG'];
                            }
                        }

                        if($CardioVasculaireDepart->activite==''){
                            $CardioVasculaireDepart->activite=$autre['activite'];
                        }

                        // if(($CardioVasculaireDepart->tabac!='oui')&&($CardioVasculaireDepart->tabac!='non')){
                        // 	if(($autre['tabac']=='oui')||($autre['tabac']=='non')){
                        // 		$CardioVasculaireDepart->tabac=$autre['tabac'];
                        // 		$CardioVasculaireDepart->darret=$autre['darret'];
                        // 		$CardioVasculaireDepart->nbrtabac=$autre['nbrtabac'];
                        // 	}
                        // }


                    }


                    $CardioVasculaireDepart = $CardioVasculaireDepart->afterDeserialisation($account);
                    #echo '<pre>', print_r($CardioVasculaireDepart); echo '</pre>';

                    //Récupération historique des dépistage aomi
                    $liste_historique = $dep_aomi->getHistoriqueDepistage($dossier->id, $account->cabinet);

                    $depistage_aomi = $dep_aomi->getByDIdAndDate($dossier->id, date("Y-m-d", strtotime($CardioVasculaireDepart->date)));

                    forward($this->mappingTable["URL_VIEW_DEPART"]);

                }
                else{
                    exitIfNull($CardioVasculaireDepart);
                    exitIfNullOrEmpty($CardioVasculaireDepart->date);
                    $dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);

                    global $suividiab;

                    $suividiab=$CardioVasculaireDepartMapper->getsuividiab($dossier->id);

                    $CardioVasculaireDepart->id = $dossier->id;
                    $result = $CardioVasculaireDepartMapper->findObject($CardioVasculaireDepart->beforeSerialisation($account));

                    if($result == false)
                    {
                        if($CardioVasculaireDepartMapper->lastError == BAD_MATCH)
                            forward($this->mappingTable["URL_MANAGE"],"Cet enregistrement n'existe pas");
                        else
                            forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
                    }
                    $CardioVasculaireDepart = $result->afterDeserialisation($account);
                    $dsuivi=$result->date;

                    $result=$BiologieMapper->findExam($dsuivi, $dossier->id, "poids");

                    $poids=new Biologie();
                    $poids->date_exam=$result["date_exam"];
                    $poids->resultat1=$result["resultat1"];
                    $poids->id=$dossier->id;
                    $poids->type_exam="poids";
                    $poids->numero=$result["numero"];

                    $result=$BiologieMapper->findExam($dsuivi, $dossier->id, "systole");

                    $systole=new Biologie();
                    $systole->date_exam=$result["date_exam"];
                    $systole->resultat1=$result["resultat1"];
                    $systole->id=$dossier->id;
                    $systole->type_exam="systole";
                    $systole->numero=$result["numero"];

                    $result=$BiologieMapper->findExam($dsuivi, $dossier->id, "diastole");

                    $diastole=new Biologie();
                    $diastole->date_exam=$result["date_exam"];
                    $diastole->resultat1=$result["resultat1"];
                    $diastole->id=$dossier->id;
                    $diastole->type_exam="diastole";
                    $diastole->numero=$result["numero"];

                    $result=$BiologieMapper->findExam($dsuivi, $dossier->id, "type_tension");

                    $type_tension=new Biologie();
                    $type_tension->date_exam=$result["date_exam"];
                    $type_tension->resultat1=$result["resultat1"];
                    $type_tension->id=$dossier->id;
                    $type_tension->type_exam="type_tension";
                    $type_tension->numero=$result["numero"];

                    $result=$BiologieMapper->findExam($dsuivi, $dossier->id, "LDL");

                    $LDL=new Biologie();
                    $LDL->date_exam=$result["date_exam"];
                    $LDL->resultat1=$result["resultat1"];
                    $LDL->id=$dossier->id;
                    $LDL->type_exam="LDL";
                    $LDL->numero=$result["numero"];

                    $result=$BiologieMapper->findExam($dsuivi, $dossier->id, "HDL");

                    $HDL=new Biologie();
                    $HDL->date_exam=$result["date_exam"];
                    $HDL->resultat1=$result["resultat1"];
                    $HDL->id=$dossier->id;
                    $HDL->type_exam="HDL";
                    $HDL->numero=$result["numero"];

                    $liste_exam=array("Chol", "triglycerides", "creat",
                        "kaliemie", "proteinurie", "hematurie",
                        "fond", "ECG", "pouls", "glycemie", "spirometrie");

                    foreach($liste_exam as $exam){
                        $result=$BiologieMapper->findExam($dsuivi, $dossier->id, $exam);

                        $$exam=new Biologie();
                        $$exam->date_exam=$result["date_exam"];
                        $$exam->resultat1=$result["resultat1"];
                        $$exam->resultat2=$result["resultat2"];
                        $$exam->id=$dossier->id;
                        $$exam->type_exam=$exam;
                        $$exam->numero=$result["numero"];
                    }

                    if($param->param1 == PARAM_EDIT)
                    {
                        //Récupération historique des dépistage aomi
                        $liste_historique = $dep_aomi->getHistoriqueDepistage($dossier->id, $account->cabinet);

                        $arrayDate = explode("/", $CardioVasculaireDepart->date);
                        $date = new DateTime($arrayDate[2]. "-" .$arrayDate[1]. "-" .$arrayDate[0]);
                        $depistage_aomi = $dep_aomi->getByDIdAndDate($dossier->id, $date->format('Y-m-d'));

                        forward($this->mappingTable["URL_AFTER_FIND_EDIT"]);
                    }
                    else
                        //Récupération historique des dépistage aomi
                        $liste_historique = $dep_aomi->getHistoriqueDepistage($dossier->id, $account->cabinet);

                    $depistage_aomi = $dep_aomi->getByDIdAndDate($dossier->id, date("Y-m-d", strtotime($CardioVasculaireDepart->date)));

                    forward($this->mappingTable["URL_AFTER_FIND_VIEW"]);
                }
                break;

            case ACTION_NEW:

                exitIfNull($dossier);
                exitIfNull($CardioVasculaireDepart);
                exitIfNullOrEmpty($CardioVasculaireDepart->date);

                $poids->id=$dossier->id;
                $poids->type_exam="poids";

                $spirometrie = new Biologie();
                $spirometrie->id=$dossier->id;
                $spirometrie->type_exam="spirometrie";

                $systole->id=$dossier->id;
                $systole->type_exam="systole";

                $diastole->id=$dossier->id;
                $diastole->type_exam="diastole";

                $type_tension->id=$dossier->id;
                $type_tension->type_exam="type_tension";

                $LDL->id=$dossier->id;
                $LDL->type_exam="LDL";

                $HDL->id=$dossier->id;
                $HDL->type_exam="HDL";

                $liste_exam=array("Chol", "triglycerides", "creat",
                    "kaliemie", "proteinurie", "hematurie",
                    "fond", "ECG", "pouls", "glycemie");

                foreach($liste_exam as $exam){
                    $$exam->id=$dossier->id;
                    $$exam->type_exam=$exam;
                }


                global $complement;
                $complement=false;

                if($param->param1 == PARAM_EDIT){
                    $complement=true;
                }
                if(!isValidDate($CardioVasculaireDepart->date)){
                    if($param->param1==PARAM_EDIT){
                        forward($this->mappingTable["URL_MANAGE_COMPLEMENT"],"La date du dépistage est invalide");
                    }
                    else{
                        forward($this->mappingTable["URL_MANAGE"],"La date du dépistage est invalide");
                    }
                }
                $dossier = checkDossierActif($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);

                $CardioVasculaireDepart->id = $dossier->id;

                $result = $CardioVasculaireDepartMapper->findObject($CardioVasculaireDepart->beforeSerialisation($account));
                if($result == false){
                    if($CardioVasculaireDepartMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"dossier non trouvé");
                }
                else{
                    if($param->param1==PARAM_EDIT){
                        forward($this->mappingTable["URL_MANAGE_COMPLEMENT"],"Cet enregistrement existe déjà");
                    }
                    else{
                        forward($this->mappingTable["URL_MANAGE"],"Cet enregistrement existe déjà");
                    }
                }

                global $suividiab;

                $depdiab=$CardioVasculaireDepartMapper->getdepdiab($dossier->id);
                $suividiab=$CardioVasculaireDepartMapper->getsuividiab($dossier->id);
                $sein=$CardioVasculaireDepartMapper->getsein($dossier->id);
                $colon=$CardioVasculaireDepartMapper->getcolon($dossier->id);
                $uterus=$CardioVasculaireDepartMapper->getuterus($dossier->id);
                $cognitif=$CardioVasculaireDepartMapper->getcognitif($dossier->id);
                $automesure=$CardioVasculaireDepartMapper->getautomesure($dossier->id);

                global $autre_proto;
                $autre_proto="";

                if($depdiab){
                    $autre_proto=$autre_proto."Dépistage diabète, ";
                }
                if($suividiab){
                    $autre_proto=$autre_proto."Suivi diabète, ";
                }
                if($sein){
                    $autre_proto=$autre_proto."Dépistage cancer du sein, ";
                }
                if($colon){
                    $autre_proto=$autre_proto."Dépistage cancer du colon, ";
                }
                if($uterus){
                    $autre_proto=$autre_proto."Dépistage cancer du col utérus, ";
                }
                if($cognitif){
                    $autre_proto=$autre_proto."Dépistage des troubles cognitifs, ";
                }
                if($automesure){
                    $autre_proto=$autre_proto."Automesure tensionnelle";
                }

                if($autre_proto!=""){
                    $autre_proto="Ce patient est inclu dans les protocoles suivants : ".$autre_proto;
                }

                $CardioVasculaireDepart=$CardioVasculaireDepart->beforeSerialisation($account);

                $result2 = $CardioVasculaireDepartMapper->getdernierExamsRCVA($dossier->id);
                if($result2 == false){
                    if($CardioVasculaireDepartMapper->lastError == BAD_MATCH) $result2=0;
                    else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
                }


                if(($result2!=0)||($glycemie!=false)){
                    $resultRCVA = $result2[0];

                    if(!isset($resultRCVA)){
                        $resultRCVA['dpoids']=$resultRCVA['dTA']=$resultRCVA['dCreat']=$resultRCVA['dproteinurie']='0000-00-00';
                        $resultRCVA['dhematurie']=$resultRCVA['dgly']=$resultRCVA['dkaliemie']=$resultRCVA['dHDL']='0000-00-00';
                        $resultRCVA['dLDL']=$resultRCVA['dFond']=$resultRCVA['dECG']=$resultRCVA['exam_cardio']='0000-00-00';
                        $resultRCVA['darret']=$resultRCVA['dpouls']=$resultRCVA['dsokolov']=$resultRCVA['dChol']='0000-00-00';
                        $resultRCVA['dtriglycerides']=$resultRCVA['spirometrie_date']='0000-00-00';
                        $autre_exam="non";
                    }
                    else{
                        $autre_exam="oui";
                    }


                    $pds= $CardioVasculaireDepartMapper->getExam($dossier->id, "poids");

                    $poids->date_exam=$pds[0]["date_exam"];
                    $poids->resultat1=$pds[0]["resultat1"];
                    $CardioVasculaireDepart->dpoids=$pds[0]['dexam'];

                    // $spir = $CardioVasculaireDepartMapper->getExam($dossier->id, "spirometrie");
                    // $spirometrie->date_exam=$spir[0]["date_exam"];
                    // $spirometrie->resultat1=$spir[0]["resultat1"];
                    // $CardioVasculaireDepart->spirometrie_date=$spri[0]['dexam'];

                    $syst= $CardioVasculaireDepartMapper->getExam($dossier->id, "systole");
                    $systole->date_exam=$syst[0]["date_exam"];
                    $systole->resultat1=$syst[0]["resultat1"];
                    $CardioVasculaireDepart->dTA=$syst[0]['dexam'];

                    $dia= $CardioVasculaireDepartMapper->getExam($dossier->id, "diastole");
                    $diastole->date_exam=$dia[0]["date_exam"];
                    $diastole->resultat1=$dia[0]["resultat1"];

                    $mode= $CardioVasculaireDepartMapper->getExam($dossier->id, "type_tension");
                    $type_tension->date_exam=$mode[0]["date_exam"];
                    $type_tension->resultat1=$mode[0]["resultat1"];

                    $ldl= $CardioVasculaireDepartMapper->getExam($dossier->id, "LDL");
                    $LDL->date_exam=$ldl[0]["date_exam"];
                    $LDL->resultat1=$ldl[0]["resultat1"];
                    $CardioVasculaireDepart->dLDL=$ldl[0]['dexam'];

                    $hdl= $CardioVasculaireDepartMapper->getExam($dossier->id, "HDL");
                    $HDL->date_exam=$hdl[0]["date_exam"];
                    $HDL->resultat1=$hdl[0]["resultat1"];
                    $CardioVasculaireDepart->dHDL=$hdl[0]['dexam'];

                    $liste_exam=array("Chol"=>"dChol",
                        "triglycerides"=>"dtriglycerides",
                        "creat"=>"dCreat",
                        "kaliemie"=>"dkaliemie",
                        "proteinurie"=>"dproteinurie",
                        "hematurie"=>"dhematurie",
                        "fond"=>"dFond",
                        "ECG"=>"dECG",
                        "pouls"=>"dpouls",
                        "glycemie"=>"dgly");

                    foreach($liste_exam as $exam=>$nom){
                        $res= $CardioVasculaireDepartMapper->getExam($dossier->id, $exam);
                        $$exam->date_exam=$res[0]["date_exam"];
                        $$exam->resultat1=$res[0]["resultat1"];
                        $CardioVasculaireDepart->$nom=$res[0]["dexam"];
                    }

                    if(($resultRCVA['exam_cardio']>'0000-00-00'))//||($resultHTA['dcoeur']>'0000-00-00'))
                    {
                        $CardioVasculaireDepart->exam_cardio=$resultRCVA['exam_cardio'];
                    }


                    if($resultRCVA['darret']>'0000-00-00'){
                        $CardioVasculaireDepart->darret=$resultRCVA['darret'];
                    }
                    if($resultRCVA['spirometrie_date']>'0000-00-00'){
                        $CardioVasculaireDepart->spirometrie_date=$resultRCVA['spirometrie_date'];
                    }

                    if($resultRCVA['dsokolov']>'0000-00-00'){
                        $CardioVasculaireDepart->dsokolov=$resultRCVA['dsokolov'];
                        $sokolov= $CardioVasculaireDepartMapper->getSokolov($dossier->id, $resultRCVA['dsokolov']);

                        $sokolov=$sokolov[0];
                        $CardioVasculaireDepart->sokolov=$sokolov['sokolov'];
                        $CardioVasculaireDepart->surcharge_ventricule=$sokolov['surcharge_ventricule'];
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
                                $CardioVasculaireDepart->traitement=explode(',',$autre['traitement']);
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

                        $autres= $CardioVasculaireDepartMapper->getHTA($dossier->id);

                        if($autres!=""){
                            foreach($autres as $autre){
                                $CardioVasculaireDepart->HTA=$autre['HTA'];
                            }
                        }

                    }


                }

                $CardioVasculaireDepart = $CardioVasculaireDepart->afterDeserialisation($account);

                if($param->param1==PARAM_EDIT){
                    if($param->param2==PARAM_DEPART){
                        forward($this->mappingTable["URL_NEW_COMPLEMENT_ORGA"]);
                    }
                    else{
                        //Récupération historique des dépistage aomi
                        $liste_historique = $dep_aomi->getHistoriqueDepistage($dossier->id, $account->cabinet);

                        forward($this->mappingTable["URL_NEW_COMPLEMENT"]);
                    }
                }
                else{
                    if($param->param2==PARAM_DEPART){
                        forward($this->mappingTable["URL_NEW_ORGA"]);
                    }
                    else{
                        forward($this->mappingTable["URL_NEW"]);
                    }
                }
                break;

            case ACTION_SAVE:
                exitIfNull($dossier);
                exitIfNull($CardioVasculaireDepart);
                exitIfNullOrEmpty($CardioVasculaireDepart->date);
                // $dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);
                $CardioVasculaireDepart->id = $dossier->id;
                $CardioVasculaireDepart->dpoids=$poids->date_exam;
                $CardioVasculaireDepart->poids=$poids->resultat1;
                $poids->id=$dossier->id;

                $CardioVasculaireDepart->spirometrie_date=$spirometrie->date_exam;
                $CardioVasculaireDepart->spirometrie_rapport_VEMS_CVF=$spirometrie->resultat1;
                $CardioVasculaireDepart->spirometrie_status=$spirometrie->resultat2;
                $spirometrie->id=$dossier->id;

                $CardioVasculaireDepart->dTA=$systole->date_exam;
                $CardioVasculaireDepart->TaSys=$systole->resultat1;
                $systole->id=$dossier->id;

                $CardioVasculaireDepart->TaDia=$diastole->resultat1;
                $diastole->id=$dossier->id;
                $CardioVasculaireDepart->TA_mode=$type_tension->resultat1;
                $type_tension->id=$dossier->id;

                $CardioVasculaireDepart->dLDL=$LDL->date_exam;
                $CardioVasculaireDepart->LDL=$LDL->resultat1;
                $LDL->id=$dossier->id;

                $CardioVasculaireDepart->dHDL=$HDL->date_exam;
                $CardioVasculaireDepart->HDL=$HDL->resultat1;
                $HDL->id=$dossier->id;

                $liste_exam=array("Chol"=>array("val"=>"Chol", "date"=>"dChol"),
                    "triglycerides"=>array("val"=>"triglycerides", "date"=>"dtriglycerides"),
                    "creat"=>array("val"=>"Creat", "date"=>"dCreat"),
                    "kaliemie"=>array("val"=>"kaliemie", "date"=>"dkaliemie"),
                    "proteinurie"=>array("val"=>"proteinurie", "date"=>"dproteinurie"),
                    "hematurie"=>array("val"=>"hematurie", "date"=>"dhematurie"),
                    "fond"=>array("val"=>"", "date"=>"dFond"),
                    "ECG"=>array("val"=>"", "date"=>"dECG"),
                    "pouls"=>array("val"=>"pouls", "date"=>"dpouls"),
                    "glycemie"=>array("val"=>"glycemie", "date"=>"dgly"));

                foreach($liste_exam as $exam=>$vals){
                    if($vals["date"]!=""){
                        $CardioVasculaireDepart->$vals["date"]=$$exam->date_exam;
                    }
                    //E.A.05-06 php5
                    if($vals["val"]!="")
                        $CardioVasculaireDepart->$vals["val"]=$$exam->resultat1;
                    $$exam->id=$dossier->id;
                }

                // print_r($systole);die;
                $errors = $CardioVasculaireDepart->check();

                if(count($errors) !=0) forward($this->mappingTable["URL_NEW"],$errors);

                global $suividiab;

                $suividiab=$CardioVasculaireDepartMapper->getsuividiab($dossier->id);

                if($suividiab){
                    $suividiab=1;
                }
                else{
                    $suividiab=0;
                }

                $result = $dossierMapper->updateObject($dossier->beforeSerialisation($account));
                $result = $CardioVasculaireDepartMapper->findObject($CardioVasculaireDepart->beforeSerialisation($account));

                if($result == false){

                    if(($CardioVasculaireDepartMapper->lastError != BAD_MATCH)&&($CardioVasculaireDepartMapper->lastError!=PARAM)) forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");

                    $result = $CardioVasculaireDepartMapper->createObject($CardioVasculaireDepart->beforeSerialisation($account));

                    if($result == false) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la création");

                    if($poids->date_exam!=""){
                        $result = $BiologieMapper->findExamSaisi($poids->beforeSerialisation($account));

                        $maj=1;
                        if($result!==false){//Un examen a été trouvé.
                            if($result["resultat1"]!=$poids->resultat1){//Le poids est différent=> il faut faire une maj
                                $poids->numero=$result["numero"];
                            }
                            else{//L'exam enregistré est identique=> pas de maj
                                $maj=0;
                            }
                        }

                        if($maj==1){
                            $result = $BiologieMapper->findObject($poids->beforeSerialisation($account));

                            if($result==false){//Aucun poids créé avec le même identifiant
                                $result = $BiologieMapper->createObject($poids->beforeSerialisation($account));
                            }
                            else{//Déjà un poids créé avec le même identifiant=>maj
                                $result = $BiologieMapper->updateObject($poids->beforeSerialisation($account));
                            }
                        }
                    }

                    if($spirometrie->date_exam!=""){
                        $result = $BiologieMapper->findExamSaisi($spirometrie->beforeSerialisation($account));

                        $maj=1;
                        if($result!==false){//Un examen a été trouvé.
                            if($result["resultat1"]!=$spirometrie->resultat1){//Le spirometrie est différent=> il faut faire une maj
                                $spirometrie->numero=$result["numero"];
                            }
                            else{//L'exam enregistré est identique=> pas de maj
                                $maj=0;
                            }
                        }

                        if($maj==1){
                            $result = $BiologieMapper->findObject($spirometrie->beforeSerialisation($account));


                            if($result==false){//Aucun spirometrie créé avec le même identifiant
                                $result = $BiologieMapper->createObject($spirometrie->beforeSerialisation($account));
                            }
                            else{//Déjà un spirometrie créé avec le même identifiant=>maj
                                $result = $BiologieMapper->updateObject($spirometrie->beforeSerialisation($account));
                            }
                        }
                    }

                    if($systole->date_exam!=""){
                        $diastole->date_exam=$systole->date_exam;
                        $type_tension->date_exam=$systole->date_exam;
// print_r($systole);
                        $result = $BiologieMapper->findExamSaisi($systole->beforeSerialisation($account));

                        $maj=1;
                        if($result!==false){//Un examen a été trouvé.
                            if($result["resultat1"]!=$systole->resultat1){//Le poids est différent=> il faut faire une maj
                                $systole->numero=$result["numero"];
                            }
                            else{//L'exam enregistré est identique=> pas de maj
                                $maj=0;
                            }
                        }

                        if($maj==1){
                            $result = $BiologieMapper->findObject($systole->beforeSerialisation($account));

                            if($result==false){//Aucun systole créé avec le même identifiant
                                $result = $BiologieMapper->createObject($systole->beforeSerialisation($account));
                            }
                            else{//Déjà un systole créé avec le même identifiant=>maj
                                $result = $BiologieMapper->updateObject($systole->beforeSerialisation($account));
                            }
                        }

                        $result = $BiologieMapper->findExamSaisi($diastole->beforeSerialisation($account));

                        $maj=1;
                        if($result!==false){//Un examen a été trouvé.
                            if($result["resultat1"]!=$diastole->resultat1){//Le poids est différent=> il faut faire une maj
                                $diastole->numero=$result["numero"];
                            }
                            else{//L'exam enregistré est identique=> pas de maj
                                $maj=0;
                            }
                        }

                        if($maj==1){
                            $result = $BiologieMapper->findObject($diastole->beforeSerialisation($account));

                            if($result==false){//Aucun diastole créé avec le même identifiant
                                $result = $BiologieMapper->createObject($diastole->beforeSerialisation($account));
                            }
                            else{//Déjà un diastole créé avec le même identifiant=>maj
                                $result = $BiologieMapper->updateObject($diastole->beforeSerialisation($account));
                            }
                        }

                        $result = $BiologieMapper->findExamSaisi($type_tension->beforeSerialisation($account));

                        $maj=1;
                        if($result!==false){//Un examen a été trouvé.
                            if($result["resultat1"]!=$type_tension->resultat1){//Le poids est différent=> il faut faire une maj
                                $type_tension->numero=$result["numero"];
                            }
                            else{//L'exam enregistré est identique=> pas de maj
                                $maj=0;
                            }
                        }

                        if($maj==1){
                            $result = $BiologieMapper->findObject($type_tension->beforeSerialisation($account));

                            if($result==false){//Aucun type_tension créé avec le même identifiant
                                $result = $BiologieMapper->createObject($type_tension->beforeSerialisation($account));
                            }
                            else{//Déjà un type_tension créé avec le même identifiant=>maj
                                $result = $BiologieMapper->updateObject($type_tension->beforeSerialisation($account));
                            }
                        }
                    }

                    if($LDL->date_exam!=""){
                        $result = $BiologieMapper->findExamSaisi($LDL->beforeSerialisation($account));

                        $maj=1;
                        if($result!==false){//Un examen a été trouvé.
                            if($result["resultat1"]!=$LDL->resultat1){//Le poids est différent=> il faut faire une maj
                                $LDL->numero=$result["numero"];
                            }
                            else{//L'exam enregistré est identique=> pas de maj
                                $maj=0;
                            }
                        }

                        if($maj==1){
                            $result = $BiologieMapper->findObject($LDL->beforeSerialisation($account));

                            if($result==false){//Aucun poids créé avec le même identifiant
                                $result = $BiologieMapper->createObject($LDL->beforeSerialisation($account));
                            }
                            else{//Déjà un poids créé avec le même identifiant=>maj
                                $result = $BiologieMapper->updateObject($LDL->beforeSerialisation($account));
                            }
                        }
                    }

                    if($HDL->date_exam!=""){
                        $result = $BiologieMapper->findExamSaisi($HDL->beforeSerialisation($account));

                        $maj=1;
                        if($result!==false){//Un examen a été trouvé.
                            if($result["resultat1"]!=$HDL->resultat1){//Le poids est différent=> il faut faire une maj
                                $LDL->numero=$result["numero"];
                            }
                            else{//L'exam enregistré est identique=> pas de maj
                                $maj=0;
                            }
                        }

                        if($maj==1){
                            $result = $BiologieMapper->findObject($HDL->beforeSerialisation($account));

                            if($result==false){//Aucun poids créé avec le même identifiant
                                $result = $BiologieMapper->createObject($HDL->beforeSerialisation($account));
                            }
                            else{//Déjà un poids créé avec le même identifiant=>maj
                                $result = $BiologieMapper->updateObject($HDL->beforeSerialisation($account));
                            }
                        }
                    }

                    foreach($liste_exam as $exam=>$vals){
                        if($$exam->date_exam!=""){
                            $result = $BiologieMapper->findExamSaisi($$exam->beforeSerialisation($account));

                            $maj=1;
                            if($result!==false){//Un examen a été trouvé.
                                if($result["resultat1"]!=$$exam->resultat1){//Le poids est différent=> il faut faire une maj
                                    $$exam->numero=$result["numero"];
                                }
                                else{//L'exam enregistré est identique=> pas de maj
                                    $maj=0;
                                }
                            }

                            if($maj==1){
                                $result = $BiologieMapper->findObject($$exam->beforeSerialisation($account));

                                if($result==false){//Aucun poids créé avec le même identifiant
                                    $result = $BiologieMapper->createObject($$exam->beforeSerialisation($account));
                                }
                                else{//Déjà un poids créé avec le même identifiant=>maj
                                    $result = $BiologieMapper->updateObject($$exam->beforeSerialisation($account));
                                }
                            }
                        }
                    }

                    $depistageAOMI->dossier_id = $dossier->id;
                    $depistageAOMI->dossier_numero = $dossier->numero;
                    $arrayDate = explode("/", $CardioVasculaireDepart->date);
                    $depistageAOMI->dateSaisie = new DateTime($arrayDate[2]. "-" .$arrayDate[1]. "-" .$arrayDate[0]);

//
//                    file_put_contents('php://stderr', print_r("debugAOMIMercredi ", TRUE));
//                    file_put_contents('php://stderr', print_r(" ".gettype($CardioVasculaireDepart->date)." ", TRUE));
//                    file_put_contents('php://stderr', print_r(" ".$CardioVasculaireDepart->date." ", TRUE));




                    if (($depistageAOMI->ipsd != NULL)
                        && ($depistageAOMI->ipsd != 0)
                        && ($depistageAOMI->ipsg != NULL)
                        && ($depistageAOMI->ipsg != 0)
                        && ($depistageAOMI->eda != NULL)
                    ){
                        $depistageAOMI->save();

//                                $d = substr($date, 0, 2);
//                                $m = substr($date, 3, 2);
//                                $y = substr($date, 6, 4);
//                                . $y . "-" . $d . "-" . $m .
//                                file_put_contents('php://stderr', print_r($foo, TRUE));
                    }

                    //Récupération historique des dépistage aomi
                    $liste_historique = $dep_aomi->getHistoriqueDepistage($dossier->id, $account->cabinet);

                    forward($this->mappingTable["URL_AFTER_CREATE"]);
                }
                else{
                    $result = $CardioVasculaireDepartMapper->updateObject($CardioVasculaireDepart->beforeSerialisation($account));

                    if($poids->date_exam!=""){
                        $result = $BiologieMapper->findExamSaisi($poids->beforeSerialisation($account));
                        if($result == false) {
                            if($CardioVasculaireDepartMapper->lastError != NOTHING_UPDATED)
                                forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour");
                        }
                        $maj=1;
                        if($result!==false){//Un examen a été trouvé.
                            if($result["resultat1"]!=$poids->resultat1){//Le poids est différent=> il faut faire une maj
                                $poids->numero=$result["numero"];
                            }
                            else{//L'exam enregistré est identique=> pas de maj
                                $maj=0;
                            }
                        }

                        if($maj==1){
                            $result = $BiologieMapper->findObject($poids->beforeSerialisation($account));

                            if($result==false){//Aucun poids créé avec le même identifiant
                                $result = $BiologieMapper->createObject($poids->beforeSerialisation($account));
                            }
                            else{//Déjà un poids créé avec le même identifiant=>maj
                                $result = $BiologieMapper->updateObject($poids->beforeSerialisation($account));
                            }
                        }
                    }

                    if($spirometrie->date_exam!=""){
                        $result = $BiologieMapper->findExamSaisi($spirometrie->beforeSerialisation($account));
                        if($result == false) {
                            if($CardioVasculaireDepartMapper->lastError != NOTHING_UPDATED)
                                forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour");
                        }
                        $maj=1;
                        if($result!==false){//Un examen a été trouvé.
                            if($result["resultat1"]!=$spirometrie->resultat1){//Le spirometrie est différent=> il faut faire une maj
                                $spirometrie->numero=$result["numero"];
                            }
                            else{//L'exam enregistré est identique=> pas de maj
                                $maj=0;
                            }
                        }

                        if($maj==1){
                            $result = $BiologieMapper->findObject($spirometrie->beforeSerialisation($account));

                            if($result==false){//Aucun spirometrie créé avec le même identifiant
                                $result = $BiologieMapper->createObject($spirometrie->beforeSerialisation($account));
                            }
                            else{//Déjà un spirometrie créé avec le même identifiant=>maj
                                $result = $BiologieMapper->updateObject($spirometrie->beforeSerialisation($account));
                            }
                        }
                    }
                    #echo '<pre>', print_r($_POST); echo '</pre>';exit;


                    if($systole->date_exam!=""){
                        $diastole->date_exam=$systole->date_exam;
                        $type_tension->date_exam=$systole->date_exam;

                        // print_r($systole);
                        $result = $BiologieMapper->findExamSaisi($systole->beforeSerialisation($account));

                        $maj=1;
                        if($result!==false){//Un examen a été trouvé.
                            if($result["resultat1"]!=$systole->resultat1){//Le poids est différent=> il faut faire une maj
                                $systole->numero=$result["numero"];
                            }
                            else{//L'exam enregistré est identique=> pas de maj
                                $maj=0;
                            }
                        }

                        if($maj==1){
                            $result = $BiologieMapper->findObject($systole->beforeSerialisation($account));

                            if($result==false){//Aucun systole créé avec le même identifiant
                                $result = $BiologieMapper->createObject($systole->beforeSerialisation($account));
                            }
                            else{//Déjà un systole créé avec le même identifiant=>maj
                                $result = $BiologieMapper->updateObject($systole->beforeSerialisation($account));
                            }
                        }

                        $result = $BiologieMapper->findExamSaisi($diastole->beforeSerialisation($account));

                        $maj=1;
                        if($result!==false){//Un examen a été trouvé.
                            if($result["resultat1"]!=$diastole->resultat1){//Le poids est différent=> il faut faire une maj
                                $diastole->numero=$result["numero"];
                            }
                            else{//L'exam enregistré est identique=> pas de maj
                                $maj=0;
                            }
                        }

                        if($maj==1){
                            $result = $BiologieMapper->findObject($diastole->beforeSerialisation($account));

                            if($result==false){//Aucun diastole créé avec le même identifiant
                                $result = $BiologieMapper->createObject($diastole->beforeSerialisation($account));
                            }
                            else{//Déjà un diastole créé avec le même identifiant=>maj
                                $result = $BiologieMapper->updateObject($diastole->beforeSerialisation($account));
                            }
                        }

                        $result = $BiologieMapper->findExamSaisi($type_tension->beforeSerialisation($account));

                        $maj=1;
                        if($result!==false){//Un examen a été trouvé.
                            if($result["resultat1"]!=$type_tension->resultat1){//Le poids est différent=> il faut faire une maj
                                $type_tension->numero=$result["numero"];
                            }
                            else{//L'exam enregistré est identique=> pas de maj
                                $maj=0;
                            }
                        }

                        if($maj==1){
                            $result = $BiologieMapper->findObject($type_tension->beforeSerialisation($account));

                            if($result==false){//Aucun type_tension créé avec le même identifiant
                                $result = $BiologieMapper->createObject($type_tension->beforeSerialisation($account));
                            }
                            else{//Déjà un type_tension créé avec le même identifiant=>maj
                                $result = $BiologieMapper->updateObject($type_tension->beforeSerialisation($account));
                            }
                        }
                    }

                    if($LDL->date_exam!=""){
                        $result = $BiologieMapper->findExamSaisi($LDL->beforeSerialisation($account));

                        $maj=1;
                        if($result!==false){//Un examen a été trouvé.
                            if($result["resultat1"]!=$LDL->resultat1){//Le poids est différent=> il faut faire une maj
                                $LDL->numero=$result["numero"];
                            }
                            else{//L'exam enregistré est identique=> pas de maj
                                $maj=0;
                            }
                        }

                        if($maj==1){
                            $result = $BiologieMapper->findObject($LDL->beforeSerialisation($account));

                            if($result==false){//Aucun poids créé avec le même identifiant
                                $result = $BiologieMapper->createObject($LDL->beforeSerialisation($account));
                            }
                            else{//Déjà un poids créé avec le même identifiant=>maj
                                $result = $BiologieMapper->updateObject($LDL->beforeSerialisation($account));
                            }
                        }
                    }

                    if($HDL->date_exam!=""){
                        $result = $BiologieMapper->findExamSaisi($HDL->beforeSerialisation($account));

                        $maj=1;
                        if($result!==false){//Un examen a été trouvé.
                            if($result["resultat1"]!=$HDL->resultat1){//Le poids est différent=> il faut faire une maj
                                $LDL->numero=$result["numero"];
                            }
                            else{//L'exam enregistré est identique=> pas de maj
                                $maj=0;
                            }
                        }

                        if($maj==1){
                            $result = $BiologieMapper->findObject($HDL->beforeSerialisation($account));

                            if($result==false){//Aucun poids créé avec le même identifiant
                                $result = $BiologieMapper->createObject($HDL->beforeSerialisation($account));
                            }
                            else{//Déjà un poids créé avec le même identifiant=>maj
                                $result = $BiologieMapper->updateObject($HDL->beforeSerialisation($account));
                            }
                        }
                    }

                    foreach($liste_exam as $exam=>$vals){
                        if($$exam->date_exam!=""){
                            $result = $BiologieMapper->findExamSaisi($$exam->beforeSerialisation($account));

                            $maj=1;
                            if($result!==false){//Un examen a été trouvé.
                                if($result["resultat1"]!=$$exam->resultat1){//Le poids est différent=> il faut faire une maj
                                    $$exam->numero=$result["numero"];
                                }
                                else{//L'exam enregistré est identique=> pas de maj
                                    $maj=0;
                                }
                            }

                            if($maj==1){
                                $result = $BiologieMapper->findObject($$exam->beforeSerialisation($account));

                                if($result==false){//Aucun poids créé avec le même identifiant
                                    $result = $BiologieMapper->createObject($$exam->beforeSerialisation($account));
                                }
                                else{//Déjà un poids créé avec le même identifiant=>maj
                                    $result = $BiologieMapper->updateObject($$exam->beforeSerialisation($account));
                                }
                            }
                        }
                    }

                    // M-a-jdu dépistage AOMI
                    if ($depistageAOMI->id != null)
                        $depistageAOMI->update();

                    //Récupération historique des dépistage aomi
                    $liste_historique = $dep_aomi->getHistoriqueDepistage($dossier->id, $account->cabinet);

                    forward($this->mappingTable["URL_AFTER_UPDATE"]);
                }
                break;

            case ACTION_DELETE:

                exitIfNull($dossier);
                exitIfNull($CardioVasculaireDepart);
                exitIfNullOrEmpty($CardioVasculaireDepart->date);

                $dossier = checkDossier($dossier,$dossierMapper,$account->cabinet);

                $CardioVasculaireDepart->id = $dossier->id;

                $result = $CardioVasculaireDepartMapper->deleteObject($CardioVasculaireDepart->beforeSerialisation($account));
                if($result == false){
                    if($CardioVasculaireDepartMapper->lastError != NOTHING_DELETED) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Delete object caused an error");
                }

                //Suppression de toutes les saisies dépistages AOMI liées au suivi
                $depistageAOMI->dossier_id = $dossier->id;
                $depistageAOMI->dateSaisie = date("Y-m-d", strtotime($depistageAOMI->dateSaisie));
                $depistageAOMI->deleteByProvenanceAndDate();

                forward($this->mappingTable["URL_AFTER_DELETE"]);


            case ACTION_LIST:
                set_time_limit(1200);//EA
                switch($param->param1){
                    case PARAM_LIST_BY_DOSSIER:
                        $result = $CardioVasculaireDepartMapper->getObjectsByDossier($account->cabinet, $dossier->numero);
                        if($result == false){
                            if($CardioVasculaireDepartMapper->lastError == BAD_MATCH) forward(new ControlerParams($param->controler,ACTION_MANAGE,true),"Pas d'enregistrements trouvés");
                            else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
                        }
                        global $rowsList;
                        $rowsList = array_natsort($result,"numero","numero");

                        forward($this->mappingTable["URL_AFTER_FIND_LIST_DOSSIER"]);
                    case PARAM_OUTDATED:
                        exitIfNull($outDateReference);

                        $result = $CardioVasculaireDepartMapper->getExpiredExams($account->cabinet,$outDateReference->period);
                        if($result == false){
                            if($CardioVasculaireDepartMapper->lastError == BAD_MATCH) forward($this->mappingTable["URL_MANAGE_OUTDATED"],"Pas d'enregistrements trouvés");
                            else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
                        }
                        else
                            if(count($result)==0) forward($this->mappingTable["URL_MANAGE_OUTDATED"],"Pas d'enregistrements trouvés");

                        global $rowsList;
                        $rowsList = array_natsort($result,"numero","numero");

                        foreach($rowsList as $pos=>$donnees){
                            $id=$donnees["id"];

                            $liste_exam=array("systole"=>"dTA",
                                "pouls"=>"dpouls",
                                "fond"=>"dFond",
                                "ECG"=>"dECG",
                                "hematurie"=>"dhematurie",
                                "proteinurie"=>"dproteinurie",
                                "kaliemie"=>"dkaliemie",
                                "creat"=>"dCreat",
                                "HDL"=>"dHDL",
                                "LDL"=>"dLDL",
                                "Chol"=>"dChol",
                                "glycemie"=>"dgly",
                                "triglycerides"=>"dtriglycerides",
                                "poids"=>"dpoids",
                                "spirometrie"=>"spirometrie_date"
                            );

                            foreach($liste_exam as $code=>$champ){
                                $result=$BiologieMapper->findExam(date("Y-m-d"), $id, $code);
                                if(strpos($result["date_exam"],"/")!==false){
                                    $result["date_exam"]=explode("/", $result["date_exam"]);
                                    $result["date_exam"]=$result["date_exam"][2]."-".$result["date_exam"][1]."-".$result["date_exam"][0];
                                }
                                $rowsList[$pos][$champ]=$result["date_exam"];
                            }
                        }


                        for($i=0;$i<count($rowsList);$i++){
                            $result = $CardioVasculaireDepartMapper->getdernierRappel($rowsList[$i]['id'], $rowsList[$i]['date']);

                            if($result == false){
                                if($CardioVasculaireDepartMapper->lastError == BAD_MATCH) $result=0;
                                else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
                            }

                            $rowsList[$i]['sortir_rappel']=$result[0]['sortir_rappel'];

                        }

                        forward($this->mappingTable["URL_AFTER_LIST_OUTDATED"]);
                        break;

                    case PARAM_DEPART:
                        $result = $CardioVasculaireDepartMapper->getObjectsByCabinet($account->cabinet);
                        if($result == false){
                            if($CardioVasculaireDepartMapper->lastError == BAD_MATCH) forward(new ControlerParams($param->controler,ACTION_MANAGE,true),"Pas d'enregistrements trouvés");
                            else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
                        }
                        global $rowsList;
                        $rowsList = array_natsort($result,"numero","numero");

                        global $depart;

                        $depart=true;

                        forward($this->mappingTable["URL_AFTER_LIST_DEPART"]);

                    case PARAM_SPIRO:
                        $result = $CardioVasculaireDepartMapper->getObjectsSpiroByCabinet($account->cabinet);
                        if($result == false){
                            if($CardioVasculaireDepartMapper->lastError == BAD_MATCH) forward(new ControlerParams($param->controler,ACTION_MANAGE,true),"Pas d'enregistrements trouvés");
                            else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
                        }
                        global $rowsList;
                        $rowsList = array_natsort($result,"numero","numero");

                        global $depart;

                        $depart=true;

                        forward($this->mappingTable["URL_AFTER_LIST_SPIRO"]);

                    default:

                        $result = $CardioVasculaireDepartMapper->getObjectsByCabinet($account->cabinet);
                        if($result == false){
                            if($CardioVasculaireDepartMapper->lastError == BAD_MATCH) forward(new ControlerParams($param->controler,ACTION_MANAGE,true),"Pas d'enregistrements trouvés");
                            else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
                        }
                        global $rowsList;
                        $rowsList = array_natsort($result,"numero","numero");

                        global $depart;
                        $depart=false;

                        forward($this->mappingTable["URL_AFTER_LIST"]);
                }


            case ACTION_GRAPH:
                forward($this->mappingTable["URL_AFTER_COMPLETUDE"]);


            default:
                echo("ACTION IS NULL");
                break;
        }
    }

}
?>

