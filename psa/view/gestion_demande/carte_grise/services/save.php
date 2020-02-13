<?php

require_once "bean/DemandeCGHistorique.php";
require_once "bean/DemandeCGIdentification.php";
require_once "bean/DemandeCGStatus.php";
require_once "bean/DemandeCGSuivi.php";

session_start();
$inf_login = $_SESSION["id.login"];
$cabinet = $_SESSION["cabinet"];

$id = $_REQUEST['id'];

$date_obtention = $_REQUEST['date_obtention'];
$puissance = $_REQUEST['puissance'];
$precisions = $_REQUEST['precisions'];
$justificatif = $_FILES['nouveau_justificatif']['name'];
$notes = $_REQUEST['notes'];

if (!isset($id) || $id == "")
{
    $cgHistorique = new DemandeCGHistorique();
    $cgIdentification = new DemandeCGIdentification();
    $cgSuivi = new DemandeCGSuivi();

    /*
     * Traitement Historique
     */
    // Cr�ation d'une nouvelle ligne d'historique
    $cgHistorique->id_demandeur = $cgSuivi->getUserIdByLogin($inf_login);
    $cgHistorique->login_demandeur = $inf_login;
    $cgHistorique->date_obtention = $date_obtention;
    $cgHistorique->puissance = $puissance;
    $cgHistorique->precisions = $precisions;

    if ($justificatif != "")
    {
        $pj = $_FILES['nouveau_justificatif']['tmp_name'];
        $pj_name = $_FILES['nouveau_justificatif']['name'];
        $pj_size = $_FILES['nouveau_justificatif']['size'];
        $pj_type = $_FILES['nouveau_justificatif']['type'];
        $pj_error = $_FILES['nouveau_justificatif']['error'];


        $remplacement=array("?"=>"e",
            "�"=>"e",
            "�"=>"e",
            "�"=>"e",
            "�"=>"e",
            "�"=>"a",
            "�"=>"a",
            "�"=>"a",
            "�"=>"a",
            "�"=>"i",
            "�"=>"i",
            "�"=>"i",
            "�"=>"i",
            "�"=>"u",
            "�"=>"u",
            "�"=>"u",
            "�"=>"u",
            "�"=>"o",
            "�"=>"o",
            "�"=>"o",
            "�"=>"o",
            "�"=>"c",
            " "=>"");

        foreach($remplacement as $rech=>$rempl){
            $pj_name=str_replace($rech, $rempl, $pj_name);
        }

        if ($pj_error>0)
        {
            switch ($pj_error)
            {
                case 2: echo 'La pi�ce jointe d�passe la taille maximum admise'; break;
                case 3: echo 'Pi�ce jointe partiellement t�l�charg�, recommencez plus tard';break;
                case 4: echo "la pi�ce jointe n'a pas �t� t�l�charg�e, recommencez ult�rieurement"; break;
                default: echo "probl�me lors du t�l�chargement de la pi�ce jointe"; break;
            }
            exit;
        }
        $newDateString = date('Y-m-d_H-i-s');

        require_once ("Config.php");
        $config = new Config();

        $rep = $config->files_path .'/carte_grises/';
        $upfile = $rep.'CG_'.$newDateString.'_login_'.$inf_login.'_uuid_'.$cgSuivi->id_demande_carte_grise.'_cab_'.$cabinet.'_'.$pj_name;

        $cgHistorique->justificatif = $upfile;

        $constant=explode(".", $pj_name);

        $const="";
        $point="";
        for($i=0;$i<count($constant)-1;$i++){
            $const=$const.$point.$constant[$i];
            $point=".";
        }
        $ext = $constant[count($constant)-1];
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
            //echo 'impossible de cr�er '.$rep.'. Il doit exister...';
        }

        if (is_uploaded_file($pj))
        {
            if (!move_uploaded_file($pj, $upfile))
            {
                echo 'probl�me : impossible de t�l�charger la pi�ce jointe.';
                exit;
            }
        }
    }

    $cgHistorique->save();

    /*
     * Traitement Identification
     */
    $cgIdentification->intitule = "Demande_CG_" . $inf_login . "_" . $cgHistorique->id;
    $cgIdentification->save();

    /*
     * Traitement Suivi
     */
    // Assignation de l'identification de la demande
    $cgSuivi->id_demande_carte_grise = $cgIdentification->id;

    // Assignation du statut dans le nouveau suivi
    $cgSuivi->id_status = 1;

    // Association de la nouvelle ligne historique au nouveau suivi
    $cgSuivi->id_historique = $cgHistorique->id;

    // Utilisateur intervenant
    $cgSuivi->id_utilisateur = $cgSuivi->getUserIdByLogin($inf_login);
    $cgSuivi->login_utilisateur = $inf_login;

    // Enregistrement
    $cgSuivi->notes = $notes;
    $cgSuivi->save();
    //for now just give success to test if it works
    echo json_encode(array('success'=>true));
}

