<?php
/**
 * Created by PhpStorm.
 * User: gisgo
 * Date: 12/10/18
 * Time: 20:05
 */

require_once "persistence/ConnectionInformedPDO.php";


class CarteGriseSuivi
{
    private $con; //variable de connexion
    public $id;
    public $id_demande_carte_grise;
    public $id_status;
    public $id_historique;
    public $id_utilisateur;
    public $login_utilisateur;
    public $notes;
    public $dcreat;
    public $dmaj;

    public function __construct()
    {
        $db = ConnectionInformedPDO::getInstance();
        $this->con = $db->getDbh();
    }

    function CarteGriseSuivi(
        $id  = NULL,
        $id_demande_carte_grise  = NULL,
        $id_status  = NULL,
        $id_historique  = NULL,
        $id_utilisateur  = NULL,
        $login_utilisateur  = NULL,
        $notes  = NULL,
        $dcreat  = NULL,
        $dmaj  = NULL
    )
    {

        $this->id = $id;
        $this->id_demande_carte_grise = $id_demande_carte_grise;
        $this->id_status = $id_status;
        $this->id_historique = $id_historique;
        $this->id_utilisateur = $id_utilisateur;
        $this->login_utilisateur = $login_utilisateur;
        $this->notes = $notes;
        $this->dcreat = $dcreat;
        $this->dmaj = $dmaj;
    }


    public function getById($demandeCarteGriseSuiviId)
    {
        $demandeCarteGriseSuivi = array();
        $sql = "SELECT   id, id_demande_carte_grise, id_status, id_historique, id_utilisateur, login_utilisateur, notes, dcreat, dmaj
                FROM demande_carte_grise_suivi
                WHERE    id = " . $demandeCarteGriseSuiviId ;
        try
        {
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $res = $this->con->query($sql);

            $demandeCarteGriseSuivi = $res->fetch(PDO::FETCH_ASSOC);
            $demandeCarteGriseSuivi['infNom'] = $this->getInfNom($demandeCarteGriseSuivi['infAjout']);
        }
        catch (PDOException $e)
        {
            exit($e->getMessage());
        }

        return $demandeCarteGriseSuivi;
    }

    public function save()
    {
        $sql = 'INSERT INTO demande_carte_grise_suivi (id_demande_carte_grise, id_status, id_historique, id_utilisateur, login_utilisateur, notes) VALUES (:id_demande_carte_grise,:id_status,:id_historique,:id_utilisateur,:login_utilisateur,:notes)';


        try
        {
            $res = $this->con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

            $res->bindParam(':id_demande_carte_grise', $this->id_demande_carte_grise, PDO::PARAM_INT );
            $res->bindParam(':id_status', $this->id_status, PDO::PARAM_INT );
            $res->bindParam(':id_historique', $this->id_historique, PDO::PARAM_INT );
            $res->bindParam(':id_utilisateur', $this->id_utilisateur, PDO::PARAM_INT );
            $res->bindParam(':login_utilisateur', $this->login_utilisateur, PDO::PARAM_STR );
            $res->bindParam(':notes', $this->notes, PDO::PARAM_STR );
            $res->execute();
        }
        catch (PDOException $exception)
        {
            echo $exception->getMessage();
        }
    }

    public function update()
    {


        $sql = 'UPDATE demande_carte_grise_suivi SET id_demande_carte_grise = :id_demande_carte_grise,id_status = :id_status,id_historique = :id_historique,id_utilisateur = :id_utilisateur,login_utilisateur = :login_utilisateur,notes = :notes WHERE id = :id';

        try
        {

            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $res = $this->con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

            $res->bindParam(':id',$this->id, PDO::PARAM_INT);


            $res->bindParam(':id_demande_carte_grise', $this->id_demande_carte_grise, PDO::PARAM_INT );
            $res->bindParam(':id_status', $this->id_status, PDO::PARAM_INT );
            $res->bindParam(':id_historique', $this->id_historique, PDO::PARAM_INT );
            $res->bindParam(':id_utilisateur', $this->id_utilisateur, PDO::PARAM_INT );
            $res->bindParam(':login_utilisateur', $this->login_utilisateur, PDO::PARAM_STR );
            $res->bindParam(':notes', $this->notes, PDO::PARAM_STR );

            $res->bindParam(':estActif',$this->estActif);
            $res->execute();
        }
        catch (PDOException $exception)
        {
            echo $exception->getMessage();
        }
    }



