<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php require_once("tools/date.php"); ?>
<?php require_once("tools/arrays.php"); ?>

<?php global $account; ?>
<?php global $rowsList; ?>
<?php global $param; ?>
<?php global $SuiviHebdomadaire2007; ?>



<table width="75%"  border="1" cellspacing="0" cellpadding="0">
 
  <CAPTION  ><?php echo(count($rowsList)) ?> enregistrements trouvés</CAPTION>
  
  <tr>
    <th scope="col">&nbsp;Réponse</th>
    <th scope="col">&nbsp;Consulter</th>
  </tr>
  <?php for($i=0;$i<count($rowsList);$i++){ ?>
  <tr>
    <td>&nbsp;<?php echo(mysqlDateTodate(getDoubleArrayElement($rowsList,$i,"date"))); ?></td>	
    <td>&nbsp;<?php
				 $additionalParams = array("SuiviHebdomadaire2007:SuiviHebdomadaire2007:date"=>mysqlDateTodate(getDoubleArrayElement($rowsList,$i,"date")));
				 buildLink("","Consulter","$path/controler/ActionControler.php",$param->controler,ACTION_FIND,array(PARAM_VIEW),$additionalParams); 
			  ?>
	</td>
  </tr>
  <?php }?>
</table>



