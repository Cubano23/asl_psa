<?php

/*   $base=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
   require("$base/inclus/accesbase.inc.php");

   # connexion aux données
   mysql_connect($serveur,$idDB,$mdpDB) or 
      die("Impossible de se connecter au SGBD");
   mysql_select_db($DB) or 
      die("Impossible de se connecter à la base");
*/
# initialisations
$nbar=25;
$fact=5;
$moisdeb=1;
$moisfin=date("m");
$andeb=$anfin=date("Y");
$message=array();

# boucle principale
$repete=false;
do {

    # étape 1 : identification du patient
    if (!isset($_POST['etape'])) {
        etape_2($repete);
        exit;
    }
    etape_2($repete);

} while($repete);

exit;



# deuxieme étape du formulaire : saisie du type de bilan
function etape_2(&$repete) {
    global $message, $IdCabinet, $nbar, $fact, $mois_FR;

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
        <title>Graphique de test</title>
    </head>
    <body bgcolor=#FFE887>
    <?php
    require_once "Config.php";
    $config = new Config();

    require($config->inclus_path . "/accesbase.inc.php");

    # connexion aux données
    mysql_connect($serveur,$idDB,$mdpDB) or
    die("Impossible de se connecter au SGBD");
    mysql_select_db($DB) or
    die("Impossible de se connecter à la base");


    $loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
    require("../global/entete.php");
    //echo $loc;

    $titre="Graphique de test";


    entete_asalee($titre);
    //echo $loc;
    ?>
    <br><br>

    <table border="0" align="center">
        <tr>
            <td align="center"><img src="graph.php"></Td>
        </Tr>
    </Table>
    </body>
    </html>
    <?php

}

function creer_image(){
    $largeur = 640;
    $hauteur = 375;
    $image = imagecreate($largeur, $hauteur);

    # couleurs
    $blanc = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
    $bleu = imagecolorallocate($image, 0x00, 0x00, 0x80);
    $noir = imagecolorallocate($image, 0x00, 0x00, 0x00);
    $gris = imagecolorallocate($image, 0xC0, 0xC0, 0xC0);
    $rouge= imagecolorallocate($image, 0xFF, 0x00, 0x00);

    $tableau = array(20, 35, 40, 52, 48, 63, 59, 69, 68, 69, 67);

    $maxval = @max($tableau);
    $nval   = sizeof($tableau);

    $background_color = imagecolorallocate($image, 0, 0, 0);
    $text_color = imagecolorallocate($image, 233, 14, 91);

    imagestring($image, 3, 100,  10, "Graphique de test (taux de respect des HBA1c)", $noir);

    imageline($image, 20, 330, 640, 330, $noir);
    imageline($image, 20, 300, 640, 300, $gris);
    imageline($image, 20, 270, 640, 270, $gris);
    imageline($image, 20, 240, 640, 240, $gris);
    imageline($image, 20, 210, 640, 210, $gris);
    imageline($image, 20, 180, 640, 180, $gris);
    imageline($image, 20, 150, 640, 150, $gris);
    imageline($image, 20, 120, 640, 120, $gris);
    imageline($image, 20, 90, 640, 90, $gris);
    imageline($image, 20, 60, 640, 60, $gris);
    imageline($image, 20, 30, 640, 30, $gris);
    imageline($image, 20, 330,  20, 20, $noir);

    imagestring($image, 1, 10,  325, "0", $noir);
    imagestring($image, 1, 10,  295, "10", $noir);
    imagestring($image, 1, 10,  265, "20", $noir);
    imagestring($image, 1, 10,  235, "30", $noir);
    imagestring($image, 1, 10,  205, "40", $noir);
    imagestring($image, 1, 10,  175, "50", $noir);
    imagestring($image, 1, 10,  145, "60", $noir);
    imagestring($image, 1, 10,  115, "70", $noir);
    imagestring($image, 1, 10,  85, "80", $noir);
    imagestring($image, 1, 10,  55, "90", $noir);
    imagestring($image, 1, 10,  25, "100", $noir);



    imagestring($image, 2, 20, 330, "12/04", $noir);
    imagestring($image, 2, 80, 330, "03/05", $noir);
    imagestring($image, 2, 140, 330, "06/05", $noir);
    imagestring($image, 2, 200, 330, "09/05", $noir);
    imagestring($image, 2, 260, 330, "12/05", $noir);
    imagestring($image, 2, 320, 330, "03/06", $noir);
    imagestring($image, 2, 380, 330, "06/06", $noir);
    imagestring($image, 2, 440, 330, "09/06", $noir);
    imagestring($image, 2, 500, 330, "12/06", $noir);
    imagestring($image, 2, 560, 330, "03/07", $noir);
    imagestring($image, 2, 610, 330, "05/07", $noir);


    for($i=0;$i<sizeof($tableau)-1;$i++){
        imageline($image, 20+($i * 60), 330-3*$tableau[$i], 20+(($i+1)*60), 330-3*$tableau[$i+1], $rouge);
        imagestring($image, 1, 20+($i * 60), 315-3*$tableau[$i], $tableau[$i], $rouge);
    }
    $i=sizeof($tableau)-1;
    imagestring($image, 1, 20+($i * 60), 315-3*$tableau[$i], $tableau[$i], $rouge);

    imagepng($image);
    imagedestroy($image);
}

?>