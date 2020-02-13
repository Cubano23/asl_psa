<?php

// Prendre en compte début année 29-12-2014

error_reporting(E_ERROR);


require_once "Config.php";
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");

# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
die("Impossible de se connecter à la base");




$req="SELECT cabinet, total_diab2, nom_cab, region ".
    "FROM account ".
    "WHERE region!='' and infirmiere!='' ".
    "GROUP BY nom_cab ".
    "ORDER BY nom_cab ";

$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

$reg=array();
$plus3["tot"]=0;
$total_diab["tot"]=0;


$hba1c85["tot"]=0;
$hba1c75["tot"]=0;
$ldl15["tot"]=0;
$ldl13["tot"]=0;
$fond["tot"]=0;
$diast["tot"]=0;

date_default_timezone_set('Europe/Paris');
$date2mois=date("Y-m-d", mktime(1, 1, 1, date("m")-2, date("d"), date("Y")));
$date1an=date("d/m/Y", mktime(1, 1, 1, date("m")-2, date("d"), date("Y")-1));
$d1an=date("Y-m-d", mktime(1, 1, 1, date("m")-2, date("d"), date("Y")-1));

while(list($cab, $total_diab2, $ville, $region) = mysql_fetch_row($res)) {

    $t_diab[$cab]=0;

    $tville[$cab]=$ville;

    $regions[$cab]=$region;
    $nb_dossiers[$cab]=0;
    $plus3[$cab]=0;
    $hba1c85[$cab]=0;
    $hba1c75[$cab]=0;
    $ldl15[$cab]=0;
    $ldl13[$cab]=0;
    $fond[$cab]=0;
    $diast[$cab]=0;

    $total_diab[$cab]=0;

    $plus3[$region]=0;
    $hba1c85[$region]=0;
    $hba1c75[$region]=0;
    $ldl15[$region]=0;
    $ldl13[$region]=0;
    $fond[$region]=0;
    $diast[$region]=0;


    $total_diab[$region]=0;
    $rcva[$cab]=0;
    $rcva1an[$cab]=0;
    $nb_dossiers[$region]=0;
    $rcva[$region]=0;
    $rcva1an[$region]=0;

    if(!in_array($region, $reg)){
        $reg[]=$region;
    }
}

foreach($tville as $cab=>$ville){
    $tcabinet_util[$cab]=1;
}


$mois=array('01'=>'Janvier', '02'=>'F&eacute;vrier', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
    '11'=>'Novembre', '12'=>'D&eacute;cembre');




$excludedsql =" cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and cabinet!='jgomes' ".
    "and cabinet!='sbirault' and cabinet!='touaregs' ";


//Patients avec au moins un suivi
$req="SELECT cabinet, dossier.id,  count( liste_exam.id ) ".
    "FROM  dossier, liste_exam  ".
    "WHERE liste_exam.date_exam >='2013-01-01' ".
    "AND dossier.id=liste_exam.id ".
    "and type_exam='HBA1c' ".
    "and " .$excludedsql.
    "AND actif='oui' ".
    "GROUP BY cabinet, dossier.id ".
    "ORDER BY cabinet ";

$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


