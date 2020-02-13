<?php

require_once "Config.php";
$config = new Config();

session_start();

if(!isset($_SESSION["cabinet"]))
{
    header("location:" . $config->psa_path);
}

set_time_limit(120);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="content-type"
          content="text/html; charset=ISO-8859-15">
  <title>Espace Comité d'Entreprise</title>
    <style scoped>
        .f1{
            width:350px;
        }
    </style>

    <style>
        .textbox textarea.textbox-text{
            white-space:pre-wrap;
        }
    </style>

    <style type="text/css">
        .datagrid-header .datagrid-cell{
            line-height:normal;
            height:auto;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="../jquery/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="../jquery/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="../jquery/demo/demo.css">

    <script type="text/javascript" src="../jquery/jquery.min.js"></script>
    <script type="text/javascript" src="../jquery/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../jquery/locale/easyui-lang-fr.js"></script>
    <script type="text/javascript">
        var url;

        function utf8_encode( string )
        {
            return unescape( encodeURIComponent( string ) );
        }

        function formatFileLink(val,row){
            var url = "../docs/blog/";
            return '<a href="'+url + val+'"  target="_blank">'+val+'</a>';
        }

        function formatType(val,row){
            var x="test";

            switch(val)
            {
                case '0': break;
                case '1':x="Juridique";break;
                case '2':x="Compte-Rendu";break;
                case '3':x="Prevoyance";break;
                case '4':x="Mutuelle";break;
                case '5':x="Salaires et primes";break;x
                case '6':x="Signatures";break;x

            }

            return x;
        }

        function formatEmetteur(val,row){
            if(row.type!='0')
                return "CE - DUP";
            return val;
        }


        function newReport()
        {
            $('#dlg').dialog('open').dialog('setTitle','Nouveau Fichier');
//			$('#numero').prop('readonly', false);
//      $('#id').prop('readonly', true);
            $('#fupload').form('clear');
            url = 'blog/save.php';
        }

        function newEmail()
        {
            $('#dlgemail').dialog('open').dialog('setTitle','Envoi Email');
            $('#femail').form('clear');
            url = 'blog/sendemail.php';
        }
        function sendEmail()
        {
            $('#femail').form('submit',{
                url: url,
                onSubmit: function(){
                    return $(this).form('validate');
                },
                success: function(result){
                    var result = eval('('+result+')');
                    if (result.success){
                        $('#dlgemail').dialog('close');		// close the dialog
                    } else {
                        $.messager.show({
                            title: 'Error',
                            msg: result.msg
                        });
                    }
                }
            });
        }



        function saveReport()
        {
            $('#fupload').form('submit',{
                url: url,
                onSubmit: function(){
                    return $(this).form('validate');
                },
                success: function(result){
                    var result = eval('('+result+')');
                    if (result.success){
                        $('#dlg').dialog('close');		// close the dialog
                        $('#dg').datagrid('reload');	// reload the user data
                    } else {
                        $.messager.show({
                            title: 'Error',
                            msg: result.msg
                        });
                    }
                }
            });
        }

        function editReport()
        {
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $('#numero').prop('readonly', false);
				$('#nir').dialog('open').dialog('setTitle','Gérer NIR Dossier');
                $('#nir1').val('');
                $('#nir2').val('');
                $('#fmnir').form('load',row);
                url = 'blog/update.php?numero='+row.numero+'&id='+row.id;
            }
        }



        function formatDate(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return  (d<10?('0'+d):d)+'/'+ (m<10?('0'+m):m)+'/'+  y;
        }


        function doSearch(){
            $('#dg').datagrid('load',{
//    timesearch: $('#timesearch').datebox('getValue'),
                ssearch: $('#ssearch').val(),
                tsearch: $('#tsearch').combobox('getValue')
            });
        }


        function doReset(){
            $('#ssearch').val('');
            $('#tsearch').combobox('setValue','0');
//    $('#psearch').combobox('setValue','0');    
            doSearch();
        }



    </script>

</head>
<body bgcolor=#FFE887>
<?php




$cabinet = $_SESSION["cabinet"];

require_once($config->webservice_path . "/GetUserId.php");
require_once("../stats/global/entete.php");

//require_once("$base/inclus/accesbase.inc.php");
//entete_asalee("Espace Comit? d'Entreprise");









?>

<div style="text-align:center; font-size: 2em;">
    <h1 style="font-size: 1em;"> Asalée </h1>
    <h1 style="font-size: 1em;"> Espace Comité d&#39;Entreprise </h1>
</div>
<br />
<br />
<br />

<p><a href="/view/docs/ASALEE_Fiche_de_poste_officiel_2017.pdf" target="_blank"  style="font-size: 1.3em;"><i class="icon-view" style="width: 16px;height: 16px;display: inline-block;"></i> Fiche de Poste</a></p>

