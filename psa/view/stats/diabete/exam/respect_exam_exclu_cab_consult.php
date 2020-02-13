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

set_time_limit(240);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="content-type"
          content="text/html; charset=ISO-8859-15">
    <title>Taux de respect des examens cabinets actifs entre -14 et -2 mois et au moins une consultation</title>
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

entete_asalee("Taux de respect des examens cabinets actifs entre -14 et -2 mois et au moins une consultation");
?>

<br><br>
<?

# boucle principale
do {
    $repete=false;

    # étape 1 : affichage tableau à la date du jour
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

            //affichage tableau à la date du jour
            case 1:
                etape_1($repete);
                break;

        }
    }
} while($repete);

# fin de traitement principal

//affichage tableau à la date du jour
function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_diab, $regions, $reg;

    $req="SELECT cabinet, total_diab2, nom_cab, region ".
        "FROM account ".
        "WHERE infirmiere!='' and region!='' ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $t_diab['tot']=0;
    $t_diab['eval']=0;
    $t_diab['eval2']=0;
    $t_diab['eval3']=0;
    $reg=array();

    while(list($cab, $total_diab2, $ville, $region) = mysql_fetch_row($res)) {
        $t_diab[$cab]=$total_diab2;
        $t_diab['tot']=$t_diab['tot']+$total_diab2;
        $tville[$cab]=$ville;
        $regions[$cab]=$region;
        if(!in_array($region, $reg)){
            $reg[]=$region;
        }


    }

    $req="SELECT dossier.cabinet, count(*) ".
        "FROM dossier, account ".
        "WHERE infirmiere!='' and dossier.cabinet=account.cabinet and region!='' ".
        "AND actif='oui' ".
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab, numero ";
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

    foreach($tcabinet as $cab){
        $tcabinet_util[$cab]=0;
    }

    $date3mois=date("Y-m-d", mktime(1, 1, 1, date("m")-3, date('d'), date("Y")));
    $req="SELECT cabinet from evaluation_infirmier, dossier where ".
        "dossier.id=evaluation_infirmier.id and date>='$date3mois' group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        $tcabinet_util[$cab]=1;
    }
    $req="SELECT cabinet from suivi_diabete, dossier where ".
        "dossier.id=suivi_diabete.dossier_id and dsuivi>='$date3mois' group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        $tcabinet_util[$cab]=1;
    }

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');

    echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

    ?>
    <br>
    <br>
    <table border=1 align='center'>
        <?php

        ///////////////Respect des examens //////////////////////////
        $date2mois=date("Y-m-d", mktime(1, 1, 1, date("m")-2, date('d'), date("Y")));

        $req="SELECT cabinet, dossier_id, DATE_ADD(max(dHBA), INTERVAL 8 MONTH), DATE_ADD(max(dExaFil), INTERVAL 14 MONTH),".
            " DATE_ADD(max(dExaPieds), INTERVAL 14 MONTH), DATE_ADD(max(dChol), INTERVAL 14 MONTH), ".
            "DATE_ADD(max(suivi_diabete.dCreat), INTERVAL 14 MONTH), DATE_ADD(max(dAlbu),INTERVAL 14 MONTH), ".
            "DATE_ADD(max(dFond), INTERVAL 14 MONTH), DATE_ADD(max(dECG), INTERVAL 14 MONTH) ".
            "from suivi_diabete,dossier where cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  ".
            "and cabinet!='jgomes' and dossier.cabinet!='sbirault' and suivi_diabete".
            ".dossier_id = dossier.id  AND dossier.actif='oui' ".
            "GROUP by dossier.cabinet, numero order by dossier.cabinet, numero";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]['hba']=0;
            $tpat[$cab]['exafil']=0;
            $tpat[$cab]['pied']=0;
            $tpat[$cab]['chol']=0;
            $tpat[$cab]['creat']=0;
            $tpat[$cab]['albu']=0;
            $tpat[$cab]['fond']=0;
            $tpat[$cab]['ecg']=0;
            $t_diab[$cab]=0;
        }


        $tpat['tot']['hba']=0;
        $tpat['tot']['exafil']=0;
        $tpat['tot']['pied']=0;
        $tpat['tot']['chol']=0;
        $tpat['tot']['creat']=0;
        $tpat['tot']['albu']=0;
        $tpat['tot']['fond']=0;
        $tpat['tot']['ecg']=0;

        foreach($reg as $region){
            $tpat[$region]['hba']=0;
            $tpat[$region]['exafil']=0;
            $tpat[$region]['pied']=0;
            $tpat[$region]['chol']=0;
            $tpat[$region]['creat']=0;
            $tpat[$region]['albu']=0;
            $tpat[$region]['fond']=0;
            $tpat[$region]['ecg']=0;
        }

        while(list($cabinet, $dossier_id, $dHBA, $dExaFil, $dExaPieds, $dChol, $dCreat, $dAlbu,
            $dFond, $dECG) = mysql_fetch_row($res)) {

            $req2="SELECT id from evaluation_infirmier where ".
                "id='$dossier_id'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            if((isset($t_diab[$cabinet]))&&(mysql_num_rows($res2)>0)){
                $t_diab[$cabinet]=$t_diab[$cabinet]+1;

                if(($dHBA!='')&&($dHBA!='NULL')){
                    if(diffmois($dHBA, $date2mois)<=0)
                    {
                        if($tcabinet_util[$cabinet]==1){
                            $tpat['tot']['hba'] = $tpat['tot']["hba"]+1;
                            $tpat[$cabinet]['hba'] = $tpat[$cabinet]["hba"]+1;
                            if(!isset($tpat[$regions[$cabinet]]['hba'])){
                                $tpat[$regions[$cabinet]]['hba']=0;
                            }
                            $tpat[$regions[$cabinet]]['hba']=$tpat[$regions[$cabinet]]['hba']+1;
                        }

                    }
                }

            }

        }

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

        foreach ($reg as $region)
        {
            foreach($liste_exam as $exam=>$libelle){
                $tpat[$region][$exam]=0;
            }
            $tpat[$region]["hba"]=0;
        }

        foreach($liste_exam as $exam=>$libelle){
            $tpat["tot"][$exam]=0;
        }
        $tpat["tot"]["hba"]=0;


        foreach($liste_exam as $exam=>$libelle){
            $req="SELECT cabinet, dossier_id, DATE_ADD(max(date_exam), INTERVAL 14 MONTH) ".
                "from suivi_diabete,dossier, liste_exam where cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  ".
                "and cabinet!='jgomes' and dossier.cabinet!='sbirault' and suivi_diabete".
                ".dossier_id = dossier.id  AND dossier.actif='oui' ".
                "and type_exam='$exam' and liste_exam.id=dossier.id ".
                "GROUP by dossier.cabinet, dossier.numero order by dossier.cabinet, dossier.numero";
            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


            while(list($cabinet, $dossier_id, $date_exam)=mysql_fetch_row($res)){
                $req2="SELECT id from evaluation_infirmier where ".
                    "id='$dossier_id'";
                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                if(mysql_num_rows($res2)>0){
                    if(($date_exam!='')&&($date_exam!='NULL'))
                    {
                        if(diffmois($date_exam, $date2mois)<=0)
                        {
                            if((isset($t_diab[$cabinet]))&&($tcabinet_util[$cabinet]==1)){
                                $tpat['tot'][$exam] = $tpat['tot'][$exam]+1;
                                $tpat[$cabinet][$exam] = $tpat[$cabinet][$exam]+1;
                                if(!isset($tpat[$regions[$cabinet]][$exam])){
                                    $tpat[$regions[$cabinet]][$exam]=0;
                                }
                                $tpat[$regions[$cabinet]][$exam]=$tpat[$regions[$cabinet]][$exam]+1;
                            }

                        }
                    }
                }
            }
        }

        $exam="hba";
        $req="SELECT cabinet, dossier_id, DATE_ADD(max(date_exam), INTERVAL 8 MONTH) ".
            "from suivi_diabete,dossier, liste_exam where cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  ".
            "and cabinet!='jgomes' and dossier.cabinet!='sbirault' and suivi_diabete".
            ".dossier_id = dossier.id  AND dossier.actif='oui' ".
            "and type_exam='HBA1c' and liste_exam.id=dossier.id ".
            "GROUP by dossier.cabinet, dossier.numero order by dossier.cabinet, dossier.numero";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        while(list($cabinet, $dossier_id, $date_exam)=mysql_fetch_row($res)){
            $req2="SELECT id from evaluation_infirmier where ".
                "id='$dossier_id'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            if(mysql_num_rows($res2)>0){
                if(($date_exam!='')&&($date_exam!='NULL'))
                {
                    if(diffmois($date_exam, $date2mois)<=0)
                    {
                        if((isset($t_diab[$cabinet]))&&($tcabinet_util[$cabinet]==1)){
                            $tpat['tot'][$exam] = $tpat['tot'][$exam]+1;
                            $tpat[$cabinet][$exam] = $tpat[$cabinet][$exam]+1;
                            if(!isset($tpat[$regions[$cabinet]][$exam])){
                                $tpat[$regions[$cabinet]][$exam]=0;
                            }
                            $tpat[$regions[$cabinet]][$exam]=$tpat[$regions[$cabinet]][$exam]+1;
                        }

                    }
                }
            }
        }


        foreach($reg as $region){
            $t_diab[$region]=0;
        }
        $t_diab["tot"]=0;
        foreach($regions as $cab=>$region){
            if((isset($tcabinet_util[$cab]))&&($tcabinet_util[$cab]==1)){
                if(!isset($t_diab[$region])){
                    $t_diab[$region]=0;
                }
                $t_diab[$region]=$t_diab[$region]+$t_diab[$cab];
                $t_diab["tot"]=$t_diab["tot"]+$t_diab[$cab];
            }
        }

        ?>

        <tr>
            <td>Taux de respect des examens &nbsp;</td>
            <td align='center'> <b>&nbsp;moyenne</b>	 &nbsp;</td>
            <?php
            foreach($reg as $region){
                if($region!=""){
                    echo "<td align='center'> <b>&nbsp;moyenne $region</b> &nbsp;</td>";
                }
            }



            foreach($tcabinet as $cab) {

                ?>
                <td><b><?php echo $tville[$cab];?></b></td>
                <?php
            }

            ?>
        </tr>
        <?php


        foreach($reg as $region){
            if ($t_diab[$region]==0)
            {
                $taux_hba[$region]=$taux_exafil[$region]=$taux_pied[$region]=$taux_chol[$region]=$taux_creat[$region]='ND';
                $taux_albu[$region]=$taux_fond[$region]=$taux_ecg[$region]="ND";
            }
            else
            {
                $taux_hba[$region]=round($tpat[$region]['hba']/$t_diab[$region]*100);
                $taux_hba[$region].="%";

            }
        }

        $liste_exam=array("hba"=>"HBA1c <sup>1</sup>",
            "monofil"=>"Examen au monofilament <sup>2</sup>",
            "pied"=>"Examen des pieds <sup>3</sup>",
            "HDL"=>"Dosage du HDL - Cholestérol <sup>4</sup>",
            "creat"=>"Créatinémie <sup>5</sup>",
            "albu"=>"Micro Albuminurie <sup>6</sup>",
            "fond"=>"Fond d'oeil <sup>7</sup>",
            "ECG"=>"ECG <sup>8</sup>");

        foreach($liste_exam as $exam=>$libelle){
            echo "<tr><td>$libelle</td>";

            if ($t_diab['tot']==0){
                echo "<td align='right'>ND</td>";
            }
            else{
                $taux=round($tpat['tot'][$exam]/$t_diab['tot']*100);
                echo "<td align='right'>$taux %</td>";
            }

            foreach($reg as $region){
                if ($t_diab[$region]==0){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    $taux=round($tpat[$region][$exam]/$t_diab[$region]*100);
                    echo "<td align='right'>$taux %</td>";
                }
            }

            foreach($tcabinet as $cab){
                if (($tcabinet_util[$cab]==0)||($t_diab[$cab]==0)){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    $taux=round($tpat[$cab][$exam]/$t_diab[$cab]*100);
                    echo "<td align='right'>$taux %</td>";
                }
            }
        }

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
    <sup>1</sup>Nombre de patients ayant eu un résultat de HBA1c dans les 6 derniers mois/Nombre de patients diabétiques ayant eu au moins une consultation<br>
    <sup>2</sup>Nombre de patients ayant eu un résultat d'examen au monofilament dans les 12 derniers mois/Nombre de patients diabétiques ayant eu au moins une consultation<br>
    <sup>3</sup>Nombre de patients ayant eu un résultat d'examen des pieds dans les 12 derniers mois/Nombre de patients diabétiques ayant eu au moins une consultation<br>
    <sup>4</sup>Nombre de patients ayant eu un résultat de dosage du HDL/Cholesterol dans les 12 derniers mois/Nombre de patients diabétiques ayant eu au moins une consultation<br>
    <sup>5</sup>Nombre de patients ayant eu un résultat de créatinémie dans les 12 derniers mois/Nombre de patients diabétiques ayant eu au moins une consultation<br>
    <sup>6</sup>Nombre de patients ayant eu un résultat de Micro-Albuminurie dans les 12 derniers mois/Nombre de patients diabétiques ayant eu au moins une consultation<br>
    <sup>7</sup>Nombre de patients ayant eu un résultat de fond d'oeil dans les 12 derniers mois/Nombre de patients diabétiques ayant eu au moins une consultation<br>
    <sup>8</sup>Nombre de patients ayant eu un résultat d'ECG dans les 12 derniers mois/Nombre de patients diabétiques ayant eu au moins une consultation<br>
    Un cabinet est considéré comme actif actif si une infirmière a été présente dans les 3 derniers mois pour une intervention (un suivi diabète ou une consultation infirmière)
    <?
}

