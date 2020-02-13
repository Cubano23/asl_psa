<?php

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
    require_once "bean/DemandeCGSuivi.php";
    $cgSuivi = new DemandeCGSuivi();
    $list_inf = $cgSuivi->getInfs();
    $infProfession = $cgSuivi->getUserProfessionByLogin($_SESSION["id.login"]);
}
$list_status = array();
if (empty($list_status))
{
    require_once "bean/DemandeCGStatus.php";
    $cgStatuses = new DemandeCGStatus();
    $list_status = $cgStatuses->getStatuses();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="content-type"
              content="text/html; charset=ISO-8859-15">
        <title>Gestion des cartes grises</title>
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
            var url;
            let pJoint = false;
            function editCG()
            {
                 pJoint = true;
                var row = $('#dg').datagrid('getSelected');
                if (row){
                    $('#dg_demandecg').dialog('open').dialog('setTitle','Modification de la d&eacute;claration');
                    document.getElementById("error").innerText = "";
                    $('#fm').form('load', 'services/getdata.php?id='+ row.id);
                    $('#fm').form({
                        onLoadSuccess: function (data) {
                            console.log('load success '+ row.id + ' test : '+ data);
                            $("#login_demandeur").show();
                            $("#date_demande").show();
                            $("#nom_intervenant").show();
                            $("#id_status").show();
                            $("#date_dernierStatut").show();
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
                    alert('Veuillez choisir une d&eacute;claration');
            }

            function newCG()
            {
                 pJoint = false;
                $('#dg_demandecg').dialog('open').dialog('setTitle','Nouvelle d&eacute;claration');
                $('#fm').form('clear');
                document.getElementById("error").innerText = "";
                // $('#fm').form('load', 'services/getdata.php?id=-2');
                $("#login_demandeur").hide();
                $("#date_demande").hide();
                $("#nom_intervenant").hide();
                $("#id_status").hide();
                $("#date_dernierStatut").hide();
                url = 'services/save.php';
            }

            function saveCG()
            {
                if($("#nouveau_justificatif").val() == '' && pJoint == false)
                {
                    // your validation error action
                    alert('Veuillez charger un fichier valide!');
                    return false;
                }

                let date_obtention = new Date(document.getElementById("date_obtention").value);
                console.log(date_obtention);
                let today = new Date();
                if (date_obtention.getTime() > today.getTime())
                {
                    document.getElementById("error").innerText = "La date d'obtention ne peut être supérieure à la date du jour !";
                    return
                }
                
                let puissance = document.getElementById("puissance").value;
                if (!parseInt(puissance))
                {
                    document.getElementById("error").innerText = "La puissance doit-ï¿½tre une valeur entiï¿½re !";
                    return
                }
                puissance = parseInt(puissance);
                if (puissance < 3 || puissance > 15)
                {
                    document.getElementById("error").innerText = "La valeur de la puissance doit se situer entre 3 et 15 (inclus) !";
                    return
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
                            $('#dg_demandecg').dialog('close');
                            $('#dg').datagrid('reload');

                        } else {
                            $.messager.show({
                                title: 'Error',
                                msg: result.msg
                            });
                        }
                    }
                });
            }

            function cancelCG()
            {
                $('#dg').datagrid('reload');	// reload the user data
            }

            function doSearch(){
                $('#dg').datagrid('load',{
                    infirmiere_search: $('#infirmiere_search').combobox('getValue'),
                    status_search: $('#status_search').combobox('getValue'),
                    nsearch: $('#nsearch').val(),
                    dsearch: $('#dsearch').val(),
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
                });
            }

            function doResetInf(){
                $('#status_search').combobox('setValue','');
                $('#nsearch').val('');
                $('#dsearch').val('');
                doSearchInf();
            }

            // Load the detailed view
            function loadDetailedFrais()
            {
                var row = $('#dg').datagrid('getSelected');
                if (row){
                    $('#dgHistory').datagrid('load',{
                        identifiant_demande: row.id
                    });
                    $("#dialogHistory").dialog('open').dialog('setTitle','Historique de la demande');
                }
                else
                    alert('Choissez une dï¿½claration de carte grise');
            }

            // Load the resumed view
            function loadResumedFrais()
            {
                $("#dialogHistory").dialog('close');
            }
        </script>
    </head>
    <body bgcolor=#FFE887>
        <?php
        $cabinet = $_SESSION["cabinet"];
        require_once($config->webservice_path . "/GetUserId.php");
        ?>

        <div style="text-align:center; font-size: 2em;">
            <h1 style="font-size: 1em;"> Asal&eacute;e </h1>
            <h1 style="font-size: 1em;"> Gestion des d&eacute;clarations de cartes grises</h1>
        </div>
        <br />
        <br />
        <br />

        <!-- Principal DataGrid -->
        <table id="dg" class="easyui-datagrid" style="width:1800px"
               url="services/getdata.php?id=-1"
               title="Dï¿½calation de cartes grises" toolbar="#toolbar"
               pagination="true" pageSize="50"
               rownumbers="true"
               singleSelect="true" fitColumns="true"
               nowrap="false">
            <thead >
            <tr>
                <th field="identifiant_suivi" hidden> identifiant_suivi </th>
                <th field="id" sortable="true"> id </th>
                <th field="date_demande" sortable="true"> date_demande </th>
                <th field="titre" hidden> Titre </th>
                <th field="nom_demandeur" sortable="true">Demandeur </th>
                <th field="date_obtention" sortable="true"> Date obtention </th>
                <th field="puissance" sortable="true"> Puissance </th>
                <th field="precisions" sortable="true"> Precisions </th>
                <th field="justificatif"> Justificatif </th>
                <th field="nom_intervenant" sortable="true"> Dernier intervenant </th>
                <th field="dernierStatus" sortable="true"> Dernier status </th>
                <th field="date_dernierStatut" sortable="true"> Date dernierStatut </th>
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
                            <a href="#" class="easyui-linkbutton" iconCls="icon-add"  onclick="newCG()" id="btNew">Nouvelle d&eacute;claration</a>
                        </td>
                        <?php
                        if ($infProfession == "gestionnaire") {
                            ?>
                            <td>
                                <a href="#" class="easyui-linkbutton" iconCls="icon-edit" onclick="editCG()" id="btEdit">Modifier</a>
                            </td>
                            <?php
                        }
                        ?>
                        <td id="btDetail">
                            <a href="#" class="easyui-linkbutton" iconCls="icon-redo" onclick="loadDetailedFrais()">Afficher l'historique d&eacute;taill&eacute;</a>
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
                                <span>Infirmi&egrave;re</span>
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
                                $lettre = "Ã©";
                                $chgLettre = "é";
                                foreach ($list_status as $status)
                                    echo "<option value='". $status['id'] ."'>".str_replace($lettre,$chgLettre,$status['intitule']) ."</option>";
                                ?>
                            </select>
                        </td>
                        <td></td>
                        <td>
                            <span>Date de la demande</span>
                        </td>
                        <td>
                            <input type="date" id="dsearch" style="width:125px; line-height:20px;border:1px solid #ccc" placeholder="jj/mm/aaaa" />
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
        <div id="dg_demandecg" class="easyui-dialog" data-options="left:250,top:40" style="width:800px;padding:50px 20px;height:auto"
             closed="true" buttons="#dg_demandefrais-buttons">
            <div class="ftitle">
                <span style="color: #4cae4c">NOTICE de validation de carte grise :</span><br /><br />
                <span style="color: #E02D2F"> * </span>Si la carte grise est à votre nom, merci de saisir la puissance fiscale du véhicule et la date d'obtention dans les champs appropriés et de télécharger une copie numérisée de la carte dans le formulaire suivant.<br /><br />
                <span style="color: #E02D2F"> * </span>Si la carte grise est au nom de votre conjoint merci de nous envoyer une copie de votre livret de famille à l'adresse gestion@asalee.fr .<br /><br />
                <span style="color: #E02D2F"> * </span>Si le titulaire est une personne tierce (autre que votre conjoint),  merci de nous communiquer par mail une attestation signée du prêt du véhicule à l'adresse gestion@asalee.fr .<br /><br />
            </div>
            <form id="fm" method="post" enctype="multipart/form-data" novalidate>
                <span id="error" style="color: red"></span><br />
                <input type="hidden" name="id">
                <input type="hidden" name="id_demandeur">
                <input type="hidden" name="identifiant_suivi">
                <div class="fitem" id="date_demande"><label for="date_demande" class="easyui-tooltip" title="">Date demande</label><input type="text" name="date_demande" class="easyui-validatebox" style="width:150px" required="true" disabled /></div>
                <div class="fitem" id="login_demandeur">
                    <label for="login_demandeur" class="easyui-tooltip" title="">Demandeur</label>
                    <select id="login_demandeur" class="easyui-combobox" name="login_demandeur" style="width:150px;">
                        <?php
                        foreach ($list_inf as $inf)
                            echo "<option value='". $inf['login'] ."'>". utf8_encode($inf['prenom']) ." ". utf8_encode($inf['nom']) ."</option>";
                        ?>
                    </select>
                </div>
                <div class="fitem"><label for="date_obtention" class="easyui-tooltip" title="">Date obtention</label><input type="date" name="date_obtention" id="date_obtention" class="easyui-validatebox" style="width:250px" pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}"></div>
                <div class="fitem"><label for="puissance" class="easyui-tooltip" title="">Puissance</label><input type="number" name="puissance" id="puissance" style="width:250px"></div>
                <div class="fitem"><label for="precisions" class="easyui-tooltip" title="">Precisions</label><input name="precisions" class="easyui-validatebox" style="width:250px"></div>
                <div class="fitem"><label for="file" class="easyui-tooltip" title=""> T&eacute;l&eacute;charger nouveau justificatif</label><input type="file" name="nouveau_justificatif" id="nouveau_justificatif" class="easyui-validatebox" style="width:250px" ></div>
                <div class="fitem" id="nom_intervenant"><label for="nom_intervenant" class="easyui-tooltip" title="">Dernier intervenant</label><input name="nom_intervenant" class="easyui-validatebox" style="width:250px" disabled></div>
                <div class="fitem" id="id_status">
                    <label>Dernier status</label>
                    <select id="id_status" class="easyui-combobox" name="id_status" style="width:150px;">
                        <?php
                        $lettre = "Ã©";
                        $chgLettre = "é";
                        foreach ($list_status as $status)
                            echo "<option value='". $status['id'] ."'>".str_replace($lettre,$chgLettre,$status['intitule']) ."</option>";
                        ?>
                    </select>
                </div>
                <div class="fitem" id="date_dernierStatut"><label class="easyui-tooltip" title="">Date dernierStatut</label><input name="date_dernierStatut" class="easyui-validatebox" style="width:250px" disabled></div>
                <div class="fitem"><label class="easyui-tooltip" title="">Notes</label><textarea name="notes" class="easyui-validatebox" cols="33" rows="6"></textarea></div>
            </form>
        </div>
        <div id="dg_demandefrais-buttons">
            <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveCG()">Enregistrer</a>
            <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="cancelCG();javascript:$('#dg_demandecg').dialog('close')">Annuler</a>
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
                        <th field="id" sortable="true"> id </th>
                        <th field="date_demande" sortable="true"> date_demande </th>
                        <th field="titre" hidden> Titre </th>
                        <th field="nom_demandeur" sortable="true">Demandeur </th>
                        <th field="date_obtention" sortable="true"> Date obtention </th>
                        <th field="puissance" sortable="true"> Puissance </th>
                        <th field="precisions" sortable="true"> Precisions </th>
                        <th field="justificatif"> Justificatif </th>
                        <th field="nom_intervenant" sortable="true"> Dernier intervenant </th>
                        <th field="dernierStatus" sortable="true"> Dernier status </th>
                        <th field="date_dernierStatut" sortable="true"> Date dernierStatut </th>
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
                            <a href="#" class="easyui-linkbutton" iconCls="icon-edit" onclick="editCG()" id="btEdit">Modifier</a>
                        </td>
                        <?php
                    }
                    ?>
                    <td id="btResume">
                        <a href="#" class="easyui-linkbutton" iconCls="icon-undo" onclick="loadResumedFrais()">Revenir &agrave; la vue r&eacute;sum&eacute;e</a>
                    </td>
                </div>
            </div>
        </div>
    </body>
</html>
