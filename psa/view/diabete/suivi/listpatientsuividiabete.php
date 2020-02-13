<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php require_once("tools/date.php"); ?>
<?php require_once("tools/arrays.php"); ?>

<?php global $account ?>
<?php global $param ?>
<?php global $suiviDiabeteList;?>


<?php require("view/common/dossierresume.php");?>

<table border="1" cellpadding='3'>
<CAPTION>Liste des <?php echo(count($suiviDiabeteList)); ?> suivis trouvés</CAPTION>
<tr>
  <th>Date</th>
  <th>Type de bilan</th>
  <th>Consulter</th>
</tr>
  <?php for($i=0;$i<count($suiviDiabeteList);$i++){ 
  	$tmp = $suiviDiabeteList[$i]; ?>
	<tr>
		<td><?php echo($tmp->dsuivi) ?></td>
		<td>
			<?php if(in_array("4",$tmp->suivi_type)){?><font color='blue'><b>4 mois</b></font>&nbsp;<?php }?>
			<?php if(in_array("s",$tmp->suivi_type) || in_array("a",$tmp->suivi_type)){
						?><font color='brown'><b>annuel</b></font>&nbsp;<?php }?>
		</td>
		<td>
          <?php
				$additionalParams = array("Dossier:dossier:id"=>$tmp->dossier_id,
						"SuiviDiabete:suiviDiabete:dsuivi"=>$tmp->dsuivi);
				buildLink("","Fiche","$path/controler/ActionControler.php",$param->controler,ACTION_FIND,array(PARAM_VIEW,PARAM_SYSTEMATIQUE),$additionalParams); 
			?>
</td>
	</tr>

<?php } ?>
</table>

