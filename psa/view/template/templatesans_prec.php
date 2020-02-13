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
    <th height="49" scope="col">
    <?php /*<a href="http://localhost/psa/bienvenue.php"><img src='<?php echo $path; ?>/view/images/maison.gif' alt="retour à l'accueil" border='0'></a>*/?>
	 <?php /* buildLink("","<img src='$path/view/images/maison.gif' alt=\"retour à l'accueil\" border='0'>","$path/controler/ActionControler.php","UtilityControler",ACTION_MAIN); */ ?>&nbsp;
	 
	</th>
    <th scope="col"><span class="headerTitle">Asal&eacute;e</span></th>
    <th scope="col"><a href="javascript:window.close()"><img src="<?php echo $path; ?>/view/images/close.gif" alt="Fermer la fenêtre" border=0 alt="fermer" width=13 height=12></a>
					<img src="<?php echo $path; ?>/view/images/inf79.gif" alt="logo informed79"><br>
      &nbsp;&nbsp;<a href="mailto:contact@asalee.fr"><font size="-1">contact</font></a></th>
  </tr>
  <tr>
    <td scope="col">&nbsp;</td>
    <td scope="col" class="bodyTitle"><?php echo($map["bodyTitle"])."<br><font size='-1'><i>".
											$_SESSION['account']->cabinet."</i></font>"; ?></td>
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

