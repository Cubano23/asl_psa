<?php 	function compareDates(){?>

    // compare 2 dates (format: dd/mm/yyyy).
    // No syntax check is done so the dates are assumed correct.
    // retunr 0 if dates are equal. 1 if dateA > dateB and -1 otherwise.
    function compareDates(dateA,dateB){

    //if dates are equal return 0 (1)
    if(dateA == dateB) return 0;

    var arrayDateA = dateA.split("/");
    var arrayDateB = dateB.split("/");

    // compare years
    if(arrayDateA[2] > arrayDateB[2]) return 1;
    if(arrayDateA[2] < arrayDateB[2]) return -1;

    //compare months
    if(arrayDateA[1] > arrayDateB[1]) return 1;
    if(arrayDateA[1] < arrayDateB[1]) return -1;

    //compare days
    if(arrayDateA[0] > arrayDateB[0]) return 1;
    if(arrayDateA[0] < arrayDateB[0]) return -1;

    //this should never happen. see (1)
    return 0;

    }

<?php } ?>

<?php 	function monthDiffDates(){?>

    //return the difference in months between 2 dates (format: dd/mm/yyyy).
    // dateA-dateB
    // No syntax check is done so the dates are assumed correct.
    // The difference is an approximation

    function monthDiffDates(dateA,dateB){
    var arrayDateA = dateA.split("/");
    var arrayDateB = dateB.split("/");

    // remove leading zeroes for month and days for correct integer parsing

    if(arrayDateA[1].charAt(0)=='0') arrayDateA[1] = arrayDateA[1].charAt(1);
    if(arrayDateB[1].charAt(0)=='0') arrayDateB[1] = arrayDateB[1].charAt(1);

    if(arrayDateA[0].charAt(0)=='0') arrayDateA[0] = arrayDateA[0].charAt(1);
    if(arrayDateB[0].charAt(0)=='0') arrayDateB[0] = arrayDateB[0].charAt(1);


    // compute the difference
    var dYears  = parseInt(arrayDateA[2]) - parseInt(arrayDateB[2]);
    var dMonths = parseInt(arrayDateA[1]) - parseInt(arrayDateB[1]);
    var dDays = parseInt(arrayDateA[0]) - parseInt(arrayDateB[0]);

    var diff = 365 *(dYears);
    diff = diff + (30 * dMonths);
    diff = diff + dDays;

    return Math.round(diff/30);
    }

<?php } ?>

<?php
function validateDate(){ ?>
    // return true if date have the following format: dd/mm/yyyy
    function validateDate(aDate){
    var objRegExp = /^\d{1,2}\/\d{1,2}\/\d{4}$/
    //check to see if in correct format
    if(!objRegExp.test(aDate))
    return false; //doesn't match pattern, bad date
    else{
    var strSeparator = aDate.substring(2,3)
    var arrayDate = aDate.split(strSeparator);

    var smallArray = { '01' : 1,'02':2 ,'03' : 3, '04' : 4,'05' : 5,'06' : 6,'07' : 7,'08' : 8,'09' : 9,'10' : 10,'11' : 11,'12' : 12,'13':13,'14':14,'15':15,'16':16,'17':17,'18':18,'19':19,'20':20,'21':21,'22':22,'23':23,'24':24,'25':25,'26':26,'27':27,'28':28,'29':29,'30':30,'31':31}
    var intYear = parseInt(arrayDate[2]);
    // don't use parseint for month..it does not work properly
    var intMonth = smallArray[arrayDate[1]];


    var febDaysNum = 28;
    if(intYear %4 == 0) febDaysNum = 29;

    //create a lookup for months.
    var arrayLookup = { '01' : 31,'02':febDaysNum ,'03' : 31, '04' : 30,'05' : 31,'06' : 30,'07' : 31,'08' : 31,'09' : 30,'10' : 31,'11' : 30,'12' : 31}

    var intDay = smallArray[arrayDate[0]];

    //check if month value and day value agree
    if(arrayLookup[arrayDate[1]] != null) {
    if(intDay <= arrayLookup[arrayDate[1]] && intDay != 0)
    return true; //found in lookup table, good date
    }
    return false;
    }
    }
<?php } ?>

<?php
function dateInRange(){ ?>
    // return true if aDate is between lowDate and currentDate
    function dateInRange(lowDate,highDate,aDate){
    if(!validateDate(aDate)) return false;
    if(compareDates(lowDate,aDate) >0 ) return false;
    if(compareDates(aDate,highDate) > 0) return false;
    return true;
    }
<?php }

function dateInRangeNaiss(){ ?>
    // return true if aDate is between lowDate and currentDate
    function dateInRange(lowDate,highDate,aDate){
    if(!validateDate(aDate)) return false;
    if(compareDates(lowDate,aDate) >0 ) return false;
    if(compareDates(aDate,highDate) > 0) return false;
    return true;
    }
<?php } ?>


