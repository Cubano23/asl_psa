<?php

require_once("bean/beanparser/htmltags.php");
require_once("view/jsgenerator/jsfunctions.php");
require_once("view/jsgenerator/jsdatefunctions.php");
require_once("view/common/vars.php");


class JSValidation {

    function startCheckFunction($functionName,$formName){
        ?>
        function <?php echo($functionName); ?>(){
        var thisForm = document.forms.<?php echo($formName.";\n"); ?>
        var submitOk = 1;
        <?php
    }

    function endCheckFunction(){
        ?>
        if(submitOk == 1) thisForm.submit();
        }
        <?php
    }

    function validateDate($property,$fieldLabel){

        $result = getPropertyAndValue($property,$attributeName,$attributeValue);
        if($result == false) return;
        ?>
        var element = document.getElementsByName('<?php echo($attributeName) ?>');
        if(element != null && element.length == 1){
        var date = 	element[0].value;
        if(!validateDate(date)){
        submitOk = 0
        alert("la date '<?php echo($fieldLabel) ?>' doit ?tre au format 'jj/mm/aaaa', et l'ann?e entre '1900' et '2100'.")
        }

        }
        <?php
    }

    function validateDateConsentement($property,$fieldLabel){
        $attributeValue = '00/00/0000';
        $result = getPropertyAndValue($property,$attributeName,$attributeValue);
        if($result == false) return;
        ?>
        var element = document.getElementsByName('<?php echo($attributeName) ?>');
        if(element != null && element.length == 1){
        var date = 	element[0].value;
        if(!validateDateConsentement(date)){
        submitOk = 0
        alert("la date '<?php echo($fieldLabel) ?>' doit ?tre au format 'jj/mm/aaaa', et l'ann?e entre '1900' et '2100'.")
        }

        }
        <?php
    }

    function dateInRangeNaiss($property,$fieldLabel){
        $result = getPropertyAndValue($property,$attributeName,$attributeValue);
        if($result == false) return;
        ?>
        var element = document.getElementsByName('<?php echo($attributeName) ?>');

        if(element != null && element.length == 1){
        var date = 	element[0].value;

        label3 = "<?php echo($fieldLabel == "Date de naissance")?>";

        //label = 1;
        if ((date =='') && (label3=="1"))
        {
        //alert ("ok");
        }
        else
        {
        /*
        if(dateInRange("01/01/1996","<?php echo(date("d/m/Y")); ?>",date))
        {

        alert("la date '<?php echo($fieldLabel) ?>' est habituellement, sauf exception, ant?rieure ? 1996, et le format de la date doit ?tre le suivant jj/mm/aaaa - exemple : 10/05/1954")
        }
        */
        }
        }
        <?php
    }

    function dateInRange($property,$fieldLabel){
        $result = getPropertyAndValue($property,$attributeName,$attributeValue);
        if($result == false) return;
        ?>
        var element = document.getElementsByName('<?php echo($attributeName) ?>');

        if(element != null && element.length == 1){
        var date = 	element[0].value;

        label = "<?php echo(($fieldLabel == 'Date colloscopie')||($fieldLabel == 'Date de mammographie'))?>";
        label2 = "<?php echo($fieldLabel == 'Date de rappel')?>";
        //label = 1;
        if ((date =='') && (label == "1")||(label2=="1"))
        {
        //alert ("ok");
        }
        else
        {
        if(label2=="1")
        {
        if(!dateInRange("<?php echo (date("d/m/Y"));?>", "31/12/2100", date))
        {
        submitOk=0
        alert("la date '<?php echo ($fieldLabel) ?>' doit ?tre au format jj/mm/yyyy et ?tre valide")
        }
        }
        else
        {
        if(!dateInRange("01/01/1900","<?php echo(date("d/m/Y")); ?>",date))
        {
        submitOk = 0
        alert("la date '<?php echo($fieldLabel) ?>' doit ?tre au format jj/mm/yyyy et ?tre valide")
        }
        }
        }

        }
        <?php
    }


    function validateRange($property,$lowerRange,$higherRange,$fieldLabel){
        $result = getPropertyAndValue($property,$attributeName,$attributeValue);
        if($result == false) return;
        ?>
        var objRegExp  = /(^-?\d*$)/
        var val =  document.getElementsByName('<?php echo($attributeName) ?>')[0].value;
        if (val > <?php echo($higherRange); ?> || val < <?php echo($lowerRange);?> || !validatePositiveNumeric(val) ){
        submitOk = 0;
        alert('le champ <?php echo($fieldLabel); ?> doit etre compris entre <?php echo ($lowerRange) ?> et <?php echo($higherRange)?>')
        }
    <?php 	}

    function validateGreater($property,$val,$fieldLabel){
        $result = getPropertyAndValue($property,$attributeName,$attributeValue);
        if($result == false) return;
        ?>
        var objRegExp  = /(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)/
        var val =  document.getElementsByName('<?php echo($attributeName) ?>')[0].value;
        if (val < <?php echo($val); ?> || !objRegExp.test(val) ){
        submitOk = 0;
        alert('le champ <?php echo($fieldLabel); ?> doit etre > <?php echo ($val) ?>')
        }
    <?php 	}


    function validateLower($property,$val,$fieldLabel){
        $result = getPropertyAndValue($property,$attributeName,$attributeValue);
        if($result == false) return;
        ?>
        var objRegExp  = /(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)/
        var val =  document.getElementsByName('<?php echo($attributeName) ?>')[0].value;

        if (val > <?php echo($val); ?> || !objRegExp.test(val) ){
        submitOk = 0;
        alert('le champ <?php echo($fieldLabel); ?> doit etre < <?php echo ($val) ?>')
        }
    <?php 	}

