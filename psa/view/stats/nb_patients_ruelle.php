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
        <title>nb patients Ruelle</title>
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

require_once "./writeexcel/class.writeexcel_workbookbig.inc.php";
require_once "./writeexcel/class.writeexcel_worksheet.inc.php";

$titre="Nb patients Ruelle";


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
    $fich="./export/Nb patients Ruelle.xls";
    $workbook =& new writeexcel_workbookbig($fich); // on lui passe en paramètre le chemin de notre fichier

    $worksheet =& $workbook->addworksheet("Patients inclus par protocole");
    $worksheet->write("A1", "");

    $colonnes=array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K");
    $col=1;

    $req="SELECT nom, prenom from medecin_ruelle group by nom, prenom ".
        "order by nom, prenom";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($nom, $prenom)=mysql_fetch_row($res)){
        $cellule=$colonnes[$col]."1";
        $worksheet->write("$cellule", "$nom $prenom");
        $colonne["$nom $prenom"]=$colonnes[$col];
        $col++;
    }

    $cellule=$colonnes[$col]."1";
    $colonne["nr"]=$colonnes[$col];
    $worksheet->write("$cellule", "Médecin non renseigné");


    $req="SELECT nom, prenom from cardio_vasculaire_depart as c, dossier ".
        "LEFT JOIN medecin_ruelle ON dossier.numero=medecin_ruelle.numero ".
        "WHERE cabinet='Ruelle' and actif='oui' and c.id=dossier.id ".
        "group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($nom, $prenom)=mysql_fetch_row($res)){
        if(!isset($rcva["$nom $prenom"])){
            $rcva["$nom $prenom"]=0;
        }
        $rcva["$nom $prenom"]=$rcva["$nom $prenom"]+1;
    }

    $req="SELECT nom, prenom from depistage_colon as c, dossier ".
        "LEFT JOIN medecin_ruelle ON dossier.numero=medecin_ruelle.numero ".
        "WHERE cabinet='Ruelle' and actif='oui' and c.id=dossier.id ".
        "group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($nom, $prenom)=mysql_fetch_row($res)){
        if(!isset($colon["$nom $prenom"])){
            $colon["$nom $prenom"]=0;
        }
        $colon["$nom $prenom"]=$colon["$nom $prenom"]+1;
    }

    $req="SELECT nom, prenom from depistage_diabete as c, dossier ".
        "LEFT JOIN medecin_ruelle ON dossier.numero=medecin_ruelle.numero ".
        "WHERE cabinet='Ruelle' and actif='oui' and c.id=dossier.id ".
        "group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($nom, $prenom)=mysql_fetch_row($res)){
        if(!isset($dep_diab["$nom $prenom"])){
            $dep_diab["$nom $prenom"]=0;
        }
        $dep_diab["$nom $prenom"]=$dep_diab["$nom $prenom"]+1;
    }

    $req="SELECT nom, prenom from depistage_sein as c, dossier ".
        "LEFT JOIN medecin_ruelle ON dossier.numero=medecin_ruelle.numero ".
        "WHERE cabinet='Ruelle' and actif='oui' and c.id=dossier.id ".
        "group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($nom, $prenom)=mysql_fetch_row($res)){
        if(!isset($sein["$nom $prenom"])){
            $sein["$nom $prenom"]=0;
        }
        $sein["$nom $prenom"]=$sein["$nom $prenom"]+1;
    }

    $req="SELECT nom, prenom from depistage_uterus as c, dossier ".
        "LEFT JOIN medecin_ruelle ON dossier.numero=medecin_ruelle.numero ".
        "WHERE cabinet='Ruelle' and actif='oui' and c.id=dossier.id ".
        "group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($nom, $prenom)=mysql_fetch_row($res)){
        if(!isset($uterus["$nom $prenom"])){
            $uterus["$nom $prenom"]=0;
        }
        $uterus["$nom $prenom"]=$uterus["$nom $prenom"]+1;
    }

    $req="SELECT nom, prenom from hemocult as c, dossier ".
        "LEFT JOIN medecin_ruelle ON dossier.numero=medecin_ruelle.numero ".
        "WHERE cabinet='Ruelle' and actif='oui' and c.id=dossier.id ".
        "group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($nom, $prenom)=mysql_fetch_row($res)){
        if(!isset($hemoccult["$nom $prenom"])){
            $hemoccult["$nom $prenom"]=0;
        }
        $hemoccult["$nom $prenom"]=$hemoccult["$nom $prenom"]+1;
    }

    $req="SELECT nom, prenom from suivi_diabete as c, dossier ".
        "LEFT JOIN medecin_ruelle ON dossier.numero=medecin_ruelle.numero ".
        "WHERE cabinet='Ruelle' and actif='oui' and c.dossier_id=dossier.id ".
        "group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($nom, $prenom)=mysql_fetch_row($res)){
        if(!isset($diab["$nom $prenom"])){
            $diab["$nom $prenom"]=0;
        }
        $diab["$nom $prenom"]=$diab["$nom $prenom"]+1;
    }

    $req="SELECT nom, prenom from tension_arterielle_moyenne as c, dossier ".
        "LEFT JOIN medecin_ruelle ON dossier.numero=medecin_ruelle.numero ".
        "WHERE cabinet='Ruelle' and actif='oui' and c.id=dossier.id ".
        "group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($nom, $prenom)=mysql_fetch_row($res)){
        if(!isset($automesure["$nom $prenom"])){
            $automesure["$nom $prenom"]=0;
        }
        $automesure["$nom $prenom"]=$automesure["$nom $prenom"]+1;
    }

    $req="SELECT nom, prenom from trouble_cognitif as c, dossier ".
        "LEFT JOIN medecin_ruelle ON dossier.numero=medecin_ruelle.numero ".
        "WHERE cabinet='Ruelle' and actif='oui' and c.id=dossier.id ".
        "group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($nom, $prenom)=mysql_fetch_row($res)){
        if(!isset($cognitif["$nom $prenom"])){
            $cognitif["$nom $prenom"]=0;
        }
        $cognitif["$nom $prenom"]=$cognitif["$nom $prenom"]+1;
    }

    $req="SELECT nom, prenom from dossier ".
        "LEFT JOIN medecin_ruelle ON dossier.numero=medecin_ruelle.numero ".
        "WHERE cabinet='Ruelle' and actif='oui' ".
        "group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($nom, $prenom)=mysql_fetch_row($res)){
        if(!isset($dossier["$nom $prenom"])){
            $dossier["$nom $prenom"]=0;
        }
        $dossier["$nom $prenom"]=$dossier["$nom $prenom"]+1;
    }


    $worksheet->write("A2", "Nb dossiers total");
    // print_r($dossier);print_r($colonne);die;
    foreach($dossier as $nom=>$nb){
        if(isset($colonne[$nom])){
            $cellule=$colonne[$nom]."2";
            $worksheet->write("$cellule", $nb);
        }
        else{
            $cellule=$colonne["nr"]."2";
            $worksheet->write("$cellule", $nb);
        }
    }

    $worksheet->write("A3", "Nb dossiers dépistage diabète");

    foreach($dep_diab as $nom=>$nb){
        if(isset($colonne[$nom])){
            $cellule=$colonne[$nom]."3";
            $worksheet->write("$cellule", $nb);
        }
        else{
            $cellule=$colonne["nr"]."3";
            $worksheet->write("$cellule", $nb);
        }
    }

    $worksheet->write("A4", "Nb dossiers suivi diabète");

    foreach($diab as $nom=>$nb){
        if(isset($colonne[$nom])){
            $cellule=$colonne[$nom]."4";
            $worksheet->write("$cellule", $nb);
        }
        else{
            $cellule=$colonne["nr"]."4";
            $worksheet->write("$cellule", $nb);
        }
    }

    $worksheet->write("A5", "Nb dossiers RCVA");

    foreach($rcva as $nom=>$nb){
        if(isset($colonne[$nom])){
            $cellule=$colonne[$nom]."5";
            $worksheet->write("$cellule", $nb);
        }
        else{
            $cellule=$colonne["nr"]."5";
            $worksheet->write("$cellule", $nb);
        }
    }

    $worksheet->write("A6", "Nb dossiers automesure");

    foreach($automesure as $nom=>$nb){
        if(isset($colonne[$nom])){
            $cellule=$colonne[$nom]."6";
            $worksheet->write("$cellule", $nb);
        }
        else{
            $cellule=$colonne["nr"]."6";
            $worksheet->write("$cellule", $nb);
        }
    }

    $worksheet->write("A7", "Nb dossiers dépistage cancer sein");

    foreach($sein as $nom=>$nb){
        if(isset($colonne[$nom])){
            $cellule=$colonne[$nom]."7";
            $worksheet->write("$cellule", $nb);
        }
        else{
            $cellule=$colonne["nr"]."7";
            $worksheet->write("$cellule", $nb);
        }
    }

    $worksheet->write("A8", "Nb dossiers dépistage cancer colon");

    foreach($colon as $nom=>$nb){
        if(isset($colonne[$nom])){
            $cellule=$colonne[$nom]."8";
            $worksheet->write("$cellule", $nb);
        }
        else{
            $cellule=$colonne["nr"]."8";
            $worksheet->write("$cellule", $nb);
        }
    }

    $worksheet->write("A9", "Nb dossiers dépistage hémoccult");

    foreach($hemoccult as $nom=>$nb){
        if(isset($colonne[$nom])){
            $cellule=$colonne[$nom]."9";
            $worksheet->write("$cellule", $nb);
        }
        else{
            $cellule=$colonne["nr"]."9";
            $worksheet->write("$cellule", $nb);
        }
    }

    $worksheet->write("A10", "Nb dossiers dépistage cancer col de l'utérus");

    foreach($uterus as $nom=>$nb){
        if(isset($colonne[$nom])){
            $cellule=$colonne[$nom]."10";
            $worksheet->write("$cellule", $nb);
        }
        else{
            $cellule=$colonne["nr"]."10";
            $worksheet->write("$cellule", $nb);
        }
    }

    $worksheet->write("A11", "Nb dossiers dépistage troubles cognitifs");

    foreach($uterus as $nom=>$nb){
        if(isset($colonne[$nom])){
            $cellule=$colonne[$nom]."11";
            $worksheet->write("$cellule", $nb);
        }
        else{
            $cellule=$colonne["nr"]."11";
            $worksheet->write("$cellule", $nb);
        }
    }

    $workbook->close();

    echo "<a href='$fich' target='_blank'>$fich</a>";
    exit;
}
?>