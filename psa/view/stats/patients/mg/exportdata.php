<?php


	include 'conn.php';

	$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'medecin.id';
	$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
 
	$where=" where medecin.nom!='' and medecin.prenom !='' and medecin.cabinet !='' and medecin.cabinet= account.cabinet and medecin.id=historique_medecin.medid and historique_medecin.actualstatus=0 ";

	$result = array();
	$sql2 = " order by $sort $order ";
	$sql = "select medecin.id as id, medecin.cabinet,medecin.prenom as prenom,medecin.nom as nom, medecin.courriel, adeli,".
              "medecin.adresse, medecin.codepostal, medecin.ville, medecin.departement, medecin.region,".
                "rpps, medecin.telephone, medecin.portable , account.cabinet as cab, medecin.recordstatus as recordstatus, historique_medecin.dstatus as dstatus  from medecin,account, historique_medecin  ";
	$sql = $sql.$where.$sql2;


	$rs = mysql_query($sql);

  $fp =  fopen("mgexport.csv", "w+");
  fprintf($fp,"id;cabinet;prenom;nom;courriel;adeli;".
              "adresse;codepostal;ville;departement;region;".
                "rpps;telephone;portable;recordstatus;ddebut;dfin\n"
                );
                                        
                                        
	
	while($row = mysql_fetch_object($rs))
  {
		$rows = (array)$row;
		$thecabinet='';
    $recordstatus=0;
    $ddebut="";
    $medid=0;
    $dfin="";
		foreach( $rows as $key => $value)
		{
			if(!is_null($value))
			{
				if($key=='cabinet')
					$thecabinet=$value;
				if($key=='recordstatus')
					$recordstatus=$value;
				if($key=='dstatus')
					$ddebut=substr($value,0,10);
        if($key=='id')
					$medid=$value;          		
				$value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);
				$rows[$key] = $value;
			}
      
      
		}
/*		if($thecabinet!='')
		{
		
			$rs2=mysql_query("select nom_cab from account where cabinet ='$thecabinet' ");
			$row2=mysql_fetch_array($rs2);
			$value=$row2[0];
			$value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);
			$rows['nom_cab']= $value;

		}*/
    
    if($recordstatus==1)
		{
      $sql2 ="select dstatus,medid from historique_medecin where medid=$medid and actualstatus=1 order by dstatus desc limit 1";
			$rs2=mysql_query($sql2);
			$row2=mysql_fetch_array($rs2);
			$value=$row2[0];
			$dfin= substr($value,0,10);
		}
    

    fprintf($fp,"%s;%s;%s;%s;%s;%s;".
                "%s;%s;%s;".
                "%s;%s;%s;%s;%s;%s;%s;%s\n", strval($rows["id"]),utf8_decode ($rows["cabinet"]), utf8_decode ($rows["prenom"]), utf8_decode ($rows["nom"]),$rows["courriel"],
                                        $rows["adeli"],
                                        utf8_decode ($rows["adresse"]), $rows["codepostal"],utf8_decode ($rows["ville"]),utf8_decode ($rows["departement"]),utf8_decode ($rows["region"]),
                                        $rows["rpps"],$rows["telephone"],$rows["portable"],strval($rows["recordstatus"]), $ddebut, $dfin                                         
    );
	}

fclose($fp);
//  $xLog=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
//  require_once("$xLog/WebService/AsaleeLog.php");
//  LogAccess("psaet.asalee.fr", "mg_getdata", $UserIDLog, 'na', $cabinet,  0, "Liste Medecins Traitants:".$answerLog);  



	echo json_encode($result);

?>
