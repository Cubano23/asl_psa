<?php
  
  session_start();
  include 'conn.php';
  


   $fp = fopen("asaleenirs.asc","w");


 $where = " where encnir != '' and cabinet!='ztest' and dconsentement!='0000-00-00' ";
//error_log($where);

  $ids = "";
  
  $sql="select id, dnaiss from $table "; 
  
  $sql = $sql.$where;
	$rs = mysql_query($sql);
	
	$items = array();
  $rec=0;
	while($row = mysql_fetch_object($rs))
	{
		$rows = (array)$row;
    $id=0;
    $dnaiss="";
		foreach( $rows as $key => $value)
		{
      if ($key=="id")
      {
      
          $id=   $value;
      }
      if ($key=="dnaiss")
      {
      
          $dnaiss=  str_replace("-", "", $value ) ;
      }
		}
    if($id!=0)
    {
        $ids = $ids . $id.";".$dnaiss."\n"; 
//       fwrite($fp, );
		}
	}
  
  
  putenv("GNUPGHOME=/tmp/apache");
  $enc = (null);
  $res = gnupg_init();
  $err="Erreur";
//
// Charger la clé publique du fichier
  $pubkey = file_get_contents( 'CNAMTS_PUB.asc');
//  $pubkey = file_get_contents( 'ASALEE_PUB.asc');
//importer la clé 
  $rtv = gnupg_import($res, $pubkey);

        $fingerprint =  $rtv['fingerprint'];
//var_dump($rtv);
        $rtv = gnupg_addencryptkey($res, $fingerprint);
        if($rtv!= false)
        {
            $encnir = gnupg_encrypt($res, $ids);
            fwrite($fp, $encnir);
  
  
      } 
      else
      {
               $result = false;
               $err= gnupg_geterror($res);
       }


   $where = " where encnir != '' and cabinet!='ztest' and dconsentement!='0000-00-00' LIMIT 100";


	$result = array();

	$sql = "select encnir from $table ";
	
	$sql = $sql.$where;
	$rs = mysql_query($sql);
	
	$items = array();
  $rec=0;
	while($row = mysql_fetch_object($rs))
	{
		$rows = (array)$row;
		foreach( $rows as $key => $value)
		{
      if ($key=="encnir")
      {
              fwrite($fp, $value );
              $rec++;
      }
            
		}

		
	}
	fclose($fp);
	
  
		echo json_encode(array('msg'=>strval($rec)));
	
?>
