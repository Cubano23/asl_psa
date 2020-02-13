<?php

/*function AnonymizeFilename( $fname)
    {
            $inputKey = pack("H*","E49F211F72FDA17B3420DEADEA99ADF5");
            $f = hash_hmac ( "md5" , $fname, $inputKey );
    
            return $fname.".".$f;
    
    }
  */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once ("Config.php");
$config = new Config();
session_start();

if(!isset($_SESSION["cabinet"])){
    header("location:". $config->psa_path);
}

$path = $config->psa_path;

require($config->inclus_path ."/accesbase.inc.php");
require_once($config->webservice_path ."/LogAccess.php");
if($_SERVER['APPLICATION_ENV']=='dev-herve'){

}else{
    require_once($config->webservice_path ."/GetUserId.php");
}


# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
die("Impossible de se connecter à la base");

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
include './Utils.php';

require_once ($config->app_path . $config->psa_path . '/lib/ComposerLibs/vendor/autoload.php');

//require_once "../stats/writeexcel/class.writeexcel_workbookbig.inc.php";
//require_once "../stats/writeexcel/class.writeexcel_worksheet.inc.php";
require_once "integration_process.php";
//require_once "integration_amberieux.php";
//require_once "integration_argenton.php";
require_once "integration_artemare_mediclick.php";
require_once "integration_bouille.php";
//require_once "integration_brioux.php";
//require_once "integration_chambery1.php";
require_once "integration_chambery2.php";
//require_once "integration_champniers.php";
//require_once "integration_chateauneuf.php";
//require_once "integration_chatillonv2.php";
//require_once "integration_chef1.php";
//require_once "integration_collet.php";
//require_once "integration_espagnac2.php";
//require_once "integration_espagnac1.php";
require_once "integration_hillion.php";
//require_once "integration_tallud.php";
//require_once "integration_mauze.php";
//require_once "integration_mont_de_marsan.php";
require_once "integration_moncoutant.php";
require_once "integration_niort1.php";
require_once "integration_niort2.php";
//require_once "integration_oleron.php";
//require_once "integration_ruelle.php";
//require_once "integration_saint_julien.php";
//require_once "integration_saulieu.php";
//require_once "integration_saint_brieuc1.php";
require_once "integration_scorbe.php";
//require_once "integration_segonzac.php";
//require_once "integration_thouars1.php";
require_once "integration_thouars2.php";
require_once "integration_touaregs.php";
//require_once "integration_venarey2.php";
require_once "integration_eomed.php"; //EA 12-02-2014
require_once "integration_hellodoc.php"; //EA 03-04-2014
require_once "integration_crossway.php"; //EA 05-04-2014
require_once "integration_mediclic.php"; //EA 07-04-2014
require_once "integration_axisante.php"; //EA 08-04-2014
require_once "integration_ict.php"; //EA 10-04-2014
set_time_limit(0);


?>




    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/html4/loose.dtd">
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title>Intégration automatique de données</title>
        <meta name="author" content="Informed79 Services SAS">
        <meta name="keywords" content="PSA,Portail Services Asalée,Informed79 Services SAS">
        <meta name="description" content="Portail Services Asalée, &agrave; votre service pour &ecirc;tre au service de vos patients.">
        <meta name="robots" content="noindex,nofollow">
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

        <script type="text/javascript" src="<?php echo $path;?>/view/login/_js/tooltip/ajax-tooltip2.js"></script>

        <link href="<?php echo $path;?>/view/login/_css/tooltip.css" rel="stylesheet" type="text/css" />

        <script type="text/javascript" src="<?php echo $path;?>/view/login/_js/multi_content.js"></script>
    </head>

<body>
    <!-- Script pilotant la navigation -->
    <script type="text/javascript" src="<?php echo $path;?>/view/login/js/milonic_src.js"></script>
    <script	type="text/javascript">
        if(ns4)_d.write("<scr"+"ipt type=text/javascript src=<?php echo $path;?>/view/login/js/mmenuns4.js><\/scr"+"ipt>");
        else _d.write("<scr"+"ipt type=text/javascript src=<?php echo $path;?>/view/login/js/mmenudom.js><\/scr"+"ipt>");
    </script>
    <script type="text/javascript" src="<?php echo $path;?>/view/login/js/menu_data_planning.js"></script>
    <!-- PAGE -->
