<?php

 require_once("bean/beanparser/htmltags.php"); 
 require_once("view/jsgenerator/jsgenerator.php"); 
 require_once("view/common/vars.php"); 

require_once "Config.php";
$config = new Config();
session_start();

global $currentObjectName;


$js = new JSValidation();
$js->dateInRange("$currentObjectName:date","Date du dépistage");


if(!isset($_SESSION["cabinet"]))
{
    header("location:" . $config->psa_path);
}

set_time_limit(120);

$list_inf = array();
if (empty($list_inf))
{
    require_once "bean/DemandeFraisSuivi.php";
    $fraisSuivi = new DemandeFraisSuivi();
    $list_inf = $fraisSuivi->getInfs();
    $infProfession = $fraisSuivi->getUserProfessionByLogin($_SESSION["id.login"]);
}
$list_status = array();
if (empty($list_status))
{
    require_once "bean/DemandeFraisStatus.php";
    $fraisStatuses = new DemandeFraisStatus();
    $list_status = $fraisStatuses->getStatuses();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="content-type"content="text/html; charset=ISO-8859-1">
    <!-- ---FONTAWESOME--- -->
    <link rel="stylesheet" href="<?php echo $config->psa_path.'/lib/fontawesome-free-5.6.3-web/css/all.min.css' ?>" >
    <title>Gestion des frais</title>
    <style type="text/css">
        #fm{
            margin:0;
            padding:10px 30px;
        }

        .textbox textarea.textbox-text{
            white-space:pre-wrap;
        }

        .datagrid-header .datagrid-cell{
            line-height:normal;selected
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

        .tooltip {
            position: relative;
            display: inline-block;
            border-bottom: 1px dotted black;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 120px;
            background-color: black;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px 0;
            position: absolute;
            z-index: 1;
            top: 150%;
            left: 50%;
            margin-left: -60px;
        }

        .tooltip .tooltiptext::after {
            content: "";
            position: absolute;
            bottom: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: transparent transparent black transparent;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
        }
        i{
            color:#1ABC9C;
        }
        .text_box{
            background-color: #FFFACD;
            border-color: red;
            border-style: solid;
           
        }
        #aide{
            text-decoration: none;
            color:#800000;
        }
        #aide_nature{
            text-decoration: none;
            color:#800000;
        }
        .etoile{
            display: none;
            background-color:red;
        }
      
    
}
    </style>   

    <link rel="stylesheet" type="text/css" href="<?= $config->psa_path ?>/lib/jquery-easyui-1.6.7/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="<?= $config->psa_path ?>/lib/jquery-easyui-1.6.7/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="<?= $config->psa_path ?>/lib/jquery-easyui-1.6.7/demo/demo.css">   
    <script type="text/javascript" src="<?= $config->psa_path ?>/lib/jquery-easyui-1.6.7/jquery.min.js"></script>
    <script type="text/javascript" src="<?= $config->psa_path ?>/lib/jquery-easyui-1.6.7/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="<?= $config->psa_path ?>/lib/jquery-easyui-1.6.7/locale/easyui-lang-fr.js"></script>

    <script type="text/javascript">
        function calculerMontant(distance, taux) {
            let montant = distance * taux
            resultat = Math.round(montant * 100) / 100
            if (isNaN(resultat)) {
                $('#montant').val('');
                }else{
                    $("#montant").val(resultat.toString());
                }
        }

      
    </script>
    <script type="text/javascript">


        $( document ).ready(function() {
            $("#distance").keyup(function(){
              calculerMontant(parseFloat(document.getElementById('distance').value),parseFloat(document.getElementById('taux_applique').value)); 
            });

            $("#distance").change(function(){
              calculerMontant(parseFloat(document.getElementById('distance').value),parseFloat(document.getElementById('taux_applique').value)); 
            });

            $("#taux_applique").change(function(){
                calculerMontant(parseFloat(document.getElementById('distance').value),parseFloat(document.getElementById('taux_applique').value)); 
               
            });
            $("#taux_applique").keyup(function(){
                calculerMontant(parseFloat(document.getElementById('distance').value),parseFloat(document.getElementById('taux_applique').value)); 
            });
            
            $("#puissance").change(function(){
              calculerMontant(parseFloat(document.getElementById('distance').value),parseFloat(document.getElementById('taux_applique').value)); 
            });

            $("#puissance").keyup(function(){
                let puiss = parseInt($('#puissance').val());

                switch (true) {
                    case (puiss<=3):
                        document.getElementById('taux_applique').value = 0.410;
                        break;

                    case (puiss==4):
                        document.getElementById('taux_applique').value = 0.493;
                        break;

                    case (puiss==5):
                        document.getElementById('taux_applique').value = 0.543;
                        break;

                    case (puiss==6):
                        document.getElementById('taux_applique').value = 0.568;
                        break;

                    case (puiss >= 7 && puiss <= 15):
                        document.getElementById('taux_applique').value = 0.595;
                        break;

                    default:
                        break;
                }

                calculerMontant(parseFloat(document.getElementById('distance').value),parseFloat(document.getElementById('taux_applique').value)); 
               
            });


        });

        let url;
        let pJoint = false;
        function editFrais()
        {
            $('b').addClass("etoile");
           
            $("#div_aide_nature").show();
            $("#aide_nature").show();
           
            removeClass();
            $('#dg_demandefrais').dialog({
                    closable: false
                    });
            pJoint = true;
            let row = $('#dg').datagrid('getSelected');
            if (row){
                $('#dg_demandefrais').dialog('open').dialog('setTitle','Modification de la demande de frais');
                $('#fm').form('clear');
                document.getElementById("error").innerText = "";
                $('#fm').form('load', 'services/getdata.php?id='+ row.id);
                $('#fm').form({
                    onLoadSuccess: function (data) {
                        console.log('load success '+ row.id + ' test : '+ data);
                        $("#login_demandeur").show();
                        $("#date_demande").show();
                        $("#div_taux").show();
                        $("#nom_intervenant").show();
                        $("#id_status").show();
                        $("#date_dernierStatut").show();
                        $("#notes").show();
                        $('#div_precision').show();
                        $('#div_aide_precision').show();
                        $("#zeRadio").hide();
                        document.getElementById('taux_applique').disabled = false;
                        document.getElementById('puissance').disabled = false;
                        document.getElementById('montant').disabled = false;
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
            else{
                alert('Choisissez une demande de frais');
            }
            removeClass();
            
        }

        function newFrais()
        {
            $('b').removeClass("etoile");
            $("#div_aide_nature").show();
            $("#aide_nature").show();

            $('#dg_demandefrais').dialog({
                    closable: false
                    });
            pJoint = false;
            $('#dg').datagrid('unselectAll');
            $('#dg_demandefrais').dialog('open').dialog('setTitle','Nouvelle demande de frais');
            document.getElementById('distance').disabled = true;
            document.getElementById('montant').readOnly = false;
            document.getElementById('nature').readOnly = false;
            $('#fm').form('clear'); $('#fm').form('clear');
            document.getElementById("r2").checked = true;
            //duplicate reset just to be sure
            $("#distance").val("");
           
            document.getElementById("error").innerText = "";
            $("#login_demandeur").hide();
            $("#date_demande").hide();
            $("#div_taux").hide();
            $("#nom_intervenant").hide();
            $("#id_status").hide();
            $("#date_dernierStatut").hide();
            $("#notes").hide();
            $('#div_precision').hide();
            $('#div_aide_precision').hide();
            $("#zeRadio").show();        
            
            
             // $('#fm').form('load', 'services/getdata.php?id=-2');

            //roquete pour envoi l'id -2 à getdata.php
            const id = -2;
            let xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200)
                    {
                       console.log('l\'id à été bien envoyé!'); 
                    }
                };
                xhttp.open("GET", "services/getdata.php?id="+id , true);
                xhttp.send();
            
            url = 'services/save.php';
            
            document.getElementById('nature').disabled = false;
            document.getElementById('montant').disabled = false;
            document.getElementById('taux_applique').disabled = false;
            document.getElementById('puissance').disabled = false;
        }

        function saveFrais()
        {

                //supprime la class au moment du enregistrement
                removeClass();
                 

                //controler si la date est valide 
                $("#date_frais").removeClass("text_box");             
                var dateformat = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/;
                var Val_date=$('#date_frais').val();
                 if(Val_date.match(dateformat)){
                    var seperator1 = Val_date.split('/');
                    var seperator2 = Val_date.split('-');

                    if (seperator1.length>1)
                    {
                        var splitdate = Val_date.split('/');
                    }
                    else if (seperator2.length>1)
                    {
                        var splitdate = Val_date.split('-');
                    }
                    var dd = parseInt(splitdate[0]);
                    var mm  = parseInt(splitdate[1]);
                    var yy = parseInt(splitdate[2]);
                    var ListofDays = [31,28,31,30,31,30,31,31,30,31,30,31];
                    if (mm==1 || mm>2)
                        {
                            if (dd>ListofDays[mm-1])
                            {
                                $("#date_frais").addClass("text_box");
                                alert('Vous avez saisi une date non valide!');
                                return ;
                            }
                        }
                        if (mm==2)
                        {
                            var lyear = false;
                            if ( (!(yy % 4) && yy % 100) || !(yy % 400))
                            {
                                lyear = true;
                            }
                            if ((lyear==false) && (dd>=29))
                            {
                                $("#date_frais").addClass("text_box");
                                alert('Vous avez saisi une date non valide!');
                                return ;
                            }
                            if ((lyear==true) && (dd>29))
                            {
                                $("#date_frais").addClass("text_box");
                                alert('Vous avez saisi une date non valide!');
                                return ;
                            }
                        }
                    }
                    else
                    {
                        $("#date_frais").addClass("text_box");
                        alert("Vous avez saisi une date non valide!");

              return ;
          }//fin controle date 

            let puissance = $("#puissance").val();
            puissance = Number.parseFloat(puissance,10);
            motif = $("#motif").val();
            var today = new Date();
            let date_frais = $("#date_frais").val();          
            document.getElementById("error").innerText = "";

            //variables pour le controle de la extension du justificatif
            var extensions_ok = "pdf,png,jpg,jpeg,xls,xlsx,csv";
            var file_name = $("#nouveau_justificatif").val(); // nom du fichier (ici en jQuery)
            var file_array = file_name.split('.');
            var file_extension = file_array[file_array.length-1]; // extension du fichier (dernier élément)

            

            let from = $("#date_frais").val().split("/");
            let date_f = new Date(from[2], from[1] - 1, from[0]);
            //controler la date si l'annee est plus petit que 1900
            if(from[2] < 1900){
                $("#date_frais").addClass("text_box");
                alert('Veuillez saisir une date  après 1900!');
                return;
            }

         
            

            console.log($("#date_frais").val());

            // controle des frais kilométriques
            let r1 = document.getElementById('r1');
            if(r1.checked == true)
            {
               
                    distance = Number.parseFloat(distance);
                    let taux = Number.parseFloat(document.getElementById("taux_applique").value);
                 


               
                    if(motif.length < 3 ){
                            $("#motif").addClass("text_box");
                            document.getElementById("error").innerText = 'le motif doit-être de 3 caractères minimum!';
                            return;
                    }else if($("#distance").val() == '' || $("#distance").val() >= 1000 || isNaN($("#distance").val()) ){
                            $("#distance").addClass("text_box");
                            document.getElementById("error").innerText = 'Veuillez saisir une distance valide!';
                            return;
                    }else if($("#distance").val() <= 0){
                            $("#distance").addClass("text_box");
                            document.getElementById("error").innerText = 'Veuillez saisir une distance supérieur à zero!';
                            return;
                    }else if($("#puissance").val() != Number.parseInt(puissance,10) || isNaN($("#puissance").val())){
                            $("#puissance").addClass("text_box");
                            document.getElementById("error").innerText = "La puissance doit-être une valeur entière !";
                            return;
                    }else if($("#puissance").val() < 1 || $("#puissance").val() > 15){
                            $("#puissance").addClass("text_box");
                            document.getElementById("error").innerText = "La valeur de la puissance doit se situer entre 1 et 15 (inclus) !";
                            return;
                    }else if($("#nouveau_justificatif").val() == '' && pJoint == false){

                            document.getElementById("error").innerText = 'Veuillez charger un justificatif valide!';
                            return;
                    }else if(extensions_ok.indexOf(file_extension)===-1){

                            document.getElementById("error").innerText = "La pièce jointe n'est pas au bon format. Formats permis : pdf, png, jpg, jpeg, xls, xlsx, csv";
                            return;
                    }else if(date_frais === ""){

                            document.getElementById("error").innerText = 'Veuillez remplir une date !';
                            return;
                    }else if(date_f.getTime() > today.getTime()){
                            $("#date_frais").addClass("text_box");
                            document.getElementById("error").innerText = "La date que vous avez saisie ne peut être supérieure à la date du jour !";
                            return;
                    }else if(date_f.getDate() == today.getDate()){

                            if(confirm('La date de la dépense est-elle bien celle du jour ?'))
                                console.log("enregistrement OK!");
                            else
                                return;

                    }else{
                                console.log("enregistrement OK!");

                    }


            }else{
                //Controles pour la partie radio Autre

                var montant = $('#montant').val();
                var nature = $('#nature').val();

               

                if(nature.length < 3 ){
                        $("#nature").addClass("text_box");
                        document.getElementById("error").innerText = 'La nature doit-être de 3 caractères minimum!';
                        return;
                }else if(motif.length < 3 ){
                        $("#motif").addClass("text_box");
                        document.getElementById("error").innerText = 'Le motif doit-être de 3 caractères minimum!';
                        return;

                }else if(montant == '' || isNaN(montant) ) {
                        $("#montant").addClass("text_box");
                        document.getElementById("error").innerText = "Veuillez saisir un montant! ";
                        return ;
                }else if(montant <= 0) {
                        $("#montant").addClass("text_box");
                        document.getElementById("error").innerText = "Veuillez saisir un montant supérieur à zero! ";
                        return ;

                }else if(date_f.getTime() > today.getTime()){
                        $("#date_frais").addClass("text_box");
                        document.getElementById("error").innerText = "La date que vous avez saisie ne peut être supérieure à la date du jour !";
                        return;
                }else if(date_frais === ""){

                        document.getElementById("error").innerText = 'Veuillez remplir une date !';
                        return;

                }else if($("#nouveau_justificatif").val() == '' && pJoint == false){

                    document.getElementById("error").innerText = 'Veuillez charger un justificatif valide!';
                    return;
                }else if(extensions_ok.indexOf(file_extension)===-1){

                    document.getElementById("error").innerText = "La pièce jointe n'est pas au bon format. Formats permis : pdf, png, jpg, jpeg, xls, xlsx, csv";
                    return;

                }else if(montant >= 300 && montant < 2000 ) {
                    if(confirm('le montant saisi étant supérieur à 300 Euros pouvez-vous confirmer ce montant ?'))
                        console.log("enregistrement OK!");
                    else
                        return;
                    ;

                }else if(montant >= 2000 ) {

                    document.getElementById("error").innerText = "Pour les montants supérieurs à 2000 Euros, merci de faire la demande de remboursement de frais  par mail à gestion@asalee.fr ";
                    return ;             

                }else if(date_f.getDate() == today.getDate()){

                    if(confirm('La date de la dépense est-elle bien celle du jour ?'))
                        console.log("enregistrement OK!");
                    else
                        return;
                }else
                        console.log("enregistrement OK!");


            }
           
            // fin des controles, nous soumettons
           
            $('#fm').form('submit',{
                url: url,
                onSubmit: function(){
                    console.log("Je suis dans la soumission");
                    //return $(this).form('validate');
                },
                success: function(result){
                    console.log(result);
                    var result = eval('('+result+')');
                    if (result.success)
                    {
                        $('#dg_demandefrais').dialog('close');// close the dialog
                        $('#dg').datagrid('reload');// reload the user data
                        $('#dgHistory').datagrid('reload');// reload the history data
                    }
                    else
                    {
                        $.messager.show({
                            title: 'Error',
                            msg: result.msg
                        });
                    }
                }
            });
           
        }


        function cancelFrais()
        {
            
            removeClass();
            $('#dg').datagrid('reload');

            
           
          
        }

        function doSearch(){
            $('#dg').datagrid('load',{
                infirmiere_search: $('#infirmiere_search').combobox('getValue'),
                status_search: $('#status_search').combobox('getValue'),
                nsearch: $('#nsearch').val(),
                dsearch: $('#dsearch').val(),
                dsearch_frais: $('#dsearch_frais').val(),
                idsearch: $('#idsearch').val(),
                viewSearch: $('#viewSearch').val(),
            });
        }

        function doReset(){
            $('#infirmiere_search').combobox('setValue','');
            $('#status_search').combobox('setValue','');
            $('#nsearch').val('');
            $('#dsearch').val('');
            $('#dsearch_frais').val('');
            $('#idsearch').val('');
            doSearch();
        }

        function doSearchInf(){
            $('#dg').datagrid('load',{
                status_search: $('#status_search').combobox('getValue'),
                nsearch: $('#nsearch').val(),
                dsearch: $('#dsearch').val(),
                dsearch_frais: $('#dsearch_frais').val(),
                idsearch: $('#idsearch').val(),
                viewSearch: $('#viewSearch').val(),
            });
        }

        function doResetInf(){
            $('#status_search').combobox('setValue','');
            $('#nsearch').val('');
            $('#dsearch').val('');
            $('#dsearch_frais').val('');
            $('#idsearch').val('');
            doSearchInf();
        }

        // Contr?l saisie frais
        function doCheck(value) {
            if (value === 0) {
                removeClass();
                document.getElementById('nature').readOnly = true;
                document.getElementById('montant').readOnly = true;
                document.getElementById('distance').disabled = false;
                $('#div_precision').show();
                $('#div_aide_precision').show();
                $('#nature').val('kilomètres');
                $('#montant').val('');
                $('#distance').val('');
                $('#puissance').val('');
                let puissance = document.getElementById("puissance").value;
                document.getElementById("r2").disabled = true;
                $("#div_aide_nature").hide();
                $("#aide_nature").hide();
                


            }
            else {
                
                document.getElementById('nature').readOnly = false;
                document.getElementById('montant').readOnly = false;
                document.getElementById('distance').readOnly = true;
                $('#div_precision').hide();
                $('#div_aide_precision').hide();
                $('#nature').val('');
                $('#montant').val('');
                $('#distance').val('');

            }
        }

        // Load the detailed view
        function loadDetailedFrais()
        {
            let row = $('#dg').datagrid('getSelected');
            if (row){
                $('#dgHistory').datagrid('load',{
                    identifiant_demande: row.id
                });
                $("#dialogHistory").dialog('open').dialog('setTitle','Historique de la demande');
            }
            else
                alert('Choisissez une demande de frais');
        }

        // Load the resumed view
        function loadResumedFrais()
        {
            $("#dialogHistory").dialog('close');
        }

        // fonction pour changer le status dans la dataGrid
        function changeStatus(obj)
        {
            // debugger;

            let row = $('#dg').datagrid('getSelected');
            if(row)
            {
                console.log("id_status: " + row.id_status);
                console.log("id: " + row.id );;
                let xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200)
                    {
                        
                        console.log("Le changement de status a bien été effectué !");
                        $('#dg').datagrid('reload');
                    }
                };
                xhttp.open("GET", "../../../controler/StatusControler.php?id_status="+obj+"&id="+row.identifiant_suivi, true);
                xhttp.send();
            }
            else
            {
                alert("Veuillez selectionner une ligne!");
                $('#dg').datagrid('reload');
            }
        }
        function exporterDonnees(){

            let id_status = $('#status_search').combobox('getValue');
            console.log(id_status);
            let r=confirm('Voulez vous vraiment télécharger le fichier excel?');
            if(r==true){
                if(id_status < 1){
                    let t=confirm('Souhaitez vous télécharger toutes les données? Si ce n\'est ne pas le cas, veuillez selectionner un status pour filtrer ');
                    if(t == true){
                        window.location="services/exporter_demande_xlsx.php?id_status="+id_status;
                        return true;
                    }else{
                        return;
                    }
                }
                        window.location="services/exporter_demande_xlsx.php?id_status="+id_status;
                        return true;
            }else{
                    console.log('failed');
            }

        }
        function newEmail()
            {
                let row = $('#dg').datagrid('getSelected');
                if (row){
                    console.log(row.id);
                    $('#dg_envoyer_email').dialog('open').dialog('setTitle','Editer email');
                    $('#f_email').form('clear');

                    document.getElementById("error").innerText = "";

                    $('#f_email').form('load', 'services/getdata.php?id='+ row.id);
                    $('#f_email').form({
                        onLoadSuccess: function (data) {
                            console.log('load success '+ row.id + ' test : '+ data);
                            $("#login_demandeur").show();
                            $("#objet").val("Demande de remboursement de frais num : "+row.id);
                            $("#nom_intervenant").hide();
                            $("#notes").hide();

                    },
                    onLoadError: function (data) {
                        console.log(data);
                    },
                    onClickCell: function (rowIndex, field, value) {
                        console.log('load click cell '+ row.id + ' test : ');
                    }
                });

            }
            else
                alert('Choisissez une demande de frais');


        }
        function cancelEmail()
            {
                $('#dg_envoyer_email').dialog('close');
            }
        function envoyerEmail()
            {
                const url = 'services/send_email.php';
                $('#f_email').form('submit',{
                url: url

                });

                alert('Votre message a été envoyé avec succès!');
                $('#dg_envoyer_email').dialog('close');


            }
        function removeClass(){
            $("#motif").removeClass("text_box");
            $("#nature").removeClass("text_box");
            $("#distance").removeClass("text_box");
            $("#montant").removeClass("text_box");
            $("#puissance").removeClass("text_box");
            $("#date_frais").removeClass("text_box");
        }
        function popup_nature(){
                         
                        }
 
    </script>
