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
    <title>Diab�tiques Chatillon sans 3-4 HBA1c</title>
</head>
<body bgcolor=#FFE887>

<?php
require_once "Config.php";
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");

# connexion aux donn�es
mysql_connect($serveur,$idDB,$mdpDB) or
    die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
    die("Impossible de se connecter � la base");


$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";

require("./global/entete.php");
//echo $loc;
require_once "../stats/writeexcel/class.writeexcel_workbookbig.inc.php";
require_once "../stats/writeexcel/class.writeexcel_worksheet.inc.php";

entete_asalee("Diab�tiques Chatillon sans 3-4 HBA1c");
?>
<!--<table cellpadding="2" cellspacing="2" border="0"
 style="text-align: left; width: 100%;">
  <tbody>
    <tr>
      <td style="width: 20%; vertical-align: top;">
      <br>
      <img src="<?php echo $loc; ?>/images/inf79.gif" alt="logo informed79"><br>
      ��<a href="mailto:contact@asalee.fr"><font size="-1">contact</font></a>
      </td>
      <td style="text-align: center; vertical-align: top;">
	       <span style="font-family: arial; font-weight: bold;">
 <?php
echo "
<i><font face='times new roman' size='32'>Asal�e</font><br>
<font face='times new roman'>Indicateurs d'�valuation Asal�e : nombre de patients vus en consultation</font></i>";
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

    # fen�tre glissante:
    if (isset($_GET['mois']) && isset($_GET['annee']))
    {
        etape_2($repete);
        exit;
    }

    # �tape 1 : identification du patient et de la date
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

            case 1:
                etape_1($repete);
                break;

            # �tape 2  : saisie des d�tails
            case 2:
                etape_2($repete);
                break;

            # �tape 3  : validation des donn�es et m�j base
            case 3:
                etape_3($repete);
                break;
        }
    }
} while($repete);

# fin de traitement principal


