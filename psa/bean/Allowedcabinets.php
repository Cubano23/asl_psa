<?php

require_once "persistence/ConnectionAnnuairePDO.php";


class Allowedcabinets{

private $con_annuaire;
public $listUtilisateurs;


public function __construct()
    {
    
        $db_ann = ConnectionAnnuairePDO::getInstance();
        $this->con_annuaire = $db_ann->getDbh();
    }


public function getAllUtilisateurs(){

            $this->listUtilisateurs = array();

            $sql = "select allowedcabinets.id, allowedcabinets.login,allowedcabinets.cabinet, 
            dmaj, recordstatus, historique_allowedcabinets.dstatus as dstatus from  allowedcabinets, 
            historique_allowedcabinets where allowedcabinets.login=historique_allowedcabinets.login and 
            allowedcabinets.cabinet=historique_allowedcabinets.cabinet and  historique_allowedcabinets.actualstatus=0 
            order by login asc";
          

        try
        {      
            $this->listUtilisateurs = $this->con_annuaire->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (Exception $exception)
        {
        error_log($exception->getMessage());
        }

        return $this->listUtilisateurs;


}




}