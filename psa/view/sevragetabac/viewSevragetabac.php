<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier; ?>
<?php global $param;?>
<?php global $sevragetabac;?>
<?php global $evaluationInfirmier;?>
<?php global $diagnosticEducatif; ?>
<?php global $liste_historique; ?>

<script type="text/javascript" >
    <?php

    function getIMC($poids,$taille){
        #echo $taille.','.$poids;
        if(is_null($taille) or !is_numeric($taille) or $taille == 0)
            return "La taille n'est pas saisie, L'imc ne peut etre calculée";
        return round($poids/pow($taille/100, 2),1);
    }


    //validatePositiveInteger();
    //validateInput();
    $js = new JSValidation();
    $js->startCheckFunction("validateInput","saveForm");
    //$js->endCheckFunction();

    ?>
</script>
<script type="text/javascript">
    function affiche_detail(element)
    {
        var element = document.getElementById(element);

        if(element.style.display == 'none')
        {
            element.style.display = '';
        }
        else
        {
            element.style.display = 'none';
        }
    }
</script>
<br>

<?php #var_dump($evaluationInfirmer);?>

<?php require("view/common/dossierresume.php");?>


<h1>1- Bilan tabagique</h1>
<a href='javascript://' onclick="ajax_showTooltip_sevrage_tabac('<?php echo $path ?>/controler/ActionControler.php?controlerparams:param:controler=SevrageTabacControler&controlerparams:param:action=AL&controlerparams:param:param1=PLISDOSSTIPFORBILAN&Dossier:dossier:numero=<?php echo $dossier->numero ?>',this);return false">Consultations passées </a><br>
<table border='1' width='700'>
    <tr>
        <td width='300' height='50'><font  style=" border-bottom:solid  ; border-color:green ;" >Tabagisme</font><img OnClick="javascript:window.open('<?php echo $path ?>/view/cardiovasculaire/tabac.html','','width=350,height=200,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Tabac' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></Td>
        <td colspan='2'>
            <font  style=" border-bottom:solid; border-color:green ;" >
                Tabagisme actif : <?php echo strtoupper($sevragetabac->tabac); ?></font><br><br>
            Nbre de paquets-années : <?php echo $sevragetabac->nbrtabac; ?><br/>
            <br />
            Date de démarrage : <?php echo $sevragetabac->ddebut; ?>
            <br />
            Date d'arrêt : <?php echo $sevragetabac->darret; ?></span>
            <br />
            Type tabac consommé : <?php echo($type_tabac[getPropertyValue("sevragetabac:type_tabac")]); ?></span>
        </td>
    </tr>
    <tr>
        <td height='50'>
            Spirom&eacute;trie <!--<?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=spirometrie&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Résultats passés</a>";?>-->
        </td>
        <td colspan='2'>
            <table>
                <tr>
                    <td colspan='2'>Date de la spirom&eacute;trie</td>
                    <td><?php echo $sevragetabac->spirometrie_date; ?></td>
                </tr>
                <tr>
                    <td>CVF &nbsp;</td>
                    <td><?php echo $sevragetabac->spirometrie_CVF; ?> litres<br/></td>

                </tr>
                <tr>
                    <td>VEMS &nbsp;</td>
                    <td><?php echo $sevragetabac->spirometrie_VEMS; ?> litres<br/></td>
                </tr>
                </tr>
                <tr>
                    <td>VEMS/CVF : </td>
                    <td><?php echo $sevragetabac->spirometrie_rapport_VEMS_CVF;?> % </td>
                </tr>

                <tr>
                    <td>DEP &nbsp;</td>
                    <td><?php echo $sevragetabac->spirometrie_DEP; ?> litres par seconde<br/></td>
                </tr>
            </table>
            <br/>
            Spirométrie  : <?php echo($spirometrie_status[getPropertyValue("sevragetabac:spirometrie_status")]); ?><br/>
        </td>

    </tr>


    <tr>
        <td>Test CO</td>
        <td>Date du test CO : <?php echo $sevragetabac->dco_test;?> <br>
            Résultat en PPM : <?php echo $sevragetabac->co_ppm;?><br>
        </td>
    </tr>
</table>
<p>&nbsp;</p>


