<?php
/**
 * Created by PhpStorm.
 * User: allam
 * Date: 12/10/18
 * Time: 10:44
 */

require_once "persistence/ConnectionInformedPDO.php";
require_once "persistence/ConnectionAnnuairePDO.php";

require_once "bean/DemandeFraisIdentification.php";
require_once "bean/DemandeFraisHistorique.php";
require_once "bean/DemandeFraisStatus.php";

class DemandeFraisSuivi
{
    private $con;
    private $con_annuaire;

    public $id;
    public $id_frais;
    public $id_status;
    public $id_historique;
    public $id_utilisateur;
    public $login_utilisateur;
    public $notes;
    public $dcreat;
    public $dmaj;

    public $demandeFrais;
    public $statusFrais;
    public $historiqueFrais;

    public function __construct()
    {
        $db = ConnectionInformedPDO::getInstance();
        $this->con = $db->getDbh();

        $db_ann = ConnectionAnnuairePDO::getInstance();
        $this->con_annuaire = $db_ann->getDbh();

        $this->demandeFrais = new DemandeFraisIdentification();
        $this->statusFrais = new DemandeFraisStatus();
        $this->historiqueFrais = new DemandeFraisHistorique();
    }

    public function __construct1($id = null, $id_frais = null, $id_status = null, $id_historique = null, $id_utilisateur = null, $login_utilisateur = null, $notes = null, $dcreat = null, $dmaj = null)
    {
        $db = ConnectionInformedPDO::getInstance();
        $this->con = $db->getDbh();

        $db_ann = ConnectionAnnuairePDO::getInstance();
        $this->con_annuaire = $db_ann->getDbh();

        $this->id = $id;
        $this->id_frais = $id_frais;
        $this->id_status = $id_status;
        $this->id_historique = $id_historique;
        $this->id_utilisateur = $id_utilisateur;
        $this->login_utilisateur = $login_utilisateur;
        $this->notes = $notes;
        $this->dcreat = $dcreat;
        $this->dmaj = $dmaj;

        $this->demandeFrais = new DemandeFraisIdentification();
        $this->statusFrais = new DemandeFraisSuivi();
        $this->historiqueFrais = new DemandeFraisHistorique();
    }

    public function getById($id)
    {
        $query = "SELECT *
                  FROM demande_frais_suivi
                  WHERE id = :id";

        try
        {
            $res = $this->con->prepare($query);
            $res->bindParam(":id",$id);
            $res->execute();
            $result = $res->fetch(PDO::FETCH_OBJ);

            $this->id = $result->id;
            $this->id_frais = $result->id_frais;
            $this->id_status = $result->id_status;
            $this->id_historique = $result->id_historique;
            $this->id_utilisateur = $result->id_utilisateur;
            $this->login_utilisateur = $result->login_utilisateur;
            $this->notes = $result->notes;
            $this->dcreat = $result->dcreat;
            $this->dmaj = $result->dmaj;

            $this->demandeFrais->getById($result->id_frais);
            $this->statusFrais->getById($result->id_status);
            $this->historiqueFrais->getById($result->id_historique);
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }
    }

    public function save()
    {
        $sql = "INSERT INTO demande_frais_suivi (id_frais, id_status, id_historique, id_utilisateur, login_utilisateur, notes, dcreat) 
                VALUES (:id_frais, :id_status, :id_historique, :id_utilisateur, :login_utilisateur, :notes, :dcreat)";

        try
        {
            $res = $this->con->prepare($sql);

            $res->bindParam(':id_frais', $this->id_frais, PDO::PARAM_INT );
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
  
   

    public function load($page, $rows, $sort, $order, $natureSearch, $infirmiereSearch, $statusSearch, $dateSearch,  $idSearch, $dateSearch_frais)
    {
        $offset = ($page - 1) * $rows;

        $sql = "SELECT *
                FROM demande_frais_vue_resumee
                WHERE 1 ";

        if (!empty($natureSearch))
            $sql .= "AND nature LIKE '%$natureSearch%' ";

        if (!empty($infirmiereSearch))
            $sql .= "AND login_demandeur = '$infirmiereSearch' ";

        if (!empty($statusSearch))
            $sql .= "AND id_status = $statusSearch ";

        if (!empty($dateSearch))
            $sql .= "AND date_demande = '$dateSearch' ";

        if (!empty($dateSearch_frais))
            $sql .= "AND date_frais = '$dateSearch_frais' ";


        if (!empty($idSearch))
            $sql .= "AND id =  $idSearch ";

        $query = $sql . "ORDER BY $sort $order limit $offset,$rows";

        $demande_frais_suivi = array();
        try
        {
            $rs = $this->con->prepare($sql);
            $rs->execute();
            $demande_frais_suivi['total'] = $rs->rowCount();
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }

        try
        {
            $res = $this->con->prepare($query);
            $res->execute();
            $demande_frais_suivi['data'] = $res->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }

        return $demande_frais_suivi;
    }

    public function loadById($id)
    {

        $sql = "SELECT *
                FROM demande_frais_vue_resumee
                WHERE id = :id";

        try
        {
            $rs = $this->con->prepare($sql);

            $rs->bindParam(':id', $id);

            $rs->execute();
            $demande_frais_suivi =  $rs->fetch(PDO::FETCH_ASSOC);
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }

        return $demande_frais_suivi;
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

    public function getUserTauxAndPowerByLogin($login)
    {
        $query = "SELECT puissance, taux AS taux_applique
                  FROM carte_grise_grille_taux_actuelle
                  WHERE login_demandeur = :login";

        $data = null;
        try
        {
            $res = $this->con->prepare($query);
            $res->bindParam(":login",$login);
            $res->execute();

            $data = $res->fetch(PDO::FETCH_ASSOC);
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }

        if ($data == null)
            return false;

        return $data;
    }

    public function loadDetail($identifiant_demande)
    {
        $sql = "SELECT *
                FROM demande_frais_vue_detaillee
                WHERE id = :id";

        $demande_frais_suivi = array();
        try
        {
            $rs = $this->con->prepare($sql);
            $rs->bindParam(":id",$identifiant_demande);
            $rs->execute();
            $demande_frais_suivi['total'] = $rs->rowCount();
            $demande_frais_suivi['data'] = $rs->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }

        return $demande_frais_suivi;
    }

    public function loadDetailById($id)
    {

        $sql = "SELECT *
                FROM demande_frais_vue_detaillee
                WHERE id = :id";

        try
        {
            $rs = $this->con->prepare($sql);

            $rs->bindParam(':id', $id);

            $rs->execute();
            $demande_frais_suivi =  $rs->fetch(PDO::FETCH_ASSOC);
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }

        return $demande_frais_suivi;
    }
}
