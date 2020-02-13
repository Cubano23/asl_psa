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
    <title>Taux de patients disposant d'au moins une mise à jour dans l'année</title>
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


$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";

require("../../global/entete.php");
//echo $loc;

set_time_limit(120);
entete_asalee("Taux de patients disposant d'au moins une mise à jour dans l'année");
?>
<!--<table cellpadding="2" cellspacing="2" border="0"
 style="text-align: left; width: 100%;">
  <tbody>
    <tr>
      <td style="width: 20%; vertical-align: top;">
      <br>
      <img src="<?php echo $loc; ?>/images/inf79.gif" alt="logo informed79"><br>
        <a href="mailto:contact@asalee.fr"><font size="-1">contact</font></a>
      </td>
      <td style="text-align: center; vertical-align: top;">
	       <span style="font-family: arial; font-weight: bold;">
 <?php
echo "
<i><font face='times new roman' size='32'>Asalée</font><br>
<font face='times new roman'>Taux de patients disposant d'au moins une mise à jour dans l'année</font></i>";
?>
           </span><br>
 <?php if(isset($_SESSION['nom'])) echo '<font size="-1"><i>'.$_SESSION['nom'].'</i></font>'; ?>
      </td>
      <td style="width: 15%; text-align: right; vertical-align: middle;">
      <img src="<?php echo $loc; ?>/images/urml.jpg" alt="logo urml"><br>
      </td>
    </tr>
  </tbody>
</table>
-->
<br><br>
<?

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


