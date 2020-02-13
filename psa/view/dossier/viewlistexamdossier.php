<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php global $account ?>
<?php global $dossier; ?>
<?php global $suiviDiabete ?>
<?php global $param ?>
<?php
global $DepistageCancerColonList;
global $DepistageCancerSeinList;
global $DepistageCancerUterusList;
global $DepistageDiabeteList;
global $suiviDiabeteList;
global $TroubleCognitifList;
global $tensionArterielleMoyenneList;
global $HyperTensionArterielleList;
global $EvaluationInfirmierList;
global $CardioVasculaireList;
global $HemocultList;
global $sevrageTabacList;

global $liste_historique;

#var_dump($sevrageTabacList);
?>

  <script language="JavaScript" type="text/javascript">

function affiche_detail(element){
	var element=document.getElementById(element);

	if(element.style.display=='none')
	{
		element.style.display='';
	}
	else
	{
	    element.style.display='none';
	}

}

</SCRIPT>
<?php require("view/common/dossierresume.php");?>
<br>
<?php

if ((count($suiviDiabeteList)==0)&&(count($DepistageCancerColonList)==0)&&(count($DepistageCancerSeinList)==0)&&
		(count($EvaluationInfirmierList)==0)&&(count($DepistageDiabeteList)==0)&&(count($TroubleCognitifList)==0)
		&& (count($HyperTensionArterielleList)==0)&&(count($DepistageCancerUterusList)==0)&&
		(count($tensionArterielleMoyenneList)==0)&&(count($HyperTensionArterielleList)==0)&&
		(count($CardioVasculaireList)==0)&&(count($HemocultList)==0))
		{
?>  <table border='0' width="500">
    <caption>
    <big><b>Aucun suivi ou dépistage pour ce dossier</b></big>
    </caption>
</table>
<?php 	}


if (count($suiviDiabeteList)!=0)
{?>
  <table border='0' width="500">
    <caption>
    <big><b>Liste des suivis du diabète</b></big>
    </caption>
</table>
<?php require("view/diabete/suivi/historiquesuividiabete.php") ?>
<?php
}

if (count($DepistageCancerSeinList)!=0)
{
	?>
<br><br>
  <table border='0' width="500">
    <caption>
    <big><b>Liste des dépistages du cancer du sein</b></big>
    </caption>
</table>
<?php require("view/cancersein/historiquedepistagesein.php") ?>

<?php
}

if (count($DepistageCancerUterusList)!=0)
{
	?>
<br><br>
  <table border='0' width="500">
    <caption>
    <big><b>Liste des dépistages du cancer du col de l'utérus</b></big>
    </caption>
</table>
<?php require("view/canceruterus/historiquedepistageuterus.php") ?>

<?php
}

if (count($TroubleCognitifList)!=0)
{
	?>
<br><br>
  <table border='0' width="500">
    <caption>
    <big><b>Liste des dépistages des troubles cognitifs</b></big>
    </caption>
</table>
<?php require("view/troublecognitif/historiquetroublecognitif.php") ?>

<?php
}


if (count($DepistageCancerColonList)!=0)
{
	?>
	
<br><br>
  <table border='0' width="500">
    <caption>
    <big><b>Liste des dépistages du cancer du colon</b></big>
    </caption>
</table>
<?php require("view/cancercolon/historiquedepistagecolon.php") ?>

<?php
}

if (count($DepistageDiabeteList)!=0)
{
	?>
<br><br>
  <table border='0' width="500">
    <caption>
    <big><b>Liste des dépistages du diabète</b></big>
    </caption>
</table>
<?php require("view/diabete/depistage/historiquedepistagediabete.php") 
?>

<?php
}


if (count($tensionArterielleMoyenneList)!=0)
{
	?>
<br><br>
  <table border='0' width="500">
    <caption>
    <big><b>Liste des tensions artérielles</b></big>
    </caption>
</table>
<?php require("view/tensionarterielle/historiquetensionarterielle.php")
?>

<?php
}



if (count($HyperTensionArterielleList)!=0)
{
	?>
<br><br>
  <table border='0' width="500">
    <caption>
    <big><b>Liste des suivis HTA</b></big>
    </caption>
</table>
<?php require("view/hypertension/historiquehypertension.php")
?>

<?php
}



if (count($CardioVasculaireList)!=0)
{
	?>
  <br><br>
    <table border='0' width="500">
      <caption>
      <big><b>Liste des suivis RCVA</b></big>
      </caption>
  </table>
<?php 
  if(count($suiviDiabeteList)>0){
  	$suividiab=1;
  }
  require("view/cardiovasculaire/historiquecardiovasculairedepart.php");
?>

<?php
}

/// sevrage
if (count($sevrageTabacList)!=0)
{
  #var_dump($sevrageTabacList);
  ?>
  <br><br>
    <table border='0' width="500">
      <caption>
      <big><b>Liste des sevrages tabac</b></big>
      </caption>
  </table>
<?php 
  if(count($sevrageTabacList)>0){
    $sevragetabac=1;
  }
  require("view/sevragetabac/historiquesevragetabac.php");
?>

<?php
}







if (count($HemocultList)!=0)
{
	?>
<br><br>
  <table border='0' width="500">
    <caption>
    <big><b>Liste des questionnaires Hémoccult</b></big>
    </caption>
</table>
<?php require("view/hemocult/historiquehemocult.php")
?>

<?php
}



if (count($EvaluationInfirmierList)!=0)
{
	?>

<br><br>
  <table border='0' width="500">
    <caption>
    <big><b>Liste des évaluations infirmier</b></big>
    </caption>
</table>
<?php require("view/evaluation/historiqueevaluationinfirmier.php") ?>

    <br /><br />

    <?php
    if (in_array($account->cabinet, $liste_cabs_aut))
    {
        $authorisation_suppression = true;
        ?>

        <br /><br />
        <?php
        require ("view/depistage/historique_depistage_aomi.php");
    }
    ?>

<?php
}?>

