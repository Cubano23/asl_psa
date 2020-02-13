<?php

require_once ("Config.php");
$config = new Config();

session_start();

if(!isset($_SESSION['nom']))
{
    # pas pass� par l'identification
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
    <title>Trace des Int�grations</title>
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
        function utf8_encode( string )
        {
            return unescape( encodeURIComponent( string ) );
        }
        function doSearch()
        {
            var x = $('#cabsearch').combobox('getValue');

            $('#dg').datagrid('load',{
                cabsearch: x
            });
        }
        function formatFileLink(val,row){
            var url = "./integration_logs/";
            return '<a href="'+url + val+'">'+val+'</a>';
        }


        function alertesHba1c()
        {
            var row = $('#dg').datagrid('getSelected');
            if (row){
//				$('#hba1c').dialog('open').dialog('setTitle','Alertes HBA1C');
//				$('#fm').form('load',row);


                url = 'integration_alertes_hba1c.php?cabinet='+row.cabinet+'&report='+row.reportfile+'&dintegration='+row.dintegration;
                if($('#allvals').is(':checked')  )
                    url = url +'&allvals=1';
                else
                    url = url +'&allvals=0';
                window.open(url);
            }
            else
            {
                alert("Veuillez choisir une ligne");


            }
        }
        function alertesHba1c_u()
        {
            var row = $('#dg').datagrid('getSelected');
            if (row){
//				$('#hba1c').dialog('open').dialog('setTitle','Alertes HBA1C');
//				$('#fm').form('load',row);


                url = 'integration_alertes_hba1c_u.php?cabinet='+row.cabinet+'&report='+row.reportfile+'&dintegration='+row.dintegration;
                if($('#allvals').is(':checked')  )
                    url = url +'&allvals=1';
                else
                    url = url +'&allvals=0';
                window.open(url);
            }
            else
            {
                alert("Veuillez choisir une ligne");


            }
        }
        function alertesGlycemie()
        {
            var row = $('#dg').datagrid('getSelected');
            if (row){

                url = 'integration_alertes_glycemie.php?cabinet='+row.cabinet+'&dintegration='+row.dintegration;
                if($('#allvals').is(':checked')  )
                    url = url +'&allvals=1';
                else
                    url = url +'&allvals=0';
                window.open(url);
            }
            else
            {
                alert("Veuillez choisir une ligne");


            }
        }
        function alertesGlycemie_u()
        {
            var row = $('#dg').datagrid('getSelected');
            if (row){

                url = 'integration_alertes_glycemie_u.php?cabinet='+row.cabinet+'&dintegration='+row.dintegration+'&report='+row.reportfile;
                if($('#allvals').is(':checked')  )
                    url = url +'&allvals=1';
                else
                    url = url +'&allvals=0';
                window.open(url);
            }
            else
            {
                alert("Veuillez choisir une ligne");


            }
        }

        function alertesLdl()
        {
            var row = $('#dg').datagrid('getSelected');
            if (row){

                url = 'integration_alertes_ldl.php?cabinet='+row.cabinet+'&dintegration='+row.dintegration;
                if($('#allvals').is(':checked')  )
                    url = url +'&allvals=1';
                else
                    url = url +'&allvals=0';
                window.open(url);
            }
            else
            {
                alert("Veuillez choisir une ligne");


            }
        }
        function alertesLdl_u()
        {
            var row = $('#dg').datagrid('getSelected');
            if (row){

                url = 'integration_alertes_ldl_u.php?cabinet='+row.cabinet+'&dintegration='+row.dintegration+'&report='+row.reportfile;
                if($('#allvals').is(':checked')  )
                    url = url +'&allvals=1';
                else
                    url = url +'&allvals=0';
                window.open(url);
            }
            else
            {
                alert("Veuillez choisir une ligne");


            }
        }
        function menuHandler(item){
            alert(item.name)
        }
    </script>

</head>
<body bgcolor=#FFE887>
<?php

/*$base=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";*/
require("../global/entete.php");
entete_asalee("Trace des Int�grations");

//require("./integration/importlogs.php");


/*$base=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
require("$base/inclus/accesbase.inc.php");*/
require($config->inclus_path ."/accesbase.inc.php");

# connexion aux donn�es
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD:". mysql_error());
mysql_select_db($DB) or
die("Impossible de se connecter � la base");


$table = "integration";

$base= $config->app_path;
$dirname = $base . $config->psa_path . "/view/integration/log";
$cpt= "Compte-rendu integration ";

//$dir = opendir($dirname);
$ext=array(".xls", ".zip");


$pattern =      $dirname."/".$cpt."*";
$files = glob($pattern,GLOB_NOSORT );

// echo ($files);                           <?php
foreach ($files as $filename) {
    /*  while($file = readdir($dir)) {

      $pos = strpos($file, $cpt);
        if($pos!==false)
        {*/

    /*
    //       Anonymisation des noms des fichiers. Laisser ne pas effacer. Elie Aouad
             $filename2 = $filename;
             $file =  basename($filename2);
             $tokens2  = explode(    ".", $file );
             if($tokens2[1]=="xls")
             {

                $fname = $tokens2[0];
                $inputKey = pack("H*","E49F211F72FDA17B3420DEADEA99ADF5");
                $f = hash_hmac ( "md5" , $fname, $inputKey );
                $file2 =  $fname.".".$f;
                $filename2=  $dirname."/".$file2.".xls";
                 if(count($tokens2)>2)
                      $filename2 = $filename2.".zip";
      //           rename ($filename, $filename2);
                 $filename = $filename2;
             }
      */
    // contournement bug accent de chiz�
    $fich2 = str_replace("�", "e", $filename);
    if($fich2!=$filename)
    {
        rename ( $filename ,$fich2);
        $filename = $fich2;
    }
    //===========================> E.A. 07-02-2014

    $file =  basename($filename);
    $sstr = substr($file, strlen($cpt),strlen($file) - strlen($cpt) );
    $sstr= str_replace($ext, "", $sstr);
    $tokens= explode(    " ", $sstr );

    $cabinet= $tokens[0];
    $dintegration=$tokens[1];
    $reportfile= $file;


    $sql = "select logiciel from account where cabinet='$cabinet'";
    $rs = mysql_query( $sql);
    list($row) = mysql_fetch_row($rs);
    $logiciel=$row;
    date_default_timezone_set('Europe/Berlin');
    $dt =   date ("H:i:s", filemtime($filename));

    $tokens  = explode(    "-", $dintegration );
//         $dintegration = $tokens[2]."-".$tokens[1]."-".$tokens[0];

    $dintegration =   date ("Y-m-d", filemtime($filename));
//         $reportfile = '<a href="integration_logs/'.$reportfile.'" target="_blank">'.$reportfile.'</a>'; 
    $sql2="INSERT  INTO integration(cabinet, logiciel, dintegration, entryfile, reportfile, cr, tintegration, hintegration)".
        " VALUES ('$cabinet','$logiciel','$dintegration','Importedrow','$reportfile',0,0,'$dt')";
    $rs = mysql_query($sql2);
}

//}


//closedir($dir);


?>


<br />
<br />
<br />

<div class="easyui-panel" style="padding:5px;">
    <a href="#" class="easyui-menubutton" data-options="menu:'#mm1'">HBA1C</a>
    <a href="#" class="easyui-menubutton" data-options="menu:'#mm2'">Glyc�mie</a>
    <a href="#" class="easyui-menubutton" data-options="menu:'#mm3'">LDL</a>
    <a href="#" class="easyui-menubutton" data-options="menu:'#mm10'">A Propos</a>
    <input type="checkbox" name="allvals" id="allvals" > Toutes Valeurs  </input>
</div>
<div id="mm1" style="width:150px;">
    <div onclick="alertesHba1c()">Int�gr�s</div>
    <div onclick="alertesHba1c_u()">Inconnus Asal�e</div>
</div>
<div id="mm2" style="width:150px;">
    <div onclick="alertesGlycemie()">Int�gr�s</div>
    <div onclick="alertesGlycemie_u()">Inconnus Asal�e</div>
</div>
<div id="mm3" style="width:150px;">
    <div onclick="alertesLdl()">Int�gr�s</div>
    <div onclick="alertesLdl_u()">Inconnus Asal�e</div>
</div>
<div id="mm10" class="menu-content" style="background:#f0f0f0;padding:10px;text-align:left">
    <p style="font-size:14px;color:#444;">Traces Int�grations Asal�e.</p>
</div>

<br />

<table id="dg" class="easyui-datagrid" style="width:1500px"
       url="integration/getdata.php"
       title="Trace des Int�grations" toolbar="#toolbar"
       pagination="true" pageSize="50"
       rownumbers="true"
       singleSelect="true" fitColumns="true"
       nowrap="false" >
    <thead  frozen="true">
    <tr>
        <th field="ck" checkbox="true"></th>
        <th field="dintegration"  sortable="true" >Date Int�gration</th>
        <th field="hintegration"   >Fin Int�gration</th>
        <th field="cabinet" width="100" sortable="true">User Cabinet</th>
        <th field="reportfile" width="750" formatter="formatFileLink" >Fichier Compte Rendu</th>
    </tr>
    <thead><tr>
        <th field="entryfile" >Fichier</th>
        <th field="logiciel" sortable="true" >logiciel</th>
        <th field="cr" >Code Retour</th>
        <th field="tintegration" >Dur�e Int�gration</th>
    </tr>
    </thead>







    </thead>


</table>
<div id="toolbar" style="padding:5px;height:auto">
    <div style="margin-bottom:5px">
        <span>Cabinet:</span> 	<input name="cabsearch"  id="cabsearch" class="easyui-combobox" style="width:200px"
                                        url="cabinets_getlist.php"
                                        valueField="cab" textField="text">
        <a href="#" class="easyui-linkbutton" iconCls="icon-search"  plain="true" onclick="doSearch()">Recherche</a>

    </div>
</div>

<div id="hba1c" class="easyui-dialog" style="width:1300px;padding:10px 20px"
     closed="true" buttons="#dlg-buttons">
    <div class="ftitle">Alertes </div>

</div>
<?php
require_once ("Config.php");
$config = new Config();

if($_SERVER['APPLICATION_ENV'] != 'dev-herve')
{
    require_once($config->webservice_path . "/AsaleeLog.php");
    LogAccess("", "intergartion_logs.php", $UserIDLog, 'na', "All", 0, "Liste Trace Integrations:".$answerLog);
}
?>


<?php
//laisser l� pour contourner le non affichage des piwik de ids
echo("<br />");
?>

</body>
</html>
