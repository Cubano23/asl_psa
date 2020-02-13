<?php

require_once ("Config.php");
$config = new Config();




if($_SERVER['APPLICATION_ENV']=='dev-herve'){
    session_start();
    error_log("test");
    session_start();
    $_SESSION['id.prenom']= "Antoine";
    $_SESSION['id.nom']= "Rizk";
    $_SESSION['nom']= "arizk";
    $UserID="ztest";
    //$a =  $_SESSION['allowedcabinets'];
}
else{
    require_once($config->webservice_path . "/GetUserId.php");

    session_start();
    if( ($_SERVER['HTTP_HOST']!="psaet.asalee.fr") && ($_SERVER['HTTP_HOST']!="psaettest.asalee.fr")  ) {
        echo" Option Interdite";
        die;
    }
    if(!isset($_SESSION['nom'])) {
        # pas pass� par l'identification
        $debut=dirname($_SERVER['PHP_SELF']);
        $self=basename($_SERVER['PHP_SELF']);
        header("Location: $debut/ident_util.php?url=$self");
        echo "<a href='$debut/ident_util.php?url=$self'>cliquez ici</a>";
        exit;
    }
    set_time_limit(120);
}




// 03-04-2015 EA Ajout du Niveau  

function getPsaetLevel()
{

    if($_SERVER['APPLICATION_ENV']=='dev-herve'){
        return 1;
    }
    else{
        $level = 0;
        $con = DoConnect();
        $answer="00";
        $auth = GetUserId( $answer);
        $UserID = substr($auth->Authentifier,2);

        $query= "select psaet from habilitations where login='$UserID' ";
        $result=mysql_query($query, $con) or die("erreur SQL:".mysql_error()."<br />$req");

        if($row = mysql_fetch_array( $result,MYSQL_BOTH ))
        {
            $level =  $row['psaet'];
        }

        mysql_close($con);

        return $level;
    }


    return 1;

}


?>