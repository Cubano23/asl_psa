<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>

<?php global $account ?>
<?php global $dossier; ?>
<?php global $AutreConsultCardio; ?>
<?php global $param; ?>

 
<form action='<?php echo("$path/controler/ActionControler.php");?>' method="post" name="manage">
<?php hiddenControler("AutreConsultCardioControler"); ?>
<?php hiddenAction(ACTION_FIND); ?>
<?php hiddenParam1(PARAM_EDIT); ?>
<?php hidden("","dossier:numero"); ?>
<?php hidden("","AutreConsultCardio:date"); ?>

<?php require("view/common/dossierresume.php");?>


<br>
	<table border='1'>
	<tr>
		<td width='150'><b>Objectifs</b></td>
			<td><b>Consultations pr�c�dentes</b></td>
			<td><b>Progr�s / difficult�s</b></td>
			<td><b>Nouveaux objectifs</b></td>
	</tr>
	<tr>
		<td width='150'>Poids <img OnClick="javascript:window.open('../premiereconsult/objectif_poids.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt="D�tail de l'objectif" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></Td>
			<td><?php echo nl2br($AutreConsultCardio->progres_poids);?></Td>
			<td><?php echo $AutreConsultCardio->obj_poids;?></Td>
	</tr>
	<tr>
		<td width='150'>Alcool <img OnClick="javascript:window.open('objectif_alcool.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt="D�tail de l'objectif" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31">
		</td>
			<td><?php echo nl2br($AutreConsultCardio->progres_alcool);?></Td>
			<td><?php echo $AutreConsultCardio->obj_alcool;?></Td>
	</Tr>
	<tr>
		<td width='150'>Tabac <img OnClick="javascript:window.open('../premiereconsult/objectif_tabac.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt="D�tail de l'objectif" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31">
		</Td>
			<td><?php echo nl2br($AutreConsultCardio->progres_tabac);?></Td>
			<td><?php echo $AutreConsultCardio->obj_tabac;?></Td>
	</Tr>
	<tr>
		<td width='150'>Tension <img OnClick="javascript:window.open('objectif_tension.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt="D�tail de l'objectif" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31">
		</td>
			<td><?php echo nl2br($AutreConsultCardio->progres_tension);?></Td>
			<td><?php echo $AutreConsultCardio->obj_tension;?></Td>
	</tr>
	</table>
	<br>
	
	<table border='1'>
	<tr>
		<td><b>Conseils prodigu�s</b></td>
			<td><b>Documents remis</b></Td>
				<td><b>Commentaire</b></td>
	</tr>
	<tr>
			<td width='300'>Consommation de sel</td>
			<td><?php if($AutreConsultCardio->brochure_sel1=="1") echo "...<br>";
					  if($AutreConsultCardio->brochure_sel2=="1") echo "... ";?>
				</td>
					<td><?php echo $AutreConsultCardio->commentaire_sel;?></td>
	</Tr>
	<tr>
			<td width='300'>Consommation d'alcool &lt; 2 verres/j pour une femme, 3 verres/j pour un homme</td>
			<td><?php if($AutreConsultCardio->brochure_alcool1=="1") echo "Alcool : votre corps se souvient de tout <img OnClick=\"javascript:window.open('$path/view/cardiovasculaire/docs/alcool2.pdf', '_blank');\" border=0 height='15' src='$path/view/images/imprimer.gif' width='15'><br>";
					  if($AutreConsultCardio->brochure_alcool2=="1") echo "...";?>
				</td>
					<td><?php echo $AutreConsultCardio->commentaire_alcool;?></td>
	</Tr>
	<tr>
			<td width='300'>Activit� physique (�quivalent de 30 min de marche rapide, 3 fois dans la semaine)</td>
			<td><?php if($AutreConsultCardio->brochure_activite1=="1") echo "Brochure \"Bouger c'est la sant�\" <img OnClick=\"javascript:window.open('$path/view/cardiovasculaire/docs/ATT02695.pdf', '_blank');\" border=0 height='15' src='$path/view/images/imprimer.gif' width='15'><br>";
				      if($AutreConsultCardio->brochure_activite2=="1") echo "...";?>
				</td>
					<td><?php echo $AutreConsultCardio->commentaire_activite;?></td>
	</Tr>
	<tr>
			<td width='300'>Sevrage tabagique</td>
				<td><?php if($AutreConsultCardio->brochure_tabac1=="1") echo "La d�pendance au tabac <img OnClick=\"window.open('$path/view/cardiovasculaire/docs/ATT02697.pdf', '_blank');\" border=0 height=\"15\" src='$path/view/images/imprimer.gif' width=\"15\"><br>";
						  if($AutreConsultCardio->brochure_tabac2=="1") echo "Les risques du tabagisme et les b�n�fices de l'arr�t <img OnClick=\"window.open('$path/view/cardiovasculaire/docs/risque_tabac.pdf', '_blank');\" border=0 height=\"15\" src='$path/view/images/imprimer.gif' width=\"15\">";?>
					</td>
					<td><?php echo $AutreConsultCardio->commentaire_tabac;?></td>
	</Tr>
	<tr>
			<td width='300'>Contr�le du poids</td>
				<td><?php if($AutreConsultCardio->brochure_poids1=="1") echo "Pense-b�te nutrition <img OnClick=\"$path/view/cardiovasculaire/docs/ATT02699.pdf', '_blank');\" border=0 height=\"15\" src='$path/view/images/imprimer.gif' width=\"15\"><br>";
						  if($AutreConsultCardio->brochure_poids2=="1") echo "...";?>
					</td>
					<td><?php echo $AutreConsultCardio->commentaire_poids;?></td>
	</Tr>
	<tr>
			<td width='300'>Alimentation riche en fruits et l�gumes</td>
				<td><?php if($AutreConsultCardio->brochure_alim1=="1") echo "La sant� vient en mangeant <img OnClick=\"window.open('$path/view/cardiovasculaire/docs/ATT02701.pdf', '_blank');\" border=0 height=\"15\" src='$path/view/images/imprimer.gif' width=\"15\"><br>";
						  if($AutreConsultCardio->brochure_alim2=="1") echo "...";?>
					</td>
					<td><?php echo $AutreConsultCardio->commentaire_alim;?></td>
	</Tr>
	<tr>
			<td width='300'>Diminution des excitants (caf�, th�, r�glisse)</td>
				<td><?php if ($AutreConsultCardio->brochure_cafe1=="1") echo "...<br>";
						  if ($AutreConsultCardio->brochure_cafe2=="1") echo "...";?>
					</td>
					<td><?php echo $AutreConsultCardio->commentaire_cafe;?></td>
	</Tr>
	</table>
	<br>
	<b>Indicateurs d'observance des traitements m�dicamenteux</b>
	<table border='1'>
	  <tr>
	  	<td>Probl�mes rencontr�s</td>
	  		<td>Commentaire</td>
	  </tr>
	  <tr>
	    <td>Qualit� de vie par rapport au traitement</td><td>
			<?php echo $AutreConsultCardio->detail_qualite_vie;?>
		</td>
	   </tr>
	   <tr>
	    <td>Effets secondaires</td><td>
			<?php echo $AutreConsultCardio->detail_secondaire;?>
		</td>
	   </tr>
	   <tr>
	    <td>D�livrance des traitements</td><td>
			<?php echo $AutreConsultCardio->detail_delivrance;?>
		</td>
	   </tr>
	   <tr>
	    <td>R�gularit� des prises</td><td>
			<?php echo $AutreConsultCardio->detail_regularite;?>
		</td>
	   </tr>
	</table>
  <br>
	<b>Bilan de consultation</b>
   <table width='850' border="1" cellpadding='3'>
    <tr>
      <td>Degr� de satisfaction:</td>
      <td colspan='2'><?php echo($satisfaction[getPropertyValue("AutreConsultCardio:degre_satisfaction")]) ?></td>
    </tr>
    <tr>
      <td>Dur�e approximative en minutes ("� 5 minutes pr�s")</td>
      <td colspan='2'><?php echo $AutreConsultCardio->duree; ?></td>
    </tr>
    <tr>
      <td>Type de consultation:</td>
      <td><?php 
		  			foreach($AutreConsultCardio->type_consultation as $consult){
		  				echo $type_consult[$consult].', ';
		  			}
			 ?></td>
		<td>Examens r�alis�s par d�l�gation : <br><br>
				<?php 
					if($AutreConsultCardio->hba==1){ echo "Prescription d�examen (s) pour le patient diab�tique type 2, ";}//Prescription HbA1c
					if($AutreConsultCardio->exapied==1){ echo "Prescription, r�alisation, interpr�tation examen des pieds, ";}
					if($AutreConsultCardio->monofil==1){ echo "Prescription, r�alisation, interpr�tation examen des pieds et monofilament, ";}
					if($AutreConsultCardio->ecg==1){ echo "Prescription et r�alisation d�ECG, ";}
					if($AutreConsultCardio->ecg_seul==1){ echo "R�alisation d�ECG seul - non d�rogatoire, ";}//ECG
					  // if($AutreConsultCardio->tension==1){ echo "Tension, ";}
					if($AutreConsultCardio->spirometre==1){ echo "Prescription, r�alisation d�une spirom�trie, ";}
					if($AutreConsultCardio->spirometre_seul==1){ echo "R�alisation d�une spirom�trie seule - non d�rogatoire, ";}
					if($AutreConsultCardio->t_cognitif==1){ echo "Prescription et r�alisation d�un rep�rage troubles cognitifs, ";}
					if($AutreConsultCardio->autre==1){ echo "Autre : ".$AutreConsultCardio->prec_autre;}?>
		</td>
    </tr>
     <tr>
      <td valign='top'>Points positifs:</td>
      <td  width='70%' colspan='2'><?php echo nl2br(stripslashes($AutreConsultCardio->points_positifs)); ?></td>
    </tr>
    <tr>
      <td valign='top'>Points � am�liorer:</td>
      <td  width='70%' colspan='2'><?php echo nl2br(stripslashes($AutreConsultCardio->points_ameliorations)); ?></td>
    </tr>
  </table>
    
  <br><br>


<table border="0">
  <tr>
    <td> 
		<?php customSubmitWithAlert("value='Supprimer cette consultation'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous r�ellement supprimer cette consultation ?"); ?>
	 </td> 
    <td> <?php customSubmit("value='Modifier cette consultation'",ACTION_FIND,array(PARAM_EDIT),$param->controler); ?>
         <?php customSubmit("value='Faire une autre consultation'",ACTION_MANAGE,"",$param->controler); ?></td>
  </tr> 
</table> 

</form>
