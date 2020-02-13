<?php
session_start();
if(!isset($_SESSION['nom'])) {
    # pas passé par l'identification
    $debut=dirname($_SERVER['PHP_SELF']);
    $self=basename($_SERVER['PHP_SELF']);
    header("Location: $debut/ident_util.php?url=$self");
    echo "<a href='$debut/ident_util.php?url=$self'>cliquez ici</a>";
    exit;
}

?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
    <head>
        <meta http-equiv="content-type"
              content="text/html; charset=ISO-8859-15">
        <title>fusion de tous les exams</title>
    </head>
    <body bgcolor=#FFE887>
<?php

require_once "Config.php";
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");

# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
    die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
    die("Impossible de se connecter à la base");

require("./global/entete.php");

// require_once "./writeexcel/class.writeexcel_workbookbig.inc.php";
// require_once "./writeexcel/class.writeexcel_worksheet.inc.php";

$titre="fusion de tous les exams";


entete_asalee($titre);

# initialisations
$nom = "";
$message=array();

# boucle principale
do {
    $repete=false;

    # étape 1 : identification de l'établissement
    if (!isset($_POST['etape'])) {
        etape_1($repete);
    }
    elseif($_POST['etape']==2) {
        # étape 2  : vérification du mot de passe et continuation vers l'url
        etape_2($repete);
    }
} while($repete);

exit;

