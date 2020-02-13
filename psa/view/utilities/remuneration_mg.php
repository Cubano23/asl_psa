<?php
echo'<meta charset="utf-8">';

DEFINE("COEF_CS", 25);

require_once("../../persistence/ConnectionFactory.php");
#require_once("../bean/EvaluationInfirmier.php");
require_once("../../bean/SuiviHebdomadaireTempsPasse.php");
require_once("../../persistence/EvaluationInfirmierMapper.php");
require_once("../../controler/remunerationControler.php");
session_start();
#var_dump($_SESSION); 



// récupération des datas dans le formulaire de selection
if(empty($_POST['md']) || empty($_POST['yd']) || empty($_POST['mf']) || empty($_POST['yf']) || empty($_POST['nbre_jours_ouvrables'])){

	$DDR = '2015-05-01'; //date de début de rémunération
	$DFR = '2015-09-30'; //date de fin de rémunération
	$nbre_jours_ouvrables = 104;
	$TF = 121;
	$mode = 'simulation';

?>


<?php include("nav_rem.php"); ?>



<h4>Calcul de la rémunération des MG</h4>
<form action="remuneration_mg.php" method="POST">

<P>Dernier tableaux de bord édités : <?php echo $periodeTdb;?></p>

<p>Début de la période 
	<select name="md">
		<option value=''>Sélectionner</option>
		<option value='01'>Janvier</option>
		<option value='02'>Février</option>
		<option value='03'>Mars</option>
		<option value='04'>Avril</option>
		<option value='05'>Mai</option>
		<option value='06'>Juin</option>
		<option value='07'>Juillet</option>
		<option value='08'>Août</option>
		<option value='09'>Septembre</option>
		<option value='10'>Octobre</option>
		<option value='11'>Novembre</option>
		<option value='12'>Décembre</option>
	</select>
	<select name="yd">
		<option value=''>Sélectionner</option>
		<option value='<?php echo date("Y");?>'><?php echo date("Y");?></option>
		<option value='<?php echo date("Y")-1;?>'><?php echo date("Y")-1;?></option>
	</select>
</p>
<p>Fin de la période de calcul
	<select name="mf">
		<option value=''>Sélectionner</option>
		<option value='01'>Janvier</option>
		<option value='02'>Février</option>
		<option value='03'>Mars</option>
		<option value='04'>Avril</option>
		<option value='05'>Mai</option>
		<option value='06'>Juin</option>
		<option value='07'>Juillet</option>
		<option value='08'>Août</option>
		<option value='09'>Septembre</option>
		<option value='10'>Octobre</option>
		<option value='11'>Novembre</option>
		<option value='12'>Décembre</option>
	</select>
	<select name="yf">
		<option value=''>Sélectionner</option>
		<option value='<?php echo date("Y");?>'><?php echo date("Y");?></option>
		<option value='<?php echo date("Y")-1;?>'><?php echo date("Y")-1;?></option>
	</select>
</p>
<p>Nbre de jours ouvrés sur la période : <input type="text" name="nbre_jours_ouvrables"></p>
<p>Nbre de jours Total Forfaitaire : <input type="text" name="TF" value="121"></p>
<p>Mode de calcul : <input type="radio" name="mode" value="test" checked> Simulation | <input type="radio" name="mode" value="reel"> Reel</p>


	<input type="submit" value="calculer">
</form>
<?php
}
else{
$mode = $_POST['mode'];
$DDR = $_POST['yd'].'-'.$_POST['md'].'-01';

$jours31 = array('01','03','05','07','08','10','12');
if(in_array($_POST['mf'],$jours31)){$joursMois = '31';}else{$joursMois = '30';}
$DFR = $_POST['yf'].'-'.$_POST['mf'].'-'.$joursMois;


$nbre_jours_ouvrables = $_POST['nbre_jours_ouvrables'];

$periodes_dashboard = remuneration::givePeriodesForDashbord($_POST['md'],$_POST['yd'],$_POST['mf'],$_POST['yf']);

$TF = $_POST['TF']; // total forfaitaire a attribuer sur les rémunarations // correspond aussi au nombre de jours sur la période
#var_dump($periodes_dashboard);

echo '<p>Calcul de '.remuneration::giveMois($_POST['md']).' à '.remuneration::giveMois($_POST['mf']).' '.$_POST['yf'].'</p>';
echo '<p>'.$_POST['nbre_jours_ouvrables'].' jours ouvrables sur la période</p>';
echo '<p><a href="remuneration_mg.php">Faire une nouvelle recherche</a></p>';
echo '<p>Mode : '.$_POST['mode'];

#$DDR = '2015-05-01'; //date de début de rémunération
#$DFR = '2015-09-30'; //date de fin de rémunération
#$TF = 121; 
#$nbre_jours_ouvrables = 104;

// on vide tous les enregistrements de la table tempon qui stocke les jours attribués si on relance le calcul....
// on se base par rapport à la date de démarrage de la période

if($mode=='reel'){
	remuneration::videJfaForPeriode($DDR);
}



$serveur = 'localhost';
/*
// pierre
$idDB = 'root';
$mdpDB = 'root';
$DB = 'isas';
*/

if($_SERVER['APPLICATION_ENV']=='dev-herve'){
$idDB = 'root';
$mdpDB = 'root';
$DB = 'isas';
}
else{
	$idDB = 'informed';
	$mdpDB = 'no11iugX';
	$DB = 'informed3';
}

mysql_connect($serveur,$idDB,$mdpDB) or
   die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
   die("Impossible de se connecter &agrave; la base");


$cabinets = remuneration::listeCabinet();

#$cabinets = array('spincourt','saintyrieix','rambervillers','chazelles','bouille');
#$cabinets = array('amberieu','saintjeandugard','descartes','villeneuvelagarenne','rochette','valence');

$nb_jours_total_periode = remuneration::giveNbJoursByDate($DDR,$DFR);


$tspdeb = strtotime(date('Y-m-d H:i:s'));



#########

echo '
		<table border="1">
		<tr>
		<td>A</td>
		<td>B</td>
		<td>C</td>
		<td>D</td>
		<td>E</td>
		<td>F</td>
		<td>G</td>
		<td>H</td>
		<td>I</td>
		<td>J</td>
		<td>K</td>
		<td>L</td>
		<td>M</td>
		<td>N</td>
		<td>O</td>
		<td>P</td>
		<td>Q</td>
		<td>R</td>
		<td>S</td>
		<td>T</td>
		<td>U</td>
		<td>V</td>
		<td>W</td>
		<td>X</td>
		<td>Y</td>
		<td>Z</td>
		</tr>

		<tr>
		<td>Id MG</td>
		<td>Nom MG</td>
		<td>Prenom MG</td>
		<td>Cabinet</td>
		<td>Date entrée Cabinet</td>
		<td>Date sortie Cabinet</td>
		<td>Date de démarrage (1ere consult cabinet)</td>
		<td>Jours d\'activité dans la période</td>
		<td>Jours forfaitaire déjà attribués (nb)</td>
		<td>Jours forfaitaires attribués a la periode (nb)</td>
		<td>Prorata temporis forfaitaire (%)</td>
		<td>Prorata temporis variable (%)</td>
		<td>Temps de concertation</td>
		<td>Taux de rémunération concertation (%)</td>
		<td>Taux de remuneration consultation(%)</td>
		<td>Taux de rémunération composé</td>
		<td>Taux de rémunération composé encadré</td>
		<td>Nbre journées travaillées par les IDE du cabinet durant la période</td>
		<td>Nbre journées travaillées par les IDE du cabinet durant la période corrigée  en cas de démarrage durant la période</td>
		<td>Nombre de MG du cabinet</td>
		<td>Nombre d\'ETP IDE par MG en heure</td>
		<td>Taux ETP IDE par MG</td>
		<td>Base de départ</td>
		<td>Rémunération variable</td>
		<td>Rémunération forfaitaire</td>
		<td>Rémunération totale</td>
		</tr>
		';



foreach($cabinets as $cabinet){

	$liste_MG = remuneration::listeMG($cabinet);
	
	foreach ($liste_MG as $md){
		
		if(!empty($md->prenom) || !empty($md->nom)){


			$nom_medecin = $md->prenom.' '.$md->nom;
			$id_mg = $md->id;

			// recuperation de l'info historique des medecins/cabinet dans historique_medecin
			$entree = remuneration::giveDateEntreeByMG($id_mg,$cabinet);
			$sortie = remuneration::giveDateSortieByMG($id_mg,$cabinet);

			$firstConsult = remuneration::giveFirstConsultByCabinet($cabinet,$DFR);
			// nbre de jours sur la période de calcul de la paie
			
			
			// nbre de jours d'activité sur la période pour le cabinet
			$nb_jap = remuneration::nombre_de_jours_periode($DDR,$DFR,$firstConsult); 
			
			if($nb_jap < 0){ $nb_jap=0; }

			// jours forfaitaires déjà attribués
			$JFDA = remuneration::giveJoursForfaitairesDejaAttribues($cabinet,$DDR,$id_mg); // nombre de jours déjà attribués, données non existante en base a crée 

			// prorata temporis forfaitaire
			$pt_level = remuneration::getProrataTemporisLevel($firstConsult,$DDR,$DFR);

			$prorata = remuneration::getProrataTemporis($pt_level,$firstConsult,$nb_jap,$DDR,$DFR,$JFDA,$TF,$id_mg,$cabinet,$mode);
			
			$prorata_temporis_forfaitaire = $prorata['pt'];
			
			if($prorata_temporis_forfaitaire > 100){
				$prorata_temporis_forfaitaire = 100;
			}

			if($nb_jap==0){
				$nb_jf_restant = 0;
				$prorata_temporis_forfaitaire = 0;
			}else{
				$nb_jf_restant = $prorata['nbRestant'];
			}
			

			#$nb_jf_restant = remuneration::calculNbJoursForfaitairesRestant($cabinet,$prorata_temporis_forfaitaire,$nb_jours_total_periode,$JFDA,$DDR,$DFR);

			
			//prorata temporis variable
			$ptv = remuneration::calcul_prorata_temporis_variable($nb_jap,$nb_jours_total_periode,$prorata_temporis_forfaitaire,$TF,$nb_jf_restant);
			// le prorata temporis variable est plafonné à 100%
			if($ptv > 100){ $ptv = 100;} 
			if($nb_jap==0){ $ptv = 0;} 
			// TC = temps de concertation medecins : 
			$tc = remuneration::calcul_temp_concertation($cabinet,$id_mg,$DDR,$DFR);


			// trc etait là


			
			$temps_declare_periode = remuneration::getSommeTempsPeriode($cabinet,$periodes_dashboard); // en jours

			// nbre de journée travaillées par les ide durant la période corrigée en cas de demmarage durant le periode
			$coef_reduc = $nb_jours_total_periode/$nb_jap;
			$nbre_jours_travailles_corrige = round($temps_declare_periode*$coef_reduc,4);

			#$nbre_jours_travailles_corrige = $temps_declare_periode.'*('.$nb_jours_total_periode.'/'.$nb_jap.')'.$nbre_jours_travailles_corrige;
			// nbre de medecin generaliste dans le cabinet
			$nbre_mg_cab = count($liste_MG);

			//nbre d'equivalent temps plein IDE par MG
			#$nb_etp_ide_par_mg = round($nbre_jours_travailles_corrige * 1.1 / $nbre_mg_cab,2);
			

			//Modification ARI le 23/05/2018  : remplacement de 1.125 par 1.0 sur demande de PDA
		        //$calc1 = $nbre_jours_travailles_corrige*1.125;
			$calc1 = $nbre_jours_travailles_corrige*1.0;

			$calc2 = $nbre_jours_ouvrables*$nbre_mg_cab;

			$nb_etp_ide_par_mg = round($calc1/$calc2,4);
			if($nb_etp_ide_par_mg > 1){
				$nb_etp_ide_par_mg = 1;
			}

			$taux_etp_ide_par_mg = round($nb_etp_ide_par_mg/0.2,2);

			
			##### deplacement du 19.12.2016
			// calcul TRC = taux de rémunération concertation
			#$tcm = '120'; // c'est un fixe qu'ils ont eux défini
			if($taux_etp_ide_par_mg < 1)
			{
			 	$tcm = 120*$taux_etp_ide_par_mg;
			}
			else{
			 	$tcm = 120;
			}
			$trc = remuneration::calcul_tx_rem_concertation($tc,$tcm,$DDR,$DFR);
			#$trc = remuneration::calcul_tx_rem_concertation($tc,$tcm,$DDR,$D
			#$nbre_jours_periode = getNombreJoursPeriode($cabinet,$DDR,$DFR);
			// calcul taux de consultation
			$tx_consult = remuneration::calcul_taux_consultation($cabinet,$periodes_dashboard);

			// taux de remuneration composé est le produit du taux de consultation et du taux de rémuneration de concertation
			$tx_rem_compose = round($tx_consult*$trc,4);

			// on plafonne le taux de remuneration composé dans une fourchette de 15 à 300
			$tx_rem_compose_encadre = remuneration::ajusteTauxRemCompose($tx_rem_compose);


			#### fin de placement du 19.12.2016


			$nbre_mois_periode = round($nb_jap/30);
			
			$base_de_depart = 12* COEF_CS *$nbre_mois_periode;

			$rem_variable = round($base_de_depart*$tx_rem_compose_encadre/100*$ptv/100*$taux_etp_ide_par_mg,4)*100;

			
			$rem_variable_plafonnee = remuneration::getPlafondRemVariable($rem_variable,$base_de_depart,$taux_etp_ide_par_mg);

			$rem_f = remuneration::calcul_rem_forfaitaire($prorata_temporis_forfaitaire,$taux_etp_ide_par_mg);
			
			
			$rem_totale = $rem_f+$rem_variable_plafonnee;
			
			$plafond = (12* COEF_CS *$nb_jap*$taux_etp_ide_par_mg)/30;
			
			if($rem_totale > $plafond){
				$rem_totale = $plafond;
			}
			
			echo '
			<tr>
			<td>'.$md->id.'</td>
			<td>'.utf8_encode($md->nom).'</td>
			<td>'.utf8_encode($md->prenom).'</td>
			<td>'.$cabinet.'</td>
			<td>'.substr($entree->dstatus,0,10).'</td>
			<td>'.substr($sortie->dstatus,0,10).'</td>
			<td>'.$firstConsult.'</td>
			<td>'.$nb_jap.'</td>
			<td>'.$JFDA.'</td>
			<td>'.$nb_jf_restant.'</td>
			<td>'.$prorata_temporis_forfaitaire.'</td> 
			<td>'.$ptv.'</td>
			<td>'.$tc.'</td>
			<td>'.$trc.'</td>
			<td>'.$tx_consult.'</td>
			<td>'.$tx_rem_compose.'</td>
			<td>'.$tx_rem_compose_encadre.'</td>
			<td>'.$temps_declare_periode.'</td>
			<td>'.$nbre_jours_travailles_corrige.'</td>
			<td>'.$nbre_mg_cab.'</td>
			<td>'.$nb_etp_ide_par_mg.'</td>
			<td>'.$taux_etp_ide_par_mg.'</td>
			<td>'.$base_de_depart.'</td>
			<td>'.$rem_variable.'</td>
			<td>'.$rem_f.'</td>
			<td>'.$rem_totale.'</td>
			</tr>';
		}
	}		
}
echo '</table>';
$tspfin = strtotime(date('Y-m-d H:i:s'));

$timing = $tspfin-$tspdeb;
if($timing > 120){$timing = $timing/60; echo 'temps écoulé : '.$timing. 'minutes';}
else { echo 'temps écoulé : '.round($timing,4).' secondes';}
}
?>