<div align="center">
    <div id="page">
    <!-- ZONE IDENTITAIRE | Header -->
    <div id="header">
        <table width="929" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="355">
                    <a href="<?php echo $path;?>/controler/ActionControler.php?controlerparams:param:controler=UtilityControler&controlerparams:param:action=AMEN" ><img src="<?php echo $path;?>/view/login/img/habillage/header_psa.gif" alt="Portail Services Asal&eacute;e" title="Retour &agrave; l'accueil du Portail Services Asal&eacute;e" width="355" height="130" border="0"></a>
                    <?php /*buildLink("","<img src='$path/view/login/img/habillage/header_psa.gif' alt='Portail Services Asal&eacute;e' title='Retour &agrave; l\'accueil du Portail Services Asal&eacute;e' width='355' height='130' border='0'>","$path/controler/ActionControler.php","UtilityControler",ACTION_MAIN);*/ ?>

                <td width="564" align="right" bgcolor="white"><img src="<?php echo $path;?>/view/login/img/logos/LogoAsalee2013.png" alt="Services propos&eacute; par&hellip;" width="190" height="68" hspace="20" vspace="0"></td>
                <td width="10"><img src="<?php echo $path;?>/view/login/img/habillage/header_right.gif" width="10" height="130"></td>
            </tr>
        </table>
    </div>
    <!-- NAVIGATION -->
    <div id="navigation">
        <table width="921" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td background="<?php echo $path;?>/view/login/img/habillage/pattern_left.gif"></td>
                <td bgcolor="white"><img src="<?php echo $path;?>/view/login/img/habillage/gray_top.gif" width="901" height="11"></td>
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
                            aI("image=<?php echo $path;?>/view/login/img/navigation/serv_patients.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_patients_over.gif;showmenu=Patients;");
                            aI("image=<?php echo $path;?>/view/login/img/navigation/serv_diabete.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_diabete_over.gif;showmenu=Diabete;");
                            aI("image=<?php echo $path;?>/view/login/img/navigation/serv_rcva.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_rcva_over.gif;showmenu=RCVA;");
                            aI("image=<?php echo $path;?>/view/login/img/navigation/serv_cancer.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_cancer_over.gif;showmenu=Cancer;");
                            aI("image=<?php echo $path;?>/view/login/img/navigation/serv_cognitifs.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_cognitifs_over.gif;showmenu=Cognitifs;");
                            aI("image=<?php echo $path;?>/view/login/img/navigation/serv_evaluation.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_evaluation_over.gif;showmenu=Evaluation;");
                        }
                        drawMenus();
                    </script>
                </td>
            </tr>
            <tr>
                <td width="10" background="<?php echo $path;?>/view/login/img/habillage/pattern_left.gif"></td>
                <td width="901" bgcolor="white"><img src="<?php echo $path;?>/view/login/img/habillage/gray_bottom.gif" width="901" height="12"></td>
            </tr>
        </table>
    </div>

    <!-- CONTENU -->
    <div id="contenu">
    <table width="921" border="0" cellspacing="0" cellpadding="0">
    <tr>
    <td width="10" valign="bottom" background="<?php echo $path;?>/view/login/img/habillage/pattern_left.gif"><img src="<?php echo $path;?>/view/login/img/habillage/left_bottom.gif" width="10" height="416"></td>
    <td width="901" valign="top" bgcolor="white">


    <div class="mainlogin">

    <br><br>
    <h1>Intégration automatique de données</h1>



<?php

# boucle principale
do {
    $repete=false;

    # étape 1 : Saisie des information
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }
    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

            # étape 1  : Sélection fichier
            case 1:
                etape_1($repete);
                break;

            # étape 2  : Validation et maj base
            case 2:
                etape_2($repete);
                break;

        }
    }
} while($repete);

exit;

