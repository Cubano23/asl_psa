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
    <title>Gestion des Habilitations</title>
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
    <script type="text/javascript" src="./jquery.printElement.min.js"></script>

    <script type="text/javascript">
        var operation=0; //0 = nouveau, 1 = edit
        var url;
        var handle;
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
        function utf8_encode( string )
        {
            return unescape( encodeURIComponent( string ) );
        }

        function doNew()
        {
            operation = 0;

            $('#login').prop('readonly', false);
            $('#fm').form('clear');
            //      $('#profession').val( 'Infirmiï¿½re');
            //      $('#psa').val( '0');
            //      $('#psa').numberbox('validate');
            $('#profession').combobox( 'setValue', '');
            $('#psa').combobox('setValue', '0');
            $('#psa').combobox('validate');
            $('#psae').combobox('setValue', '0');
            $('#psae').combobox('validate');
            $('#psv').combobox('setValue', '0');
            $('#psv').combobox('validate');
            //      $('#psae').val('0');
            //      $('#psae').numberbox('validate');
            //      $('#psv').val( '0');
            //      $('#psv').numberbox('validate');
            // $$$     $('#psaet').val( '0');
            //$$$      $('#psaet').numberbox('validate');
            $('#psaet').combobox('reload','habilitations/getrolelist.php');  // reload list data using new URL
            $('#psaet').combobox('setValue', '0');
            $('#psaet').combobox('validate');
            //      $('#psvae').val( '0');
            //      $('#psvae').numberbox('validate');
            $('#psvae').combobox('setValue', '0');
            $('#psvae').combobox('validate');

            $('#psar').combobox('setValue', '0');
            $('#psar').combobox('validate');

            $('#erp').combobox('setValue', '0');
            $('#erp').combobox('validate');

            $('#psamed').combobox('setValue', '0');
            $('#psamed').combobox('validate');


            $('#dlg').dialog('open').dialog('setTitle','Nouvel Utilisateur');
            url = 'habilitations/save.php';
        }
        function doCert()
        {

            var row = $('#dg').datagrid('getSelected');
            if (row){
                $('#fmcert').form('clear');
                $('#certdocert').linkbutton('enable');
                handle = $('#dlgcert').dialog('open').dialog('setTitle','G&eacute;n&eacute;rer un Certificat');
                $('#certlogin').prop('readonly', true);
                $('#certnom').prop('readonly', true);
                $('#certprenom').prop('readonly', true);
                $('#certemail').prop('readonly', true);
                $('#certphone').prop('readonly', true);

                $('#fmcert').form('load',row);
                url = 'habilitations/gencert.php';
            }
            else
            {
                alert("Choisir un Utilisateur");

            }

        }

        function doRevocCert()
        {

            var row = $('#dg').datagrid('getSelected');
            if (row){
                $('#fmrevoccert').form('clear');
                $('#certdorevoccert').linkbutton('enable');
                handle = $('#dlgrevoccert').dialog('open').dialog('setTitle','R&eacute;voquer un Certificat');
                $('#fmrevoccert').form('load',row);
                url = 'habilitations/revoccert.php';
            }
            else
            {
                alert("Choisir un Utilisateur");

            }

        }

        function doPrint()
        {

            var content =
                '<p><b>Demande de Certificat</b></p>'+
                '<br /><br /><br />'+
                '<table border>'+
                '<tr><td>Appliction</td><td>psaet</td></tr>'+
                '<tr><td>Utilisateur</td><td>'+$('#certlogin').val()+'</td></tr>'+
                '<tr><td>Nom</td><td>'+$('#certnom').val()+'</td></tr>'+
                '<tr><td>Prï¿½nom</td><td>'+$('#certprenom').val()+'</td></tr>'+
                '<tr><td>Email</td><td>'+$('#certemail').val()+'</td></tr>'+
                '<tr><td>Portable</td><td>'+$('#certphone').val()+'</td></tr>'+
                '<tr><td>Code Retrait</td><td>'+$('#certauthentifiant').val()+'</td></tr>'+
                '<tr><td>Index</td><td>'+$('#certindex').val()+'</td></tr>'+
                '</table>';

            var contents = $('#fmcert').html();
            var pri = document.getElementById("ifmcontentstoprint").contentWindow;
            pri.document.open();
            pri.document.write(content);
            pri.document.close();
            pri.focus();
            pri.print();

        }
        function doGenerateCert()
        {


            $('#fmcert').form('submit',{
                url: 'habilitations/gencert.php',
                onSubmit: function(){

                    return $(this).form('validate');
                },
                success: function(result){
                    var res = eval('('+result+')');
                    $.messager.show({
                        title: 'Retour',
                        msg: res.msg
                    });
                    if (res.success){
                        $('#certauthentifiant').val(res.otp);
                        $('#certindex').val(res.index);
                        $('#certdocert').linkbutton('disable');
                        //             $('#dlgcert').dialog('close');
                    } else {

                    }
                }
            });
        }
        function doRevocateCert()
        {


            $('#fmrevoccert').form('submit',{
                url: 'habilitations/revoccert.php',
                onSubmit: function(){

                    return $(this).form('validate');
                },
                success: function(result){

                    var res = eval('('+result+')');

                    $.messager.show({
                        title: 'Retour',
                        msg: res.msg
                    });


                    if (res.success){

                        $('#certdorevoccert').linkbutton('disable');
                    } else {



                    }
                }
            });
        }

        function doGeneratePwd()
        {


            $('#fmpwd').form('submit',{
                url: url,
                onSubmit: function()
                {
                    return $(this).form('validate');
                },
                success: function(result)
                {
                    var res = eval('('+result+')');

                    $.messager.show({
                        title: 'Retour',
                        msg: res.msg
                    });
                    //  $('#pwdhashed').val(res.hashed);
                    //   $('#pwdhashed').textbox.('setText',res.hashed);
                    if (res.success)
                    {
                        $('#pwdpwd').val(res.pwd);
                        $('#pwdhashed').val(res.hashed);
                        $('#pwdcount').val(res.count);
                        $('#pwdsalt').val(res.salt);
                        $('#pwddopwd').linkbutton('disable');
                    } else {

                    }
                }
            });
        }

        function doPwd()
        {

            var row = $('#dg').datagrid('getSelected');
            if (row){
                $('#fmpwd').form('clear');
                $('#pwddopwd').linkbutton('enable');
                handle = $('#dlgpwd').dialog('open').dialog('setTitle','Mot de Passe Certificat');
                $('#pwdlogin').prop('readonly', true);
                $('#pwdnom').prop('readonly', true);
                $('#pwdprenom').prop('readonly', true);
                $('#pwdemail').prop('readonly', true);
                $('#pwdphone').prop('readonly', true);

                $('#fmpwd').form('load',row);
                url = 'habilitations/genpwd.php';
            }
            else
            {
                alert("Choisir un Utilisateur");
            }

        }


        function doEdit()
        {
            operation = 1;
            var row = $('#dg').datagrid('getSelected');
            
            if (row){
                $('#dlg').dialog('open').dialog('setTitle','Modifier Utilisateur');
                $('#login').prop('readonly', true);
                $('#fm').form('load',row);
                url = 'habilitations/update.php';
            }
            else
            {
                alert("Choisir un Utilisateur");

            }

        }

        function doCancel()
        {


        }
        function doSave()
        {

            document.getElementById("error_tel").innerText = "";
            document.getElementById("error_adeli").innerText = "";
            let telephone = $("#telephone").val();            
            let adeli = $("#adeli").val();


             if((telephone.length != 14 && telephone.length != 0) && (telephone.length != 10 && telephone.length != 0)){ 
                document.getElementById("error_tel").innerText = 'Veuillez saisir un numéro de téléphone valide!';
                return;             
            }else if(adeli.length != 9 && adeli.length != 0){ 
                document.getElementById("error_adeli").innerText = 'Veuillez saisir un adeli valide!';
                return;            
            }else{
                console.log("OK!");
          



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

        function doRemove()
        {
            var row = $('#dg').datagrid('getSelected');
            if (row)
            {
                $.messager.confirm('Confirm','Etes vous s&ucirc;rs d\'effacer l\'Habilitation?',function(r)
                {
                    if (r)
                    {
                        $.post('habilitations/remove.php',{login:row.login},function(result)
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
            {
                alert("Choisir un Utilisateur");

            }
        }
        function doAllrequests( operation)
        {
            var row = $('#dg').datagrid('getSelected');
            var theurl ='cert/getdata.php';

            if((row) && (operation==0))
            {
                //        theurl = theurl + '?login='+ row.login;
                $('#tblcerts').datagrid({queryParams: {login:row.login}});
            }
            else
                $('#tblcerts').datagrid({queryParams: {login:''}});
            $('#dlgcerts').dialog('open').dialog('setTitle','Visualiser');

        }


        function doSearch(){
            $('#dg').datagrid('load',{
                nsearch: $('#nsearch').val(),
                lsearch: $('#lsearch').val(),
                hsearch: $('#hsearch').combobox('getValue')

            });
        }


        function doReset(){
            $('#nsearch').val('');
            $('#lsearch').val('');
            $('#hsearch').combobox('setValue','0');
            doSearch();
        }

        function doExport()
        {
            $("*").css("cursor", "progress");
            $.post('habilitations/exportdata.php',function(result)
            {
                if (result.success)
                {

                } else
                {

                    // Construct the <a> element
                    var link = document.createElement("a");
                    link.download = 'habilitations/habexport.csv';
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



        function menuHandler(item){
            alert(item.name)
        }



        function str_to_noaccent (x ){

            x = x.toLowerCase();
            var accent = [
                /[\300-\306]/g, /[\340-\346]/g, // A, a
                /[\310-\313]/g, /[\350-\353]/g, // E, e
                /[\314-\317]/g, /[\354-\357]/g, // I, i
                /[\322-\330]/g, /[\362-\370]/g, // O, o
                /[\331-\334]/g, /[\371-\374]/g, // U, u
                /[\321]/g, /[\361]/g, // N, n
                /[\307]/g, /[\347]/g, // C, c
            ];
            var noaccent = ['A','a','E','e','I','i','O','o','U','u','N','n','C','c'];

            var str = x;
            for(var i = 0; i < accent.length; i++){
                str = str.replace(accent[i], noaccent[i]);
            }

            str = str.replace(" ","");
            str = str.replace("'","");

            return str;
        }



        function setFields()
        {
            if (operation==0)
            {
                var p = $('#prenom').val();
                var n = $('#nom').val();
                var res = p.substring(0,1);
                if(p.includes(" "))  // nom composï¿½
                {
                    var a = p.split(" ");
                    if(a.length==2)
                        res = res+ a[1].substring(0,1);
                }
                if(p.includes("-") ) // nom composï¿½
                {
                    var a = p.split("-");
                    if(a.length==2)
                        res = res+ a[1].substring(0,1);
                }


                var x =  res + n;
                x = str_to_noaccent (x );
                $('#login').text('setValue', x );
                $('#login').val( x );
                $('#email').val( x+'@asalee.fr' );
            }

        }

    </script>

</head>
<body bgcolor=#FFE887>
<?php




require("../global/entete.php");
entete_asalee("Gestion des Habilitations");



?>


<br />
<br />
<br />


<table id="dg" class="easyui-datagrid" style="width:1800px"
       url="habilitations/getdata.php"
       title="Gestion des Habilitations" toolbar="#toolbar"
       pagination="true" pageSize="20"
       rownumbers="true"
       singleSelect="true" fitColumns="true"
       nowrap="false" >
    <thead  frozen="true">
    <tr>
        <th field="ck" checkbox="true"></th>
        <th field="nom" width="200"  sortable="true">Nom</th>
        <th field="prenom"  sortable="true">Pr&eacute;nom</th>
        <th field="login"  sortable="true" >Login</th>
        <th field="hpassword"   >Pwd Hash&eacute;</th>
        <!--        <th field="pass"   >Mot de Passe</th> -->

    </tr>
    <thead><tr>
        <th field="telephone"  >T&eacute;l&eacute;phone</th>
        <th field="email"  >Courriel</th>
        <th field="profession" >Profession</th>
        <th field="adeli" >ADELI</th>
        <th field="type" >Type</th>
        <th field="psa"  >psa</th>
        <th field="psae"  >psae</th>
        <th field="psv"  >psv</th>
        <th field="psaet"  >psaet</th>
        <th field="psvae"  >psvae</th>
        <th field="psar"  >psar</th>

        <th field="erp"  >erp</th>
        <th field="psamed"  >psamed</th>
        <th field="idcps"  >ID CPS</th>
        <th field="dmaj"  sortable="true" >Date Identifications</th>
        <th field="dmaj2"  sortable="true" >Date Habilitations</th>
        <th field="cabinets"  sortable="true" >Cabinets</th>
        
    </tr>
    </thead>


    </thead>

</table>
<div id="toolbar" style="padding:5px;height:auto">
    <div style="margin-bottom:5px">
        <a href="#" class="easyui-linkbutton" iconCls="icon-add"   onclick="doNew()">Nouveau</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-edit"   onclick="doEdit()">Editer</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-remove"  onclick="doRemove()">Effacer</a>
        <span class="button-sep"></span>
        <a href="#" class="easyui-linkbutton" iconCls="icon-cert"   onclick="doCert()">Certificat</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-view"   onclick="doAllrequests(0)">Requ&eacute;tes Individuelles</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-view"   onclick="doAllrequests(1)">Requ&eacute;tes Globales</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-cert"   onclick="doRevocCert()">R&eacute;
        voquer Certificat</a>
        <span class="button-sep"></span>
        <a href="#" class="easyui-linkbutton" iconCls="icon-pwd"   onclick="doPwd()">Mot de Passe</a>
        <span class="button-sep"></span>
        <a href="#" class="easyui-linkbutton" iconCls="icon-export"   onclick="doExport()">Exporter</a>
        <!--      <a href="#" class="easyui-linkbutton" iconCls="icon-import"  plain="true" onclick="doImport()">Importer</a>  -->


    </div>

    <div>
        <table>
            <tr>
                <td><span>Nom:</span>
                    <input id="nsearch" style="line-height:20px;border:1px solid #ccc">

                    <span>Login:</span>
                    <input id="lsearch" style="line-height:20px;border:1px solid #ccc">

                    <span>Pwd Hash&eacute;?:</span>
                    <select id="hsearch" class="easyui-combobox" name="hsearch" style="width:100px;">
                        <option value="0">Tous</option>
                        <option value="1">Oui</option>
                        <option value="2">Non</option>
                    </select>
                    <a href="#" class="easyui-linkbutton" onclick="doSearch()" iconCls="icon-search">Rechercher</a>
                    <a href="#" class="easyui-linkbutton" onclick="doReset()" iconCls="icon-reload">Reinitialiser</a></td>
            </tr>
        </table>
    </div>


</div>

<div id="hba1c" class="easyui-dialog" style="width:1300px;padding:10px 20px"
     closed="true" buttons="#dlg-buttons">
    <div class="ftitle">Alertes </div>

</div>

<div id="dlg" class="easyui-dialog" style="width:600px;padding:10px 20px"
     closed="true" buttons="#dlg-buttons">
    <form id="fm" method="post" novalidate>
        <div class="ftitle">Informations</div>
        <div class="fitem"><label>Pr&eacute;nom </label><input name ="prenom" id ="prenom" class="easyui-validatebox" style="width:150px" required="true" onkeyup="setFields()"></div>
        <div class="fitem"><label >Nom</label><input name="nom" id ="nom" class="easyui-validatebox" style="width:150px" required="true" onkeyup="setFields()" ></div>
        <div class="fitem"><label class="easyui-tooltip" title="Pas d'accents">Login</label><input id = "login" type="text" name="login" class="easyui-validatebox" style="width:150px" required="true"></div>
        <!--			<div class="fitem"><label>Mot de Passe </label><input name ="pass" id ="pass" class="easyui-validatebox" style="width:150px" required="true"></div> -->

        <div class="fitem"><label>Courriel</label><input name="email" id ="email" style="width:250px" class="easyui-validatebox" validType="email" required="true"></div>
        <span id="error_tel" style="color: red"></span><br />
        <div class="fitem"><label>T&eacute;l&eacute;phone</label><input id="telephone" name="telephone" id ="telephone" class="easyui-validatebox" required="true"></div>
        <div class="fitem"><label>Cabinets</label><input name="cabinets" id ="cabinets" class="easyui-validatebox" required="false" disabled ></div>
        <div class="fitem"><label>Profession</label><select class="easyui-combobox" name ="profession" id="profession" style="width:150px" >
                <option>Infirmi&egrave;re</option>
                <option>Infirmier</option>
                <option>Ing&eacute;nieur</option>
                <option>Ing&eacute;nieur support</option>
                <option>M&eacute;decin</option>
                <option>Prestataire</option>
                <option>gestionnaire</option>

            </select>
        </div>
        <span id="error_adeli" style="color: red"></span><br />
        <div class="fitem"><label>ADELI</label><input id="adeli" name="adeli" class="easyui-validatebox" ></div>
        <div class="fitem"><label>Type</label><input class="easyui-combobox" name ="type" id="type" style="width:150px" valueField="type" textField="type_t"  url="habilitations/gettypeslist.php"></div>
        <div class="ftitle">Habilitations</div>
        <table>
            <tr>
                <td style="padding-right:45px" > <label class="easyui-tooltip" title="SuperAdmin: Toutes opï¿½rations">psa</label>      </td>
                <td  ><input class="easyui-combobox"  name="psa"  id="psa"  valueField="applevel" textField="applevel_t" required="true" url="habilitations/getapplevelslist.php" ></td>
            </tr>
            <tr>
                <td style="padding-right:45px"><label class="easyui-tooltip" title="0:non, 1:permis">psae</label></td>
                <td>
                    <input class="easyui-combobox"  name="psae"  id="psae"  valueField="applevel" textField="applevel_t" required="true" url="habilitations/getapplevelslist.php" >
                </td>
            </tr>
            <tr>
                <td style="padding-right:45px"> <label class="easyui-tooltip" title="0:non, 1:permis">psv</label></td>
                <td>
                    <input class="easyui-combobox"  name="psv"  id="psv"  valueField="applevel" textField="applevel_t" required="true" url="habilitations/getapplevelslist.php" >
                </td>
            </tr>
            <tr>
                <td style="padding-right:45px"> <label class="easyui-tooltip" title="0:non, 1:permis">psar</label></td>
                <td>
                    <input class="easyui-combobox"  name="psar"  id="psar"  valueField="applevel" textField="applevel_t" required="true" url="habilitations/getapplevelslist.php" >
                </td>
            </tr>


            <tr>
                <td style="padding-right:45px"> <label class="easyui-tooltip" title="0:non, 1:permis">erp</label></td>
                <td>
                    <input class="easyui-combobox"  name="erp"  id="erp"  valueField="applevel" textField="applevel_t" required="true" url="habilitations/getapplevelslist.php" >
                </td>
            </tr>

            <tr>
                <td style="padding-right:45px"> <label class="easyui-tooltip" title="0:non, 1:permis">psamed</label></td>
                <td>
                    <input class="easyui-combobox"  name="psamed"  id="psamed"  valueField="applevel" textField="applevel_t" required="true" url="habilitations/getapplevelslist.php" >
                </td>
            </tr>

        </table>
        <!--				<div class="fitem"><label class="easyui-tooltip" title="0:non, 1:permis">psa</label><input name="psa" id ="psa"  class="easyui-numberbox" data-options="min:0,max:1" required="true"></div>
                               <div class="fitem"><label class="easyui-tooltip" title="0:non, 1:permis">psae</label><input name="psae" id ="psae" class="easyui-numberbox" data-options="min:0,max:1" required="true"></div>
                              <div class="fitem"><label class="easyui-tooltip" title="0:non, 1:permis">psv</label><input name="psv" id ="psv" class="easyui-numberbox" data-options="min:0,max:1" required="true"></div>        -->
        <div class="ftitle">Administrations</div>
        <!--			<div class="fitem"><label class="easyui-tooltip" title="0:non, 1:permis">psaet</label><input name="psaet" id ="psaet" class="easyui-numberbox" data-options="min:0,max:3" required="true"></div> -->

        <table >

            <tr>
                <td style="padding-right:45px" > <label class="easyui-tooltip" title="SuperAdmin: Toutes opï¿½rations">psaet</label>      </td>
                <td  ><input class="easyui-combobox"  name="psaet"  id="psaet"  valueField="role" textField="role_t" required="true" url="habilitations/getroleslist.php" ></td>
            </tr>
            <tr>

                <td><label class="easyui-tooltip" title="0:non, 1:permis">psvae</label></td>
                <td>

                    <!--              <input name="psvae" id ="psvae" class="easyui-numberbox" data-options="min:0,max:1" required="true">  -->
                    <input class="easyui-combobox"  name="psvae"  id="psvae"  valueField="applevel" textField="applevel_t" required="true" url="habilitations/getapplevelslist.php" >
                </td>
            </tr>

        </table>


        <div class="ftitle">Autres</div>
        <div class="fitem"><label >ID CPS</label><input name="idcps" id ="idcps" class="easyui-validatebox" ></div>


    </form>
</div>
<div id="dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="doSave()">Enregistrer</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="doCancel();javascript:$('#dlg').dialog('close')">Annuler</a>
</div>



<div id="dlgcert" class="easyui-dialog" style="width:600px;padding:10px 20px"
     closed="true" buttons="#dlg-buttons2">
    <form id="fmcert" method="post" novalidate>
        <div class="ftitle">Informations</div>
        <div class="fitem"><label >Utilisateur Certificat</label><input name="login" id = "certlogin"  class="easyui-textbox" style="width:150px" ></div>
        <div class="fitem"><label>Pr&eacute;lnom </label><input name ="prenom" id ="certprenom" class="easyui-textbox" style="width:150px" ></div>
        <div class="fitem"><label >Nom</label><input name="nom" id ="certnom" class="easyui-textbox" style="width:150px" ></div>
        <div class="fitem"><label>Courriel</label><input name="email" id ="certemail" style="width:250px" class="easyui-textbox"></div>
        <div class="fitem"><label>Portable</label><input name="telephone" id ="certphone" style="width:250px" class="easyui-textbox"></div>
        <div class="ftitle">Options</div>
        <div class="fitem"><label>Courriel/SMS</label><input name="certsendemail" id ="certsendemail"   type="checkbox" value="1"></div>
        <div class="fitem"><label>Test</label><input name="certtest" id ="certtest"   type="checkbox" value="1"></div>

        <div class="ftitle">R&eacute;sultats</div>
        <div class="fitem"><label>Code Retrait</label><input name="authentifiant" id ="certauthentifiant" style="width:250px" class="easyui-textbox"></div>
        <div class="fitem"><label>Index</label><input name="index" id ="certindex" style="width:250px"  class="easyui-textbox"></div>

    </form>
</div>


<div id="dlg-buttons2">
    <a href="#" class="easyui-linkbutton" id="certdocert" iconCls="icon-cert" onclick="doGenerateCert()">G&eacute;n&eacute;rer le Certificat</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-print" onclick="doPrint();">Imprimer</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="$('#dlgcert').dialog('close')">Sortir</a>
</div>






<div id="dlgrevoccert" class="easyui-dialog" style="width:600px;padding:10px 20px"
     closed="true" buttons="#dlg-revocbuttons2">
    <form id="fmrevoccert" method="post" novalidate>
        <div class="ftitle">Informations</div>
        <div class="fitem"><label >Utilisateur Certificat</label><input name="login" id = "certlogin"  class="easyui-textbox" style="width:150px" readonly="true"></div>
        <div class="fitem"><label>Pr&eacute;nom </label><input name ="prenom" id ="certprenom" class="easyui-textbox" style="width:150px" readonly="true"></div>
        <div class="fitem"><label >Nom</label><input name="nom" id ="certnom" class="easyui-textbox" style="width:150px" readonly="true"></div>
        <div class="fitem"><label>Index</label><input name="revocindex" id ="revoccertindex" style="width:250px"  class="easyui-textbox" ></div>
        <div class="fitem"><label>Test</label><input name="revoccerttest" id ="revoccerttest"   type="checkbox" value="1"></div>
        <div class="fitem"><label>Commentaire</label><input name="revoccomment" id ="revoccomment" style="width:250px"  class="easyui-textbox" ></div>
    </form>
</div>
<div id="dlg-revocbuttons2">
    <a href="#" class="easyui-linkbutton" id="certdorevoccert" iconCls="icon-cert" onclick="doRevocateCert()">R&eacute;voquer Certificat</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="$('#dlgrevoccert').dialog('close')">Sortir</a>
</div>
<div id="dlgpwd" class="easyui-dialog" style="width:600px;padding:10px 20px"
     closed="true" buttons="#dlg-buttons3">
    <form id="fmpwd" method="post" novalidate>
        <div class="ftitle">Informations</div>
        <div class="fitem"><label >Utilisateur Certificat</label><input name="login" id = "pwdlogin"  class="easyui-textbox" style="width:150px" ></div>
        <div class="fitem"><label>Pr&eacute;nom </label><input name ="prenom" id ="pwdprenom" class="easyui-textbox" style="width:150px" ></div>
        <div class="fitem"><label >Nom</label><input name="nom" id ="pwdnom" class="easyui-textbox" style="width:150px" ></div>
        <div class="fitem"><label>Courriel</label><input name="email" id ="pwdemail" style="width:250px" class="easyui-textbox"></div>
        <div class="fitem"><label>Portable</label><input name="telephone" id ="pwdphone" style="width:250px" class="easyui-textbox"></div>
        <div class="ftitle">Options</div>
        <div class="fitem"><label>SMS</label><input name="pwdsendsms" id ="pwdsendsms" type="checkbox" value="1"></div>
        <div class="fitem"><label>Test</label><input name="pwdtest" id ="pwdtest"   type="checkbox" value="1"></div>

        <div class="ftitle">R&eacute;sultat</div>
        <div class="fitem"><label>Mot de Passe</label><input name="motdepasse" id ="pwdpwd" style="width:250px" class="easyui-textbox"></div>
        <div class="fitem"><label>Count</label><input name="pwdcount" id ="pwdcount" style="width:250px" class="easyui-textbox"></div>
        <div class="fitem"><label>Salt</label><input name="pwdsalt" id ="pwdsalt" style="width:250px" class="easyui-textbox"></div>
        <div class="fitem"><label>Hash</label><input name="pwdhashed" id ="pwdhashed" style="width:250px" class="easyui-textbox"></div>



    </form>
</div>
<div id="dlg-buttons3">
    <a href="#" class="easyui-linkbutton" id="pwddopwd" iconCls="icon-pwd" onclick="doGeneratePwd()">G&eacute;n&eacute;rer Mot de Passe</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="$('#dlgpwd').dialog('close')">Sortir</a>
</div>


<div id="dlgcerts" class="easyui-dialog" style="width:1200px;padding:10px 20px"
     closed="true" buttons="#dlg-buttons3">
    <table id="tblcerts" class="easyui-datagrid" style="width:1000px"
           title="Requ&eacute;tes de Certificats"
           url="cert/getdata.php"
           pagination="true" pageSize="20"
           rownumbers="true"
           singleSelect="true" fitColumns="true"
           nowrap="false" >
        <thead  >
        <tr>
            <th field="dmaj" width="50" sortable="true">Date de Cr&eacute;ation</th>
            <th field="owner" width="50" sortable="true">Propri&eacute;taire Certificat</th>
            <th field="organisation"  >Organisation</th>
            <th field="token"  >Token</th>
            <th field="lot" >Lot</th>
        </tr></thead>
    </table>
</div>
<div id="dlg-buttons3">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="doCancel();javascript:$('#dlgcerts').dialog('close')">Sortir</a>
</div>



<iframe id="ifmcontentstoprint" style="height: 0px; width: 0px; position: absolute"></iframe>

<?php
//laisser lï¿½ pour contourner le non affichage des piwik de ids
echo("<br />");
?>

</body>
</html>
