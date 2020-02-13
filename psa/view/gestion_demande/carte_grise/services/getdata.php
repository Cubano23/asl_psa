<?php
require_once "bean/DemandeCGHistorique.php";
require_once "bean/DemandeCGIdentification.php";
require_once "bean/DemandeCGStatus.php";
require_once "bean/DemandeCGSuivi.php";

include 'conn.php';

$id = $_GET["id"];
$inf_login = $_SESSION["id.login"];

if(!isset($id)){
    echo "error";
    die;
}

else if($id == -1)
{
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
    $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id';
    $order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';

    $infirmiereSearch = isset($_POST['infirmiere_search']) ? clean($_POST['infirmiere_search'], 1) : null;
    $statusSearch = isset($_POST['status_search']) ? (int)$_POST['status_search'] : null;
    $dateSearch = isset($_POST['dsearch']) ? clean($_POST['dsearch'], 1) : '';

    $identifiant_demande = "";
    if (isset($_POST['identifiant_demande']))
        $identifiant_demande = $_POST['identifiant_demande'];

    $demandCGSuivi = new DemandeCGSuivi();
    $infProfession = $demandCGSuivi->getUserProfessionByLogin($inf_login);

    switch ($infProfession)
    {
        case "gestionnaire":
            if ($identifiant_demande == "")
                $rs = $demandCGSuivi->load($page, $rows, $sort, $order, $infirmiereSearch, $statusSearch, $dateSearch);
            else
                $rs = $demandCGSuivi->loadDetail($identifiant_demande);

            $items = array();
            foreach ($rs['data'] as $row)
            {
                $rows = (array)$row;
                foreach( $rows as $key => $value)
                {
                    if (($key == "dmaj") || ($key == "date_demande")  || ($key == "date_obtention"))
                    {
                        $value = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $value);
                    }
                    if ($key == "justificatif")
                    {
                        $filepath = $config->psa_path.'/view/gestion_demande/carte_grise/services/download.php?file='.$value;
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
                $rs = $demandCGSuivi->load($page, $rows, $sort, $order, $inf_login, $statusSearch, $dateSearch);
            else
                $rs = $demandCGSuivi->loadDetail($identifiant_demande);

            $items = array();
            foreach ($rs['data'] as $row)
            {
                $rows = (array)$row;
                foreach( $rows as $key => $value)
                {
                    if (($key == "dmaj") || ($key == "date_demande")  || ($key == "date_obtention"))
                    {
                        $value = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $value);
                    }
                    if ($key == "justificatif")
                    {
                        $filepath = $config->psa_path.'/view/gestion_demande/carte_grise/services/download.php?file='.$value;
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
    $cgSuivi = new DemandeCGSuivi();
    $result = $cgSuivi->loadById($id);

    $myDateTime = DateTime::createFromFormat('Y-m-d',  $result['date_demande']);
    $result['date_demande'] = $myDateTime->format('d/m/Y');

    $result['date_dernierStatut'] = $myDateTime->format('d/m/Y');

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