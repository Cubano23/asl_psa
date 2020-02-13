<?php

error_reporting(E_ALL);
session_start();


if(!isset($_SESSION['nom'])) {
    # pas passé par l'identification
    $debut=dirname($_SERVER['PHP_SELF']);
    $self=basename($_SERVER['PHP_SELF']);
    header("Location: $debut/ident_util.php?url=$self");
    echo "<a href='$debut/ident_util.php?url=$self'>cliquez ici</a>";
    exit;
}

#echo 'ici';exit;
set_time_limit(2800);



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="content-type"
          content="text/html; charset=ISO-8859-15">
    <title>Liste des évaluations infirmières</title>
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

entete_asalee("Liste des évaluations infirmières");

//echo $loc;
?>

<br><br>
<?php
# boucle principale
do {
    $repete=false;

    # fenêtre glissante:
    if (isset($_GET['mois']) && isset($_GET['annee']))
    {
        etape_2($repete);
        exit;
    }

    # étape 1 : identification du patient et de la date
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

            case 1:
                etape_1($repete);
                break;

            # étape 2  : saisie des détails
            case 2:
                etape_2($repete);
                break;

            # étape 3  : validation des données et màj base
            case 3:
                etape_3($repete);
                break;
        }
    }
} while($repete);


# fin de traitement principal


