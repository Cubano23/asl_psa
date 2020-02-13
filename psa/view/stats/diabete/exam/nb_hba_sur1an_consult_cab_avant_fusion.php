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
    <title>Nombre d'examens du HBA1c réalisés lors des 12 derniers mois - patients avec consultation - cabinets actifs</title>
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

entete_asalee("Nombre de HBA1c réalisés lors des 12 derniers mois - patients avec consultation - cabinets actifs");

//echo $loc;
?>
<!--
<table cellpadding="2" cellspacing="2" border="0"
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
<font face='times new roman'>Indicateurs d'évaluation Asalée taux de suivi des diabétiques</font></i>";
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
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville;


    $req="SELECT dossier.cabinet, count(*), nom_cab, region ".
        "FROM dossier, account ".
        "WHERE region!='' ".
        "AND actif='oui' ".
        "and dossier.cabinet=account.cabinet ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet, numero ";
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
        $t_diab[$cab]=array();
        $plus3[$cab]=0;
        $moins3[$cab]=0;
//	 $tpat[$cab] = $pat;
    }

    $t_diab['tot']=0;

    foreach($tville as $cab=>$ville){
        $tcabinet_util[$cab]=0;
    }

    $date3mois=date("Y-m-d", mktime(1, 1, 1, date("m")-3, date('d'), date("Y")));
    $req="SELECT cabinet from evaluation_infirmier, dossier where ".
        "dossier.id=evaluation_infirmier.id and date>='$date3mois' group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        if(isset($tville[$cab])){
            $tcabinet_util[$cab]=1;
        }
    }
    $req="SELECT cabinet from suivi_diabete, dossier where ".
        "dossier.id=dossier_id and dsuivi>='$date3mois' ".
        "group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        if(isset($tville[$cab])){
            $tcabinet_util[$cab]=1;
        }
    }



    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');

    echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";


    ?>
    <br>
    <br>
    <table border=1 width='100%'>
        <tr><td></td>
            <td align="center"><b>Moyenne</b></td>

            <?php

            foreach($liste_reg as $reg){
                echo "<td align='center'><b>moyenne $reg</b></td>";
                $plus3eval[$reg]=0;
                $moins3eval[$reg]=0;
            }

            foreach ($tcabinet as $cab)
            {
                ?>
                <td align="center"><b><?php echo $tville[$cab];?></b></td>
                <?php

            }
            ?>
        </tr>

        <?php

        $max_pat=0;
        $plus3tot=0;
        $moins3tot=0;
        /*$plus3eval2=0;
        $moins3eval2=0;
        $plus3eval3=0;
        $moins3eval3=0;
        */
        //Patients avec au moins un suivi
        $req="SELECT cabinet, dossier.id, numero, count(*) ".
            "FROM suivi_diabete, dossier, evaluation_infirmier ".
            "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND actif='oui' ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "and dossier.id=evaluation_infirmier.id ".
            "GROUP BY cabinet, dossier_id ".
            "ORDER BY cabinet ";
        //echo $req;
        //die;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        while(list($cab, $id,$numero) = mysql_fetch_row($res)) {
            if((isset($tcabinet_util[$cab]))&&($tcabinet_util[$cab]==1))
            {

//Nombre de HBA1c réalisés sur les 12 derniers mois
                $req="SELECT  count(*) ".
                    "FROM suivi_diabete, dossier ".
                    "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
                    "and dossier.cabinet!='sbirault' ".
                    "AND actif='oui' ".
                    "AND suivi_diabete.dossier_id=dossier.id ".
                    "and dHBA is not NULL and DATE_ADD(dHBA, ".
                    "INTERVAL 1 YEAR) >= CURDATE() ".
                    "and id='$id' ".
                    "GROUP BY id, dHBA";
//echo $req;
//die;
                $res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");



                if(mysql_num_rows($res2)==0)
                {
                    if(!isset($t_diab[$cab][0])){
                        $t_diab[$cab][0]=1;
                    }
                    else{
                        $t_diab[$cab][0]=$t_diab[$cab][0]+1;
                    }

                    if(!isset($total[0]))
                    {
                        $total[0]=1;
                        $total_eval[$regions[$cab]][0]=1;

                    }
                    else{
                        $total[0]=$total[0]+1;
                        if(!isset($total_eval[$regions[$cab]][0])){
                            $total_eval[$regions[$cab]][0]=1;
                        }
                        else
                            $total_eval[$regions[$cab]][0]=$total_eval[$regions[$cab]][0]+1;

                    }

                    $moins3[$cab]=$moins3[$cab]+1;
                    $moins3tot++;

                    $moins3eval[$regions[$cab]]=$moins3eval[$regions[$cab]]+1;
                }
                else
                {
                    $pat=mysql_num_rows($res2);
//list($pat) = mysql_fetch_row($res2);

                    if(!isset($t_diab[$cab][$pat]))
                    {
                        $t_diab[$cab][$pat]=1;
                    }
                    else
                    {
                        $t_diab[$cab][$pat]=$t_diab[$cab][$pat]+1;
                    }

                    if(!isset($total[$pat]))
                    {
                        $total[$pat]=1;
                        $total_eval[$regions[$cab]][$pat]=1;

                    }
                    else{
                        $total[$pat]=$total[$pat]+1;
                        if(!isset($total_eval[$regions[$cab]][$pat])){
                            $total_eval[$regions[$cab]][$pat]=1;
                        }
                        else
                            $total_eval[$regions[$cab]][$pat]=$total_eval[$regions[$cab]][$pat]+1;

                    }

                    if($pat>$max_pat)
                    {
                        $max_pat=$pat;
                    }

                    if($pat>=3)
                    {
                        $plus3[$cab]=$plus3[$cab]+1;
                        $plus3tot++;
                        $plus3eval[$regions[$cab]]++;

                    }
                    else
                    {
                        $moins3[$cab]=$moins3[$cab]+1;
                        $moins3tot++;
                        $moins3eval[$regions[$cab]]++;

                    }

                }



            }


        }


        ?>
        <tr>
            <td>+ de 3 HBA1c</td>
            <td align='right'><?php echo round(100*$plus3tot/($plus3tot+$moins3tot));?>%</td>
            <?php

            foreach($liste_reg as $reg){
                if(($plus3eval[$reg]==0)&&($moins3eval[$reg]==0)){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round(100*$plus3eval[$reg]/($plus3eval[$reg]+$moins3eval[$reg]))."%</td>";
                }
            }


            foreach($tcabinet as $cab) {
                if(($plus3[$cab]==0)&&($moins3[$cab]==0)){
                    echo "<td align='right'>ND</td>";
                }
                else{

                    ?>
                    <td align='right'><?php if($moins3[$cab]!=0) echo round(100*$plus3[$cab]/($plus3[$cab]+$moins3[$cab]));
                        else echo "100";?>%</td>
                    <?php
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
                foreach($liste_reg as $reg){
                    echo "<td align='right'>";
                    if(isset($total_eval[$reg][$i])) echo $total_eval[$reg][$i];
                    else echo "0";
                    echo "</td>";
                }


                foreach($tcabinet as $cab) {

                    echo "<td align='right'>";
                    if(isset($t_diab[$cab][$i])) echo $t_diab[$cab][$i];
                    else echo "0";

                    echo "</td>";
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
            foreach($liste_reg as $reg){
                if(!isset($total_eval[$reg])){
                    echo "<td align='right'>0</td>";
                }
                else{
                    echo "<td align='right'>".array_sum($total_eval[$reg])."</td>";
                }
            }

            foreach($tcabinet as $cab) {


                ?>
                <td align='right'><?php echo array_sum($t_diab[$cab]); ?></td>
                <?php
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

function tableau($date){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville;

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

    $tab_date=split('-', $date);

    echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";


    $req="SELECT dossier.cabinet, count(*), nom_cab, region  ".
        "FROM dossier, account ".
        "WHERE region!='' ".
        "and dossier.cabinet=account.cabinet ".
        "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
        "GROUP BY dossier.cabinet ".
        "ORDER BY dossier.cabinet, numero ";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if (mysql_num_rows($res)==0) {
        exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }
//$tcabinet=array();
    $tcabinet=array();

    $liste_reg=array();

    while(list($cab, $pat, $ville, $region) = mysql_fetch_row($res)) {
        $tcabinet[] = $cab;
        $tville[$cab]=$ville;
        $regions[$cab]=$region;

        if(!in_array($region, $liste_reg)){
            $liste_reg[]=$region;
        }
        $t_diab[$cab]=array();
        $plus3[$cab]=0;
        $moins3[$cab]=0;
//	 $tpat[$cab] = $pat;
    }

    $t_diab['tot']=0;




    foreach($tville as $cab=>$ville){
        $tcabinet_util[$cab]=0;
    }

    $date3mois=date("Y-m-d", mktime(1, 1, 1, $tab_date[1]-3, $tab_date[2], $tab_date[0]));
    $req="SELECT cabinet from evaluation_infirmier, dossier where ".
        "dossier.id=evaluation_infirmier.id and date>='$date3mois' group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        if(isset($tville[$cab])){
            $tcabinet_util[$cab]=1;
        }
    }
    $req="SELECT cabinet from suivi_diabete, dossier where ".
        "dossier.id=dossier_id and dsuivi>='$date3mois' ".
        "group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        if(isset($tville[$cab])){
            $tcabinet_util[$cab]=1;
        }
    }



    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');


    ?>
    <br>
    <br>
    <table border=1 width='100%'>
        <tr><td></td>
            <td align="center"><b>Moyenne</b></td>

            <?php

            foreach($liste_reg as $reg){
                echo "<td align='center'><b>moyenne $reg</b></td>";
                $plus3eval[$reg]=0;
                $moins3eval[$reg]=0;
            }

            foreach ($tcabinet as $cab)
            {
                ?>
                <td align="center"><b><?php echo $tville[$cab];?></b></td>
                <?php

            }
            ?>
        </tr>


        <?php



        $plus3tot=0;
        $moins3tot=0;
        $max_pat=0;

        //Patients avec au moins un suivi et une éval
        $req="SELECT cabinet, dossier.id, count(*) ".
            "FROM suivi_diabete, dossier, evaluation_infirmier ".
            "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "and evaluation_infirmier.id=dossier.id ".
            "and evaluation_infirmier.date<='$date' ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "GROUP BY cabinet, dossier_id ".
            "ORDER BY cabinet ";
        //echo $req;
        //die;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        while(list($cab, $id) = mysql_fetch_row($res)) {
            if((isset($tcabinet_util[$cab]))&&($tcabinet_util[$cab]==1)){
//Nombre de HBA1c réalisés sur les 12 derniers mois
                $req="SELECT count(*) ".
                    "FROM suivi_diabete, dossier ".
                    "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
                    "and dossier.cabinet!='sbirault' ".
                    "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
                    "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
                    "AND suivi_diabete.dossier_id=dossier.id ".
                    "and dHBA is not NULL and DATE_ADD(dHBA, ".
                    "INTERVAL 1 YEAR) >= '$date' and dHBA<='$date' ".
                    "AND id='$id' ".
                    "GROUP BY cabinet, dossier_id, dHBA ".
                    "ORDER BY cabinet ";
//echo $req;
//die;
                $res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


                if(mysql_num_rows($res2)==0)
                {
                    if(!isset($t_diab[$cab][0])){
                        $t_diab[$cab][0]=1;
                    }
                    else{
                        $t_diab[$cab][0]=$t_diab[$cab][0]+1;
                    }

                    if(!isset($total[0]))
                    {
                        $total[0]=1;
                        $total_eval[$regions[$cab]][0]=1;
                    }
                    else{
                        $total[0]=$total[0]+1;
                        if(!isset($total_eval[$regions[$cab]][0])){
                            $total_eval[$regions[$cab]][0]=1;
                        }
                        else{
                            $total_eval[$regions[$cab]][0]=$total_eval[$regions[$cab]][0]+1;
                        }
                    }

                    $moins3[$cab]=$moins3[$cab]+1;
                    $moins3tot++;

                    $moins3eval[$regions[$cab]]++;

                }
                else
                {
//	list($pat) = mysql_fetch_row($res2);
                    $pat=mysql_num_rows($res2);

                    if(!isset($t_diab[$cab][$pat]))
                    {
                        $t_diab[$cab][$pat]=1;
                    }
                    else
                    {
                        $t_diab[$cab][$pat]=$t_diab[$cab][$pat]+1;
                    }

                    if(!isset($total[$pat]))
                    {
                        $total[$pat]=1;

                        $total_eval[$regions[$cab]][$pat]=1;

                    }
                    else{
                        $total[$pat]=$total[$pat]+1;
                        if(!isset($total_eval[$regions[$cab]][$pat])){
                            $total_eval[$regions[$cab]][$pat]=1;
                        }
                        else{
                            $total_eval[$regions[$cab]][$pat]=$total_eval[$regions[$cab]][$pat]+1;
                        }

                    }

                    if($pat>$max_pat)
                    {
                        $max_pat=$pat;
                    }

                    if($pat>=3)
                    {
                        $plus3[$cab]=$plus3[$cab]+1;
                        $plus3tot++;
                        $plus3eval[$regions[$cab]]=$plus3eval[$regions[$cab]]+1;

                    }
                    else
                    {
                        $moins3[$cab]=$moins3[$cab]+1;
                        $moins3tot++;
                        $moins3eval[$regions[$cab]]=$moins3eval[$regions[$cab]]+1;

                    }
                }
            }
        }


        ?>
        <tr>
            <td>+ de 3 HBA1c</td>
            <td align='right'><?php echo round(100*$plus3tot/($plus3tot+$moins3tot));?>%</td>
            <?php

            foreach($liste_reg as $reg){
                if(($plus3eval[$reg]==0)&&($moins3eval[$reg]==0)){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round(100*$plus3eval[$reg]/($plus3eval[$reg]+$moins3eval[$reg]))."%</td>";
                }
            }


            foreach($tcabinet as $cab) {
                if(($plus3[$cab]==0)&&($moins3[$cab]==0)){
                    echo "<td align='right'>ND</td>";
                }
                else{

                    ?>
                    <td align='right'><?php if($moins3[$cab]!=0) echo round(100*$plus3[$cab]/($plus3[$cab]+$moins3[$cab]));
                        else echo "100";?>%</td>
                    <?php
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
                foreach($liste_reg as $reg){
                    echo "<td align='right'>";
                    if(isset($total_eval[$reg][$i])) echo $total_eval[$reg][$i];
                    else echo "0";
                    echo "</td>";
                }


                foreach($tcabinet as $cab) {

                    echo "<td align='right'>";
                    if(isset($t_diab[$cab][$i])) echo $t_diab[$cab][$i];
                    else echo "0";

                    echo "</td>";
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
            foreach($liste_reg as $reg){
                if(!isset($total_eval[$reg])){
                    echo "<td align='right'>0</td>";
                }
                else{
                    echo "<td align='right'>".array_sum($total_eval[$reg])."</td>";
                }
            }

            foreach($tcabinet as $cab) {


                ?>
                <td align='right'><?php echo array_sum($t_diab[$cab]); ?></td>
                <?php
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
