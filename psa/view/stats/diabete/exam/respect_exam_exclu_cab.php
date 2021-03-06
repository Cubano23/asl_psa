<?php
session_start();
if(!isset($_SESSION['nom'])) {
    # pas pass� par l'identification
    $debut=dirname($_SERVER['PHP_SELF']);
    $self=basename($_SERVER['PHP_SELF']);
    header("Location: $debut/ident_util.php?url=$self");
    echo "<a href='$debut/ident_util.php?url=$self'>cliquez ici</a>";
    exit;
}

set_time_limit(120)
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="content-type"
          content="text/html; charset=ISO-8859-15">
    <title>Taux de respect des examens cabinets actifs entre -14 et -2 mois</title>
</head>
<body bgcolor=#FFE887>
<?php
require_once "Config.php";
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");

# connexion aux donn�es
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
die("Impossible de se connecter � la base");


$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";

require("../../global/entete.php");
//echo $loc;

entete_asalee("Taux de respect des examens cabinets actifs entre -14 et -2 mois");
?>

<br><br>
<?

# boucle principale
do {
    $repete=false;


    # �tape 1 : affichage tableau � la date du jour
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

            //affichage tableau � la date du jour
            case 1:
                etape_1($repete);
                break;


        }
    }
} while($repete);

# fin de traitement principal

