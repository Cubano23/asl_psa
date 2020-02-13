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
    <title>Répartition en fonction de l'IMC</title>
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

entete_asalee("Répartition en fonction de l'IMC");
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
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville;


    /*print_r($t_tot);echo "<br>";
    print_r($t_sein);echo "<br>";
    print_r($t_cogni);echo "<br>";
    print_r($t_colon);echo "<br>";
    print_r($t_uterus);echo "<br>";
    print_r($t_diab);echo "<br>";
    */



    $req="SELECT dossier.cabinet, count(*), nom_cab ".
        "FROM dossier, account ".
        "WHERE dossier.cabinet!='zTest' and dossier.cabinet!='irdes'   and dossier.cabinet!='ergo' ".
        "and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' ".
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

    while(list($cab, $pat, $ville) = mysql_fetch_row($res)) {
        $tcabinet[] = $cab;
        $tville[$cab]=$ville;
//	 $tpat[$cab] = $pat;
    }

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');

    echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";
    ?>
    <br>
    <br>
    <table border=1 width='100%'>

        <?php

        //taux diab 2 suivis dans asalée
        $req="SELECT cabinet, id, dsuivi, poids, taille ".
            "FROM suivi_diabete, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND actif='oui' AND poids>0 ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "ORDER BY cabinet, id, dsuivi ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

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

        $tpat['eval'][0]=0;
        $tpat['eval'][1]=0;
        $tpat['eval'][2]=0;
        $tpat['eval'][3]=0;
        $tpat['eval'][4]=0;

        $tpat['eval2'][0]=0;
        $tpat['eval2'][1]=0;
        $tpat['eval2'][2]=0;
        $tpat['eval2'][3]=0;
        $tpat['eval2'][4]=0;

        $tpat['eval3'][0]=0;
        $tpat['eval3'][1]=0;
        $tpat['eval3'][2]=0;
        $tpat['eval3'][3]=0;
        $tpat['eval3'][4]=0;


        $total['tot']=0;
        $total['eval']=0;
        $total['eval2']=0;
        $total['eval3']=0;

        $id_prec='';

        while(list($cab, $id, $dsuivi, $poids, $taille) = mysql_fetch_row($res)) {
            if($id_prec!=$id)
            {

                if($id_prec!='')
                {
                    if(is_null($taille_prec) or !is_numeric($taille_prec) or $taille_prec == 0)
                    {
                        $tpat['tot'][0]=$tpat['tot'][0]+1;
                        $tpat[$cab_prec][0]=$tpat[$cab_prec][0]+1;

                        if((strcasecmp($cab_prec, "chatillon")==0)||(strcasecmp($cab_prec, "argenton")==0)||(strcasecmp($cab_prec, 'brioux')==0)||
                            (strcasecmp($cab_prec, "niort")==0)||(strcasecmp($cab_prec, "saint-varent")==0))
                        {
                            $tpat['eval'][0]=$tpat['eval'][0]+1;
                        }
                        elseif((strcasecmp($cab_prec, "lucquin")==0)||(strcasecmp($cab_prec, "dominault")==0)||(strcasecmp($cab_prec, 'paquereau')==0)||
                            (strcasecmp($cab_prec, "chizé")==0))
                        {
                            $tpat['eval2'][0]=$tpat['eval2'][0]+1;
                        }
                        elseif((strcasecmp($cab_prec, "couture")==0)||(strcasecmp($cab_prec, "lezay")==0)||(strcasecmp($cab_prec, 'lezay2')==0)||
                            (strcasecmp($cab_prec, 'chef-boutonne1')==0)||(strcasecmp($cab_prec, 'chef-boutonne2')==0)||
                            (strcasecmp($cab_prec, 'bouille')==0)||(strcasecmp($cab_prec, 'la-mothe')==0)||
                            (strcasecmp($cab_prec, 'frontenay')==0)||(strcasecmp($cab_prec, "mauzé")==0))
                        {
                            $tpat['eval3'][0]=$tpat['eval3'][0]+1;
                        }
                    }
                    else
                    {
                        $imc=round($poids_prec/pow($taille_prec/100, 2),1);

                        if($imc<25)
                        {
                            $tpat['tot'][1]=$tpat['tot'][1]+1;
                            $tpat[$cab_prec][1]=$tpat[$cab_prec][1]+1;

                            if((strcasecmp($cab_prec, "chatillon")==0)||(strcasecmp($cab_prec, "argenton")==0)||(strcasecmp($cab_prec, 'brioux')==0)||
                                (strcasecmp($cab_prec, "niort")==0)||(strcasecmp($cab_prec, "saint-varent")==0))
                            {
                                $tpat['eval'][1]=$tpat['eval'][1]+1;
                            }
                            elseif((strcasecmp($cab_prec, "lucquin")==0)||(strcasecmp($cab_prec, "dominault")==0)||(strcasecmp($cab_prec, 'paquereau')==0)||
                                (strcasecmp($cab_prec, "chizé")==0))
                            {
                                $tpat['eval2'][1]=$tpat['eval2'][1]+1;
                            }
                            elseif((strcasecmp($cab_prec, "couture")==0)||(strcasecmp($cab_prec, "lezay")==0)||(strcasecmp($cab_prec, 'lezay2')==0)||
                                (strcasecmp($cab_prec, 'chef-boutonne1')==0)||(strcasecmp($cab_prec, 'chef-boutonne2')==0)||
                                (strcasecmp($cab_prec, 'bouille')==0)||(strcasecmp($cab_prec, 'la-mothe')==0)||
                                (strcasecmp($cab_prec, 'frontenay')==0)||(strcasecmp($cab_prec, "mauzé")==0))
                            {
                                $tpat['eval3'][1]=$tpat['eval3'][1]+1;
                            }
                        }
                        elseif(($imc>=25)&&($imc<30))
                        {
                            $tpat['tot'][2]=$tpat['tot'][2]+1;
                            $tpat[$cab_prec][2]=$tpat[$cab_prec][2]+1;

                            if((strcasecmp($cab_prec, "chatillon")==0)||(strcasecmp($cab_prec, "argenton")==0)||(strcasecmp($cab_prec, 'brioux')==0)||
                                (strcasecmp($cab_prec, "niort")==0)||(strcasecmp($cab_prec, "saint-varent")==0))
                            {
                                $tpat['eval'][2]=$tpat['eval'][2]+1;
                            }
                            elseif((strcasecmp($cab_prec, "lucquin")==0)||(strcasecmp($cab_prec, "dominault")==0)||(strcasecmp($cab_prec, 'paquereau')==0)||
                                (strcasecmp($cab_prec, "chizé")==0))
                            {
                                $tpat['eval2'][2]=$tpat['eval2'][2]+1;
                            }
                            elseif((strcasecmp($cab_prec, "couture")==0)||(strcasecmp($cab_prec, "lezay")==0)||(strcasecmp($cab_prec, 'lezay2')==0)||
                                (strcasecmp($cab_prec, 'chef-boutonne1')==0)||(strcasecmp($cab_prec, 'chef-boutonne2')==0)||
                                (strcasecmp($cab_prec, 'bouille')==0)||(strcasecmp($cab_prec, 'la-mothe')==0)||
                                (strcasecmp($cab_prec, 'frontenay')==0)||(strcasecmp($cab_prec, "mauzé")==0))
                            {
                                $tpat['eval3'][2]=$tpat['eval3'][2]+1;
                            }
                        }
                        elseif(($imc>=30)&&($imc<40))
                        {
                            $tpat['tot'][3]=$tpat['tot'][3]+1;
                            $tpat[$cab_prec][3]=$tpat[$cab_prec][3]+1;

                            if((strcasecmp($cab_prec, "chatillon")==0)||(strcasecmp($cab_prec, "argenton")==0)||(strcasecmp($cab_prec, 'brioux')==0)||
                                (strcasecmp($cab_prec, "niort")==0)||(strcasecmp($cab_prec, "saint-varent")==0))
                            {
                                $tpat['eval'][3]=$tpat['eval'][3]+1;
                            }
                            elseif((strcasecmp($cab_prec, "lucquin")==0)||(strcasecmp($cab_prec, "dominault")==0)||(strcasecmp($cab_prec, 'paquereau')==0)||
                                (strcasecmp($cab_prec, "chizé")==0))
                            {
                                $tpat['eval2'][3]=$tpat['eval2'][3]+1;
                            }
                            elseif((strcasecmp($cab_prec, "couture")==0)||(strcasecmp($cab_prec, "lezay")==0)||(strcasecmp($cab_prec, 'lezay2')==0)||
                                (strcasecmp($cab_prec, 'chef-boutonne1')==0)||(strcasecmp($cab_prec, 'chef-boutonne2')==0)||
                                (strcasecmp($cab_prec, 'bouille')==0)||(strcasecmp($cab_prec, 'la-mothe')==0)||
                                (strcasecmp($cab_prec, 'frontenay')==0)||(strcasecmp($cab_prec, "mauzé")==0))
                            {
                                $tpat['eval3'][3]=$tpat['eval3'][3]+1;
                            }
                        }
                        else
                        {
                            $tpat['tot'][4]=$tpat['tot'][4]+1;
                            $tpat[$cab_prec][4]=$tpat[$cab_prec][4]+1;

                            if((strcasecmp($cab_prec, "chatillon")==0)||(strcasecmp($cab_prec, "argenton")==0)||(strcasecmp($cab_prec, 'brioux')==0)||
                                (strcasecmp($cab_prec, "niort")==0)||(strcasecmp($cab_prec, "saint-varent")==0))
                            {
                                $tpat['eval'][4]=$tpat['eval'][4]+1;
                            }
                            elseif((strcasecmp($cab_prec, "lucquin")==0)||(strcasecmp($cab_prec, "dominault")==0)||(strcasecmp($cab_prec, 'paquereau')==0)||
                                (strcasecmp($cab_prec, "chizé")==0))
                            {
                                $tpat['eval2'][4]=$tpat['eval2'][4]+1;
                            }
                            elseif((strcasecmp($cab_prec, "couture")==0)||(strcasecmp($cab_prec, "lezay")==0)||(strcasecmp($cab_prec, 'lezay2')==0)||
                                (strcasecmp($cab_prec, 'chef-boutonne1')==0)||(strcasecmp($cab_prec, 'chef-boutonne2')==0)||
                                (strcasecmp($cab_prec, 'bouille')==0)||(strcasecmp($cab_prec, 'la-mothe')==0)||
                                (strcasecmp($cab_prec, 'frontenay')==0)||(strcasecmp($cab_prec, "mauzé")==0))
                            {
                                $tpat['eval3'][4]=$tpat['eval3'][4]+1;
                            }
                        }
                    }

                    $total['tot']=$total['tot']+1;
                    $total[$cab_prec]=$total[$cab_prec]+1;

                    if((strcasecmp($cab_prec, "chatillon")==0)||(strcasecmp($cab_prec, "argenton")==0)||(strcasecmp($cab_prec, 'brioux')==0)||
                        (strcasecmp($cab_prec, "niort")==0)||(strcasecmp($cab_prec, "saint-varent")==0))
                    {
                        $total['eval']=$total['eval']+1;
                    }
                    elseif((strcasecmp($cab_prec, "lucquin")==0)||(strcasecmp($cab_prec, "dominault")==0)||(strcasecmp($cab_prec, 'paquereau')==0)||
                        (strcasecmp($cab_prec, "chizé")==0))
                    {
                        $total['eval2']=$total['eval2']+1;
                    }
                    elseif((strcasecmp($cab_prec, "couture")==0)||(strcasecmp($cab_prec, "lezay")==0)||(strcasecmp($cab_prec, 'lezay2')==0)||
                        (strcasecmp($cab_prec, 'chef-boutonne1')==0)||(strcasecmp($cab_prec, 'chef-boutonne2')==0)||
                        (strcasecmp($cab_prec, 'bouille')==0)||(strcasecmp($cab_prec, 'la-mothe')==0)||
                        (strcasecmp($cab_prec, 'frontenay')==0)||(strcasecmp($cab_prec, "mauzé")==0))
                    {
                        $total['eval3']=$total['eval3']+1;
                    }

                    $cab_prec=$cab;
                    $poids_prec=$poids;
                    $taille_prec=$taille;
                    $id_prec=$id;


                }
                else
                {
                    $id_prec=$id;
                    $poids_prec=$poids;
                    $taille_prec=$taille;
                    $cab_prec=$cab;

                }

            }
            else
            {

                if($poids!=0){
                    $poids_prec=$poids;
                }
                $taille_prec=$taille;
                $cab_prec=$cab;
            }


        }

        if(is_null($taille_prec) or !is_numeric($taille_prec) or $taille_prec == 0)
        {
            $tpat['tot'][0]=$tpat['tot'][0]+1;
            $tpat[$cab_prec][0]=$tpat[$cab_prec][0]+1;

            if((strcasecmp($cab_prec, "chatillon")==0)||(strcasecmp($cab_prec, "argenton")==0)||(strcasecmp($cab_prec, 'brioux')==0)||
                (strcasecmp($cab_prec, "niort")==0)||(strcasecmp($cab_prec, "saint-varent")==0))
            {
                $tpat['eval'][0]=$tpat['eval'][0]+1;
            }
            elseif((strcasecmp($cab_prec, "lucquin")==0)||(strcasecmp($cab_prec, "dominault")==0)||(strcasecmp($cab_prec, 'paquereau')==0)||
                (strcasecmp($cab_prec, "chizé")==0))
            {
                $tpat['eval2'][0]=$tpat['eval2'][0]+1;
            }
            elseif((strcasecmp($cab_prec, "couture")==0)||(strcasecmp($cab_prec, "lezay")==0)||(strcasecmp($cab_prec, 'lezay2')==0)||
                (strcasecmp($cab_prec, 'chef-boutonne1')==0)||(strcasecmp($cab_prec, 'chef-boutonne2')==0)||
                (strcasecmp($cab_prec, 'bouille')==0)||(strcasecmp($cab_prec, 'la-mothe')==0)||
                (strcasecmp($cab_prec, 'frontenay')==0)||(strcasecmp($cab_prec, "mauzé")==0))
            {
                $tpat['eval3'][0]=$tpat['eval3'][0]+1;
            }
        }
        else
        {
            $imc=round($poids_prec/pow($taille_prec/100, 2),1);

            if($imc<25)
            {
                $tpat['tot'][1]=$tpat['tot'][1]+1;
                $tpat[$cab_prec][1]=$tpat[$cab_prec][1]+1;

                if((strcasecmp($cab_prec, "chatillon")==0)||(strcasecmp($cab_prec, "argenton")==0)||(strcasecmp($cab_prec, 'brioux')==0)||
                    (strcasecmp($cab_prec, "niort")==0)||(strcasecmp($cab_prec, "saint-varent")==0))
                {
                    $tpat['eval'][1]=$tpat['eval'][1]+1;
                }
                elseif((strcasecmp($cab_prec, "lucquin")==0)||(strcasecmp($cab_prec, "dominault")==0)||(strcasecmp($cab_prec, 'paquereau')==0)||
                    (strcasecmp($cab_prec, "chizé")==0))
                {
                    $tpat['eval2'][1]=$tpat['eval2'][1]+1;
                }
                elseif((strcasecmp($cab_prec, "couture")==0)||(strcasecmp($cab_prec, "lezay")==0)||(strcasecmp($cab_prec, 'lezay2')==0)||
                    (strcasecmp($cab_prec, 'chef-boutonne1')==0)||(strcasecmp($cab_prec, 'chef-boutonne2')==0)||
                    (strcasecmp($cab_prec, 'bouille')==0)||(strcasecmp($cab_prec, 'la-mothe')==0)||
                    (strcasecmp($cab_prec, 'frontenay')==0)||(strcasecmp($cab_prec, "mauzé")==0))
                {
                    $tpat['eval3'][1]=$tpat['eval3'][1]+1;
                }
            }
            elseif(($imc>=25)&&($imc<30))
            {
                $tpat['tot'][2]=$tpat['tot'][2]+1;
                $tpat[$cab_prec][2]=$tpat[$cab_prec][2]+1;

                if((strcasecmp($cab_prec, "chatillon")==0)||(strcasecmp($cab_prec, "argenton")==0)||(strcasecmp($cab_prec, 'brioux')==0)||
                    (strcasecmp($cab_prec, "niort")==0)||(strcasecmp($cab_prec, "saint-varent")==0))
                {
                    $tpat['eval'][2]=$tpat['eval'][2]+1;
                }
                elseif((strcasecmp($cab_prec, "lucquin")==0)||(strcasecmp($cab_prec, "dominault")==0)||(strcasecmp($cab_prec, 'paquereau')==0)||
                    (strcasecmp($cab_prec, "chizé")==0))
                {
                    $tpat['eval2'][2]=$tpat['eval2'][2]+1;
                }
                elseif((strcasecmp($cab_prec, "couture")==0)||(strcasecmp($cab_prec, "lezay")==0)||(strcasecmp($cab_prec, 'lezay2')==0)||
                    (strcasecmp($cab_prec, 'chef-boutonne1')==0)||(strcasecmp($cab_prec, 'chef-boutonne2')==0)||
                    (strcasecmp($cab_prec, 'bouille')==0)||(strcasecmp($cab_prec, 'la-mothe')==0)||
                    (strcasecmp($cab_prec, 'frontenay')==0)||(strcasecmp($cab_prec, "mauzé")==0))
                {
                    $tpat['eval3'][2]=$tpat['eval3'][2]+1;
                }
            }
            elseif(($imc>=30)&&($imc<40))
            {
                $tpat['tot'][3]=$tpat['tot'][3]+1;
                $tpat[$cab_prec][3]=$tpat[$cab_prec][3]+1;

                if((strcasecmp($cab_prec, "chatillon")==0)||(strcasecmp($cab_prec, "argenton")==0)||(strcasecmp($cab_prec, 'brioux')==0)||
                    (strcasecmp($cab_prec, "niort")==0)||(strcasecmp($cab_prec, "saint-varent")==0))
                {
                    $tpat['eval'][3]=$tpat['eval'][3]+1;
                }
                elseif((strcasecmp($cab_prec, "lucquin")==0)||(strcasecmp($cab_prec, "dominault")==0)||(strcasecmp($cab_prec, 'paquereau')==0)||
                    (strcasecmp($cab_prec, "chizé")==0))
                {
                    $tpat['eval2'][3]=$tpat['eval2'][3]+1;
                }
                elseif((strcasecmp($cab_prec, "couture")==0)||(strcasecmp($cab_prec, "lezay")==0)||(strcasecmp($cab_prec, 'lezay2')==0)||
                    (strcasecmp($cab_prec, 'chef-boutonne1')==0)||(strcasecmp($cab_prec, 'chef-boutonne2')==0)||
                    (strcasecmp($cab_prec, 'bouille')==0)||(strcasecmp($cab_prec, 'la-mothe')==0)||
                    (strcasecmp($cab_prec, 'frontenay')==0)||(strcasecmp($cab_prec, "mauzé")==0))
                {
                    $tpat['eval3'][3]=$tpat['eval3'][3]+1;
                }
            }
            else
            {
                $tpat['tot'][4]=$tpat['tot'][4]+1;
                $tpat[$cab_prec][4]=$tpat[$cab_prec][4]+1;

                if((strcasecmp($cab_prec, "chatillon")==0)||(strcasecmp($cab_prec, "argenton")==0)||(strcasecmp($cab_prec, 'brioux')==0)||
                    (strcasecmp($cab_prec, "niort")==0)||(strcasecmp($cab_prec, "saint-varent")==0))
                {
                    $tpat['eval'][4]=$tpat['eval'][4]+1;
                }
                elseif((strcasecmp($cab_prec, "lucquin")==0)||(strcasecmp($cab_prec, "dominault")==0)||(strcasecmp($cab_prec, 'paquereau')==0)||
                    (strcasecmp($cab_prec, "chizé")==0))
                {
                    $tpat['eval2'][4]=$tpat['eval2'][4]+1;
                }
                elseif((strcasecmp($cab_prec, "couture")==0)||(strcasecmp($cab_prec, "lezay")==0)||(strcasecmp($cab_prec, 'lezay2')==0)||
                    (strcasecmp($cab_prec, 'chef-boutonne1')==0)||(strcasecmp($cab_prec, 'chef-boutonne2')==0)||
                    (strcasecmp($cab_prec, 'bouille')==0)||(strcasecmp($cab_prec, 'la-mothe')==0)||
                    (strcasecmp($cab_prec, 'frontenay')==0)||(strcasecmp($cab_prec, "mauzé")==0))
                {
                    $tpat['eval3'][4]=$tpat['eval3'][4]+1;
                }
            }
        }

        $total['tot']=$total['tot']+1;
        $total[$cab_prec]=$total[$cab_prec]+1;


        if((strcasecmp($cab_prec, "chatillon")==0)||(strcasecmp($cab_prec, "argenton")==0)||(strcasecmp($cab_prec, 'brioux')==0)||
            (strcasecmp($cab_prec, "niort")==0)||(strcasecmp($cab_prec, "saint-varent")==0))
        {
            $tpat['eval'][1]=$tpat['eval'][1]+1;
        }
        elseif((strcasecmp($cab_prec, "lucquin")==0)||(strcasecmp($cab_prec, "dominault")==0)||(strcasecmp($cab_prec, 'paquereau')==0)||
            (strcasecmp($cab_prec, "chizé")==0))
        {
            $tpat['eval2'][1]=$tpat['eval2'][1]+1;
        }
        elseif((strcasecmp($cab_prec, "couture")==0)||(strcasecmp($cab_prec, "lezay")==0)||(strcasecmp($cab_prec, 'lezay2')==0)||
            (strcasecmp($cab_prec, 'chef-boutonne1')==0)||(strcasecmp($cab_prec, 'chef-boutonne2')==0)||
            (strcasecmp($cab_prec, 'bouille')==0)||(strcasecmp($cab_prec, 'la-mothe')==0)||
            (strcasecmp($cab_prec, 'frontenay')==0)||(strcasecmp($cab_prec, "mauzé")==0))
        {
            $tpat['eval2'][1]=$tpat['eval2'][1]+1;
        }


        ?>

        <tr>
            <td>IMC (kg/m&sup2;)</td>
            <td align="center"><b>Proportion de patients</b></td>
            <td align="center"><b>moyenne eval</b></td>
            <td align="center"><b>moyenne cab 2005</b></td>
            <td align="center"><b>moyenne cab 2006</b></td>
            <?php
            foreach ($tcabinet as $cab)
            {
                ?>
                <td align="center"><b><?php echo $tville[$cab];?></b></td>
                <?php

            }
            ?>
        </tr>

        <?php

        $intitule=array('Taille non saisie<sup>1</sup>', '<25<sup>2</Sup>', '[25 - 30[<sup>3</sup>', '[30 - 40[<sup>4</sup>', '>40<sup>5</Sup>');


        for($i=0; $i<=4; $i++)
        {
            ?>
            <tr>
                <td><?php echo $intitule[$i]; ?></td>
                <td align="right"><?php echo round($tpat['tot'][$i]/$total['tot']*100);?>%</Td>
                <td align="right"><?php echo round($tpat['eval'][$i]/$total['eval']*100);?>%</Td>
                <td align="right"><?php echo round($tpat['eval2'][$i]/$total['eval2']*100);?>%</Td>
                <td align="right"><?php echo round($tpat['eval3'][$i]/$total['eval3']*100);?>%</Td>

                <?php
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

        <?php

        $intitule2=array('nb Taille non saisie<sup>6</sup>', 'nb <25<sup>7</Sup>', 'nb [25 - 30[<sup>8</sup>', 'nb [30 - 40[<sup>9</sup>', 'nb >40<sup>10</Sup>');


        for($i=0; $i<=4; $i++)
        {
            ?>
            <tr>
                <td><?php echo $intitule2[$i]; ?></td>
                <td align="right"><?php echo $tpat['tot'][$i];?></Td>
                <td align="right"><?php echo $tpat['eval'][$i];?></Td>
                <td align="right"><?php echo $tpat['eval2'][$i];?></Td>
                <td align="right"><?php echo $tpat['eval3'][$i];?></Td>

                <?php
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

    <sup>1</sup>Proportion de patients ayant eu au moins un suivi diabète et dont la taille n'est pas saisie<br>
    <sup>2</sup>Proportion de patients ayant eu au moins un suivi diabète et dont le dernier IMC est < 25 <br>
    <sup>3</sup>Proportion de patients ayant eu au moins un suivi diabète et dont le dernier IMC est compris entre 25 et 30<br>
    <sup>4</sup>Proportion de patients ayant eu au moins un suivi diabète et dont le dernier IMC est compris entre 30 et 40<br>
    <sup>5</sup>Proportion de patients ayant eu au moins un suivi diabète et dont le dernier IMC est compris >40
    <sup>6</sup>Nb de patients ayant eu au moins un suivi diabète et dont la taille n'est pas saisie<br>
    <sup>7</sup>Nb de patients ayant eu au moins un suivi diabète et dont le dernier IMC est < 25 <br>
    <sup>8</sup>Nb de patients ayant eu au moins un suivi diabète et dont le dernier IMC est compris entre 25 et 30<br>
    <sup>9</sup>Nb de patients ayant eu au moins un suivi diabète et dont le dernier IMC est compris entre 30 et 40<br>
    <sup>10</sup>Nb de patients ayant eu au moins un suivi diabète et dont le dernier IMC est compris >40
    <?php

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

        //taux diab 2 suivis dans asalée
        $req="SELECT cabinet, id, dsuivi, poids, taille ".
            "FROM suivi_diabete, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "AND poids>0 ".
            "AND dsuivi<='$date' ".
            "ORDER BY cabinet, id, dsuivi ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

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

        $tpat['eval'][0]=0;
        $tpat['eval'][1]=0;
        $tpat['eval'][2]=0;
        $tpat['eval'][3]=0;
        $tpat['eval'][4]=0;

        $tpat['eval2'][0]=0;
        $tpat['eval2'][1]=0;
        $tpat['eval2'][2]=0;
        $tpat['eval2'][3]=0;
        $tpat['eval2'][4]=0;

        $tpat['eval3'][0]=0;
        $tpat['eval3'][1]=0;
        $tpat['eval3'][2]=0;
        $tpat['eval3'][3]=0;
        $tpat['eval3'][4]=0;


        $total['tot']=0;
        $total['eval']=0;
        $total['eval2']=0;
        $total['eval3']=0;

        $id_prec='';

        while(list($cab, $id, $dsuivi, $poids, $taille) = mysql_fetch_row($res)) {
            if($id_prec!=$id)
            {

                if($id_prec!='')
                {
                    if(is_null($taille_prec) or !is_numeric($taille_prec) or $taille_prec == 0)
                    {
                        $tpat['tot'][0]=$tpat['tot'][0]+1;
                        $tpat[$cab_prec][0]=$tpat[$cab_prec][0]+1;

                        if((strcasecmp($cab_prec, "chatillon")==0)||(strcasecmp($cab_prec, "argenton")==0)||(strcasecmp($cab_prec, 'brioux')==0)||
                            (strcasecmp($cab_prec, "niort")==0)||(strcasecmp($cab_prec, "saint-varent")==0))
                        {
                            $tpat['eval'][0]=$tpat['eval'][0]+1;
                        }
                        elseif((strcasecmp($cab_prec, "lucquin")==0)||(strcasecmp($cab_prec, "dominault")==0)||(strcasecmp($cab_prec, 'paquereau')==0)||
                            (strcasecmp($cab_prec, "chizé")==0))
                        {
                            $tpat['eval2'][0]=$tpat['eval2'][0]+1;
                        }
                        elseif((strcasecmp($cab_prec, "couture")==0)||(strcasecmp($cab_prec, "lezay")==0)||(strcasecmp($cab_prec, 'lezay2')==0)||
                            (strcasecmp($cab_prec, 'chef-boutonne1')==0)||(strcasecmp($cab_prec, 'chef-boutonne2')==0)||
                            (strcasecmp($cab_prec, 'bouille')==0)||(strcasecmp($cab_prec, 'la-mothe')==0)||
                            (strcasecmp($cab_prec, 'frontenay')==0)||(strcasecmp($cab_prec, "mauzé")==0))
                        {
                            $tpat['eval3'][0]=$tpat['eval3'][0]+1;
                        }
                    }
                    else
                    {
                        $imc=round($poids_prec/pow($taille_prec/100, 2),1);

                        if($imc<25)
                        {
                            $tpat['tot'][1]=$tpat['tot'][1]+1;
                            $tpat[$cab_prec][1]=$tpat[$cab_prec][1]+1;

                            if((strcasecmp($cab_prec, "chatillon")==0)||(strcasecmp($cab_prec, "argenton")==0)||(strcasecmp($cab_prec, 'brioux')==0)||
                                (strcasecmp($cab_prec, "niort")==0)||(strcasecmp($cab_prec, "saint-varent")==0))
                            {
                                $tpat['eval'][1]=$tpat['eval'][1]+1;
                            }
                            elseif((strcasecmp($cab_prec, "lucquin")==0)||(strcasecmp($cab_prec, "dominault")==0)||(strcasecmp($cab_prec, 'paquereau')==0)||
                                (strcasecmp($cab_prec, "chizé")==0))
                            {
                                $tpat['eval2'][1]=$tpat['eval2'][1]+1;
                            }
                            elseif((strcasecmp($cab_prec, "couture")==0)||(strcasecmp($cab_prec, "lezay")==0)||(strcasecmp($cab_prec, 'lezay2')==0)||
                                (strcasecmp($cab_prec, 'chef-boutonne1')==0)||(strcasecmp($cab_prec, 'chef-boutonne2')==0)||
                                (strcasecmp($cab_prec, 'bouille')==0)||(strcasecmp($cab_prec, 'la-mothe')==0)||
                                (strcasecmp($cab_prec, 'frontenay')==0)||(strcasecmp($cab_prec, "mauzé")==0))
                            {
                                $tpat['eval3'][1]=$tpat['eval3'][1]+1;
                            }
                        }
                        elseif(($imc>=25)&&($imc<30))
                        {
                            $tpat['tot'][2]=$tpat['tot'][2]+1;
                            $tpat[$cab_prec][2]=$tpat[$cab_prec][2]+1;

                            if((strcasecmp($cab_prec, "chatillon")==0)||(strcasecmp($cab_prec, "argenton")==0)||(strcasecmp($cab_prec, 'brioux')==0)||
                                (strcasecmp($cab_prec, "niort")==0)||(strcasecmp($cab_prec, "saint-varent")==0))
                            {
                                $tpat['eval'][2]=$tpat['eval'][2]+1;
                            }
                            elseif((strcasecmp($cab_prec, "lucquin")==0)||(strcasecmp($cab_prec, "dominault")==0)||(strcasecmp($cab_prec, 'paquereau')==0)||
                                (strcasecmp($cab_prec, "chizé")==0))
                            {
                                $tpat['eval2'][2]=$tpat['eval2'][2]+1;
                            }
                            elseif((strcasecmp($cab_prec, "couture")==0)||(strcasecmp($cab_prec, "lezay")==0)||(strcasecmp($cab_prec, 'lezay2')==0)||
                                (strcasecmp($cab_prec, 'chef-boutonne1')==0)||(strcasecmp($cab_prec, 'chef-boutonne2')==0)||
                                (strcasecmp($cab_prec, 'bouille')==0)||(strcasecmp($cab_prec, 'la-mothe')==0)||
                                (strcasecmp($cab_prec, 'frontenay')==0)||(strcasecmp($cab_prec, "mauzé")==0))
                            {
                                $tpat['eval3'][2]=$tpat['eval3'][2]+1;
                            }
                        }
                        elseif(($imc>=30)&&($imc<40))
                        {
                            $tpat['tot'][3]=$tpat['tot'][3]+1;
                            $tpat[$cab_prec][3]=$tpat[$cab_prec][3]+1;

                            if((strcasecmp($cab_prec, "chatillon")==0)||(strcasecmp($cab_prec, "argenton")==0)||(strcasecmp($cab_prec, 'brioux')==0)||
                                (strcasecmp($cab_prec, "niort")==0)||(strcasecmp($cab_prec, "saint-varent")==0))
                            {
                                $tpat['eval'][3]=$tpat['eval'][3]+1;
                            }
                            elseif((strcasecmp($cab_prec, "lucquin")==0)||(strcasecmp($cab_prec, "dominault")==0)||(strcasecmp($cab_prec, 'paquereau')==0)||
                                (strcasecmp($cab_prec, "chizé")==0))
                            {
                                $tpat['eval2'][3]=$tpat['eval2'][3]+1;
                            }
                            elseif((strcasecmp($cab_prec, "couture")==0)||(strcasecmp($cab_prec, "lezay")==0)||(strcasecmp($cab_prec, 'lezay2')==0)||
                                (strcasecmp($cab_prec, 'chef-boutonne1')==0)||(strcasecmp($cab_prec, 'chef-boutonne2')==0)||
                                (strcasecmp($cab_prec, 'bouille')==0)||(strcasecmp($cab_prec, 'la-mothe')==0)||
                                (strcasecmp($cab_prec, 'frontenay')==0)||(strcasecmp($cab_prec, "mauzé")==0))
                            {
                                $tpat['eval3'][3]=$tpat['eval3'][3]+1;
                            }
                        }
                        else
                        {
                            $tpat['tot'][4]=$tpat['tot'][4]+1;
                            $tpat[$cab_prec][4]=$tpat[$cab_prec][4]+1;

                            if((strcasecmp($cab_prec, "chatillon")==0)||(strcasecmp($cab_prec, "argenton")==0)||(strcasecmp($cab_prec, 'brioux')==0)||
                                (strcasecmp($cab_prec, "niort")==0)||(strcasecmp($cab_prec, "saint-varent")==0))
                            {
                                $tpat['eval'][4]=$tpat['eval'][4]+1;
                            }
                            elseif((strcasecmp($cab_prec, "lucquin")==0)||(strcasecmp($cab_prec, "dominault")==0)||(strcasecmp($cab_prec, 'paquereau')==0)||
                                (strcasecmp($cab_prec, "chizé")==0))
                            {
                                $tpat['eval2'][4]=$tpat['eval2'][4]+1;
                            }
                            elseif((strcasecmp($cab_prec, "couture")==0)||(strcasecmp($cab_prec, "lezay")==0)||(strcasecmp($cab_prec, 'lezay2')==0)||
                                (strcasecmp($cab_prec, 'chef-boutonne1')==0)||(strcasecmp($cab_prec, 'chef-boutonne2')==0)||
                                (strcasecmp($cab_prec, 'bouille')==0)||(strcasecmp($cab_prec, 'la-mothe')==0)||
                                (strcasecmp($cab_prec, 'frontenay')==0)||(strcasecmp($cab_prec, "mauzé")==0))
                            {
                                $tpat['eval3'][4]=$tpat['eval3'][4]+1;
                            }
                        }
                    }

                    $total['tot']=$total['tot']+1;
                    $total[$cab_prec]=$total[$cab_prec]+1;

                    if((strcasecmp($cab_prec, "chatillon")==0)||(strcasecmp($cab_prec, "argenton")==0)||(strcasecmp($cab_prec, 'brioux')==0)||
                        (strcasecmp($cab_prec, "niort")==0)||(strcasecmp($cab_prec, "saint-varent")==0))
                    {
                        $total['eval']=$total['eval']+1;
                    }
                    elseif((strcasecmp($cab_prec, "lucquin")==0)||(strcasecmp($cab_prec, "dominault")==0)||(strcasecmp($cab_prec, 'paquereau')==0)||
                        (strcasecmp($cab_prec, "chizé")==0))
                    {
                        $total['eval2']=$total['eval2']+1;
                    }
                    elseif((strcasecmp($cab_prec, "couture")==0)||(strcasecmp($cab_prec, "lezay")==0)||(strcasecmp($cab_prec, 'lezay2')==0)||
                        (strcasecmp($cab_prec, 'chef-boutonne1')==0)||(strcasecmp($cab_prec, 'chef-boutonne2')==0)||
                        (strcasecmp($cab_prec, 'bouille')==0)||(strcasecmp($cab_prec, 'la-mothe')==0)||
                        (strcasecmp($cab_prec, 'frontenay')==0)||(strcasecmp($cab_prec, "mauzé")==0))
                    {
                        $total['eval3']=$total['eval3']+1;
                    }

                    $cab_prec=$cab;
                    $poids_prec=$poids;
                    $taille_prec=$taille;
                    $id_prec=$id;


                }
                else
                {
                    $id_prec=$id;
                    $poids_prec=$poids;
                    $taille_prec=$taille;
                    $cab_prec=$cab;

                }

            }
            else
            {
                if($poids_prec!=0){
                    $poids_prec=$poids;
                }
                $taille_prec=$taille;
                $cab_prec=$cab;
            }


        }

        if(is_null($taille_prec) or !is_numeric($taille_prec) or $taille_prec == 0)
        {
            $tpat['tot'][0]=$tpat['tot'][0]+1;
            $tpat[$cab_prec][0]=$tpat[$cab_prec][0]+1;

            if((strcasecmp($cab_prec, "chatillon")==0)||(strcasecmp($cab_prec, "argenton")==0)||(strcasecmp($cab_prec, 'brioux')==0)||
                (strcasecmp($cab_prec, "niort")==0)||(strcasecmp($cab_prec, "saint-varent")==0))
            {
                $tpat['eval'][0]=$tpat['eval'][0]+1;
            }
            elseif((strcasecmp($cab_prec, "lucquin")==0)||(strcasecmp($cab_prec, "dominault")==0)||(strcasecmp($cab_prec, 'paquereau')==0)||
                (strcasecmp($cab_prec, "chizé")==0))
            {
                $tpat['eval2'][0]=$tpat['eval2'][0]+1;
            }
            elseif((strcasecmp($cab_prec, "couture")==0)||(strcasecmp($cab_prec, "lezay")==0)||(strcasecmp($cab_prec, 'lezay2')==0)||
                (strcasecmp($cab_prec, 'chef-boutonne1')==0)||(strcasecmp($cab_prec, 'chef-boutonne2')==0)||
                (strcasecmp($cab_prec, 'bouille')==0)||(strcasecmp($cab_prec, 'la-mothe')==0)||
                (strcasecmp($cab_prec, 'frontenay')==0)||(strcasecmp($cab_prec, "mauzé")==0))
            {
                $tpat['eval3'][0]=$tpat['eval3'][0]+1;
            }
        }
        else
        {
            $imc=round($poids_prec/pow($taille_prec/100, 2),1);

            if($imc<25)
            {
                $tpat['tot'][1]=$tpat['tot'][1]+1;
                $tpat[$cab_prec][1]=$tpat[$cab_prec][1]+1;

                if((strcasecmp($cab_prec, "chatillon")==0)||(strcasecmp($cab_prec, "argenton")==0)||(strcasecmp($cab_prec, 'brioux')==0)||
                    (strcasecmp($cab_prec, "niort")==0)||(strcasecmp($cab_prec, "saint-varent")==0))
                {
                    $tpat['eval'][1]=$tpat['eval'][1]+1;
                }
                elseif((strcasecmp($cab_prec, "lucquin")==0)||(strcasecmp($cab_prec, "dominault")==0)||(strcasecmp($cab_prec, 'paquereau')==0)||
                    (strcasecmp($cab_prec, "chizé")==0))
                {
                    $tpat['eval2'][1]=$tpat['eval2'][1]+1;
                }
                elseif((strcasecmp($cab_prec, "couture")==0)||(strcasecmp($cab_prec, "lezay")==0)||(strcasecmp($cab_prec, 'lezay2')==0)||
                    (strcasecmp($cab_prec, 'chef-boutonne1')==0)||(strcasecmp($cab_prec, 'chef-boutonne2')==0)||
                    (strcasecmp($cab_prec, 'bouille')==0)||(strcasecmp($cab_prec, 'la-mothe')==0)||
                    (strcasecmp($cab_prec, 'frontenay')==0)||(strcasecmp($cab_prec, "mauzé")==0))
                {
                    $tpat['eval3'][1]=$tpat['eval3'][1]+1;
                }
            }
            elseif(($imc>=25)&&($imc<30))
            {
                $tpat['tot'][2]=$tpat['tot'][2]+1;
                $tpat[$cab_prec][2]=$tpat[$cab_prec][2]+1;

                if((strcasecmp($cab_prec, "chatillon")==0)||(strcasecmp($cab_prec, "argenton")==0)||(strcasecmp($cab_prec, 'brioux')==0)||
                    (strcasecmp($cab_prec, "niort")==0)||(strcasecmp($cab_prec, "saint-varent")==0))
                {
                    $tpat['eval'][2]=$tpat['eval'][2]+1;
                }
                elseif((strcasecmp($cab_prec, "lucquin")==0)||(strcasecmp($cab_prec, "dominault")==0)||(strcasecmp($cab_prec, 'paquereau')==0)||
                    (strcasecmp($cab_prec, "chizé")==0))
                {
                    $tpat['eval2'][2]=$tpat['eval2'][2]+1;
                }
                elseif((strcasecmp($cab_prec, "couture")==0)||(strcasecmp($cab_prec, "lezay")==0)||(strcasecmp($cab_prec, 'lezay2')==0)||
                    (strcasecmp($cab_prec, 'chef-boutonne1')==0)||(strcasecmp($cab_prec, 'chef-boutonne2')==0)||
                    (strcasecmp($cab_prec, 'bouille')==0)||(strcasecmp($cab_prec, 'la-mothe')==0)||
                    (strcasecmp($cab_prec, 'frontenay')==0)||(strcasecmp($cab_prec, "mauzé")==0))
                {
                    $tpat['eval3'][2]=$tpat['eval3'][2]+1;
                }
            }
            elseif(($imc>=30)&&($imc<40))
            {
                $tpat['tot'][3]=$tpat['tot'][3]+1;
                $tpat[$cab_prec][3]=$tpat[$cab_prec][3]+1;

                if((strcasecmp($cab_prec, "chatillon")==0)||(strcasecmp($cab_prec, "argenton")==0)||(strcasecmp($cab_prec, 'brioux')==0)||
                    (strcasecmp($cab_prec, "niort")==0)||(strcasecmp($cab_prec, "saint-varent")==0))
                {
                    $tpat['eval'][3]=$tpat['eval'][3]+1;
                }
                elseif((strcasecmp($cab_prec, "lucquin")==0)||(strcasecmp($cab_prec, "dominault")==0)||(strcasecmp($cab_prec, 'paquereau')==0)||
                    (strcasecmp($cab_prec, "chizé")==0))
                {
                    $tpat['eval2'][3]=$tpat['eval2'][3]+1;
                }
                elseif((strcasecmp($cab_prec, "couture")==0)||(strcasecmp($cab_prec, "lezay")==0)||(strcasecmp($cab_prec, 'lezay2')==0)||
                    (strcasecmp($cab_prec, 'chef-boutonne1')==0)||(strcasecmp($cab_prec, 'chef-boutonne2')==0)||
                    (strcasecmp($cab_prec, 'bouille')==0)||(strcasecmp($cab_prec, 'la-mothe')==0)||
                    (strcasecmp($cab_prec, 'frontenay')==0)||(strcasecmp($cab_prec, "mauzé")==0))
                {
                    $tpat['eval3'][3]=$tpat['eval3'][3]+1;
                }
            }
            else
            {
                $tpat['tot'][4]=$tpat['tot'][4]+1;
                $tpat[$cab_prec][4]=$tpat[$cab_prec][4]+1;

                if((strcasecmp($cab_prec, "chatillon")==0)||(strcasecmp($cab_prec, "argenton")==0)||(strcasecmp($cab_prec, 'brioux')==0)||
                    (strcasecmp($cab_prec, "niort")==0)||(strcasecmp($cab_prec, "saint-varent")==0))
                {
                    $tpat['eval'][4]=$tpat['eval'][4]+1;
                }
                elseif((strcasecmp($cab_prec, "lucquin")==0)||(strcasecmp($cab_prec, "dominault")==0)||(strcasecmp($cab_prec, 'paquereau')==0)||
                    (strcasecmp($cab_prec, "chizé")==0))
                {
                    $tpat['eval2'][4]=$tpat['eval2'][4]+1;
                }
                elseif((strcasecmp($cab_prec, "couture")==0)||(strcasecmp($cab_prec, "lezay")==0)||(strcasecmp($cab_prec, 'lezay2')==0)||
                    (strcasecmp($cab_prec, 'chef-boutonne1')==0)||(strcasecmp($cab_prec, 'chef-boutonne2')==0)||
                    (strcasecmp($cab_prec, 'bouille')==0)||(strcasecmp($cab_prec, 'la-mothe')==0)||
                    (strcasecmp($cab_prec, 'frontenay')==0)||(strcasecmp($cab_prec, "mauzé")==0))
                {
                    $tpat['eval3'][4]=$tpat['eval3'][4]+1;
                }
            }
        }

        $total['tot']=$total['tot']+1;
        $total[$cab_prec]=$total[$cab_prec]+1;


        if((strcasecmp($cab_prec, "chatillon")==0)||(strcasecmp($cab_prec, "argenton")==0)||(strcasecmp($cab_prec, 'brioux')==0)||
            (strcasecmp($cab_prec, "niort")==0)||(strcasecmp($cab_prec, "saint-varent")==0))
        {
            $total['eval']=$total['eval']+1;
        }
        elseif((strcasecmp($cab_prec, "lucquin")==0)||(strcasecmp($cab_prec, "dominault")==0)||(strcasecmp($cab_prec, 'paquereau')==0)||
            (strcasecmp($cab_prec, "chizé")==0))
        {
            $total['eval2']=$total['eval2']+1;
        }
        elseif((strcasecmp($cab_prec, "couture")==0)||(strcasecmp($cab_prec, "lezay")==0)||(strcasecmp($cab_prec, 'lezay2')==0)||
            (strcasecmp($cab_prec, 'chef-boutonne1')==0)||(strcasecmp($cab_prec, 'chef-boutonne2')==0)||
            (strcasecmp($cab_prec, 'bouille')==0)||(strcasecmp($cab_prec, 'la-mothe')==0)||
            (strcasecmp($cab_prec, 'frontenay')==0)||(strcasecmp($cab_prec, "mauzé")==0))
        {
            $total['eval3']=$total['eval3']+1;
        }

        ?>

        <tr>
            <td>IMC (kg/m&sup2;)</td>
            <td align="center"><b>Proportion de patients</b></td>
            <td align="center"><b>Moyenne eval</b></td>
            <td align="center"><b>Moyenne cab 2005</b></td>
            <td align="center"><b>Moyenne cab 2006</b></td>
            <?php
            foreach ($tcabinet as $cab)
            {
                ?>
                <td align="center"><b><?php echo $tville[$cab];?></b></td>
                <?php

            }
            ?>
        </tr>


        <?php

        $intitule=array('Taille non saisie<sup>1</sup>', '<25<sup>2</Sup>', '[25 - 30[<sup>3</sup>', '[30 - 40[<sup>4</sup>', '>40<sup>5</sup>');


        for($i=0; $i<=4; $i++)
        {
            ?>
            <tr>
                <td><?php echo $intitule[$i]; ?></td>
                <td align="right"><?php echo round($tpat['tot'][$i]/$total['tot']*100);?>%</Td>
                <td align="right"><?php echo round($tpat['eval'][$i]/$total['eval']*100);?>%</Td>
                <td align="right"><?php if($total['eval2']==0) echo "ND"; else echo round($tpat['eval2'][$i]/$total['eval2']*100);?>%</Td>
                <td align="right"><?php if($total['eval3']==0) echo "ND"; else echo round($tpat['eval3'][$i]/$total['eval3']*100);?>%</Td>

                <?php
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






        <?php

        $intitule2=array('Nb Taille non saisie<sup>6</sup>', 'Nb <25<sup>7</Sup>', 'Nb [25 - 30[<sup>8</sup>', 'Nb [30 - 40[<sup>9</sup>', 'Nb >40<sup>10</sup>');


        for($i=0; $i<=4; $i++)
        {
            ?>
            <tr>
                <td><?php echo $intitule2[$i]; ?></td>
                <td align="right"><?php echo $tpat['tot'][$i];?></Td>
                <td align="right"><?php echo $tpat['eval'][$i];?></Td>
                <td align="right"><?php echo $tpat['eval2'][$i];?></Td>
                <td align="right"><?php echo $tpat['eval3'][$i];?></Td>

                <?php
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
