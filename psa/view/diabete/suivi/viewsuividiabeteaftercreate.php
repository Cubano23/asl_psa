<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php global $account ?>
<?php global $dossier; ?>
<?php global $suiviDiabete; ?>
<?php global $param; ?>
<?php global $liste_historique; ?>
 
<form action='<?php echo("$path/controler/ActionControler.php");?>' method="post" name="manage">
<?php hiddenControler(""); ?>
<?php hiddenAction(""); ?>
<?php hiddenParam1(""); ?>
<?php hidden("","dossier:numero"); ?>
<?php hidden("","suiviDiabete:dsuivi"); ?>

    <!-- Information concernant les d�pistages saisies � partir de ce suivi -->
    <input type="hidden" name="DepistageAOMI:DepistageAOMI:provenance" value="SuiviDiabete">
    <input type="hidden" name="DepistageAOMI:DepistageAOMI:dateSaisie" value="<?= $suiviDiabete->dsuivi; ?>">

<?php require("view/common/dossierresume.php");?>

    <table border='1' width='100%' align='center'>
        <tr> 
          <td valign='top'>poids</td> 
          <td>&nbsp;<?php typePropertyValue("suiviDiabete:poids"); ?></td> 
        </tr>
		<tr>
			<td>Date du poids</td>
			<td><?php if($suiviDiabete->dPoids) echo($suiviDiabete->dPoids) ?>&nbsp;</td>
		</tr>
        <tr> 
          <td valign='top'>Indice de masse pond�rale</td> 
          <td><?php echo($suiviDiabete->getIMC($dossier->taille)); ?></td> 
        </tr> 
        <tr> 
          <td colspan=2><b>Traitements</b></td> 
        </tr> 
        <tr> 
          <td>
		  	<?php if($suiviDiabete->Regime != false) echo("R�gime seul"); ?><br>
			<?php  if($suiviDiabete->InsulReq != false) echo("Insulino requerant"); ?><br>&nbsp;
		  </td> 
          <td>
		  	Anti diab�tiques oraux:<br>
			<?php 
				for($i=0;$i<count($suiviDiabete->ADO);$i++){
					echo($suiviDiabete->ADO[$i]);
					echo("<br>");
				}
			?>	&nbsp;	  </td> 
        </tr> 
        <tr> 
          <td valign='top'>Tension arterielle prise</td> 
          <td><?php typePropertyValue("suiviDiabete:TaSys"); ?>/<?php typePropertyValue("suiviDiabete:TaDia"); ?>&nbsp;</td> 
        </tr> 
        <tr> 
          <td valign='top'>&nbsp;</td> 
          <td>&nbsp;<?php typePropertyValue("suiviDiabete:TA_mode"); ?></td> 
        </tr> 
		<tr>
			<td>Date de la tension</td>
			<td><?php if($suiviDiabete->dtension) echo($suiviDiabete->dtension) ?>&nbsp;</td>
		</tr>
		<tr> 
          <td valign='top'>Tabac</td> 
          <td><?php typePropertyValue("suiviDiabete:nbrtabac"); ?>&nbsp;Paquets - ann�es</td> 
        </tr> 
        <tr> 
          <th scope="row" colspan="2">Co-pathologies</th> 
        </tr> 
		<tr>
			<td colspan="2"> 
				<?php if($suiviDiabete->hta) { echo("Hypertension art�rielle"); ?> <br> <?php }?>
				<?php if($suiviDiabete->arte){ echo("Art�rite"); ?> <br> <?php }?>
				<?php if($suiviDiabete->neph){ echo("Nephropathie"); ?> <br> <?php }?>
				<?php if($suiviDiabete->coro){ echo("Insuffisance Coronarienne"); ?> <br> <?php }?>
				<?php if($suiviDiabete->reti){ echo("R�tinopathie diab�tique"); ?> <br> <?php }?>
				<?php if($suiviDiabete->neur){ echo("Neuropathie p�riph�rique"); ?> <br> <?php }?>&nbsp;
			</td>
		</tr>
		<tr>
			<td>Diab�te type <?php echo $suiviDiabete->type;?></td>
		</Tr>
