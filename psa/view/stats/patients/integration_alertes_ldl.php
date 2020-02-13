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
    <title>Trace des Int�grations</title>
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
            if(parseFloat(val)>=2.0)
                return '<font color="red">' + val+ '</font>';
            return val;
        }


    </script>

</head>
<body bgcolor=#FFE887>
<?php

require("../global/entete.php");
entete_asalee("Alertes LDL Dossiers Int&eacute;gr&eacute;s");




$table = "integration";

$logs = "./integration_logs/";

$cabinet = 	$_GET['cabinet'];
$dintegration =  $_GET['dintegration'];
$reportfile =  $_GET['report'];
$allvals =     $_GET['allvals'];
// $sql = "SELECT liste_exam.id, liste_exam.numero, liste_exam.type_exam, liste_exam.date_exam FROM `liste_exam`,dossier WHERE DATE(liste_exam.dmaj)= "2014/07/28" and dossier.cabinet = 'Argenton' and liste_exam.id=dossier.id";
//        


?>


<br />
<br />
<br />

<h2>Cabinet: <?php echo $cabinet ?></h2>

<table id="dg" class="easyui-datagrid" style="width:1000px"
       url="integration/ldl_getdata.php?cabinet=<?php echo $cabinet ?>&dintegration=<?php echo $dintegration ?>&allvals=<?php echo $allvals ?>"
       title="Alertes LDL Dossiers Int�gr�s"
       pagination="true" pageSize="50"
       rownumbers="true"
       singleSelect="true" fitColumns="true"
       nowrap="false" >
    <thead>
    <tr>

        <th field="ldl_dossier"  sortable="true" >Dossier</th>
        <th field="ldl_date"   >Date Examen</th>
        <th field="ldl_valeur"  sortable="true" formatter="formatValue" >Valeur</th>
        <th field="ldl_consultation"  sortable="true"  >Date Consultation</th>
        <th field="ldl_age"   >Age Patient</th>
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
    LogAccess("", "ldl", $UserIDLog, 'na', $cabinet,  0, "Liste Trace Integration Ldl:".$answerLog);
}


//laisser l� pour contourner le non affichage des piwik de ids
echo("<br />");
?>

</body>
</html>
