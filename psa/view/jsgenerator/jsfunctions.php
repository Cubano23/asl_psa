<?php function validateDateOOOLLLD(){
?>

function isInteger(s){
var i;
for (i = 0; i < s.length; i++){
// Check that current character is number.
var c = s.charAt(i);
if (((c < "0") || (c > "9"))) return false;
}
// All characters are numbers.
return true;
}

function stripCharsInBag(s, bag){
var i;
var returnString = "";
// Search through string's characters one by one.
// If character is not in bag, append to returnString.
for (i = 0; i < s.length; i++){
var c = s.charAt(i);
if (bag.indexOf(c) == -1) returnString += c;
}
return returnString;
}

function daysInFebruary (year){
// February has 29 days in any year evenly divisible by four,
// EXCEPT for centurial years which are not also divisible by 400.
return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}
function DaysArray(n) {
for (var i = 1; i <= n; i++) {
this[i] = 31
if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
if (i==2) {this[i] = 29}
}
return this
}

function validateDate(dtStr){
// Declaring valid date character, minimum year and maximum year
var dtCh= "/";
var minYear=1900;
var maxYear=2100;
var daysInMonth = DaysArray(12)
var pos1=dtStr.indexOf(dtCh)
var pos2=dtStr.indexOf(dtCh,pos1+1)
var strDay=dtStr.substring(0,pos1)
var strMonth=dtStr.substring(pos1+1,pos2)
var strYear=dtStr.substring(pos2+1)
strYr=strYear

// Pour ne pas avoir de tol?rance sur les champs commen?ant par '0'
if (strDay.length != 2) return false;
if (strMonth.length != 2) return false;
if (strYear.length != 4) return false;

if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
for (var i = 1; i <= 3; i++) {
if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
}
month=parseInt(strMonth)
day=parseInt(strDay)
year=parseInt(strYr)
if (pos1==-1 || pos2==-1){
//		alert("The date format should be : mm/dd/yyyy")
return false
}
if (strMonth.length<1 || month<1 || month>12){
//		alert("Please enter a valid month")
return false
}
if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
//		alert("Please enter a valid day")
return false
}
if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
    //		alert("Please enter a valid 4 digit year between "+minYear+" and "+maxYear)
    return false
    }
    if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
    //		alert("Please enter a valid date")
    return false
    }
    return true
    }
    <?php }
    ?>

    <?php function validateEmail(){
        ?>
        function validateEmail( strValue) {
        var objRegExp  =
        /(^[a-z]([a-z_\.]*)@([a-z_\.]*)([.][a-z]{3})$)|(^[a-z]([a-z_\.]*)@([a-z_\]*)
        (\.[a-z]{3})(\.[a-z]{2})*$)/i;

        //check for valid email
        return objRegExp.test(strValue);
        }
    <?php }
    ?>

    <?php function validateNumeric(){
        ?>
        function  validateNumeric( strValue ) {
        var objRegExp  =  /(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)/

        //check for numeric characters
        return objRegExp.test(strValue);
        }
    <?php }
    ?>

    <?php function validatePositiveNumeric(){
        ?>
        function  validatePositiveNumeric( strValue ) {
        var objRegExp  =  /(\d\d*\.\d*$)|(\d\d*$)|(\.\d\d*$)/

        //check for positive numeric characters
        return objRegExp.test(strValue);
        }
    <?php }
    ?>

    <?php function validateNumeroDossier(){
        ?>
        function validateNumeroDossier( strValue ) {
        var NumeroDossier = strValue;

        if(NumeroDossier.length > 0 && NumeroDossier.length <= 16){
        return true;
        }
        return false;
        }
    <?php }
    ?>

    <?php function validateInteger(){
        ?>
        function validateInteger( strValue ) {
        var objRegExp  = /(^-?\d\d*$)/

        //check for integer characters
        return objRegExp.test(strValue);
        }
    <?php }
    ?>

    <?php function validatePositiveInteger(){
        ?>
        function validatePositiveInteger( strValue ) {
        var objRegExp  = /(^\d*$)/

        //check for positive integer characters
        return objRegExp.test(strValue);
        }
    <?php }
    ?>

    <?php function validateTaille(){
        ?>
        function validateTaille( strValue ) {
        var objRegExp  = /(^\d*$)/
        // taille is not mandatory
        if(strValue == "") return true;
        //check for positive integer characters
        if ((objRegExp.test(strValue)) == 0) {
        return false;
        }
        if (parseInt(strValue) < 280) {
        return true;
        }
        return false;
        }
    <?php }
    ?>

    <?php function validateNotEmpty(){
        ?>
        function validateNotEmpty( strValue ) {
        var strTemp = strValue;
        strTemp = strTemp;
        if(strTemp.length > 0){
        return true;
        }
        return false;
        }
    <?php }
    ?>

    <?php function validateSexe(){
        ?>
        function validateSexe( strValue ) {
        var Sexe = strValue;
        if(Sexe.length != 1) {
        return false
        }
        if (Sexe == 'F' || Sexe == 'M'){
        return true
        }
        return false
        }
    <?php }
    ?>

    <?php function validateAge(){
        ?>
        function validateAge( strValue ) {
        var Age = strValue;
        var objRegExp  = /(^\d*$)/

        //check for integer characters
        if ( ! objRegExp.test(Age)) {
        return false;
        }

        var AgeInt = parseInt(Age);
        var AgeMax = 150;

        if(Age.length == 0) {
        return true
        }

        if (AgeInt <= AgeMax){
        return true;
        }
        return false;
        }
    <?php }
    ?>

    <?php function validateMois(){
        ?>
        function validateMois( strValue ) {
        var Mois = strValue;
        var MoisInt = ParseInt(Mois);

        if(Mois.length == 0) {
        return true;
        }
        //check for integer characters
        if (MoisINt < 1 || MoisInt > 12) {
        return false;
        }
        return true;
        }
    <?php }
    ?>