</table>
		  		<?php if(in_array("4",$suiviDiabete->suivi_type)){?><font color='blue'><b>Suivi tous les 4 mois</b></font>&nbsp;<br>
				<table border='1' cellpadding='3'>
				        <tr>
				          <th>Test</th>
				          <th>R�sultat(s)</th>
				        </tr>
				        <tr>
				          <td>R�sultat analyse HBA1c</td>
				          <td><?php if($suiviDiabete->ResHBA) { echo($suiviDiabete->ResHBA) ?> %<?php } ?>&nbsp;</td>
				        </tr>
				        <tr>
				          <td>Date analyse HBA1C</td>
				          <td><?php if($suiviDiabete->dHBA) echo($suiviDiabete->dHBA) ?>&nbsp</td>
				        </tr>
				      </table>
 <?php }?>
				<?php if(in_array("s",$suiviDiabete->suivi_type) ||
				         in_array("a",$suiviDiabete->suivi_type)){?><font color='brown'><b>Suivi annuel</b></font>&nbsp;<br>
				    <table border='1' cellpadding='3'>
				        <tr>
				          <th>Test</th>
				          <th>R�sultat(s)</th>
				        </tr>
				<!--        <tr>
				          <td>Examen des pieds au filament</td>
				          <td><?php# echo($suiviDiabete->ExaFil); ?>&nbsp;</td>
				        </tr>-->
				        <tr>
				          <td>Date examen au filament</td>
				          <td><?php echo($suiviDiabete->dExaFil); ?>&nbsp;</td>
				        </tr>
<!--				        <tr>
				          <td>Examen des pieds</td>
				          <td><?php echo($suiviDiabete->ExaPieds); ?>&nbsp;</td>
				        </tr>-->
				        <tr>
				          <td>Date examen des pieds</td>
				          <td><?php echo($suiviDiabete->dExaPieds); ?>&nbsp;</td>
				        </tr>

				        <tr>
				          <td>Mesure Cholest�rol HDL</td>
				          <td><?php echo($suiviDiabete->HDL); ?>&nbsp;</td>
				        </tr>
				        <tr>
				          <td>HDL pathologique</td>
				          <td><?php echo($suiviDiabete->iChol); ?>&nbsp;</td>
				        </tr>
				        <tr>
				          <td>Date Cholest�rol HDL</td>
				          <td><?php echo($suiviDiabete->dChol); ?>&nbsp;</td>
				        </tr>
				        <tr>
				          <td>Mesure LDL</td>
				          <td><?php echo($suiviDiabete->LDL); ?>&nbsp;</td>
				        </tr>
				        <tr>
				          <td>LDL pathologique</td>
				          <td><?php echo($suiviDiabete->iLDL); ?>&nbsp;</td>
				        </tr>
				        <tr>
				          <td>Date Cholest�rol LDL</td>
				          <td><?php echo($suiviDiabete->dLDL); ?>&nbsp;</td>
				        </tr>
				        <tr>
				          <td>Mesure Cr�atinin�mie</td>
				          <td><?php echo($suiviDiabete->Creat); ?>&nbsp;</td>
				        </tr>
				        <tr>
				          <td>Clearance calcul�e</td>
				          <td>&nbsp;<?php echo($suiviDiabete->getClearance($dossier)); ?></td>
				        </tr>
				        <tr>
				          <td>Cr�atinine pathologique</td>
				          <td><?php echo($suiviDiabete->iCreat); ?>&nbsp;</td>
				        </tr>
				        <tr>
				          <td>Date cr�atinine</td>
				          <td><?php echo($suiviDiabete->dCreat); ?>&nbsp;</td>
				        </tr>
				        <tr>
				          <td>Micro Albuminurie</td>
				          <td><?php echo($suiviDiabete->iAlbu); ?>&nbsp;</td>
				        </tr>
				        <tr>
				          <td>Date Micro-Albuminurie</td>
				          <td><?php echo($suiviDiabete->dAlbu); ?>&nbsp;</td>
				        </tr>
				        <tr>
				          <td>Fond d'&#339;il</td>
				          <td><?php if($suiviDiabete->iFond) echo("R�alis�"); ?>&nbsp;</td>
				        </tr>
				        <tr>
				          <td>Date Fond d'&#339;il</td>
				          <td><?php echo($suiviDiabete->dFond); ?>&nbsp;</td>
				        </tr>
				        <tr>
				          <td>ECG de repos</td>
				          <td><?php if($suiviDiabete->iECG) echo("R�alis�"); ?>&nbsp;</td>
				        </tr>
				        <tr>
				          <td>Date ECG de repos</td>
				          <td><?php echo($suiviDiabete->dECG); ?>&nbsp;</td>
				        </tr>
				      </table>
<?php }?>

    <table border='1' width='100%' align='center'>
        <tr>
          <th colspan=2>Objectifs</th> 
        </tr> 
		<tr>
			<td colspan="2">
				<?php if($suiviDiabete->equilib){ echo("Diabete �quilibre");?> <br> <?php }?>
				<?php if($suiviDiabete->tension){ echo("Objectif tensionnel (135/80)"); ?> <br> <?php }?>&nbsp;
			</td>
		</tr>
        <tr> 
          <th colspan=2>Mesures � prendre</th>
        </tr> 
        <tr> 
          <td colspan="2">
		  	<?php if($suiviDiabete->mesure_ADO){ echo("Modification traitement antidiab�tique oraux"); ?> <br> <?php }?>
			<?php if($suiviDiabete->insuline){ echo("Modification ou mise � l'insuline"); ?> <br> <?php }?>
			<?php if($suiviDiabete->mesure_hta){ echo("Correction HTA"); ?> <br> <?php }?>
			<?php if($suiviDiabete->hypl){ echo("Prise en charge hyperlipid�mie"); ?> <br> <?php }?>&nbsp;			
		  </td> 
        </tr> 
        <tr> 
          <th  colspan="2">Coaching infirmi�re</th> 
        </tr> 
        <tr> 
          <td colspan="2">
		  		<?php if($suiviDiabete->phys){ echo("Exercice physique"); ?> <br> <?php }?>
				<?php if($suiviDiabete->diet){ echo("Mesures di�t�tiques"); ?> <br> <?php }?>
				<?php if($suiviDiabete->taba){ echo("Arr�t du tabac"); ?> <br> <?php }?>&nbsp;
				<?php if($suiviDiabete->etp){ echo("ETP de groupe"); ?> <br> <?php }?>&nbsp;
		  </td>           
        </tr>        
        <tr> 
          <th colspan='2'>Date de d�but de diab�te </th>
        </Tr>
        <tr>
		  	<td colspan='2' align='center'> <?php echo $suiviDiabete->date_debut;?>&nbsp;</td> 
        </tr> 
        
        <?php 
        	if($suiviDiabete->diab10ans=="1"){
        		?>
        		<tr>
        			<td colspan="2" align="center">Diab�te > 10 ans</td>
        		</tr>
        		<?php         	}
?>
        <tr> 
          <th  colspan="2">Sortir cette personne du suivi diab�te</th> 
        </tr> 
        <tr> 
          <td colspan="2" align='center'>
		  		<?php echo ($suiviDiabete->sortie=='1'?"Oui":"Non");?>&nbsp;
		  </td>           
        </tr>        
      </table>

    <br><br>


    <?php
    if (in_array($account->cabinet, $liste_cabs_aut))
        include_once "view/depistage/historique_depistage_aomi.php";
    ?>

    <br><br>

<table>
	  </td>
  </tr>
  <tr> 
    <td> 
        <?php customSubmitWithAlert("value='Supprimer ce suivi'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous r�ellement supprimer ce suivi ?"); ?>
	</td> 
    <td>
        <?php customSubmit("value='Modifier ce suivi'",ACTION_FIND,array(PARAM_EDIT),$param->controler); ?> 
        <?php customSubmit("value='Faire un autre suivi'",ACTION_MANAGE,array(PARAM_PRE_CREATE),$param->controler); ?>
    </td> 
  </tr> 
</table> 
</form>

