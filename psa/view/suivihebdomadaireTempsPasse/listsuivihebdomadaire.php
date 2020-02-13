<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php require_once("tools/date.php"); ?>
<?php require_once("tools/arrays.php"); ?>

<?php global $account; ?>
<?php global $rowsList; ?>
<?php global $param; ?>
<?php global $SuiviHebdomadaireTempsPasse; ?>

<p>Le suivi hebdomadaire du temps pass&eacute; vous permet de voir la r&eacute;partition en minutes du temps pass&eacute; par types d'activit&eacute;s.<br/>

Ce suivi calcule automatiquement la dur&eacute;e de chaque type de consultation &agrave; partir des dur&eacute;es de consultations que vous avez d&eacute;clar&eacute;es dans les diff&eacute;rents comptes-rendus de consultation. Est &eacute;galement calcul&eacute; automatiquement un temps forfaitaire de pr&eacute;paration et de bilan des consultations.<br/><br/>

Il vous appartient de compl&eacute;ter les autres rubriques concernant par exemple la gestion des dossiers patients, l'auto formation, etc.</p><br/>

<table width="75%"  border="1" cellspacing="0" cellpadding="0">
 
  <CAPTION  ><?php echo(count($rowsList)) ?> enregistrements trouv&eacute;s</CAPTION>
  
  <tr>
    <th scope="col">&nbsp;Semaine commen&ccedil;ant le</th>
    <th scope="col">&nbsp;Consulter</th>
  </tr>


  <?php foreach($rowsList as $list){ ?>
  <tr>
    <td>&nbsp;<?php echo $list; ?></td>  
    <td>&nbsp;<?php
         $additionalParams = array("SuiviHebdomadaireTempsPasse:SuiviHebdomadaireTempsPasse:date"=>$list);
         buildLink("","Consulter","$path/controler/ActionControler.php",$param->controler,ACTION_FIND,array(PARAM_EDIT),$additionalParams); 
        ?>
  </td>
  </tr>
  <?php }?>


<!--   <?php for($i=0;$i<count($rowsList);$i++){ ?>
  <tr>
    <td>&nbsp;<?php echo(mysqlDateTodate(getDoubleArrayElement($rowsList,$i,"date"))); ?></td>	
    <td>&nbsp;<?php
				$additionalParams = array("SuiviHebdomadaireTempsPasse:SuiviHebdomadaireTempsPasse:date"=>mysqlDateTodate(getDoubleArrayElement($rowsList,$i,"date")));
				 buildLink("","Consulter","$path/controler/ActionControler.php",$param->controler,ACTION_FIND,array(PARAM_EDIT),$additionalParams); 
			  ?>
	</td>
  </tr>
  <?php }?>  -->
</table>



