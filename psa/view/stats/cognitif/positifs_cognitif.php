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
    <title>Taux de patients dépistés positifs pour les troubles cognitifs</title>
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

entete_asalee("Taux de patients dépistés positifs pour les troubles cognitifs");
//echo $loc;
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
<font face='times new roman'>Indicateurs d'évaluation Asalée : taux de dépistage des troubles cognitifs</font></i>";
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
    global $message,$Dossier,$Cabinet, $deval, $self,$tcabinet, $tville, $t_cogni;

    $req="SELECT cabinet, nom_cab, region ".
        "FROM account ".
        "WHERE infirmiere!='' and region!='' ".
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $t_cogni['tot']=0;
    $reg=array();
    while(list($cab, $ville, $region) = mysql_fetch_row($res)) {
        $tville[$cab]=$ville;
        $regions[$cab]=$region;
        if(!in_array($region, $reg)){
            $reg[]=$region;
        }
    }

    sort($reg);
    $req="SELECT cabinet, count(*) ".
        "FROM dossier ".
        "WHERE cabinet!='zTest'  and cabinet!='irdes' and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
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
            <td align='center'><b> Total </b></td>


            <?php

            foreach($reg as $region){
                echo "<td align='center'><b>$region</b></td>";
            }

            foreach($tville as $cab => $ville) {
                ?>
                <td align='center'><b><?php echo $tville[$cab]; ?></b></td>
                <?php
            }
            ?>
        </tr>


        <?php

        ///////////////////////////TROUBLES COGNITIFS//////////////////////////////////

        $req="SELECT dossier.id as id, date, suivi_type, date_rappel, dep_type, raison_dep, mmse_annee, mmse_saison, mmse_mois, mmse_jour_mois, ".
            " mmse_jour_semaine, mmse_nom_hop, mmse_nom_ville, mmse_nom_dep, mmse_region, mmse_etage, mmse_cigare1, mmse_fleur1,".
            "mmse_porte1, mmse_93, mmse_86, mmse_79, mmse_72, mmse_65, mmse_monde, mmse_cigare2, mmse_fleur2, mmse_porte2, ".
            "mmse_crayon, mmse_montre, mmse_repete_phrase, mmse_feuille_prise, mmse_feuille_pliee, mmse_feuille_jetee, ".
            "mmse_fermer_yeux, mmse_ecrit_phrase, mmse_copie_dessin, gds_satisf, gds_renonce_act, gds_vie_vide, gds_ennui,".
            "gds_avenir_opt, gds_cata, gds_bonne_humeur, gds_besoin_aide, gds_prefere_seul, gds_mauvaise_mem, gds_heureux_vivre, ".
            "gds_bon_rien, gds_energie, gds_desespere_sit, gds_sit_autres_best, iadl_telephone, iadl_transport, iadl_med, ".
            "iadl_budget, horloge, cabinet ".
            "FROM trouble_cognitif, dossier ".
            "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
//		 "AND dossier.actif='oui' ".
            "AND trouble_cognitif.id=dossier.id ".
