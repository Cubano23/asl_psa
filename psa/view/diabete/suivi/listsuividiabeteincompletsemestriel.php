<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php require_once("tools/date.php"); ?>
<?php require_once("tools/arrays.php"); ?>

<?php global $account ?>
<?php global $param ?>
<?php global $rowsList ?>
 
<table border="1" align='center'> 
  <CAPTION>
   Liste des <?php echo(count($rowsList)); ?> suivis <?php if ($param->param3 == 'PINCOMP') echo "incomplets";
   							     elseif ($param->param3 == 'PCOMP') echo "complets";
   							     elseif ($param->param3 == 'PTOUS') echo "complets et incompets";?>
   		de type semestriel pour le cabinet <?php echo($account->cabinet); ?>
  </CAPTION> 
  <tr> 
    <th>Dossier</th> 
    <th>Date</th> 
    <th>Modifier</th> 
    <th><font size='-1'>Examen des pieds au filament</font></th> 
    <th><font size='-1'>Date examen au filament</font></th> 
    <th><font size='-1'>Examen des pieds</font></th> 
    <th><font size='-1'>Date examen des pieds</font></th> 
  </tr> 
   <?php for($i=0;$i<count($rowsList);$i++){ ?>
  <tr> 
    <td>&nbsp;<?php echo(getDoubleArrayElement($rowsList,$i,"numero")); ?></td> 
    <td>&nbsp;<?php echo(mysqlDateTodate(getDoubleArrayElement($rowsList,$i,"dsuivi"))); ?></td> 
    <td>&nbsp;
		<?php
			$additionalParams = array("Dossier:dossier:numero"=>getDoubleArrayElement($rowsList,$i,"numero"),
					"SuiviDiabete:suiviDiabete:dsuivi"=>mysqlDateTodate(getDoubleArrayElement($rowsList,$i,"dsuivi")));
			buildLink("","Modifier","$path/controler/ActionControler.php",$param->controler,ACTION_FIND,array(PARAM_EDIT),$additionalParams); 
		?>
	</td> 
    <td align='center'>&nbsp;<?php echo(getDoubleArrayElement($rowsList,$i,"ExaFil")); ?>
    <td align='center'>&nbsp;<?php echo(mysqlDateTodate(getDoubleArrayElement($rowsList,$i,"dExaFil"))); ?>
    <td align='center'>&nbsp;<?php echo(getDoubleArrayElement($rowsList,$i,"ExaPieds")); ?> 
    <td align='center'>&nbsp;<?php echo(mysqlDateTodate(getDoubleArrayElement($rowsList,$i,"dExaPieds"))); ?>
  </tr> 
  <?php }?>
</table> 

