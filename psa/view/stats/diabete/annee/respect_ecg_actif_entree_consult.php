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
    <title>Taux de respect de l'ECG / dossiers actifs entre -14 et -2 mois par date d'entrée ayant au moins 1 consultation</title>
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

entete_asalee("Taux de respect de l'ECG / dossiers actifs entre -14 et -2 mois par date d'entrée ayant au moins 1 consultation");

echo "<br><br>";

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
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_diab;

    $req="SELECT cabinet, total_diab2, nom_cab, region ".
        "FROM account ".
        "WHERE region!='' and infirmiere!=''  ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $t_diab['tot']=0;

    $annee=2005;
    $i=0;

    while($annee<=date("Y")){
        $t_diab[$i]=0;
        $tpat[$i]=0;
        $tpat_1[$i]=0;
        $pos[$annee]=$i;
        $i++;
        $annee++;
    }

    $date_test=date("Y")."0630";
    if(date("Ymd")>$date_test){
        $pos[$annee]=$i;
        $t_diab[$i]=0;
        $tpat[$i]=0;
        $tpat_1[$i]=0;
    }

    while(list($cab, $total_diab2, $ville, $region) = mysql_fetch_row($res)) {

        if($region!=""){
            // $t_diab[$cab]=0;

            $tville[$cab]=$ville;

            $regions[$cab]=$region;

        }

    }

    $exclu=array();


