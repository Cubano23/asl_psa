<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php global $account; ?>
<?php global $dossier; ?>
<?php global $param; ?>
<?php global $Biologie;  ?>


<script type="text/javascript" >
<?php
	validateNumeroDossier();
	validateDate();
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","aForm");
	$js->validateNumeroDossier("dossier:numero","Num�ro de dossier");
	$js->validateDate("suiviDiabete:dsuivi","Date du suivi");
	$js->endCheckFunction();	
?>
</script>

<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="aForm"> 
	<?php hiddenControler("HistoBiologieControler"); ?> 
	<?php /*hiddenAction(ACTION_MANAGE); ?> 
	<?php hiddenParam1(PARAM_CREATE);*/ ?> 
	<?php hiddenAction(ACTION_SAVE); ?> 
	<?php hiddenParam1(""); ?>
	<?php hiddenParamN("",2); ?>
	<?php hidden("","dossier:numero");?>
	<?php hidden("","Biologie:numero");?>
	<?php hidden("","Biologie:type_exam");?>

  <?php
    $type_exam = array("Chol"=>"Cholest�rol total",
                "creat"=>"Cr�atinine",
                "dent"=>"Dentiste",
				"diastole"=>"Diastole",
                "ECG"=>"ECG",
                "monofil"=>"Examen au monofilament",
				"pied"=>"Examen des pieds",
                "fond"=>"Fond d'oeil",
                "glycemie"=>"Glyc�mie",
                "HBA1c"=>"HBA1c",
                "HDL"=>"HDL",
                "hematurie"=>"H�maturie",
                "kaliemie"=>"Kali�mie",
                "LDL"=>"LDL",
                "albu"=>"Micro Albuminurie",
				"poids"=>"Poids",
				"pouls"=>"Pouls",
				"proteinurie"=>"Prot�inurie",
				"systole"=>"Systole",
				"triglycerides"=>"Triglyc�rides"); 

	$unites=array("Chol"=>"g/l",
                "creat"=>"mg",
                "dent"=>"",
				"diastole"=>"mmHg",
                "ECG"=>"",
                "monofil"=>"",
				"pied"=>"",
                "fond"=>"",
                "glycemie"=>"g/l",
                "HBA1c"=>"%",
                "HDL"=>"g/l",
                "hematurie"=>"",
                "kaliemie"=>"mmol/l",
                "LDL"=>"g/l",
                "albu"=>"",
				"poids"=>"kg",
				"pouls"=>"/min",
				"proteinurie"=>"",
				"systole"=>"mmHg",
				"triglycerides"=>"g/l");
	
	$positif=array("fond", "hematurie", "albu", "proteinurie");
				?>
    
<style type="text/css">
.btn{
width:100%;
}
</style>

  <table border="0"> 
    <tr> 
      <td>Type d'examen : </td> 
      <td><?php echo $type_exam[$Biologie->type_exam]; ?> 
</td> 
    </tr> 
    <tr> 
      <td>Date de l'examen</td> 
      <td><?php text("size='10'","Biologie:date_exam"); ?></td> 
    </tr> 
    <tr> 
      <td>Valeur de l'examen</td> 
      <td><?php 
		if($unites[$Biologie->type_exam]==""){
			if(in_array($Biologie->type_exam, $positif)){
				checkBox("id='resultat1'","Biologie:resultat1","1"); echo "Cocher si positif";
			}
		}
		else{
			text("size='10'","Biologie:resultat1"); echo $unites[$Biologie->type_exam];
			
			if($Biologie->type_exam=="creat"){
				echo "</tr><tr><td>Cocher si positif </td><td>";checkBox("id='resultat2'","Biologie:resultat2","1"); 
			}
		}
		?></td> 
    </tr> 
    <tr> 
	</table><br>
	<?php
	
			 customSubmit("value='Enregistrer' ",ACTION_SAVE,"", "","validateInput"); ?><br>		

</form> 

