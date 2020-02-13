<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php
require_once ("Config.php");
$config = new Config();

//include($this->config->app_path . $this->config->psa_path . '/lib/htmlMimeMail-2.5.1/htmlMimeMail.php');
?>
<?php global $account ?>
<?php global $param ?>
<?php global $Frais;  ?>


<script type="text/javascript" >
    <?php
    validateNumeroDossier();
    validateDate();
    $js = new JSValidation();
    $js->startCheckFunction("validateInput","aForm");
    $js->validateDate("Frais:date_frais","Date des frais");
    $js->endCheckFunction();
    ?>

    function affiche_autre(){
        var nature = document.getElementById("nature");
        var nat = nature.options[nature.selectedIndex].value;

        if(nat=="autres"){
            document.getElementById("texte_autre_conge").style.display="";
            document.getElementById("autre_conge").style.display="";
        }
        else{
            document.getElementById("texte_autre_conge").style.display="none";
            document.getElementById("autre_conge").style.display="none";
        }

    }
</script>
<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="aForm" enctype='multipart/form-data' >
    <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
    <?php hiddenControler("FraisControler"); ?>
    <?php /*hiddenAction(ACTION_MANAGE); ?>
	<?php hiddenParam1(PARAM_CREATE);*/ ?>
    <?php hiddenAction(ACTION_SAVE); ?>
    <?php hidden("","Frais:id");?>

    <?php
    $nature=array(""=>"",
        "paye"=>"Cong&eacute;s pay&eacute;s",
        "sanssolde"=>"Cong&eacute;s sans solde",
        "autres"=>"autres");
    ?>
    <style type="text/css">
        .btn{
            width:100%;
        }
      
    </style>

    <div style="margin-top: 15px">
        <h4><span style="color: #E02D2F"> * </span>Si la demande de frais concerne des frais kilom&eacute;triques, merci de fournir un justificatif Mappy<br /> indiquant les adresses de d&eacute;part et d'arriv&eacute;e et le nombre de km parcourus.</h4>
    <a href="<?php echo $path?>/view/docs/frais/justif_deplacement.pdf" target="_blank"><i class="fas fa-info-circle"></i> Aide à la saisie du justificatif Mappy</a>
    <br/><br/>
    </div>
    <table border="0">

       
        <tr>
            <td>Date des frais</td>
            <td colspan='2'><?php text("size='10' onkeyup='formate_date(this)'","Frais:date_frais"); ?> Format jj/mm/aaaa</td>
        </tr>
        <tr>
            <td>B&eacute;n&eacute;ficiaire </td>
            <td colspan='2'><?php echo utf8_decode($_SESSION['id.prenom'].' '.$_SESSION['id.nom']);?></td>
        </tr>
        <tr>
            <td>Nature des frais</td>
            <td><?php text("","Frais:nature"); ?> <i>Exemple : frais kilom&eacute;triques</i></td>
        </tr>
        <tr>
            <td>Motif</td>
            <td><?php text("","Frais:motif"); ?> <i>Exemple : Formation au cabinet du Dr XXX</i></td>
        </tr>
        <tr>
            <td>Le cas &eacute;ch&eacute;ant, montant en euros</td>
            <td><?php text(" size='5'","Frais:montant"); ?> euros</td>
        </tr>
        <tr>
            <td>Le cas &eacute;ch&eacute;ant, nombre de km</td>
            <td><?php text("","Frais:autre_calcul"); ?> <i>Exemple : 52 km</i></td>
        </tr>
        <tr>
            <td>Le cas &eacute;ch&eacute;ant, justificatif en pi&egrave;ce jointe<br><i> (poids MAX : 2Mo)</i></td>
            <td><?php echo "<input type='file' name='pj'>"; ?></td>
        </tr>
    </table><br>
    <input value="Enregistrer" type="button" id="btn-submit">
    <?php

    //customSubmit("value='Enregistrer' id='btn-submit' ",ACTION_SAVE,"", "","validateInput"); ?><br>

</form>





