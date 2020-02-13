<?PHP
require("bean/Allowedcabinets.php");



$utilisateurs = new Allowedcabinets();
$listUtilisateurs = $utilisateurs->getAllUtilisateurs();

  function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // filename for download
  $filename = "Liste_utilisateurs_" . date('Ymd') . ".xlsx";

  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type: application/vnd.ms-excel");

  $flag = false;
  foreach($listUtilisateurs as $row) {
    if(!$flag) {
     
      echo implode(",", array_keys($row)) . "\r\n";
      $flag = true;
    }
    array_walk($row, __NAMESPACE__ . '\cleanData');
    echo implode(",", array_values($row)) . "\r\n";
  }
  exit;

?>