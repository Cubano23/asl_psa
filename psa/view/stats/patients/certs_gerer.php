<?php

/*
	session_start();
if(!isset($_SESSION['nom'])) {
	# pas passé par l'identification
	$debut=dirname($_SERVER['PHP_SELF']);
	$self=basename($_SERVER['PHP_SELF']);
    header("Location: $debut/ident_util.php?url=$self");
    echo "<a href='$debut/ident_util.php?url=$self'>cliquez ici</a>";
	exit;
}
*/

require_once('filterdomain.php');
$admin_level = getPsaetLevel();
if( ($admin_level!=1) && ($admin_level!=2))
{
    echo" Option Interdite";
    die;
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>


<head>
    <meta http-equiv="content-type"
          content="text/html; charset=ISO-8859-15">
    <title>Gestion Certificats </title>
</head>
<body bgcolor=#FFE887>

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

    /*
    0 ? Actif
    1 ? Révoqué
    2 ? En attente de révocation
    3 ? En attente de retrait
    */

    function formatStatus(val, row){

        var ival = parseInt(val);
        var codeMsg=val;
        switch(ival)
        {
            case 0: codeMsg = "<span style='color:green'>Actif</span>";break;
            case 1: codeMsg = "<span style='color:red'>Révoqué</span>";break;
            case 2: codeMsg = "<span style='color:red'>En attente de révocation</span>";break;
            case 3: codeMsg = "<span style='color:orange'>En attente de retrait</span>";break;
        }
        return  codeMsg  ;
    }

    function doSearchCerts(){
        $('#dg2').datagrid('load',{
            reqfilter: $('#reqfilter').val(),
            ownerfilter: $('#ownerfilter').val()
        });
    }


    function doResetCerts(){

        $('#reqfilter').val('');
        $('#ownerfilter').val('');
        doSearchCerts();
    }

</script>


<?php
require_once "Config.php";
$config = new Config();

require_once($config->webservice_path . "/GetUserId.php");
require_once($config->webservice_path . "/GetCertStatus.php");

require_once("../global/entete.php");

entete_asalee("Gestion des Certificats");
?>


<table id="dg2" class="easyui-datagrid" style="width:1200px"
       title="Status des Certificats"    url="cert/getstatusdata.php"     toolbar="#toolbarCerts"
       rownumbers="true"
       pagination="true" pageSize="50"
       singleSelect="true" fitColumns="true"
       nowrap="false" >
    <thead  >
    <tr>
        <th field="dret"  width="50" sortable="true">Date de Retrait</th>
        <th field="certowner"  width="50" >Propriétaire Certificat</th>
        <th field="certorganisation"  >Organisation</th>
        <th field="certindex"  >Index</th>
        <th field="certstatus"  width="100" formatter="formatStatus">Etat</th>
        <th field="certrequester" width="100" sortable="true">Demandeur</th>
        <th field="certcomment" width="100" >Commentaire</th>
    </tr></thead>
</table>
<div id="toolbarCerts" style="padding:5px;height:auto">
    <div style="margin-bottom:5px">
        <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="doSearchCerts()">Recharger</a>
    </div>
    <div>
        <table>
            <tr>
                <td><span >Propriétaire:</span></td>
                <td><input id="ownerfilter" style="line-height:26px;border:1px solid #ccc"></td>
                <td><span >Demandeur:</span></td>
                <td><input id="reqfilter" style="line-height:26px;border:1px solid #ccc"></td>
            </tr>
            <tr>
                <td><a href="#" class="easyui-linkbutton" onclick="doSearchCerts()" iconCls="icon-search">Rechercher</a></td>
                <td><a href="#" class="easyui-linkbutton" onclick="doResetCerts()" iconCls="icon-reload">Reinitialiser</a></td>
            </tr>
        </table>
    </div>
</div>


</table>

<br />
<br />


<table id="dg" class="easyui-datagrid" style="width:1000px"
       url="cert/getdata.php"
       title="Gestion des Certificats"
       pagination="true" pageSize="20"
       rownumbers="true"
       singleSelect="true" fitColumns="true"
       nowrap="false" >
    <thead  >
    <tr>
        <th field="dmaj" width="50" sortable="true">Date de Création</th>
        <th field="owner" width="50" sortable="true">Propriétaire Certificat</th>
        <th field="organisation"  >Organisation</th>
        <th field="token"  >Token</th>
        <th field="lot" >Lot</th>
    </tr></thead>
</table>


<br />
<br />


</body>
</html>
