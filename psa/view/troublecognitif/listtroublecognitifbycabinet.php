<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php require_once("tools/date.php"); ?>
<?php require_once("tools/arrays.php"); ?>

<?php global $account; ?>
<?php global $rowsList; ?>
<?php global $param; ?>
<?php global $signature; ?>

<table width="75%"  border="1" cellspacing="0" cellpadding="0">

    <CAPTION ><?php echo(count($rowsList)) ?> enregistrements trouv�s</CAPTION>

  <tr>
    <th scope="col">&nbsp;Dossier</th>
    <th scope="col">&nbsp;Sexe</th>
    <th scope="col">&nbsp;Date de naissance</th>
 <?php /*   <th scope="col">&nbsp;R�ponse</th>*/?>
    <th scope="col">&nbsp;Consulter</th>
  </tr>
  <?php for($i=0;$i<count($rowsList);$i++){ ?>
  <tr>
    <td>&nbsp;<?php echo(getDoubleArrayElement($rowsList,$i,"numero")); ?></td>
    <td>&nbsp;<?php echo($sexe[getDoubleArrayElement($rowsList,$i,"sexe")]); ?></td>
    <td>&nbsp;<?php echo(mysqlDateTodate(getDoubleArrayElement($rowsList,$i,"dnaiss"))); ?></td>
<?php /*     <td>&nbsp;<?php echo(mysqlDateTodate(getDoubleArrayElement($rowsList,$i,"dsuivi"))); ?></td>*/?>
    <td>&nbsp;<?php
				 $additionalParams = array("Dossier:dossier:numero"=>getDoubleArrayElement($rowsList,$i,"numero"),
				 						   "SuiviDiabete:suiviDiabete:dsuivi"=>mysqlDateTodate(getDoubleArrayElement($rowsList,$i,"dsuivi")));
				 buildLink("","Consulter","$path/controler/ActionControler.php",$param->controler,ACTION_LIST,array(PARAM_ANY),$additionalParams); 
			  ?>
	</td>
  </tr>
  <?php }?>
</table>


