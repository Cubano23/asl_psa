<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once('filterdomain.php');
require_once("lib/ComposerLibs/vendor/autoload.php");

$admin_level = getPsaetLevel();
if( ($admin_level!=1) && ($admin_level!=2))
{
    echo" Option Interdite";
    die;
}

/*
	session_start();
if(!isset($_SESSION['nom'])) {
	# pas passé par l'identification
	$debut=dirname($_SERVER['PHP_SELF']);
	$self=basename($_SERVER['PHP_SELF']);
    header("Location: $debut/ident_util.php?url=$self");
    echo "<a href='$debut/ident_util.php?url=$self'>cliquez ici</a>";
	exit;
}
*/





?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="content-type"
          content="text/html; charset=ISO-8859-15">
    <title>Demander un Certificat Cabinet</title>
</head>
<body bgcolor=#FFE887>
<?php
require_once ("Config.php");
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php") ;
require_once($config->webservice_path . "/LogAccess.php");
require_once($config->webservice_path . "/GetUserId.php");
require_once($config->webservice_path . "/CertRequest.php");



# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
$DB="annuaire";
mysql_select_db($DB) or
die("Impossible de se connecter à la base");


$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
//echo $loc;
require("../global/entete.php");
//echo $loc;

entete_asalee("Demander un certificat utilisateur");


# boucle principale
do {
    $repete=false;


    # étape 0 : sélection de l'utilisateur
    if (!isset($_POST['etape'])) {
        etape_0($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

            case 0://sélection du cabinet

                etape_0($repete);
                break;

            case 1://saisie des infos
                etape_1($repete);
                break;

            # étape 2  : enregistrement des infos
            case 2:
                etape_2($repete);
                break;

        }
    }
} while($repete);

# fin de traitement principal


//Sélection de l'utilisateur
function etape_0(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self;

    if(sizeof($message)>0){
        echo "<font style='color:red'><b>";
        foreach($message as $m){
            echo $m."<br>";
        }
        echo "</font>";
    }

    echo "<form action=".$_SERVER['PHP_SELF']." method='post' enctype='multipart/form-data'>
<input type='hidden' name='etape' value='1'>";

    echo "<table border='1'>".
        "<tr><td>Sélectionner un utilisateur : </td>".
        "<td>".
        "<SELECT name='login'><option value=''></option>";
    /*
    $req="SELECT user, nom_complet ".
             "FROM account_psaet ".
             "WHERE user!='' and courriel!='' and nom_complet!='' ".
             "ORDER BY user";
    */
    $req="SELECT login, nom, prenom ".
        "FROM identifications ".
        "WHERE login!='' and email!='' ".
        "ORDER BY prenom, nom";


    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($login, $nom, $prenom)=mysql_fetch_row($res)){
        echo "<option value=\"$login\" ";
        $nom_complet = $prenom. " ". $nom;
        echo ">$nom_complet</option>";
    }

    echo "</SELECT></td></tr>
</table><br><br>
<input type='submit' value='Valider'>";

}

//Saisie des infos
function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self;



    if(sizeof($message)==0){
        $login=$_POST["login"];


        if(($login=="")){
            $message[]="Veuillez préciser l'utilisateur";
        }

        if(sizeof($message)>0){
            $_POST["etape"]=0;
            $repete=true;
            return;
        }
        /*
            $req="SELECT  nom_complet,  ".
                 "courriel ".
                 " from account_psaet where user='$user'";
        */
        $req="SELECT  nom, prenom ,  ".
            "email ".
            " from identifications where login='$login'";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        list($nom, $prenom,
            $email)=mysql_fetch_row($res);

        $nom_complet = $prenom. " ". $nom;

    }
    else{
        echo "<b><font style='color:red'>";

        foreach($message as $m){
            echo $m."<br>";
        }
        echo "</b></font>";
        extract($_POST);
    }


    echo "<form action=".$_SERVER['PHP_SELF']." method='post' enctype='multipart/form-data'>
