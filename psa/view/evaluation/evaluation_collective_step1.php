<?php

session_start();
require_once("persistence/ConnectionFactory.php");
require_once("bean/Account.php"); 
require_once("persistence/AccountMapper.php");
require_once("bean/GroupesDossiers.php");

#var_dump($_SESSION['cabinet']); 
require_once ("Config.php");
$config = new Config();
require_once($config->rest_path . '/GetCabsAndLogins.php') ;

$path = 'https://'.$_SERVER['HTTP_HOST'];
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
#


$idgroupe = $_GET['idgroupe'];

$groupe = GroupesDossiers::getGroupeById($idgroupe);
$dossiers = explode(',',$groupe['dossiers']);
#var_dump($dossiers);

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Consultations collectives</title>
<meta name="robots" content="noindex,nofollow">
<link href="<?php echo $path;?>/view/login/css/psp5.css" rel="stylesheet" type="text/css">
<link href="<?php echo $path;?>/view/login/css/psp_nicetitle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>


<script type="text/javascript">

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

              <!-- debut du contenu -->
              <p>&nbsp;</p>

              <h1>Nouvelle consultation collective</h1><p>&nbsp;</p>
              

              <div id="formulaire">
                <form action="evaluation_collective_step2.php" method="POST">
                  <input type="hidden" name="idgroupe" value="<?php echo $groupe['id_groupe'];?>" id="idgroupe">
                  <input type="hidden" name="cabinet" value="<?php echo $_SESSION['cabinet'];?>" id="cabinet">
                  
                  <table align="center" border="1" cellpadding="5">
                    <tr>
                      <td colspan="2" align="left"><h3><?php echo $groupe['libelle'];?></h3></td>
                    </tr>
                    <tr>
                      <td width="40%">Date de consultation<br>jj/mm/aaaa</td>
<!--                      <td align="left" width="60%"><input type="date" name="date" id="evaluationdate" style="width:200px"></td>-->
                          <td align="left" width="60%"><input  name="date" id="evaluationdate" style="width:200px" onkeyup='formate_date(this)'></td>
                                                                        
                    </tr>
                    <tr>
                      
                      <td colspan="2" align="left">
                        <h3>Sélection des dossiers</h3>
                        <table>
                        
                          <?php 
                          $dossiers = json_decode($groupe['dossiers'],true);
                          #var_dump($dossiers);
                          foreach($dossiers as $dossier){
                            #var_dump($dossier);
                            $key = key($dossier);
                            ?>
                            <tr>
                            <td colspan="2">
                              <input type="checkbox" value="<?php echo $key;?>" name="id_dossier[]" checked="checked" class="checkDossier" dos="<?php echo $key;?>"> 
                              <?php echo $key;?>
                             
                            </td>
                            <td>&nbsp;</td>
                          </tr>
                          <?php } ?>
                        </table>

                      </td>
                      
                    </tr>
                    
                    <tr>
                      <td colspan="3" align="center"><input type="submit" value="Poursuivre">
                  
                  </table>
                  <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
                </form>
              </div>


             




               


              <!-- fin du contenu -->


              </td>
              
            </tr>
            </table>
      <br>


<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>


    </td></tr>
    </table>
       


</div>



<script language="javascript">
  
  // init date 
     function Date_toYMD() {
      var today = new Date();
        var year, month, day;
        year = String(today.getFullYear());
        month = String(today.getMonth() + 1);
        if (month.length == 1) {
            month = "0" + month;
        }
        day = String(today.getDate());
        if (day.length == 1) {
            day = "0" + day;
        }
        return year + "-" + month + "-" + day;
    }
    
     function Date_toDMY() {
      var today = new Date();
        var year, month, day;
        year = String(today.getFullYear());
        month = String(today.getMonth() + 1);
        if (month.length == 1) {
            month = "0" + month;
        }
        day = String(today.getDate());
        if (day.length == 1) {
            day = "0" + day;
        }
        return day + "/" + month + "/" + year;
    }

    
//     var str = Date_toYMD();
     var str = Date_toDMY();
     
     document.getElementById('evaluationdate').value = str;
     
    $('#formulaire').submit(function(){

      var date = $("#evaluationdate").val();
      
      if(date == "undefined" || date ==''){
        alert('La date ne peux pas être nulle !');return false;
      }

      var controlcoche=0;
      $(".checkDossier").each(function(){

        if($(this).is(':checked')){
         //alert($(this).attr('dos'));
         controlcoche=+1;
        }
      });
      
      if(controlcoche==0){
        alert('Vous devez sélectionner au moins un dossier pour enregistrer votre consultation');return false;
      }
      

      });

    /*
  $('#evaluationdate').change(function(){

      var date = $(this).val();
      controlDossierDate();

      });
*/
  

</script>
</body>
</html>
