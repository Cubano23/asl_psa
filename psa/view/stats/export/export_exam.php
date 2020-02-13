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
set_time_limit(0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="content-type"
          content="text/html; charset=ISO-8859-15">
    <title>Exporter des examens</title>
</head>
<body bgcolor=#FFE887>
<?php
require_once "Config.php";
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");
require_once "../writeexcel/class.writeexcel_workbookbig.inc.php";
require_once "../writeexcel/class.writeexcel_worksheet.inc.php";

# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
die("Impossible de se connecter à la base");


$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";

require("../global/entete.php");
//echo $loc;

entete_asalee("Exporter des examens");

//echo $loc;
?>

<br><br>
<?php

# boucle principale
do {
    $repete=false;


    # étape 1 : Choix de l'exam
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {
            //choix de l'exam
            case 1:
                etape_1($repete);
                break;

            # étape 2  : création fichier excel
            case 2:
                etape_2($repete);
                break;

        }
    }
} while($repete);

# fin de traitement principal

//Choix de l'exam
function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville;

    if(sizeof($message)==0){
        $exam="";

    }
    else{
        echo "<font style='color:red'><b>";

        foreach($message as $m){
            echo $m."<br>";
        }
        echo "</b></font>";
        extract($_POST);
    }

    $liste_exam=array("HDL"=>"Cholestérol HDL", "LDL"=>"Cholestérol LDL", "Chol"=>"Cholestérol total",
        "creat"=>"Créatinine", "ECG"=>"ECG", "monofil"=> "Examen au monofilament",
        "pied"=>"Examen des pieds", "fond"=>"Fond d'oeil", "glycemie"=>"Glycémie à jeun",
        "HBA1c"=>"HBA1c", "hematurie"=>"Hématurie", "kaliemie"=>"Kaliémie",
        "albu"=>"Micro-albuminurie", "Poids"=>"poids", "Pouls"=>"Pouls", "proteinurie"=>"Protéinurie",
        "dent"=>"RDV dentiste", "tension"=>"Tension", "triglycerides"=>"Triglycérides");

    ?>
    <table>
        <form method="post" action="<?php echo $self;?>" >
            <input type='hidden' name='etape' value='2'>
            <tr>
                <td>Examen à exporter</font>
                </td>
                <td><select name='exam'><option value=''></option>
                        <?php
                        foreach($liste_exam as $code=>$lib){
                            echo "<option value='$code'>$lib</option>";
                        }

                        ?>
                </td>
            </tr>
            <tr><td colspan='2'><input type='submit' value='Exporter'>

        </form>
    </table>
    <?php
}

