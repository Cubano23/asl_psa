<?php
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
    <title>Gestion des Médecins</title>
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

        .l-btn{
            vertical-align:middle;
        }
        .button-sep{
            display:inline-block;
            width:0;
            height:22px;
            border-left:1px solid #ccc;
            border-right:1px solid #fff;
            vertical-align:middle;
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



        function newMG()
        {
            $('#dlg').dialog('open').dialog('setTitle','Nouveau Médecin');
            $('#fm').form('clear');
            url = 'mg/save.php';
        }
        function editMG()
        {
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $('#dlg').dialog('open').dialog('setTitle','Modifier Médecin');
                $('#fm').form('load',row);
                url = 'mg/update.php?id='+row.id;
            }
        }
        function saveMG()
        {
            document.getElementById("error").innerText = "";
            let telephone = $("#telephone").val();
            let portable = $("#portable").val();
            let adeli = $("#adeli").val();
            let rpps = $("#rpps").val();

      
            if((telephone.length != 14 && telephone.length != 0) && (telephone.length != 10 && telephone.length != 0)){ 
                document.getElementById("error").innerText = 'Veuillez saisir un numéro de téléphone valide!';
                return;
            }else if((portable.length != 14 && portable.length != 0) && (portable.length != 10 && portable.length != 0)){ 
                document.getElementById("error").innerText = 'Veuillez saisir un numéro de portable valide!';
                return; 
            }else if(adeli.length != 9 && adeli.length != 0){ 
                document.getElementById("error").innerText = 'Veuillez saisir un adeli valide!';
                return;
            }else if(rpps.length != 11 && rpps.length != 0){ 
                document.getElementById("error").innerText = 'Veuillez saisir un RPPS valide!';
                return;
            }else{
                console.log("OK!");
            
            

             // fin des controles, nous soumettons
            $('#fm').form('submit',{
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
    }
        function utf8_encode( string )
        {
            return unescape( encodeURIComponent( string ) );
        }
        function removeMG()
        {

            var row = $('#dg').datagrid('getSelected');
            if (row)
            {
                $.messager.confirm('Confirm','Etes vous sûrs d\'effacer le Médecin?',function(r)
                {
                    if (r)
                    {
                        $.post('mg/remove.php',{id:row.id,
                            cabinet:row.cabinet},function(result)
                        {
                            if (result.success)
                            {
                                $('#dg').datagrid('reload');	// reload the user data
                            } else
                            {
                                $.messager.show({	// show error message
                                    title: 'Error',
                                    msg: result.msg
                                });
                            }
                        },'json');
                    }
                });
            }//if row

        }

        function exportMG()
        {
            $("*").css("cursor", "progress");
            $.post('mg/exportdata.php',function(result)
            {
                if (result.success)
                {

                } else
                {

                    // Construct the <a> element
                    var link = document.createElement("a");
                    link.download = 'mg/mgexport.csv';
                    // Construct the uri
                    var uri = link.download ;
                    link.href = uri;
                    document.body.appendChild(link);
                    link.click();
                    // Cleanup the DOM
                    document.body.removeChild(link);
                    delete link;

                }
                $("*").css("cursor", "default");
            },'json');

        }




        function doSearch()
        {
            var x = $('#cabsearch').combobox('getValue');
            var e = $('#hsearch').combobox('getValue');

            if(e==1)
                enabledisabebuttons(1);
            else
                enabledisabebuttons(0);

            $('#dg').datagrid('load',{
                cabsearch: x,
                nsearch: $('#nsearch').val(),
                hsearch: $('#hsearch').combobox('getValue')
            });
        }
        function doReset(){
            $('#cabsearch').combobox('setValue','');
            $('#nsearch').val('');
            $('#hsearch').combobox('setValue','0');
            doSearch();
        }



        function enabledisabebuttons(recordstatus)
        {

            if(recordstatus==0)
            {
                $('#btAdd').linkbutton('enable');$('#btDelete').linkbutton('enable');$('#btEdit').linkbutton('enable');
            }
            else
            {
                $('#btAdd').linkbutton('disable');$('#btDelete').linkbutton('disable');$('#btEdit').linkbutton('disable');
            }


        }



    </script>

</head>
<body bgcolor=#FFE887>
<?php

require_once("../global/entete.php");

entete_asalee("Gestion des Médecins Traitants");
?>


<br />
<br />
<br />



<table id="dg" class="easyui-datagrid" style="width:1600px"
       url="mg/getdata.php"
       title="Gestion des Médecins" toolbar="#toolbar"
       pagination="true" pageSize="20"
       autoRowHeight =true"
       singleSelect="true" fitColumns="true"
       nowrap="false"
>
    <thead  frozen="true">
    <tr>
        <th field="id" width="30" sortable="true">Id</th>
        <th field="cabinet"  sortable="true">User Cabinet</th>
        <th field="nom_cab" width="100" sortable="true">Nom Cabinet</th>
        <th field="prenom" >Prénom</th>
        <th field="nom" sortable="true">Nom</th>
        <th field="ddebut" >Date Entrée</th>
        <th field="dfin" sortable="true">Date Sortie</th>

    </tr></thead>
    <thead><tr>
        <th field="courriel" >Courriel</th>
        <th field="telephone" width="60">Téléphone</th>
        <th field="portable" width="60">Portable</th>
        <th field="adeli" width="50">ADELI</th>
        <th field="rpps" width="50">RPPS</th>
        <th field="adresse" width="100">Adresse</th>
        <th field="codepostal" width="50">Code Postal</th>
        <th field="ville" >Ville</th>
        <th field="departement" width="100" sortable="true">Département</th>
        <th field="region" width="100" sortable="true">Région</th>
        <th field="recordstatus" width="100" sortable="true">RecordStatus</th>


    </tr>
    </thead>
</table>

<div id="toolbar" style="padding:5px;height:auto">
    <div style="margin-bottom:5px">
        <table>
            <tr>
                <td>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-add" onclick="newMG()"    id="btAdd"      >Créer</a>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-edit"  onclick="editMG()"  id="btEdit"     >Modifier</a>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-remove"  onclick="removeMG()"  id="btDelete" >Effacer</a>
                    <span class="button-sep"></span>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-export"  onclick="exportMG()">Exporter</a>
                    <!--          <a href="#" class="easyui-linkbutton" iconCls="icon-import"  onclick="importMG()">Importer</a> -->
                </td>
            </tr>
            <tr>
                <td>
                    <span>Cabinet:</span> 	<input name="cabsearch"  id="cabsearch" class="easyui-combobox" style="width:200px"
                                                    url="cab/cabinets_getlist.php"
                                                    valueField="cab" textField="text">
                    <span>Nom:</span> 	<input name="nsearch"  id="nsearch" class="easyui-textbox" style="width:150px">
                    <span class="button-sep"></span>
                    <span>Etat Médecins:</span>
                    <select id="hsearch" class="easyui-combobox" name="hsearch" style="width:100px;">
                        <option value="0">Actifs</option>
                        <option value="1">Sortis</option>
                        <option value="2">Tous</option>
                    </select>
                    <span class="button-sep"></span>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-search"  onclick="doSearch()">Recherche</a>
                    <a href="#" class="easyui-linkbutton" onclick="doReset()"  iconCls="icon-reload">Reinitialiser</a>

                </td>
            </tr>
        </table>
    </div>
</div>

<div id="dlg" class="easyui-dialog" style="width:400px;padding:10px 20px"
     closed="true" buttons="#dlg-buttons">
    <div class="ftitle">Médecin</div>
    <span id="error" style="color: red"></span><br />
    <form id="fm" method="post" novalidate>
        <div class="fitem">
            <label>Cabinet:</label>
            <input class="easyui-combobox" style="width:150px" name="cabinet"
                   url="cab/cabinets_getlist.php"
                   valueField="cab" textField="text" required="true">
        </div>
        <div class="fitem"><label>Prénom:</label><input name="prenom" class="easyui-validatebox" required="true">
        </div>
        <div class="fitem"><label>Nom</label><input id="nom" name="nom" class="easyui-validatebox" required="true"></div>
        <div class="fitem"><label>Courriel</label><input id="courriel" name="courriel" class="easyui-validatebox" validType="email"></div>
        <div class="fitem"><label>Téléphone</label><input id="telephone" name="telephone" class="easyui-validatebox" ></div>
        <div class="fitem"><label>Portable</label><input id="portable" name="portable" class="easyui-validatebox" ></div>
        <div class="fitem"><label>ADELI</label><input id="adeli" name="adeli" class="easyui-validatebox" ></div>
        <div class="fitem"><label>RPPS</label><input id="rpps" name="rpps" class="easyui-validatebox" ></div>
        <div class="fitem"><label>Adresse</label><input id="adresse" name="adresse" class="easyui-validatebox" ></div>
        <div class="fitem"><label>Code Postal</label><input id="codepostal" name="codepostal" class="easyui-validatebox" ></div>
        <div class="fitem"><label>Ville</label><input id="ville" name="ville" class="easyui-validatebox" ></div>

    </form>
</div>
<div id="dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveMG()">Enregistrer</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">Annuler</a>
</div

<?php
//laisser là pour contourner le non affichage des piwik de ids
echo("<br />");
?>


<script type="text/javascript">

    // when double click a cell, begin editing and make the editor get focus
    $('#dg').datagrid({
        onClickRow: function(index,row){
//			var row = $('#dg').datagrid('getSelected');
            if (row)
            {
                enabledisabebuttons(row.recordstatus);

            }


        }
    });
</script>


</body>
</html>
