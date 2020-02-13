
<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier; ?>
<?php global $param;?>
<?php global $sevragetabac;?>
<?php global $evaluationInfirmier;?>
<?php global $diagnosticEducatif; ?>
<?php global $last_consult ?>

<?php
global $liste_historique;
global $form_class;
$form_class = "SevrageTabac";
?>


<script type="text/javascript" >
    <?php


    //validatePositiveInteger();
    //validateInput();
    $js = new JSValidation();
    $js->startCheckFunction("validateInput","saveForm");
    //$js->endCheckFunction();

    ?>

</script>

<h2 style="color:#000000">Consultation du  <?php echo $sevragetabac->date;?><br>&nbsp;</h2>

<form action=<?php echo("'$path/controler/ActionControler.php'"); ?> method='post' name='saveForm'>
    <?php hiddenControler("SevrageTabacControler"); ?>
    <?php hiddenAction(ACTION_SAVE); ?>
    <?php hidden("","dossier:numero");?>
    <?php hidden("","dossier:id"); ?>
    <?php hidden("","dossier:cabinet"); ?>
    <?php hidden("","evaluationInfirmier:date"); ?>
    <?php hidden("","evaluationInfirmier:id"); ?>
    <?php hidden("","sevragetabac:id"); ?>
    <?php hidden("","sevragetabac:date"); ?>
    <?php hidden("","sevragetabac:numero"); ?>

    <?php #var_dump($evaluationInfirmier);?>

    <?php require("view/common/dossierresume.php"); #var_dump($sevragetabac);?>



    <h1>1- Bilan tabagique</h1>
    <a href='javascript://' onclick="ajax_showTooltip_sevrage_tabac('<?php echo $path ?>/controler/ActionControler.php?controlerparams:param:controler=SevrageTabacControler&controlerparams:param:action=AL&controlerparams:param:param1=PLISDOSSTIPFORBILAN&Dossier:dossier:numero=<?php echo $dossier->numero ?>',this);return false">Consultations passées </a><br>
    <table border='1' width='700'>
        <tr>
            <td width='300' height='50'><font  style=" border-bottom:solid  ; border-color:green ;" >Tabagisme</font></Td>
            <td colspan='2'>
                <font  style=" border-bottom:solid; border-color:green ;" >
                    <?php radioButton("id='tabac_oui' $color required","sevragetabac:tabac","oui"); ?>Oui &nbsp;
                    <?php radioButton("id='tabac_non' $color required","sevragetabac:tabac","non"); ?>Non &nbsp;
                    <?php radioButton("id='tabac_nsp' $color required","sevragetabac:tabac","nsp"); ?>Nsp</font>&nbsp;&nbsp;&nbsp;&nbsp;
                Nbre de paquets-années  &nbsp;<?php text("id='nbrtabac' size='4' ","sevragetabac:nbrtabac", "");?><img OnClick="javascript:window.open('<?php echo $path ?>/view/cardiovasculaire/paquet-annee.html','','width=350,height=200,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Paquets-annee' border=0 src='<?php echo "$path/view/"?>images/loupe.gif' width="21" height="21" ><br/>
                <br />Année de démarrage
                <?php text("id='darret'  $color size='10' maxlength='4'","sevragetabac:ddebut");?> <span style="font-size:10px;">(aaaa)</span><br>
                Date d'arrêt <?php text("id='darret' onkeyup='formate_date(this)' $color size='10' maxlength='10'","sevragetabac:darret");?> <span style="font-size:10px;">(jj/mm/aaaa)</span>
                <br/><span style="color:red;font-size:0.8em">Attention le format de la date d'arrêt a changé, vérifiez la cohérence de la donnée indiquée.</span><br/><br />
                Type tabac consommé &nbsp;<?php selectv("","sevragetabac:type_tabac",$type_tabac); ?><br/>
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
                        <td><?php text("id='spirometrie_date' onkeyup='formate_date(this)' $color size='10' maxlength='10'","sevragetabac:spirometrie_date");?></td>
                    </tr>
                    <tr>
                        <td>CVF &nbsp;</td>
                        <td><?php text("id='CVF' onkeyup='rapportspiro();' $color size='10' maxlength='6'","sevragetabac:spirometrie_CVF");?> litres<br/></td>
                        <td rowspan='2' style='border:1px solid #919191;padding:5px;'>
                            VEMS/CVF
                            <p><b id='rap_vems_cvf' > <?php if($sevragetabac->spirometrie_rapport_VEMS_CVF!=''){echo '<br>'.$sevragetabac->spirometrie_rapport_VEMS_CVF.'%';}?>
                                </b></p>
                            <input id='rap_vems_cvf_hidden'  type='hidden' name='SevrageTabac:sevragetabac:spirometrie_rapport_VEMS_CVF' value='<?php echo (round(($sevragetabac->spirometrie_VEMS/$sevragetabac->spirometrie_CVF)*10000)/100); ?>'/>
                        </td>
                    </tr>
                    <tr>
                        <td>VEMS &nbsp;</td>
                        <td><?php text("id='VEMS' onkeyup='rapportspiro();' $color size='10' maxlength='6'","sevragetabac:spirometrie_VEMS");?> litres<br/></td>
                    </tr>
                    <tr>
                        <td>DEP &nbsp;</td>
                        <td><?php text("id='DEP' onkeyup='rapportspiro();' $color size='10' maxlength='6'","sevragetabac:spirometrie_DEP");?> litres par seconde<br/></td>
                    </tr>
                </table>
                <br/>
                Spirométrie &nbsp;<?php selectv("","sevragetabac:spirometrie_status",$spirometrie_status); ?><br/>
            </td>

        </tr>
        <script type="text/javascript">
            function rapportspiro(){
                CVF = document.getElementById("CVF").value = document.getElementById("CVF").value.replace(",",".");
                VEMS = document.getElementById("VEMS").value = document.getElementById("VEMS").value.replace(",",".");
                DEP = document.getElementById("DEP").value = document.getElementById("DEP").value.replace(",",".");
                var rap_vems_cvf = Number(VEMS) / Number(CVF);
                rap_vems_cvf = Math.round(rap_vems_cvf*100*100)/100;

                if(isNaN(rap_vems_cvf)) { rap_vems_cvf = 0}
                if(rap_vems_cvf==Number.POSITIVE_INFINITY) { rap_vems_cvf = 0}

                document.getElementById("rap_vems_cvf").innerHTML = rap_vems_cvf + '%';
                document.getElementById("rap_vems_cvf_hidden").value = rap_vems_cvf ;
            }
        </script>


        <tr>
            <td>Test CO</td>
            <td>Date du test CO : <?php text("id='test_co' onkeyup='formate_date(this)'  $color size='10' maxlength='10'","sevragetabac:dco_test");?> <br>
                Résultat en PPM : <?php text("id='test_co_ppm' $color size='10' maxlength='6'","sevragetabac:co_ppm");?><br>
            </td>
        </tr>

    </table>
    <p>&nbsp;</p>


    <h4 id="tests">Tests de dépendance</h4>
    <p>Calculez le niveau de d&eacute;pendance &agrave; la cigarette de votre patient en lui posant les questions des tests ci-dessous. <br>Indiquez les résultats dans les cases correspondantes
    </p>
    <table border='1' width='700' cellpadding="10">


        <tr>
            <td valign="top"><b><a class="fa fa-file-pdf-o fa-2x" aria-hidden="true" href="../view/docs/sevragetabac/Test_Fagerstrom.pdf" target="_blank" title="Télécharger le Test"></a> Test de Fagerstrom</b>
                <br>&nbsp;<a class="testit" href="#tests"><h4><u>Accéder au test en ligne</u></h4></a>
                <div id="testF" style="display:none">
                    <br><table width="400" border="1" cellpadding="5">
                        <tr>
                            <td colspan="2">Répondez aux questions suivantes calculer le résultat du test - <u>ATTENTION seul le score final sera conservé lors de la prochaine visite.</u> </td>

                        <tr>
                            <td valign="top">1. Le matin, combien de temps apr&egrave;s vous être r&eacute;veill&eacute;(e) fumez-vous votre premi&egrave;re cigarette ?</td>
                            <td width="150">
                                <input type="radio" name="q1" value="3" class="rf"> Moins de 5 minutes
                                <br><input type="radio" name="q1" value="2" class="rf"> 6 &agrave; 30 minutes
                                <br><input type="radio" name="q1" value="1" class="rf"> 31 &agrave; 60 minutes
                                <br><input type="radio" name="q1" value="0" class="rf"> Apr&egrave;s 60 minutes

                            </td>
                        </tr>

                        <tr>
                            <td valign="top">2. Trouvez-vous qu'il est difficile de vous abstenir de fumer dans les endroits o&ugrave; c'est interdit ? (ex : cin&eacute;ma, biblioth&egrave;que). </td>
                            <td>
                                <input type="radio" name="q2" value="1" class="rf"> Oui
                                <br><input type="radio" name="q2" value="0" class="rf"> Non
                            </td>
                        </tr>

                        <tr>
                            <td valign="top">3. A quelle cigarette renonceriez-vous plus difficilement ?</td>
                            <td>
                                <input type="radio" name="q3" value="1" class="rf"> La premi&egrave;re
                                <br><input type="radio" name="q3" value="0" class="rf"> Une autre
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">4. Combien de cigarettes fumez-vous par jour ?</td>
                            <td>
                                <input type="radio" name="q4" value="0"> 10 ou moins
                                <br><input type="radio" name="q4" value="1" class="rf"> 11 &agrave; 20
                                <br><input type="radio" name="q4" value="2" class="rf"> 21 &agrave; 30
                                <br><input type="radio" name="q4" value="3" class="rf"> 31 ou plus
                            </td>
                        </tr>

                        <tr>
                            <td valign="top">5. Fumez-vous à intervalles plus rapproch&eacute;s durant les premi&egrave;res heures de la matin&eacute;e que durant le reste de la journ&eacute;e ?</td>
                            <td>
                                <input type="radio" name="q5" value="1" class="rf"> Oui
                                <input type="radio" name="q5" value="0" class="rf"> Non
                            </td>
                        </tr>

                        <tr>
                            <td valign="top">6. Fumez-vous lorsque vous êtes malade au point de devoir rester au lit presque toute la journ&eacute;e ?</td>
                            <td>
                                <input type="radio" name="q6" value="1" class="rf"> Oui
                                <input type="radio" name="q6" value="0" class="rf"> Non
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
            <td>
                Score : <?php text("id='fagerstrom' size='10' maxlength='6'","sevragetabac:fagerstrom");?>
            </td>
        </tr>
        <tr>
            <td valign="top"><b><a class="fa fa-file-pdf-o fa-2x" aria-hidden="true" href="../view/docs/sevragetabac/Test_Horn.pdf" target="_blank" title="Télécharger le Test"></a> Test de Horn</a></b><br>(Score de 3 à 15)</td>
            <td>
                <table>
                    <tr><td>Stimulation</td><td><?php text("id='horn1' size='10' maxlength='6'","sevragetabac:horn_stimulation");?></td></tr>
                    <tr><td>Plaisir du geste</td><td><?php text("id='horn2' size='10' maxlength='6'","sevragetabac:horn_plaisir");?></td></tr>
                    <tr><td>Relaxation</td><td><?php text("id='horn3' size='10' maxlength='6'","sevragetabac:horn_relaxation");?></td></tr>
                    <tr><td>Anxiété-Soutien</td><td><?php text("id='horn4' size='10' maxlength='6'","sevragetabac:horn_anxiete");?></td></tr>
                    <tr><td>Besoin absolu</td><td><?php text("id='horn5' size='10' maxlength='6'","sevragetabac:horn_besoin");?></td></tr>
                    <tr><td>Habitude acquise</td><td><?php text("id='horn6' size='10' maxlength='6'","sevragetabac:horn_habitude");?></td></tr>
                </table>
                <!--Score : <?php text("id='fagerstrom' size='10' maxlength='6'","sevragetabac:fagerstrom");?>-->
            </td>
        </tr>
        <tr>
            <td valign="top"><b><a class="fa fa-file-pdf-o fa-2x" aria-hidden="true" href="../view/docs/sevragetabac/Test_HAD.pdf" target="_blank" title="Télécharger le Test"></a> Test HAD</a></b><br>(Note de 0 à 21)</td>
            <td>
                <table>
                    <tr><td>Anxiété</td><td><?php text("id='had1' size='10' maxlength='6'","sevragetabac:had_anxiete");?></td></tr>
                    <tr><td>Dépression</td><td><?php text("id='had2' size='10' maxlength='6'","sevragetabac:had_depression");?></td></tr>
                </table>
                <!--Score : <?php text("id='fagerstrom' size='10' maxlength='6'","sevragetabac:fagerstrom");?>-->
            </td>
        </tr>

    </table>


    <p>&nbsp;</p>
    <h4>Bilan motivationnel</h4>
    <table border='1' width='700' cellpadding="10">
        <tr>
            <td valign="top"><a href="../view/docs/sevragetabac/Echelle_evaluation_sentiment_efficacite.pdf" target="_blank" class="fa fa-file-pdf-o fa-2x" title="Télécharger le support"></a> Sentiment d'efficacité <br>(Echelle de 0 à 10)</td>
            <td>
                <?php text("id='echelle_analogique' size='10' maxlength='6'","sevragetabac:echelle_analogique");?>
            </td>
        </tr>
        <tr>
            <td valign="top"><a href="../view/docs/sevragetabac/Echelle_evaluation_motivation.pdf" target="_blank" class="fa fa-file-pdf-o fa-2x"></a> Importance du projet <br>(Echelle de 0 à 10)</td>
            <td>
                <?php text("id='echelle_confiance' size='10' maxlength='6'","sevragetabac:echelle_confiance");?>
            </td>
        </tr>
        <tr>
            <td valign="top">Stade motivationnel</td>
            <td>
                <?php selectv("required","sevragetabac:stade_motivationnel",$stade_motivationnel); ?>
            </td>
        </tr>

    </table>





    <br>
    <b>Mode de vie</b>
    <table border='1' width='700'>

        <tr>
            <td width='300'>Poids  <!--<?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=poids&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Résultats passés</a>";?>--></td>

            <td>
                <?php text("id='poids' $color  size='4' maxlength='4'","sevragetabac:poids");?>kg. &nbsp;
                le <?php text("id='dpoids' $color size='10' maxlength='10' onkeyup='formate_date(this)'","sevragetabac:dpoids");?>(jj/mm/aaaa)&nbsp;&nbsp;
                Taille : <span id="taille"><?php echo $dossier->taille;?></span><br>
                <table>
                    <tr>
                        <?php  $cal_taille = ($dossier->taille/100) * ($dossier->taille/100);
                        $imc = $sevragetabac->poids / $cal_taille;
                        ?>
                        <td id='IMC'>IMC : <span id="imc"><?php echo round($imc,2);?></span></td>
                    </tr>
                </table>
            </Td>
        </tr>
        <tr>
            <td width='300'>Activité physique (heures par semaine. 2h30=2.5h)<img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>activite.html','','width=250,height=200,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Définition activité physique' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></td>

            <td colspan='2'><?php text("id='activite' $color onchange='remplacevirgule2(\"activite\")' size='4' maxlength='4'","sevragetabac:activite");?>h</Td>
        </Tr>
        <tr>
            <td width='300'>Co-addictions (Alcool (>20g/j), cannabis)<img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>alcool.html','','width=350,height=200,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Alcool' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></td>
            <td colspan='2'>
                <?php radioButton("id='alcool_oui' $color ","sevragetabac:alcool","oui"); ?>Oui &nbsp;&nbsp;
                <?php radioButton("id='alcool_non' $color ","sevragetabac:alcool","non"); ?>Non &nbsp;&nbsp;
                <?php radioButton("id='alcool_nsp' $color ","sevragetabac:alcool","nsp"); ?>Nsp</td>
        </tr>
    </table>
    <br>


    <?php #var_dump($evaluationInfirmier->type_consultation);?>


    <h1>2- Faire le bilan de la consultation</h1>
    <?php echo "<a href='javascript://' onclick=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=EvaluationInfirmierControler&controlerparams:param:action=AL&controlerparams:param:param1=PLISDOSSTIP&Dossier:dossier:numero=$dossier->numero',this);return false\">Consultations passées </a><br>";
    ?>
    <table border="1" cellpadding='3'>
        <input type="hidden" name="evaluationInfirmier:evaluationInfirmier:id_utilisateur" value="<?= $_SESSION['id.login']; ?>">
        <input type="hidden" name="evaluationInfirmier:evaluationInfirmier:id_cabinet" value="<?= $_SESSION['cabinet']; ?>">
        <tr>
            <td>Degré de satisfaction:</td>
            <td colspan='2'><?php selectv("","evaluationInfirmier:degre_satisfaction",$satisfaction); ?></td>
        </tr>
        <tr>
            <td>Durée approximative en minutes ("à 5 minutes près")</td>
            <td><?php text("size='4'  required","evaluationInfirmier:duree"); ?></td>
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
            <td><?php selectv("multiple size='12'","evaluationInfirmier:type_consultation",$type_consult); ?></td>
            <td>A chaque fois qu'une action de nature dérogatoire est effectuée, au titre du protocole de coopération ASALEE,
                agrée par la Haute Autorité de Santé le 22 mars 2012, et sous réserve de l'autorisation de l'Agence Régionale de Santé
                et de la notification de l'équipe ASALEE (médecins-infirmières), <br>
                cocher la ou les actions concernées.<br><br>

                <?php
                checkBox("","evaluationInfirmier:hba","1"); echo "Prescription d'examen(s) pour le patient diabétique type 2 <br>";
                checkBox("","evaluationInfirmier:exapied","1"); echo "Prescription, réalisation, interprétation examen des pieds<br>";
                checkBox("","evaluationInfirmier:monofil","1"); echo "Prescription, réalisation, interprétation examen des pieds et monofilament<br>";
                checkBox("","evaluationInfirmier:ecg","1"); echo "Prescription et réalisation d'ECG<br>";
                checkBox("","evaluationInfirmier:ecg_seul","1"); echo "Réalisation d'ECG seul à non dérogatoire<br>";
                checkBox("","evaluationInfirmier:spirometre","1"); echo "Prescription, réalisation d'une spirométrie <br>";
                checkBox("","evaluationInfirmier:spirometre_seul","1"); echo "Réalisation d'une spirométrie seule à non dérogatoire <br>";
                checkBox("","evaluationInfirmier:t_cognitif","1"); echo "Prescription et réalisation d'un repérage troubles cognitifs <br>";
                /*checkBox("","evaluationInfirmier:tension","1"); echo "Tension <br>";*/
                checkBox("","evaluationInfirmier:autre","1"); echo "Autre. Précisez : ";
                text("","evaluationInfirmier:prec_autre"); echo "<br>";
                ?>
            </td>
        </tr>
        <tr>
            <td valign='top'>Points positifs :
                <div style="font-size:9px">
                    Besoins du patient pris en compte<br>
                    Objectifs prévus atteints<br>
                    Objectifs  non  prévus atteints<br>
                    Outil(s), support (s), méthodes  utilisés </div></td>
            <td  width='70%' colspan='2'><?php textArea("rows=\"8\" cols=\"60\"","evaluationInfirmier:points_positifs"); ?></td>
        </tr>
        <tr>
            <td valign='top'>Points à améliorer :
                <div style="font-size:9px">
                    Besoins du patient non pris en compte<br>
                    Objectifs prévus non  atteints<br>
                    Objectifs peréus à atteindre<br>
                    Méthodes envisagées prochaine séance</div></td>
            <td  width='70%' colspan='2'><?php textArea("rows=\"8\" cols=\"60\"","evaluationInfirmier:points_ameliorations"); ?></td>
        </tr>
    </table>


    <?php
    $number = '3';
    #var_dump($sevragetabac);
    $diagnostic_educatif = $sevragetabac->diagnostic_educatif;
    include ('../view/diagnostic_educatif/new_diagnostic_educatif.php'); ?>


    <br>

    <?php
    if (in_array($account->cabinet, $liste_cabs_aut))
        require("view/depistage/depistage_aomi.php");
    ?>

    <br>

    <!--<input type='button' value='Valider la saisie' onclick='validateInput()'>-->
    <input type='submit' value='Valider la saisie'>
    <input type='reset' value='Recommencer'>

    <br><br>
