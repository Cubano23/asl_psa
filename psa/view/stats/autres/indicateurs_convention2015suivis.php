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
    <title>Suivi des indicateurs ROSP 2015 patients suivis</title>

    <link rel="stylesheet" type="text/css" href="../../jquery/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="../../jquery/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="../../jquery/demo/demo.css">
    <script type="text/javascript" src="../../jquery/jquery.min.js"></script>
    <script type="text/javascript" src="../../jquery/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../../jquery/locale/easyui-lang-fr.js"></script>
    <script type="text/javascript">
        $('#table1').datagrid({
            columns:[[
                {field:'r0',title:'Type',
                    styler: function(value,row,index){
                        {
                            return 'background-color:#ffee00;color:red;';
                            // the function can return predefined css class and inline style
                            // return {class:'c1',style:'color:red'}
                        }
                    }
                }
            ]]
        });
    </script>
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

require("../global/entete.php");

entete_asalee("Suivi des indicateurs ROSP 2015");
?>
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


    $hba1c85["tot"]=0;
    $hba1c75["tot"]=0;
    $ldl15["tot"]=0;
    $ldl13["tot"]=0;
    $fond["tot"]=0;

    date_default_timezone_set('Europe/Paris');
    $date2mois=date("Y-m-d", mktime(1, 1, 1, date("m")-2, date("d"), date("Y")));
    $date1an=date("d/m/Y", mktime(1, 1, 1, date("m")-2, date("d"), date("Y")-1));
    $d1an=date("Y-m-d", mktime(1, 1, 1, date("m")-2, date("d"), date("Y")-1));

    while(list($cab, $total_diab2, $ville, $region) = mysql_fetch_row($res)) {

        $t_diab[$cab]=0;

        $tville[$cab]=$ville;

        $regions[$cab]=$region;
        $nb_dossiers[$cab]=0;
        $plus3[$cab]=0;
        $hba1c85[$cab]=0;
        $hba1c75[$cab]=0;
        $ldl15[$cab]=0;
        $ldl13[$cab]=0;
        $fond[$cab]=0;

        $total_diab[$cab]=0;

        $plus3[$region]=0;
        $hba1c85[$region]=0;
        $hba1c75[$region]=0;
        $ldl15[$region]=0;
        $ldl13[$region]=0;
        $fond[$region]=0;

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






    echo "<table id=\"table12\" class=\"easyui-datagrid\"  data-options=\"singleSelect:true,rownumbers:true,nowrap:false\" title=\"Indicateurs ROSP 2015 patients suivis dans PSA\" url=\"rosp/getdata_suivis.php?startdate=2015\"  >".
        "<thead frozen=\"true\"><tr>".
        "<th data-options=\"field:'r02' , styler: function(value,row,index){return 'font-size:10px';}\"    width=\"500\">Type</th>".
        "<th data-options=\"field:'ci02'\">Cible</th>".
        "<th data-options=\"field:'i02'\">Interm�diare</th>".

        "<th data-options=\"field:'c02'\">Nb cabinet respectant l'objectif</th>".
        "<th data-options=\"field:'m02'\">Moyenne</th>";
    echo "</tr></thead>".
        "<thead><tr>";
    $nbv=0;
    foreach($tville as $ville){
        $nbv = $nbv+1;
        echo "<th data-options=\"field:'vx".$nbv."'\">$ville</th>";
    }
    echo "</tr></thead>";
    echo "</table>";









}




?>
</body>
</html>
