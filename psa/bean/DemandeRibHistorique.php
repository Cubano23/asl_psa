<?php
/**
 * Created by SublimeText
 * User: Gisgo
 * Date: 21-11-2018
 * Time: 15:14
 */

require_once "persistence/ConnectionInformedPDO.php";

class DemandeRibHistorique
{
    private $con;

    public $id;
    public $id_demandeur;
    public $login_demandeur;
    public $iban;
    public $justificatif;
    public $dcreat;
    public $dmaj;

    public function __construct()
    {
        $db = ConnectionInformedPDO::getInstance();
        $this->con = $db->getDbh();
    }

    public function __construct1($id = null, $id_demandeur = null, $login_demandeur = null, $iban = null, $justificatif = null, $dcreat = null, $dmaj = null)
    {
        $db = ConnectionInformedPDO::getInstance();
        $this->con = $db->getDbh();

        $this->id = $id;
        $this->id_demandeur = $id_demandeur;
        $this->login_demandeur = $login_demandeur;
        $this->iban = $iban;
        $this->justificatif = $justificatif;
        $this->dcreat = $dcreat;
        $this->dmaj = $dmaj;
    }

    public function getById($id)
    {
        $query = "SELECT *
                  FROM demande_rib_historique
                  WHERE id = :id";

        try
        {
            $res = $this->con->prepare($query);
            $res->bindParam(":id", $id);
            $res->execute();
            $result = $res->fetch(PDO::FETCH_OBJ);

            $this->id = $result->id;
            $this->id_demandeur = $result->id_demandeur;
            $this->login_demandeur = $result->login_demandeur;
            $this->iban = $result->iban;
            $this->justificatif = $result->justificatif;
            $this->dcreat = $result->dcreat;
            $this->dmaj = $result->dmaj;
            
        }
        catch (Exception $exception)
        {
            error_log($exception ->getMessage());
        }
       
    }

    public function save()
    {
        $sql = "INSERT INTO demande_rib_historique (id_demandeur, login_demandeur, iban, justificatif, dcreat) 
                VALUES (:id_demandeur, :login_demandeur, :iban, :justificatif, :dcreat)";

        try
        {
            $res = $this->con->prepare($sql);

            $res->bindParam(':id_demandeur', $this->id_demandeur, PDO::PARAM_INT );
            $res->bindParam(':login_demandeur', $this->login_demandeur, PDO::PARAM_STR );
            $res->bindParam('iban', $this->iban, PDO::PARAM_STR);
            $res->bindParam(':justificatif', $this->justificatif, PDO::PARAM_STR );
            $date = date('Y-m-d H:i:s');
            $res->bindParam(':dcreat', $date );

            $res->execute();

            $this->id = $this->con->lastInsertId();
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }
    }
}