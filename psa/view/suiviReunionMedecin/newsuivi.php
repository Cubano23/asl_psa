<?php require_once("bean/beanparser/htmltags.php");?>
<?php require_once("view/common/vars.php");?>
<?php require_once("view/jsgenerator/jsgenerator.php");?>
<?php global $account; ?>
<?php global $param; ?>
<?php global $listReunionMedecin;?>
<?php global $SuiviReunionMedecin; ?>
<?php global $Rowlist; ?>
<?php global $medecins; ?>
<?php global $infirmieres; ?>
<?php
require_once ("Config.php");
$config = new Config();
require_once($config->rest_path . '/GetCabsAndLogins.php') ;

#echo '<pre> '; print_r($_SERVER['DOCUMENT_ROOT'].'/rest/GetCabsAndLogins.php'); echo '</pre>';exit;
$infirmieres = GetLoginsByCab($_SESSION['cabinet'], $status);


#var_dump($_SESSION['account']);
?>


<script type="text/javascript" src="jquery_latest.js"></script>

<style type="text/css">
  .hide{
    display:none;
  }
</style>
 <table border='1'  cellpadding='0'> 
  <tr> 
     <td><b>Cabinet: </b></td>
     <td><?php echo($account->cabinet) ?></td> 
  </tr> 
  <tr> 
     <td><b>R&eacute;union du : </b></td>
     <td><?php echo($SuiviReunionMedecin->date); ?></td>
   </tr> 
</table> 
<br> 

<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="edit"> 
  <?php hiddenControler("SuiviReunionMedecinControler"); ?>
  <?php hiddenAction(ACTION_SAVE);?>
  <?php hidden("","SuiviReunionMedecin:cabinet");?>
  <?php hidden("","SuiviReunionMedecin:date");?>
  <input id='compt' type='hidden' name='compt' value='<?php echo (count($Rowlist)==0) ? 1 : count($Rowlist) ; ?>'/>



<table border="1" cellpadding='1'> 
  <tr> 
    <th align="center">Date r&eacute;union</th>
    <th>Medecins : </th>
    <th>Infirmi&egrave;res : </th>
    <th align="center">Dur&eacute;e <span style="font-weight:100">minutes</span></th>
    <th align="center">Motif</th>
  </tr> 

      <?php 

    if($Rowlist){
    foreach($Rowlist as $i=>$list){
      #$listemedecins = explode(',', $list['medecin']);
      $listemedecins = explode(',', $list['id_mg']);
      $listeinfirmieres = explode(',', $list['infirmiere']);
      
    ?>
     <tr>
      <td>
        <?php echo $SuiviReunionMedecin->date; ?>
        <input type="hidden" name="date_reunion_<?php echo $i; ?>" size='10' value="<?php echo $SuiviReunionMedecin->date; ?>" />
      </td>
      <td>
        <?php foreach($medecins as $m){ ?>
                <input name='id_mg_<?php echo $i; ?>[]' type='checkbox' <?php echo in_array($m['id'],$listemedecins)? "checked=checked": '';?> value="<?php echo $m['id'];?>">&nbsp;<?php echo $m['prenom'].' '.$m['nom'];?><br/>
        <?php } ?>
      </td>
      <td>
        <?php foreach($infirmieres as $inf){ ?>
                <input name='infirmiere_<?php echo $i; ?>[]' type='checkbox' <?php echo in_array($inf['prenom'].' '.$inf['nom'],$listeinfirmieres)? "checked=checked": '';?> value="<?php echo $inf['prenom'].' '.$inf['nom']?>">&nbsp;<?php echo utf8_decode($inf['prenom'].' '.$inf['nom']);?>..<br/>
        <?php } ?>
      </td>
      <td style="text-align:right"><input size='10' maxlength='6' type="text" name="duree_<?php echo $i; ?>" value="<?php echo $list['duree'];?>"/></td>
      <td><input type="text" size='60' maxlength='250' name="motif_<?php echo $i; ?>" value="<?php echo $list['motif'];?>"/></td>
      
    </tr>
    <?php } 
    for($i=count($Rowlist);$i<=9; $i++):?>
      <tr class="ligne hide ehh">
        <td><?php echo $SuiviReunionMedecin->date; ?>
          <input type="hidden" name="date_reunion_<?php echo $i; ?>" size='10'  value="<?php echo $SuiviReunionMedecin->date; ?>" /></td>
        <td>
          <?php foreach($medecins as $m){ 
            echo '<input type="checkbox" name="id_mg_'.$i.'[] " value="'.$m['id'].'">'.$m['prenom'].' '.$m['nom'].'<br/>';

          } ?>
        </td>
        <td>
        <?php foreach($infirmieres as $inf){ ?>
                <input name='infirmiere_<?php echo $i; ?>[]' type='checkbox' value="<?php echo utf8_decode($inf['prenom'].' '.$inf['nom']);?>">&nbsp;<?php echo utf8_decode($inf['prenom'].' '.$inf['nom']);?><br/>
        <?php } ?>
      </td>
        <td style="text-align:right"><input size='10' maxlength='6' type="text" name="duree_<?php echo $i; ?>" value=""/></td>
        <td><input type="text" size='60' maxlength='250' name="motif_<?php echo $i; ?>" value=""/></td>
      </tr>
  <?php endfor;

    }else{ ?>

  <?php for($i=0; $i<=9; $i++): ?>
    <tr class="ligne<?php echo $i>0  ? ' hide' : ''; ?>">
      <td><?php echo $SuiviReunionMedecin->date; ?>
        <input type="hidden" name="date_reunion_<?php echo $i; ?>" size='10'  value="<?php echo $SuiviReunionMedecin->date; ?>" /></td>      
      <td>
        <?php foreach($medecins as $m){
          echo '<input type="checkbox" name="id_mg_'.$i.'[] " value="'.$m['id'].'">  '.$m['prenom'].' '.$m['nom'].'<br/>';
        }?>
      </td>
      <td>
        <?php foreach($infirmieres as $inf){
          echo '<input type="checkbox" name="infirmiere_'.$i.'[] " value="'.utf8_decode($inf['prenom'].' '.$inf['nom']).'">  '.utf8_decode($inf['prenom'].' '.$inf['nom']).'<br/>';
        }?>
      </td>
      <td style="text-align:right"><input size='10' maxlength='6' type="text" name="duree_<?php echo $i; ?>" value=""/></td>
      <td><input type="text" size='60' maxlength='250' name="motif_<?php echo $i; ?>" value=""/></td>
    </tr>
  <?php endfor;?>

 
  <?php } ?>
  <tr>
    <td colspan="5"><a id="ajoutligne" href="#">Ajouter une reunion</a></td>
  </tr>




   



  <tr> 
    <td colspan="5" align='center'><input type='submit' value='Valider la saisie'><input type='reset' value='Recommencer'> </td> 
  </tr> 
</table> 
</form> 

</body></html>
<script type="text/javascript">

  var clic=0;

  $('#ajoutligne').click(function(){
    var nbr = $('#compt').val();
    $('#compt').attr('value', parseInt(nbr)+1);
    
    $('tr.hide').first().removeClass('hide');
    clic = clic+1;

    if(clic==9)$(this).hide();
  });

</script>