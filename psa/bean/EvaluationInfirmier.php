<?php

require_once "persistence/ConnectionInformedPDO.php";
require_once "persistence/ConnectionAnnuairePDO.php";

class EvaluationInfirmier{
    private $con;
    private $con_annuaire;

    var $id;
    var $date;
    var $id_utilisateur;
    var $id_cabinet;
    var $degre_satisfaction;
    var $duree;
    var $consult_domicile;
    var $consult_tel;
    var $consult_collective;
    var $points_positifs;
    var $points_ameliorations;
    var $type_consultation;
    var $ecg;
    var $ecg_seul;
    var $monofil;
    var $exapied;
    var $hba;
    var $tension;
    var $spirometre;
    var $spirometre_seul;
    var $t_cognitif;
    var $autre;
    var $prec_autre;
    var $aspects_limitant;
    var $aspects_facilitant;
    var $objectifs_patient;


    function EvaluationInfirmier(
        $id = "",
        $date = "",
        $id_utilisateur = "",
        $id_cabinet = "",
        $degre_satisfaction = "",
        $duree = "",
        $consult_domicile = "",
        $consult_tel = "",
        $consult_collective = "",
        $points_positifs = "",
        $points_ameliorations = "",
        $type_consultation = array(),
        $ecg = "",
        $ecg_seul = "",
        $monofil = "",
        $exapied = "",
        $hba = "",
        $tension = "",
        $spirometre = "",
        $spirometre_seul = "",
        $t_cognitif = "",
        $autre = "",
        $prec_autre = "",
        $aspects_limitant = "",
        $aspects_facilitant = "",
        $objectifs_patient = ""
    ){
        $db = ConnectionInformedPDO::getInstance();
        $this->con = $db->getDbh();

        $db_ann = ConnectionAnnuairePDO::getInstance();
        $this->con_annuaire = $db_ann->getDbh();

        $this->id = $id;
        $this->date = $date;
        $this->id_utilisateur = $id_utilisateur;
        $this->id_cabinet = $id_cabinet;
        $this->degre_satisfaction = $degre_satisfaction;
        $this->duree = $duree;
        $this->consult_domicile = $consult_domicile;
        $this->consult_tel = $consult_tel;
        $this->consult_collective = $consult_collective;
        $this->points_positifs = $points_positifs;
        $this->points_ameliorations = $points_ameliorations;
        $this->type_consultation = $type_consultation;
        $this->ecg = $ecg;
        $this->ecg_seul = $ecg_seul;
        $this->monofil = $monofil;
        $this->exapied = $exapied;
        $this->hba = $hba;
        $this->tension = $tension;
        $this->spirometre = $spirometre;
        $this->spirometre_seul = $spirometre_seul;
        $this->t_cognitif = $t_cognitif;
        $this->autre = $autre;
        $this->prec_autre = $prec_autre;
        $this->aspects_limitant = $aspects_limitant;
        $this->aspects_facilitant = $aspects_facilitant;
        $this->objectifs_patient = $objectifs_patient;
    }

    function toString(){
        return
            $this->id." ".
            $this->date." ".
            $this->id_utilisateur." ".
            $this->id_cabinet." ".
            $this->degre_satisfaction." ".
            $this->duree." ".
            $this->consult_domicile." ".
            $this->consult_tel." ".
            $this->consult_collective." ".
            $this->points_positifs." ".
            $this->points_ameliorations." ".
            $this->type_consultation." ".
            $this->ecg." ".
            $this->ecg_seul." ".
            $this->monofil." ".
            $this->exapied." ".
            $this->hba." ".
            $this->tension." ".
            $this->spirometre." ".
            $this->spirometre_seul." ".
            $this->t_cognitif." ".
            $this->autre." ".
            $this->prec_autre." ".
            $this->aspects_limitant." ".
            $this->aspects_facilitant." ".
            $this->objectifs_patient;
    }

    function check(){
        $errors = array();
        $i = 0;
        if( empty($this->type_consultation[0]) ) $errors[$i++] = "Veuillez indiquer un type de consultation";
        if( empty($this->duree)||$this->duree =='0' ) $errors[$i++] = "Veuillez indiquer une dur&eacute;e de consultation";
        #if( empty($this->consult_domicile) && empty($this->consult_tel) && empty($this->consult_collective) ) $errors[$i++] = "Veuillez indiquer la consultation &agrave; domicile, t&eacute;l&eacute;phonique ou collective";

        if( !empty($this->consult_domicile) && !empty($this->consult_tel) ) $errors[$i++] = "Vous devez choisir une seule consultation parmi les trois suivantes : &agrave; domicile, t&eacute;l&eacute;phonique ou collective";
        if( !empty($this->consult_domicile) && !empty($this->consult_collective) ) $errors[$i++] = "Vous devez choisir une seule consultation parmi les trois suivantes : &agrave; domicile, t&eacute;l&eacute;phonique ou collective";
        if( !empty($this->consult_tel) && !empty($this->consult_collective) ) $errors[$i++] = "Vous devez choisir une seule consultation parmi les trois suivantes : &agrave; domicile, t&eacute;l&eacute;phonique ou collective";

        return $errors;
    }

    function beforeSerialisation($account){
        $clone = clone $this;

        $date1 = substr($clone->date,2,1);
        $date2 = substr($clone->date,5,1);
        #echo "@".$date1.$date2."@";
        if($date1.$date2=='//'){
            //date FR on converti
            $clone->date = dateToMysqlDate($clone->date);
        }

        #var_dump($clone);
        return $clone;
    }

    function afterDeserialisation($account){
        $clone = clone $this;
        $clone->date = mysqlDateTodate($clone->date);
        return $clone;
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

    public function getCabIdByCab($cabinet)
    {
        $query = "SELECT id
                  FROM account
                  WHERE cabinet = :cabinet";

        $infId = null;
        try
        {
            $res = $this->con->prepare($query);
            $res->bindParam(":cabinet",$cabinet);
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
}
?>
