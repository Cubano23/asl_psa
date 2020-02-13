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

    $req="SELECT cabinet, nom_cab ".
        "FROM account ".
        "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and cabinet!='jgomes' and cabinet!='sbirault' ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $t_diab['tot']=0;

    while(list($cab, $ville) = mysql_fetch_row($res)) {
        $t_diab[$cab]=array();
        $tville[$cab]=$ville;
        $plus3[$cab]=0;
        $moins3[$cab]=0;
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
            <td align='center'><b> total </b></td><td><b>moyenne eval</b></td><td><b>moyenne cab 2005</b></td><td><b>moyenne cab 2006</b></td>
            <?php
            foreach($tcabinet as $cab) {
                ?>
                <td align='center'><b><?php echo $tville[$cab]; ?></b></td>
                <?php
            }
            ?>
        </tr>

        <?php

        $max_pat=0;
        $plus3tot=0;
        $moins3tot=0;
        $plus3eval=0;
        $moins3eval=0;
        $plus3eval2=0;
        $moins3eval2=0;
        $plus3eval3=0;
        $moins3eval3=0;

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
            /*

            $req="SELECT max(depistage_diabete.date) FROM suivi_diabete, depistage_diabete WHERE dossier_id='$id' and depistage_diabete.id='$id' GROUP BY dossier_id";
            $res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            $nb_commun=mysql_num_rows($res2);

            $dossier="";
            if($nb_commun>0){
                list($date_dep)=mysql_fetch_row($res2);

                list($annee2, $mois2, $jour2)=explode("-", $date_dep);

                $annee=date('Y');
                $mois=date('m');

                if($annee2==date('Y'){
                    $mois_test=$mois-6;

                    if($mois_test>=$mois2){
                        $dossier='ok';
                    }
                }
                else{
                    $mois_test=$mois-6;

                    if($mois_test<=0){
                        $mois_test=$mois_test+12;
                        $annee=$annee-1;
                    }

                    if($annee>$annee2){
                        $dossier="ok";
                    }
                    if(($annee==$annee2)&&($mois_test>=$mois2)){
                        $dossier="ok";
                    }
                }
            }

            if(($nb_commun==0)||($dossier=="ok"))*/
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
                        if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                            (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                        {
                            $total_eval[0]=1;
                        }
                        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                            (strcasecmp($cab, "chizé")==0))
                        {
                            $total_eval2[0]=1;
                        }
                        elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                            (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                            (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                            (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                        {
                            $total_eval3[0]=1;
                        }

                    }
                    else{
                        $total[0]=$total[0]+1;
                        if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                            (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                        {
                            if(!isset($total_eval[0])){
                                $total_eval[0]=1;
                            }
                            else
                                $total_eval[0]=$total_eval[0]+1;
                        }
                        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                            (strcasecmp($cab, "chizé")==0))
                        {
                            if(!isset($total_eval2[0])){
                                $total_eval2[0]=1;
                            }
                            else
                                $total_eval2[0]=$total_eval2[0]+1;
                        }
                        elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                            (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                            (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                            (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                        {
                            if(!isset($total_eval3[0])){
                                $total_eval3[0]=1;
                            }
                            else
                                $total_eval3[0]=$total_eval3[0]+1;
                        }
                    }

                    $moins3[$cab]=$moins3[$cab]+1;
                    $moins3tot++;

                    if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                        (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                    {
                        $moins3eval++;
                    }
                    elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                        (strcasecmp($cab, "chizé")==0))
                    {
                        $moins3eval2++;
                    }
                    elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                        (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                        (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                        (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                    {
                        $moins3eval3++;
                    }
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
                        if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                            (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                        {
                            $total_eval[$pat]=1;
                        }
                        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                            (strcasecmp($cab, "chizé")==0))
                        {
                            $total_eval2[$pat]=1;
                        }
                        elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                            (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                            (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                            (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                        {
                            $total_eval3[$pat]=1;
                        }
                    }
                    else{
                        $total[$pat]=$total[$pat]+1;
                        if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                            (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                        {
                            if(!isset($total_eval[$pat])){
                                $total_eval[$pat]=1;
                            }
                            else
                                $total_eval[$pat]=$total_eval[$pat]+1;
                        }
                        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                            (strcasecmp($cab, "chizé")==0))
                        {
                            if(!isset($total_eval2[$pat])){
                                $total_eval2[$pat]=1;
                            }
                            else
                                $total_eval2[$pat]=$total_eval2[$pat]+1;
                        }
                        elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                            (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                            (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                            (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                        {
                            if(!isset($total_eval3[$pat])){
                                $total_eval3[$pat]=1;
                            }
                            else
                                $total_eval3[$pat]=$total_eval3[$pat]+1;
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
                        if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                            (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                        {
                            $plus3eval++;
                        }
                        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                            (strcasecmp($cab, "chizé")==0))
                        {
                            $plus3eval2++;
                        }
                        elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                            (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                            (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                            (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                        {
                            $plus3eval3++;
                        }
                    }
                    else
                    {
                        $moins3[$cab]=$moins3[$cab]+1;
                        $moins3tot++;
                        if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                            (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                        {
                            $moins3eval++;
                        }
                        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                            (strcasecmp($cab, "chizé")==0))
                        {
                            $moins3eval2++;
                        }
                        elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                            (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                            (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                            (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                        {
                            $moins3eval3++;
                        }
                    }

                }



            }


        }





        ?>
        <tr>
            <td>+ de 3 HBA1c</td>
            <td align='right'><?php echo round(100*$plus3tot/($plus3tot+$moins3tot));?>%</td>
            <td align='right'><?php echo round(100*$plus3eval/($plus3eval+$moins3eval));?>%</td>
            <td align='right'><?php echo round(100*$plus3eval2/($plus3eval2+$moins3eval2));?>%</td>
            <td align='right'><?php echo round(100*$plus3eval3/($plus3eval3+$moins3eval3));?>%</td>

            <?
            foreach($tcabinet as $cab) {


                ?>
                <td align='right'><?php if($moins3[$cab]!=0) echo round(100*$plus3[$cab]/($plus3[$cab]+$moins3[$cab]));
                    else echo "100";?>%</td>
                <?php
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
                <td align='right'><?php if(isset($total_eval[$i])) echo $total_eval[$i];
                    else echo "0"; ?></td>
                <td align='right'><?php if(isset($total_eval2[$i])) echo $total_eval2[$i];
                    else echo "0"; ?></td>
                <td align='right'><?php if(isset($total_eval3[$i])) echo $total_eval3[$i];
                    else echo "0"; ?></td>
                <?php

                foreach($tcabinet as $cab) {


                    ?>
                    <td align='right'><?php if(isset($t_diab[$cab][$i])) echo $t_diab[$cab][$i];
                        else echo "0"; ?></td>
                    <?php
                }
                ?>
            </tr>

            <?
        }
        ?>

        <tr>
            <td>Somme</td>
            <td align='right'><?php echo array_sum($total);?></td>
            <td align='right'><?php echo array_sum($total_eval);?></td>
            <td align='right'><?php echo array_sum($total_eval2);?></td>
            <td align='right'><?php echo array_sum($total_eval3);?></td>
            <?php

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
            <td align="center"><b>moyenne eval</b></Td>
            <td align="center"><b>moyenne cab 2005</b></Td>
            <td align="center"><b>moyenne cab 2006</b></Td>
            <?php
            foreach($tcabinet as $cab) {
                ?>
                <td align='center'><b><?php echo $tville[$cab]; ?></b></td>
                <?php
            }
            ?>
        </tr>

        <?php



        $plus3tot=0;
        $moins3tot=0;
        $plus3eval=0;
        $moins3eval=0;
        $plus3eval2=0;
        $moins3eval2=0;
        $plus3eval3=0;
        $moins3eval3=0;
        $max_pat=0;

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
                    if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                        (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                    {
                        $total_eval[0]=1;
                    }
                    elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                        (strcasecmp($cab, "chizé")==0))
                    {
                        $total_eval2[0]=1;
                    }
                    elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                        (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                        (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                        (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                    {
                        $total_eval3[0]=1;
                    }
                }
                else{
                    $total[0]=$total[0]+1;
                    if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                        (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                    {
                        if(!isset($total_eval[0])){
                            $total_eval[0]=1;
                        }
                        else
                            $total_eval[0]=$total_eval[0]+1;
                    }
                    elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                        (strcasecmp($cab, "chizé")==0))
                    {
                        if(!isset($total_eval2[0])){
                            $total_eval2[0]=1;
                        }
                        else
                            $total_eval2[0]=$total_eval2[0]+1;
                    }
                    elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                        (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                        (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                        (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                    {
                        if(!isset($total_eval3[0])){
                            $total_eval3[0]=1;
                        }
                        else
                            $total_eval3[0]=$total_eval3[0]+1;
                    }
                }

                $moins3[$cab]=$moins3[$cab]+1;
                $moins3tot++;
                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $moins3eval++;
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $moins3eval2++;
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $moins3eval3++;
                }
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
                    if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                        (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                    {
                        $total_eval[$pat]=1;
                    }
                    elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                        (strcasecmp($cab, "chizé")==0))
                    {
                        $total_eval2[$pat]=1;
                    }
                    elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                        (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                        (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                        (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                    {
                        $total_eval3[$pat]=1;
                    }
                }
                else{
                    $total[$pat]=$total[$pat]+1;
                    if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                        (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                    {
                        if(!isset($total_eval[$pat])){
                            $total_eval[$pat]=1;
                        }
                        else
                            $total_eval[$pat]=$total_eval[$pat]+1;
                    }
                    elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                        (strcasecmp($cab, "chizé")==0))
                    {
                        if(!isset($total_eval2[$pat])){
                            $total_eval2[$pat]=1;
                        }
                        else
                            $total_eval2[$pat]=$total_eval2[$pat]+1;
                    }
                    elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                        (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                        (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                        (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                    {
                        if(!isset($total_eval3[$pat])){
                            $total_eval3[$pat]=1;
                        }
                        else
                            $total_eval3[$pat]=$total_eval3[$pat]+1;
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
                    if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                        (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                    {
                        $plus3eval++;
                    }
                    elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                        (strcasecmp($cab, "chizé")==0))
                    {
                        $plus3eval2++;
                    }
                    elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                        (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                        (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                        (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                    {
                        $plus3eval3++;
                    }
                }
                else
                {
                    $moins3[$cab]=$moins3[$cab]+1;
                    $moins3tot++;
                    if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                        (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                    {
                        $moins3eval++;
                    }
                    elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                        (strcasecmp($cab, "chizé")==0))
                    {
                        $moins3eval2++;
                    }
                    elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                        (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                        (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                        (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                    {
                        $moins3eval3++;
                    }
                }
            }
        }


        ?>
        <tr>
            <td>+ de 3 HBA1c</td>
            <td align='right'><?php echo round(100*$plus3tot/($plus3tot+$moins3tot));?>%</td>
            <td align='right'><?php echo round(100*$plus3eval/($plus3eval+$moins3eval));?>%</td>
            <td align='right'><?php if(($plus3eval2+$moins3eval2)==0) echo "ND"; else echo round(100*$plus3eval2/($plus3eval2+$moins3eval2));?>%</td>
            <td align='right'><?php if(($plus3eval3+$moins3eval3)==0) echo "ND"; else echo round(100*$plus3eval3/($plus3eval3+$moins3eval3));?>%</td>

            <?
            foreach($tcabinet as $cab) {


                ?>
                <td align='right'><?php if($tcabinet_util[$cab]==0) echo 'ND';
                    else{
                        if($moins3[$cab]!=0) echo round(100*$plus3[$cab]/($plus3[$cab]+$moins3[$cab]));
                        else echo "100";?>%
                        <?
                    }
                    ?></td>
                <?php
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
                <td align='right'><?php if(isset($total_eval[$i])) echo $total_eval[$i];
                    else echo "0"; ?></td>
                <td align='right'><?php if(isset($total_eval2[$i])) echo $total_eval2[$i];
                    else echo "0"; ?></td>
                <td align='right'><?php if(isset($total_eval3[$i])) echo $total_eval3[$i];
                    else echo "0"; ?></td>
                <?php

                foreach($tcabinet as $cab) {


                    ?>
                    <td align='right'><?php if($tcabinet_util[$cab]==0) echo "ND";
                        else {
                            if(isset($t_diab[$cab][$i])) echo $t_diab[$cab][$i];
                            else echo "0";
                        }
                        ?></td>
                    <?php
                }
                ?>
            </tr>

            <?
        }
        ?>


        <tr>
            <td>Somme</td>
            <td align='right'><?php echo array_sum($total);?></td>
            <td align='right'><?php echo array_sum($total_eval);?></td>
            <td align='right'><?php if(isset($total_eval2)) echo array_sum($total_eval2); else echo "0";?></td>
            <td align='right'><?php if(isset($total_eval3)) echo array_sum($total_eval3); else echo "0";?></td>
            <?php

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
