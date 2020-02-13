<?php
/**
 * Created by SublimeText
 * User: Gisgo
 * Date: 21-11-2018
 * Time: 14:40
 */
require_once "bean/DemandeRibHistorique.php";
require_once "bean/DemandeRibIdentification.php";
require_once "bean/DemandeRibStatus.php";
require_once "bean/DemandeRibSuivi.php";

session_start();
$inf_login = $_SESSION["id.login"];
$cabinet = $_SESSION["cabinet"];

$id = $_REQUEST['id'];
$iban = $_REQUEST['iban'];
$justificatif = $_FILES['nouveau_justificatif']['name'];
$notes = $_REQUEST['notes'];



if (!isset($id) || $id == "")
{
    $ribHistorique = new DemandeRibHistorique();
    $ribIdentification = new DemandeRibIdentification();
    $ribSuivi = new DemandeRibSuivi();

    /*
     * Traitement Historique
     */
    // Création d'une nouvelle ligne d'historique
    $ribHistorique->id_demandeur = $ribSuivi->getUserIdByLogin($inf_login);
    $ribHistorique->login_demandeur = $inf_login;
	$ribHistorique->iban = $iban;

    if ($justificatif != "")
    {
        $pj = $_FILES['nouveau_justificatif']['tmp_name'];
        $pj_name = $_FILES['nouveau_justificatif']['name'];
        $pj_size = $_FILES['nouveau_justificatif']['size'];
        $pj_type = $_FILES['nouveau_justificatif']['type'];
        $pj_error = $_FILES['nouveau_justificatif']['error'];


        $remplacement=array("?"=>"e",
            "é"=>"e",
            "è"=>"e",
            "ê"=>"e",
            "ë"=>"e",
            "á"=>"a",
            "à"=>"a",
            "â"=>"a",
            "ä"=>"a",
            "í"=>"i",
            "ì"=>"i",
            "ï"=>"i",
            "î"=>"i",
            "ú"=>"u",
            "ù"=>"u",
            "ü"=>"u",
            "û"=>"u",
            "ó"=>"o",
            "ò"=>"o",
            "ô"=>"o",
            "ö"=>"o",
            "ç"=>"c",
            " "=>"");

        foreach($remplacement as $rech=>$rempl){
            $pj_name=str_replace($rech, $rempl, $pj_name);
        }

        if ($pj_error>0)
        {
            switch ($pj_error)
            {
                case 2: echo 'La pièce jointe dépasse la taille maximum admise'; break;
                case 3: echo 'Pièce jointe partiellement téléchargé, recommencez plus tard';break;
                case 4: echo "la pièce jointe n'a pas été téléchargée, recommencez ultérieurement"; break;
                default: echo "problème lors du téléchargement de la pièce jointe"; break;
            }
            exit;
        }
        $newDateString = date('Y-m-d_H-i-s');

        require_once ("Config.php");
        $config = new Config();

        $rep = $config->files_path .'/rib/';
        $upfile= $rep.'Rib_'.$newDateString.'_login_'.$inf_login.'_uuid_'.$ribSuivi->id_rib.'_cab_'.$cabinet.'_'.$pj_name;	

        $ribHistorique->justificatif = $upfile;

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

        /*if(!mkdir($rep, 0775)){
            //echo 'impossible de créer '.$rep.'. Il doit exister...';
        }*/

        if (is_uploaded_file($pj))
        {
            if (!move_uploaded_file($pj, $upfile))
            {
                echo 'problème : impossible de télécharger la pièce jointe.';
                exit;
            }
        }
    }

    $ribHistorique->save();

    /*
     * Traitement Identification
     */
    $ribIdentification->intitule = "Demande_Rib_" . $inf_login . "_" . $ribHistorique->id;
    $ribIdentification->save();

    /*
     * Traitement Suivi
     */
    // Assignation de l'identification de la demande
    $ribSuivi->id_rib = $ribIdentification->id;

    // Assignation du statut dans le nouveau suivi
    $ribSuivi->id_status = 1;

    // Association de la nouvelle ligne historique au nouveau suivi
    $ribSuivi->id_historique = $ribHistorique->id;

    // Utilisateur intervenant
    $ribSuivi->id_utilisateur = $ribSuivi->getUserIdByLogin($inf_login);
    $ribSuivi->login_utilisateur = $inf_login;

    // Enregistrement
    $ribSuivi->notes = $notes;

    $ribSuivi->save();

    //for now just give success to test if it works
    echo json_encode(array(utf8_encode('success')=>utf8_encode(true)));
}

