<?php

function clean($src, $op)
{
    return utf8_decode ($src) ; //EA 27-05-2015

}

require_once ("Config.php");
$config = new Config();

require($config->inclus_path . "/connectannuaire.php");

$con = DoConnect();

$table = "identifications  ";

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'nom';
$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
$nsearch = isset($_POST['nsearch']) ? clean($_POST['nsearch'], 1) : '';
$lsearch = isset($_POST['lsearch']) ? clean($_POST['lsearch'], 1) : '';
$hsearch = isset($_POST['hsearch']) ? clean($_POST['hsearch'], 1) : '0';



if($sort=='dmaj2') {
    $sort = 'habilitations.dmaj';
}
    
$innerjoin = " inner join habilitations on identifications.login=habilitations.login ";
$leftjoin =  " left join allowedcabinets on allowedcabinets.login = identifications.login ";
$where=" where nom LIKE '$nsearch%' and  identifications.login LIKE '$lsearch%' ";
//$wherenon=" and where not exists (select hpassword from hpasswords where hpasswords.login = habilitations.login) ";
$where2="";
if($hsearch=='1'){
    $where2=" and  identifications.login in (select login from hpasswords ) ";
}
if($hsearch=='2'){
    $where2=" and  identifications.login not in (select login from hpasswords ) ";
}
$groupby = "GROUP BY identifications.login,nom,prenom,telephone, email,profession,type,adeli, pass, psa, psae, psaet,psv,psae,psvae,idcps,psar,erp, psamed, identifications.dmaj,habilitations.dmaj " ;

$where = $where. $where2;
$offset = ($page-1)*$rows;
$result = array();

$rs = mysql_query("select count(*) from $table ".$innerjoin.$where);
$row = mysql_fetch_row($rs);
$result["total"] = $row[0];
$sql2 = " order by $sort $order limit $offset,$rows";
$sql = "select identifications.login,nom,prenom,telephone, email,profession,type,adeli, pass, psa, psae, psaet,psv,psae,psvae,idcps,psar,erp, psamed, identifications.dmaj as dmaj,habilitations.dmaj as dmaj2 ,GROUP_CONCAT(distinct allowedcabinets.cabinet SEPARATOR ', ') as cabinets from identifications ";
$sql = $sql.$innerjoin.$leftjoin.$where.$groupby.$sql2;
$rs = mysql_query($sql);
$items = array();
while($row = mysql_fetch_object($rs))
{
    $rows = (array)$row;
    foreach( $rows as $key => $value)
    {

        if(!is_null($value)){
            $value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);
            $rows[$key] = $value;
        }
    }
    $k=$rows["login"];
    $sql5="select hpassword from hpasswords where login='$k' ";
    $rs5 = mysql_query($sql5);
    if($rs5)
    {
        if(mysql_num_rows($rs5) == 0){
            $rows["hpassword"] = "Non";
        }
        else{
            $rows["hpassword"] = "Oui";
        }
    }
    /*  if(strtime($dt2)>strtime($dt1))
            $rows["dmaj"]=$dt2;*/
    array_push($items, $rows);
}
$result["rows"] = $items;


require_once ("Config.php");
$config = new Config();

if($_SERVER['APPLICATION_ENV'] != 'dev-herve')
{
    require_once($config->webservice_path . "/AsaleeLog.php");
    LogAccess("psaet.asalee.fr", "hab_getdata", $UserIDLog, 'na', '',  0, "Liste Habilitations:".$answerLog);
}




echo json_encode($result);

?>
