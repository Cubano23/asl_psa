<?php

session_start();
require_once("persistence/ConnectionFactory.php");
require_once("bean/Account.php");
require_once("persistence/AccountMapper.php");
require_once("bean/GroupesDossiers.php");
require_once("controler/UtilityControler.php");
require_once("controler/EvaluationInfirmierControler.php");

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

#require_once($path.'/bean/EvaluationInfirmier.php');
#require_once($path.'/persistence/EvaluationInfirmierMapper.php');


$evaluation = new stdClass();
$evaluation->idgroupe = $_POST['idgroupe'];
//EAOUAD inverser format des dates 08-02-2017 

$xxdate=implode('-',array_reverse  (explode('/',$_POST['date'])));
$evaluation->date = $xxdate;
//

foreach($_POST['id_dossier'] as $iddos){
    $dossiers .=$iddos.', ';#echo $iddos.'@';
}
$evaluation->dossiers = substr($dossiers,0,-2);


if($_POST['send']==1){
    // on traite les datas du formulaire
    $evaluation = EvaluationInfirmierControler::traiteFormEvaluationCollective(); // renvoi un objet $evaluation
    #var_dump($evaluation);
    $responses = EvaluationInfirmierControler::recordEvaluationCollective($evaluation);

    foreach($responses as $key=>$result){
        if($result){
            $affReponse .='Le dossier <b>'.$key.'</b> a bien été inséré <br />';
        }
        else{
            $affReponse .='Le dossier <span style="color:red"><b>'.$key.'</b></span> n\'a pas été inséré, il doit déjà exister une consultation à cette date<br />';
        }
    }



}



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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

</head>

<?php
if($_SERVER['HTTP_HOST'] == 'psatest.asalee.fr') {
    $bodycolor = 'style="background-color:green"';
}
?>
<body <?php echo $bodycolor;?>>

