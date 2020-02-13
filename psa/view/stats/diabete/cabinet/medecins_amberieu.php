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
    <title>Intégration des médecins Ambérieu</title>
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

entete_asalee("intégration des médecins Ambérieu");
?>
<!--<table cellpadding="2" cellspacing="2" border="0"
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
<font face='times new roman'>Répartition en fonction de l'IMC</font></i>";
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

    $fp=fopen("patient.csv", "r");

    while($ligne=fgets($fp)){
        $test=explode(";", $ligne);

        while(count($test)<12){//La fin est à la ligne suivante
            $l=fgets($fp);
            $ligne=$ligne.$l;

            $test=explode(";", $ligne);
        }

        $medecin=$test[11];
        $numero=$test[9];

        $medecin=str_replace("\n", "", $medecin);
        $medecin=str_replace("\r", "", $medecin);

        if($numero!=""){
            $req="INSERT INTO amberieu set numero='$numero', medecin='".addslashes(stripslashes($medecin))."';";
            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
        }
    }
    echo "fin";die;
    die;
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

    <sup>1</sup>Proportion de patients ayant eu au moins un suivi diabète et dont la taille n'est pas saisie<br>
    <sup>2</sup>Proportion de patients ayant eu au moins un suivi diabète et dont le dernier IMC est < 25 <br>
    <sup>3</sup>Proportion de patients ayant eu au moins un suivi diabète et dont le dernier IMC est compris entre 25 et 30<br>
    <sup>4</sup>Proportion de patients ayant eu au moins un suivi diabète et dont le dernier IMC est compris entre 30 et 40<br>
    <sup>5</sup>Proportion de patients ayant eu au moins un suivi diabète et dont le dernier IMC est compris >40
    <?

}

