<?php 
	
	require_once("bean/Biologie.php");	
	require_once("bean/CardioVasculaireDepart.php");
	require_once("bean/ControlerParams.php");
	require_once("persistence/BiologieMapper.php");
	require_once("persistence/CardioVasculaireDepartMapper.php");
	require_once("GenericControler.php");
	require_once("bean/diageduc.php");
	require_once("persistence/diageducMapper.php");
	
	class diageducControler{
	
		var $mappingTable;
		
		function diageducControler() {
			$this->mappingTable = 
			array(
			"URL_MANAGE"=>"view/cardiovasculaire/diageduc/managediageduc.php",
			"URL_NEW"=>"view/cardiovasculaire/diageduc/newdiageduc.php",
			"URL_AFTER_CREATE"=>"view/cardiovasculaire/diageduc/viewdiageducaftercreate.php",
			"URL_AFTER_UPDATE"=>"view/cardiovasculaire/diageduc/viewdiageducaftercreate.php",
			"URL_AFTER_FIND_VIEW"=>"view/cardiovasculaire/diageduc/viewdiageduc.php",
			"URL_AFTER_FIND_EDIT"=>"view/cardiovasculaire/diageduc/newdiageduc.php",
			"URL_AFTER_DELETE"=>new ControlerParams("diageducControler",ACTION_MANAGE,true),
			"URL_AFTER_LIST"=>"view/cardiovasculaire/diageduc/listdiageduc.php",
			"URL_AFTER_FIND_LIST_DOSSIER"=>"view/cardiovasculaire/diageduc/listdiageducbydossier.php",
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
			global $diageduc;
			global $poids;
			global $systole;	
			global $diastole;	
			global $type_tension;	
			global $HDL;	
			global $LDL;	
			
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

			if(array_key_exists("diageduc",$objects))
				$diageduc = $objects["diageduc"];

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

			foreach ($liste_exam as $exam){
				if(array_key_exists($exam,$objects))
					$$exam = $objects[$exam];
			}
			
			$ledgerFactory = new LedgerFactory();
			$ledger = $ledgerFactory->getLedger("Controler","diageducControler");

			//Create connection factory
			$cf = new ConnectionFactory();

			//create mappers
			$CardioVasculaireDepartMapper = new CardioVasculaireDepartMapper($cf->getConnection());
			$diageducMapper = new diageducMapper($cf->getConnection());
			$dossierMapper = new DossierMapper($cf->getConnection());
			$BiologieMapper = new BiologieMapper($cf->getConnection());

			$ledger->writeArray(I,"Start","Control Parameters = ",$param);


			switch($param->action){
				case ACTION_MANAGE:
					$dossier = new Dossier();
					$diageduc = new diageduc();
					$diageduc->date= date("d/m/Y");
					
					$poids = new Biologie();										
					$systole = new Biologie();										
					$diastole = new Biologie();										
					$type_tension = new Biologie();										
					$HDL = new Biologie();										
					$LDL = new Biologie();										

					$liste_exam=array("Chol", "triglycerides", "creat", "kaliemie", 
									  "proteinurie", "hematurie", "fond", "ECG", 
									  "pouls", "glycemie");	
					
					foreach($liste_exam as $exam){
						$$exam = new Biologie();
					}
					
					forward($this->mappingTable["URL_MANAGE"]);
					break;


				case ACTION_NEW:
						exitIfNull($dossier);
						exitIfNull($diageduc);
						exitIfNullOrEmpty($diageduc->date);
						
						$poids->id=$dossier->id;
						$poids->type_exam="poids";
						
						$systole->id=$dossier->id;
						$systole->type_exam="systole";

						$diastole->id=$dossier->id;
						$diastole->type_exam="diastole";

						$type_tension->id=$dossier->id;
						$type_tension->type_exam="type_tension";

						$HDL->id=$dossier->id;
						$HDL->type_exam="HDL";

						$LDL->id=$dossier->id;
						$LDL->type_exam="LDL";

						$liste_exam=array("Chol", "triglycerides", "creat", "kaliemie", 
										  "proteinurie", "hematurie", "fond", "ECG", 
										  "pouls", "glycemie");	
										  
						foreach($liste_exam as $exam){
							$$exam->id=$dossier->id;
							$$exam->type_exam=$exam;
						}

						if(!isValidDate($diageduc->date)){
							forward($this->mappingTable["URL_MANAGE"],"La date de consultation est invalide");
						}

						$dossier = checkDossierActif($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);

						global $suividiab;
						
						$suividiab=$CardioVasculaireDepartMapper->getsuividiab($dossier->id);

						$CardioVasculaireDepart = new CardioVasculaireDepart();
						$CardioVasculaireDepart->id = $dossier->id;
						$CardioVasculaireDepart->date = $diageduc->date;

						$diageduc->id = $dossier->id;

						$result = $diageducMapper->findObject($diageduc->beforeSerialisation($account));
						if($result == false){
							if($diageducMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"dossier non trouvé");
						}
						else{
							forward($this->mappingTable["URL_MANAGE"],"Cet enregistrement existe dejà");
						}


						$CardioVasculaireDepart=$CardioVasculaireDepart->beforeSerialisation($account);

						$result2 = $CardioVasculaireDepartMapper->getdernierExamsRCVA($dossier->id);
						if($result2 == false){
							if($CardioVasculaireDepartMapper->lastError == BAD_MATCH) $result2=0;
							else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
						}
						
							
						// $glycemie = $CardioVasculaireDepartMapper->getGlycemieDiab($dossier->id);
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

	/*					if(($resultRCVA['dpoids']>'0000-00-00'))
						{

						    $poids= $CardioVasculaireDepartMapper->getPoidsRCVA($dossier->id, $resultRCVA['dpoids']);
							$CardioVasculaireDepart->dpoids=$resultRCVA['dpoids'];
							$poids=$poids[0];
							$CardioVasculaireDepart->poids=$poids['poids'];
						}
	*/
						$pds= $CardioVasculaireDepartMapper->getExam($dossier->id, "poids");
						// print_r($pds);die;
						
						$poids->date_exam=$pds[0]["date_exam"];
						$poids->resultat1=$pds[0]["resultat1"];
						$CardioVasculaireDepart->dpoids=$pds[0]['dexam'];
	

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

						if(($resultRCVA['exam_cardio']>'0000-00-00'))
						{
							$CardioVasculaireDepart->exam_cardio=$resultRCVA['exam_cardio'];
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

						    $autres= $CardioVasculaireDepartMapper->getHTA($dossier->id);
							
							if($autres!=""){
								foreach($autres as $autre){
									$CardioVasculaireDepart->HTA=$autre['HTA'];
								}
							}
								
						}
						

						}

						$CardioVasculaireDepart = $CardioVasculaireDepart->afterDeserialisation($account);

						forward($this->mappingTable["URL_NEW"]);

						break;

				case ACTION_SAVE:
						exitIfNull($dossier);
						exitIfNull($CardioVasculaireDepart);
						exitIfNull($diageduc);
						exitIfNullOrEmpty($diageduc->date);
						$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);

						$diageduc->id=$dossier->id;
						
						$CardioVasculaireDepart->id = $dossier->id;
						$CardioVasculaireDepart->date=$diageduc->date;
						$CardioVasculaireDepart->dpoids=$poids->date_exam;
						$CardioVasculaireDepart->poids=$poids->resultat1;
						$poids->id=$dossier->id;
						
						$CardioVasculaireDepart->dTA=$systole->date_exam;
						$CardioVasculaireDepart->TaSys=$systole->resultat1;
						$systole->id=$dossier->id;
						$CardioVasculaireDepart->TaDia=$diastole->resultat1;
						$diastole->id=$dossier->id;
						$CardioVasculaireDepart->TA_mode=$type_tension->resultat1;
						$type_tension->id=$dossier->id;

						$CardioVasculaireDepart->dHDL=$HDL->date_exam;
						$CardioVasculaireDepart->HDL=$HDL->resultat1;
						$HDL->id=$dossier->id;

						$CardioVasculaireDepart->dLDL=$LDL->date_exam;
						$CardioVasculaireDepart->LDL=$LDL->resultat1;
						$LDL->id=$dossier->id;

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

						$errors = $CardioVasculaireDepart->check();
						$errors2 = $diageduc->check();
						
						if(count($errors2)!=0){
							foreach($errors2 as $erreur){
								$errors[]=$erreur;
							}
						}

						if(count($errors) !=0) forward($this->mappingTable["URL_NEW"],$errors);

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

						if($HDL->date_exam!=""){
							$result = $BiologieMapper->findExamSaisi($HDL->beforeSerialisation($account));									

							$maj=1;
							if($result!==false){//Un examen a été trouvé. 
								if($result["resultat1"]!=$HDL->resultat1){//Le poids est différent=> il faut faire une maj
									$HDL->numero=$result["numero"];
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
						
						$result = $CardioVasculaireDepartMapper->findObject($CardioVasculaireDepart->beforeSerialisation($account));

						if($result == false){

							if(($CardioVasculaireDepartMapper->lastError != BAD_MATCH)&&($CardioVasculaireDepartMapper->lastError!=PARAM)) forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");

							$CardioVasculaireDepart = $CardioVasculaireDepartMapper->purgeexam($CardioVasculaireDepart->beforeSerialisation($account));
							
							if($CardioVasculaireDepart!==false){
								$CardioVasculaireDepart->date=$diageduc->date;
								$result = $CardioVasculaireDepartMapper->createObject($CardioVasculaireDepart);
								if($result == false){
									if($CardioVasculaireDepartMapper->lastError!= NOTHING_UPDATED) 
										forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour des examens");
								}
							}
							
							$result = $diageducMapper->findObject($diageduc->beforeSerialisation($account));
							
							if($result==false){
								$result=$diageducMapper->createObject($diageduc->beforeSerialisation($account));
								if($result == false){
									if($diageducMapper->lastError!= NOTHING_UPDATED) 
										 forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la création de la consultation");
								}
							}
							else{
								$result = $diageducMapper->updateObject($diageduc->beforeSerialisation($account));
								if($result == false){
									if($diageducMapper->lastError!= NOTHING_UPDATED) 
										forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour de la consultation");
								}
							}
							forward($this->mappingTable["URL_AFTER_CREATE"]);

						}
						else{
							$result = $CardioVasculaireDepartMapper->updateObject($CardioVasculaireDepart->beforeSerialisation($account));

							if($result == false) {
								if($CardioVasculaireDepartMapper->lastError != NOTHING_UPDATED)
									forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour");
							}

							$result = $diageducMapper->findObject($diageduc->beforeSerialisation($account));
							
							if($result==false){
								$result=$diageducMapper->createObject($diageduc->beforeSerialisation($account));
								if($result == false) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la création de la consultation");
							}
							else{
								$result = $diageducMapper->updateObject($diageduc->beforeSerialisation($account));
								if($result == false){
									if($diageducMapper->lastError!= NOTHING_UPDATED) 
										forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour de la consultation");
								}
							}
							forward($this->mappingTable["URL_AFTER_CREATE"]);
							
						}
						break;

				case ACTION_DELETE:

					exitIfNull($dossier);
					exitIfNull($diageduc);
					exitIfNullOrEmpty($diageduc->date);

					$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet);

					$diageduc->id = $dossier->id;

					$result = $diageducMapper->deleteObject($diageduc->beforeSerialisation($account));
					if($result == false){
						if($diageducMapper->lastError != NOTHING_DELETED) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Delete object caused an error");
					}
					forward($this->mappingTable["URL_AFTER_DELETE"]);


				case ACTION_LIST:
					set_time_limit(1200);  //EA
					switch($param->param1){
						case PARAM_LIST_BY_DOSSIER:
							$result = $diageducMapper->getObjectsByDossier($account->cabinet, $dossier->numero);
							if($result == false){
								if($diageducMapper->lastError == BAD_MATCH) forward(new ControlerParams($param->controler,ACTION_MANAGE,true),"Pas d'enregistrements trouvés");
								else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
							}
							global $rowsList;
							$rowsList = array_natsort($result,"numero","numero");

							forward($this->mappingTable["URL_AFTER_FIND_LIST_DOSSIER"]);

						default:
							$result = $diageducMapper->getObjectsByCabinet($account->cabinet);
							if($result == false){
								if($diageducMapper->lastError == BAD_MATCH) forward(new ControlerParams($param->controler,ACTION_MANAGE,true),"Pas d'enregistrements trouvés");
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
						
						exitIfNull($diageduc);
						exitIfNullOrEmpty($diageduc->date);
						$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);

						global $suividiab;
						
						$suividiab=$CardioVasculaireDepartMapper->getsuividiab($dossier->id);

						$diageduc->id = $dossier->id;
						$result = $diageducMapper->findObject($diageduc->beforeSerialisation($account));

						if($result == false)
						{
							if($diageducMapper->lastError == BAD_MATCH)
								forward($this->mappingTable["URL_MANAGE"],"Cet enregistrement n'existe pas");
							else
								forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
						}
						$diageduc = $result->afterDeserialisation($account);
						$dsuivi=$result->date;
						$result=$BiologieMapper->findExam($result->date, $dossier->id, "poids");

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
										  "fond", "ECG", "pouls", "glycemie");
										  
						foreach($liste_exam as $exam){
							$result=$BiologieMapper->findExam($dsuivi, $dossier->id, $exam);

							$$exam=new Biologie();
							$$exam->date_exam=$result["date_exam"];
							$$exam->resultat1=$result["resultat1"];
							$$exam->id=$dossier->id;
							$$exam->type_exam=$exam;
							$$exam->numero=$result["numero"];
						}

						if($param->param1 == PARAM_EDIT)
						{
							$CardioVasculaireDepart = new CardioVasculaireDepart();
							$CardioVasculaireDepart->id = $dossier->id;
							$CardioVasculaireDepart->date = $diageduc->date;

							$CardioVasculaireDepart=$CardioVasculaireDepart->beforeSerialisation($account);

							$result2 = $CardioVasculaireDepartMapper->getdernierExamsRCVA($dossier->id);
							if($result2 == false){
								if($CardioVasculaireDepartMapper->lastError == BAD_MATCH) $result2=0;
								else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
							}
							
								
							// $glycemie = $CardioVasculaireDepartMapper->getGlycemieDiab($dossier->id);
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

					/*		if(($resultRCVA['dpoids']>'0000-00-00'))
							{

							    $poids= $CardioVasculaireDepartMapper->getPoidsRCVA($dossier->id, $resultRCVA['dpoids']);
								$CardioVasculaireDepart->dpoids=$resultRCVA['dpoids'];
								$poids=$poids[0];
								$CardioVasculaireDepart->poids=$poids['poids'];
							}*/

/*							if(($resultRCVA['dTA']>'0000-00-00'))
							{
							    $tension= $CardioVasculaireDepartMapper->getTensionRCVA($dossier->id, $resultRCVA['dTA']);
								$CardioVasculaireDepart->dTA=$resultRCVA['dTA'];

								$tension=$tension[0];
								$CardioVasculaireDepart->TaSys=$tension['TaSys'];
								$CardioVasculaireDepart->TaDia=$tension['TaDia'];
								$CardioVasculaireDepart->HTA=$tension['HTA'];
								$CardioVasculaireDepart->TA_mode=$tension['TA_mode'];
								
							}*/

							if(($resultRCVA['exam_cardio']>'0000-00-00'))
							{
								$CardioVasculaireDepart->exam_cardio=$resultRCVA['exam_cardio'];
							}

	/*						if(($resultRCVA['dCreat']>'0000-00-00'))
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
							}*/

/*							if(($resultRCVA['dHDL']>'0000-00-00'))
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
							}*/

/*							if(($resultRCVA['dFond']>'0000-00-00'))
							{
									$CardioVasculaireDepart->dFond=$resultRCVA['dFond'];
							}

							if(($resultRCVA['dECG']>'0000-00-00'))
							{
								$CardioVasculaireDepart->dECG=$resultRCVA['dECG'];
							}*/

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
							
	/*						if($resultRCVA['dChol']>'0000-00-00'){
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
							*/
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



				default:
					echo("ACTION IS NULL");
					break;
			}
		}

	}
?> 
