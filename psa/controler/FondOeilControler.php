<?php 
	
	require_once("bean/FondOeil.php");
	require_once("bean/ControlerParams.php");
	require_once("persistence/FondOeilMapper.php");
	require_once("GenericControler.php");
	
	class FondOeilControler{
	
		var $mappingTable;
		
		function FondOeilControler() {
			$this->mappingTable = 
			array(
			"URL_NEW"=>"view/fondoeil/newfond.php",
			"URL_AFTER_CREATE"=>"view/fondoeil/viewfondaftercreate.php",
			"URL_AFTER_UPDATE"=>"view/fondoeil/viewfondaftercreate.php",
			"URL_AFTER_LIST"=>"view/fondoeil/listfond.php",
			"URL_ON_CALLBACK_FAIL"=>"view/");
		}
	

		function start(){

			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;
			global $dossier;
			global $FondOeil;


			if(array_key_exists("outDateReference",$objects))
				$outDateReference = $objects["outDateReference"];

			global $dossier;
			if(array_key_exists("dossier",$objects))
				$dossier = $objects["dossier"];

			if(array_key_exists("FondOeil",$objects))
				$FondOeil = $objects["FondOeil"];


			$ledgerFactory = new LedgerFactory();
			$ledger = $ledgerFactory->getLedger("Controler","FondOeilControler");

			//Create connection factory
			$cf = new ConnectionFactory();

			//create mappers
			$FondOeilMapper = new FondOeilMapper($cf->getConnection());
			$dossierMapper = new DossierMapper($cf->getConnection());

			$ledger->writeArray(I,"Start","Control Parameters = ",$param);


			switch($param->action){

				case ACTION_NEW:

					$FondOeil = new FondOeil();
					$dossier=new Dossier();


					forward($this->mappingTable["URL_NEW"]);
					break;

				case ACTION_SAVE:
						exitIfNull($FondOeil);
						exitIfNull($dossier);

						$dossier = checkDossier($dossier,$dossierMapper,$account->cabinet);
						
						$errors = $FondOeil->check();

						$fichier_joint = $_FILES['fichier_joint']['tmp_name'];
						$fichier_joint_name = $_FILES['fichier_joint']['name'];
						$fichier_joint_size = $_FILES['fichier_joint']['size'];
						$fichier_joint_type = $_FILES['fichier_joint']['type'];
						$fichier_joint_error = $_FILES['fichier_joint']['error'];
						
						$i=count($errors)-1;
						
						if ($fichier_joint_error>0)
						{
							switch ($fichier_joint_error)
							{
							    case 2: $errors[$i++] = 'Le fichier dépasse la taille maximum admise'; break;
								case 3: $errors[$i++] = 'fichier partiellement téléchargé, recommencez plus tard'; break;
								case 4: $errors[$i++] = "le fichier n'a pas été téléchargé, recommencez ultérieurement"; break;
								default: $errors[$i++]= "problème lors du téléchargement"; break;
							}
							exit;
						}

						$upfile="../view/fondoeil/images/".$fichier_joint_name;
						if (is_uploaded_file($fichier_joint))
						{
						    if (!move_uploaded_file($fichier_joint, $upfile))
						    {
						        $errors[$i++]='problème : impossible de télécharger';
						        exit;
						    }
						}

						if(count($errors) !=0) forward($this->mappingTable["URL_NEW"],$errors);
						
						$FondOeil->id=$dossier->id;


						$FondOeil->fichier=$upfile;

						$result = $FondOeilMapper->createObject($FondOeil->beforeSerialisation($account));

						$FondOeil->fichier=$fichier_joint_name;
						forward($this->mappingTable["URL_AFTER_CREATE"]);
						break;


				case ACTION_LIST :
/*					$result = $FondOeilMapper->getObjectsByDossier($account->cabinet, $dossier->numero);

					if($result == false){
						if($FondOeilMapper->lastError == BAD_MATCH) forward($this->mappingTable["URL_AFTER_LIST"],"Pas d'enregistrements trouvés");
						else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find objects caused an error");
					}
*/
					global $rowsList;
					$rowsList=array();
//					$rowsList = $result;

					$dir="../view/diabete/suivi/foeil/".strtolower($account->cabinet);

					if (is_dir($dir)) {
					    if ($dh = opendir($dir)) {
					        while (($file = readdir($dh)) !== false) {
					                $under=strpos($file, "_");

									if($under!==false){
									    $dos=explode("_", $file);
									    
										if($dos[0]==$dossier->numero){
										    if ($dh2 = opendir($dir.'/'.$file)) {
										        while (($file2 = readdir($dh2)) !== false) {
										            if(($file2!=".")&&($file2!="..")){
													    $fond["fichier"]=$dir .'/'. $file . '/'. $file2;
													    $fond["date"]=$dos[1];

														$rowsList[]=$fond;
										            }
										        }
										    }
										}
									}
					        }
					        closedir($dh);
					    }
					}

					if(count($rowsList)==0){
						forward($this->mappingTable["URL_AFTER_LIST"],"Pas d'enregistrements trouvés");
					}
					forward($this->mappingTable["URL_AFTER_LIST"]);
				

				default:
					echo("ACTION IS NULL");
					break;
			}
		}

	}
?> 
