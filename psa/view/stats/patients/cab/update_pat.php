<?php
	
  function update_patients0($cabinet)
  {
      $sql =   "select id from medecin where cabinet='$cabinet' and recordstatus= 0 " ;
  	   $result = @mysql_query($sql);
       $medecins = mysql_num_rows($result);
       if($medecins==0)
                       if($medecins=1);
       if($medecins>0)
       {
//           $_REQUEST['total_pat']	 =      $_REQUEST['total_sein'] =       $_REQUEST['total_cogni'] =      $_REQUEST['total_colon'] =
//              $_REQUEST['total_uterus'] =         $_REQUEST['total_diab2'] =     $_REQUEST['total_HTA'] =
              $total_pat = 800* $medecins ;
             	$total_sein  = 150 * $medecins ;
	       $total_cogni  = 120 * $medecins;
	        $total_colon  = 300 * $medecins;
	         $total_uterus  = 180 * $medecins ;
	       $total_diab2  = 50 * $medecins;
	        $total_HTA  = 150 * $medecins;
  
          $sql= "update account SET total_pat='$total_pat', total_sein='$total_sein', ".
		            "total_cogni='$total_cogni', total_colon='$total_colon', ".
            		 "total_uterus='$total_uterus', total_diab2='$total_diab2', ".
            		 "total_HTA='$total_HTA' where cabinet='$cabinet'";
 
// error_log($sql); 

      	$result = @mysql_query($sql);
      }  

  
  
  }
  
  function update_patients($cabinet, $op)
  {
  
    if( ($op==1) || ($op==-1) )
    {
      $sql= "select total_pat, total_sein,total_cogni,total_colon,total_uterus,total_diab2,total_HTA  from account where cabinet='$cabinet' and recordstatus= 0 "; 
      
  	   $result = @mysql_query($sql);
       $row =  mysql_fetch_array ($result);
       if($row)
       {
                 $total_pat = 800* $op + $row["total_pat"] ;
                 $total_sein  = 150 * $op + $row["total_sein"] ;
	               $total_cogni  = 120 * $op   + $row["total_cogni"];
	               $total_colon  = 300 * $op  + $row["total_colon"];
	               $total_uterus  = 180 * $op + $row["total_uterus"];
	               $total_diab2  = 50 * $op + $row["total_diab2"];
	               $total_HTA  = 150 * $op + $row["total_HTA"];

                 if($total_pat<0) $total_pat=0 ;
                 if($total_sein<0) $total_sein=0 ;
	               if($total_cogni<0) $total_cogni=0;
	               if($total_colon<0) $total_colon=0;
	               if($total_uterus<0) $total_uterus=0;
	               if($total_diab2<0) $total_diab2=0;
	               if($total_HTA<0) $total_HTA=0;


  
                $sql= "update account SET total_pat='$total_pat', total_sein='$total_sein', ".
		            "total_cogni='$total_cogni', total_colon='$total_colon', ".
            		 "total_uterus='$total_uterus', total_diab2='$total_diab2', ".
            		 "total_HTA='$total_HTA' where cabinet='$cabinet'";
             	$result = @mysql_query($sql);
              if ($result)
              {

                		$req="INSERT INTO histo_account SET cabinet='$cabinet', ".
			               "d_modif='".date("Y-m-d H:i:s")."', ".
			               "total_pat='$total_pat', total_sein='$total_sein', ".
			               "total_cogni='$total_cogni', total_colon='$total_colon', ".
			               "total_uterus='$total_uterus', total_diab2='$total_diab2', ".
			               "total_HTA='$total_HTA'";
		                  $result = @mysql_query($req);
              }
      }  
    }
  }

	
?>
