<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php global $account; ?>

<?php global $SuiviHebdomadaire; ?>
 <table border='1'  cellpadding='0'> 
  <tr> 
     <td>Cabinet: </td> 
     <td><?php echo($account->cabinet) ?></td> 
   </tr> 
  <tr> 
     <td>Semaine: </td> 
     <td><?php echo($SuiviHebdomadaire->date); ?> </td>
   </tr> 
</table> 
<br> 
<br> 
<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage"> 
  <?php hiddenControler("SuiviHebdomadaireControler"); ?>
  <?php hiddenAction(ACTION_SAVE); ?> 
  <?php hidden("", "SuiviHebdomadaire:cabinet"); ?>
  <?php hidden("","SuiviHebdomadaire:date");?>
  <table border="1" cellpadding='3'> 
    <tr> 
      <th>tâche</th> 
      <th>durée<br> 
        (heures)</th> 
      <th>nombre</th> 
    </tr> 
    <tr> 
      <td colspan=2><b>Dépistage</b></td> 
    </tr> 
    <tr> 
      <td>Travail sur base de données</td> 
      <td><?php text("id='travail_base_h' onKeyUp='calc_total()'","SuiviHebdomadaire:travail_base_h");?></td>
    </tr> 
    <tr> 
      <td>Consultations individuelles</td> 
      <td><?php text("id='consult_indiv_h' onKeyUp='calc_total()'","SuiviHebdomadaire:consult_indiv_h");?></td>
      <td><?php text("","SuiviHebdomadaire:consult_indiv_n");?></td>
    </tr> 
    <tr> 
      <td>&nbsp; </td> 
    </tr> 
    <tr> 
      <td colspan=2><b>Prévention</b></td> 
    </tr> 
    <tr> 
      <td colspan=2>Consultation d'éducation à la santé</td> 
    </tr> 
    <tr> 
      <td>diabète</td> 
      <td><?php text("id='prevention_diabete_h' onKeyUp='calc_total()'","SuiviHebdomadaire:prevention_diabete_h");?></td>
    </tr> 
    <tr> 
      <td>autres</td> 
      <td><?php text("id='prevention_autre_h' onKeyUp='calc_total()'","SuiviHebdomadaire:prevention_autre_h");?></td>
    </tr> 
    <tr> 
      <td>si autres, lesquelles</td> 
      <td colspan=2><?php text("","SuiviHebdomadaire:prevention_autre_note");?></td>
    </tr> 
    <tr> 
      <td colspan=2>Séances collectives</td> 
    </tr> 
    <tr> 
      <td>diabète</td> 
      <td><?php text("id='seance_diabete_h' onKeyUp='calc_total()'","SuiviHebdomadaire:seance_diabete_h");?></td>
    </tr> 
    <tr> 
      <td>autres</td> 
      <td><?php text("id='seance_autre_h' onKeyUp='calc_total()'","SuiviHebdomadaire:seance_autre_h");?></td>
    </tr> 
    <tr> 
      <td>si autres, lesquelles</td> 
      <td colspan=2><?php text("","SuiviHebdomadaire:seance_autre_note");?></td>
    </tr> 
    <tr> 
      <td>&nbsp; </td> 
    </tr> 
    <tr> 
      <td>Suivi de l'armoire à pharmacie</td> 
      <td><?php text("id='suivi_armoire_h' onKeyUp='calc_total()'","SuiviHebdomadaire:suivi_armoire_h");?></td>
      <td><?php text("","SuiviHebdomadaire:suivi_armoire_n");?></td>
    </tr> 
    <tr> 
      <td>&nbsp; </td> 
    </tr> 
    <tr> 
      <td colspan=2><b>Aide au fonctionnement du cabinet</b></td> 
    </tr> 
    <tr> 
      <td>Téléphone</td> 
      <td><?php text("id='aide_telephone' onKeyUp='calc_total()'","SuiviHebdomadaire:aide_telephone");?></td>
    </tr> 
    <tr> 
      <td>Préparation du matériel</td> 
      <td><?php text("id='aide_prep_matos' onKeyUp='calc_total()'","SuiviHebdomadaire:aide_prep_matos");?></td>
    </tr> 
    <tr> 
      <td>Examens complémentaires<br> 
        <font size="-1">(ECG, automesure TA, autres)</font></td> 
      <td valign='top'><?php text("id='aide_examen_compl' onKeyUp='calc_total()'","SuiviHebdomadaire:aide_examen_compl");?></td>
    </tr> 
    <tr> 
      <td>Soins</td> 
      <td><?php text("id='aide_soins' onKeyUp='calc_total()'","SuiviHebdomadaire:aide_soins");?></td>
    </tr> 
    <tr> 
      <td>&nbsp; </td> 
    </tr> 
    <tr> 
      <td>Formation </td> 
      <td><?php text("id='aide_formation' onKeyUp='calc_total()'","SuiviHebdomadaire:aide_formation");?></td>
    </tr> 
    <tr> 
      <td>&nbsp; </td> 
    </tr> 
    <tr> 
      <td>Autres</td> 
      <td><?php text("id='aide_autre' onkeyup='calc_total()'","SuiviHebdomadaire:aide_autre");?></td>
    </tr> 
    <tr> 
      <td>&nbsp; </td> 
      <td>____</td> 
    </tr> 
    <tr> 
      <td>Total </td> 
      <td id="total">&nbsp;</td> 
    </tr> 
    <tr> 
      <td>&nbsp; </td> 
    </tr> 
    <tr> 
      <td colspan="3" align='center'> <input type='submit' value='Valider la saisie'> 
        <input type='reset' value='Recommencer'> </td> 
    </tr> 
  </table> 
</form> 
<script language="JavaScript" type="text/javascript">
  <!-- 
  


  function calc_total() { // calcule et affiche le nb d'heures total déclaré
   var tot;
    
   tot = Number(document.getElementById("travail_base_h").value) + 
   		Number(document.getElementById("consult_indiv_h").value) +
		Number(document.getElementById("prevention_diabete_h").value) +
		Number(document.getElementById("prevention_autre_h").value) +
		Number(document.getElementById("seance_diabete_h").value) +
		Number(document.getElementById("seance_autre_h").value) +
		Number(document.getElementById("suivi_armoire_h").value) +
		Number(document.getElementById("aide_telephone").value) + 
		Number(document.getElementById("aide_prep_matos").value) +
		Number(document.getElementById("aide_examen_compl").value) +
		Number(document.getElementById("aide_soins").value) +
		Number(document.getElementById("aide_formation").value) +
		Number(document.getElementById("aide_autre").value);

        
  		 if(isNaN(tot)) tot="Erreur";    
   		 document.getElementById("total").innerHTML = tot; 
  }
   -->  
</script> 
</body></html>
