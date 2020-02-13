<?php
session_start();
if(!isset($_SESSION['nom'])) {
    # pas pass� par l'identification
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
        <title>Fusion de dossiers</title>
    </head>
<body bgcolor=#FFE887>
<?php

require_once "Config.php";
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");

# connexion aux donn�es
mysql_connect($serveur,$idDB,$mdpDB) or
    die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
    die("Impossible de se connecter � la base");

require("./global/entete.php");

$titre="Fusion de dossiers";


entete_asalee($titre);

# initialisations
$nom = "";
$message=array();

# boucle principale
do {
    $repete=false;

    # �tape 1 : identification de l'�tablissement
    if (!isset($_POST['etape'])) {
        etape_1($repete);
    }
    elseif($_POST['etape']==2) {
        # �tape 2  : v�rification du mot de passe et continuation vers l'url
        etape_2($repete);
    }
} while($repete);

exit;

# �tape 1 : identification de l'�tablissement
function etape_1(&$repete) {
    global $message, $nom;

    $cabinet1=$cabinet2=$numero1=$numero2="";
    if(sizeof($message)>0){
        extract($_POST);
    }

    ?>
    <span style="font-family:'Arial',Times,serif">
   <table border="0">
   <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
   <input type="hidden" name="etape" value=2>
   <tr><td width='300'>1- Choisir le cabinet et le num�ro de dossier � fusionner. 
		Ce dossier sera supprim� � l'issue de la proc�dure:</td>
		
		<td><select name="cabinet1"><option value=""></option>

                <?php
                # liste d�roulante des communes  participantes

                $req="select cabinet, nom_cab from account where nom_cab!='' order by nom_cab";
                $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
                while(list($cab, $nom_cab)=mysql_fetch_row($res))  {
                    echo "<option ";
                    if($cabinet1==$cab) echo "selected ";
                    echo "value='$cab'>$nom_cab</option>\n";
                }
                ?>
   </select>
   </td></tr>
   <tr><td>Num�ro de dossier:</td><td><input name="numero1" size=10 type="text" <?php echo "value='$numero1'";?>> 
   </td></tr>
    <tr><td width='300'>2- Choisir le cabinet et le num�ro de dossier � conserver apr�s la fusion. 
		A l'issue de la proc�dure, seul ce dossier sera conserv�:</td>
		
		<td><select name="cabinet2"><option value=''></option>

                <?php
                # liste d�roulante des communes  participantes

                $req="select cabinet, nom_cab from account where nom_cab!='' order by nom_cab";
                $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
                while(list($cab, $nom_cab)=mysql_fetch_row($res))  {
                    echo "<option ";
                    if($cabinet2==$cab) echo "selected ";
                    echo "value='$cab'>$nom_cab</option>\n";
                }
                ?>
   </select>
   </td></tr>
   <tr><td>Num�ro de dossier:</td><td><input name="numero2" size=10 type="text" <?php echo "value='$numero2'";?>> 
   </td></tr>
       <?php
       # affichage des erreurs �ventuelles
       if(sizeof($message)>0) {
           echo "<tr><td colspan=2 align='center'><font color='red'><b>".implode("<br>\n", $message)."</b></font></td></tr>\n";
       }
       ?>
       <tr><td colspan=2 align="center"><input type="submit" value="Valider">
   </td></tr>
   </table>
   A l'issue de la proc�dure, l'ensemble des donn�es saisies dans les protocoles pour le dossier 1 sont transf�r�es sur le dossier 2 et le dossier 1 est ensuite supprim�. Les dossiers 1 et 2 doivent exister.
   La fusion ne se fait que si le sexe et la date de naissance sont identique dans les 2 dossiers.
   </span>
    </div>
    </body>
    </html>
    <?php
}

# �tape 2 : v�rification du mot de passe et continuation vers l'url 
function etape_2(&$repete) {
    global $message, $nom, $self;


    extract($_POST);

    //V�rification si le dossier 1 existe et r�cup�ration de l'id
    $req="SELECT id, sexe, dnaiss from dossier WHERE cabinet='$cabinet1' and numero='$numero1'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    if(mysql_num_rows($res)==0){
        $message[]="Le dossier 1 n'existe pas";
    }
    else{
        list($id1, $sexe1, $dnaiss1)=mysql_fetch_row($res);
    }

    //V�rification si le dossier 2 existe et r�cup�ration de l'id
    $req="SELECT id, sexe, dnaiss from dossier WHERE cabinet='$cabinet2' and numero='$numero2'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    if(mysql_num_rows($res)==0){
        $message[]="Le dossier 2 n'existe pas";
    }
    else{
        list($id2, $sexe2, $dnaiss2)=mysql_fetch_row($res);
    }

    if(($dnaiss1!=$dnaiss2)||($sexe1!=$sexe2)){
        $message[]="Les dossiers 1 et 2 ne sont pas identique (sexe ou date de naissance diff�rents)";
    }
    # retour au formulaire initial en cas d'erreur
    if(sizeof($message)>0) {
        unset($_POST['etape']);
        $repete=true;
        return;
    }


    $req="UPDATE cardio_autre_consult SET id='$id2' where id='$id1'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    echo mysql_affected_rows()." lignes consulation RCVA corrig�es<br>";


    $req="UPDATE cardio_vasculaire_depart SET id='$id2' where id='$id1'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    echo mysql_affected_rows()." lignes formulaire collecte de donn�es RCVA corrig�es<br>";


    $req="UPDATE depistage_colon SET id='$id2' where id='$id1'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    echo mysql_affected_rows()." lignes d�pistage cancer colon corrig�es<br>";


    $req="UPDATE depistage_diabete SET id='$id2' where id='$id1'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    echo mysql_affected_rows()." lignes d�pistage diab�te corrig�es<br>";


    $req="UPDATE depistage_sein SET id='$id2' where id='$id1'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    echo mysql_affected_rows()." lignes d�pistage cancer sein corrig�es<br>";

    $req="UPDATE depistage_uterus SET id='$id2' where id='$id1'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    echo mysql_affected_rows()." lignes d�pistage cancer ut�rus corrig�es<br>";

    $req="SELECT max(numero_eval) from eval_continue WHERE id='$id2'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
    list($max2)=mysql_fetch_row($res);

    $req="SELECT numero_eval, date, suivi, causes, terminologie, ".
        "comprendre_traitement, appliquer_traitement, risques, ".
        "gravite, mesures, appliquer, connaitre_equilibre, ".
        "appliquer_equilibre, activite, autre, dmaj from eval_continue ".
        "WHERE id='$id1'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    echo mysql_affected_rows()." lignes �valuation continue corrig�es<br>";

    while(list($numero_eval, $date, $suivi, $causes, $terminologie,
        $comprendre_traitement, $appliquer_traitement, $risques,
        $gravite, $mesures, $appliquer, $connaitre_equilibre,
        $appliquer_equilibre, $activite, $autre, $dmaj)=mysql_fetch_row($res)){

        $max2++;
        $req2="INSERT into eval_continue set id='$id2', numero_eval='$max2', ".
            "date='$date', suivi='$suivi', causes='$causes', ".
            "terminologie='$terminologie', ".
            "comprendre_traitement='$comprendre_traitement', ".
            "appliquer_traitement='$appliquer_traitement', risques='$risques', ".
            "gravite='$gravite', mesures='$mesures', appliquer='$appliquer', ".
            "connaitre_equilibre='$connaitre_equilibre', ".
            "appliquer_equilibre='$appliquer_equilibre', ".
            "activite='$activite', autre='$autre', dmaj='$dmaj'";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
    }

    $req="UPDATE evaluation_infirmier SET id='$id2' where id='$id1'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    echo mysql_affected_rows()." lignes �valuation infirmi�re corrig�es<br>";

    $req="UPDATE hemocult SET id='$id2' where id='$id1'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    echo mysql_affected_rows()." lignes h�moccult corrig�es<br>";

    $req="UPDATE suivi_diabete SET dossier_id='$id2' where dossier_id='$id1'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    echo mysql_affected_rows()." lignes suivi diab�te corrig�es<br>";

    $req="UPDATE tension_arterielle SET id='$id2' where id='$id1'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    echo mysql_affected_rows()." lignes tension art�rielle corrig�es<br>";

    $req="UPDATE tension_arterielle_moyenne SET id='$id2' where id='$id1'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    echo mysql_affected_rows()." lignes tension art�rielle moyenne corrig�es<br>";

    $req="UPDATE trouble_cognitif SET id='$id2' where id='$id1'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    echo mysql_affected_rows()." lignes troubles cognitifs corrig�es<br>";

    $req="DELETE FROM `dossier` WHERE `id` = '$id1' ";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

    echo "Le dossier $numero1 au cabinet $cabinet1 a �t� supprim� et les donn�es ont �t� transf�r�es sur le dossier $numero2 cabinet $cabinet2.<br><br>";

    echo "<input type='button' value='fusionner un autre dossier' onclick=\"window.open('$self', '_top')\">";
    exit;
}
?>