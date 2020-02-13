<?php
require_once('filterdomain.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="content-type"
          content="text/html; charset=ISO-8859-15">
    <title>Statistiques NIR</title>


    <link rel="stylesheet" type="text/css" href="../../jquery/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="../../jquery/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="../../jquery/demo/demo.css">


    <script type="text/javascript" src="../../jquery/jquery.min.js"></script>
    <script type="text/javascript" src="../../jquery/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../../jquery/locale/easyui-lang-fr.js"></script>

    <script  type="text/javascript">
        function doSearch(){
            $('#dg').datagrid('load',{
//    timesearch: $('#timesearch').datebox('getValue'),
                csearch: $('#csearch').val()
            });
        }


        function doReset(){
            $('#csearch').val('');
            doSearch();
        }


        function doExportStats()
        {
            $("*").css("cursor", "progress");
            $.post('nir/exportstats.php',function(result)
            {
                if (result.success)
                {

                } else
                {

                    // Construct the <a> element
                    var link = document.createElement("a");
                    link.download = 'nir/statsnirexport.csv';
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

        function doExportTotalStats()
        {
            $("*").css("cursor", "progress");
            $.post('nir/exporttotalstats.php',
                {
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
                        link.download = 'nir/totalstats.csv';
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

        function doExportNirs()
        {

            $.messager.confirm('Confirm','Le fichier des NIRs va être exporté. Veuillez Confirmer',function(r)
            {
                if (r)
                {
                    $("*").css("cursor", "progress");
                    $.post('nir/exportnir.php',
                        {
                            osearch: $('#osearch').combobox('getValue'),
                        },



                        function(result)
                        {
                            if (result.success)
                            {
                            }
                            else
                            {
                                /*           alert("Enregistrements NIR Exportés:"+result.msg);
                                                                $.messager.show({	// show error message
                                                                    title: 'NIRs',
                                                                    msg: "Enregistrements NIR Exportés:"+result.msg
                                                                });*/

                                // Construct the <a> element
                                var link = document.createElement("a");
                                link.download = 'nir/asaleenirs.asc';
                                // Construct the uri
                                var uri = link.download ;
                                link.href = uri;
                                document.body.appendChild(link);
                                link.click();
                                // Cleanup the DOM
                                document.body.removeChild(link);
                                delete link;
                            }

                        },'json');
                    $("*").css("cursor", "default");
                }
            });
        }


    </script>

</head>
<body bgcolor=#FFE887>
<?php

require_once("../global/entete.php");
entete_asalee("Statistiques NIR");


?>


<br />
<br />
<br />


<table id="dgtotal" class="easyui-datagrid" style="width:900px"
       url="nir/statstotal.php"
       title="Statistiques Totaux NIR" toolbar="#toolbartotal"
       singleSelect="true" fitColumns="true"
       nowrap="false" >
    <thead>	<tr>
        <th field="encnirs" width="100"  >Nirs Saisis</th>
        <th field="dconsent" width="100"  >Avec Consentement</th>
        <th field="dossiers" width="100" >Dossiers Totaux</th>
    </tr></thead>
</table>
<div id="toolbartotal" style="padding:5px;height:auto">
    <div>
        <table>
            <tr>
                <td><span >NIR saisi?:</span></td>
                <td> <select id="nsearch" class="easyui-combobox" name="nsearch" style="width:100px;">
                        <option value="1">Oui</option>
                        <option value="2">Non</option>
                        <option value="0">Tous</option>
                    </select>
                </td>
                <td><span >Date Consentement?:</span></td>
                <td><select id="dsearch" class="easyui-combobox" name="dsearch" style="width:100px;">
                        <option value="1">Oui</option>
                        <option value="0">Tous</option>
                        <option value="2">Non</option>
                    </select>
                </td>
                <td></td>
                <td><span class="button-sep"></span></td>
                <td><a href="#" class="easyui-linkbutton" iconCls="icon-export"   onclick="doExportTotalStats()">Exporter</a></td>
            </tr>
            <tr>
                <td><span >Options Cnam:</span></td>
                <td><select id="osearch" class="easyui-combobox" name="osearch" style="width:100px;">
                        <option value="0">Tous</option>
                        <option value="1">100</option>
                        <option value="2">ztest</option>
                        <option value="5">ztest tous</option>
                    </select>
                </td>
                <td><a href="#" class="easyui-linkbutton" iconCls="icon-save"   onclick="doExportNirs()">NIR Cnam</a></td>
            </tr>
        </table>
    </div>


</div>




<br />
<br />


<table id="dg" class="easyui-datagrid" style="width:900px"
       url="nir/stats.php"
       title="Statistiques NIR par cabinet" toolbar="#toolbar"
       pagination="true" pageSize="30" pageList="[10,20,30,50,100,200]"
       rownumbers="true"
       singleSelect="true" fitColumns="true"
       nowrap="false" >
    <thead>	<tr>
        <th field="ck" checkbox="true"></th>
        <th field="cabinet" width="100" sortable="true" >Cabinet</th>
        <th field="encnirs" width="100"  >Nirs Saisis</th>
        <th field="dconsent" width="100"  >Avec Consentement</th>
        <th field="dossiers" width="100" >Dossiers Totaux</th>
    </tr></thead>
</table>

<div id="toolbar" style="padding:5px;height:auto">
    <div>
        <table>
            <tr>
                <td><span >Cabinet:</span></td>
                <td><input id="csearch" style="line-height:20px;border:1px solid #ccc"></td>
                <!--            <td><span >NIR saisi?:</span></td>
                            <td> <select id="nsearch" class="easyui-combobox" name="nsearch" style="width:100px;">
                                             <option value="0">Tous</option>
                                             <option value="1">Oui</option>
                                             <option value="2">Non</option>
                                  </select>
                            </td>
                             <td><span >Patient Consulté?:</span></td>
                           <td><select id="psearch" class="easyui-combobox" name="psearch" style="width:100px;">
                                             <option value="0">Tous</option>
                                             <option value="1">Oui</option>
                                </select>
                            </td>  -->
                <td></td>
                <td><a href="#" class="easyui-linkbutton" onclick="doSearch()" iconCls="icon-search">Rechercher</a></td>
                <td><a href="#" class="easyui-linkbutton" onclick="doReset()" iconCls="icon-reload">Reinitialiser</a></td>
                <td><span class="button-sep"></span></td>
                <td><a href="#" class="easyui-linkbutton" iconCls="icon-export"   onclick="doExportStats()">Exporter</a></td>

            </tr>
        </table>
    </div>


</div>
<br />
<br />







<?php
//laisser là pour contourner le non affichage des piwik de ids
echo("<br />");
?>

</body>
</html>
