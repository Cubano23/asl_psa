<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php require_once("tools/date.php"); ?>
<?php require_once("tools/arrays.php"); ?>

<?php global $account; ?>
<?php global $rowsList; ?>
<?php global $param; ?>
<?php global $SuiviReunionMedecin; ?>



<table width="95%"  border="1" cellspacing="0" cellpadding="1">
 
  <CAPTION  ><?php echo(count($rowsList)) ?> enregistrements trouv&eacute;s</CAPTION>
  
  <tr>
    <!--<th scope="col">&nbsp;Enregistrement</th>-->
    <th scope="col" align="center" height="30">R&eacute;union du</th>
    <th scope="col">Medecin</th>
    <th scope="col">Infirmi&egrave;re</th>
    <th scope="col">Dur&eacute;e</th>
    <th scope="col">Motif</th>
    <th scope="col">Consulter</th>
  </tr>

<!--  <?php for($i=0;$i<count($rowsList);$i++){ ?>
  <tr>
    <td>&nbsp;<?php echo(mysqlDateTodate(getDoubleArrayElement($rowsList,$i,"date"))); ?></td>	
    <td>&nbsp;<?php
				 $additionalParams = array("SuiviReunionMedecin:date"=>mysqlDateTodate(getDoubleArrayElement($rowsList,$i,"date")));
				 buildLink("","Consulter","$path/controler/ActionControler.php",$param->controler,ACTION_FIND,array(PARAM_EDIT),$additionalParams); 
         // $additionalParams = array("SuiviHebdomadaireTempsPasse:SuiviHebdomadaireTempsPasse:date"=>$list);
         // buildLink("","Consulter","$path/controler/ActionControler.php",$param->controler,ACTION_FIND,array(PARAM_VIEW),$additionalParams); 
			  ?>
	</td>
  </tr>
  <?php }?>  -->


  <?php foreach($rowsList as $list){ 
    #var_dump($list);exit;
    ?>
  <tr>
    <!--<td>&nbsp;<?php echo mysqlDateTodate($list['date']); ?></td>  -->
    <td><?php echo mysqlDateTodate($list['date_reunion']); ?></td>  
    <td><?php echo $list['medecin'];?></td>
    <td><?php echo utf8_decode($list['infirmiere']);?></td>
    <td align="center"><?php echo $list['duree'];?></td>
    <td><?php echo $list['motif'];?></td>
    <td align="center">&nbsp;<?php
         $additionalParams = array("suiviReunionMedecin:SuiviReunionMedecin:date"=>mysqlDateTodate($list['date']));
         buildLink("","Consulter","$path/controler/ActionControler.php",$param->controler,ACTION_FIND,array(PARAM_VIEW),$additionalParams); 
        ?>
  </td>
  </tr>
  <?php }?>





</table>