<h4>Test de Fagerstrom</h4>
<p>Calculez le niveau de d&eacute;pendance &agrave; la cigarette de votre patient en lui posant les six questions suivantes. <br>Cliquez dans la case correspondant &agrave; chacune de ses r&eacute;ponses
</p>
<table border='1' width='700' cellpadding="10">



    <tr>
        <td valign="top"><b>Résultats du test Fagerstrom</b></td>
        <td>
            Score : <?php echo $sevragetabac->fagerstrom;?>
        </td>
    </tr>
    <tr>
        <td valign="top"><b><a href="../view/docs/sevragetabac/Test_Horn.pdf" target="_blank">Test de Horn</a></b>Score de 3 à 15</td>
        <td>
            <table>
                <tr><td>Stimulation : </td><td><?php echo $sevragetabac->horn_stimulation;?></td></tr>
                <tr><td>Plaisir du geste : </td><td><?php echo $sevragetabac->horn_plaisir;?></td></tr>
                <tr><td>Relaxation : </td><td><?php echo $sevragetabac->horn_relaxation;?></td></tr>
                <tr><td>Anxiété-Soutien : </td><td><?php echo $sevragetabac->horn_anxiete;?></td></tr>
                <tr><td>Besoin absolu : </td><td><?php echo $sevragetabac->horn_besoin;?></td></tr>
                <tr><td>Habitude acquise : </td><td><?php echo $sevragetabac->horn_habitude;?></td></tr>
            </table>
            <!--Score : <?php text("id='fagerstrom' size='10' maxlength='6'","sevragetabac:fagerstrom");?>-->
        </td>
    </tr>
    <tr>
        <td valign="top"><b><a href="../view/docs/sevragetabac/Test_HAD.pdf" target="_blank">Test HAD</a></b>Note de 0 à 21</td>
        <td>
            <table widht="80%">
                <tr><td>Anxiété : </td><td align="center"><?php echo $sevragetabac->had_anxiete;?></td></tr>
                <tr><td>Dépression : </td><td align="center"><?php echo $sevragetabac->had_depression;?></td></tr>
            </table>
            <!--Score : <?php text("id='fagerstrom' size='10' maxlength='6'","sevragetabac:fagerstrom");?>-->
        </td>
    </tr>

</table>


<p>&nbsp;</p>

<p>&nbsp;</p>
<h4>Bilan motivationnel</h4>
<table border='1' width='700' cellpadding="10">
    <tr>
        <td valign="top">Sentiment d'efficacité</td>
        <td>
            <?php echo $sevragetabac->echelle_analogique;?>
        </td>
    </tr>
    <tr>
        <td valign="top">Importance du projet</td>
        <td>
            <?php echo $sevragetabac->echelle_confiance;?>
        </td>
    </tr>
    <tr>
        <td valign="top">Stade motivationnel</td>
        <td>
            <?php echo($stade_motivationnel[getPropertyValue("sevragetabac:stade_motivationnel")]); ?>
        </td>
    </tr>

</table>





<br>
<b>Mode de vie</b>
<table border='1' width='700'>

    <tr>
        <td width='300'>Poids  <!--<?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=poids&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Résultats passés</a>";?>--></td>

        <td>
            <?php echo $sevragetabac->poids;?> kg. &nbsp;
            le <?php echo $sevragetabac->dpoids;?>&nbsp;&nbsp;
            Taille : <?php echo $dossier->taille;?><br>
            <table><tr><td id='IMC'>IMC : <?php echo getIMC($sevragetabac->poids,$dossier->taille);?></td></tr></table> </Td>
    </tr>
    <tr>
        <td width='300'>Activité physique (heures par semaine. 2h30=2.5h)<img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>activite.html','','width=250,height=200,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Définition activité physique' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></td>

        <td colspan='2'><?php echo $sevragetabac->activite; ?> h</Td>
    </Tr>
    <tr>
        <td width='300'>Co-addictions (Alcool (>20g/j), cannabis)<img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>alcool.html','','width=350,height=200,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Alcool' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></td>
        <td colspan='2'>
            <?php echo $sevragetabac->alcool; ?></td>
    </tr>
</table>
<br>

<?php #var_dump($evaluationInfirmier);?>

