
  <b>Type de d�pistage:</b><br>
  <table border=1>
<?php if($TroubleCognitif->dep_type=='coll'){?>

    <tr>
    	<td colspan='2'>D�pistage collectif</td>
	<tr>
		<td valign='top'>Date Rappel : </td><td valign='top'> <?php echo ($TroubleCognitif->date_rappel);?></td>
    </tr>
    </tr>
    <?php }

else
{?>
    <tr>
      <td valign='top' colspan="2">D�pistage individuel</td></tr>
      <tr>
	  		<td valign="top">Raison du d�pistage : </td><td> <?php echo $TroubleCognitif->raison_dep ?></td>
		</tr>
	<tr>
	<td valign='top'>Date Rappel : </td><td valign='top'> <?php echo ($TroubleCognitif->date_rappel);?></td>
    </tr>
<?php }
?>  </table>
<br><br>

