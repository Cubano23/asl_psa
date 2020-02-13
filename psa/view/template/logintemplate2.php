<?php require_once("view/common/vars.php") ?>
<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php global $map ?>

 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo($map["pageTitle"]); ?></title>
<meta name="author" content="ISAS">
<meta name="keywords" content="PSP,Portail Services Praticiens,ISAS,GDS,G&eacute;n&eacute;rale de Sant&eacute;">
<meta name="description" content="Portail Services Praticiens, &agrave; votre service pour &ecirc;tre au service de vos patients.">
<meta name="robots" content="noindex,nofollow">
<link href="<?php echo $path;?>/view/login/css/psp.css" rel="stylesheet" type="text/css">
<link href="<?php echo $path;?>/view/login/css/psp.css" rel="stylesheet" type="text/css">
<link href="<?php echo $path;?>/view/login/css/psp_nicetitle.css" rel="stylesheet" type="text/css">
</head>
<body>
<!-- PAGE -->
<div align="center">
	<div id="page">
<!-- ZONE IDENTITAIRE | Header -->
		<div id="header">
			<table width="806" border="0" cellspacing="0" cellpadding="0">
            	<tr>
            		<td width="355"><img src="<?php echo $path;?>/view/login/img/habillage/header_psv.gif" alt="Portail Services Vigilance" width="355" height="130" border="0"></td>
            		<td width="441" align="right" bgcolor="white"><!--<img src="<?php echo $path;?>/view/login/img/logos/logo.gif" alt="Services propos&eacute; par&hellip;" width="142" height="71" hspace="20" vspace="0">--></td>
            		<td width="10"><img src="<?php echo $path;?>/view/login/img/habillage/header_right.gif" width="10" height="130"></td>
           		</tr>
           	</table>
		</div>
<!-- NAVIGATION -->
		<div id="navigation">
			<table width="798" border="0" cellspacing="0" cellpadding="0">
            	<tr>
            		<td background="<?php echo $path;?>/view/login/img/habillage/pattern_left.gif"></td>
            		<td bgcolor="white"><img src="<?php echo $path;?>/view/login/img/habillage/gray_top.gif" width="778" height="11"></td>
            		<td width="10" rowspan="3"><img src="<?php echo $path;?>/view/login/img/habillage/right_top_nav.gif" width="10" height="63"></td>
           		</tr>
            	<tr>
            		<td background="<?php echo $path;?>/view/login/img/habillage/pattern_left.gif"></td>
            		<td align="left" bgcolor="white"><img src="<?php echo $path;?>/view/login/img/habillage/serv_login.gif" alt="Afin de consulter et d'utiliser le PSV, veuillez vous identifier &agrave; l'aide de votre acc&egrave;s personnalis&eacute;" width="778" height="40"></td>
           		</tr>
            	<tr>
            		<td width="10" background="<?php echo $path;?>/view/login/img/habillage/pattern_left.gif"></td>
            		<td width="778" bgcolor="white"><img src="<?php echo $path;?>/view/login/img/habillage/gray_bottom.gif" width="778" height="12"></td>
           		</tr>
			</table>
		</div>
<!-- CONTENU -->
		<div id="contenu">
			<table width="798" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="10" valign="bottom" background="<?php echo $path;?>/view/login/img/habillage/left_bottom.gif" style="background-position:bottom;">&nbsp;</td>
					<td width="778" valign="top" bgcolor="white">
					<div class="connexe">
						<div class="login">
							<h1><img src="<?php echo $path;?>/view/login/img/titres/acces_personnel.gif" alt="Acc&egrave;s personnalis&eacute;" width="265" height="20"></h1>
							<h2>Veuillez rentrer votre nom d'utilisateur et votre mot de passe.</h2>
							<p class="errorMessage"><?php echo($_REQUEST["REQUEST_MESSAGE"]); ?></p>
							<?php require($map["body"]); ?>
						</div>
					</div>
						<div class="mainlogin">
						<img src="<?php echo $path;?>/view/login/img/visuels/visuel_psv_01.jpg" alt="&Ecirc;tre vigilant pour &ecirc;tre au service de vos patients" width="480" height="157"></div>
						<div class="footer">
							<img src="<?php echo $path;?>/view/login/img/habillage/gray_top.gif" width="778" height="11"><br>
							&copy;2005 ISAS <span>|</span> <a href="#" title="Consultez les informations juridiques">Informations juridiques</a> <span>|</span> Services propos&eacute;s par Isas </div>
					</td>
					<td width="10" valign="top" background="<?php echo $path;?>/view/login/img/habillage/right_top.gif">&nbsp;
					</td>
				</tr>
				<tr>
					<td colspan="3"><img src="<?php echo $path;?>/view/login/img/habillage/bottom.gif" width="798" height="10"></td>
				</tr>
			</table>
		</div>
	</div>
</div>
</body>
</html>
