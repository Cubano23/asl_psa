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
    <title>Trace des Int&eacute;grations</title>
    <style type="text/css">
        #fm{
            margin:0;
            padding:10px 30px;
        }
        .ftitle{
            font-size:14px;
            font-weight:bold;
            color:#666;
            padding:5px 0;
            margin-bottom:10px;
            border-bottom:1px solid #ccc;
        }
        .fitem{
            margin-bottom:5px;
        }
        .fitem label{
            display:inline-block;
            width:80px;
        }
    </style>

    <style type="text/css">
        form{
            margin:0;
            padding:0;
        }
        .dv-table td{
            border:0;
        }
        .dv-table input{
            border:1px solid #ccc;
        }
    </style>


    <style type="text/css">
        .datagrid-header .datagrid-cell{
            line-height:normal;
            height:auto;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="../../jquery/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="../../jquery/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="../../jquery/demo/demo.css">

    <script type="text/javascript" src="../../jquery/jquery.min.js"></script>
    <script type="text/javascript" src="../../jquery/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../../jquery/locale/easyui-lang-fr.js"></script>
    <script type="text/javascript">
        var url;
        // extend the 'equals' rule
        $.extend($.fn.validatebox.defaults.rules,
            {
                equals: {
                    validator: function(value,param){
                        var x = $(param[0]).val();
                        return value == x;

                    }
                    ,

                    message: 'Champs non identiques'
                }
            }
        );
        function utf8_encode( string )
        {
            return unescape( encodeURIComponent( string ) );
        }

        function formatValue(val,row){
            if(parseFloat(val)>=1.26)
                return '<font color="red">' + val+ '</font>';
            if(parseFloat(val)>=1.1)
                return '<font color="orange">' + val+ '</font>';

            return val;
        }

        function formatValueH(val,row){
            if(parseFloat(val)>=8)
                return '<font color="red">' + val+ '</font>';

            return val;
        }

    </script>

</head>
<body bgcolor=#FFE887>
<?php






// $sql = "SELECT liste_exam.id, liste_exam.numero, liste_exam.type_exam, liste_exam.date_exam FROM `liste_exam`,dossier WHERE DATE(liste_exam.dmaj)= "2014/07/28" and dossier.cabinet = 'Argenton' and liste_exam.id=dossier.id";
//        
function endsWith($haystack, $needle)
{
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}
include('reader.php');
$phase = 0;
$xls = "";
$cabinet = 	$_GET['cabinet'];
$dintegration =  $_GET['dintegration'];
$reportdir =    "./integration_logs/";
$reportfile =  $reportdir .  $_GET['report'];
$allvals =     $_GET['allvals'];
$table = "liste_exam_".$cabinet."_u";
$deleteflag = 0;
while ($phase < 10)
{

    switch ($phase)
    {

        case 0: //

            require("../global/entete.php");
            entete_asalee("Alertes Glyc&eacute;mie Dossiers Inconnus Asal&eacute;e");
            break;

        case 1:// Create Table
//              echo "Creating Table\n";  
            require_once ("Config.php");
            $config = new Config();

            require($config->inclus_path . "/accesbase.inc.php") ;


            # connexion aux données
            mysql_connect($serveur,$idDB,$mdpDB) or
            die("Impossible de se connecter au SGBD:". mysql_error());
            mysql_select_db($DB) or
            die("Impossible de se connecter à la base");


            $sqlcreate =
                "CREATE TABLE IF NOT EXISTS `".$table."` (
                `dossier` varchar(16) NOT NULL DEFAULT 'X',
                `type_exam` varchar(30) NOT NULL DEFAULT '',
                `date_exam` date NOT NULL DEFAULT '0000-00-00',
                `resultat1` varchar(30) DEFAULT NULL,
                PRIMARY KEY (`dossier`,`type_exam`,`date_exam` ),
                KEY `type_exam` (`type_exam`),
                KEY `date_exam` (`date_exam`)
                ) ENGINE=MyISAM DEFAULT CHARSET=latin1 ";


            mysql_query($sqlcreate );
            break;
        case 2: // Drop Table
            mysql_query("truncate table ".$table );

            break;

        case 3: // if zip unzip it
            $file = $reportfile;
            if(endsWith($reportfile, "zip"))
            {
                // get the absolute path to $file
                $path = pathinfo(realpath($file), PATHINFO_DIRNAME);
                $zip = new ZipArchive;
                $res = $zip->open($file);
                if ($res === TRUE) {
                    $xls = $reportdir .  $zip->getNameIndex(0);
                    // extract it to the path we determined above
                    $zip->extractTo($path);
                    $zip->close();
                    $deleteflag=1;

                } else {
                    echo "Erreur ouverture fichier compte-rendu";
                }
            }
            else
                $xls = $reportfile;



            break;

        case 4:
            ini_set("memory_limit","512M");
            $data = new Spreadsheet_Excel_Reader();
            $data->read($xls);
            break;
        case 5:
            $i=2;
            while(isset($data->sheets[2]['cells'][$i][2])&&($data->sheets[2]['cells'][$i][2]!=""))
            {
                $dossier = $data->sheets[2]['cells'][$i][2];
                $date_exam = $data->sheets[2]['cells'][$i][3];
                $valeur = isset($data->sheets[2]['cells'][$i][4])?$data->sheets[2]['cells'][$i][4]:'';
                $type_exam = $data->sheets[2]['cells'][$i][5];
                $raison = strtolower( $data->sheets[2]['cells'][$i][6]);
                $i++;
                if ( ((strpos($raison, "inconnu"))      ||  (strpos($raison, "non trouv"))   )
                    && ( (strtolower($type_exam) == "hba1c")
                        || (strtolower($type_exam) == "glycemie")
                    )
                )
                {

                    $sqlinsert = "INSERT IGNORE INTO ". $table." (`dossier`,  `type_exam`, `date_exam`, `resultat1`)".
                        " VALUES ('$dossier', '$type_exam','$date_exam','$valeur') ";

                    mysql_query($sqlinsert);

                }
            }
//                  echo $i;
            break;
        case 6:
            //        $data->close();
            if($deleteflag==1)
                unlink($xls);
            break;

    }

    $phase ++;



}

