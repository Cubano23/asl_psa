<?php 
	require_once("global/config.php");	
	require_once("bean/SuiviDiabete.php");	
	require_once("bean/Biologie.php");	
	require_once("bean/OutdateReference.php");
	require_once("bean/DepistageAOMI.php");
	require_once("persistence/BiologieMapper.php");
	require_once("persistence/SuiviDiabeteMapper.php");
	require_once("persistence/ConnectionFactory.php");	
	
	class SuiviDiabeteControler {
	
		var $mappingTable;
		
		function SuiviDiabeteControler() {
			//Définition du mapping => liste des scripts d'affichage
			$this->mappingTable = array(
			"URL_MANAGE_INCOMPLETE"=>"view/diabete/suivi/managesuividiabeteincomplet.php",
			"URL_MANAGE_OUTDATED"=>"view/diabete/suivi/managealertesuividiabete.php",
			"URL_MANAGE_PRE_CREATE"=>"view/diabete/suivi/managesuividiabeteprecreate.php",
			"URL_MANAGE_CREATE"=>"view/diabete/suivi/managesuividiabetecreate.php",
			"URL_MANAGE_CONSULT"=>"view/diabete/suivi/managesuividiabete.php",
			"URL_NEW"=>"view/diabete/suivi/newsuividiabete.php",
			"URL_AFTER_CREATE"=>"view/diabete/suivi/viewsuividiabeteaftercreate.php",
			"URL_AFTER_UPDATE"=>"view/diabete/suivi/viewsuividiabeteaftercreate.php",

			"URL_AFTER_FIND_VIEW"=>"view/diabete/suivi/viewsuividiabetesystematique.php",
			"URL_AFTER_FIND_VIEW_4MOIS"=>"view/diabete/suivi/viewsuividiabete4mois.php",
			"URL_AFTER_FIND_VIEW_SEMESTRIEL"=>"view/diabete/suivi/viewsuividiabetesemestriel.php",
			"URL_AFTER_FIND_VIEW_ANNUEL"=>"view/diabete/suivi/viewsuividiabeteannuel.php",			
			"URL_AFTER_FIND_EDIT"=>"view/diabete/suivi/newsuividiabete.php",
			
			"URL_AFTER_LIST_BY_CABINET"=>"view/diabete/suivi/listsuividiabetebycabinet.php",
			"URL_AFTER_LIST_OUTDATED"=>"view/diabete/suivi/listsuividiabetealerte.php",
			"URL_AFTER_LIST_ANY"=>"view/diabete/suivi/listpatientsuividiabete.php",
			"URL_AFTER_LIST_INCOMPLETE_SYSTEMATIQUE"=>"view/diabete/suivi/listsuividiabeteincompletsystematique.php",
			"URL_AFTER_LIST_INCOMPLETE_4MOIS"=>"view/diabete/suivi/listsuividiabete4moisincomplet.php",
			"URL_AFTER_LIST_INCOMPLETE_SEMESTRIEL"=>"view/diabete/suivi/listsuividiabeteincompletsemestriel.php",
			"URL_AFTER_LIST_INCOMPLETE_ANNUEL"=>"view/diabete/suivi/listsuividiabeteincompletannuel.php",
			
			"URL_AFTER_DELETE"=>new ControlerParams("SuiviDiabeteControler",ACTION_MANAGE,true,PARAM_ANY));
		}
			
		function start() {//Lancement de l'application
			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;			
			global $dossier;
			global $suiviDiabete;
			global $outDateReference;
			global $suiviDiabeteList;
			global $depistageAOMI;
            global $depistage_aomi;
            global $liste_historique;

            $dep_aomi = new DepistageAOMI();
			
			foreach($_ENV['liste_exam_diabete'] as $exam){
				global $$exam;
			}
			
			//Récupération des variables lors d'une validation d'un formulaire
			if(array_key_exists("dossier",$objects))
				$dossier = $objects["dossier"];	

			if(array_key_exists("suiviDiabete",$objects))
				$suiviDiabete = $objects["suiviDiabete"];	

			if(array_key_exists("outDateReference",$objects))
				$outDateReference = $objects["outDateReference"];

            if(array_key_exists("DepistageAOMI",$objects))
                $depistageAOMI = $objects["DepistageAOMI"];

			foreach($_ENV['liste_exam_diabete'] as $exam){
				if(array_key_exists($exam,$objects))
					$$exam = $objects[$exam];
			}
			
			// create ledger for this controler
			$ledgerFactory = new LedgerFactory();
			$ledger = $ledgerFactory->getLedger("Controler","SuiviDiabeteControler");

				
			//Create connection factory
			$cf = new ConnectionFactory();
		
			//create mappers
			$suiviDiabeteMapper = new SuiviDiabeteMapper($cf->getConnection());
			$BiologieMapper = new BiologieMapper($cf->getConnection());
			$dossierMapper = new DossierMapper($cf->getConnection());
			
			$ledger->writeArray(I,"Start","Control Parameters = ",$param);
			
			switch($param->action){//Switch sur l'action pour exécuter le bon bout de programme
				case ACTION_MANAGE:	//En ACTION_MANAGE : définition du numéro de dossier, 1ère page recherche alertes, etc...	

					if(!$param->isParam1Valid()) forward(URL_CONTROLER_PARAMS_ERROR,"Invalid param1");//Param 1 non valide=> retour vers page d'erreur
					if($param->param1 != PARAM_CREATE){//Initialisation des variable si on n'est pas en PARAM_CREATE
						$dossier = new Dossier();										

						foreach($_ENV['liste_exam_diabete'] as $exam){
							$$exam = new Biologie();
						}
					}
					$suiviDiabete = new SuiviDiabete();//Initialisation Bean SuiviDiabete
					$suiviDiabete->dsuivi= date("d/m/Y");
					switch($param->param1){//Redirection vers la bonne page en fonction du PARAM passé											
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
							forward($this->mappingTable["URL_MANAGE_CREATE"]);
						case PARAM_PRE_CREATE:							
							forward($this->mappingTable["URL_MANAGE_PRE_CREATE"]);							
					}
					
					break;
				
				case ACTION_FIND://En ACTION_FIND : visualisation de donnuméroes

					//Vérifications  sur certaines variable est exit si variable non conforme
					if(!$param->isParam1Valid()) forward(URL_CONTROLER_PARAMS_ERROR,"Invalid param1");
					exitIfNull($dossier);
					#var_dump($suiviDiabete);
					exitIfNull($suiviDiabete);
					exitIfNullOrEmpty($suiviDiabete->dsuivi);				
					$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet);
					
					
					$suiviDiabete->dossier_id = $dossier->id;

					//Récupération historique des dépistage aomi
                    $liste_historique = $dep_aomi->getHistoriqueDepistage($dossier->id, $account->cabinet);

					//Recherche du suivi diabète demandé
					$result = $suiviDiabeteMapper->findObject($suiviDiabete->beforeSerialisation($account));

					if($result == false){
						if($suiviDiabeteMapper->lastError == BAD_MATCH){
							if($param->param2==PARAM_VIEW){
								forward($this->mappingTable["URL_MANAGE_PRE_CREATE"],"Pas d'enregistrement trouvé");
							}
							else {
								forward($this->mappingTable["URL_MANAGE_CONSULT"],"Pas d'enregistrements trouvés");
							}
						}
						else {
							forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
						}
					}
					
					//Mise au format jj/mm/aaaa des date du suivi diabète
					$suiviDiabete = $result->afterDeserialisation($account);
					
					$dsuivi=$result->dsuivi;

					foreach($_ENV['liste_examRD_diabete'] as $exam=>$date){

						if($result->$date>"0000-00-00"){//le suivi a été fait manuellement donc on n'affiche que les donnuméroes saisies
							$res=$BiologieMapper->findExam($result->$date, $dossier->id, $exam);
							$$exam=new Biologie();
							$$exam->date_exam=$res["date_exam"];
							$$exam->resultat1=$res["resultat1"];
							$$exam->id=$dossier->id;
							$$exam->type_exam=$exam;
							$$exam->numero=$res["numero"];

						}
						else{
							$$exam=new Biologie();
							$$exam->id=$dossier->id;
							$$exam->type_exam=$exam;
							#echo '<pre>';var_dump($$exam);echo '</pre>';
						}
					}


					$debut=explode("/",$suiviDiabete->date_debut);
					if(count($debut)==3){
						$suiviDiabete->date_debut=$debut[1]."/".$debut[2];
					}					
					
					if($suiviDiabete->poids==0){
						$suiviDiabete->poids="";
					}
					if($param->param1 == PARAM_EDIT) {//On souhaite modifier un suivi diabète déjà créé
						global $dernier_suivi;
						//Recherche du dernier suivi déjà créé pour permettre l'affichage des cases en orange pour les donnuméroes en alerte
						$dernier_suivi = new SuiviDiabete();

						$dernier_suivi->dHBA=$dernier_suivi->dExaFil=$dernier_suivi->dExaPieds=date("d/m/Y");
						$dernier_suivi->dChol=$dernier_suivi->dLDL=$dernier_suivi->dCreat=date("d/m/Y");
						$dernier_suivi->dAlbu=$dernier_suivi->dFond=$dernier_suivi->dECG=date('d/m/Y');

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
						#$resultdiab['dTabac']=date('Y-m-d');
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
						$dernier_suivi->dsuivi=$resultdiab["dsuivi"];
						$dernier_suivi->dPoids=$resultdiab["dPoids"];
						$dernier_suivi->dtension=$resultdiab["dtension"];
						$dernier_suivi->dentiste=$resultdiab["dentiste"];
						#$dernier_suivi->dTabac=$resultdiab["dTabac"];
						
						$result = $suiviDiabeteMapper->getsystematique($dossier->id, $dernier_suivi->dsuivi);
						$result= $result[0];
						
						$dernier_suivi->hta = $result["hta"];
						$dernier_suivi->arte = $result["arte"];
						$dernier_suivi->neph = $result["neph"];
						$dernier_suivi->coro = $result["coro"];
						$dernier_suivi->reti = $result["reti"];
						$dernier_suivi->neur = $result["neur"];
						$dernier_suivi->equilib = $result["equilib"];
						$dernier_suivi->lipide = $result["lipide"];
						
						foreach($_ENV['liste_examRD_diabete'] as $exam=>$champ){
							$result=$BiologieMapper->findExam(date("Y-m-d"), $dossier->id, $exam);
							$dernier_suivi->$champ=$result["dexam"];
						}

		
					
					  $dernier_suivi = $dernier_suivi->afterDeserialisation($account);
					  #echo '<pre>';var_dump($dernier_suivi);echo '</pre>';exit;
					}

                        $arrayDate = explode("/", $dernier_suivi->dsuivi);
                        $date = new DateTime($arrayDate[2]. "-" .$arrayDate[1]. "-" .$arrayDate[0]);
						$depistage_aomi = $dep_aomi->getByDIdAndDate($dossier->id, $date->format('Y-m-d'));

						forward($this->mappingTable["URL_AFTER_FIND_EDIT"]);
					}
					else//On souhaite visualiser un suivi diabète déjà saisi => renvoi vers la bonne page
					switch($param->param2){ 
						case PARAM_SYSTEMATIQUE:
                            //Récupération historique des dépistage aomi
                            $liste_historique = $dep_aomi->getHistoriqueDepistage($dossier->id, $account->cabinet);
							forward($this->mappingTable["URL_AFTER_FIND_VIEW"]);
							break;
						case PARAM_4MOIS: forward($this->mappingTable["URL_AFTER_FIND_VIEW_4MOIS"]);
						case PARAM_SEMESTRIEL: forward($this->mappingTable["URL_AFTER_FIND_VIEW_SEMESTRIEL"]);
						case PARAM_ANNUEL: forward($this->mappingTable["URL_AFTER_FIND_VIEW_ANNUEL"]);
					}
					break;
				
				case ACTION_NEW:	//Création suivi diabète

					//Vérification sur les variables
					exitIfNull($dossier);
					exitIfNull($poids);
					exitIfNull($systole);
					exitIfNull($diastole);
					exitIfNull($HDL);
					exitIfNull($HDL);
					exitIfNull($suiviDiabete);
					exitIfNullOrEmpty($suiviDiabete->dsuivi);								
					$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE_PRE_CREATE"]);

					foreach($_ENV['liste_exam_diabete'] as $exam){
						$$exam->id=$dossier->id;
						$$exam->type_exam=$exam;
					}

                    //Récupération historique des dépistage aomi
                    $liste_historique = $dep_aomi->getHistoriqueDepistage($dossier->id, $account->cabinet);

					//Recherche de la liste des suivis diabète déjà saisis pour affichage en haut de page
					$suiviDiabete->suivi_type=array("a", "4");
					$suiviDiabete->dossier_id = $dossier->id;
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

					//Vérification si le suivi diabète n'a pas encore été saisi pour éviter les conflits
					$result = $suiviDiabeteMapper->findObject($suiviDiabete->beforeSerialisation($account));

					if($result == false){
						if($suiviDiabeteMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");					
					}
					else
						forward($this->mappingTable["URL_MANAGE_PRE_CREATE"],"Cet enregistrement existe déjà, cliquez sur modifier pour modifier cet enregistrement");
					
					//Définition du dernier suivi pour affichage des cases en orange si exam en alerte
					global $dernier_suivi;
					
					$dernier_suivi = new SuiviDiabete();
					
					$result = $suiviDiabeteMapper->getdernierExams($dossier->id);
					#echo '<pre>', print_r($result); echo '</pre>';
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
						$dernier_suivi->dsuivi=$resultdiab["dsuivi"];
						$dernier_suivi->dPoids=$resultdiab["dPoids"];
						$dernier_suivi->dtension=$resultdiab["dtension"];
						$dernier_suivi->dentiste=$resultdiab["dentiste"];

						//Recherche des examens sur la partie suivi systématique et qui ne changent à priori pas : numérophropatie, artériopathie, etc...
						$result = $suiviDiabeteMapper->getsystematique($dossier->id, $dernier_suivi->dsuivi);
						$result = $result[0];
						
						$suiviDiabete->hta = $result["hta"];
						$suiviDiabete->arte = $result["arte"];
						$suiviDiabete->neph = $result["neph"];
						$suiviDiabete->coro = $result["coro"];
						$suiviDiabete->reti = $result["reti"];
						$suiviDiabete->neur = $result["neur"];
						$suiviDiabete->equilib = $result["equilib"];
						$suiviDiabete->lipide = $result["lipide"];
						$suiviDiabete->type = $result["type"];
						
						foreach($_ENV['liste_examRD_diabete'] as $exam=>$champ){
							$result=$BiologieMapper->findExam(date("Y-m-d"), $dossier->id, $exam);
							if($exam=="poids"){
								$dernier_suivi->poids=$result["resultat1"];
							}
							$dernier_suivi->$champ=$result["dexam"];
						}


						//Conversion des dates du dernier suivi au format jj/mm/aaaa
					  $dernier_suivi = $dernier_suivi->afterDeserialisation($account);
					}
					
					//Recherche des dates du début du suivi diabète
					$suiviDiabete->date_debut=$suiviDiabeteMapper->getDebut($dossier);
					$suiviDiabete->diab10ans=$suiviDiabeteMapper->get10ans($dossier);
					forward($this->mappingTable["URL_NEW"]);
					break;
														
				case ACTION_SAVE://Sauvegarde des donnuméroes

					//Controle sur les donnuméroes
					
					exitIfNull($dossier);
					exitIfNull($suiviDiabete);
					exitIfNullOrEmpty($suiviDiabete->dsuivi);						
					
					if($suiviDiabete->date_debut!=''){
						$suiviDiabete->date_debut="01/".$suiviDiabete->date_debut;
					}
					
					$suiviDiabete->dossier_id = $dossier->id;

					$antiDiabetiqueOraux = new Biologie();
					$antiDiabetiqueOraux->id = $dossier->id;
					$antiDiabetiqueOraux->type_exam = 'ADO';
					$antiDiabetiqueOraux->date_exam = $suiviDiabete->dsuivi;
					$antiDiabetiqueOraux->resultat1 = implode(",",$suiviDiabete->ADO);


					$suiviDiabete->dtension=$systole->date_exam;
					$suiviDiabete->TaSys=$systole->resultat1;
					$suiviDiabete->TaDia=$diastole->resultat1;
					$suiviDiabete->TA_mode=$type_tension->resultat1;


					
					foreach($_ENV['liste_exam_saisie_diabete'] as $exam=>$vals){
						if($vals["date"]!=""){
							$suiviDiabete->$vals["date"]=$$exam->date_exam;
						}
						//E.A.05-06 php5
						if($vals["val"]!="") 
						$suiviDiabete->$vals["val"]=$$exam->resultat1;
						if(isset($vals["val2"])){
							$suiviDiabete->$vals["val2"]=$$exam->resultat2;
						}
						$$exam->id=$dossier->id;
					}

					//Vérification s'il y a des erreurs de saisie. S'il y a des erreurs, renvoi vers la page de saisie
					$errors = $suiviDiabete->check($dossier);

					if(count($errors) !=0) {
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
							}

							$dernier_suivi->dHBA=$resultdiab['dHBA'];
							$dernier_suivi->dExaFil=$resultdiab['dExaFil'];
							$dernier_suivi->dExaPieds=$resultdiab['dExaPieds'];
							$dernier_suivi->dChol=$resultdiab['dChol'];
							$dernier_suivi->dLDL=$resultdiab['dLDL'];
							$dernier_suivi->dCreat=$resultdiab['dCreat'];
							$dernier_suivi->dAlbu=$resultdiab['dAlbu'];
							$dernier_suivi->dFond=$resultdiab['dFond'];
							$dernier_suivi->dECG=$resultdiab['dECG'];

							$dernier_suivi = $dernier_suivi->afterDeserialisation($account);
						}
						
						$suiviDiabete->date_debut=str_replace("01/", "", $suiviDiabete->date_debut);
						forward($this->mappingTable["URL_NEW"],$errors);
					}

					//Mise à jour des infos sur le dossier en cas de modification
					$result = $dossierMapper->updateObject($dossier->beforeSerialisation($account));
					
					//Recherche s'il y a un suivi diab à la même date. Si oui => mise à jour des donnuméroes, sinon création donnuméroes
					$result = $suiviDiabeteMapper->findObject($suiviDiabete->beforeSerialisation($account));									
					
					if($result == false){
						if($suiviDiabeteMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");
						$result = $suiviDiabeteMapper->createObject($suiviDiabete->beforeSerialisation($account));
						if($result == false) forward(URL_CONTROLER_PERSISTENCE_ERROR,"erreur lors de la création");
						
						$suiviDiabete->date_debut=str_replace("01/", "", $suiviDiabete->date_debut);

						if($systole->date_exam!=""){
							$diastole->date_exam=$systole->date_exam;
							$type_tension->date_exam=$systole->date_exam;

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

								if($result==false){//Aucun HDL créé avec le même identifiant
									$result = $BiologieMapper->createObject($type_tension->beforeSerialisation($account));
								}
								else{//Déjà un HDL créé avec le même identifiant=>maj
									$result = $BiologieMapper->updateObject($type_tension->beforeSerialisation($account));
								}
							}
						}
						/* nbr de paquets de tabac */
						// $result = $BiologieMapper->findExamSaisi($nbrtabac->beforeSerialisation($account));	
						// $maj=1;
						// 	if($result!==false){//Un examen a été trouvé. 
						// 		if($result["resultat1"]!=$nbrtabac->resultat1){//Le poids est différent=> il faut faire une maj
						// 			$nbrtabac->numero=$result["numero"];
						// 		}
						// 		else{//L'exam enregistré est identique=> pas de maj
						// 			$maj=0;
						// 		}
						// 	}

						foreach($_ENV['liste_examRD_diabete'] as $exam=>$vals){
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

                        // Enregistrement du dépistage AOMI
                        $depistageAOMI->dossier_id = $dossier->id;
                        $depistageAOMI->dossier_numero = $dossier->numero;
                        $arrayDate = explode("/", $suiviDiabete->dsuivi);
                        $depistageAOMI->dateSaisie = new DateTime($arrayDate[2]. "-" .$arrayDate[1]. "-" .$arrayDate[0]);

                        if (($depistageAOMI->ipsd != NULL) && ($depistageAOMI->ipsd != 0) && ($depistageAOMI->ipsg != NULL) && ($depistageAOMI->ipsg != 0) && ($depistageAOMI->eda != NULL))
                            $depistageAOMI->save();

                        //Récupération historique des dépistage aomi
                        $liste_historique = $dep_aomi->getHistoriqueDepistage($dossier->id, $account->cabinet);
						
						forward($this->mappingTable["URL_AFTER_CREATE"]);
					}
					else{
						$result = $suiviDiabeteMapper->updateObject($suiviDiabete->beforeSerialisation($account));
						if($result == false) {
							if($suiviDiabeteMapper->lastError != NOTHING_UPDATED)
								forward(URL_CONTROLER_PERSISTENCE_ERROR,"update failed");						}
						$suiviDiabete->date_debut=str_replace("01/", "", $suiviDiabete->date_debut);

						if($systole->date_exam!=""){
							$diastole->date_exam=$systole->date_exam;
							$type_tension->date_exam=$systole->date_exam;

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

								if($result==false){//Aucun HDL créé avec le même identifiant
									$result = $BiologieMapper->createObject($type_tension->beforeSerialisation($account));
								}
								else{//Déjà un HDL créé avec le même identifiant=>maj
									$result = $BiologieMapper->updateObject($type_tension->beforeSerialisation($account));
								}
							}
						}


						foreach($_ENV['liste_examRD_diabete'] as $exam=>$vals){
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
                        $depistageAOMI->update();

                        //Récupération historique des dépistage aomi
                        $liste_historique = $dep_aomi->getHistoriqueDepistage($dossier->id, $account->cabinet);

						forward($this->mappingTable["URL_AFTER_UPDATE"]);
					}
					break;
						
				case ACTION_DELETE://Suppression d'un suivi diabète
					exitIfNull($dossier);
					exitIfNull($suiviDiabete);
					exitIfNullOrEmpty($suiviDiabete->dsuivi);

					$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet);
					$suiviDiabete->dossier_id = $dossier->id;
					$result = $suiviDiabeteMapper->deleteObject($suiviDiabete->beforeSerialisation($account));
					if($result == false){
						if($suiviDiabeteMapper->lastError != NOTHING_DELETED) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Delete object caused an error");
					}

                    //Suppression de toutes les saisies dépistages AOMI liées au suivi
                    $depistageAOMI->dossier_id = $dossier->id;
                    $depistageAOMI->dateSaisie = date("Y-m-d", strtotime($depistageAOMI->dateSaisie));
                    $depistageAOMI->deleteByProvenanceAndDate();

					forward($this->mappingTable["URL_AFTER_DELETE"]);
				
				case ACTION_LIST://Affichage liste suivi diabète
					global $rowsList;
					set_time_limit(1200); //ea
					if(!$param->isParam1Valid()) forward(URL_CONTROLER_PARAMS_ERROR,"Invalid param1");
					switch($param->param1){					
						case PARAM_INCOMPLETE://Suivis incomplets ou complets
							if(!$param->isParamNValid(2)) forward(URL_CONTROLER_PARAMS_ERROR,"Invalid param2");
							if(!$param->isParamNValid(3)) forward(URL_CONTROLER_PARAMS_ERROR,"Invalid param3");							
							echo 'A';
							switch($param->param2)
							{//Dans le cas recherche suivis complets ou incomplets => switch sur param 2 et 3 pour définir la requête à faire 
								case PARAM_SYSTEMATIQUE:
									switch($param->param3)
									{
										case PARAM_INCOMPLET :
										echo 'B';
											$result = $suiviDiabeteMapper->getIncompletedExams($account->cabinet);	
											break;
										case PARAM_COMPLET :echo 'C';
											$result = $suiviDiabeteMapper->getcompletedExams($account->cabinet);	
											break;
										case PARAM_TOUS :echo 'D';
											$result = $suiviDiabeteMapper->gettousExams($account->cabinet);	
											break;
									}
									break;

								case PARAM_4MOIS:
									switch($param->param3)
									{
										case PARAM_INCOMPLET :
											$result = $suiviDiabeteMapper->getIncompletedExamsType4($account->cabinet);	
											break;
										case PARAM_COMPLET :
											$result = $suiviDiabeteMapper->getcompletedExamsType4($account->cabinet);	
											break;
										case PARAM_TOUS :
											$result = $suiviDiabeteMapper->gettousExamsType4($account->cabinet);	
											break;
									}
									break;
								
								case PARAM_ANNUEL:
									switch($param->param3)
									{
										case PARAM_INCOMPLET :
											$result = $suiviDiabeteMapper->getIncompletedExamsTypeA($account->cabinet);
											break;
										case PARAM_COMPLET :
											$result = $suiviDiabeteMapper->getcompletedExamsTypeA($account->cabinet);
											break;
										case PARAM_TOUS :
											$result = $suiviDiabeteMapper->gettousExamsTypeA($account->cabinet);
											break;
									}
									break;
							}
							
							if($result == false){
								if($suiviDiabeteMapper->lastError == BAD_MATCH) forward($this->mappingTable["URL_MANAGE_INCOMPLETE"],"Pas d'enregistrements trouvés");
								else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");				
							}		
													
							//tri sur le résultat pour affichage de la liste
							$rowsList = array_natsort($result,"numero", "numero");

							switch($param->param2)
							{
								case PARAM_SYSTEMATIQUE:
									forward($this->mappingTable["URL_AFTER_LIST_INCOMPLETE_SYSTEMATIQUE"]);								
									break;
																
								case PARAM_4MOIS:
									forward($this->mappingTable["URL_AFTER_LIST_INCOMPLETE_4MOIS"]);
									break;
								
								case PARAM_ANNUEL:
									forward($this->mappingTable["URL_AFTER_LIST_INCOMPLETE_ANNUEL"]);
									break;
							}
							
							break;
							
						case PARAM_OUTDATED://exams en alerte
							exitIfNull($outDateReference);
							$result = $suiviDiabeteMapper->getExpiredExams($account->cabinet,$outDateReference->period);
							if($result == false){
								if($suiviDiabeteMapper->lastError == BAD_MATCH) forward($this->mappingTable["URL_MANAGE_OUTDATED"],"Pas d'enregistrements trouvés");
								else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
							}
							else
								if(count($result)==0) forward($this->mappingTable["URL_MANAGE_OUTDATED"],"Pas d'enregistrements trouvés");
							
							$rowsList = array_natsort($result,"numero","numero");

							//Recherche des exams à partir de la liste des exams
							foreach($rowsList as $pos=>$donnees){
								$id=$donnees["dossier_id"];
						
						/*		$liste_exam=array("creat"=>"dCreat", 
												  "albu"=>"dAlbu", 
												  "fond"=>"dFond", 
												  "ECG"=>"dECG", 
												  "dent"=>"dentiste", 
												  "pied"=>"dExaPieds", 
												  "monofil"=>"dExaFil",
												  "poids"=>"dPoids",
												  "HDL"=>"dChol",
												  "LDL"=>"dLDL",
												  "HBA1c"=>"dHBA");*/
								
								foreach($_ENV['liste_examRD_diabete'] as $code=>$champ){
									$result=$BiologieMapper->findExam(date("Y-m-d"), $id, $code);
									if(strpos($result["date_exam"],"/")!==false){
										$result["date_exam"]=explode("/", $result["date_exam"]);
										$result["date_exam"]=$result["date_exam"][2]."-".$result["date_exam"][1]."-".$result["date_exam"][0];
									}
									$rowsList[$pos][$champ]=$result["date_exam"];
								}
							}
							
							//pour chaque dossier vérification si la personne n'a pas été sortie du suivi diabète
							for($i=0;$i<count($rowsList);$i++){
								$result = $suiviDiabeteMapper->getdernierRappel($rowsList[$i]['dossier_id'], $rowsList[$i]['dsuivi']);

								if($result == false){
									if($suiviDiabeteMapper->lastError == BAD_MATCH) $result=0;
									else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
								}

								$rowsList[$i]['sortie']=$result[0]['sortie'];

							}
							


							forward($this->mappingTable["URL_AFTER_LIST_OUTDATED"]);
							break;
							
						case PARAM_ANY:	//Liste de tous les suivis d'un dossier
							exitIfNull($dossier);	
							
							$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE_CONSULT"]);
							$suiviDiabete = new SuiviDiabete();
							$suiviDiabete->dossier_id = $dossier->id;
							$result = $suiviDiabeteMapper->findObjects($suiviDiabete);
							if($result == false){
								if($suiviDiabeteMapper->lastError == BAD_MATCH) forward($this->mappingTable["URL_MANAGE_CONSULT"],"Pas d'enregistrements trouvés");
								else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");				
							}

							$suiviDiabeteList = $result;
							for($i=0;$i<count($suiviDiabeteList);$i++){
								$suiviDiabeteList[$i] = $suiviDiabeteList[$i]->afterDeserialisation($account);
							}
							forward($this->mappingTable["URL_AFTER_LIST_ANY"]);				
							break;
							
						case PARAM_LIST_BY_CABINET://Liste des suivis du cabinet
							$result = $suiviDiabeteMapper->getObjectsByCabinet($account->cabinet);

							if($result == false){
								if($suiviDiabeteMapper->lastError == BAD_MATCH) forward($this->mappingTable["URL_MANAGE_CONSULT"],"Pas de suivis trouvés");
								else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");				
							}
							global $rowsList;
							$rowsList = array_natsort($result,"numero", "numero");
				
							

							forward($this->mappingTable["URL_AFTER_LIST_BY_CABINET"]);
							break;
					}					
					
					break;
						
				default:
					echo("ACTION IS NULL");
					break;
			}
		}
	}

?>

