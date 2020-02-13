<?php global $dossier;?>
<?php global $currentObjectName;?>
<?php global $$currentObjectName;?>
<?php global $account;?>

  <b>Dossier</b>
  <table border='1'>
  	<tr>
	  	<td>Numéro de dossier</td>
		  	<td width='50'><?php echo($dossier->numero); ?></td>
		  		<td width='10'></td>
		<td>Nom</Td>
			<td width='100'></Td>
		  		<td width='10'></td>
		<td>Prénom</td>
			<td width='100'></td>
		  		<td width='10'></td>
		<td>Age</td>
			<td width='50'><?php echo($dossier->getAge()); ?></td>
		  		<td width='10'></td>
		<td>Sexe</td>
			<td width='50'><?php echo($sexe[$dossier->sexe]); ?></td>
		  		<td width='10'></td>
		<td>Date de naissance</td>
			<td width='50'><?php echo($dossier->dnaiss); ?></td>
	</tr>
  </Table>

<!--<table border="1">
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
    <td>&nbsp;<?php echo($sexe[$dossier->sexe]); ?></td>
  </tr>
  <tr>
    <th scope="row" align="left">&nbsp;Né<?php echo($dossier->sexe=="M"?"":"e");?> le</th>
    <td>&nbsp;<?php echo($dossier->dnaiss); ?></td>
  </tr>
  <tr>
    <th scope="row" align="left">&nbsp;Age</th>
    <td>&nbsp;<?php echo($dossier->getAge()); ?>&nbsp; ans</td>
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
-->
<br><br>
