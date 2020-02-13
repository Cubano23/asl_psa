<?php
require_once "bean/DemandeFraisHistorique.php";
require_once "bean/DemandeFraisIdentification.php";
require_once "bean/DemandeFraisStatus.php";
require_once "bean/DemandeFraisSuivi.php";

include 'conn.php';


$fraisStatuses = new DemandeFraisStatus();
$list_status = $fraisStatuses->getStatuses();

$id = $_GET["id"];

$inf_login = $_SESSION["id.login"];

if(!isset($id)){
    echo "error";
    die;
}

else if ($id == -2)
{
    $fraisSuivi = new DemandeFraisSuivi();
    $data = $fraisSuivi->getUserTauxAndPowerByLogin($inf_login);
    $data['puissance'] = (int) $data['puissance'];
    

    echo json_encode($data);    
}

else if($id == -1)
{
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
    $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id';
    $order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';

    $natureSearch = isset($_POST['nsearch']) ? clean($_POST['nsearch'], 1) : '';
    $infirmiereSearch = isset($_POST['infirmiere_search']) ? clean($_POST['infirmiere_search'], 1) : null;
    $statusSearch = isset($_POST['status_search']) ? (int)$_POST['status_search'] : null;
    $idSearch = isset($_POST['idsearch']) ? clean($_POST['idsearch'], 1) : null;
    $dateSearch = isset($_POST['dsearch']) ? clean($_POST['dsearch'], 1) : '';
    $dateSearch_frais = isset($_POST['dsearch_frais']) ? clean($_POST['dsearch_frais'], 1) : '';

    if ($dateSearch != '')
    {
        $tabDate = explode('/' , $dateSearch);
        $dateSearch  = $tabDate[2].'/'.$tabDate[1].'/'.$tabDate[0];
    }
    if ($dateSearch_frais != '')
    {
        $tabDate = explode('/' , $dateSearch_frais);
        $dateSearch_frais  = $tabDate[2].'/'.$tabDate[1].'/'.$tabDate[0];
    }
   
    
    

    $identifiant_demande = "";
    if (isset($_POST['identifiant_demande']))
        $identifiant_demande = $_POST['identifiant_demande'];

    $demandFraisSuivi = new DemandeFraisSuivi();
    $infProfession = $demandFraisSuivi->getUserProfessionByLogin($inf_login);

    switch ($infProfession)
    {
        case "gestionnaire":
            if ($identifiant_demande == "")
                $rs = $demandFraisSuivi->load($page, $rows, $sort, $order, $natureSearch, $infirmiereSearch, $statusSearch, $dateSearch, $idSearch, $dateSearch_frais);
            else
                $rs = $demandFraisSuivi->loadDetail($identifiant_demande);

            $items = array();
            foreach ($rs['data'] as $row)
            {
                $rows = (array)$row;
                foreach( $rows as $key => $value)
                {
                    if (($key == "dmaj") || ($key == "date_demande")  || ($key == "date_frais"))
                    {
                        $value = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $value);
                    }

                    if ($key == "justificatif")
                    {
                        $filepath = $config->psa_path.'/view/gestion_demande/frais/services/download.php?file='.$value;
                        $value = '<button type="button" onclick="location.href=\''.$filepath.'\'">Justificatif</button>';
                    }

                    if ($identifiant_demande == "")
                    {
                        if ($key == "dernierStatus")
                        {
                           
                           
                            $vraiValeur = $value;
                            
                            $value = "<select onchange='changeStatus(this.value)'>"; 
                           
                            foreach ($list_status as $status)
                            {
                                if (htmlentities($status['intitule'], ENT_QUOTES, "ISO-8859-1") == htmlentities($vraiValeur, ENT_QUOTES, "ISO-8859-1"))
                                    $value .=  "<option value='". $status['id'] ."' selected>". htmlentities($status['intitule'], ENT_QUOTES, "ISO-8859-1") ."</option>";
                                else
                                    $value .=  "<option value='". $status['id'] ."'>". htmlentities($status['intitule'], ENT_QUOTES, "ISO-8859-1") ."</option>";
                            }
                            $value .= "</select>";
                        }
                    }

                    if(!is_null($value))
                    {
                        $value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);
                        $rows[$key] = $value;
                    }

                    if ($key == "date_dernierStatut")
                    {
                        $myDateTime = DateTime::createFromFormat('Y-m-d H:i:s',  $value);
                        $rows[$key] = $myDateTime->format('d/m/Y H:i:s');
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
                $rs = $demandFraisSuivi->load($page, $rows, $sort, $order, $natureSearch, $inf_login, $statusSearch, $dateSearch, $idSearch, $dateSearch_frais);
            else
                $rs = $demandFraisSuivi->loadDetail($identifiant_demande);

            $items = array();
            foreach ($rs['data'] as $row)
            {
                $rows = (array)$row;
                foreach( $rows as $key => $value)
                {
                    if (($key == "dmaj") || ($key == "date_demande")  || ($key == "date_frais"))
                    {
                        $value = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $value);
                    }
                    if ($key == "justificatif")
                    {
                        $filepath = $config->psa_path.'/view/gestion_demande/frais/services/download.php?file='.$value;
                        $value = '<button type="button" onclick="location.href=\''.$filepath.'\'">Justificatif</button>';
                    }
                    if(!is_null($value))
                    {
                        $value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);
                        $rows[$key] = $value;
                    }

                    if ($key == "date_dernierStatut")
                    {
                        $myDateTime = DateTime::createFromFormat('Y-m-d H:i:s',  $value);
                        $rows[$key] = $myDateTime->format('d/m/Y H:i:s');
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
    $fraisSuivi = new DemandeFraisSuivi();
    $result = $fraisSuivi->loadById($id);

    $myDateTime = DateTime::createFromFormat('Y-m-d',  $result['date_demande']);
    $result['date_demande'] = $myDateTime->format('d/m/Y');

    $myDateTime = DateTime::createFromFormat('Y-m-d',  $result['date_frais']);
    $result['date_frais'] = $myDateTime->format('d/m/Y');

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