# étape 1 : identification de l'établissement
function etape_1(&$repete) {
    global $message, $nom;


    extract($_POST);

    set_time_limit(0);


    ////////////////HDL/////////////////////////////
    $nb_hdl=0;
    $nb_ldl=0;
    $nb_poids=0;
    $nb_gly=0;
    $nb_albu=0;
    $nb_chol=0;
    $nb_creat=0;
    $nb_dent=0;
    $nb_tension=0;
    $nb_ECG=0;
    $nb_fond=0;
    $nb_hematurie=0;
    $nb_kaliemie=0;
    $nb_monofil=0;
    $nb_pied=0;
    $nb_pouls=0;
    $nb_proteinurie=0;
    $nb_triglycerides=0;

    $req="SELECT id, dHDL, HDL from cardio_vasculaire_depart WHERE ".
        "dHDL>'0000-00-00' and HDL>0 order by id, dHDL";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dHDL, $HDL)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='HDL' and date_exam='$dHDL'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='HDL', ".
                "date_exam='$dHDL', resultat1='$HDL'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
            $nb_hdl++;
        }
    }

    $req="SELECT dossier_id, dChol, HDL from suivi_diabete WHERE ".
        "dChol>'0000-00-00' and HDL>0 order by dossier_id, dChol";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dHDL, $HDL)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='HDL' and date_exam='$dHDL'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='HDL', ".
                "date_exam='$dHDL', resultat1='$HDL'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
            $nb_hdl++;

        }
    }



    //////////////////////LDL///////////////////////////

    $req="SELECT id, dLDL, LDL from cardio_vasculaire_depart WHERE ".
        "dLDL>'0000-00-00' and LDL>0 order by id, dLDL";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dLDL, $LDL)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='LDL' and date_exam='$dLDL'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='LDL', ".
                "date_exam='$dLDL', resultat1='$LDL'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_ldl++;
        }
    }

    $req="SELECT dossier_id, dLDL, LDL from suivi_diabete WHERE ".
        "dLDL>'0000-00-00' and LDL>0 order by dossier_id, dLDL";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dLDL, $LDL)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='LDL' and date_exam='$dLDL'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='LDL', ".
                "date_exam='$dLDL', resultat1='$LDL'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_ldl++;
        }
    }


    //////////////////////poids///////////////////////////

    $req="SELECT id, dpoids, poids from cardio_vasculaire_depart WHERE ".
        "dpoids>'0000-00-00' and poids>0 order by id, dpoids";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dpoids, $poids)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='poids' and date_exam='$dpoids'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='poids', ".
                "date_exam='$dpoids', resultat1='$poids'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_poids++;
        }
    }

    $req="SELECT dossier_id, dpoids, poids from suivi_diabete WHERE ".
        "dpoids>'0000-00-00' and poids>0 order by dossier_id, dpoids";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dpoids, $poids)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='poids' and date_exam='$dpoids'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='poids', ".
                "date_exam='$dpoids', resultat1='$poids'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_poids++;
        }
    }

    $req="SELECT id, dpoids, poids from depistage_diabete WHERE ".
        "dpoids>'0000-00-00' and poids>0 order by id, dpoids";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dpoids, $poids)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='poids' and date_exam='$dpoids'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='poids', ".
                "date_exam='$dpoids', resultat1='$poids'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_poids++;
        }
    }

    //////////////////////glycemie///////////////////////////

    $req="SELECT id, dgly, glycemie from cardio_vasculaire_depart WHERE ".
        "dgly>'0000-00-00' and glycemie>0 order by id, dgly";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dgly, $glycemie)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='glycemie' and date_exam='$dgly'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='glycemie', ".
                "date_exam='$dgly', resultat1='$glycemie'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_gly++;
        }
    }

    $req="SELECT id, derniere_gly_date, derniere_gly_resultat from depistage_diabete WHERE ".
        "derniere_gly_date>'0000-00-00' and derniere_gly_resultat>0 order by id, derniere_gly_date";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dgly, $glycemie)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='glycemie' and date_exam='$dgly'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='glycemie', ".
                "date_exam='$dgly', resultat1='$glycemie'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_gly++;
        }
    }


    //////////////////////Albu///////////////////////////


    $req="SELECT dossier_id, dAlbu, ialbu from suivi_diabete WHERE ".
        "dAlbu>'0000-00-00' order by dossier_id, dAlbu";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dAlbu, $ialbu)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='albu' and date_exam='$dAlbu'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='albu', ".
                "date_exam='$dAlbu', resultat1='$ialbu'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_albu++;
        }
    }

    //////////////////////Chol tot///////////////////////////

    $req="SELECT id, dChol, Chol from cardio_vasculaire_depart WHERE ".
        "dChol>'0000-00-00' and Chol>0 order by id, dChol";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dChol, $Chol)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='chol' and date_exam='$dChol'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='Chol', ".
                "date_exam='$dChol', resultat1='$Chol'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_chol++;
        }
    }


    //////////////////////Creat///////////////////////////

    $req="SELECT id, dCreat, Creat from cardio_vasculaire_depart WHERE ".
        "dCreat>'0000-00-00' and Creat>0 order by id, dCreat";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dCreat, $Creat)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='creat' and date_exam='$dCreat'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='creat', ".
                "date_exam='$dCreat', resultat1='$Creat'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_creat++;
        }
    }

    $req="SELECT dossier_id, dCreat, Creat, iCreat from suivi_diabete WHERE ".
        "dCreat>'0000-00-00' and Creat>0 order by dossier_id, dCreat";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dCreat, $Creat, $iCreat)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='creat' and date_exam='$dCreat'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='creat', ".
                "date_exam='$dCreat', resultat1='$Creat', resultat2='$iCreat'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_creat++;
        }
    }

    //////////////////////dentiste///////////////////////////


    $req="SELECT dossier_id, dentiste from suivi_diabete WHERE ".
        "dentiste>'0000-00-00' order by dossier_id, dentiste";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dentiste)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='dent' and date_exam='$dentiste'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='dent', ".
                "date_exam='$dentiste'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_dent++;
        }
    }


    //////////////////////diastole/systole/type tension///////////////////////////

    $req="SELECT id, dTA, TaSys, TaDia, TA_mode from cardio_vasculaire_depart WHERE ".
        "dTA>'0000-00-00' and TaSys>0 order by id, dTA";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dTA, $TaSys, $TaDia, $TA_mode)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='systole' and date_exam='$dTA'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='systole', ".
                "date_exam='$dTA', resultat1='$TaSys'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $req2="INSERT INTO liste_exam SET id='$id', type_exam='diastole', ".
                "date_exam='$dTA', resultat1='$TaDia'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $req2="INSERT INTO liste_exam SET id='$id', type_exam='type_tension', ".
                "date_exam='$dTA', resultat1='$TA_mode'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_tension++;
        }
    }

    $req="SELECT dossier_id, dtension, TaSys, TaDia, TA_mode from suivi_diabete WHERE ".
        "dtension>'0000-00-00' and TaSys>0 order by dossier_id, dtension";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dTA, $TaSys, $TaDia, $TA_mode)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='systole' and date_exam='$dTA'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='systole', ".
                "date_exam='$dTA', resultat1='$TaSys'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $req2="INSERT INTO liste_exam SET id='$id', type_exam='diastole', ".
                "date_exam='$dTA', resultat1='$TaDia'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $req2="INSERT INTO liste_exam SET id='$id', type_exam='type_tension', ".
                "date_exam='$dTA', resultat1='$TA_mode'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_tension++;
        }
    }

    //////////////////////ECG///////////////////////////

    $req="SELECT id, dECG from cardio_vasculaire_depart WHERE ".
        "dECG>'0000-00-00' order by id, dECG";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dECG)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='ECG' and date_exam='$dECG'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='ECG', ".
                "date_exam='$dECG'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_ECG++;
        }
    }

    $req="SELECT dossier_id, dECG, iECG from suivi_diabete WHERE ".
        "dECG>'0000-00-00' order by dossier_id, dECG";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dECG, $iECG)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='ECG' and date_exam='$dECG'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='ECG', ".
                "date_exam='$dECG', resultat1='$iECG'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_ECG++;
        }
    }


    //////////////////////Fond Oeil///////////////////////////

    $req="SELECT id, dFond from cardio_vasculaire_depart WHERE ".
        "dFond>'0000-00-00' order by id, dFond";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dFond)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='fond' and date_exam='$dFond'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='fond', ".
                "date_exam='$dFond'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_fond++;
        }
    }

    $req="SELECT dossier_id, dFond, iFond from suivi_diabete WHERE ".
        "dFond>'0000-00-00' order by dossier_id, dFond";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dFond, $iFond)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='fond' and date_exam='$dFond'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='fond', ".
                "date_exam='$dFond', resultat1='$iFond'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_fond++;
        }
    }


    //////////////////////hematurie///////////////////////////

    $req="SELECT id, dhematurie, hematurie from cardio_vasculaire_depart WHERE ".
        "dhematurie>'0000-00-00' order by id, dhematurie";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dhematurie, $hematurie)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='hematurie' and date_exam='$dhematurie'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='hematurie', ".
                "date_exam='$dhematurie', resultat1='$hematurie'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_hematurie++;
        }
    }

    //////////////////////kaliemie///////////////////////////

    $req="SELECT id, dkaliemie, kaliemie from cardio_vasculaire_depart WHERE ".
        "dkaliemie>'0000-00-00' order by id, dkaliemie";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dkaliemie, $kaliemie)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='kaliemie' and date_exam='$dkaliemie'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='kaliemie', ".
                "date_exam='$dkaliemie', resultat1='$kaliemie'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_kaliemie++;
        }
    }


    //////////////////////monofil///////////////////////////


    $req="SELECT dossier_id, dExaFil from suivi_diabete WHERE ".
        "dExaFil>'0000-00-00' order by dossier_id, dExaFil";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dExaFil)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='monofil' and date_exam='$dExaFil'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='monofil', ".
                "date_exam='$dExaFil'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_monofil++;
        }
    }

    //////////////////////ExaPied///////////////////////////


    $req="SELECT dossier_id, dExaPieds from suivi_diabete WHERE ".
        "dExaPieds>'0000-00-00' order by dossier_id, dExaPieds";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dExaPieds)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='pied' and date_exam='$dExaPieds'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='pied', ".
                "date_exam='$dExaPieds'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_pied++;
        }
    }


    //////////////////////pouls///////////////////////////

    $req="SELECT id, dpouls, pouls from cardio_vasculaire_depart WHERE ".
        "dpouls>'0000-00-00' and pouls>0 order by id, dpouls";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dpouls, $pouls)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='pouls' and date_exam='$dpouls'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='pouls', ".
                "date_exam='$dpouls', resultat1='$pouls'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_pouls++;
        }
    }


    //////////////////////protéinurie///////////////////////////

    $req="SELECT id, dproteinurie, proteinurie from cardio_vasculaire_depart WHERE ".
        "dproteinurie>'0000-00-00' order by id, dproteinurie";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dproteinurie, $proteinurie)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='proteinurie' and date_exam='$dproteinurie'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='proteinurie', ".
                "date_exam='$dproteinurie', resultat1='$proteinurie'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_proteinurie++;
        }
    }


    //////////////////////triglycerides///////////////////////////

    $req="SELECT id, dtriglycerides, triglycerides from cardio_vasculaire_depart WHERE ".
        "dtriglycerides>'0000-00-00' and triglycerides>0 order by id, dtriglycerides";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dtriglycerides, $triglycerides)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='triglycerides' and date_exam='$dtriglycerides'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='triglycerides', ".
                "date_exam='$dtriglycerides', resultat1='$triglycerides'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $nb_triglycerides++;
        }
    }

    echo "fin de la fusion : <br>
	$nb_hdl HDL intégrés ; <br>
	$nb_ldl LDL intégrés ; <br>
	$nb_poids poids intégrés ;<br>
	$nb_gly glycemie; <br>
	$nb_albu albu;<br>
	$nb_chol chol tot;<br>
	$nb_creat creat;<br>
	$nb_dent dentiste;<br>
	$nb_tension tension;<br>
	$nb_ECG ECG; <br>
	$nb_fond fond oeil;<br>
	$nb_hematurie hematurie;<br>
	$nb_kaliemie kaliemie;<br>
	$nb_monofil monofil;<br>
	$nb_pied pied;<br>
	$nb_pouls pouls;<br>
	$nb_proteinurie proteinurie;<br>
	$nb_triglycerides trigly;
";
}
?>