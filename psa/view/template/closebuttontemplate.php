<?php require_once("view/common/vars.php") ?>
<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php global $map ?>

<html>
<head>
<title><?php echo($map["pageTitle"]); ?> </title>
<link href='<?php echo("$path/view/css/main.css") ?>' rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

</head>

<body>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th height="49" scope="col">	 &nbsp;
	 <a href="javascript:history.back()"> </a>
	</th>
    <th scope="col">&nbsp;</th>
    <th scope="col"><input type="button" value="Fermer" onclick="window.close()"></th>
  </tr>
  <tr>
    <td scope="col">&nbsp;</td>
    <td scope="col" class="bodyTitle"><?php echo($map["bodyTitle"]) ?></td>
    <td scope="col">&nbsp;</td>
  </tr>
  <tr>
    <td scope="col">&nbsp;</td>
    <td scope="col">&nbsp;</td>
    <td scope="col">&nbsp;</td>
  </tr>
  <tr>
    <td scope="col">&nbsp;</td>
    <td scope="col"><?php require($map["body"]); ?></td>
    <td scope="col">&nbsp;</td>
  </tr>
  <tr>
    <td scope="col">&nbsp;</td>
    <td scope="col">&nbsp;</td>
    <td scope="col">&nbsp;</td>
  </tr>
  <tr>
    <td width="16%" scope="col">&nbsp;</td>
    <td width="68%" scope="col">
	<div class="errorMessage">
		<?php if(is_array($_REQUEST["REQUEST_MESSAGE"])) {
			echo (implode("<br>",$_REQUEST["REQUEST_MESSAGE"]));
		} 
		else echo($_REQUEST["REQUEST_MESSAGE"]); ?>
	</div>
	</td>
    <td width="16%" scope="col">&nbsp;</td>
  </tr>
</table>

	
</body>
</html>