//Création fichier excel
function etape_2(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville;

    $liste_exam=array("HDL"=>"Cholestérol HDL", "LDL"=>"Cholestérol LDL", "Chol"=>"Cholestérol total",
        "creat"=>"Créatinine", "ECG"=>"ECG", "monofil"=> "Examen au monofilament",
        "pied"=>"Examen des pieds", "fond"=>"Fond d'oeil", "glycemie"=>"Glycémie à jeun",
        "HBA1c"=>"HBA1c", "hematurie"=>"Hématurie", "kaliemie"=>"Kaliémie",
        "albu"=>"Micro-albuminurie", "Poids"=>"poids", "Pouls"=>"Pouls", "proteinurie"=>"Protéinurie",
        "dent"=>"RDV dentiste", "tension"=>"Tension", "triglycerides"=>"Triglycérides");

    extract($_POST);

    if($exam==""){
        $message[]="Veuillez sélectionner un examen à exporter";
    }

    if(sizeof($message)>0){
        $_POST["etape"]=1;
        $repete=true;
        return;
    }




    if($exam!="tension"){//Tous les examens sauf tension
        $req="SELECT dossier.id, dossier.numero, account.nom_cab, date_format(`date_exam`, '%d/%m/%Y'), resultat1 ".
            "FROM `liste_exam` , dossier, account ".
            "WHERE dossier.id = liste_exam.id AND account.cabinet=dossier.cabinet and region != '' and ".
            "type_exam='$exam' and date_exam!='0000-00-00' and dossier.actif='oui'";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
    }
    else{
        $req="SELECT dossier.id, dossier.numero, account.nom_cab, date_format(`date_exam`, '%d/%m/%Y'), resultat1 ".
            "FROM `liste_exam` , dossier, account ".
            "WHERE dossier.id = liste_exam.id AND account.cabinet=dossier.cabinet and region != '' and ".
            "type_exam='systole' and date_exam!='0000-00-00' and dossier.actif='oui'";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
    }
    echo $req;
// echo mysql_num_rows($res);
    $fichier="../export/Liste des $exam ".date("dmY").".xls";
    $i=1;
    while(file_exists($fichier)){
        $fichier="../export/Liste des $exam ".date("dmY").".$i.xls";
        $i++;
    }

    $workbook =& new writeexcel_workbookbig($fichier); // on lui passe en paramètre le chemin de notre fichier
    $worksheet =& $workbook->addworksheet("Liste $exam");
    $worksheet->write("A1", "id");
    $worksheet->write("B1", "numéro");
    $worksheet->write("C1", "cabinet");
    $worksheet->write("D1", "date ".$liste_exam[$exam]);

    if($exam!="tension"){
        $worksheet->write("E1", "résultat examen");
    }
    else{
        $worksheet->write("E1", "Systole");
        $worksheet->write("F1", "Diastole");
    }
// $worksheet->write("P1", "date maj");
    $l=1;
    $page = 1;
    while(list($id, $numero, $nom_cab, $date_exam, $resultat1)=mysql_fetch_row($res)){
        $l++;
        if($l>65000){
            $page++;
            $worksheet =& $workbook->addworksheet("Liste $exam ". $page);      // correction bug nom page 06-11-2014 EA
            $worksheet->write("A1", "id");
            $worksheet->write("B1", "numéro");
            $worksheet->write("C1", "cabinet");
            $worksheet->write("D1", "date ".$liste_exam[$exam]);

            if($exam!="tension"){
                $worksheet->write("E1", "résultat examen");
            }
            else{
                $worksheet->write("E1", "Systole");
                $worksheet->write("F1", "Diastole");
            }
            $l=2;
        }
        $worksheet->write("A$l", "$id");
        $worksheet->write_string("B$l", "$numero");
        $worksheet->write("C$l", "$nom_cab");
        $worksheet->write("D$l", "$date_exam");
        $resultat1=str_replace(" ", "", $resultat1);
        $resultat1=str_replace("\t", "", $resultat1);
        $resultat1=str_replace("\r", "", $resultat1);
        $resultat1=str_replace("\n", "", $resultat1);
        $worksheet->write("E$l", "$resultat1");

        if($exam=="tension"){
            $dexam=explode("/", $date_exam);
            $dexam=$dexam[2]."-".$dexam[1]."-".$dexam[0];
            $req2="SELECT resultat1 ".
                "FROM `liste_exam` ".
                "WHERE type_exam='diastole' and id='$id' and date_exam='$dexam'";

            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            list($resultat1)=mysql_fetch_row($res2);
            $worksheet->write("F$l", "$resultat1");
        }

    }

    /*
     * Pierre Dufour - 04/08/15
     * Ajout des auto-mesures tentionnelles dans l'export
     * TODO : a terme, inserer ces automesures directement dans la table des examens ??
     */
    if($exam=="tension"){
        $req3="SELECT t.id, d.numero, d.cabinet, DATE_ADD(date_debut,INTERVAL nombre_jours DAY) as date_totale, moyenne_sys as sys, moyenne_dia as dia ".
            "FROM tension_arterielle_moyenne as t ".
            "INNER JOIN dossier as d ON t.id=d.id";

        $res3=mysql_query($req3) or die("erreur SQL:".mysql_error()."<br>$req3");
        while(list($id, $numero, $nom_cab, $date_exam, $sys, $dia)=mysql_fetch_row($res3)){
            $l++;
            if($l>65000){
                $worksheet =& $workbook->addworksheet("Liste $exam 2");
                $worksheet->write("A1", "id");
                $worksheet->write("B1", "numéro");
                $worksheet->write("C1", "cabinet");
                $worksheet->write("D1", "date ".$liste_exam[$exam]);
                $worksheet->write("E1", "Systole");
                $worksheet->write("F1", "Diastole");
                $l=2;
            }
            $worksheet->write("A$l", "$id");
            $worksheet->write_string("B$l", "$numero");
            $worksheet->write("C$l", "$nom_cab");
            $worksheet->write("D$l", "$date_exam");
            $resultat1=str_replace(" ", "", $resultat1);
            $resultat1=str_replace("\t", "", $resultat1);
            $resultat1=str_replace("\r", "", $resultat1);
            $resultat1=str_replace("\n", "", $resultat1);
            $worksheet->write("E$l", "$sys");
            $worksheet->write("F$l", "$dia");
        }
    }
    /* fin ajout Pierre Dufour */

// echo "</table>";
    $workbook->close();
    echo "Télécharger le fichier : <a href='$fichier' target='_blank'>".str_replace("../export/", "", $fichier)."</a><br>";

}


?>
</body>
</html>
