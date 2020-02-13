<?php

      function DoConnect() 
      {
           $hostname_mysql = "localhost";
	         $database_mysql = "annuaire";
	         $username_mysql = "informed";
	         $password_mysql = "no11iugX";
          	$con = mysql_connect($hostname_mysql, $username_mysql, $password_mysql) ;	
          	mysql_select_db($database_mysql, $con);		
            return $con;
      }     
?>