<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function data_uri($file, $mime)
{
    $contents = file_get_contents($file);
    $base64   = base64_encode($contents);
    return ('data:' . $mime . ';base64,' . $base64);
}

/*

*/

require_once ("Config.php");
$config = new Config();

require($config->webservice_path . "/LogAccess.php");
require($config->webservice_path . "/GetUserId.php");
require($config->webservice_path . "/CertRequest.php");
require($config->webservice_path . "/SendSms.php");
require_once("lib/ComposerLibs/vendor/autoload.php");


$con = DoConnect();

$login = $_REQUEST['login'];
$testmode = $_REQUEST['certtest'];
$sendconfirm = $_REQUEST['certsendemail'];


$phase = 0;
$answer = "OK";
$result = true;

while ( ($phase <10) && ($result==true))
{

    switch ($phase)
    {
        case 0:

            $req="SELECT  nom, prenom ,email , telephone ".
                " from identifications where login='$login'";

            $result=mysql_query($req);
            if(!result)
            {
                $answer = mysql_error();

            }
            break;

        case 1:
            list( $nom, $prenom, $email, $telephone)=mysql_fetch_row($result);
            // G?n?rer les demandes

            $auth = GetUserId( $answer);

            $UserID = $auth->Authentifier;
            $ndx = 0;
            $otp ="001234";
            $app = "psaet";
            $demande="";
            $answer="OK";

            if(!isset($testmode))
                CertRequest("psaet.asalee.fr", $UserID,  $app, $login, $email, 3 ,  $answer,  $ndx, $otp );
            //               $answer!="OK" ;
            if($answer!="OK")
                $result = false;
            else
                $result = true;

            break;

        case 2:
//envoi email


            if(isset($sendconfirm))
            {
                /*
                Ancienne version basique


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
                */


                $head =
                    '
<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
<meta name=Generator content="Microsoft Word 14 (filtered)">
<style>
<!--
 /* Font Definitions */
 @font-face
	{font-family:Cambria;
	panose-1:2 4 5 3 5 4 6 3 2 4;}
@font-face
	{font-family:Calibri;
	panose-1:2 15 5 2 2 2 4 3 2 4;}
@font-face
	{font-family:Tahoma;
	panose-1:2 11 6 4 3 5 4 4 2 4;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{margin:0cm;
	margin-bottom:.0001pt;
	font-size:11.0pt;
	font-family:"Calibri","sans-serif";}
h1
	{mso-style-link:"Titre 1 Car";
	margin-top:30.0pt;
	margin-right:0cm;
	margin-bottom:4.0pt;
	margin-left:21.6pt;
	text-indent:-21.6pt;
	border:none;
	padding:0cm;
	font-size:12.0pt;
	font-family:"Cambria","serif";
	color:#365F91;}
h2
	{mso-style-link:"Titre 2 Car";
	margin-top:10.0pt;
	margin-right:0cm;
	margin-bottom:4.0pt;
	margin-left:28.8pt;
	text-indent:-28.8pt;
	border:none;
	padding:0cm;
	font-size:12.0pt;
	font-family:"Cambria","serif";
	color:#365F91;
	font-weight:normal;}
h3
	{mso-style-link:"Titre 3 Car";
	margin-top:10.0pt;
	margin-right:0cm;
	margin-bottom:4.0pt;
	margin-left:36.0pt;
	text-indent:-36.0pt;
	border:none;
	padding:0cm;
	font-size:12.0pt;
	font-family:"Cambria","serif";
	color:#4F81BD;
	font-weight:normal;}
h4
	{mso-style-link:"Titre 4 Car";
	margin-top:10.0pt;
	margin-right:0cm;
	margin-bottom:4.0pt;
	margin-left:43.2pt;
	text-indent:-43.2pt;
	border:none;
	padding:0cm;
	font-size:12.0pt;
	font-family:"Cambria","serif";
	color:#4F81BD;
	font-weight:normal;
	font-style:italic;}
h5
	{mso-style-link:"Titre 5 Car";
	margin-top:10.0pt;
	margin-right:0cm;
	margin-bottom:4.0pt;
	margin-left:50.4pt;
	text-indent:-50.4pt;
	font-size:10.0pt;
	font-family:"Cambria","serif";
	color:#4F81BD;
	font-weight:normal;}
h6
	{mso-style-link:"Titre 6 Car";
	margin-top:14.0pt;
	margin-right:0cm;
	margin-bottom:5.0pt;
	margin-left:57.6pt;
	text-indent:-57.6pt;
	font-size:10.0pt;
	font-family:"Cambria","serif";
	color:#4F81BD;
	font-weight:normal;
	font-style:italic;}
p.MsoHeading7, li.MsoHeading7, div.MsoHeading7
	{mso-style-link:"Titre 7 Car";
	margin-top:16.0pt;
	margin-right:0cm;
	margin-bottom:5.0pt;
	margin-left:64.8pt;
	text-indent:-64.8pt;
	font-size:10.0pt;
	font-family:"Cambria","serif";
	color:#9BBB59;
	font-weight:bold;}
p.MsoHeading8, li.MsoHeading8, div.MsoHeading8
	{mso-style-link:"Titre 8 Car";
	margin-top:16.0pt;
	margin-right:0cm;
	margin-bottom:5.0pt;
	margin-left:72.0pt;
	text-indent:-72.0pt;
	font-size:10.0pt;
	font-family:"Cambria","serif";
	color:#9BBB59;
	font-weight:bold;
	font-style:italic;}
p.MsoHeading9, li.MsoHeading9, div.MsoHeading9
	{mso-style-link:"Titre 9 Car";
	margin-top:16.0pt;
	margin-right:0cm;
	margin-bottom:5.0pt;
	margin-left:79.2pt;
	text-indent:-79.2pt;
	font-size:10.0pt;
	font-family:"Cambria","serif";
	color:#9BBB59;
	font-style:italic;}
p.MsoCaption, li.MsoCaption, div.MsoCaption
	{margin:0cm;
	margin-bottom:.0001pt;
	font-size:9.0pt;
	font-family:"Calibri","sans-serif";
	font-weight:bold;}
p.MsoTitle, li.MsoTitle, div.MsoTitle
	{mso-style-link:"Titre Car";
	margin:0cm;
	margin-bottom:.0001pt;
	text-align:center;
	border:none;
	padding:0cm;
	font-size:30.0pt;
	font-family:"Cambria","serif";
	color:#243F60;
	font-style:italic;}
p.MsoSubtitle, li.MsoSubtitle, div.MsoSubtitle
	{mso-style-link:"Sous-titre Car";
	margin-top:10.0pt;
	margin-right:0cm;
	margin-bottom:45.0pt;
	margin-left:0cm;
	text-align:right;
	font-size:12.0pt;
	font-family:"Calibri","sans-serif";
	font-style:italic;}
a:link, span.MsoHyperlink
	{color:blue;
	text-decoration:underline;}
a:visited, span.MsoHyperlinkFollowed
	{color:purple;
	text-decoration:underline;}
strong
	{letter-spacing:0pt;}
em
	{color:#5A5A5A;
	font-weight:bold;}
p.MsoAcetate, li.MsoAcetate, div.MsoAcetate
	{mso-style-link:"Texte de bulles Car";
	margin:0cm;
	margin-bottom:.0001pt;
	font-size:8.0pt;
	font-family:"Tahoma","sans-serif";}
p.MsoNoSpacing, li.MsoNoSpacing, div.MsoNoSpacing
	{mso-style-link:"Sans interligne Car";
	margin:0cm;
	margin-bottom:.0001pt;
	font-size:10.0pt;
	font-family:"Calibri","sans-serif";}
p.MsoListParagraph, li.MsoListParagraph, div.MsoListParagraph
	{margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:36.0pt;
	margin-bottom:.0001pt;
	font-size:11.0pt;
	font-family:"Calibri","sans-serif";}
p.MsoListParagraphCxSpFirst, li.MsoListParagraphCxSpFirst, div.MsoListParagraphCxSpFirst
	{margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:36.0pt;
	margin-bottom:.0001pt;
	font-size:11.0pt;
	font-family:"Calibri","sans-serif";}
p.MsoListParagraphCxSpMiddle, li.MsoListParagraphCxSpMiddle, div.MsoListParagraphCxSpMiddle
	{margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:36.0pt;
	margin-bottom:.0001pt;
	font-size:11.0pt;
	font-family:"Calibri","sans-serif";}
p.MsoListParagraphCxSpLast, li.MsoListParagraphCxSpLast, div.MsoListParagraphCxSpLast
	{margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:36.0pt;
	margin-bottom:.0001pt;
	font-size:11.0pt;
	font-family:"Calibri","sans-serif";}
p.MsoQuote, li.MsoQuote, div.MsoQuote
	{mso-style-link:"Citation Car";
	margin:0cm;
	margin-bottom:.0001pt;
	font-size:10.0pt;
	font-family:"Cambria","serif";
	color:#5A5A5A;
	font-style:italic;}
p.MsoIntenseQuote, li.MsoIntenseQuote, div.MsoIntenseQuote
	{mso-style-link:"Citation intense Car";
	margin-top:16.0pt;
	margin-right:72.0pt;
	margin-bottom:16.0pt;
	margin-left:72.0pt;
	line-height:125%;
	background:#4F81BD;
	border:none;
	padding:0cm;
	font-size:12.0pt;
	font-family:"Cambria","serif";
	color:white;
	font-style:italic;}
span.MsoSubtleEmphasis
	{color:#5A5A5A;
	font-style:italic;}
span.MsoIntenseEmphasis
	{color:#4F81BD;
	font-weight:bold;
	font-style:italic;}
span.MsoSubtleReference
	{color:windowtext;
	text-decoration:underline;}
span.MsoIntenseReference
	{color:#76923C;
	font-weight:bold;
	text-decoration:underline;}
span.MsoBookTitle
	{font-family:"Cambria","serif";
	color:windowtext;
	font-weight:bold;
	font-style:italic;}
p.MsoTocHeading, li.MsoTocHeading, div.MsoTocHeading
	{margin-top:30.0pt;
	margin-right:0cm;
	margin-bottom:4.0pt;
	margin-left:0cm;
	border:none;
	padding:0cm;
	font-size:12.0pt;
	font-family:"Cambria","serif";
	color:#365F91;
	font-weight:bold;}
span.Titre1Car
	{mso-style-name:"Titre 1 Car";
	mso-style-link:"Titre 1";
	font-family:"Cambria","serif";
	color:#365F91;
	font-weight:bold;}
span.Titre2Car
	{mso-style-name:"Titre 2 Car";
	mso-style-link:"Titre 2";
	font-family:"Cambria","serif";
	color:#365F91;}
span.Titre3Car
	{mso-style-name:"Titre 3 Car";
	mso-style-link:"Titre 3";
	font-family:"Cambria","serif";
	color:#4F81BD;}
span.Titre4Car
	{mso-style-name:"Titre 4 Car";
	mso-style-link:"Titre 4";
	font-family:"Cambria","serif";
	color:#4F81BD;
	font-style:italic;}
span.Titre5Car
	{mso-style-name:"Titre 5 Car";
	mso-style-link:"Titre 5";
	font-family:"Cambria","serif";
	color:#4F81BD;}
span.Titre6Car
	{mso-style-name:"Titre 6 Car";
	mso-style-link:"Titre 6";
	font-family:"Cambria","serif";
	color:#4F81BD;
	font-style:italic;}
span.Titre7Car
	{mso-style-name:"Titre 7 Car";
	mso-style-link:"Titre 7";
	font-family:"Cambria","serif";
	color:#9BBB59;
	font-weight:bold;}
span.Titre8Car
	{mso-style-name:"Titre 8 Car";
	mso-style-link:"Titre 8";
	font-family:"Cambria","serif";
	color:#9BBB59;
	font-weight:bold;
	font-style:italic;}
span.Titre9Car
	{mso-style-name:"Titre 9 Car";
	mso-style-link:"Titre 9";
	font-family:"Cambria","serif";
	color:#9BBB59;
	font-style:italic;}
span.TitreCar
	{mso-style-name:"Titre Car";
	mso-style-link:Titre;
	font-family:"Cambria","serif";
	color:#243F60;
	font-style:italic;}
span.Sous-titreCar
	{mso-style-name:"Sous-titre Car";
	mso-style-link:Sous-titre;
	font-style:italic;}
span.SansinterligneCar
	{mso-style-name:"Sans interligne Car";
	mso-style-link:"Sans interligne";}
span.CitationCar
	{mso-style-name:"Citation Car";
	mso-style-link:Citation;
	font-family:"Cambria","serif";
	color:#5A5A5A;
	font-style:italic;}
span.CitationintenseCar
	{mso-style-name:"Citation intense Car";
	mso-style-link:"Citation intense";
	font-family:"Cambria","serif";
	color:white;
	background:#4F81BD;
	font-style:italic;}
span.TextedebullesCar
	{mso-style-name:"Texte de bulles Car";
	mso-style-link:"Texte de bulles";
	font-family:"Tahoma","sans-serif";}
.MsoChpDefault
	{font-size:10.0pt;
	font-family:"Calibri","sans-serif";}
@page WordSection1
	{size:595.3pt 841.9pt;
	margin:70.85pt 70.85pt 70.85pt 70.85pt;}
div.WordSection1
	{page:WordSection1;}
 /* List Definitions */
 ol
	{margin-bottom:0cm;}
ul
	{margin-bottom:0cm;}
-->
</style>

</head>

';


//$image1=  '<img border="0" width="510" height="364"  src="'.data_uri('image001.jpg','image/jpg').'" alt="image001" />' ;
//$image2 = '<img border="0" width="556" height="391"  src="'.data_uri('image002.jpg','image/jpg').'" alt="image002" />' ;;
                $image1 = '<img border="0" width="340" height="240"  src="http://www.easeit.fr/downloads/image001.jpg" />' ;;
                $image2 = '<img border="0" width="370" height="260"  src="http://www.easeit.fr/downloads/image002.jpg" />' ;;

//file_put_contents ( "image1.txt" , $image1); 
//file_put_contents ( "image2.txt" , $image2);
                $body = "
<body lang=FR link=blue vlink=purple>

<div class=WordSection1>

<p class=MsoNormal>Bonjour</p>

<p class=MsoNormal>&nbsp;</p>

<p class=MsoNormal>Voici la procédure pour récupérer votre certificat et
ensuite accéder à PSA.</p>

<ul type=disc>
 <li class=MsoNormal>Allez sur le site&nbsp;<a href=\"https://cert.idshost.fr/\"><span
     style='color:windowtext'><b>https://cert.idshost.fr</b></span></a></li>
 <li class=MsoNormal>Cliquer à droite sur le logo IDS (écrit en grand)<br>$image1</li>
 <li class=MsoNormal>Le site analyse votre ordinateur et coche successivement
     en vert :</li>
 <ul type=circle>
  <li class=MsoNormal>votre système d'exploitation</li>
  <li class=MsoNormal>votre navigateur</li>
  <li class=MsoNormal>la présence d'un composant d'inscription de certificats</li>
 </ul>
 <li class=MsoNormal>Cliquez sur '&nbsp;continuer&nbsp;'</li>
 <li class=MsoNormal>Entrez votre identifiant&nbsp;: <a
     href=\"mailto:$email\"><span style='color:windowtext'><b>$email</b></span></a>
     &nbsp;et votre code de retrait qui vous a été envoyé à l'instant par SMS  </li>
 <li class=MsoNormal>Cliquez sur OK (éventuellement plusieurs fois en cas de demandes successives de
     messages de confirmation)</li>
 <li class=MsoNormal>A la fin de la procédure vous devez voir s'afficher à fin
     de l'installation du certificat</li>
 <li class=MsoNormal>Note : Si vous utilisez un ordinateur Apple, une étape
     supplémentaire est nécessaire pour valider la mise en ?uvre de votre
     certificat, pour cela :</li>
 <ul type=circle>
  <li class=MsoNormal>Ouvrir le à finder à en cliquant sur l'icône à finder,
      en bas à gauche</li>
  <li class=MsoNormal>Dans la fenêtre qui s'ouvre, cliquer sur l'icone à petite
      maison à droite de la fenêtre qui s'ouvre, puis cliquer sur
      téléchargement 11. Dans la liste qui s'affiche, vous retrouvez le
      certificat: double cliquer sur le certificat du type à <b>03$login</b>
      (ASALEE)</li>
 </ul>
</ul>
   <br />
   <br />
<p class=MsoNormal>Vous pouvez désormais vous connecter au Portail de Services
Asalée en suivant la procédure suivante&nbsp;:</p>

<p class=MsoListParagraphCxSpFirst style='text-indent:-18.0pt;text-autospace:
none'><span style='font-family:Symbol'>*</span><span style='font-family:\"Times New Roman\",\"serif\"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span>Allez sur le site <a href=\"https://psa.asalee.fr\"><span
style='color:windowtext'><b>https://psa.asalee.fr</b></span></a></p>

<p class=MsoListParagraphCxSpMiddle style='text-indent:-18.0pt;text-autospace:
none'><span style='font-family:Symbol'>*</span><span style='font-family:\"Times New Roman\",\"serif\"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span>Choisissez le certificat du type à <b>03$login</b> (ASALEE) à et cliquez sur
OK</p>

<p class=MsoListParagraphCxSpMiddle style='text-indent:-18.0pt;text-autospace:
none'><span style='font-family:Symbol'>*</span><span style='font-family:\"Times New Roman\",\"serif\"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span>Cliquez de nouveau sur OK si la fenêtre de choix du certificat
réapparaît</p>

<p class=MsoListParagraphCxSpMiddle style='text-indent:-18.0pt;text-autospace:
none'><span style='font-family:Symbol'>*</span><span style='font-family:\"Times New Roman\",\"serif\"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span>Cliquez sur l'icône représentant une personne et une cocarde IDS (celui
du milieu)</p>

<p class=MsoListParagraphCxSpMiddle style='text-indent:-18.0pt;text-autospace:
none'>$image2</p>

<p class=MsoListParagraphCxSpMiddle style='text-indent:-18.0pt;text-autospace:
none'><span style='font-family:Symbol'>*</span><span style='font-size:7.0pt;
font-family:\"Times New Roman\",\"serif\"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span>Dans la page qui s'affiche, votre nom apparaît comme identifiant. 
Si vous avez reçu un mot de passe par SMS en même temps que cet email, saisissez-le, sinon saisissez le mot de passe 
que vous utilisez déjà pour vos certificats Asalée depuis vos autres postes. Validez.
<br />
Note : conservez bien ce mot de passe qui vous sera systématiquement nécessaire pour accéder à PSA dans le futur.
</p>

<p class=MsoListParagraphCxSpMiddle style='text-indent:-18.0pt'><span
style='font-family:Symbol'>*</span><span style='font-size:7.0pt;font-family:\"Times New Roman\",\"serif\"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>s'affiche
alors la page d'accueil du site PSA, entrez l'identifiant&nbsp;: *&nbsp;nom du
cabinet&nbsp;*, et le mot de passe du cabinet *&nbsp;mot de passe du
cabinet&nbsp;*</p>

<p class=MsoListParagraphCxSpLast style='text-indent:-18.0pt'><span
style='font-size:10.0pt;font-family:Symbol'>*</span><span style='font-size:
7.0pt;font-family:\"Times New Roman\",\"serif\"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span>Vous avez accès au contenu relatif à votre cabinet</p>
<br />
<br />
<p class=MsoNormal>Au cas où vous auriez besoin de vous connecter un jour à PSA
depuis un ordinateur non équipé d'un certificat, voici la procédure à
suivre&nbsp;:</p>

<ul type=disc>
 <li class=MsoNormal>Se connecter à <a href=\"https://psa.asalee.fr\"><span
     style='color:windowtext'><b>https://psa.asalee.fr</b></span></a></li>
 <li class=MsoNormal>Si un écran vous propose de choisir un certificat, cliquer
     sur annuler (sauf si dans la liste apparaît le certificat du type
     <b>03$login</b> (ASALEE), dans ce cas, ceci voudrait dire qu'un
     certificat Asalée a déjà été installé et la présente procédure est sans
     objet)</li>
 <li class=MsoNormal>Dans la page qui s'affiche entrer votre identifiant
     (<b>$login</b>) et le mot de passe IDS reçu par SMS lors de votre
     arrivée (cf ci-dessus)</li>
 <li class=MsoNormal>Un nouveau SMS vous est alors envoyé par un robot avec un code à usage unique de 6 chiffres: entrer ce code dans la case&nbsp;* code de confirmation </li>
 <li class=MsoNormal>s'affiche alors la page d'accueil du site PSA, entrez
     l'identifiant et le mot de passe du cabinet auquel vous souhaitez accéder</li>
</ul>
  <br />
  <br />
  
 <p class=MsoNormal> En cas de problème pour l'installation du certificat, vous pouvez contacter
 <a href=\"mailto:support.informatique@asalee.fr\" >support.informatique@asalee.fr. </a>
 
 </p>
 <br />
<p class=MsoNormal>Cordialement</p>

<p class=MsoNormal>L'équipe Asalée</p>

</div>

</body>

";

                $demande =       "<html>". $head. $body.  "</html>";



                $esujet = 'Demande de Certificat';

                $emessage = $demande;
                $edestinataire = $email;
                $answer = $email;
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
                    $answer =  "Email transmis pour: ". $email;
                }
                catch (Exception $e)
                {
                    $answer =  "Mauvais email transmis pour: ".$email;
                    error_log('Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
                }

            }

            break;
        case 3:
            if (isset($sendconfirm))
            {
                $smsmes="";
                $smsid = "";
                if(!isset($testmode))
                    SendSms("psaet.asalee.fr", $telephone,  $UserID ,  "Asalee",

                        "Equipe Asalee \n\n".
                        "Code Retrait Certificat:\n $otp",


                        $smsmes , $smsid);

                $answer = $answer . "\n". "Envoi Sms: $smsmes , id: $smsid";


            }
            break;
        case 4:
            if(!isset($testmode))
            {
                $req="INSERT INTO `certificats`(`owner`, `ownermail`, `organisation`, `token`, `lot`) VALUES ('".$login."','".$email."','".$app."','".$otp."',$ndx)";
                $res=mysql_query($req);
                if(!res)
                    $answer = $answer."\nerreur SQL:".mysql_error()."\n";

            }
            break;



    }
    $phase++;
}




// Log WebService


LogAccess("psaet.asalee.fr", "gencert", $UserID, 'na', $infirmiere, 1, "Demande de certificat utilisateur ". $login.":".$answer);

//   echo json_encode(array('success'=>true));

if($result==true)
{
    echo json_encode(array(utf8_decode('success')=>true,
        utf8_encode('msg')=>utf8_encode($answer),
        utf8_encode('otp')=>utf8_encode($otp),
        utf8_encode('index')=>utf8_encode($ndx)
    ));
//      error_log($answer);
}
else
{
    echo json_encode(array(utf8_encode('msg')=>utf8_encode($answer)));
}
?>
