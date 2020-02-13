<?php 
	
	require_once("bean/HistoRCVA.php");
	require_once("bean/ControlerParams.php");
	require_once("persistence/HistoRCVAMapper.php");
	require_once("GenericControler.php");
	require_once("tools/formulas.php");
	
	class HistoRCVAControler{
	
		var $mappingTable;
		
		function HistoRCVAControler() {
			$this->mappingTable = 
			array(
			"URL_AFTER_LIST"=>"view/cardiovasculaire/histoexam.php",
			"URL_ON_CALLBACK_FAIL"=>"view/");
		}
	

		function start(){

			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;
			global $dossier;
			global $liste_exam;

			if(array_key_exists("outDateReference",$objects))
				$outDateReference = $objects["outDateReference"];

			global $dossier;
			if(array_key_exists("dossier",$objects))
				$dossier = $objects["dossier"];

			if(array_key_exists("HistoRCVA",$objects))
				$HistoRCVA = $objects["HistoRCVA"];

			$ledgerFactory = new LedgerFactory();
			$ledger = $ledgerFactory->getLedger("Controler","HistoRCVAControler");

			//Create connection factory
			$cf = new ConnectionFactory();

			//create mappers
			$HistoRCVAMapper = new HistoRCVAMapper($cf->getConnection());
			$dossierMapper = new DossierMapper($cf->getConnection());

			$ledger->writeArray(I,"Start","Control Parameters = ",$param);


			switch($param->action){
				case ACTION_LIST:

					exitIfNull($dossier);
					exitIfNull($HistoRCVA);
					exitIfNullOrEmpty($HistoRCVA->type_exam);
					$type_exam=$HistoRCVA->type_exam;
					
					global $affiche_resultat;
					$affiche_resultat=1;
					
					if(($type_exam=="foeil")||($type_exam=="ecg")||($type_exam=="exam_cardio")){
						$affiche_resultat=0;
					}

					global $liste_resultats;
					$liste_resultats=array();
					
					if($type_exam=="RCVA"){
						$date1an=date("Y");
						$date1an--;
						$date1an=$date1an."-".date("m")."-".date("d");
						$i=0;
						$req="SELECT min(date) from cardio_vasculaire_depart WHERE ".
							 "id='$dossier->id'";
						$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
						
						list($premier_suivi)=mysql_fetch_row($res);

						$req="SELECT sexe, dnaiss, max(dTA), max(dChol), max(dHDL) ".
							 "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
							 "and dTA>='$date1an' and dChol>='$date1an' and dHDL>='$date1an' and dossier.id='$dossier->id' ".
							 "group by dossier.id";
						$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
						
						list($sexe, $dnaiss, $dTA, $dChol, $dHDL)=mysql_fetch_row($res);
						
						$tension=$choltot=$hdl="";
						if(($dTA>'0000-00-00')&&($dChol>'0000-00-00')&&($dHDL>'0000-00-00')){
							$req2="SELECT TaSys FROM cardio_vasculaire_depart WHERE id='$dossier->id' and dTA='$dTA'";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
							
							list($tension)=mysql_fetch_row($res2);

							$req2="SELECT Chol FROM cardio_vasculaire_depart WHERE id='$dossier->id' and dChol='$dChol'";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
							
							list($choltot)=mysql_fetch_row($res2);
							
							$req2="SELECT HDL FROM cardio_vasculaire_depart WHERE id='$dossier->id' and dHDL='$dHDL'";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
							
							list($hdl)=mysql_fetch_row($res2);
						}
						
						$req2="SELECT HVG from cardio_vasculaire_depart WHERE id='$dossier->id' and (HVG='oui' or HVG='non') ".
							  "ORDER by date DESC limit 0,1";
						$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
						
						list($ventricule)=mysql_fetch_row($res2);
						
						$req2="SELECT surcharge_ventricule from cardio_vasculaire_depart WHERE id='$dossier->id' and ".
							  "(surcharge_ventricule='oui' or surcharge_ventricule='non') ".
							  "ORDER by date DESC limit 0,1";
						$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
						
						list($surcharge_ventricule)=mysql_fetch_row($res2);
						
						$req2="SELECT tabac from cardio_vasculaire_depart WHERE id='$dossier->id' and ".
							  "(tabac='oui' or tabac='non') ".
							  "ORDER by date DESC limit 0,1";
						$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
						
						list($tab)=mysql_fetch_row($res2);
						
						$calcul=1;
						if(($tab!="oui")&&($tab!="non")){
							$calcul=0;
						}
						if($tension==''){
							$calcul=0;
						}
						if($choltot==''){
							$calcul=0;
						}
						if($hdl==''){
							$calcul=0;
						}
						if(($ventricule!="oui")&&($ventricule!="non")&&($surcharge_ventricule!="oui")&&($surcharge_ventricule!="non")){
							$calcul=0;
						}
						
						if($calcul==1){
							$age=get_age($dnaiss, date("Y-m-d"));
							
							$req2="SELECT dossier_id from suivi_diabete WHERE dossier_id='$dossier->id' ";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
							if(mysql_num_rows($res2)>0){
								$diab=1;
							}
							else{
								$diab=0;
							}

							$rcv=get_rcva($sexe, $age, $diab, $tab, $tension, $choltot, $hdl, $ventricule, $surcharge_ventricule);
						}
						else{
							$rcv="NC";
						}

						$HistoRCVA = new HistoRCVA($type_exam, date("Y-m-d"), $rcv);
						$HistoRCVA = $HistoRCVA->afterDeserialisation($account);
						$liste_resultats[]=$HistoRCVA;
						// $liste_resultats[]=array("date"=>date("d/m/Y"), "valeur"=>$rcv);
						
						if($premier_suivi<"2006-12-31"){
							$premier_suivi="2006-12-31";
						}
						$date0=explode("-", $premier_suivi);

						$annee0=$date0[0];
						$mois0=$date0[1];

						$annee=date('Y');
						$mois=date('m');

						$mois--;


						if($mois<3)
						{
							$annee--;
							$mois=12;
						}
						elseif(($mois>=3)&&($mois<6))
						{
							$mois=3;
						}
						elseif(($mois>=6)&&($mois<9))
						{
							$mois=6;
						}
						elseif(($mois>=9)&&($mois<12))
						{
							$mois=9;
						}

						$jour[3]=$jour[12]=31;
						$jour[6]=$jour[9]=30;

						while(($annee>$annee0)||(($annee==$annee0)&&($mois>=$mois0)))
						{
							if($mois<10)
							{
								$date=$annee.'-0'.$mois.'-'.$jour[$mois];
							}
							else
							{
								$date=$annee.'-'.$mois.'-'.$jour[$mois];
							}


							$tab_date=explode('-', $date); //EA 22-04-2014


							$date1an=$tab_date[0];
							$date1an--;
							$date1an=$date1an."-".$tab_date[1]."-".$tab_date[2];

							$req="SELECT sexe, dnaiss, max(dTA), max(dChol), max(dHDL) ".
								 "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
								 "and dTA>='$date1an' and dChol>='$date1an' and dHDL>='$date1an' and dossier.id='$dossier->id' ".
								 "and dTA<='$date' and dChol<='$date' and dHDL<='$date' ".
								 "group by dossier.id";
							$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

							list($sexe, $dnaiss, $dTA, $dChol, $dHDL)=mysql_fetch_row($res);
							$tension=$choltot=$hdl="";
							if(($dTA>'0000-00-00')&&($dChol>'0000-00-00')&&($dHDL>'0000-00-00')){
								$req2="SELECT TaSys FROM cardio_vasculaire_depart WHERE id='$dossier->id' and dTA='$dTA'";
								$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
								
								list($tension)=mysql_fetch_row($res2);

								$req2="SELECT Chol FROM cardio_vasculaire_depart WHERE id='$dossier->id' and dChol='$dChol'";
								$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
								
								list($choltot)=mysql_fetch_row($res2);
								
								$req2="SELECT HDL FROM cardio_vasculaire_depart WHERE id='$dossier->id' and dHDL='$dHDL'";
								$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
								
								list($hdl)=mysql_fetch_row($res2);
							}
							
							$req2="SELECT HVG from cardio_vasculaire_depart WHERE id='$dossier->id' and (HVG='oui' or HVG='non') ".
								  "ORDER by date DESC limit 0,1";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
							
							list($ventricule)=mysql_fetch_row($res2);
							
							$req2="SELECT surcharge_ventricule from cardio_vasculaire_depart WHERE id='$dossier->id' and ".
								  "(surcharge_ventricule='oui' or surcharge_ventricule='non') ".
								  "ORDER by date DESC limit 0,1";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
							
							list($surcharge_ventricule)=mysql_fetch_row($res2);
							
							$req2="SELECT tabac from cardio_vasculaire_depart WHERE id='$dossier->id' and ".
								  "(tabac='oui' or tabac='non') ".
								  "ORDER by date DESC limit 0,1";
							$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
							
							list($tab)=mysql_fetch_row($res2);
							
							$calcul=1;
							if(($tab!="oui")&&($tab!="non")){
								$calcul=0;
							}
							if($tension==''){
								$calcul=0;
							}
							if($choltot==''){
								$calcul=0;
							}
							if($hdl==''){
								$calcul=0;
							}
							if(($ventricule!="oui")&&($ventricule!="non")&&($surcharge_ventricule!="oui")&&($surcharge_ventricule!="non")){
								$calcul=0;
							}
							
							if($calcul==1){
								$age=get_age($dnaiss, date("Y-m-d"));
								
								$req2="SELECT dossier_id from suivi_diabete WHERE dossier_id='$dossier->id' ";
								$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
								if(mysql_num_rows($res2)>0){
									$diab=1;
								}
								else{
									$diab=0;
								}
								$rcv=get_rcva($sexe, $age, $diab, $tab, $tension, $choltot, $hdl, $ventricule, $surcharge_ventricule);
							}
							else{
								$rcv="NC";
							}
						
							$HistoRCVA = new HistoRCVA($type_exam, $date, $rcv);
							$HistoRCVA = $HistoRCVA->afterDeserialisation($account);
							$liste_resultats[]=$HistoRCVA;
							// $liste_resultats[$i]=array("date"=>date("d/m/Y"), "valeur"=>$rcv);




							$mois=$mois-3;

							if($mois<=0)
							{
								$mois=$mois+12;
								$annee--;
							}
						}
					}
					else{
						$result = $HistoRCVAMapper->ListeExams($HistoRCVA->beforeSerialisation($account), $dossier);
						if($result == false){//Aucune ligne trouvée
							if($HistoRCVAMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"dossier non trouvé");
						}
						
						
						foreach($result as $tab){
							$HistoRCVA = new HistoRCVA($type_exam, $tab["date"], $tab["valeur"]);
							$HistoRCVA = $HistoRCVA->afterDeserialisation($account);
							$liste_resultats[]=$HistoRCVA;
						}

					}

					forward($this->mappingTable["URL_AFTER_LIST"]);

					break;

				default:
					echo("ACTION IS NULL");
					break;
			}
		}

	}
?> 
