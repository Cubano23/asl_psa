<?php

      function DoConnect($new_link = false) 
      {
           $hostname_mysql = "localhost";
	         $database_mysql = "erp";
	         $username_mysql = "informed";
	         $password_mysql = "no11iugX";
          	$con = mysql_connect($hostname_mysql, $username_mysql, $password_mysql, $new_link) ;	
          	mysql_select_db($database_mysql, $con);		
            return $con;
      }    
      
      function DoClose($con)
      {
              mysql_close($con);
      
      } 
?>