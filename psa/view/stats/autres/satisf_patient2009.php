<?php
session_start();

if(!isset($_SESSION['nom'])&&(!isset($_SESSION["account"]))) {
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
    <title>Statistiques questionnaire satisfaction patient 2009</title>
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

entete_asalee("Statistiques questionnaire satisfaction patient réalisé en 2009");
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
<font face='times new roman'>Indicateurs d'évaluation Asalée : nombre de patients vus en consultation</font></i>";
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
global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_tot;

$req="SELECT demande_consult, motif_diabete, motif_depistage, motif_automesure, motif_autre, motif_rcva, motif_hemoccult, conseils_alimentaires, ".
    "adapter_vie_sante, conseils_realisables, compris_conseils, qualite_conseils, repondu_questions, informations_ignorees, ".
    "temps_ecoute, aise, satisf_consult, suivi_conseils, concerne_sante, revoir_inf  FROM satisf_patient2009 ";

$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

$num_quest=mysql_num_rows($res);

$nb_consult['medecin']=$nb_consult['patient']=$nb_consult['rien']=$motif_diab=$motif_dep=$motif_auto=0;
$mot_autre=$motif_rien=$conseil_alim[0]=$conseil_alim[1]=$conseil_alim[2]=$conseil_alim[3]=$conseil_alim[4]=0;
$conseil_alim[5]=$conseil_alim[6]=$adapt_vie[0]=$adapt_vie[1]=$adapt_vie[2]=$adapt_vie[3]=$adapt_vie[4]=0;
$adapt_vie[5]=$adapt_vie[6]=$conseil_real[0]=$conseil_real[1]=$conseil_real[2]=$conseil_real[3]=$conseil_real[4]=0;
$conseil_real[5]=$conseil_real[6]=$conseil_compris[0]=$conseil_compris[1]=$conseil_compris[2]=$conseil_compris[3]=$conseil_compris[4]=0;
$conseil_compris[5]=$conseil_compris[6]=$qual_conseil[0]=$qual_conseil[1]=$qual_conseil[2]=$qual_conseil[3]=$qual_conseil[4]=0;
$qual_conseil[5]=$qual_conseil[6]=$rep_quest[0]=$rep_quest[1]=$rep_quest[2]=$rep_quest[3]=$rep_quest[4]=0;
$rep_quest[5]=$info[0]=$info[1]=$info[2]=$info[3]=$info[4]=0;
$info[5]=$ecoute[0]=$ecoute[1]=$ecoute[2]=$ecoute[3]=$ecoute[4]=$ecoute[5]=0;
$aiser[0]=$aiser[1]=$aiser[2]=$aiser[3]=$aiser[4]=$aiser[5]=0;
$satisf[0]=$satisf[1]=$satisf[2]=$satisf[3]=$satisf[4]=$satisf[5]=0;
$suivi[0]=$suivi[1]=$suivi[2]=$suivi[3]=$suivi[4]=$suivi[5]=0;
$concerne[0]=$concerne[1]=$concerne[2]=$concerne[3]=$concerne[4]=$concerne[5]=0;
$revoir[0]=$revoir[1]=$revoir[2]=$revoir[3]=$revoir[4]=$revoir[5]=0;
$mot_rcva=$mot_hemoccult=0;

while(list($demande_consult, $motif_diabete, $motif_depistage, $motif_automesure, $motif_autre, $motif_rcva, $motif_hemoccult, $conseils_alimentaires,
    $adapter_vie_sante, $conseils_realisables, $compris_conseils, $qualite_conseils, $repondu_questions,
    $informations_ignorees, $temps_ecoute, $aise, $satisf_consult, $suivi_conseils, $concerne_sante,
    $revoir_inf)=mysql_fetch_row($res))
{
    if($demande_consult=="medecin")
    {
        $nb_consult['medecin']++;
    }
    elseif($demande_consult=="patient")
    {
        $nb_consult['patient']++;
    }
    else
    {
        $nb_consult['rien']++;
    }

    if(($motif_diabete!=1)&&($motif_depistage!=1)&&($motif_automesure!=1)&&($motif_autre!=1)&&($motif_rcva!=1)&&($motif_hemoccult!=1))
    {
        $motif_rien++;
    }
    else
    {
        if($motif_diabete==1)
        {
            $motif_diab++;
        }
        if($motif_depistage==1)
        {
            $motif_dep++;
        }
        if($motif_automesure==1)
        {
            $motif_auto++;
        }
        if($motif_autre==1)
        {
            $mot_autre++;
        }
        if($motif_rcva==1)
        {
            $mot_rcva++;
        }
        if($motif_hemoccult==1)
        {
            $mot_hemoccult++;
        }
    }

    if(($conseils_alimentaires=='NULL')||($conseils_alimentaires==''))
    {
        $conseil_alim[0]=$conseil_alim[0]+1;
    }
    else
    {
        $conseil_alim[$conseils_alimentaires]=$conseil_alim[$conseils_alimentaires]+1;
    }

    if(($adapter_vie_sante=='NULL')||($adapter_vie_sante==''))
    {
        $adapt_vie[0]=$adapt_vie[0]+1;
    }
    else
    {
        $adapt_vie[$adapter_vie_sante]=$adapt_vie[$adapter_vie_sante]+1;
    }


    if(($conseils_realisables=='NULL')||($conseils_realisables==''))
    {
        $conseil_real[0]=$conseil_real[0]+1;
    }
    else
    {
        $conseil_real[$conseils_realisables]=$conseil_real[$conseils_realisables]+1;
    }


    if(($compris_conseils=='NULL')||($compris_conseils==''))
    {
        $conseil_compris[0]=$conseil_compris[0]+1;
    }
    else
    {
        $conseil_compris[$compris_conseils]=$conseil_compris[$compris_conseils]+1;
    }


    if(($qualite_conseils=='NULL')||($qualite_conseils==''))
    {
        $qual_conseil[0]=$qual_conseil[0]+1;
    }
    else
    {
        $qual_conseil[$qualite_conseils]=$qual_conseil[$qualite_conseils]+1;
    }


    if(($repondu_questions=='NULL')||($repondu_questions==''))
    {
        $rep_quest[0]=$rep_quest[0]+1;
    }
    else
    {
        $rep_quest[$repondu_questions]=$rep_quest[$repondu_questions]+1;
    }


    if(($informations_ignorees=='NULL')||($informations_ignorees==''))
    {
        $info[0]=$info[0]+1;
    }
    else
    {
        $info[$informations_ignorees]=$info[$informations_ignorees]+1;
    }


    if(($temps_ecoute=='NULL')||($temps_ecoute==''))
    {
        $ecoute[0]=$ecoute[0]+1;
    }
    else
    {
        $ecoute[$temps_ecoute]=$ecoute[$temps_ecoute]+1;
    }


    if(($aise=='NULL')||($aise==''))
    {
        $aiser[0]=$aiser[0]+1;
    }
    else
    {
        $aiser[$aise]=$aiser[$aise]+1;
    }


    if(($satisf_consult=='NULL')||($satisf_consult==''))
    {
        $satisf[0]=$satisf[0]+1;
    }
    else
    {
        $satisf[$satisf_consult]=$satisf[$satisf_consult]+1;
    }


    if(($suivi_conseils=='NULL')||($suivi_conseils==''))
    {
        $suivi[0]=$suivi[0]+1;
    }
    else
    {
        $suivi[$suivi_conseils]=$suivi[$suivi_conseils]+1;
    }



    if(($concerne_sante=='NULL')||($concerne_sante==''))
    {
        $concerne[0]=$concerne[0]+1;
    }
    else
    {
        $concerne[$concerne_sante]=$concerne[$concerne_sante]+1;
    }


    if(($revoir_inf=='NULL')||($revoir_inf==''))
    {
        $revoir[0]=$revoir[0]+1;
    }
    else
    {
        $revoir[$revoir_inf]=$revoir[$revoir_inf]+1;
    }
}


echo $num_quest." questionnaires renseignés";
?>
<table border=1>
    <tr>
        <td colspan="3">
            <b>Le patient à consulté : </b>
        </td>
    </tr>
    <td>Sur les conseils de son médecin traitant</td>
    <td>A sa demande</td>
    <td>Non renseigné</td>
    </tr>
    <tr>
        <td><?php echo number_format(round(100*$nb_consult['medecin']/$num_quest, 1), 1, ',', ' '); ?>%</td>
        <td><?php echo number_format(round(100*$nb_consult['patient']/$num_quest, 1), 1, ',', ' '); ?>%</td>
        <td><?php echo number_format(round(100*$nb_consult['rien']/$num_quest, 1), 1, ',', ' '); ?>%</td>
    </Tr>

</table>
<br><br>

<table border=1>
    <tr>
        <td colspan="2">
            <b>2- Pour quels motifs le patient a consulté l'infirmière</b>
        </td>
    </tr>
    <td width='30%'>Diabète</td>
    <td><?php echo number_format(round(100*$motif_diab/$num_quest, 1), 1, ',', ' '); ?>%</td>
    </tr>
    </tr>
    <td>Dépistage</td>
    <td><?php echo number_format(round(100*$motif_dep/$num_quest, 1), 1, ',', ' '); ?>%</td>
    </tr>
    </tr>
    <td>Automesure tensionnelle</td>
    <td><?php echo number_format(round(100*$motif_auto/$num_quest, 1), 1, ',', ' '); ?>%</td>
    </tr>
    </tr>
    <td>Autres tests</td>
    <td><?php echo number_format(round(100*$mot_autre/$num_quest, 1), 1, ',', ' '); ?>%</td>
    </tr>
    </tr>
    <td>RCVA</td>
    <td><?php echo number_format(round(100*$mot_rcva/$num_quest, 1), 1, ',', ' '); ?>%</td>
    </tr>
    </tr>
    <td>Explications test hémoccult</td>
    <td><?php echo number_format(round(100*$mot_rcva/$num_quest, 1), 1, ',', ' '); ?>%</td>
    </tr>
    </tr>
    <td>Non renseigné</td>
    <td><?php echo number_format(round(100*$motif_rien/$num_quest, 1), 1, ',', ' '); ?>%</td>
    </tr>
</table>
<br><br>


<b>Résultat aux questions</b>

<table border="1">
    <tr>
        <td>&nbsp;</td>
        <td>Moyenne /20</td>
        <td>Taux de couverture</Td>
    </tr>


    <?

    $texte['suivi']="Allez-vous suivre les conseils que l'infirmière vous a donnés ?";
    $texte['temps']="L'infirmière a pris tout son temps pour m'écouter";
    $texte['revoir']="Voulez-vous revoir l'infirmière ?";
    $texte['aise']="Je me suis senti parfaitement à l'aise avec l'infirmière";
    $texte['conseil']="La qualité des conseils qu'elle m'a donnés est très bonne";
    $texte['concerne']="Vous sentez-vous plus concerné par votre santé ?";
    $texte['satisfait']="Etes vous satisfait(e) de cette consultation ?";
    $texte['alim']="L'infirmière m'a donné des conseils alimentaires très satisfaisants";
    $texte['compris']="J'ai compris tous les conseils que l'infirmière m'a donné";
    $texte['mode_vie']="L'infirmière m'a donné des conseils très satisfaisants pour adapter mon mode de vie à ma santé";
    $texte['ignore']="L'infirmière m'a donné des informations que j'ignorais";
    $texte['question']="L'infirmière a répondu à toutes mes questions";
    $texte['realisable']="L'infirmière m'a donné des conseils parfaitement réalisables dans mon quotidien";




    $total_point=0;
    $total_rep=0;

    $total_pat=$num_quest*13;
    $moy_tmp=($suivi[1]+$suivi[2]*2+$suivi[3]*3+$suivi[4]*4)/($suivi[1]+$suivi[2]+$suivi[3]+$suivi[4])*5;
    $moyenne["$moy_tmp"]='suivi';
    $couverture['suivi']=number_format(round(($suivi[1]+$suivi[2]+$suivi[3]+$suivi[4])/$num_quest*100), 0, '', '');
    $moyenne2['suivi']=number_format(round(($suivi[1]+$suivi[2]*2+$suivi[3]*3+$suivi[4]*4)/($suivi[1]+$suivi[2]+$suivi[3]+$suivi[4])*5, 1), 1, ',', ' ');

    $total_point=$total_point+($suivi[1]+$suivi[2]*2+$suivi[3]*3+$suivi[4]*4)*5;
    $total_rep=$total_rep+$suivi[1]+$suivi[2]+$suivi[3]+$suivi[4];

    $moy_tmp=($ecoute[1]+$ecoute[2]*2+$ecoute[3]*3+$ecoute[4]*4+$ecoute[5]*5)/($ecoute[1]+$ecoute[2]+$ecoute[3]+$ecoute[4]+$ecoute[5])*4;

    while(isset($moyenne[$moy_tmp]))
    {
        $moy_tmp=$moy_tmp-0.0001;
    }

    $moyenne["$moy_tmp"]='temps';
    $couverture['temps']=number_format(round(($ecoute[1]+$ecoute[2]+$ecoute[3]+$ecoute[4]+$ecoute[5])/$num_quest*100), 0, '', '');
    $moyenne2['temps']=number_format(round(($ecoute[1]+$ecoute[2]*2+$ecoute[3]*3+$ecoute[4]*4+$ecoute[5]*5)/($ecoute[1]+$ecoute[2]+$ecoute[3]+$ecoute[4]+$ecoute[5])*4, 1), 1, ',', ' ');

    $total_point=$total_point+($ecoute[1]+$ecoute[2]*2+$ecoute[3]*3+$ecoute[4]*4+$ecoute[5]*5)*4;
    $total_rep=$total_rep+$ecoute[1]+$ecoute[2]+$ecoute[3]+$ecoute[4]+$ecoute[5];

    $moy_tmp=($revoir[1]+$revoir[2]*2+$revoir[3]*3+$revoir[4]*4)/($revoir[1]+$revoir[2]+$revoir[3]+$revoir[4])*5;

    while(isset($moyenne[$moy_tmp]))
    {
        $moy_tmp=$moy_tmp-0.0001;
    }

    $moyenne["$moy_tmp"]='revoir';
    $couverture['revoir']=number_format(round(($revoir[1]+$revoir[2]+$revoir[3]+$revoir[4])/$num_quest*100), 0, '', '');
    $moyenne2['revoir']=number_format(round(($revoir[1]+$revoir[2]*2+$revoir[3]*3+$revoir[4]*4)/($revoir[1]+$revoir[2]+$revoir[3]+$revoir[4])*5, 1), 1, ',', ' ');

    $total_point=$total_point+($revoir[1]+$revoir[2]*2+$revoir[3]*3+$revoir[4]*4)*5;
    $total_rep=$total_rep+$revoir[1]+$revoir[2]+$revoir[3]+$revoir[4];

    $moy_tmp=($aiser[1]+$aiser[2]*2+$aiser[3]*3+$aiser[4]*4+$aiser[5]*5)/($aiser[1]+$aiser[2]+$aiser[3]+$aiser[4]+$aiser[5])*4;

    while(isset($moyenne[$moy_tmp]))
    {
        $moy_tmp=$moy_tmp-0.0001;
    }

    $moyenne["$moy_tmp"]='aise';
    $couverture['aise']=number_format(round(($aiser[1]+$aiser[2]+$aiser[3]+$aiser[4]+$aiser[5])/$num_quest*100), 0, '', '');
    $moyenne2['aise']=number_format(round(($aiser[1]+$aiser[2]*2+$aiser[3]*3+$aiser[4]*4+$aiser[5]*5)/($aiser[1]+$aiser[2]+$aiser[3]+$aiser[4]+$aiser[5])*4, 1), 1, ',', ' ');

    $total_point=$total_point+($aiser[1]+$aiser[2]*2+$aiser[3]*3+$aiser[4]*4+$aiser[5]*5)*4;
    $total_rep=$total_rep+$aiser[1]+$aiser[2]+$aiser[3]+$aiser[4]+$aiser[5];


    $moy_tmp=($qual_conseil[1]+$qual_conseil[2]*2+$qual_conseil[3]*3+$qual_conseil[4]*4+$qual_conseil[5]*5)/($qual_conseil[1]+$qual_conseil[2]+$qual_conseil[3]+$qual_conseil[4]+$qual_conseil[5])*4;

    while(isset($moyenne[$moy_tmp]))
    {
        $moy_tmp=$moy_tmp-0.0001;
    }

    $moyenne["$moy_tmp"]='conseil';
    $couverture['conseil']=number_format(round(($qual_conseil[1]+$qual_conseil[2]+$qual_conseil[3]+$qual_conseil[4]+$qual_conseil[5])/$num_quest*100), 0, '', '');
    $moyenne2['conseil']=number_format(round(($qual_conseil[1]+$qual_conseil[2]*2+$qual_conseil[3]*3+$qual_conseil[4]*4+$qual_conseil[5]*5)/($qual_conseil[1]+$qual_conseil[2]+$qual_conseil[3]+$qual_conseil[4]+$qual_conseil[5])*4, 1), 1, ',', ' ');

    $total_point=$total_point+($qual_conseil[1]+$qual_conseil[2]*2+$qual_conseil[3]*3+$qual_conseil[4]*4+$qual_conseil[5]*5)*4;
    $total_rep=$total_rep+$qual_conseil[1]+$qual_conseil[2]+$qual_conseil[3]+$qual_conseil[4]+$qual_conseil[5];


    $moy_tmp=($concerne[1]+$concerne[2]*2+$concerne[3]*3+$concerne[4]*4)/($concerne[1]+$concerne[2]+$concerne[3]+$concerne[4])*5;

    while(isset($moyenne[$moy_tmp]))
    {
        $moy_tmp=$moy_tmp-0.0001;
    }

    $moyenne["$moy_tmp"]="concerne";
    $couverture['concerne']=number_format(round(($concerne[1]+$concerne[2]+$concerne[3]+$concerne[4])/$num_quest*100), 0, '', '');
    $moyenne2["concerne"]=number_format(round(($concerne[1]+$concerne[2]*2+$concerne[3]*3+$concerne[4]*4)/($concerne[1]+$concerne[2]+$concerne[3]+$concerne[4])*5, 1), 1, ',', ' ');

    $total_point=$total_point+($concerne[1]+$concerne[2]*2+$concerne[3]*3+$concerne[4]*4)*5;
    $total_rep=$total_rep+$concerne[1]+$concerne[2]+$concerne[3]+$concerne[4];


    $moy_tmp=($satisf[1]+$satisf[2]*2+$satisf[3]*3+$satisf[4]*4+$satisf[5]*5)/($satisf[1]+$satisf[2]+$satisf[3]+$satisf[4]+$satisf[5])*4;

    while(isset($moyenne[$moy_tmp]))
    {
        $moy_tmp=$moy_tmp-0.0001;
    }

    $moyenne["$moy_tmp"]='satisfait';
    $couverture['satisfait']=number_format(round(($satisf[1]+$satisf[2]+$satisf[3]+$satisf[4]+$satisf[5])/$num_quest*100), 0, '', '');
    $moyenne2['satisfait']=number_format(round(($satisf[1]+$satisf[2]*2+$satisf[3]*3+$satisf[4]*4+$satisf[5]*5)/($satisf[1]+$satisf[2]+$satisf[3]+$satisf[4]+$satisf[5])*4, 1), 1, ',', ' ');

    $total_point=$total_point+($satisf[1]+$satisf[2]*2+$satisf[3]*3+$satisf[4]*4+$satisf[5]*5)*4;
    $total_rep=$total_rep+$satisf[1]+$satisf[2]+$satisf[3]+$satisf[4]+$satisf[5];

    $moy_tmp=($conseil_alim[1]+$conseil_alim[2]*2+$conseil_alim[3]*3+$conseil_alim[4]*4+$conseil_alim[5]*5)/($conseil_alim[1]+$conseil_alim[2]+$conseil_alim[3]+$conseil_alim[4]+$conseil_alim[5])*4;

    while(isset($moyenne[$moy_tmp]))
    {
        $moy_tmp=$moy_tmp-0.0001;
    }

    $moyenne["$moy_tmp"]='alim';
    $couverture['alim']=number_format(round(($conseil_alim[1]+$conseil_alim[2]+$conseil_alim[3]+$conseil_alim[4]+$conseil_alim[5])/$num_quest*100), 0, '', '');
    $moyenne2['alim']=number_format(round(($conseil_alim[1]+$conseil_alim[2]*2+$conseil_alim[3]*3+$conseil_alim[4]*4+$conseil_alim[5]*5)/($conseil_alim[1]+$conseil_alim[2]+$conseil_alim[3]+$conseil_alim[4]+$conseil_alim[5])*4, 1), 1, ',', ' ');

    $total_point=$total_point+($conseil_alim[1]+$conseil_alim[2]*2+$conseil_alim[3]*3+$conseil_alim[4]*4+$conseil_alim[5]*5)*4;
    $total_rep=$total_rep+$conseil_alim[1]+$conseil_alim[2]+$conseil_alim[3]+$conseil_alim[4]+$conseil_alim[5];


    $moy_tmp=($conseil_compris[1]+$conseil_compris[2]*2+$conseil_compris[3]*3+$conseil_compris[4]*4+$conseil_compris[5]*5)/($conseil_compris[1]+$conseil_compris[2]+$conseil_compris[3]+$conseil_compris[4]+$conseil_compris[5])*4;

    while(isset($moyenne[$moy_tmp]))
    {
        $moy_tmp=$moy_tmp-0.0001;
    }

    $moyenne["$moy_tmp"]='compris';
    $couverture['compris']=number_format(round(($conseil_compris[1]+$conseil_compris[2]+$conseil_compris[3]+$conseil_compris[4]+$conseil_compris[5])/$num_quest*100), 0, '', '');
    $moyenne2['compris']=number_format(round(($conseil_compris[1]+$conseil_compris[2]*2+$conseil_compris[3]*3+$conseil_compris[4]*4+$conseil_compris[5]*5)/($conseil_compris[1]+$conseil_compris[2]+$conseil_compris[3]+$conseil_compris[4]+$conseil_compris[5])*4, 1), 1, ',', ' ');

    $total_point=$total_point+($conseil_compris[1]+$conseil_compris[2]*2+$conseil_compris[3]*3+$conseil_compris[4]*4+$conseil_compris[5]*5)*4;
    $total_rep=$total_rep+$conseil_compris[1]+$conseil_compris[2]+$conseil_compris[3]+$conseil_compris[4]+$conseil_compris[5];


    $moy_tmp=($adapt_vie[1]+$adapt_vie[2]*2+$adapt_vie[3]*3+$adapt_vie[4]*4+$adapt_vie[5]*5)/($adapt_vie[1]+$adapt_vie[2]+$adapt_vie[3]+$adapt_vie[4]+$adapt_vie[5])*4;

    while(isset($moyenne[$moy_tmp]))
    {
        $moy_tmp=$moy_tmp-0.0001;
    }

    $moyenne["$moy_tmp"]='mode_vie';
    $couverture['mode_vie']=number_format(round(($adapt_vie[1]+$adapt_vie[2]+$adapt_vie[3]+$adapt_vie[4]+$adapt_vie[5])/$num_quest*100), 0, '', '');
    $moyenne2['mode_vie']=number_format(round(($adapt_vie[1]+$adapt_vie[2]*2+$adapt_vie[3]*3+$adapt_vie[4]*4+$adapt_vie[5]*5)/($adapt_vie[1]+$adapt_vie[2]+$adapt_vie[3]+$adapt_vie[4]+$adapt_vie[5])*4, 1), 1, ',', ' ');

    $total_point=$total_point+($adapt_vie[1]+$adapt_vie[2]*2+$adapt_vie[3]*3+$adapt_vie[4]*4+$adapt_vie[5]*5)*4;
    $total_rep=$total_rep+$adapt_vie[1]+$adapt_vie[2]+$adapt_vie[3]+$adapt_vie[4]+$adapt_vie[5];

    $moy_tmp=($info[1]+$info[2]*2+$info[3]*3+$info[4]*4+$info[5]*5)/($info[1]+$info[2]+$info[3]+$info[4]+$info[5])*4;

    while(isset($moyenne[$moy_tmp]))
    {
        $moy_tmp=$moy_tmp-0.0001;
    }

    $moyenne["$moy_tmp"]='ignore';
    $couverture['ignore']=number_format(round(($info[1]+$info[2]+$info[3]+$info[4]+$info[5])/$num_quest*100), 0, '', '');
    $moyenne2['ignore']=number_format(round(($info[1]+$info[2]*2+$info[3]*3+$info[4]*4+$info[5]*5)/($info[1]+$info[2]+$info[3]+$info[4]+$info[5])*4, 1), 1, ',', ' ');

    $total_point=$total_point+($info[1]+$info[2]*2+$info[3]*3+$info[4]*4+$info[5]*5)*4;
    $total_rep=$total_rep+$info[1]+$info[2]+$info[3]+$info[4]+$info[5];

    $moy_tmp=($rep_quest[1]+$rep_quest[2]*2+$rep_quest[3]*3+$rep_quest[4]*4+$rep_quest[5]*5)/($rep_quest[1]+$rep_quest[2]+$rep_quest[3]+$rep_quest[4]+$rep_quest[5])*4;

    while(isset($moyenne[$moy_tmp]))
    {
        $moy_tmp=$moy_tmp-0.0001;
    }

    $moyenne["$moy_tmp"]='question';
    $couverture['question']=number_format(round(($rep_quest[1]+$rep_quest[2]+$rep_quest[3]+$rep_quest[4]+$rep_quest[5])/$num_quest*100), 0, '', '');
    $moyenne2['question']=number_format(round(($rep_quest[1]+$rep_quest[2]*2+$rep_quest[3]*3+$rep_quest[4]*4+$rep_quest[5]*5)/($rep_quest[1]+$rep_quest[2]+$rep_quest[3]+$rep_quest[4]+$rep_quest[5])*4, 1), 1, ',', ' ');

    $total_point=$total_point+($rep_quest[1]+$rep_quest[2]*2+$rep_quest[3]*3+$rep_quest[4]*4+$rep_quest[5]*5)*4;
    $total_rep=$total_rep+$rep_quest[1]+$rep_quest[2]+$rep_quest[3]+$rep_quest[4]+$rep_quest[5];

    $moy_tmp=($conseil_real[1]+$conseil_real[2]*2+$conseil_real[3]*3+$conseil_real[4]*4+$conseil_real[5]*5)/($conseil_real[1]+$conseil_real[2]+$conseil_real[3]+$conseil_real[4]+$conseil_real[5])*4;

    while(isset($moyenne[$moy_tmp]))
    {
        $moy_tmp=$moy_tmp-0.0001;
    }

    $moyenne["$moy_tmp"]='realisable';
    $couverture['realisable']=number_format(round(($conseil_real[1]+$conseil_real[2]+$conseil_real[3]+$conseil_real[4]+$conseil_real[5])/$num_quest*100), 0, '', '');
    $moyenne2['realisable']=number_format(round(($conseil_real[1]+$conseil_real[2]*2+$conseil_real[3]*3+$conseil_real[4]*4+$conseil_real[5]*5)/($conseil_real[1]+$conseil_real[2]+$conseil_real[3]+$conseil_real[4]+$conseil_real[5])*4, 1), 1, ',', ' ');

    $total_point=$total_point+($conseil_real[1]+$conseil_real[2]*2+$conseil_real[3]*3+$conseil_real[4]*4+$conseil_real[5]*5)*4;
    $total_rep=$total_rep+$conseil_real[1]+$conseil_real[2]+$conseil_real[3]+$conseil_real[4]+$conseil_real[5];


    krsort($moyenne);

    foreach($moyenne as $valeur=>$nom)
    {
        ?>
        <tr>
            <td><?php echo $texte[$nom];?></td>
            <td><?php echo $moyenne2[$nom];?></td>
            <td><?php echo $couverture[$nom];?>%</td>
        </tr>
        <?
    }

    $moyenne=number_format(round($total_point/$total_rep, 1), 1, ',', ' ');
    $couverture=number_format(round($total_rep/$total_pat*100), 0, '', '');
    ?>
    <tr>
        <td>Moyenne générale/couverture globale</td>
        <td><?php echo $moyenne?></td>
        <td><?php echo $couverture;?>%</td>
    </tr>
</table>

<br><br>
<b>Commentaires particuliers ajoutés</b>
<table border="1">
    <?

    $req="SELECT commentaire FROM satisf_patient2009 ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $i=1;

    while(list($commentaire)=mysql_fetch_row($res))
    {
        if($commentaire!='')
        {
            echo "<tr><td>".$i."- ".nl2br($commentaire)."</td></tr>";
            $i++;
        }

    }
    echo "</table>";
    }


    ?>
</body>
</html>
