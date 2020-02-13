<?php
/**
 * Created by SublimeText
 * User: Gisgo
 * Date: 21-11-2018
 * Time: 14:40
 */

require_once "persistence/ConnectionInformedPDO.php";
require_once "persistence/ConnectionAnnuairePDO.php";

require_once "bean/DemandeRibIdentification.php";
require_once "bean/DemandeRibHistorique.php";
require_once "bean/DemandeRibStatus.php";

class DemandeRibSuivi
{
    private $con;
    private $con_annuaire;

    public $id;
    public $id_rib;
    public $id_status;
    public $id_historique;
    public $id_utilisateur;
    public $login_utilisateur;
    public $notes;
    public $dcreat;
    public $dmaj;

    public $demandeRib;
    public $statusRib;
    public $historiqueRib;

    public function __construct()
    {
        $db = ConnectionInformedPDO::getInstance();
        $this->con = $db->getDbh();

        $db_ann = ConnectionAnnuairePDO::getInstance();
        $this->con_annuaire = $db_ann->getDbh();

        $this->demandeRib = new demandeRibIdentification();
        $this->statusRib = new demandeRibStatus();
        $this->historiqueRib = new demandeRibHistorique();
    }

    public function __construct1($id = null, $id_rib = null, $id_status = null, $id_historique = null, $id_utilisateur = null, $login_utilisateur = null, $notes = null, $dcreat = null, $dmaj = null)
    {
        $db = ConnectionInformedPDO::getInstance();
        $this->con = $db->getDbh();

        $db_ann = ConnectionAnnuairePDO::getInstance();
        $this->con_annuaire = $db_ann->getDbh();

        $this->id = $id;
        $this->id_rib = $id_rib;
        $this->id_status = $id_status;
        $this->id_historique = $id_historique;
        $this->id_utilisateur = $id_utilisateur;
        $this->login_utilisateur = $login_utilisateur;
        $this->notes = $notes;
        $this->dcreat = $dcreat;
        $this->dmaj = $dmaj;

        $this->demandeRib = new demandeRibIdentification();
        $this->statusRib = new demandeRibSuivi();
        $this->historiqueRib = new demandeRibHistorique();
    }

    public function getById($id)
    {
        $query = "SELECT *
                  FROM demande_rib_suivi
                  WHERE id = :id";

        try
        {
            $res = $this->con->prepare($query);
            $res->bindParam(":id",$id);
            $res->execute();
            $result = $res->fetch(PDO::FETCH_OBJ);

            $this->id = $result->id;
            $this->id_rib = $result->id_demande_rib;
            $this->id_status = $result->id_status;
            $this->id_historique = $result->id_historique;
            $this->id_utilisateur = $result->id_utilisateur;
            $this->login_utilisateur = $result->login_utilisateur;
            $this->notes = $result->notes;
            $this->dcreat = $result->dcreat;
            $this->dmaj = $result->dmaj;

            $this->demandeRib->getById($result->id_rib);
            $this->statusRib->getById($result->id_status);
            $this->historiqueRib->getById($result->id_historique);
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }
    }

    public function save()
    {
        $sql = "INSERT INTO demande_rib_suivi (id_demande_rib, id_status, id_historique, id_utilisateur, login_utilisateur, notes, dcreat) 
                VALUES (:id_rib, :id_status, :id_historique, :id_utilisateur, :login_utilisateur, :notes, :dcreat)";

        try
        {
            $res = $this->con->prepare($sql);

            $res->bindParam(':id_rib', $this->id_rib, PDO::PARAM_INT );
            $res->bindParam(':id_status', $this->id_status, PDO::PARAM_INT );
            $res->bindParam(':id_historique', $this->id_historique, PDO::PARAM_INT );
            $res->bindParam(':id_utilisateur', $this->id_utilisateur, PDO::PARAM_INT );
            $res->bindParam(':login_utilisateur', $this->login_utilisateur, PDO::PARAM_STR );
            $res->bindParam(':notes', $this->notes, PDO::PARAM_STR );
                $date = date('Y-m-d H:i:s');
            $res->bindParam(':dcreat', $date);

            $res->execute();
            
            $this->id = $this->con->lastInsertId();
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }
    }

