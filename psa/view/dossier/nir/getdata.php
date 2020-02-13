<?php
/*

Thu, 08 Jan 2015 18:33:52 +0100 INFO DepistageDiabeteMapper Custom Find Rows Lancer la requeteselect * from depistage_diabete,dossier where cabinet='zTest' and depistage_diabete.id = dossier.id AND dossier.actif='oui' GROUP BY dossier.numero
Thu, 08 Jan 2015 18:35:27 +0100 INFO DepistageCancerColonMapper Custom Find Rows Lancer la requeteselect * from depistage_colon,dossier where cabinet='zTest' and depistage_colon.id = dossier.id AND dossier.actif='oui' GROUP BY dossier.numero
Thu, 08 Jan 2015 18:35:36 +0100 INFO DepistageCancerSeinMapper Custom Find Rows Lancer la requeteselect * from depistage_sein,dossier where cabinet='zTest' and depistage_sein.id = dossier.id AND dossier.actif='oui' GROUP BY dossier.numero
Thu, 08 Jan 2015 18:35:50 +0100 INFO DepistageCancerUterusMapper Custom Find Rows Lancer la requeteselect * from depistage_uterus,dossier where cabinet='zTest' and depistage_uterus.id = dossier.id AND dossier.actif='oui' GROUP BY dossier.numero
Thu, 08 Jan 2015 18:35:58 +0100 INFO HyperTensionArterielleMapper Custom Find Rows Lancer la requeteselect * from hyper_tension,dossier where cabinet='zTest' and hyper_tension.id = dossier.id AND dossier.actif='oui' GROUP BY dossier.numero
Thu, 08 Jan 2015 18:36:07 +0100 INFO TroubleCognitifMapper Custom Find Rows Lancer la requeteselect * from trouble_cognitif,dossier where cabinet='zTest' and trouble_cognitif.id = dossier.id AND dossier.actif='oui' GROUP BY dossier.numero ORDER BY dossier.numero
Thu, 08 Jan 2015 18:36:21 +0100 INFO CardioVasculaireDepartMapper Custom Find Rows Lancer la requeteselect * from cardio_vasculaire_depart,dossier where cabinet='zTest' and cardio_vasculaire_depart.id = dossier.id AND dossier.actif='oui' AND spirometrie_date!='0000-00-00' GROUP BY dossier.numero


*/
session_start();
$cabinet = $_SESSION["cabinet"];
include 'conn.php';

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'numero';
$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
$ndsearch = isset($_POST['ndsearch']) ? clean($_POST['ndsearch'], 1) : '';
$dsearch = isset($_POST['dsearch']) ? clean($_POST['dsearch'], 1) : '0';
$nsearch = isset($_POST['nsearch']) ? clean($_POST['nsearch'], 1) : '0';
$psearch = isset($_POST['psearch']) ? clean($_POST['psearch'], 1) : '0';

if($cabinet=="Chizé")
    $cabinet = "Chize";

$where = " where cabinet = '$cabinet' and numero LIKE '$ndsearch%' ";

//Filtre  Date consentement
$where2 = "";
if($dsearch=='1')
    $where2=" and dconsentement >'0000-00-00' ";
if($dsearch=='2')
    $where2=" and dconsentement ='0000-00-00' ";
$where = $where.$where2;

//Filtre  Nir chiffré
$where2 = "";
if($nsearch=='1')
    $where2=" and encnir !='' ";
if($nsearch=='2')
    $where2=" and encnir ='' ";
$where = $where.$where2;

// error_log($nsearch.",".$where);

$offset = ($page-1)*$rows;
$result = array();

$rs = mysql_query("select count(*) from $table " .$where);
$row = mysql_fetch_row($rs);
$result["total"] = $row[0];
$sql2 = " order by $sort $order limit $offset,$rows";
$sql = "select * from $table ";

$sql = $sql.$where.$sql2;
$rs = mysql_query($sql);

$items = array();
while($row = mysql_fetch_object($rs))
{
    $rows = (array)$row;
    foreach( $rows as $key => $value)
    {
        if ($key=="encnir")
        {

            /*      if (stristr($value,"BEGIN PGP MESSAGE" )!=false)
                       $value="O";
                     else
                           $value="N";*/
        }

        if (($key=="dnaiss")
            ||   ($key=="dconsentement")
            ||   ($key=="dcreat")
        )
        {


            $value = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $value);
        }
        if(!is_null($value))
        {

            $value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);
            $rows[$key] = $value;

        }
    }

    array_push($items, $rows);
}
$result["rows"] = $items;

require_once ("Config.php");
$config = new Config();
require_once($config->webservice_path ."/AsaleeLog.php");
LogAccess("", "dossier_getdata", $UserIDLog, 'na', $cabinet,  0, "Liste Dossiers:".$answerLog);



echo json_encode($result);

?>