//Patients avec au moins un suivi
    $req="SELECT cabinet, dossier.id, min(dsuivi), count(*) ".
        "FROM suivi_diabete, dossier, evaluation_infirmier ".
        "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ".
        "AND actif='oui' ".
        "AND evaluation_infirmier.id=dossier.id ".
        "AND suivi_diabete.dossier_id=dossier.id ".
        "GROUP BY cabinet, dossier_id ".
        "ORDER BY cabinet ";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    while(list($cab, $id, $dsuivi) = mysql_fetch_row($res)) {

        $req="SELECT sortie FROM suivi_diabete WHERE dossier_id='$id' order by dsuivi DESC limit 0,1 ";
        $res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        list($sortie)=mysql_fetch_row($res2);

        if($sortie!=1){
            if($_SESSION["national"]==1){
                $date=explode("-", $dsuivi);
                $annee=$date[0];
                $moisannee=$date[1].$date[2];

                if(!isset($pos[$annee])){//Année avant 2005
                    $annee=2005;
                    $av[$id]=$annee;

                    if(isset($regions[$cab])){
                        $t_diab[$pos[$annee]]=$t_diab[$pos[$annee]]+1;
                    }
                }
                elseif($moisannee<="0630"){
                    $av[$id]=$annee;

                    if(isset($regions[$cab])){
                        $t_diab[$pos[$annee]]=$t_diab[$pos[$annee]]+1;
                    }
                }
                else{
                    $annee=$annee+1;
                    $av[$id]=$annee;

                    if(isset($regions[$cab])){
                        $t_diab[$pos[$annee]]=$t_diab[$pos[$annee]]+1;
                    }
                }

            }
            elseif($_SESSION["region"]==1){
                if($regions[$cab]==$_SESSION["nom_region"]){
                    if($dsuivi<='2005-06-30'){
                        $avjuin2005[$id]=1;

                        if(isset($regions[$cab])){
                            $t_diab['eval']=$t_diab['eval']+1;
                        }
                    }
                    elseif(($dsuivi<='2006-06-30')&&($dsuivi>'2005-06-30')){
                        $avjuin2006[$id]=1;

                        if(isset($regions[$cab])){
                            $t_diab['eval2']=$t_diab['eval2']+1;
                        }
                    }
                    elseif(($dsuivi<='2007-06-30')&&($dsuivi>'2006-06-30')){
                        $avjuin2007[$id]=1;

                        if(isset($regions[$cab])){
                            $t_diab['eval3']=$t_diab['eval3']+1;
                        }
                    }
                    elseif(($dsuivi<='2008-06-30')&&($dsuivi>'2007-06-30')){
                        $avjuin2008[$id]=1;

                        if(isset($regions[$cab])){
                            $t_diab['eval4']=$t_diab['eval4']+1;
                        }
                    }
                    else{
                        $apjuin2008[$id]=1;

                        if(isset($regions[$cab])){
                            $t_diab['eval5']=$t_diab['eval5']+1;
                        }
                    }
                }
            }
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

        ///////////////Respect des examens //////////////////////////

        //Patients avec au moins un suivi
        $req="SELECT cabinet, dossier.id, min(dsuivi), count(*) ".
            "FROM suivi_diabete, dossier, evaluation_infirmier ".
            "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND actif='oui' ".
            "AND evaluation_infirmier.id=dossier.id ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "GROUP BY cabinet, dossier_id ".
            "ORDER BY cabinet ";
        //echo $req;
        //die;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        while(list($cab, $dossier_id, $dsuivi)=mysql_fetch_row($res)){
            $req3="SELECT sortie FROM suivi_diabete WHERE dossier_id='$dossier_id' order by dsuivi DESC limit 0,1";

            $res3=mysql_query($req3) or die("erreur SQL:".mysql_error()."<br>$req3");

            list($sortie)=mysql_fetch_row($res3);

            if($sortie!=1){
                if($_SESSION["national"]==1){
                    $req2="SELECT date_exam FROM liste_exam WHERE id='$dossier_id' and type_exam='ECG' ".
                        "AND DATE_ADD(date_exam, INTERVAL 14 MONTH)>=CURDATE() ".
                        "AND DATE_ADD(date_exam, INTERVAL 2 MONTH)<=CURDATE()";

                    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                    if(mysql_num_rows($res2)>=1){
                        $annee=$av[$dossier_id];

                        $tpat[$pos[$annee]]=$tpat[$pos[$annee]]+1;

                    }
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        $req2="SELECT dHBA FROM suivi_diabete WHERE dossier_id='$dossier_id' AND DATE_ADD(dalbu, INTERVAL 14 MONTH)>=CURDATE() ".
                            "AND DATE_ADD(dalbu, INTERVAL 2 MONTH)<=CURDATE() ";

                        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                        if(mysql_num_rows($res2)>=1){
                            if(isset($avjuin2005[$dossier_id]))
                            {
                                if(isset($regions[$cab])){
                                    $tpat['eval']=$tpat['eval']+1;
                                }
                            }
                            elseif(isset($avjuin2006[$dossier_id]))
                            {
                                if(isset($regions[$cab])){
                                    $tpat['eval2']=$tpat['eval2']+1;
                                }
                            }
                            elseif(isset($avjuin2007[$dossier_id]))
                            {
                                if(isset($regions[$cab])){
                                    $tpat['eval3']=$tpat['eval3']+1;
                                }
                            }
                            elseif(isset($avjuin2008[$dossier_id]))
                            {
                                if(isset($regions[$cab])){
                                    $tpat['eval4']=$tpat['eval4']+1;
                                }
                            }
                            elseif(isset($apjuin2008[$dossier_id]))
                            {
                                if(isset($regions[$cab])){
                                    $tpat['eval5']=$tpat['eval5']+1;
                                }
                            }
                        }
                    }
                }
            }
        }




        ?>

        <tr>
            <td>Taux de respect des examens &nbsp;</td>
            <td> <b>&nbsp;Total</b> &nbsp;</td>
            <?php
            foreach($pos as $annee=>$i){
                $test=$annee."0630";
                if($annee==2005){
                    echo "<td align='center'> <b>&nbsp;avant le 30/06/2005</b> &nbsp;</Td>";
                }
                elseif($test<=date("Ymd")){
                    $annee_inf=$annee-1;
                    echo "<td align='center'><b>&nbsp; entre le 01/07/$annee_inf et le 30/06/$annee</b> &nbsp;</td>";
                }
                else{
                    $annee_inf=$annee-1;
                    echo "<td align='center'><b>&nbsp;après le 30/06/$annee_inf</b> &nbsp;</td>";
                }
            }


            ?>
        </tr>
        <?php

        $t_diab['total']=0;
        foreach($pos as $i){
            $t_diab["total"]=$t_diab["total"]+$t_diab[$i];
        }

        $tpat['total']=0;
        foreach($tpat as $nb){
            $tpat["total"]=$tpat["total"]+$nb;
        }

        // $t_diab['eval']+$t_diab['eval2']+$t_diab['eval3']+$t_diab['eval4']+$t_diab['eval5'];
        $taux_hba['total']=round($tpat['total']/$t_diab['total']*100);
        $taux_hba['total'].="%";

        ?>
        <tr>
            <td>1 ECG dans l'année écoulée<sup>1</sup></td>
            <td align='right'><?php echo $taux_hba['total']; ?></td>

            <?php

            foreach($pos as $annee=>$i){

                if($t_diab[$i]==0){
                    $val="ND";
                }
                else{
                    $val=round($tpat[$i]/$t_diab[$i]*100);
                    $val.="%";
                }

                echo "<td align='right'>$val</td>";
            }

            ?>
        </tr>

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
        tableau($date, $regions);

        $mois=$mois-3;

        if($mois<=0)
        {
            $mois=$mois+12;
            $annee--;
        }
    }
    ?>
    <sup>1</sup>Nombre de patients ayant eu au moins 1 ECG entre -14 et -2 mois/nb dossiers actifs avec un suivi et une consultation<br>

    <?
}

