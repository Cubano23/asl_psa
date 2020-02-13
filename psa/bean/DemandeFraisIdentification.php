<?php
/**
 * Created by PhpStorm.
 * User: allam
 * Date: 12/10/18
 * Time: 10:41
 */

require_once "persistence/ConnectionInformedPDO.php";

class DemandeFraisIdentification
{
    private $con;

    public $id;
    public $intitule;
    public $dcreat;
    public $dmaj;

    public function __construct()
    {
        $db = ConnectionInformedPDO::getInstance();
        $this->con = $db->getDbh();
    }

    public function __construct1($id = null, $intitule = null, $dcreat = null, $dmaj = null)
    {
        $db = ConnectionInformedPDO::getInstance();
        $this->con = $db->getDbh();

        $this->id = $id;
        $this->intitule = $intitule;
        $this->dcreat = $dcreat;
        $this->dmaj = $dmaj;
    }

    public function getById($id)
    {
        $query = "SELECT *
                  FROM demande_frais_identification
                  WHERE id = :id";

        try
        {
            $res = $this->con->prepare($query);
            $res->bindParam(":id",$id);
            $res->execute();
            $result = $res->fetch(PDO::FETCH_OBJ);

            $this->id = $result->id;
            $this->intitule = $result->intitule;
            $this->dcreat = $result->dcreat;
            $this->dmaj = $result->dmaj;
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }
    }

    public function save()
    {
        $sql = "INSERT INTO demande_frais_identification (intitule, dcreat) 
                VALUES (:intitule, :dcreat)";

        try
        {
            $res = $this->con->prepare($sql);

            $res->bindParam(':intitule', $this->intitule, PDO::PARAM_STR );
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

    public function update()
    {
        $sql = "UPDATE demande_frais_identification
                SET intitule = :intitule";

        try
        {
            $res = $this->con->prepare($sql);

            $res->bindParam(':intitule', $this->intitule, PDO::PARAM_STR );

            $res->execute();
            $result = $res->fetch(PDO::FETCH_CLASS, get_class($this));

            $this->intitule = $result->intitule;
            $this->dmaj = $result->dmaj;
        }
        catch (Exception $exception)
        {
            error_log($exception->getMessage());
        }
    }
}