</head>

<body bgcolor=#FFE887>
<?php

$cabinet = $_SESSION["cabinet"];

require_once($config->webservice_path . "/GetUserId.php");
require_once("../../stats/global/entete.php");

?>

<div style="text-align:center; font-size: 2em;">
    <h1 style="font-size: 1em;"> Asal&eacute;e </h1>
    <h1 style="font-size: 1em;"> Gestion des demandes de remboursement de frais </h1>
</div>
<br />
<br />
<br />

<!-- Principal DataGrid -->
<table id="dg" class="easyui-datagrid" style="width:auto"
       url="services/getdata.php?id=-1"
       title="Demandes de frais" toolbar="#toolbar"
       pagination="true" pageSize="50"
       rownumbers="true"
       singleSelect="true" fitColumns="true"
       nowrap="false"
       data-options="rowStyler: function(index, row){
                       if (row.nbOcc > 1){
                           return 'background-color: #FF6347; color:#fff; font-weight:bold;';
                       }
				   }
               ">
    <thead>
    <tr>
        <th field="identifiant_suivi" hidden> identifiant_suivi </th>
        <th field="id" sortable="true"> id </th>
        <th field="date_demande" sortable="true"> Date demande </th>
        <th field="titre" hidden> Titre </th>
        <th field="nom_demandeur" sortable="true">Demandeur </th>
        <th field="date_frais" sortable="true"> Date frais </th>
        <th field="nature"> Nature </th>
        <th field="motif"> Motif </th>
        <th field="distance" sortable="true"> Distance </th>
        <th field="puissance"> Puissance </th>
        <th field="taux_applique" hidden> Taux applique 1</th>
        <th field="montant"> Montant </th>
        <th field="justificatif"> Justificatif </th>
        <th field="nom_intervenant" sortable="true"> Dernier intervenant </th>
        <th field="dernierStatus" sortable="true" > Dernier status </th>
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
                    <a href="#" class="easyui-linkbutton" iconCls="icon-add"  onclick="newFrais()" id="btNew">Nouvelle demande</a>
                </td>
                <?php
                if ($infProfession == "gestionnaire") {
                    ?>
                    <td>
                        <a href="#" class="easyui-linkbutton" iconCls="icon-edit" onclick="editFrais()" id="btEdit">Modifier</a>
                    </td>
                    <?php
                }
                ?>
                <td id="btDetail">
                    <a href="#" class="easyui-linkbutton" iconCls="icon-redo" onclick="loadDetailedFrais()">Afficher l'historique d&eacute;taill&eacute;</a>
                </td>
                <?php
                    if ($infProfession == "gestionnaire") {
                ?>
                <td>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-save" onclick="exporterDonnees()">Exporter les données par status</a>
                </td>

                <td>
                    <a href="#" class="easyui-linkbutton"    onclick="newEmail()"><i class="fas fa-envelope "></i> Envoyer un email</a>
                </td>
                <?php
                    }
                ?>
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
                                echo "<option value='". $inf['login'] ."'>". htmlentities($inf['prenom'], ENT_QUOTES, "ISO-8859-1") ." ". htmlentities($inf['nom'], ENT_QUOTES, "ISO-8859-1") ."</option>";
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
                    <select id="status_search" class="easyui-combobox" selected name="status_search" style="width:150px;">
                        <option value="">Tous</option>
                        <?php
                       

                        foreach ($list_status as $status)
                            echo "<option value='". $status['id'] ."'>".htmlentities($status['intitule'], ENT_QUOTES, "ISO-8859-1")."</option>";

                        ?>
                    </select>
                </td>
                <td></td>
                <td>
                    <span>Nature</span>
                </td>
                <td>
                    <input id="nsearch" style="width:125px; line-height:20px;border:1px solid #ccc" />
                </td>
                <td></td>
                <td>
                    <span>Date de la demande</span>
                </td>
                <td>
                    <!--<input type="date"  id="dsearch"  style="width:125px; line-height:20px;border:1px solid #ccc" placeholder="jj/mm/aaaa" />-->
                    <?php text("size='10' id='dsearch' pattern=\"0*([1-9]|[12][0-9]|3[01])/0*([1-9]|1[0-2])/(19[0-8][0-9]|199[0-9]|20[0-9]{2}|2100)\"    placeholder='jj/mm/aaaa' onkeyup='formate_date(this)' ","$currentObjectName:date"); ?>
                </td>
                <td>
                    <span>Date Frais</span>
                </td>
                <td>
                    
                    <?php text("size='10' id='dsearch_frais' pattern=\"0*([1-9]|[12][0-9]|3[01])/0*([1-9]|1[0-2])/(19[0-8][0-9]|199[0-9]|20[0-9]{2}|2100)\"  placeholder='jj/mm/aaaa' onkeyup='formate_date(this)' ","$currentObjectName:date"); ?>
                    
                </td>
                <td>
                    <span>ID de la demande</span>
                </td>
                <td>
                    <input type="number" id="idsearch" style="width:125px; line-height:20px;border:1px solid #ccc" placeholder="Saisir un id" />
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

