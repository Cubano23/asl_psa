<?php

    
    function logiciel_getname($logiciel)
    {
    require("logiciels.php");
    
    $value="";
   	$key = $logiciel;
      if($key!="")
      {
    
        if(array_key_exists (  $key , $logiciels )) 
            $value = $logiciels[$key]; 
        else
            $value = $key;        
      }
      if($value==''){
        $value='nc';
      }
    return $value;
    }

//    echo logiciel_getname("axisante55555");


    
?>