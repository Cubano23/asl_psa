<?php
  
  session_start();
  include 'conn.php';
  $cabinet = $_SESSION["cabinet"];
  
  
    function AnonymizeFilename( $fname)
    {
            $inputKey = pack("H*","E49F211F72FDA17B3420DEADEA99AD42");     //clé différente
            $f = hash_hmac ( "md5" , $fname, $inputKey );
            return $fname.$f;
    }

  
 
 $where = " ";
  $ndsearch = isset($_POST['ndsearch']) ? clean($_POST['ndsearch'], 1) : '';
  $dsearch = isset($_POST['dsearch']) ? clean($_POST['dsearch'], 1) : '0';
  $nsearch = isset($_POST['nsearch']) ? clean($_POST['nsearch'], 1) : '0';
  
  if($cabinet=="Chizé")
      $cabinet = "Chize";
  if(strtolower($cabinet)=="ztest")
       $where = " where numero LIKE '$ndsearch%' ";
  else
  $where = " where cabinet = '$cabinet' and numero LIKE '$ndsearch%' ";
  
  //Filtre  Date consentement
  $where2 = "";
  if($dsearch=='1')
      $where2=" and dconsentement >'0000-00-00' ";
  if($dsearch=='2')
      $where2=" and dconsentement ='0000-00-00' ";
  $where = $where.$where2;

  //Filtre  Nir chiffré
  $where2 = "";
  if($nsearch=='1')
      $where2=" and encnir !='' ";
  if($nsearch=='2')
      $where2=" and encnir ='' ";
  $where = $where.$where2;

 
 


	$result = array();

	$sql = "select * from $table ";
  $sql2 = " order by cabinet, numero asc";
	$sql = $sql.$where.$sql2;
  
	$rs = mysql_query($sql);
	
	$items = array();


  $fname =AnonymizeFilename( "dossierexport".date("dmYHis")).".csv";  

  $fp = fopen($fname,"w");
  
  
  fprintf($fp,"cabinet;dossier;id;dconsent;nir\n"  );
  
  $rec=0;
	while($row = mysql_fetch_object($rs))
	{
		$rows = (array)$row;
		foreach( $rows as $key => $value)
		{
      if ($key=="encnir")
      {
      
              if($value!="")
                    $value = "Oui";
              else
                    $value = "Non";
              $rows[$key] = $value;      
              $rec++;
      }
  	}

		 fprintf($fp,"%s;%s;%s;%s;%s\n", 
          utf8_decode ($rows["cabinet"]),utf8_decode($rows["numero"]),strval($rows["id"]),strval($rows["dconsentement"]),strval($rows["encnir"])
          );
	}
	fclose($fp);
	
  
	echo json_encode(array($result,'fname'=>$fname));
	
?>
