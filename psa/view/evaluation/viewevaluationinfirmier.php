<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $evaluationInfirmier ?>
<?php global $param ?>

<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage">
<?php hiddenControler(""); ?>
<?php hiddenAction(""); ?>
<?php hiddenParam1(""); ?>
<?php hidden("","evaluationInfirmier:date");?>
<?php hidden("","dossier:numero");?>

<table border='0' width='100%' align='center'>
  <tr>
    <td valign='top'><?php require("view/common/dossierresume.php");?></td>
    <td><table border='1' cellpadding='3'>
        <tr>
          <th>Question</th>
          <th>R�ponse(s)</th>
        </tr>
        <tr>
          <td colspan=2><b>�valuation infirmi�re</b></td>
        </tr>
        <tr>
          <td valign='top'>Satisfaction</td>
          <td><?php echo($satisfaction[getPropertyValue("evaluationInfirmier:degre_satisfaction")]); ?></td>
        </tr>
        <tr>
          <td valign='top'>Dur�e en minutes</td>
          <td><?php typePropertyValue("evaluationInfirmier:duree"); if($evaluationInfirmier->consult_domicile==1){echo ', &agrave; domicile';}?></td>
        </tr>
        <tr>
          <td valign='top'>Type de consultation</td>
          <td><?php 
		  			foreach($evaluationInfirmier->type_consultation as $consult){
		  				echo $type_consult[$consult].', ';
		  			}
			 ?></td>
        </tr>
		<tr>
			<td valign='top'>Examens r�alis�s par d�l�gation</td>
			<td><?php 
            if($evaluationInfirmier->hba==1){ echo "Prescription d'examen(s) pour le patient diab�tique type 2 ";}//Prescription HbA1c
            if($evaluationInfirmier->exapied==1){ echo "Prescription, r�alisation, interpr�tation examen des pieds ";}//Examen des pieds
            if($evaluationInfirmier->monofil==1){ echo "Prescription, r�alisation, interpr�tation examen des pieds et monofilament ";}//Monofilament
            if($evaluationInfirmier->ecg==1){ echo "Prescription, r�alisation d'ECG, ";}//nouveau ajout ECG
            if($evaluationInfirmier->ecg_seul==1){ echo "R�alisation d'ECG seul - non d�rogatoire, ";}// ECG seul
					  /*if($evaluationInfirmier->tension==1){ echo "Tension, ";}*/
					  if($evaluationInfirmier->spirometre==1){ echo "Prescription, r�alisation d\'une spirom�tre ";}//Spirom�tre
            if($evaluationInfirmier->spirometre_seul==1){ echo "R�alisation d�une spirom�trie seule - non d�rogatoire, ";}
            if($evaluationInfirmier->t_cognitif==1){ echo "Prescription, r�alisation d\'un rep�rage troubles cognitifs ";}//Nouveau ajout Cognitif
					  if($evaluationInfirmier->autre==1){ echo "Autre : ".$evaluationInfirmier->prec_autre;}?>
			</td>
        <tr>
          <td valign='top'>Points positifs</td>
          <td><?php typePropertyValue("evaluationInfirmier:points_positifs"); ?></td>
        </tr>
        <tr>
          <td valign='top'>Points � am�liorer</td>
          <td><?php typePropertyValue("evaluationInfirmier:points_ameliorations"); ?></td>
        </tr>
      </table>
      <br></td>
  </tr>
  <tr>
    <td>
        <?php customSubmitWithAlert("value='Supprimer cette &eacute;valuation'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous r&eacute;ellement supprimer cette &eacute;valuation?"); ?>
    </td>
    <td>
        <?php customSubmit("value='Modifier cette &eacute;valuation'",ACTION_FIND,array(PARAM_EDIT),$param->controler); ?>
    </td>
  </tr>
</table>
</form>

