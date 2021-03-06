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

set_time_limit(120);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="content-type"
          content="text/html; charset=ISO-8859-15">
    <title>Taux de tension �quilibr�e - cabinets actifs</title>
</head>
<body bgcolor=#FFE887>
<?php
require_once ("Config.php");
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

entete_asalee("Taux de tension �quilibr�e - cabinets actifs");

//echo $loc;
?>

<br><br>
<?

# boucle principale
do {
    $repete=false;

    # �tape 1 : tableau � la date du jour
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {
            //tableau � la date du jour
            case 1:
                etape_1($repete);
                break;

        }
    }
} while($repete);

# fin de traitement principal

//tableau � la date du jour
function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $regions, $liste_reg;



    $req="SELECT dossier.cabinet, count(*), nom_cab, region ".
        "FROM dossier, account ".
        "WHERE infirmiere!='' and region!='' ".
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
    $mois=array('01'=>'Janvier', '02'=>'F�vrier', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Ao�t', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'D�cembre');

    echo '<b>Donn�es � la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

    ?>
    <br>
    <br>
    <table border=1 width='100%'>

        <?php

        //taux diab 2 suivis dans asal�e
        $req="SELECT cabinet, dossier.id, date_exam, resultat1 ".
            "FROM cardio_vasculaire_depart, dossier, liste_exam ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and dossier.cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND actif='oui' ".
            "AND cardio_vasculaire_depart.id=dossier.id and dossier.id=liste_exam.id ".
            "and type_exam='systole' ".
            "GROUP BY cabinet, dossier.id, date_exam ";
        "ORDER BY cabinet, dossier.id, date_exam ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        foreach ($tcabinet as $cab)
        {
            $tpat[$cab][0]=0;//pas de TA
            $tpat[$cab][1]=0;//TA < 140/90
            $tpat[$cab][2]=0;//TA>=140/90
            $total[$cab]=0;
        }

        $tpat['tot'][0]=0;
        $tpat['tot'][1]=0;
        $tpat['tot'][2]=0;
        $total['tot']=0;

        foreach($liste_reg as $reg){
            $tpat[$reg][0]=0;
            $tpat[$reg][1]=0;
            $tpat[$reg][2]=0;
            $total[$reg]=0;
        }


        $id_prec='';
        $cab_prec="";
        $i=0;
        while(list($cab, $id, $dsuivi, $TaSys) = mysql_fetch_row($res)) {
            $req2="SELECT resultat1 from liste_exam where id='$id' and type_exam='diastole' and ".
                "date_exam='$dsuivi'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            list($TaDia)=mysql_fetch_row($res2);

            if(isset($regions[$cab_prec])){
                if($tcabinet_util[$cab_prec]==1){
                    $i++;
                    if($id_prec!=$id)
                    {

                        if($id_prec!='')
                        {

                            if(($TaSys_prec<140)&&($TaDia_prec<90)&&($TaSys_prec>0)&&($TaDia_prec>0))
                            {
                                $tpat['tot'][1]=$tpat['tot'][1]+1;
                                $tpat[$cab_prec][1]=$tpat[$cab_prec][1]+1;

                                $tpat[$regions[$cab_prec]][1]=$tpat[$regions[$cab_prec]][1]+1;
                            }
                            elseif(($TaSys_prec>=140)||($TaDia_prec>=90))
                            {
                                $tpat['tot'][2]=$tpat['tot'][2]+1;
                                $tpat[$cab_prec][2]=$tpat[$cab_prec][2]+1;

                                $tpat[$regions[$cab_prec]][2]=$tpat[$regions[$cab_prec]][2]+1;
                            }
                            else //pas de mesure
                            {
                                $tpat['tot'][0]=$tpat['tot'][0]+1;
                                $tpat[$cab_prec][0]=$tpat[$cab_prec][0]+1;

                                $tpat[$regions[$cab_prec]][0]=$tpat[$regions[$cab_prec]][0]+1;
                            }


                            $total['tot']=$total['tot']+1;

                            $total[$regions[$cab_prec]]=$total[$regions[$cab_prec]]+1;

                            $total[$cab_prec]=$total[$cab_prec]+1;

                            $cab_prec=$cab;
                            $TaSys_prec=$TaSys;
                            $TaDia_prec=$TaDia;
                            $id_prec=$id;


                        }
                        else
                        {
                            $id_prec=$id;
                            $TaSys_prec=$TaSys;
                            $TaDia_prec=$TaDia;
                            $cab_prec=$cab;

                        }

                    }
                    else
                    {
                        if(($TaSys!=0)&&($TaSys!='NULL')&&($TaDia!=0)&&($TaDia!='NULL'))
                        {
                            $TaSys_prec=$TaSys;
                            $TaDia_prec=$TaDia;
                        }
                    }
                }
                else
                {
                    $id_prec=$id;
                    $TaSys_prec=$TaSys;
                    $TaDia_prec=$TaDia;
                    $cab_prec=$cab;

                }
            }
            else
            {
                $id_prec=$id;
                $TaSys_prec=$TaSys;
                $TaDia_prec=$TaDia;
                $cab_prec=$cab;

            }
        }

        if(isset($regions[$cab_prec])){
            if($tcabinet_util[$cab_prec]==1){
                if(($TaSys_prec<140)&&($TaSys_prec>0)&&($TaDia_prec>=90)&&($TaDia_prec>0))
                {
                    $tpat['tot'][1]=$tpat['tot'][1]+1;
                    $tpat[$cab_prec][1]=$tpat[$cab_prec][1]+1;

                    $tpat[$regions[$cab_prec]][1]=$tpat[$regions[$cab_prec]][1]+1;
                }
                elseif(($TaSys_prec>=140)&&($TaDia_prec>=90))

                {
                    $tpat['tot'][2]=$tpat['tot'][2]+1;
                    $tpat[$cab_prec][2]=$tpat[$cab_prec][2]+1;

                    $tpat[$regions[$cab_prec]][2]=$tpat[$regions[$cab_prec]][2]+1;
                }
                else //pas de mesure
                {
                    $tpat['tot'][0]=$tpat['tot'][0]+1;
                    $tpat[$cab_prec][0]=$tpat[$cab_prec][0]+1;

                    $tpat[$regions[$cab_prec]][0]=$tpat[$regions[$cab_prec]][0]+1;
                }



                $total['tot']=$total['tot']+1;

                $total[$regions[$cab_prec]]=$total[$regions[$cab_prec]]+1;

                $total[$cab_prec]=$total[$cab_prec]+1;
            }
        }

        ?>

        <tr>
            <td>Valeurs de la tension</td>
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

        $intitule=array('Manquant', '<140/90<sup>1</sup>', '>=140/90<sup>2</sup>');


        for($i=1; $i<=2; $i++)
        {
            ?>
            <tr>
                <td><?php echo $intitule[$i]; ?></td>
                <?php
                if($total["tot"]==0){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round($tpat['tot'][$i]/$total['tot']*100)."%</Td>";
                }


                foreach($liste_reg as $reg){
                    if($total[$reg]==0){
                        echo "<td align='right'>ND</td>";
                    }
                    else{
                        echo "<td align='right'>".round($tpat[$reg][$i]/$total[$reg]*100)."%</Td>";
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
                    <?
                }

                }
                ?>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td>Manquant<sup>3</sup></td>
            <?php
            if($total["tot"]==0){
                echo "<td align='right'>ND</td>";
            }
            else{
                echo "<td align='right'>".round($tpat['tot'][0]/$total['tot']*100)."%</td>";
            }


            foreach($liste_reg as $reg){
                if($total[$reg]==0){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round($tpat[$reg][0]/$total[$reg]*100)."%</td>";
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
                <?
            }


            }
            ?>

        </tr>


        <?
        $intitule2=array('Manquant', 'nb<140/90<sup>4</sup>', 'nb >=140/90<sup>5</sup>');


        for($i=1; $i<=2; $i++)
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
                    <?
                }

                }
                ?>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td>nb Manquant<sup>6</sup></td>
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
                <?
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

    <sup>1</sup>Proportion de patients ayant eu au moins un suivi diab�te et dont la derni�re valeur de tension est inf�rieure � 140/90mmHg<br>
    <sup>2</sup>Proportion de patients ayant eu au moins un suivi diab�te et dont la derni�re valeur de tension est sup�rieure � 140/90mmHg<br>
    <sup>3</sup>Proportion de patients ayant eu au moins un suivi diab�te et avec aucune valeur de tension<br>
    <sup>4</sup>Nb de patients ayant eu au moins un suivi diab�te et dont la derni�re valeur de tension est inf�rieure � 140/90mmHg<br>
    <sup>5</sup>Nb de patients ayant eu au moins un suivi diab�te et dont la derni�re valeur de tension est sup�rieure � 140/90mmHg<br>
    <sup>6</sup>Nb de patients ayant eu au moins un suivi diab�te et avec aucune valeur de tension<br>
    <?
}

