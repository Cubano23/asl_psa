<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("lib/ComposerLibs/vendor/autoload.php");

session_start();
if(!isset($_SESSION['nom'])) {
    # pas passé par l'identification
    $debut=dirname($_SERVER['PHP_SELF']);
    $self=basename($_SERVER['PHP_SELF']);
    header("Location: $debut/ident_util.php?url=$self");
    echo "<a href='$debut/ident_util.php?url=$self'>cliquez ici</a>";
    exit;
}

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
require($config->webservice_path . "/LogAccess.php");
require($config->webservice_path . "/GetUserId.php");
require($config->webservice_path . "/CertRequest.php");



# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
die("Impossible de se connecter à la base");


$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
//echo $loc;
require("../global/entete.php");
//echo $loc;

entete_asalee("Demander un certificat cabinet");


# boucle principale
do {
    $repete=false;


    # étape 0 : sélection du cabinet
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


//Sélection du cabinet
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
        "<tr><td>Sélectionner un cabinet : </td>".
        "<td>".
        "<SELECT name='cabinet'><option value=''></option>";

    $req="SELECT cabinet, nom_cab ".
        "FROM account ".
        "WHERE infirmiere!='' and region!='' ".
        "ORDER BY nom_cab";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cabinet, $nom_cab)=mysql_fetch_row($res)){
        echo "<option value=\"$cabinet\" ";
        echo ">$nom_cab</option>";
    }

    echo "</SELECT></td></tr>
</table><br><br>
<input type='submit' value='Valider'>";

}

//Saisie des infos
function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self;



    if(sizeof($message)==0){
        $cabinet=$_POST["cabinet"];


        if(($cabinet=="")){
            $message[]="Veuillez préciser le cabinet";
        }

        if(sizeof($message)>0){
            $_POST["etape"]=0;
            $repete=true;
            return;
        }

        $req="SELECT  nom_complet, ville, contact, telephone, ".
            "courriel, ".
            "infirmiere, nom_cab, ".
            "region from account where cabinet='$cabinet'";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        list($nom_complet, $ville, $contact, $telephone,
            $email,
            $infirmiere, $nom_cab,
            $region)=mysql_fetch_row($res);
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

    echo "<table border='1'>".
        "<tr><td>Nom d'utilisateur du Certificat:</td>".
        "<td>$cabinet<input type='hidden' name='cabinet' value=\"$cabinet\"></td></tr>".
        "</tr>".
        "<tr><td>Nom complet : </Td>".
        "<td>$nom_complet</td></tr>".
        "<tr><td>Ville : </td>".
        "<td>$ville</td></tr>".
        "<tr><td>Contact recevant l'authentifiant: </td>".
        "<td>$contact </td></tr>".
        "<tr><td>Email : </td>".
        "<td>$email<input type='hidden' name='email' value='$email'></td></tr>".
        "<tr><td>Envoi par Email : </td>".
        "<td><input type='checkbox' name='sendconfirm' value='yes'></td></tr>".
        "<tr><td>Mode test : </td>".
        "<td><input type='checkbox' name='testmode' value='yes'></td></tr>".

        /*

              "<tr><td>Nom de l'infirmière recevant le certificat: </td>".
             "<td>Choisir dans la liste si l'infirmière exerce dans un cabinet déjà créé : ".
             "<SELECT name='infirmiere'><option value=''></option>";

        $req="SELECT infirmiere, courriel, count(*) ".
                 "FROM account ".
                 "WHERE infirmiere!='' ".
                 "GROUP BY infirmiere ".
                 "ORDER BY infirmiere";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            while(list($inf, $cmail, $nb)=mysql_fetch_row($res)){
                echo "<option value=\"$inf\" ";
                if($inf==$infirmiere){
                    echo "selected";
                }
                echo ">$inf"."("."$cmail".")"."</option>";
            }


        echo "</SELECT><br>".
        */
        "<br/>"."";


    echo "".
        "</td></tr></table>";



    echo"<br><br><input type='submit' value='Valider'>";

}

//Enregistrement des modifs
function etape_2(&$repete) {
    global $message, $Dossier, $Cabinet, $deval, $self, $doc;

    extract($_POST);



    if($cabinet==""){
        $message[]="Veuillez préciser le nom du cabinet";
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
    $app = "psa";
    $user = $cabinet;
    $demande="";

    /*	if(!isset($testmode))
                CertRequest("psaet.asalee.fr", $UserID,  $app, $user, $email, 3 ,  $answer,  $ndx, $otp );*/
    if(strcmp("OK", $answer)==0)
    {
        $demande= "Votre demande de certificat cabinet a été prise en compte.".
            "<br/>".
            "<br/>".
            "<table border>".
            "<tr><td>Appliction</td><td>".$app."</td></tr>".
            "<tr><td>Utilisateur</td><td>".$user."</td></tr>".
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
                echo "L'email a bien été envoyé à ".$email;
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
            $req="INSERT INTO `certificats`(`owner`, `ownermail`, `organisation`, `token`, `lot`) VALUES ('".$user."','".$email."','".$app."','".$otp."',$ndx)";
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


    LogAccess("psaet.asalee.fr", "creer_cert.php", $UserID, 'na', $infirmiere, 1, "Demande de certificat cabinet ". $user.":".$answer);
    echo "<input type='button' value='Demander un autre certificat?' onclick='window.open(\"".$_SERVER['PHP_SELF']."\", \"_top\")'>";

}
?>
</body>
</html>