<div id="dg_demandefrais" class="easyui-dialog" data-options="left:250,top:40" style="width:800px;padding:50px 20px;height:auto"
     title="Demande de frais" closed="true" buttons="#dg_demandefrais-buttons">
    <div class="ftitle">
        <span style="color: #4cae4c">Aide à la saisie :</span><br /><br />
        <div id="div_aide_nature">
        <span style="color: #E02D2F"> <i id="aide" class="fas fa-money-bill-wave"></i> </span> Pour toute dépense concernant plusieurs personnes merci de mentionner les noms des personnes concernées dans le champ nature.<br /><br />
        </div>
        <div id="div_aide_precision">
            <span style="color: #E02D2F"> <i id="aide" class="fas fa-taxi"></i> </span> Zone p6 sur la carte grise.<br /><br />
            <a href="<?php echo $path?>/view/docs/frais/justif_deplacement.pdf" target="_blank" id="aide"><i id="aide" class="fas fa-info-circle fa-2x"></i> Aide à la saisie du justificatif Mappy</a>
        </div>

    </div>
    <form id="fm" method="post" enctype="multipart/form-data" novalidate>
        <span id="error" style="color: red"></span><br />
        <input type="hidden" name="id">
        <input type="hidden" name="id_demandeur">
        <input type="hidden" name="identifiant_suivi">
        <br /><div id="zeRadio" class="fitem" style="margin-left: 50px">
            <input type="radio" id="r1" value="0" name="check_frais" onclick="doCheck(0)">Kilom&egrave;tres
            <input type="radio" id="r2" checked="checked" value="1" name="check_frais" onclick="doCheck(1)" >Autre
        </div><br />
        <div class="fitem" id="date_demande"><label for="date_demande" class="easyui-tooltip" title="">Date demande</label><input type="text" name="date_demande" class="easyui-validatebox" style="width:150px" required="true" disabled /></div>
        <div class="fitem" id="login_demandeur">
            <label for="login_demandeur" class="easyui-tooltip" title="">Demandeur</label>
            <select id="login_demandeur" class="easyui-combobox" name="login_demandeur" style="width:150px;">
                <?php
                foreach ($list_inf as $inf)
                    echo "<option value='". $inf['login'] ."'>". htmlentities($inf['prenom'], ENT_QUOTES, "ISO-8859-1") ." ". htmlentities($inf['nom'], ENT_QUOTES, "ISO-8859-1") ."</option>";
                ?>
            </select>
        </div>
        <div class="fitem"><label for="date" class="easyui-tooltip" title="">Date frais</label><span style="color: #E02D2F"  ><b id="star" class=""> *</b> </span><?php text("size='10' id='date_frais' class='' pattern=\"0*([1-9]|[12][0-9]|3[01])/0*([1-9]|1[0-2])/(19[0-8][0-9]|199[0-9]|20[0-9]{2}|2100)\" placeholder='jj/mm/aaaa' name='date_frais'  onkeyup='formate_date(this)' ","$currentObjectName:date"); ?></div>
        <div class="fitem"><label for="nature" class="easyui-tooltip" title="">Nature <i id="aide_nature" class="fas fa-money-bill-wave"></i></label><span style="color: #E02D2F" > <b id="star" class=""> *</b> </span><input name="nature" id="nature"  class="" style="width:250px" pattern=".{3,}" required="true" onclick="popup_nature()"></div>
        <div class="fitem"><label for="motif" class="easyui-tooltip" title="">Motif  </label><span style="color: #E02D2F" ><b id="star" class=""> *</b> </span><input id="motif" name="motif" class="" style="width:250px" required="true" pattern=".{3,}" ></div>
        <div class="fitem"><label for="distance" class="easyui-tooltip" title="" onkeypress="calculateAmount()">Distance</label><span style="color: #E02D2F" > <b id="star" class=""> *</b> </span><input type="text" id="distance" name="distance" class="" style="width:250px"></div>
        <div class="fitem" id="div_precision"><label for="puissance" class="easyui-tooltip" title=""> Puissance fiscale <i id="aide" class="fas fa-taxi"></i></label><span style="color: #E02D2F" > <b id="star" class=""> *</b> </span><input type="ext" id="puissance" name="puissance" class="" style="width:250px" ></div>
        <div class="fitem" id="div_taux"><label for="taux_applique" class="easyui-tooltip" title="">Taux applique 2</label><input id="taux_applique" name="taux_applique" class="easyui-validatebox" style="width:250px" disabled></div>
        <div class="fitem"><label for="montant" class="easyui-tooltip" title="">Montant</label><span style="color: #E02D2F" > <b id="star" class=""> *</b> </span><input name="montant" class="" id="montant" style="width:250px"></div>
        <div class="fitem"><label for="file" class="easyui-tooltip" title=""> T&eacute;l&eacute;charger justificatif</label><span style="color: #E02D2F" > <b id="star" class=""> *</b> </span><input type="file" name="nouveau_justificatif" class="easyui-validatebox" style="width:250px" id="nouveau_justificatif"  ></div>
        <div class="fitem" id="nom_intervenant"><label for="nom_intervenant" class="easyui-tooltip" title="">Dernier intervenant</label><input name="nom_intervenant" class="easyui-validatebox" style="width:250px" disabled></div>
        <div class="fitem" id="id_status">
            <label>Dernier status</label>
            <select id="id_status" class="easyui-combobox" name="id_status" style="width:150px;">
                <?php
               
                foreach ($list_status as $status)
                    echo "<option  value='". $status['id'] ."'>". htmlentities($status['intitule'], ENT_QUOTES, "ISO-8859-1") ."</option>";
                ?>
            </select>
        </div>
        <div class="fitem" id="date_dernierStatut"><label class="easyui-tooltip" title="">Date dernierStatut</label><input name="date_dernierStatut" class="easyui-validatebox" style="width:250px" disabled></div>
        <div class="fitem" id="notes"><label class="easyui-tooltip" title="">Notes</label><textarea name="notes" class="easyui-validatebox" cols="33" rows="6"></textarea></div>
    </form>
