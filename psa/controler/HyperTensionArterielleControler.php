<?php 
	
	require_once("bean/HyperTensionArterielle.php");
	require_once("bean/ControlerParams.php");
	require_once("persistence/HyperTensionArterielleMapper.php");
	require_once("GenericControler.php");
	
	class HyperTensionArterielleControler{
	
		var $mappingTable;
		
		function HyperTensionArterielleControler() {
			$this->mappingTable = 
			array(
			"URL_MANAGE"=>"view/hypertension/managehypertension.php",
			"URL_NEW"=>"view/hypertension/newhypertension.php",
//			"URL_AFTER_CREATE"=>new ControlerParams("HyperTensionArterielleControler",ACTION_MANAGE,true),
//			"URL_AFTER_UPDATE"=>new ControlerParams("HyperTensionArterielleControler",ACTION_MANAGE,true),
			"URL_AFTER_CREATE"=>"view/hypertension/viewhypertensionaftercreate.php",
			"URL_AFTER_UPDATE"=>"view/hypertension/viewhypertensionaftercreate.php",
			"URL_AFTER_FIND_VIEW"=>"view/hypertension/viewhypertension.php",
			"URL_AFTER_FIND_EDIT"=>"view/hypertension/newhypertension.php",
			"URL_AFTER_DELETE"=>new ControlerParams("HyperTensionArterielleControler",ACTION_MANAGE,true),
			"URL_AFTER_LIST"=>"view/hypertension/listhypertension.php",
			"URL_AFTER_FIND_LIST_DOSSIER"=>"view/hypertension/listhypertensionbydossier.php",
			"URL_MANAGE_OUTDATED"=>"view/hypertension/managealertehypertension.php",
			"URL_AFTER_LIST_OUTDATED"=>"view/hypertension/listhypertensionalerte.php",
			"URL_ON_CALLBACK_FAIL"=>"view/");
		}
	

		function start(){

			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;
			global $dossier;
			global $HyperTensionArterielle;
			global $outDateReference;
			global $HyperTensionArterielleList;


			if(array_key_exists("outDateReference",$objects))
				$outDateReference = $objects["outDateReference"];

			global $dossier;
			if(array_key_exists("dossier",$objects))
				$dossier = $objects["dossier"];

			if(array_key_exists("HyperTensionArterielle",$objects))
				$HyperTensionArterielle = $objects["HyperTensionArterielle"];


			$ledgerFactory = new LedgerFactory();
			$ledger = $ledgerFactory->getLedger("Controler","HyperTensionArterielleControler");

			//Create connection factory
			$cf = new ConnectionFactory();

			//create mappers
			$HyperTensionArterielleMapper = new HyperTensionArterielleMapper($cf->getConnection());
			$dossierMapper = new DossierMapper($cf->getConnection());

			$ledger->writeArray(I,"Start","Control Parameters = ",$param);


			switch($param->action){
				case ACTION_MANAGE:
					$dossier = new Dossier();
					$HyperTensionArterielle = new HyperTensionArterielle();
					$HyperTensionArterielle->date= date("d/m/Y");

					if(!$param->isParam1Valid())
						forward($this->mappingTable["URL_MANAGE"]);
					else
					{
					    switch($param->param1){
							case PARAM_OUTDATED:
								$outDateReference = new OutDateReference();
								forward($this->mappingTable["URL_MANAGE_OUTDATED"]);
							default:
								forward($this->mappingTable["URL_MANAGE"]);
						}

					}
					break;

				case ACTION_FIND:
						if(!$param->isParam1Valid())
							forward(URL_CONTROLER_PARAMS_ERROR,"Invalid param1");
						exitIfNull($dossier);
						exitIfNull($HyperTensionArterielle);
						exitIfNullOrEmpty($HyperTensionArterielle->date);
						$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);

						$HyperTensionArterielle->id = $dossier->id;
						$result = $HyperTensionArterielleMapper->findObject($HyperTensionArterielle->beforeSerialisation($account));

						if($result == false)
						{
							if($HyperTensionArterielleMapper->lastError == BAD_MATCH)
								forward($this->mappingTable["URL_MANAGE"],"Cet enregistrement n'existe pas");
							else
								forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
						}
						$HyperTensionArterielle = $result->afterDeserialisation($account);

						if($param->param1 == PARAM_EDIT)
						{
							$result = $HyperTensionArterielleMapper->getdernierExams($dossier->id);
							if($result == false){
								if($HyperTensionArterielleMapper->lastError == BAD_MATCH) $result=0;
								else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
							}

							global $rowsList;

						if($result!=0)
						{
							$rowsList = array_natsort($result,"id","id");

							$rowsList=$rowsList[0];

							if(($rowsList['dpoids']!='')&&($rowsList['dpoids']!='0000-00-00'))
							{
							    $poids= $HyperTensionArterielleMapper->getPoids($dossier->id, $rowsList['dpoids']);
								$poids=$poids[0];
							    $rowsList['poids']=$poids['poids'];
							}
							if(($rowsList['dtension']!='')&&($rowsList['dtension']!='0000-00-00'))
							{
							    $tension= $HyperTensionArterielleMapper->getTension($dossier->id, $rowsList['dtension']);
							    $tension=$tension[0];
							    $rowsList['TaSys']=$tension['TaSys'];
							    $rowsList['TaDia']=$tension['TaDia'];
							}
							if(($rowsList['dcreat']!='')&&($rowsList['dcreat']!='0000-00-00'))
							{
							    $Creat=$HyperTensionArterielleMapper->getCreat($dossier->id, $rowsList['dcreat']);
							    $Creat=$Creat[0];
							    $rowsList['Creat']= $Creat['Creat'];
							}
							if(($rowsList['dproteinurie']!='')&&($rowsList['dproteinurie']!='0000-00-00'))
							{
							    $proteinurie=$HyperTensionArterielleMapper->getProteinurie($dossier->id, $rowsList['dproteinurie']);
							    $proteinurie=$proteinurie[0];
							    $rowsList['proteinurie']= $proteinurie['proteinurie'];
							}
							if(($rowsList['dhematurie']!='')&&($rowsList['dhematurie']!='0000-00-00'))
							{
							    $hematurie=$HyperTensionArterielleMapper->getHematurie($dossier->id, $rowsList['dhematurie']);
							    $hematurie=$hematurie[0];
           						$rowsList['hematurie']= $hematurie['hematurie'];
							}
							if(($rowsList['dglycemie']!='')&&($rowsList['dglycemie']!='0000-00-00'))
							{
							    $gly= $HyperTensionArterielleMapper->getGlycemie($dossier->id, $rowsList['dglycemie']);
							    $gly=$gly[0];
							    $rowsList['glycemie']=$gly['glycemie'];
							}
							if(($rowsList['dkaliemie']!='')&&($rowsList['dkaliemie']!='0000-00-00'))
							{
							    $kaliemie=$HyperTensionArterielleMapper->getKaliemie($dossier->id, $rowsList['dkaliemie']);
							    $kaliemie=$kaliemie[0];
								$rowsList['kaliemie']= $kaliemie['kaliemie'];
							}
							if(($rowsList['dChol']!='')&&($rowsList['dChol']!='0000-00-00'))
							{
							    $HDL=$HyperTensionArterielleMapper->getHDL($dossier->id, $rowsList['dChol']);
							    $HDL=$HDL[0];
							    $rowsList['HDL']= $HDL['HDL'];
							}
							if(($rowsList['dLDL']!='')&&($rowsList['dLDL']!='0000-00-00'))
							{
							    $LDL=$HyperTensionArterielleMapper->getLDL($dossier->id, $rowsList['dLDL']);
							    $LDL=$LDL[0];
							    $rowsList['LDL']= $LDL['LDL'];
							}

						}
							forward($this->mappingTable["URL_AFTER_FIND_EDIT"]);
						}
						else
							forward($this->mappingTable["URL_AFTER_FIND_VIEW"]);

						echo "Fonction indisponible actuellement";
						break;

				case ACTION_NEW:
						exitIfNull($dossier);
						exitIfNull($HyperTensionArterielle);
						exitIfNullOrEmpty($HyperTensionArterielle->date);

						if(!isValidDate($HyperTensionArterielle->date))
							forward($this->mappingTable["URL_MANAGE"],"La date du dépistage est invalide");
						$dossier = checkDossierActif($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);

						$HyperTensionArterielle->id = $dossier->id;

						$result = $HyperTensionArterielleMapper->findObject($HyperTensionArterielle->beforeSerialisation($account));
						if($result == false){
							if($HyperTensionArterielleMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"dossier non trouvé");
						}
						else
							forward($this->mappingTable["URL_MANAGE"],"Cet enregistrement existe dejà, Appuyer sur Modifier");

						$result = $HyperTensionArterielleMapper->getdernierExams($dossier->id);
						if($result == false){
							if($HyperTensionArterielleMapper->lastError == BAD_MATCH) $result=0;
							else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
						}

						global $rowsList;

						if($result!=0)
						{
							$rowsList = array_natsort($result,"id","id");
							
							$rowsList=$rowsList[0];

							if(($rowsList['dpoids']!='')&&($rowsList['dpoids']!='0000-00-00'))
							{
							    $poids= $HyperTensionArterielleMapper->getPoids($dossier->id, $rowsList['dpoids']);
								$poids=$poids[0];
							    $rowsList['poids']=$poids['poids'];
							}
							if(($rowsList['dtension']!='')&&($rowsList['dtension']!='0000-00-00'))
							{
							    $tension= $HyperTensionArterielleMapper->getTension($dossier->id, $rowsList['dtension']);
							    $tension=$tension[0];
							    $rowsList['TaSys']=$tension['TaSys'];
							    $rowsList['TaDia']=$tension['TaDia'];
							}
							if(($rowsList['dcreat']!='')&&($rowsList['dcreat']!='0000-00-00'))
							{
							    $Creat=$HyperTensionArterielleMapper->getCreat($dossier->id, $rowsList['dcreat']);
							    $Creat=$Creat[0];
							    $rowsList['Creat']= $Creat['Creat'];
							}
							if(($rowsList['dproteinurie']!='')&&($rowsList['dproteinurie']!='0000-00-00'))
							{
							    $proteinurie=$HyperTensionArterielleMapper->getProteinurie($dossier->id, $rowsList['dproteinurie']);
							    $proteinurie=$proteinurie[0];
							    $rowsList['proteinurie']= $proteinurie['proteinurie'];
							}
							if(($rowsList['dhematurie']!='')&&($rowsList['dhematurie']!='0000-00-00'))
							{
							    $hematurie=$HyperTensionArterielleMapper->getHematurie($dossier->id, $rowsList['dhematurie']);
							    $hematurie=$hematurie[0];
           						$rowsList['hematurie']= $hematurie['hematurie'];
							}
							if(($rowsList['dglycemie']!='')&&($rowsList['dglycemie']!='0000-00-00'))
							{
							    $gly= $HyperTensionArterielleMapper->getGlycemie($dossier->id, $rowsList['dglycemie']);
							    $gly=$gly[0];
							    $rowsList['glycemie']=$gly['glycemie'];
							}
							if(($rowsList['dkaliemie']!='')&&($rowsList['dkaliemie']!='0000-00-00'))
							{
							    $kaliemie=$HyperTensionArterielleMapper->getKaliemie($dossier->id, $rowsList['dkaliemie']);
							    $kaliemie=$kaliemie[0];
								$rowsList['kaliemie']= $kaliemie['kaliemie'];
							}
							if(($rowsList['dChol']!='')&&($rowsList['dChol']!='0000-00-00'))
							{
							    $HDL=$HyperTensionArterielleMapper->getHDL($dossier->id, $rowsList['dChol']);
							    $HDL=$HDL[0];
							    $rowsList['HDL']= $HDL['HDL'];
							}
							if(($rowsList['dLDL']!='')&&($rowsList['dLDL']!='0000-00-00'))
							{
							    $LDL=$HyperTensionArterielleMapper->getLDL($dossier->id, $rowsList['dLDL']);
							    $LDL=$LDL[0];
							    $rowsList['LDL']= $LDL['LDL'];
							}

						}

						forward($this->mappingTable["URL_NEW"]);
						break;

				case ACTION_SAVE:
						exitIfNull($dossier);
						exitIfNull($HyperTensionArterielle);
						exitIfNullOrEmpty($HyperTensionArterielle->date);
						$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet,true,$this->mappingTable["URL_MANAGE"]);

						$HyperTensionArterielle->id = $dossier->id;

						$errors = $HyperTensionArterielle->check();

						if(count($errors) !=0) forward($this->mappingTable["URL_NEW"],$errors);

						$result = $HyperTensionArterielleMapper->findObject($HyperTensionArterielle->beforeSerialisation($account));

						if($result == false){

							if(($HyperTensionArterielleMapper->lastError != BAD_MATCH)&&($HyperTensionArterielleMapper->lastError!=PARAM)) forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");

							$result = $HyperTensionArterielleMapper->createObject($HyperTensionArterielle->beforeSerialisation($account));

							if($result == false) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la création");
							forward($this->mappingTable["URL_AFTER_CREATE"]);
						}
						else{
							$result = $HyperTensionArterielleMapper->updateObject($HyperTensionArterielle->beforeSerialisation($account));

							if($result == false) {
								if($HyperTensionArterielleMapper->lastError != NOTHING_UPDATED)
									forward(URL_CONTROLER_PERSISTENCE_ERROR,"Echec lors de la mise à jour");
							}
							forward($this->mappingTable["URL_AFTER_UPDATE"]);
						}
						break;

				case ACTION_DELETE:

					exitIfNull($dossier);
					exitIfNull($HyperTensionArterielle);
					exitIfNullOrEmpty($HyperTensionArterielle->date);

					$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet);

					$HyperTensionArterielle->id = $dossier->id;

					$result = $HyperTensionArterielleMapper->deleteObject($HyperTensionArterielle->beforeSerialisation($account));
					if($result == false){
						if($HyperTensionArterielleMapper->lastError != NOTHING_DELETED) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Delete object caused an error");
					}
					forward($this->mappingTable["URL_AFTER_DELETE"]);


				case ACTION_LIST:
					switch($param->param1){
						case PARAM_LIST_BY_DOSSIER:
							$result = $HyperTensionArterielleMapper->getObjectsByDossier($account->cabinet, $dossier->numero);
							if($result == false){
								if($HyperTensionArterielleMapper->lastError == BAD_MATCH) forward(new ControlerParams($param->controler,ACTION_MANAGE,true),"Pas d'enregistrements trouvés");
								else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
							}
							global $rowsList;
							$rowsList = array_natsort($result,"numero","numero");

							forward($this->mappingTable["URL_AFTER_FIND_LIST_DOSSIER"]);
	      				case PARAM_OUTDATED:
							exitIfNull($outDateReference);
							$result = $HyperTensionArterielleMapper->getExpiredExams($account->cabinet,$outDateReference->period);
							if($result == false){
								if($HyperTensionArterielleMapper->lastError == BAD_MATCH) forward($this->mappingTable["URL_MANAGE_OUTDATED"],"Pas d'enregistrements trouvés");
								else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find object caused an error");
							}
							else
								if(count($result)==0) forward($this->mappingTable["URL_MANAGE_OUTDATED"],"Pas d'enregistrements trouvés");

								global $rowsList;
								$rowsList = array_natsort($result,"numero","numero");
							forward($this->mappingTable["URL_AFTER_LIST_OUTDATED"]);
							break;

						default:
							$result = $HyperTensionArterielleMapper->getObjectsByCabinet($account->cabinet);
							if($result == false){
								if($HyperTensionArterielleMapper->lastError == BAD_MATCH) forward(new ControlerParams($param->controler,ACTION_MANAGE,true),"Pas d'enregistrements trouvés");
								else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
							}
							global $rowsList;
							$rowsList = array_natsort($result,"numero","numero");


							forward($this->mappingTable["URL_AFTER_LIST"]);
					}




				default:
					echo("ACTION IS NULL");
					break;
			}
		}

//		function start() {
//			$this->genericControler("HyperTensionArterielleControler","HyperTensionArterielle","HyperTensionArterielle","HyperTensionArterielleMapper",$this->mappingTable);
//		}
	}
?> 