while(list($cab, $id , $pat ) = mysql_fetch_row($res))
{

//	if(isset($regions[$cab]))
    {

        if($pat>=3)	{
            $plus3[$cab]=$plus3[$cab]+1;
            $plus3["tot"]=$plus3["tot"]+1;


        }
        $total_diab[$cab]=$total_diab[$cab]+1;
        $total_diab["tot"]=$total_diab["tot"]+1;

        //Nombre de HBA1c r&eacute;alis&eacute;s sur les 12 derniers mois
        $req="SELECT  resultat1 ".
            "FROM liste_exam ".
            "WHERE  type_exam='HBA1c' ".
            "and id='$id' ".
            "order BY date_exam DESC limit 0,1";

        $res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        list($hba1c)=mysql_fetch_row($res2);


        if($hba1c<8.5){
            $hba1c85[$cab]=$hba1c85[$cab]+1;
            $hba1c85["tot"]=$hba1c85["tot"]+1;

        }
        if($hba1c<7.5){
            $hba1c75[$cab]=$hba1c75[$cab]+1;
            $hba1c75["tot"]=$hba1c75["tot"]+1;

        }

        //Nombre de LDL r&eacute;alis&eacute;s sur les 12 derniers mois
        $req="SELECT  resultat1 ".
            "FROM liste_exam ".
            "WHERE  type_exam='LDL' ".
            "and id='$id' ".
            "order BY date_exam DESC limit 0,1";
        $res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        list($LDL)=mysql_fetch_row($res2);


        if($LDL<1.5){
            $ldl15[$cab]=$ldl15[$cab]+1;
            $ldl15["tot"]=$ldl15["tot"]+1;
        }
        if($LDL<1.3){
            $ldl13[$cab]=$ldl13[$cab]+1;
            $ldl13["tot"]=$ldl13["tot"]+1;
        }

        //Nombre de fonds d'oeils r&eacute;alis&eacute;s sur les 24 derniers mois
        $req="SELECT  count(*) ".
            "FROM liste_exam ".
            "WHERE date_exam > '2012-01-01' ".
            "and type_exam='fond' ".
            "and id='$id' ".
            "GROUP BY id, date_exam";
        $res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        $pat=mysql_num_rows($res2);

        if($pat>=1){
            $fond[$cab]=$fond[$cab]+1;
            $fond["tot"]=$fond["tot"]+1;

        }


        $req="SELECT date_exam, resultat1 ".
            "FROM liste_exam ".
            "WHERE  type_exam='systole' and date_exam > '2013-01-01' ".
            "and id='$id' ".
            "order BY date_exam DESC limit 0,1";

        $res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        list($date_exam, $systole)=mysql_fetch_row($res2);


        if($systole<140)
        {
            $req="SELECT resultat1 ".
                "FROM liste_exam ".
                "WHERE  type_exam='diastole' and date_exam = '$date_exam' ".
                "and id='$id' ".
                "order BY date_exam DESC limit 0,1";

            $res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


            list($diastole)=mysql_fetch_row($res2);

            if($diastole<90)
            {
                $diast[$cab]=$diast[$cab]+1;
                $diast["tot"]=$diast["tot"]+1;

            }
        }



    }
}


$nb_tot=0;
$nb_ok=0;

$array = array();
$result = array();

$array["r0" ] =  	"Diab&egrave;te - HbA1c. Nombre de patients MT trait&eacute;s par antidiab&eacute;tiques et b&eacute;n&eacute;ficiant ".
    "de 3 &agrave; 4 dosages d'HbA1c dans l'ann&eacute;e parmi l'ensemble des patients trait&eacute;s par ".
    "antidiab&eacute;tiques ayant choisi le m&eacute;decin comme \" m&eacute;decin traitant \" ";
$array["ci0"] ="<font style='color:green'>65%</font>";
$array["i0" ] ="<font style='color:orange'>54%</font>";
foreach($tville as $cab => $ville){
    $nb_tot++;

    if($total_diab[$cab]==0){
        $taux="ND";
    }
    else{
        $taux=round($plus3[$cab]/$total_diab[$cab]*100);
    }

    if($taux>=65){
        $nb_ok++;
    }
}
$array["c0" ] = "$nb_ok"."/"."$nb_tot";


$taux=round($plus3["tot"]/$total_diab["tot"]*100);

if($taux>=65){
    $color="green";
}
else{
    if($taux>=54)
        $color="orange";
    else

        $color="red";
}
$array["m0" ] = "<font style='color:$color'>$taux%</font>";
$plus3["tot"]=0;
$nbv=0;
foreach($tville as $cab => $ville){
    if($total_diab[$cab]==0){
        $taux="ND";
    }
    else{
        $taux=round($plus3[$cab]/$total_diab[$cab]*100);
    }

    if($taux>=65){
        $color="green";
    }
    else{
        if($taux>=54)
            $color="orange";
        else
            $color="red";
    }
    $nbv = $nbv+1;
    $array["v".$nbv ] ="<font style='color:$color'>$taux% ($plus3[$cab]/$total_diab[$cab])</font>";
    $plus3[$cab]=0;
}

array_push($result, $array);
$array["r0" ] = "Patients diab&eacute;tiques type II - HbA1C 8,5% -  Part des patients diab&eacute;tiques de type II ".
    "vous ayant d&eacute;clar&eacute; comme m&eacute;decin traitant et dont le r&eacute;sultat de dosage d'HbA1c &lt; 8.5 %.";

$array["ci0"] ="<font style='color:green'>90%</font>";
$array["i0" ] ="<font style='color:orange'>80%</font>";
//Patients avec au moins un suivi


$nb_ok=0;
foreach($tville as $cab => $ville){
    if($total_diab[$cab]==0){
        $taux="ND";
    }
    else{
        $taux=round($hba1c85[$cab]/$total_diab[$cab]*100);
    }

    if($taux>=90){
        $nb_ok++;
    }
}

$array["c0" ] = "$nb_ok"."/"."$nb_tot";

