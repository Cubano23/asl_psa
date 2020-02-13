<?php

require_once "persistence/ConnectionInformedPDO.php";
require_once "persistence/ConnectionAnnuairePDO.php";

class GetEmail{

    public $con;   
    public $email;
    public $db;

public function __construct()
    {

        $db = ConnectionInformedPDO::getInstance();
        $this->con = $db->getDbh();

        $db_ann = ConnectionAnnuairePDO::getInstance();
        $this->con_annuaire = $db_ann->getDbh();

       
    }
public function getEmail($login_demandeur)
    {
       
        $sql = "SELECT   email
                FROM identifications
                WHERE login = '$login_demandeur'";
        try
        {
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $res = $this->con_annuaire->query($sql);

            $this->email = $res->fetch(PDO::FETCH_ASSOC);
            
        }
        catch (PDOException $e)
        {
            exit($e->getMessage());
        }

        return $this->email['email'];
  

}



}