function tableau($date){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville;


    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

    $tab_date=split('-', $date);

    echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";

    /*print_r($t_tot);echo "<br>";
    print_r($t_sein);echo "<br>";
    print_r($t_cogni);echo "<br>";
    print_r($t_colon);echo "<br>";
    print_r($t_uterus);echo "<br>";
    print_r($t_diab);echo "<br>";
    */



    ?>
    <br>
    <br>
    <table border=1 width='100%'>

        <?php

        foreach($tville as $cab=>$ville){
            $tcabinet_util[$cab]=0;
        }

        $date3mois=date("Y-m-d", mktime(1, 1, 1, $tab_date[1]-3, $tab_date[2], $tab_date[0]));
        $req="SELECT cabinet from liste_exam, dossier where ".
            "dossier.id=liste_exam.id and date_exam>='$date3mois' group by cabinet";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        while(list($cab)=mysql_fetch_row($res)){
            if(isset($tville[$cab])){
                $tcabinet_util[$cab]=1;
            }
        }

        //taux diab 2 suivis dans asalée
        $req="SELECT cabinet, dossier.id, date_exam, resultat1, taille ".
            "FROM suivi_diabete, dossier, liste_exam ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND suivi_diabete.dossier_id=dossier.id and dossier.id=liste_exam.id ".
            "AND dsuivi<='$date' and date_exam<='$date' and type_exam='poids' ".
            "GROUP BY cabinet, dossier.id, date_exam ".
            "ORDER BY cabinet, dossier.id, date_exam ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
        // echo $req;die;
        foreach ($tcabinet as $cab)
        {
            $tpat[$cab][0]=0;
            $tpat[$cab][1]=0;
            $tpat[$cab][2]=0;
            $tpat[$cab][3]=0;
            $tpat[$cab][4]=0;
            $total[$cab]=0;
        }

        $tpat['tot'][0]=0;
        $tpat['tot'][1]=0;
        $tpat['tot'][2]=0;
        $tpat['tot'][3]=0;
        $tpat['tot'][4]=0;


        $total['tot']=0;

        $id_prec='';
        $cab_precedent="";
        $cab_prec="";
        $cab_util=array();

        while(list($cab, $id, $dsuivi, $poids, $taille) = mysql_fetch_row($res)) {
            if(isset($tcabinet_util[$cab_prec])&&($tcabinet_util[$cab_prec]==1)){
                if($id_prec!=$id)
                {

                    if($id_prec!='')
                    {
                        if(is_null($taille_prec) or !is_numeric($taille_prec) or $taille_prec == 0)
                        {
                            $tpat['tot'][0]=$tpat['tot'][0]+1;
                            $tpat[$cab_prec][0]=$tpat[$cab_prec][0]+1;
                        }
                        else
                        {
                            $imc=round($poids_prec/pow($taille_prec/100, 2),1);

                            if($imc<25)
                            {
                                $tpat['tot'][1]=$tpat['tot'][1]+1;
                                $tpat[$cab_prec][1]=$tpat[$cab_prec][1]+1;
                            }
                            elseif(($imc>=25)&&($imc<30))
                            {
                                $tpat['tot'][2]=$tpat['tot'][2]+1;
                                $tpat[$cab_prec][2]=$tpat[$cab_prec][2]+1;
                            }
                            elseif(($imc>=30)&&($imc<40))
                            {
                                $tpat['tot'][3]=$tpat['tot'][3]+1;
                                $tpat[$cab_prec][3]=$tpat[$cab_prec][3]+1;
                            }
                            else
                            {
                                $tpat['tot'][4]=$tpat['tot'][4]+1;
                                $tpat[$cab_prec][4]=$tpat[$cab_prec][4]+1;
                            }
                        }

                        $total['tot']=$total['tot']+1;
                        $total[$cab_prec]=$total[$cab_prec]+1;

                        $cab_prec=$cab;
                        $poids_prec=$poids;
                        $taille_prec=$taille;


                    }
                    $id_prec=$id;
                    $poids_prec=$poids;
                    $taille_prec=$taille;
                    $cab_prec=$cab;


                }
                else
                {
                    $poids_prec=$poids;
                    $taille_prec=$taille;
                    $cab_prec=$cab;
                }
            }
            else
            {
                $poids_prec=$poids;
                $taille_prec=$taille;
                $cab_prec=$cab;
            }

            if($cab_precedent!=$cab)
            {
                $cab_util[]=$cab;
                $cab_precedent=$cab;
            }
        }

        if(isset($tcabinet_util[$cab_prec])&&($tcabinet_util[$cab_prec]==1)){
            if(is_null($taille_prec) or !is_numeric($taille_prec) or $taille_prec == 0)
            {
                $tpat['tot'][0]=$tpat['tot'][0]+1;
                $tpat[$cab_prec][0]=$tpat[$cab_prec][0]+1;
            }
            else
            {
                $imc=round($poids_prec/pow($taille_prec/100, 2),1);

                if($imc<25)
                {
                    $tpat['tot'][1]=$tpat['tot'][1]+1;
                    $tpat[$cab_prec][1]=$tpat[$cab_prec][1]+1;
                }
                elseif(($imc>=25)&&($imc<30))
                {
                    $tpat['tot'][2]=$tpat['tot'][2]+1;
                    $tpat[$cab_prec][2]=$tpat[$cab_prec][2]+1;
                }
                elseif(($imc>=30)&&($imc<40))
                {
                    $tpat['tot'][3]=$tpat['tot'][3]+1;
                    $tpat[$cab_prec][3]=$tpat[$cab_prec][3]+1;
                }
                else
                {
                    $tpat['tot'][4]=$tpat['tot'][4]+1;
                    $tpat[$cab_prec][4]=$tpat[$cab_prec][4]+1;
                }
            }
        }

        $total['tot']=$total['tot']+1;
        $total[$cab_prec]=$total[$cab_prec]+1;
        // print_r($total);echo "<br><br>";
        // print_r($tpat);die;

        ?>

        <tr>
            <td>IMC (kg/m&sup2;)</td>
            <td align="center"><b>Proportion de patients</b></td>
            <td align="center"><b><?php echo $tville[$_SESSION['nom']];?></b></td>
            <td align="center"><b>Borne Basse</b></td>
            <td align="center"><b>Borne Haute</b></td>
            <?php

            for($i=0;$i<=4;$i++)
            {
                $mini[$i]=100;
                $maxi[$i]=0;
            }

            foreach ($tcabinet_util as $cab =>$val)
            {
                if($val==1){

                    for($i=0;$i<=4;$i++)
                    {
                        if($total[$cab]!=0){
                            $moy=round($tpat[$cab][$i]/$total[$cab]*100);
                            if($moy<$mini[$i])
                            {
                                $mini[$i]=$moy;
                            }
                            if($moy>$maxi[$i])
                            {
                                $maxi[$i]=$moy;
                            }
                        }
                    }
                }
            }
            ?>
        </tr>


        <?php

        $intitule=array('Taille non saisie <sup>1</sup>', '<25 <sup>2</sup>', '[25 - 30[ <sup>3</sup>', '[30 - 40[ <sup>4</sup>', '>40 <sup>5</sup>');


        for($i=0; $i<=4; $i++)
        {
            ?>
            <tr>
                <td><?php echo $intitule[$i]; ?></td>
                <td align="right"><?php echo round($tpat['tot'][$i]/$total['tot']*100);?>%</Td>
                <td align="right"><?php if($total[$_SESSION['nom']]==0)
                    {
                        echo "ND</td>";
                    }
                    else
                    {
                    echo round($tpat[$_SESSION['nom']][$i]/$total[$_SESSION['nom']]*100);?>%</td>
                <?
                }
                ?>
                <td align="right"><?php echo $mini[$i];?>%</td>
                <td align="right"><?php echo $maxi[$i];?>%</td>
            </tr>
            <?php
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
