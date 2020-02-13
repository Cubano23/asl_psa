<?php
require_once "bean/DemandeRibHistorique.php";
require_once "bean/DemandeRibIdentification.php";
require_once "bean/DemandeRibStatus.php";
require_once "bean/DemandeRibSuivi.php";

include 'conn.php'	;

$id = $_GET["id"];
$inf_login = $_SESSION["id.login"];

if(!isset($id)){
    echo "error";
    die;
}

if ($id == -2)
{
    $ribSuivi = new DemandeRibSuivi();
  
   

   
}

else if($id == -1)
{
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
    $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id';
    $order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';

   
    $statusSearch = isset($_POST['status_search']) ? (int)$_POST['status_search'] : null;
    $dateSearch = isset($_POST['dsearch']) ? clean($_POST['dsearch'], 1) : '';

     $identifiant_demande = "";
    if (isset($_POST['identifiant_demande']))
        $identifiant_demande = $_POST['identifiant_demande'];

    $demandRibSuivi = new DemandeRibSuivi();
    $infProfession = $demandRibSuivi->getUserProfessionByLogin($inf_login);

    switch ($infProfession)
    {
        case "gestionnaire":
             if ($identifiant_demande == "")
                $rs = $demandRibSuivi->load($page, $rows, $sort, $order, $infirmiereSearch, $statusSearch, $dateSearch);
            else
                $rs = $demandRibSuivi->loadDetail($identifiant_demande);

            $items = array();
            foreach ($rs['data'] as $row)
            {
                $rows = (array)$row;
                foreach( $rows as $key => $value)
                {
                    if (($key == "dmaj") || ($key == "date_demande"))
                    {
                        $value = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $value);
                    }
                    if ($key == "justificatif")
                    {
                        $filepath = $config->psa_path.'/view/gestion_demande/rib/services/download.php?file='.$value;
                        $value = '<button type="button" onclick="location.href=\''.$filepath.'\'">Justificatif</button>';
                    }
                    if(!is_null($value))
                    {
                        $value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);
                        $rows[$key] = $value;
                    }
                }

                array_push($items, $rows);
            }

            $result["total"] = $rs["total"];
            $result["rows"] = $items;

            echo json_encode($result);
            break;

        default:
            if ($identifiant_demande == "")
                $rs = $demandRibSuivi->load($page, $rows, $sort, $order,  $inf_login, $statusSearch, $dateSearch);
            else
                $rs = $demandRibSuivi->loadDetail($identifiant_demande);

            $items = array();
            foreach ($rs['data'] as $row)
            {
                $rows = (array)$row;
                foreach( $rows as $key => $value)
                {
                    if (($key == "dmaj") || ($key == "date_demande"))
                    {
                        $value = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $value);
                    }
                    if ($key == "justificatif")
                    {
                        $filepath = $config->psa_path.'/view/gestion_demande/rib/services/download.php?file='.$value;
                        $value = '<button type="button" onclick="location.href=\''.$filepath.'\'">Justificatif</button>';
                    }
                    if(!is_null($value))
                    {
                        $value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);
                        $rows[$key] = $value;
                    }
                }

                array_push($items, $rows);
            }

            $result["total"] = $rs["total"];
            $result["rows"] = $items;

            echo json_encode($result);
            break;
    }
}
else
{
    $ribSuivi = new DemandeRibSuivi();
    $result = $ribSuivi->loadById($id);

    $myDateTime = DateTime::createFromFormat('Y-m-d',  $result['date_demande']);
    $result['date_demande'] = $myDateTime->format('d/m/Y');

    $myDateTime = DateTime::createFromFormat('Y-m-d H:i:s',  $result['date_dernierStatut']);
    $result['date_dernierStatut'] = $myDateTime->format('d/m/Y H:i:s');

    foreach( $result as $key => $value)
    {
        if(!is_null($value))
        {
            $value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);
            $result[$key] = $value;
        }
    }

    echo json_encode($result);
}

?>