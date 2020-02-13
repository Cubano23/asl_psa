<?php
function clean($src, $op)
{
    return $src;

}

require_once ("Config.php");
$config = new Config();

require($config->inclus_path . "/connectannuaire.php") ;

$con = DoConnect();

$table = "allowedcabinets ";
$result = array();
$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'login';
$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
$where=" where allowedcabinets.login=historique_allowedcabinets.login and allowedcabinets.cabinet=historique_allowedcabinets.cabinet and  historique_allowedcabinets.actualstatus=0 ";
//$wherenon=" and where not exists (select hpassword from hpasswords where hpasswords.login = habilitations.login) ";
$where2="";


$where = $where. $where2;

$sql2 = " order by $sort $order ";
$sql = "select allowedcabinets.id, allowedcabinets.login,allowedcabinets.cabinet, dmaj, recordstatus, historique_allowedcabinets.dstatus as dstatus from $table, historique_allowedcabinets ";

$sql = $sql.$where.$sql2;
$rs = mysql_query($sql, $con);
error_log($sql);
$fp =  fopen("allowedexport.csv", "w+");
fprintf($fp,"id;login;cabinet;dmaj;recordstatus;ddebut;dfin\n" );

while($row = mysql_fetch_object($rs))
{
    $rows = (array)$row;
    $ddebut="";
    $dfin="";
    $recordstatus=0;
    $login="";
    $cabinet="";
    foreach( $rows as $key => $value)
    {

        if(!is_null($value))
        {

            if($key=='login')
                $login = $value;
            if($key=='cabinet')
                $cabinet= $value;
            if($key=='recordstatus')
                $recordstatus= $value;
            if($key=='dstatus')
                $ddebut=substr($value,0,10);

            $value = mb_check_encoding($value, 'UTF-8') ? utf8_decode($value) : $value;

            $rows[$key] = $value;
        }
    }
    if($recordstatus==1)
    {
        $sql2 ="select dstatus from historique_allowedcabinets where cabinet='$cabinet' and login='$login' and actualstatus=1 order by dstatus desc limit 1";
        $rs2=mysql_query($sql2, $con);
        $row2=mysql_fetch_array($rs2);
        $value=$row2[0];
        $dfin= substr($value,0,10);

    }
    fprintf($fp,"%s;%s;%s;%s;%s;%s;%s\n", strval($rows["id"]), $rows["login"], $rows["cabinet"],strval($rows["dmaj"]),
        strval($rows["recordstatus"]), $ddebut, $dfin
    );


}
fclose($fp);



echo json_encode($result);

?>
