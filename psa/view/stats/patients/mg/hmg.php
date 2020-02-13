<?php
  
  if($argc<2)
  {
  
      exit("syntaxe: $argv[0] file_name \n");
  
  }

  $table = "historique_medecin"; 
  $serveur="localhost";
  $idDB="informed";
  $mdpDB="no11iugX";
  $DB="informed3";

# connexion aux données
  mysql_connect($serveur,$idDB,$mdpDB) or
   die("Impossible de se connecter au SGBD");
  mysql_select_db($DB) or
   die("Impossible de se connecter à la base");

  
  
    
  $i=0;
  $j = 0;
  echo "Begin Import \n";
  $fname = $argv[1];
  $handle=fopen($fname,"r");
  if ($handle) 
  {
    fgets($handle); //première ligne
    while (($buffer = fgets($handle)) !== false) 
    {
//echo $buffer;    
         $buffer = str_replace("\n", "", $buffer);
         $buffer = str_replace("\r", "", $buffer);
         $tokens = explode(";", $buffer);
         if(count($tokens)>=5)
         {

              $medid = intval($tokens[0]);
              $nom = $tokens[1];
              $prenom = $tokens[2];
              $cab = trim($tokens[3]);  
              $ddebut = $tokens[4];
              $dfin = $tokens[5];
 
              
              $sql2 = " SELECT  dossier.id, cabinet, date  FROM `evaluation_infirmier` , `dossier` WHERE dossier.id = evaluation_infirmier.id and cabinet = '$cab' order by  date asc limit 1";
              if (strlen($ddebut)==0)
              {
              // demander la première
                      $xid="";
                      $cabinet="";
//                      $res = mysql_query($sql2);
                      $res=mysql_query($sql2) or die("erreur SQL:".mysql_error());
                      if($res)
                      {
                            list($xid, $cabinet, $ddebut)=mysql_fetch_row($res);
                      
                      }
                      if (strlen($ddebut)==0)
                      {
                            $sql2 = " SELECT  dmaj FROM `medecin`  WHERE id =  '$medid'";
                            $res=mysql_query($sql2) or die("erreur SQL:".mysql_error());
                            if($res)
                            {
                                  list( $ddebut)=mysql_fetch_row($res);
                            }
                      
                      
                      }
                      
//                      echo $cabinet." " . $ddebut. "\n";
              }               
              $sql = "INSERT INTO `historique_medecin`( `medid`, `cabinet`, `nom`, `prenom`, `actualstatus`, `dstatus`) VALUES ('$medid','$cab','$nom','$prenom',0,'$ddebut')";
              $rs = mysql_query($sql);
              if($rs)
              {
                $i++;
                if($i%10==0)
                    echo(".");
                if( strlen($dfin)==10) 
                {
                    
              $sql = "INSERT INTO `historique_medecin`( `medid`, `cabinet`, `nom`, `prenom`, `actualstatus`, `dstatus`) VALUES ('$medid','$cab','$nom','$prenom',1,'$dfin')";
                  $rs = mysql_query($sql);
                    if($rs)
                    {
                      $j++;
                      if($j%10==0)
                              echo("*");

                      $sql = "UPDATE `medecin` SET `recordstatus`=1 WHERE id='$medid'";
                      $rs = mysql_query($sql);
                    }
                    
                    
                }
              
              }
              else
              mysql_error();
            
              
                          
          }
        //echo $buffer;
    }
    if (!feof($handle)) {
        echo "Error: fgets() \n";
    
    }
    echo "\nImported $i begin records and $j end records\n";
    fclose($handle);
   }
  
  
  /*

 
	
  
  
  $cabinet =  utf8_decode ( $cabinet); //utf8 EA 20-05-2015
	$where=" where identifications.login=allowedcabinets.login and allowedcabinets.cabinet='$cabinet' ";
  //$wherenon=" and where not exists (select hpassword from hpasswords where hpasswords.login = habilitations.login) ";
  $where2="";

  
  $where = $where. $where2;
	$offset = ($page-1)*$rows;
	$result = array();

	$rs = mysql_query("select count(*) from $table ". $where );
	$row = mysql_fetch_row($rs);
	$result["total"] = $row[0];
	$sql2 = " order by $sort $order limit $offset,$rows";
	$sql = "select allowedcabinets.id,identifications.login,nom,prenom from $table ";
	
	$sql = $sql.$where.$sql2;
	
//	error_log($sql);
	$items = array();
	while($row = mysql_fetch_object($rs))
	{
		$rows = (array)$row;
		foreach( $rows as $key => $value)
		{

			if(!is_null($value))
			{
		    
				$value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);
       
				$rows[$key] = $value;
			}
		}
  
		array_push($items, $rows);
	}
	$result["rows"] = $items;
  

  $xLog=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
  require_once("$xLog/WebService/AsaleeLog.php");
  LogAccess("psaet.asalee.fr", "allowed_getdata", $UserIDLog, 'na', '',  0, "Liste Cabinets/Infirmières: ".$answerLog);  

  
  
  
	echo json_encode($result);
   }
   */
   
?>
