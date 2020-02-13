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
    <title>Taux de diabète équilibré - patients avec consultation - cabinets actifs</title>
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

entete_asalee("Taux de diabète équilibré - patients avec consultation - cabinets actifs");

//echo $loc;
?>

<br><br>
<?php

# boucle principale
do {
    $repete=false;

    # étape 1 : affichage tableaux
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {
            //affichage tableuax
            case 1:
                etape_1($repete);
                break;

        }
    }
} while($repete);

# fin de traitement principal

//affichag tableau à la date du jour
function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $regions, $liste_reg;


    /*print_r($t_tot);echo "<br>";
    print_r($t_sein);echo "<br>";
    print_r($t_cogni);echo "<br>";
    print_r($t_colon);echo "<br>";
    print_r($t_uterus);echo "<br>";
    print_r($t_diab);echo "<br>";
    */

    $req="SELECT dossier.cabinet, count(*), nom_cab, region ".
        "FROM dossier, account ".
        "WHERE infirmiere!='' and region!='' ".
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


    sort($liste_reg);
    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');

    echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

    ?>
    <br>
    <br>
    <table border=1 width='100%'>

        <?php

        //taux diab 2 suivis dans asalée
        $req="SELECT cabinet, dossier.id, dsuivi, resultat1 ".
            "FROM suivi_diabete, dossier, evaluation_infirmier, liste_exam ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and dossier.cabinet!='ergo'  ".
            "and dossier.cabinet!='sbirault' and dossier.cabinet!='jgomes' ".
            "AND actif='oui' ".
            "AND suivi_diabete.dossier_id=dossier.id and ".
            "AND dossier.id=liste_exam.id and type_exam='HBA1c' ".
            "dossier.id=evaluation_infirmier.id ".
            "group by cabinet, dossier.id, date_exam ".
            "ORDER BY cabinet, dossier.id, date_exam ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        foreach ($tcabinet as $cab)
        {
            $tpat[$cab][0]=0;//pas de hba
            $tpat[$cab][1]=0;//hba<6.5
            $tpat[$cab][2]=0;//6.5<hba<8
            $tpat[$cab][3]=0;//>8
            $total[$cab]=0;
            $tpat[$cab][4]=0; // hba <7
        }

        foreach($regions as $reg){
            $tpat[$reg][0]=0;
            $tpat[$reg][1]=0;
            $tpat[$reg][2]=0;
            $tpat[$reg][3]=0;
            $tpat[$reg][4]=0;

            $total[$reg]=0;
        }
        $tpat['tot'][0]=0;
        $tpat['tot'][1]=0;
        $tpat['tot'][2]=0;
        $tpat['tot'][3]=0;
        $tpat['tot'][4]=0;

        $total["tot"]=0;

        $id_prec='';
        $cab_prec="";
        $i=0;
        while(list($cab, $id, $dsuivi, $ResHBA) = mysql_fetch_row($res)) {
            if((isset($tcabinet_util[$cab_prec]))&&($tcabinet_util[$cab_prec]==1)){
                $i++;
                if($id_prec!=$id)
                {

                    if($id_prec!='')
                    {

                        if(($hba_prec<=6.5)&&($hba_prec>0))
                        {
                            $tpat['tot'][1]=$tpat['tot'][1]+1;
                            $tpat[$cab_prec][1]=$tpat[$cab_prec][1]+1;

                            $tpat[$regions[$cab_prec]][1]=$tpat[$regions[$cab_prec]][1]+1;
                        }
                        elseif(($hba_prec>6.5)&&($hba_prec<8))
                        {
                            $tpat['tot'][2]=$tpat['tot'][2]+1;
                            $tpat[$cab_prec][2]=$tpat[$cab_prec][2]+1;

                            $tpat[$regions[$cab_prec]][2]=$tpat[$regions[$cab_prec]][2]+1;
                        }
                        elseif($hba_prec>=8)
                        {
                            $tpat['tot'][3]=$tpat['tot'][3]+1;
                            $tpat[$cab_prec][3]=$tpat[$cab_prec][3]+1;

                            $tpat[$regions[$cab_prec]][3]=$tpat[$regions[$cab_prec]][3]+1;
                        }
                        else //pas de mesure
                        {
                            $tpat['tot'][0]=$tpat['tot'][0]+1;
                            $tpat[$cab_prec][0]=$tpat[$cab_prec][0]+1;

                            $tpat[$regions[$cab_prec]][0]=$tpat[$regions[$cab_prec]][0]+1;
                        }

                        if(($hba_prec<=7)&&($hba_prec>0))//<7
                        {
                            $tpat['tot'][4]=$tpat['tot'][4]+1;
                            $tpat[$cab_prec][4]=$tpat[$cab_prec][4]+1;

                            $tpat[$regions[$cab_prec]][4]=$tpat[$regions[$cab_prec]][4]+1;
                        }

                        $total['tot']=$total['tot']+1;

                        $total[$regions[$cab_prec]]=$total[$regions[$cab_prec]]+1;

                        $total[$cab_prec]=$total[$cab_prec]+1;
                        $cab_prec=$cab;
                        $hba_prec=$ResHBA;
                        $id_prec=$id;
                    }
                    else
                    {
                        $id_prec=$id;
                        $hba_prec=$ResHBA;
                        $cab_prec=$cab;

                    }

                }
                else
                {
                    if(($ResHBA!=0)&&($ResHBA!='NULL'))
                    {
                        $hba_prec=$ResHBA;
                    }
                }
            }
            else
            {
                $id_prec=$id;
                $hba_prec=$ResHBA;
                $cab_prec=$cab;

            }
        }

        if((isset($tcabinet_util[$cab_prec]))&&($tcabinet_util[$cab_prec]==1)){
            if(($hba_prec<=6.5)&&($hba_prec>0))
            {
                $tpat['tot'][1]=$tpat['tot'][1]+1;
                $tpat[$cab_prec][1]=$tpat[$cab_prec][1]+1;

                $tpat[$regions[$cab_prec]][1]=$tpat[$regions[$cab_prec]][1]+1;
            }
            elseif(($hba_prec>6.5)&&($hba_prec<8))
            {
                $tpat['tot'][2]=$tpat['tot'][2]+1;
                $tpat[$cab_prec][2]=$tpat[$cab_prec][2]+1;

                $tpat[$regions[$cab_prec]][2]=$tpat[$regions[$cab_prec]][2]+1;
            }
            elseif($hba_prec>=8)
            {
                $tpat['tot'][3]=$tpat['tot'][3]+1;
                $tpat[$cab_prec][3]=$tpat[$cab_prec][3]+1;

                $tpat[$regions[$cab_prec]][3]=$tpat[$regions[$cab_prec]][3]+1;
            }
            else //pas de mesure
            {
                $tpat['tot'][0]=$tpat['tot'][0]+1;
                $tpat[$cab_prec][0]=$tpat[$cab_prec][0]+1;

                $tpat[$regions[$cab_prec]][0]=$tpat[$regions[$cab_prec]][0]+1;
            }

            if(($hba_prec<=7)&&($hba_prec>0))
            {
                $tpat['tot'][4]=$tpat['tot'][4]+1;
                $tpat[$cab_prec][4]=$tpat[$cab_prec][4]+1;

                $tpat[$regions[$cab_prec]][4]=$tpat[$regions[$cab_prec]][4]+1;
            }


            $total['tot']=$total['tot']+1;

            $total[$regions[$cab_prec]]=$total[$regions[$cab_prec]]+1;

            $total[$cab_prec]=$total[$cab_prec]+1;
        }


        ?>

        <tr>
            <td>Valeurs de l'HBA1c</td>
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

        <?php

        $intitule=array('Manquant', '<=6,5 / équilibré<sup>1</sup>', ']6,5 - 8 [<sup>2</sup>', '>=8 / Très déséquilibré<sup>3</sup>',
            '<7%<sup>5</sup>');


        for($i=1; $i<=3; $i++)
        {
            ?>
            <tr>
                <td><?php echo $intitule[$i]; ?></td>
                <td align="right"><?php echo round($tpat['tot'][$i]/$total['tot']*100);?>%</Td>

                <?php
                foreach($liste_reg as $reg){
                    if($total[$reg]>0){
                        echo "<td align='right'>".round($tpat[$reg][$i]/$total[$reg]*100)."%</Td>";
                    }
                    else{
                        echo "<td align='right'>ND</td>";
                    }
                }

                foreach ($tcabinet as $cab)
                {
                    ?>
                    <td align="right"><?php if($total[$cab]==0)
                {
                    echo "ND</td>";
                }
                else
                {
                    echo round($tpat[$cab][$i]/$total[$cab]*100);?>%</td>
                    <?php
                }

                }
                ?>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td>Manquant<sup>4</sup></td>

            <td align='right'><?php echo round($tpat['tot'][0]/$total['tot']*100);?>%</td>

            <?php

            foreach($liste_reg as $reg){
                if($total[$reg]>0){
                    echo "<td align='right'>".round($tpat[$reg][0]/$total[$reg]*100)."%</td>";
                }
                else{
                    echo "<td align='right'>ND</td>";
                }
            }

            foreach ($tcabinet as $cab)
            {
                ?>
                <td align="right"><?php if($total[$cab]==0)
            {
                echo "ND</td>";
            }
            else
            {
                echo round($tpat[$cab][0]/$total[$cab]*100);?>%</td>
                <?php
            }


            }
            ?>

        </tr>

        <tr>
            <td><?php echo $intitule[4]; ?></td>
            <td align="right"><?php echo round($tpat['tot'][4]/$total['tot']*100);?>%</Td>

            <?php

            foreach($liste_reg as $reg){
                if($total[$reg]>0){
                    echo "<td align='right'>".round($tpat[$reg][4]/$total[$reg]*100)."%</Td>";
                }
                else{
                    echo "<td align='right'>ND</td>";
                }
            }


            foreach ($tcabinet as $cab)
            {
                ?>
                <td align="right"><?php if($total[$cab]==0)
            {
                echo "ND</td>";
            }
            else
            {
                echo round($tpat[$cab][4]/$total[$cab]*100);?>%</td>
                <?php
            }

            }
            ?>
        </tr>



        <?php

        $intitule2=array('Manquant', 'nb <=6,5 / équilibré<sup>6</sup>', 'nb ]6,5 - 8 [<sup>7</sup>', 'nb >=8 / Très déséquilibré<sup>8</sup>',
            'nb <7%<sup>10</sup>');


        for($i=1; $i<=3; $i++)
        {
            ?>
            <tr>
                <td><?php echo $intitule2[$i]; ?></td>
                <td align="right"><?php echo $tpat['tot'][$i];?></Td>

                <?php

                foreach($liste_reg as $reg){
                    echo "<td align='right'>".$tpat[$reg][$i]."</Td>";
                }

                foreach ($tcabinet as $cab)
                {
                    ?>
                    <td align="right"><?php if($total[$cab]==0)
                {
                    echo "ND</td>";
                }
                else
                {
                    echo $tpat[$cab][$i];?></td>
                    <?php
                }

                }
                ?>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td>Manquant<sup>9</sup></td>
            <td align='right'><?php echo $tpat['tot'][0];?></td>

            <?php

            foreach($liste_reg as $reg){
                echo "<td align='right'>".$tpat[$reg][0]."</td>";
            }

            foreach ($tcabinet as $cab)
            {
                ?>
                <td align="right"><?php if($total[$cab]==0)
            {
                echo "ND</td>";
            }
            else
            {
                echo $tpat[$cab][0];?></td>
                <?php
            }


            }
            ?>

        </tr>

        <tr>
            <td><?php echo $intitule[4]; ?></td>
            <td align="right"><?php echo $tpat['tot'][4];?></Td>

            <?php

            foreach($liste_reg as $reg){
                echo "<td align='right'>".$tpat[$reg][4]."</Td>";
            }

            foreach ($tcabinet as $cab)
            {
                ?>
                <td align="right"><?php if($total[$cab]==0)
            {
                echo "ND</td>";
            }
            else
            {
                echo $tpat[$cab][4];?></td>
                <?php
            }

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
        tableau($date);

        $mois=$mois-3;

        if($mois<=0)
        {
            $mois=$mois+12;
            $annee--;
        }
    }
    ?>

    <sup>1</sup>Proportion de patients ayant eu au moins un suivi diabète et dont la dernière valeur de HBA1c est inférieure à 6,5<br>
    <sup>2</sup>Proportion de patients ayant eu au moins un suivi diabète et dont la dernière valeur de HBA1c est comprise entre 6,5 et 8<br>
    <sup>3</sup>Proportion de patients ayant eu au moins un suivi diabète et dont la dernière valeur de HBA1c est supérieure à 8<br>
    <sup>4</sup>Proportion de patients ayant eu au moins un suivi diabète et avec aucune valeur de HBA1c<br>
    <sup>5</sup>Proportion de patients ayant eu au moins un suivi diabète et dont la dernière valeur de HBA1c est inférieure à 7<br>
    <sup>6</sup>Nb de patients ayant eu au moins un suivi diabète et dont la dernière valeur de HBA1c est inférieure à 6,5<br>
    <sup>7</sup>Nb de patients ayant eu au moins un suivi diabète et dont la dernière valeur de HBA1c est comprise entre 6,5 et 8<br>
    <sup>8</sup>Nb de patients ayant eu au moins un suivi diabète et dont la dernière valeur de HBA1c est supérieure à 8<br>
    <sup>9</sup>Nb de patients ayant eu au moins un suivi diabète et avec aucune valeur de HBA1c<br>
    <sup>10</sup>Nb de patients ayant eu au moins un suivi diabète et dont la dernière valeur de HBA1c est inférieure à 7<br>
    <?php
}

//affichage arrêtés triemstriels
function tableau($date){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $regions, $liste_reg;


    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

    $tab_date=split('-', $date);

    echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";



    ?>
    <br>
    <br>
    <table border=1 width='100%'>

        <?php

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
        //taux diab 2 suivis dans asalée
        $req="SELECT cabinet, dossier.id, dsuivi, resultat1 ".
            "FROM suivi_diabete, dossier, evaluation_infirmier, liste_exam ".
            "WHERE  ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "AND date_exam<='$date' and evaluation_infirmier.date<='$date' and ".
            "and dossier.id=liste_exam.id and type_exam='HBA1c' ".
            "dossier.id=evaluation_infirmier.id ".
            "ORDER BY cabinet, dossier.id, date_exam ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");



        // print_r($tcabinet_util);die;
        foreach ($tcabinet as $cab)
        {
            $tpat[$cab][0]=0;//pas de hba
            $tpat[$cab][1]=0;//hba<6.5
            $tpat[$cab][2]=0;//6.5<hba<8
            $tpat[$cab][3]=0;//>8
            $tpat[$cab][4]=0;//<7
            $total[$cab]=0;
        }

        $tpat['tot'][0]=0;
        $tpat['tot'][1]=0;
        $tpat['tot'][2]=0;
        $tpat['tot'][3]=0;
        $tpat['tot'][4]=0;

        foreach($liste_reg as $reg){
            $tpat[$reg][0]=0;
            $tpat[$reg][1]=0;
            $tpat[$reg][2]=0;
            $tpat[$reg][3]=0;
            $tpat[$reg][4]=0;

            $total[$reg]=0;
        }


        $total['tot']=0;

        $id_prec='';
        $cab_prec="";
        $i=0;
        while(list($cab, $id, $dsuivi, $ResHBA) = mysql_fetch_row($res)) {

            if((isset($tcabinet_util[$cab_prec]))&&($tcabinet_util[$cab_prec]==1)){
                $i++;
                if($id_prec!=$id)
                {

                    if($id_prec!='')
                    {
                        if(($hba_prec<=6.5)&&($hba_prec>0))
                        {
                            $tpat['tot'][1]=$tpat['tot'][1]+1;
                            $tpat[$cab_prec][1]=$tpat[$cab_prec][1]+1;

                            $tpat[$regions[$cab_prec]][1]=$tpat[$regions[$cab_prec]][1]+1;
                        }
                        elseif(($hba_prec>6.5)&&($hba_prec<8))
                        {
                            $tpat['tot'][2]=$tpat['tot'][2]+1;
                            $tpat[$cab_prec][2]=$tpat[$cab_prec][2]+1;

                            $tpat[$regions[$cab_prec]][2]=$tpat[$regions[$cab_prec]][2]+1;
                        }
                        elseif($hba_prec>=8)
                        {
                            $tpat['tot'][3]=$tpat['tot'][3]+1;
                            $tpat[$cab_prec][3]=$tpat[$cab_prec][3]+1;

                            $tpat[$regions[$cab_prec]][3]=$tpat[$regions[$cab_prec]][3]+1;
                        }
                        else //pas de mesure
                        {
                            $tpat['tot'][0]=$tpat['tot'][0]+1;
                            $tpat[$cab_prec][0]=$tpat[$cab_prec][0]+1;

                            $tpat[$regions[$cab_prec]][0]=$tpat[$regions[$cab_prec]][0]+1;
                        }


                        if(($hba_prec<=7)&&($hba_prec>0))//<7
                        {
                            $tpat['tot'][4]=$tpat['tot'][4]+1;
                            $tpat[$cab_prec][4]=$tpat[$cab_prec][4]+1;

                            $tpat[$regions[$cab_prec]][4]=$tpat[$regions[$cab_prec]][4]+1;
                        }

                        $total['tot']=$total['tot']+1;

                        $total[$regions[$cab_prec]]=$total[$regions[$cab_prec]]+1;
                        $total[$cab_prec]=$total[$cab_prec]+1;

                        $cab_prec=$cab;
                        $hba_prec=$ResHBA;
                        $id_prec=$id;


                    }
                    else
                    {
                        $id_prec=$id;
                        $hba_prec=$ResHBA;
                        $cab_prec=$cab;

                    }

                }
                else
                {
                    if(($ResHBA!=0)&&($ResHBA!='NULL'))
                    {
                        $hba_prec=$ResHBA;
                    }
                }
            }
            else
            {
                $id_prec=$id;
                $hba_prec=$ResHBA;
                $cab_prec=$cab;

            }
        }

        if((isset($tcabinet_util[$cab_prec]))&&($tcabinet_util[$cab_prec]==1)){
            if(($hba_prec<=6.5)&&($hba_prec>0))
            {
                $tpat['tot'][1]=$tpat['tot'][1]+1;
                $tpat[$cab_prec][1]=$tpat[$cab_prec][1]+1;

                $tpat[$regions[$cab_prec]][1]=$tpat[$regions[$cab_prec]][1]+1;
            }
            elseif(($hba_prec>6.5)&&($hba_prec<8))
            {
                $tpat['tot'][2]=$tpat['tot'][2]+1;
                $tpat[$cab_prec][2]=$tpat[$cab_prec][2]+1;

                $tpat[$regions[$cab_prec]][2]=$tpat[$regions[$cab_prec]][2]+1;
            }
            elseif($hba_prec>=8)
            {
                $tpat['tot'][3]=$tpat['tot'][3]+1;
                $tpat[$cab_prec][3]=$tpat[$cab_prec][3]+1;

                $tpat[$regions[$cab_prec]][3]=$tpat[$regions[$cab_prec]][3]+1;
            }
            else //pas de mesure
            {
                $tpat['tot'][0]=$tpat['tot'][0]+1;
                $tpat[$cab_prec][0]=$tpat[$cab_prec][0]+1;

                $tpat[$regions[$cab_prec]][0]=$tpat[$regions[$cab_prec]][0]+1;
            }

            if(($hba_prec<=7)&&($hba_prec>0))//<7
            {
                $tpat['tot'][4]=$tpat['tot'][4]+1;
                $tpat[$cab_prec][4]=$tpat[$cab_prec][4]+1;

                $tpat[$regions[$cab_prec]][4]=$tpat[$regions[$cab_prec]][4]+1;
            }

            $total['tot']=$total['tot']+1;

            $total[$regions[$cab_prec]]=$total[$regions[$cab_prec]]+1;

            $total[$cab_prec]=$total[$cab_prec]+1;
        }

        ?>

        <tr>
            <td>Valeurs de l'HBA1c</td>
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

        <?php

        $intitule=array('Manquant', '<=6,5 / équilibré<sup>1</sup>', ']6,5 - 8 [<sup>2</sup>', '>=8 / Très déséquilibré<sup>3</sup>',
            '<7%<sup>5</sup>');


        for($i=1; $i<=3; $i++)
        {
            ?>
            <tr>
                <td><?php echo $intitule[$i]; ?></td>
                <td align="right"><?php echo round($tpat['tot'][$i]/$total['tot']*100);?>%</Td>

                <?php

                foreach($liste_reg as $reg){
                    if($total[$reg]>0){
                        echo "<td align='right'>".round($tpat[$reg][$i]/$total[$reg]*100)."%</Td>";
                    }
                    else{
                        echo "<td align='right'>ND</td>";
                    }
                }

                foreach ($tcabinet as $cab)
                {
                    ?>
                    <td align="right"><?php if($total[$cab]==0)
                {
                    echo "ND</td>";
                }
                else
                {
                    echo round($tpat[$cab][$i]/$total[$cab]*100);?>%</td>
                    <?php
                }


                }
                ?>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td>Manquant<sup>4</sup></td>
            <td align='right'><?php echo round($tpat['tot'][0]/$total['tot']*100);?>%</td>

            <?php

            foreach($liste_reg as $reg){
                if($total[$reg]>0){
                    echo "<td align='right'>".round($tpat[$reg][0]/$total[$reg]*100)."%</td>";
                }
                else{
                    echo "<td align='right'>ND</td>";
                }
            }

            foreach ($tcabinet as $cab)
            {
                ?>
                <td align="right"><?php if($total[$cab]==0)
            {
                echo "ND</td>";
            }
            else
            {
                echo round($tpat[$cab][0]/$total[$cab]*100);?>%</td>
                <?php
            }

            }
            ?>

        </tr>

        <tr>
            <td><?php echo $intitule[4]; ?></td>
            <td align="right"><?php echo round($tpat['tot'][4]/$total['tot']*100);?>%</Td>

            <?php

            foreach($liste_reg as $reg){
                if($total[$reg]>0){
                    echo "<td align='right'>".round($tpat[$reg][4]/$total[$reg]*100)."%</Td>";
                }
                else{
                    echo "<td align='right'>ND</td>";
                }
            }

            foreach ($tcabinet as $cab)
            {
                ?>
                <td align="right"><?php if($total[$cab]==0)
            {
                echo "ND</td>";
            }
            else
            {
                echo round($tpat[$cab][4]/$total[$cab]*100);?>%</td>
                <?php
            }


            }
            ?>
        </tr>









        <?php

        $intitule2=array('Manquant', 'nb <=6,5 / équilibré<sup>6</sup>', 'nb ]6,5 - 8 [<sup>7</sup>', 'nb >=8 / Très déséquilibré<sup>8</sup>',
            'nb <7%<sup>10</sup>');


        for($i=1; $i<=3; $i++)
        {
            ?>
            <tr>
                <td><?php echo $intitule2[$i]; ?></td>
                <td align="right"><?php echo $tpat['tot'][$i];?></Td>

                <?php

                foreach($liste_reg as $reg){
                    echo "<td align='right'>".$tpat[$reg][$i]."</Td>";
                }

                foreach ($tcabinet as $cab)
                {
                    ?>
                    <td align="right"><?php if($total[$cab]==0)
                {
                    echo "ND</td>";
                }
                else
                {
                    echo $tpat[$cab][$i];?></td>
                    <?php
                }


                }
                ?>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td>Manquant<sup>9</sup></td>
            <td align='right'><?php echo $tpat['tot'][0];?></td>

            <?php

            foreach($liste_reg as $reg){
                echo "<td align='right'>".$tpat[$reg][0]."</td>";
            }

            foreach ($tcabinet as $cab)
            {
                ?>
                <td align="right"><?php if($total[$cab]==0)
            {
                echo "ND</td>";
            }
            else
            {
                echo $tpat[$cab][0];?></td>
                <?php
            }

            }
            ?>

        </tr>

        <tr>
            <td><?php echo $intitule[4]; ?></td>
            <td align="right"><?php echo $tpat['tot'][4];?></Td>

            <?php

            foreach($liste_reg as $reg){
                echo "<td align='right'>".$tpat[$reg][4]."</Td>";
            }

            foreach ($tcabinet as $cab)
            {
                ?>
                <td align="right"><?php if($total[$cab]==0)
            {
                echo "ND</td>";
            }
            else
            {
                echo $tpat[$cab][4];?></td>
                <?php
            }


            }
            ?>
        </tr>

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
