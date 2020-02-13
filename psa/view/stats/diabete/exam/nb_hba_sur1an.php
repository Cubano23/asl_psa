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
    <title>Nombre d'examens du HBA1c réalisés lors des 12 derniers mois</title>
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

entete_asalee("Nombre de HBA1c réalisés lors des 12 derniers mois");

//echo $loc;
?>

<br><br>
<?

# boucle principale
do {
    $repete=false;


    # étape 1 : tableau à la date du jour
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

            //tableau à la date du joru
            case 1:
                etape_1($repete);
                break;

        }
    }
} while($repete);

# fin de traitement principal

//tableau à la date du jour
function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $reg, $regions;

    $req="SELECT cabinet, nom_cab, region ".
        "FROM account ".
        "WHERE region!='' and infirmiere!='' ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $t_diab['tot']=0;
    $reg=array();

    while(list($cab, $ville, $region) = mysql_fetch_row($res)) {
        $t_diab[$cab]=array();
        $tville[$cab]=$ville;
        $plus3[$cab]=0;
        $moins3[$cab]=0;
        $regions[$cab]=$region;
        $t_diab[$region]=array();

        if(!in_array($region, $reg)){
            $reg[]=$region;
            $moins3[$region]=0;
            $plus3[$region]=0;
        }
    }

    $req="SELECT dossier.cabinet, count(*) ".
        "FROM dossier, account ".
        "WHERE dossier.cabinet!='zTest' and dossier.cabinet!='irdes'  and dossier.cabinet!='ergo'  and ".
        "dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' and dossier.cabinet=account.cabinet and region!='' ".
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
    }

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');

    echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";


    ?>
    <br>
    <br>
    <table border=1 width='100%'>
        <tr>
            <td></td>
            <td align='center'><b> total </b></td>
            <?php

            foreach($reg as $region){
                echo "<td align='center'><b>$region</b></b></td>";
            }

            foreach($tcabinet as $cab) {
                if(isset($regions[$cab])){
                    echo "<td align='center'><b>".$tville[$cab]."</b></td>";
                }
            }
            ?>
        </tr>

        <?php

        $max_pat=0;
        $plus3tot=0;
        $moins3tot=0;

        //Patients avec au moins un suivi
        $req="SELECT cabinet, id, numero, count(*) ".
            "FROM suivi_diabete, dossier ".
            "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND actif='oui' ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "GROUP BY cabinet, dossier_id ".
            "ORDER BY cabinet ";
        //echo $req;
        //die;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        while(list($cab, $id,$numero) = mysql_fetch_row($res)) {

            if(isset($regions[$cab])){


                //Nombre de HBA1c réalisés sur les 12 derniers mois
                $req="SELECT  count(*) ".
                    "FROM liste_exam ".
                    "WHERE  DATE_ADD(date_exam, ".
                    "INTERVAL 1 YEAR) >= CURDATE() and type_exam='HBA1c' ".
                    "and id='$id' ".
                    "GROUP BY id, date_exam";
                //echo $req;
                //die;
                $res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


                $pat=mysql_num_rows($res2);
//list($pat) = mysql_fetch_row($res2);

                if(!isset($t_diab[$cab][$pat]))
                {
                    $t_diab[$cab][$pat]=0;
                }
                $t_diab[$cab][$pat]=$t_diab[$cab][$pat]+1;

                if(!isset($total[$pat]))
                {
                    $total[$pat]=0;
                }
                $total[$pat]=$total[$pat]+1;

                if(!isset($t_diab[$regions[$cab]][$pat])){
                    $t_diab[$regions[$cab]][$pat]=0;
                }

                $t_diab[$regions[$cab]][$pat]=$t_diab[$regions[$cab]][$pat]+1;

                if($pat>$max_pat)
                {
                    $max_pat=$pat;
                }

                if($pat>=3)
                {
                    $plus3[$cab]=$plus3[$cab]+1;
                    $plus3tot++;

                    $plus3[$regions[$cab]]=$plus3[$regions[$cab]]+1;

                }
                else
                {
                    $moins3[$cab]=$moins3[$cab]+1;
                    $moins3tot++;
                    $moins3[$regions[$cab]]=$moins3[$regions[$cab]]+1;

                }

            }



        }








        ?>
        <tr>
            <td>+ de 3 HBA1c</td>
            <td align='right'><?php echo round(100*$plus3tot/($plus3tot+$moins3tot));?>%</td>

            <?php

            foreach($reg as $region){
                $taux=round(100*$plus3[$region]/($plus3[$region]+$moins3[$region]));
                echo "<td align='right'>$taux %</td>";
            }


            foreach($tcabinet as $cab) {
                if(isset($regions[$cab])){
                    if($moins3[$cab]!=0){
                        $taux=round(100*$plus3[$cab]/($plus3[$cab]+$moins3[$cab]));
                    }
                    elseif($plus3[$cab]==0){
                        $taux="ND";
                    }
                    else{
                        $taux="100";
                    }

                    echo "<td align='right'>$taux %</td>";
                }

            }
            ?>
        </tr>
        <?
        for($i=0;$i<=$max_pat; $i++)
        {
            ?>
            <tr>
                <td><?php echo $i;?> HBA1c réalisé<?php if ($i>1) echo "s";?> sur les 12 derniers mois</td>
                <td align='right'><?php if(isset($total[$i])) echo $total[$i];
                    else echo "0"; ?></td>

                <?php

                foreach($reg as $region){
                    if(isset($t_diab[$region][$i])){
                        echo "<td align='right'>".$t_diab[$region][$i]."</td>";
                    }
                    else{
                        echo "<td align='right'>0</td>";
                    }
                }

                foreach($tcabinet as $cab) {
                    if(isset($regions[$cab])){

                        ?>
                        <td align='right'><?php if(isset($t_diab[$cab][$i])) echo $t_diab[$cab][$i];
                            else echo "0"; ?></td>
                        <?php
                    }
                }
                ?>
            </tr>

            <?
        }
        ?>

        <tr>
            <td>Somme</td>
            <td align='right'><?php echo array_sum($total);?></td>

            <?php

            foreach($reg as $region){
                $somme=array_sum($t_diab[$region]);
                echo "<td align='right'>$somme</td>";
            }

            foreach($tcabinet as $cab) {
                if(isset($regions[$cab])){
                    $somme=array_sum($t_diab[$cab]);
                    echo "<td align='right'>$somme</td>";
                }
            }
            ?>
        </Tr>

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

}

