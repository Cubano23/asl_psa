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
    <title>Gestion des Cabinets</title>
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
        var caburl;
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
        function newCab()
        {
            $('#dlg').dialog('open').dialog('setTitle','Nouveau Cabinet');
            $('#cabinet').readonly=false;
            $('#fm').form('clear');
            caburl = 'cab/save.php';
            $('#dgmed').datagrid('loadData', {"total":0,"rows":[]});
            $('#tabmed').hide();
            $('#dginf').datagrid('loadData', {"total":0,"rows":[]});
            $('#tabinf').hide();

        }
        function editCab()
        {
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $('#dgmed').datagrid('load',{
                    cabinet: row.cabinet
                });

                $('#dginf').datagrid('load',{
                    cabinet: row.cabinet
                });

                $('#dlg').dialog('open').dialog('setTitle','Modifier Cabinet');
                $('#cabinet').readonly=true;
                $('#fm').form('load',row);
//				$('#password2').val($('#password').val());
                caburl = 'cab/update.php?cabinet='+row.cabinet;
                $('#tabmed').show();
                $('#tabinf').show();
            }
            else
                alert('Choisir un cabinet');
        }


        function update_patients(op)
        {

            return;
            var row = $('#dg').datagrid('getSelected');
            var rowIndex = $("#dg").datagrid("getRowIndex", row);
            if (row)
            {
//          $('#dg').datagrid('reload');	// reload the user data
//          $("#dg").datagrid("setRowIndex", rowIndex);
                var medecins = $('#dgmed').datagrid('getData').total + op;
                if(medecins<=0)
                    medecins = 1;
                $('#total_pat').val(800* medecins);
                $('#total_sein').val(150* medecins);
                $('#total_cogni').val(120* medecins);
                $('#total_colon').val(300* medecins);
                $('#total_uterus').val(180* medecins);
                $('#total_diab2').val(50* medecins);
                $('#total_HTA').val(150* medecins);

            }
        }

        function removeCab()
        {
            var row = $('#dg').datagrid('getSelected');
            if (row)
            {
                $.messager.confirm('Confirm','Etes vous s?rs d\'effacer le Cabinet?',function(r)
                {
                    if (r)
                    {
                        $.post('cab/remove.php',{cabinet:row.cabinet},function(result)
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
            else
                alert('Choisir un cabinet');
        }

        // function newMG()
        // {
        //     $('#dlg').dialog('open').dialog('setTitle','Nouveau Médecin');
        //     $('#fm').form('clear');
        //     url = 'mg/save.php';
        // }
        function newMed()
        {
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $('#dlgmed').dialog('open').dialog('setTitle','Nouveau M&eacute;decin');
                $('#fmmed').form('clear');
                url = 'mg/save2.php?cabinet='+row.cabinet;
            }
        }


        function saveMed()
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
            
            var res= 1;
            $('#fmmed').form('submit',{
                url: url,
                onSubmit: function(){
                    return $(this).form('validate');
                },
                success: function(result){
                    var result = eval('('+result+')');
                    if (result.success){
                        $('#dlgmed').dialog('close');		// close the dialog
                        update_patients(1);
                        $('#dgmed').datagrid('reload');	// reload the user data
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
        function editMed()
        {
            var row = $('#dgmed').datagrid('getSelected');
            if (row){
                $('#dlgmed').dialog('open').dialog('setTitle','Modifier M&eacute;decin');
                $('#fmmed').form('load',row);
                url = 'mg/update2.php?id='+row.id;

            }
            else
                alert('Choisir un cabinet');
        }




        function cancelCab()
        {
            $('#dg').datagrid('reload');	// reload the user data
        }
        function saveCab()
        {
            $('#fm').form('submit',{
                url: caburl,
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


        function renameCab()
        {
            var t=$('#dgmed').datagrid('getData').total;
            var v=$('#dgmed').datagrid('getRows')[0].nom;
            var cab = "Cabinet Dr.";
            var i;
            for (i=0; i<t; i++)
            {
                cab = cab+' '+ $('#dgmed').datagrid('getRows')[i].nom ;
                if(i!=t-1)
                    cab = cab+',' ;
            }
//    alert(cab);
            $('#nom_complet').val(cab);

        }

        function renameContact()
        {
            var t=$('#dginf').datagrid('getData').total;
            var v=$('#dginf').datagrid('getRows')[0].nom;
            var cab = "";
            var i;
            for (i=0; i<t; i++)
            {
                cab = cab+' '+  $('#dginf').datagrid('getRows')[i].prenom+' '+ $('#dginf').datagrid('getRows')[i].nom ;
                if(i!=t-1)
                    cab = cab+',' ;
            }
//    alert(cab);
            $('#contact').val(cab);

        }


        function utf8_encode( string )
        {
            return unescape( encodeURIComponent( string ) );
        }

        function removeMed()
        {

            var row = $('#dgmed').datagrid('getSelected');
            if (row)
            {
                $.messager.confirm('Confirm','Etes vous s?rs d\'effacer le M&eacute;decin?',function(r)
                {
                    if (r)
                    {
                        $.post('mg/remove2.php',{id:row.id,
                                cabinet:row.cabinet}
                            ,function(result)
                            {
                                if (result.success)
                                {
                                    update_patients(-1);
                                    $('#dgmed').datagrid('reload');	// reload the user data


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


        function removeInf()
        {

            var row = $('#dginf').datagrid('getSelected');
            if (row)
            {
                $.messager.confirm('Confirm','Etes vous s?rs d\'enlever l\'utilisateur?',function(r)
                {
                    if (r)
                    {
                        $.post('allowedcab/remove2.php',{id:row.id},function(result)
                        {
                            if (result.success)
                            {
                                $('#dginf').datagrid('reload');	// reload the user data
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


        function saveInf()
        {

            $('#fminf').form('submit',{
                url: url,
                onSubmit: function(){
                    return $(this).form('validate');
                },
                success: function(result){
                    var result = eval('('+result+')');
                    if (result.success){
                        $('#dlginf').dialog('close');		// close the dialog
                        $('#dginf').datagrid('reload');	// reload the user data
                    } else {
                        $.messager.show({
                            title: 'Error',
                            msg: result.msg
                        });
                    }
                }
            });
        }



        function newInf()
        {
            var row = $('#dg').datagrid('getSelected');
            if (row){

                $('#dlginf').dialog('open').dialog('setTitle','Ajouter Utilisateur');
                $('#fminf').form('clear');
                url = 'allowedcab/save2.php?cabinet='+row.cabinet;
            }
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
                hsearch: $('#hsearch').combobox('getValue')
            });
        }

        function doReset(){
            $('#cabsearch').combobox('setValue','');
            $('#hsearch').combobox('setValue','0');
            doSearch();
        }


       /*  function doExportAllowed()
        {
            $("*").css("cursor", "progress");
            $.post('allowedcab/exportdata2.php',function(result)
            {
                if (result.success)
                {

                } else
                {

                    // Construct the <a> element
                    var link = document.createElement("a");
                    link.download = 'allowedcab/allowedexport.csv';
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
 */

        function doExportAllowed(){

      
                window.location="allowedcab/export_utilisateurs.php";
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

entete_asalee("Gestion des Cabinets");
?>


<br />
<br />


<table id="dg" class="easyui-datagrid" style="width:2300px"
       url="cab/getdata.php"
       title="Gestion des Cabinets" toolbar="#toolbar"
       pagination="true" pageSize="20"
       rownumbers="true"
       singleSelect="true" fitColumns="true"
       nowrap="false" >
    <thead  frozen="true">
    <tr>
        <th field="cabinet" width="100" sortable="true">User Cabinet</th>
        <!--				<th field="password" type="password" >Mot de Passe</th> -->
        <th field="nom_complet" width="150" sortable="true">Nom Complet</th>
        <th field="nom_cab" sortable="true">Nom Cabinet</th>
        <th field="adresseCabinet" sortable="true">Adresse</th>
        <th field="contact" sortable="true">Contact</th>
        <!--				<th field="infirmiere" sortable="true">Infirmi?re</th> -->
    </tr></thead>
    <thead><tr>
        <!--				<th field="courriel" >Courriel</th>
                        <th field="telephone" width="60">T?l?phone</th>
                        <th field="portable" width="60">Portable</th>-->
        <th field="ville" >Ville</th>
        <th field="code_postal" width="50" sortable="true">CP</th>
        <th field="region" width="75" sortable="true">R&eacute;gion</th>
        <th field="logiciel" width="75" sortable="true">Logiciel</th>
        <th field="log_ope" width="20" formatter="formatOperational" sortable="true">Op</th>
        <th field="total_pat" width="75">Total Patients</th>
        <th field="total_sein" width="100">Patients Eligibles <br />D&eacute;pistage Cancer Sein</th>
        <th field="total_cogni" width="120">Patients Eligibles <br />D&eacute;pistage Troubles Cognitifs</th>
        <th field="total_colon" width="100">Patients Eligibles <br />D&eacute;pistage Cancer Colon</th>
        <th field="total_uterus" width="100">Patients Eligibles <br />D&eacute;pistage Cancer Ut&eacute;rus</th>
        <th field="total_diab2" width="100">Patients Eligibles <br />Suivi Diab&eacute;te II</th>
        <th field="total_HTA" width="100">Patients Eligibles <br />Suivi RCVA</th>
        <th field="recordstatus"  formatter="formatStatus"  sortable="true">RecordStatus</th>



    </tr>
    </thead>
</table>

<div id="toolbar" style="padding:5px;height:auto">
    <div style="margin-bottom:5px">
        <table>
            <tr>
                <td><a href="#" class="easyui-linkbutton" iconCls="icon-add" onclick="newCab()" id="btAdd">Cr&eacute;er</a>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-edit"  onclick="editCab()" id="btEdit" >Modifier</a>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-remove"  onclick="removeCab()" id="btDelete" >Effacer</a>
                    <span class="button-sep"></span>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-export"   onclick="doExportAllowed()">Exporter Utilisateurs</a>
                </td>
            </tr>
            <tr>
                <td><span>Cabinet:</span>
                    <input name="cabsearch"  id="cabsearch" class="easyui-combobox" style="width:200px"
                           url="cab/cabinets_getlist.php"
                           valueField="cab" textField="text">
                    <span class="button-sep"></span>
                    <span>Etat Cabinets:</span>
                    <select id="hsearch" class="easyui-combobox" name="hsearch" style="width:100px;">
                        <option value="0">Actifs</option>
                        <option value="1">Sortis</option>
                        <option value="2">Tous</option>
                    </select>
                    <span class="button-sep"></span>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-search"  onclick="doSearch()">Recherche</a>
                    <a href="#" class="easyui-linkbutton" onclick="doReset()" iconCls="icon-reload">Reinitialiser</a></td>
            </tr>
        </table>
    </div>
</div>

<div id="dlg" class="easyui-dialog" data-options="left:200,top:50" style="width:800px;padding:50px 20px;height:auto"
     title="Cabinet"
     closed="true" buttons="#dlg-buttons">
    <div class="ftitle">Cabinet</div>
    <form id="fm" method="post" novalidate>
        <div class="ftitle">Informations</div>
        <div class="fitem"><label class="easyui-tooltip" title="Pas d'accents">Cabinet</label><input name="cabinet" class="easyui-validatebox" style="width:150px" required="true"></div>
        <!--	<div class="fitem"><label>Mot de Passe</label><input type="password"  id ="password"  name ="password" class="easyui-validatebox" style="width:150px" required="true"></div>
            <div class="fitem"><label>Confirmer</label><input  type="password"  id="password2"   name="password2" class="easyui-validatebox" style="width:150px" required="true" validType="equals['#password']"></div>-->
        <div class="fitem"><label  class="easyui-tooltip" title="Par exemple cabinet des Dr Gautier, Dr Bandet, Dr Chevalier, Dr Salesse">Nom Complet</label><input name="nom_complet" id="nom_complet" class="easyui-validatebox" style="width:250px"  required="true">
            <!--			<a href="#" id="namecab"  class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="renameCab()">Nommer Cabinet</a> -->
            <div class="fitem"><label class="easyui-tooltip" title="Ce nom est affich? sur la 1?re page du PSA lorsque l'infirmi?re est connect?e">Contact</label><input name="contact" id="contact" style="width:250px" class="easyui-validatebox" ></div>
            <div class="fitem"><label>Adresse</label><input name="adresseCabinet" style="width:250px" class="easyui-validatebox"  required="true"> </div>
            <div class="fitem"><label>Code Postal</label><input  name="code_postal" style="width:250px" class="easyui-validatebox" required="true"> </div>
            <!--			<div class="fitem"><label>R?gion</label><input class="easyui-combobox" style="width:250px" name="region" url="cab/regions_getlist.php" valueField="reg" textField="reg_t"></div>-->
            <div class="fitem"><label>Logiciel</label><input class="easyui-combobox" style="width:250px" name="logiciel" url="cab/logiciels_getlist.php" valueField="lgc" textField="lgc_t"></div>
            <div class="fitem"><label class="easyui-tooltip" title="0: Non, 1:Oui" >Op&eacute;rationel</label><input class="easyui-combobox" required="true" name="log_ope"  url="cab/getlogicielop.php" valueField="applevel" textField="applevel_t"  ></div>


        </div>
        <div class="fitem"><label>Nom Cabinet</label><input name="nom_cab" class="easyui-validatebox" style="width:250px" ></div>
        <br />
        <br />
        <div class="easyui-tabs" style="width:700px;height:auto" id="myTabs" >
            <!--    <div title="Contacts" style="padding:20px 20px">
                        <div class="fitem"><label>Infirmi?re</label><input class="easyui-combobox" style="width:250px" name="infirmiere" url="cab/infirmieres_getlist.php" valueField="inf" textField="inf_t" ></div>-->


            <!--			<div class="fitem"><label>Courriel</label><input name="courriel" style="width:250px" class="easyui-validatebox" validType="email"></div>
                        <div class="fitem"><label>T?l?phone</label><input name="telephone" class="easyui-validatebox" ></div>
                        <div class="fitem"><label>Portable</label><input name="portable" class="easyui-validatebox" ></div>

                </div>                                -->
            <!--
                    <div title="Patients"  style="padding:20px 20px">
                        <div class="fitem"><label style="width:250px" class="easyui-tooltip" title="Environ 800 patients par m?decin ?quivalent temps plein" >Total Patients</label><input name="total_pat" id ="total_pat" class="easyui-numberbox easyui-tooltip" data-options="min:0" title="Environ 800 patients par m?decin ?quivalent temps plein"></div>
                        <div class="fitem"><label style="width:250px" class="easyui-tooltip" title="Environ 150 patients par m?decin ?quivalent temps plein" >Patients Eligibles D?pistage Cancer Sein</label><input name="total_sein"  id="total_sein" class="easyui-numberbox easyui-tooltip" data-options="min:0" title="Environ 150 patients par m?decin ?quivalent temps plein"></div>
                        <div class="fitem"><label style="width:250px" class="easyui-tooltip" title="Environ 120 patients par m?decin ?quivalent temps plein" >Patients Eligibles D?pistage Trouble Cognitif</label><input name="total_cogni" id="total_cogni"  class="easyui-numberbox easyui-tooltip" data-options="min:0" title="Environ 120 patients par m?decin ?quivalent temps plein"></div>
                        <div class="fitem"><label style="width:250px" class="easyui-tooltip" title="Environ 300 patients par m?decin ?quivalent temps plein" >Patients Eligibles D?pistage Cancer Colon</label><input name="total_colon"  id="total_colon" class="easyui-numberbox easyui-tooltip" data-options="min:0" title="Environ 300 patients par m?decin ?quivalent temps plein"></div>
                        <div class="fitem"><label style="width:250px" class="easyui-tooltip" title="Environ 180 patients par m?decin ?quivalent temps plein" >Patients Eligibles D?pistage Cancer Ut?rus</label><input name="total_uterus" id="total_uterus"  class="easyui-numberbox easyui-tooltip" data-options="min:0" title="Environ 180 patients par m?decin ?quivalent temps plein"></div>
                        <div class="fitem"><label style="width:250px" class="easyui-tooltip" title="Environ  50 patients par m?decin ?quivalent temps plein" >Patients Eligibles Suivi Diab?te II</label><input name="total_diab2" id="total_diab2" class="easyui-numberbox easyui-tooltip" data-options="min:0" title="Environ 50 patients par m?decin ?quivalent temps plein"></div>
                        <div class="fitem"><label style="width:250px" class="easyui-tooltip" title="Environ 150 patients par m?decin ?quivalent temps plein" >Patients Eligibles Suivi RCVA</label><input name="total_HTA" id="total_HTA"  class="easyui-numberbox easyui-tooltip" data-options="min:0" title="Environ 150 patients par m?decin ?quivalent temps plein"></div>
                </div> -->
            <div title="M&eacute;decins" id="tabmed" style="padding:20px" name="tabmed">

                <table id="dgmed" class="easyui-datagrid" style="width:600px;height:300px;padding:20x 100px"
                       url="mg/getdata2.php"
                       title="M&eacute;decins du Cabinet"
                       toolbar="#toolbarmed"
                       singleSelect="true" fitColumns="true"
                       pagination="true" pageSize="10"
                       nowrap="false" >
                    <thead >
                    <tr>
                        <th field="id"  width="50">Id</th>
                        <th field="prenom"  width="200">Pr&eacute;nom</th>
                        <th field="nom" width="200">Nom</th>
                        <th field="ddebut" >Date D&eacute;but</th>
                        <th field="dfin" >Date Sortie</th>
                    </tr>
                    </thead>
                </table>
                <div id="toolbarmed" style="padding:5px;height:auto" >
                    <div style="margin-bottom:5px">
                        <a href="#" id="addmed"  class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newMed()">Ajouter</a>
                        <a href="#" id="editmed"  class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editMed()">Modifier</a>
                        <a href="#" id="delmed"  class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeMed()">Enlever</a>
                        <a href="#" id="namecab"  class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="renameCab()">Nommer Cabinet</a>

                    </div>
                </div>
            </div>

            <div title="Utilisateurs Habilit&eacute;s" id ="tabinf" style="padding:20px" name="tabinf">
                <table id="dginf" class="easyui-datagrid" style="width:600px;height:300px;padding:20x 100px"
                       url="allowedcab/getdata2.php"
                       title="Utilisateurs du Cabinet"
                       toolbar="#toolbarinf"
                       singleSelect="true" fitColumns="true"
                       pagination="true" pageSize="10"
                       nowrap="false" >
                    <thead >
                    <tr>
                        <th field="id"  >id</th>
                        <th field="login"  width="150">login</th>
                        <th field="prenom"  width="200">Pr&eacute;nom</th>
                        <th field="nom" width="200">Nom</th>
                        <th field="profession" width="200">Profession</th>
                        <th field="ddebut" >Date D&eacute;but</th>
                        <th field="dfin" >Date Sortie</th>
                    </tr>
                    </thead>
                </table>
                <div id="toolbarinf" style="padding:5px;height:auto" >
                    <div style="margin-bottom:5px">
                        <a href="#" id="addmed"  class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newInf()">Ajouter</a>
                        <a href="#" id="delmed"  class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeInf()">Enlever</a>
                        <a href="#" id="namecontact"  class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="renameContact()">Nommer Contact</a>


                    </div>
                </div>
            </div>



        </div>

    </form>

</div>
<div id="dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveCab()">Enregistrer</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="cancelCab();javascript:$('#dlg').dialog('close')">Annuler</a>
</div>


<!-- 	<div id="dlgmed" class="easyui-dialog" data-options="left:200,top:50" style="width:300px;padding:50px 20px;"-->
<!--      title="M?decin" -->
<!--			closed="true" buttons="#dlg-buttonsmed">-->
<!--		<form id="fmmed" method="post" novalidate>-->
<!--			<div class="fitem"><label>Pr?nom</label><input name="prenom" class="easyui-validatebox" style="width:150px" required="true"></div>-->
<!--			<div class="fitem"><label>Nom</label><input name="nom" class="easyui-validatebox" style="width:150px" required="true"></div>-->
<!--		</form>-->
<!--    -->
<!--	</div>-->

<div id="dlgmed" class="easyui-dialog" style="width:400px;padding:10px 20px"
     closed="true" buttons="#dlg-buttonsmed">
    <div class="ftitle">M&eacute;decin</div>
    <span id="error" style="color: red"></span><br />
    <form id="fmmed" method="post" novalidate>
        <!--            <div class="fitem">-->
        <!--                <label>Cabinet:</label>-->
        <!--                <input class="easyui-combobox" style="width:150px" name="cabinet"-->
        <!--                       url="cab/cabinets_getlist.php"-->
        <!--                       valueField="cab" textField="text" required="true">-->
        <!--            </div>-->
        <div class="fitem"><label>Pr&eacute;nom:</label><input name="prenom" class="easyui-validatebox" required="true"></div>
        <div class="fitem"><label>Nom</label><input name="nom" class="easyui-validatebox" required="true"></div>
        <div class="fitem"><label>Courriel</label><input name="courriel" class="easyui-validatebox" validType="email"></div>
        <div class="fitem"><label>T&eacute;l&eacute;phone</label><input id="telephone" name="telephone" class="easyui-validatebox" ></div>
        <div class="fitem"><label>Portable</label><input id="portable" name="portable" class="easyui-validatebox" ></div>
        <div class="fitem"><label>ADELI</label><input id="adeli" name="adeli" class="easyui-validatebox" ></div>
        <div class="fitem"><label>RPPS</label><input id="rpps" name="rpps" class="easyui-validatebox" ></div>
        <!--        <div class="fitem"><label>Adresse</label><input name="adresse" class="easyui-validatebox" ></div>-->
        <!--        <div class="fitem"><label>Code Postal</label><input name="codepostal" class="easyui-validatebox" ></div>-->
        <!--        <div class="fitem"><label>Ville</label><input name="ville" class="easyui-validatebox" ></div>-->

    </form>
</div>
<div id="dlg-buttonsmed">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveMed()">Enregistrer</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="$('#dlgmed').dialog('close')">Annuler</a>
</div>

<div id="dlginf" class="easyui-dialog" data-options="left:200,top:50" style="width:350px;padding:50px 20px;"
     title="Utilisateur Habilit&eacute;"
     closed="true" buttons="#dlg-buttonsinf">
    <form id="fminf" method="post" novalidate>
        <div class="fitem" ><label style="width:200px">Identit? Utilisateur</label>
            <input name="login" class="easyui-combobox" style="width:250px" required="true" url="allowedcab/getloginslist.php" valueField="cblogin" textField="cblogin_t"></div>
    </form>

</div>
<div id="dlg-buttonsinf">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveInf()">Enregistrer</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="$('#dlginf').dialog('close')">Annuler</a>
</div>



<?php
//laisser l? pour contourner le non affichage des piwik de ids
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


    function formatOperational(val, row)
    {
        // if((val=='1') || (val==1))
        if (val==1)
            return 'Oui';
        return 'Non';


    }

    function formatStatus(val, row)
    {
        // if((val=='1') || (val==1))
        if (val==0)
            return 'Actif';
        return 'Sorti';


    }



</script>



</body>
</html>