<div id="header" style="width:100%">

    <table width="80%" border="0" cellspacing="0" cellpadding="0" align="center" background-color="#FFF">
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
            <td colspan="3" style="background-color:#FFF;padding:10px;">

                <!-- debut du contenu -->
                <p>&nbsp;</p>

                <h1>Consultation collective - Saisie</h1><p>&nbsp;</p>

                <h4 style="text-align:left">Date de la consultation : <?php echo UtilityControler::inversedate($evaluation->date,'fr');   ?></h4>




                <p style="text-align:left"><br />Ce formulaire permet de saisir un compte rendu suite à une <u><b>consultation collective</b></u>, quel que soit le type de consultation.<br><br>

                    Il permet de saisir un diagnostic éducatif ainsi qu'une évaluation continue d'éducation sur les dossiers suivants : <b><?php echo $evaluation->dossiers;?></b><br><br></p>

                <?php
                if($affReponse){
                    echo '<div style="border:solid 2px green;background-color:#9fdbb1;padding:10px 0;margin-bottom:15px;"><p>'.$affReponse.'</p></div>';

                    echo '<div>
      <a class="fa fa-window-close-o fa-2x close" aria-hidden="true" style="border:solid 1px;padding:5px;cursor: pointer;"> Fermer la fenêtre</a>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <a href="'.$path.'/view/dossier/liste_groupes.php" class="fa fa-pencil-square-o fa-2x" aria-hidden="true" style="border:solid 1px;padding:5px;cursor: pointer;"> Saisir une nouvelle consultation</a></div>';


                }
                else{


                    ?>

                    <form action="#" method="POST" id="form">
                        <input type="hidden" name="idgroupe" value="<?php echo $evaluation->idgroupe;?>" id="idgroupe">
                        <input type="hidden" name="dossiers" value="<?php echo $evaluation->dossiers;?>" id="dossiers">
                        <input type="hidden" name="cabinet" value="<?php echo $evalaution->cabinet;?>" id="cabinet">
                        <input type="hidden" name="send" value="1">
                        <input type="hidden" name="date" value="<?php echo $evaluation->date;?>">


                        <div style="text-align:left;">
                            <h2>1- Faire le bilan de la consultation<br />&nbsp;</h2>

                            <table border="1" cellpadding='3'>
                                <tr>
                                    <td>Degré de satisfaction:</td>
                                    <td colspan='2'><select  name='degre_satisfaction'>
                                            <option value="a+">très bon</option>
                                            <option value="a">bon</option>
                                            <option value="b">moyen</option>
                                            <option value="c">mauvais</option>
                                            <option value="d">très mauvais</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <input type="hidden" name="id_utilisateur" value="<?= $_SESSION['id.login']; ?>">
                                    <input type="hidden" name="id_cabinet" value="<?= $_SESSION['cabinet']; ?>">
                                    <td>Durée approximative en minutes ("à 5 mi nutes près de <u>5 à 300</u>")</td>
                                    <td><input type='number' size='3' name='duree' id="duree" step="5" min="5" max="300" value="0">
                                    </td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td>En cas de consultation &agrave;<br/>domicile, cocher la case:<br/>
                                                    <input  name='consult_domicile' type='checkbox' value='1' >
                                                </td>
                                                <td width="10">&nbsp;&nbsp;&nbsp;</td>
                                                <td>En cas de consultation <br/>t&eacute;l&eacute;phonique, cocher la case:<br/>
                                                    <input  name='consult_tel' type='checkbox' value='1' >
                                                </td>
                                                <td width="10">&nbsp;&nbsp;&nbsp;</td>
                                                <td>En cas de consultation <br/>collective, cocher la case:<br/>
                                                    <input  name='consult_collective' type='checkbox' value='1' checked="checked">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Type de consultation:</td>
                                    <td><select multiple size='12' name='type_consultation[]' id="type_consultation">
                                            <option value="dep_diab">Dépistage du diabète de type 2</option>
                                            <option value="suivi_diab">Suivi du diabète de type 2</option>
                                            <option value="rcva">Suivi du patient RCVA</option>
                                            <option value="bpco">Repérage BPCO tabagique</option>
                                            <option value="cognitif">Repérage des troubles cognitifs</option>
                                            <option value="sevrage_tabac">Sevrage Tabagique</option>
                                            <option value="automesure">Automesure tensionnelle</option>
                                            <option value="hemocult">Hémocult</option>
                                            <option value="sein">Dépistage cancer du sein</option>
                                            <option value="colon">Dépistage cancer du colon</option>
                                            <option value="uterus">Dépistage cancer col de l'utérus</option>
                                            <option value="surpoids">Surpoids chez l'enfant</option>
                                            <option value="autres">Autres</option>
                                        </select>
                                    </td>
                                    <td>A chaque fois qu’une action de nature dérogatoire est effectuée, au titre du protocole de coopération ASALEE,
                                        agréé par la Haute Autorité de Santé le 22 mars 2012, et sous réserve de l’autorisation de l’Agence Régionale de Santé
                                        et de la notification de l’équipe ASALEE (médecins-infirmières), <br>
                                        cocher la ou les actions concernées.<br><br>

                                        <input  name='hba' type='checkbox' value='1' > Prescription d’examen(s) pour le patient diabétique type 2 <br>
                                        <input  name='exapied' type='checkbox' value='1' > Prescription, réalisation, interprétation examen des pieds<br>
                                        <input  name='monofil' type='checkbox' value='1'> Prescription, réalisation, interprétation examen des pieds et monofilament<br>
                                        <input  name='ecg' type='checkbox' value='1'> Prescription et réalisation d’ECG<br>
                                        <input  name='ecg_seul' type='checkbox' value='1'> Réalisation d’ECG seul – non dérogatoire<br>
                                        <input  name='spirometre' type='checkbox' value='1'> Prescription, réalisation d’une spirométrie <br>
                                        <input  name='spirometre_seul' type='checkbox' value='1'> Réalisation d’une spirométrie seule – non dérogatoire <br>
                                        <input  name='t_cognitif' type='checkbox' value='1'> Prescription et réalisation d’un repérage troubles cognitifs <br>
                                        <input  name='autre' type='checkbox' value='1'> Autre. Précisez : <input type='text'  name='prec_autre' value="">
                                        <br>    </td>
                                </tr>
                                <tr>
                                    <td valign='top'>Points positifs :
                                        <div style="font-size:9px">
                                            Besoins du patient pris en compte<br>
                                            Objectifs prévus atteints<br>
                                            Objectifs  non  prévus atteints<br>
                                            Outil(s), support (s), méthodes  utilisés </div></td>
                                    <td  width='70%' colspan='2'><textarea rows="8" cols="60" name='points_positifs'></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top'>Points à améliorer :
                                        <div style="font-size:9px">
                                            Besoins du patient non pris en compte<br>
                                            Objectifs prévus non  atteints<br>
                                            Objectifs perçus à atteindre<br>
                                            Méthodes envisagées prochaine séance</div></td>
                                    <td  width='70%' colspan='2'><textarea rows="8" cols="60" name='points_ameliorations'></textarea>
                                    </td>
                                </tr>
                            </table>
                            <br>


                            <h2>2- Diagnostic éducatif - synthèse<br />&nbsp;</h2>
                            <table width='880' border="1" cellpadding='3'>
                                <tr>
                                    <td valign='top'>Aspects limitants:</td>
                                    <td  width='70%' colspan='2'><textarea rows="8" cols="60" name='aspects_limitant'></textarea>
                                    </td>
                                </Tr>
                                <tr>
                                    <td valign='top'>Aspects facilitants:</td>
                                    <td  width='70%' colspan='2'><textarea rows="8" cols="60" name='aspects_facilitant'></textarea>
                                    </td>
                                </Tr>
                                <tr>
                                    <td valign='top'>Objectifs du patient:</td>
                                    <td  width='70%' colspan='2'><textarea rows="8" cols="60" name='objectifs_patient'></textarea>
                                    </td>
                                </Tr>
                            </table>
                            <br>



                            <input type='submit' value='Valider la saisie'>

                            <br><br>
                    </form>

                <?php } ?>
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

    $('#form').submit(function(){

        var retour = controlDatas();

        if(retour == false){
            return false;
        }

    });


    function controlDatas(){

        //alert($('#duree').val());
        if($('#duree').val() > 300){
            alert('Erreur : la duree de consultation ne peux pas dépasser 300 minutes.');return false;
        }
        if($('#duree').val() <= 0){
            alert('Erreur : la duree de consultation ne peux pas être négative.');return false;
        }

        if($("#type_consultation").val() == null){
            alert('Erreur : vous devez sélectionner au moins un type de consultation.');return false;
        }

    }

    $(".close").click(function(){

        window.close();

    })

</script>
</body>
</html>
