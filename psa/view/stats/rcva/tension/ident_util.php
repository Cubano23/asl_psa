<?php
# saisie d'un identifiant d'utilisateur (cabinet)

session_start();

# paramétrage
require_once ("Config.php");
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");

# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
mysql_select_db('informed3') or
die("Impossible de se connecter à la base");
$mysql_connecte=true;

# initialisations
$nom = "";
$message=array();

# boucle principale
do {
    $repete=false;

    # étape 1 : identification de l'établissement
    if (!isset($_POST['etape'])) {
        etape_1($repete);
    }
    elseif($_POST['etape']==2) {
        # étape 2  : vérification du mot de passe et continuation vers l'url
        etape_2($repete);
    }
} while($repete);

exit;

# étape 1 : identification de l'établissement
function etape_1(&$repete) {
    global $message, $nom;
    ?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
    <html>
    <head>
        <title>Identification</title>
        <meta content="text/html; charset=ISO-8859-15" http-equiv="content-type">
    </head>
    <body bgcolor=#FFE887><div align="center"><br/>
        <!--  onLoad="javascript:self.resizeTo(220,280)" -->
        <?php

        # paramètres
        if(isset($_REQUEST['url']))
            $url=$_REQUEST['url'];
        elseif(isset($_SERVER['HTTP_REFERER']))
            $url=$_SERVER['HTTP_REFERER'];
        else die('paramètre manquant</div>');

        ?>
        <span style="font-family:'Arial',Times,serif">
   <p align='center'><b>Identification<b></p> 
   <table border="0">
   <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
   <input type="hidden" name="etape" value=2>
   <input type="hidden" name="url" value="<?php echo $url;?>">
   <tr><td>identifiant:</td><td><select name="nom">

   <?php
   # liste déroulante des communes  participantes

   $req="select cabinet from account order by cabinet";
   $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
   while(list($lenom)=mysql_fetch_row($res))  {
       echo '<option';
       if ($nom==$lenom) echo ' selected';
       echo ">$lenom</option>\n";
   }
   ?>
   </select>
   </td></tr>
   <tr><td>mot de passe:</td><td><input name="MotDePasse" size=10 maxsize=10 type="password"> 
   </td></tr>
       <?php
       # affichage des erreurs éventuelles
       if(sizeof($message)>0) {
           echo "<tr><td colspan=2 align='center'><font color='red'><b>".implode("<br>\n", $message)."</b></font></td></tr>\n";
       }
       ?>
       <tr><td colspan=2 align="center"><input type="submit" value="Valider">
   </td></tr>
   </table>
   </span>
    </div>
    </body>
    </html>
    <?php
}

# étape 2 : vérification du mot de passe et continuation vers l'url 
function etape_2(&$repete) {
    global $message, $nom;

    # récupération des données de l'étape précédente
    foreach(array('nom','MotDePasse', 'url') as $val)
        $$val=$_POST[$val];

    #vérification de l'identifiant
    $req="select password from account where cabinet='$nom'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
    #echo "$req<br>";
    if (mysql_num_rows($res)<>1) {
        $message[]="Le cabinet $nom n'est pas défini";
    }
    else {
        list($mdpasse)=mysql_fetch_row($res);
        if ($mdpasse !== $MotDePasse) {
            $message[]="Le mot de passe est erroné";
        }
    }
    # retour au formulaire initial en cas d'erreur
    if(sizeof($message)>0) {
        unset($_POST['etape']);
        $repete=true;
        return;
    }
    # mise en session des identifiants
    $_SESSION['nom']=$nom;

    /*
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
    exit;
    */

# passage à l'url demandée
    header("Location: $url");
    exit;
}
?>