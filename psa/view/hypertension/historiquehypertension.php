 <?php 	global $HyperTensionArterielleList; ?>
 <?php global $dossier ?>
 <?php
 ?>
 <table border=1>
  <tr align='center'> 
    <th>Date</td>
    <th>Réponses</td>
  </tr>
  </tr>
  <?php 

    for($j=0;$j<count($HyperTensionArterielleList);$j++){
        
  		$tmphisto = $HyperTensionArterielleList[$j]; ?>
	
	  <tr>
	    <td>
	        <?php echo $tmphisto->date;?>
		</td>
		<td><a href='#hta_<?php echo $tmphisto->date; ?>' onclick="affiche_detail('hta<?php echo $tmphisto->date; ?>')">Afficher/masquer les détails</a>
	  <tr style="display:none" id="hta<?php echo $tmphisto->date; ?>">
    <td>
		<?php echo $tmphisto->date; /*$additionalParams = array("Dossier:dossier:id"=>$tmphisto->id,
						"DepistageCancerSein:DepistageCancerSein:date"=>$tmphisto->date);
				buildLink("target='_blank'",$tmphisto->date,"$path/controler/ActionControler.php","DepistageCancerSeinControler",ACTION_FIND,array(PARAM_VIEW,PARAM_SYSTEMATIQUE),$additionalParams);*/
		?>
		&nbsp;
	</td> 
	<td>
  <b>Indicateurs cliniques</b>
  <table border=1>
    <tr>
        <td colspan="2">Indicateur</td>
            <td>Valeur</td>
                <td>Date (jj/mm/aaaa)</td>
    </tr>
    <tr >
      <td colspan="2">Poids </td>
      <td><?php echo $tmphisto->poids; ?>kg<br>
      <table border='0'>
      <tr>
	      <td>IMC:&nbsp;<?php echo($tmphisto->getIMC($dossier->taille)==0?"":$tmphisto->getIMC($dossier->taille)); ?></td>
      </tr>
      </table>
      <td><?php echo $tmphisto->dpoids;?></td>
    </tr>
    <tr>
      <td  colspan="2">Chiffres tensionnels<br><br>
	  				   Objectif tensionnel atteint</td>
      <td colspan="2" nowrap>
        <?php

		echo $tmphisto->TaSys."/".$tmphisto->TaDia; ?>&nbsp;&nbsp;<?php echo $tmphisto->dtension; ?><br>
        <?php echo $tmphisto->TA_mode; ?><br>
        <?php echo $tmphisto->obj_tension; ?>
	  </td>
	</tr>
	<tr>
	    <td rowspan="4">Examen cardiovasculaire</td>
	        <td>Auscultation du coeur</td>

	                <td colspan="2" align="center"><?php echo $tmphisto->dcoeur;?></td>
	</tr>
	<tr>
	        <td>Auscultation des artères</td>

                <td colspan="2" align="center"><?php echo $tmphisto->dartere;?></td>
	</tr>
	<tr>
	        <td>Palpation des pouls périphériques</td>
	                <td colspan="2" align="center"><?php echo $tmphisto->dpouls;?></td>
	</tr>
	<tr>
	        <td>Recherche d'un souffle abdominal</td>
	                <td colspan="2" align="center"><?php echo $tmphisto->dsouffle;?></td>
    </tr>

  </table>
  <br>
  <br>
  <b>Indicateurs biologiques</b><br>
  <table border=1>
  <tr>
    <td>&nbsp;</td>
   		<td>Valeur</td>
		    <td>Date (jj/mm/aaaa)</td>
  </tr>
  <tr>
    <td>Créatinine</td>
    <td><?php

	echo ($tmphisto->Creat==0?"":$tmphisto->Creat); ?> mg<br>
	<table border="0">
	<tr>
	    <td>Clearance calculée : </td>
	    <td>&nbsp;<?php echo($tmphisto->getClearance($dossier)); ?>ml/mn</td>
	</tr>
 </table>
    <td><?php echo $tmphisto->dcreat; ?></td>
  </tr>
  <tr>
    <td>Glycémie à jeun</td>
    <td><?php
		echo ($tmphisto->glycemie==0?"":$tmphisto->glycemie); ?>g/l</td>
    <td><?php echo $tmphisto->dglycemie; ?></td>
  </tr>
  <tr>
    <td>Kaliémie</td>
    <td><?php

		echo ($tmphisto->kaliemie==0?"":$tmphisto->kaliemie); ?>mmol/l</td>
    <td><?php echo $tmphisto->dkaliemie; ?></td>
  </tr>
  <tr valign='top'>
    <td>Cholestérol HDL</td>
    <td><?php

		echo ($tmphisto->HDL==0?"":$tmphisto->HDL); ?> g/l</td>
    <td><?php echo $tmphisto->dChol; ?></td>
  </tr>
  <tr>
    <td valign='top'>LDL</td>
    <td><?php

		echo ($tmphisto->LDL==0?"":$tmphisto->LDL); ?> g/l</td>
    <td><?php echo $tmphisto->dLDL; ?>
	</td>
  </tr>
  <tr>
    <td>Protéinurie</td>
    <td><?php

		 if($tmphisto->dproteinurie!='')
		 {
		 	echo $tmphisto->proteinurie=="1"?"Positive":"Négative";
		 }?>
		</td>
    <td><?php echo $tmphisto->dproteinurie; ?></td>
  </tr>
  <tr>
    <td>Hématurie</td>
    <td><?php

		 if($tmphisto->dhematurie!='')
		 {
		 	echo $tmphisto->hematurie=="1"?"Positive":"Négative";
		 }?>

		</td>
    <td><?php echo $tmphisto->dhematurie; ?></td>
  </tr>
  </table>
  <br>
  <br>
  <b>Indicateurs para-cliniques</b><br>
  <table border=1>
  <tr>
    <td>&nbsp;</td>
        <td>Date</td>
  </tr>
  <tr>
    <td>Fond d'&oelig;il</td>
    <td><?php echo $tmphisto->dfond; ?></td>
  </tr>
  <td>ECG</td>
    <td><?php echo $tmphisto->dECG; ?></td>
  </tr>
  </table>
  <br>
  <br>
  <b>Indicateurs de gravité</b><br>
  <table border='1'>
  <tr>
    <td><?php if($tmphisto->hta_instable=="1") {?>
	      HTA non stabilisée ou instable,
		<?php
		  		}

		  		if($tmphisto->hta_tritherapie=="1"){ ?>
	      HTA sous trithérapie ou plus,
		<?php
				}

				if($tmphisto->hta_complique=="1"){ ?>
	      HTA compliquée,
		<?php
				}
				if ($tmphisto->tabac=="1"){ ?>
	      Tabac,
		<?php
				}
				if($tmphisto->hyperlipidemie=="1"){ ?>
	      Hyperlipidémie,
		<?php
				}
				if($tmphisto->alcool=="1"){ ?>
	      Alcool
		<?php
				}
				?>
		</td>
  </tr>
  </table>
  <br>
  <br>
  <b>S'il y a eu consultation infirmière</b><br>
  <table border='1'>
 <tr>
    <td>Date de la consultation</td>
        <td><?php echo $tmphisto->dconsult; ?></td>
  </tr>
  <tr>
      <td>Degré de satisfaction:</td>
      <td><?php if($tmphisto->degre_satisfaction!="selected")
	  				echo $satisfaction[$tmphisto->degre_satisfaction]; ?></td>
  </tr>
  <tr>
    <td valign="top" rowspan="4" width='50%'>Indicateurs d'observance du traitement<br>
								Liste provisoire, à affiner. La mention "fait" indique que le sujet a été abordé en consultation</td>
    <td>Evaluation de la qualité de vie par rapport au traitement<br>
		<?php if($tmphisto->qualite_vie=="oui")
				 echo "Fait";
			  if ($tmphisto->qualite_vie=="non")
			  	 echo "Pas fait";
		?>
	</td>
   </tr>
   <tr>
    <td>Recherche de la iatrogénie et des effets secondaires<br>
		<?php
			if($tmphisto->iatrogenie=="oui")
				echo "Fait";
			if ($tmphisto->iatrogenie=="non")
				echo "Pas fait";
		?>
	</td>
   </tr>
   <tr>
    <td>Délivrance des traitements<br>
		<?php
			if ($tmphisto->deliv_trait=="oui")
				echo "Fait";
			if ($tmphisto->deliv_trait=="non")
				echo "Pas fait";
		?>
	</td>
   </tr>
   <tr>
    <td>Régularité des prises<br>
		<?php
			if ($tmphisto->regul_prises=="oui")
				echo "Fait";
			if	($tmphisto->regul_prises=="non")
				echo "Pas fait";
		?>
	</td>
   </tr>
   <tr>
    <td>Compte-rendu de la consultation (soulignez principalement les manques)</td>
      <td><?php echo stripslashes(nl2br($tmphisto->cpt_rendu)); ?></td>
    </td>
   </tr>
  </table>
      </td>
  </tr>
  <?php } ?>
</table>



			
			
