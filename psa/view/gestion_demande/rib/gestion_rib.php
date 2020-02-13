<?php

/**
 * Created by SublimeText
 * User: Gisgo
 * Date: 21-11-2018
 * Time: 14:40
 */

require_once "Config.php";

$config = new Config();

session_start();

if(!isset($_SESSION["cabinet"]))
{
    header("location:" . $config->psa_path);
}

set_time_limit(120);

$list_inf = array();
if (empty($list_inf))
{
    require_once "bean/DemandeRibSuivi.php";
    $ribSuivi = new DemandeRibSuivi();
    $list_inf = $ribSuivi->getInfs();
    $infProfession = $ribSuivi->getUserProfessionByLogin($_SESSION["id.login"]);
}
$list_status = array();
if (empty($list_status))
{
    require_once "bean/DemandeRibStatus.php";
    $ribStatuses = new DemandeRibStatus();
    $list_status = $ribStatuses->getStatuses();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="content-type"
              content="text/html; charset=ISO-8859-15">
        <title>Gestion de Rib</title>
        <style type="text/css">
            #fm{
                margin:0;
                padding:10px 30px;
            }

            .textbox textarea.textbox-text{
                white-space:pre-wrap;
            }

            .datagrid-header .datagrid-cell{
                line-height:normal;
                height:auto;
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
        <link rel="stylesheet" type="text/css" href="<?= $config->psa_path ?>/lib/jquery-easyui-1.6.7/themes/default/easyui.css">
        <link rel="stylesheet" type="text/css" href="<?= $config->psa_path ?>/lib/jquery-easyui-1.6.7/themes/icon.css">
        <link rel="stylesheet" type="text/css" href="<?= $config->psa_path ?>/lib/jquery-easyui-1.6.7/demo/demo.css">

        <script type="text/javascript" src="<?= $config->psa_path ?>/lib/jquery-easyui-1.6.7/jquery.min.js"></script>
        <script type="text/javascript" src="<?= $config->psa_path ?>/lib/jquery-easyui-1.6.7/jquery.easyui.min.js"></script>
        <script type="text/javascript" src="<?= $config->psa_path ?>/lib/jquery-easyui-1.6.7/locale/easyui-lang-fr.js"></script>
        <script type="text/javascript">
            let url;

            function editRib()
            {
                var row = $('#dg').datagrid('getSelected');
                if (row){
                    $('#dg_demanderib').dialog('open').dialog('setTitle','Modification de la D&eacuteclaration de RIB');
                    $('#fm').form('clear');
                    $('#fm').form('load', 'services/getdata.php?id='+ row.id);
                    $('#fm').form({
                        onLoadSuccess: function (data) {
                            console.log('load success '+ row.id + ' test : '+ data);
                            $("#login_demandeur").show();
                            $("#date_demande").show();
                            $("#nom_intervenant").show();
                            $("#id_status").show();
                            $("#date_dernierStatut").show();
                            $("#notes").show();
                        },
                        onLoadError: function (data) {
                            console.log(data);
                        },
                        onClickCell: function (rowIndex, field, value) {
                            console.log('load click cell '+ row.id + ' test : ');
                        }
                    });
                    url = 'services/save.php';
                }
                else
                    alert('Choissez une D&eacteclaration de RIB');
            }

            function newRib()
            {
                $('#dg').datagrid('unselectAll');
                $('#dg_demanderib').dialog('open').dialog('setTitle','Nouvelle D&eacuteclaration de RIB');
                $('#fm').form('clear');
                $("#login_demandeur").hide();
                $("#date_demande").hide();
                $("#nom_intervenant").hide();
                $("#id_status").hide();
                $("#date_dernierStatut").hide();
                $("#notes").hide();
                $('#fm').form('load', 'services/getdata.php?id=-2');
                url = 'services/save.php';
               
                
            }

            function saveRib()
            {
               

                    if($("#nouveau_justificatif").val() == ''){
                        // your validation error action
                        alert('Veuillez charger un fichier valide!');
                        return false;

                    }
                  
               
                
                $('#fm').form('submit',{
                    url: url,
                    onSubmit: function(){
                        return $(this).form('validate');
                    },
                    success: function(result){
                        console.log(result);
                        var result = eval('('+result+')');
                        if (result.success){
                            $('#dg_demanderib').dialog('close');		// close the dialog
                            $('#dg').datagrid('reload');	// reload the user data
                            $('#dgHistory').datagrid('reload');	// reload the history data

                        } else {
                            $.messager.show({
                                title: 'Error',
                                msg: result.msg
                            });
                        }
                    }
                });
            }

            function cancelRib()
            {
                $('#dg').datagrid('reload');	// reload the user data
            }

            function doSearch(){
                $('#dg').datagrid('load',{
                    infirmiere_search: $('#infirmiere_search').combobox('getValue'),
                    status_search: $('#status_search').combobox('getValue'),
                    nsearch: $('#nsearch').val(),
                    dsearch: $('#dsearch').val(),
                    viewSearch: $('#viewSearch').val(),
                });
            }

            function doReset(){
                $('#infirmiere_search').combobox('setValue','');
                $('#status_search').combobox('setValue','');
                $('#nsearch').val('');
                $('#dsearch').val('');
                doSearch();
            }

            function doSearchInf(){
                $('#dg').datagrid('load',{
                    status_search: $('#status_search').combobox('getValue'),
                    nsearch: $('#nsearch').val(),
                    dsearch: $('#dsearch').val(),
                    viewSearch: $('#viewSearch').val(),
                });
            }

            function doResetInf(){
                $('#status_search').combobox('setValue','');
                $('#nsearch').val('');
                $('#dsearch').val('');
                doSearchInf();
            }

           

            // Load the detailed view
            function loadDetailedRib()
            {
                var row = $('#dg').datagrid('getSelected');
                if (row){
                    $('#dgHistory').datagrid('load',{
                        identifiant_demande: row.id
                   });
                    $("#dialogHistory").dialog('open').dialog('setTitle','Historique de la demande');
                }
                else
                    alert('Choissez une d&eacuteclaration de RIB');
            }

            // Load the resumed view
            function loadResumedRib()
            {
                $("#dialogHistory").dialog('close');
            }
            </script>
    </head>

    <body bgcolor=#dbb9cb>
        <?php

        $cabinet = $_SESSION["cabinet"];

        require_once($config->webservice_path . "/GetUserId.php");
        require_once("../../stats/global/entete.php");

        ?>

        <div style="text-align:center; font-size: 2em;">
            <h1 style="font-size: 1em;"> Asal&eacutee </h1>
            <h1 style="font-size: 1em;"> Gestion des d&eacuteclarations de Rib </h1>
        </div>
        <br />
        <br />
        <br />

        <!-- Principal DataGrid -->
        <table id="dg" class="easyui-datagrid" style="width:auto"
               url="services/getdata.php?id=-1"
               title="D&eacuteclaration de Rib" toolbar="#toolbar"
               pagination="true" pageSize="50"
               rownumbers="true"
               singleSelect="true" fitColumns="true"
               nowrap="false">
            <thead>
            <tr>
                <th field="identifiant_suivi" hidden> identifiant_suivi </th>
                <th field="id" sortable="true"> id </th>
                <th field="date_demande" sortable="true"> Date demande </th>
                <th field="titre" hidden> Titre </th>
                <th field="nom_demandeur" sortable="true">Demandeur </th>
                <th field="iban" sortable="true">IBAN </th>             
                <th field="justificatif"> Justificatif </th>
                <th field="nom_intervenant" sortable="true"> Dernier intervenant </th>
                <th field="dernierStatus" sortable="true"> Dernier status </th>
                <th field="date_dernierStatut" sortable="true"> Date dernier statut </th>
                <th field="notes"> Notes </th>
            </tr>
            </thead>
        </table>
        <!-- Toolbar Principal DataGrid -->
        <div id="toolbar" style="padding:5px;height:auto">
            <div style="margin-bottom:5px">
                <table>
                    <tr>
                            <td>
                                <a href="#" class="easyui-linkbutton" iconCls="icon-add"  onclick="newRib()" id="btNew">Nouvelle demande</a>
                            </td>
<?php
                            if ($infProfession == "gestionnaire") {
                            ?>
                            <td>
                                <a href="#" class="easyui-linkbutton" iconCls="icon-edit" onclick="editRib()" id="btEdit">Modifier</a>
                            </td>
                            <?php
                            }
                        ?>
                        <td id="btDetail">
                            <a href="#" class="easyui-linkbutton" iconCls="icon-redo" onclick="loadDetailedRib()">Afficher l'historique d&eacutetaill&eacute</a>
                        </td>
                    </tr>
                </table>
            </div>

            <div>
                <table>
                    <tr>
                        <?php
                        if ($infProfession == "gestionnaire")
                        {
                            ?>
                            <td>
                                <span>Infirmi&eacutere</span>
                            </td>
                            <td>
                                <select id="infirmiere_search" class="easyui-combobox" name="infirmiere_search" style="width:150px;">
                                    <option value="">Toutes</option>
                                    <?php
                                    foreach ($list_inf as $inf)
                                        echo "<option value='". $inf['login'] ."'>". utf8_encode($inf['prenom']) ." ". utf8_encode($inf['nom']) ."</option>";
                                    ?>
                                </select>
                            </td>
                            <?php
                        }
                        ?>
                        <td>
                            <span>Status</span>
                        </td>
                        <td>
                            <select id="status_search" class="easyui-combobox" name="status_search" style="width:150px;">
                                <option value="">Tous</option>
                                <?php
                                foreach ($list_status as $status)
                                    echo "<option value='". $status['id'] ."'>". utf8_encode($status['intitule']) ."</option>";
                                ?>
                            </select>
                        </td>
                        <td></td>
                        <td></td>
                        <td>
                            <span>Date de la demande</span>
                        </td>
                        <td>
                            <input type="date" id="dsearch" style="width:125px; line-height:20px;border:1px solid #ccc" placeholder="jj/mm/aaaa" />
                        </td>
                        <td>
                            <input type="hidden" name="viewSearch" value="resume" id="viewSearch" />
                        </td>
                        <td></td>
                        <?php
                        if ($infProfession == "gestionnaire") {
                            ?>
                            <td><a href="#" class="easyui-linkbutton" onclick="doSearch()" iconCls="icon-search">Rechercher</a></td>
                            <td><a href="#" class="easyui-linkbutton" onclick="doReset()" iconCls="icon-reload">Reinitialiser</a></td>
                            <?php
                        }
                        else {
                            ?>
                            <td><a href="#" class="easyui-linkbutton" onclick="doSearchInf()" iconCls="icon-search">Rechercher</a></td>
                            <td><a href="#" class="easyui-linkbutton" onclick="doResetInf()" iconCls="icon-reload">Reinitialiser</a></td>
                            <?php
                        }
                        ?>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Form new request -->
        <div id="dg_demanderib" class="easyui-dialog" data-options="left:250,top:40" style="width:auto;padding:50px 20px;height:auto"
             title="D&eacuteclaration de RIB" closed="true" buttons="#dg_demanderib-buttons">
            <div class="ftitle">D&eacuteclaration de RIB</div>
            <form id="fm" method="post" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="id">
                <input type="hidden" name="id_demandeur">
                <input type="hidden" name="identifiant_suivi">
				<div class="fitem" id="date_demande"><label for="date_demande" class="easyui-tooltip" title="">Date demande</label><input type="text" name="date_demande" class="easyui-validatebox" style="width:150px" required="true" disabled /></div>
                <div class="fitem" id="iban"><label for="iban" class="easyui-tooltip" title="">IBAN</label><input type="text" name="iban" class="easyui-validatebox" style="width:350px" required="true" /></div>

                
                <div class="fitem" id="login_demandeur">
                    <label for="login_demandeur" class="easyui-tooltip" title="">Demandeur</label>
                    <select id="login_demandeur" class="easyui-combobox" name="login_demandeur" style="width:150px;">
                        <?php
                        foreach ($list_inf as $inf)
                            echo "<option value='". $inf['login'] ."'>". utf8_encode($inf['prenom']) ." ". utf8_encode($inf['nom']) ."</option>";
                        ?>
                    </select>
                </div>
                
              
                <br />
               
              
              
                <div class="fitem"><label for="file" class="easyui-tooltip" title=""> t&eacutel&eacutecharger nouveau justificatif</label><input type="file" name="nouveau_justificatif" id="nouveau_justificatif" class="easyui-validatebox" style="width:250px" ></div>
                
                <div class="fitem" id="nom_intervenant"><label for="nom_intervenant" class="easyui-tooltip" title="">Dernier intervenant</label><input name="nom_intervenant" class="easyui-validatebox" style="width:250px" disabled></div>
                <div class="fitem" id="id_status">
                    <label>Dernier status</label>
                    <select id="id_status" class="easyui-combobox" name="id_status" style="width:150px;">
                        <?php
                        foreach ($list_status as $status)
                            echo "<option value='". $status['id'] ."'>". utf8_encode($status['intitule']) ."</option>";
                        ?>
                    </select>
                </div>
                <div class="fitem" id="date_dernierStatut"><label class="easyui-tooltip" title="">Date dernierStatut</label><input name="date_dernierStatut" class="easyui-validatebox" style="width:250px" disabled></div>
                <div class="fitem"><label class="easyui-tooltip" title="">Notes</label><textarea name="notes" class="easyui-validatebox" cols="33" rows="6"></textarea></div>
            </form>
        </div>
        <!-- Buttons for form -->
        <div id="dg_demanderib-buttons">
            <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveRib()">Enregistrer</a>
            <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="cancelRib();javascript:$('#dg_demanderib').dialog('close')">Annuler</a>
        </div>
        
        <!-- Request history DataGrid -->
        <div  id="dialogHistory" class="easyui-dialog" data-options="left:250,top:40" style="width:1500px; height:300px; padding:50px 20px; height:auto"
              closed="true">
            <table class="easyui-datagrid"
                   id="dgHistory"
                   url="services/getdata.php?id=-1"
                   toolbar="#toolbarHistory"
                   singleSelect="true" fitColumns="true"
                   pagination="true"
                   nowrap="false" >
                <thead>
                <tr>
                <th field="identifiant_suivi" hidden> identifiant_suivi </th>
                <th field="id"> id </th>
                <th field="date_demande"> Date demande </th>
                <th field="titre" hidden> Titre </th>
                <th field="nom_demandeur">Demandeur </th>
                <th field="iban">IBAN </th>             
                <th field="justificatif"> Justificatif </th>
                <th field="nom_intervenant"> Dernier intervenant </th>
                <th field="dernierStatus"> Dernier status </th>
                <th field="date_dernierStatut"> Date dernier statut </th>
                <th field="notes"> Notes </th>
            </tr>
                </thead>
            </table>
            <!-- buttons for request history DataGrid -->
            <div id="toolbarHistory" style="padding:5px;height:auto" >
                <div style="margin-bottom:5px">
                    <?php
                    if ($infProfession == "gestionnaire") {
                        ?>
                        <td>
                            <a href="#" class="easyui-linkbutton" iconCls="icon-edit" onclick="editRib()" id="btEdit">Modifier</a>
                        </td>
                        <?php
                    }
                    ?>
                    <td id="btResume">
                        <a href="#" class="easyui-linkbutton" iconCls="icon-undo" onclick="loadResumedRib()">Revenir à la vue r&eacutesum&eacutee</a>
                    </td>
                </div>
            </div>
        </div>
    </body>
</html>
