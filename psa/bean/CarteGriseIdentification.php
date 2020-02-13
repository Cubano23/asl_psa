<?php
/**
 * Created by PhpStorm.
 * User: gisgo
 * Date: 12/10/18
 * Time: 20:05
 */

require_once "persistence/ConnectionInformedPDO.php";


class CarteGriseIdentification
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

    function CarteGriseIdentification(
        $id = NULL,
        $intitule = NULL,
        $dcreat = NULL,
        $dmaj = NULL
    )
    {
        $this->id = $id;
        $this->intitule = $intitule;
        $this->dcreat = $dcreat;
        $this->dmaj = $dmaj;
    }

    public function getById($demandeCarteGriseId)
    {
        $demandeCarteGrise = array();
        $sql = "SELECT   id, intitule, dcreat, dmaj
                FROM demande_carte_grise_identification
                WHERE    id = " . $demandeCarteGriseId ." ";
        try
        {
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $res = $this->con->query($sql);

            $demandeCarteGrise = $res->fetch(PDO::FETCH_ASSOC);
            $demandeCarteGrise['infNom'] = $this->getInfNom($demandeCarteGrise['infAjout']);
        }
        catch (PDOException $e)
        {
            exit($e->getMessage());
        }

        return $demandeCarteGrise;
    }

    public function save()
    {
        $sql = 'INSERT INTO demande_carte_grise_identification (intitule) VALUES (:intitule)';


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


        $sql = 'UPDATE demande_carte_grise_identification SET intitule = :intitule WHERE id = :id';

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

//
//
//    public function getListe()
//    {
//        $listeEntretiens = array();
//        $sql = "SELECT   id, infAjout, dateAjout
//                FROM     entretienAnnuel
//                WHERE    infAJout = '" . $_SESSION['nom'] ."' AND cabinet = '" . $_SESSION['cabinet'] ."' AND estActif = 1
//                ORDER BY id DESC
//                LIMIT    30";
//        try
//        {
//            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//            $res = $this->con->query($sql);
//            $listeEntretiens = $res->fetchAll(PDO::FETCH_CLASS, 'EntretienAnnuel');
//        }
//        catch (PDOException $e)
//        {
//            exit($e->getMessage());
//        }
//        return $listeEntretiens;
//    }
//
//
//    public function getInfNom($infLogin)
//    {
//        $infNom = array();
//        $sql = "SELECT  nom, prenom
//                FROM    annuaire.identifications
//                WHERE   login = '" .$infLogin. "'";
//        try
//        {
//            $res = $this->con->query($sql);
//
//            $infNom = $res->fetch(PDO::FETCH_ASSOC);
//        }
//        catch (PDOException $e)
//        {
//            exit($e->getMessage());
//        }
//
//        return $infNom['nom']. ' ' . $infNom['prenom'];
//    }
//
//    public function delete($entretienAnnuelId)
//    {
//        $sql = 'UPDATE entretienAnnuel SET estActif = 0 WHERE id = :id';
//
//        try
//        {
//            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//            $res = $this->con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
//            $res->bindParam(':id',$entretienAnnuelId, PDO::PARAM_INT);
//            $res->execute();
//        }
//        catch (PDOException $exception)
//        {
//            echo $exception->getMessage();
//        }
//    }

}

