<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php require_once("tools/date.php"); ?>
<?php require_once("tools/arrays.php"); ?>

<?php global $account; ?>
<?php global $rowsList; ?>
<?php global $param; ?>
<?php global $currentObjectName; ?>
<?php global $currentObjectClass; ?>
<?php global $signature; ?>


<div style="float:right;color:#fff;"><?php echo "<a style='color:#fff;' title='fermer' href='javascript://' onclick=\"ajax_hideTooltip()\">X</a><br>";?></div>
<table width="100%"  border="1" cellspacing="0" cellpadding="0">
 
  <CAPTION  ><?php echo(count($rowsList)) ?> enregistrements trouv&eacute;s</CAPTION>
  
  <tr>
    <th scope="col">&nbsp;Date consultation</th>
	<th scope="col">&nbsp;Aspects limitant</th>	
	<th scope="col">&nbsp;Aspects facilitant</th>	
	<th scope="col">&nbsp;Objectifs patient</th>	
  </tr>
  <?php for($i=0;$i<count($rowsList);$i++){ ?>
  <tr>
    <td>&nbsp;<?php echo(mysqlDateTodate(getDoubleArrayElement($rowsList,$i,"date"))); ?></td>	
	<td>&nbsp;<?php echo htmlentities(getDoubleArrayElement($rowsList,$i,"aspects_limitant")); ?></td>
	<td>&nbsp;<?php echo htmlentities(getDoubleArrayElement($rowsList,$i,"aspects_facilitant")); ?></td>
	<td>&nbsp;<?php echo htmlentities(getDoubleArrayElement($rowsList,$i,"objectifs_patient")); ?></td>
  </tr>
  <?php }?>
</table>



