<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php require_once("tools/date.php"); ?>
<?php require_once("tools/arrays.php"); ?>

<?php global $account; ?>
<?php global $rowsList; ?>
<?php global $param; ?>


  <script language="JavaScript" type="text/javascript">

function affiche_detail(element){
	var element=document.getElementById(element);

	if(element.style.display=='none')
	{
		element.style.display='';
	}
	else
	{
	    element.style.display='none';
	}

}

</SCRIPT>


<table width="75%"  border="1" cellspacing="0" cellpadding="0">
<?php
if(($rowsList=="")||($rowsList==false)){
	$nb=0;
}
else{
	$nb=count($rowsList);
}
?>
  <CAPTION  ><?php echo($nb); ?> enregistrements trouvés</CAPTION>

  <tr>
    <th scope="col">&nbsp;Date</th>
    <th scope="col">&nbsp;Cabinet</th>
    <th scope="col">&nbsp;Infirmière</th>
    <th scope="col">&nbsp;Sujet</th>
    <th scope="col">&nbsp;Consulter</th>
  </tr>

  <?php
	if(($rowsList!="")&&($rowsList!=false)){
	  for($i=0;$i<count($rowsList);$i++){ ?>
	  <tr>
	    <td>&nbsp;<?php echo(mysqlDateTodate(getDoubleArrayElement($rowsList,$i,"date"))); ?></td>
	    <td>&nbsp;<?php echo(getDoubleArrayElement($rowsList,$i,"cabinet")); ?></td>
	    <td>&nbsp;<?php echo(getDoubleArrayElement($rowsList,$i,"infirmiere")); ?></td>
	    <td>&nbsp;<?php echo(getDoubleArrayElement($rowsList,$i,"titre")); ?></td>
	    <td>&nbsp;<a href='#<?php echo getDoubleArrayElement($rowsList,$i,"id"); ?>'
				onclick="affiche_detail('<?php echo getDoubleArrayElement($rowsList,$i,"id"); ?>')">
				Afficher/masquer les détails</a>
		  <tr style="display:none" id="<?php echo getDoubleArrayElement($rowsList,$i,'id'); ?>">
		  <td>&nbsp;Question : </td>
		    <td colspan='4'>&nbsp;<?php echo(getDoubleArrayElement($rowsList,$i,"corps")); ?></td>
		 </tr>
	  <?php }
	}?>
</table>



