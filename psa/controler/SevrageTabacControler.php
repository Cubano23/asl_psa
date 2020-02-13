<?php 


	require_once("global/config.php");	
	require_once("bean/SevrageTabac.php");
	require_once("bean/Dossier.php");
	require_once("bean/ControlerParams.php");
	require_once("bean/DepistageAOMI.php");
	require_once("persistence/SevrageTabacMapper.php");
	require_once("persistence/ConnectionFactory.php");
	require_once("persistence/AccountMapper.php");
	require_once("persistence/DossierMapper.php");
	require_once("persistence/CardioVasculaireDepartMapper.php");
	
	require_once("controler/DiagnosticEducatifControler.php");



	#require_once("bean/EvalContinue.php");
	
	require_once("GenericControler.php");
	require_once("persistence/EvaluationInfirmierMapper.php");




	class SevrageTabacControler extends GenericControler{
	
		var $mappingTable;
        var $suppression;
		
		function SevrageTabacControler() {
			$this->mappingTable = 
			array(
				"URL_MANAGE"=>"view/sevragetabac/manageSevragetabac.php",
				"URL_MANAGE_CONSULT"=>"view/sevragetabac/manageSevragetabac.php",
				"URL_NEW"=>"view/sevragetabac/newSevragetabac.php",
	
				"URL_AFTER_CREATE"=>"view/sevragetabac/viewSevragetabac.php",
				"URL_AFTER_UPDATE"=>"view/sevragetabac/viewSevragetabac.php",

				"URL_VIEW"=>"view/sevragetabac/viewSevragetabac.php",
				"URL_EDIT"=>"view/sevragetabac/newSevragetabac.php",
				"URL_DOCS1"=>"view/sevragetabac/documents_support_sevragetabac.php",
				"URL_ON_CALLBACK_FAIL"=>"view/",

				"URL_AFTER_FIND_HISTORIQUE_TABAC_TOOLTIP"=>"view/sevragetabac/historique_valeur_sevrage_tabac_tooltip.php",

                "URL_AFTER_LIST" => "view/sevragetabac/list_sevragetabac.php",
                "URL_AFTER_FIND_LIST_DOSSIER" => "view/sevragetabac/list_dossier_sevragetabac.php",
			);

		}


		function start() {
			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;
			global $dossier;
			global $evaluationInfirmier;
			global $sevragetabac;	
			global $diagnosticEducatif;

            global $depistageAOMI;
            global $depistage_aomi;
            global $liste_historique;

            $dep_aomi = new DepistageAOMI();
	
			if(array_key_exists("SevrageTabac",$objects))
				$SevrageTabac = $objects["SevrageTabac"];
			
			global $dossier;
			if(array_key_exists("dossier",$objects))
				$dossier = $objects["dossier"];	

			if(array_key_exists("evaluationInfirmier",$objects)){
				$EvaluationInfirmier = $objects["evaluationInfirmier"];#echo 'rempli';
			}
			//echo '<pre>'; var_dump($objects);exit();

            if(array_key_exists("DepistageAOMI",$objects))
                $depistageAOMI = $objects["DepistageAOMI"];
			

			//Create connection factory
			$cf = new ConnectionFactory();

			//create mappers
			$dossierMapper = new DossierMapper($cf->getConnection());
			$SevrageTabacMapper = new SevrageTabacMapper($cf->getConnection());
			$EvaluationInfirmierMapper = new EvaluationInfirmierMapper($cf->getConnection());
			
			#var_dump($SevrageTabacMapper);

			#$ledger->writeArray(I,"Start","Control Parameters = ",$param);
			switch($param->action){
				
				case ACTION_MANAGE:
					// chargement de la page de selection du dossier et de la date de consultation
					#echo 'manage';exit();
					$dossier = new Dossier();
					$sevragetabac = new SevrageTabac();					
					$evaluationInfirmier = new evaluationInfirmier();
					

					// on est pas en edit, donc on va sélectionner un nouveau dossier
					$sevragetabac->date= date("d/m/Y");
					#echo 'manage';
					forward($this->mappingTable["URL_MANAGE"]);

					break;


				case ACTION_NEW:
					//echo "ok"; exit();
					// l'idée est que cette page charge la page de formulaire, vide ou pré-remplit
					#var_dump($_POST);
					#exitIfNull($dossier);
					#exitIfNull($EvaluationInfirmier);
					#exitIfNullOrEmpty($EvaluationInfirmier->date);
					
					// if($param->param1 == PARAM_EDIT) {
					// 	echo "oco"; exit();
					// }

					// nouvel enregistrement, il faut vérifier qu'il y a pas déjà un enregistrement sur cette date/dossier
					
					// dans tous les cas on récup le dossier
					$dossier->id = $_POST['Dossier:dossier:id'];
					if($dossier->id==''){
						$dossier->id = $_REQUEST['Dossier:dossier:id'];
					}

					$sevragetabac = new SevrageTabac();#var_dump($SevrageTabac);
					#var_dump($_POST);
					$sevragetabac->date = $_POST['SevrageTabac:sevragetabac:date'];
					if($sevragetabac->date==''){
						$sevragetabac->date = $_REQUEST['SevrageTabac:sevragetabac:date'];
					}
					#var_dump($SevrageTabac);
					$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);
					
					// estce qu'il y a un sevrage qui existe avec le dossier et la date
					$sevrageExist = $SevrageTabacMapper->sevrageExist(dateToMysqlDate($sevragetabac->date),$dossier->id);

					// diagnostic educatif est lié au dossier mais pas à la date de la consultation
					// il est suivi de consult en consult
					$diagnosticEducatifMapper = new DiagnosticEducatifMapper($cf->getConnection());
					
					$lastDiag = $diagnosticEducatifMapper->getLast($dossier->id,'sevrage_tabac');
					#var_dump($lastDiag);
					$diagnosticEducatif = DiagnosticEducatifMapper::getObjectToObject($lastDiag);
					#var_dump($diagnosticEducatif);

					if($param->param1 == PARAM_EDIT && !$sevrageExist){
						forward($this->mappingTable["URL_MANAGE_CONSULT"],"Pas d'enregistrements trouvés");
					}

					if($param->param1 == PARAM_EDIT || $sevrageExist){
						#echo 'le suivi_sevrage existe sur cette date/dossier';
						
						// recup de l'evaluation infirmier
						if($sevrageExist){
							$evaluationInfirmier = new EvaluationInfirmier();
							$evaluationInfirmier->date = $sevrageExist->date;
						}else{
							$evaluationInfirmier = new EvaluationInfirmier();
							$evaluationInfirmier->date = $_POST['SevrageTabac:sevragetabac:date'];
						}
						
						
						#var_dump($_POST);
						$evaluationInfirmierMapper = new evaluationInfirmierMapper();
						$evaluationInfirmier = $evaluationInfirmierMapper->getObjectByDateAndNumero($evaluationInfirmier->date,$dossier->id);
						//echo '<pre>'; var_dump($evaluationInfirmier);exit();

						if($sevrageExist){
							#echo '@1';
							$sevrage= $sevrageExist;
							$sevragetabac = new SevrageTabac();
							$sevragetabac = self::createObjectFromDatabase($sevrage);
							// recuparation du diagnostic
							#$diagnosticEducatif = new DiagnosticEducatif();
							#$diagnosticEducatif = $lastDiagEduc;

							$evaluationInfirmier = EvaluationInfirmierMapper::getObjectToObject($evaluationInfirmier);
							$evaluationInfirmier->type_consultation = explode(",",$evaluationInfirmier->type_consultation);
							#var_dump($evaluationInfirmier);

						}
						else{
							#echo'@2';
							$sevrage= $SevrageTabacMapper->sevrageExist(dateToMysqlDate($sevragetabac->date),$dossier->id);
							$sevragetabac = new SevrageTabac();
							$sevragetabac = self::createObjectFromDatabase($sevrage);
							$evaluationInfirmier = EvaluationInfirmierMapper::getObjectToObject($evaluationInfirmier);
							$evaluationInfirmier->type_consultation = explode(",",$evaluationInfirmier->type_consultation);
							// recuparation du diagnostic
							#$diagnosticEducatif = new DiagnosticEducatif();
							#$diagnosticEducatif = $lastDiagEduc;


						}
						
						if($param->param1 == "" && $sevrageExist){
							forward($this->mappingTable["URL_NEW"],"Cet enregistrement existe déjà");
						}
						else {
                            //Récupération historique des dépistage aomi
                            $liste_historique = $dep_aomi->getHistoriqueDepistage($dossier->id, $account->cabinet);

                            $depistage_aomi = $dep_aomi->getByDIdAndDate($dossier->id, date("Y-m-d", strtotime($sevrageExist->date)));

						//	echo "@@ ICI"; exit();
							forward($this->mappingTable["URL_NEW"], $str);
						}

						break;

					}else{

						$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);
						$sevragetabac = new SevrageTabac();
						$evaluationInfirmier = new EvaluationInfirmier();
						$sevragetabac->date= $_POST['SevrageTabac:sevragetabac:date'];
						$sevragetabac->numero = $dossier->id;
						// recuparation du diagnostic
						#$diagnosticEducatif = new DiagnosticEducatif();
						#$diagnosticEducatif = $lastDiagEduc;
					}


					// verif si evaluation existe ou pas
					if(!$EvaluationInfirmier){
						$EvaluationInfirmier = new EvaluationInfirmier();
						$EvaluationInfirmier->date = dateToMysqlDate($_POST['SevrageTabac:sevragetabac:date']);
						$EvaluationInfirmier->id = $dossier->id;
						$evaluationInfirmier->type_consultation = array('sevrage_tabac');
					}
					$result = $EvaluationInfirmierMapper->findObject($EvaluationInfirmier->beforeSerialisation($account));	
					#var_dump($result);
					if($result){
						$evaluationInfirmier = $result;
						#$evaluationInfirmier->type_consultation = explode(",",$result->type_consultation);
							
					}
					else{
						$evaluationInfirmier->date= $_POST['SevrageTabac:sevragetabac:date'];
					}
				
					global $last_consult;
					$list_all_consult = $SevrageTabacMapper->listSevragesByDossier($dossier->id);
					if(count($list_all_consult) > 0) {
						$last_consult = $list_all_consult[count($list_all_consult) - 1];
						$sevragetabac->tabac = $last_consult->tabac;
						$sevragetabac->nbrtabac = $last_consult->nbrtabac;
						$sevragetabac->ddebut = $last_consult->ddebut;
						$sevragetabac->darret = ($last_consult->darret != '' && $last_consult->darret != '0000-00-00') ? date('d/m/Y', strtotime($last_consult->darret)) : '';
						$sevragetabac->type_tabac = $last_consult->type_tabac;
						$sevragetabac->spirometrie_date = ($last_consult->spirometrie_date != '' && $last_consult->spirometrie_date != '0000-00-00') ? date('d/m/Y', strtotime($last_consult->spirometrie_date)) : '';
						$sevragetabac->spirometrie_CVF = $last_consult->spirometrie_CVF;
						$sevragetabac->spirometrie_VEMS = $last_consult->spirometrie_VEMS;
						$sevragetabac->spirometrie_DEP = $last_consult->spirometrie_DEP;
						$sevragetabac->spirometrie_status = $last_consult->spirometrie_status;
						$sevragetabac->dco_test = ($last_consult->dco_test != '' && $last_consult->dco_test != '0000-00-00') ? date('d/m/Y', strtotime($last_consult->dco_test)) : '';
						$sevragetabac->co_ppm = $last_consult->co_ppm;
						$sevragetabac->fagerstrom = $last_consult->fagerstrom;
						$sevragetabac->horn_stimulation = $last_consult->horn_stimulation;
						$sevragetabac->horn_plaisir = $last_consult->horn_plaisir;
						$sevragetabac->horn_relaxation = $last_consult->horn_relaxation;
						$sevragetabac->horn_anxiete = $last_consult->horn_anxiete;
						$sevragetabac->horn_besoin = $last_consult->horn_besoin;
						$sevragetabac->horn_habitude = $last_consult->horn_habitude;
						$sevragetabac->had_anxiete = $last_consult->had_anxiete;
						$sevragetabac->had_depression = $last_consult->had_depression;
						$sevragetabac->echelle_analogique = $last_consult->echelle_analogique;
						$sevragetabac->echelle_confiance = $last_consult->echelle_confiance;
						$sevragetabac->stade_motivationnel = $last_consult->stade_motivationnel;
						$sevragetabac->poids = $last_consult->poids;
						$sevragetabac->dpoids = ($last_consult->dpoids != '' && $last_consult->dpoids != '0000-00-00') ? date('d/m/Y', strtotime($last_consult->dpoids)) : '';
						$sevragetabac->activite = $last_consult->activite;
						$sevragetabac->alcool = $last_consult->alcool;
					}
					else {
						$last_consult = array();
					}

                    //Récupération historique des dépistage aomi
                    $liste_historique = $dep_aomi->getHistoriqueDepistage($dossier->id, $account->cabinet);

					#var_dump($diagnosticEducatif);
					forward($this->mappingTable["URL_NEW"]);
				break;

				case ACTION_SAVE:
					// enregistrement des données en insert ou update puis load de la page de visualisation
					#var_dump($_POST);
					//var_dump($EvaluationInfirmier);
						
					$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);
					//echo "<pre>"; var_dump($dossier); exit();
