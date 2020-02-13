<?php
/**
 * Created by PhpStorm.
 * User: allam
 * Date: 12/10/18
 * Time: 10:36
 */

require_once "persistence/ConnectionInformedPDO.php";

class DemandeFraisHistorique
{
    private $con;

    public $id;
    public $id_demandeur;
    public $login_demandeur;
    public $date_frais;
    public $nature;
    public $motif;
    public $distance;
    public $taux_applique;
    public $puissance;
    public $montant;
    public $justificatif;
    public $dcreat;
    public $dmaj;

    public function __construct()
    {
        $db = ConnectionInformedPDO::getInstance();
        $this->con = $db->getDbh();
    }

    public function __construct1($id = null, $id_demandeur = null, $login_demandeur = null, $date_frais = null, $nature = null, $motif = null, $distance = null, $taux_applique = null,$puissance = null, $montant = null, $justificatif = null, $dcreat = null, $dmaj = null)
    {
        $db = ConnectionInformedPDO::getInstance();
        $this->con = $db->getDbh();

        $this->id = $id;
        $this->id_demandeur = $id_demandeur;
        $this->login_demandeur = $login_demandeur;
        $this->date_frais = $date_frais;
        $this->nature = $nature;
        $this->motif = $motif;
        $this->distance = $distance;
        $this->taux_applique = $taux_applique;
        $this->puissance = $puissance;
        $this->montant = $montant;
        $this->justificatif = $justificatif;
        $this->dcreat = $dcreat;
        $this->dmaj = $dmaj;
    }

    public function getById($id)
    {
        $query = "SELECT *
                  FROM demande_frais_historique
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
            $this->date_frais = $result->date_frais;
            $this->nature = $result->nature;
            $this->motif = $result->motif;
            $this->distance = $result->distance;
            $this->taux_applique = $result->taux_applique;
            $this->puissance = $result->puissance;
            $this->montant = $result->montant;
            $this->justificatif = $result->justificatif;
            $this->dcreat = $result->dcreat;
            $this->dmaj = $result->dmaj;
        }
        catch (Exception $exception)
        {
            error_log($exception ->getMessage());
        }
    }
    //fonction pour recuperer les donnees à extration excel
    public function getAllByStatus($id_status)                 

            {
                $demande = array();
                if($id_status >= 1){
                    $query = "SELECT id as ID,id_demandeur as ID_DEMANDEUR, login_demandeur as LOGIN_DEMANDEUR, date_frais as DATE_FRAIS, nature as NATURE, motif as MOTIF, distance as DISTANCE, taux_applique as TAUX_APPLIQUE , puissance as PUISSANCE, montant as MONTANT, dernierStatus as DERNIER_STATUS
                              FROM demande_frais_vue_resumee
                              WHERE id_status =  $id_status ";
                }else{
                    $query = "SELECT id as ID,id_demandeur as ID_DEMANDEUR, login_demandeur as LOGIN_DEMANDEUR, date_frais as DATE_FRAIS, nature as NATURE, motif as MOTIF, distance as DISTANCE, taux_applique as TAUX_APPLIQUE , puissance as PUISSANCE, montant as MONTANT, dernierStatus as DERNIER_STATUS
                              FROM demande_frais_vue_resumee";
                }
                try
                {
                   $demande = $this->con->query($query)->fetchAll(PDO::FETCH_ASSOC);
                  
                }
                catch (PDOException $exception)
                {
                    error_log("Une erreur PDO s'est produite : " . $exception->getMessage());
                }

                return $demande;
            }
                

    public function save()
    {
        $sql = "INSERT INTO demande_frais_historique (id_demandeur, login_demandeur, date_frais, nature, motif, distance, taux_applique, puissance, montant, justificatif, dcreat) 
                VALUES (:id_demandeur, :login_demandeur, :date_frais, :nature, :motif, :distance, :taux_applique,:puissance, :montant, :justificatif, :dcreat)";

        try
        {
            $res = $this->con->prepare($sql);

            $res->bindParam(':id_demandeur', $this->id_demandeur, PDO::PARAM_INT );
            $res->bindParam(':login_demandeur', $this->login_demandeur, PDO::PARAM_STR );
            $res->bindParam(':date_frais', $this->date_frais );
            $res->bindParam(':nature', $this->nature, PDO::PARAM_STR );
            $res->bindParam(':motif', $this->motif, PDO::PARAM_STR );
            $res->bindParam(':distance', $this->distance );
            $res->bindParam(':taux_applique', $this->taux_applique );
            $res->bindParam(':puissance', $this->puissance );
            $res->bindParam(':montant', $this->montant );
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