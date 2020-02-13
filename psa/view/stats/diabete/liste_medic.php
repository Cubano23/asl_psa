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
    <title>Taux de patients diabétiques disposant d'un médicament</title>
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

require("../global/entete.php");
//echo $loc;

entete_asalee("Taux de patients diabétiques disposant d'un médicament");

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


function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_diab;

    $req="SELECT cabinet, total_diab2, nom_cab ".
        "FROM account ".
        "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and cabinet!='jgomes' and dossier.cabinet!='sbirault' ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $potentielsaisi['tot']=0;
    $potentielsaisi['eval']=0;
    $potentielsaisi['eval2']=0;
    $potentielsaisi['eval3']=0;

    while(list($cab, $total_diab2, $ville) = mysql_fetch_row($res)) {
        $potentielsaisi[$cab]=$total_diab2;
        $t_diab[$cab]=$total_diab2;
        $tville[$cab]=$ville;
        $potentielsaisi['tot']=$potentielsaisi['tot']+$total_diab2;

        if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
            (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
        {
            $potentielsaisi['eval']=$potentielsaisi['eval']+$total_diab2;
        }
        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
            (strcasecmp($cab, "chizé")==0))
        {
            $potentielsaisi['eval2']=$potentielsaisi['eval2']+$total_diab2;
        }
        else
        {
            $potentielsaisi['eval3']=$potentielsaisi['eval3']+$total_diab2;
        }
    }


