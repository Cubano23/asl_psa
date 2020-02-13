<?php
# saisie d'un identifiant d'utilisateur (cabinet)

session_start();

# param�trage
require_once ("Config.php");
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");

# connexion aux donn�es
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
mysql_select_db('informed3') or
die("Impossible de se connecter � la base");
$mysql_connecte=true;

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

        # param�tres
        if(isset($_REQUEST['url']))
            $url=$_REQUEST['url'];
        elseif(isset($_SERVER['HTTP_REFERER']))
            $url=$_SERVER['HTTP_REFERER'];
        else die('param�tre manquant</div>');

        ?>
        <span style="font-family:'Arial',Times,serif">
   <p align='center'><b>Identification<b></p> 
   <table border="0">
   <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
   <input type="hidden" name="etape" value=2>
   <input type="hidden" name="url" value="<?php echo $url;?>">
   <tr><td>identifiant:</td><td><select name="nom">

   <?php
   # liste d�roulante des communes  participantes

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
       # affichage des erreurs �ventuelles
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

# �tape 2 : v�rification du mot de passe et continuation vers l'url 
function etape_2(&$repete) {
    global $message, $nom;

    # r�cup�ration des donn�es de l'�tape pr�c�dente
    foreach(array('nom','MotDePasse', 'url') as $val)
        $$val=$_POST[$val];

    #v�rification de l'identifiant
    $req="select password from account where cabinet='$nom'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
    #echo "$req<br>";
    if (mysql_num_rows($res)<>1) {
        $message[]="Le cabinet $nom n'est pas d�fini";
    }
    else {
        list($mdpasse)=mysql_fetch_row($res);
        if ($mdpasse !== $MotDePasse) {
            $message[]="Le mot de passe est erron�";
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

# passage � l'url demand�e
    header("Location: $url");
    exit;
}
?>