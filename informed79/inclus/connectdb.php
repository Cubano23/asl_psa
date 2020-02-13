<?php

      function DoConnectDb($db,  $new_link = false) 
      {
           $hostname_mysql = "localhost";
	         $database_mysql = $db;
	         $username_mysql = "informed";
	         $password_mysql = "no11iugX";
          	$con = mysql_connect($hostname_mysql, $username_mysql, $password_mysql, $new_link) ;	
          	mysql_select_db($database_mysql, $con);		
            return $con;
      }    
      
      function DoCloseDb($con)
      {
              mysql_close($con);
      
      }
      
      function DoConnectDbi($db,  $new_link = false) 
      {
           $hostname_mysql = "localhost";
	         $database_mysql = $db;
	         $username_mysql = "informed";
	         $password_mysql = "no11iugX";
          	$con = mysqli_connect($hostname_mysql, $username_mysql, $password_mysql, $database_mysql) ;	
            return $con;
      }    
      
      function DoCloseDbi($con)
      {
              mysqli_close($con);
      
      } 
      
       
?>