<?php
require_once("bean/ControlerParams.php");
global $sep;
$sep = ":";

function getPropertyAndValue($property,&$attributeName,&$attributeValue){
    global $sep;
    $dotPos = strpos($property,$sep);
    if( $dotPos == 0) return false;

    list($beanName,$propertyName) = explode($sep,$property);

    if($beanName == "") return false;
    if($propertyName == "") return false;


    global $$beanName;
    $internalBeanName = $$beanName;

    if(is_null($internalBeanName)) return false;	// Ajout Pierre pour eviter Warning non Object

    $className = get_class($internalBeanName);
    $properties = get_object_vars ( $internalBeanName );
    if(is_null($properties)) return false;

    $attributeName = $className.$sep.$beanName.$sep.$propertyName;
    $attributeValue = $properties[$propertyName];

    if(is_array($attributeValue)) $attributeName = $attributeName."[]";

    return true;
}

function text($htmlParams,$property){
    getPropertyAndValue($property,$attributeName,$attributeValue);
    echo("<input type='text' $htmlParams name='$attributeName' value=\"$attributeValue\">");
    echo("\n");
}

function password($htmlParams,$property){
    getPropertyAndValue($property,$attributeName,$attributeValue);
    echo("<input type='password' $htmlParams name='$attributeName' value='$attributeValue'>");
    echo("\n");
}

function textArea($htmlParams,$property){
    getPropertyAndValue($property,$attributeName,$attributeValue);
    echo("<textarea $htmlParams name='$attributeName'>$attributeValue</textarea>\n");
}

function hidden($htmlParams,$property,$forceValue=""){
    getPropertyAndValue($property,$attributeName,$attributeValue);
    $value = ($forceValue == ""?$attributeValue:$forceValue);
    echo("<input type='hidden' $htmlParams name='$attributeName' value='$value'>");
    echo("\n");
}

function typePropertyValue($property){
    getPropertyAndValue($property,$attributeName,$attributeValue);
    echo(stripslashes($attributeValue));
}

function getPropertyValue($property){
    getPropertyAndValue($property,$attributeName,$attributeValue);
    return $attributeValue;
}

function typePropertyName($property){
    getPropertyAndValue($property,$attributeName,$attributeValue);
    echo($attributeName);
}

function getPropertyName($property){
    getPropertyAndValue($property,$attributeName,$attributeValue);
    return $attributeName;
}

function checkBox($htmlParams,$property,$value){
    getPropertyAndValue($property,$attributeName,$attributeValue);
    $checked = ($value == $attributeValue?"checked":"");
    echo("<input $htmlParams name='$attributeName' type='checkbox' value='$value' $checked>\n");
}

function radioButton($htmlParams,$property,$value){
    getPropertyAndValue($property,$attributeName,$attributeValue);
    $checked = ($value == $attributeValue?"checked":"");
    echo("<input $htmlParams name='$attributeName' type='radio' value='$value' $checked>\n");
}

function selectGeneric($htmlParams,$property,$values,$addValue=false,$addEmptyItem=false,$additionalItemsArray = NULL){

    getPropertyAndValue($property,$attributeName,$attributeValue);

    echo("<select $htmlParams name='$attributeName'>");
    echo("\n");

    if($addEmptyItem) {
        echo("<option></option>");
        echo("\n");
    }
    if(is_array($additionalItemsArray)){
        foreach($additionalItemsArray as $key=>$val){
            echo("<option value='$key'>$val</option>\n");
        }
    }
    var_dump($values);
    if(!is_null($values)){
        foreach ($values as $name => $value){
            if($value!=''){

                echo("<option");
                if($addValue && strlen($value) > 0){
                    echo(" value=$name");
                    if(is_array($attributeValue)) {
                        if(in_array($name,$attributeValue)) echo (" selected ");
                    }
                    else echo($attributeValue == $name?" selected":"");
                }
                else{
                    echo($attributeName == $value?" selected ":"");
                }
                echo(">$value</option>");
                echo("\n");
            }

        }


        echo("</select>");
    }
    echo("\n");

}