//		 "AND DATE_ADD(dnaiss,INTERVAL 75 YEAR) <= CURDATE() ".
//		 "and (trouble_cognitif.date_rappel is not NULL and ".
//		 "DATE_ADD(trouble_cognitif.date_rappel, INTERVAL 1 MONTH) >= CURDATE()) ".
            "ORDER BY cabinet, id, date ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $total[$cab]=0;
            $positif[$cab]=0;
        }

        $total['tot']=0;
        $positif['tot']=0;

        foreach($reg as $region){
            $total[$region]=0;
            $positif[$region]=0;
        }

        $id_prec='';

        while($liste = mysql_fetch_array($res)) {

            $tab_exam=explode(',', $liste['suivi_type']);

            if(($liste['id']!=$id_prec)&&($id_prec!='')){

                if(isset($regions[$cab_prec])){
                    if($mmse>25){
                        if($gds<5){
                            if($iadl<2){
                                if($horloge>9){
                                    if($dubois<10){

                                        $positif[$regions[$cab_prec]]=$positif[$regions[$cab_prec]]+1;

                                        $positif[$cab_prec]=$positif[$cab_prec]+1;
                                        $positif['tot']=$positif['tot']+1;
                                    }
                                }
                                else{

                                    $positif[$regions[$cab_prec]]=$positif[$regions[$cab_prec]]+1;

                                    $positif[$cab_prec]=$positif[$cab_prec]+1;
                                    $positif['tot']=$positif['tot']+1;
                                }
                            }
                            else{
                                $positif[$regions[$cab_prec]]=$positif[$regions[$cab_prec]]+1;

                                $positif[$cab_prec]=$positif[$cab_prec]+1;
                                $positif['tot']=$positif['tot']+1;
                            }
                        }
                        else{

                            $positif[$regions[$cab_prec]]=$positif[$regions[$cab_prec]]+1;

                            $positif[$cab_prec]=$positif[$cab_prec]+1;
                            $positif['tot']=$positif['tot']+1;
                        }
                    }
                    else{

                        $positif[$regions[$cab_prec]]=$positif[$regions[$cab_prec]]+1;

                        $positif[$cab_prec]=$positif[$cab_prec]+1;
                        $positif['tot']=$positif['tot']+1;
                    }

                    $total[$cab_prec]=$total[$cab_prec]+1;
                    $total['tot']=$total['tot']+1;


                    $total[$regions[$cab_prec]]=$total[$regions[$cab_prec]]+1;


                    if(in_array('mmse', $tab_exam)){
                        $mmse=get_mmse($liste);
                    }
                    else{
                        $mmse="30";
                    }
                    if(in_array('gds', $tab_exam)){
                        $gds=get_gds($liste);
                    }
                    else{
                        $gds="0";
                    }
                    if(in_array('iadl', $tab_exam)){
                        $iadl=get_iadl($liste);
                    }
                    else{
                        $iadl="0";
                    }
                    if(in_array('horl', $tab_exam)){
                        $horloge=$liste['horloge'];
                    }
                    else{
                        $horloge="10";
                    }
                    if(in_array('dubois', $tab_exam)){
                        $dubois=get_dubois($liste);
                    }
                    else{
                        $dubois="10";
                    }
                }

                $id_prec=$liste['id'];
                $cab_prec=$liste['cabinet'];
            }
            elseif($id_prec==''){
                if(in_array('mmse', $tab_exam)){
                    $mmse=get_mmse($liste);
                }
                else{
                    $mmse="30";
                }
                if(in_array('gds', $tab_exam)){
                    $gds=get_gds($liste);
                }
                else{
                    $gds="0";
                }
                if(in_array('iadl', $tab_exam)){
                    $iadl=get_iadl($liste);
                }
                else{
                    $iadl="0";
                }
                if(in_array('horl', $tab_exam)){
                    $horloge=$liste['horloge'];
                }
                else{
                    $horloge="10";
                }
                if(in_array('dubois', $tab_exam)){
                    $dubois=get_dubois($liste);
                }
                else{
                    $dubois="10";
                }

                $id_prec=$liste['id'];
                $cab_prec=$liste['cabinet'];
            }
            else{
                if(in_array('mmse', $tab_exam)){
                    $mmse=get_mmse($liste);
                }

                if(in_array('gds', $tab_exam)){
                    $gds=get_gds($liste);
                }

                if(in_array('iadl', $tab_exam)){
                    $iadl=get_iadl($liste);
                }

                if(in_array('horl', $tab_exam)){
                    $horloge=$liste['horloge'];
                }
                if(in_array('dubois', $tab_exam)){
                    $dubois=get_dubois($liste);
                }
                else{
                    $dubois="10";
                }
            }
        }

        ?>

        <tr>
            <td>Taux de patients dépistés positifs<sup>1</sup></td>
            <td align='right'><?php echo round($positif['tot']/$total['tot']*100,0);?>%</td>

            <?php

            foreach($reg as $region){
                echo "<td align='right'>".round($positif[$region]/$total[$region]*100,0)."%</td>";
            }
            /*
                            <td align='right'><?php echo round($positif['eval2']/$total['eval2']*100,0);?>%</td>
                                <td align='right'><?php echo round($positif['eval3']/$total['eval3']*100,0);?>%</td>

            <?php*/

            foreach($tville as $cab=>$ville) {
                if ($total[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$positif[$cab]/$total[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo $taux; ?></td>
                <?php
            }
            ?>
        </tr>
    </table>
    <br>
    <br>

    <sup>1</sup>Taux de patients dépistés positifs : MMSE <=25 OU Horloge <= 9 OU GDS >= 5 OU IADL >= 2 ou dubois <10
    <?

}


function get_iadl($ligne){
    $iadl=4;
    if($ligne['iadl_telephone'] == 'tout'){
        $iadl--;;
    }

    if($ligne['iadl_transport'] == 'tout'){
        $iadl--;
    }

    if($ligne['iadl_med'] == 'tout'){
        $iadl--;
    }

    if($ligne['iadl_budget'] == 'tout'){
        $iadl--;
    }


    if(($ligne['iadl_telephone'] == NULL)&&($ligne['iadl_transport'] == NULL)&&($ligne['iadl_med'] == NULL)
        &&($ligne['iadl_budget'] == NULL))
        $iadl='ND';

    return $iadl;
}

function get_mmse($ligne){
    $mmse=0;

    $mmse= $ligne['mmse_annee']+$ligne['mmse_saison']+$ligne['mmse_mois']+$ligne['mmse_jour_mois']+
        $ligne['mmse_jour_semaine']+$ligne['mmse_nom_hop']+$ligne['mmse_nom_ville']+
        $ligne['mmse_nom_dep']+$ligne['mmse_region']+$ligne['mmse_etage']+$ligne['mmse_cigare1']+
        $ligne['mmse_fleur1']+$ligne['mmse_porte1']+$ligne['mmse_93']+$ligne['mmse_86']+$ligne['mmse_79']+
        $ligne['mmse_72']+$ligne['mmse_65']+$ligne['mmse_cigare2']+$ligne['mmse_fleur2']+
        $ligne['mmse_porte2']+$ligne['mmse_crayon']+$ligne['mmse_montre']+$ligne['mmse_repete_phrase']+
        $ligne['mmse_feuille_prise']+$ligne['mmse_feuille_pliee']+$ligne['mmse_feuille_jetee']+
        $ligne['mmse_fermer_yeux']+$ligne['mmse_ecrit_phrase']+$ligne['mmse_copie_dessin'];


    if(($ligne['mmse_annee']==NULL)&&($ligne['mmse_saison']==NULL)&&($ligne['mmse_mois==NULL']==NULL)&&
        ($ligne['mmse_jour_mois']==NULL)&&($ligne['mmse_jour_semaine']==NULL)&&($ligne['mmse_nom_hop']==NULL)&&
        ($ligne['mmse_nom_ville']==NULL)&&($ligne['mmse_nom_dep']==NULL)&&($ligne['mmse_region']==NULL)&&
        ($ligne['mmse_etage']==NULL)&&($ligne['mmse_cigare1']==NULL)&&($ligne['mmse_fleur1']==NULL)&&
        ($ligne['mmse_porte1']==NULL)&&($ligne['mmse_93']==NULL)&&($ligne['mmse_86']==NULL)&&($ligne['mmse_79']==NULL)&&
        ($ligne['mmse_72']==NULL)&&($ligne['mmse_65']==NULL)&&($ligne['mmse_cigare2']==NULL)&&($ligne['mmse_fleur2']==NULL)&&
        ($ligne['mmse_porte2']==NULL)&&($ligne['mmse_crayon']==NULL)&&($ligne['mmse_montre']==NULL)&&
        ($ligne['mmse_repete_phrase']==NULL)&&($ligne['mmse_feuille_prise']==NULL)&&($ligne['mmse_feuille_pliee']==NULL)&&
        ($ligne['mmse_feuille_jetee']==NULL)&&($ligne['mmse_fermer_yeux']==NULL)&&($ligne['mmse_ecrit_phrase']==NULL)&&
        ($ligne['mmse_copie_dessin']==NULL))
        $mmse="ND";

    return $mmse;
}


function get_gds($ligne){
    $gds=0;

    if($ligne['gds_satisf']=='non')
    {
        $gds++;
    }

    if($ligne['gds_renonce_act']=='oui')
    {
        $gds++;
    }

    if($ligne['gds_vie_vide']=='oui')
    {
        $gds++;
    }

    if($ligne['gds_ennui']=='oui')
    {
        $gds++;
    }

    if($ligne['gds_avenir_opt']=='non')
    {
        $gds++;
    }

    if($ligne['gds_cata']=='oui')
    {
        $gds++;
    }

    if($ligne['gds_bonne_humeur']=='non')
    {
        $gds++;
    }

    if($ligne['gds_besoin_aide']=='oui')
    {
        $gds++;
    }

    if($ligne['gds_prefere_seul']=='oui')
    {
        $gds++;
    }

    if($ligne['gds_mauvaise_mem']=='oui')
    {
        $gds++;
    }

    if($ligne['gds_heureux_vivre']=='non')
    {
        $gds++;
    }

    if($ligne['gds_bon_rien']=='oui')
    {
        $gds++;
    }

    if($ligne['gds_energie']=='non')
    {
        $gds++;
    }

    if($ligne['gds_desespere_sit']=='oui')
    {
        $gds++;
    }

    if($ligne['gds_sit_autres_best']=='oui')
    {
        $gds++;
    }



    if(($ligne['gds_satisf']==NULL)&&($ligne['gds_renonce_act']==NULL)&&($ligne['gds_vie_vide']==NULL)&&
        ($ligne['gds_ennui']==NULL)&&($ligne['gds_avenir_opt']==NULL)&&($ligne['gds_cata']==NULL)&&
        ($ligne['gds_bonne_humeur']==NULL)&&($ligne['gds_besoin_aide']==NULL)&&($ligne['gds_prefere_seul']==NULL)&&
        ($ligne['gds_mauvaise_mem']==NULL)&&($ligne['gds_heureux_vivre']==NULL)&&($ligne['gds_bon_rien']==NULL)&&
        ($ligne['gds_energie']==NULL)&&($ligne['gds_desespere_sit']==NULL)&&($ligne['gds_sit_autres_best']==NULL))
    {
        $gds='ND';
    }


    return $gds;
}

?>
</body>
</html>