function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_tot;

    $req="SELECT cabinet, total_diab2, nom_cab, region ".
        "FROM account ".
        "WHERE region!='' and infirmiere!='' ".
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $reg=array();
    $plus3["tot"]=0;
    $total_diab["tot"]=0;

    $date2mois=date("Y-m-d", mktime(1, 1, 1, date("m")-2, date("d"), date("Y")));

    while(list($cab, $total_diab2, $ville, $region) = mysql_fetch_row($res)) {

        $t_diab[$cab]=0;

        $tville[$cab]=$ville;

        $regions[$cab]=$region;
        $nb_dossiers[$cab]=0;
        $plus3[$cab]=0;
        $total_diab[$cab]=0;
        $plus3[$region]=0;
        $total_diab[$region]=0;
        $rcva[$cab]=0;
        $rcva1an[$cab]=0;
        $nb_dossiers[$region]=0;
        $rcva[$region]=0;
        $rcva1an[$region]=0;

        if(!in_array($region, $reg)){
            $reg[]=$region;
        }
    }

    foreach($tville as $cab=>$ville){
        $tcabinet_util[$cab]=0;
    }

    $colonnes=array("A", "B", "C", "D", "E", "F", "G");
    $date1an=date("Y-m-d", mktime(1, 1, 1, date("m")-2, date('d'), date("Y")-1));
    $date3mois=date("Y-m-d", mktime(1, 1, 1, date("m")-3, date('d'), date("Y")));
    $req="SELECT cabinet from evaluation_infirmier, dossier where ".
        "dossier.id=evaluation_infirmier.id and date>='$date3mois' group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        if(isset($tville[$cab])){
            $tcabinet_util[$cab]=1;
        }
    }
    $req="SELECT cabinet from cardio_vasculaire_depart, dossier where ".
        "dossier.id=cardio_vasculaire_depart.id and date>='$date3mois' ".
        "group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        if(isset($tville[$cab])){
            $tcabinet_util[$cab]=1;
        }
    }

    $mois=array('01'=>'Janvier', '02'=>'F�vrier', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Ao�t', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'D�cembre');

    echo '<b>Donn�es � la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

    ?>
    <br>
    <br>

    <?php

    echo "<br><br><table border='1'><td nowrap></td><th>Id dans asal�e</th><th>N� dossier</th>".
        "<th>date 1er suivi diab�te</th>";

    echo "</tr>";

//Patients avec au moins un suivi
    $req="SELECT cabinet, id, numero, min(dsuivi) ".
        "FROM suivi_diabete, dossier ".
        "WHERE actif='oui' ".
        "AND suivi_diabete.dossier_id=dossier.id ".
        "GROUP BY cabinet, dossier_id ".
        "ORDER BY cabinet, numero ";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $cab_prec="";

    while(list($cab, $id,$numero, $dsuivi) = mysql_fetch_row($res)) {

        if($dsuivi<=$date1an){
            if(isset($tcabinet_util[$cab])){
                if($cab_prec!=$cab){
                    if($workbook){
                        $workbook->close();
                    }
                    $cab_prec=$cab;

                    $fich="./export/diabetiques moins 3 HBA1c $cab ".date("d-m-Y").".xls";
                    $workbook =& new writeexcel_workbookbig($fich); // on lui passe en param�tre le chemin de notre fichier


                    $worksheet =& $workbook->addworksheet("moins 3 HBA1c");
                    $worksheet->write("A1", "Id dans asal�e");
                    $worksheet->write("B1", "N� dossier");
                    $worksheet->write("C1", "date 1er suivi dans asal�e");
                    $worksheet->write("D1", "date HBA1c");
                    $worksheet->write("E1", "valeur HBA1c");
                    $worksheet->write("F1", "date HBA1c");
                    $worksheet->write("G1", "valeur HBA1c");
                    $l=1;
                }
                $req2="SELECT sortie, ADO from suivi_diabete where dossier_id='$id' order by dsuivi DESC limit 0,1";
                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
                list($sortie, $ADO)=mysql_fetch_row($res2);

                if((isset($regions[$cab]))&&($sortie!=1)&&($ADO!="aucun")&&($ADO!="")){


                    //Nombre de HBA1c r�alis�s sur les 12 derniers mois
                    $req="SELECT  resultat1, date_exam ".
                        "FROM liste_exam ".
                        "WHERE  DATE_ADD(date_exam, ".
                        "INTERVAL 1 YEAR) >= '$date2mois' and date_exam<='$date2mois' ".
                        "and type_exam='HBA1c' ".
                        "and id='$id' ";
                    //echo $req;
                    //die;
                    $res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


                    // $pat=mysql_num_rows($res2);

                    if(mysql_num_rows($res2)<3){


                        $l++;
                        $worksheet->write("A$l", $id);
                        $worksheet->write("B$l", $numero);
                        $worksheet->write("C$l", $dsuivi);

                        $col=3;
                        while(list($ResHBA, $dHBA)=mysql_fetch_row($res2)){
                            $cel=$colonnes[$col].$l;
                            $worksheet->write($cel, $dHBA);
                            $col++;
                            $cel=$colonnes[$col].$l;
                            $worksheet->write($cel, $ResHBA);
                            $col++;
                        }

                    }
                }
            }
        }



    }

    if($workbook){
        $workbook->close();
    }

// echo "</table>";

    ?>
    <br>
    <br>
    <?php

    echo "<sup>1</sup> Nb patients ayant eu 3 ou 4 HBA1c dans les 12 derniers mois/nb patients ayant eu au moins 1 suivi diab�te dans asal�e<br>";
    echo "<sup>2</sup> Nb patients dont la derni�re HBA1c est &lt;8.5 /nb patients ayant eu au moins 1 suivi diab�te dans asal�e<br>";
    echo "<sup>3</sup> Nb patients dont la derni�re HBA1c est &lt;7.5 /nb patients ayant eu au moins 1 suivi diab�te dans asal�e<br>";
    echo "<sup>4</sup> Nb patients dont le dernier LDL est &lt;1.5 /nb patients ayant eu au moins 1 suivi diab�te dans asal�e<br>";
    echo "<sup>5</sup> Nb patients dont le dernier LDL est &lt;1.3 /nb patients ayant eu au moins 1 suivi diab�te dans asal�e<br>";
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
    <sup>1</sup>Nombre de personnes ayant eu au moins une consultation/potentiel du cabinet
    <?php

}

