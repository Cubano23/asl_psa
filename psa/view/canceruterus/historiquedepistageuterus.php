 <?php 	global $DepistageCancerUterusList; ?>
 <?php global $dossier ?>
 <table border=1> 
  <tr align='center'> 
    <th>Date</td>
    <th>Date frottis</td>
    <th>Frottis</td>
    <th>Avis médecin</td>
    <th>Date de rappel</td>
  </tr> 
  
  <?php 
    for($i=0;$i<count($DepistageCancerUterusList);$i++){
//        print_r($tmphisto->ADO);
  		$tmphisto = $DepistageCancerUterusList[$i]; ?>
	
  <tr> 
    <td>
		<?php echo $tmphisto->date; /*$additionalParams = array("Dossier:dossier:id"=>$tmphisto->id,
						"DepistageCancerSein:DepistageCancerSein:date"=>$tmphisto->date);
				buildLink("target='_blank'",$tmphisto->date,"$path/controler/ActionControler.php","DepistageCancerSeinControler",ACTION_FIND,array(PARAM_VIEW,PARAM_SYSTEMATIQUE),$additionalParams);*/
		?>
		&nbsp;
	</td> 
    <td align='center'> <?php echo $tmphisto->date_frottis;?>
	</td>
    <td><?php echo($tmphisto->frottis_normal=='oui'?"Normal":"Anormal");?> </td>
    <td><?php echo $tmphisto->avis_medecin; ?>&nbsp;
	</td> 
    <td><?php echo $tmphisto->date_rappel; ?>&nbsp;	</td>
  </tr> 
  <?php } ?>
</table>



			
			
