<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php global $account ?>
<?php global $param ?>
<?php global $Frais;  ?>


<script type="text/javascript" >
<?php
	validateNumeroDossier();
	validateDate();
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","aForm");
	$js->validateDate("suiviDiabete:dsuivi","Date du suivi");
	$js->endCheckFunction();	
?>

function affiche_autre(){
	var nature = document.getElementById("nature");
	var nat = nature.options[nature.selectedIndex].value;
	
	if(nat=="autres"){
		document.getElementById("texte_autre_conge").style.display="";
		document.getElementById("autre_conge").style.display="";
	}
	else{
		document.getElementById("texte_autre_conge").style.display="none";
		document.getElementById("autre_conge").style.display="none";
	}

}
</script>

<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="aForm"> 
	<?php hiddenControler("CongesControler"); ?> 
	<?php /*hiddenAction(ACTION_MANAGE); ?> 
	<?php hiddenParam1(PARAM_CREATE);*/ ?> 
	<?php hiddenAction(ACTION_SAVE); ?> 
	<?php hidden("","Conges:id");?>

	<?php
	$nature=array(""=>"",
				  "paye"=>"Cong?s pay?s",
				  "sanssolde"=>"Cong?s sans solde",
				  "autres"=>"autres");
    ?>
<style type="text/css">
.btn{
width:100%;
}
</style>

  <table border="0"> 
  <table border="0"> 
    <tr> 
      <td>Date des frais</td> 
      <td ><?php echo $Frais->date_frais; ?> </td>
    </tr> 
    <tr> 
      <td>Bénéficiaire</td>
      <td ><?php echo $Frais->infirmiere; ?> </td>
    </tr> 
    <tr> 
      <td>Nature des frais</td> 
      <td><?php echo $Frais->nature; ?> </td>
      <?php
      if ($Frais->autreNature != NULL || $Frais->autreNature != "") {
      ?>
          <td><em><?php echo $Frais->autreNature; ?></em></td>
      <?php
      }
      ?>
    </tr> 
    <tr> 
      <td>Motif</td> 
      <td><?php echo $Frais->motif; ?> </td>
    </tr> 
    <tr> 
      <td>Le cas échéant, montant en euros</td>
      <td><?php echo $Frais->montant; ?> ?</td>
    </tr> 
    <tr> 
      <td>Le cas échéant, autre unité de calcul</td>
      <td><?php echo $Frais->autre_calcul; ?> </td>
    </tr> 
    <!--chemin faux il a chang? le 6.10.2016<tr> 
      <td>Le cas ?ch?ant, justificatif en pi?ce jointe</td> 
     <td><?php echo "<a href='$Frais->pj' target='_blank'>".str_replace("../view/pj/", "", $Frais->pj)."</a>" ?></td>
    </tr>
    --> 
	</table><br>

	
</form> 


