<?php

session_start();
require_once("persistence/ConnectionFactory.php");
require_once("bean/Account.php"); 
require_once("persistence/AccountMapper.php");
require_once("persistence/EvaluationInfirmierMapper.php");
require_once("controler/EvaluationInfirmierControler.php");
require_once("controler/UtilityControler.php");
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

$listeConsultations = EvaluationInfirmierControler::HistoConsultCollectiveByGroup($idgroupe);
                  
#var_dump($listeConsultations);






?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Liste des groupes de consultations collectives</title>
<meta name="robots" content="noindex,nofollow">
<link href="<?php echo $path;?>/view/login/css/psp5.css" rel="stylesheet" type="text/css">
<link href="<?php echo $path;?>/view/login/css/psp_nicetitle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

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

              <h1>Historique des consultations collectives du groupe</h1><p>&nbsp;</p>
              
               <a href="<?php echo $path;?>/view/dossier/liste_groupes.php" class="fa fa-pencil-square-o" aria-hidden="true" style="border:solid 1px;padding:5px;cursor: pointer;"> Retour à la liste des groupes</a></div>

              <table border="1" align="center" width="80%">
                
                  <td><h3>Date consultation / code</h3></td>
                  <td><h3>Durée de la consultation</h3></td>
                  <td><h3>Type de consultation</h3></td>
                  <td><h3>Nombre de dossiers</h3></td>
                  <td><h3>Numéros de dossiers rattachés</h3></td>
                  
                </tr>

                <?php 
                foreach ($listeConsultations as $consult){
                  #$consult = current($consult);
                  // on récupere tous les consultations indivudelles qui ont le mm uuid_collectif
                  $consultations = EvaluationInfirmierMapper::listeDossiersByConsultCollectives($consult->uuid_collectif);
                  #var_dump($consultations);exit;
                  $dossiers='';
                  foreach($consultations as $dossier){
                    $dossiers .= $dossier->numero.',';
                  }
                  $dossiers = substr($dossiers,0,-1);
                  ?>
                  <tr>
                    <td align="center"><?php echo UtilityControler::inversedate($consult->date,'fr');?><br>
                      <span style="font-size:0.8em;"><?php echo $consult->uuid_collectif;?></span></td>
                    <td align="center"><?php echo $consult->duree;?></td>
                    <td><?php echo $consult->type_consultation;?></td>
                    <td><?php echo count($consultations);?></td>
                    <td><?php echo $dossiers;?></td>
                    
                  </tr>

                  <?php
                    }
                  ?>

                 
                </table>


<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

               


              <!-- fin du contenu -->


              </td>
              
            </tr>
            </table>
      <br>







    </td></tr>
    </table>
       


</div>



<script language="javascript">
  
  $("#formulaire").hide();


  // validation du formulaire
   $('#formulaire').submit(function(){


      var retour = controlDatas();

      if(retour == false){
        return false;
      }

      });


  $('.edit').click(function(){

        var idgroupe = $(this).attr('id');
        
        // avec l'id groupe on recu les infos en json

        $.ajax({
          url: 'refresh_groupe.php',
          type: 'POST',
          data: {
            idgroupe: idgroupe          
          },
          success: function(data){
            var results = JSON.parse(data);
            //alert(data);
            // on cole les infos dans le formulaire
            // $("#note_").html(results['html']);
            $("#idgroupe").val(idgroupe);
            $("#libelle").val(results['libelle']);
            $("#commentaire").val(results['commentaire']);
            if(results['dossiers']=='false'){
              var resdossiers='';
            }
            else{
              var resdossiers=results['dossiers'];
            }
            $("#dossiers").val(resdossiers);
            $("#reponse-control-dossiers").html('');

          }

        });

        

        $('#formulaire').show();
        //controlDossiers();
        return false;
      });

  
    $('.new').click(function(){

        var idgroupe = $(this).attr('id');
            $("#idgroupe").val(idgroupe);
            $("#libelle").val('');
            $("#commentaire").val('');
            $("#dossiers").val('');
            $("#reponse-control-dossiers").html('');

            $('#formulaire').show();
            //controlDossiers();
            return false;
      });

  


  $('.control-dossiers').click(function(){

      controlDossiers();
    

  });


  function controlDossiers(){
    var dossiers = $("#dossiers").val();
    var cabinet = $("#cabinet").val();
    //alert(dossiers);
      $.ajax({
          url: 'control_dossiers_groupe.php',
          type: 'POST',
          data: {
            dossiers: dossiers,         
            cabinet: cabinet,         
          },
          success: function(data){
            var results = JSON.parse(data);
            //alert(data);
            $("#reponse-control-dossiers").html(results['msg']);
          }

        });
  }


  function controlDatas(){

    var dossiers = $("#dossiers").val();
    if(dossiers.length < 1){
      alert('Erreur : vous devez indiquer des numéros de dossier.');return false;
    }
    var dossiers = $("#libelle").val();
    if(dossiers.length < 3){
      alert('Erreur : vous devez indiquer un nom de groupe (minimum 3 caractères).');return false;
    }
  }


</script>

</body>
</html>