</form>

</div>

</form>


<script language="javascript">
    $(".testit").click( function(){
        $('#testF').toggle();
    });

    // deroule du test
    $('.rf').click(function (){

        t1 = $('input[type=radio][name=q1]:checked').attr('value');
        t2 = $('input[type=radio][name=q2]:checked').attr('value');
        t3 = $('input[type=radio][name=q3]:checked').attr('value');
        t4 = $('input[type=radio][name=q4]:checked').attr('value');
        t5 = $('input[type=radio][name=q5]:checked').attr('value');
        t6 = $('input[type=radio][name=q6]:checked').attr('value');
        if(isNaN(t1)){t1=0;}
        if(isNaN(t2)){t2=0;}
        if(isNaN(t3)){t3=0;}
        if(isNaN(t4)){t4=0;}
        if(isNaN(t5)){t5=0;}
        if(isNaN(t6)){t6=0;}
        total = parseInt(t1) + parseInt(t2) + parseInt(t3) + parseInt(t4) + parseInt(t5) + parseInt(t6);
        //alert(total);
        $("#fagerstrom").val(total);

    });


    // calcul IMC
    $("#poids").keyup( function(){
        var taille = $("#taille").html();
        var poids = $("#poids").val();
        var imc = poids / ((taille/100)*(taille/100));
        $("#imc").html(imc.toFixed(2));
    });

    //Ajout de la fonction pour afficher et masquer un bloc
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


</script>