//affichage tableau � la date du jour
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

        if(!isset($t_diab[$region])){
            $t_diab[$region]=0;
        }
        $t_diab[$region]=$t_diab[$region]+$total_diab2;

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

    $mois=array('01'=>'Janvier', '02'=>'F�vrier', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Ao�t', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'D�cembre');

    echo '<b>Donn�es � la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

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
            ".dossier_id = dossier.id  AND dossier.actif='oui' and ".
            "((dHBA is not NULL and DATE_ADD(dHBA, INTERVAL 8 MONTH) >= '$date2mois') ".
            " or ((dExaFil is not NULL and DATE_ADD(dExaFil, ".
            "INTERVAL 14 MONTH) >= '$date2mois') or (dExaPieds is not NULL and ".
            "DATE_ADD(dExaPieds, INTERVAL 14 MONTH) >= '$date2mois')) or ".
            "((dChol is not NULL and DATE_ADD(dChol, INTERVAL 14 MONTH) >= '$date2mois') ".
            " or (dLDL is not NULL and DATE_ADD(dLDL, INTERVAL 14 MONTH) >= '$date2mois')  ".
            "or (suivi_diabete.dCreat is not NULL and DATE_ADD(suivi_diabete.dCreat, INTERVAL 14 MONTH) >= '$date2mois') or (dAlbu is not NULL ".
            "and DATE_ADD(dAlbu, INTERVAL 14 MONTH) >= '$date2mois') or (dFond is not NULL and DATE_ADD(dFond, ".
            "INTERVAL 14 MONTH) >= '$date2mois') or (dECG is not NULL and DATE_ADD(dECG, INTERVAL 14 MONTH) >= ".
            "'$date2mois'))) GROUP by dossier_id order by dossier_id";

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

        $liste_exam=array("monofil"=>"Examen au monofilament <sup>2</sup>",
            "pied"=>"Examen des pieds <sup>3</sup>",
            "HDL"=>"Dosage du HDL - Cholest�rol <sup>4</sup>",
            "creat"=>"Cr�atin�mie <sup>5</sup>",
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
                            if(!isset($tpat[$regions[$cabinet]][$exam])){
                                $tpat[$regions[$cabinet]][$exam]=0;
                            }
                            $tpat[$regions[$cabinet]][$exam]=$tpat[$regions[$cabinet]][$exam]+1;
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
                        if(!isset($tpat[$regions[$cabinet]]["hba"])){
                            $tpat[$regions[$cabinet]]["hba"]=0;
                        }
                        $tpat[$regions[$cabinet]]["hba"]=$tpat[$regions[$cabinet]]["hba"]+1;
                    }

                }
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
                if ($tcabinet_util[$cab]==0)
                {
                    $taux_hba[$cab]=$taux_exafil[$cab]=$taux_pied[$cab]=$taux_chol[$cab]=$taux_creat[$cab]="ND";
                    $taux_albu[$cab]=$taux_fond[$cab]=$taux_ecg[$cab]="ND";
                }
                else
                {
                    $taux_hba[$cab]=round($tpat[$cab]['hba']/$t_diab[$cab]*100);
                    $taux_hba[$cab].="%";

                    $taux_exafil[$cab]=round($tpat[$cab]['exafil']/$t_diab[$cab]*100);
                    $taux_exafil[$cab].="%";

                    $taux_pied[$cab]=round($tpat[$cab]['pied']/$t_diab[$cab]*100);
                    $taux_pied[$cab].="%";

                    $taux_chol[$cab]=round($tpat[$cab]['chol']/$t_diab[$cab]*100);
                    $taux_chol[$cab].="%";

                    $taux_creat[$cab]=round($tpat[$cab]['creat']/$t_diab[$cab]*100);
                    $taux_creat[$cab].="%";

                    $taux_albu[$cab]=round($tpat[$cab]['albu']/$t_diab[$cab]*100);
                    $taux_albu[$cab].="%";

                    $taux_fond[$cab]=round($tpat[$cab]['fond']/$t_diab[$cab]*100);
                    $taux_fond[$cab].="%";

                    $taux_ecg[$cab]=round($tpat[$cab]['ecg']/$t_diab[$cab]*100);
                    $taux_ecg[$cab].="%";
                }
                ?>
                <td><b><?php echo $tville[$cab];?></b></td>
                <?php
            }

            ?>
        </tr>
        <?php
        foreach($regions as $cab=>$region){
            if((!isset($tcabinet_util[$cab]))||($tcabinet_util[$cab]==0)){
                $t_diab['tot']=$t_diab['tot']-$t_diab[$cab];
                $t_diab[$region]=$t_diab[$region]-$t_diab[$cab];
            }
        }

        $liste_exam=array("hba"=>"HBA1c <sup>1</sup>",
            "monofil"=>"Examen au monofilament <sup>2</sup>",
            "pied"=>"Examen des pieds <sup>3</sup>",
            "HDL"=>"Dosage du HDL - Cholest�rol <sup>4</sup>",
            "creat"=>"Cr�atin�mie <sup>5</sup>",
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
                if ($tcabinet_util[$cab]==0){
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
    <sup>1</sup>Nombre de patients ayant eu un r�sultat de HBA1c dans les 6 derniers mois/potentiel du cabinet<br>
    <sup>2</sup>Nombre de patients ayant eu un r�sultat d'examen au monofilament dans les 12 derniers mois/potentiel du cabinet<br>
    <sup>3</sup>Nombre de patients ayant eu un r�sultat d'examen des pieds dans les 12 derniers mois/potentiel du cabinet<br>
    <sup>4</sup>Nombre de patients ayant eu un r�sultat de dosage du HDL/Cholesterol dans les 12 derniers mois/potentiel du cabinet<br>
    <sup>5</sup>Nombre de patients ayant eu un r�sultat de cr�atin�mie dans les 12 derniers mois/potentiel du cabinet<br>
    <sup>6</sup>Nombre de patients ayant eu un r�sultat de Micro-Albuminurie dans les 12 derniers mois/potentiel du cabinet<br>
    <sup>7</sup>Nombre de patients ayant eu un r�sultat de fond d'oeil dans les 12 derniers mois/potentiel du cabinet<br>
    <sup>8</sup>Nombre de patients ayant eu un r�sultat d'ECG dans les 12 derniers mois/potentiel du cabinet<br>
    Un cabinet est consid�r� comme actif actif si une infirmi�re a �t� pr�sente dans les 3 derniers mois pour une intervention (un suivi diab�te ou une consultation infirmi�re)
    <?
}

//arr�t�s trimestriels
function tableau($date){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_diab, $regions, $reg;


    $mois=array('01'=>'Janvier', '02'=>'F�vrier', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Ao�t', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'D�cembre');

    $tab_date=split('-', $date);

    echo "<b>Donn�es au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";

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
    $t_diab['eval']=0;
    $cab_prec="";

    while(list($cab, $pat) = mysql_fetch_row($res)) {
//	 $tcabinet[] = $cab;

        if($cab!=$cab_prec)
        {
            if((isset($t_diab[$cab]))&&(isset($tcabinet_util[$cab]))&&($tcabinet_util[$cab]==1)){
                $t_diab['tot']=$t_diab['tot']+$t_diab[$cab];
                if(!isset($t_diab[$regions[$cab]])){
                    $t_diab[$regions[$cab]]=0;
                }
                $t_diab[$regions[$cab]]=$t_diab[$regions[$cab]]+$t_diab[$cab];
                // $t_diab["tot"]=$t_diab["tot"]+$t_diab[$cab];
            }
            $cab_prec=$cab;

        }
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
            "and ((dHBA is not NULL and dHBA <= '$date2mois' and DATE_ADD(dHBA, INTERVAL 8 MONTH) >= '$date2mois') ".
            " or ((dExaFil is not NULL and dExaFil<='$date2mois' and DATE_ADD(dExaFil, ".
            "INTERVAL 14 MONTH) >= '$date2mois') or (dExaPieds is not NULL and dExaPieds <='$date2mois' and ".
            "DATE_ADD(dExaPieds, INTERVAL 14 MONTH) >= '$date2mois')) or ".
            "((dChol is not NULL and dChol<='$date2mois' and DATE_ADD(dChol, INTERVAL 14 MONTH) >= '$date2mois') ".
            " or (dLDL is not NULL and dLDL<='$date2mois' and DATE_ADD(dLDL, INTERVAL 14 MONTH) >= '$date')  ".
            "or (suivi_diabete.dCreat is not NULL and suivi_diabete.dCreat<='$date2mois' and DATE_ADD(suivi_diabete.dCreat, ".
            "INTERVAL 14 MONTH) >= '$date2mois') or (dAlbu is not NULL ".
            "and dAlbu<='$date2mois' and DATE_ADD(dAlbu, INTERVAL 14 MONTH) >= '$date2mois') or (dFond is not NULL and ".
            "dFond<='$date2mois' and DATE_ADD(dFond, INTERVAL 14 MONTH) >= '$date2mois') or (dECG is not NULL and ".
            "dECG<='$date2mois' and DATE_ADD(dECG, INTERVAL 14 MONTH) >= ".
            "'$date2mois'))) GROUP by cabinet, numero order by cabinet, numero";



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

            if(($dHBA!='')&&($dHBA!='NULL')){
                if(diffmois($dHBA, $date2mois)<=0)
                {
                    if((isset($t_diab[$cabinet]))&&(isset($tcabinet_util[$cabinet]))&&($tcabinet_util[$cabinet]==1)){
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

        $liste_exam=array("monofil"=>"Examen au monofilament <sup>2</sup>",
            "pied"=>"Examen des pieds <sup>3</sup>",
            "HDL"=>"Dosage du HDL - Cholest�rol <sup>4</sup>",
            "creat"=>"Cr�atin�mie <sup>5</sup>",
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
                ".dossier_id = dossier.id  AND ( (dossier.actif='oui' AND dossier.dcreat<='$date2mois') ".
                "OR (dossier.actif='non' AND dossier.dmaj>'$date2mois' AND dossier.dcreat<='$date2mois')) ".
                "and (date_exam is not NULL) and (DATE_ADD(date_exam, INTERVAL 14 MONTH) >= '$date2mois') ".
                "and date_exam<='$date2mois' ".
                "and type_exam='$exam' and liste_exam.id=dossier.id ".
                "GROUP by cabinet, dossier.numero order by cabinet, dossier.numero";
            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            while(list($cabinet, $dossier_id, $date_exam)=mysql_fetch_row($res)){
                if(($date_exam!='')&&($date_exam!='NULL'))
                {
                    if(diffmois($date_exam, $date2mois)<=0)
                    {
                        if((isset($t_diab[$cabinet]))&&(isset($tcabinet_util[$cabinet]))&&($tcabinet_util[$cabinet]==1)){
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

        $exam="hba";

        $req="SELECT cabinet, dossier_id, DATE_ADD(max(date_exam), INTERVAL 8 MONTH) ".
            "from suivi_diabete,dossier, liste_exam where cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  ".
            "and cabinet!='jgomes' and dossier.cabinet!='sbirault' and suivi_diabete".
            ".dossier_id = dossier.id  AND ( (dossier.actif='oui' AND dossier.dcreat<='$date2mois') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date2mois' AND dossier.dcreat<='$date2mois')) ".
            "and (date_exam is not NULL) and (DATE_ADD(date_exam, INTERVAL 8 MONTH) >= '$date2mois') ".
            "and date_exam<='$date2mois' ".
            "and type_exam='HBA1c' and liste_exam.id=dossier.id ".
            "GROUP by cabinet, dossier.numero order by cabinet, dossier.numero";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        while(list($cabinet, $dossier_id, $date_exam)=mysql_fetch_row($res)){
            if(($date_exam!='')&&($date_exam!='NULL'))
            {
                if(diffmois($date_exam, $date2mois)<=0)
                {
                    if((isset($t_diab[$cabinet]))&&(isset($tcabinet_util[$cabinet]))&&($tcabinet_util[$cabinet]==1)){
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


                ?>
                <td><b><?php echo $tville[$cab]; ?></b></td>
                <?php
            }

            ?>
        </tr>
        <?php


        $liste_exam=array("hba"=>"HBA1c <sup>1</sup>",
            "monofil"=>"Examen au monofilament <sup>2</sup>",
            "pied"=>"Examen des pieds <sup>3</sup>",
            "HDL"=>"Dosage du HDL - Cholest�rol <sup>4</sup>",
            "creat"=>"Cr�atin�mie <sup>5</sup>",
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
                if ($tcabinet_util[$cab]==0){
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

# calcul de la diff�rence en mois � partir d'un timestamp MySQL
function diffmois($date, $ref=false) {

    list($a,$m,$j)= explode('-',$date,3);

    if($ref===false)//aucune date de r�f�rence
    {
        $diff_mois = (date('Y')-$a)*12;
        $diff_mois=$diff_mois+ date('m')-$m;
        /*  if(date('m') < $m) $age--;*/
        if(date('d') < $j) $diff_mois--;
    }
    else //une date de r�f�rence au format 'yyyy-mm-dd-
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