# saisie des données
function etape_1(&$repete) {
    global $message, $path;

    $logiciels=array(""=>"", "axisante"=>"Axisante", "axisante4"=>"Axisante 4", "axisante5"=>"Axisante 5", "crossway"=>"CrossWay",
        "dbmed"=>"DBmed", "easyprat"=>"EasyPrat","eomed"=>"EoMed",
        "hellodoc"=>"Hellodoc", "hellodoc_v5.6"=>"Hellodoc v5.6", "hellodoc_v5.55"=>"Hellodoc v5.55", "ict"=>"ICT",
        "medicawin"=>"Medicawin", "mediclic"=>"Mediclick", "mediclic3"=>"Mediclick 3", "mediclic4"=>"Mediclick 4",  "mediclic5"=>"Mediclick 5",
        "medimust"=>"Medimust", "medistory"=>"Medistory",
        "mediwin"=>"MediWin", "shaman"=>"Shaman",
        "xmed"=>"XMed");
    ?>


    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" >
        <input type="hidden" name="etape" value="2">
        <input type="hidden" name="MAX_FILE_SIZE" value="40000000">
        Ces fonctions d'administration permettent d'intégrer de façon semi-automatique les données disponibles dans les logiciels de gestion des cabinets et nécessaires à l'exercice d'asalée. Les données sont strictement anonymes. Le format d'intégration est déterminé cabinet par cabinet, logiciel par logiciel.
        <br><br>
        Les logiciels d'intégration sont développés par informed79 services SAS, la procédure d'intégration est la suivante : <br>
        1- l'infirmière asalée fait tourner dans le cabinet une requête notée dans le langage propre à chacun des logiciels de gestion de cabinet
        <br><br>
        2- elle dépose le fichier à traiter ci-dessous pour le cabinet concerné.
        <br><br>
        3- le système d'intégration vérifie le format du fichier et intègre automatiquement les données. Un même fichier peut être posté plusieurs fois, le système vérifiant automatiquement les doublons. Il envoi un compte-rendu d'intégration à l'infirmière.
        <br><br>

        <?php

        if(sizeof($message)>0){
            echo "<font style='color:red'><b>";
            foreach($message as $m){
                echo $m."<br>";
            }
            echo "</font><br><br>";
        }

        ?>
        <table border='1'>
            <tr><td>Cabinet : </td><td>Région</td><td>Logiciel</td><td>Infirmière </td><td>Email </td></tr>
            <?php



            $req="SELECT nom_cab, region, logiciel FROM account WHERE cabinet='".$_SESSION["cabinet"]."'";
            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

            list($nom_cab, $region, $logiciel)=mysql_fetch_row($res);

            echo "<tr><td>$nom_cab";

            echo "</td><td>$region";

            echo "</td><td>".$logiciels[$logiciel]."</td>.<td>".$_SESSION["id.prenom"]." ".$_SESSION["id.nom"]."</td><td>".$_SESSION["id.email"]."</td></tr>";
            if(($logiciel=="mediclic")|| ($logiciel=="medimust")){
                echo "<tr><td colspan='3'>Fichier des examens <input type='file' name='fichier_ex'> <a href='javascript://' onmouseover=\"ajax_showTooltip('$path/view/integration/notice.php?logiciel=$logiciel',this);return false\" ><img src='../login/img/puces/aide.gif'></a></td></Tr>";
                echo "<tr><td colspan='3'>Fichier patients <input type='file' name='fichier_pat'></td></Tr>";
            }
            else{
                echo "<tr><td colspan='3'><input type='file' name='fichier'> <a href='javascript://' onmouseover=\"ajax_showTooltip('$path/view/integration/notice.php?logiciel=$logiciel',this);return false\" ><img src='../login/img/puces/aide.gif'></a></td></Tr>";
            }
            ?>
        </table>

        <input type='submit' value='Intégrer'>

    </div>
    <div class="footer"><img src="<?php echo $path;?>/view/login/img/habillage/gray_top.gif" width="901" height="11"><br>
        &copy;2005 ISAS <span>|</span> <a href="#" title="Consultez les informations juridiques">Informations juridiques</a> <span>|</span> Services propos&eacute;s par Isas <a href="#" title="Consultez le site de la G&eacute;n&eacute;rale de Sant&eacute;"></a>
    </div>
    </td>
    <td width="10" valign="top">
        <img src="<?php echo $path;?>/view/login/img/habillage/right_top.gif" width="10" height="90"><!--473-->
    </td>
    </tr>
    <tr>
        <td colspan="3"><img src="<?php echo $path;?>/view/login/img/habillage/bottom.gif" width="921" height="10"></td>
    </tr>
    </table>
    </div>
    </div>
    </div><!-- fin <div align="center"> -->
    </body>
    </html>

    <?php
}