<script type="text/javascript">
    $(document).on('ready', function() {
        $('#btn-submit').on('click', function() {
            console.log('submit buttong clicked');
            $('.errorMessage').html('')
            if($('input[name="Frais:Frais:date_frais"]').val() == "") {
                $('.errorMessage').html('Veuillez remplir le champ "date des frais"')
            }
            var dateContinue = false

            var today = new Date()
            if($('input[name="Frais:Frais:date_frais"]').val() == ((today.getDate() < 10) ? '0' + today.getDate() : today.getDate()) + '/' + (((today.getMonth() + 1) < 10) ? '0' + (today.getMonth() + 1) : (today.getMonth() + 1)) + '/' + today.getFullYear()) {
                if(confirm('La date de la d&eacute;pense est-elle bien celle du jour ?')) {
                    dateContinue = true;
                }
            }else {
                dateContinue = true;
                console.log('autre')
                // javascript:document.getElementById('action').value='AS';
                // doSubmit();
            }
            var montantContinue = false;

            if($('input[name="Frais:Frais:montant"]').val().replace(',', '.') >= 300 && $('input[name="Frais:Frais:montant"]').val().replace(',', '.') < 3000 ) {
                if(confirm('le montant saisi &eacute;tant sup&eacute;rieur &agrave; 300 Euros pouvez-vous confirmer ce montant ?')) {
                    montantContinue = true;
                }
            }else {
                if($('input[name="Frais:Frais:montant"]').val().replace(',', '.') < 300 ) {
                    montantContinue = true;
                }else {
                    console.log('autre')
                    // javascript:document.getElementById('action').value='AS';
                    // doSubmit();
                }
            }
            var montantCorrect = false;

            if($('input[name="Frais:Frais:montant"]').val().replace(',', '.') >= 3000 ) {
                alert("Pour les montants sup&eacute;rieurs ? 3000 Euros, merci de faire la demande de remboursement de frais  par mail &agrave; gestion@asalee.fr ");
            }else {
                montantCorrect = true;
            }

            var pieceJointeCorrect = false;

            if($('input[name="pj"]').val() == "") {
                alert("Les pi&egrave;ces jointes sont obligatoires. Si la demande de frais concerne des frais kilom&eacute;triques, merci de fournir un justificatif Mappy indiquant les adresses de d&eacute;part et d'arriv&eacute;e et le nombre de km parcourus. ");
            }else {
                pieceJointeCorrect = true;
            }

            if(montantContinue && dateContinue && montantCorrect && pieceJointeCorrect) {
                console.log('je soumets');
                javascript:document.getElementById('action').value='AS';
                doSubmit();
            }
            else {
                dateContinue = false;
                montantContinue = false;
                pieceJointeCorrect = false;

            }

        });

        function doSubmit() {
            var nature = document.getElementById('nature');

            if($('input[name="Frais:Frais:motif"]').val() == '')
            {
                $('.errorMessage').html('Veuillez indiquer un motif')
            }
            else if($('input[name="Frais:Frais:nature"]').val() == '')
            {
                $('.errorMessage').html('Veuillez renseigner une nature')
            }
            else if($('input[name="Frais:Frais:montant"]').val() != '' && $('input[name="Frais:Frais:autre_calcul"]').val() != '') {
                $('.errorMessage').html('Vous ne pouvez pas saisir un montant + un nombre de km.<br />Pour des frais kilom&eacute;triques, veuillez remplir uniquement le champ "nombre de km"')
            }
            else {
                if($('input[name="Frais:Frais:autre_calcul"]').val() == '') {
                    var val = $('input[name="Frais:Frais:montant"]').val().replace(',', '.');
                    console.log('val ' + val);
                    if(isNaN(val) || val == "") {
                        $('.errorMessage').html('Veuillez remplir le champ "montant" par un nombre')
                    }
                    else {
                        if(val == "0") {
                            $('.errorMessage').html('Veuillez remplir le champ "montant" correctement')
                        }
                        else {
                            console.log('debug3 : ' + Math.round(val*100)/100)
                            if($('input[type="file"]').val() == "") {
                                $('.errorMessage').html('Vous devez fournir une pi&egrave;ce jointe pour un montant en euros')
                            }
                            else {
                                console.log('val ' + val);
                                $('input[name="Frais:Frais:montant"]').val(Math.round(val*100)/100)
                                validateInput()
                            }
                        }
                    }
                }
                else {
                    if($('input[name="Frais:Frais:montant"]').val() == '') {
                        var val = $('input[name="Frais:Frais:autre_calcul"]').val().replace(',', '.');
                        if(isNaN(val)) {
                            $('.errorMessage').html('Veuillez remplir le champ "km" par un nombre')
                        }
                        else if(val == "0") {
                            $('.errorMessage').html('Veuillez remplir le champ "km" correctement')
                        }
                        else {
                            $('input[name="Frais:Frais:autre_calcul"]').val(Math.round(val*10)/10)
                            validateInput()
                        }
                    }
                }
            }

        }

    });
</script>


