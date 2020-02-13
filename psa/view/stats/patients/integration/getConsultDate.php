<?php


    function getMaxConsultDate($id)
    {
      $cdate="";
      
    $sql2= " select max(dsuivi) as dmaj from suivi_diabete where dossier_id=$id "; 
    $rs2 = mysql_query($sql2);
    $row2 = mysql_fetch_row($rs2);
    $cdate=$row2[0];
    
      
    $sql3= " select max(date)  from cardio_vasculaire_depart where id=$id "; 
    $rs3 = mysql_query($sql3);
    $row3 = mysql_fetch_row($rs3);
   
     if($row3[0]> $row2[0])
          $cdate=$row3[0];
    
    
     return $cdate;                                            
    
    
    
    
    }














?>