# Validation / maj base
function etape_2(&$repete) {
    $config = new Config();
    global $message, $path;
    global $isgeneric;
    extract($_POST);


    $isgeneric=0;
    $req="SELECT nom_cab, region, logiciel, courriel FROM account WHERE cabinet='".$_SESSION["cabinet"]."'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    list($nom_cab, $region, $logiciel, $courriel)=mysql_fetch_row($res);

//Vérif sur le cabinet pour voir si la procédure est validée
    if(
//  (strcasecmp($_SESSION["cabinet"],"amberieu")!=0)&&
//	   (strcasecmp($_SESSION["cabinet"],"argenton")!=0)&&
        (strcasecmp($_SESSION["cabinet"],"artemare")!=0)&&
//	   (strcasecmp($_SESSION["cabinet"],"avallon")!=0)&&    
        (strcasecmp($_SESSION["cabinet"],"bouille")!=0)&&
//	   (strcasecmp($_SESSION["cabinet"],"Brioux")!=0)&&
//	   (strcasecmp($_SESSION["cabinet"],"chambery1")!=0)&&
        (strcasecmp($_SESSION["cabinet"],"chambery2")!=0)&&
//	   (strcasecmp($_SESSION["cabinet"],"champniers")!=0)&&
//	   (strcasecmp($_SESSION["cabinet"],"chateauneuf")!=0)&&
//	   (strcasecmp($_SESSION["cabinet"],"chatillon")!=0)&&
        (strcasecmp($_SESSION["cabinet"],"Chef-boutonne1")!=0)&&
//	   (strcasecmp($_SESSION["cabinet"],"collet")!=0)&&
//	   (strcasecmp($_SESSION["cabinet"],"dignac")!=0)&&    //EA 15-11-2013
//	   (strcasecmp($_SESSION["cabinet"],"espagnac2")!=0)&&
//	   (strcasecmp($_SESSION["cabinet"],"espagnac1")!=0)&&
//	   (strcasecmp($_SESSION["cabinet"],"gienjeannedarc")!=0)&& //EA 15-11-2013
//	   (strcasecmp($_SESSION["cabinet"],"hillion")!=0)&&
//	   (strcasecmp($_SESSION["cabinet"],"lucquin")!=0)&&
//	   (strcasecmp($_SESSION["cabinet"],"mauze-thouarsais")!=0)&&
        (strcasecmp($_SESSION["cabinet"],"moncoutant")!=0)&&
        (strcasecmp($_SESSION["cabinet"],"niort")!=0)&&
//	   (strcasecmp($_SESSION["cabinet"],"oleron")!=0)&&
//	   (strcasecmp($_SESSION["cabinet"],"Paquereau")!=0)&&
//	   (strcasecmp($_SESSION["cabinet"],"Ruelle")!=0)&&
//	   (strcasecmp($_SESSION["cabinet"],"saint-julien")!=0)&&
//	   (strcasecmp($_SESSION["cabinet"],"saulieu")!=0)&&
        (strcasecmp($_SESSION["cabinet"],"Scorbe")!=0)&&
//	   (strcasecmp($_SESSION["cabinet"],"brieuc1")!=0)&&
//	   (strcasecmp($_SESSION["cabinet"],"segonzac")!=0)&&
//	   (strcasecmp($_SESSION["cabinet"],"Smarves")!=0)&&  //EA 29-10-2013
//	   (strcasecmp($_SESSION["cabinet"],"thouars")!=0)&&
        (strcasecmp($_SESSION["cabinet"],"thouars2")!=0)&&
        (strcasecmp($_SESSION["cabinet"],"touaregs")!=0)

//	   (strcasecmp($_SESSION["cabinet"],"venarey2")!=0)&&
//	   (strcasecmp($_SESSION["cabinet"],"venissieuxcroizat")!=0) &&
//	   (strcasecmp($_SESSION["cabinet"],"ztest")!=0)                        //EA cct 22-06-2017

    )

    {

//EA 04-12-2013 cabients générics
        if(	($logiciel=="hellodoc") ||
            ($logiciel=="hellodoc_v5.6") ||
            ($logiciel=="hellodoc_v5.55") ||
            ($logiciel=="axisante4") ||
            ($logiciel=="mediclic5") ||
            ($logiciel=="mediclic4") ||
            ($logiciel=="mediclic3") || //EA 10-04-2014
            ($logiciel=="mediclic") ||
            ($logiciel=="axisante") ||
            ($logiciel=="crossway") ||
            ($logiciel=="medimust") ||
            ($logiciel=="ict")   //EA 09-12-2013
            || ($logiciel=="eomed")   //EA 12-02-2014


        )
        {

            $isgeneric = 1;
        }
        else
        {
            echo "La procédure d'intégration n'a pas encore été validée pour le cabinet $nom_cab<br>".
                "Lorsque la procédure d'intégration aura été validée, vous pourrez télécharger le compte-rendu d'intégration";
            die;
        }
    }

    //Vérif sur les documents joints pour faire la maj
    if(($logiciel=="mediclic") ||($logiciel=="medimust")){
        $fichier_ex = $_FILES['fichier_ex']['tmp_name'];
        $fichier_ex_name = $_FILES['fichier_ex']['name'];
        $fichier_ex_size = $_FILES['fichier_ex']['size'];
        $fichier_ex_type = $_FILES['fichier_ex']['type'];
        $fichier_ex_error = $_FILES['fichier_ex']['error'];

        if($fichier_ex==""){
            $message[]="Veuillez sélectionner un fichier examens";
            $_POST["etape"]=1;
            $repete=true;
            return;
        }

        if ($fichier_ex_error>0)
        {
            $piece=0;

            switch ($fichier_ex_error)
            {
                case 2: echo "Le fichier examens dépasse la taille maximum admise"; break;
                case 3: echo "fichier examens partiellement téléchargé, recommencez plus tard";break;
                case 4: echo "Le fichier examens n'a pas été téléchargé, recommencez ultérieurement"; break;
                default: echo "problème lors du téléchargement du fichier examens"; break;
            }
            exit;
        }

        $fichier_pat = $_FILES['fichier_pat']['tmp_name'];
        $fichier_pat_name = $_FILES['fichier_pat']['name'];
        $fichier_pat_size = $_FILES['fichier_pat']['size'];
        $fichier_pat_type = $_FILES['fichier_pat']['type'];
        $fichier_pat_error = $_FILES['fichier_pat']['error'];

        if($fichier_pat==""){
            $message[]="Veuillez sélectionner un fichier examens";
            $_POST["etape"]=1;
            $repete=true;
            return;
        }

        if ($fichier_pat_error>0)
        {
            $piece=0;

            switch ($fichier_pat_error)
            {
                case 2: echo 'Le fichier patients dépasse la taille maximum admise'; break;
                case 3: echo 'fichier patients partiellement téléchargé, recommencez plus tard';break;
                case 4: echo "Le fichier patients n'a pas été téléchargé, recommencez ultérieurement"; break;
                default: echo "problème lors du téléchargement du fichier patients"; break;
            }
            exit;
        }
    }
    else{
        $fichier = $_FILES['fichier']['tmp_name'];
        $fichier_name = $_FILES['fichier']['name'];
        $fichier_size = $_FILES['fichier']['size'];
        $fichier_type = $_FILES['fichier']['type'];
        $fichier_error = $_FILES['fichier']['error'];

        if($fichier==""){
            $message[]="Veuillez sélectionner un fichier";
            $_POST["etape"]=1;
            $repete=true;
            return;
        }

        if ($fichier_error>0)
        {
            $piece=0;

            switch ($fichier_error)
            {
                case 2: echo 'Le fichier dépasse la taille maximum admise'; break;
                case 3: echo 'fichier partiellement téléchargé, recommencez plus tard';break;
                case 4: echo "Le fichier n'a pas été téléchargé, recommencez ultérieurement"; break;
                default: echo "problème lors du téléchargement du fichier"; break;
            }
            exit;
        }
    }
// Historique  
    $date1 = time();

    if($isgeneric==0) //EA 04-12-2013 cabient généric
    {
        //En fonction du cabinet, appel de la fonction adéquate.

        //mediclic 2 fichiers
        if(strcasecmp($_SESSION["cabinet"], "artemare")==0){
            $fich=integration_artemare_mediclick($fichier_ex, $fichier_pat);
        }
        //medimust
        if(strcasecmp($_SESSION["cabinet"], "bouille")==0){
            $fich=integration_bouille($fichier_ex, $fichier_pat);
        }
        //mediclic 2 fichiers
        if(strcasecmp($_SESSION["cabinet"], "moncoutant")==0){
            $fich=integration_moncoutant($fichier_ex, $fichier_pat);
        }

        //axisante non zippé
        if((strcasecmp($_SESSION["cabinet"],"Chef-boutonne1")==0)||
            (strcasecmp($_SESSION["cabinet"],"ztest")==0)){
            $fich=integration_axisante_notzipped($fichier);
        }
        // mediclic 2 fichiers
        if(strcasecmp($_SESSION["cabinet"], "Scorbe")==0){
            $fich=integration_scorbe($fichier_ex, $fichier_pat);
        }
        //mediclic 2 fichiers
        if(strcasecmp($_SESSION["cabinet"], "thouars2")==0){
            $fich=integration_thouars2($fichier_ex, $fichier_pat);
        }
        //mediwin
        if((strcasecmp($_SESSION["cabinet"],"chambery2")==0)){
            $fich=integration_chambery2($fichier);
        }
        //medicawin
        if(strcasecmp($_SESSION["cabinet"], "niort")==0){
            $fich=integration_niort1($fichier);
        }
        if(strcasecmp($_SESSION["cabinet"],"touaregs")==0){
            $fich=integration_touaregs($fichier, $fichier_name);
        }


        /*	if((strcasecmp($_SESSION["cabinet"],"argenton")==0)){

                $fich=integration_argenton($fichier);
            } cct ict */
        /*	if(strcasecmp($_SESSION["cabinet"], "amberieu")==0){
                $fich=integration_hellodoc($fichier, $fichier_name);
            } CCT hellodoc*/


        /*	if(strcasecmp($_SESSION["cabinet"], "avallon")==0){
        //		$fich=integration_ruelle($fichier, $fichier_name);
                $fich=integration_hellodoc($fichier, $fichier_name);
            }*/ // CCt en hellodoc




        /*	if(strcasecmp($_SESSION["cabinet"], "brieuc1")==0){
                $fich=integration_saint_brieuc1($fichier);
            } cct crossway */

        /*	if(strcasecmp($_SESSION["cabinet"], "Brioux")==0){
                $fich=integration_brioux($fichier);
            } CCT mediclic */

        /*	if(strcasecmp($_SESSION["cabinet"], "chambery1")==0){
                $fich=integration_chambery1($fichier, $fichier_name);
            } CCT hellodoc */


        /*	if((strcasecmp($_SESSION["cabinet"],"champniers")==0)){
                $fich=integration_champniers($fichier, $fichier_name);
            } CCT hellodoc */

        /*	if((strcasecmp($_SESSION["cabinet"],"chateauneuf")==0)){
                $fich=integration_chateauneuf($fichier, $fichier_name);
            } cct chateauneuf */

        /*	if((strcasecmp($_SESSION["cabinet"],"chatillon")==0)){

                $fich=integration_chatillon($fichier, $fichier_name);
            } cct axisante */


        /*	if(strcasecmp($_SESSION["cabinet"], "collet")==0){
                $fich=integration_collet($fichier);
            }  CCT crossway */
        /*	if((strcasecmp($_SESSION["cabinet"],"dignac")==0)){ //15-11-2013 EA

                $fich=integration_chatillon($fichier, $fichier_name);
            }  CCT axisante*/


        /*	if(strcasecmp($_SESSION["cabinet"], "espagnac2")==0){
                $fich=integration_espagnac2($fichier);
            } CCT mediclic */

        /*	if(strcasecmp($_SESSION["cabinet"], "espagnac1")==0){
                $fich=integration_espagnac1($fichier);
            } CCT  */

        /*	if(strcasecmp($_SESSION["cabinet"], "gienjeannedarc")==0){   //15-11-2013 EA
                $fich=integration_brioux($fichier);
            } CCT mediclic */



        /*	if((strcasecmp($_SESSION["cabinet"],"hillion")==0)){

                $fich=integration_hillion($fichier);
            } cct crossway */

        /*	if(strcasecmp($_SESSION["cabinet"], "lucquin")==0){
        //		$fich=integration_tallud($fichier, $fichier_name);
                $fich=integration_ruelle($fichier, $fichier_name);

            }*/ //Cct en hellodoc

        // if(strcasecmp($_SESSION["cabinet"], "mauze-thouarsais")==0){
        // 	$fich=integration_mauze_thouarsais($fichier_ex, $fichier_pat);
        // }

        /*	if(strcasecmp($_SESSION["cabinet"], "mauze-thouarsais")==0){
                $fich=integration_mauze($fichier);
            } cct mediclic */


        /*	if(strcasecmp($_SESSION["cabinet"], "marsan")==0){
                $fich=integration_mont_de_marsan($fichier, $fichier_name);
            } cct hellodoc */


        /*	if(strcasecmp($_SESSION["cabinet"], "oleron")==0){
                $fich=integration_oleron($fichier);
            } cct mediclic */

        /*	if(strcasecmp($_SESSION["cabinet"], "Paquereau")==0){
                $fich=integration_niort2($fichier);
            } cct mediclic */

        /*	if(strcasecmp($_SESSION["cabinet"], "ruelle")==0){
                $fich=integration_ruelle($fichier, $fichier_name);
            }  CCT hellodoc*/


        /*	if(strcasecmp($_SESSION["cabinet"], "saulieu")==0){
                $fich=integration_saulieu($fichier, $fichier_name);
            } cct hellodoc */

        /*	if(strcasecmp($_SESSION["cabinet"], "saint-julien")==0){
                $fich=integration_saint_julien($fichier);
            } cct mediclic */

        /*	if(strcasecmp($_SESSION["cabinet"], "segonzac")==0){
                $fich=integration_segonzac($fichier);
            }  CCT mediclic*/
        /*	if((strcasecmp($_SESSION["cabinet"],"Smarves")==0)){   //EA 29-10-2013

                $fich=integration_chatillon($fichier, $fichier_name);
            }  cct axisante */

        /*	if(strcasecmp($_SESSION["cabinet"], "thouars")==0){
                $fich=integration_thouars1($fichier);
            } cct ict */



        /*	if(strcasecmp($_SESSION["cabinet"], "venarey2")==0){
                $fich=integration_venarey2($fichier, $fichier_name);
            } cct hellodoc */
        /*	if(strcasecmp($_SESSION["cabinet"], "venissieuxcroizat")==0){
                $fich=integration_ruelle($fichier, $fichier_name);
            } Cct en Hellodoc */
    }
    else //EA 04-12-2013
    {

        if( ($logiciel=="hellodoc") ||
            ($logiciel=="hellodoc_v5.6") ||
            ($logiciel=="hellodoc_v5.55")
        )

        {
            $fich=integration_hellodoc($fichier, $fichier_name);
        }
        if( ($logiciel=="axisante4") || ($logiciel=="axisante") )
        {
            $fich=integration_axisante($fichier, $fichier_name);
        }
        if( ($logiciel=="mediclic5") ||
            ($logiciel=="mediclic4") ||
            ($logiciel=="mediclic3") ||
            ($logiciel=="mediclic")

        )
        {
            $fich=integration_mediclic($fichier);
        }

        if($logiciel=="crossway")
        {
            $fich=integration_crossway($fichier);
        }
        if($logiciel=="medimust")
        {
            $fich=integration_bouille($fichier_ex, $fichier_pat);
        }
        if($logiciel=="ict")   //EA 09-12-2013
        {

            $fich=integration_ict($fichier);
        }
        if($logiciel=="eomed")   //EA 12-02-2014
        {
            $fich=integration_eomed($fichier);
        }
    }
    // contournement bug accent de chizé
    $fich2 = str_replace("é", "e", $fich);
    if($fich2!=$fich)
    {
        rename ( $fich ,$fich2);
        $fich = $fich2;
    }
    //===========================> E.A. 07-02-2014


    // Historique
    $date2 = time();
    $interval = strval( $date2 - $date1 );
    //ajout EA 27-06-2016
    $cabinet = $_SESSION["cabinet"];
    $email =   $_SESSION["id.email"];
    $contact = $_SESSION["id.prenom"]." ". $_SESSION["id.nom"];
    // Log IDS
    $answerLog="OK";

    if($_SERVER['APPLICATION_ENV']=='dev-herve')
    {
    }
    else
    {
        $authLog = GetUserId( $answerLog);
        $idslogUser = $authLog->Authentifier;
        LogAccess("", "integration", $idslogUser, 'na', "All", 1,"Cabinet:" . $cabinet." Objet:integration");
    }
//
    Echo "Fin de l'intégration. Vous pouvez télécharger le compte-rendu d'intégration. : <br>".
        //"<a href='$fich' target='_blank'>".str_replace("./log/", "", $fich)."</a><br><br>";
        "<a href='$fich' target='_blank'>".basename($fich)."</a><br><br>";


    //Envoi du fichier par messagerie auprès de l'infirmière
    //antoine to check

    $mail = new PHPMailer(true);

    try
    {
        //Recipients
        $mail->setFrom('contact@asalee.fr', 'Portail PSA - intégrations automatiques');
        $mail->addAddress($email);

        $mail->addAttachment($fich, basename($fich), "text/plain");

        //Content
        $mail->isHTML(true);
        $mail->Subject = "Intégrations automatique ".$_SESSION["cabinet"]." - rapport du ".date("d/m/Y");
        $mail->Body    = "Intégration automatique ".$_SESSION["cabinet"]." - rapport du ".date("d/m/Y");

        $mail->send();
    }
    catch (Exception $e)
    {
        error_log('Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
    }

    date_default_timezone_set('Europe/Berlin'); //EA 22-04-2014

    $dt =   date ("H:i:s", filemtime($fich));
    $dintegration =   date ("Y-m-d", filemtime($fich));
    $fichier =  basename($fichier);
    $fich =  basename($fich);



    $sql2="INSERT  INTO integration(cabinet, logiciel, dintegration, entryfile, reportfile, cr, tintegration, hintegration)".
        " VALUES ('$cabinet','$logiciel','$dintegration','$fichier','$fich',0,'$interval','$dt')";

    $sql2 = $sql2." ON DUPLICATE KEY UPDATE  logiciel='$logiciel',hintegration='$dt',entryfile='$fichier',reportfile='$fich',cr=0,tintegration='$interval'";

//                                           cabinet ='$cabinet', dintegration= '$dintegration',


    echo "Le compte-rendu d'intégration a été envoyé sur votre adresse mail $courriel";

// Historique

    $rs = mysql_query($sql2);



    ?>
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
    <?php
}


# contrôle des dates
function date_valide($date_e, &$date_s, &$message) {

    if($date_e=="")
    {
        return true;
    }

    if(!preg_match('`^([0-9]{1,2})(/|-)([0-9]{1,2})(/|-)([0-9]{2}|[0-9]{4})$`',$date_e, $reg)) {
        $message[]="La date $date_e doit être au format jj/mm/aaaa";
        return false;
    }
    if($reg[5]<100) { # année sur deux chiffres
        $reg[5] += 1900;
    }
    if (!checkdate($reg[3],$reg[1],$reg[5])) {
        $message[]="La date $date_e est invalide";
        return false;
    }

    if( $reg[5] <= 1880) {
        $message[]="La date $date_e doit être supérieure à 1880";
        return false;
    }
    $date_s = sprintf("%04d%02d%02d", $reg[5], $reg[3], $reg[1]); # date au format aaaammjj
    return true;
}

?>