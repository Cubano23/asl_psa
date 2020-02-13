<h1><?php echo $number;?>. Diagnostic éducatif</h1>

<?php 

#$diagnostic_educatif = get_object_vars($diagnostic_educatif);

#var_dump($diagnosticEducatif);
?>
 <table width='880' border="1" cellpadding='3'>
    <tr>
      <td valign='top'><b>Aspects limitants :</b> <br>Peur, croyance, représentation, environnement, habitudes de vie...</td>
      <td  width='70%' colspan='2'><?php echo nl2br(stripslashes($diagnosticEducatif->aspects_limitants)); ?></td>
	</Tr>
    <tr>
      <td valign='top'><b>Aspects facilitants :</b> <br>Ressources pour y arriver (humaine ou matérielles)</td>
      <td  width='70%' colspan='2'><?php echo nl2br(stripslashes($diagnosticEducatif->aspects_facilitants)); ?></td>
	</Tr>
    <tr>
      <td valign='top'><b>Objectifs du patient :</b> <br>Comment la personne imagine son arret</td>
      <td  width='70%' colspan='2'><?php echo nl2br(stripslashes($diagnosticEducatif->objectifs_patient)); ?></td>
	</Tr>
	</table>