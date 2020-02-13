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
            <th>R�ponse(s)</th> 
          </tr> 

          <tr> 
            <th scope="row" colspan=2>d�pistage diab�te</th> 
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
            <td>&nbsp;parent (au premier degr�) diab�tique de type 2</td> 
            <td>&nbsp;</td> 
          </tr> 
		  <?php }?>
		  
		  <?php if($depistageDiabete->ant_intolerance_glucose == 1){ ?>
          <tr> 
            <td>&nbsp;Ant�c�dents personnels d'intol�rance au glucose</td> 
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
            <td>&nbsp;Dyslipid�mie</td> 
            <td>&nbsp;</td> 
          </tr> 
		  <?php }?>	
		  
		  <?php if($depistageDiabete->hdl == 1){ ?>
          <tr> 
            <td>HDL &lt;= 0,40 (F) ou 0,35 (H) mg/l ou triglyc�rides &gt; 1,8mgl/</td> 
            <td>&nbsp;</td> 
          </tr> 
		  <?php }?>	
		  
		  <?php if($depistageDiabete->bebe_sup_4kg == 1 and $dossier->sexe=="F"){ ?>
          <tr> 
            <td>&nbsp;A eu un b�b� de poids de naissance &gt; � 4 kg</td> 
            <td>&nbsp;</td> 
          </tr> 
		  <?php }?>
		  
		  <?php if($depistageDiabete->ant_diabete_gestationnel == 1 and $dossier->sexe=="F"){ ?>  
          <tr> 
            <td>&nbsp;Ant�c�dents de diab�te gestationnel</td> 
            <td>&nbsp;</td> 
          </tr> 
  		  <?php }?>
		  
          <tr> 
            <th scope="row" colspan=2>Diab�te transitoire dans d'autres circonstances:</th> 
          </tr> 
		  
		  <?php if($depistageDiabete->corticotherapie == 1){ ?>		  
          <tr> 
            <td>&nbsp;corticoth�rapie</td> 
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
            <td>&nbsp;Date Glyc�mie</td> 
            <td>&nbsp;<?php echo($depistageDiabete->derniere_gly_date); ?></td> 
          </tr>
		  <?php }?>
		  <?php if($depistageDiabete->derniere_gly_resultat != ""){ ?>	
		  <tr> 
            <td>&nbsp;R�sultat Glyc�mie</td> 
            <td>&nbsp;<?php echo($depistageDiabete->derniere_gly_resultat); ?></td> 
          </tr>
		   <tr>
		      <th align="left" scope="row">&nbsp;Prescrire nouvelle glyc�mie</th>
		      <td>&nbsp;
		        <?php echo $depistageDiabete->prescription_gly=="1"?"oui":"non";?>
		        <i>derni�re glyc�mie:</i><?php echo($depistageDiabete->getDateDiff()); ?>&nbsp;mois</td>
		    </tr>
<!--		    <tr>
		      <th  colspan="2">&nbsp;R�sultat de la nouvelle glyc�mie</th>
		    </tr>
		    <tr>
		      <th align="left" scope="row">&nbsp;Date</th>
		      <td>&nbsp;
		        <?php echo $depistageDiabete->nouvelle_gly_date;?></td>
		    </tr>
		    <tr>
		      <th align="left" scope="row">&nbsp;R�sultat</th>
		      <td>&nbsp;
		        <?php echo $depistageDiabete->nouvelle_gly_resultat;?>
		        g/l</td>
		    </tr>

		  <?php }?>
	  <?php if($depistageDiabete->note_gly != ""){ ?>	
		  <tr> 
            <td>&nbsp;Si la glyc�mie n'a pas �t� faite: pourquoi ?</td> 
            <td>&nbsp;<?php echo($depistageDiabete->note_gly); ?></td> 
          </tr>
		  <?php }?>
-->		  <tr> 
            <th scope="row" colspan=2>Mesures de suivi recommand�es:</th>
          </tr> 
		  <?php if($depistageDiabete->mesure_suivi_diabete != ""){ ?>	
		  <tr> 
            <td>&nbsp;Diab�te</td> 
            <td>&nbsp;oui</td> 
          </tr>
		  <?php }?>
		  <?php if($depistageDiabete->mesure_suivi_controle_annuel != ""){ ?>	
		  <tr> 
            <td>&nbsp;Contr�le annuel</td> 
            <td>&nbsp;oui</td> 
          </tr>
		  <?php }?>
		  <?php if($depistageDiabete->mesure_suivi_hygieno_dietetique != ""){ ?>	
		  <tr> 
            <td>&nbsp;Mesure hygi�no-di�t�tiques</td> 
            <td>&nbsp;oui</td> 
          </tr>
		  <?php }?>
        </table> 
      <br></td> 
    </tr> 
    <tr> 
      <td>
		<?php customSubmitWithAlert("value='Supprimer ce d�pistage'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous r�ellement supprimer cette r�ponse ?"); ?></td>
      <td> <input type='submit' value='Modifier ce suivi'> </td> 
    </tr> 
  </table> 
</form> 