    public function load($page, $rows, $sort, $order, $infirmiereSearch, $statusSearch, $dateSearch)
    {
        $offset = ($page - 1) * $rows;

        $sql = "SELECT *
                FROM demande_rib_vue_resumee
                WHERE 1 ";

      	if (!empty($infirmiereSearch))
            $sql .= "AND login_demandeur = '$infirmiereSearch' ";

        if (!empty($statusSearch))
            $sql .= "AND id_status = $statusSearch ";

        if (!empty($dateSearch))
            $sql .= "AND date_demande = $dateSearch ";

        $query = $sql . "ORDER BY $sort $order limit $offset,$rows";

        $demande_rib_suivi = array();
        try
        {
            $rs = $this->con->prepare($sql);
            $rs->execute();
            $demande_rib_suivi['total'] = $rs->rowCount();
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }

        try
        {
            $res = $this->con->prepare($query);
            $res->execute();
            $demande_rib_suivi['data'] = $res->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }

        return $demande_rib_suivi;
    }

    public function loadById($id)
    {

        $sql = "SELECT *
                FROM demande_rib_vue_resumee
                WHERE id = :id";

        try
        {
            $rs = $this->con->prepare($sql);

            $rs->bindParam(':id', $id);

            $rs->execute();
            $demande_rib_suivi =  $rs->fetch(PDO::FETCH_ASSOC);
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }

        return $demande_rib_suivi;
    }

    public function getInfs()
    {
        $list_inf = array();

        $query = "select * from identifications";
        try
        {
            $list_inf = $this->con_annuaire->query($query)->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $exception)
        {
            error_log("Une erreur PDO s'est produite : " . $exception->getMessage());
        }

        return $list_inf;
    }

    public function getUserIdByLogin($login)
    {
        $query = "SELECT id
                  FROM identifications
                  WHERE login = :login";

        $infId = null;
        try
        {
            $res = $this->con_annuaire->prepare($query);
            $res->bindParam(":login",$login);
            $res->execute();

            $res = $res->fetch(PDO::FETCH_ASSOC);
            $infId = $res['id'];
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }

        if ($infId == null)
            return false;

        return $infId;
    }

    public function getUserProfessionByLogin($login)
    {
        $query = "SELECT profession
                  FROM identifications
                  WHERE login = :login";

        $infProfession = null;
        try
        {
            $res = $this->con_annuaire->prepare($query);
            $res->bindParam(":login",$login);
            $res->execute();

            $res = $res->fetch(PDO::FETCH_ASSOC);
            $infProfession = $res['profession'];
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }

        if ($infProfession == null)
            return false;

        return $infProfession;
    }

    public function loadDetail($identifiant_demande)
    {
        $sql = "SELECT *
                FROM demande_rib_vue_detaillee
                WHERE id = :id";

        $demande_rib_suivi = array();
        try
        {
            $rs = $this->con->prepare($sql);
            $rs->bindParam(":id",$identifiant_demande);
            $rs->execute();
            $demande_rib_suivi['total'] = $rs->rowCount();
            $demande_rib_suivi['data'] = $rs->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }

        return $demande_rib_suivi;
    }

    
    public function loadDetailById($id)
    {

        $sql = "SELECT *
                FROM demande_rib_vue_detaillee
                WHERE id = :id";

        try
        {
            $rs = $this->con->prepare($sql);

            $rs->bindParam(':id', $id);

            $rs->execute();
            $demande_rib_suivi =  $rs->fetch(PDO::FETCH_ASSOC);
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }

        return $demande_rib_suivi;
    }
}