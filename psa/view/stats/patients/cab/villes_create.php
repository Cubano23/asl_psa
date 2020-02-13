<?php



       $fi= fopen("cp.csv","r");
       $fo = fopen("villes.php","w");
       
       $line = fgets($fi); // first line
       
       fwrite($fo, "<?php".PHP_EOL.PHP_EOL);
       fwrite($fo, "\$villes=array();".PHP_EOL);
       $ville="";
       
       while(($line = fgets($fi))!== false)
       {
          $tok =  explode(";", $line);
//          echo $tok[1]." ".$tok[2]."\n";
          if($ville!=$tok[1])
          {
            $v = str_replace(" L "," L'", $tok[1] );
            $v = str_replace(" D "," D'", $v );
            $v = str_replace("ST ","Saint ", $v );
            $x = "\$ville[\"".$v."\"]=";
            $x = $x."\"$tok[2]\"";
            $x=$x.";".PHP_EOL;
            fwrite($fo, $x);
            $ville=$tok[1];
          }
       }
       



    fwrite($fo, PHP_EOL."?>");

     fclose($fi);
     fclose($fo);



?>