function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_diab;

    $req="SELECT cabinet, total_diab2, nom_cab ".
        "FROM account ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and cabinet!='jgomes' ".
        "and cabinet!='sbirault' ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $t_diab['tot']=0;
    while(list($cab, $total_diab2, $ville) = mysql_fetch_row($res)) {
        $t_diab[$cab]=$total_diab2;
        // $t_diab['tot']=$t_diab['tot']+$total_diab2;
        $tville[$cab]=$ville;
    }

    foreach($tville as $cab=>$ville){
        $tcabinet_util[$cab]=0;
    }

    $date2mois=date("Y-m-d", mktime(1, 1, 1, date("m")-2, date('d'), date("Y")));
    $date3mois=date("Y-m-d", mktime(1, 1, 1, date("m")-3, date('d'), date("Y")));
    $req="SELECT cabinet from liste_exam, dossier where ".
        "dossier.id=liste_exam.id and date_exam>='$date3mois' group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        if(isset($tville[$cab])){
            $tcabinet_util[$cab]=1;
            $t_diab["tot"]=$t_diab["tot"]+$t_diab[$cab];
        }
    }

    $req="SELECT cabinet, count(*) ".
        "FROM dossier ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ".
        "AND actif='oui' ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet, numero ";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if (mysql_num_rows($res)==0) {
        exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }
    $tcabinet=array();

    while(list($cab, $pat) = mysql_fetch_row($res)) {
        $tcabinet[] = $cab;
//	 $tpat[$cab] = $pat;
    }

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');

    echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

    ?>
    <br>
    <br>
    <table border=1 align='center'>
        <?php


        $liste_exam=array("monofil"=>"Examen au monofilament <sup>2</sup>",
            "pied"=>"Examen des pieds <sup>3</sup>",
            "HDL"=>"Dosage du HDL - Cholestérol <sup>4</sup>",
            "creat"=>"Créatinémie <sup>5</sup>",
            "albu"=>"Micro Albuminurie <sup>6</sup>",
            "fond"=>"Fond d'oeil <sup>7</sup>",
            "ECG"=>"ECG <sup>8</sup>");



        foreach($liste_exam as $exam=>$libelle){
            $tpat["tot"][$exam]=0;
            $t_diab["tot"]=0;
        }
        $tpat["tot"]["hba"]=0;


        $req2="SELECT libelle, count(*) ".
            "FROM amberieu GROUP BY libelle ";
        // echo $req;
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

        $liste_medecin=array();
        while(list($medecin)=mysql_fetch_row($res2))
        {
            $t_diab[$medecin]=0;
            $liste_medecin[]=$medecin;

            foreach($liste_exam as $exam=>$libelle){
                $tpat[$medecin][$exam]=0;
            }
            $tpat[$medecin]["hba"]=0;
        }

        sort($liste_medecin);

        foreach($liste_exam as $exam=>$libelle){
            $req="SELECT cabinet, dossier_id, DATE_ADD(max(date_exam), INTERVAL 14 MONTH), libelle ".
                "from suivi_diabete,dossier, liste_exam, amberieu where cabinet='amberieu' ".
                "and suivi_diabete.dossier_id = dossier.id and dossier.numero=amberieu.numero ".
                " AND dossier.actif='oui' and ".
                "(date_exam is not NULL) and (DATE_ADD(date_exam, INTERVAL 14 MONTH) >= '$date2mois') ".
                "and type_exam='$exam' and liste_exam.id=dossier.id ".
                "GROUP by dossier.id order by dossier.id";
            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            while(list($cabinet, $dossier_id, $date_exam, $medecin)=mysql_fetch_row($res)){
                if(($date_exam!='')&&($date_exam!='NULL'))
                {
                    if(diffmois($date_exam, $date2mois)<=0)
                    {
                        $tpat['tot'][$exam] = $tpat['tot'][$exam]+1;
                        $tpat[$medecin][$exam] = $tpat[$medecin][$exam]+1;

                    }
                }
            }
        }


        $req="SELECT cabinet, dossier_id, DATE_ADD(max(date_exam), INTERVAL 8 MONTH), libelle ".
            "from suivi_diabete,dossier, liste_exam, amberieu where cabinet='amberieu' ".
            "and suivi_diabete.dossier_id = dossier.id  AND dossier.actif='oui' and ".
            "(date_exam is not NULL) and (DATE_ADD(date_exam, INTERVAL 8 MONTH) >= '$date2mois') ".
            "and type_exam='HBA1c' and liste_exam.id=dossier.id and amberieu.numero=dossier.numero ".
            "GROUP by dossier.id order by dossier.id";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        while(list($cabinet, $dossier_id, $date_exam, $medecin)=mysql_fetch_row($res)){
            if(($date_exam!='')&&($date_exam!='NULL'))
            {
                if(diffmois($date_exam, $date2mois)<=0)
                {
                    $tpat['tot']["hba"] = $tpat['tot']["hba"]+1;
                    $tpat[$medecin]["hba"] = $tpat[$medecin]["hba"]+1;

                }
            }
        }

        $req="SELECT cabinet, dossier_id, libelle ".
            "from suivi_diabete,dossier, amberieu where cabinet='amberieu' ".
            "and suivi_diabete.dossier_id = dossier.id  AND dossier.actif='oui' and amberieu.numero=dossier.numero ".
            "GROUP by dossier.id order by dossier.id";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        while(list($cabinet, $dossier_id, $medecin)=mysql_fetch_row($res)){
            $t_diab['tot'] = $t_diab['tot']+1;
            $t_diab[$medecin] = $t_diab[$medecin]+1;

        }

        ?>

        <tr>
            <td>Taux de respect des examens &nbsp;</td>

            <?php

            ?>
            <td align="center"><b>Total</b></td>
            <?php

            foreach($liste_medecin as $med){
                if($med==""){
                    echo "<td align='center'><b>Pas de médecin indiqué</b></td>";
                }
                else{
                    echo "<td align='center'><b>Dr $med</b></td>";
                }
            }
            echo "</tr>";

            $liste_exam=array("hba"=>"HBA1c",
                "monofil"=>"Examen au monofilament",
                "pied"=>"Examen des pieds",
                "HDL"=>"Dosage du HDL - Cholestérol",
                "creat"=>"Créatinémie",
                "albu"=>"Micro Albuminurie",
                "fond"=>"Fond d'oeil",
                "ECG"=>"ECG");

            foreach($liste_exam as $code=>$titre){
                echo "<tr><td>$titre</td>";
                $taux=round($tpat["tot"][$code]/$t_diab["tot"]*100);
                echo "<td align='right'>$taux %</td>";
                $max=0;
                $mini=100;
                foreach($liste_medecin as $med){
                    $taux=round($tpat[$med][$code]/$t_diab[$med]*100);

                    echo "<td align='right'>$taux %</td>";

                    if($taux>$max){
                        $max=$taux;
                    }
                    if($taux<$mini){
                        $mini=$taux;
                    }

                }

            }

            echo "<tr><td>Nb patients</td>";
            $taux=$t_diab["tot"];
            echo "<td align='right'>$taux</td>";
            $max=0;
            $mini=100;
            foreach($liste_medecin as $med){
                $taux=$t_diab[$med];

                echo "<td align='right'>$taux</td>";

                if($taux>$max){
                    $max=$taux;
                }
                if($taux<$mini){
                    $mini=$taux;
                }

            }

            die;
            ?>

    </table>
    <br><br>
    <?php


    $annee0=2004;
    $mois0=3;

    $annee=date('Y');
    $mois=date('m');

    $mois--;


    if($mois<3)
    {
        $annee--;
        $mois=12;
    }
    elseif(($mois>=3)&&($mois<6))
    {
        $mois=3;
    }
    elseif(($mois>=6)&&($mois<9))
    {
        $mois=6;
    }
    elseif(($mois>=9)&&($mois<12))
    {
        $mois=9;
    }

    $jour[3]=$jour[12]=31;
    $jour[6]=$jour[9]=30;

    while(($annee>$annee0)||(($annee==$annee0)&&($mois>=$mois0)))
    {
        if($mois<10)
        {
            $date=$annee.'-0'.$mois.'-'.$jour[$mois];
        }
        else
        {
            $date=$annee.'-'.$mois.'-'.$jour[$mois];
        }
        tableau($date);

        $mois=$mois-3;

        if($mois<=0)
        {
            $mois=$mois+12;
            $annee--;
        }
    }

    ?>
    <sup>1</sup>Nombre de patients ayant eu un résultat de HBA1c dans les 6 derniers mois/potentiel du cabinet<br>
    <sup>2</sup>Nombre de patients ayant eu un résultat d'examen au monofilament dans les 12 derniers mois/potentiel du cabinet<br>
    <sup>3</sup>Nombre de patients ayant eu un résultat d'examen des pieds dans les 12 derniers mois/potentiel du cabinet<br>
    <sup>4</sup>Nombre de patients ayant eu un résultat de dosage du HDL/Cholesterol dans les 12 derniers mois/potentiel du cabinet<br>
    <sup>5</sup>Nombre de patients ayant eu un résultat de créatinémie dans les 12 derniers mois/potentiel du cabinet<br>
    <sup>6</sup>Nombre de patients ayant eu un résultat de Micro-Albuminurie dans les 12 derniers mois/potentiel du cabinet<br>
    <sup>7</sup>Nombre de patients ayant eu un résultat de fond d'oeil dans les 12 derniers mois/potentiel du cabinet<br>
    <sup>8</sup>Nombre de patients ayant eu un résultat d'ECG dans les 12 derniers mois/potentiel du cabinet<br>
    <?
}