$taux=round($hba1c85["tot"]/$total_diab["tot"]*100);
if($taux>=90){
    $color="green";
}
else{
    if($taux>=80)
        $color="orange";
    else
        $color="red";
}
$array["m0" ] = "<font style='color:$color'>$taux%</font>";
$nbv=0;

foreach($tville as $cab => $ville){
    if($total_diab[$cab]==0){
        $taux="ND";
    }
    else{
        $taux=round($hba1c85[$cab]/$total_diab[$cab]*100);
    }
    if($taux>=90){
        $color="green";
    }
    else{
        if($taux>=80)
            $color="orange";
        else
            $color="red";
    }
    $nbv = $nbv+1;
    $array["v".$nbv ] = "<font style='color:$color'>$taux% ($hba1c85[$cab]/$total_diab[$cab])</font>";
}

array_push($result, $array);

$array["r0" ] = "Patients diab&eacute;tiques type II - HbA1C 7,5% -  Part des patients diab&eacute;tiques de type II ".
    "vous ayant d&eacute;clar&eacute; comme m&eacute;decin traitant et dont le r&eacute;sultat de dosage d'HbA1c &lt; 7.5 %.";

$array["ci0"] ="<font style='color:green'>80%</font>";
$array["i0" ] ="<font style='color:orange'>60%</font>";


$nb_ok=0;
foreach($tville as $cab => $ville){
    if($total_diab[$cab]==0){
        $taux="ND";
    }
    else{
        $taux=round($hba1c75[$cab]/$total_diab[$cab]*100);
    }

    if($taux>=80){
        $nb_ok++;
    }
}

$array["c0" ] = "$nb_ok"."/"."$nb_tot";

$taux=round($hba1c75["tot"]/$total_diab["tot"]*100);

if($taux>=80){
    $color="green";
}
else{
    if($taux>=60)
        $color="orange";
    else
        $color="red";
}
$array["m0" ] = "<font style='color:$color'>$taux%</font>";
$nbv=0;

foreach($tville as $cab => $ville){
    if($total_diab[$cab]==0){
        $taux="ND";
    }
    else{
        $taux=round($hba1c75[$cab]/$total_diab[$cab]*100);
    }

    if($taux>=80){
        $color="green";
    }
    else{
        if($taux>=60)
            $color="orange";
        else
            $color="red";
    }
    $nbv = $nbv+1;
    $array["v".$nbv ] = "<font style='color:$color'>$taux% ($hba1c75[$cab]/$total_diab[$cab])</font>";
}
array_push($result, $array);
$array["r0" ] = "Patients diab&eacute;tiques type II - LDL 1,5% - Part des patients diab&eacute;tiques de type II vous ayant d&eacute;clar&eacute; ".
    "comme m&eacute;decin traitant et dont le r&eacute;sultat de dosage de LDL cholest&eacute;rol est &lt; 1.5g/l.";
$array["ci0"] ="<font style='color:green'>90%</font>";
$array["i0" ] ="<font style='color:orange'>80%</font>";


//Patients avec au moins un suivi

$nb_ok=0;
foreach($tville as $cab => $ville){
    if($total_diab[$cab]==0){
        $taux="ND";
    }
    else{
        $taux=round($ldl15[$cab]/$total_diab[$cab]*100);
    }

    if($taux>=90){
        $nb_ok++;
    }
}

$array["c0" ] = "$nb_ok"."/"."$nb_tot";

$taux=round($ldl15["tot"]/$total_diab["tot"]*100);
$plus3["tot"]=0;
if($taux>=90){
    $color="green";
}
else{
    if($taux>=80)
        $color="orange";
    else
        $color="red";
}
$array["m0" ] = "<font style='color:$color'>$taux%</font>";
$nbv=0;

foreach($tville as $cab => $ville){
    if($total_diab[$cab]==0){
        $taux="ND";
    }
    else{
        $taux=round($ldl15[$cab]/$total_diab[$cab]*100);
    }

    if($taux>=90){
        $color="green";
    }
    else{
        if($taux>=80)
            $color="orange";
        else
            $color="red";
    }
    $nbv = $nbv+1;
    $array["v".$nbv ] = "<font style='color:$color'>$taux% ($ldl15[$cab]/$total_diab[$cab])</font>";
}
array_push($result, $array);

$array["r0" ] = 		"Patients diab&eacute;tiques type II - LDL 1,3% - Part des patients diab&eacute;tiques de type II vous ayant d&eacute;clar&eacute; ".
    "comme m&eacute;decin traitant et dont le r&eacute;sultat de dosage de LDL cholest&eacute;rol est &lt; 1.3g/l.";