    /*
    public function getListeEntretiens()
    {
        $listeEntretiens = array();
        $sql = "SELECT   id, infAjout, dateAjout 
                FROM     entretienAnnuel
                WHERE    infAJout = '" . $_SESSION['nom'] ."' AND cabinet = '" . $_SESSION['cabinet'] ."' AND estActif = 1
                ORDER BY id DESC 
                LIMIT    30";
        try
        {
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $res = $this->con->query($sql);
            $listeEntretiens = $res->fetchAll(PDO::FETCH_CLASS, 'EntretienAnnuel');
        }
        catch (PDOException $e)
        {
            exit($e->getMessage());
        }
        return $listeEntretiens;
    }

    public function getEntretienAnnuelById($entretienAnnuelId)
    {
        $entretienAnnuel = array();
        $sql = "SELECT   id, difficultesRencontrees, ressourcesIdentifiees, formationsSuivies, realisationsMarquantes, perspectivesProEnvisagees, besoinsASatisfaire, projetAcademique, realiseAvecPrenom, realiseAvecNom, realiseAvecLoginAsalee, infAjout, cabinet, dateAjout, estActif
                FROM entretienAnnuel
                WHERE    id = " . $entretienAnnuelId ." AND estActif = 1";
        try
        {
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $res = $this->con->query($sql);

            $entretienAnnuel = $res->fetch(PDO::FETCH_ASSOC);
            $entretienAnnuel['infNom'] = $this->getInfNom($entretienAnnuel['infAjout']);
        }
        catch (PDOException $e)
        {
            exit($e->getMessage());
        }

        return $entretienAnnuel;
    }

    public function save()
    {
        $sql = 'INSERT INTO entretienAnnuel (difficultesRencontrees,ressourcesIdentifiees,formationsSuivies,realisationsMarquantes,perspectivesProEnvisagees,besoinsASatisfaire,projetAcademique,realiseAvecPrenom,realiseAvecNom,realiseAvecLoginAsalee,infAjout, cabinet, dateAjout, estActif) VALUES (:difficultesRencontrees, :ressourcesIdentifiees, :formationsSuivies, :realisationsMarquantes, :perspectivesProEnvisagees, :besoinsASatisfaire, :projetAcademique, :realiseAvecPrenom, :realiseAvecNom, :realiseAvecLoginAsalee, :infAjout, :cabinet, :dateAjout, :estActif)';


        try
        {
            $res = $this->con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));



            $res->bindParam(':difficultesRencontrees', $this->difficultesRencontrees, PDO::PARAM_STR );
            $res->bindParam(':ressourcesIdentifiees', $this->ressourcesIdentifiees, PDO::PARAM_STR );
            $res->bindParam(':formationsSuivies', $this->formationsSuivies, PDO::PARAM_STR );
            $res->bindParam(':realisationsMarquantes', $this->realisationsMarquantes, PDO::PARAM_STR );
            $res->bindParam(':perspectivesProEnvisagees', $this->perspectivesProEnvisagees, PDO::PARAM_STR );
            $res->bindParam(':besoinsASatisfaire', $this->besoinsASatisfaire, PDO::PARAM_STR );
            $res->bindParam(':projetAcademique', $this->projetAcademique, PDO::PARAM_INT );
            $res->bindParam(':realiseAvecPrenom', $this->realiseAvecPrenom, PDO::PARAM_STR );
            $res->bindParam(':realiseAvecNom', $this->realiseAvecNom, PDO::PARAM_STR );
            $res->bindParam(':realiseAvecLoginAsalee', $this->realiseAvecLoginAsalee, PDO::PARAM_STR );

            $res->bindParam(':infAjout',$_SESSION['nom']);
            $res->bindParam(':cabinet',$_SESSION['cabinet']);

            $date = date('Y-m-d H:i:s');
            $res->bindParam(':dateAjout',$date);

            $res->bindParam(':estActif',$this->estActif);
            $res->execute();
        }
        catch (PDOException $exception)
        {
            echo $exception->getMessage();
        }
    }

    public function update()
    {


        $sql = 'UPDATE entretienAnnuel SET difficultesRencontrees = :difficultesRencontrees , ressourcesIdentifiees = :ressourcesIdentifiees , formationsSuivies = :formationsSuivies , realisationsMarquantes = :realisationsMarquantes , perspectivesProEnvisagees = :perspectivesProEnvisagees , besoinsASatisfaire = :besoinsASatisfaire , projetAcademique = :projetAcademique , realiseAvecPrenom = :realiseAvecPrenom , realiseAvecNom = :realiseAvecNom , realiseAvecLoginAsalee = :realiseAvecLoginAsalee , estActif = :estActif WHERE id = :id';

        try
        {

            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $res = $this->con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

            $res->bindParam(':id',$this->id, PDO::PARAM_INT);
            $res->bindParam(':difficultesRencontrees', $this->difficultesRencontrees, PDO::PARAM_STR );
            $res->bindParam(':ressourcesIdentifiees', $this->ressourcesIdentifiees, PDO::PARAM_STR );
            $res->bindParam(':formationsSuivies', $this->formationsSuivies, PDO::PARAM_STR );
            $res->bindParam(':realisationsMarquantes', $this->realisationsMarquantes, PDO::PARAM_STR );
            $res->bindParam(':perspectivesProEnvisagees', $this->perspectivesProEnvisagees, PDO::PARAM_STR );
            $res->bindParam(':besoinsASatisfaire', $this->besoinsASatisfaire, PDO::PARAM_STR );
            $res->bindParam(':projetAcademique', $this->projetAcademique, PDO::PARAM_INT );
            $res->bindParam(':realiseAvecPrenom', $this->realiseAvecPrenom, PDO::PARAM_STR );
            $res->bindParam(':realiseAvecNom', $this->realiseAvecNom, PDO::PARAM_STR );
            $res->bindParam(':realiseAvecLoginAsalee', $this->realiseAvecLoginAsalee, PDO::PARAM_STR );
//            $res->bindParam(':infAjout',$_SESSION['nom']);
//            $res->bindParam(':cabinet',$_SESSION['cabinet']);
            $res->bindParam(':estActif',$this->estActif);
            $res->execute();
        }
        catch (PDOException $exception)
        {
            echo $exception->getMessage();
        }
    }

    public function getInfNom($infLogin)
    {
        $infNom = array();
        $sql = "SELECT  nom, prenom
                FROM    annuaire.identifications
                WHERE   login = '" .$infLogin. "'";
        try
        {
            $res = $this->con->query($sql);

            $infNom = $res->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            exit($e->getMessage());
        }

        return $infNom['nom']. ' ' . $infNom['prenom'];
    }

    public function delete($entretienAnnuelId)
    {
        $sql = 'UPDATE entretienAnnuel SET estActif = 0 WHERE id = :id';

        try
        {
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $res = $this->con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $res->bindParam(':id',$entretienAnnuelId, PDO::PARAM_INT);
            $res->execute();
        }
        catch (PDOException $exception)
        {
            echo $exception->getMessage();
        }
    }
    */
}

