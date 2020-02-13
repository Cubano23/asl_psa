<?php
require_once("bean/beanparser/htmltags.php");
require_once("view/common/vars.php");
require_once("bean/ControlerParams.php");
require_once("controler/tdbControler.php");
#require_once("bean/Dashboard.php");
require_once ("Config.php");
$config = new Config();
?>

<style type="text/css">
    .bandeau_actu_flash {
        padding: 12px;
        height: 15px;
        background-color: #EC9C0E;
        color: white;
    }

    #bandeau_actu_flash_content {
        white-space:nowrap;
        overflow:hidden;
    }

    #bandeau_actu_flash_content ul {
        transition: ease 0.1s;
    }

    #bandeau_actu_flash_content li {
        display: inline-block;
        margin-right: 75px;
        text-transform: uppercase;
        font-size: 12px;
    }
</style>


<h1><img src="<?php echo $path;?>/view/login/img/titres/acces_personnel.gif" alt="Acc&egrave;s personnalis&eacute;" width="265" height="20"></h1>
<h3><a href="<?php echo $path;?>/view/blog/blog_gerer.php"  target='_blank' style="color: inherit;">Acc&eacute;der à l&quot;espace CSE <br/>Comit&eacute; Social et &eacute;conomique</a></h3>
<h3><?php echo $_SESSION["account"]->cabinet."(".$_SESSION["account"]->nom.")";
    // EA 25-09-2016 mise du login cabinet
    ?></h3>
<!-- <p class="appel"></p> -->
<p class="profil"><a href="<?php echo $path;?>/controler/ActionControler.php?controlerparams:param:controler=FicheCabinetControler&controlerparams:param:action=AF&fichecabinet:fichecabinet:cabinet=<?php echo $_SESSION['nom']; ?>" title="Vous voulez modifier les param&egrave;tres de votre profil" target='_blank'>Modifier votre profil</a></p>
<p class="aide"><a href="<?php echo $path;?>/controler/ActionControler.php?controlerparams:param:controler=PoserQuestionControler&controlerparams:param:action=AF&poserquestion:poserquestion:cabinet=<?php echo $_SESSION['nom']; ?>" title="Contacter le support Asalée" target='_blank'>Contacter le support Asalée</a></p>
<p class="appel"><a href="https://www.asalee.fr/horde" title="Vous souhaitez acc&eacute;der &agrave; votre messagerie" target='_blank'>Acc&eacute;der &agrave; sa messagerie</a></p>
<p class="fermer"><a href="<?php echo $path;?>/deconnect.php" title="D&eacute;connexion">D&eacute;connexion</a></p>
<br />

<form action="../view/search/search.php" method="get" target="_blank">
    <input type="text" name="query" id="query"  value="">
    <input type="submit" value="Recherche">
    <input type="hidden" name="search" value="1">
</form>



</div>
<div class="actus">
    <h1><img src="<?php echo $path;?>/view/login/img/titres/actualite.gif" alt="Actualit&eacute;s" width="265" height="20"></h1>

    <?php include ('actualites.php'); ?>