$array["ci0"] ="<font style='color:green'>80%</font>";
$array["i0" ] ="<font style='color:orange'>65%</font>";

$nb_ok=0;
foreach($tville as $cab => $ville){
    if($total_diab[$cab]==0){
        $taux="ND";
    }
    else{
        $taux=round($ldl13[$cab]/$total_diab[$cab]*100);
    }

    if($taux>=80){
        $nb_ok++;
    }
}

$array["c0" ] = "$nb_ok"."/"."$nb_tot";

$taux=round($ldl13["tot"]/$total_diab["tot"]*100);

if($taux>=80){
    $color="green";
}
else{
    if($taux>=65)
        $color="orange";
    else
        $color="red";
}
$array["m0" ] = "<font style='color:$color'>$taux%</font>";
$nbv=0;

foreach($tville as $cab => $ville){
    if($total_diab[$cab]==0){
        $taux="ND";
    }
    else{
        $taux=round($ldl13[$cab]/$total_diab[$cab]*100);
    }
    $plus3[$cab]=0;
    if($taux>=80){
        $color="green";
    }
    else{
        if($taux>=50)
            $color="orange";
        else
            $color="red";
    }
    $nbv = $nbv+1;
    $array["v".$nbv ] = "<font style='color:$color'>$taux% ($ldl13[$cab]/$total_diab[$cab])</font>";
}

array_push($result, $array);
$array["r0" ] = "Nombre de patients MT trait&eacute;s par antidiab&eacute;tiques et b&eacute;n&eacute;ficiant d'une consultation ".
    "ou d'un examen du fond d'oeil ou d'une r&eacute;tinographie dans les deux ans rapport&eacute; &agrave; ".
    "l'ensemble des patients MT trait&eacute;s par antidiab&eacute;tiques.";
$array["ci0"] ="<font style='color:green'>80%</font>";
$array["i0" ] ="<font style='color:green'>80%</font>";
$nb_ok=0;
foreach($tville as $cab => $ville){
    if($total_diab[$cab]==0){
        $taux="ND";
    }
    else{
        $taux=round($fond[$cab]/$total_diab[$cab]*100);
    }

    if($taux>=80){
        $nb_ok++;
    }
}

$array["c0" ] = "$nb_ok"."/"."$nb_tot";

$taux=round($fond["tot"]/$total_diab["tot"]*100);

$plus3["tot"]=0;
if($taux>=80){
    $color="green";
}
else{
    $color="red";
}

$array["m0" ] = "<font style='color:$color'>$taux%</font>";
$nbv=0;

foreach($tville as $cab => $ville){
    if($total_diab[$cab]==0){
        $taux="ND";
    }
    else{
        $taux=round($fond[$cab]/$total_diab[$cab]*100);
    }
    $plus3[$cab]=0;
    if($taux>=80){
        $color="green";
    }
    else{
        $color="red";
    }
    $nbv = $nbv+1;
    $array["v".$nbv ] = "<font style='color:$color'>$taux% ($fond[$cab]/$total_diab[$cab])</font>";
}
array_push($result, $array);

$array["r0" ] = 		"Part de patients MT trait&eacute;s par antihypertenseurs dont la pression art&eacute;rielle est &lt;= &agrave; 140 / 90 mmHg";
$array["ci0"] ="<font style='color:green'>60%</font>";
$array["i0" ] ="<font style='color:orange'>50%</font>";
$nb_ok=0;
foreach($tville as $cab => $ville){
    if($total_diab[$cab]==0){
        $taux="ND";
    }
    else{
        $taux=round($diast[$cab]/$total_diab[$cab]*100);
    }

    if($taux>=60){
        $nb_ok++;
    }
}

$array["c0" ] = "$nb_ok"."/"."$nb_tot";

$taux=round($diast["tot"]/$total_diab["tot"]*100);
if($taux>=60){
    $color="green";
}
else{
    if($taux>=50)
        $color="orange";
    else
        $color="red";
}
$array["m0" ] = "<font style='color:$color'>$taux%</font>";
$nbv=0;

foreach($tville as $cab => $ville){
    if($total_diab[$cab]==0){
        $taux="ND";
    }
    else{
        $taux=round($diast[$cab]/$total_diab[$cab]*100);
    }

    if($taux>=60){
        $color="green";
    }
    else{
        if($taux>=50)
            $color="orange";
        else
            $color="red";
    }
    $nbv = $nbv+1;
    $array["v".$nbv ] = "<font style='color:$color'>$taux% ($diast[$cab]/$total_diab[$cab])</font>";
}
array_push($result, $array);


echo json_encode($result);


?>