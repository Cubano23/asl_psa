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
        <title>fusion des HBA1c</title>
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

$titre="fusion des HBA1c";


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

    $req="SELECT dossier_id, dHBA, ResHBA from suivi_diabete WHERE ".
        "dHBA>'0000-00-00' and ResHBA>0 order by dossier_id, dHBA";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $dHBA, $HBA)=mysql_fetch_row($res)){
        $req2="SELECT id from liste_exam WHERE ".
            "id='$id' and type_exam='HBA1c' and date_exam='$dHBA'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $req2="INSERT INTO liste_exam SET id='$id', type_exam='HBA1c', ".
                "date_exam='$dHBA', resultat1='$HBA'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
        }
    }



    echo "fin de la fusion";
}
?>