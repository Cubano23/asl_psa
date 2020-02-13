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

set_time_limit(120);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="content-type"
          content="text/html; charset=ISO-8859-15">
    <title>Comparaison patients HTA Chatillon</title>
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

require("./global/entete.php");
//echo $loc;
require_once "../stats/writeexcel/class.writeexcel_workbookbig.inc.php";
require_once "../stats/writeexcel/class.writeexcel_worksheet.inc.php";

entete_asalee("comparaison patients HTA");
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

    $liste_jga=array('N2',
        'N24',
        'N39',
        'N92',
        'N116',
        'N152',
        'N167',
        'N203',
        'N231',
        'N266',
        'N298',
        'N302',
        'N305',
        'N306',
        'N321',
        'N336',
        'N349',
        'N372',
        'N375',
        'N411',
        'N418',
        'N456',
        'N466',
        'N467',
        'N493',
        'N505',
        'N582',
        'N586',
        'N587',
        'N626',
        'N629',
        'N655',
        'N673',
        'N697',
        'N733',
        'N734',
        'N738',
        'N739',
        'N787',
        'N804',
        'N805',
        'N814',
        'N868',
        'N920',
        'N930',
        'N936',
        'N948',
        'N979',
        'N980',
        'N983',
        'N1012',
        'N1022',
        'N1027',
        'N1149',
        'N1184',
        'N1209',
        'N1256',
        'N1275',
        'N1296',
        'N1312',
        'N1356',
        'N1363',
        'N1377',
        'N1385',
        'N1420',
        'N1516',
        'N1543',
        'N1555',
        'N1562',
        'N1587',
        'N1588',
        'N1595',
        'N1717',
        'N1742',
        'N1747',
        'N1752',
        'N1831',
        'N1841',
        'N1852',
        'N1872',
        'N1924',
        'N1938',
        'N1939',
        'N1944',
        'N1958',
        'N1990',
        'N2005',
        'N2007',
        'N2031',
        'N2041',
        'N2059',
        'N2061',
        'N2068',
        'N2091',
        'N2118',
        'N2122',
        'N2145',
        'N2167',
        'N2171',
        'N2228',
        'N2263',
        'N2281',
        'N2303',
        'N2308',
        'N2309',
        'N2310',
        'N2318',
        'N2354',
        'N2356',
        'N2367',
        'N2393',
        'N2396',
        'N2397',
        'N2399',
        'N2400',
        'N2424',
        'N2436',
        'N2491',
        'N2496',
        'N2516',
        'N2569',
        'N2579',
        'N2595',
        'N2608',
        'N2644',
        'N2650',
        'N2699',
        'N2740',
        'N2741',
        'N2750',
        'N2772',
        'N2831',
        'N2895',
        'N2906',
        'N2909',
        'N2921',
        'N2953',
        'N3067',
        'N3086',
        'N3102',
        'N3110',
        'N3139',
        'N3143',
        'N3146',
        'N3147',
        'N3161',
        'N3195',
        'N3208',
        'N3212',
        'N3280',
        'N3292',
        'N3296',
        'N3329',
        'N3377',
        'N3378',
        'N3414',
        'N3427',
        'N3433',
        'N3441',
        'N3474',
        'N3479',
        'N3492',
        'N3498',
        'N3513',
        'N3527',
        'N3529',
        'N3533',
        'N3534',
        'N3581',
        'N3613',
        'N3615',
        'N3657',
        'N3659',
        'N3670',
        'N3679',
        'N3680',
        'N3716',
        'N3718',
        'N3810',
        'N3812',
        'N3815',
        'N3829',
        'N3865',
        'N3892',
        'N3905',
        'N3908',
        'N3919',
        'N3953',
        'N3965',
        'N3984',
        'N3986',
        'N3998',
        'N4001',
        'N4068',
        'N4069',
        'N4119',
        'N4125',
        'N4157',
        'N4160',
        'N4171',
        'N4174',
        'N4190',
        'N4200',
        'N4266',
        'N4304',
        'N4364',
        'N4382',
        'N4393',
        'N4399',
        'N4527',
        'N4528',
        'N4539',
        'N4573',
        'N4609',
        'N4714',
        'N4769',
        'N4800',
        'N4824',
        'N4834',
        'N4881',
        'N4934',
        'N4937',
        'N5008',
        'N5076',
        'N5156',
        'N5235',
        'N5237',
        'N5254',
        'N5368',
        'N5373',
        'N5374',
        'N5387',
        'N5527',
        'N5528',
        'N5555',
        'N5600',
        'N5612',
        'N5621',
        'N5698',
        'N5715',
        'N5733',
        'N5738',
        'N5821',
        'N5842',
        'N5847',
        'N5905',
        'N5916',
        'N5966',
        'N6078',
        'N6081',
        'N6095',
        'N6114',
        'N6126',
        'N6132',
        'N6135',
        'N6153',
        'N6183',
        'N6190',
        'N6225',
        'N6242',
        'N6274',
        'N6278',
        'N6289',
        'N6336',
        'N6358',
        'N6363',
        'N6365',
        'N6370',
        'N6371',
        'N6379',
        'N6401',
        'N6426',
        'N6445',
        'N6454',
        'N6457',
        'N6460',
        'N6494',
        'N6496',
        'N6507',
        'N6580',
        'N6603',
        'N6607',
        'N6690',
        'N6739',
        'N6746',
        'N6825',
        'N6830',
        'N6833',
        'N6834',
        'N6840',
        'N6841',
        'N6907',
        'N6961',
        'N6966',
        'N6974',
        'N6980',
        'N7051',
        'N7057',
        'N7074',
        'N7089',
        'N7120',
        'N7128',
        'N7191',
        'N7222',
        'N7527',
        'N7633',
        'N7651',
        'N7837',
        'N8141',
        'N8193',
        'N8221',
        'N8228',
        'N8230',
        'N8258',
        'N8259',
        'N8289',
        'N8383',
        'N8462',
        'N8520',
        'N8878',
        'N8899',
        'N8964',
        'N9000',
        'N9110',
        'N9172',
        'N9313',
        'N9369',
        'N9782',
        'N9809',
        'N9872',
        'N9932',
        'N10114',
        'N10140',
        'N10282',
        'N10297',
        'N10303',
        'N10313',
        'N10324',
        'N10332',
        'N10418',
        'N10467',
        'N10470',
        'N10554',
        'N10703',
        'N10736',
        'N10870',
        'N10964',
        'N11154',
        'N11183',
        'N11200',
        'N11230',
        'N11281',
        'N11322',
        'N11460',
        'N11478',
        'N11479',
        'N11530',
        'N11537',
        'N11582',
        'N11585',
        'N11733',
        'N11840',
        'N11844',
        'N12015',
        'N12129',
        'N12276',
        'N12302',
        'N12315',
        'N12353',
        'N12412',
        'N12413',
        'N12431',
        'N12526',
        'N12622',
        'N12633',
        'N12717',
        'N12757',
        'N12768',
        'N12988',
        'N13000',
        'N13027',
        'N13084',
        'N13144',
        'N13186',
        'N13189',
        'N13231',
        'N13263',
        'N13308',
        'N13357',
        'N13406',
        'N13419',
        'N13516',
        'N13556',
        'N13625',
        'N13627',
        'N13668',
        'N13675',
        'N13802',
        'N13879',
        'N13884',
        'N13886',
        'N13924',
        'N13976',
        'N13996',
        'N14067',
        'N14161',
        'N14190',
        'N14197',
        'N14209',
        'N14258',
        'N14353',
        'N14428',
        'N14478',
        'N14580',
        'N14590',
        'N14607',
        'N14665',
        'N14728',
        'N14741',
        'N14775',
        'N14794',
        'N14842',
        'N14940',
        'N15108',
        'N15126',
        'N15208',
        'N15242',
        'N15361',
        'N15407',
        'N15428',
        'N15498',
        'N15512',
        'N15522',
        'N15525',
        'N15603',
        'N15646',
        'N15651',
        'N15663',
        'N15931',
        'N15991',
        'N16109',
        'N16158',
        'N16182',
        'N16183',
        'N16201',
        'N16383',
        'N16409',
        'N16430',
        'N16478',
        'N16500',
        'N16636',
        'N16682',
        'N16722',
        'N16744',
        'N16768',
        'N17044',
        'N17078',
        'N17114',
        'N17130',
        'N17266',
        'N17298',
        'N17401',
        'N17541',
        'N17595',
        'N17609',
        'N17612',
        'N17665',
        'N17690',
        'N17753',
        'N17780',
        'N17790',
        'N17826',
        'N17842',
        'N17912',
        'N17919',
        'N18007',
        'N18009',
        'N18012',
        'N18199',
        'N18217',
        'N18223',
        'N18467',
        'N18514',
        'N18573',
        'N18602',
        'N18629',
        'N18636',
        'N18638',
        'N18639',
        'N18699',
        'N18786',
        'N18797',
        'N18808',
        'N18831',
        'N18913',
        'N18949',
        'N18977',
        'N18978',
        'N19039',
        'N19131',
        'N19386',
        'N19498',
        'N19523',
        'N19533',
        'N19566',
        'N19800',
        'N19978');

    $nb=$nb_modif=0;
    foreach($liste_jga as $numero){
        $req="SELECT id from dossier where cabinet='chatillon' and numero='$numero'";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        if(mysql_num_rows($res)==1){
            list($id)=mysql_fetch_row($res);

            $req="SELECT dossier_id from suivi_diabete where dossier_id='$id'";
            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            if(mysql_num_rows($res)==0){//Aucun suivi diabète intégré pour ce patient
                $req="SELECT date, hta from cardio_vasculaire_depart WHERE id='$id' order BY date DESC limit 0, 1";
                $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

                if(mysql_num_rows($res)==0){//Aucun suivi RCVA indiqué pour le suivi
                    $req="INSERT INTO cardio_vasculaire_depart SET id='$id', date='".date("Ymd")."', ".
                        "hta='oui'";
                    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

                    $nb++;
                }
                else{//Au moins un suivi RCVA créé
                    list($date, $hta)=mysql_fetch_row($res);

                    if($hta!="oui"){
                        $req="UPDATE cardio_vasculaire_depart set hta='oui' where id='$id' and date='$date'";
                        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
                        $nb_modif++;
                    }
                }
            }
        }
    }

    echo "$nb créé ; $nb_modif modif fin";

}

?>
</body>
</html>