// 					if(!$EvaluationInfirmier){
// //echo 'ixi';exit();
// 						$EvaluationInfirmier = new EvaluationInfirmier();
// 						$EvaluationInfirmier->id=$dossier->id;
// 						$EvaluationInfirmier->date=$SevrageTabac->date;
// 					}
					
//					echo '<pre>'; var_dump($SevrageTabac);exit();
					
					$sevrage = new SevrageTabac();
					if(isset($_POST['SevrageTabac:sevragetabac:id'])){
						$sevrage->id = $_POST['SevrageTabac:sevragetabac:id'];
					}

					$sevrage->date = $_POST['SevrageTabac:sevragetabac:date'];
					#var_dump($sevrage);exit;
					$EvaluationInfirmier->id=$dossier->id;
					$EvaluationInfirmier->date = $_POST['EvaluationInfirmier:evaluationInfirmier:date'];
					$EvaluationInfirmier->id_utilisateur = $EvaluationInfirmier->getUserIdByLogin($_POST['evaluationInfirmier:evaluationInfirmier:id_utilisateur']);
					$EvaluationInfirmier->id_cabinet = $EvaluationInfirmier->getCabIdByCab($_POST['evaluationInfirmier:evaluationInfirmier:id_cabinet']);
					#var_dump($EvaluationInfirmier);
					$sevrage = self::createObjectFromPost($sevrage);
					

					#$diagnosticEducatif = new diagnosticEducatif();
					#var_dump($diagnosticEducatif);exit;	

					
					
					#var_dump($sevrage);exit;
					if($sevrage->id==''){
						#echo 'add';
						$sevrage->id = $SevrageTabacMapper->createSevrageTabac($sevrage);
						$sevrage = $SevrageTabacMapper->sevrageExist($sevrage->date,$dossier->id);
						$sevragetabac = self::createObjectFromDatabase($sevrage);
						
						// verification si une evaluation existe ou non 
						$result = $EvaluationInfirmierMapper->findObject($EvaluationInfirmier->beforeSerialisation($account));	
						if($result){
							//echo ' evaluation exist on updt';exit();
							$EvaluationInfirmierMapper = new evaluationInfirmierMapper($cf->getConnection());
							$result = $EvaluationInfirmierMapper->updateObject($EvaluationInfirmier->beforeSerialisation($account));	
						}
						else{
							//echo ' evaluation a creer<pre>';var_dump($EvaluationInfirmier->beforeSerialisation($account));exit();
							// creation de 
							$EvaluationInfirmier->date = $sevrage->date;
							$EvaluationInfirmier->id = $sevrage->numero;
							$result = $EvaluationInfirmierMapper->createObject($EvaluationInfirmier->beforeSerialisation($account));	
						
						}
						
						$diagnosticEducatif->aspects_limitants = addslashes($_POST['DiagnosticEducatif:diagnosticEducatif:aspects_limitants']);
						$diagnosticEducatif->aspects_facilitants = addslashes($_POST['DiagnosticEducatif:diagnosticEducatif:aspects_facilitants']);
						$diagnosticEducatif->objectifs_patient = addslashes($_POST['DiagnosticEducatif:diagnosticEducatif:objectifs_patient']);
						
						$diagExist = DiagnosticEducatifMapper::getLast($dossier->id,'sevrage_tabac');


						if($diagExist){
							$diagnosticEducatif->id_dossier = $dossier->id;
							$diagnosticEducatif->type = 'sevrage_tabac';
							$diagnosticEducatif->statut = '1';
							$diagnosticEducatifMapper = new DiagnosticEducatifMapper($cf->getConnection());
							$diagnosticEducatifMapper->update($diagnosticEducatif);#echo 'updt D';
						}
						else{
							$diagnosticEducatifMapper = new DiagnosticEducatifMapper($cf->getConnection());
							$diagnosticEducatif->id_dossier = $dossier->id;
							$diagnosticEducatif->type = 'sevrage_tabac';
							#var_dump($diagnosticEducatif);exit;
							#echo 'add D';
							$diagnosticEducatifMapper->add($diagnosticEducatif);
						
						}
//exit();

					}
					else{
						//echo 'updt';exit();
						
						$SevrageTabacMapper->updateSevrageTabac($sevrage);
						#var_dump($sevrage);echo'<p>';
						$sevrage = $SevrageTabacMapper->sevrageExist($sevrage->date,$dossier->id);
						$sevragetabac = self::createObjectFromDatabase($sevrage);
						
						#$EvaluationInfirmier->check();
						//echo '<pre>'; var_dump($EvaluationInfirmier);exit();
						$EvaluationInfirmierMapper = new evaluationInfirmierMapper($cf->getConnection());
						$result = $EvaluationInfirmierMapper->updateObject($EvaluationInfirmier->beforeSerialisation($account));	
						
						//recharge les datas pour les afficher dans la vue
						
						$evaluationInfirmierMapper = new evaluationInfirmierMapper($cf->getConnection());
						$ladate = $sevrage->date;
						$evaluationInfirmier = $evaluationInfirmierMapper->getObjectByDateAndNumero($ladate,$dossier->id);
						$evaluationInfirmier = EvaluationInfirmierMapper::getObjectToObject($evaluationInfirmier);
						
						$diagnosticEducatifMapper = new DiagnosticEducatifMapper($cf->getConnection());	
						$lastDiag = $diagnosticEducatifMapper->getLast($dossier->id,'sevrage_tabac');
						#var_dump($lastDiag);exit;
						$diagnosticEducatif = DiagnosticEducatifMapper::getObjectToObject($lastDiag);
						#var_dump($diagnosticEducatif);

						if($_POST){
							#echo '<p>POST</p>';
							// on post le formulaire donc un met à jour le diagnostic
							$diagnosticEducatif->aspects_limitants = addslashes($_POST['DiagnosticEducatif:diagnosticEducatif:aspects_limitants']);
							$diagnosticEducatif->aspects_facilitants = addslashes($_POST['DiagnosticEducatif:diagnosticEducatif:aspects_facilitants']);
							$diagnosticEducatif->objectifs_patient = addslashes($_POST['DiagnosticEducatif:diagnosticEducatif:objectifs_patient']);
							
							if(!is_null($diagnosticEducatif->statut)){
								$diagnosticEducatifMapper->update($diagnosticEducatif);#echo 'updt D2';
							}
							else{
								$diagnosticEducatifMapper->add($diagnosticEducatif);#echo 'add D2';
							
							}
						}

                        // M-a-jdu dépistage AOMI
                        $depistageAOMI->update();

                        //Récupération historique des dépistage aomi
                        $liste_historique = $dep_aomi->getHistoriqueDepistage($dossier->id, $account->cabinet);

						forward($this->mappingTable["URL_AFTER_UPDATE"]);
						#var_dump($sevrage);
					}

					// récupération des datas avant load de la page de visualisation					
					$evaluationInfirmierMapper = new evaluationInfirmierMapper();
					$evaluationInfirmier = $evaluationInfirmierMapper->getObjectByDateAndNumero(dateToMysqlDate($sevragetabac->date),$sevrage->numero);
					
					$diagnosticEducatifMapper = new DiagnosticEducatifMapper($cf->getConnection());	
					$lastDiag = $diagnosticEducatifMapper->getLast($dossier->id,'sevrage_tabac');
					#var_dump($lastDiag);exit;
					$diagnosticEducatif = DiagnosticEducatifMapper::getObjectToObject($lastDiag);


                    // Enregistrement du dépistage AOMI
                    $depistageAOMI->dossier_id = $dossier->id;
                    $depistageAOMI->dossier_numero = $dossier->numero;
                    $depistageAOMI->dateSaisie = new DateTime($sevrage->date);

                    file_put_contents('php://stderr', print_r("debugAOMIMercredi ", TRUE));
                    file_put_contents('php://stderr', print_r(" ".gettype($sevrage->date)." ", TRUE));
                    file_put_contents('php://stderr', print_r(" ".$sevrage->date." ", TRUE));

                    if (($depistageAOMI->ipsd != NULL) && ($depistageAOMI->ipsd != 0) && ($depistageAOMI->ipsg != NULL) && ($depistageAOMI->ipsg != 0) && ($depistageAOMI->eda != NULL))
                        $depistageAOMI->save();

                    //Récupération historique des dépistage aomi
                    $liste_historique = $dep_aomi->getHistoriqueDepistage($dossier->id, $account->cabinet);

					forward($this->mappingTable["URL_AFTER_UPDATE"]);

					break;

				case ACTION_FIND: 

					//Vàrifications  sur certaines variable est exit si variable non conforme
					if(!$param->isParam1Valid()) forward(URL_CONTROLER_PARAMS_ERROR,"Invalid param1");
					exitIfNull($dossier);
					$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet);
					
					$dossier->id = $_POST['Dossier:dossier:id'];
					if($dossier->id==''){
						$dossier->id = $_REQUEST['Dossier:dossier:id'];
					}

					$result = $SevrageTabacMapper->findObject($sevragetabac->beforeSerialisation($account));
					if($result == false){
						if($SevrageTabacMapper->lastError == BAD_MATCH){
							if($param->param2==PARAM_VIEW){
								forward($this->mappingTable["URL_MANAGE_PRE_CREATE"],"Pas d'enregistrement trouvà");
							}
							else {
								forward($this->mappingTable["URL_MANAGE_CONSULT"],"Pas d'enregistrements trouvés");
							}
						}
						else {
							forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
						}
					}

					$sevragetabac = new SevrageTabac();#var_dump($SevrageTabac);
					#var_dump($_POST);
					$sevragetabac->date = $_POST['SevrageTabac:sevragetabac:date'];
					if($sevragetabac->date==''){
						$sevragetabac->date = $_REQUEST['SevrageTabac:sevragetabac:date'];
					}
					#var_dump($SevrageTabac);
					$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);
					
					// estce qu'il y a un sevrage qui existe avec le dossier et la date
					$sevrageExist = $SevrageTabacMapper->sevrageExist(dateToMysqlDate($sevragetabac->date),$dossier->id);
	
					
					// diagnostic educatif est lié au dossier mais pas à la date de la consultation
					// il est suivi de consult en consult
					$diagnosticEducatifMapper = new DiagnosticEducatifMapper($cf->getConnection());
					
					$lastDiag = $diagnosticEducatifMapper->getLast($dossier->id,'sevrage_tabac');
					#var_dump($lastDiag);
					$diagnosticEducatif = DiagnosticEducatifMapper::getObjectToObject($lastDiag);
					#var_dump($diagnosticEducatif);
					

					
					if($param->param1 == PARAM_EDIT){
						#echo 'le suivi_sevrage existe sur cette date/dossier';
						
						// recup de l'evaluation infirmier
						if($sevrageExist){
							$evaluationInfirmier = new EvaluationInfirmier();
							$evaluationInfirmier->date = $sevrageExist->date;
						}else{
							$evaluationInfirmier = new EvaluationInfirmier();
							$evaluationInfirmier->date = $_POST['SevrageTabac:sevragetabac:date'];
						}
						
						
						#var_dump($_POST);
						$evaluationInfirmierMapper = new evaluationInfirmierMapper();
						$evaluationInfirmier = $evaluationInfirmierMapper->getObjectByDateAndNumero($evaluationInfirmier->date,$dossier->id);
						#var_dump($evaluationInfirmier);

						if($sevrageExist){
							#echo '@1';
							$sevrage= $sevrageExist;
							$sevragetabac = new SevrageTabac();
							$sevragetabac = self::createObjectFromDatabase($sevrage);
							// recuparation du diagnostic
							#$diagnosticEducatif = new DiagnosticEducatif();
							#$diagnosticEducatif = $lastDiagEduc;

							$evaluationInfirmier = EvaluationInfirmierMapper::getObjectToObject($evaluationInfirmier);
							$evaluationInfirmier->type_consultation = explode(",",$evaluationInfirmier->type_consultation);
							#var_dump($evaluationInfirmier);

						}
						else{
							#echo'@2';
							$sevrage= $SevrageTabacMapper->sevrageExist(dateToMysqlDate($sevragetabac->date),$dossier->id);
							$sevragetabac = new SevrageTabac();
							$sevragetabac = self::createObjectFromDatabase($sevrage);
							$evaluationInfirmier = EvaluationInfirmierMapper::getObjectToObject($evaluationInfirmier);
							$evaluationInfirmier->type_consultation = explode(",",$evaluationInfirmier->type_consultation);
							// recuparation du diagnostic
							#$diagnosticEducatif = new DiagnosticEducatif();
							#$diagnosticEducatif = $lastDiagEduc;


						}
						

						//forward($this->mappingTable["URL_NEW"]);break;

					}

					// verif si evaluation existe ou pas
					if(!$EvaluationInfirmier){
						$EvaluationInfirmier = new EvaluationInfirmier();
						$EvaluationInfirmier->date = dateToMysqlDate($_POST['SevrageTabac:sevragetabac:date']);
						$EvaluationInfirmier->id = $dossier->id;
						$evaluationInfirmier->type_consultation = array('sevrage_tabac');
					}
					$result = $EvaluationInfirmierMapper->findObject($EvaluationInfirmier->beforeSerialisation($account));	
					#var_dump($result);
					if($result){
						$evaluationInfirmier = $result;
						#$evaluationInfirmier->type_consultation = explode(",",$result->type_consultation);
							
					}
					else{
						$evaluationInfirmier->date= $_POST['SevrageTabac:sevragetabac:date'];
					}

                    //Récupération historique des dépistage aomi
                    $liste_historique = $dep_aomi->getHistoriqueDepistage($dossier->id, $account->cabinet);

                    $depistage_aomi = $dep_aomi->getByDIdAndDate($dossier->id, date("Y-m-d", strtotime($sevragetabac->date)));

					//var_dump($diagnosticEducatif);
					forward($this->mappingTable["URL_EDIT"]);


					break;

				case ACTION_LIST:
					
					switch($param->param1)
					{
						case PARAM_LIST_BY_DOSSIER_TOOLTIP_FOR_BILAN:
							
							$result_sevrage_tabac = $SevrageTabacMapper->getObjectsByDossier($account->cabinet, $dossier->numero);
							//
							$rcva = new CardioVasculaireDepartMapper($cf->getConnection());
							$result_rcva = $rcva->getObjectsByDossier($account->cabinet, $dossier->numero);
							//
							$result = array();
							foreach ($result_sevrage_tabac as $value) {
								$result[$value['date']] = $value;
							}
							foreach ($result_rcva as $value) {
								if(!isset($result[$value['date']])) {
									$result[$value['date']] = $value;
								}
								else {
									$result[$value['date']. '-2'] = $value;
								}
							}
							// echo '<pre>'; var_dump($result); 
							// exit();
							
							if($result == false){
								if($SevrageTabacMapper->lastError == BAD_MATCH) forward($this->mappingTable["URL_AFTER_FIND_HISTORIQUE_TABAC_TOOLTIP"],"Pas d'enregistrements trouvàs2");
								else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
							}
							global $rowsList;

							krsort($result);
							$rowsList = $result;
							//$rowsList = array_reverse(array_natsort($result,"numero","numero","desc"));
							// echo '<pre>';
							// var_dump($rowsList);
							// echo '</pre>';exit;

							forward($this->mappingTable["URL_AFTER_FIND_HISTORIQUE_TABAC_TOOLTIP"]);

							break;

						// A IMPLÉMENTER
						//case PARAM_SPIRO:
						//	forward($this->mappingTable[""]);

						case PARAM_VIEW:
                            $result = $SevrageTabacMapper->getObjectsByCabinet($account->cabinet);

                            if($result == false)
                                forward($this->mappingTable["URL_VIEW"],"Pas d'enregistrements trouvés");

                            global $rowsList;

                            $rowsList = array_natsort($result,"numero","numero");

                            forward($this->mappingTable["URL_AFTER_LIST"]);
							break;

						case PARAM_LIST_BY_DOSSIER:
                            $result = $SevrageTabacMapper->getObjectsByDossier($account->cabinet, $dossier->numero);

                            if($result == false)
                                forward($this->mappingTable["URL_VIEW"],"Pas d'enregistrements trouvés");

                            global $rowsList;
                            $rowsList = array_natsort($result,"numero","numero");

                            if ($this->suppression == true)
							{
								$this->suppression = false;
                                forward($this->mappingTable["URL_AFTER_FIND_LIST_DOSSIER"], "Sevrage supprimé avec succès !");
							}
                            forward($this->mappingTable["URL_AFTER_FIND_LIST_DOSSIER"]);
							break;

						case PARAM_STAND_ALONE:
                            $dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);

                            $sevrage = new SevrageTabac();
                            if(isset($_POST['SevrageTabac:sevragetabac:id'])){
                                $sevrage->id = $_POST['SevrageTabac:sevragetabac:id'];
                            }
                            $sevrage->date = $SevrageTabac->date;
                            $sevrage = $SevrageTabacMapper->sevrageExist(dateToMysqlDate($sevrage->date),$dossier->id);
                            $sevragetabac = self::createObjectFromDatabase($sevrage);
                            // récupération des datas avant load de la page de visualisation
                            $evaluationInfirmierMapper = new evaluationInfirmierMapper();
                            $evaluationInfirmier = $evaluationInfirmierMapper->getObjectByDateAndNumero(dateToMysqlDate($sevragetabac->date),$sevrage->numero);

                            $diagnosticEducatifMapper = new DiagnosticEducatifMapper($cf->getConnection());
                            $lastDiag = $diagnosticEducatifMapper->getLast($dossier->id,'sevrage_tabac');
                            $diagnosticEducatif = DiagnosticEducatifMapper::getObjectToObject($lastDiag);

                            //Récupération historique des dépistage aomi
                            $liste_historique = $dep_aomi->getHistoriqueDepistage($dossier->id, $account->cabinet);

                            forward($this->mappingTable["URL_AFTER_UPDATE"]);
							break;

						default : 
							global $rowsList;
							#echo 'ici là ';exit; 
							
							$rowsList = ''; // listing des dossiers avec sevrage_tabac

							if((count($rowsList)==0)||($rowsList=="")||($rowsList==false)){
								forward($this->mappingTable["URL_VIEW"],"Pas d'enregistrements trouvés");
							}

							forward($this->mappingTable["URL_VIEW"]);

					}
					
					break;

                case ACTION_DELETE: //Suppression d'un sevrage

                    $dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);
                    $sevrage = new SevrageTabac();
                    if(isset($_POST['SevrageTabac:sevragetabac:id'])){
                        $sevrage->id = $_POST['SevrageTabac:sevragetabac:id'];
                    }
                    $result = $SevrageTabacMapper->deleteSevrage($sevrage->id);
                    if($result == false)
                        if ($SevrageTabacMapper->lastError != NOTHING_DELETED) forward(URL_CONTROLER_PERSISTENCE_ERROR, "Delete object caused an error");

                    $param->action = ACTION_LIST;
                    $param->param1 = PARAM_LIST_BY_DOSSIER;
                    $this->suppression = true;
                    $this->start();
                    break;

				case ACTION_HARD:

					$ct = $_GET['param1'];

					switch($ct){
						case 'CT1' : // documents support patient
						forward($this->mappingTable["URL_DOCS1"]);break;
					}


					break;

				default:
					echo("ACTION IS NULL");
					break;
			}


		}

		/**
		 * création de l'objet sevrage à réception des infos du formulaire
		 * @return [type] [description]
		 */
		static function createObjectFromPost($sevrage){
			#echo 'ici';
			$sevrage->date = dateToMysqlDate($sevrage->date);
			$sevrage->id = $_POST['SevrageTabac:sevragetabac:id'];
			$sevrage->numero = $_POST['SevrageTabac:sevragetabac:numero'];
			$sevrage->tabac = $_POST['SevrageTabac:sevragetabac:tabac'];
			$sevrage->nbrtabac = $_POST['SevrageTabac:sevragetabac:nbrtabac'];
			$sevrage->type_tabac = $_POST['SevrageTabac:sevragetabac:type_tabac'];
			$sevrage->ddebut = $_POST['SevrageTabac:sevragetabac:ddebut'];
			$sevrage->darret = dateToMysqlDate($_POST['SevrageTabac:sevragetabac:darret']);
			$sevrage->spirometrie_date = dateToMysqlDate($_POST['SevrageTabac:sevragetabac:spirometrie_date']);
			$sevrage->spirometrie_CVF = $_POST['SevrageTabac:sevragetabac:spirometrie_CVF'];
			$sevrage->spirometrie_VEMS = $_POST['SevrageTabac:sevragetabac:spirometrie_VEMS'];
			$sevrage->spirometrie_DEP = $_POST['SevrageTabac:sevragetabac:spirometrie_DEP'];
			$sevrage->spirometrie_status = $_POST['SevrageTabac:sevragetabac:spirometrie_status'];
			$sevrage->spirometrie_rapport_VEMS_CVF = $_POST['SevrageTabac:sevragetabac:spirometrie_rapport_VEMS_CVF'];
			$sevrage->dco_test = dateToMysqlDate($_POST['SevrageTabac:sevragetabac:dco_test']);
			$sevrage->co_ppm = $_POST['SevrageTabac:sevragetabac:co_ppm'];
			$sevrage->fagerstrom = $_POST['SevrageTabac:sevragetabac:fagerstrom'];
			$sevrage->horn_stimulation = $_POST['SevrageTabac:sevragetabac:horn_stimulation'];
			$sevrage->horn_plaisir = $_POST['SevrageTabac:sevragetabac:horn_plaisir'];
			$sevrage->horn_relaxation = $_POST['SevrageTabac:sevragetabac:horn_relaxation'];
			$sevrage->horn_anxiete = $_POST['SevrageTabac:sevragetabac:horn_anxiete'];
			$sevrage->horn_besoin = $_POST['SevrageTabac:sevragetabac:horn_besoin'];
			$sevrage->horn_habitude = $_POST['SevrageTabac:sevragetabac:horn_habitude'];
			$sevrage->had_anxiete = $_POST['SevrageTabac:sevragetabac:had_anxiete'];
			$sevrage->had_depression = $_POST['SevrageTabac:sevragetabac:had_depression'];
			$sevrage->echelle_analogique = $_POST['SevrageTabac:sevragetabac:echelle_analogique'];
			$sevrage->echelle_confiance = $_POST['SevrageTabac:sevragetabac:echelle_confiance'];
			$sevrage->stade_motivationnel = $_POST['SevrageTabac:sevragetabac:stade_motivationnel'];
			$sevrage->poids = $_POST['SevrageTabac:sevragetabac:poids'];
			$sevrage->dpoids = dateToMysqlDate($_POST['SevrageTabac:sevragetabac:dpoids']);
			$sevrage->activite = $_POST['SevrageTabac:sevragetabac:activite'];
			$sevrage->alcool = $_POST['SevrageTabac:sevragetabac:alcool'];
			$sevrage->aspects_limitants = addslashes($_POST['SevrageTabac:sevragetabac:aspects_limitants']);
			$sevrage->aspects_facilitants = addslashes($_POST['SevrageTabac:sevragetabac:aspects_facilitants']);
			$sevrage->objectifs_patient = addslashes($_POST['SevrageTabac:sevragetabac:objectifs_patient']);
			#var_dump($sevrage);
			return $sevrage;
		}

		/**
		 * remise au propre de l'object pour affichage dans les view et formulaire
		 * possible que ca existe dans les déserialization mais on sait pas comment ca marche
		 * note RV 27.10.2016
		 * @param  [type] $sevrage [description]
		 * @return [type]          [description]
		 */
		function createObjectFromDatabase($sevrage){
			$sevrageNew = new SevrageTabac();
			$sevrageNew->date = mysqlDateTodate($sevrage->date);
			$sevrageNew->id = $sevrage->id;
			$sevrageNew->numero = $sevrage->numero;
			$sevrageNew->tabac = $sevrage->tabac;
			$sevrageNew->type_tabac = $sevrage->type_tabac;
			$sevrageNew->nbrtabac = $sevrage->nbrtabac;
			$sevrageNew->ddebut = $sevrage->ddebut;
			$sevrageNew->darret =  mysqlDateTodate($sevrage->darret);
			$sevrageNew->spirometrie_date = mysqlDateTodate($sevrage->spirometrie_date);
			$sevrageNew->spirometrie_CVF = $sevrage->spirometrie_CVF;
			$sevrageNew->spirometrie_VEMS = $sevrage->spirometrie_VEMS;
			$sevrageNew->spirometrie_DEP = $sevrage->spirometrie_DEP;
			$sevrageNew->spirometrie_status = $sevrage->spirometrie_status;
			$sevrageNew->spirometrie_rapport_VEMS_CVF = $sevrage->spirometrie_rapport_VEMS_CVF;
			$sevrageNew->fagerstrom = $sevrage->fagerstrom; 
			$sevrageNew->co_ppm =  $sevrage->co_ppm;
			$sevrageNew->dco_test = mysqlDateTodate($sevrage->dco_test);
			$sevrageNew->horn_stimulation = $sevrage->horn_stimulation;
			$sevrageNew->horn_plaisir = $sevrage->horn_plaisir;
			$sevrageNew->horn_relaxation = $sevrage->horn_relaxation;
			$sevrageNew->horn_anxiete = $sevrage->horn_anxiete;
			$sevrageNew->horn_besoin = $sevrage->horn_besoin;
			$sevrageNew->horn_habitude = $sevrage->horn_habitude;
			$sevrageNew->had_anxiete = $sevrage->had_anxiete;
			$sevrageNew->had_depression = $sevrage->had_depression;
			$sevrageNew->echelle_analogique = $sevrage->echelle_analogique;
			$sevrageNew->echelle_confiance = $sevrage->echelle_confiance;
			$sevrageNew->stade_motivationnel = $sevrage->stade_motivationnel;		
			$sevrageNew->poids = $sevrage->poids;
			$sevrageNew->dpoids = mysqlDateTodate($sevrage->dpoids);
			$sevrageNew->activite = $sevrage->activite;
			$sevrageNew->alcool = $sevrage->alcool;
#			$sevrageNew->aspects_limitants = $sevrage->aspects_limitants;
#			$sevrageNew->aspects_facilitants = $sevrage->aspects_facilitants;
#			$sevrageNew->objectifs_patient = $sevrage->objectifs_patient;
			
			return $sevrageNew;
		}
	}
?> 

