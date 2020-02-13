<?php global $dossier;?>
<?php global $currentObjectName;?>
<?php global $$currentObjectName;?>
<?php global $account;?>

<table border="1">
	<caption> Dossier</caption>
  <tr>
    <th align="left" scope="row">&nbsp;Cabinet</th>
    <td >&nbsp;<?php typePropertyValue("account:cabinet"); ?></td>
  </tr>
  <tr>
    <th scope="row" align="left">&nbsp;N&deg; de dossier</th>
    <td>&nbsp;<?php echo($dossier->numero); ?></td>
  </tr>
  <tr>
    <th scope="row" align="left">&nbsp;Sexe</th>
      <td><?php selectv("","dossier:sexe",$sexe); ?></td>
  </tr>
  <tr>
    <th scope="row" align="left">&nbsp;Né<?php echo($dossier->sexe=="M"?"":"e");?> le</th>
      <td><?php text("size='10' onkeyup='formate_date(this)'","dossier:dnaiss"); ?></td>
  </tr>
  <tr>
    <th scope="row" align="left">&nbsp;Age</th>
    <td>&nbsp;<?php echo($dossier->getAge()); ?>&nbsp; ans</td>
  </tr>
  <tr>
    <th scope="row" align="left">&nbsp;Taille</th>
      <td><?php text("size='3'","dossier:taille"); ?>&nbsp; cm</td>
  </tr>
  <tr>
    <th scope="row" align="left">&nbsp;Statut</th>
      <td><?php selectv("","dossier:actif",$actif); ?></td>
  </tr>
  <tr>
  <th scope="row" align="left">Date de consentement</th>
    <td><?php text("size='10' onkeyup='formate_date(this)'","dossier:dconsentement"); ?></td>
  </tr>
  <?php if(!is_null($$currentObjectName)){?>
  <tr>
    <th scope="row" align="left">&nbsp;Dépistage du</th>
    <td>&nbsp;<?php echo($$currentObjectName->date);?></td>
  </tr>
  <?php }?>
  
</table>

<br><br>
