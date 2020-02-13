<?php 
	
	require_once("bean/Hemocult.php");
	require_once("bean/ControlerParams.php");
	require_once("persistence/HemocultMapper.php");
	require_once("GenericControler.php");
	
	class HemocultControler{
	
		var $mappingTable;
		
		function HemocultControler() {
			$this->mappingTable = 
			array(
			"URL_MANAGE"=>"view/hemocult/managehemocult.php",
			"URL_NEW"=>"view/hemocult/newhemocult.php",
//			"URL_AFTER_CREATE"=>new ControlerParams("DepistageCancerSeinControler",ACTION_MANAGE,true),
//			"URL_AFTER_UPDATE"=>new ControlerParams("DepistageCancerSeinControler",ACTION_MANAGE,true),
			"URL_AFTER_CREATE"=>"view/hemocult/viewhemocultaftercreate.php",
			"URL_AFTER_UPDATE"=>"view/hemocult/viewhemocultaftercreate.php",
			"URL_AFTER_FIND_VIEW"=>"view/hemocult/viewhemocult.php",
			"URL_AFTER_FIND_EDIT"=>"view/hemocult/newhemocult.php",
			"URL_AFTER_DELETE"=>new ControlerParams("hemocultControler",ACTION_MANAGE,true),
			"URL_AFTER_LIST"=>"view/hemocult/listhemocult.php",
			"URL_AFTER_FIND_LIST_DOSSIER"=>"view/hemocult/listhemocultbydossier.php",
			"URL_MANAGE_OUTDATED"=>"view/hemocult/managealertehemocult.php",
			"URL_AFTER_LIST_OUTDATED"=>"view/hemocult/listhemocultalerte.php",
			"URL_ON_CALLBACK_FAIL"=>"view/error.php");
		}
	

		function start(){

			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;
			global $dossier;
			global $Hemocult;
			global $outDateReference;
			global $HemocultList;


			if(array_key_exists("outDateReference",$objects))
				$outDateReference = $objects["outDateReference"];

			global $dossier;
			if(array_key_exists("dossier",$objects))
				$dossier = $objects["dossier"];

			if(array_key_exists("CardioVasculaireDepart",$objects))
				$CardioVasculaireDepart = $objects["CardioVasculaireDepart"];


			$ledgerFactory = new LedgerFactory();
			$ledger = $ledgerFactory->getLedger("Controler","CardioVasculaireDepartControler");

			//Create connection factory
			$cf = new ConnectionFactory();

			//create mappers
			$CardioVasculaireDepartMapper = new CardioVasculaireDepartMapper($cf->getConnection());
			$dossierMapper = new DossierMapper($cf->getConnection());

			$ledger->writeArray(I,"Start","Control Parameters = ",$param);


			switch($param->action){
				case ACTION_MANAGE:
					$dossier = new Dossier();
					$CardioVasculaireDepart = new CardioVasculaireDepart();
					$CardioVasculaireDepart->date= date("d/m/Y");
					
					global $complement;
					$complement=false;


					if(!$param->isParam1Valid())
						forward($this->mappingTable["URL_MANAGE"]);
					else
					{
					    switch($param->param1){
							case PARAM_OUTDATED:
								$outDateReference = new OutDateReference();
								forward($this->mappingTable["URL_MANAGE_OUTDATED"]);
								
							case PARAM_EDIT:
								$complement=true;
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
							
							foreach($autres as $autre){
								if(($CardioVasculaireDepart->antecedants!='oui')&&($CardioVasculaireDepart->antecedants!='non')){
									if(($autre['antecedants']=='oui')||($autre['antecedants']=='non')){
										$CardioVasculaireDepart->antecedants=$autre['antecedants'];
									}
								}
								
								if($CardioVasculaireDepart->traitement==array()){
									$CardioVasculaireDepart->traitement=split(',',$autre['traitement']);
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
								
								if(($CardioVasculaireDepart->tabac!='oui')&&($CardioVasculaireDepart->tabac!='non')){
									if(($autre['tabac']=='oui')||($autre['tabac']=='non')){
										$CardioVasculaireDepart->tabac=$autre['tabac'];
										$CardioVasculaireDepart->darret=$autre['darret'];
									}
								}
								
							}
							
						
						$CardioVasculaireDepart = $CardioVasculaireDepart->afterDeserialisation($account);
		
							forward($this->mappingTable["URL_VIEW_DEPART"]);

						}	
						else{

						exitIfNull($CardioVasculaireDepart);
						exitIfNullOrEmpty($CardioVasculaireDepart->date);
						$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);

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

						if($param->param1 == PARAM_EDIT)
						{
							forward($this->mappingTable["URL_AFTER_FIND_EDIT"]);
						}
						else
							forward($this->mappingTable["URL_AFTER_FIND_VIEW"]);
						}
						break;

				case ACTION_NEW:

						exitIfNull($dossier);
						exitIfNull($CardioVasculaireDepart);
						exitIfNullOrEmpty($CardioVasculaireDepart->date);
						
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
								forward($this->mappingTable["URL_MANAGE"],"Cet enregistrement existe dejà");
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

/*						$result1 = $CardioVasculaireDepartMapper->getdernierExamsHTA($dossier->id);
						if($result1 == false){
							if($CardioVasculaireDepartMapper->lastError == BAD_MATCH) $result1=0;
							else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
						}
*/						
						$result2 = $CardioVasculaireDepartMapper->getdernierExamsRCVA($dossier->id);
						if($result2 == false){
							if($CardioVasculaireDepartMapper->lastError == BAD_MATCH) $result2=0;
							else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
						}
						
							
						$glycemie = $CardioVasculaireDepartMapper->getGlycemieDiab($dossier->id);
/*						if(($result1!=0)||($result2!=0))
						{
							if($result1!=0)
								$resultHTA=$result1[0];

*/							if(($result2!=0)||($glycemie!=false)){
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
/*if(!isset($resultHTA)){
	$resultHTA['dpoids']=$resultHTA['dTA']=$resultHTA['dcoeur']=$resultHTA['dcreat']=$resultHTA['dproteinurie']='0000-00-00';
	$resultHTA['dhematurie']=$resultHTA['dgly']=$resultHTA['dkaliemie']=$resultHTA['dHDL']='0000-00-00';
	$resultHTA['dLDL']=$resultHTA['dFond']=$resultHTA['dECG']='0000-00-00';
	$resultHTA['alcool']=$resultHTA['tabac']="";
}
*/
//$CardioVasculaireDepart->traitement=array();
							if(($resultRCVA['dpoids']>'0000-00-00'))//||($resultHTA['dpoids']>'0000-00-00'))
							{
//								if($resultRCVA['dpoids']>$resultHTA['dpoids']){
								    $poids= $CardioVasculaireDepartMapper->getPoidsRCVA($dossier->id, $resultRCVA['dpoids']);
									$CardioVasculaireDepart->dpoids=$resultRCVA['dpoids'];
/*								}
								else{
								    $poids= $CardioVasculaireDepartMapper->getPoidsHTA($dossier->id, $resultHTA['dpoids']);
									$CardioVasculaireDepart->dpoids=$resultHTA['dpoids'];
								}
*/								$poids=$poids[0];
								$CardioVasculaireDepart->poids=$poids['poids'];
							}

							if(($resultRCVA['dTA']>'0000-00-00'))//||($resultHTA['dTA']>'0000-00-00'))
							{
//								if($resultRCVA['dTA']>$resultHTA['dTA']){
								    $tension= $CardioVasculaireDepartMapper->getTensionRCVA($dossier->id, $resultRCVA['dTA']);
									$CardioVasculaireDepart->dTA=$resultRCVA['dTA'];
/*								}
								else{
								    $tension= $CardioVasculaireDepartMapper->getTensionHTA($dossier->id, $resultHTA['dTA']);
									$CardioVasculaireDepart->dTA=$resultHTA['dTA'];
								}
*/								$tension=$tension[0];
								$CardioVasculaireDepart->TaSys=$tension['TaSys'];
								$CardioVasculaireDepart->TaDia=$tension['TaDia'];
								$CardioVasculaireDepart->HTA=$tension['HTA'];
								$CardioVasculaireDepart->TA_mode=$tension['TA_mode'];
								
/*								if(($tension['TaSys']>140)||($tension['TaDia']>80)){
									$CardioVasculaireDepart->HTA="oui";
								}
								else{
									$CardioVasculaireDepart->HTA="non";
								}
*/							}

							if(($resultRCVA['exam_cardio']>'0000-00-00'))//||($resultHTA['dcoeur']>'0000-00-00'))
							{
//								if($resultRCVA['exam_cardio']>$resultHTA['dcoeur']){
									$CardioVasculaireDepart->exam_cardio=$resultRCVA['exam_cardio'];
/*								}
								else{
									$CardioVasculaireDepart->exam_cardio=$resultHTA['dcoeur'];
								}
*/							}

							if(($resultRCVA['dCreat']>'0000-00-00'))//||($resultHTA['dcreat']>'0000-00-00'))
							{
//								if($resultRCVA['dCreat']>$resultHTA['dcreat']){
								    $creat= $CardioVasculaireDepartMapper->getCreatRCVA($dossier->id, $resultRCVA['dCreat']);
									$CardioVasculaireDepart->dCreat=$resultRCVA['dCreat'];
/*								}
								else{
								    $creat= $CardioVasculaireDepartMapper->getCreatHTA($dossier->id, $resultHTA['dcreat']);
									$CardioVasculaireDepart->dCreat=$resultHTA['dcreat'];
								}
*/								$creat=$creat[0];
								$CardioVasculaireDepart->Creat=$creat['Creat'];
							}

							if(($resultRCVA['dproteinurie']>'0000-00-00'))//||($resultHTA['dproteinurie']>'0000-00-00'))
							{
//								if($resultRCVA['dproteinurie']>$resultHTA['dproteinurie']){
								    $proteinurie= $CardioVasculaireDepartMapper->getProteinurieRCVA($dossier->id, $resultRCVA['dproteinurie']);
									$CardioVasculaireDepart->dproteinurie=$resultRCVA['dproteinurie'];
/*								}
								else{
								    $proteinurie= $CardioVasculaireDepartMapper->getProteinurieHTA($dossier->id, $resultHTA['dproteinurie']);
									$CardioVasculaireDepart->dproteinurie=$resultHTA['dproteinurie'];
								}
*/								$proteinurie=$proteinurie[0];
								$CardioVasculaireDepart->proteinurie=$proteinurie['proteinurie'];
							}

							if(($resultRCVA['dhematurie']>'0000-00-00'))//||($resultHTA['dhematurie']>'0000-00-00'))
							{
//								if($resultRCVA['dhematurie']>$resultHTA['dhematurie']){
								    $hematurie= $CardioVasculaireDepartMapper->getHematurieRCVA($dossier->id, $resultRCVA['dhematurie']);
									$CardioVasculaireDepart->dhematurie=$resultRCVA['dhematurie'];
/*								}
								else{
								    $hematurie= $CardioVasculaireDepartMapper->getHematurieHTA($dossier->id, $resultHTA['dhematurie']);
									$CardioVasculaireDepart->dhematurie=$resultHTA['dhematurie'];
								}
*/								$hematurie=$hematurie[0];
								$CardioVasculaireDepart->hematurie=$hematurie['hematurie'];
							}

							
							if(($resultRCVA['dgly']>'0000-00-00')||($glycemie!=false))//||($resultHTA['dgly']>'0000-00-00'))
							{
//								if($resultRCVA['dgly']>$resultHTA['dgly']){
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

									
									
/*								}
								else{
								    $glycemie= $CardioVasculaireDepartMapper->getGlycemieHTA($dossier->id, $resultHTA['dgly']);
									$CardioVasculaireDepart->dgly=$resultHTA['dgly'];
								}
*/							}

							if(($resultRCVA['dkaliemie']>'0000-00-00'))//||($resultHTA['dkaliemie']>'0000-00-00'))
							{
//								if($resultRCVA['dkaliemie']>$resultHTA['dkaliemie']){
								    $kaliemie= $CardioVasculaireDepartMapper->getKaliemieRCVA($dossier->id, $resultRCVA['dkaliemie']);
									$CardioVasculaireDepart->dkaliemie=$resultRCVA['dkaliemie'];
/*								}
								else{
								    $kaliemie= $CardioVasculaireDepartMapper->getKaliemieHTA($dossier->id, $resultHTA['dkaliemie']);
									$CardioVasculaireDepart->dkaliemie=$resultHTA['dkaliemie'];
								}
*/								$kaliemie=$kaliemie[0];
								$CardioVasculaireDepart->kaliemie=$kaliemie['kaliemie'];
							}

							if(($resultRCVA['dHDL']>'0000-00-00'))//||($resultHTA['dHDL']>'0000-00-00'))
							{
//								if($resultRCVA['dHDL']>$resultHTA['dHDL']){
								    $HDL= $CardioVasculaireDepartMapper->getHDLRCVA($dossier->id, $resultRCVA['dHDL']);
									$CardioVasculaireDepart->dHDL=$resultRCVA['dHDL'];
/*								}
								else{
								    $HDL= $CardioVasculaireDepartMapper->getHDLHTA($dossier->id, $resultHTA['dHDL']);
									$CardioVasculaireDepart->dHDL=$resultHTA['dHDL'];
								}
*/								$HDL=$HDL[0];
								$CardioVasculaireDepart->HDL=$HDL['HDL'];
							}

							if(($resultRCVA['dLDL']>'0000-00-00'))//||($resultHTA['dLDL']>'0000-00-00'))
							{
//								if($resultRCVA['dLDL']>$resultHTA['dLDL']){
								    $LDL= $CardioVasculaireDepartMapper->getLDLRCVA($dossier->id, $resultRCVA['dLDL']);
									$CardioVasculaireDepart->dLDL=$resultRCVA['dLDL'];
/*								}
								else{
								    $LDL= $CardioVasculaireDepartMapper->getLDLHTA($dossier->id, $resultHTA['dLDL']);
									$CardioVasculaireDepart->dLDL=$resultHTA['dLDL'];
								}
*/								$LDL=$LDL[0];
								$CardioVasculaireDepart->LDL=$LDL['LDL'];
							}

							if(($resultRCVA['dFond']>'0000-00-00'))//||($resultHTA['dFond']>'0000-00-00'))
							{
//								if($resultRCVA['dFond']>$resultHTA['dFond']){
									$CardioVasculaireDepart->dFond=$resultRCVA['dFond'];
/*								}
								else{
									$CardioVasculaireDepart->dFond=$resultHTA['dFond'];
								}
*/							}

							if(($resultRCVA['dECG']>'0000-00-00'))//||($resultHTA['dECG']>'0000-00-00'))
							{
//								if($resultRCVA['dECG']>$resultHTA['dECG']){
									$CardioVasculaireDepart->dECG=$resultRCVA['dECG'];
/*								}
								else{
									$CardioVasculaireDepart->dECG=$resultHTA['dECG'];
								}
*/							}

/*						if($resultHTA['tabac']=='1'){
							$CardioVasculaireDepart->tabac='oui';
						}
						if($resultHTA['alcool']=='1'){
							$CardioVasculaireDepart->alcool='oui';
						}
*/						if($resultRCVA['darret']>'0000-00-00'){
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
						    $autres= $CardioVasculaireDepartMapper->getAutreExam($dossier->id);
							
							foreach($autres as $autre){
								$CardioVasculaireDepart->antecedants=$autre['antecedants'];
								$CardioVasculaireDepart->traitement=split(',',$autre['traitement']);
								$CardioVasculaireDepart->dosage=$autre['dosage'];
								$CardioVasculaireDepart->hypertenseur3=$autre['hypertenseur3'];
								$CardioVasculaireDepart->automesure=$autre['automesure'];
								$CardioVasculaireDepart->diuretique=$autre['diuretique'];
								$CardioVasculaireDepart->HVG=$autre['HVG'];
								$CardioVasculaireDepart->activite=$autre['activite'];
							}
							
						}
						

						}

						$CardioVasculaireDepart = $CardioVasculaireDepart->afterDeserialisation($account);

						if($param->param1==PARAM_EDIT){
							forward($this->mappingTable["URL_NEW_COMPLEMENT"]);
						}
						else{
							forward($this->mappingTable["URL_NEW"]);
						}
						break;

				case ACTION_SAVE:
						exitIfNull($dossier);
						exitIfNull($CardioVasculaireDepart);
						exitIfNullOrEmpty($CardioVasculaireDepart->date);
						$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);

						$CardioVasculaireDepart->id = $dossier->id;

						$errors = $CardioVasculaireDepart->check();

						if(count($errors) !=0) forward($this->mappingTable["URL_NEW"],$errors);

						$result = $CardioVasculaireDepartMapper->findObject($CardioVasculaireDepart->beforeSerialisation($account));

						if($result == false){

							if(($CardioVasculaireDepartMapper->lastError != BAD_MATCH)&&($CardioVasculaireDepartMapper->lastError!=PARAM)) forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");

							$result = $CardioVasculaireDepartMapper->createObject($CardioVasculaireDepart->beforeSerialisation($account));

							if($result == false) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la création");
							forward($this->mappingTable["URL_AFTER_CREATE"]);
						}
						else{
							$result = $CardioVasculaireDepartMapper->updateObject($CardioVasculaireDepart->beforeSerialisation($account));

							if($result == false) {
								if($CardioVasculaireDepartMapper->lastError != NOTHING_UPDATED)
									forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour");
							}
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
					forward($this->mappingTable["URL_AFTER_DELETE"]);


				case ACTION_LIST:
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




				default:
					echo("ACTION IS NULL");
					break;
			}
		}

	}
?> 
