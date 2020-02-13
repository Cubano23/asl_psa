<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $CardioVasculaireDepart; #echo '<pre>', print_r($CardioVasculaireDepart); echo '</pre>';?>
<?php global $AutreConsultCardio; ?>
<?php global $EvaluationInfirmier; ?>
<?php global $rowsList;?>
<?php global $complement;?>
<?php global $autre_proto;?>
<?php //global $suividiab;?>
<?php global $EvalContinue;?>
<?php //global $poids;?>
<?php //global $spirometrie;?>
<?php //global $systole ?>
<?php //global $diastole ?>
<?php //global $type_tension ?>
<?php

// $liste_exam=array("Chol", "triglycerides", "creat", "kaliemie",
// 				  "proteinurie", "hematurie", "fond", "ECG",
// 				  "pouls", "glycemie", "HDL", "LDL");

// foreach($liste_exam as $exam){
// 	global $$exam;
// }
?>

<?php global $ListConsult;?>
<?php global $currentObjectName;
$currentObjectName="AutreConsultCardio";?>

<script language="javascript">

    function remplacevirgule(valeur){
        return valeur.replace(",",".");
    }

    function remplacevirgule2(valeur){
        donnee=document.getElementById(valeur);
        donnee.value=donnee.value.replace(",",".");
        return true;
    }

    function update_date(date_exam, exam){
        remplacevirgule2(exam);

        dChol=document.getElementById("dChol");

        if((dChol.value!="")&&(document.getElementById(date_exam).value!="")){
            document.getElementById(date_exam).value=dChol.value;
        }
    }

    function computeCleanrance(sexe,age){
        var clearance = document.getElementById("clearance");
        var poids = document.getElementById("poids").value;
        var creatininemie = document.getElementById("Creat").value;
        var clearanceVal;
        var objRegExp  =  /(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)/

        creatininemie=remplacevirgule(creatininemie);
        document.getElementById("Creat").value=creatininemie;

        if(creatininemie == "0" || !objRegExp.test(creatininemie)){
            clearanceVal = "";
        }
        else{
            clearanceVal = (140-parseInt(age))*parseInt(poids)/(7.2*parseInt(creatininemie));
            if(sexe == "F") clearanceVal *= 0.85;
            if(isNaN(clearanceVal)) clearanceVal = "";
        }

        clearance.innerHTML = Math.round(clearanceVal) + " ml/mn";
    }

    function displayIMC(taille,poids){
        if(taille == 0){
            document.getElementById("IMC").innerHTML = "&nbsp;La taille est invalide IMC ne peut etre calculée";
        }
        else{
            poidsValue = parseFloat(document.getElementById("poids").value);
            if(isNaN(poidsValue) || poidsValue <30 || poidsValue >200){
                document.getElementById("IMC").innerHTML = "IMC : ";
                return;
            }
            else{
                var imc = Math.round(poidsValue/Math.pow(taille/100, 2));
                obj = document.getElementById("IMC").innerHTML="IMC : "+imc;
            }
        }

        if(document.getElementById("Creat").value!=""){
            computeCleanrance("<?php echo $dossier->sexe; ?>","<?php echo $dossier->getAge(); ?>");
        }
    }

    function arret_tabac(){
        formate_date(document.getElementById("darret"));
        var tabac_oui=document.getElementById("tabac_oui");
        var tabac_non=document.getElementById("tabac_non");
        var date_tabac=document.getElementById("darret");

        if(date_tabac.value.length==10){
            tab_tabac=date_tabac.value.split('/');
            date_jour=new Date();

            tab_tabac[2]=parseInt(tab_tabac[2])+3;
            tab_tabac[1]=parseInt(tab_tabac[1])-1;

            date_tabac3=new Date(tab_tabac[2], tab_tabac[1], tab_tabac[0]);

            if(date_tabac3.getTime()>date_jour.getTime()){
                tabac_oui.checked=true;
            }
            else{
                tabac_non.checked=true;
            }
        }

    }

    function date_spiro(){
        formate_date(document.getElementById("spirometrie_date"));
        var date_spi=document.getElementById("spirometrie_date");

        if(date_spi.value.length==10){
            tab_spi=date_spi.value.split('/');
            date_jour=new Date();

            tab_spi[2]=parseInt(tab_spi[2])+3;
            tab_spi[1]=parseInt(tab_spi[1])-1;

            date_spi3=new Date(tab_spi[2], tab_spi[1], tab_spi[0]);
        }
    }

    function display_hypertenseur(){
        var tab_hypertenseur1=document.getElementById("tab_hypertenseur1");
        var tab_hypertenseur2=document.getElementById("tab_hypertenseur2");
        var hypertenseur_oui=document.getElementById("hypertenseur_oui");
        var hypertenseur_non=document.getElementById("hypertenseur_non");
        var hypertenseur_nsp=document.getElementById("hypertenseur_nsp");
        var automesure_oui=document.getElementById("automesure_oui");
        var automesure_non=document.getElementById("automesure_non");
        var automesure_nsp=document.getElementById("automesure_nsp");
        var diuretique_oui=document.getElementById("diuretique_oui");
        var diuretique_non=document.getElementById("diuretique_non");
        var diuretique_nsp=document.getElementById("diuretique_nsp");

        if(hypertenseur_oui.checked==true){
            tab_hypertenseur1.style.display='';
            tab_hypertenseur2.style.display='';
        }
        if(hypertenseur_non.checked==true){
            tab_hypertenseur1.style.display='none';
            tab_hypertenseur2.style.display='none';
            automesure_oui.checked=false;
            automesure_non.checked=false;
            automesure_nsp.checked=false;
            diuretique_oui.checked=false;
            diuretique_non.checked=false;
            diuretique_nsp.checked=false;
        }
        if(hypertenseur_nsp.checked==true){
            tab_hypertenseur1.style.display='none';
            tab_hypertenseur2.style.display='none';
            automesure_oui.checked=false;
            automesure_non.checked=false;
            automesure_nsp.checked=false;
            diuretique_oui.checked=false;
            diuretique_non.checked=false;
            diuretique_nsp.checked=false;
        }

    }

    function calcul_hta(){
        var hta_oui=document.getElementById('HTA_oui');
        var hta_non=document.getElementById('HTA_non');
        var syst=document.getElementById("syst");
        var dias=document.getElementById("dias");

        if((syst.value>140)||(dias.value>90)){
            hta_oui.checked=true;
        }
        else{
            hta_non.checked=true;
        }

        display_complementhta();
    }

    function display_complementhta(){
        var hta_oui=document.getElementById("HTA_oui");
        var hta_non=document.getElementById("HTA_non");
        var info_complementaire=document.getElementById("info_complementaire");

        if(hta_oui.checked==true){
            info_complementaire.style.display='';
        }
        if(hta_non.checked==true){
            info_complementaire.style.display='none';
        }
    }

    function update_surcharge(){
        var ventricule_oui=document.getElementById("surcharge_ventricule_oui");
        var ventricule_non=document.getElementById("surcharge_ventricule_non");
        var sokolov=document.getElementById("sokolov");

        sokolov.value=remplacevirgule(sokolov.value);


        if(sokolov.value>35){
            ventricule_oui.checked=true;
        }
        else{
            ventricule_non.checked=true;
        }
    }

    function calcul_rcva(diab, sexe, age)
    {
        var tabacoui=document.getElementById("tabac_oui");
        var tabacnon=document.getElementById("tabac_non");
        var tension=document.getElementById("syst");
        var choltot=document.getElementById("chol");
        var hdl=document.getElementById("HDL");
        var ventriculeoui=document.getElementById("HVG_oui");
        var ventriculenon=document.getElementById("HVG_non");
        var surcharge_ventricule_oui=document.getElementById("surcharge_ventricule_oui");
        var surcharge_ventricule_non=document.getElementById("surcharge_ventricule_non");
//		var horizon=document.getElementById("horizon");
        var rcva=document.getElementById("rcva");

        var ok='1';

        if((!tabacoui.checked)&&(!tabacnon.checked)){
            alert("Veuillez préciser si la personne fume");
            ok="0";
        }
        if(tension.value==''){
            alert("Veuillez saisir la tension");
            ok="0";
        }
        if(choltot.value==''){
            alert("Veuillez saisir la valeur du cholestérol total");
            ok="0";
        }
        if(hdl.value==''){
            alert("Veuillez saisir la valeur du HDL");
            ok="0";
        }
        if((!ventriculeoui.checked)&&(!ventriculenon.checked)&&(!surcharge_ventricule_oui.checked)&&(!surcharge_ventricule_non.checked)){
            alert("Veuillez préciser si la personne a une hypertrophie ventriculaire gauche (ou surcharge ventriculaire gauche)");
            ok="0";
        }

        /*		if(horizon.value==''){
                    alert("Veuillez préciser à quel horizon vous souhaitez calculer le risque");
                    ok="0";
                }
        */
        if(ok=='0'){
            return false;
        }



        var e1 = -0.9119;
        var e2 = -0.2767;
        var e3 = -0.7181;
        var e4 = -0.5865;
        var l = 11.1122;
        var m0 = 4.4181 ;
        var s0 = -0.3155 ;
        var s1 = -0.2784;
        var c1 = -1.4792 ;
        var c2 = -0.1759 ;
        var d1 = -5.8549;
        var d2 = 1.8515 ;
        var d3 = -0.3758 ;
        var horizon=10;

        var pas=tension.value;
        var tabac=0;
        var hvg=0;
        var chol=choltot.value;
        var HDL=hdl.value;

        if(tabacoui.checked){
            tabac=1;
        }
        if((!ventriculeoui.checked)&&(!ventriculenon.checked)&&(surcharge_ventricule_oui.checked)){
            hvg=1;
        }
        if(ventriculeoui.checked){
            hvg=1;
        }

        var a = l + e1*Math.log(pas) + e2*tabac + e3*Math.log(chol/HDL) + e4*hvg;

        if(sexe=="M"){
            var m = a + c1*Math.log (age) + c2*diab;
        }
        if(sexe=='F'){
            var m = a + d1 + d2*Math.pow((Math.log (age/74)), 2) + d3*diab;
        }

        var m_calc = m0 + m;
        var s = Math.exp(s0 + s1*m);

        var u = (Math.log (horizon) - m_calc ) / s ;

        var pt = 1- Math.exp(-Math.exp(u));

        rcva.innerHTML=Math.round(pt*10000, 2)/100+"%";

    }

