<?php
echo'<meta charset="utf-8">';

require_once("../../persistence/ConnectionFactory.php");
#require_once("../bean/EvaluationInfirmier.php");
#equire_once("../../bean/SuiviHebdomadaireTempsPasse.php");
require_once("../../bean/dashboard.php");
#require_once("../../persistence/EvaluationInfirmierMapper.php");
require_once("../../controler/remunerationControler.php");


$cf = new ConnectionFactory();
$cf->getConnection();


include ("nav_rem.php");


$results = Dashboard::calculForAllPeriod();

?>
<h3>Tableaux de bord enregistrés en base locale</h3>
<p>Ce tableau indique le nombre de tableau de bord qui ont été enregistrés dnas la base de donnée locale pour chaque mois.</p>
<table border="1" cellpadding="3">
	<tr>
		<td>Période enregistrée</td>
		<td align="center">Nombre de TDB</td>
	</tr>

<?php foreach($results as $res){
	?>
	<tr>
		<td><?php echo $res['periode'];?></td>
		<td align="center"><?php echo $res['nbre'];?></td>
	</tr>
<?php	
}
?>

</table>