</div>
</div>
<div class="mainlogin">
    <div style="background: url(<?php echo $path;?>/view/login/img/visuels/visuel_psa_01.jpg) no-repeat center center; width:100%; height:200px; background-size:cover;"></div>
    <!-- <img src="<?php //echo $path;?>/view/login/img/visuels/visuel_psa_01.jpg" alt="&Agrave; votre service pour &ecirc;tre au service de vos patients" width="480" height="157">-->
    <?php //if($_SESSION["account"]->cabinet == "zTest"): ?>
    <div id="bandeau_actu_flash" class="bandeau_actu_flash">
        <div id="bandeau_actu_flash_content"></div>
    </div>
    <?php //endif ?>
    <h1>
        Bonjour <?php echo utf8_decode($_SESSION['id.prenom']). ' '.utf8_decode($_SESSION['id.nom']) ?>.
        <br>
        Bienvenue sur votre Portail Services Asal&eacute;e Nous sommes ravis de vous voir
    </h1>

    <?php


    #var_dump($_SESSION);
    /**
     * test de récupération des infirmières du cabinet suite à la mise en place du référentiel cabinet
     * WS mise en place par Easelt
     */
    #var_dump($_SERVER);
    // require_once('/home/informed/rest/GetCabsAndLogins.php');
    $config = new Config();
    require_once($config->rest_path . '/GetCabsAndLogins.php') ;


    /*
    echo '<p>// TEST //</p>';
    echo '<p>Liste des infirmières du cabinet </p>';



    $infirmieres = GetLoginsByCab($_SESSION['cabinet'], &$status);
    #echo '<pre> '; print_r($infirmieres); echo '</pre>';
    #var_dump($infirmieres);
        foreach($infirmieres as $inf){
            echo '<p>'.$inf['prenom'].' '.$inf['nom'].' ('.$inf['type'].')<br>';

    }
    */
    ?>

    <p>&nbsp;</p>
    <h1 style="color:#000000"><u>Documents à télécharger :</u> </h1>

    <p style="margin-left:20px;">
    <div style="font-size:12px;margin-left:10px;margin-bottom:10px"><a href="<?php echo $path;?>/view/docs/Art_51.zip">- Protocole Asalée</a> </div>
    <div style="font-size:12px;margin-left:10px;margin-bottom:10px"><a href="<?php echo $path;?>/view/docs/Doc_derogatoire_diabete.zip">- Protocole Asalée : DIABETE</a></div>
    <div style="font-size:12px;margin-left:10px;margin-bottom:10px"><a href="<?php echo $path;?>/view/docs/Doc_derogatoire_RCV.zip">- Protocole Asalée : RCVA</a></div>
    <div style="font-size:12px;margin-left:10px;margin-bottom:10px"><a href="<?php echo $path;?>/view/docs/Doc_deroga_BPCO.zip">- Protocole Asalée : BPCO</a></div>
    <div style="font-size:12px;margin-left:10px;margin-bottom:10px"><a href="<?php echo $path;?>/view/docs/Doc_troubles_cognitifs.zip">- Protocole Asalée : TROUBLES COGNITIFS</a></div>
    <div style="font-size:12px;margin-left:10px;margin-bottom:10px"><a href="<?php echo $path;?>/view/docs/recos_HAS_RCV_diabete1.zip">- Recommandation HAS : DIABETE et RCVA</a></div>
    <div style="font-size:12px;margin-left:10px;margin-bottom:10px"><a href="<?php echo $path;?>/view/docs/recos_test_Alzheimer_HTA_diabete.zip">- Recommandation HAS : TROUBLES COGNITIFS</a></div>
    <div style="font-size:12px;margin-left:10px;margin-bottom:10px"><a href="<?php echo $path;?>/view/docs/doc_ETP_entretiens.zip">- Education Thérapeutique du Patient (ETP1)</a></div>
    <div style="font-size:12px;margin-left:10px;margin-bottom:10px"><a href="<?php echo $path;?>/view/docs/doc_ETP2_entretiens.zip">- Education Thérapeutique du Patient (ETP2)</a></div>
    </p>

    <p>&nbsp;</p>
    <h1 style="color:#000000"><u>Convention collective des cabinets médicaux : </u></h1>
    <div style="font-size:12px;margin-left:10px;margin-bottom:10px"><a href="https://www.legifrance.gouv.fr/affichIDCC.do?idConvention=KALICONT000005635409" target="_blank">Accédez à la convention collective</a></div>
    <div style="font-size:12px;margin-left:10px;margin-bottom:10px"><a href="<?php echo $path;?>/view/docs/ASALEE_Fiche_de_poste_officiel_2017.pdf" target="_blank">Fiche de Poste</a></div>


    <p>&nbsp;</p>
    <h1 style="color:#000000"><u>Les tableaux de bord : </u></h1>
    <?php
    $lesCodesTDB = tdbControler::givePublishedCode();
    #var_dump($lesCodesTDB);
    $in='';
    #var_dump($_SERVER);
    $cabinet = $_SESSION['cabinet'];
    #echo $cabinet;
    //if($cabinet=='zTest'){$cabinet = 'ladoix';}
    if($cabinet=='Chizé'){$cabinet = 'Chize';}

    $mdCab = MD5($cabinet);

    //$path = '/var/data/home/informed/www/_files/dashboard/pdf';
    $path = $config->files_path .'/dashboard/pdf/';

    #echo $path;
    #echo $code.'/'.$mdCab.'_'.$cabinet.'.pdf';
    foreach($lesCodesTDB as $code => $libTDB){
        #echo $code. ' '.$libTDB;
        #							$in.="'".$code."',";
        #echo 'https://'.$_SERVER['HTTP_HOST'].'/psa/view/tdb/'.$code.'/'.$mdCab.'_'.$cabinet.'.pdf<br>';
        #echo 'TT-> '.$path.$code.'/'.$mdCab.'_'.$cabinet.'.pdf<br>';
        if(file_exists($path.'/'.$code.'/'.$mdCab.'_'.$cabinet.'.pdf')){

            #echo '<div style="font-size:12px;margin-left:10px;margin-bottom:10px"><a href="/psa/view/tdb/'.$code.'/'.$mdCab.'_'.$cabinet.'.pdf" target="_blank">Télécharger le '.utf8_decode($libTDB).'</a></div>';
            echo '<div style="font-size:12px;margin-left:10px;margin-bottom:10px"><a href="'. $config->psa_path .'/view/tdb/load_tdb.php?rep='.$code.'" target="_blank">Télécharger le '.utf8_decode($libTDB).'</a></div>';
        }



    }
    #						$in = substr($in,0,-1);

    //$lesTDB = Dashboard::getByCabinetsAndAllowedCodes($_SESSION['cabinet'],$in);
    #var_dump($lesTDB);

    #foreach($lesTDB as $tdb){

    #}
    #echo $_SESSION['cabinet'];
    ?>

    <?php if(file_exists(dirname(__FILE__) . '/files_dossiers_nir_consentement/20170427/Fichier '.$cabinet.'.xlsx')) : ?>
        <p>&nbsp;</p>
        <h1 style="color:#000000"><u>Dossiers de patients vu dans la période 2010 – 2014 et dont nous n’avons pas le Nir-Consentement</u></h1>
        <?php if(file_exists(dirname(__FILE__) . '/files_dossiers_nir_consentement/20170427/Fichier '.$cabinet.'.xlsx')): ?>
            <div style="font-size:12px;margin-left:10px;margin-bottom:10px"><a href="<?= $config->psa_path ?>/view/files_dossiers_nir_consentement/20170427/Fichier <?php echo $cabinet ?>.xlsx" target="_blank">Télécharger la liste des dossiers de patients vu dans la période 2010 – 2014 et dont nous n’avons pas le NIR-Consentement</a></div>
        <?php endif ?>
    <?php endif ?>

    <?php if(file_exists(dirname(__FILE__) . '/files_dossiers_hba1c_manquant/20170719/Fichier '.$cabinet.'.xlsx')) : ?>
        <p>&nbsp;</p>
        <h1 style="color:#000000"><u>Dossiers de patients vus en suivi de diabète et dont nous n’avons pas de valeur d’HBA1c avant la première consultation Asalée</u></h1>
        <?php if(file_exists(dirname(__FILE__) . '/files_dossiers_hba1c_manquant/20170719/Fichier '.$cabinet.'.xlsx')): ?>
            <div style="font-size:12px;margin-left:10px;margin-bottom:10px"><a href="<?= $config->psa_path ?>/view/files_dossiers_hba1c_manquant/20170719/Fichier <?php echo $cabinet ?>.xlsx" target="_blank">Télécharger la liste des dossiers de patients vus en suivi de diabète et dont nous n’avons pas de valeur d’HBA1c avant la première consultation Asalée</a></div>
        <?php endif ?>
    <?php endif ?>
