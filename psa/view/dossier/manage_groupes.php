<?php

session_start();
require_once("persistence/ConnectionFactory.php");
require_once("bean/CompetencesInfirmieres.php");
require_once("bean/Account.php"); 
require_once("persistence/AccountMapper.php");
require_once("controler/UtilityControler.php");
require_once("controler/CompetencesControler.php");

#var_dump($_SESSION['cabinet']); 


require_once ("Config.php");
$config = new Config();
require_once($config->rest_path . '/GetCabsAndLogins.php') ;

$path = 'http://'.$_SERVER['HTTP_HOST'];
#echo $path;

if($_SERVER['APPLICATION_ENV']=='dev-herve'){
  $_SESSION['id.login'] = 'arizk';
  $_SESSION['id.nom'] = 'Rizk';
  $_SESSION['id.prenom'] = 'Antoine';
  $_SESSION['id.email'] = 'antoine.rizk@gisgo.fr';
  $_SESSION['id.telephone'] = '0680118013';
  $path = 'http://'.$_SERVER['HTTP_HOST']. $config->psa_path;
}

#
$conn = new ConnectionFactory();
$conn->getConnection();
session_start();
#var_dump($_POST);
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Gestion des groupes</title>
<meta name="robots" content="noindex,nofollow">
<link href="<?php echo $path;?>/view/login/css/psp5.css" rel="stylesheet" type="text/css">
<link href="<?php echo $path;?>/view/login/css/psp_nicetitle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
</head>

<?php
if($_SERVER['HTTP_HOST'] == 'psatest.asalee.fr') {
  $bodycolor = 'style="background-color:green"';
}
?>
<body <?php echo $bodycolor;?>>

<div id="header" style="width:100%">
      
    <table width="90%" border="0" cellspacing="0" cellpadding="0" align="center" background-color="#FFF">
              <tr>
                <td bgcolor="white">
                  <a href='<?php echo $path;?>/controler/ActionControler.php?controlerparams:param:controler=UtilityControler&controlerparams:param:action=AMEN' ><img src="<?php echo $path;?>/view/login/img/habillage/header_psa.gif" alt='Portail Services Asal&eacute;e' title='Retour accueil du Portail Services Asal&eacute;e' width='355' height='130' border='0'></a>
                <td align="right" bgcolor="white"><img src="<?php echo $path;?>/view/login/img/logos/LogoAsalee2013.png" alt="Services propos&eacute; par&hellip;" width="190" height="68" hspace="20" vspace="0"></td>
                <td width="10"><img src="<?php echo $path;?>/view/login/img/habillage/header_right.gif" width="10" height="130"></td>
              </tr>
            
              <tr>
                <td colspan="3" style="background-color:#FFF;"><hr style="width:90%"></td>
              </tr>

              <tr>     
                <td colspan="3" style="background-color:#FFF;">

    
                <form action="#" method="post" name="manage">

                <table border="1" cellspacing="1" width="100%">
                  <tr><td>
                    <center><h1><u>Consultations Collectives</u></h1></center><br>&nbsp;
                    <table border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>Cabinet</td>
                        <td><?php echo $_SESSION['cabinet'];?></td>
                      </tr>
                      <tr>
                        <td>Nom du groupe</td>
                        <td>&nbsp;</td>
                      </tr>
                    <tr>
                        <td>Date de l'�valuation </td>    
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                     
                    </table>
                 

              </td>
              
            </tr>
            </table>
      <br>




      </form>







    </td></tr>
    </table>
       


    </div>



