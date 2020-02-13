<?php 

/**
 * Created by Codelobster
 * User: Gisgo
 * Date: 22-11-2018
 * Time: 14:40
 */
	
	require_once("bean/Rib.php");
	require_once("bean/ControlerParams.php");
	require_once("persistence/RibMapper.php");
	require_once("GenericControler.php");
	require_once("tools/formulas.php");

	require_once("Config.php");
	$config = new Config();
	require_once($config->rest_path . '/GetCabsAndLogins.php');
	
	class RibControler{
	
		var $mappingTable;
    	var $config;
		
		function RibControler() {
			$this->config = new Config();
			$this->mappingTable =
			array(
			"URL_NEW"=>"view/rib/newrib.php",
			"URL_AFTER_CREATE"=>"view/rib/viewribaftercreate.php",
			"URL_ON_CALLBACK_FAIL"=>"view/");
		}
	
		function dForward($param,$url,$message=NULL,$resetRequest=false){						
				forward($this->getForward($param,$url),$message,$resetRequest);			
		}
		
		function getForward($param,$url){
			if($param == PARAM_STAND_ALONE)							
				return $this->mappingTable[$url."_STA"];
			else 
				return $this->mappingTable[$url];
		}

		function start(){

			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;
			global $Rib;

			if(array_key_exists("Rib",$objects))
				$Rib = $objects["Rib"];

			$ledgerFactory = new LedgerFactory();
			$ledger = $ledgerFactory->getLedger("Controler","CongesControler");

			//Create connection factory
			$cf = new ConnectionFactory();

			//create mappers
			$RibMapper = new RibMapper($cf->getConnection());

			$ledger->writeArray(I,"Start","Control Parameters = ",$param);


			switch($param->action){
				
				
				case ACTION_NEW:

					$Rib=new Rib();
					forward($this->mappingTable["URL_NEW"]);
				break;


				case ACTION_SAVE:
					exitIfNull($Rib);

					$errors = $Rib->check();

					if(count($errors) != 0)
						$this->dForward($param->param3,"URL_NEW",$errors);


					include($this->config->app_path . $this->config->psa_path . '/lib/htmlMimeMail-2.5.1/htmlMimeMail.php');
					$upfile="";
					if (!empty($_FILES['pj']['name']))
					{

						$piece=1;
						
						$pj = $_FILES['pj']['tmp_name'];
						$pj_name = $_FILES['pj']['name'];
						$pj_size = $_FILES['pj']['size'];
						$pj_type = $_FILES['pj']['type'];
						$pj_error = $_FILES['pj']['error'];

						
						$remplacement=array("�"=>"e",
											"�"=>"e",
                            				"�"=>"e",
                            				"�"=>"e",
											"�"=>"a",
											"�"=>"a",
											"�"=>"a",
											"�"=>"a",
											"�"=>"i",
											"�"=>"i",
											"�"=>"i",
											"�"=>"i",
											"�"=>"u",
                            				"�"=>"u",
											"�"=>"u",
											"�"=>"u",
											"�"=>"o",
											"�"=>"o",
											"�"=>"o",
											"�"=>"o",
											"�"=>"c",
											" "=>"_");

						foreach($remplacement as $rech=>$rempl){
							$pj_name=str_replace($rech, $rempl, $pj_name);
						}
						
						if ($pj_error>0)
						{
							$piece=0;

							switch ($pj_error)
							{
								case 2: echo 'La pi�ce jointe d�passe la taille maximum admise'; break;
								case 3: echo 'Pi�ce jointe partiellement t�l�charg�, recommencez plus tard';break;
								case 4: echo "la pi�ce jointe n'a pas �t� t�l�charg�, recommencez ult�rieurement"; break;
								default: echo "probl�me lors du t�l�chargement de la pi�ce jointe"; break;
							}
							exit;
						}

						//$upfile='/var/data/home/informed/www/_files/notes_de_frais/'.$pj_name;
                        $newDateString = date('Y-m-d_H-i-s');
                        $upfile = $this->config->files_path . '/notes_de_frais/Frais_'. $newDateString .'_login_'. $_SESSION["id.login"] .'_cab_'. $_SESSION["cabinet"] .'_'.$pj_name;
						
						$constant=explode(".", $pj_name);

						$ext=$constant[count($constant)-1];
                        if ($ext != "pdf" && $ext != "png" && $ext != "jpg" && $ext != "jpeg")
                            forward($this->mappingTable["URL_NEW"],"La pi�ce jointe n'est pas au bon format. Formats permis : pdf, png, jpg, jpeg");

                       
						
						if (is_uploaded_file($pj))
						{
							if (!move_uploaded_file($pj, $upfile))
							{
								$piece=0;
								echo 'probl�me : impossible de t�l�charger la pi�ce jointe.';
								exit;
							}
						}

					}

					
					$Rib->date_demande=date("YmdHis");
					#$infirmiere=$FraisMapper->getInfirmiere($account);
					
					$Rib->inf_login = $_SESSION['id.login'];
					$Rib->pj=$upfile;
					
					#var_dump($Frais);
					#$infosInf = GetInfosByLogin($_SESSION['cabinet'], $status);

					#var_dump($_SESSION['id.email']);exit;
					
					$result = $RibMapper->findObject($Rib->beforeSerialisation($account));									
					
					if($result == false){
						if($RibMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"find failed");
						$result = $RibMapper->createObject($Rib->beforeSerialisation($account));
						if($result == false) forward(URL_CONTROLER_PERSISTENCE_ERROR,"erreur lors de la cr�ation");


                       
						

						


						$mail = new htmlMimeMail();
						// echo $pj_type;
						if($piece==1){
							$attachment = $mail->getFile("$upfile");
							$mail->addAttachment($attachment, $pj_name, $pj_type);
						}


						$mail->setSubject($sujet);

						$mail->setHtml($corps);

						$mail->setFrom('"Portail psa" <contact@asalee.fr>');

						$mail->setCc("jl.fievre@medsyn.fr, ".$_SESSION['id.email'] );
					//	$mail->setBcc('xguillon@asalee.fr');

						if($_SERVER['APPLICATION_ENV']!='dev-herve'){
							$result = $mail->send(array('gestion@asalee.fr'));
						}
						
						
//EA TESTS	                                                                						$result = $mail->send(array('eaouad@free.fr'));
						
						forward($this->mappingTable["URL_AFTER_CREATE"]);
					}
					else{
						$result = $RibMapper->updateObject($Rib->beforeSerialisation($account));
						if($result == false) {
							if($RibMapper->lastError != NOTHING_UPDATED){
								forward(URL_CONTROLER_PERSISTENCE_ERROR,"update failed");	
							}
						}

                      
                       

						$mail = new htmlMimeMail();
						// echo $pj_type;
						if($piece==1){
							$attachment = $mail->getFile("$upfile");
							$mail->addAttachment($attachment, $pj_name, $pj_type);
						}


						$mail->setSubject($sujet);

						$mail->setHtml($corps);

						$mail->setFrom('"Portail psa" <contact@asalee.fr>');

						$mail->setCc("jl.fievre@medsyn.fr,  aderville@asalee.fr, asaleefrais@asalee.fr, ".$_SESSION['id.email'] );
					//	$mail->setBcc('xguillon@asalee.fr');

						if($_SERVER['APPLICATION_ENV']!='dev-herve'){
							$result = $mail->send(array('j.gautier@medsyn.fr'));
						}
						forward($this->mappingTable["URL_AFTER_UPDATE"]);
					}
					
					

				break;

				default:
					echo("ACTION IS NULL");
					break;
			}
		}

	}
?> 