</script>

<script type="text/javascript" >

    function validDateValuePair(date,value,dateLabel,valueLabel){
        if(value == false) value="";
        if(value == true) value ="true";
        if(date.length==0 && value.length== 0){
            return -1;
        }

        if(date.length!=0 && value.length== 0){
            alert("Entrer une valeur pour "+dateLabel);
            return 0;
        }
        if(date.length==0 && value.length!= 0){
            alert("Entrer une date pour "+valueLabel);
            return 0;
        }

        return 1;
    }

    <?php
    compareDates();
    dateInRange();
    validateDate();
    validatePositiveNumeric();
    validateNumeric();

    $js = new JSValidation();

    $js->startCheckFunction("validateInput","saveForm");

    $js->endCheckFunction();
    ?>

</script>

<?php
$hypolemiantArray = array("Aucun"=>"Aucun",
    "Atorvastatine"=>"Atorvastatine",
    "atorvastatine_ezetimibe" => "Atorvastatine, Ezetimibe",
    "Benfluorex"=>"Benfluorex",
    "Bezafibrate"=>"Bezafibrate",
    "Cholestyramine"=>"Cholestyramine",
    "Ciprofibrate"=>"Ciprofibrate",
    "Colestipol"=>"Colestipol",
    "Ezetimibe"=>"Ezetimibe",
    "Fenofibrate"=>"Fenofibrate",
    "Fluvastatine"=>"Fluvastatine",
    "Gemfibrozil"=>"Gemfibrozil",
    "Pravastatine"=>"Pravastatine",
    "Rosuvastatine"=>"Rosuvastatine",
    "Simvastatine"=>"Simvastatine",
    "Simvastatine_ezetimibe"=>"Simvastatine + ezetimibe",
    "Tiadenol"=>"Tiadenol"); ?>