function selectv($htmlParams,$property,$values,$addEmptyItem=false,$additionalItemsArray = NULL){
    #var_dump($addEmptyItem);
    selectGeneric($htmlParams,$property,$values,true,$addEmptyItem,$additionalItemsArray);
}


function hiddenAction($action){
    $sep=":";
    echo("<input id='action' type='hidden' name='controlerparams".$sep."param".$sep."action' value='$action'/>\n");
}
function hiddenParam1($param1){
    $sep=":";
    echo("<input id='param1' type='hidden' name='controlerparams".$sep."param".$sep."param1' value='$param1'/>\n");
}

function hiddenParamN($param,$number){
    $sep=":";
    echo("<input id='param$number' type='hidden' name='controlerparams".$sep."param".$sep."param$number' value='$param'/>\n");
}

function hiddenControler($controler){
    $sep=":";
    echo("<input id='controler' type='hidden' name='controlerparams".$sep."param".$sep."controler' value='$controler'/>\n");
}

function buildCustomSubmit($htmlParams,$action,$params,$controler,$jsFunction=NULL,$alertMessage = NULL){
    $submit = "<input $htmlParams type='button'  onclick='javascript:";
    if(!is_null($alertMessage)) $submit = $submit."if(!confirm(\"$alertMessage\")) return;";
    if(!is_null($action) && $action !="") $submit = $submit."document.getElementById(\"action\").value=\"$action\";";
    if(!is_null($params) && $params !="")
        for($i=0;$i<count($params);$i++){
            $j=$i+1;
            $submit = $submit."document.getElementById(\"param$j\").value=\"$params[$i]\";";
        }
    if(!is_null($controler) && $controler != "")
        $submit = $submit."document.getElementById(\"controler\").value=\"$controler\";";
    if(is_null($jsFunction))
        $submit =  $submit."submit();";
    else
        $submit =  $submit.$jsFunction."();";
    $submit = $submit."'/>\n";
    return $submit;
}

function customSubmit($htmlParams,$action,$params,$controler,$jsFunction=NULL){
    echo(buildCustomSubmit($htmlParams,$action,$params,$controler,$jsFunction));
}

function customSubmitWithAlert($htmlParams,$action,$params,$controler,$jsFunction=FALSE,$alertMessage){
    echo(buildCustomSubmit($htmlParams,$action,$params,$controler,$jsFunction,$alertMessage));
}

function getLink($actionControler,$controler,$action,$params="",$additionalParams=""){
    global $sep;
    if(is_null($actionControler)) return "";
    if(is_null($action)) return "";
    if(is_null($controler)) return "";
    $httpParamAction = "controlerparams".$sep."param".$sep."action";
    $httpParamControler = "controlerparams".$sep."param".$sep."controler";

    $link = "$actionControler?$httpParamControler=$controler&$httpParamAction=$action";

    if(is_array($params)){
        for($i=0;$i<count($params);$i++){
            $j=$i+1;
            $paramj= "controlerparams".$sep."param".$sep."param".$j;
            $link = $link."&".$paramj."=".$params[$i];
        }
    }

    if(is_array($additionalParams)){
        foreach($additionalParams as $key=>$value){
            $link = $link."&".$key."=".$value;
        }
    }

    return $link;
}

function buildLink($htmlParams,$text,$actionControler,$controler,$action,$params="",$additionalParams=""){

    $link = getLink($actionControler,$controler,$action,$params,$additionalParams);
    echo("<a $htmlParams href='$link' >$text</a>");
}

function selectArray($htmlParams,$property,$values){

    getPropertyAndValue($property,$attributeName,$attributeValue);

    echo("<select $htmlParams name='$attributeName'>");
    echo("\n");

    var_dump($values);
    if(!is_null($values)){
        foreach ($values as $name => $value){
            if($value!=''){

                echo("<option");
                {
                    echo(" value=$value");
                    if(is_array($attributeValue)) {
                        if(in_array($name,$attributeValue)) echo (" selected ");
                    }
                    else echo($attributeValue == $name?" selected":"");
                }

            }
            echo(">$value</option>");
            echo("\n");
        }

    }


    echo("</select>");
    echo("\n");

}


?>
