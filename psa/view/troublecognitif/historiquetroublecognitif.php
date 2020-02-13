 <?php 	global $TroubleCognitifList; ?>
 <?php global $dossier ?>
 <table border=1> 
  <tr align='center'> 
    <th>Date</td> 
    <th>MMSE</td>
    <th>IADL</td>
    <th>GDS</td>
    <th>Horloge</th>
  </tr>
  <?php 
    for($i=0;$i<count($TroubleCognitifList);$i++){
//        print_r($tmphisto->ADO);
  		$tmphisto = $TroubleCognitifList[$i]; ?>
  <tr> 
    <td>
		<?php $additionalParams = array("Dossier:dossier:id"=>$tmphisto->id,
						"TroubleCognitif:TroubleCognitif:date"=>$tmphisto->date);
				buildLink("target='_blank'",$tmphisto->date,"$path/controler/ActionControler.php","TroubleCognitifControler",ACTION_FIND,array(PARAM_VIEW),$additionalParams);
		?>
		&nbsp;
	</td> 
    <td align='center'><?php echo $tmphisto->get_mmse(); ?>&nbsp;</td>
    <td><?php echo($tmphisto->get_iadl()); ?>&nbsp;</td>
    <td>
		<?php echo($tmphisto->get_gds());?>&nbsp;
	</td> 
    <td>
		<?php echo($tmphisto->horloge);?> &nbsp;
	</td> 
  </tr> 
  <?php } ?>
</table>



			
			
