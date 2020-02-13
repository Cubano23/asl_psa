<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php global $account ?>
<?php global $dossier; ?>
<?php global $depistageDiabete; ?>
<?php global $param;?>
 
<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="aForm"> 
  <?php hiddenControler("DepistageDiabeteControler"); ?> 
  <?php hiddenAction(ACTION_FIND); ?> 
  <?php hiddenParam1(PARAM_EDIT); ?> 
  <?php hidden("","depistageDiabete:date");?> 
  <?php hidden("","dossier:numero");?> 
 
  <table border='0' width='100%' align='center'> 
    <tr> 
      <td valign='top'><?php require("view/common/dossierresume.php");?></td> 
      <td> <table border='1' rules='none' cellpadding='3'> 
          <tr> 
            <th>Question</th> 
            <th>Réponse(s)</th> 
          </tr> 

          <tr> 
            <th scope="row" colspan=2>dépistage diabète</th> 
          </tr> 
          <tr> 
            <td valign='top'>Poids</td> 
            <td><?php typePropertyValue("depistageDiabete:poids"); ?></td> 
          </tr> 
          <tr> 
            <td valign='top'>Indice de masse corporelle (imc)</td> 
            <td><?php echo($depistageDiabete->getIMC($dossier->taille));?></td> 
          </tr> 
          <tr> 
            <th scope="row" colspan="2">Facteurs de risque:</th> 
          </tr> 
		  
		  <?php if($depistageDiabete->getIMC($dossier->taille)>28){?>
		  <tr> 
            <td>&nbsp;L'indice de masse corporelle est >28Kg/m<sup>2</sup></td> 
            <td>&nbsp;</td> 
          </tr> 
		  <?php } ?>
		  
		  <?php if($depistageDiabete->parent_diabetique_type2 ==1){ ?>
          <tr> 
            <td>&nbsp;parent (au premier degré) diabétique de type 2</td> 
            <td>&nbsp;</td> 
          </tr> 
		  <?php }?>
		  
		  <?php if($depistageDiabete->ant_intolerance_glucose == 1){ ?>
          <tr> 
            <td>&nbsp;Antécédents personnels d'intolérance au glucose</td> 
            <td>&nbsp;</td> 
          </tr> 
		  <?php }?>
		  
		  <?php if($depistageDiabete->hypertension_arterielle == 1){ ?>
          <tr> 
            <td>&nbsp;Hypertension art&eacute;rielle >= 140/90 ou HTA trait&eacute;e</td> 
            <td>&nbsp;</td> 
          </tr> 
		  <?php }?>		  
		  
		  <?php if($depistageDiabete->dyslipidemie_en_charge == 1){?>
          <tr> 
            <td>&nbsp;Dyslipidémie</td> 
            <td>&nbsp;</td> 
          </tr> 
		  <?php }?>	
		  
		  <?php if($depistageDiabete->hdl == 1){ ?>
          <tr> 
            <td>HDL &lt;= 0,40 (F) ou 0,35 (H) mg/l ou triglycérides &gt; 1,8mgl/</td> 
            <td>&nbsp;</td> 
          </tr> 
		  <?php }?>	
		  
		  <?php if($depistageDiabete->bebe_sup_4kg == 1 and $dossier->sexe=="F"){ ?>
          <tr> 
            <td>&nbsp;A eu un bébé de poids de naissance &gt; à 4 kg</td> 
            <td>&nbsp;</td> 
          </tr> 
		  <?php }?>
		  
		  <?php if($depistageDiabete->ant_diabete_gestationnel == 1 and $dossier->sexe=="F"){ ?>  
          <tr> 
            <td>&nbsp;Antécédents de diabète gestationnel</td> 
            <td>&nbsp;</td> 
          </tr> 
  		  <?php }?>
		  
          <tr> 
            <th scope="row" colspan=2>Diabète transitoire dans d'autres circonstances:</th> 
          </tr> 
		  
		  <?php if($depistageDiabete->corticotherapie == 1){ ?>		  
          <tr> 
            <td>&nbsp;corticothérapie</td> 
            <td>&nbsp;</td> 
          </tr> 
		  <?php }?>
		  <?php if($depistageDiabete->infection == 1){ ?>		
          <tr> 
            <td>&nbsp;infection</td> 
            <td>&nbsp;</td> 
          </tr> 
		  <?php }?>
		  <?php if($depistageDiabete->intervention_chirugicale == 1){ ?>		
          <tr> 
            <td>&nbsp;intervention chirurgicale</td> 
            <td>&nbsp;</td> 
          </tr> 
		  <?php }?>
		  <?php if($depistageDiabete->autre == 1){ ?>		
          <tr> 
            <td>&nbsp;autre</td> 
            <td>&nbsp;</td> 
          </tr> 
		  <?php }?>
          <tr> 
            <th scope="row" colspan=2>Examen:</th>
          </tr> 
		  <?php if($depistageDiabete->derniere_gly_date != ""){ ?>	
		  <tr> 
            <td>&nbsp;Date Glycémie</td> 
            <td>&nbsp;<?php echo($depistageDiabete->derniere_gly_date); ?></td> 
          </tr>
		  <?php }?>
		  <?php if($depistageDiabete->derniere_gly_resultat != ""){ ?>	
		  <tr> 
            <td>&nbsp;Résultat Glycémie</td> 
            <td>&nbsp;<?php echo($depistageDiabete->derniere_gly_resultat); ?></td> 
          </tr>
		   <tr>
		      <th align="left" scope="row">&nbsp;Prescrire nouvelle glycémie</th>
		      <td>&nbsp;
		        <?php echo $depistageDiabete->prescription_gly=="1"?"oui":"non";?>
		        <i>dernière glycémie:</i><?php echo($depistageDiabete->getDateDiff()); ?>&nbsp;mois</td>
		    </tr>
<!--		    <tr>
		      <th  colspan="2">&nbsp;Résultat de la nouvelle glycémie</th>
		    </tr>
		    <tr>
		      <th align="left" scope="row">&nbsp;Date</th>
		      <td>&nbsp;
		        <?php echo $depistageDiabete->nouvelle_gly_date;?></td>
		    </tr>
		    <tr>
		      <th align="left" scope="row">&nbsp;Résultat</th>
		      <td>&nbsp;
		        <?php echo $depistageDiabete->nouvelle_gly_resultat;?>
		        g/l</td>
		    </tr>

		  <?php }?>
	  <?php if($depistageDiabete->note_gly != ""){ ?>	
		  <tr> 
            <td>&nbsp;Si la glycémie n'a pas été faite: pourquoi ?</td> 
            <td>&nbsp;<?php echo($depistageDiabete->note_gly); ?></td> 
          </tr>
		  <?php }?>
-->		  <tr> 
            <th scope="row" colspan=2>Mesures de suivi recommandées:</th>
          </tr> 
		  <?php if($depistageDiabete->mesure_suivi_diabete != ""){ ?>	
		  <tr> 
            <td>&nbsp;Diabète</td> 
            <td>&nbsp;oui</td> 
          </tr>
		  <?php }?>
		  <?php if($depistageDiabete->mesure_suivi_controle_annuel != ""){ ?>	
		  <tr> 
            <td>&nbsp;Contrôle annuel</td> 
            <td>&nbsp;oui</td> 
          </tr>
		  <?php }?>
		  <?php if($depistageDiabete->mesure_suivi_hygieno_dietetique != ""){ ?>	
		  <tr> 
            <td>&nbsp;Mesure hygiéno-diététiques</td> 
            <td>&nbsp;oui</td> 
          </tr>
		  <?php }?>
        </table> 
      <br></td> 
    </tr> 
    <tr> 
      <td>
		<?php customSubmitWithAlert("value='Supprimer ce dépistage'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous réellement supprimer cette réponse ?"); ?></td>
      <td> <input type='submit' value='Modifier ce suivi'> </td> 
    </tr> 
  </table> 
</form> 
