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
        <title>nb consult A Duez et c. Iung</title>
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

$titre="Nb consult A. Duez et C. Iung";


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
    echo "<table>";
    echo "<tr><td rowspan='2'></td><td colspan='6'>Du 01/07/2009 au 30/06/2010</td><td colspan='6'>du 01/07/2010 au 30/06/2011</td></tr>".
        "<tr><td>Suivi diabète</Td><td>dépistage diabète</Td><td>RCVA</td><td>hémoccult</td><td>automesure</td><td>total</td>".
        "<td>Suivi diabète</Td><td>dépistage diabète</Td><td>RCVA</td><td>hémoccult</td><td>automesure</td><td>total</td></tr>";

    $req="SELECT cabinet, nom_cab, infirmiere from account WHERE infirmiere='Anne Duez' ".
        "or infirmiere='Christine Iung' order by nom_cab ";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");


    while(list($cabinet, $nom_cab, $infirmiere)=mysql_fetch_row($res)){
        $titres[$cabinet]="$nom_cab ($infirmiere)";
        $dep[1][$cabinet]=0;
        $dep[2][$cabinet]=0;
        $suivi[1][$cabinet]=0;
        $suivi[2][$cabinet]=0;
        $rcva[1][$cabinet]=0;
        $rcva[2][$cabinet]=0;
        $hemoccult[1][$cabinet]=0;
        $hemoccult[2][$cabinet]=0;
        $auto[1][$cabinet]=0;
        $auto[2][$cabinet]=0;
        $total[1][$cabinet]=0;
        $total[2][$cabinet]=0;
    }

    $req="SELECT cabinet, date, dossier.id, type_consultation from evaluation_infirmier, dossier ".
        "where dossier.id=evaluation_infirmier.id ".
        "and date>'2009-06-30' and date<'2011-07-01' ";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($cabinet, $date, $id, $type_consult)=mysql_fetch_row($res)){
        if(isset($titres[$cabinet])){
            if(strpos($type_consult, "dep_diab")!==false){
                if($date<"2010-07-01"){
                    $dep[1][$cabinet]=$dep[1][$cabinet]+1;
                    $total[1][$cabinet]=$total[1][$cabinet]+1;
                }
                else{
                    $dep[2][$cabinet]=$dep[2][$cabinet]+1;
                    $total[2][$cabinet]=$total[2][$cabinet]+1;
                }
            }

            if(strpos($type_consult, "suivi_diab")!==false){
                if($date<"2010-07-01"){
                    $suivi[1][$cabinet]=$suivi[1][$cabinet]+1;
                    $total[1][$cabinet]=$total[1][$cabinet]+1;
                }
                else{
                    $suivi[2][$cabinet]=$suivi[2][$cabinet]+1;
                    $total[2][$cabinet]=$total[2][$cabinet]+1;
                }
            }

            if(strpos($type_consult, "rcva")!==false){
                if($date<"2010-07-01"){
                    $rcva[1][$cabinet]=$rcva[1][$cabinet]+1;
                    $total[1][$cabinet]=$total[1][$cabinet]+1;
                }
                else{
                    $rcva[2][$cabinet]=$rcva[2][$cabinet]+1;
                    $total[2][$cabinet]=$total[2][$cabinet]+1;
                }
            }

            if(strpos($type_consult, "hemocult")!==false){
                if($date<"2010-07-01"){
                    $hemoccult[1][$cabinet]=$hemoccult[1][$cabinet]+1;
                    $total[1][$cabinet]=$total[1][$cabinet]+1;
                }
                else{
                    $hemoccult[2][$cabinet]=$hemoccult[2][$cabinet]+1;
                    $total[2][$cabinet]=$total[2][$cabinet]+1;
                }
            }

            if(strpos($type_consult, "automesure")!==false){
                if($date<"2010-07-01"){
                    $auto[1][$cabinet]=$auto[1][$cabinet]+1;
                    $total[1][$cabinet]=$total[1][$cabinet]+1;
                }
                else{
                    $auto[2][$cabinet]=$auto[2][$cabinet]+1;
                    $total[2][$cabinet]=$total[2][$cabinet]+1;
                }
            }

        }
    }

    foreach($titres as $cab=>$titre){
        echo "<tr><td>$titre</td><td>".$suivi[1][$cab]."</td><td>".$dep[1][$cab]."</td>".
            "<td>".$rcva[1][$cab]."</td><td>".$hemoccult[1][$cab]."</td>".
            "<td>".$auto[1][$cab]."</td><td>".$total[1][$cab]."</td>".
            "<td>".$suivi[2][$cab]."</td><td>".$dep[2][$cab]."</td>".
            "<td>".$rcva[2][$cab]."</td><td>".$hemoccult[2][$cab]."</td>".
            "<td>".$auto[2][$cab]."</td><td>".$total[2][$cab]."</td></tr>";
    }
    echo "</table>";
    exit;
}
?>