//arr�t�s trimestriels
function tableau($date){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $regions, $liste_reg;


    $mois=array('01'=>'Janvier', '02'=>'F�vrier', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Ao�t', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'D�cembre');

    $tab_date=split('-', $date);

    echo "<b>Donn�es au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";



    ?>
    <br>
    <br>
    <table border=1 width='100%'>

        <?php

        foreach($tville as $cab=>$ville){
            $tcabinet_util[$cab]=0;
        }

        $date3mois=date("Y-m-d", mktime(1, 1, 1, $tab_date[1]-3, $tab_date[2], $tab_date[0]));
        $req="SELECT cabinet from evaluation_infirmier, dossier where ".
            "dossier.id=evaluation_infirmier.id and date>='$date3mois' ".
            "and date<='$date' ".
            "group by cabinet";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        while(list($cab)=mysql_fetch_row($res)){
            $tcabinet_util[$cab]=1;
        }
        $req="SELECT cabinet from suivi_diabete, dossier where ".
            "dossier.id=suivi_diabete.dossier_id and dsuivi>='$date3mois' ".
            "and dsuivi<='$date' ".
            "group by cabinet";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        while(list($cab)=mysql_fetch_row($res)){
            $tcabinet_util[$cab]=1;
        }

        //taux diab 2 suivis dans asal�e
        $req="SELECT cabinet, dossier.id, date_exam, resultat1 ".
            "FROM cardio_vasculaire_depart, dossier, liste_exam ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND cardio_vasculaire_depart.id=dossier.id ".
            "AND date<='$date' and date_exam<='$date' ".
            "and type_exam='systole' and dossier.id=liste_exam.id ".
            "GROUP by cabinet, dossier.id, date_exam ".
            "ORDER BY cabinet, dossier.id, date_exam ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        foreach ($tcabinet as $cab)
        {
            $tpat[$cab][0]=0;//pas de tension
            $tpat[$cab][1]=0;//Ta<140/90
            $tpat[$cab][2]=0;//Ta>=140/90
            $total[$cab]=0;
        }

        $tpat['tot'][0]=0;
        $tpat['tot'][1]=0;
        $tpat['tot'][2]=0;
        $total['tot']=0;

        foreach($liste_reg as $reg){
            $tpat[$reg][0]=0;
            $tpat[$reg][1]=0;
            $tpat[$reg][2]=0;
            $total[$reg]=0;
        }



        $id_prec='';
        $cab_prec="";
        $i=0;
        while(list($cab, $id, $dsuivi, $TaSys) = mysql_fetch_row($res)) {
            $req2="SELECT resultat1 from liste_exam where id='$id' and type_exam='diastole' and ".
                "date_exam='$dsuivi'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            list($TaDia)=mysql_fetch_row($res2);

            if(isset($regions[$cab_prec])){
                if($tcabinet_util[$cab_prec]==1){
                    $i++;
                    if($id_prec!=$id)
                    {
                        if($id_prec!='')
                        {
                            if(($TaSys_prec<140)&&($TaDia_prec<90)&&($TaSys_prec>0)&&($TaDia_prec>0))
                            {
                                $tpat['tot'][1]=$tpat['tot'][1]+1;
                                $tpat[$cab_prec][1]=$tpat[$cab_prec][1]+1;

                                $tpat[$regions[$cab_prec]][1]=$tpat[$regions[$cab_prec]][1]+1;
                            }
                            elseif(($TaSys_prec>=140)||($TaDia_prec>=90))
                            {
                                $tpat['tot'][2]=$tpat['tot'][2]+1;
                                $tpat[$cab_prec][2]=$tpat[$cab_prec][2]+1;

                                $tpat[$regions[$cab_prec]][2]=$tpat[$regions[$cab_prec]][2]+1;
                            }
                            else //pas de mesure
                            {
                                $tpat['tot'][0]=$tpat['tot'][0]+1;
                                $tpat[$cab_prec][0]=$tpat[$cab_prec][0]+1;

                                $tpat[$regions[$cab_prec]][0]=$tpat[$regions[$cab_prec]][0]+1;
                            }


                            $total['tot']=$total['tot']+1;

                            $total[$regions[$cab_prec]]=$total[$regions[$cab_prec]]+1;

                            $total[$cab_prec]=$total[$cab_prec]+1;

                            $cab_prec=$cab;
                            $TaSys_prec=$TaSys;
                            $TaDia_prec=$TaDia;
                            $id_prec=$id;

                        }
                        else
                        {
                            $id_prec=$id;
                            $TaSys_prec=$TaSys;
                            $TaDia_prec=$TaDia;
                            $cab_prec=$cab;

                        }

                    }
                    else
                    {
                        if(($TaSys!=0)&&($TaSys!='NULL')&&($TaDia!=0)&&($TaDia!='NULL'))
                        {
                            $TaSys_prec=$TaSys;
                            $TaDia_prec=$TaDia;
                        }
                    }
                }
                else{
                    $id_prec=$id;
                    $TaSys_prec=$TaSys;
                    $TaDia_prec=$TaDia;
                    $cab_prec=$cab;
                }
            }
            else
            {
                $id_prec=$id;
                $TaSys_prec=$TaSys;
                $TaDia_prec=$TaDia;
                $cab_prec=$cab;

            }
        }

        if(isset($regions[$cab_prec])){
            if($tcabinet_util[$cab_prec]==1){
                if(($TaSys_prec<140)&&($TaDia_prec<90)&&($TaSys_prec>0)&&($TaDia_prec>0))
                {
                    $tpat['tot'][1]=$tpat['tot'][1]+1;
                    $tpat[$cab_prec][1]=$tpat[$cab_prec][1]+1;

                    $tpat[$regions[$cab_prec]][1]=$tpat[$regions[$cab_prec]][1]+1;
                }
                elseif(($TaSys_prec>=140)||($TaDia_prec>=90))
                {
                    $tpat['tot'][2]=$tpat['tot'][2]+1;
                    $tpat[$cab_prec][2]=$tpat[$cab_prec][2]+1;

                    $tpat[$regions[$cab_prec]][2]=$tpat[$regions[$cab_prec]][2]+1;
                }
                else //pas de mesure
                {
                    $tpat['tot'][0]=$tpat['tot'][0]+1;
                    $tpat[$cab_prec][0]=$tpat[$cab_prec][0]+1;

                    $tpat[$regions[$cab_prec]][0]=$tpat[$regions[$cab_prec]][0]+1;
                }



                $total['tot']=$total['tot']+1;

                $total[$regions[$cab_prec]]=$total[$regions[$cab_prec]]+1;

                $total[$cab_prec]=$total[$cab_prec]+1;
            }
        }

        ?>

        <tr>
            <td>Valeurs de la tension</td>
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

        $intitule=array('Manquant', '<140/90<sup>1</sup>', '>=140/90<sup>2</sup>');


        for($i=1; $i<=2; $i++)
        {
            ?>
            <tr>
                <td><?php echo $intitule[$i]; ?></td>
                <td align="right"><?php echo round($tpat['tot'][$i]/$total['tot']*100);?>%</Td>

                <?php

                foreach($liste_reg as $reg){
                    if($total[$reg]==0){
                        echo "<td align='right'>ND</td>";
                    }
                    else{
                        echo "<td align='right'>".round($tpat[$reg][$i]/$total[$reg]*100)."%</Td>";
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
                    <?
                }


                }
                ?>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td>Manquant<sup>3</sup></td>
            <td align='right'><?php echo round($tpat['tot'][0]/$total['tot']*100);?>%</td>

            <?php

            foreach($liste_reg as $reg){
                if($total[$reg]==0){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round($tpat[$reg][0]/$total[$reg]*100)."%</td>";
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
                <?
            }

            }
            ?>

        </tr>



        <?
        $intitule2=array('Manquant', 'nb<140/90<sup>4</sup>', 'nb >=140/90<sup>5</sup>');

        for($i=1; $i<=2; $i++)
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
                    <td align="right"><?php
                        echo $tpat[$cab][$i];?></td>
                    <?


                }
                ?>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td>nb Manquant<sup>6</sup></td>
            <td align='right'><?php echo $tpat['tot'][0];?></td>

            <?php

            foreach($liste_reg as $reg){
                echo "<td align='right'>".$tpat[$reg][0]."</td>";
            }

            foreach ($tcabinet as $cab)
            {
                ?>
                <td align="right"><?php
                    echo $tpat[$cab][0];?></td>
                <?


            }
            ?>

        </tr>

    </table>
    <br><br>
    <?php

}


?>
</body>
</html>
