<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php global $account ?>
<?php global $param ?>
<?php global $Conges;  ?>


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
				  "paye"=>"Congés payés",
				  "sanssolde"=>"Congés sans solde",
				  "autres"=>"autres");
    ?>
<style type="text/css">
.btn{
width:100%;
}
</style>

  <table border="0"> 
    <tr> 
      <td>Nom de l'infirmiere : </td> 
      <td><?php echo $Conges->nom; ?> 
</td> 
    </tr> 
    <tr> 
      <td>Prénom de l'infirmière</td> 
      <td><?php echo $Conges->prenom; ?></td> 
    </tr> 
    <tr> 
      <td>Date du 1er jour d'absence</td> 
      <td><?php echo $Conges->date_debut; ?> </td>
    </tr> 
    <tr> 
      <td>Date du dernier jour d'absence</td> 
      <td><?php echo $Conges->date_fin; ?> </td>
    </tr> 
    <tr> 
      <td>Nature du congé</td> 
      <td><?php echo $nature[$Conges->nature]; ?> 

		 
	<?php 
		if($Conges->nature=="autres"){
			echo " : $Conges->prec";
		}
?> 
	</td>
    <tr> 
	</table><br>

	
</form> 

