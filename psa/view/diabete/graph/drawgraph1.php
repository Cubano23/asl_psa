<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php global $image; ?>

<table width="40%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th scope="row">&nbsp;</th>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th scope="row">&nbsp;</th>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th scope="row"><img src="<?php echo(getLink("$path/controler/ActionControler.php","HBAGraphControler",ACTION_GRAPH,array(PARAM_GRAPH1),$image)); ?>"></th>
    <td>&nbsp;</td>
  </tr>
</table>

