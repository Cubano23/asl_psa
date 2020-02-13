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
  <title>Gestion des SMS</title>
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
    var smsurl;
	   
		function newMessage()
		{
      var date = new Date();
			$('#dlg').dialog('open').dialog('setTitle','Nouveau Message');
			$('#fm').form('clear');
      $('#smsid').val('0');
      $('#smsdate').val(date.toISOString().substring(0, 19).replace('T',' '));
			$('#smscab').readonly=false;
      $('#smsdate').readonly=true;
      $('#dlg').dialog('open').dialog('setTitle','Nouveau Message');
      $('#btSave').linkbutton('enable');$('#btSend').linkbutton('enable');$('#btDelete').linkbutton('disable');
        
		}
		function editMessage()
		{
			var row = $('#dg').datagrid('getSelected');
			if (row){
           
				$('#dlg').dialog('open').dialog('setTitle','Modifier Message');
        $('#fm').form('load',row);
				$('#smscab').combobox('readonly',false);
 				$('#smsdate').prop('disabled',true);	
 				$('#smstext').prop('disabled',false);	
        $('#btSave').linkbutton('enable');$('#btSend').linkbutton('enable');$('#btDelete').linkbutton('enable');

			}
      else
      alert('Choisir un message');
		}


		function viewMessage()
		{
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#dlg').dialog('open').dialog('setTitle','Visualiser un Message');
        $('#fm').form('load',row);
				$('#smscab').combobox('readonly',true);
 				$('#smsdate').prop('disabled',true);	
 				$('#smstext').prop('disabled',true);	

        $('#btSave').linkbutton('disable');$('#btSend').linkbutton('disable');

			}
      else
      alert('Choisir un message');
		}

    function doSmsCancel()
    {
   	    $('#dlg').dialog('close');		// close the dialog
				$('#dg').datagrid('reload');	// reload the user data
    }
    
    function doSmsDelete()
    {
 			var row = $('#dg').datagrid('getSelected');
			if (row){
 				$.messager.confirm('Confirm','Etes vous sûrs d\'effacer le SMS?',function(r)
				{
					if (r)
					{
        
						$.post('sms/remove.php',{id:row.id}
                                     ,function(result)
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
        }   
			

   	    $('#dlg').dialog('close');		// close the dialog

    }
    
    function doSmsDraft()
    {


						$.post('sms/draft.php',{id:$('#smsid').val(), 
                                     sms_to:$('#smscab').combobox('getText'),
                                    sms_text:$('#smstext').val(),
                                     sms_status:'1'
                                   }
                                     , function(result)
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

   	    $('#dlg').dialog('close');		// close the dialog

    }

     function doSmsSend()
    {


						$.post('sms/send.php',{id:$('#smsid').val(), 
                                     sms_to:$('#smscab').combobox('getText'),
                                    sms_text:$('#smstext').val(),
                                     sms_status:'0'
                                   }
                                     , function(result)
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

   	    $('#dlg').dialog('close');		// close the dialog

    }

    
		function utf8_encode( string ) 
		{
			return unescape( encodeURIComponent( string ) );
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



       function enabledisabebuttons(recordstatus)
      {
      
            if(recordstatus==0)
            {
              $('#btEdit').linkbutton('disable');$('#btSave').linkbutton('disable');$('#btSend').linkbutton('disable');
            }
            else
            {
               $('#btEdit').linkbutton('enable');$('#btSave').linkbutton('enable');$('#btSend').linkbutton('enable');
            }
      
      
      }


</script>

</head>
<body bgcolor=#FFE887>
<?php

require_once("../global/entete.php");

entete_asalee("Envoi Sms aux Cabinets");
?>


<br />
<br />

	
	<table id="dg" class="easyui-datagrid" style="width:1000px"
			url="sms/getdata.php"
			title="Envoi sms aux Cabinets" toolbar="#toolbar" 
			pagination="true" pageSize="20" 
			rownumbers="true" 
			singleSelect="true" fitColumns="true"
			nowrap="false" >
		<thead  frozen="true">
			<tr>
        <th field="id" width="50" >Id</th>
        <th field="sms_status" width="150" >Etat</th>
				<th field="sms_date" width="100" sortable="true">Date</th>
				<th field="sms_to" width="100" sortable="true">Destinataires</th>
				<th field="sms_from" sortable="true">Emetteur</th>
				<th field="sms_text" width="150" >Texte</th>
</tr></thead>
	</table>
	
	<div id="toolbar" style="padding:5px;height:auto">
		<div style="margin-bottom:5px">
    <table>
			<tr>
          <td><a href="#" class="easyui-linkbutton" iconCls="icon-add" onclick="newMessage()" id="btAdd">Nouveau</a>
			    <a href="#" class="easyui-linkbutton" iconCls="icon-edit"  onclick="editMessage()" id="btEdit" >Editer</a>
          <a href="#" class="easyui-linkbutton" iconCls="icon-view"  onclick="viewMessage()" id="btView" >Visualiser</a>
          
          </td>
      </tr>
      <tr>
			<td><span>Cabinet:</span>
       	<input name="cabsearch"  id="cabsearch" class="easyui-combobox" style="width:200px"
					url="sms/cabinets_getlist.php"
					valueField="cab" textField="text">
          <span class="button-sep"></span>
           <span>Etat Sms:</span>
                 <select id="hsearch" class="easyui-combobox" name="hsearch" style="width:100px;">
                             <option value="0">Transmis</option>
                             <option value="1">Brouillons</option>
                             <option value="2">Tous</option>
                  </select>
          <span class="button-sep"></span>

			<a href="#" class="easyui-linkbutton" iconCls="icon-search"  onclick="doSearch()">Recherche</a>
      <a href="#" class="easyui-linkbutton" onclick="doReset()" iconCls="icon-reload">Reinitialiser</a></td>
      </tr>
    </table>
		</div>
	</div>



      <div class="easyui-dialog" title="Envoi Sms" style="width:500px;padding:10px;" id="dlg" closed="true" >
        <form id="fm" method="post" >


        <table style="width:100%;">
        <tr>
          <td> Id:</td>
              <td>
                    <input type="text" class="easyui-textbox" name="id"  id="smsid" disabled="true" ></input>  
              </td>
         </tr>
        
        </tr>
        <tr>
          <td> Date:</td>
              <td>
                    <input type="text" class="easyui-textbox" name="sms_date"  id="smsdate" disabled="true" ></input>  
              </td>
         </tr>
          <tr>
              <td>Cabinet:</td>
              <td>
              	<input name="sms_to"  id="smscab" class="easyui-combobox" style="width:200px"
					url="cab/cabinets_getlist.php"
					valueField="cab" textField="text"
          data-options="required:true" 
          >
             </td>
              
         </tr>
         <tr>
          <td>Message:</td>
          <td>
                <textarea class="easyui-textbox" name="sms_text"  id="smstext"  data-options="multiline:true"   style="width:100%;height:60px" maxlength="160" data-options="required:true" ></textarea>
          </td>         
         </tr>
         </table>
              
              
              
              
              
               
                
      <br />
      <br />
			<a href="#" class="easyui-linkbutton" iconCls="icon-email2"  onclick="doSmsSend()"  id="btSend" >Envoi</a>
      <a href="#" class="easyui-linkbutton" iconCls="icon-save"  onclick="doSmsDraft()"  id="btSave" >Brouillon</a>
      <a href="#" class="easyui-linkbutton" iconCls="icon-cancel"  onclick="doSmsCancel()" id="btCancel">Annuler</a>
      <a href="#" class="easyui-linkbutton" iconCls="icon-remove"  onclick="doSmsDelete()" id="btDelete">Effacer</a>
                                  
        </form>
    </div>


<?php
//laisser là pour contourner le non affichage des piwik de ids
		echo("<br />");
?>

<script type="text/javascript">

    // when double click a cell, begin editing and make the editor get focus
$('#dg').datagrid({

	onClickRow: function(index,row){
//			var row = $('#dg').datagrid('getSelected');
			if (row)
			{
              enabledisabebuttons(row.sms_status);      
      }
	}
});

  

</script>



</body>
</html>
