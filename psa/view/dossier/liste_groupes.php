<?php

session_start();
require_once("persistence/ConnectionFactory.php");
require_once("bean/Account.php"); 
require_once("persistence/AccountMapper.php");
require_once("persistence/EvaluationInfirmierMapper.php");
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


$listeGroupesActifs = GroupesDossiers::listeGroupesByCab($_SESSION['cabinet'], TRUE);
$listeGroupesInactifs = GroupesDossiers::listeGroupesByCab($_SESSION['cabinet'], FALSE);

#var_dump($listeGroupesActifs);




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

              <h1>Liste des groupes de consultations collectives</h1><p>&nbsp;</p>
              

              <div id="formulaire">
                <form action="<?php echo $path ?>/view/dossier/xt_groupe.php" method="POST">
                  <input type="hidden" name="idgroupe" value="" id="idgroupe">
                  <input type="hidden" name="cabinet" value="<?php echo $_SESSION['cabinet'];?>" id="cabinet">
                  <table align="center" border="1" cellpadding="5">
                    <tr>
                      <td colspan="2" align="left"><h3>Edition du groupe</h3></td>
                    </tr>
                    <tr>
                      <td width="40%">Nom du groupe</td>
                      <td align="left" width="60%"><input type="text" name="libelle" id="libelle" style="width:200px"></td>
                    </tr>
                    <tr>
                      <td>Commentaire</td>
                      <td align="left"><textarea style="width:200px" name="commentaire"  id="commentaire"><?php echo $commentaire;?></textarea></td>
                    </tr>
                    <tr>
                      <td>Dossiers<br>Indiquez les numéros de dossiers<br>séparés par des virgules<br><span style="font-size:0.8em;color:red"><u>Attention</u> les dossiers non reconnus seront ignorés !</span></td>
                      <td align="left"><textarea style="width:200px" name="dossiers" id="dossiers" ><?php echo $dossiers;?></textarea>
                        <br><a class="control-dossiers">Vérifier les numéros de dossiers</a><br>
                        <div id="reponse-control-dossiers"></div></td>
                    </tr>
                    <tr>
                      <td colspan="2"><input type="submit" value="Enregistrer"></td>
                    </tr>
                  
                  </table>
                  <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
                </form>
              </div>


              <table border="1" align="center" width="80%">
                <tr><td colspan="7" align="left"><a class="new">+ Créer un nouveau groupe</a></td></tr>
                <tr>
                  <td><h3>Nom du groupe</h3></td>
                  <td><h3>Commentaire</h3></td>
                  <td><h3>Nombre de dossiers</h3></td>
                  <td><h3>Editer</h3></td>
                  <td><h3>Archiver</h3></td>
                  <td><h3>Consultation</h3></td>
                  <td><h3>Nombre de consultations</h3></td>
                  
                </tr>

                <?php 
                foreach ($listeGroupesActifs as $groupe){

                  $dossiersTab = explode(',',$groupe['dossiers']);

                  // comptage du nombre de consultations par groupe
                  
                  $consultations = EvaluationInfirmierMapper::listConsultCollectiveByGroup($groupe['id_groupe']);
                  if(count($consultations)>0){
                    $link_histo = '<a href="'.$path.'/view/evaluation/evaluation_collective_historique.php?idgroupe='.$groupe['id_groupe'].'">'.count($consultations).'</a>';
                  }else{
                    $link_histo = '0';
                  }
                  ?>
                  <tr>
                    <td align="left"><?php echo stripslashes($groupe['libelle']);?></td>
                    <td align="left"><?php echo stripslashes($groupe['commentaire']);?></td>
                    <td><?php echo count($dossiersTab);?></td>
                    <td><a href=""><i class="fa fa-pencil-square-o fa-2x edit" aria-hidden="true" id="<?php echo $groupe['id_groupe'];?>"></i></a></td>
                    <td><a onClick="if(confirm('Êtes-vous certain d'archiver ce groupe ? \nSi vous archivez ce groupe, cet archivage sera définitif, en revanche vous pourrez toujours consulter les consultations enregistrées')) { window.location.href='<?php echo $path ?>/controler/ActionControler.php?controlerparams:param:controler=GroupesControler&controlerparams:param:action=AD&controlerparams:param:id=<?php echo $groupe['id_groupe'] ?>'}"><i class="fa fa-trash fa-2x delete" aria-hidden="true"></i></a></td>
                    <td><a href="<?php echo $path ?>/view/evaluation/evaluation_collective_step1.php?idgroupe=<?php echo $groupe['id_groupe'];?>">Saisir Consultation</a></td>
                    <td><?php echo $link_histo;?></td>
                  </tr>

                  <?php
                    }
                  ?>

                 
                </table>


<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<h3 style="margin-left:10%;text-align:left;">Groupes archivés :</h3>
<br />&nbsp;
<table border="1" align="center" width="80%">
                <tr>
                  <td><h3>Nom du groupe</h3></td>
                  <td><h3>Commentaire</h3></td>
                  <td><h3>Nombre de dossiers</h3></td>
                  <td><h3>Nombre de consultations</h3></td>
                  
                </tr>

                <?php 
                foreach ($listeGroupesInactifs as $groupe){

                  $dossiersTab = explode(',',$groupe['dossiers']);

                  // comptage du nombre de consultations par groupe
                  
                  $consultations = EvaluationInfirmierMapper::listConsultCollectiveByGroup($groupe['id_groupe']);
                  if(count($consultations)>0){
                    $link_histo = '<a href="'.$path.'/view/evaluation/evaluation_collective_historique.php?idgroupe='.$groupe['id_groupe'].'">'.count($consultations).'</a>';
                  }else{
                    $link_histo = '0';
                  }
                  ?>
                  <tr>
                    <td align="left"><?php echo stripslashes($groupe['libelle']);?></td>
                    <td align="left"><?php echo stripslashes($groupe['commentaire']);?></td>
                    <td><?php echo count($dossiersTab);?></td>
                    <td><?php echo $link_histo;?></td>
                  </tr>

                  <?php
                    }
                  ?>

                 
                </table>
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

  // $('.delete').click(function(){

  //   var idgroupe = $(this).attr('id');
  //   $.ajax({
  //     url: 'ajax_delete_groupe.php',
  //     type: 'POST',
  //     data: {
  //       idgroupe: idgroupe          
  //     },
  //     success: function(data){
  //       window.location.reload();

  //     }

  //   });
  // });

  $('.edit').click(function(){

        var idgroupe = $(this).attr('id');
        
        // avec l'id groupe on recu les infos en json

        $.ajax({
          url: '<?php echo $path ?>/view/dossier/refresh_groupe.php',
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
          url: '<?php echo $path ?>/view/dossier/control_dossiers_groupe.php',
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