function tableau($date){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_tot;

    $mois=array('01'=>'Janvier', '02'=>'F�vrier', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Ao�t', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'D�cembre');

    $tab_date=split('-', $date);

    echo "<b>Donn�es au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";

    /*foreach($tcabinet as $cab) {
         $t_tot[$cab]=0;
    }

    $req="SELECT cabinet, total_pat ".
             "FROM histo_account ".
             "WHERE cabinet!='zTest' and cabinet!='irdes'  ".
             "AND dmaj<='$date 23:59:59' ".
             "ORDER BY cabinet, dmaj ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");



    while(list($cab, $total_pat) = mysql_fetch_row($res)) {
         $t_tot[$cab]=$total_pat;
    }
    */

    $req="SELECT cabinet, count(*) ".
        "FROM dossier ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ";

    if($date>='2008-01-01'){
        $req.="and dossier.cabinet!='saint-varent' ";
    }

    $req.=	 "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
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
    $t_tot['tot']=0;
    $t_tot['eval']=0;
    $t_tot['eval2']=0;
    $t_tot['eval3']=0;
    $cab_prec="";

    foreach ($tcabinet as $cab)
    {
        $tcabinet_util[$cab]=0;

    }


    while(list($cab, $pat) = mysql_fetch_row($res)) {
//	 $tcabinet[] = $cab;

        if($cab!=$cab_prec)
        {
            $t_tot['tot']=$t_tot['tot']+$t_tot[$cab];
            $cab_prec=$cab;
            $tcabinet_util[$cab]=$t_tot[$cab];

            if((strcasecmp($cab, "clamecy")!=0)&&(strcasecmp($cab, "varzy")!=0))//||(strcasecmp($cab, 'brioux')==0)||
//			(strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
            {
                $t_tot['eval']=$t_tot['eval']+$t_tot[$cab];
            }
            elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                (strcasecmp($cab, "chiz�")==0))
            {
                $t_tot['eval2']=$t_tot['eval2']+$t_tot[$cab];
            }
            /*		else
                    {
                         $t_tot['eval3']=$t_tot['eval3']+$t_tot[$cab];
                    }
            */	 }
    }


    ?>

    <table border=1 width='100%'>
        <tr>
            <td></td>
            <td><b> Moyenne g�n�rale</b></td>
            <td><b> Moyenne cabinets 79 </b></td>
            <td><b> Moyenne cab 2005 </b></td>
            <td><b> Moyenne cab 2006 </b></td>
            <?php
            foreach($tcabinet as $cab) {
                ?>
                <td align='center'><b><?php echo $tville[$cab]; ?></b></td>
                <?php
            }
            ?>
        </tr>


        <?php

        ////////////////////EVALUATION INFIRMIER////////////////////////////////////

        $req="SELECT cabinet, count(*) ".
            "FROM dossier, evaluation_infirmier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ";

        if($date>='2008-01-01'){
            $req.=" and dossier.cabinet!='saint-varent' ";
        }

        $req.=	 "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND evaluation_infirmier.id =dossier.id ".
            "AND evaluation_infirmier.date<'$date' ".
            "GROUP BY cabinet, dossier.id ".
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

            if((strcasecmp($cab, "clamecy")!=0)&&(strcasecmp($cab, "varzy")!=0))//||(strcasecmp($cab, 'brioux')==0)||
//		(strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
            {
                $tpat['eval'] = $tpat['eval']+1;
            }
            elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                (strcasecmp($cab, "chiz�")==0))
            {
                $tpat['eval2'] = $tpat['eval2']+1;
            }
            /*	else
                {
                     $tpat['eval3'] = $tpat['eval3']+1;
                }
            */
        }


        ?>

        <tr>
            <td>Taux de patients vus en consultation<sup>1</sup></td>

            <td align='right'><?php echo round($tpat['tot']/$t_tot['tot']*100,0);?>%</td>
            <td align='right'><?php echo round($tpat['eval']/$t_tot['eval']*100,0);?>%</td>
            <td align='right'><?php echo ($t_tot['eval2']==0)?"ND":round($tpat['eval2']/$t_tot['eval2']*100,0);?>%</td>
            <td align='right'><?php echo ($t_tot['eval3']==0)?"ND":round($tpat['eval3']/$t_tot['eval3']*100,0);?>%</td>

            <?php

            foreach($tcabinet as $cab) {
                if ($tcabinet_util[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_tot[$cab]*100;
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
    <?php

}

?>
</body>
</html>
