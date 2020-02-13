
<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>




<form action="#" method="post" name="manage">

  
Ce formulaire permet de gérer un groupe lors des consultations collectives<br><br>



<table border="1" cellspacing="1" width="95%">
  <tr><td>
    <center><h1><u>Consultations Collectives</u></h1></center><br>&nbsp;
    <table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>Cabinet</td>
        <td>&nbsp;<?php typePropertyValue("account:cabinet"); ?></td>
      </tr>
      <tr>
        <td>Nom du groupe</td>
        <td>&nbsp;<?php text("size='10'","dossier:numero"); ?></td>
      </tr>
  	<tr>
        <td>Date de l'évaluation </td>	  
        <td>&nbsp;<?php text("size='10' onkeyup='formate_date(this)'","$currentObjectName:date"); ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
     
    </table>
  </td>
  
</tr>
</table>
<br>




</form>