</div>
</div>
<!--Form envoyer email-->
<div id="dg_envoyer_email" class="easyui-dialog" data-options="left:250,top:40" style="width:auto;padding:50px 20px;height:auto"
             title="Envoyer Email" closed="true" buttons="#dg_envoyer_email-buttons">
            <div class="ftitle">Envoyer Email</div>
            <form id="f_email" method="post" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="id">
                <input type="hidden" name="id_demandeur">
                <input type="hidden" name="identifiant_suivi">


                <div class="fitem" id="login_demandeur">
                    <label for="cible" class="easyui-tooltip" title="">Envoyer à:</label>
                    <select id="login_demandeur" class="easyui-combobox" name="login_demandeur" style="width:150px;">
                        <?php
                        foreach ($list_inf as $inf)
                            echo "<option value='". $inf['login'] ."'>". htmlentities($inf['prenom'], ENT_QUOTES, "ISO-8859-1") ." ". htmlentities($inf['nom'], ENT_QUOTES, "ISO-8859-1") ."</option>";
                        ?>
                    </select>

                </div>
                <div class="fitem" id="obj"><label for="obj" class="easyui-tooltip" title="">Objet:</label><input type="text" name="objet" id="objet"  class="easyui-validatebox" style="width:350px"  /></div>
                <br />

                <div class="fitem" id="id_status">

                </div>

                <div class="fitem"><label class="easyui-tooltip" title="">Texte:</label><textarea name="notes" class="easyui-validatebox" cols="33" rows="6"></textarea></div>
            </form>
            <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="envoyerEmail()">Envoyer</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="cancelEmail();javascript:$('#dg_envoyer_email').dialog('close')">Annuler</a>
        </div>
<!--fin du form-->
<!-- Buttons for form -->
<div id="dg_demandefrais-buttons">
    <div id="star" style="text-align:left;">
    <b><small ><span style="color: #E02D2F"> * </span>Champ obligatoire!</small></b>
    </div>
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveFrais()">Enregistrer</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="cancelFrais();javascript:$('#dg_demandefrais').dialog('close')">Annuler</a>
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
            <th field="date_frais"> Date frais </th>
            <th field="nature"> Nature </th>
            <th field="motif"> Motif </th>
            <th field="distance"> Distance </th>
            <th field="taux_applique"> Taux applique 1</th>
            <th field="montant"> Montant </th>
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
                    <a href="#" class="easyui-linkbutton" iconCls="icon-edit" onclick="editFrais()" id="btEdit">Modifier</a>
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
