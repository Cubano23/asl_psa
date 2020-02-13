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

    $nb_ldl=$nb_hdl=0;
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


    echo "fin de la fusion : $nb_hdl HDL intégrés ; $nb_ldl LDL intégrés";
}
?>