//arrêtés trimestriels
function tableau($date, $regions){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_diab;


    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

    $tab_date=split('-', $date);

    echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";





    $req="SELECT cabinet, total_diab2, nom_cab, region ".
        "FROM account ".
        "WHERE region!='' and infirmiere!='' ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $t_diab['tot']=0;
    $annee=2005;
    $i=0;

    while($annee<=date("Y")){
        $t_diab[$i]=0;
        $tpat[$i]=0;
        $tpat_1[$i]=0;
        $pos[$annee]=$i;
        $i++;
        $annee++;
    }

    $date_test=date("Y")."0630";
    if(date("Ymd")>$date_test){
        $pos[$annee]=$i;
        $t_diab[$i]=0;
        $tpat[$i]=0;
        $tpat_1[$i]=0;
    }


    while(list($cab, $total_diab2, $ville, $region) = mysql_fetch_row($res)) {
        if($region!=""){
            // $t_diab[$cab]=0;

            $tville[$cab]=$ville;
        }
    }

    $exclu=array();


//Patients avec au moins un suivi
    $req="SELECT cabinet, dossier.id, min(dsuivi), count(*) ".
        "FROM suivi_diabete, dossier, evaluation_infirmier ".
        "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ".
        "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
        "AND dsuivi<='$date' AND evaluation_infirmier.date<='$date' ".
        "AND evaluation_infirmier.id=dossier.id ".
        "AND suivi_diabete.dossier_id=dossier.id ".
        "GROUP BY cabinet, dossier_id ".
        "ORDER BY cabinet ";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    while(list($cab, $id, $dsuivi) = mysql_fetch_row($res)) {

        $req2="SELECT sortie FROM suivi_diabete WHERE dsuivi<='$date' AND dossier_id='$id' ORDER BY dsuivi DESC limit 0,1";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

        list($sortie)=mysql_fetch_row($res2);

        if($sortie!=1){
            if($_SESSION["national"]==1){
                $datesuivi=explode("-", $dsuivi);
                $annee=$datesuivi[0];
                $moisannee=$datesuivi[1].$datesuivi[2];

                if(!isset($pos[$annee])){//Année avant 2005
                    $annee=2005;
                    $av[$id]=$annee;

                    if(isset($regions[$cab])){
                        $t_diab[$pos[$annee]]=$t_diab[$pos[$annee]]+1;
                    }
                }
                elseif($moisannee<="0630"){
                    $av[$id]=$annee;

                    if(isset($regions[$cab])){
                        $t_diab[$pos[$annee]]=$t_diab[$pos[$annee]]+1;
                    }
                }
                else{
                    $annee=$annee+1;
                    $av[$id]=$annee;

                    if(isset($regions[$cab])){
                        $t_diab[$pos[$annee]]=$t_diab[$pos[$annee]]+1;
                    }
                }

            }
            elseif($_SESSION["region"]==1){
                if($regions[$cab]==$_SESSION["nom_region"]){
                    if($dsuivi<='2005-06-30')
                    {
                        $avjuin2005[$id]=1;

                        if(isset($regions[$cab])){
                            $t_diab['eval']=$t_diab['eval']+1;
                        }
                    }
                    elseif(($dsuivi<='2006-06-30')&&($dsuivi>'2005-06-30'))
                    {
                        $avjuin2006[$id]=1;

                        if(isset($regions[$cab])){
                            $t_diab['eval2']=$t_diab['eval2']+1;
                        }
                    }
                    elseif(($dsuivi<='2007-06-30')&&($dsuivi>'2006-06-30'))
                    {
                        $avjuin2007[$id]=1;

                        if(isset($regions[$cab])){
                            $t_diab['eval3']=$t_diab['eval3']+1;
                        }
                    }
                    elseif(($dsuivi<='2008-06-30')&&($dsuivi>'2007-06-30'))
                    {
                        $avjuin2008[$id]=1;

                        if(isset($regions[$cab])){
                            $t_diab['eval4']=$t_diab['eval4']+1;
                        }
                    }
                    else
                    {
                        $apjuin2008[$id]=1;

                        if(isset($regions[$cab])){
                            $t_diab['eval5']=$t_diab['eval5']+1;
                        }
                    }
                }
            }
        }


    }



    ?>
    <br>
    <br>
    <table border=1 align='center'>
        <?php

        ///////////////Respect des examens //////////////////////////



        $req="SELECT cabinet, dossier.id ".
            "FROM suivi_diabete, dossier, evaluation_infirmier ".
            "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND dsuivi<='$date' AND evaluation_infirmier.date<='$date' ".
            "AND evaluation_infirmier.id=dossier.id ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "GROUP BY cabinet, dossier_id ".
            "ORDER BY cabinet ";
        //echo $req;
        //die;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        while(list($cab, $dossier_id) = mysql_fetch_row($res)) {

            $req3="SELECT sortie FROM suivi_diabete WHERE dossier_id='$dossier_id' AND dsuivi<='$date' ORDER BY dsuivi DESC limit 0,1";

            $res3=mysql_query($req3) or die("erreur SQL:".mysql_error()."<br>$req3");

            list($sortie)=mysql_fetch_row($res3);

            if(isset($regions[$cab])&&($sortie!=1)){
                $req2="SELECT date_exam FROM liste_exam WHERE id='$dossier_id' and type_exam='ECG' ".
                    "AND DATE_ADD(date_exam, INTERVAL 14 MONTH)>='$date' ".
                    "AND DATE_ADD(date_exam, INTERVAL 2 MONTH)<='$date'";

                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                if(mysql_num_rows($res2)>=1){
                    if($_SESSION["national"]==1){
                        if(isset($av[$dossier_id])){
                            $annee=$av[$dossier_id];

                            $tpat[$pos[$annee]]=$tpat[$pos[$annee]]+1;
                        }

                    }
                    elseif($_SESSION["region"]==1){
                        if(isset($avjuin2005[$dossier_id]))
                        {
                            if(isset($regions[$cab])){
                                $tpat['eval']=$tpat['eval']+1;
                            }
                        }
                        elseif(isset($avjuin2006[$dossier_id]))
                        {
                            if(isset($regions[$cab])){
                                $tpat['eval2']=$tpat['eval2']+1;
                            }
                        }
                        elseif(isset($avjuin2007[$dossier_id]))
                        {
                            if(isset($regions[$cab])){
                                $tpat['eval3']=$tpat['eval3']+1;
                            }
                        }
                        elseif(isset($avjuin2008[$dossier_id]))
                        {
                            if(isset($regions[$cab])){
                                $tpat['eval4']=$tpat['eval4']+1;
                            }
                        }
                        elseif(isset($apjuin2008[$dossier_id]))
                        {
                            if(isset($regions[$cab])){
                                $tpat['eval5']=$tpat['eval5']+1;
                            }
                        }
                    }
                }
            }
        }


        ?>

        <tr>
            <td>Taux de respect des examens &nbsp;</td>
            <td align="center"> <b>&nbsp;Total</b>	 &nbsp;</td>
            <?php
            foreach($pos as $annee=>$i){
                $test=$annee."0630";
                if($annee==2005){
                    echo "<td align='center'> <b>&nbsp;avant le 30/06/2005</b> &nbsp;</Td>";
                }
                elseif($test<=date("Ymd")){
                    $annee_inf=$annee-1;
                    echo "<td align='center'><b>&nbsp; entre le 01/07/$annee_inf et le 30/06/$annee</b> &nbsp;</td>";
                }
                else{
                    $annee_inf=$annee-1;
                    echo "<td align='center'><b>&nbsp;après le 30/06/$annee_inf</b> &nbsp;</td>";
                }
            }

            ?>
        </tr>
        <?php

        $t_diab['total']=0;
        foreach($pos as $i){
            $t_diab["total"]=$t_diab["total"]+$t_diab[$i];
        }

        $tpat['total']=0;
        foreach($tpat as $nb){
            $tpat["total"]=$tpat["total"]+$nb;
        }

        // $t_diab['eval']+$t_diab['eval2']+$t_diab['eval3']+$t_diab['eval4']+$t_diab['eval5'];
        $taux_hba['total']=round($tpat['total']/$t_diab['total']*100);
        $taux_hba['total'].="%";

        ?>
        <tr>
            <td>1 ECG dans l'année écoulée<sup>1</sup></td>
            <td align='right'><?php echo $taux_hba['total']; ?></td>

            <?php

            foreach($pos as $annee=>$i){

                if($t_diab[$i]==0){
                    $val="ND";
                }
                else{
                    $val=round($tpat[$i]/$t_diab[$i]*100);
                    $val.="%";
                }

                echo "<td align='right'>$val</td>";
            }

            ?>
        </tr>


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
