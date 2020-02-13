<?php require_once("view/common/vars.php") ?>
<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php global $map ?>

<?php
require_once ("Config.php");
$config = new Config();

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

    <!-- Pour la compatibilité IE - EDGE -->
    <meta http-equiv="X-UA-Compatible" content="IE-edge" />

    <title><?php echo($map["pageTitle"]); ?></title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="author" content="Informed79 Services SAS">
    <meta name="keywords" content="PSA,Portail Services Asal&eacute;,Informed79 Services SAS">
    <meta name="description" content="Portail Services Asal&eacute;e, &agrave; votre service pour &ecirc;tre au service de vos patients.">
    <meta name="robots" content="noindex,nofollow">

    <!-- ---FONTAWESOME--- -->    
    <!--
     <link rel="stylesheet" href="<?php echo $path.'/lib/fontawesome-free-5.6.3-web/css/fontawesome.min.css' ?>" >
     <link rel="stylesheet" href="<?php echo $path.'/lib/fontawesome-free-5.6.3-web/css/fontawesome.css' ?>" >
    -->
    <link rel="stylesheet" href="<?php echo $path.'/lib/fontawesome-free-5.6.3-web/css/all.min.css' ?>" >
    <link rel="stylesheet" href="<?php echo $path.'/view/activite_physique/css/style.css' ?>" >
    
    
    <link href="<?php echo $path;?>/view/login/css/psp2.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $path;?>/view/login/css/psp_nicetitle.css" rel="stylesheet" type="text/css">
    
    <script type="text/javascript" src="<?php echo $path;?>/view/login/_js/scripts-all.js"></script>
    <script type="text/javascript" src="<?php echo $path;?>/view/login/_js/menus2.js"></script>
    <script type="text/javascript">

        var GB_ROOT_DIR = "";

    </script>
    <script type="text/javascript" src="<?php echo $path;?>/view/login/_js/greybox/AJS.js"></script>
    <script type="text/javascript" src="<?php echo $path;?>/view/login/_js/greybox/AJS_fx.js"></script>
    <script type="text/javascript" src="<?php echo $path;?>/view/login/_js/greybox/gb_scripts.js"></script>
    <script type="text/javascript" src="<?php echo $path;?>/view/login/_js/dynCont/ajax.js"></script>
    <script type="text/javascript" src="<?php echo $path;?>/view/login/_js/tooltip/ajax-dynamic-content.js"></script>
    <script type="text/javascript" src="<?php echo $path;?>/view/login/_js/tooltip/ajax-tooltip2.js?201703281016"></script>
    <link href="<?php echo $path;?>/view/login/_css/tooltip.css?20170328" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?php echo $path;?>/view/login/_js/multi_content.js"></script>
</head>

<?php
if($_SERVER['HTTP_HOST'] == 'psatest.asalee.fr') {
    $bodycolor = 'style="background-color:green"';
}
?>
<body <?php echo $bodycolor;?>>
<!-- Script pilotant la navigation -->
<script type="text/javascript" src="<?php echo $path;?>/view/login/js/milonic_src.js"></script>
<script	type="text/javascript">
    if(ns4)_d.write("<scr"+"ipt type=text/javascript src=<?php echo $path;?>/view/login/js/mmenuns4.js><\/scr"+"ipt>");
    else _d.write("<scr"+"ipt type=text/javascript src=<?php echo $path;?>/view/login/js/mmenudom.js><\/scr"+"ipt>");
</script>
<script type="text/javascript" src="<?php echo $path;?>/view/login/js/menu_data.js"></script>
<script type="text/javascript" src="<?php echo $path;?>/view/template/jquery.min.js"></script>
<!-- PAGE -->
<div align="center">
    <div id="page">
        <!-- ZONE IDENTITAIRE | Header -->
        <div id="header">
            <table width="929" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="355">
                        <?php buildLink("","<img src='$path/view/login/img/habillage/header_psa.gif' alt='Portail Services Asal&eacute;e' title='Retour &agrave; l\'accueil du Portail Services Asal&eacute;e' width='355' height='130' border='0'>","$path/controler/ActionControler.php","UtilityControler",ACTION_MAIN); ?>
                    </td>
                    <!--441-->
                    <td width="564" align="right" bgcolor="white"><img src="<?php echo $path;?>/view/login/img/logos/LogoAsalee2013.png" alt="Services propos&eacute; par&hellip;" width="190" height="68" hspace="20" vspace="0"></td>
                    <td width="10"><img src="<?php echo $path;?>/view/login/img/habillage/header_right.gif" width="10" height="130"></td>
                </tr>
            </table>
        </div>
        <!-- NAVIGATION -->
        <div style='margin-left:15px;'><?php require_once('view/login/menu.php');?></div>
        <!-- Script de description du menu -->
        <script type="text/javascript" src="<?php echo $path;?>/view/login/js/menu_data.js"></script>


        <!-- CONTENU -->
        <div id="contenu">
            <table width="921" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="10" valign="bottom" background="<?php echo $path;?>/view/login/img/habillage/pattern_left.gif"><img src="<?php echo $path;?>/view/login/img/habillage/left_bottom.gif" width="10" height="416"></td>
                    <td width="901" valign="top" bgcolor="white">
                        <div class="mainlogin">
                            <h1><?php echo $map["bodyTitle"];?></h1>
                            <div class="errorMessage">
                                <?php if(is_array($_REQUEST["REQUEST_MESSAGE"])) {
                                    echo (implode("<br>",$_REQUEST["REQUEST_MESSAGE"]));
                                }
                                else echo($_REQUEST["REQUEST_MESSAGE"]); ?>
                            </div>
                            <?php require($map["body"]); ?>

                        </div>
                        <div class="footer"><img src="<?php echo $path;?>/view/login/img/habillage/gray_top.gif" width="901" height="11"><br>
                            &copy;2005 ISAS <span>|</span> <a href="#" title="Consultez les informations juridiques">Informations juridiques</a> <span>|</span> Services propos&eacute;s par Isas <a href="#" title="Consultez le site de la G&eacute;n&eacute;rale de Sant&eacute;"></a>
                        </div>
                    </td>
                    <td width="10" valign="top">
                        <img src="<?php echo $path;?>/view/login/img/habillage/right_top.gif" width="10" height="473">
                    </td>
                </tr>
                <tr>
                    <td colspan="3"><img src="<?php echo $path;?>/view/login/img/habillage/bottom.gif" width="921" height="10"></td>
                </tr>
            </table>
        </div>
    </div>
</div>
</body>
</html>
