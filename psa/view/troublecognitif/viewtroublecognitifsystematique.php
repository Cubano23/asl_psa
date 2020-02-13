
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
?>  </table>
<br><br>

