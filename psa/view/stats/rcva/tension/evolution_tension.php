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

set_time_limit(120);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="content-type"
          content="text/html; charset=ISO-8859-15">
    <title>Tension moyenne à la date du jour et 1 an avant - cabinets actifs</title>
</head>
<body bgcolor=#FFE887>
<?php
require_once ("Config.php");
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

entete_asalee("Tension moyenne à la date du jour et 1 an avant - cabinets actifs");

//echo $loc;
?>

<br><br>
<?

# boucle principale
do {
    $repete=false;

    # étape 1 : affichage tableau
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {
            //affihcage tableau
            case 1:
                etape_1($repete);
                break;

        }
    }
} while($repete);

# fin de traitement principal

//affichage tableau
function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $regions, $liste_reg;



    $req="SELECT dossier.cabinet, count(*), nom_cab, region ".
        "FROM dossier, account ".
        "WHERE region!='' and infirmiere!='' ".
        "AND actif='oui' ".
        "and dossier.cabinet=account.cabinet ".
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab, numero ";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if (mysql_num_rows($res)==0) {
        exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }
    $tcabinet=array();
    $liste_reg=array();

    while(list($cab, $pat, $ville, $region) = mysql_fetch_row($res)) {
        $tcabinet[] = $cab;
        $tville[$cab]=$ville;
        $regions[$cab]=$region;

        if(!in_array($region, $liste_reg)){
            $liste_reg[]=$region;
        }

//	 $tpat[$cab] = $pat;
    }

    foreach($tville as $cab=>$ville){
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
        "dossier.id=suivi_diabete.dossier_id and dsuivi>='$date3mois' ".
        "group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        $tcabinet_util[$cab]=1;
    }

    sort($liste_reg);
    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');

    echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

    ?>
    <br>
    <br>
    <table border=1 width='100%'>

        <?php

        //RCVA suivis dans asalée
        $req="SELECT cabinet, dossier.id, date_exam, resultat1 ".
            "FROM cardio_vasculaire_depart, dossier, liste_exam ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and dossier.cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND actif='oui' ".
            "AND cardio_vasculaire_depart.id=dossier.id and dossier.id=liste_exam.id ".
            "and type_exam='systole' and date_exam>'0000-00-00' ".
            "ORDER BY cabinet, dossier.id, date_exam ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;//nb patients
            $syst[$cab]=0;//Valeur total systole
            $diast[$cab]=0;//Valeur total diastole
            $tpat_consult[$cab]=0;//nb patients consult
            $syst_consult[$cab]=0;//Valeur total systole
            $diast_consult[$cab]=0;//Valeur total diastole

            $syst1an[$cab]=0;//Valeur total systole 1 an avant
            $diast1an[$cab]=0;//Valeur total diastole 1 an avant
            $syst_consult1an[$cab]=0;//Valeur total systole 1 an avant
            $diast_consult1an[$cab]=0;//Valeur total diastole 1 an avant
        }

        $tpat['tot']=0;
        $syst['tot']=0;
        $diast['tot']=0;
        $tpat_consult['tot']=0;
        $syst_consult['tot']=0;
        $diast_consult['tot']=0;

        $syst1an['tot']=0;
        $diast1an['tot']=0;
        $syst_consult1an['tot']=0;
        $diast_consult1an['tot']=0;

        foreach($liste_reg as $reg){
            $tpat[$reg]=0;
            $syst[$reg]=0;
            $diast[$reg]=0;
            $tpat_consult[$reg]=0;
            $syst_consult[$reg]=0;
            $diast_consult[$reg]=0;

            $syst1an[$reg]=0;
            $diast1an[$reg]=0;
            $syst_consult1an[$reg]=0;
            $diast_consult1an[$reg]=0;
        }


        $id_prec='';
        $cab_prec="";
        $i=0;
        while(list($cab, $id, $dtension, $TaSys) = mysql_fetch_row($res)) {
            $req2="SELECT resultat1 from liste_exam where id='$id' and type_exam='diastole' and ".
                "date_exam='$dtension'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            list($TaDia)=mysql_fetch_row($res2);

            if(isset($regions[$cab_prec])){
                if($tcabinet_util[$cab_prec]==1){
                    $i++;
                    if($id_prec!=$id)
                    {

                        if($id_prec!='')
                        {
                            $tension_prec=explode("-", $tension_prec);
                            $tension_prec[0]=$tension_prec[0]-1;
                            //Vérif s'il y a eu une tension 1 an avant, +/- 2 mois
                            $req2="SELECT date_exam, resultat1 from liste_exam WHERE ".
                                "DATEDIFF(date_exam, '".$tension_prec[0]."-".
                                $tension_prec[1]."-".$tension_prec[2]."')>-60 and ".
                                "DATEDIFF(date_exam, '".$tension_prec[0]."-".
                                $tension_prec[1]."-".$tension_prec[2]."')<60 and ".
                                "id='$id_prec' and type_exam='systole' ".
                                "order by date_exam DESC limit 0,1";
                            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                            if(mysql_num_rows($res2)>0){
                                list($date1an, $sys1an)=mysql_fetch_row($res2);
                                $req2="SELECT resultat1 from liste_exam WHERE ".
                                    "id='$id_prec' and type_exam='diastole' ".
                                    "and date_exam='$date1an' order by date_exam DESC limit 0,1";
                                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                                list($dia1an)=mysql_fetch_row($res2);

                                //Vérif si le patient a bien eu une consult
                                $req2="SELECT date from evaluation_infirmier WHERE id='$id'";
                                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                                if(mysql_num_rows($res2)>0){
                                    $tpat_consult['tot']=$tpat_consult['tot']+1;
                                    $tpat_consult[$cab_prec]=$tpat_consult[$cab_prec]+1;
                                    $tpat_consult[$regions[$cab_prec]]=$tpat_consult[$regions[$cab_prec]]+1;

                                    $syst_consult['tot']=$syst_consult['tot']+$TaSys_prec;
                                    $syst_consult[$cab_prec]=$syst_consult[$cab_prec]+$TaSys_prec;
                                    $syst_consult[$regions[$cab_prec]]=$syst_consult[$regions[$cab_prec]]+$TaSys_prec;

                                    $diast_consult['tot']=$diast_consult['tot']+$TaDia_prec;
                                    $diast_consult[$cab_prec]=$diast_consult[$cab_prec]+$TaDia_prec;
                                    $diast_consult[$regions[$cab_prec]]=$diast_consult[$regions[$cab_prec]]+$TaDia_prec;

                                    $syst_consult1an[$cab_prec]=$syst_consult1an[$cab_prec]+$sys1an;
                                    $syst_consult1an[$regions[$cab_prec]]=$syst_consult1an[$regions[$cab_prec]]+$sys1an;
                                    $syst_consult1an['tot']=$syst_consult1an['tot']+$sys1an;

                                    $diast_consult1an[$cab_prec]=$diast_consult1an[$cab_prec]+$dia1an;
                                    $diast_consult1an[$regions[$cab_prec]]=$diast_consult1an[$regions[$cab_prec]]+$dia1an;
                                    $diast_consult1an['tot']=$diast_consult1an['tot']+$dia1an;

                                }
                                $tpat['tot']=$tpat['tot']+1;
                                $tpat[$cab_prec]=$tpat[$cab_prec]+1;
                                $tpat[$regions[$cab_prec]]=$tpat[$regions[$cab_prec]]+1;

                                $syst['tot']=$syst['tot']+$TaSys_prec;
                                $syst[$cab_prec]=$syst[$cab_prec]+$TaSys_prec;
                                $syst[$regions[$cab_prec]]=$syst[$regions[$cab_prec]]+$TaSys_prec;

                                $diast['tot']=$diast['tot']+$TaDia_prec;
                                $diast[$cab_prec]=$diast[$cab_prec]+$TaDia_prec;
                                $diast[$regions[$cab_prec]]=$diast[$regions[$cab_prec]]+$TaDia_prec;

                                $syst1an['tot']=$syst1an['tot']+$sys1an;
                                $syst1an[$cab_prec]=$syst1an[$cab_prec]+$sys1an;
                                $syst1an[$regions[$cab_prec]]=$syst1an[$regions[$cab_prec]]+$sys1an;

                                $diast1an['tot']=$diast1an['tot']+$dia1an;
                                $diast1an[$cab_prec]=$diast1an[$cab_prec]+$dia1an;
                                $diast1an[$regions[$cab_prec]]=$diast1an[$regions[$cab_prec]]+$dia1an;
                            }

                            $cab_prec=$cab;
                            $TaSys_prec=$TaSys;
                            $TaDia_prec=$TaDia;
                            $id_prec=$id;
                            $tension_prec=$dtension;


                        }
                        else
                        {
                            $id_prec=$id;
                            $TaSys_prec=$TaSys;
                            $TaDia_prec=$TaDia;
                            $cab_prec=$cab;
                            $tension_prec=$dtension;

                        }

                    }
                    else
                    {
                        if(($TaSys!=0)&&($TaSys!='NULL')&&($TaDia!=0)&&($TaDia!='NULL'))
                        {
                            $TaSys_prec=$TaSys;
                            $TaDia_prec=$TaDia;
                            $tension_prec=$dtension;
                        }
                    }
                }
                else
                {
                    $id_prec=$id;
                    $TaSys_prec=$TaSys;
                    $TaDia_prec=$TaDia;
                    $cab_prec=$cab;
                    $tension_prec=$dtension;

                }
            }
            else
            {
                $id_prec=$id;
                $TaSys_prec=$TaSys;
                $TaDia_prec=$TaDia;
                $cab_prec=$cab;
                $tension_prec=$dtension;

            }
        }

        if(isset($regions[$cab_prec])){
            if($tcabinet_util[$cab_prec]==1){
                $tension_prec=explode("-", $tension_prec);
                $tension_prec[0]=$tension_prec[0]-1;
                //Vérif s'il y a eu une tension 1 an avant, +/- 2 mois
                $req2="SELECT date_exam, resultat1 from liste_exam WHERE ".
                    "DATEDIFF(date_exam, '".$tension_prec[0]."-".
                    $tension_prec[1]."-".$tension_prec[2]."')>-60 and ".
                    "DATEDIFF(date_exam, '".$tension_prec[0]."-".
                    $tension_prec[1]."-".$tension_prec[2]."')<60 and ".
                    "id='$id_prec' and type_exam='systole' ".
                    "order by date_exam DESC limit 0,1";
                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                if(mysql_num_rows($res2)>0){
                    list($date1an, $sys1an)=mysql_fetch_row($res2);
                    $req2="SELECT resultat1 from liste_exam WHERE ".
                        "id='$id_prec' and type_exam='diastole' ".
                        "and date_exam='$date1an' order by date_exam DESC limit 0,1";
                    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                    list($dia1an)=mysql_fetch_row($res2);
                    //Vérif si le patient a bien eu une consult
                    $req2="SELECT date from evaluation_infirmier WHERE id='$id'";
                    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                    if(mysql_num_rows($res2)>0){
                        $tpat_consult['tot']=$tpat_consult['tot']+1;
                        $tpat_consult[$cab_prec]=$tpat_consult[$cab_prec]+1;
                        $tpat_consult[$regions[$cab_prec]]=$tpat_consult[$regions[$cab_prec]]+1;

                        $syst_consult['tot']=$syst_consult['tot']+$TaSys_prec;
                        $syst_consult[$cab_prec]=$syst_consult[$cab_prec]+$TaSys_prec;
                        $syst_consult[$regions[$cab_prec]]=$syst_consult[$regions[$cab_prec]]+$TaSys_prec;

                        $diast_consult['tot']=$diast_consult['tot']+$TaDia_prec;
                        $diast_consult[$cab_prec]=$diast_consult[$cab_prec]+$TaDia_prec;
                        $diast_consult[$regions[$cab_prec]]=$diast_consult[$regions[$cab_prec]]+$TaDia_prec;

                        $syst_consult1an[$cab_prec]=$syst_consult1an[$cab_prec]+$sys1an;
                        $syst_consult1an[$regions[$cab_prec]]=$syst_consult1an[$regions[$cab_prec]]+$sys1an;
                        $syst_consult1an["tot"]=$syst_consult1an["tot"]+$sys1an;

                        $diast_consult1an[$cab_prec]=$diast_consult1an[$cab_prec]+$dia1an;
                        $diast_consult1an[$regions[$cab_prec]]=$diast_consult1an[$regions[$cab_prec]]+$dia1an;
                        $diast_consult1an["tot"]=$diast_consult1an["tot"]+$dia1an;

                    }
                    $tpat['tot']=$tpat['tot']+1;
                    $tpat[$cab_prec]=$tpat[$cab_prec]+1;
                    $tpat[$regions[$cab_prec]]=$tpat[$regions[$cab_prec]]+1;

                    $syst['tot']=$syst['tot']+$TaSys_prec;
                    $syst[$cab_prec]=$syst[$cab_prec]+$TaSys_prec;
                    $syst[$regions[$cab_prec]]=$syst[$regions[$cab_prec]]+$TaSys_prec;

                    $diast['tot']=$diast['tot']+$TaDia_prec;
                    $diast[$cab_prec]=$diast[$cab_prec]+$TaDia_prec;
                    $diast[$regions[$cab_prec]]=$diast[$regions[$cab_prec]]+$TaDia_prec;

                    $syst1an['tot']=$syst1an['tot']+$sys1an;
                    $syst1an[$cab_prec]=$syst1an[$cab_prec]+$sys1an;
                    $syst1an[$regions[$cab_prec]]=$syst1an[$regions[$cab_prec]]+$sys1an;

                    $diast1an['tot']=$diast1an['tot']+$dia1an;
                    $diast1an[$cab_prec]=$diast1an[$cab_prec]+$dia1an;
                    $diast1an[$regions[$cab_prec]]=$diast1an[$regions[$cab_prec]]+$dia1an;
                }
            }
        }

        echo "<br><br>";
        ?>

        <tr>
            <td colspan='2'></td>
            <td align="center"><b>Moyenne</b></td>
            <?php


            foreach($liste_reg as $reg){
                echo "<td align='center'><b>moyenne $reg</b></td>";
            }

            foreach ($tcabinet as $cab)
            {
                ?>
                <td align="center"><b><?php echo $tville[$cab];?></b></td>
                <?php

            }
            ?>
        </tr>

        <tr>
            <td rowspan='3'>Tous patients confondus</td>
            <td>Valeur moyenne de la dernière tension</td>
            <?php

            echo "<td align='right'>".round($syst["tot"]/$tpat["tot"])."/".round($diast["tot"]/$tpat["tot"])."</td>";

            foreach($liste_reg as $reg){
                if($tpat[$reg]==0){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round($syst[$reg]/$tpat[$reg])."/".round($diast[$reg]/$tpat[$reg])."</td>";
                }
            }

            foreach ($tcabinet as $cab)
            {
                if($tpat[$cab]==0){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round($syst[$cab]/$tpat[$cab])."/".round($diast[$cab]/$tpat[$cab])."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Valeur de la tension 1 an auparavant</td>
            <?php
            echo "<td align='right'>".round($syst1an["tot"]/$tpat["tot"])."/".round($diast1an["tot"]/$tpat["tot"])."</td>";

            foreach($liste_reg as $reg){
                if($tpat[$reg]==0){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round($syst1an[$reg]/$tpat[$reg])."/".round($diast1an[$reg]/$tpat[$reg])."</td>";
                }
            }

            foreach ($tcabinet as $cab)
            {
                if($tpat[$cab]==0){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round($syst1an[$cab]/$tpat[$cab])."/".round($diast1an[$cab]/$tpat[$cab])."</td>";
                }
            }
            echo "</tr>";
            ?>
        <tr>
            <td>Nombre de patients concernés</td>
            <?php
            echo "<td align='right'>".$tpat["tot"]."</td>";

            foreach($liste_reg as $reg){
                echo "<td align='right'>".$tpat[$reg]."</td>";
            }

            foreach ($tcabinet as $cab)
            {
                echo "<td align='right'>".$tpat[$cab]."</td>";
            }
            echo "</tr>";


            ?>
        <tr>
            <td rowspan='3'>Patients vus en consultation</td>
            <td>Valeur moyenne de la dernière tension</td>
            <?php

            echo "<td align='right'>".round($syst_consult["tot"]/$tpat_consult["tot"])."/".round($diast_consult["tot"]/$tpat_consult["tot"])."</td>";

            foreach($liste_reg as $reg){
                if($tpat[$reg]==0){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round($syst_consult[$reg]/$tpat_consult[$reg])."/".round($diast_consult[$reg]/$tpat_consult[$reg])."</td>";
                }
            }

            foreach ($tcabinet as $cab)
            {
                if($tpat_consult[$cab]==0){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round($syst_consult[$cab]/$tpat_consult[$cab])."/".round($diast_consult[$cab]/$tpat_consult[$cab])."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Valeur de la tension 1 an auparavant</td>
            <?php
            echo "<td align='right'>".round($syst_consult1an["tot"]/$tpat_consult["tot"])."/".round($diast_consult1an["tot"]/$tpat_consult["tot"])."</td>";

            foreach($liste_reg as $reg){
                if($tpat[$reg]==0){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round($syst_consult1an[$reg]/$tpat_consult[$reg])."/".round($diast_consult1an[$reg]/$tpat_consult[$reg])."</td>";
                }
            }

            foreach ($tcabinet as $cab)
            {
                if($tpat_consult[$cab]==0){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round($syst_consult1an[$cab]/$tpat_consult[$cab])."/".round($diast_consult1an[$cab]/$tpat_consult[$cab])."</td>";
                }
            }
            echo "</tr>";
            ?>
        <tr>
            <td>Nombre de patients concernés</td>
            <?php
            echo "<td align='right'>".$tpat_consult["tot"]."</td>";

            foreach($liste_reg as $reg){
                echo "<td align='right'>".$tpat_consult[$reg]."</td>";
            }

            foreach ($tcabinet as $cab)
            {
                echo "<td align='right'>".$tpat_consult[$cab]."</td>";
            }
            echo "</tr>";

            ?>
        <tr>
            <td rowspan='3'>Patients non vus en consultation</td>
            <td>Valeur moyenne de la dernière tension</td>
            <?php

            echo "<td align='right'>".round(($syst["tot"]-$syst_consult["tot"])/($tpat["tot"]-$tpat_consult["tot"]))."/".round(($diast["tot"]-$diast_consult["tot"])/($tpat["tot"]-$tpat_consult["tot"]))."</td>";

            foreach($liste_reg as $reg){
                if($tpat[$reg]==$tpat_consult[$reg]){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round(($syst[$reg]-$syst_consult[$reg])/($tpat[$reg]-$tpat_consult[$reg]))."/".round(($diast[$reg]-$diast_consult[$reg])/($tpat[$reg]-$tpat_consult[$reg]))."</td>";
                }
            }

            foreach ($tcabinet as $cab)
            {
                if($tpat[$cab]==$tpat_consult[$cab]){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round(($syst[$cab]-$syst_consult[$cab])/($tpat[$cab]-$tpat_consult[$cab]))."/".round(($diast[$cab]-$diast_consult[$cab])/($tpat[$cab]-$tpat_consult[$cab]))."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Valeur de la tension 1 an auparavant</td>
            <?php
            echo "<td align='right'>".round(($syst1an["tot"]-$syst_consult1an["tot"])/($tpat["tot"]-$tpat_consult["tot"]))."/".round(($diast1an["tot"]-$diast_consult1an["tot"])/($tpat["tot"]-$tpat_consult["tot"]))."</td>";

            foreach($liste_reg as $reg){
                if($tpat[$reg]==0){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round(($syst1an[$reg]-$syst_consult1an[$reg])/($tpat[$reg]-$tpat_consult[$reg]))."/".round(($diast1an[$reg]-$diast_consult1an[$reg])/($tpat[$reg]-$tpat_consult[$reg]))."</td>";
                }
            }

            foreach ($tcabinet as $cab)
            {
                if($tpat[$cab]==$tpat_consult[$cab]){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round(($syst1an[$cab]-$syst_consult1an[$cab])/($tpat[$cab]-$tpat_consult[$cab]))."/".round(($diast1an[$cab]-$diast_consult1an[$cab])/($tpat[$cab]-$tpat_consult[$cab]))."</td>";
                }
            }
            echo "</tr>";
            ?>
        <tr>
            <td>Nombre de patients concernés</td>
            <?php
            echo "<td align='right'>".($tpat["tot"]-$tpat_consult["tot"])."</td>";

            foreach($liste_reg as $reg){
                echo "<td align='right'>".($tpat[$reg]-$tpat_consult[$reg])."</td>";
            }

            foreach ($tcabinet as $cab)
            {
                echo "<td align='right'>".($tpat[$cab]-$tpat_consult[$cab])."</td>";
            }
            echo "</tr>";



            ?>
    </table>
    <br><br>
    <?php
    die;

}



?>
</body>
</html>
