<?php
function clean($src, $op)
{
    return $src;

}

if($argc<2)
{

    exit("syntaxe: $argv[0] file_name \n");

}

require_once ("Config.php");
$config = new Config();

require($config->inclus_path . "/connectannuaire.php");
$table = "historique_allowedcabinets";
$con = DoConnect();



$i=0;
$j = 0;
echo "Begin Import \n";
$fname = $argv[1];
$handle=fopen($fname,"r");
if ($handle)
{
    fgets($handle); //première ligne
    while (($buffer = fgets($handle)) !== false)
    {
//echo $buffer;    
        $buffer = str_replace("\n", "", $buffer);
        $buffer = str_replace("\r", "", $buffer);
        $tokens = explode(";", $buffer);
        if(count($tokens)>=3)
        {

            $login = $tokens[0];
            $cab = $tokens[1];
            $ddebut = $tokens[2];
            $dfin = $tokens[3];
            $sql = "INSERT INTO $table ( `login`, `cabinet`, `actualstatus`, `dstatus`) VALUES ('$login','$cab',0, '$ddebut')";
            $rs = mysql_query($sql, $con);

            if($rs)
            {
                $i++;
                if($i%10==0)
                    echo(".");
                if( strlen($dfin)==10)
                {

//                    var_dump($tokens);

                    $sql = "INSERT INTO $table ( `login`, `cabinet`, `actualstatus`, `dstatus`) VALUES ('$login','$cab',1, '$dfin')";
                    $rs = mysql_query($sql, $con);
                    if($rs)
                    {
                        $j++;
                        if($j%10==0)
                            echo("*");

                        $sql = "UPDATE `allowedcabinets` SET `recordstatus`=1 WHERE login='$login' and cabinet='$cab'";
                        $rs = mysql_query($sql, $con);
                    }


                }

            }




        }
        //echo $buffer;
    }
    if (!feof($handle)) {
        echo "Error: fgets() \n";

    }
    echo "\nImported $i begin records and $j end records\n";
    fclose($handle);
}


/*





$cabinet =  utf8_decode ( $cabinet); //utf8 EA 20-05-2015
  $where=" where identifications.login=allowedcabinets.login and allowedcabinets.cabinet='$cabinet' ";
//$wherenon=" and where not exists (select hpassword from hpasswords where hpasswords.login = habilitations.login) ";
$where2="";


$where = $where. $where2;
  $offset = ($page-1)*$rows;
  $result = array();

  $rs = mysql_query("select count(*) from $table ". $where );
  $row = mysql_fetch_row($rs);
  $result["total"] = $row[0];
  $sql2 = " order by $sort $order limit $offset,$rows";
  $sql = "select allowedcabinets.id,identifications.login,nom,prenom from $table ";

  $sql = $sql.$where.$sql2;

//	error_log($sql);
  $items = array();
  while($row = mysql_fetch_object($rs))
  {
      $rows = (array)$row;
      foreach( $rows as $key => $value)
      {

          if(!is_null($value))
          {

              $value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);

              $rows[$key] = $value;
          }
      }

      array_push($items, $rows);
  }
  $result["rows"] = $items;


$xLog=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
require_once("$xLog/WebService/AsaleeLog.php");
LogAccess("psaet.asalee.fr", "allowed_getdata", $UserIDLog, 'na', '',  0, "Liste Cabinets/Infirmières: ".$answerLog);




  echo json_encode($result);
 }
 */

?>