</div>


<script type="text/javascript">
    function removeChilds(parent) {
        while (parent.firstChild) {
            parent.removeChild(parent.firstChild);
        }
    }

    function NewsRow(news, id) {
        this.news = news || [];
        this.element = document.getElementById(id);
        this.ul = null;
        this.len = 0;
        this.begin = 0;
        this.end = 0;
        this.idx = 0;
        this.speed = 100; //px / s

        this.start = function() {
            removeChilds(this.element)
            this.ul = document.createElement('ul')
            for (var i=0;i<news.length;i++) {
                this.ul.appendChild(this.createElement(this.news[i]))
            }
            this.element.appendChild(this.ul);
            this.begin = -this.ul.scrollWidth;
            this.idx = this.element.offsetWidth;
            this.end = this.begin;
            this.element.style.transform = "tarnslateX(-" + this.len + "px)";
            var obj = this;
            setInterval(function() {
                obj.idx = obj.idx - obj.speed / 10;
                if (obj.idx <= obj.end) obj.idx = obj.element.offsetWidth + 30;
                obj.ul.style.transform = "translateX(" + obj.idx + "px)";
            }, 100)
        }

        this.createElement = function(text) {
            var e = document.createElement('li')
            e.textContent = text
            return e;
        }

        this.pause = function() {

        }

        this.resume = function() {

        }

        this.clear = function() {

        }
    }
    <?php //if($_SESSION['cabinet'] == "zTest"): ?>
    //     var e = new NewsRow(["LE TABLEAU DE BORD D'ACTIVITE DU MOIS DE JANVIER 2018 EST EN LIGNE POUR LES CABINETS AYANT ENREGISTRE AU MOINS UNE CONSULTATION DURANT CE MOIS", ''], "bandeau_actu_flash_content")
    // LE TABLEAU DE BORD D'ACTIVITE DU MOIS DE JANVIER 2018 EST EN LIGNE POUR LES CABINETS AYANT ENREGISTRE AU MOINS UNE CONSULTATION DURANT CE MOIS
    var e = new NewsRow(["Le tableau de bord d'activité du mois de novembre 2018 est en ligne pour les cabinets ayant enregistré au moins une consultation durant ce mois","/////////////////","Les comptes rendus des dernières réunions CSE sont en ligne dans l'espace comité d'entreprise", ''], "bandeau_actu_flash_content")
    // le compte rendu de la réunion du CE de Mai est en ligne dans l<92>espace CE
    e.start()
    <?php //endif ?>
</script>