else
{
    $id_demandeur = $_POST['id_demandeur'];
    $identifiant_suivi = $_POST['identifiant_suivi'];
    $date_demande = $_POST['date_demande'];
    $titre = $_POST['titre'];
    $login_demandeur = $_POST['login_demandeur'];

    $dernierIntervenant = $_POST['dernierIntervenant'];
    $dernierStatus = $_POST['id_status'];
    $date_dernierStatut = $_POST['date_dernierStatut'];

    $cgSuivi= new DemandeCGSuivi();

    $cgSuivi->getById($identifiant_suivi);

    if (
        $cgSuivi->historiqueCG->login_demandeur == $login_demandeur &&
        $cgSuivi->historiqueCG->date_obtention == $date_obtention &&
        $cgSuivi->historiqueCG->puissance == $puissance &&
        $cgSuivi->historiqueCG->precisions == $precisions &&
        $justificatif == "" &&
        $cgSuivi->statusCG->id == (int)$dernierStatus &&
        $cgSuivi->notes == $notes
    )
    {
        echo "";
    }

    elseif (
        ($cgSuivi->statusCG->id != (int)$dernierStatus || $cgSuivi->notes != $notes)  && (
            $cgSuivi->historiqueCG->login_demandeur == $login_demandeur &&
            $cgSuivi->historiqueCG->date_obtention == $date_obtention &&
            $cgSuivi->historiqueCG->puissance == $puissance &&
            $cgSuivi->historiqueCG->precisions == $precisions
        )
    )
    {
        $cgStatut = new DemandeCGStatus();
        $cgStatut->getById((int)$dernierStatus);

        $cgSuivi->id_status = $cgStatut->id;
        $cgSuivi->notes = $notes;
        $cgSuivi->save();
    }

    else
    {
        // Chargement des anciennes valeur de l'historique
        $cgHistorique= new DemandeCGHistorique();
        $cgHistorique->getById($cgSuivi->id_historique);
        // Cr�ation d'une nouvelle ligne d'historique pour les mise � jour
        $cgHistorique->id_demandeur = $cgSuivi->getUserIdByLogin($login_demandeur);
        $cgHistorique->login_demandeur = $login_demandeur;
        $cgHistorique->date_obtention = $date_obtention;
        $cgHistorique->puissance = $puissance;
        $cgHistorique->precisions = $precisions;
        if ($justificatif != "")
        {
            $pj = $_FILES['nouveau_justificatif']['tmp_name'];
            $pj_name = $_FILES['nouveau_justificatif']['name'];
            $pj_size = $_FILES['nouveau_justificatif']['size'];
            $pj_type = $_FILES['nouveau_justificatif']['type'];
            $pj_error = $_FILES['nouveau_justificatif']['error'];


            $remplacement=array("?"=>"e",
                "�"=>"e",
                "�"=>"e",
                "�"=>"e",
                "�"=>"e",
                "�"=>"a",
                "�"=>"a",
                "�"=>"a",
                "�"=>"a",
                "�"=>"i",
                "�"=>"i",
                "�"=>"i",
                "�"=>"i",
                "�"=>"u",
                "�"=>"u",
                "�"=>"u",
                "�"=>"u",
                "�"=>"o",
                "�"=>"o",
                "�"=>"o",
                "�"=>"o",
                "�"=>"c",
                " "=>"");

            foreach($remplacement as $rech=>$rempl){
                $pj_name=str_replace($rech, $rempl, $pj_name);
            }

            if ($pj_error>0)
            {
                switch ($pj_error)
                {
                    case 2: echo 'La pi�ce jointe d�passe la taille maximum admise'; break;
                    case 3: echo 'Pi�ce jointe partiellement t�l�charg�, recommencez plus tard';break;
                    case 4: echo "la pi�ce jointe n'a pas �t� t�l�charg�e, recommencez ult�rieurement"; break;
                    default: echo "probl�me lors du t�l�chargement de la pi�ce jointe"; break;
                }
                exit;
            }
            $newDateString = date('Y-m-d_H-i-s');

            require_once ("Config.php");
            $config = new Config();

            $rep = $config->files_path .'/carte_grises/';
            $upfile= $rep.'CG_'.$newDateString.'_login_'.$inf_login.'_uuid_'.$cgSuivi->id_demande_carte_grise.'_cab_'.$cabinet.'_'.$pj_name;

            $cgHistorique->justificatif = $upfile;

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
                //echo 'impossible de cr�er '.$rep.'. Il doit exister...';
            }

            if (is_uploaded_file($pj))
            {
                if (!move_uploaded_file($pj, $upfile))
                {
                    echo 'probl�me : impossible de t�l�charger la pi�ce jointe.';
                    exit;
                }
            }
        }

        $cgHistorique->save();

        // R�cup�ration du nouveau statut
        $cgStatut = new DemandeCGStatus();
        $cgStatut->getById((int)$dernierStatus);

        // Mise � jour du statut dans le nouveau suivi
        $cgSuivi->id_status = $cgStatut->id;

        // Association de la nouvelle ligne historique au nouveau suivi
        $cgSuivi->id_historique = $cgHistorique->id;

        // Mise � jour de l'intervenant
        if($_SERVER['APPLICATION_ENV'] != 'dev-herve')
        {
            $cgSuivi->id_utilisateur = $cgSuivi->getUserIdByLogin($inf_login);
            $cgSuivi->login_utilisateur = $inf_login;
        }

        // Enregistrement
        $cgSuivi->notes = $notes;
        $cgSuivi->save();
    }
    //for now just give success to test if it works
    echo json_encode(array('success'=>true));
}