<h1>2- Bilan de consultation</h1>
<table width='850' border="1" cellpadding='3'>
    <tr>
        <td>Degré de satisfaction:</td>
        <td colspan='2'><?php echo($satisfaction[$evaluationInfirmier->degre_satisfaction]); ?></td>
    </tr>
    <tr>
        <td>Durée approximative en minutes ("à 5 minutes près")</td>
        <td><?php echo $evaluationInfirmier->duree; ?></td>
        <td><?php
            if($evaluationInfirmier->consult_domicile==1){ echo 'Consultation à domicile, ';}
            if($evaluationInfirmier->consult_tel==1){ echo 'Consultation au t&eacute;l&eacute;phone, ';}
            if($evaluationInfirmier->consult_collective==1){ echo 'Consultation collective ,';}
            ?></td>
    </tr>
    <tr>
        <td>Type de consultation:</td>
        <td>	 <?php
            $type_consultTab = explode(",",$evaluationInfirmier->type_consultation);
            foreach($type_consultTab as $type){
                #echo $type.'<br>';
                echo($type_consult[$type]).'<br>';
            }
            ?>

        </td>
        <td>Examens réalisés par délégation : <br><br>

            <?php
            if($evaluationInfirmier->hba==1){ echo "Prescription d'examen (s) pour le patient diabétique type 2, ";}//Prescription HbA1c
            if($evaluationInfirmier->exapied==1){ echo "Prescription, réalisation, interprétation examen des pieds, ";}
            if($evaluationInfirmier->monofil==1){ echo "Prescription, réalisation, interprétation examen des pieds et monofilament, ";}
            if($evaluationInfirmier->ecg==1){ echo "Prescription et réalisation d'ECG, ";}
            if($evaluationInfirmier->ecg_seul==1){ echo "Réalisation d'ECG seul - non dérogatoire, ";}//ECG
            // if($EvaluationInfirmier->tension==1){ echo "Tension, ";}
            if($evaluationInfirmier->spirometre==1){ echo "Prescription, réalisation d'une spirométrie, ";}
            if($evaluationInfirmier->spirometre_seul==1){ echo "Réalisation d'une spirométrie seule - non dérogatoire, ";}
            if($evaluationInfirmier->t_cognitif==1){ echo "Prescription et réalisation d'un repérage troubles cognitifs, ";}
            if($evaluationInfirmier->autre==1){ echo "Autre : ".$evaluationInfirmier->prec_autre;}?>
        </td>
    </tr>
    <tr>
        <td valign='top'>Points positifs:</td>
        <td  width='70%' colspan='2'><?php echo nl2br(stripslashes($evaluationInfirmier->points_positifs)); ?></td>
    </tr>
    <tr>
        <td valign='top'>Points à améliorer:</td>
        <td  width='70%' colspan='2'><?php echo nl2br(stripslashes($evaluationInfirmier->points_ameliorations)); ?></td>
    </tr>
</table>


<?php
$number = '3';
#var_dump($sevragetabac);
$diagnostic_educatif = $sevragetabac->diagnostic_educatif;
include ('../view/diagnostic_educatif/view_diagnostic_educatif.php'); ?>


<br><br>


<?php
if (in_array($account->cabinet, $liste_cabs_aut))
    include_once "view/depistage/historique_depistage_aomi.php";
?>

<br><br>

<!--<input type='button' value='Valider la saisie' onclick='validateInput()'>-->
<form action=<?php echo("'$path/controler/ActionControler.php'"); ?> method='post' name='saveForm'>
    <?php hiddenControler("SevrageTabacControler"); ?>
    <?php hiddenAction(ACTION_NEW); ?>
    <?php hiddenParam1(PARAM_EDIT); ?>
    <?php hidden("","dossier:numero");?>
    <?php hidden("","dossier:id"); ?>
    <?php hidden("","dossier:cabinet"); ?>
    <?php hidden("","evaluationInfirmier:date"); ?>
    <?php hidden("","sevragetabac:date"); ?>
    <?php hidden("","sevragetabac:id"); ?>


    <?php customSubmitWithAlert("value='Supprimer ce suivi'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous réellement supprimer ce suivi ?"); ?>
    <input type="submit" value='Modifier la saisie'>

    <br><br>
</form>