<input type='hidden' name='etape' value='2'>";
    $nom_complet = $prenom. " ". $nom;
    echo "<table border='1'>".
        "<tr><td>Nom d'utilisateur du Certificat:</td>".
        "<td>$login<input type='hidden' name='login' value=\"$login\"></td></tr>".
        "</tr>".
        "<tr><td>Nom complet : </Td>".
        "<td>$nom_complet</td></tr>".
        "<tr><td>Email : </td>".
        "<td>$email<input type='hidden' name='email' value='$email'></td></tr>".
        "<tr><td>Envoi par Email : </td>".
        "<td><input type='checkbox' name='sendconfirm' value='yes'></td></tr>".
        "<tr><td>Mode test : </td>".
        "<td><input type='checkbox' name='testmode' value='yes'></td></tr>".

        "<br/>"."";


    echo "".
        "</td></tr></table>";



    echo"<br><br><input type='submit' value='Valider'>";

}

//Enregistrement des modifs
function etape_2(&$repete) {
    global $message, $Dossier, $Cabinet, $deval, $self, $doc;

    extract($_POST);



    if($login==""){
        $message[]="Veuillez préciser le nom de l'utilisateur";
    }

    if(sizeof($message)>0){
        $_POST["etape"]=1;
        $repete=true;
        return;
    }

// Générer les demandes
    $answer="OK";
    $auth = GetUserId( $answer);

    $UserID = $auth->Authentifier;
    $ndx = 0;
    $otp ="001234";
    $app = "psaet";
    $demande="";

    if(!isset($testmode))
        CertRequest("psaet.asalee.fr", $UserID,  $app, $login, $email, 3 ,  $answer,  $ndx, $otp );
    if(strcmp("OK", $answer)==0)
    {
        $demande= "Votre demande de certificat utilisateur a été prise en compte.".
            "<br/>".
            "<br/>".
            "<table border>".
            "<tr><td>Appliction</td><td>".$app."</td></tr>".
            "<tr><td>Utilisateur</td><td>".$login."</td></tr>".
            "<tr><td>email</td><td>".$email."</td></tr>".
            "<tr><td>Authentifiant</td><td>".$otp."</td></tr>".
            "<tr><td>Index</td><td>".$ndx."</td></tr>".
            "</table>";
        echo $demande;

        echo "<br />";
//envoi email
        if(isset($sendconfirm))
        {

            $esujet = 'Demande de Certificat';
            $emessage = "Bonjour,<br />".$demande.
                "<br />".
                "Merci<br /> Equipe Asalée.";
            $edestinataire = $email;
            /*
             * NEW MAILER
             */
            $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
            try
            {
                //Recipients
                $mail->setFrom('contact@asalee.fr', 'Equipe Asalée');
                $mail->AddReplyTo('contact@asalee.fr', 'Equipe Asalée');
                $mail->addAddress($edestinataire);

                //Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = $esujet;
                $mail->Body    = $emessage;

                $mail->send();
                echo "L'email a bien été envoyé à ". $email;
            }
            catch (Exception $e)
            {
                echo "Une erreur s'est produite lors de l'envois de l'email.";
                error_log('Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
            }

            echo "<br/>";

        }
// Insérer dans la base
        if(!isset($testmode))
        {
            $req="INSERT INTO `certificats`(`owner`, `ownermail`, `organisation`, `token`, `lot`) VALUES ('".$login."','".$email."','".$app."','".$otp."',$ndx)";
            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
        }

        echo "<input type='button' value='Imprimer' onclick='window.print();'>";
    }
    else
    {
        echo "Votre demande échoué. Raison:".$answer;
        echo "<br/>";
    }
// Log WebService


    LogAccess("psaet.asalee.fr", "creer_cert", $UserID, 'na', $infirmiere, 1, "Demande de certificat utilisateur ". $login.":".$answer);
    echo "<input type='button' value='Demander un autre certificat?' onclick='window.open(\"".$_SERVER['PHP_SELF']."\", \"_top\")'>";

}
?>
</body>
</html>
