<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $FicheCabinet; ?>
<?php global $param;?>


<br> 

	
  <b>Renseignements administratifs1</b>
  <table border=1> 
    <tr> 
      <td>Cabinet</td>
      <td><?php typePropertyValue("FicheCabinet:cabinet"); ?></td>
	</tr>
	<tr>
      <td>Nom complet</td>
      <td><?php typePropertyValue("FicheCabinet:nom_complet"); ?></td>
	</tr>
	<tr>
      <td>Ville</td>
      <td><?php typePropertyValue("FicheCabinet:ville"); ?></td>
	</tr>
	<tr>
      <td>Contact</td>
      <td><?php typePropertyValue("FicheCabinet:contact"); ?></td>
	</tr>
	<tr>
      <td>Téléphone</td>
      <td><?php typePropertyValue("FicheCabinet:telephone"); ?></td>
	</tr>
	<tr>
      <td>Courriel</td>
      <td><?php typePropertyValue("FicheCabinet:courriel"); ?></td>
	</tr>
  </table>
	<br>
  <b>Informations sur le cabinet</b><br>
  <table border=1>
    <tr> 
      <td>Nombre total de patients</td>
      <td><?php typePropertyValue("FicheCabinet:total_pat"); ?></td>
    </tr>
    <tr>
      <td>Nombre total de patientes éligibles pour le cancer du sein</td>
      <td><?php typePropertyValue("FicheCabinet:total_sein"); ?></td>
    </tr>
    <tr>
      <td>Nombre total de patients éligibles pour les troubles cognitifs</td>
      <td><?php typePropertyValue("FicheCabinet:total_cogni"); ?></td>
    </tr>
    <tr>
      <td>Nombre total de patients éligibles pour le cancer du colon</td>
      <td><?php typePropertyValue("FicheCabinet:total_colon"); ?></td>
    </tr>
    <tr>
      <td>Nombre total de patientes éligibles pour le cancer de l'utérus</td>
      <td><?php typePropertyValue("FicheCabinet:total_uterus"); ?></td>
    </tr>
    <tr>
      <td>Nombre total de patients diabétiques de type 2</td>
      <td><?php typePropertyValue("FicheCabinet:total_diab2"); ?></td>
    </tr>
    <tr>
      <td>Nombre total de patients éligibles au suivi RCVA</td>
      <td><?php typePropertyValue("FicheCabinet:total_HTA"); ?></td>
    </tr>
  </table>

  <br> 

