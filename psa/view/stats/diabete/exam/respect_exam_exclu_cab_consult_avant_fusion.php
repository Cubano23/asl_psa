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
    <title>Taux de respect des examens cabinets actifs entre -14 et -2 mois et au moins une consultation</title>
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

entete_asalee("Taux de respect des examens cabinets actifs entre -14 et -2 mois et au moins une consultation");
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
<font face='times new roman'>Taux de patients disposant d'au moins une mise à jour dans l'année</font></i>";
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
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_diab, $regions, $reg;

    $req="SELECT cabinet, total_diab2, nom_cab, region ".
        "FROM account ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and cabinet!='jgomes' ".
        "and cabinet!='sbirault' ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $t_diab['tot']=0;
    $t_diab['eval']=0;
    $t_diab['eval2']=0;
    $t_diab['eval3']=0;
    $reg=array();

    while(list($cab, $total_diab2, $ville, $region) = mysql_fetch_row($res)) {
        $t_diab[$cab]=$total_diab2;
        $t_diab['tot']=$t_diab['tot']+$total_diab2;
        $tville[$cab]=$ville;
        $regions[$cab]=$region;
        if(!in_array($region, $reg)){
            $reg[]=$region;
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
//	 $tpat[$cab] = $pat;
    }

    foreach($tcabinet as $cab){
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
        "dossier.id=suivi_diabete.dossier_id and dsuivi>='$date3mois' group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        $tcabinet_util[$cab]=1;
    }

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');

    echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

    ?>
    <br>
    <br>
    <table border=1 align='center'>
        <?php

        ///////////////Respect des examens //////////////////////////
        $date2mois=date("Y-m-d", mktime(1, 1, 1, date("m")-2, date('d'), date("Y")));

        $req="SELECT cabinet, dossier_id, DATE_ADD(max(dHBA), INTERVAL 8 MONTH), DATE_ADD(max(dExaFil), INTERVAL 14 MONTH),".
            " DATE_ADD(max(dExaPieds), INTERVAL 14 MONTH), DATE_ADD(max(dChol), INTERVAL 14 MONTH), ".
            "DATE_ADD(max(suivi_diabete.dCreat), INTERVAL 14 MONTH), DATE_ADD(max(dAlbu),INTERVAL 14 MONTH), ".
            "DATE_ADD(max(dFond), INTERVAL 14 MONTH), DATE_ADD(max(dECG), INTERVAL 14 MONTH) ".
            "from suivi_diabete,dossier where cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  ".
            "and cabinet!='jgomes' and dossier.cabinet!='sbirault' and suivi_diabete".
            ".dossier_id = dossier.id  AND dossier.actif='oui' and ".
            "((dHBA is not NULL and DATE_ADD(dHBA, INTERVAL 8 MONTH) >= CURDATE()) ".
            " or ((dExaFil is not NULL and DATE_ADD(dExaFil, ".
            "INTERVAL 14 MONTH) >= CURDATE()) or (dExaPieds is not NULL and ".
            "DATE_ADD(dExaPieds, INTERVAL 14 MONTH) >= CURDATE())) or ".
            "((dChol is not NULL and DATE_ADD(dChol, INTERVAL 14 MONTH) >= CURDATE()) ".
            " or (dLDL is not NULL and DATE_ADD(dLDL, INTERVAL 14 MONTH) >= CURDATE())  ".
            "or (suivi_diabete.dCreat is not NULL and DATE_ADD(suivi_diabete.dCreat, INTERVAL 14 MONTH) >= CURDATE()) or (dAlbu is not NULL ".
            "and DATE_ADD(dAlbu, INTERVAL 14 MONTH) >= CURDATE()) or (dFond is not NULL and DATE_ADD(dFond, ".
            "INTERVAL 14 MONTH) >= CURDATE()) or (dECG is not NULL and DATE_ADD(dECG, INTERVAL 14 MONTH) >= ".
            "CURDATE()))) GROUP by numero order by numero";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]['hba']=0;
            $tpat[$cab]['exafil']=0;
            $tpat[$cab]['pied']=0;
            $tpat[$cab]['chol']=0;
            $tpat[$cab]['creat']=0;
            $tpat[$cab]['albu']=0;
            $tpat[$cab]['fond']=0;
            $tpat[$cab]['ecg']=0;
            $t_diab[$cab]=0;
        }


        $tpat['tot']['hba']=0;
        $tpat['tot']['exafil']=0;
        $tpat['tot']['pied']=0;
        $tpat['tot']['chol']=0;
        $tpat['tot']['creat']=0;
        $tpat['tot']['albu']=0;
        $tpat['tot']['fond']=0;
        $tpat['tot']['ecg']=0;

        foreach($reg as $region){
            $tpat[$region]['hba']=0;
            $tpat[$region]['exafil']=0;
            $tpat[$region]['pied']=0;
            $tpat[$region]['chol']=0;
            $tpat[$region]['creat']=0;
            $tpat[$region]['albu']=0;
            $tpat[$region]['fond']=0;
            $tpat[$region]['ecg']=0;
        }

        while(list($cabinet, $dossier_id, $dHBA, $dExaFil, $dExaPieds, $dChol, $dCreat, $dAlbu,
            $dFond, $dECG) = mysql_fetch_row($res)) {

            $req2="SELECT id from evaluation_infirmier where ".
                "id='$dossier_id'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            if(mysql_num_rows($res2)>0){
                $t_diab[$cabinet]=$t_diab[$cabinet]+1;

                if(($dHBA!='')&&($dHBA!='NULL')){
                    if(diffmois($dHBA, $date2mois)<=0)
                    {
                        if($tcabinet_util[$cabinet]==1){
                            $tpat['tot']['hba'] = $tpat['tot']["hba"]+1;
                            $tpat[$cabinet]['hba'] = $tpat[$cabinet]["hba"]+1;
                            if(!isset($tpat[$regions[$cabinet]]['hba'])){
                                $tpat[$regions[$cabinet]]['hba']=0;
                            }
                            $tpat[$regions[$cabinet]]['hba']=$tpat[$regions[$cabinet]]['hba']+1;
                        }

                    }
                }

                if(($dExaFil!='')&&($dExaFil!='NULL'))
                {
                    if(diffmois($dExaFil, $date2mois)<=0)
                    {
                        if($tcabinet_util[$cabinet]==1){
                            $tpat['tot']['exafil'] = $tpat['tot']["exafil"]+1;
                            $tpat[$cabinet]['exafil'] = $tpat[$cabinet]["exafil"]+1;
                            if(!isset($tpat[$regions[$cabinet]]['exafil'])){
                                $tpat[$regions[$cabinet]]['exafil']=0;
                            }
                            $tpat[$regions[$cabinet]]['exafil']=$tpat[$regions[$cabinet]]['exafil']+1;
                        }

                    }
                }

                if(($dExaPieds!='')&&($dExaPieds!='NULL'))
                {
                    if(diffmois($dExaPieds, $date2mois)<=0)
                    {
                        if($tcabinet_util[$cabinet]==1){
                            $tpat['tot']['pied'] = $tpat['tot']["pied"]+1;
                            $tpat[$cabinet]['pied'] = $tpat[$cabinet]["pied"]+1;
                            if(!isset($tpat[$regions[$cabinet]]['pied'])){
                                $tpat[$regions[$cabinet]]['pied']=0;
                            }
                            $tpat[$regions[$cabinet]]['pied']=$tpat[$regions[$cabinet]]['pied']+1;
                        }

                    }
                }

                if(($dChol!='')&&($dChol!='NULL'))
                {
                    if(diffmois($dChol, $date2mois)<=0)
                    {
                        if($tcabinet_util[$cabinet]==1){
                            $tpat['tot']['chol'] = $tpat['tot']["chol"]+1;
                            $tpat[$cabinet]['chol'] = $tpat[$cabinet]["chol"]+1;
                            if(!isset($tpat[$regions[$cabinet]]['chol'])){
                                $tpat[$regions[$cabinet]]['chol']=0;
                            }
                            $tpat[$regions[$cabinet]]['chol']=$tpat[$regions[$cabinet]]['chol']+1;
                        }

                    }
                }

                if(($dCreat!='')&&($dCreat!='NULL'))
                {
                    if(diffmois($dCreat, $date2mois)<=0)
                    {
                        if($tcabinet_util[$cabinet]==1){
                            $tpat['tot']['creat'] = $tpat['tot']["creat"]+1;
                            $tpat[$cabinet]['creat'] = $tpat[$cabinet]["creat"]+1;
                            if(!isset($tpat[$regions[$cabinet]]['creat'])){
                                $tpat[$regions[$cabinet]]['creat']=0;
                            }
                            $tpat[$regions[$cabinet]]['creat']=$tpat[$regions[$cabinet]]['creat']+1;
                        }

                    }
                }

                if(($dAlbu!='')&&($dAlbu!='NULL'))
                {
                    if(diffmois($dAlbu, $date2mois)<=0)
                    {
                        if($tcabinet_util[$cabinet]==1){
                            $tpat['tot']['albu'] = $tpat['tot']["albu"]+1;
                            $tpat[$cabinet]['albu'] = $tpat[$cabinet]["albu"]+1;
                            if(!isset($tpat[$regions[$cabinet]]['albu'])){
                                $tpat[$regions[$cabinet]]['albu']=0;
                            }
                            $tpat[$regions[$cabinet]]['albu']=$tpat[$regions[$cabinet]]['albu']+1;
                        }

                    }
                }

                if(($dFond!='')&&($dFond!='NULL'))
                {
                    if(diffmois($dFond, $date2mois)<=0)
                    {
                        if($tcabinet_util[$cabinet]==1){
                            $tpat['tot']['fond'] = $tpat['tot']["fond"]+1;
                            $tpat[$cabinet]['fond'] = $tpat[$cabinet]["fond"]+1;
                            if(!isset($tpat[$regions[$cabinet]]['fond'])){
                                $tpat[$regions[$cabinet]]['fond']=0;
                            }
                            $tpat[$regions[$cabinet]]['fond']=$tpat[$regions[$cabinet]]['fond']+1;
                        }

                    }
                }

                if(($dECG!='')&&($dECG!='NULL'))
                {
                    if(diffmois($dECG, $date2mois)<=0)
                    {
                        if($tcabinet_util[$cabinet]==1){
                            $tpat['tot']['ecg'] = $tpat['tot']["ecg"]+1;
                            $tpat[$cabinet]['ecg'] = $tpat[$cabinet]["ecg"]+1;
                            if(!isset($tpat[$regions[$cabinet]]['ecg'])){
                                $tpat[$regions[$cabinet]]['ecg']=0;
                            }
                            $tpat[$regions[$cabinet]]['ecg']=$tpat[$regions[$cabinet]]['ecg']+1;
                        }

                    }
                }
            }

        }

        foreach($reg as $region){
            $t_diab[$region]=0;
        }
        $t_diab["tot"]=0;
        foreach($regions as $cab=>$region){
            if($tcabinet_util[$cab]==1){
                if(!isset($t_diab[$region])){
                    $t_diab[$region]=0;
                }
                $t_diab[$region]=$t_diab[$region]+$t_diab[$cab];
                $t_diab["tot"]=$t_diab["tot"]+$t_diab[$cab];
            }
        }

        ?>

        <tr>
            <td>Taux de respect des examens &nbsp;</td>
            <td align='center'> <b>&nbsp;moyenne</b>	 &nbsp;</td>
            <?php
            foreach($reg as $region){
                if($region!=""){
                    echo "<td align='center'> <b>&nbsp;moyenne $region</b> &nbsp;</td>";
                }
            }



            foreach($tcabinet as $cab) {
                if (($tcabinet_util[$cab]==0)||($t_diab[$cab]==0))
                {
                    $taux_hba[$cab]=$taux_exafil[$cab]=$taux_pied[$cab]=$taux_chol[$cab]=$taux_creat[$cab]="ND";
                    $taux_albu[$cab]=$taux_fond[$cab]=$taux_ecg[$cab]="ND";
                }
                else
                {
                    $taux_hba[$cab]=round($tpat[$cab]['hba']/$t_diab[$cab]*100);
                    $taux_hba[$cab].="%";

                    $taux_exafil[$cab]=round($tpat[$cab]['exafil']/$t_diab[$cab]*100);
                    $taux_exafil[$cab].="%";

                    $taux_pied[$cab]=round($tpat[$cab]['pied']/$t_diab[$cab]*100);
                    $taux_pied[$cab].="%";

                    $taux_chol[$cab]=round($tpat[$cab]['chol']/$t_diab[$cab]*100);
                    $taux_chol[$cab].="%";

                    $taux_creat[$cab]=round($tpat[$cab]['creat']/$t_diab[$cab]*100);
                    $taux_creat[$cab].="%";

                    $taux_albu[$cab]=round($tpat[$cab]['albu']/$t_diab[$cab]*100);
                    $taux_albu[$cab].="%";

                    $taux_fond[$cab]=round($tpat[$cab]['fond']/$t_diab[$cab]*100);
                    $taux_fond[$cab].="%";

                    $taux_ecg[$cab]=round($tpat[$cab]['ecg']/$t_diab[$cab]*100);
                    $taux_ecg[$cab].="%";
                }
                ?>
                <td><b><?php echo $tville[$cab];?></b></td>
                <?php
            }

            ?>
        </tr>
        <?php
        /*	foreach($regions as $cab=>$region){
                if($tcabinet_util[$cab]==0){
                    $t_diab['tot']=$t_diab['tot']-$t_diab[$cab];
                    $t_diab[$region]=$t_diab[$region]-$t_diab[$cab];
                }
            }
        */
        if ($t_diab['tot']==0)
        {
            $taux_hba['tot']=$taux_exafil['tot']=$taux_pied['tot']=$taux_chol['tot']=$taux_creat['tot']='ND';
            $taux_albu['tot']=$taux_fond['tot']=$taux_ecg['tot']="ND";
        }
        else
        {
            $taux_hba['tot']=round($tpat['tot']['hba']/$t_diab['tot']*100);
            $taux_hba['tot'].="%";

            $taux_exafil['tot']=round($tpat['tot']['exafil']/$t_diab['tot']*100);
            $taux_exafil['tot'].="%";

            $taux_pied['tot']=round($tpat['tot']['pied']/$t_diab['tot']*100);
            $taux_pied['tot'].="%";

            $taux_chol['tot']=round($tpat['tot']['chol']/$t_diab['tot']*100);
            $taux_chol['tot'].="%";

            $taux_creat['tot']=round($tpat['tot']['creat']/$t_diab['tot']*100);
            $taux_creat['tot'].="%";

            $taux_albu['tot']=round($tpat['tot']['albu']/$t_diab['tot']*100);
            $taux_albu['tot'].="%";

            $taux_fond['tot']=round($tpat['tot']['fond']/$t_diab['tot']*100);
            $taux_fond['tot'].="%";

            $taux_ecg['tot']=round($tpat['tot']['ecg']/$t_diab['tot']*100);
            $taux_ecg['tot'].="%";
        }

        foreach($reg as $region){
            if ($t_diab[$region]==0)
            {
                $taux_hba[$region]=$taux_exafil[$region]=$taux_pied[$region]=$taux_chol[$region]=$taux_creat[$region]='ND';
                $taux_albu[$region]=$taux_fond[$region]=$taux_ecg[$region]="ND";
            }
            else
            {
                $taux_hba[$region]=round($tpat[$region]['hba']/$t_diab[$region]*100);
                $taux_hba[$region].="%";

                $taux_exafil[$region]=round($tpat[$region]['exafil']/$t_diab[$region]*100);
                $taux_exafil[$region].="%";

                $taux_pied[$region]=round($tpat[$region]['pied']/$t_diab[$region]*100);
                $taux_pied[$region].="%";

                $taux_chol[$region]=round($tpat[$region]['chol']/$t_diab[$region]*100);
                $taux_chol[$region].="%";

                $taux_creat[$region]=round($tpat[$region]['creat']/$t_diab[$region]*100);
                $taux_creat[$region].="%";

                $taux_albu[$region]=round($tpat[$region]['albu']/$t_diab[$region]*100);
                $taux_albu[$region].="%";

                $taux_fond[$region]=round($tpat[$region]['fond']/$t_diab[$region]*100);
                $taux_fond[$region].="%";

                $taux_ecg[$region]=round($tpat[$region]['ecg']/$t_diab[$region]*100);
                $taux_ecg[$region].="%";
            }
        }

        ?>

        <tr>
            <td>HBA1c <sup>1</sup></td>
            <td align='right'><?php echo $taux_hba['tot']; ?></td>
            <?php

            foreach($reg as $region){
                if($region!=""){
                    echo "<td align='right'>".$taux_hba[$region]."</td>";
                }
            }

            foreach($tcabinet as $cab) {
                ?>
                <td align='right'><?php echo $taux_hba[$cab]; ?></td>
                <?php
            }
            ?>

        </tr>
        <td>Examen au monofilament<sup>2</sup></td>
        <td align='right'><?php echo $taux_exafil['tot']; ?></td>
        <?php
        foreach($reg as $region){
            if($region!=""){
                echo "<td align='right'>".$taux_exafil[$region]."</td>";
            }
        }

        foreach($tcabinet as $cab) {?>
            <td align='right'><?php echo $taux_exafil[$cab]; ?></td>

            <?php
        }
        ?>

        </tr>
        <td>Examen des pieds<sup>3</Sup></td>
        <td align='right'><?php echo $taux_pied['tot']; ?></td>
        <?php
        foreach($reg as $region){
            if($region!=""){
                echo "<td align='right'>".$taux_pied[$region]."</td>";
            }
        }

        foreach($tcabinet as $cab) {
            ?>
            <td align='right'><?php echo $taux_pied[$cab]; ?></td>

            <?php
        }
        ?>

        </tr>
        <td>Dosage du HDL - Cholestérol<sup>4</sup></td>
        <td align='right'><?php echo $taux_chol['tot']; ?></td>
        <?php

        foreach($reg as $region){
            if($region!=""){
                echo "<td align='right'>".$taux_chol[$region]."</td>";
            }
        }

        foreach($tcabinet as $cab) {
            ?>
            <td align='right'><?php echo $taux_chol[$cab]; ?></td>

            <?php
        }
        ?>

        </tr>
        <td>Créatinémie<sup>5</sup></td>
        <td align='right'><?php echo $taux_creat['tot']; ?></td>
        <?php

        foreach($reg as $region){
            if($region!=""){
                echo "<td align='right'>".$taux_creat[$region]."</td>";
            }
        }

        foreach($tcabinet as $cab) {
            ?>
            <td align='right'><?php echo $taux_creat[$cab]; ?></td>

            <?php
        }
        ?>

        </tr>
        <td>Micro Albuminurie<sup>6</sup></td>
        <td align='right'><?php echo $taux_albu['tot']; ?></td>
        <?php

        foreach($reg as $region){
            if($region!=""){
                echo "<td align='right'>".$taux_albu[$region]."</td>";
            }
        }

        foreach($tcabinet as $cab) {
            ?>
            <td align='right'><?php echo $taux_albu[$cab]; ?></td>

            <?php
        }
        ?>

        </tr>
        <td>Fond d'oeil<sup>7</sup></td>
        <td align='right'><?php echo $taux_fond['tot']; ?></td>
        <?php
        foreach($reg as $region){
            if($region!=""){
                echo "<td align='right'>".$taux_fond[$region]."</td>";
            }
        }

        foreach($tcabinet as $cab) {
            ?>
            <td align='right'><?php echo $taux_fond[$cab]; ?></td>

            <?php
        }
        ?>

        </tr>
        <td>ECG<sup>8</sup></td>
        <td align='right'><?php echo $taux_ecg['tot']; ?></td>
        <?php
        foreach($reg as $region){
            if($region!=""){
                echo "<td align='right'>".$taux_ecg[$region]."</td>";
            }
        }

        foreach($tcabinet as $cab) {
            ?>
            <td align='right'><?php echo $taux_ecg[$cab]; ?></td>

            <?php
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
    <sup>1</sup>Nombre de patients ayant eu un résultat de HBA1c dans les 6 derniers mois/potentiel du cabinet<br>
    <sup>2</sup>Nombre de patients ayant eu un résultat d'examen au monofilament dans les 12 derniers mois/potentiel du cabinet<br>
    <sup>3</sup>Nombre de patients ayant eu un résultat d'examen des pieds dans les 12 derniers mois/potentiel du cabinet<br>
    <sup>4</sup>Nombre de patients ayant eu un résultat de dosage du HDL/Cholesterol dans les 12 derniers mois/potentiel du cabinet<br>
    <sup>5</sup>Nombre de patients ayant eu un résultat de créatinémie dans les 12 derniers mois/potentiel du cabinet<br>
    <sup>6</sup>Nombre de patients ayant eu un résultat de Micro-Albuminurie dans les 12 derniers mois/potentiel du cabinet<br>
    <sup>7</sup>Nombre de patients ayant eu un résultat de fond d'oeil dans les 12 derniers mois/potentiel du cabinet<br>
    <sup>8</sup>Nombre de patients ayant eu un résultat d'ECG dans les 12 derniers mois/potentiel du cabinet<br>
    Un cabinet est considéré comme actif actif si une infirmière a été présente dans les 3 derniers mois pour une intervention (un suivi diabète ou une consultation infirmière)
    <?
}

