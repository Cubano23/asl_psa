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
        function newMG()
        {
            $('#dlg').dialog('open').dialog('setTitle','Nouveau Cabinet');
            $('#cabinet').readonly=false;
            $('#fm').form('clear');
            url = 'nir/save.php';
        }
        function editMG()
        {
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $('#dlg').dialog('open').dialog('setTitle','Modifier Cabinet');
                $('#cabinet').readonly=true;
                $('#fm').form('load',row);
                $('#password2').val($('#password').val());
                url = 'nir/update.php?cabinet='+row.cabinet;
            }
        }
        function cancelMG()
        {


        }
        function saveMG()
        {
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
                        $.post('cab/remove.php',{id:row.id},function(result)
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
        function doSearch()
        {
            var x = $('#cabsearch').combobox('getValue');

            $('#dg').datagrid('load',{
                cabsearch: x
            });
        }

    </script>

</head>
<body bgcolor=#FFE887>
<?php

require("../global/entete.php");

entete_asalee("Gestion des NIR");
?>


<br />
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
        <th field="password" type="password" >Mot de Passe</th>
        <th field="nom_complet" width="150" sortable="true">Nom Complet</th>
        <th field="nom_cab" sortable="true">Nom Cabinet</th>
        <th field="contact" sortable="true">Contact</th>
        <th field="infirmiere" sortable="true">Infirmière</th>

    </tr></thead>
    <thead><tr>
        <th field="courriel" >Courriel</th>
        <th field="telephone" width="60">Téléphone</th>
        <th field="portable" width="60">Portable</th>
        <th field="ville" >Ville</th>
        <th field="region" width="75" sortable="true">Région</th>
        <th field="logiciel" width="75" sortable="true">Logiciel</th>
        <th field="log_ope" width="20" editor="{type:'checkbox',options:{on:'1',off:'0'}}">Op</th>
        <th field="total_pat" width="75">Total Patients</th>
        <th field="total_sein" width="100">Patients Eligibles <br />Dépistage Cancer Sein</th>
        <th field="total_cogni" width="120">Patients Eligibles <br />Dépistage Troubles Cognitifs</th>
        <th field="total_colon" width="100">Patients Eligibles <br />Dépistage Cancer Colon</th>
        <th field="total_uterus" width="100">Patients Eligibles <br />Dépistage Cancer Utérus</th>
        <th field="total_diab2" width="100">Patients Eligibles <br />Suivi Diabète II</th>
        <th field="total_HTA" width="100">Patients Eligibles <br />Suivi RCVA</th>



    </tr>
    </thead>
</table>

<div id="toolbar" style="padding:5px;height:auto">
    <div style="margin-bottom:5px">
        <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newMG()">Créer</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editMG()">Modifier</a>
        <span>Cabinet:</span> 	<input name="cabsearch"  id="cabsearch" class="easyui-combobox" style="width:200px"
                                        url="cabinets_getlist.php"
                                        valueField="cab" textField="text">
        <a href="#" class="easyui-linkbutton" iconCls="icon-search"  plain="true" onclick="doSearch()">Recherche</a>

    </div>
</div>

<div id="dlg" class="easyui-dialog" style="width:600px;padding:10px 20px"
     closed="true" buttons="#dlg-buttons">
    <div class="ftitle">Cabinet</div>
    <form id="fm" method="post" novalidate>
        <div class="ftitle">Informations</div>
        <div class="fitem"><label class="easyui-tooltip" title="Pas d'accents">Cabinet</label><input name="cabinet" class="easyui-validatebox" style="width:150px" required="true"></div>
        <div class="fitem"><label>Mot de Passe</label><input type="password"  id ="password"  name ="password" class="easyui-validatebox" style="width:150px" required="true"></div>
        <div class="fitem"><label>Confirmer</label><input  type="password"  id="password2"   name="password2" class="easyui-validatebox" style="width:150px" required="true" validType="equals['#password']"></div>
        <div class="fitem"><label  class="easyui-tooltip" title="Par exemple cabinet des Dr Gautier, Dr Bandet, Dr Chevalier, Dr Salesse">Nom Complet</label><input name="nom_complet" class="easyui-validatebox" style="width:250px"  required="true"></div>
        <div class="fitem"><label>Nom Cabinet</label><input name="nom_cab" class="easyui-validatebox" style="width:250px" ></div>
        <div class="fitem"><label>Infirmière</label><input class="easyui-combobox" style="width:250px" name="infirmiere" url="infirmieres_getlist.php" valueField="inf" textField="inf_t" required="true"></div>

        <div class="fitem"><label class="easyui-tooltip" title="Ce nom est affiché sur la 1ère page du PSA lorsque l'infirmière est connectée">Contact</label><input name="contact" style="width:250px" class="easyui-validatebox" required="true"></div>

        <div class="fitem"><label>Courriel</label><input name="courriel" style="width:250px" class="easyui-validatebox" validType="email"></div>
        <div class="fitem"><label>Téléphone</label><input name="telephone" class="easyui-validatebox" ></div>
        <div class="fitem"><label>Portable</label><input name="portable" class="easyui-validatebox" ></div>
        <div class="fitem"><label>Ville</label><input name="ville" style="width:250px" class="easyui-validatebox" ></div>
        <div class="fitem"><label>Région</label><input class="easyui-combobox" style="width:250px" name="region" url="regions_getlist.php" valueField="reg" textField="reg_t"></div>
        <div class="fitem"><label>Logiciel</label><input class="easyui-combobox" style="width:250px" name="logiciel" url="logiciels_getlist.php" valueField="lgc" textField="lgc_t"></div>
        <div class="fitem"><label class="easyui-tooltip" title="0: Non, 1:Oui" >Opérationel</label><input class="easyui-numberbox"  name="log_ope" data-options="min:0, max:1"></div>
        <div class="ftitle">Patients</div>
        <div class="fitem"><label style="width:250px" class="easyui-tooltip" title="Environ 800 patients par médecin équivalent temps plein" >Total Patients</label><input name="total_pat" class="easyui-numberbox" data-options="min:0"></div>
        <div class="fitem"><label style="width:250px" class="easyui-tooltip" title="Environ 150 patients par médecin équivalent temps plein" >Patients Eligibles Dépistage Cancer Sein</label><input name="total_sein" class="easyui-numberbox" data-options="min:0"></div>
        <div class="fitem"><label style="width:250px" class="easyui-tooltip" title="Environ 120 patients par médecin équivalent temps plein" >Patients Eligibles Dépistage Trouble Cognitif</label><input name="total_cogni" class="easyui-numberbox" data-options="min:0"></div>
        <div class="fitem"><label style="width:250px" class="easyui-tooltip" title="Environ 300 patients par médecin équivalent temps plein" >Patients Eligibles Dépistage Cancer Colon</label><input name="total_colon" class="easyui-numberbox" data-options="min:0"></div>
        <div class="fitem"><label style="width:250px" class="easyui-tooltip" title="Environ 180 patients par médecin équivalent temps plein" >Patients Eligibles Dépistage Cancer Utérus</label><input name="total_uterus" class="easyui-numberbox" data-options="min:0"></div>
        <div class="fitem"><label style="width:250px" class="easyui-tooltip" title="Environ  50 patients par médecin équivalent temps plein" >Patients Eligibles Suivi Diabète II</label><input name="total_diab2" class="easyui-numberbox" data-options="min:0"></div>
        <div class="fitem"><label style="width:250px" class="easyui-tooltip" title="Environ 150 patients par médecin équivalent temps plein" >Patients Eligibles Suivi RCVA</label><input name="total_HTA" class="easyui-numberbox" data-options="min:0"></div>



    </form>
</div>
<div id="dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveMG()">Enregistrer</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="cancelMG();javascript:$('#dlg').dialog('close')">Annuler</a>
</div>

<?php
//laisser là pour contourner le non affichage des piwik de ids
echo("<br />");
?>

</body>
</html>