function tableau($date){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_diab;


    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

    $tab_date=split('-', $date);

    echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";


    $req="SELECT cabinet, count(*) ".
        "FROM dossier ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ".
        "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet, numero ";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if (mysql_num_rows($res)==0) {
        exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }
//$tcabinet=array();
    $t_diab['tot']=0;
    $cab_prec="";


    foreach($tville as $cab=>$ville){
        $tcabinet_util[$cab]=0;
    }

    $date2mois=date("Y-m-d", mktime(1, 1, 1, $tab_date[1]-2, $tab_date[2], $tab_date[0]));
    $date3mois=date("Y-m-d", mktime(1, 1, 1, $tab_date[1]-3, $tab_date[2], $tab_date[0]));
    $req="SELECT cabinet from liste_exam, dossier where ".
        "dossier.id=liste_exam.id and date_exam>='$date3mois' group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        if(isset($tville[$cab])){
            $tcabinet_util[$cab]=1;
            $t_diab["tot"]=$t_diab["tot"]+$t_diab[$cab];
        }
    }

    ?>
    <br>
    <br>
    <table border=1 align='center'>
        <?php

        $liste_exam=array("monofil"=>"Examen au monofilament <sup>2</sup>",
            "pied"=>"Examen des pieds <sup>3</sup>",
            "HDL"=>"Dosage du HDL - Cholestérol <sup>4</sup>",
            "creat"=>"Créatinémie <sup>5</sup>",
            "albu"=>"Micro Albuminurie <sup>6</sup>",
            "fond"=>"Fond d'oeil <sup>7</sup>",
            "ECG"=>"ECG <sup>8</sup>");

        foreach ($tcabinet as $cab)
        {
            foreach($liste_exam as $exam=>$libelle){
                $tpat[$cab][$exam]=0;
            }
            $tpat[$cab]["hba"]=0;
        }


        foreach($liste_exam as $exam=>$libelle){
            $tpat["tot"][$exam]=0;
        }
        $tpat["tot"]["hba"]=0;

        foreach($liste_exam as $exam=>$libelle){
            $req="SELECT cabinet, dossier_id, DATE_ADD(max(date_exam), INTERVAL 14 MONTH) ".
                "from suivi_diabete,dossier, liste_exam where cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  ".
                "and cabinet!='jgomes' and dossier.cabinet!='sbirault' and suivi_diabete".
                ".dossier_id = dossier.id  AND dossier.actif='oui' and ".
                "(date_exam is not NULL) and (DATE_ADD(date_exam, INTERVAL 14 MONTH) >= '$date2mois') ".
                "and type_exam='$exam' and liste_exam.id=dossier.id ".
                "GROUP by dossier.id order by dossier.id";
            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            while(list($cabinet, $dossier_id, $date_exam)=mysql_fetch_row($res)){
                if(($date_exam!='')&&($date_exam!='NULL'))
                {
                    if(diffmois($date_exam, $date2mois)<=0)
                    {
                        if($tcabinet_util[$cabinet]==1){
                            $tpat['tot'][$exam] = $tpat['tot'][$exam]+1;
                            $tpat[$cabinet][$exam] = $tpat[$cabinet][$exam]+1;
                        }

                    }
                }
            }
        }

        $req="SELECT cabinet, dossier_id, DATE_ADD(max(date_exam), INTERVAL 8 MONTH) ".
            "from suivi_diabete,dossier, liste_exam where cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  ".
            "and cabinet!='jgomes' and dossier.cabinet!='sbirault' and suivi_diabete".
            ".dossier_id = dossier.id  AND dossier.actif='oui' and ".
            "(date_exam is not NULL) and (DATE_ADD(date_exam, INTERVAL 8 MONTH) >= '$date2mois') ".
            "and type_exam='HBA1c' and liste_exam.id=dossier.id ".
            "GROUP by dossier.id order by dossier.id";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        while(list($cabinet, $dossier_id, $date_exam)=mysql_fetch_row($res)){
            if(($date_exam!='')&&($date_exam!='NULL'))
            {
                if(diffmois($date_exam, $date2mois)<=0)
                {
                    if($tcabinet_util[$cabinet]==1){
                        $tpat['tot']["hba"] = $tpat['tot']["hba"]+1;
                        $tpat[$cabinet]["hba"] = $tpat[$cabinet]["hba"]+1;
                    }

                }
            }
        }

        ?>

        <tr>
            <td>Taux de respect des examens &nbsp;</td>
            <td> <b>&nbsp;Moyenne</b>	 &nbsp;</td>


            <td><b><?php echo $tville[$_SESSION['nom']];?></b></td>
            <td><b>Borne Basse</b></td>
            <td><b>Borne Haute</b></td>
        </tr>
        <?php

        $liste_exam=array("hba"=>"HBA1c <sup>1</sup>",
            "monofil"=>"Examen au monofilament <sup>2</sup>",
            "pied"=>"Examen des pieds <sup>3</sup>",
            "HDL"=>"Dosage du HDL - Cholestérol <sup>4</sup>",
            "creat"=>"Créatinémie <sup>5</sup>",
            "albu"=>"Micro Albuminurie <sup>6</sup>",
            "fond"=>"Fond d'oeil <sup>7</sup>",
            "ECG"=>"ECG <sup>8</sup>");

        foreach($liste_exam as $code=>$titre){
            echo "<tr><td>$titre</td>";
            $taux=round($tpat["tot"][$code]/$t_diab["tot"]*100);
            echo "<td align='right'>$taux %</td>";
            $max=0;
            $mini=100;
            foreach($tcabinet_util as $cabinet=>$val){
                if($val==1){
                    $taux=round($tpat[$cabinet][$code]/$t_diab[$cabinet]*100);

                    if($taux>$max){
                        $max=$taux;
                    }
                    if($taux<$mini){
                        $mini=$taux;
                    }
                }
            }

            $taux=round($tpat[$_SESSION["nom"]][$code]/$t_diab[$_SESSION["nom"]]*100);
            echo "<td align='right'>$taux %</td>";
            echo "<td align='right'>$mini %</td>";
            echo "<td align='right'>$max %</td></tr>";
        }

        ?>

    </table>
    <br><br>
    <?php

}

# calcul de la différence en mois à partir d'un timestamp MySQL
function diffmois($date, $ref=false) {

    list($a,$m,$j)= explode('-',$date,3);

    if($ref===false)//aucune date de référence
    {
        $diff_mois = (date('Y')-$a)*12;
        $diff_mois=$diff_mois+ date('m')-$m;
        /*  if(date('m') < $m) $age--;*/
        if(date('d') < $j) $diff_mois--;
    }
    else //une date de référence au format 'yyyy-mm-dd-
    {
        list($aref, $mref, $jref)=explode('-', $ref, 3);
        $diff_mois = ($aref-$a)*12;
        $diff_mois=$diff_mois+ $mref-$m;
        /*  if(date('m') < $m) $age--;*/
        if($jref < $j) $diff_mois--;
    }
    return $diff_mois;
}

?>
</body>
</html>
