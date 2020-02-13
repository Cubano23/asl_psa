<?php

require_once "Config.php";
$config = new Config();

session_start();

if(!isset($_SESSION["cabinet"])){
    header("location:" . $config->psa_path);
}



set_time_limit(120);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="content-type"
          content="text/html; charset=ISO-8859-15">
    <title>Gestion des Patients avec NIR</title>
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





    <link rel="stylesheet" type="text/css" href="../jquery/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="../jquery/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="../jquery/demo/demo.css">


    <script type="text/javascript" src="../jquery/jquery.min.js"></script>
    <script type="text/javascript" src="../jquery/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../jquery/locale/easyui-lang-fr.js"></script>


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
            $('#dlg').dialog('open').dialog('setTitle','Nouveau Dossier');
            $('#numero').prop('readonly', false);
            $('#id').prop('readonly', true);
            $('#fm').form('clear');
            <?php if($_SESSION["cabinet"] == "moissanstabac2017"): ?>
            $('#numero').prop('value', "<?php echo $_SESSION['id.login'] ?>");
            <?php endif ?>
            url = 'nir/save.php';
        }
        function editMG()
        {
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $('#dlg').dialog('open').dialog('setTitle','Modifier Dossier');
                $('#numero').prop('readonly', false);
                $('#id').prop('readonly', true);
                $('#fm').form('load',row);
                url = 'nir/update.php';
            }
        }

        function editNIR()
        {
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $('#numero').prop('readonly', false);
                $('#nir').dialog('open').dialog('setTitle','Gérer NIR Dossier');
                $('#nir1').val('');
                $('#nir2').val('');
                $('#fmnir').form('load',row);
                url = 'nir/updatenir.php?numero='+row.numero+'&id='+row.id;
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


        function validateNIR(  inseeString )
        {
            var isValid = true;
            var partialInsee = inseeString.substring(0,13).replace("2A", 19).replace("2B", 18);
            var a = parseFloat(partialInsee) % 97;
            var clef = 97 - a;
            if(clef!=parseInt(inseeString.substring(13,15)))
                isValid = false;

            return isValid;
        }

        function saveNIR()
        {
            $('#fmnir').form('submit',{
                url: url,
                onSubmit: function(){
                    var error="";
                    var isValidate =  $(this).form('validate');

                    var vnir1 = $('#nir1').val().split(' ').join('');
                    vnir1 = vnir1.split('-').join('');
                    var vnir2 = $('#nir2').val().split(' ').join('');
                    vnir2 = vnir2.split('-').join('');
                    var sexe =  $('#nirsexe').combobox('getText');
                    var dnaiss =  $('#nirdnaiss').val(); //datebox("getValue");
//          alert(dnaiss);


                    var annee="66";
                    var mois ="06";

                    var sexe2="1";
                    var annee2="60";
                    var mois2="04";
                    var departement="92";

                    if(sexe.substring(0,1)=="M")
                        sexe="1";
                    if(sexe.substring(0,1)=="F")
                        sexe="2";

//          myPattern = '^[12][0-9]{2}[0-1][0-9](2[AB]|[0-9]{2})[0-9]{3}[0-9]{3}[0-9]{2}$';      Cct pour naissances algérie
//          myPattern = '^[127][0-9]{2}(50|90|[0-1][0-9])(2[AB]|[0-9]{2})[0-9]{3}[0-9]{3}[0-9]{2}$';
                    myPattern = '^[12378][0-9]{2}([0-9][0-9])(2[AB]|[0-9]{2})[0-9]{3}[0-9]{3}[0-9]{2}$';
                    var expressionInsee = new RegExp(myPattern);

                    //
                    //

                    //    alert(vnir1);
                    if (vnir1.length!=15)
                    {
                        error+="NIR Assuré n'a pas une taille de 15 \n";
                        isValidate = false;
                    }
                    else
                    {


                        if(vnir1.match(expressionInsee) )
                        {

                            sexe2= vnir1.substring(0,1);
                            annee2 = vnir1.substring(1,3);
                            mois2 = vnir1.substring(3,5);
                            isValidate = validateNIR(vnir1);
                        }
                        else
                            isValidate = false;
                        if(!isValidate)
                            error+="Erreur de Syntaxe NIR Assuré\n";

                    }
                    if ( (vnir2!="") && (vnir2.length!=15) )
                    {
                        error+="NIR Patient n'a pas une taille de 15 \n";
                        isValidate = false;
                    }
                    else
                    {
                        if(vnir2!="")
                        {
                            if(vnir2.match(expressionInsee) )
                            {


                                sexe2= vnir2.substring(0,1);
                                annee2 = vnir2.substring(1,3);
                                mois2 = vnir2.substring(3,5);
                            }
                            else
                            {
                                error+="Erreur de Syntaxe NIR Patient\n";
                                isValidate = false;

                            }
                        }
                    }
                    annee = dnaiss.substring(8,10);
                    mois  = dnaiss.substring(3,5);
                    /*  Court circuit pour cause de c.... des logiciels médicaux  15-01-2015 EA
                              if(sexe!=sexe2)
                              {
                                      error+="Incompatibilité de Sexe \n";
                                      isValidate = false;
                              }
                              if(mois!=mois2)
                              {
                                      error+="Incompatibilité de Mois de Naissance \n";
                                      isValidate = false;
                              }
                              if(annee!=annee2)
                              {
                                      error+="Incompatibilité d'Année de Naissance \n";
                                      isValidate = false;
                              }
                      */

//        var clef = 97 – (parseFloat(partialInsee) % 97);
//        if(clef!=parseInt(inseeString.substring(13,15)))
                    if(isValidate==false)
                        alert (error);
                    return  isValidate;
                },
                success: function(result){
                    var result = eval('('+result+')');
                    if (result.success){
                        $('#nir').dialog('close');		// close the dialog
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
                $.messager.confirm('Confirm','Etes vous sûrs d\'effacer le Dossier?',function(r)
                {
                    if (r)
                    {
                        $.post('nir/remove.php',{numero:row.numero},function(result)
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

        function removeNIR()
        {

            var row = $('#dg').datagrid('getSelected');
            if (row)
            {
                $.messager.confirm('Confirm','Etes vous sûrs d\'effacer le NIR du Dossier?',function(r)
                {
                    if (r)
                    {
                        $.post('nir/removenir.php',{numero:row.numero},function(result)
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


        function doSearch(){
            $('#dg').datagrid('load',{
//    timesearch: $('#timesearch').datebox('getValue'),
                ndsearch: $('#ndsearch').val(),
                dsearch: $('#dsearch').combobox('getValue'),
                nsearch: $('#nsearch').combobox('getValue')
//    psearch: $('#psearch').val()
            });
        }


        function doReset(){
            $('#ndsearch').val('');
            $('#dsearch').combobox('setValue','0');
            $('#nsearch').combobox('setValue','0');
//    $('#psearch').combobox('setValue','0');    
            doSearch();
        }


        // Formattage de la date pour dconsent
        function formate_date(zone){
            if(zone.value.length==2){
                zone.value=zone.value+"/";
            }
            if(zone.value.length==4){
                zone.value=zone.value.replace("//", "/");
            }
            if(zone.value.length==5){
                zone.value=zone.value+"/";
            }
            if(zone.value.length==7){
                zone.value=zone.value.replace("//", "/");
            }
        }

    </script>

</head>
<body bgcolor=#FFE887>
<?php

$cabinet = $_SESSION["cabinet"];

/*if(strtolower($cabinet)!="ztest")
    die("Option Non Possible");
*/
require_once ("Config.php");
$config = new Config();

/*$base=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
require("$base/inclus/accesbase.inc.php");*/

require($config->inclus_path ."/accesbase.inc.php");

# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD:". mysql_error());
mysql_select_db($DB) or
die("Impossible de se connecter à la base");


$table = "integration";

$base= $config->app_path;


?>


<br />
<br />
<br />


<table id="dg" class="easyui-datagrid" style="width:1600px"
       url="nir/getdata.php"
       title="Gestion des Dossiers avec NIR" toolbar="#toolbar"
       pagination="true" pageSize="50" pageList="[10,20,50,100,200]"
       rownumbers="true"
       singleSelect="true" fitColumns="true"
       nowrap="false" >
    <thead>	<tr>
        <th field="ck" checkbox="true"></th>
        <th field="numero" width="100" sortable="true" >Numéro Dossier</th>
        <th field="id" width="100"  >Identifiant Asalée</th>
        <th field="dnaiss" width="150" >Date Naissance</th>
        <th field="sexe" >Sexe</th>
        <th field="taille" >Taille</th>
        <th field="actif" >Actif</th>
        <th field="dconsentement" width="150" >Date Consentement</th>
        <th field="dcreat" width="150" >Date Création</th>
        <th field="dmaj" width="150" >Date Mise à Jour</th>
        <th field="encnir"  sortable="true" >NIR Chiffré</th>
    </tr></thead>
</table>

<div id="toolbar" style="padding:5px;height:auto">
    <div style="margin-bottom:5px">
        <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newMG()">Créer</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editMG()">Modifier</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editNIR()">Editer NIR</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeNIR()">Effacer NIR</a>
        <?php if(strtolower($cabinet)=="ztest")
        {

//     echo (' <a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="exportNIR()">Préparer fichier NIRs</a>');
//          echo (' <a href="#" class="easyui-linkbutton" iconCls="icon-export" plain="true" onclick="doExport()">Exporter Dossiers</a>');
            /*
                function doExport()
                    {
                     $("*").css("cursor", "progress");
                                    $.post('nir/exportdata.php',
                        {
                            ndsearch: $('#ndsearch').val(),
                            dsearch: $('#dsearch').combobox('getValue'),
                            nsearch: $('#nsearch').combobox('getValue')
                        },
                        function(result)
                                    {
                                        if (result.success)
                                        {
                                        } else
                                        {

                          // Construct the <a> element
                          var link = document.createElement("a");
                          link.download =  'nir/'+ result.fname ; // +   'nir/dossierexport.csv';
            //              alert(link.download);
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



                    function exportNIR()
                    {

                            $.messager.confirm('Confirm','Le fichier des NIRs va être exporté. Veuillez Confirmer',function(r)
                            {
                                if (r)
                                {
                                    $.post('nir/exportnir.php',function(result)
                                    {
                                        if (result.success)
                                        {
                                        }
                          else
                                        {
                                            $.messager.show({	// show error message
                                                title: 'NIRs',
                                                msg: "Enregistrements NIR Exportés:"+result.msg
                                            });
                                        }
                                    },'json');
                                }
                            });
                    }

            */



        }
        ?>

    </div>
    <div>
        <table>
            <tr>
                <td><span >Numéro Dossier:</span></td>
                <td><input id="ndsearch" style="line-height:20px;border:1px solid #ccc"></td>
                <td><span >Date Consentement:</span></td>
                <td><select id="dsearch" class="easyui-combobox" name="dsearch" style="width:100px;">
                        <option value="0">Tous</option>
                        <option value="1">Oui</option>
                        <option value="2">Non</option>
                    </select>
                </td>
                <td><span >NIR saisi?:</span></td>
                <td> <select id="nsearch" class="easyui-combobox" name="nsearch" style="width:100px;">
                        <option value="0">Tous</option>
                        <option value="1">Oui</option>
                        <option value="2">Non</option>
                    </select>
                </td>
                <!--             <td><span >Patient Consulté?:</span></td>
                           <td><select id="psearch" class="easyui-combobox" name="psearch" style="width:100px;">
                                             <option value="0">Tous</option>
                                             <option value="1">Oui</option>
                                </select>
                            </td>  -->
            </tr>
            <tr>
                <td></td>
                <td><a href="#" class="easyui-linkbutton" onclick="doSearch()" iconCls="icon-search">Rechercher</a></td>
                <td><a href="#" class="easyui-linkbutton" onclick="doReset()" iconCls="icon-reload">Reinitialiser</a></td>
            </tr>
        </table>
    </div>


</div>

<div id="dlg" class="easyui-dialog" style="width:600px;padding:10px 20px"
     closed="true" buttons="#dlg-buttons">
    <div class="ftitle">Dossier</div>
    <form id="fm" method="post" novalidate>
        <div class="ftitle">Informations</div>
        <div class="fitem"><label class="easyui-tooltip" title="Pas d'accents">Dossier</label><input name="numero" id="numero" class="easyui-validatebox" style="width:150px" required="true"></div>
        <?php if($_SESSION["cabinet"] == "moissanstabac2017"): ?>
            <div class="fitem">
                <p style="color:red; font-weight:bold;">
                    Dans le cabinet 'MOISSANSTABAC2017', les n° de dossier doivent être constitué de votre identifiant PSA puis d'un numéro d'ordre.
                </p>
            </div>
        <?php endif ?>
        <div class="fitem"><label >Id Asalée</label><input name="id" id="id"  style="width:150px" ></div>
        <div class="fitem"><label>Date Naissance</label><input name="dnaiss" style="width:250px" type="text" onkeyup='formate_date(this)' required="required" ></div>
        <div class="fitem"><label>Sexe</label>
            <select name="sexe" class="easyui-combobox" required="required">
                <option value="M" selected="true">Male</option>
                <option value="F">Femme</option>
            </select>
        </div>
        <div class="fitem"><label class="easyui-tooltip" title="Taille en cm">Taille en cm</label><input name="taille" class="easyui-numberbox" data-options="min:0"></div>
        <div class="fitem"><label>Actif</label>
            <select class="easyui-combobox" name="actif"  valueField="act" textField="act_t" required="required" value="oui">
                <option value="oui" selected>Oui</option>
                <option value="non">Non</option>
            </select>
        </div>

        <div class="fitem"><label>Date Consentement</label><input name="dconsentement"  onkeyup='formate_date(this)'></div>
    </form>
</div>
<div id="dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveMG()">Enregistrer</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="cancelMG();javascript:$('#dlg').dialog('close')">Annuler</a>

</div >






<div id="nir" class="easyui-dialog" style="width:600px;padding:10px 20px" closed="true" buttons="#dlg-buttons-nir">
    <div class="ftitle">Dossier NIR</div>
    <form id="fmnir" method="post" novalidate>
        <div class="ftitle">Informations</div>
        <div class="fitem"><label class="easyui-tooltip" title="Pas d'accents" readonly>Dossier</label><input name="numero" id="numero" class="easyui-validatebox" style="width:150px" readonly></div>
        <div class="fitem"><label >Numéro Asalée</label><input name="id" id="id" class="easyui-validatebox" style="width:150px" required="true" readonly></div>
        <div class="fitem"><label>Date Naissance</label><input name="dnaiss" id ="nirdnaiss" style="width:250px" onkeyup='formate_date(this)' required="required" ></div>
        <div class="fitem"><label>Sexe</label>
            <select name="sexe" id ="nirsexe" class="easyui-combobox" required="required">
                <option value="M" selected="true">Male</option>
                <option value="F">Femme</option>
            </select>
        </div>
        <div class="fitem"><label>Taille</label><input name="taille" class="easyui-numberbox" data-options="min:0"></div>
        <div class="fitem"><label>Actif</label>
            <select class="easyui-combobox" name="actif"  valueField="act" textField="act_t" required="required" value="oui">
                <option value="oui" selected>Oui</option>
                <option value="non">Non</option>
            </select>
        </div>

        <div class="fitem"><label>Date Consentement</label><input name="dconsentement" onkeyup='formate_date(this)'></div>
        <div class="ftitle">Zones NIR</div>
        <div class="fitem"><label class="easyui-tooltip" title="NIR Principal">Assuré</label><input name="nir1" id="nir1" class="easyui-validatebox" style="width:150px" maxlength="25" ></div>
        <div class="fitem"><label class="easyui-tooltip" title="NIR Secondaire">Patient Attaché</label><input name="nir2" id="nir2" type="textbox" style="width:150px" maxlength="25" ></div>
    </form>
</div>
<div id="dlg-buttons-nir">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveNIR()">Enregistrer</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="cancelMG();javascript:$('#nir').dialog('close')">Annuler</a>
</div >








<?php
//laisser là pour contourner le non affichage des piwik de ids
echo("<br />");
?>

</body>
</html>
