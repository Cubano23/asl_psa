<?php
/**
 * Created by PhpStorm.
 * User: allam
 * Date: 09/11/18
 * Time: 14:34
 */

require_once "persistence/ConnectionInformedPDO.php";
require_once "persistence/ConnectionAnnuairePDO.php";

require_once "bean/DemandeCGIdentification.php";
require_once "bean/DemandeCGHistorique.php";
require_once "bean/DemandeCGStatus.php";

class DemandeCGSuivi
{
    private $con;
    private $con_annuaire;

    public $id;
    public $id_demande_carte_grise;
    public $id_status;
    public $id_historique;
    public $id_utilisateur;
    public $login_utilisateur;
    public $notes;
    public $dcreat;
    public $dmaj;

    public $demandeCG;
    public $statusCG;
    public $historiqueCG;

    public function __construct()
    {
        $db = ConnectionInformedPDO::getInstance();
        $this->con = $db->getDbh();

        $db_ann = ConnectionAnnuairePDO::getInstance();
        $this->con_annuaire = $db_ann->getDbh();

        $this->demandeCG = new DemandeCGIdentification();
        $this->statusCG = new DemandeCGStatus();
        $this->historiqueCG = new DemandeCGHistorique();
    }

    public function __construct1($id = null, $id_demande_carte_grise = null, $id_status = null, $id_historique = null, $id_utilisateur = null, $login_utilisateur = null, $notes = null, $dcreat = null, $dmaj = null)
    {
        $db = ConnectionInformedPDO::getInstance();
        $this->con = $db->getDbh();

        $db_ann = ConnectionAnnuairePDO::getInstance();
        $this->con_annuaire = $db_ann->getDbh();

        $this->id = $id;
        $this->id_demande_carte_grise = $id_demande_carte_grise;
        $this->id_status = $id_status;
        $this->id_historique = $id_historique;
        $this->id_utilisateur = $id_utilisateur;
        $this->login_utilisateur = $login_utilisateur;
        $this->notes = $notes;
        $this->dcreat = $dcreat;
        $this->dmaj = $dmaj;

        $this->demandeCG = new DemandeCGIdentification();
        $this->statusCG = new DemandeCGStatus();
        $this->historiqueCG = new DemandeCGHistorique();
    }

    public function getById($id)
    {
        $query = "SELECT *
                  FROM demande_carte_grise_suivi
                  WHERE id = :id";

        try
        {
            $res = $this->con->prepare($query);
            $res->bindParam(":id",$id);
            $res->execute();
            $result = $res->fetch(PDO::FETCH_OBJ);

            $this->id = $result->id;
            $this->id_demande_carte_grise = $result->id_demande_carte_grise;
            $this->id_status = $result->id_status;
            $this->id_historique = $result->id_historique;
            $this->id_utilisateur = $result->id_utilisateur;
            $this->login_utilisateur = $result->login_utilisateur;
            $this->notes = $result->notes;
            $this->dcreat = $result->dcreat;
            $this->dmaj = $result->dmaj;

            $this->demandeCG->getById($result->id_demande_carte_grise);
            $this->statusCG->getById($result->id_status);
            $this->historiqueCG->getById($result->id_historique);
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }
    }

    public function save()
    {
        $sql = "INSERT INTO demande_carte_grise_suivi (id_demande_carte_grise, id_status, id_historique, id_utilisateur, login_utilisateur, notes, dcreat) 
                VALUES (:id_demande_carte_grise, :id_status, :id_historique, :id_utilisateur, :login_utilisateur, :notes, :dcreat)";

        try
        {
            $res = $this->con->prepare($sql);

            $res->bindParam(':id_demande_carte_grise', $this->id_demande_carte_grise, PDO::PARAM_INT );
            $res->bindParam(':id_status', $this->id_status, PDO::PARAM_INT );
            $res->bindParam(':id_historique', $this->id_historique, PDO::PARAM_INT );
            $res->bindParam(':id_utilisateur', $this->id_utilisateur, PDO::PARAM_INT );
            $res->bindParam(':login_utilisateur', $this->login_utilisateur, PDO::PARAM_STR );
            $res->bindParam(':notes', $this->notes, PDO::PARAM_STR );
            $date = date('Y-m-d H:i:s');
            $res->bindParam(':dcreat', $date);

            $res->execute();
            $result = $res->fetch(PDO::FETCH_CLASS, get_class($this));

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
                FROM demande_carte_grise_vue_resumee
                WHERE 1 ";

        if (!empty($infirmiereSearch))
            $sql .= "AND login_demandeur = '$infirmiereSearch' ";

        if (!empty($statusSearch))
            $sql .= "AND id_status = $statusSearch ";

        if (!empty($dateSearch))
            $sql .= "AND date_demande = '$dateSearch' ";

        $query = $sql . "ORDER BY $sort $order limit $offset,$rows";

        $demande_cg_suivi = array();
        try
        {
            $rs = $this->con->prepare($sql);
            $rs->execute();
            $demande_cg_suivi['total'] = $rs->rowCount();
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }

        try
        {
            $res = $this->con->prepare($query);
            $res->execute();
            $demande_cg_suivi['data'] = $res->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }

        return $demande_cg_suivi;
    }

    public function loadById($id)
    {

        $sql = "SELECT *
                FROM demande_carte_grise_vue_resumee
                WHERE id = :id";

        try
        {
            $rs = $this->con->prepare($sql);

            $rs->bindParam(':id', $id);

            $rs->execute();
            $demande_cg_suivi =  $rs->fetch(PDO::FETCH_ASSOC);
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }

        return $demande_cg_suivi;
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

    public function getUserTauxByLogin($login)
    {
        $query = "SELECT taux AS taux_applique
                  FROM carte_grise_grille_taux_actuelle
                  WHERE login_demandeur = :login";

        $infTaux = null;
        try
        {
            $res = $this->con->prepare($query);
            $res->bindParam(":login",$login);
            $res->execute();

            $res = $res->fetch(PDO::FETCH_ASSOC);
            $infTaux = $res;
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }

        if ($infTaux == null)
            return false;

        return $infTaux;
    }

    public function loadDetail($identifiant_demande)
    {
        $sql = "SELECT *
                FROM demande_carte_grise_vue_detaillee
                WHERE id = :id";

        $demande_cg_suivi = array();
        try
        {
            $rs = $this->con->prepare($sql);
            $rs->bindParam(":id",$identifiant_demande);
            $rs->execute();
            $demande_cg_suivi['total'] = $rs->rowCount();
            $demande_cg_suivi['data'] = $rs->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }

        return $demande_cg_suivi;
    }

    public function loadDetailById($id)
    {

        $sql = "SELECT *
                FROM demande_carte_grise_vue_detaillee
                WHERE id = :id";

        try
        {
            $rs = $this->con->prepare($sql);

            $rs->bindParam(':id', $id);

            $rs->execute();
            $demande_cg_suivi =  $rs->fetch(PDO::FETCH_ASSOC);
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }

        return $demande_cg_suivi;
    }
}