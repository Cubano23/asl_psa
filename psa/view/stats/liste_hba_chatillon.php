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
        <title>Liste des HBA1c à Chatillon et sur export</title>
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

$titre="Liste des HBA1c à Chatillon et sur export";


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
    $fich="./export/Liste HBA1c enregistres et non enregistres.xls";
    $workbook =& new writeexcel_workbookbig($fich); // on lui passe en paramètre le chemin de notre fichier

    $worksheet_asalee =& $workbook->addworksheet("données uniquement dans asalée");
    $worksheet_asalee->write("A1", "n° dossier");
    $worksheet_asalee->write("B1", "date");
    $worksheet_asalee->write("C1", "valeur");


    /*	$fichier="VarCheminUserData]kSuivi.txt";

        $fp=fopen("$fichier", "r");

        $zones=array("numero", "date", "type", "val");
        $zone=0;
        $numero=$date=$type=$val="";

        while(!feof($fp)){
            $car=fread($fp, 1);

            if($car=="\t"){//on change de zone de texte
                $zone++;
            }
            elseif($car=="\n"){
                $zone=0;

                if($type=="PA_A1C         %"){//Il s'agit d'un examen HBA1c => intégration dans une table temporaire
                    $date=explode("/", $date);
                    if($date[0]<10){
                        $date[0]="0".$date[0];
                    }
                    if($date[1]<10){
                        $date[1]="0".$date[1];
                    }
                    if($date[2]<10){
                        $date[2]="200".$date[2];
                    }
                    elseif($date[2]<15){
                        $date[2]="20".$date[2];
                    }
                    else{
                        $date[2]="19".$date[2];
                    }

                    $date=$date[2]."-".$date[1]."-".$date[0];
                    $req="SELECT id from dossier WHERE cabinet='Chatillon' ".
                         "and numero='N$numero'";
                    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
                    if(mysql_num_rows($res)==1){
                        list($id)=mysql_fetch_row($res);
                    }
                    else{
                        $id="";
                    }

                    $req="INSERT INTO exam_chatillon SET exam='HBA1c', numero='$numero', ".
                         "date_exam='$date', valeur='".str_replace(" ", "", $val)."', id='$id'";
                    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
                }

                $numero=$date=$type=$val="";
            }
            else{//Tous les caractères "normaux"
                if($zone==0){
                    $numero.=$car;
                }
                if($zone==1){
                    $date=$date.$car;
                }
                if($zone==2){
                    $type=$type.$car;
                }
                if($zone==3){
                    if((is_numeric($car))||($car==".")){
                        $val=$val.$car;
                    }
                }
            }
        }
    */
    //Intégration des données présentes uniquement dans l'export
    $req="SELECT id, numero, date_format(date_exam, '%d/%m/%Y'), date_exam, valeur ".
        "from exam_chatillon WHERE id!=''";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $numero, $date_exam, $dexam, $valeur)=mysql_fetch_row($res)){
        $dateexam=explode("-", $dexam);
        $date_avant=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]-15, $dateexam[0]));
        $date_apres=date("Y-m-d", mktime(1, 1, 1, $dateexam[1]  , $dateexam[2]+15, $dateexam[0]));

        $req2="SELECT dossier_id from suivi_diabete where dossier_id='$id' ".
            "and dhba>'$date_avant' and dhba<'$date_apres'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){//La donnée n'est pas présente dans asalée
            $l++;
            $dsuivi=date("Y-m-d", mktime(1, 1, 1, date("m")  , date("d"), date("Y")));
            $req2="SELECT dsuivi from suivi_diabete where dossier_id='$id' and dsuivi='$dsuivi'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
            $i=0;

            while(mysql_num_rows($res2)==1){
                $i++;
                $dsuivi=date("Y-m-d", mktime(1, 1, 1, date("m")  , date("d")-$i, date("Y")));
                $req2="SELECT dsuivi from suivi_diabete where dossier_id='$id' and dsuivi='$dsuivi'";
                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
            }

            $req2="INSERT INTO suivi_diabete SET dossier_id='$id', ".
                "dsuivi='$dsuivi', dHBA='$dexam', resHBA='$valeur'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
        }
    }


    $worksheet_chat =& $workbook->addworksheet("données uniquement dans export");
    $worksheet_chat->write("A1", "n° dossier export");
    $worksheet_chat->write("B1", "date");
    $worksheet_chat->write("C1", "valeur");
    $worksheet_chat->write("D1", "N° dossier dans asalée");

    //Recherche des examens uniquement dans le fichier texte => d'abord les examens pour lesquels le dossier n'est pas reconnu
    $req="SELECT numero, date_format(date_exam, '%d/%m/%Y'), valeur ".
        "from exam_chatillon WHERE id=''";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    $l=1;
    while(list($numero, $date_exam, $valeur)=mysql_fetch_row($res)){
        $l++;
        $worksheet_chat->write("A$l", $numero);
        $worksheet_chat->write("B$l", $date_exam);
        $worksheet_chat->write("C$l", $valeur);
    }

    //Recherche des examens uniquement dans le fichier texte => Parcours des différents résultats et recherche sur ce qui est enregistré
    $req="SELECT id, numero, date_format(date_exam, '%d/%m/%Y'), date_exam, valeur ".
        "from exam_chatillon WHERE id!=''";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    while(list($id, $numero, $date_exam, $dexam, $valeur)=mysql_fetch_row($res)){
        $dexam=explode("-", $dexam);
        $date_avant=date("Y-m-d", mktime(1, 1, 1, $dexam[1]  , $dexam[2]-15, $dexam[0]));
        $date_apres=date("Y-m-d", mktime(1, 1, 1, $dexam[1]  , $dexam[2]+15, $dexam[0]));

        $req2="SELECT dossier_id from suivi_diabete where dossier_id='$id' ".
            "and dhba>'$date_avant' and dhba<'$date_apres'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $l++;
            $req2="SELECT numero from dossier where id='$id'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
            list($dossier_asalee)=mysql_fetch_row($res2);
            $worksheet_chat->write("A$l", $numero);
            $worksheet_chat->write("B$l", $date_exam);
            $worksheet_chat->write("C$l", $valeur);
            $worksheet_chat->write("D$l", $dossier_asalee);
        }
    }

    $worksheet_commun =& $workbook->addworksheet("données communes");
    $worksheet_commun->write("A1", "n° dossier export");
    $worksheet_commun->write("B1", "date dans le fichier texte");
    $worksheet_commun->write("C1", "valeur dans le fichier texte");
    $worksheet_commun->write("D1", "date dans asalée");
    $worksheet_commun->write("E1", "valeur dans asalée");
    $worksheet_commun->write("F1", "N° dossier dans asalée");

    //Recherche des examens dans les 2 fichiers
    $req="SELECT numero, dossier_id, date_format(date_exam, '%d/%m/%Y'), valeur, ".
        "date_format(dhba, '%d/%m/%Y'), ResHBA ".
        "from exam_chatillon, suivi_diabete WHERE id=dossier_id and ".
        "datediff(dhba,date_exam)<15 and datediff(dhba,date_exam)>-15 ";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    $l=1;
    while(list($numero, $id, $date_exam, $valeur, $dHBA, $resHBA)=mysql_fetch_row($res)){
        $l++;
        $req2="SELECT numero from dossier where id='$id'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
        list($dossier_asalee)=mysql_fetch_row($res2);

        $worksheet_commun->write("A$l", $numero);
        $worksheet_commun->write("B$l", $date_exam);
        $worksheet_commun->write("C$l", $valeur);
        $worksheet_commun->write("D$l", $dHBA);
        $worksheet_commun->write("E$l", $resHBA);
        $worksheet_commun->write("F$l", $dossier_asalee);
    }

    //Recherche des examens uniquement dans asalée
    $req="SELECT numero, id, date_format(dHBA, '%d/%m/%Y'), dHBA, ResHBA ".
        "from suivi_diabete, dossier WHERE id=dossier_id and ".
        "cabinet='Chatillon' and dhba>'2009-05-04'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    $l=1;
    while(list($numero, $id, $date_exam, $dHBA, $resHBA)=mysql_fetch_row($res)){
        $dexam=explode("-", $dHBA);
        $date_avant=date("Y-m-d", mktime(1, 1, 1, $dexam[1]  , $dexam[2]-15, $dexam[0]));
        $date_apres=date("Y-m-d", mktime(1, 1, 1, $dexam[1]  , $dexam[2]+15, $dexam[0]));
        $req2="SELECT * from exam_chatillon where date_exam>'$date_avant' ".
            "and date_exam<'$date_apres' and id='$id'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");

        if(mysql_num_rows($res2)==0){
            $l++;
            $worksheet_asalee->write("A$l", $numero);
            $worksheet_asalee->write("B$l", $date_exam);
            $worksheet_asalee->write("C$l", $resHBA);
        }
    }

    $workbook->close();

    echo "<a href='$fich' target='_blank'>$fich</a>";
    exit;
}
?>