function tableau($date){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_diab, $regions, $reg;


    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

    $tab_date=split('-', $date);

    echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";

    foreach ($tcabinet as $cab)
    {
//	$tpat[$cab]=0;
        $tcabinet_util[$cab]=0;

    }

    foreach($reg as $region){
        $t_diab[$region]=0;
    }

    foreach($tcabinet as $cab){
        $tcabinet_util[$cab]=0;
    }

    $date2mois=date("Y-m-d", mktime(1, 1, 1, $tab_date[1]-2, $tab_date[2], $tab_date[0]));
    $date3mois=date("Y-m-d", mktime(1, 1, 1, $tab_date[1]-3, $tab_date[2], $tab_date[0]));

    $req="SELECT cabinet from evaluation_infirmier, dossier where ".
        "dossier.id=evaluation_infirmier.id and date>='$date3mois' group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        $tcabinet_util[$cab]=1;
    }
    $req="SELECT cabinet from suivi_diabete, dossier where ".
        "dossier.id=suivi_diabete.dossier_id and dsuivi>='$date3mois' group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        $tcabinet_util[$cab]=1;
    }

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
    $t_diab['eval']=0;
    $cab_prec="";

    while(list($cab, $pat) = mysql_fetch_row($res)) {
//	 $tcabinet[] = $cab;

        if($cab!=$cab_prec)
        {
            if($tcabinet_util[$cab]==1){
                $t_diab['tot']=$t_diab['tot']+$t_diab[$cab];
                if(!isset($t_diab[$regions[$cab]])){
                    $t_diab[$regions[$cab]]=0;
                }
                $t_diab[$regions[$cab]]=$t_diab[$regions[$cab]]+$t_diab[$cab];
                // $t_diab["tot"]=$t_diab["tot"]+$t_diab[$cab];
            }
            $cab_prec=$cab;

        }
    }

    ?>
    <br>
    <br>
    <table border=1 align='center'>
        <?php

        ///////////////Respect des examens //////////////////////////

        $req="SELECT cabinet, dossier_id, DATE_ADD(max(dHBA), INTERVAL 8 MONTH), DATE_ADD(max(dExaFil), INTERVAL 14 MONTH),".
            " DATE_ADD(max(dExaPieds), INTERVAL 14 MONTH), DATE_ADD(max(dChol), INTERVAL 14 MONTH), ".
            "DATE_ADD(max(suivi_diabete.dCreat), INTERVAL 14 MONTH), DATE_ADD(max(dAlbu),INTERVAL 14 MONTH), ".
            "DATE_ADD(max(dFond), INTERVAL 14 MONTH), DATE_ADD(max(dECG), INTERVAL 14 MONTH) ".
            "from suivi_diabete,dossier where cabinet!='zTest'  and cabinet!='ergo' and cabinet!='irdes'  ".
            "and cabinet!='jgomes' and dossier.cabinet!='sbirault' and suivi_diabete".
            ".dossier_id = dossier.id  AND ( (dossier.actif='oui' AND dossier.dcreat<='$date2mois') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date2mois' AND dossier.dcreat<='$date2mois')) ".
            "and ((dHBA is not NULL and dHBA <= '$date2mois' and DATE_ADD(dHBA, INTERVAL 8 MONTH) >= '$date') ".
            " or ((dExaFil is not NULL and dExaFil<='$date2mois' and DATE_ADD(dExaFil, ".
            "INTERVAL 14 MONTH) >= '$date') or (dExaPieds is not NULL and dExaPieds <='$date2mois' and ".
            "DATE_ADD(dExaPieds, INTERVAL 14 MONTH) >= '$date')) or ".
            "((dChol is not NULL and dChol<='$date2mois' and DATE_ADD(dChol, INTERVAL 14 MONTH) >= '$date') ".
            " or (dLDL is not NULL and dLDL<='$date2mois' and DATE_ADD(dLDL, INTERVAL 14 MONTH) >= '$date')  ".
            "or (suivi_diabete.dCreat is not NULL and suivi_diabete.dCreat<='$date2mois' and DATE_ADD(suivi_diabete.dCreat, ".
            "INTERVAL 14 MONTH) >= '$date') or (dAlbu is not NULL ".
            "and dAlbu<='$date2mois' and DATE_ADD(dAlbu, INTERVAL 14 MONTH) >= '$date') or (dFond is not NULL and ".
            "dFond<='$date2mois' and DATE_ADD(dFond, INTERVAL 14 MONTH) >= '$date') or (dECG is not NULL and ".
            "dECG<='$date2mois' and DATE_ADD(dECG, INTERVAL 14 MONTH) >= ".
            "'$date'))) GROUP by numero order by cabinet, numero";
        /*$req="SELECT cabinet, count(*) ".
                 "FROM suivi_diabete, dossier ".
                 "WHERE cabinet!='zTest' and cabinet!='irdes'  ".
                 "AND actif='oui' ".
                 "AND suivi_diabete.dossier_id=dossier.id ".
                 "and ((dsuivi is not NULL and DATE_ADD(dsuivi, ".
                 "INTERVAL 1 YEAR) >= CURDATE())) ".
                 "GROUP BY cabinet, dossier_id ".
                 "ORDER BY cabinet ";*/
        // echo $req;
        //die;

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]['hba']=0;
            $tpat[$cab]['exafil']=0;
            $tpat[$cab]['pied']=0;
            $tpat[$cab]['chol']=0;
            $tpat[$cab]['creat']=0;
            $tpat[$cab]['albu']=0;
            $tpat[$cab]['fond']=0;
            $tpat[$cab]['ecg']=0;
            $t_diab[$cab]=0;
        }

        $tpat['tot']['hba']=0;
        $tpat['tot']['exafil']=0;
        $tpat['tot']['pied']=0;
        $tpat['tot']['chol']=0;
        $tpat['tot']['creat']=0;
        $tpat['tot']['albu']=0;
        $tpat['tot']['fond']=0;
        $tpat['tot']['ecg']=0;

        foreach($reg as $region){
            $tpat[$region]['hba']=0;
            $tpat[$region]['exafil']=0;
            $tpat[$region]['pied']=0;
            $tpat[$region]['chol']=0;
            $tpat[$region]['creat']=0;
            $tpat[$region]['albu']=0;
            $tpat[$region]['fond']=0;
            $tpat[$region]['ecg']=0;
        }

        $cab_prec="";
        // $t_diab['tot']=0;


        while(list($cabinet, $dossier_id, $dHBA, $dExaFil, $dExaPieds, $dChol, $dCreat, $dAlbu,
            $dFond, $dECG) = mysql_fetch_row($res)) {

            $req2="SELECT id from evaluation_infirmier where ".
                "id='$dossier_id' and date<='$date'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            if(mysql_num_rows($res2)>0){
                $t_diab[$cabinet]=$t_diab[$cabinet]+1;
                if(($dHBA!='')&&($dHBA!='NULL')){
                    if(diffmois($dHBA, $date2mois)<=0)
                    {
                        if($tcabinet_util[$cabinet]==1){
                            $tpat['tot']['hba'] = $tpat['tot']["hba"]+1;
                            $tpat[$cabinet]['hba'] = $tpat[$cabinet]["hba"]+1;
                            if(!isset($tpat[$regions[$cabinet]]['hba'])){
                                $tpat[$regions[$cabinet]]['hba']=0;
                            }
                            $tpat[$regions[$cabinet]]['hba']=$tpat[$regions[$cabinet]]['hba']+1;
                        }

                    }
                }

                if(($dExaFil!='')&&($dExaFil!='NULL'))
                {
                    if(diffmois($dExaFil, $date2mois)<=0)
                    {
                        if($tcabinet_util[$cabinet]==1){
                            $tpat['tot']['exafil'] = $tpat['tot']["exafil"]+1;
                            $tpat[$cabinet]['exafil'] = $tpat[$cabinet]["exafil"]+1;
                            if(!isset($tpat[$regions[$cabinet]]['exafil'])){
                                $tpat[$regions[$cabinet]]['exafil']=0;
                            }
                            $tpat[$regions[$cabinet]]['exafil']=$tpat[$regions[$cabinet]]['exafil']+1;
                        }

                    }
                }

                if(($dExaPieds!='')&&($dExaPieds!='NULL'))
                {
                    if(diffmois($dExaPieds, $date2mois)<=0)
                    {
                        if($tcabinet_util[$cabinet]==1){
                            $tpat['tot']['pied'] = $tpat['tot']["pied"]+1;
                            $tpat[$cabinet]['pied'] = $tpat[$cabinet]["pied"]+1;
                            if(!isset($tpat[$regions[$cabinet]]['pied'])){
                                $tpat[$regions[$cabinet]]['pied']=0;
                            }
                            $tpat[$regions[$cabinet]]['pied']=$tpat[$regions[$cabinet]]['pied']+1;
                        }

                    }
                }

                if(($dChol!='')&&($dChol!='NULL'))
                {
                    if(diffmois($dChol, $date2mois)<=0)
                    {
                        if($tcabinet_util[$cabinet]==1){
                            $tpat['tot']['chol'] = $tpat['tot']["chol"]+1;
                            $tpat[$cabinet]['chol'] = $tpat[$cabinet]["chol"]+1;
                            if(!isset($tpat[$regions[$cabinet]]['chol'])){
                                $tpat[$regions[$cabinet]]['chol']=0;
                            }
                            $tpat[$regions[$cabinet]]['chol']=$tpat[$regions[$cabinet]]['chol']+1;
                        }

                    }
                }

                if(($dCreat!='')&&($dCreat!='NULL'))
                {
                    if(diffmois($dCreat, $date2mois)<=0)
                    {
                        if($tcabinet_util[$cabinet]==1){
                            $tpat['tot']['creat'] = $tpat['tot']["creat"]+1;
                            $tpat[$cabinet]['creat'] = $tpat[$cabinet]["creat"]+1;
                            if(!isset($tpat[$regions[$cabinet]]['creat'])){
                                $tpat[$regions[$cabinet]]['creat']=0;
                            }
                            $tpat[$regions[$cabinet]]['creat']=$tpat[$regions[$cabinet]]['creat']+1;
                        }

                    }
                }

                if(($dAlbu!='')&&($dAlbu!='NULL'))
                {
                    if(diffmois($dAlbu, $date2mois)<=0)
                    {
                        if($tcabinet_util[$cabinet]==1){
                            $tpat['tot']['albu'] = $tpat['tot']["albu"]+1;
                            $tpat[$cabinet]['albu'] = $tpat[$cabinet]["albu"]+1;
                            if(!isset($tpat[$regions[$cabinet]]['albu'])){
                                $tpat[$regions[$cabinet]]['albu']=0;
                            }
                            $tpat[$regions[$cabinet]]['albu']=$tpat[$regions[$cabinet]]['albu']+1;
                        }

                    }
                }

                if(($dFond!='')&&($dFond!='NULL'))
                {
                    if(diffmois($dFond, $date2mois)<=0)
                    {
                        if($tcabinet_util[$cabinet]==1){
                            $tpat['tot']['fond'] = $tpat['tot']["fond"]+1;
                            $tpat[$cabinet]['fond'] = $tpat[$cabinet]["fond"]+1;
                            if(!isset($tpat[$regions[$cabinet]]['fond'])){
                                $tpat[$regions[$cabinet]]['fond']=0;
                            }
                            $tpat[$regions[$cabinet]]['fond']=$tpat[$regions[$cabinet]]['fond']+1;
                        }

                    }
                }

                if(($dECG!='')&&($dECG!='NULL'))
                {
                    if(diffmois($dECG, $date2mois)<=0)
                    {
                        if($tcabinet_util[$cabinet]==1){
                            $tpat['tot']['ecg'] = $tpat['tot']["ecg"]+1;
                            $tpat[$cabinet]['ecg'] = $tpat[$cabinet]["ecg"]+1;
                            if(!isset($tpat[$regions[$cabinet]]['ecg'])){
                                $tpat[$regions[$cabinet]]['ecg']=0;
                            }
                            $tpat[$regions[$cabinet]]['ecg']=$tpat[$regions[$cabinet]]['ecg']+1;
                        }

                    }
                }
            }


        }
        foreach($reg as $region){
            $t_diab[$region]=0;
        }
        $t_diab["tot"]=0;
        foreach($regions as $cab=>$region){
            if($tcabinet_util[$cab]==1){
                $t_diab[$region]=$t_diab[$region]+$t_diab[$cab];
                $t_diab["tot"]=$t_diab["tot"]+$t_diab[$cab];
            }
        }

        ?>

        <tr>
            <td>Taux de respect des examens &nbsp;</td>
            <td align="center"> <b>&nbsp;moyenne</b>	 &nbsp;</td>
            <?php

            foreach($reg as $region){
                if($region!=""){
                    echo "<td align='center'> <b>&nbsp;moyenne $region</b>	 &nbsp;</td>";
                }
            }

            // print_r($tpat);
            foreach($tcabinet as $cab) {
                if (($tcabinet_util[$cab]==0)||($t_diab[$cab]==0))
                {
                    $taux_hba[$cab]=$taux_exafil[$cab]=$taux_pied[$cab]=$taux_chol[$cab]=$taux_creat[$cab]="ND";
                    $taux_albu[$cab]=$taux_fond[$cab]=$taux_ecg[$cab]="ND";
                }
                else
                {
                    $taux_hba[$cab]=round($tpat[$cab]['hba']/$t_diab[$cab]*100);
                    $taux_hba[$cab].="%";

                    $taux_exafil[$cab]=round($tpat[$cab]['exafil']/$t_diab[$cab]*100);
                    $taux_exafil[$cab].="%";

                    $taux_pied[$cab]=round($tpat[$cab]['pied']/$t_diab[$cab]*100);
                    $taux_pied[$cab].="%";

                    $taux_chol[$cab]=round($tpat[$cab]['chol']/$t_diab[$cab]*100);
                    $taux_chol[$cab].="%";

                    $taux_creat[$cab]=round($tpat[$cab]['creat']/$t_diab[$cab]*100);
                    $taux_creat[$cab].="%";

                    $taux_albu[$cab]=round($tpat[$cab]['albu']/$t_diab[$cab]*100);
                    $taux_albu[$cab].="%";

                    $taux_fond[$cab]=round($tpat[$cab]['fond']/$t_diab[$cab]*100);
                    $taux_fond[$cab].="%";

                    $taux_ecg[$cab]=round($tpat[$cab]['ecg']/$t_diab[$cab]*100);
                    $taux_ecg[$cab].="%";
                }
                ?>
                <td><b><?php echo $tville[$cab];?></b></td>
                <?php
            }

            ?>
        </tr>
        <?php

        if ($t_diab['tot']==0)
        {
            $taux_hba['tot']=$taux_exafil['tot']=$taux_pied['tot']=$taux_chol['tot']=$taux_creat['tot']='ND';
            $taux_albu['tot']=$taux_fond['tot']=$taux_ecg['tot']="ND";
        }
        else
        {
            $taux_hba['tot']=round($tpat['tot']['hba']/$t_diab['tot']*100);
            $taux_hba['tot'].="%";

            $taux_exafil['tot']=round($tpat['tot']['exafil']/$t_diab['tot']*100);
            $taux_exafil['tot'].="%";

            $taux_pied['tot']=round($tpat['tot']['pied']/$t_diab['tot']*100);
            $taux_pied['tot'].="%";

            $taux_chol['tot']=round($tpat['tot']['chol']/$t_diab['tot']*100);
            $taux_chol['tot'].="%";

            $taux_creat['tot']=round($tpat['tot']['creat']/$t_diab['tot']*100);
            $taux_creat['tot'].="%";

            $taux_albu['tot']=round($tpat['tot']['albu']/$t_diab['tot']*100);
            $taux_albu['tot'].="%";

            $taux_fond['tot']=round($tpat['tot']['fond']/$t_diab['tot']*100);
            $taux_fond['tot'].="%";

            $taux_ecg['tot']=round($tpat['tot']['ecg']/$t_diab['tot']*100);
            $taux_ecg['tot'].="%";
        }


        foreach($reg as $region){
            if ($t_diab[$region]==0)
            {
                $taux_hba[$region]=$taux_exafil[$region]=$taux_pied[$region]=$taux_chol[$region]=$taux_creat[$region]='ND';
                $taux_albu[$region]=$taux_fond[$region]=$taux_ecg[$region]="ND";
            }
            else
            {
                $taux_hba[$region]=round($tpat[$region]['hba']/$t_diab[$region]*100);
                $taux_hba[$region].="%";

                $taux_exafil[$region]=round($tpat[$region]['exafil']/$t_diab[$region]*100);
                $taux_exafil[$region].="%";

                $taux_pied[$region]=round($tpat[$region]['pied']/$t_diab[$region]*100);
                $taux_pied[$region].="%";

                $taux_chol[$region]=round($tpat[$region]['chol']/$t_diab[$region]*100);
                $taux_chol[$region].="%";

                $taux_creat[$region]=round($tpat[$region]['creat']/$t_diab[$region]*100);
                $taux_creat[$region].="%";

                $taux_albu[$region]=round($tpat[$region]['albu']/$t_diab[$region]*100);
                $taux_albu[$region].="%";

                $taux_fond[$region]=round($tpat[$region]['fond']/$t_diab[$region]*100);
                $taux_fond[$region].="%";

                $taux_ecg[$region]=round($tpat[$region]['ecg']/$t_diab[$region]*100);
                $taux_ecg[$region].="%";
            }
        }

        ?>

        <tr>
            <td>HBA1c<sup>1</sup></td>
            <td align='right'><?php echo $taux_hba['tot']; ?></td>
            <?php

            foreach($reg as $region){
                if($region!=""){
                    echo "<td align='right'>".$taux_hba[$region]."</td>";
                }
            }


            foreach($tcabinet as $cab) {
                ?>
                <td align='right'><?php echo $taux_hba[$cab]; ?></td>
                <?php
            }
            ?>

        </tr>
        <td>Examen au monofilament<sup>2</sup></td>
        <td align='right'><?php echo $taux_exafil['tot']; ?></td>
        <?php

        foreach($reg as $region){
            if($region!=""){
                echo "<td align='right'>".$taux_exafil[$region]."</td>";
            }
        }

        foreach($tcabinet as $cab) {?>
            <td align='right'><?php echo $taux_exafil[$cab]; ?></td>

            <?php
        }
        ?>

        </tr>
        <td>Examen des pieds<sup>3</sup></td>
        <td align='right'><?php echo $taux_pied['tot']; ?></td>
        <?php

        foreach($reg as $region){
            if($region!=""){
                echo "<td align='right'>".$taux_pied[$region]."</td>";
            }
        }

        foreach($tcabinet as $cab) {
            ?>
            <td align='right'><?php echo $taux_pied[$cab]; ?></td>

            <?php
        }
        ?>

        </tr>
        <td>Dosage du HDL - Cholestérol<sup>4</sup></td>
        <td align='right'><?php echo $taux_chol['tot']; ?></td>
        <?php

        foreach($reg as $region){
            if($region!=""){
                echo "<td align='right'>".$taux_chol[$region]."</td>";
            }
        }

        foreach($tcabinet as $cab) {
            ?>
            <td align='right'><?php echo $taux_chol[$cab]; ?></td>

            <?php
        }
        ?>

        </tr>
        <td>Créatinémie<sup>5</sup></td>
        <td align='right'><?php echo $taux_creat['tot']; ?></td>
        <?php

        foreach($reg as $region){
            if($region!=""){
                echo "<td align='right'>".$taux_creat[$region]."</td>";
            }
        }

        foreach($tcabinet as $cab) {
            ?>
            <td align='right'><?php echo $taux_creat[$cab]; ?></td>

            <?php
        }
        ?>

        </tr>
        <td>Micro Albuminurie<sup>6</sup></td>
        <td align='right'><?php echo $taux_albu['tot']; ?></td>
        <?php

        foreach($reg as $region){
            if($region!=""){
                echo "<td align='right'>".$taux_albu[$region]."</td>";
            }
        }

        foreach($tcabinet as $cab) {
            ?>
            <td align='right'><?php echo $taux_albu[$cab]; ?></td>

            <?php
        }
        ?>

        </tr>
        <td>Fond d'oeil<sup>7</sup></td>
        <td align='right'><?php echo $taux_fond['tot']; ?></td>
        <?php

        foreach($reg as $region){
            if($region!=""){
                echo "<td align='right'>".$taux_fond[$region]."</td>";
            }
        }

        foreach($tcabinet as $cab) {
            ?>
            <td align='right'><?php echo $taux_fond[$cab]; ?></td>

            <?php
        }
        ?>

        </tr>
        <td>ECG<sup>8</sup></td>
        <td align='right'><?php echo $taux_ecg['tot']; ?></td>
        <?php

        foreach($reg as $region){
            if($region!=""){
                echo "<td align='right'>".$taux_ecg[$region]."</td>";
            }
        }

        foreach($tcabinet as $cab) {
            ?>
            <td align='right'><?php echo $taux_ecg[$cab]; ?></td>

            <?php
        }
        ?>

        </tr>


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