<table id="dg" class="easyui-datagrid" style="width:1800px"
       url="blog/getdata.php"
       title="Comité d'Entreprise" toolbar="#toolbar"
       pagination="true" pageSize="50"
       rownumbers="true"
       singleSelect="true" fitColumns="true"
       nowrap="false" >
    <thead >
    <tr>
        <th field="id"  sortable="true" >Id</th>
        <th field="type"  formatter="formatType" width="150">Type</th>
        <th field="redacteur"  formatter="formatEmetteur" >Emetteur</th>
        <th field="dcreat" sortable="true"  >Date de Création</th>
        <th field="sujet"   width="350">Sujet</th>
        <th field="lien" formatter="formatFileLink" width="750" >Fichier Compte Rendu</th>
        <th field="dmaj"  sortable="true" width="200">Date de Mise à jour</th>
    </tr>
    </thead>
</table>
<div id="toolbar" style="padding:5px;height:auto">
    <div style="margin-bottom:5px">

        <?php if(strtolower($cabinet)=="ztest" or strtolower($cabinet)=="lezay3")
        {
            echo ('<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newReport()">Nouveau</a>

           ');

//           			<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editReport()">Modifier</a>
        }
        ?>
        <a href="#" class="easyui-linkbutton" iconCls="icon-email2" plain="true" onclick="newEmail()">Contacter</a>

    </div>

    <div>
        <table>
            <tr>
                <td><span >Type Fichier:</span></td>
                <td><select id="tsearch" class="easyui-combobox" name="tsearch" style="width:100px;">
                        <option value="0">Tous</option>
                        <option value="1">Juridique</option>
                        <option value="2">Compte-Rendu</option>
                        <option value="3">Prevoyance</option>
                        <option value="4">Mutuelle</option>
                        <option value="5">Salaires et primes</option>
                    </select>
                </td>
                <td></td>
                <td><span >Sujet:</span></td>
                <td><input id="ssearch" style="width:200px; line-height:20px;border:1px solid #ccc"></input></td>

            </tr>

            <tr>
                <td></td>
                <td><a href="#" class="easyui-linkbutton" onclick="doSearch()" iconCls="icon-search">Rechercher</a></td>
                <td><a href="#" class="easyui-linkbutton" onclick="doReset()" iconCls="icon-reload">Reinitialiser</a></td>
            </tr>
        </table>
    </div>



</div>



<div id="dlg" class="easyui-dialog" style="width:500px;padding:10px 20px"
     closed="true" buttons="#dlg-buttons">
    <form id="fupload" action="blog/save.php" method="post" enctype="multipart/form-data">
        <table>
            <tr>
                <td>Type:</td>
                <td>


                    <select name="type" class="easyui-combobox" required="required" style="width:250px">
                        <option value="1" selected="true">Juridique</option>
                        <option value="2">Compte-Rendu</option>
                    </select>

                </td>
            </tr>
            <tr>
                <td>Date:</td>
                <td><input name="dcreat" style="width:250px" type="text" class="easyui-textbox" required="required"  ></input></td>
            </tr>
            <tr>
                <td>Sujet:</td>
                <td><input name="sujet" id class="easyui-textbox" required="required" style="width:250px"></input></td>
            </tr>
            <tr>
                <td>File:</td>
                <td><input name="file" type="file" class="easyui-filebox" style="width:250px"></input></td>
            </tr>

        </table>
    </form>
</div>
<div id="dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveReport()">Valider</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="$('#dlg').dialog('close')">Annuler</a>

</div >


<div id="dlgemail" class="easyui-dialog" style="width:500px;padding:10px 20px"
     closed="true" buttons="#dlgemail-buttons">
    <form id="femail" action="blog/email.php" method="post" enctype="multipart/form-data">
        <table>
            <tr>
                <td>Nom:</td>
                <td><input name="nom" style="width:250px" class="easyui-textbox" required="required"></input></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><input name="email" style="width:250px" class="easyui-textbox" data-options="required:true,validType:'email'"></input></td>
            </tr>

            <tr>
                <td>Sujet:</td>
                <td><input name="sujet" style="width:250px" class="easyui-textbox" required="required"></input></td>
            </tr>
            <tr>
                <td>Message:</td>
                <td><input class="easyui-textbox" name="message" data-options="multiline:true" style="height:100px;width:250px;"></input></td>
            </tr>

        </table>
    </form>
</div>
<div id="dlgemail-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="sendEmail()">Envoyer</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="$('#dlgemail').dialog('close')">Annuler</a>

</div >




<?php
//laisser là pour contourner le non affichage des piwik de ids
echo("<br />");
?>

</body>
</html>
