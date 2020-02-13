<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>

<?php global $account ?>
<?php global $dossier; ?>
<?php global $Hemocult; ?>
<?php global $param; ?>

<form action='<?php echo("$path/controler/ActionControler.php");?>' method="post" name="manage">
<?php hiddenControler("HemocultControler"); ?>
<?php hiddenAction(ACTION_FIND); ?>
<?php hiddenParam1(PARAM_EDIT); ?>
<?php hidden("","dossier:numero"); ?>
<?php hidden("","Hemocult:date"); ?>

<?php require("view/common/dossierresume.php");?>
	
<table  width='100%' align='center'> 
  <tr> 
    <td valign='top'>&nbsp; </td> 
    <td> 
      <table border='1' cellpadding='3'>
        <tr>
          <th>Question</th>
          <th>Réponse(s)</th>
        </tr>
        <tr>
          <th align="left" scope="row" colspan=2>Test d'hémoccult</th>
        </tr>
        <tr>
          <th align="left" scope="row">Date de convocation</th>
	          <td><?php echo ($Hemocult->date_convoc);?>&nbsp;</td>
        </tr>
        <tr>
          <th align="left" scope="row">Date remise des plaquettes</th>
          <td><?php echo$Hemocult->date_plaquette;
				?>&nbsp;</td>
        </tr>
        <tr>
          <th align="left" scope="row">Date de résultat : </th>
          <td><?php echo($Hemocult->date_resultat); ?>&nbsp;</td>
        </tr>
        <tr>
          <th align='left' scope="row">Résultat</th>
          <td><?php if($Hemocult->resultat=='1') echo "Positif";
		  			elseif($Hemocult->resultat=="0") echo "Négatif";
					else echo ""; ?>&nbsp;</td>
        </tr>
        <tr>
          <th align="left" scope="row">Rappel</th>
	          <td><?php if($Hemocult->rappel=='0') echo "Pas de rappel";
			  			else echo $Hemocult->date_rappel;?>&nbsp;</td>
        </tr>
      </table>
      <br>
      <table border="1" <?php echo($Hemocult->sortir_rappel=="1"?"":"class='hidden'")?>>
        <tr>
			<td colspan="2"><b>Pas de nouveau dépistage</b></td>
		</tr>
		<tr>
		    <td><b>Raison</b></td>
		        <td><?php echo $Hemocult->raison_sortie;?></td>
		</tr>
		</table>
		<br>
	  </td>
  </tr> 
  <tr> 
    <td> <?php customSubmitWithAlert("value='Supprimer ce dépistage'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous réellement supprimer cette réponse ?"); ?> </td> 
    <td> <?php customSubmit("value='Modifier ce dépistage'",ACTION_FIND,array(PARAM_EDIT),$param->controler); ?></td> 
  </tr> 
</table> 
</form>
