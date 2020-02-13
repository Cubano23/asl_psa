<?php

require_once "Config.php";
$config = new Config();

global $dossier;

#echo count($sevrageTabacList);
echo '<table border="1">';


foreach($sevrageTabacList as $sevrage){

    $dateConsult = UtilityControler::inversedate($sevrage->date,'fr');

    $link = $config->psa_path . '/controler/ActionControler.php?controlerparams:param:controler=SevrageTabacControler&controlerparams:param:action=AN&controlerparams:param:param1=&Dossier:dossier:numero='.$dossier->numero.'&SevrageTabac:sevragetabac:date='.$dateConsult;

    echo'
  <tr><td>'.UtilityControler::inversedate($sevrage->date,'fr').'</td>
  <td><a class="details_sevrage" href="'.$link.'" target="_blank">Accéder à la consultation</a></td>
  </tr>';
}

echo '</table>';

?>

			
			
