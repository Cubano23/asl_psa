<br> 
<table border='1' rules='none' width='70%'> 
  <caption> 
  <big><b>Objectifs atteints:</b></big> 
  </caption> 
  <tr> 
    <td><?php checkBox("id='OBJ_equilib'","suiviDiabete:equilib","1"); ?>
      diabète équilibré</td> 
    <td valign='top'><?php checkBox("id='OBJ_tension'","suiviDiabete:tension","1"); ?>
      objectif tensionnel (135/80)</td> 
    <td>&nbsp; <?php checkBox("id='OBJ_lipide'","suiviDiabete:lipide","1");?>objectif lipidique</td> 
  </tr> 
</table>
<br> 
<br> 
<table border='1' width='70%'> 
  <caption> 
  <big><b>Mesures à prendre par le médecin:</b></big> 
  </caption> 
  <tr> 
    <td><?php checkBox("","suiviDiabete:mesure_ADO","1"); ?>
      Modification traitement antidiabétiques oraux</td> 
    <td><?php checkBox("","suiviDiabete:insuline","1"); ?>
      Modification ou mise à l'insuline</td> 
  </tr> 
  <td><?php checkBox("","suiviDiabete:mesure_hta","1"); ?>
      Correction HTA</td> 
    <td><?php checkBox("","suiviDiabete:hypl","1"); ?>
      Prise en charge hyperlipidémie</td> 
  </tr> </table>
<br> 
<br> 
<table border='1' rules='none' width='70%'> 
  <caption> 
  <big><b>Coaching infirmière:</b></big> 
  </caption> 
  <tr> 
    <td><?php checkBox("","suiviDiabete:phys","1"); ?>
      Exercice physique</td> 
    <td><?php checkBox("","suiviDiabete:diet","1"); ?>
      Mesures diététiques</td> 
  </tr> 
  <tr> 
    <td><?php checkBox("","suiviDiabete:taba","1"); ?>
      Arrêt du tabac</td> 
    <td><?php checkBox("","suiviDiabete:etp","1"); ?>
      ETP de groupe</td> 
  </tr> 
</table>
<br>
<table border='1' rules='none' width='70%'> 
<tr>
	<td>Date de début du diabète (format mm/aaaa)</td>
      <td><?php text("id='date_debut' size='7' maxlength='7'","suiviDiabete:date_debut"); ?></td>
</tr>
<tr>
	<td>Diabète supérieur à 10 ans</td>
      <td><?php checkbox("","suiviDiabete:diab10ans","1"); ?></td>
</tr>
<tr>
	<td>
<?php checkBox("","suiviDiabete:sortie","1"); ?></td>
      <td>Sortir cette personne du suivi diabète</td>
</tr>
</table> 
</body></html>
