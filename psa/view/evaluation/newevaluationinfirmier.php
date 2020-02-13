<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php
session_start();
?>
<?php global $account;?>
<?php global $dossier ?>
<?php global $evaluationInfirmier ?>
<?php global $dernierExam;
if($dernierExam!=""){
    $evaluationInfirmier->aspects_limitant=$dernierExam->aspects_limitant;
    $evaluationInfirmier->aspects_facilitant=$dernierExam->aspects_facilitant;
    $evaluationInfirmier->objectifs_patient=$dernierExam->objectifs_patient;
}
?>
<?php global $currentObjectName;
$currentObjectName="evaluationInfirmier";
// print_r($EvaluationInfirmier);	

?>
<?php global $EvalContinue;?>

<script type="text/javascript" >


    <?php
    validateDate();
    dateInRange();
    compareDates();
    $js = new JSValidation();
    $js->startCheckFunction("validateInput","saveForm");
    $js->dateInRange("dossier:dnaiss","Date de naissance");
    ?>
    var submitContinue = 1;

    submitContinue = checkContinue("aForm");

    if(submitContinue==0){
        submitOk=0;
    }
    <?php
    $js->endCheckFunction();


    ?>
</script>


<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="saveForm">
    <?php hiddenControler("EvaluationInfirmierControler"); ?>
    <?php hiddenAction(ACTION_SAVE); ?>
    <?php hidden("","evaluationInfirmier:date");?>
    <?php hidden("","dossier:numero");?>
    <?php hidden("","dossier:id");?>
    <?php hidden("","dossier:cabinet");?>
    <?php hidden("","Epices:id");?>

    <table border='0'><tr><td>
                <?php require("view/common/dossierresume_modif.php");?>
            </td><td width='20'>&nbsp;</td>
            <td>Ce formulaire permet de saisir un compte rendu suite � une consultation, quel que soit le type de consultation.<br><br>
                Il permet de saisir un diagnostic �ducatif ainsi qu'une �valuation continue d'�ducation.<br><br>
            </td></Tr>
    </table>

    <h1>1- Faire le bilan de la consultation</h1>
    <?php echo "<a href='javascript://' onclick=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=EvaluationInfirmierControler&controlerparams:param:action=AL&controlerparams:param:param1=PLISDOSSTIP&Dossier:dossier:numero=$dossier->numero',this);return false\">Consultations pass�es </a><br>";
    ?>
    <table border="1" cellpadding='3'>
        <input type="hidden" name="evaluationInfirmier:evaluationInfirmier:id_utilisateur" value="<?= $_SESSION['id.login']; ?>">
        <input type="hidden" name="evaluationInfirmier:evaluationInfirmier:id_cabinet" value="<?= $account->cabinet; ?>">
        <tr>
            <td>Degr� de satisfaction:</td>
            <td colspan='2'><?php selectv("","evaluationInfirmier:degre_satisfaction",$satisfaction); ?></td>
        </tr>
        <tr>
            <td>Dur�e approximative en minutes ("� 5 minutes pr�s")</td>
            <td><?php text("size='4'","evaluationInfirmier:duree"); ?></td>
            <td>
                <table>
                    <tr>
                        <td>En cas de consultation &agrave;<br/>domicile, cocher la case:<br/>
                            <?php checkBox("","evaluationInfirmier:consult_domicile","1"); ?>
                        </td>
                        <td width="10">&nbsp;&nbsp;&nbsp;</td>
                        <td>En cas de consultation <br/>t&eacute;l&eacute;phonique, cocher la case:<br/>
                            <?php checkBox("","evaluationInfirmier:consult_tel","1"); ?>
                        </td>
                        <td width="10">&nbsp;&nbsp;&nbsp;</td>
                        <td>En cas de consultation <br/>collective, cocher la case:<br/>
                            <?php checkBox("","evaluationInfirmier:consult_collective","1"); ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>Type de consultation:</td>
            <td><?php selectv("multiple size='14'","evaluationInfirmier:type_consultation",$type_consult); ?></td>
            <td>A chaque fois qu�une action de nature d�rogatoire est effectu�e, au titre du protocole de coop�ration ASALEE,
                agr�� par la Haute Autorit� de Sant� le 22 mars 2012, et sous r�serve de l�autorisation de l�Agence R�gionale de Sant�
                et de la notification de l��quipe ASALEE (m�decins-infirmi�res), <br>
                cocher la ou les actions concern�es.<br><br>

                <?php
                checkBox("","evaluationInfirmier:hba","1"); echo "Prescription d�examen(s) pour le patient diab�tique type 2 <br>";
                checkBox("","evaluationInfirmier:exapied","1"); echo "Prescription, r�alisation, interpr�tation examen des pieds<br>";
                checkBox("","evaluationInfirmier:monofil","1"); echo "Prescription, r�alisation, interpr�tation examen des pieds et monofilament<br>";
                checkBox("","evaluationInfirmier:ecg","1"); echo "Prescription et r�alisation d�ECG<br>";
                checkBox("","evaluationInfirmier:ecg_seul","1"); echo "R�alisation d�ECG seul � non d�rogatoire<br>";
                checkBox("","evaluationInfirmier:spirometre","1"); echo "Prescription, r�alisation d�une spirom�trie <br>";
                checkBox("","evaluationInfirmier:spirometre_seul","1"); echo "R�alisation d�une spirom�trie seule � non d�rogatoire <br>";
                checkBox("","evaluationInfirmier:t_cognitif","1"); echo "Prescription et r�alisation d�un rep�rage troubles cognitifs <br>";
                /*checkBox("","evaluationInfirmier:tension","1"); echo "Tension <br>";*/
                checkBox("","evaluationInfirmier:autre","1"); echo "Autre. Pr�cisez : ";
                text("","evaluationInfirmier:prec_autre"); echo "<br>";
                ?>
            </td>
        </tr>
        <tr>
            <td valign='top'>Points positifs :
                <div style="font-size:9px">
                    Besoins du patient pris en compte<br>
                    Objectifs pr�vus atteints<br>
                    Objectifs  non  pr�vus atteints<br>
                    Outil(s), support (s), m�thodes  utilis�s </div></td>
            <td  width='70%' colspan='2'><?php textArea("rows=\"8\" cols=\"60\"","evaluationInfirmier:points_positifs"); ?></td>
        </tr>
        <tr>
            <td valign='top'>Points � am�liorer :
                <div style="font-size:9px">
                    Besoins du patient non pris en compte<br>
                    Objectifs pr�vus non  atteints<br>
                    Objectifs per�us � atteindre<br>
                    M�thodes envisag�es prochaine s�ance</div></td>
            <td  width='70%' colspan='2'><?php textArea("rows=\"8\" cols=\"60\"","evaluationInfirmier:points_ameliorations"); ?></td>
        </tr>
    </table>
    <br>

    <?php
    $item=2;
    require("view/common/epices.php");?>

    <?php
    $item=3;
    require("view/common/diag_educ.php");?>
    <?php
    $item=4;
    require("view/common/eval_continue.php");?>

    <input type='button' value='Valider la saisie' onclick='validateInput()'>
    <input type='reset' value='Recommencer'>
    <br><br>
</form>

