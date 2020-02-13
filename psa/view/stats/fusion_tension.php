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
        <title>fusion des tensions</title>
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

$titre="fusion des tensions";


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

    $req="SELECT id, dTA, TaSys, TaDia, TA_mode from cardio_vasculaire_depart WHERE ".
        "dpoids>'0000-00-00' and poids>0 order by id, dTA";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    $id_prec=$date_prec="";
    while(list($id, $dTA, $TaSys, $TaDia, $TA_mode)=mysql_fetch_row($res)){
        if(($id_prec!=$id)||($date_prec!=$dTA)){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='systole', ".
                "date_exam='$dTA', resultat1='$TaSys'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $req2="INSERT INTO liste_exam SET id='$id', type_exam='diastole', ".
                "date_exam='$dTA', resultat1='$TaDia'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

            $req2="INSERT INTO liste_exam SET id='$id', type_exam='type_tension', ".
                "date_exam='$dTA', resultat1='$TA_mode'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
            $id_prec=$id;
            $date_prec=$dTA;
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
        }
    }


    echo "fin de la fusion";
}
?>