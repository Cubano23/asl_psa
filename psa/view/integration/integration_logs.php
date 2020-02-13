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
    <title>Trace des Intégrations</title>
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
            var url = "./log/";
            return '<a href="'+url + val+'">'+val+'</a>';
        }
    </script>

</head>
<body bgcolor=#FFE887>
<?php




$cabinet = $_SESSION["cabinet"];

require_once "Config.php";
$config = new Config();

//require("$base/global/entete.php");

require($config->inclus_path . "/accesbase.inc.php");
//entete_asalee("Trace des Int?grations");

//require("./integration/importlogs.php");




# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD:". mysql_error());
mysql_select_db($DB) or
die("Impossible de se connecter à la base");


$table = "integration";

$base = $config->app_path;
$dirname = $base . $config->psa_path . "/view/integration/log";
$cpt= "Compte-rendu integration ".$cabinet;

//$dir = opendir($dirname);
$ext=array(".xls", ".zip");



$pattern = $dirname."/".$cpt."*";
$files = glob($pattern,GLOB_NOSORT );

/* // echo ($files);                           <?php
  foreach ($files as $filename) {
     
     
         $file =  basename($filename);   
         $sstr = substr($file, strlen($cpt),strlen($file) - strlen($cpt) );
         $sstr= str_replace($ext, "", $sstr);
         $tokens= explode(    " ", $sstr );
          
//		     $cabinet= $tokens[0];
         $dintegration=$tokens[1];
         $reportfile= $file;
         
  
         $sql = "select logiciel from account where cabinet='$cabinet'";
         $rs = mysql_query( $sql);
         list($row) = mysql_fetch_row($rs);
         $logiciel=$row;
         $dt =   date ("H:i:s", filemtime($filename));
         
         $tokens  = explode(    "-", $dintegration );
         $dintegration = $tokens[2]."-".$tokens[1]."-".$tokens[0];
  //       $reportfile = '<a href="integration_logs/'.$reportfile.'" target="_blank">'.$reportfile.'</a>'; 
         $sql2="INSERT  INTO integration(cabinet, logiciel, dintegration, entryfile, reportfile, cr, tintegration, hintegration)".
               " VALUES ('$cabinet','$logiciel','$dintegration','Importedrow','$reportfile',0,0,'$dt')";
         $rs = mysql_query($sql2);   
	}

 //}
  
 
//closedir($dir);
  */

?>


<br />
<br />
<br />



<table id="dg" class="easyui-datagrid" style="width:2300px"
       url="getdata.php"
       title="Trace des Intégrations" toolbar="#toolbar"
       pagination="true" pageSize="50"
       rownumbers="true"
       singleSelect="true" fitColumns="true"
       nowrap="false" >
    <thead  frozen="true">
    <tr>
        <th field="dintegration"  sortable="true" >Date Intégration</th>
        <th field="hintegration"   >Fin Intégration</th>
        <th field="reportfile" formatter="formatFileLink" width="750" >Fichier Compte Rendu</th>
    </tr>
    <thead><tr>
        <th field="logiciel" sortable="true" >logiciel</th>
        <th field="tintegration" >Durée Intégration</th>
    </tr>
    </thead>






</table>



<?php
//laisser là pour contourner le non affichage des piwik de ids
echo("<br />");
?>

</body>
</html>
