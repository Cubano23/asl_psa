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
  $path = 'http://'.$_SERVER['HTTP_HOST'].$config->psa_path;
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
                <td colspan="3" style="background-color:#FFF;padding-let:20px;">

              <!-- debut du contenu -->
              <p>&nbsp;</p>

              <h1>Consultation collective - Aide</h1><p>&nbsp;</p>
              <div><a href="javascript:history.back();">Retour</a></div>
              <div style="text-align:left; margin-left:40px;margin-right:40px;">

              <h2 style="margin-bottom:3px;">Présentation</h2>
              <p style="margin-bottom:10px;">Les consultations collectives ont pour but de saisir en une seule fois une consultation de plusieurs patients.</p>
             
              <h2 style="margin-bottom:3px;">Principe de fonctionnement</h2>
              <h3 style="margin-bottom:5px;"><u>Les groupes : </u></h3>

              <p style="margin-bottom:10px;">Afin de simplifier cette saisie vous pouvez créer des "groupes" de patient, un groupe de patient peux contenir autant de patients que vous le souhaitez, il suffit simplement d'indiquer le numéro de dossier de chaque patient séparés par des virgules "," et ainsi constituer chacun de vos groupes (exple 12,36,A6545,R7678...).<br/>
              Le nombre de groupes ainsi que le nombre de dossiers ne sont pas limités.
              <p style="margin-bottom:10px;">Chaque groupe doit contenir un nom de groupe, vous pouvez y indiquer un commentaire (pour vous), et la liste des dossiers.<br>
              <br/>Un lien pratique <span style="color:orange">"Vérifier les numéros de dossiers"</span> permet de contrôler que les numéros de dossiers sont bien reconus par le système. Un message d'alerte vous indique les dossiers éventuellement non reconnus ou un message du type <span style="color:green">"les dossiers sont OK"</span> qui confirme que votre saisie est correcte.
              <br />A noter que les dossiers <u>non reconnus</u> ne <b>seront pas mémorisés</b> dans le groupe.</p>

              <p style="margin-bottom:10px;"><b>CREER UN GROUPE</b><br>
              Pour créer un nouveau groupe cliquez sur le lien <span style="color:orange">"Créer un nouveau groupe"</span> - Une zone de saisie s'affiche en haut de page et vous permet d'indiquer vos informations comme indiqué ci-dessus. L'enregistrement de vos informations ajoute votre groupe dans le tableau principal avec l'indication du nombre de dossiers mémorisés dans le groupe.</p>

              <p style="margin-bottom:10px;">Vous pouvez librement modifier/supprimer les groupes depuis la page "liste des groupes" qui vous permet de gérer l'intégralité de vos groupes.</p>
              
              <p style="margin-bottom:10px;">

              

              <h3 style="margin-bottom:5px;"><u>Saisie des consultations : </u></h3>

              <p style="margin-bottom:10px;">Un fois votre groupe constitué, vous pouvez saisir votre nouvelle consultation.<br/><br/>
              
              <p style="margin-bottom:10px;"><b>CREER UNE NOUVELLE CONSULTATION</b><br>
              Pour saisir une nouvelle consultation, cliquez sur le lien <span style="color:orange">"Saisir consultation"</span> à droite du tableau sur le groupe souhaité.</p>
              Un nouvel écran vous demandera la date de la consultation, (NB : cette zone est équipée d'un calendrier qui vous permet de saisir rapidement la date souhaitée - <u>cette fonctionnalité n'est pas disponible sous Firefox et Internet Explorer antérieur à la version 10</u>), ensuite vous retrouverez la liste des dossiers de votre groupe, par defaut tous les dossiers sont sélectionnés, mais il vous est possible de les dé-cocher pour ainsi exclure un patient qui n'aurait pas participé à une consultation.</p> 
              <br><br>
              En cliquant sur le bouton "poursuivre" vous passez à l'étape suivante qui correspond à la saisie des informations de consultation avec chaque dossier choisis.</p>
              A la validation de votre consultation, le système vous affiche un rapport pour chacun des dossiers afin de valider si la consultation a été enregistrée ou non.<br><br>
              <b><u>IMPORTANT :</u></b> Si une consultation existe déjà pour un patient à la même date, le système vous l'indique dans le message.

              </div>

              <!-- fin du contenu -->

              <p>&nbsp;</p>
              <p>&nbsp;</p>
              <p>&nbsp;</p>
              </td>
              
            </tr>
            </table>




       


</div>



<script language="javascript">
  
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