//Patients avec au moins un suivi
    $req="SELECT cabinet, count(*) ".
        "FROM suivi_diabete, dossier ".
        "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' ".
        "AND actif='oui' ".
        "AND suivi_diabete.dossier_id=dossier.id ".
        "GROUP BY cabinet, dossier_id ".
        "ORDER BY cabinet ";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    foreach ($t_diab as $cab =>$potentiel)
    {
        $nbsuivis[$cab]=0;
    }

    $nbsuivis['tot']=0;
    $nbsuivis['eval']=0;
    $nbsuivis['eval2']=0;
    $nbsuivis['eval3']=0;

    while(list($cab, $pat) = mysql_fetch_row($res)) {
        $nbsuivis[$cab] = $nbsuivis[$cab]+1;
        $nbsuivis['tot']=$nbsuivis['tot']+1;

        if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
            (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
        {
            $nbsuivis['eval']=$nbsuivis['eval']+1;
        }
        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
            (strcasecmp($cab, "chizé")==0))
        {
            $nbsuivis['eval2']=$nbsuivis['eval2']+1;
        }
        else
        {
            $nbsuivis['eval3']=$nbsuivis['eval3']+1;
        }
    }


    $t_diab['tot']=0;
    $t_diab['eval']=0;
    $t_diab['eval2']=0;
    $t_diab['eval3']=0;

    foreach($tville as $cab=>$ville){
        if($nbsuivis[$cab]>$potentielsaisi[$cab]){
            $t_diab[$cab]=$nbsuivis[$cab];
        }
        $t_diab['tot']=$t_diab['tot']+$t_diab[$cab];

        if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
            (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
        {
            $t_diab['eval']=$t_diab['eval']+$t_diab[$cab];
        }
        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
            (strcasecmp($cab, "chizé")==0))
        {
            $t_diab['eval2']=$t_diab['eval2']+$t_diab[$cab];
        }
        else
        {
            $t_diab['eval3']=$t_diab['eval3']+$t_diab[$cab];
        }
    }


    $req="SELECT cabinet, count(*) ".
        "FROM dossier ".
        "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' ".
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
        $tpat[$cab] = $pat;
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
            <?php
            foreach($tcabinet as $cab) {
                ?>
                <td align='center'><b><?php echo $tville[$cab]; ?></b></td>
                <?php
            }
            ?>
        </tr>

        <?php

        //taux diab 2 avec médicament
        $req="SELECT cabinet, dossier_id, ADO, InsulReq ".
            "FROM `suivi_diabete`, dossier ".
            "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' ".
            "AND actif='oui' ".
            "AND dossier_id=id ".
            "GROUP BY cabinet, dossier_id, dsuivi ".
            "ORDER BY cabinet, dossier_id, dsuivi ";
        //echo $req;
        //die;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['eval']=0;
        $tpat['eval2']=0;
        $tpat['eval3']=0;
        $id_prec="";

        while(list($cab, $dossier_id, $ADO, $InsulReq) = mysql_fetch_row($res)) {
            if($dossier_id!=$id_prec){
                if($id_prec==""){
                    $id_prec=$dossier_id;
                    $ADO_prec=$ADO;
                    $Insulprec=$InsulReq;
                    $cab_prec=$cab;
                }
                else{
                    if((($ADO_prec!="NULL")&&($ADO_prec!='')&&($ADO_prec!="aucun"))||($Insulprec=='1')){
                        echo "ADO : ".$ADO_prec.".. insul: $Insulprec<br>";
                        if(!isset($liste_ADO[$cab_prec][$ADO_prec])){
                            $liste_ADO[$cab_prec][$ADO_prec]=1;
                            if($Insulprec=='1'){
                                if(!isset($liste_ADO[$cab_prec]["insuline"])){
                                    $liste_ADO[$cab_prec]["insuline"]=1;
                                }
                                else{
                                    $liste_ADO[$cab_prec]["insuline"]=$liste_ADO[$cab_prec]["insuline"]+1;
                                }
                            }
                        }
                        else{
                            $liste_ADO[$cab_prec][$ADO_prec]=$liste_ADO[$cab_prec][$ADO_prec]+1;
                            if($Insulprec=='1'){
                                if(!isset($liste_ADO[$cab_prec]["insuline"])){
                                    $liste_ADO[$cab_prec]["insuline"]=1;
                                }
                                else{
                                    $liste_ADO[$cab_prec]["insuline"]=$liste_ADO[$cab_prec]["insuline"]+1;
                                }
                            }
                        }
                    }
                    $id_prec=$dossier_id;
                    $ADO_prec=$ADO;
                    $Insulprec=$InsulReq;
                    $cab_prec=$cab;
                }
            }
            else{
                $ADO_prec=$ADO;
                $Insulprec=$InsulReq;
            }
        }


        if((($ADO_prec!="NULL")&&($ADO_prec!='')&&($ADO_prec!="aucun"))||($Insulprec=='1')){
            if(!isset($liste_ADO[$cab_prec][$ADO_prec])){
                $liste_ADO[$cab_prec][$ADO_prec]=1;
            }
            else{
                $liste_ADO[$cab_prec][$ADO_prec]=$liste_ADO[$cab_prec][$ADO_prec]+1;
            }
        }

        ?>

        <tr>
            <td>Taux de patients diabétiques disposant d'un médicament par rapport au potentiel<sup>1</sup></td>
            <?php

            foreach($tcabinet as $cab) {


                ?>
                <td align='right'>
                    <table>
                        <tr>
                            <td>molécule</Td><td>nb</td>
                        <tr>

                            <?php
                            foreach($liste_ADO[$cab] as $molecule=>$nb){
                                echo "<tr><td>$molecule</td><td>$nb</Td></tr>";
                            }
                            ?>
                    </Table>
                </td>
                <?php
            }
            ?>
        </tr>



        <tr>
            <td>Taux de patients diabétiques disposant d'un médicament par au nb de dossiers<sup>2</sup></td>
            <td align='right'><?php echo round($tpat['tot']/$nbsuivis['tot']*100, 0); ?>%</td>
            <td align='right'><?php echo round($tpat['eval']/$nbsuivis['eval']*100, 0); ?>%</td>
            <td align='right'><?php echo round($tpat['eval2']/$nbsuivis['eval2']*100,0);?>%</td>
            <td align='right'><?php echo round($tpat['eval3']/$nbsuivis['eval3']*100, 0);?>%</td>
            <?php

            foreach($tcabinet as $cab) {
                if ($nbsuivis[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$nbsuivis[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo $taux; ?></td>
                <?php
            }
            ?>
        </tr>




        <tr>
            <td>Nombre de patients ayant eu au moins 1 suivi</td>
            <td align='right'><?php echo $nbsuivis['tot']; ?></td>
            <td align='right'><?php echo $nbsuivis['eval'];?></Td>
            <td align='right'><?php echo $nbsuivis['eval2'];?></td>
            <td align='right'><?php echo $nbsuivis['eval3'];?></td>
            <?php

            foreach($tcabinet as $cab) {

                ?>
                <td align='right'><?php echo $nbsuivis[$cab]; ?></td>
                <?php
            }
            ?>
        </tr>


        <tr>
            <td>Potentiel du cabinet</td>
            <td align='right'><?php echo $potentielsaisi['tot']; ?></td>
            <td align='right'><?php echo $potentielsaisi['eval'];?></td>
            <td align='right'><?php echo $potentielsaisi['eval2'];?></td>
            <td align='right'><?php echo $potentielsaisi['eval3'];?></td>
            <?php

            foreach($tcabinet as $cab) {

                ?>
                <td align='right'><?php echo $potentielsaisi[$cab]; ?></td>
                <?php
            }
            ?>
        </tr>


    </table>
    <br><br>
    <?php
    /*
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

    */
    ?>
    <sup>1</sup>Nombre de personnes ayant eu au moins un suivi du diabète et un médicament ou insuline lors du dernier suivi/potentiel du cabinet<br>
    <sup>2</sup>Nombre de personnes ayant eu au moins un suivi du diabète et un médicament ou insuline lors du dernier suivi/Nombre de dossiers ayant au moins 1 suivi
    <!--<sup>2</sup>Nombre de personnes ayant eu au moins un suivi du diabète et une évaluation infirmière/potentiel du cabinet-->
    <?php
}

function tableau($date){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_diab;

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

    $tab_date=split('-', $date);

    echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";

    foreach($tcabinet as $cab) {
        $t_diab[$cab]=0;
        $potentielsaisi[$cab]=0;
    }


    $req="SELECT cabinet, total_diab2 ".
        "FROM histo_account ".
        "WHERE cabinet!='zTest'  and cabinet!='irdes' AND cabinet!='ergo' AND cabinet!='jgomes' and dossier.cabinet!='sbirault' ".
        "AND dmaj<='$date 23:59:59' ".
        "ORDER BY cabinet, dmaj ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");



    while(list($cab, $total_diab2) = mysql_fetch_row($res)) {
        $t_diab[$cab]=$total_diab2;
        $potentielsaisi[$cab]=$total_diab2;
    }

//Patients avec au moins un suivi
    $req="SELECT cabinet, count(*) ".
        "FROM suivi_diabete, dossier ".
        "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' ".
        "AND ( ((dossier.actif='oui') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date')) ".
        "AND dossier.dcreat<='$date' ) ".
        "AND dsuivi<='$date' ".
        "AND suivi_diabete.dossier_id=dossier.id ".
        "GROUP BY cabinet, dossier_id ".
        "ORDER BY cabinet ";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    foreach ($tcabinet as $cab)
    {
        $nbsuivis[$cab]=0;
    }

    $nbsuivis['tot']=0;
    $nbsuivis['eval']=0;
    $nbsuivis['eval2']=0;
    $nbsuivis['eval3']=0;

    while(list($cab, $pat) = mysql_fetch_row($res)) {
        $nbsuivis[$cab] = $nbsuivis[$cab]+1;
        $nbsuivis['tot']=$nbsuivis['tot']+1;

        if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
            (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
        {
            $nbsuivis['eval']=$nbsuivis['eval']+1;
        }
        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
            (strcasecmp($cab, "chizé")==0))
        {
            $nbsuivis['eval2']=$nbsuivis['eval2']+1;
        }
        else
        {
            $nbsuivis['eval3']=$nbsuivis['eval3']+1;
        }
    }


    foreach($t_diab as $cab=>$potentiel){
        if(isset($nbsuivis[$cab])){
            if($potentiel<$nbsuivis[$cab]){
                $t_diab[$cab]=$nbsuivis[$cab];
            }
        }
    }

    $req="SELECT cabinet, count(*) ".
        "FROM dossier ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' ".
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
    $t_diab['eval2']=0;
    $t_diab['eval3']=0;
    $potentielsaisi['tot']=0;
    $potentielsaisi['eval']=0;
    $potentielsaisi['eval2']=0;
    $potentielsaisi['eval3']=0;
    $cab_prec="";

    foreach ($tcabinet as $cab)
    {
        $tpat[$cab]=0;
        $tcabinet_util[$cab]=0;

    }


    while(list($cab, $pat) = mysql_fetch_row($res)) {
//	 $tcabinet[] = $cab;

        if($cab!=$cab_prec)
        {
            $t_diab['tot']=$t_diab['tot']+$t_diab[$cab];
            $potentielsaisi['tot']=$potentielsaisi['tot']+$potentielsaisi[$cab];
            $cab_prec=$cab;
            $tcabinet_util[$cab]=$t_diab[$cab];

            if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
            {
                $t_diab['eval']=$t_diab['eval']+$t_diab[$cab];
                $potentielsaisi['eval']=$potentielsaisi['eval']+$potentielsaisi[$cab];
            }
            elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                (strcasecmp($cab, "chizé")==0))
            {
                $t_diab['eval2']=$t_diab['eval2']+$t_diab[$cab];
                $potentielsaisi['eval2']=$potentielsaisi['eval2']+$potentielsaisi[$cab];
            }
            else
            {
                $t_diab['eval3']=$t_diab['eval3']+$t_diab[$cab];
                $potentielsaisi['eval3']=$potentielsaisi['eval3']+$potentielsaisi[$cab];
            }
        }
    }



    ?>
    <table border=1 width='100%'>
        <tr>
            <td></td>
            <td align='center'><b> Moyenne </b></Td>
            <td align='center'><b>Moyenne eval</b></td>
            <td align='center'><b>Moyenne cab 2005</b></td>
            <td align='center'><b>Moyenne cab 2006</b></td>

            <?php
            foreach($tcabinet as $cab) {
                ?>
                <td align='center'><b><?php echo $tville[$cab]; ?></b></td>
                <?php
            }
            ?>
        </tr>

        <?php

        //taux diab 2 suivis dans asalée
        $req="SELECT cabinet, count(*) ".
            "FROM suivi_diabete, dossier ".
            "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "and ((dsuivi is not NULL and DATE_ADD(dsuivi, ".
            "INTERVAL 5 MONTH) >= '$date') and (dsuivi<='$date')) ".
            "GROUP BY cabinet, dossier_id ".
            "ORDER BY cabinet ";
        //echo $req;
        //die;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['eval']=0;
        $tpat['eval2']=0;
        $tpat['eval3']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;


            if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
            {
                $tpat['eval']=$tpat['eval']+1;
            }
            elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                (strcasecmp($cab, "chizé")==0))
            {
                $tpat['eval2']=$tpat['eval2']+1;
            }
            else
            {
                $tpat['eval3']=$tpat['eval3']+1;
            }
        }
        //print_r($t_diab);
        ?>

        <tr>
            <td>Taux de patients diabétiques suivis dans Asalée<sup>1</sup></td>
            <td align='right'><?php echo round($tpat['tot']/$t_diab['tot']*100, 0); ?>%</td>
            <td align='right'><?php echo round($tpat['eval']/$t_diab['eval']*100, 0);?>%</td>
            <td align='right'><?php if($t_diab['eval2']==0) echo "ND"; else echo round($tpat['eval2']/$t_diab['eval2']*100, 0);?>%</td>
            <td align='right'><?php if($t_diab['eval3']==0) echo "ND"; else echo round($tpat['eval3']/$t_diab['eval3']*100, 0);?>%</td>
            <?php

            foreach($tcabinet as $cab) {
                if ($tcabinet_util[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_diab[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo $taux; ?></td>
                <?php
            }
            ?>
        </tr>




        <?php

        ///taux de diabétiques 2 vus en consult : pas ok à priori

        $req="SELECT cabinet, count(*) ".
            "FROM suivi_diabete, dossier, evaluation_infirmier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'   and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND ( ((dossier.actif='oui') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date')) and dossier.dcreat<='$date') ".
            "AND suivi_diabete.dossier_id=evaluation_infirmier.id ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "AND evaluation_infirmier.date<='$date' ".
            "GROUP BY cabinet, dossier_id ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['eval']=0;
        $tpat['eval2']=0;
        $tpat['eval3']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;

            if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
            {
                $tpat['eval']=$tpat['eval']+1;
            }
            elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                (strcasecmp($cab, "chizé")==0))
            {
                $tpat['eval2']=$tpat['eval2']+1;
            }
            else
            {
                $tpat['eval3']=$tpat['eval3']+1;
            }
        }


        ?>

        <tr>
            <td>Taux de patients diabétiques type 2 vus en consultation<sup>2</sup></td>
            <td align='right'><?php echo round($tpat['tot']/$t_diab['tot']*100, 0); ?>%</td>
            <td align='right'><?php echo round($tpat['eval']/$t_diab['eval']*100, 0);?>%</td>
            <td align='right'><?php if($t_diab['eval2']==0) echo "ND"; else echo round($tpat['eval2']/$t_diab['eval2']*100, 0);?>%</td>
            <td align='right'><?php if($t_diab['eval3']==0) echo "ND"; else echo round($tpat['eval3']/$t_diab['eval3']*100, 0);?>%</td>
            <?php

            foreach($tcabinet as $cab) {
                if ($tcabinet_util[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_diab[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo $taux; ?></td>
                <?php
            }
            ?>
        </tr>









        <tr>
            <td>Nombre de patients ayant eu au moins 1 suivi</td>
            <td align='right'><?php echo $tpat['tot']; ?></td>
            <td align='right'><?php echo $tpat['eval'];?></td>
            <td align='right'><?php echo $tpat['eval2'];?></td>
            <td align='right'><?php echo $tpat['eval3'];?></td>
            <?php
            foreach($tcabinet as $cab) {

                ?>
                <td align='right'><?php
                    if ($tcabinet_util[$cab]==0)
                        echo "ND";
                    else
                    {
                        echo $nbsuivis[$cab];
                    }
                    ?>
                </td>
                <?php
            }
            ?>
        </tr>


        <tr>
            <td>Potentiel du cabinet</td>
            <td align='right'><?php echo $potentielsaisi['tot']; ?></td>
            <td align='right'><?php echo $potentielsaisi['eval'];?></Td>
            <td align='right'><?php echo $potentielsaisi['eval2'];?></td>
            <td align='right'><?php echo $potentielsaisi['eval3'];?></td>
            <?php

            foreach($tcabinet as $cab) {

                ?>
                <td align='right'><?php if ($tcabinet_util[$cab]==0)
                        echo "ND";
                    else
                    {
                        echo $potentielsaisi[$cab]; 									}
                    ?>
                </td>
                <?php
            }
            ?>

        </tr>




    </table>
    <br>
    <br>

    <?php

}

?>
</body>
</html>
