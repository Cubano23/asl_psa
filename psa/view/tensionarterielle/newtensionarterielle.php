 <?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier;?>
<?php global $tensionArterielleManagement; ?>

<?php 
	for($i = 0;$i < $tensionArterielleManagement->nombreJours; $i++){
		for($j = 0;$j < 3; $j++){
			$taMatinName = "ta".$i."matin".$j;
			$taSoirName = "ta".$i."soir".$j;			
			global $$taMatinName;
			global $$taSoirName;										
		}
	}
?>

  <?php require("view/common/dossierresume.php");?>

<table border='0' width='500'> 
<tr> 
  <td> <table border=0> 
      <tr> 
        <td>&nbsp;</td> 
        <td>
		<form action=<?php echo("'$path/controler/ActionControler.php'"); ?> method='post'> 
		<?php hiddenControler("TensionArterielleControler"); ?>
		<?php hiddenAction(ACTION_LIST); ?>
		<?php hidden("","dossier:numero"); ?>
            <input type='submit' name='submit' value='TAantérieures'> 
        </form></td> 
      </tr> 
    </table> 
    <br> 
	
    <form action=<?php echo("'$path/controler/ActionControler.php'"); ?> method='post' name='saveForm'> 
	 <?php hiddenControler("TensionArterielleControler"); ?>
	 <?php hiddenAction(ACTION_SAVE); ?>
	<?php hidden("","dossier:numero"); ?>
	<?php hidden("","tensionArterielleManagement:dateDebut");?>
	<?php hidden("","tensionArterielleManagement:nombreJours");?>
      <table border="1" width="500"  cellpadding='3'> 
        <caption> 
        <b>Saisissez les mesures</b> (en mmHg) 
        </caption> 
        <tr> 
          <th rowspan=2>jour</th> 
          <th rowspan=2>moment</th> 
          <th colspan=2>1&deg; mesure</th> 
          <th colspan=2>2&deg; mesure</th> 
          <th colspan=2>3&deg; mesure</th> 
        </tr> 
        <tr> 
          <th>Sys</th> 
          <th>Dia</th> 
          <th>Sys</th> 
          <th>Dia</th> 
          <th>Sys</th> 
          <th>Dia</th> 
        </tr> 
<?php	for($i = 0;$i < $tensionArterielleManagement->nombreJours; $i++){
		?>

        <tr> 
          <td rowspan=2><?php echo(getDayAndMonthName(increaseDateBy($tensionArterielleManagement->dateDebut,$i))); ?></td> 
          <td>matin</td> 
		  <?php for($j = 0;$j < 3; $j++){
			$taMatinName = "ta".$i."matin".$j;
			
		  ?>
          <td><?php text("size='3'","$taMatinName:systole");?></td>
          <td><?php text("size='3'","$taMatinName:diastole");?></td> 
		 <?php }?>
        </tr> 
		
		<tr> 
        <td>soir</td> 
          <?php for($j = 0;$j < 3; $j++){
			$taSoirName = "ta".$i."soir".$j;		
		   ?>
          <td><?php text("size='3'","$taSoirName:systole");?></td> 
          <td><?php text("size='3'","$taSoirName:diastole");?></td> 
		 <?php }?>
		 </tr> 
		 <?php }?>
		</table> 
      <br> 
	  
	
	  
      <table border="1" width="500"> 
        <caption> 
        <b>Valider la saisie:</b> 
        </caption> 
        <tr> 
          <td align='center'><input type='submit' value='Valider la saisie'> 
            <input type='reset' value='Recommencer'> </td> 
        </tr> 
		
      </table> 
    </form> 

