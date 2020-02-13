<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>

<?php global $account ?>
<?php global $dossier; ?>
<?php global $HyperTensionArterielle; ?>
<?php global $param; ?>


<form action='<?php echo("$path/controler/ActionControler.php");?>' method="post" name="manage">
<?php hiddenControler("HyperTensionArterielleControler"); ?>
<?php hiddenAction(ACTION_FIND); ?>
<?php hiddenParam1(PARAM_EDIT); ?>
<?php hidden("","dossier:numero"); ?>
<?php hidden("","HyperTensionArterielle:date"); ?>

<?php require("view/common/dossierresume.php");?>


  <b>Indicateurs cliniques</b>
  <table border=1>
    <tr>
        <td colspan="2">Indicateur</td>
            <td>Valeur</td>
                <td>Date (jj/mm/aaaa)</td>
    </tr>
    <tr >
      <td colspan="2">Poids </td>
      <td><?php echo $HyperTensionArterielle->poids; ?>kg<br>
      <table border='0'>
      <tr>
	      <td>IMC:&nbsp;<?php echo($HyperTensionArterielle->getIMC($dossier->taille)==0?"":$HyperTensionArterielle->getIMC($dossier->taille)); ?></td>
      </tr>
      </table>
      <td><?php echo $HyperTensionArterielle->dpoids;?></td>
    </tr>
    <tr>
      <td  colspan="2">Chiffres tensionnels<br><br>
	  				   Objectif tensionnel atteint</td>
      <td colspan="2" nowrap>
        <?php

		echo $HyperTensionArterielle->TaSys."/".$HyperTensionArterielle->TaDia; ?>&nbsp;&nbsp;<?php
		echo $HyperTensionArterielle->dtension;?><br>
        <?php echo $HyperTensionArterielle->TA_mode; ?><br>
        <?php echo $HyperTensionArterielle->obj_tension; ?>
	  </td>
	</tr>
	<tr>
	    <td rowspan="4">Examen cardiovasculaire</td>
	        <td>Auscultation du coeur</td>

	                <td colspan="2" align="center"><?php echo $HyperTensionArterielle->dcoeur;?></td>
	</tr>
	<tr>
	        <td>Auscultation des artères</td>

                <td colspan="2" align="center"><?php echo $HyperTensionArterielle->dartere;?></td>
	</tr>
	<tr>
	        <td>Palpation des pouls périphériques</td>
	                <td colspan="2" align="center"><?php echo $HyperTensionArterielle->dpouls;?></td>
	</tr>
	<tr>
	        <td>Recherche d'un souffle abdominal</td>
	                <td colspan="2" align="center"><?php echo $HyperTensionArterielle->dsouffle;?></td>
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

	echo ($HyperTensionArterielle->Creat==0?"":$HyperTensionArterielle->Creat); ?> mg<br>
	<table border="0">
	<tr>
	    <td>Clearance calculée : </td>
	    <td>&nbsp;<?php echo($HyperTensionArterielle->getClearance($dossier)); ?>ml/mn</td>
	</tr>
 </table>
    <td><?php echo $HyperTensionArterielle->dcreat; ?></td>
  </tr>
  <tr>
    <td>Glycémie à jeun</td>
    <td><?php
		echo ($HyperTensionArterielle->glycemie==0?"":$HyperTensionArterielle->glycemie); ?>g/l</td>
    <td><?php echo $HyperTensionArterielle->dglycemie; ?></td>
  </tr>
  <tr>
    <td>Kaliémie</td>
    <td><?php

		echo ($HyperTensionArterielle->kaliemie==0?"":$HyperTensionArterielle->kaliemie); ?>mmol/l</td>
    <td><?php echo $HyperTensionArterielle->dkaliemie; ?></td>
  </tr>
  <tr valign='top'>
    <td>Cholestérol HDL</td>
    <td><?php

		echo ($HyperTensionArterielle->HDL==0?"":$HyperTensionArterielle->HDL); ?> g/l</td>
    <td><?php echo $HyperTensionArterielle->dChol; ?></td>
  </tr>
  <tr>
    <td valign='top'>LDL</td>
    <td><?php

		echo ($HyperTensionArterielle->LDL==0?"":$HyperTensionArterielle->LDL); ?> g/l</td>
    <td><?php echo $HyperTensionArterielle->dLDL; ?>
	</td>
  </tr>
  <tr>
    <td>Protéinurie</td>
    <td><?php

		 if($HyperTensionArterielle->dproteinurie!='')
		 {
		 	echo $HyperTensionArterielle->proteinurie=="1"?"Positive":"Négative";
		 }?>
		</td>
    <td><?php echo $HyperTensionArterielle->dproteinurie; ?></td>
  </tr>
  <tr>
    <td>Hématurie</td>
    <td><?php

		 if($HyperTensionArterielle->dhematurie!='')
		 {
		 	echo $HyperTensionArterielle->hematurie=="1"?"Positive":"Négative";
		 }?>

		</td>
    <td><?php echo $HyperTensionArterielle->dhematurie; ?></td>
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
    <td><?php echo $HyperTensionArterielle->dfond; ?></td>
  </tr>
  <td>ECG</td>
    <td><?php echo $HyperTensionArterielle->dECG; ?></td>
  </tr>
  </table>
  <br>
  <br>
  <b>Indicateurs de gravité</b><br>
  <table border='1'>
  <tr>
    <td><?php if($HyperTensionArterielle->hta_instable=="1") {?>
	      HTA non stabilisée ou instable,
		<?php
		  		}

		  		if($HyperTensionArterielle->hta_tritherapie=="1"){ ?>
	      HTA sous trithérapie ou plus,
		<?php
				}

				if($HyperTensionArterielle->hta_complique=="1"){ ?>
	      HTA compliquée,
		<?php
				}
				if ($HyperTensionArterielle->tabac=="1"){ ?>
	      Tabac,
		<?php
				}
				if($HyperTensionArterielle->hyperlipidemie=="1"){ ?>
	      Hyperlipidémie,
		<?php
				}
				if($HyperTensionArterielle->alcool=="1"){ ?>
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
        <td><?php echo $HyperTensionArterielle->dconsult; ?></td>
  </tr>
  <tr>
      <td>Degré de satisfaction:</td>
      <td><?php if($HyperTensionArterielle->degre_satisfaction!="selected")
	  				echo $satisfaction[$HyperTensionArterielle->degre_satisfaction]; ?></td>
  </tr>
  <tr>
    <td valign="top" rowspan="4" width='50%'>Indicateurs d'observance du traitement<br>
								Liste provisoire, à affiner. La mention "fait" indique que le sujet a été abordé en consultation</td>
    <td>Evaluation de la qualité de vie par rapport au traitement<br>
		<?php if($HyperTensionArterielle->qualite_vie=="oui")
				 echo "Fait";
			  if ($HyperTensionArterielle->qualite_vie=="non")
			  	 echo "Pas fait";
		?>
	</td>
   </tr>
   <tr>
    <td>Recherche de la iatrogénie et des effets secondaires<br>
		<?php
			if($HyperTensionArterielle->iatrogenie=="oui")
				echo "Fait";
			if ($HyperTensionArterielle->iatrogenie=="non")
				echo "Pas fait";
		?>
	</td>
   </tr>
   <tr>
    <td>Délivrance des traitements<br>
		<?php
			if ($HyperTensionArterielle->deliv_trait=="oui")
				echo "Fait";
			if ($HyperTensionArterielle->deliv_trait=="non")
				echo "Pas fait";
		?>
	</td>
   </tr>
   <tr>
    <td>Régularité des prises<br>
		<?php
			if ($HyperTensionArterielle->regul_prises=="oui")
				echo "Fait";
			if	($HyperTensionArterielle->regul_prises=="non")
				echo "Pas fait";
		?>
	</td>
   </tr>
   <tr>
    <td>Compte-rendu de la consultation (soulignez principalement les manques)</td>
      <td><?php echo stripslashes(nl2br($HyperTensionArterielle->cpt_rendu)); ?></td>
    </td>
   </tr>
  </table>
  <br><br>

<table border="0">
  <tr>
    <td>
		<?php customSubmitWithAlert("value='Supprimer ce suivi'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous réellement supprimer cette réponse ?"); ?>
	 </td>
    <td> <?php customSubmit("value='Modifier ce suivi'",ACTION_FIND,array(PARAM_EDIT),$param->controler); ?>
</td>
  </tr>
</table>

</form>

