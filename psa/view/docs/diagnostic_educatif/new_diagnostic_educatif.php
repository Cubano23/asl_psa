<h1><?php echo $number;?>. Diagnostic �ducatif</h1>

<?php 
hidden("","diagnosticEducatif:id_dossier"); 
#$diagnostic_educatif = get_object_vars($diagnostic_educatif);

?>
 <table width='880' border="1" cellpadding='3'>
    <tr>
      <td valign='top'><b>Aspects limitants :</b> <br>Peur, croyance, repr�sentation, environnement, habitudes de vie...</td>
      <td  width='70%' colspan='2'><?php textArea("rows=\"8\" cols=\"60\"","diagnosticEducatif:aspects_limitants"); ?></td>
	</Tr>
    <tr>
      <td valign='top'><b>Aspects facilitants :</b> <br>Ressources pour y arriver (humaine ou mat�rielles)</td>
      <td  width='70%' colspan='2'><?php textArea("rows=\"8\" cols=\"60\"","diagnosticEducatif:aspects_facilitants"); ?></td>
	</Tr>
    <tr>
      <td valign='top'><b>Objectifs du patient :</b> <br>Comment la personne imagine son arret</td>
      <td  width='70%' colspan='2'><?php textArea("rows=\"8\" cols=\"60\"","diagnosticEducatif:objectifs_patient"); ?></td>
	</Tr>
	</table>