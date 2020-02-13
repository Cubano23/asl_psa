<?php

          
 require_once "excelwriter.php";
 
 
          try
          
          {
          
          echo memory_get_usage() . "\n"; // 36640
          ini_set("memory_limit","512M");
          echo memory_get_usage() . "\n"; // 36640             
          $obj = new writeexcel_workbookbig("toto.xls");

          $w1 = $obj->addworksheet("t1") ;
          $w2 = $obj->addworksheet("t2") ;
          $w1->write("A1","toto")  ;
          $w2->write("A2","tata")  ;
          
          for ($i=1; $i<200000; $i++)
          {
          
//                         echo ("A$i\n");
                         
                $w1->write("A$i", "$i")  ;
                $w2->write("A$i", "$i")  ;
          
          }
                  echo memory_get_usage() . "\n"; // 36640
  $mem = memory_get_usage();
          $mem = ceil( $mem * 4 / 1000000);
          echo ("allocating ".$mem."M\n");
                 ini_set("memory_limit",$mem."M");
          $obj->close();

          echo memory_get_usage() . "\n"; // 36640
                    unset($obj);
          echo memory_get_usage() . "\n"; // 36640                       
           }
           
        catch (Exception $e) {
              echo 'Exception reçue : ',  $e->getMessage(), "\n";
        }










?>