<?php
function clean($src, $op)
{
    return utf8_decode ($src) ; //EA 27-05-2015

}

require_once ("Config.php");
$config = new Config();

require($config->inclus_path . "/connectannuaire.php");

$con = DoConnect();

$table = "identifications, habilitations ";

$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'login';
$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';

$where=" where nom!='' and prenom !='' ";
$where2="";

$where = $where. $where2;
$result = array();

$sql2 = " order by $sort $order ";
$sql = "select login,nom,prenom,telephone, email,profession,departement, status, type,adeli,rfu1,rfu2,photo,dmaj from identifications ";

$sql = $sql.$where.$sql2;
$rs = mysql_query($sql);
//	error_log($sql);
$fp =  fopen("habexport.csv", "w+");
fprintf($fp,"login;nom;prenom;telephone;email;profession;photo;departement;rfu1;rfu2;status;type;dmaj;adeli\n" );

while($row = mysql_fetch_object($rs))
{
    $rows = (array)$row;
    foreach( $rows as $key => $value)
    {

        if($key=="type")
        {
            switch($value)
            {
                case 0: $value = "Salarié"; break;
                case 1: $value = "Libéral"; break;
                default: $value = "Autre"; break;
            }
            $rows[$key] = $value;
        }

        if(!is_null($value))
        {

            $value = mb_check_encoding($value, 'UTF-8') ? utf8_decode($value) : $value;

            $rows[$key] = $value;
        }
    }
    fprintf($fp,"%s;%s;%s;%s;%s;".
        "%s;%s;%s;%s;".
        "%s;%s;%s;%s;%s\n", $rows["login"],  $rows["nom"], $rows["prenom"],$rows["telephone"], $rows["email"],
        $rows["profession"], strval ($rows["photo"]), strval ($rows["departement"]),strval ($rows["rfu1"]),strval ($rows["rfu2"]),strval ($rows["status"]),$rows["type"],
        strval ($rows["dmaj"]),$rows["adeli"]);

}
fclose($fp);
echo json_encode($result);

?>
