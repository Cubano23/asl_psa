<?php
session_start();


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="content-type"
          content="text/html; charset=ISO-8859-15">
    <title>Liste des évaluations infirmières</title>
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
?>

<br><br>
<?php

# boucle principale
do {
    $repete=false;

    # fenêtre glissante:
    if (isset($_GET['mois']) && isset($_GET['annee']))
    {
        etape_2($repete);
        exit;
    }

    # étape 1 : identification du patient et de la date
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

            case 1:
                etape_1($repete);
                break;

            # étape 2  : saisie des détails
            case 2:
                etape_2($repete);
                break;

            # étape 3  : validation des données et màj base
            case 3:
                etape_3($repete);
                break;
        }
    }
} while($repete);

# fin de traitement principal


function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville;







    $req="SELECT * FROM `questionnaire_medecin` ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
    echo mysql_num_rows($res);
    echo "<table border='1'><tr><td>id</td><td>medecin</td><td>nom</td><td>prenom</td><td>discipline</td><td>adresse_pro</Td>".
        "<td>tel</td><td>fax</td><td>email</td><td>implic_initiation</td><td>commentaire_implic_initiation</td>".
        "<td>implic_conception</td><td>commentaire_implic_conception</td><td>implic_recueil</td><td>commentaire_implic_recueil".
        "</td><td>implic_analyse</td><td>commentaire_implic_analyse</td><td>implic_mise_oeuvre</td><td>".
        "commentaire_implic_mise_oeuvre</td><td>implic_suivi</td><td>commentaire_implic_suivi</td><td>amelioration_pratique".
        "</td><td>note_pratique</td><td>organisation_soins</td><td>note_soin</td><td>utilite_patient</td><td>note_patient".
        "</td><td>demarche_faisable</td><td>autres_actions</td><td>satisfaction</td><td>difficultes</td><td>ameliorations</td>".
        "<td>dmajcabinet</td></tr>";

    while(list($medecin, $nom,$prenom   	  ,$discipline   	  ,$adresse_pro   	  ,$tel   	  ,$fax   	  ,$email
        ,$implic_initiation   	  ,$commentaire_implic_initiation   	  ,$implic_conception   	  ,
        $commentaire_implic_conception   	  ,$implic_recueil   	  ,$commentaire_implic_recueil   	  ,$implic_analyse
        ,$commentaire_implic_analyse   	  ,$implic_mise_oeuvre   	  ,$commentaire_implic_mise_oeuvre   	  ,$implic_suivi
        ,$commentaire_implic_suivi   	  ,$amelioration_pratique   	  ,$note_pratique   	  ,$organisation_soins
        ,$note_soin   	  ,$utilite_patient   	  ,$note_patient   	  ,$demarche_faisable   	  ,$autres_actions
        ,$satisfaction   	  ,$difficultes   	  ,$ameliorations   	  ,$dmaj)=mysql_fetch_row($res)){
        echo "<tr><td>$medecin</td><td>$nom   	  </td><td>$prenom   	  </td><td>$discipline   	  </td><td>$adresse_pro   	  
		</td><td>$tel   	  </td><td>$fax   	  </td><td>$email   	  </td><td>$implic_initiation   	  </td><td>
		$commentaire_implic_initiation   	  </td><td>$implic_conception   	  </td><td>$commentaire_implic_conception   	  
		</td><td>$implic_recueil   	  </td><td>$commentaire_implic_recueil   	  </td><td>$implic_analyse   	  </td><td>
		$commentaire_implic_analyse   	  </td><td>$implic_mise_oeuvre   	  </td><td>$commentaire_implic_mise_oeuvre   	  
		</td><td>$implic_suivi   	  </td><td>$commentaire_implic_suivi   	  </td><td>$amelioration_pratique   	  
		</td><td>$note_pratique   	  </td><td>$organisation_soins   	  </td><td>$note_soin   	  </td><td>$utilite_patient   	  
		</td><td>$note_patient   	  </td><td>$demarche_faisable   	  </td><td>$autres_actions   	  </td><td>$satisfaction   	  
		</td><td>$difficultes   	  </td><td>$ameliorations   	  </td><td>$dmaj</td></tr>";
    }

    echo "</table>";

}


?>
</body>
</html>