function etape_1(&$repete)
{
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville;



    $liste_exam=array(""=>"", "automesure"=>"automesure", "autres"=>"autres", "cognitif"=>"troubles cognitifs",
        "rcva"=>"rcva", "colon"=>"cancer colon", "sein"=>"cancer sein",
        "hemocult"=>"hémoccult", "uterus"=>"cancer utérus",
        "dep_diab"=>"dépistage diabète", "suivi_diab"=>"suivi diabète", 'bpco' => "bpco");


    // requete abandonnée le 27 octobre 2015 on checke plus la relation avec les cabinets
    #$req="SELECT dossier.id, numero, dossier.actif, account.nom_cab, date_format(`date`, '%d/%m/%Y'), degre_satisfaction, points_positifs, points_ameliorations, type_consultation, ".
    #	 "ecg, ecg_seul, exapied, monofil, hba, spirometre, spirometre_seul, t_cognitif, autre, prec_autre, aspects_limitant, aspects_facilitant, objectifs_patient  FROM `evaluation_infirmier` , `dossier`, `account` WHERE dossier.id = evaluation_infirmier.id AND account.cabinet=dossier.cabinet and region != ''
    #	 ";//"  and  `date`>='2013-10-01' ";

    if($_GET['mode']=='light'){
        $cond =" AND date > '2014-01-01' ";
        $mode='light';
    }

    $req="SELECT dossier.id, numero, dossier.actif, cabinet, date_format(`date`, '%d/%m/%Y'), degre_satisfaction, points_positifs, points_ameliorations, type_consultation, ".
        "duree, consult_domicile, consult_tel, consult_collective, ecg, ecg_seul, exapied, monofil, hba, spirometre, spirometre_seul, t_cognitif, autre, prec_autre, aspects_limitant, aspects_facilitant, objectifs_patient  
		 FROM `evaluation_infirmier` , `dossier` WHERE dossier.id = evaluation_infirmier.id 
		 $cond;
		 ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    if($mode=='light'){
        $file_code = 'light';
    }
    else{
        $file_code = 'depuis origine';
    }

    #echo mysql_num_rows($res);exit;
    $fichier="../export/files/Liste evaluation infirmieres ".$file_code." ".date("dmY").".xls";
    $i=1;
    while(file_exists($fichier)){
        $fichier="../export/files/Liste evaluation infirmieres ".$file_code." ".date("dmY").".$i.xls";
        $i++;
    }

    $workbook =& new writeexcel_workbookbig($fichier); // on lui passe en paramètre le chemin de notre fichier

    $worksheet =& $workbook->addworksheet("Liste évaluations");
    $worksheet->write("A1", "id");
    $worksheet->write("B1", "numéro");
    $worksheet->write("C1", "actif");
    $worksheet->write("D1", "cabinet");
    $worksheet->write("E1", "date évaluation");
    if($mode!='light'){
        $worksheet->write("F1", "degré satisfaction");
        $worksheet->write("G1", "points positifs");
        $worksheet->write("H1", "points amélioration");
    }

    $worksheet->write("I1", "type consultation");
    $worksheet->write("J1", "ECG");
    $worksheet->write("K1", "ECG seul non dérogatoire");
    $worksheet->write("L1", "Examen des pieds");//examen des pieds
    $worksheet->write("M1", "Monofilament");//Monofilament
    $worksheet->write("N1", "Spirometrie");//nouvel ajout cognitif
    $worksheet->write("O1", "Spirometrie seule");//nouvel ajout cognitif
    $worksheet->write("P1", "Troubles cognitifs");//nouvel ajout cognitif
    $worksheet->write("Q1", "Diabétique type 2");//Prescription HBA1c
    // $worksheet->write("P1", "Tension");
    $worksheet->write("R1", "Autre");
    $worksheet->write("S1", "Précision autre examen");
    $worksheet->write("T1", "Aspects limitants");
    $worksheet->write("U1", "Aspects faciliants");
    $worksheet->write("V1", "Objectifs patient");
    $worksheet->write("W1", "Duree");
    $worksheet->write("X1", "Consultation domicile");
    $worksheet->write("Y1", "Consultation telephonique");
    $worksheet->write("Z1", "Consultation collective");




    // $worksheet->write("P1", "date maj");

    $l=1;
    /*echo "<table border='1'><tr><td>id</td><td>numero</td><td>cabinet</td><td>date evaluation</td><td>degre satisfaction</td>
         <td>points positifs</td><td>points amélioration</td><td>type consultation</td><td>ECG</Td><td>Monofilament</td><td>examen des pieds</Td>
         <td>Prescription HBA1c</td><td>Tension</Td><td>Autre</Td><td>Précision autre examen</Td></tr>";*/


    while(list($id, $numero, $actif, $cabinet, $date, $degre_satisfaction, $points_positifs, $points_ameliorations, $type_consultation,
        $duree, $consult_domicile, $consult_tel, $consult_collective, $ecg, $ecg_seul, $exapied, $monofil, $hba, $spirometre, $spirometre_seul, $t_cognitif, $autre, $prec_autre, $aspects_limitant, $aspects_facilitant, $objectifs_patient)=mysql_fetch_row($res))
    {



        $l++;
        #echo $ligne.'<br>';
        if($l == 60000){
            $onglet++;
            $worksheet =& $workbook->addworksheet("Liste évaluation $onglet");
            $worksheet->write("A1", "id");
            $worksheet->write("B1", "numéro");
            $worksheet->write("C1", "actif");
            $worksheet->write("D1", "cabinet");
            $worksheet->write("E1", "date évaluation");
            if($mode!='light'){
                $worksheet->write("F1", "degré satisfaction");
                $worksheet->write("G1", "points positifs");
                $worksheet->write("H1", "points amélioration");
            }
            $worksheet->write("I1", "type consultation");
            $worksheet->write("J1", "ECG");
            $worksheet->write("K1", "ECG seul non dérogatoire");
            $worksheet->write("L1", "Examen des pieds");//examen des pieds
            $worksheet->write("M1", "Monofilament");//Monofilament
            $worksheet->write("N1", "Spirometrie");//nouvel ajout cognitif
            $worksheet->write("O1", "Spirometrie seule");//nouvel ajout cognitif
            $worksheet->write("P1", "Troubles cognitifs");//nouvel ajout cognitif
            $worksheet->write("Q1", "Diabétique type 2");//Prescription HBA1c
            // $worksheet->write("P1", "Tension");
            $worksheet->write("R1", "Autre");
            $worksheet->write("S1", "Précision autre examen");
            $worksheet->write("T1", "Aspects limitants");
            $worksheet->write("U1", "Aspects faciliants");
            $worksheet->write("V1", "Objectifs patient");
            $worksheet->write("W1", "Duree");
            $worksheet->write("X1", "Consultation domicile");
            $worksheet->write("Y1", "Consultation telephonique");
            $worksheet->write("Z1", "Consultation collective");
            // $worksheet->write("P1", "date maj");
            $l=2;
        }

        $worksheet->write("A$l", "$id");
        $worksheet->write_string("B$l", "$numero");
        $worksheet->write("C$l", "$actif");
        $worksheet->write("D$l", "$cabinet");
        $worksheet->write("E$l", "$date");
        if($mode!='light'){
            $worksheet->write("F$l", "$degre_satisfaction");
            $worksheet->write("G$l", '"'.$points_positifs.'"');
            $worksheet->write("H$l", '"'.$points_ameliorations.'"');
        }
        $type_consultation=explode(",", $type_consultation);
        $type_consult="";
        $virgule="";
        foreach($type_consultation as $consult){
            $type_consult=$type_consult.$virgule.$liste_exam[$consult];
            $virgule=", ";
        }
        $worksheet->write("I$l", "$type_consult");
        $worksheet->write("J$l", "$ecg");
        $worksheet->write("K$l", "$ecg_seul");
        $worksheet->write("L$l", "$exapied");
        $worksheet->write("M$l", "$monofil");
        $worksheet->write("N$l", "$spirometre");
        $worksheet->write("O$l", "$spirometre_seul");
        $worksheet->write("P$l", "$t_cognitif");
        $worksheet->write("Q$l", "$hba");
        $worksheet->write("R$l", "$autre");
        $worksheet->write("S$l", '"'.$prec_autre.'"');
        $worksheet->write("T$l", '"'.$aspects_limitant.'"');
        $worksheet->write("U$l", '"'.$aspects_facilitant.'"');
        $worksheet->write("V$l", '"'.$objectifs_patient.'"');
        $worksheet->write("W$l", "$duree");
        $worksheet->write("X$l", "$consult_domicile");
        $worksheet->write("Y$l", "$consult_tel");
        $worksheet->write("Z$l", "$consult_collective");


        #$worksheet->write("P1", "date maj");
        /*echo "<tr><td>$id</td><td>$numero</td><td>$cabinet</td><td>$date</td><td>$degre_satisfaction</td>
         <td>$points_positifs</td><td>$points_ameliorations</td><td>$type_consultation</td><td>$ecg</Td>
         <td>$monofil</Td><td>$exapied</td><td>$hba</td><td>$tension</Td><td>$autre</td><td>$prec_autre</td></tr>";*/

    }
    //echo "</table>";

    $workbook->close();
    echo "Télécharger le fichier : <a href='$fichier' target='_blank'>".str_replace("../export/", "", $fichier)."</a><br>";

}



?>
</body>
</html>
