<?php

require_once "bean/DemandeFraisHistorique.php";
require_once "bean/DemandeFraisIdentification.php";
require_once "bean/DemandeFraisStatus.php";
require_once "bean/DemandeFraisSuivi.php";

session_start();
$inf_login = $_SESSION["id.login"];
$cabinet = $_SESSION["cabinet"];

$id = $_REQUEST['id'];

//Convert date string in date
$str = $_REQUEST['date_frais'];
$tabDate = explode('/' , $str);


$date_frais  = $tabDate[2].'/'.$tabDate[1].'/'.$tabDate[0];


$nature = $_REQUEST['nature'];
$motif = $_REQUEST['motif'];
$distance = $_REQUEST['distance'];
$montant = $_REQUEST['montant'];
$justificatif = $_FILES['nouveau_justificatif']['name'];
$notes = $_REQUEST['notes'];
$puissance = (int)$_REQUEST['puissance'];
$taux_applique = $_REQUEST['taux_applique'];


 if(isset($_REQUEST['check_frais'])) {
     $check_frais = $_REQUEST['check_frais'];
 };

if (!isset($id) || $id == "") // Nouvelle saisie
{
    $fraisHistorique = new DemandeFraisHistorique();
    $fraisIdentification = new DemandeFraisIdentification();
    $fraisSuivi = new DemandeFraisSuivi();

    /*
     * Traitement Historique
     */
    // Cr?ation d'une nouvelle ligne d'historique
    $fraisHistorique->id_demandeur = $fraisSuivi->getUserIdByLogin($inf_login);
    $fraisHistorique->login_demandeur = $inf_login;
    $fraisHistorique->date_frais = $date_frais;
    $fraisHistorique->nature = $nature;
    $fraisHistorique->motif = $motif;
    if ($check_frais == "0") // Nouvelle saisie de frais kilom?trique
    {
        $fraisHistorique->distance = $distance;
        $fraisHistorique->taux_applique = (float)$taux_applique ;
        $fraisHistorique->puissance = $puissance;
        $fraisHistorique->montant = (float)$distance * $fraisHistorique->taux_applique;
        
        $fraisHistorique->nature = "kilom�tres";
    }
    else // Nouvelle saisie de frais autre
    {
        $fraisHistorique->distance = 0.000;
        $fraisHistorique->taux_applique = 0.000;
        $fraisHistorique->puissance = 3;
        $fraisHistorique->montant = (float)$montant;
    }

    if ($justificatif != "")
    {
        $pj = $_FILES['nouveau_justificatif']['tmp_name'];
        $pj_name = $_FILES['nouveau_justificatif']['name'];
        $pj_size = $_FILES['nouveau_justificatif']['size'];
        $pj_type = $_FILES['nouveau_justificatif']['type'];
        $pj_error = $_FILES['nouveau_justificatif']['error'];


        $remplacement=array("?"=>"e",
            "?"=>"e",
            "?"=>"e",
            "?"=>"e",
            "?"=>"e",
            "?"=>"a",
            "?"=>"a",
            "?"=>"a",
            "?"=>"a",
            "?"=>"i",
            "?"=>"i",
            "?"=>"i",
            "?"=>"i",
            "?"=>"u",
            "?"=>"u",
            "?"=>"u",
            "?"=>"u",
            "?"=>"o",
            "?"=>"o",
            "?"=>"o",
            "?"=>"o",
            "?"=>"c",
            " "=>"");

        foreach($remplacement as $rech=>$rempl){
            $pj_name=str_replace($rech, $rempl, $pj_name);
        }

        if ($pj_error>0)
        {
            switch ($pj_error)
            {
                case 2: echo 'La pi?ce jointe d?passe la taille maximum admise'; break;
                case 3: echo 'Pi?ce jointe partiellement t?l?charg?, recommencez plus tard';break;
                case 4: echo "la pi?ce jointe n'a pas ?t? t?l?charg?e, recommencez ult?rieurement"; break;
                default: echo "probl?me lors du t?l?chargement de la pi?ce jointe"; break;
            }
            exit;
        }
        $newDateString = date('Y-m-d_H-i-s');

        require_once ("Config.php");
        $config = new Config();

        $rep = $config->files_path .'/notes_de_frais/';
        $upfile= $rep.'Frais_'.$newDateString.'_login_'.$inf_login.'_uuid_'.$fraisSuivi->id_frais.'_cab_'.$cabinet.'_'.$pj_name;

        $fraisHistorique->justificatif = $upfile;

        $constant=explode(".", $pj_name);

        $const="";
        $point="";
        for($i=0;$i<count($constant)-1;$i++){
            $const=$const.$point.$constant[$i];
            $point=".";
        }
        $ext=$constant[count($constant)-1];
        $ext = strtolower($ext);

        $authorized_ext = array(
            "pdf",
            "png",
            "jpg",
            "jpeg",
            "xls",
            "xlsx",
            "csv"
        );

        if (!in_array($ext, $authorized_ext))
        {
            echo "<script>alert(\"La pièce jointe n'est pas au bon format. Formats permis : pdf, png, jpg, jpeg, xls, xlsx, csv\")</script>";
            exit;
        }

        if(!mkdir($rep, 0775)){
            //echo 'impossible de cr?er '.$rep.'. Il doit exister...';
        }

        if (is_uploaded_file($pj))
        {
            if (!move_uploaded_file($pj, $upfile))
            {
                echo 'probl?me : impossible de t?l?charger la pi?ce jointe.';
                exit;
            }
        }
    }

    $fraisHistorique->save();

    /*
     * Traitement Identification
     */
    $fraisIdentification->intitule = "Demande_Frais_" . $inf_login . "_" . $fraisHistorique->id;
    $fraisIdentification->save();

    /*
     * Traitement Suivi
     */
    // Assignation de l'identification de la demande
    $fraisSuivi->id_frais = $fraisIdentification->id;

    // Assignation du statut dans le nouveau suivi
    $fraisSuivi->id_status = 1;

    // Association de la nouvelle ligne historique au nouveau suivi
    $fraisSuivi->id_historique = $fraisHistorique->id;

    // Utilisateur intervenant
    $fraisSuivi->id_utilisateur = $fraisSuivi->getUserIdByLogin($inf_login);
    $fraisSuivi->login_utilisateur = $inf_login;

    // Enregistrement
    $fraisSuivi->notes = $notes;
    $fraisSuivi->save();
    //for now just give success to test if it works
    echo json_encode(array(utf8_encode('success')=>utf8_encode(true)));
}

