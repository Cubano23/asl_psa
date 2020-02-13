 <?php 	global $suiviDiabeteList; ?>
 <?php global $dossier ?>
 <table border=1> 
  <tr align='center'> 
    <th rowspan=2>Date</td> 
    <th rowspan=2>Poids</td> 
    <th rowspan=2>IMC</td> 
    <th rowspan=2>Traitements</td> 
    <th rowspan=2>Objectifs<br> 
      atteints</td> 
    <th colspan=2>Bilan</td>
  <tr> 
    <th>4 mois</th> 
<?php //    <th>semestriel</th>
?>    <th>annuel</th>
  </tr> 
  
  <?php 
    for($i=0;$i<count($suiviDiabeteList);$i++){ 
//        print_r($tmphisto->ADO);
  		$tmphisto = $suiviDiabeteList[$i]; ?>
  <tr> 
    <td>
		<?php $additionalParams = array("Dossier:dossier:id"=>$tmphisto->dossier_id,
						"SuiviDiabete:suiviDiabete:dsuivi"=>$tmphisto->dsuivi);
				buildLink("target='_blank'",$tmphisto->dsuivi,"$path/controler/ActionControler.php","SuiviDiabeteControler",ACTION_FIND,array(PARAM_VIEW,PARAM_SYSTEMATIQUE),$additionalParams);
		?>
		&nbsp;
	</td> 
    <td align='center'><?php echo($tmphisto->poids); ?>&nbsp;</td> 
    <td><?php echo($tmphisto->getIMC($dossier->taille)); ?>&nbsp;</td> 
    <td>
		<?php if($tmphisto->Regime != false) { ?> Régime seul <br> <?php }?>
		<?php if($tmphisto->InsulReq != false){ ?> Insulino réquerant <br><?php }?>
		<?php /*if(count($tmphisto->ADO) >0){?> ADO <?php }*/?><!--&nbsp;-->
		<?php if(($tmphisto->ADO[0]!='aucun')&&($tmphisto->ADO[0]!='')){?> ADO <?php }?>&nbsp;
	</td> 
    <td>
		<?php if($tmphisto->equilib){ ?> Diabete équilibre <br> <?php }?>
		<?php if($tmphisto->tension){ ?> Objectif tensionnel (135/80) <?php }?> &nbsp;
	</td> 
    <td align='center'><?php if(in_array("4",$tmphisto->suivi_type)){?><font color='blue'><b>X</b></font><?php }?>&nbsp;</td> 
    <td align='center'><?php if(in_array("s",$tmphisto->suivi_type) || /*){?><font color='green'><b>X</b></font><?php }*>?><!--&nbsp;</td>
    <td align='center'>--><?php /*if(*/
								in_array("a",$tmphisto->suivi_type)){?><font color='brown'><b>X</b></font><?php }?>&nbsp;</td>
  </tr> 
  <?php } ?>
</table>



			
			
