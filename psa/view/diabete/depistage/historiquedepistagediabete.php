 <?php 	global $DepistageDiabeteList; ?>
 <?php global $dossier ?>
 <table border=1> 
  <tr align='center'> 
    <th>Date</td>
    <th>Données saisies</td>
  </tr> 
  
  <?php 
    for($i=0;$i<count($DepistageDiabeteList);$i++){
  		$tmphisto = $DepistageDiabeteList[$i];
	?>


	  <tr>
	    <td>
	        <?php echo $tmphisto->date;?>
		</td>
		<td><a href='#depdiabete_<?php echo $tmphisto->date; ?>' onclick="affiche_detail('depdiabete<?php echo $tmphisto->date; ?>')">Afficher/masquer les détails</a>
	  <tr style="display:none" id="depdiabete<?php echo $tmphisto->date; ?>">
    <td>
		<?php echo $tmphisto->date; /*$additionalParams = array("Dossier:dossier:id"=>$tmphisto->id,
						"DepistageCancerSein:DepistageCancerSein:date"=>$tmphisto->date);
				buildLink("target='_blank'",$tmphisto->date,"$path/controler/ActionControler.php","DepistageCancerSeinControler",ACTION_FIND,array(PARAM_VIEW,PARAM_SYSTEMATIQUE),$additionalParams);*/
		?>
		&nbsp;
	</td> 
	<td>
<table width="500" >
          <tr>
            <th width="450">Question</th>
            <th>Réponse(s)</th>
          </tr>

          <tr>
            <td valign='top'>Poids</td>
            <td><?php echo $tmphisto->poids; ?></td>
          </tr>
          <tr>
            <td valign='top'>Indice de masse corporelle (imc)</td>
            <td><?php echo ($tmphisto->getIMC($dossier->taille));?></td>
          </tr>
          <tr>
            <th scope="row" colspan="2">Facteurs de risque:</th>
          </tr>

		  <?php if($tmphisto->getIMC($dossier->taille)>28){?>
		  <tr>
            <td>&nbsp;L'indice de masse corporelle est >28Kg/m<sup>2</sup></td>
            <td>&nbsp;</td>
          </tr>
		  <?php } ?>

		  <?php if($tmphisto->parent_diabetique_type2 ==1){ ?>
          <tr>
            <td>&nbsp;parent (au premier degré) diabétique de type 2</td>
            <td>&nbsp;</td>
          </tr>
		  <?php }?>

		  <?php if($tmphisto->ant_intolerance_glucose == 1){ ?>
          <tr>
            <td>&nbsp;Antécédents personnels d'intolérance au glucose</td>
            <td>&nbsp;</td>
          </tr>
		  <?php }?>

		  <?php if($tmphisto->hypertension_arterielle == 1){ ?>
          <tr>
            <td>&nbsp;Hypertension art&eacute;rielle >= 140/90 ou HTA trait&eacute;e</td>
            <td>&nbsp;</td>
          </tr>
		  <?php }?>

		  <?php if($tmphisto->dyslipidemie_en_charge == 1){?>
          <tr>
            <td>&nbsp;Dyslipidémie</td>
            <td>&nbsp;</td>
          </tr>
		  <?php }?>

		  <?php if($tmphisto->hdl == 1){ ?>
          <tr>
            <td>HDL &lt;= 0,40 (F) ou 0,35 (H) mg/l ou triglycérides &gt; 1,8mg/l</td>
            <td>&nbsp;</td>
          </tr>
		  <?php }?>

		  <?php if($tmphisto->bebe_sup_4kg == 1 and $dossier->sexe=="F"){ ?>
          <tr>
            <td>&nbsp;A eu un bébé de poids de naissance &gt; à 4 kg</td>
            <td>&nbsp;</td>
          </tr>
		  <?php }?>

		  <?php if($tmphisto->ant_diabete_gestationnel == 1 and $dossier->sexe=="F"){ ?>
          <tr>
            <td>&nbsp;Antécédents de diabète gestationnel</td>
            <td>&nbsp;</td>
          </tr>
  		  <?php }?>

          <tr>
            <th scope="row" colspan=2>Diabète transitoire dans d'autres circonstances:</th>
          </tr>

		  <?php if($tmphisto->corticotherapie == 1){ ?>
          <tr>
            <td>&nbsp;corticothérapie</td>
            <td>&nbsp;</td>
          </tr>
		  <?php }?>
		  <?php if($tmphisto->infection == 1){ ?>
          <tr>
            <td>&nbsp;infection</td>
            <td>&nbsp;</td>
          </tr>
		  <?php }?>
		  <?php if($tmphisto->intervention_chirugicale == 1){ ?>
          <tr>
            <td>&nbsp;intervention chirurgicale</td>
            <td>&nbsp;</td>
          </tr>
		  <?php }?>
		  <?php if($tmphisto->autre == 1){ ?>
          <tr>
            <td>&nbsp;autre</td>
            <td>&nbsp;</td>
          </tr>
		  <?php }?>
          <tr>
            <th scope="row" colspan=2>Examen:</th>
          </tr>
		  <?php if($tmphisto->derniere_gly_date != ""){ ?>
		  <tr>
            <td>&nbsp;Date Glycémie</td>
            <td>&nbsp;<?php echo($tmphisto->derniere_gly_date); ?></td>
          </tr>
		  <?php }?>
		  <?php if($tmphisto->derniere_gly_resultat != ""){ ?>
		  <tr>
            <td>&nbsp;Résultat Glycémie</td>
            <td>&nbsp;<?php echo($tmphisto->derniere_gly_resultat); ?></td>
          </tr>
		  <?php }?>
<!--		  <?php if($tmphisto->note_gly != ""){ ?>
		  <tr>
            <td>&nbsp;Si la glycémie n'a pas été faite: pourquoi ?</td>
            <td>&nbsp;<?php echo($tmphisto->note_gly); ?></td>
          </tr>
		  <?php }?>
-->	  <tr>
            <th scope="row" colspan=2>Mesures de suivi recommandées:</th>
          </tr>
		  <?php if($tmphisto->mesure_suivi_diabete != ""){ ?>
		  <tr>
            <td>&nbsp;Diabète</td>
            <td>&nbsp;oui</td>
          </tr>
		  <?php }?>
		  <?php if($tmphisto->mesure_suivi_controle_annuel != ""){ ?>
		  <tr>
            <td>&nbsp;Contrôle annuel</td>
            <td>&nbsp;oui</td>
          </tr>
		  <?php }?>
		  <?php if($tmphisto->mesure_suivi_hygieno_dietetique != ""){ ?>
		  <tr>
            <td>&nbsp;Mesure hygiéno-diététiques</td>
            <td>&nbsp;oui</td>
          </tr>
		  <?php }?>
        </table>
        </td>
  </tr>
  <?php } ?>
</table>



			
			