else
{
    $id_demandeur = $_POST['id_demandeur'];
    $identifiant_suivi = $_POST['identifiant_suivi'];
    $login_demandeur = $_POST['login_demandeur'];
    $dernierStatus = $_POST['id_status'];
    $nouveauTaux = $_POST['taux_applique'];

    $fraisSuivi = new DemandeFraisSuivi();
    $fraisSuivi->getById($identifiant_suivi);

    if (
        $fraisSuivi->historiqueFrais->login_demandeur == $login_demandeur &&
        $fraisSuivi->historiqueFrais->date_frais == $date_frais &&
        $fraisSuivi->historiqueFrais->nature == $nature &&
        $fraisSuivi->historiqueFrais->motif == $motif &&
        $fraisSuivi->historiqueFrais->distance == $distance &&
        $fraisSuivi->historiqueFrais->montant == $montant &&
        $fraisSuivi->historiqueFrais->taux_applique == $nouveauTaux &&
        $justificatif == "" &&
        $fraisSuivi->statusFrais->id == (int)$dernierStatus &&
        $fraisSuivi->notes == $notes
    )
    {
        echo "";
    }

    else if (
        ($fraisSuivi->statusFrais->id != (int)$dernierStatus || $fraisSuivi->notes != $notes) && (
            $fraisSuivi->historiqueFrais->login_demandeur == $login_demandeur &&
            $fraisSuivi->historiqueFrais->date_frais == $date_frais &&
            $fraisSuivi->historiqueFrais->nature == $nature &&
            $fraisSuivi->historiqueFrais->motif == $motif &&
            $fraisSuivi->historiqueFrais->distance == $distance &&
            $fraisSuivi->historiqueFrais->montant == $montant &&
            $fraisSuivi->historiqueFrais->taux_applique == $nouveauTaux
        )
    )
    {
        $fraisStatut = new DemandeFraisStatus();
        $fraisStatut->getById((int)$dernierStatus);

        $fraisSuivi->id_status = $fraisStatut->id;
        $fraisSuivi->notes = $notes;
        $fraisSuivi->save();
    }

    else
    {
        // Chargement des anciennes valeur de l'historique
        $fraisHistorique = new DemandeFraisHistorique();
        $fraisHistorique->getById($fraisSuivi->id_historique);
        // Cr?ation d'une nouvelle ligne d'historique pour les mise ? jour
        $fraisHistorique->id_demandeur = $fraisSuivi->getUserIdByLogin($login_demandeur);
        $fraisHistorique->login_demandeur = $login_demandeur;
        $fraisHistorique->date_frais = $date_frais;
        $fraisHistorique->nature = $nature;
        $fraisHistorique->motif = $motif;
        if ($distance != null)
            $fraisHistorique->distance = $distance;
        $fraisHistorique->puissance = $puissance;
        $fraisHistorique->taux_applique = $nouveauTaux;
        $fraisHistorique->montant = $montant;

        if ($justificatif != "")
        {
            $pj = $_FILES['nouveau_justificatif']['tmp_name'];
            $pj_name = $_FILES['nouveau_justificatif']['name'];
            $pj_size = $_FILES['nouveau_justificatif']['size'];
            $pj_type = $_FILES['nouveau_justificatif']['type'];
            $pj_error = $_FILES['nouveau_justificatif']['error'];


            $remplacement=array("?"=>"e",
                "?"=>"e",
                "?"=>"e",
                "?"=>"e",
                "?"=>"e",
                "?"=>"a",
                "?"=>"a",
                "?"=>"a",
                "?"=>"a",
                "?"=>"i",
                "?"=>"i",
                "?"=>"i",
                "?"=>"i",
                "?"=>"u",
                "?"=>"u",
                "?"=>"u",
                "?"=>"u",
                "?"=>"o",
                "?"=>"o",
                "?"=>"o",
                "?"=>"o",
                "?"=>"c",
                " "=>"");

            foreach($remplacement as $rech=>$rempl){
                $pj_name=str_replace($rech, $rempl, $pj_name);
            }

            if ($pj_error>0)
            {
                switch ($pj_error)
                {
                    case 2: echo 'La pi?ce jointe d?passe la taille maximum admise'; break;
                    case 3: echo 'Pi?ce jointe partiellement t?l?charg?, recommencez plus tard';break;
                    case 4: echo "la pi?ce jointe n'a pas ?t? t?l?charg?e, recommencez ult?rieurement"; break;
                    default: echo "probl?me lors du t?l?chargement de la pi?ce jointe"; break;
                }
                exit;
            }
            $newDateString = date('Y-m-d_H-i-s');

            require_once ("Config.php");
            $config = new Config();

            $rep = $config->files_path .'/notes_de_frais/';
            $upfile= $rep.'Frais_'.$newDateString.'_login_'.$inf_login.'_uuid_'.$fraisSuivi->id_frais.'_cab_'.$cabinet.'_'.$pj_name;

            $fraisHistorique->justificatif = $upfile;

            $constant=explode(".", $pj_name);

            $const="";
            $point="";
            for($i=0;$i<count($constant)-1;$i++){
                $const=$const.$point.$constant[$i];
                $point=".";
            }
            $ext=$constant[count($constant)-1];
            $ext = strtolower($ext);

            $authorized_ext = array(
                "pdf",
                "png",
                "jpg",
                "jpeg",
                "xls",
                "xlsx",
                "csv"
            );

            if (!in_array($ext, $authorized_ext))
            {
                echo "<script>alert(\"La pièce jointe n'est pas au bon format. Formats permis : pdf, png, jpg, jpeg, xls, xlsx, csv\")</script>";
                exit;
            }

            if(!mkdir($rep, 0775)){
                //echo 'impossible de cr?er '.$rep.'. Il doit exister...';
            }

            if (is_uploaded_file($pj))
            {
                if (!move_uploaded_file($pj, $upfile))
                {
                    echo 'probl?me : impossible de t?l?charger la pi?ce jointe.';
                    exit;
                }
            }
        }

        $fraisHistorique->save();

        // R?cup?ration du nouveau statut
        $fraisStatut = new DemandeFraisStatus();
        $fraisStatut->getById((int)$dernierStatus);

        // Mise ? jour du statut dans le nouveau suivi
        $fraisSuivi->id_status = $fraisStatut->id;

        // Association de la nouvelle ligne historique au nouveau suivi
        $fraisSuivi->id_historique = $fraisHistorique->id;

        // Mise ? jour de l'intervenant
        if($_SERVER['APPLICATION_ENV'] != 'dev-herve')
        {
            $fraisSuivi->id_utilisateur = $fraisSuivi->getUserIdByLogin($inf_login);
            $fraisSuivi->login_utilisateur = $inf_login;
        }

        // Enregistrement
        $fraisSuivi->notes = $notes;
        $fraisSuivi->save();
    }

    //for now just give success to test if it works
    echo json_encode(array(utf8_encode('success')=>utf8_encode(true)));
}