?>


<br />
<br />
<br />

<h2>Cabinet: <?php echo $cabinet ?></h2>

<table id="dg" class="easyui-datagrid" style="width:1000px"
       url="integration/glycemie_u_getdata.php?cabinet=<?php echo $cabinet ?>&dintegration=<?php echo $dintegration ?>&allvals=<?php echo $allvals ?>&val=1"
       title="Alertes Glyc&eacute;mie Dossiers Inconnus <=1.26 g/l"
       pagination="true" pageSize="50"
       rownumbers="true"
       singleSelect="true" fitColumns="true"
       nowrap="false" >
    <thead>
    <tr>

        <th field="glyc_dossier"  sortable="true" >Dossier</th>
        <th field="glyc_date"   >Date Examen</th>
        <th field="glyc_valeur"  sortable="true" formatter="formatValue" >Valeur</th>
        <th field="hba1c_date"    >Date HBA1c</th>
        <th field="hba1c_valeur"  formatter="formatValueH" >Valeur HBA1c</th>

    </tr>
    </thead>



</table>

<br />
<br />

<table id="dg2" class="easyui-datagrid" style="width:1000px"
       url="integration/glycemie_u_getdata.php?cabinet=<?php echo $cabinet ?>&dintegration=<?php echo $dintegration ?>&allvals=<?php echo $allvals ?>&val=2"
       title="Alertes Glyc&eacute;mie Dossiers Inconnus > 1.26 g/l"
       pagination="true" pageSize="50"
       rownumbers="true"
       singleSelect="true" fitColumns="true"
       nowrap="false" >
    <thead>
    <tr>

        <th field="glyc_dossier"  sortable="true" >Dossier</th>
        <th field="glyc_date"   >Date Examen</th>
        <th field="glyc_valeur"  sortable="true" formatter="formatValue" >Valeur</th>
        <th field="hba1c_date"    >Date HBA1c</th>
        <th field="hba1c_valeur"  formatter="formatValueH"  >Valeur HBA1c</th>

    </tr>
    </thead>



</table>


<div id="dlg-buttons2">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="javascript:window.close()">Fin</a>
</div>



<?php
// Log

require_once ("Config.php");
$config = new Config();

if($_SERVER['APPLICATION_ENV'] != 'dev-herve')
{
    require_once($config->webservice_path . "/AsaleeLog.php");
    LogAccess("", "glycemie_u", $UserIDLog, 'na', $cabinet , 0, "Liste Trace Integration Glycemie Inconnus:".$answerLog);
}


//laisser là pour contourner le non affichage des piwik de ids
echo("<br />");
?>

</body>
</html>
