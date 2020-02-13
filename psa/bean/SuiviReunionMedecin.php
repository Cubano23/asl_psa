<?php

// ajout le 16juin des id_mg et login_inf dans la table suivi_reunion

class SuiviReunionMedecin{

    var $cabinet;
    var $date;
    var $date_reunion;
    var $duree;
    var $medecin;
    var $infirmiere;
    var $motif;
    var $id_mg;



    function SuiviReunionMedecin(
        $cabinet = "",
        $date = "",
        $date_reunion = "",
        $duree = 0,
        $medecin = "",
        $infirmiere = "",
        $motif = "",
        $id_mg = ""){
        $this->cabinet = $cabinet;
        $this->date = $date;
        $this->date_reunion = $date_reunion;
        $this->duree = $duree;
        $this->medecin = $medecin;
        $this->infirmiere = $infirmiere;
        $this->motif = $motif;
        $this->id_mg = $id_mg;
    }

    function toString(){
        return
            $this->cabinet." ".
            $this->date." ".
            $this->date_reunion." ".
            $this->duree." ".
            $this->medecin." ".
            $this->infirmiere." ".
            $this->motif;
        $this->id_mg;
    }



    function beforeSerialisation($account){
        $clone = clone $this;
        $clone->date = dateToMysqlDate($clone->date);
        $clone->date_reunion = dateToMysqlDate($clone->date_reunion);
        return $clone;
    }

    function afterDeserialisation($account){
        $clone = clone $this;
        $clone->date = mysqlDateTodate($clone->date);
        $clone->date_reunion = mysqlDateTodate($clone->date_reunion);
        return $clone;
    }

    function check(){
        $errors = array();
        $i = 0;

        if(!isValidDate($this->date_reunion)) $errors[$i++] = "La date de la reunion suivi est invalide";

        if(empty($this->duree)) $errors[$i++] = "Le nombre de minutes de la dur&eacute;e est manquant";

        if((!empty($this->duree))&&(!is_numeric($this->duree)))$errors[$i++] = "Le nombre de minutes de la dur&eacute;e est invalide";

        if(empty($this->id_mg)) $errors[$i++] = "Veuillez selectionner le nom d'un m&eacute;decin";

        if(empty($this->infirmiere)) $errors[$i++] = "Veuillez selectionner le nom d'un infirmière";

        if(empty($this->motif)) $errors[$i++] = "Le champ motif est manquant";

        return $errors;
    }
    function isValidDate($date){
        if(!isDate($date)) return false;
        $currentDate = date("d/m/Y");
        if(compare($date,$currentDate) > 0) return false;
        return true;
    }
}
?>
