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
<!-- Script pilotant la navigation -->
<script type="text/javascript" src="<?php echo $path;?>/view/login/js/milonic_src.js"></script>
<script	type="text/javascript">
if(ns4)_d.write("<scr"+"ipt type=text/javascript src=<?php echo $path;?>/view/login/js/mmenuns4.js><\/scr"+"ipt>");
  else _d.write("<scr"+"ipt type=text/javascript src=<?php echo $path;?>/view/login/js/mmenudom.js><\/scr"+"ipt>");
</script>
<script type="text/javascript" src="<?php echo $path;?>/view/login/js/menu_data.js"></script>
<!-- PAGE -->
<div align="center">
	<div id="page">
<!-- ZONE IDENTITAIRE | Header -->
		<div id="header">
			<table width="806" border="0" cellspacing="0" cellpadding="0">
            	<tr>
            		<td width="355"><a href="<?php echo $path."/";?>" title="Retour &agrave; l'accueil du Portail Services Vigilance"><img src="<?php echo $path;?>/view/login/img/habillage/header_psv.gif" alt="Portail Services Vigilance" width="355" height="130" border="0"></a></td>
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
            		<td align="left" bgcolor="white">
					<!-- Script de description du menu -->
					 <script type="text/javascript" src="<?php echo $path;?>/view/login/js/menu_data.js"></script>
					<script>
					with(milonic=new menuname("Main Menu")){
						alwaysvisible=1;
						position="relative";
						left=200;
						top=155;
						style=AllImagesStyle;
						orientation="horizontal";
						overfilter="";
						<?php
						if($_SESSION['account']->portail=='1'){?>
							aI("image=<?php echo $path;?>/view/login/img/navigation/serv_beneficiaires.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_beneficiaires_over.gif;showmenu=Beneficiaires;");
							aI("image=<?php echo $path;?>/view/login/img/navigation/serv_signalement.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_signalement_over.gif;showmenu=Signalement;");
							aI("image=<?php echo $path;?>/view/login/img/navigation/serv_alerte.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_alerte_over.gif;showmenu=Alerte;");
							aI("image=<?php echo $path;?>/view/login/img/navigation/serv_relation.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_relation_over.gif;showmenu=Relation;");
							<?php
							
							if($_SESSION['account']->structure=='0'){?>
								aI("image=<?php echo $path;?>/view/login/img/navigation/serv_administration.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_administration_over.gif;showmenu=Administration_pro;");
								<?php
							}
							else{?>
								aI("image=<?php echo $path;?>/view/login/img/navigation/serv_administration.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_administration_over.gif;showmenu=Administration_struct;");
<?							}?>
						<?php
						}
						else{?>
							aI("image=<?php echo $path;?>/view/login/img/navigation/serv_beneficiaires.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_beneficiaires_over.gif;showmenu=Beneficiaires_mini;");
							aI("image=<?php echo $path;?>/view/login/img/navigation/serv_signalement.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_signalement_over.gif;showmenu=Signalement_mini;");
							aI("image=<?php echo $path;?>/view/login/img/navigation/serv_alerte.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_alerte_over.gif;showmenu=Alerte_mini;");
							aI("image=<?php echo $path;?>/view/login/img/navigation/serv_relation.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_relation_over.gif;showmenu=Relation_mini;");
							aI("image=<?php echo $path;?>/view/login/img/navigation/serv_administration.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_administration_over.gif;showmenu=Administration_mini;");
						<?php
						}?>
					}
					drawMenus();
					</script>
					</td>
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
					<td width="10" valign="bottom" background="<?php echo $path;?>/view/login/img/habillage/pattern_left.gif"><img src="<?php echo $path;?>/view/login/img/habillage/left_bottom.gif" width="10" height="416"></td>
					<td width="778" valign="top" bgcolor="white">
						<div class="connexe">
							<div class="login">
							<?php require($map["body"]); ?>
</div>
						<div class="footer"><img src="<?php echo $path;?>/view/login/img/habillage/gray_top.gif" width="778" height="11"><br>
							&copy;2005 ISAS <span>|</span> <a href="#" title="Consultez les informations juridiques">Informations juridiques</a> <span>|</span> Services propos&eacute;s par Isas <a href="#" title="Consultez le site de la G&eacute;n&eacute;rale de Sant&eacute;"></a>
						</div>
					</td>
					<td width="10" valign="top">
						<img src="<?php echo $path;?>/view/login/img/habillage/right_top.gif" width="10" height="473">
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

