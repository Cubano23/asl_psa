<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account ?>
<?php global $url_depart ?>
<?php global $parametre_redirect;?>
<?php $controler_redirect=$parametre_redirect->controler;
	  $action_redirect=$parametre_redirect->action;
	  $param1_redirect=$parametre_redirect->param1;
	  $param2_redirect=$parametre_redirect->param2;
	  $param3_redirect=$parametre_redirect->param3;

?>
<form action="<?php echo("$path/controler/LoginControler.php"); ?>" method="post" name="manage">
<?php hiddenControler("LoginControler"); ?>
<?php hiddenAction(ACTION_FIND); ?>
<?php echo "<input type='hidden' name='controler_redirect' value='$controler_redirect'>";?>
<?php echo "<input type='hidden' name='action_redirect' value='$action_redirect'>";?>
<?php echo "<input type='hidden' name='param1_redirect' value='$param1_redirect'>";?>
<?php echo "<input type='hidden' name='param2_redirect' value='$param2_redirect'>";?>
<?php echo "<input type='hidden' name='param3_redirect' value='$param3_redirect'>";?>

	<table width="40%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th scope="row">Nom de cabinet </th>	
    <td>&nbsp;<?php  selectv("","account:cabinet",$cabinets) ?></td>
  </tr>
  <tr>
    <th scope="row">Mot de passe </th>
    <td>&nbsp;<?php password("","account:password"); ?></td>
  </tr>
  <tr>
    <th scope="row">&nbsp;</th>
    <td><input type="submit" name="Ok" value="Valider"></td>
  </tr>
</table>

</form>
