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
    <title>Liste Traces IDS</title>

    <link rel="stylesheet" type="text/css" href="../jquery/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="../jquery/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="../jquery/demo/demo.css">

    <script type="text/javascript" src="../jquery/jquery.min.js"></script>
    <script type="text/javascript" src="../jquery/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../jquery/locale/easyui-lang-fr.js"></script>

    <script type="text/javascript">

        function myformatter(date)
        {
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
        }
        function myparser(s){
            if (!s) return new Date();
            var ss = (s.split('-'));
            var y = parseInt(ss[0],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[2],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d))
            {
                return new Date(y,m-1,d);
            } else
            {
                return new Date();
            }
        }

        function doSearchLogs(){
            $('#dg').datagrid('load',{
                mindate: $('#mindate').datebox('getValue'),
                maxdate: $('#maxdate').datebox('getValue'),
                <?php


                //       if($_SERVER['SERVER_NAME']=="psaet.asalee.fr")
                {

                    echo " reqfilter: $('#hsearch').val(),";
                    echo " patientfilter: $('#osearch').val(),";
                }
                ?>
                extrafilter: $('#csearch').val()
            });
        }


        function doResetLogs(){
            $('#mindate').datebox('setValue','');
            $('#maxdate').datebox('setValue','');
            <?php
            //       if($_SERVER['SERVER_NAME']=="psaet.asalee.fr")
            {
                echo "  $('#hsearch').val('');";
                echo"  $('#osearch').val('');";
            }
            ?>
            $('#csearch').val('');
            doSearchLogs();
        }



        function doExportLogs()
        {


            $("*").css("cursor", "progress");
            $.post('logs/exportdata.php',function(result)
            {

                {

                    // Construct the <a> element
                    var link = document.createElement("a");
                    link.download = 'logs/idslogs.csv';
                    // Construct the uri
                    var uri = link.download ;
                    link.href = uri;
                    document.body.appendChild(link);
                    link.click();
                    // Cleanup the DOM
                    document.body.removeChild(link);
                    delete link;
                }

            },'json');
            $("*").css("cursor", "default");
        }



        /*
        0 Consultation	Application
        1	Création	Application
        2	Modification	Application
        3	Suppression	Application
        4	Connexion d'un utilisateur	Authentification
        5	Déconnexion d'un utilisateur	Authentification
        6	Demande de certificat	PKI
        7	Demande de révocation	PKI
        8	Retrait d'un certificat	PKI
        9	Révocation effective	PKI
        */
        function formatOperation(val, row){

            var ival = parseInt(val);
            var codeMsg=val;
            switch(ival)
            {
                case 0: codeMsg = "Consultation	Application";break;
                case 1: codeMsg = "Création	Application";break;
                case 2: codeMsg = "Modification	Application";break;
                case 3: codeMsg = "Suppression	Application";break;
                case 4: codeMsg = "Connexion d'un utilisateur";break;
                case 5: codeMsg = "Déconnexion d'un utilisateur";break;
                case 6: codeMsg = "Demande de certificat";break;
                case 7: codeMsg = "Demande de révocation";break;
                case 8: codeMsg = "Retrait d'un certificat";break;
                case 9:  codeMsg = "Révocation effective";break;


            }
            return  codeMsg  ;
        }


    </script>
</head>
<body bgcolor=#FFE887>

<?php

require_once "Config.php";
$config = new Config();

require_once($config->webservice_path . "/GetUserId.php");
require_once($config->webservice_path . "/GetLog.php");

require_once("../stats/global/entete.php");

entete_asalee("Lister les traces IDS");


?>


<table id="dg" class="easyui-datagrid" style="width:1500px;height=500px;"
       title="Traces IDS"       url="logs/getdata.php"     toolbar="#toolbarLogs"
       singleSelect="true" fitColumns="false"
       pagination="true" pageSize="50"
       rownumbers="true"
       autoRowHeight="false" >
    <thead  >
    <tr>
        <th field="dret" width="200" sortable="true">Date </th>
        <th field="accesstype" formatter="formatOperation">Type Accès</th>
        <th field="requester"  >Demandeur</th>
        <th field="pagename"  >Page</th>
        <th field="patient"  >Objet</th>
        <th field="unit"  >Unité</th>
        <th field="extra" >Commentaire</th>
    </tr></thead>
</table>
<div id="toolbarLogs" style="padding:5px;height:auto">
    <div style="margin-bottom:5px">
        <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="doSearchLogs()">Recharger</a>
    </div>
    <div>
        <table>
            <tr>
                <td><span>Date D&eacute;but:</span></td>
                <td><input class="easyui-datebox" id="mindate" data-options="formatter:myformatter,parser:myparser"></td>
                <td><span>Date Fin:</span></td>
                <td><input class="easyui-datebox" id="maxdate" data-options="formatter:myformatter,parser:myparser"></td>

                <?php

                //       if($_SERVER['SERVER_NAME']=="psaet.asalee.fr")
                {
                    echo '<td><span >Demandeur:</span></td>';
                    echo '<td><input id="hsearch" style="line-height:26px;border:1px solid #ccc"></td>';
                    echo '<td><span >Objet:</span></td>';
                    echo '<td><input id="osearch" style="line-height:26px;border:1px solid #ccc"></td>';
                }
                ?>
                <td><span>Commentaire:</span></td>
                <td><input id="csearch" style="line-height:26px;border:1px solid #ccc"></td>

            </tr>
            <td><a href="#" class="easyui-linkbutton" onclick="doSearchLogs()" iconCls="icon-search">Rechercher</a></td>
            <td><a href="#" class="easyui-linkbutton" onclick="doResetLogs()" iconCls="icon-reload">Reinitialiser</a></td>
            <td></td>
            <td><a href="#" class="easyui-linkbutton" onclick="doExportLogs()" iconCls="icon-export">Exporter</a></td>
            </tr>
        </table>
    </div>
</div>



<?php
//laisser là pour contourner le non affichage des piwik de ids
echo("<br />");
?>

</body>
</html>