else
{
    $id_demandeur = $_REQUEST['id_demandeur'];
    $identifiant_suivi = $_REQUEST['identifiant_suivi'];
    $titre = $_REQUEST['titre'];
    $login_demandeur = $_REQUEST['login_demandeur'];
    $dernierIntervenant = $_REQUEST['dernierIntervenant'];
    $dernierStatus = $_REQUEST['id_status'];
    $iban = $_REQUEST['iban']; 
    $date_dernierStatut = $_REQUEST['date_dernierStatut'];

    $ribSuivi = new DemandeRibSuivi();

    $ribSuivi->getById($identifiant_suivi);

    if (
        $ribSuivi->historiqueRib->login_demandeur == $login_demandeur &&
        $justificatif == "" &&
        $ribSuivi->historiqueRib->iban == $iban
    ) {
        echo "";
    }
    else if (($ribSuivi->statusRib->id != (int)$dernierStatus || $ribSuivi->notes != $notes )&& ($ribSuivi->historiqueRib->login_demandeur == $login_demandeur && $ribSuivi->historiqueRib->iban == $iban && $ribSuivi->notes == $notes)) {
        
        $ribStatut = new DemandeRibStatus();
        $ribStatut->getById((int)$dernierStatus);

        $ribSuivi->id_status = $ribStatut->id;
        $ribSuivi->notes = $notes;
        $ribSuivi->save();
        
    } else {
        // Chargement des anciennes valeur de l'historique
        $ribHistorique = new DemandeRibHistorique();
        $ribHistorique->getById($ribSuivi->id_historique);
        // Création d'une nouvelle ligne d'historique pour les mise à jour
        $ribHistorique->id_demandeur = $ribSuivi->getUserIdByLogin($login_demandeur);
        $ribHistorique->login_demandeur = $login_demandeur;
		$ribHistorique->iban = $iban;
        
        
        if ($justificatif != "")
        {
            $pj = $_FILES['nouveau_justificatif']['tmp_name'];
            $pj_name = $_FILES['nouveau_justificatif']['name'];
            $pj_size = $_FILES['nouveau_justificatif']['size'];
            $pj_type = $_FILES['nouveau_justificatif']['type'];
            $pj_error = $_FILES['nouveau_justificatif']['error'];

            $remplacement=array("?"=>"e",
                "é"=>"e",
                "è"=>"e",
                "ê"=>"e",
                "ë"=>"e",
                "á"=>"a",
                "à"=>"a",
                "â"=>"a",
                "ä"=>"a",
                "í"=>"i",
                "ì"=>"i",
                "ï"=>"i",
                "î"=>"i",
                "ú"=>"u",
                "ù"=>"u",
                "ü"=>"u",
                "û"=>"u",
                "ó"=>"o",
                "ò"=>"o",
                "ô"=>"o",
                "ö"=>"o",
                "ç"=>"c",
                " "=>"");

            foreach($remplacement as $rech=>$rempl){
                $pj_name=str_replace($rech, $rempl, $pj_name);
            }

            if ($pj_error>0)
            {
                switch ($pj_error)
                {
                    case 2: echo 'La pièce jointe dépasse la taille maximum admise'; break;
                    case 3: echo 'Pièce jointe partiellement téléchargé, recommencez plus tard';break;
                    case 4: echo "la pièce jointe n'a pas été téléchargée, recommencez ultérieurement"; break;
                    default: echo "problème lors du téléchargement de la pièce jointe"; break;
                }
                exit;
            }
            $newDateString = date('Y-m-d_H-i-s');

            require_once ("Config.php");
            $config = new Config();

            $rep = $config->files_path .'/rib/';
            $upfile= $rep.'Rib_'.$newDateString.'_login_'.$inf_login.'_uuid_'.$ribSuivi->id_rib.'_cab_'.$cabinet.'_'.$pj_name;

            $ribHistorique->justificatif = $upfile;

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
                //echo 'impossible de créer '.$rep.'. Il doit exister...';
            }

            if (is_uploaded_file($pj))
            {
                if (!move_uploaded_file($pj, $upfile))
                {
                    echo 'problème : impossible de télécharger la pièce jointe.';
                    exit;
                }
            }
        }

        $ribHistorique->save();

        // Récupération du nouveau statut
        $ribStatut = new DemandeRibStatus();
        $ribStatut->getById((int)$dernierStatus);

        // Mise à jour du statut dans le nouveau suivi
        $ribSuivi->id_status = $ribStatut->id;

        // Association de la nouvelle ligne historique au nouveau suivi
        $ribSuivi->id_historique = $ribHistorique->id;

        // Mise à jour de l'intervenant
        if($_SERVER['APPLICATION_ENV'] != 'dev-herve')
        {
            $ribSuivi->id_utilisateur = $ribSuivi->getUserIdByLogin($inf_login);
            $ribSuivi->login_utilisateur = $inf_login;
        }

        // Enregistrement
        $ribSuivi->notes = $notes;
        $ribSuivi->save();
    }

    //for now just give success to test if it works
    echo json_encode(array(utf8_encode('success')=>utf8_encode(true)));
}
