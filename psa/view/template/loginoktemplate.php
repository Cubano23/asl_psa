<?php require_once("view/common/vars.php") ?>
<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php global $map ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title><?php echo($map["pageTitle"]); ?></title>
    <meta name="author" content="Informed79 Services SAS">
    <meta name="keywords" content="PSA,Portail Services Asalée,Informed79 Services SAS">
    <meta name="description" content="Portail Services Asalée, &agrave; votre service pour &ecirc;tre au service de vos patients.">
    <meta name="robots" content="noindex,nofollow">
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
<script type="text/javascript" src="<?php echo $path;?>/view/login/js/menu_data2.js"></script>
<!-- PAGE -->
<div align="center">
    <div id="page">
        <!-- ZONE IDENTITAIRE | Header -->
        <div id="header" style='margin-left:-8px;'><!--806-->
            <table width="929" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="355"><a href="index.php" title="Retour &agrave; l'accueil du Portail Services Asalée"><img src="<?php echo $path;?>/view/login/img/habillage/header_psa.gif" alt="Portail Services Asal&eacute;e" width="355" height="130" border="0"></a></td>
                    <!--441--><td width="564" align="right" bgcolor="white"><img src="<?php echo $path;?>/view/login/img/logos/LogoAsalee2013.png" alt="Services propos&eacute; par&hellip;" width="190" height="68" hspace="20" vspace="0"></td>
                    <td width="10"><img src="<?php echo $path;?>/view/login/img/habillage/header_right.gif" width="10" height="130"></td>
                </tr>
            </table>
        </div>
        <!-- NAVIGATION -->

        <div class='margin-left:-3px;'><?php require_once('view/login/menu.php');?></div>
        <!-- <div id="navigation"> -->
        <!--798-->			<!-- <table width="921" border="0" cellspacing="0" cellpadding="0">
            	<tr>
            		<td background="<?php echo $path;?>/view/login/img/habillage/pattern_left.gif"></td> -->
        <!--778-->            		<!-- <td bgcolor="white"><img src="<?php echo $path;?>/view/login/img/habillage/gray_top.gif" width="901" height="11"></td>
            		<td width="10" rowspan="3"><img src="<?php echo $path;?>/view/login/img/habillage/right_top_nav.gif" width="10" height="63"></td>
           		</tr>
            	<tr>
            		<td background="<?php echo $path;?>/view/login/img/habillage/pattern_left.gif"></td>
            		<td align="left" bgcolor="white"> -->
        <!-- Script de description du menu -->
        <!-- <script type="text/javascript" src="<?php echo $path;?>/view/login/js/menu_data.js"></script> -->
        <script>
            // with(milonic=new menuname("Main Menu")){
            // 	alwaysvisible=1;
            // 	position="relative";
            // 	left=200;
            // 	top=155;
            // 	style=AllImagesStyle;
            // 	orientation="horizontal";
            // 	overfilter="";
            // 	aI("image=<?php echo $path;?>/view/login/img/navigation/serv_patients.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_patients_over.gif;showmenu=Patients;");
            // 	aI("image=<?php echo $path;?>/view/login/img/navigation/serv_diabete.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_diabete_over.gif;showmenu=Diabete;");
            // 	aI("image=<?php echo $path;?>/view/login/img/navigation/serv_rcva.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_rcva_over.gif;showmenu=RCVA;");
            // 	aI("image=<?php echo $path;?>/view/login/img/navigation/serv_cancer.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_cancer_over.gif;showmenu=Cancer;");
            // 	aI("image=<?php echo $path;?>/view/login/img/navigation/serv_cognitifs.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_cognitifs_over.gif;showmenu=Cognitifs;");
            // 	aI("image=<?php echo $path;?>/view/login/img/navigation/serv_evaluation.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_evaluation_over.gif;showmenu=Evaluation;");
            // }
            // drawMenus();
        </script>
        <!-- </td>
           		</tr>
            	<tr>
            		<td width="10" background="<?php echo $path;?>/view/login/img/habillage/pattern_left.gif"></td> -->
        <!--778-->           		<!-- <td width="901" bgcolor="white"><img src="<?php echo $path;?>/view/login/img/habillage/gray_bottom.gif" width="901" height="12"></td>
           		</tr>
			</table>
		</div> -->
        <!-- CONTENU -->
        <div id="contenu">
            <!--798-->			<table width="921" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="10" valign="bottom" background="<?php echo $path;?>/view/login/img/habillage/pattern_left.gif"><img src="<?php echo $path;?>/view/login/img/habillage/left_bottom.gif" width="10" height="416"></td>
                    <!--778-->					<td width="901" valign="top" bgcolor="white">
                        <div class="connexe">
                            <div class="login">
                                <?php require($map["body"]); ?>
                            </div>
                            <!--778-->						<div class="footer"><img src="<?php echo $path;?>/view/login/img/habillage/gray_top.gif" width="901" height="11"><br>
                                &copy;2005 Informed79 Services SAS <span>|</span> <a href="#" title="Consultez les informations juridiques">Informations juridiques</a> <span>|</span> Services propos&eacute;s par Informed79 SAS <a href="#" title="Consultez le site de la G&eacute;n&eacute;rale de Sant&eacute;"></a>
                            </div>
                    </td>
                    <td width="10" valign="top">
                        <img src="<?php echo $path;?>/view/login/img/habillage/right_top.gif" width="10" height="473">
                    </td>
                </tr>
                <tr>
                    <!--798-->					<td colspan="3"><img src="<?php echo $path;?>/view/login/img/habillage/bottom.gif" width="921" height="10"></td>
                </tr>
            </table>
        </div>
    </div>
</div>
</body>
</html>
