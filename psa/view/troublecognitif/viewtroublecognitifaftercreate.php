<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php global $account ?>
<?php global $dossier; ?>
<?php global $TroubleCognitif; ?>
<?php global $param; ?>

 
<form action='<?php echo("$path/controler/ActionControler.php");?>' method="post" name="manage">
<?php hiddenControler(""); ?>
<?php hiddenAction(""); ?>
<?php hiddenParam1(""); ?>
<?php hidden("","dossier:numero"); ?>
<?php hidden("","TroubleCognitif:date"); ?>

<?php require("view/common/dossierresume.php");?>

  <b>Type de dépistage:</b><br>
  <table border=1>
<?php if($TroubleCognitif->dep_type=='coll'){?>

    <tr>
    	<td colspan='2'>Dépistage collectif</td>
	<tr>
		<td valign='top'>Date Rappel : </td><td valign='top'> <?php echo ($TroubleCognitif->date_rappel);?></td>
    </tr>
    </tr>
    <?php }

else
{?>
    <tr>
      <td valign='top' colspan="2">Dépistage individuel</td></tr>
      <tr>
	  		<td valign="top">Raison du dépistage : </td><td> <?php echo $TroubleCognitif->raison_dep ?></td>
		</tr>
	<tr>
	<td valign='top'>Date Rappel : </td><td valign='top'> <?php echo ($TroubleCognitif->date_rappel);?></td>
    </tr>
<?php }

if($TroubleCognitif->sortir_rappel=='1'){
	?>
	<tr>
		<td colspan='2'>Cette personne est sortie du dépistage</td>
	</Tr>
	<tr>
		<td>Raison de la sortie : </Td>
			<td><?php echo nl2br($TroubleCognitif->raison_sortie);?></td>
	</Tr>
	<?php
}
?>  </table>
<br><br>
<?php

 if(in_array('mmse',$TroubleCognitif->suivi_type)) {?>
    <table border='1' width='100%' align='center'>
      <caption>
  <big><b><font color='blue'>MMSE</font></b></big>
  </caption>

        <tr>
          <td width="20%">Score :
          </td>
          <td><?php echo $TroubleCognitif->get_mmse(); ?></td>
  </tr>
</table>
<?php
 }

?>
<br><br>
<?php

 if(in_array('gds',$TroubleCognitif->suivi_type)) {?>
    <table border='1' width='100%' align='center'>
      <caption>
  <big><b><font color='green'>GDS</font></b></big>
  </caption>

        <tr>
          <td width="20%">Score :
          </td>
          <td><?php echo $TroubleCognitif->get_gds(); ?></td>
  </tr>
</table>
<?php

 }

?>
<br><br>
<?php

 if(in_array('iadl',$TroubleCognitif->suivi_type)) {?>
    <table border='1' width='100%' align='center'>
      <caption>
  <big><b><font color='#FF00FF'>IADL</font></b></big>
  </caption>

        <tr>
          <td width="20%">Score :
          </td>
          <td><?php echo $TroubleCognitif->get_iadl(); ?></td>
  </tr>
</table>
<?php


 }

?>
<br><br>

<?php if(in_array('horl',$TroubleCognitif->suivi_type)) {?>
    <table border='1' width='100%' align='center'> 
      <caption>
  <big><b><font color='brown'>Test de l'horloge</font></b></big>
  </caption>

        <tr>
          <td width="20%">Score :
          </td>
          <td><?php echo($TroubleCognitif->horloge); ?></td>
  </tr> 
</table> 
<?php


}

?>

<br><br>

<?php if(in_array('dubois',$TroubleCognitif->suivi_type)) {?>
    <table border='1' width='100%' align='center'> 
      <caption>
        <big><b><font color='orange'>5 mots de dubois</font></b></big>
      </caption>

      <tr>
          <td width="20%">Score :</td>
          <td>Mots sans indice : <?php echo($TroubleCognitif->dubois_immediatsi); ?></td>
          <td>Mots avec indice : <?php echo($TroubleCognitif->dubois_immediatai); ?></td>
          <td>&Eacute;tape rappel différé mots sans indice : <?php echo($TroubleCognitif->dubois_diffsi); ?></td>
          <td>&Eacute;tape rappel différé mots avec indice : <?php echo($TroubleCognitif->dubois_diffai); ?></td>
      </tr> 
    </table> 
<?php


}

?>

<br>
<?php require("view/troublecognitif/troublecognitifpdf.php");?>
<br>
<table border="0" width='100%' align="center">
  <tr>
    <td>
        <?php


            $rep = $config->files_path."/tc/".$account->cabinet.'/';
            $myDateTime = DateTime::createFromFormat('m/d/Y', $TroubleCognitif->date);
            $newDateString = $myDateTime->format('Y-m-d');
            $file= 'TroubleCognitif_'.$newDateString.'_Doss_'.$dossier->numero.'_cab_'.$account->cabinet.'.pdf';

        ?>

        <button type="button" onclick="location.href=' <?php echo $config->psa_path.'/view/troublecognitif/download.php?file='.$file.'&rep='.$rep?>'">Télécharger</button>
        <?php customSubmitWithAlert("value='Supprimer ce suivi'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous réellement supprimer ce suivi ?"); ?>
	</td>
    <td>
        <?php customSubmit("value='Modifier ce suivi'",ACTION_FIND,array(PARAM_EDIT),$param->controler); ?>
        <?php customSubmit("value='Faire un autre suivi'",ACTION_MANAGE,array(PARAM_PRE_CREATE),$param->controler); ?>

    </td>
  </tr>
</table>
</form>
