 <?php 	global $DepistageCancerSeinList; ?>
 <?php global $dossier ?>
 <table border=1> 
  <tr align='center'> 
    <th>Date</td>
    <th>Ant�c�dants</td>
    <th>Type de d�pistage</td>
    <th>Date Mammographie</td>
    <th>Date de rappel</td>
  </tr> 
  
  <?php 
    for($i=0;$i<count($DepistageCancerSeinList);$i++){
//        print_r($tmphisto->ADO);
  		$tmphisto = $DepistageCancerSeinList[$i]; ?>
	
  <tr> 
    <td>
		<?php echo $tmphisto->date; /*$additionalParams = array("Dossier:dossier:id"=>$tmphisto->id,
						"DepistageCancerSein:DepistageCancerSein:date"=>$tmphisto->date);
				buildLink("target='_blank'",$tmphisto->date,"$path/controler/ActionControler.php","DepistageCancerSeinControler",ACTION_FIND,array(PARAM_VIEW,PARAM_SYSTEMATIQUE),$additionalParams);*/
		?>
		&nbsp;
	</td> 
    <td align='center'> <?php echo($tmphisto->ant_fam_mere=="1"?"m�re, ":"");
						  	  echo($tmphisto->ant_fam_soeur=="1"?"s&oelig;ur, ":"");
						  	  echo($tmphisto->ant_fam_fille=="1"?"fille, ":"");
							  echo($tmphisto->ant_fam_tante=="1"?"tante, ":"");
							  echo($tmphisto->ant_fam_grandmere=="1"?"grand-m�re":"");?>
	</td>
    <td><?php echo($tmphisto->dep_type=='indiv'?"Individuel":"Collectif");?> </td>
    <td><?php echo $tmphisto->mamograph_date; ?>&nbsp;
	</td> 
    <td><?php echo $tmphisto->rappel_mammographie; ?>&nbsp;	</td>
  </tr> 
  <?php } ?>
</table>



			
			
