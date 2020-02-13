<?php 
require_once("persistence/SuiviDiabeteMapper.php");
require_once("persistence/ConnectionFactory.php");
require_once("bean/ControlerParams.php");
require_once("bean/GraphBean.php");
require_once("bean/beanparser/htmltags.php");


class HBAGraphControler {
	
	var $mappingTable;
	
	function HBAGraphControler(){
		$this->mappingTable = array(
		"HBA1GRAPHManage" => "view/diabete/graph/managegraph1.php",
		"HBA1GRAPH" => "view/diabete/graph/drawgraph1.php",
		"HBA1IMAGE" => "view/diabete/graph/graph1image.php",
		"HBA2GRAPHManage" => "view/diabete/graph/managegraph2.php",
		"HBA2GRAPH" => "view/diabete/graph/drawgraph2.php",
		"HBA2IMAGE" => "view/diabete/graph/graph2image.php",
		);
	}
	
	
	function start() {
			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;			
			global $graphBean;
			
			// create leder for this controler
			$ledgerFactory = new LedgerFactory();
			$ledger = $ledgerFactory->getLedger("Controler","HBAGraphControler");


			//Create connection factory
			$cf = new ConnectionFactory();
		
			//create mappers
			$suiviDiabeteMapper = new SuiviDiabeteMapper($cf->getConnection());			
			
			if(array_key_exists("graphBean",$objects)) {
				$graphBean = $objects["graphBean"];
			}
			
			$ledger->writeArray(I,"Start","Control Parameters = ",$param);
			switch($param->action){
				case ACTION_MANAGE:			
					$graphBean = new GraphBean();	
					switch($param->param1){							
						case PARAM_GRAPH1:
							forward($this->mappingTable["HBA1GRAPHManage"]);						
						break;
						
						case PARAM_GRAPH2:
							forward($this->mappingTable["HBA2GRAPHManage"]);
						break;
					}
				
				case ACTION_FIND:
					global $image;
					if (empty($graphBean->cabinets)&&($_SESSION['account']->cabinet!='admin'))
					    $graphBean->cabinets=$_SESSION['account']->cabinet;

					$image = array(getPropertyName("graphBean:cabinets")=>$graphBean->cabinets,
									getPropertyName("graphBean:startYear")=>$graphBean->startYear,
									getPropertyName("graphBean:startMonth")=>$graphBean->startMonth,
									getPropertyName("graphBean:endYear")=>$graphBean->endYear,
									getPropertyName("graphBean:endMonth")=>$graphBean->endMonth,
									getPropertyName("graphBean:zoom")=>$graphBean->zoom,
									getPropertyName("graphBean:stepsNum")=>$graphBean->stepsNum);

					switch($param->param1){	
						case PARAM_GRAPH1:
							forward($this->mappingTable["HBA1GRAPH"]);
						case PARAM_GRAPH2:
							forward($this->mappingTable["HBA2GRAPH"]);
					}
					
					break;
					
				case ACTION_GRAPH:

					if(is_null($graphBean)) 
						forward(URL_CONTROLER_PERSISTENCE_ERROR,"Missing Parameters");
					switch($param->param1){		
						case PARAM_GRAPH1:
						
							$ledger->write(I,"Computing Graph1","");
							$result = $suiviDiabeteMapper->getHBA1GraphData($graphBean->cabinets,$graphBean->startMonth,$graphBean->endMonth,$graphBean->startYear,$graphBean->endYear);
							if($result == false){
								if($suiviDiabeteMapper->lastError == BAD_MATCH) forward($this->mappingTable["HBA1GRAPHManage"],"Pas de resultats");
								else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find Failed");
							}
							
	$ledger->write(I,"Stats","Results"+count($result));
							global $selectedtCabinetsArray;
							global $totalArray;
							global $averageArray;
							$ledger->writeArray(I,"Computing Graph1=". count($result),$image);
							for($i=0;$i<count($result);$i++){
								$row = $result[$i];
								$totalArray[$row["yHBA"].$row["mHBA"]]=$row["total"];
								$averageArray[$row["yHBA"].$row["mHBA"]]=$row["av"];
							}
							
							if(!is_null($graphBean->cabinets) and ($graphBean->cabinets !=""))							
								$selectedtCabinetsArray = array($graphBean->cabinets);								
							else	
								$selectedtCabinetsArray = NULL;

							forward($this->mappingTable["HBA1IMAGE"]);
							
						break;
						
						case PARAM_GRAPH2:
						
							global $resultats;
							global $selectedtCabinetsArray;
							global $mediane;
							global $nborne;
							global $texte;
							
							$ledger->write(I,"Computing Graph2","");
							$ledger->writeArray(I,"Computing Graph2","graphBean = ",$graphBean);
							$result = $suiviDiabeteMapper->getHBA2GraphData1($graphBean->cabinets,$graphBean->startMonth,$graphBean->endMonth,$graphBean->startYear,$graphBean->endYear);

							if($result == false){
								$ledger->write(W,"Computing Graph2","Result is false");
								if($suiviDiabeteMapper->lastError == BAD_MATCH) forward($this->mappingTable["HBA1GRAPHManage"],"Pas de resultats");
								else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find Failed");
							}

							$ledger->writeArray(I,"Computing Graph2","Graph2 Data1",$result[0]);
							$resArray = $result[0];
							$moy = $resArray["av"];
							$stdev = $resArray["std"];
							$nb = $resArray["cnt"];
							$valmin=max(0,round($moy-($stdev*20/$graphBean->zoom),0));   
							$valmax=round(($stdev*20/$graphBean->zoom)+$moy,2);
							$step=($valmax-$valmin)/$graphBean->stepsNum;
							$resultats=array();
							for ($i=0 ; $i<$graphBean->stepsNum ; $i++) {
								$resultats[$i]=0;
								$nborne[$i]=round($valmin+($i*$step),2);
							}
							$result = $suiviDiabeteMapper->getHBA2GraphData2($graphBean->cabinets,$graphBean->startMonth,$graphBean->endMonth,$graphBean->startYear,$graphBean->endYear,$step,$valmin);
							if($result == false){
								if($suiviDiabeteMapper->lastError == BAD_MATCH) forward($this->mappingTable["HBA1GRAPHManage"],"Pas de resultats");
								else forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find Failed");
							}
							$nbmini=$nbmaxi=0; 
							for($i=0;$i<count($result);$i++) {
								$resArray = $result[$i];
								$borne = $resArray["borne"];
								$nb = $resArray["cnt"];
								if ($borne<0)
									$nbmini+=$nb;
								elseif ($borne>=$graphBean->stepsNum)
									$nbmaxi+=$nb;
								else {
								   $resultats[$borne]+=$nb;
								}
							}
							if ((!isset($mediane)) or (!is_numeric($mediane))) {
								$nb=array_sum($resultats)+$nbmaxi+$nbmini;
								$result = $suiviDiabeteMapper->getHBA2GraphDataMediane($graphBean->cabinets,$graphBean->startMonth,$graphBean->endMonth,$graphBean->startYear,$graphBean->endYear,$nb);
								if($result == false){
									if($suiviDiabeteMapper->lastError != BAD_MATCH) forward(URL_CONTROLER_PERSISTENCE_ERROR,"Find Failed");
									else $mediane ='';
								}
								$resArray = $result[0];
								$mediane = $resArray["resHBA"];
							}
							$texte=array();
							if ($nbmini)
								$texte[]="(et $nbmini mesures en dessous de $valmin)";
							if ($nbmaxi)
								$texte[]="(et $nbmaxi mesures au dessus de $valmax";
							if (isset($mediane) and (is_numeric($mediane))) {
								$mediane=round($mediane, 2);
								$texte[]="ligne rouge: valeur médiane ($mediane)";
							}
							else $mediane='';
														
							if(!is_null($graphBean->cabinets) and ($graphBean->cabinets !=""))							
								$selectedtCabinetsArray = array($graphBean->cabinets);								
							else	
								$selectedtCabinetsArray = NULL;
							forward($this->mappingTable["HBA2IMAGE"]);

						break;
					}
					
					break;
				
			}			
	}
}
?>