//affichage arrêtés trimestriels
function tableau($date){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_diab, $regions, $reg;


    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

    $tab_date=split('-', $date);

    echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";

    foreach ($tcabinet as $cab)
    {
//	$tpat[$cab]=0;
        $tcabinet_util[$cab]=0;

    }

    foreach($reg as $region){
        $t_diab[$region]=0;
    }

    foreach($tcabinet as $cab){
        $tcabinet_util[$cab]=0;
        $t_diab[$cab]=0;
    }

    $date2mois=date("Y-m-d", mktime(1, 1, 1, $tab_date[1]-2, $tab_date[2], $tab_date[0]));
    $date3mois=date("Y-m-d", mktime(1, 1, 1, $tab_date[1]-3, $tab_date[2], $tab_date[0]));

    $req="SELECT cabinet from evaluation_infirmier, dossier where ".
        "dossier.id=evaluation_infirmier.id and date>='$date3mois' group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        $tcabinet_util[$cab]=1;
    }
    $req="SELECT cabinet from suivi_diabete, dossier where ".
        "dossier.id=suivi_diabete.dossier_id and dsuivi>='$date3mois' group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        $tcabinet_util[$cab]=1;
    }


    ?>
    <br>
    <br>
    <table border=1 align='center'>
        <?php

        ///////////////Respect des examens //////////////////////////

        $req="SELECT cabinet, dossier_id, DATE_ADD(max(dHBA), INTERVAL 8 MONTH), DATE_ADD(max(dExaFil), INTERVAL 14 MONTH),".
            " DATE_ADD(max(dExaPieds), INTERVAL 14 MONTH), DATE_ADD(max(dChol), INTERVAL 14 MONTH), ".
            "DATE_ADD(max(suivi_diabete.dCreat), INTERVAL 14 MONTH), DATE_ADD(max(dAlbu),INTERVAL 14 MONTH), ".
            "DATE_ADD(max(dFond), INTERVAL 14 MONTH), DATE_ADD(max(dECG), INTERVAL 14 MONTH) ".
            "from suivi_diabete,dossier where cabinet!='zTest'  and cabinet!='ergo' and cabinet!='irdes'  ".
            "and cabinet!='jgomes' and dossier.cabinet!='sbirault' and suivi_diabete".
            ".dossier_id = dossier.id  AND ( (dossier.actif='oui' AND dossier.dcreat<='$date2mois') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date2mois' AND dossier.dcreat<='$date2mois')) ".
            "and ((dHBA <= '$date2mois') ".
            " or (dExaFil<='$date2mois' ) or (dExaPieds <='$date2mois') or ".
            "(dChol<='$date2mois') ".
            " or (dLDL<='$date2mois')  ".
            "or (suivi_diabete.dCreat<='$date2mois') or (".
            "dAlbu<='$date2mois') or (".
            "dFond<='$date2mois') or (".
            "dECG<='$date2mois')) GROUP by cabinet, numero order by cabinet, numero";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]['hba']=0;
            $tpat[$cab]['exafil']=0;
            $tpat[$cab]['pied']=0;
            $tpat[$cab]['chol']=0;
            $tpat[$cab]['creat']=0;
            $tpat[$cab]['albu']=0;
            $tpat[$cab]['fond']=0;
            $tpat[$cab]['ecg']=0;
            $t_diab[$cab]=0;
        }

        $tpat['tot']['hba']=0;
        $tpat['tot']['exafil']=0;
        $tpat['tot']['pied']=0;
        $tpat['tot']['chol']=0;
        $tpat['tot']['creat']=0;
        $tpat['tot']['albu']=0;
        $tpat['tot']['fond']=0;
        $tpat['tot']['ecg']=0;

        foreach($reg as $region){
            $tpat[$region]['hba']=0;
            $tpat[$region]['exafil']=0;
            $tpat[$region]['pied']=0;
            $tpat[$region]['chol']=0;
            $tpat[$region]['creat']=0;
            $tpat[$region]['albu']=0;
            $tpat[$region]['fond']=0;
            $tpat[$region]['ecg']=0;
        }

        $cab_prec="";
        // $t_diab['tot']=0;


        while(list($cabinet, $dossier_id, $dHBA, $dExaFil, $dExaPieds, $dChol, $dCreat, $dAlbu,
            $dFond, $dECG) = mysql_fetch_row($res)) {

            $req2="SELECT id from evaluation_infirmier where ".
                "id='$dossier_id' and date<='$date'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            if((isset($t_diab[$cabinet]))&&(mysql_num_rows($res2)>0)){
                $t_diab[$cabinet]=$t_diab[$cabinet]+1;
                if(($dHBA!='')&&($dHBA!='NULL')){
                    if(diffmois($dHBA, $date2mois)<=0)
                    {
                        if($tcabinet_util[$cabinet]==1){
                            $tpat['tot']['hba'] = $tpat['tot']["hba"]+1;
                            $tpat[$cabinet]['hba'] = $tpat[$cabinet]["hba"]+1;
                            if(!isset($tpat[$regions[$cabinet]]['hba'])){
                                $tpat[$regions[$cabinet]]['hba']=0;
                            }
                            $tpat[$regions[$cabinet]]['hba']=$tpat[$regions[$cabinet]]['hba']+1;
                        }

                    }
                }


            }


        }

        // print_r($t_diab);

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

        foreach ($reg as $region)
        {
            foreach($liste_exam as $exam=>$libelle){
                $tpat[$region][$exam]=0;
            }
            $tpat[$region]["hba"]=0;
        }

        foreach($liste_exam as $exam=>$libelle){
            $tpat["tot"][$exam]=0;
        }
        $tpat["tot"]["hba"]=0;


        foreach($liste_exam as $exam=>$libelle){
            $req="SELECT cabinet, dossier_id, DATE_ADD(max(date_exam), INTERVAL 14 MONTH) ".
                "from suivi_diabete,dossier, liste_exam, evaluation_infirmier ".
                "where cabinet!='zTest' and cabinet!='irdes' ".
                "and cabinet!='ergo' and evaluation_infirmier.id=dossier.id ".
                "and evaluation_infirmier.date<='$date2mois' ".
                "and cabinet!='jgomes' and dossier.cabinet!='sbirault' and suivi_diabete".
                ".dossier_id = dossier.id  AND ( (dossier.actif='oui' AND dossier.dcreat<='$date2mois') ".
                "OR (dossier.actif='non' AND dossier.dmaj>'$date2mois' AND dossier.dcreat<='$date2mois')) ".
                "and type_exam='$exam' and liste_exam.id=dossier.id and date_exam<='$date2mois' ".
                "GROUP by cabinet, dossier.numero order by cabinet, dossier.numero";
            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
// echo $req;die;
            while(list($cabinet, $dossier_id, $date_exam)=mysql_fetch_row($res)){
                $req2="SELECT id from evaluation_infirmier where ".
                    "id='$dossier_id'";
                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                if(mysql_num_rows($res2)>0){
                    if(($date_exam!='')&&($date_exam!='NULL'))
                    {
                        if(diffmois($date_exam, $date2mois)<=0)
                        {
                            if((isset($t_diab[$cabinet]))&&($tcabinet_util[$cabinet]==1)){
                                $tpat['tot'][$exam] = $tpat['tot'][$exam]+1;
                                $tpat[$cabinet][$exam] = $tpat[$cabinet][$exam]+1;
                                if(!isset($tpat[$regions[$cabinet]][$exam])){
                                    $tpat[$regions[$cabinet]][$exam]=0;
                                }
                                $tpat[$regions[$cabinet]][$exam]=$tpat[$regions[$cabinet]][$exam]+1;
                            }

                        }
                    }
                }
            }
        }

        $exam="hba";

        $req="SELECT cabinet, dossier_id, DATE_ADD(max(date_exam), INTERVAL 8 MONTH) ".
            "from suivi_diabete,dossier, liste_exam, evaluation_infirmier ".
            "where cabinet!='zTest' and cabinet!='irdes' ".
            "and cabinet!='ergo' and evaluation_infirmier.id=dossier.id ".
            "and evaluation_infirmier.date<='$date2mois' ".
            "and cabinet!='jgomes' and dossier.cabinet!='sbirault' and suivi_diabete".
            ".dossier_id = dossier.id  AND ( (dossier.actif='oui' AND dossier.dcreat<='$date2mois') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date2mois' AND dossier.dcreat<='$date2mois')) ".
            "and type_exam='HBA1c' and liste_exam.id=dossier.id and date_exam<='$date2mois' ".
            "GROUP by cabinet, dossier.numero order by cabinet, dossier.numero";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
        // echo $req;die;
        while(list($cabinet, $dossier_id, $date_exam)=mysql_fetch_row($res)){
            $req2="SELECT id from evaluation_infirmier where ".
                "id='$dossier_id'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            if(mysql_num_rows($res2)>0){
                if(($date_exam!='')&&($date_exam!='NULL'))
                {
                    if(diffmois($date_exam, $date2mois)<=0)
                    {
                        if((isset($t_diab[$cabinet]))&&($tcabinet_util[$cabinet]==1)){
                            $tpat['tot'][$exam] = $tpat['tot'][$exam]+1;
                            $tpat[$cabinet][$exam] = $tpat[$cabinet][$exam]+1;
                            if(!isset($tpat[$regions[$cabinet]][$exam])){
                                $tpat[$regions[$cabinet]][$exam]=0;
                            }
                            $tpat[$regions[$cabinet]][$exam]=$tpat[$regions[$cabinet]][$exam]+1;
                        }

                    }
                }
            }
        }

        foreach($reg as $region){
            $t_diab[$region]=0;
        }
        $t_diab["tot"]=0;
        foreach($regions as $cab=>$region){
            if((isset($tcabinet_util[$cab]))&&($tcabinet_util[$cab]==1)){
                $t_diab[$region]=$t_diab[$region]+$t_diab[$cab];
                $t_diab["tot"]=$t_diab["tot"]+$t_diab[$cab];
            }
        }

        ?>

        <tr>
            <td>Taux de respect des examens &nbsp;</td>
            <td align="center"> <b>&nbsp;moyenne</b>	 &nbsp;</td>
            <?php

            foreach($reg as $region){
                if($region!=""){
                    echo "<td align='center'> <b>&nbsp;moyenne $region</b>	 &nbsp;</td>";
                }
            }

            // print_r($tpat);
            foreach($tcabinet as $cab) {
                if (($tcabinet_util[$cab]==0)||($t_diab[$cab]==0))
                {
                    $taux_hba[$cab]=$taux_exafil[$cab]=$taux_pied[$cab]=$taux_chol[$cab]=$taux_creat[$cab]="ND";
                    $taux_albu[$cab]=$taux_fond[$cab]=$taux_ecg[$cab]="ND";
                }
                else
                {
                    $taux_hba[$cab]=round($tpat[$cab]['hba']/$t_diab[$cab]*100);
                    $taux_hba[$cab].="%";

                }
                ?>
                <td><b><?php echo $tville[$cab];?></b></td>
                <?php
            }

            ?>
        </tr>
        <?php

        if ($t_diab['tot']==0)
        {
            $taux_hba['tot']=$taux_exafil['tot']=$taux_pied['tot']=$taux_chol['tot']=$taux_creat['tot']='ND';
            $taux_albu['tot']=$taux_fond['tot']=$taux_ecg['tot']="ND";
        }
        else
        {
            $taux_hba['tot']=round($tpat['tot']['hba']/$t_diab['tot']*100);
            $taux_hba['tot'].="%";

        }


        foreach($reg as $region){
            if ($t_diab[$region]==0)
            {
                $taux_hba[$region]=$taux_exafil[$region]=$taux_pied[$region]=$taux_chol[$region]=$taux_creat[$region]='ND';
                $taux_albu[$region]=$taux_fond[$region]=$taux_ecg[$region]="ND";
            }
            else
            {
                $taux_hba[$region]=round($tpat[$region]['hba']/$t_diab[$region]*100);
                $taux_hba[$region].="%";

            }
        }


        $liste_exam=array("hba"=>"HBA1c <sup>1</sup>",
            "monofil"=>"Examen au monofilament <sup>2</sup>",
            "pied"=>"Examen des pieds <sup>3</sup>",
            "HDL"=>"Dosage du HDL - Cholestérol <sup>4</sup>",
            "creat"=>"Créatinémie <sup>5</sup>",
            "albu"=>"Micro Albuminurie <sup>6</sup>",
            "fond"=>"Fond d'oeil <sup>7</sup>",
            "ECG"=>"ECG <sup>8</sup>");


        foreach($liste_exam as $exam=>$libelle){
            echo "<tr><td>$libelle</td>";

            if ($t_diab['tot']==0){
                echo "<td align='right'>ND</td>";
            }
            else{
                $taux=round($tpat['tot'][$exam]/$t_diab['tot']*100);
                echo "<td align='right'>$taux %</td>";
            }

            foreach($reg as $region){
                if ($t_diab[$region]==0){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    $taux=round($tpat[$region][$exam]/$t_diab[$region]*100);
                    echo "<td align='right'>$taux %</td>";
                }
            }

            foreach($tcabinet as $cab){
                if (($tcabinet_util[$cab]==0)||($t_diab[$cab]==0)){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    $taux=round($tpat[$cab][$exam]/$t_diab[$cab]*100);
                    echo "<td align='right'>$taux %</td>";
                }
            }
        }


        ?>

    </table>
    <br>
    <br>
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
