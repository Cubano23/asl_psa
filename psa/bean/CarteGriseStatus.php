<?php
/**
 * Created by PhpStorm.
 * User: gisgo
 * Date: 12/10/18
 * Time: 20:05
 */

require_once "persistence/ConnectionInformedPDO.php";


class CarteGriseStatus
{
    private $con; //variable de connexion
    public $id;
    public $intitule;
    public $dcreat;
    public $dmaj;

    public function __construct()
    {
        $db = ConnectionInformedPDO::getInstance();
        $this->con = $db->getDbh();
    }

    function CarteGriseStatus(
        $id    = NULL,
        $intitule    = NULL,
        $dcreat    = NULL,
        $dmaj    = NULL
    )
    {
        $this->id = $id;
        $this->intitule = $intitule;
        $this->dcreat = $dcreat;
        $this->dmaj = $dmaj;
    }


    public function getById($demandeCarteGriseStatusId)
    {
        $demandeCarteGriseStatus = array();
        $sql = "SELECT   id,intitule,dcreat,dmaj
                FROM demande_carte_grise_status
                WHERE    id = " . $demandeCarteGriseStatusId ;
        try
        {
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $res = $this->con->query($sql);

            $demandeCarteGriseStatus = $res->fetch(PDO::FETCH_ASSOC);
            $demandeCarteGriseStatus['infNom'] = $this->getInfNom($demandeCarteGriseStatus['infAjout']);
        }
        catch (PDOException $e)
        {
            exit($e->getMessage());
        }

        return $demandeCarteGriseStatus;
    }

    public function save()
    {
        $sql = 'INSERT INTO demande_carte_grise_status (intitule) VALUES (:intitule)';


        try
        {
            $res = $this->con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $res->bindParam(':intitule', $this->difficultesRencontrees, PDO::PARAM_STR );
            $res->execute();
        }
        catch (PDOException $exception)
        {
            echo $exception->getMessage();
        }
    }

    public function update()
    {


        $sql = 'UPDATE demande_carte_grise_status SET intitule = :intitule WHERE id = :id';

        try
        {

            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $res = $this->con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

            $res->bindParam(':id',$this->id, PDO::PARAM_INT);
            $res->bindParam(':intitule', $this->difficultesRencontrees, PDO::PARAM_STR );
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

