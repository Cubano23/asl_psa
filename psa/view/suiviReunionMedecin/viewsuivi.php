<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php global $account; ?>
<?php global $Rowlist; ?>
<?php global $param; ?>
<?php global $SuiviReunionMedecin; 
$cf = new ConnectionFactory();
      //create mappers
      $SuiviReunionMedecinMapper = new SuiviReunionMedecinMapper($cf->getConnection());
?>

<table border='1' cellpadding='0'>
  <tr>
     <td><b>Cabinet: </b></td>
     <td><?php echo($account->cabinet) ;?></td>
   </tr>
  <tr>
     <td><b>R&eacute;union(s) du : </b></td>
     <td><?php echo($SuiviReunionMedecin->date); ?> </td>
   </tr>
</table>
<br>

<table border="1" cellpadding='1'> 
  <tr> 
    <th width="100" align="center"><b>Date r&eacute;union</th>
    <th width="200"><b>Medecins : </b></th>
    <th width="200"><b>Infirmi&egrave;res : </b></th>
    <th width="70" align="center"><b>Dur&eacute;e</b></th>
    <th width="450" align="center"><b>Motif</b></th>
  </tr> 


  <?php 
  
  foreach($Rowlist as $list){

    $medecins = explode(",",$list['id_mg']);
    foreach($medecins as $mg){
      $infosMedecin = $SuiviReunionMedecinMapper->getMedecinById($mg);
      $nom_mg .= $infosMedecin['prenom'].' '.$infosMedecin['nom'].', ';
    }
    $nom_mg = substr($nom_mg,0,-2);
    ?>
  <tr> 
    <td><?php echo mysqlDateTodate($list['date_reunion']);?></td>
    <td><?php echo $nom_mg;?></td>
    <td><?php echo utf8_decode($list['infirmiere']);?></td>
    <td style="text-align:right"><?php echo $list['duree'];?></td>
    <td><?php echo $list['motif'];?></td>

  </tr>
  <?php } ?>
  <tr>
    <td colspan="5">
      <form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage">
        <?php hiddenControler("SuiviReunionMedecinControler"); ?>
        <?php hiddenAction(""); ?>
        <?php hiddenParam1(""); ?>
        <?php hidden("","SuiviReunionMedecin:cabinet"); ?>
        <?php hidden("","SuiviReunionMedecin:date"); ?>

        <?php customSubmit("value='Compl&eacute;ter ou modifier ces informations'",ACTION_FIND,array(PARAM_EDIT),$param->controler); ?>
      </form>
    </td>
  <tr> 
    <td colspan='5' align='center' style="display:none;">
        <?php #customSubmitWithAlert("value='Supprimer cette &eacute;valuation'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous r&eacute;ellement supprimer cette &eacute;valuation?"); ?>
    </td>
  </tr> 
</table> 
</form>

</body></html>