<form action=<?php echo("'$path/controler/ActionControler.php'"); ?> method='post' name='saveForm'>
    <?php hiddenControler("AutreConsultCardioControler"); ?>
    <?php hiddenAction(ACTION_SAVE); ?>
    <?php hidden("","dossier:numero");?>
    <?php hidden("","AutreConsultCardio:date");?>
    <?php hidden("","Epices:id");?>


    Ce formulaire permet d'assurer le suivi éducatif dans le protocole RCVA.<br><br>
    Il s'appuie sur le diagnostic éducatif d'entrée dans le protocole RCVA et sur les données les plus récentes du patient (poids, résultats d'examens, etc...)
    permettant de calculer son Risque Cardio-Vasculaire Absolu.<br><br>
    Il est également possible de renseigner ces données directement au cours de la consultation. <br><br>
    Il est également possible d'y visualiser l'historique des données (poids, examens, RCV, etc...). <br><br>
    <table border='0'><tr><td>
                <?php require("view/common/dossierresume_cardio.php");?>
            </td><td width='20'>&nbsp;</td><td>
                Par défaut sont réaffichées toutes les valeurs de la dernière consultation de suivi ou première
                consultation le cas échéant.<br><br>

                <font style='color:orange'>Figurent en orange, les données arrivées à échéance dans le suivi courant d'un patient à la date du jour.</font><br><br>

                <font  style=" border-bottom:solid  ; border-color:green ;   " >Sont soulignées en vert, les zones utilisées dans le calcul du Risque Cardio-Vasculaire Absolu.</font>
            </td></tr></table>

    <?php
    $item=1;
    require("view/common/diag_educ.php");?>
    <?php
    $item=2;
    require("view/common/eval_continue.php");?>


    <h1>3- Voir les difficultés et les progrès par rapport aux objectifs et fixer de nouveaux objectifs</h1>
    <table border='1'>
        <tr>
            <td width='150'><b>Objectifs</b></td>
            <td><b>Consultations précédentes</b></td>
            <td><b>Progrès / difficultés</b></td>
            <td><b>Nouveaux objectifs</b></td>
        </tr>
        <tr>
            <td width='150'>Poids <img OnClick="javascript:window.open('<?php echo $path;?>/view/cardiovasculaire/objectif_poids.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt="Détail de l'objectif" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></Td>
            <td> <?php
                foreach($ListConsult as $date=>$date_affiche){
                    if($date_affiche!=$AutreConsultCardio->date){
                        echo "$date_affiche <a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=AutreConsultCardioControler&controlerparams:param:action=ACE&type_exam=poids&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Détail</a><br>";
                    }
                }
                ?>

            </td>
            <td><?php textarea("id='progres_poids' cols='25' rows='4' ","AutreConsultCardio:progres_poids");?></Td>
            <td><?php text("id='obj_poids' maxlength='255'","AutreConsultCardio:obj_poids");?></Td>
        </tr>
        <tr>
            <td width='150'>Alcool <img OnClick="javascript:window.open('<?php echo $path;?>/view/cardiovasculaire/objectif_alcool.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt="Détail de l'objectif" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31">
            </td>
            <td> <?php
                foreach($ListConsult as $date=>$date_affiche){
                    if($date_affiche!=$AutreConsultCardio->date){
                        echo "$date_affiche <a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=AutreConsultCardioControler&controlerparams:param:action=ACE&AutreConsultCardio:AutreConsultCardio:type_exam=alcool&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Détail</a><br>";
                    }
                }
                ?>

            </td>
            <td><?php textarea("id='progres_alcool' cols='25' rows='4' ","AutreConsultCardio:progres_alcool");?></Td>
            <td><?php text("id='obj_alcool' maxlength='255'","AutreConsultCardio:obj_alcool");?></Td>
        </Tr>
        <tr>
            <td width='150'>Tabac <img OnClick="javascript:window.open('<?php echo $path;?>/view/cardiovasculaire/objectif_tabac.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt="Détail de l'objectif" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31">
            </Td>
            <td> <?php
                foreach($ListConsult as $date=>$date_affiche){
                    if($date_affiche!=$AutreConsultCardio->date){
                        echo "$date_affiche <a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=AutreConsultCardioControler&controlerparams:param:action=ACE&AutreConsultCardio:AutreConsultCardio:type_exam=tabac&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Détail</a><br>";
                    }
                }
                ?>

            </td>
            <td><?php textarea("id='progres_tabac' cols='25' rows='4' ","AutreConsultCardio:progres_tabac");?></Td>
            <td><?php text("id='obj_tabac' maxlength='255'","AutreConsultCardio:obj_tabac");?></Td>
        </Tr>
        <tr>
            <td width='150'>Tension <img OnClick="javascript:window.open('<?php echo $path;?>/view/cardiovasculaire/objectif_tension.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt="Détail de l'objectif" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31">
            </td>
            <td> <?php
                foreach($ListConsult as $date=>$date_affiche){
                    if($date_affiche!=$AutreConsultCardio->date){
                        echo "$date_affiche <a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=AutreConsultCardioControler&controlerparams:param:action=ACE&AutreConsultCardio:AutreConsultCardio:type_exam=tension&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Détail</a><br>";
                    }
                }
                ?>

            </td>
            <td><?php textarea("id='progres_tension' cols='25' rows='4' ","AutreConsultCardio:progres_tension");?></Td>
            <td><?php text("id='obj_tension' maxlength='255'","AutreConsultCardio:obj_tension");?></Td>
        </tr>
    </table>
    <br>

    <h1>4- Ajustez les conseils prodigués</h1>
    <table border='1'>
        <tr>
            <td><b>Conseils prodigués</b></td>
            <td><b>Consultations précédentes</b></td>
            <td><b>Documents remis</b></Td>
            <td><b>Commentaire</b></td>
        </tr>
        <tr>
            <td width='300'>Consommation de sel</td>
            <td> <?php
                foreach($ListConsult as $date=>$date_affiche){
                    if($date_affiche!=$AutreConsultCardio->date){
                        echo "$date_affiche <a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=AutreConsultCardioControler&controlerparams:param:action=ACE&AutreConsultCardio:AutreConsultCardio:type_exam=conso_sel&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Détail</a><br>";
                    }
                }
                ?>

            </td>
            <td><?php checkBox("id='brochure_sel1' ","AutreConsultCardio:brochure_sel1","1");?>... <img alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15"><br>
                <?php checkBox("id='brochure_sel2' ","AutreConsultCardio:brochure_sel2","1");?>... <img alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15">
            </td>
            <td><?php text("id='commentaire_sel' maxlength='255'","AutreConsultCardio:commentaire_sel");?></td>
        </Tr>
        <tr>
            <td width='300'>Consommation d'alcool < 2 verres/j pour une femme, 3 verres/j pour un homme</td>
            <td> <?php
                foreach($ListConsult as $date=>$date_affiche){
                    if($date_affiche!=$AutreConsultCardio->date){
                        echo "$date_affiche <a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=AutreConsultCardioControler&controlerparams:param:action=ACE&AutreConsultCardio:AutreConsultCardio:type_exam=conso_alcool&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Détail</a><br>";
                    }
                }
                ?>

            </td>
            <td><?php checkBox("id='brochure_alcool1' ","AutreConsultCardio:brochure_alcool1","1");?>Alcool : votre corps se souvient de tout <img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire"?>/docs/alcool2.pdf', '_blank');" alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15"><br>
                <?php checkBox("id='brochure_alcool2' ","AutreConsultCardio:brochure_alcool2","1");?>... <img alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15">
            </td>
            <td><?php text("id='commentaire_alcool' maxlength='255'","AutreConsultCardio:commentaire_alcool");?></td>
        </Tr>
        <tr>
            <td width='300'>Activité physique (équivalent de 30 min de marche rapide, 3 fois dans la semaine)</td>
            <td> <?php
                foreach($ListConsult as $date=>$date_affiche){
                    if($date_affiche!=$AutreConsultCardio->date){
                        echo "$date_affiche <a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=AutreConsultCardioControler&controlerparams:param:action=ACE&AutreConsultCardio:AutreConsultCardio:type_exam=act_phys&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Détail</a><br>";
                    }
                }
                ?>

            </td>
            <td><?php checkBox("id='brochure_activite1' ","AutreConsultCardio:brochure_activite1","1");?>Brochure "Bouger c'est la santé" <img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire"?>/docs/ATT02695.pdf', '_blank');" alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15"><br>
                <?php checkBox("id='brochure_activite2' ","AutreConsultCardio:brochure_activite2","1");?>... <img alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15">
            </td>
            <td><?php text("id='commentaire_activite' maxlength='255'","AutreConsultCardio:commentaire_activite");?></td>
        </Tr>
        <tr>
            <td width='300'>Sevrage tabagique</td>
            <td> <?php
                foreach($ListConsult as $date=>$date_affiche){
                    if($date_affiche!=$AutreConsultCardio->date){
                        echo "$date_affiche <a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=AutreConsultCardioControler&controlerparams:param:action=ACE&AutreConsultCardio:AutreConsultCardio:type_exam=act_phys&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Détail</a><br>";
                    }
                }
                ?>

            </td>
            <td><?php checkBox("id='brochure_tabac1' ","AutreConsultCardio:brochure_tabac1","1");?>La dépendance au tabac <img OnClick="window.open('<?php echo "$path/view/cardiovasculaire"?>/docs/ATT02697.pdf', '_blank');" alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15"><br>
                <?php checkBox("id='brochure_tabac2' ","AutreConsultCardio:brochure_tabac2","1");?>Les risques du tabagisme et les bénéfices de l'arrêt <img OnClick="window.open('<?php echo "$path/view/cardiovasculaire"?>/docs/risque_tabac.pdf', '_blank');" alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15">
            </td>
            <td><?php text("id='commentaire_tabac' maxlength='255'","AutreConsultCardio:commentaire_tabac");?></td>
        </Tr>
        <tr>
            <td width='300'>Contrôle du poids</td>
            <td> <?php
                foreach($ListConsult as $date=>$date_affiche){
                    if($date_affiche!=$AutreConsultCardio->date){
                        echo "$date_affiche <a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=AutreConsultCardioControler&controlerparams:param:action=ACE&AutreConsultCardio:AutreConsultCardio:type_exam=controle_poids&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Détail</a><br>";
                    }
                }
                ?>

            </td>
            <td><?php checkBox("id='brochure_poids1' ","AutreConsultCardio:brochure_poids1","1");?>Pense-bête nutrition <img OnClick="window.open('<?php echo "$path/view/cardiovasculaire"?>/docs/ATT02699.pdf', '_blank');" alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15"><br>
                <?php checkBox("id='brochure_poids2' ","AutreConsultCardio:brochure_poids2","1");?>... <img alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15">
            </td>
            <td><?php text("id='commentaire_poids' maxlength='255'","AutreConsultCardio:commentaire_poids");?></td>
        </Tr>
        <tr>
            <td width='300'>Alimentation riche en fruits et légumes</td>
            <td> <?php
                foreach($ListConsult as $date=>$date_affiche){
                    if($date_affiche!=$AutreConsultCardio->date){
                        echo "$date_affiche <a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=AutreConsultCardioControler&controlerparams:param:action=ACE&AutreConsultCardio:AutreConsultCardio:type_exam=fruits&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Détail</a><br>";
                    }
                }
                ?>
            </td>
            <td><?php checkBox("id='brochure_alim1' ","AutreConsultCardio:brochure_alim1","1");?>La santé vient en mangeant <img OnClick="window.open('<?php echo "$path/view/cardiovasculaire"?>/docs/ATT02701.pdf', '_blank');" alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15"><br>
                <?php checkBox("id='brochure_alim2' ","AutreConsultCardio:brochure_alim2","1");?>... <img alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15">
            </td>
            <td><?php text("id='commentaire_alim' maxlength='255'","AutreConsultCardio:commentaire_alim");?></td>
        </Tr>
        <tr>
            <td width='300'>Diminution des excitants (café, thé, réglisse)</td>
            <td> <?php
                foreach($ListConsult as $date=>$date_affiche){
                    if($date_affiche!=$AutreConsultCardio->date){
                        echo "$date_affiche <a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=AutreConsultCardioControler&controlerparams:param:action=ACE&AutreConsultCardio:AutreConsultCardio:type_exam=cafe&AutreConsultCardio:AutreConsultCardio:date_exclu=$AutreConsultCardio->date&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Détail</a><br>";
                    }
                }
                ?>
            </td>
            <td><?php checkBox("id='brochure_cafe1' ","AutreConsultCardio:brochure_cafe1","1");?>... <img alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15"><br>
                <?php checkBox("id='brochure_cafe2' ","AutreConsultCardio:brochure_cafe2","1");?>... <img alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15">
            </td>
            <td><?php text("id='commentaire_cafe' maxlength='255'","AutreConsultCardio:commentaire_cafe");?></td>
        </Tr>
    </table>
    <br>
    <h1>5- Faire le point sur les traitements médicamenteux</h1>
    <b>Indicateurs d'observance des traitements médicamenteux</b>
    <table border='1'>
        <tr>
            <td>Problèmes rencontrés</td>
            <td>Commentaire</td>
        </tr>
        <tr>
            <td><?php checkBox("id='probleme_qualite_vie' ","AutreConsultCardio:probleme_qualite_vie","1");?>Qualité de vie par rapport au traitement</td><td>
                <?php text("id='detail_qualite_vie' maxlength='255'","AutreConsultCardio:detail_qualite_vie");?>
            </td>
        </tr>
        <tr>
            <td><?php checkBox("id='probleme_secondaire' ","AutreConsultCardio:probleme_secondaire","1");?>Effets secondaires</td><td>
                <?php text("id='detail_secondaire' maxlength='255'","AutreConsultCardio:detail_secondaire");?>
            </td>
        </tr>
        <tr>
            <td><?php checkBox("id='pb_delivrance' ","AutreConsultCardio:pb_delivrance","1");?>Délivrance des traitements</td><td>
                <?php text("id='detail_delivrance' maxlength='255'","AutreConsultCardio:detail_delivrance");?>
            </td>
        </tr>
        <tr>
            <td><?php checkBox("id='regularite_prise' ","AutreConsultCardio:regularite_prise","1");?>Régularité des prises</td><td>
                <?php text("id='detail_regularite' maxlength='255'","AutreConsultCardio:detail_regularite");?>
            </td>
        </tr>
    </table>
    <br>
    <?php
    $item=6;
    require("view/common/epices.php");?>
    <br>
    <h1>7- Faire le bilan de la consultation</h1>
    <b>Bilan de consultation</b><br>
    <?php #echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=EvaluationInfirmierControler&controlerparams:param:action=AL&controlerparams:param:param1=PLISDOSSTIP&Dossier:dossier:numero=$dossier->numero',this);return false\" onmouseout=\"ajax_hideTooltip()\">Consultations passées </a><br>";
    ?>
    <?php echo "<a href='javascript://' onclick=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=EvaluationInfirmierControler&controlerparams:param:action=AL&controlerparams:param:param1=PLISDOSSTIP&Dossier:dossier:numero=$dossier->numero',this);return false\">Consultations passées </a><br>";
    ?>
    <table width='880' border="1" cellpadding='3'>
        <tr>
            <td>Degré de satisfaction:</td>
            <td colspan='2'><?php selectv("","AutreConsultCardio:degre_satisfaction",$satisfaction); ?></td>
        </tr>
        <tr>
            <td>Durée approximative en minutes ("à 5 minutes près")</td>
            <td><?php text("size='4'","AutreConsultCardio:duree"); ?></td>

            <td>
                <table>
                    <tr>
                        <td>En cas de consultation &agrave;<br/>domicile, cocher la case:<br/>
                            <?php checkBox("","AutreConsultCardio:consult_domicile","1"); ?>
                        </td>
                        <td width="10">&nbsp;&nbsp;&nbsp;</td>
                        <td>En cas de consultation <br/>t&eacute;l&eacute;phonique, cocher la case:<br/>
                            <?php checkBox("","AutreConsultCardio:consult_tel","1"); ?>
                        </td>
                        <td width="10">&nbsp;&nbsp;&nbsp;</td>
                        <td>En cas de consultation <br/>collective, cocher la case:<br/>
                            <?php checkBox("","AutreConsultCardio:consult_collective","1"); ?>
                        </td>
                    </tr>
                </table>
            </td>

        </tr>
        <tr>
            <td>Type de consultation:</td>
            <!-- <td><?php selectv("multiple size='12'","AutreConsultCardio:type_consultation",$type_consult); ?></td>
		<td>le relevé d'information qui suit est provisoire. Il accompagnera les protocoles de délégation
						de tâche correspondants une fois que ces derniers auront été validés. <br><br>
						Au cours de cette consultation l'infirmière a elle même réalisé par délégation les examens suivants : <br><br> -->
            <td><?php selectv("multiple size='12'","AutreConsultCardio:type_consultation",$type_consult); ?></td>
            <td>A chaque fois qu’une action de nature dérogatoire est effectuée, au titre du protocole de coopération ASALEE,
                agréé par la Haute Autorité de Santé le 22 mars 2012, et sous réserve de l’autorisation de l’Agence Régionale de Santé
                et de la notification de l’équipe ASALEE (médecins-infirmières), <br>
                cocher la ou les actions concernées.<br><br>
                <?php
                // checkBox("","AutreConsultCardio:ecg","1"); echo "ECG<br>";
                // checkBox("","AutreConsultCardio:monofil","1"); echo "Monofilament<br>";
                // checkBox("","AutreConsultCardio:exapied","1"); echo "Examen des pieds<br>";
                // checkBox("","AutreConsultCardio:hba","1"); echo "Prescription HbA1c<br>";
                // checkBox("","AutreConsultCardio:tension","1"); echo "Tension<br>";
                // checkBox("","AutreConsultCardio:autre","1"); echo "Autre. Précisez : ";
                // text("","AutreConsultCardio:prec_autre"); echo "<br>";

                checkBox("","AutreConsultCardio:hba","1"); echo "Prescription d’examen(s) pour le patient diabétique type 2 <br>";
                checkBox("","AutreConsultCardio:exapied","1"); echo "Prescription, réalisation, interprétation examen des pieds<br>";
                checkBox("","AutreConsultCardio:monofil","1"); echo "Prescription, réalisation, interprétation examen des pieds et monofilament<br>";
                checkBox("","AutreConsultCardio:ecg","1"); echo "Prescription et réalisation d’ECG<br>";
                checkBox("","AutreConsultCardio:ecg_seul","1"); echo "Réalisation d’ECG seul – non dérogatoire<br>";
                checkBox("","AutreConsultCardio:spirometre","1"); echo "Prescription, réalisation d’une spirométrie <br>";
                checkBox("","AutreConsultCardio:spirometre_seul","1"); echo "Réalisation d’une spirométrie seule - non dérogatoire <br>";
                checkBox("","AutreConsultCardio:t_cognitif","1"); echo "Prescription et réalisation d’un repérage troubles cognitifs <br>";
                /*checkBox("","evaluationInfirmier:tension","1"); echo "Tension <br>";*/
                checkBox("","AutreConsultCardio:autre","1"); echo "Autre. Précisez : ";
                text("","AutreConsultCardio:prec_autre"); echo "<br>";

                ?>
            </td>
        </tr>
        <tr>
            <td valign='top'>Points positifs :
                <div style="font-size:9px">
                    Besoins du patient pris en compte<br>
                    Objectifs prévus atteints<br>
                    Objectifs  non  prévus atteints<br>
                    Outil(s), support (s), méthodes  utilisés </div>
            </td>
            <td  width='70%' colspan='2'><?php textArea("rows=\"8\" cols=\"60\"","AutreConsultCardio:points_positifs"); ?></td>
        </tr>
        <tr>
            <td valign='top'>Points à améliorer :
                <div style="font-size:9px">
                    Besoins du patient non pris en compte<br>
                    Objectifs prévus non  atteints<br>
                    Objectifs perçus à atteindre<br>
                    Méthodes envisagées prochaine séance</div>
            </td>
            <td  width='70%' colspan='2'><?php textArea("rows=\"8\" cols=\"60\"","AutreConsultCardio:points_ameliorations"); ?></td>
        </tr>
    </table>

    <br><br>



    <input type='button' value='Valider la saisie' onClick="validateInput();">
    <input type='reset' value='Recommencer'>
</form>

<script type="text/javascript">
    // var $ = function(id) {
    //    return document.getElementById(id);
    //  }
    //  function calcIMC(){

    //  }
</script>
</body>