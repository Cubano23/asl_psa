<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php require_once("tools/date.php"); ?>
<?php require_once("tools/arrays.php"); ?>

<?php global $account; ?>
<?php global $rowsList; ?>
<?php global $param; ?>
<?php global $currentObjectName; ?>
<?php global $currentObjectClass; ?>
<?php global $signature; ?>


<div style="float:right;color:#fff;"><?php echo "<a style='color:#fff;' title='fermer' href='javascript://' onclick=\"ajax_hideTooltip()\">X</a><br>";?></div>
<table width="100%"  border="1" cellspacing="0" cellpadding="0">
 
  <CAPTION  ><?php echo(count($rowsList)) ?> enregistrements trouv&eacute;s</CAPTION>
  
  <tr>
    <th scope="col">&nbsp;Date consultation</th>
	<th scope="col">&nbsp;Source</th>
	<th scope="col">&nbsp;Tabac</th>
	<th scope="col">&nbsp;Nbre paquets/années</th>
	<th scope="col">&nbsp;Année de démarrage</th>
	<th scope="col">&nbsp;Date d'arrêt</th>
	<th scope="col">&nbsp;Type tabac</th>
	<th scope="col">&nbsp;Spirométrie / date</th>
	<th scope="col">&nbsp;Spirométrie / CVF</th>
	<th scope="col">&nbsp;Spirométrie / VEMS</th>
	<th scope="col">&nbsp;Spirométrie / DEP</th>
	<th scope="col">&nbsp;Spirométrie / type</th>
	<th scope="col">&nbsp;Test CO / date</th>
	<th scope="col">&nbsp;Test CO / Résultat (PPM)</th>
	<th scope="col">&nbsp;Fagerstrom</th>
	<th scope="col">&nbsp;Horn / stimulation</th>
	<th scope="col">&nbsp;Horn / plaisir</th>
	<th scope="col">&nbsp;Horn / relaxation</th>
	<th scope="col">&nbsp;Horn / anxiété</th>
	<th scope="col">&nbsp;Horn / besoin</th>
	<th scope="col">&nbsp;Horn / habitude</th>
	<th scope="col">&nbsp;HAD / Anxiété</th>
	<th scope="col">&nbsp;HAD / Dépression</th>
	<th scope="col">&nbsp;Motivation / efficacité</th>
	<th scope="col">&nbsp;Motivation / importance</th>
	<th scope="col">&nbsp;Motivation / stade</th>
	<th scope="col">&nbsp;Poids / date</th>
	<th scope="col">&nbsp;Poids / valeur</th>
	<th scope="col">&nbsp;Activité physique</th>
	<th scope="col">&nbsp;Addictions</th>
  </tr>
  <?php foreach($rowsList as $k => $item): ?>
	  <tr>
	    <td>&nbsp;<?php echo mysqlDateTodate($k); ?></td>	
		<td>&nbsp;<?php echo isset($item['HDL']) ? 'rcva' : 'sevrage tabac' ?></td>
		<td>&nbsp;<?php echo $item['tabac'] ?></td>
		<td>&nbsp;<?php echo $item['nbrtabac'] ?></td>
		<td>&nbsp;<?php echo $item['ddebut'] ?></td>
		<td>&nbsp;<?php echo mysqlDateTodate($item['darret']) ?></td>
		<td>&nbsp;<?php echo $item['type_tabac'] ?></td>
		<td>&nbsp;<?php echo mysqlDateTodate($item['spirometrie_date']) ?></td>
		<td>&nbsp;<?php echo $item['spirometrie_CVF'] ?></td>
		<td>&nbsp;<?php echo $item['spirometrie_VEMS'] ?></td>
		<td>&nbsp;<?php echo $item['spirometrie_DEP'] ?></td>
		<td>&nbsp;<?php echo $item['spirometrie_status'] ?></td>
		<td>&nbsp;<?php echo mysqlDateTodate($item['dco_test']) ?></td>
		<td>&nbsp;<?php echo $item['co_ppm'] ?></td>
		<td>&nbsp;<?php echo $item['fagerstrom'] ?></td>
		<td>&nbsp;<?php echo $item['horn_stimulation'] ?></td>
		<td>&nbsp;<?php echo $item['horn_plaisir'] ?></td>
		<td>&nbsp;<?php echo $item['horn_relaxation'] ?></td>
		<td>&nbsp;<?php echo $item['horn_anxiete'] ?></td>
		<td>&nbsp;<?php echo $item['horn_besoin'] ?></td>
		<td>&nbsp;<?php echo $item['horn_habitude'] ?></td>
		<td>&nbsp;<?php echo $item['had_anxiete'] ?></td>
		<td>&nbsp;<?php echo $item['had_depression'] ?></td>
		<td>&nbsp;<?php echo $item['echelle_analogique'] ?></td>
		<td>&nbsp;<?php echo $item['echelle_confiance'] ?></td>
		<td>&nbsp;<?php echo $item['stade_motivationnel'] ?></td>
		<td>&nbsp;<?php echo mysqlDateTodate($item['dpoids']) ?></td>
		<td>&nbsp;<?php echo $item['poids'] ?></td>
		<td>&nbsp;<?php echo $item['activite'] ?></td>
		<td>&nbsp;<?php echo $item['alcool'] ?></td>
	  </tr>
  <?php endforeach ?>
</table>