//arrêtés trimestriels
function tableau($date){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $regions, $reg;

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

    foreach ($tcabinet as $cab)
    {
        $tpat[$cab]=0;
        $tcabinet_util[$cab]=0;
        $t_diab[$cab]=array();
        $plus3[$cab]=0;
        $moins3[$cab]=0;

    }

    foreach($regions as $region){
        $t_diab[$region]=array();
        $moins3[$region]=0;
        $plus3[$region]=0;
    }



    while(list($cab, $pat) = mysql_fetch_row($res)) {
//	 $tcabinet[] = $cab;

        if($cab!=$cab_prec)
        {
            $tcabinet_util[$cab]=1;
        }
    }



    ?>
    <table border=1 width='100%'>
        <tr>
            <td></td>
            <td align='center'><b> total </b></td>
            <?php

            foreach($reg as $region){
                echo "<td align='center'><b>$region</b></b></td>";
            }

            foreach($tcabinet as $cab) {
                if(isset($regions[$cab])){
                    echo "<td align='center'><b>".$tville[$cab]."</b></td>";
                }
            }


            echo "</tr>";

            $max_pat=0;
            $plus3tot=0;
            $moins3tot=0;
            //Patients avec au moins un suivi
            $req="SELECT cabinet, id, count(*) ".
                "FROM suivi_diabete, dossier ".
                "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
                "and dossier.cabinet!='sbirault' ".
                "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
                "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
                "AND suivi_diabete.dossier_id=dossier.id ".
                "GROUP BY cabinet, dossier_id ".
                "ORDER BY cabinet ";
            //echo $req;
            //die;
            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


            while(list($cab, $id) = mysql_fetch_row($res)) {

//Nombre de HBA1c réalisés sur les 12 derniers mois
                $req="SELECT count(*) ".
                    "FROM liste_exam ".
                    "WHERE  DATE_ADD(date_exam, ".
                    "INTERVAL 1 YEAR) >= '$date' and date_exam<='$date' ".
                    "AND id='$id' and type_exam='HBA1c' ".
                    "GROUP BY id, date_exam";
//echo $req;
//die;
                $res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

                if(isset($regions[$cab])){

                    $pat=mysql_num_rows($res2);
//list($pat) = mysql_fetch_row($res2);

                    if(!isset($t_diab[$cab][$pat]))
                    {
                        $t_diab[$cab][$pat]=0;
                    }
                    $t_diab[$cab][$pat]=$t_diab[$cab][$pat]+1;

                    if(!isset($total[$pat]))
                    {
                        $total[$pat]=0;
                    }
                    $total[$pat]=$total[$pat]+1;

                    if(!isset($t_diab[$regions[$cab]][$pat])){
                        $t_diab[$regions[$cab]][$pat]=0;
                    }

                    $t_diab[$regions[$cab]][$pat]=$t_diab[$regions[$cab]][$pat]+1;

                    if($pat>$max_pat)
                    {
                        $max_pat=$pat;
                    }

                    if($pat>=3)
                    {
                        $plus3[$cab]=$plus3[$cab]+1;
                        $plus3tot++;

                        $plus3[$regions[$cab]]=$plus3[$regions[$cab]]+1;

                    }
                    else
                    {
                        $moins3[$cab]=$moins3[$cab]+1;
                        $moins3tot++;
                        $moins3[$regions[$cab]]=$moins3[$regions[$cab]]+1;

                    }
                }
            }


            ?>
        <tr>
            <td>+ de 3 HBA1c</td>
            <td align='right'><?php echo round(100*$plus3tot/($plus3tot+$moins3tot));?>%</td>

            <?php

            foreach($reg as $region){
                $taux=round(100*$plus3[$region]/($plus3[$region]+$moins3[$region]));
                echo "<td align='right'>$taux %</td>";
            }


            foreach($tcabinet as $cab) {
                if(isset($regions[$cab])){
                    if($moins3[$cab]!=0){
                        $taux=round(100*$plus3[$cab]/($plus3[$cab]+$moins3[$cab]));
                    }
                    elseif($plus3[$cab]==0){
                        $taux="ND";
                    }
                    else{
                        $taux="100";
                    }

                    echo "<td align='right'>$taux %</td>";
                }

            }
            ?>
        </tr>
        <?
        for($i=0;$i<=$max_pat; $i++)
        {
            ?>
            <tr>
                <td><?php echo $i;?> HBA1c réalisé<?php if ($i>1) echo "s";?> sur les 12 derniers mois</td>
                <td align='right'><?php if(isset($total[$i])) echo $total[$i];
                    else echo "0"; ?></td>

                <?php

                foreach($reg as $region){
                    if(isset($t_diab[$region][$i])){
                        echo "<td align='right'>".$t_diab[$region][$i]."</td>";
                    }
                    else{
                        echo "<td align='right'>0</td>";
                    }
                }

                foreach($tcabinet as $cab) {
                    if(isset($regions[$cab])){

                        ?>
                        <td align='right'><?php if(isset($t_diab[$cab][$i])) echo $t_diab[$cab][$i];
                            else echo "0"; ?></td>
                        <?php
                    }
                }
                ?>
            </tr>

            <?
        }
        ?>

        <tr>
            <td>Somme</td>
            <td align='right'><?php echo array_sum($total);?></td>

            <?php

            foreach($reg as $region){
                $somme=array_sum($t_diab[$region]);
                echo "<td align='right'>$somme</td>";
            }

            foreach($tcabinet as $cab) {
                if(isset($regions[$cab])){
                    $somme=array_sum($t_diab[$cab]);
                    echo "<td align='right'>$somme</td>";
                }
            }
            ?>
        </Tr>

    </table>
    <br>
    <br>

    <?php

}

?>
</body>
</html>
