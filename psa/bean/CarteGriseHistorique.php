<?php
/**
 * Created by PhpStorm.
 * User: gisgo
 * Date: 12/10/18
 * Time: 20:05
 */

require_once "persistence/ConnectionInformedPDO.php";


class CarteGriseHistorique
{
    private $con; //variable de connexion
    public $id;
    public $id_demandeur;
    public $login_demandeur;
    public $date_obtention;
    public $puissance;
    public $precisions;
    public $justificatif;
    public $dcreat;
    public $dmaj;


    public function __construct()
    {
        $db = ConnectionInformedPDO::getInstance();
        $this->con = $db->getDbh();
    }

    function CarteGriseHistorique(
        $id   = NULL,
        $id_demandeur   = NULL,
        $login_demandeur   = NULL,
        $date_obtention   = NULL,
        $puissance   = NULL,
        $precisions   = NULL,
        $justificatif   = NULL,
        $dcreat   = NULL,
        $dmaj   = NULL
    )
    {

        $this->id = $id;
        $this->id_demandeur = $id_demandeur;
        $this->login_demandeur = $login_demandeur;
        $this->date_obtention = $date_obtention;
        $this->puissance = $puissance;
        $this->precisions = $precisions;
        $this->justificatif = $justificatif;
        $this->dcreat = $dcreat;
        $this->dmaj = $dmaj;
    }


    public function getById($demandeCarteGriseHistoriqueId)
    {
        $demandeCarteGriseHistorique = array();
        $sql = "SELECT   id,id_demandeur,login_demandeur,date_obtention,puissance,precisions,justificatif,dcreat,dmaj
                FROM demande_carte_grise_historique
                WHERE    id = " . $demandeCarteGriseHistoriqueId ;
        try
        {
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $res = $this->con->query($sql);

            $demandeCarteGriseHistorique = $res->fetch(PDO::FETCH_ASSOC);
            $demandeCarteGriseHistorique['infNom'] = $this->getInfNom($demandeCarteGriseHistorique['infAjout']);
        }
        catch (PDOException $e)
        {
            exit($e->getMessage());
        }

        return $demandeCarteGriseHistorique;
    }

    public function save()
    {
        $sql = 'INSERT INTO demande_carte_grise_historique (id_demandeur,login_demandeur,date_obtention,puissance,precisions,justificatif) VALUES (:id_demandeur,:login_demandeur,:date_obtention,:puissance,:precisions,:justificatif)';


        try
        {
            $res = $this->con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

            $res->bindParam(':id_demandeur', $this->id_demandeur, PDO::PARAM_INT );
            $res->bindParam(':login_demandeur', $this->login_demandeur, PDO::PARAM_STR );
            $res->bindParam(':date_obtention', $this->date_obtention, PDO::PARAM_STR );
            $res->bindParam(':puissance', $this->puissance, PDO::PARAM_STR );
            $res->bindParam(':precisions', $this->precisions, PDO::PARAM_STR );
            $res->bindParam(':justificatif', $this->justificatif, PDO::PARAM_STR );
            $res->execute();
        }
        catch (PDOException $exception)
        {
            echo $exception->getMessage();
        }
    }

    public function update()
    {


        $sql = 'UPDATE demande_carte_grise_historique SET id_demandeur = :id_demandeur,login_demandeur = :login_demandeur,date_obtention = :date_obtention,puissance = :puissance,precisions = :precisions,justificatif = :justificatif WHERE id = :id';

        try
        {

            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $res = $this->con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

            $res->bindParam(':id',$this->id, PDO::PARAM_INT);
            $res->bindParam(':id_demandeur', $this->id_demandeur, PDO::PARAM_INT );
            $res->bindParam(':login_demandeur', $this->login_demandeur, PDO::PARAM_STR );
            $res->bindParam(':date_obtention', $this->date_obtention, PDO::PARAM_STR );
            $res->bindParam(':puissance', $this->puissance, PDO::PARAM_STR );
            $res->bindParam(':precisions', $this->precisions, PDO::PARAM_STR );
            $res->bindParam(':justificatif', $this->justificatif, PDO::PARAM_STR );
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

