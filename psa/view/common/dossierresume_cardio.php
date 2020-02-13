<?php global $dossier;?>
<?php global $currentObjectName;?>
<?php global $$currentObjectName;?>
<?php global $account;?>

<b>Dossier</b>
<table border="1">
  <tr>
    <th align="left" scope="row">&nbsp;Cabinet</th>
    <td >&nbsp;<?php typePropertyValue("account:cabinet"); ?></td>
  </tr>
  <tr>
    <th scope="row" align="left">&nbsp;N&deg; de dossier</th>
    <td>&nbsp;<?php echo($dossier->numero); ?></td>
  </tr>
  <tr>
    <th scope="row" align="left"><font  style=" border-bottom:solid  ; border-color:green ;   " >&nbsp;Sexe</font></th>
    <td><font  style=" border-bottom:solid  ; border-color:green ;   " >&nbsp;<?php echo($sexe[$dossier->sexe]); ?></font></td>
  </tr>
  <tr>
    <th scope="row" align="left">&nbsp;Né<?php echo($dossier->sexe=="M"?"":"e");?> le</th>
    <td>&nbsp;<?php echo($dossier->dnaiss); ?></td>
  </tr>
  <tr>
    <th scope="row" align="left"><font  style=" border-bottom:solid  ; border-color:green ;   " >&nbsp;Age</font></th>
    <td><font  style=" border-bottom:solid  ; border-color:green ;   " >&nbsp;<?php echo($dossier->getAge()); ?>&nbsp; ans</font></td>
  </tr>
  <tr>
    <th scope="row" align="left">&nbsp;Taille</th>
    <td>&nbsp;<?php echo($dossier->taille); ?>&nbsp;cm</td>
  </tr>
  <tr>
    <th scope="row" align="left">&nbsp;Statut</th>
    <td>&nbsp;<?php echo($actif[$dossier->actif]); ?></td>
  </tr>
  <?php if(!is_null($$currentObjectName)){?>
  <tr>
    <th scope="row" align="left">&nbsp;Dépistage du</th>
    <td>&nbsp;<?php echo($$currentObjectName->date);?></td>
  </tr>
  <?php }?>
  
</table>

<br><br>