    function validateNumeroDossier($property,$fieldLabel){
        $result = getPropertyAndValue($property,$attributeName,$attributeValue);
        if($result == false) return;
        ?>

        var field = document.getElementsByName('<?php echo($attributeName) ?>')[0].value;
        if(!validateNumeroDossier(field)){
        submitOk = 0
        alert("le champ '<?php echo($fieldLabel)?>' n'est pas un num?ro de dossier valide")
        }
        <?php
    }

    function validateInteger($property,$fieldLabel){
        $result = getPropertyAndValue($property,$attributeName,$attributeValue);
        if($result == false) return;
        ?>
        var field = document.getElementsByName('<?php echo($attributeName) ?>')[0].value;
        if(!validateInteger(field)){
        submitOk = 0
        alert("le champ '<?php echo($fieldLabel)?>' doit ?tre un entier")
        }
        <?php
    }

    function validatePositiveInteger($property,$fieldLabel){
        $result = getPropertyAndValue($property,$attributeName,$attributeValue);
        if($result == false) return;
        ?>
        var field = document.getElementsByName('<?php echo($attributeName) ?>')[0].value;
        if(!validatePositiveInteger(field)){
        submitOk = 0
        alert("le champ '<?php echo($fieldLabel)?>' doit ?tre un entier naturel")
        }
        <?php
    }

    function validateTaille($property,$fieldLabel){
        $result = getPropertyAndValue($property,$attributeName,$attributeValue);
        if($result == false) return;
        ?>
        var field = document.getElementsByName('<?php echo($attributeName) ?>')[0].value;
        if(!validateTaille(field)){
        submitOk = 0
        alert("le champ '<?php echo($fieldLabel)?>' doit etre entre 0 et 280")
        }
        <?php
    }

    function validateNumeric($property,$fieldLabel){
        $result = getPropertyAndValue($property,$attributeName,$attributeValue);
        if($result == false) return;
        ?>
        var field = document.getElementsByName('<?php echo($attributeName) ?>')[0].value;
        if(!validateNumeric(field)){
        submitOk = 0
        alert("le champ '<?php echo($fieldLabel)?>' doit ?tre num?rique")
        }
        <?php
    }

    function validatePositiveNumeric($property,$fieldLabel){
        $result = getPropertyAndValue($property,$attributeName,$attributeValue);
        if($result == false) return;
        ?>
        var field = document.getElementsByName('<?php echo($attributeName) ?>')[0].value;
        if(!validatePositiveNumeric(field)){
        submitOk = 0
        alert("le champ '<?php echo($fieldLabel)?>' doit ?tre num?rique et positif")
        }
        <?php
    }

    function validateEmail($property,$fieldLabel){
        $result = getPropertyAndValue($property,$attributeName,$attributeValue);
        if($result == false) return;
        ?>
        var field = document.getElementsByName('<?php echo($attributeName) ?>')[0].value;
        if(!validateEmail(field)){
        submitOk = 0
        alert("le champ '<?php echo($fieldLabel)?>' doit ?tre une adresse email valide")
        }
        <?php
    }

    function validateSexe($property,$fieldLabel){
        $result = getPropertyAndValue($property,$attributeName,$attributeValue);
        if($result == false) return;
        ?>
        var field = document.getElementsByName('<?php echo($attributeName) ?>')[0].value;
        if(!validateSexe(field)){
        submitOk = 0
        alert("le champ '<?php echo($fieldLabel)?>' doit ?tre 'F?minin' ou 'Masculin'")
        }
        <?php
    }

    function validateAge($property,$fieldLabel){
        $result = getPropertyAndValue($property,$attributeName,$attributeValue);
        if($result == false) return;
        ?>
        var field = document.getElementsByName('<?php echo($attributeName) ?>')[0].value;
        if(!validateAge(field)){
        submitOk = 0
        alert("le champ '<?php echo($fieldLabel)?>' doit ?tre un ?ge valide")
        }
        <?php
    }

    function validateMois($property,$fieldLabel){
        $result = getPropertyAndValue($property,$attributeName,$attributeValue);
        if($result == false) return;
        ?>
        var field = document.getElementsByName('<?php echo($attributeName) ?>')[0].value;
        if(!validateMois(field)){
        submitOk = 0
        alert("le champ '<?php echo($fieldLabel)?>' doit ?tre un num?ro de mois valide")
        }
        <?php
    }

    function numericString($property){
        $result = getPropertyAndValue($property,$attributeName,$attributeValue);
        if($result == false) return;

        echo("	if (!(thisForm.$attributeName.value.match('/[0-99999999]/'))){\n");
        echo("		submitOk = 0;\n");
        echo("		alert('le champ ".$fieldLabel." doit etre numerique');\n");
        echo("	}\n");
    }

    function changeActionAndSubmit($formName,$inputId,$inputValue){
        echo("\t var obj = document.getElementById(\"$inputId\");\n");
        echo("\t obj.value = $inputValue;\n");
        echo("\t $formName.submit();\n");
    }


}
?>
<script type="text/javascript" >
    function formate_date(zone){
        if(zone.value.length==2){
            zone.value=zone.value+"/";
        }
        if(zone.value.length==4){
            zone.value=zone.value.replace("//", "/");
        }
        if(zone.value.length==5){
            zone.value=zone.value+"/";
        }
        if(zone.value.length==7){
            zone.value=zone.value.replace("//", "/");
        }
    }
</script>
