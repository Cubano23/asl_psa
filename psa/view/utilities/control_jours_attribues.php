<?php
echo'<meta charset="utf-8">';

require_once("../../persistence/ConnectionFactory.php");
#require_once("../bean/EvaluationInfirmier.php");
#equire_once("../../bean/SuiviHebdomadaireTempsPasse.php");
require_once("../../bean/dashboard.php");
#require_once("../../persistence/EvaluationInfirmierMapper.php");
require_once("../../controler/remunerationControler.php");
require_once("../../controler/UtilityControler.php");


$cf = new ConnectionFactory();
$cf->getConnection();


include ("nav_rem.php");


$listeCabinets = remuneration::listeCabinet();

?>
<h3>Mémorisation du nombre de jours forfaitaires attribués par medecins/cabinet</h3>
<p>Ce tableau indique le nombre de jours forfaitaires attribués pour chaque médecin dans chaque cabinet.</p>


<?php foreach($listeCabinets as $cabinet){
	echo '<h4>'.$cabinet.'</h4>';
	// pour chaque cabinet on liste les médecins et les jours attribués dans la table
	$mgs = remuneration::listeMG($cabinet);

	foreach($mgs as $mg){
		echo '<h5 style="margin-left:20px">'.$mg->id.' - '.utf8_encode(strtoupper($mg->nom)).' '.utf8_encode($mg->prenom).'</h5>';
		$total_jours = 0;
		// on récup chaque attribution de jours forfaitaires
		$listeJours = remuneration::giveAllJoursForfaitairesDejaAttribues($cabinet,$mg->id);

		foreach($listeJours as $jours){
			echo '<p style="margin-left:40px">Periode du '.utilityControler::inverseDate($jours->date_debut,'fr').' au '.utilityControler::inverseDate($jours->date_fin,'fr').' => '.$jours->nbre_jours.'</p>';
			$total_jours = $total_jours + $jours->nbre_jours;
		}

		echo '<h5 style="color:green;margin-left:40px">Soit '.$total_jours.' jour(s) forfaitaires attribués</h5>';

	}

	?>
	<?php ?>
<?php	
}
?>

</table>