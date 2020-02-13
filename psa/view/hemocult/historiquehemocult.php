 <?php 	global $HemocultList; ?>
 <?php global $dossier ?>
 <table border=1> 
  <tr align='center'>
    <th>Date</td>
    <th>Date convocation</td>
    <th>Date remise plaquettes</td>
    <th>Date Résultat</td>
    <th>Résultat</td>
    <th>Rappel</td>
  </tr> 
  
  <?php 
    for($i=0;$i<count($HemocultList);$i++){
//        print_r($tmphisto->ADO);
  		$tmphisto = $HemocultList[$i]; ?>
	
	<tr> 
	    <td>
			<?php echo $tmphisto->date; /*$additionalParams = array("Dossier:dossier:id"=>$tmphisto->id,
							"DepistageCancerSein:DepistageCancerSein:date"=>$tmphisto->date);
					buildLink("target='_blank'",$tmphisto->date,"$path/controler/ActionControler.php","DepistageCancerSeinControler",ACTION_FIND,array(PARAM_VIEW,PARAM_SYSTEMATIQUE),$additionalParams);*/
			?>
			&nbsp;
		</td> 
	    <td align='center'> <?php echo $tmphisto->date_convoc;?>
		</td>
	    <td><?php echo $tmphisto->date_plaquette;?> </td>
	    <td><?php echo $tmphisto->date_resultat; ?>&nbsp;
		</td> 
	    <td><?php if($tmphisto->resultat=="1") echo "Positif";
				  elseif($tmphisto->resultat=="0") echo "Négatif";
				  else echo ""; ?>&nbsp;	</td>
		  <td><?php if($tmphisto->rappel=='0') echo "Pas de rappel";
					else echo $tmphisto->date_rappel;
					?>&nbsp;</td>
	</tr>
  <?php } ?>
</table>



			
			
