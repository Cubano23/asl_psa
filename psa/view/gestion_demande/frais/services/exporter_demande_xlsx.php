<?PHP
require("bean/DemandeFraisHistorique.php");

$id_status = $_GET["id_status"];

$demande = new DemandeFraisHistorique();
$listDemande = $demande->getAllByStatus($id_status);

  function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // filename for download
  $filename = "Liste_demande_" . date('Ymd') . ".xlsx";

  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type: application/vnd.ms-excel");

  $flag = false;
  foreach($listDemande as $row) {
    if(!$flag) {
     
      echo implode(",", array_keys($row)) . "\r\n";
      $flag = true;
    }
    array_walk($row, __NAMESPACE__ . '\cleanData');
    echo implode(",", array_values($row)) . "\r\n";
  }
  exit;

?>