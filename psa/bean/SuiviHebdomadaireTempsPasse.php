<?php
class SuiviHebdomadaireTempsPasse{
    var $cabinet;
    var $date;
    var $info_asalee;
    var $info_dossiermed;

    var $nb_contact_tel_patient;
    var $tps_contact_tel_patient;

    var $autoformation;
    var $formation;
    var $stagiaires;
    var $nb_reunion_medecin;
    var $tps_reunion_medecin;
    var $nb_reunion_infirmiere;
    var $tps_reunion_infirmiere;
    var $tps_passe_cabinet;
    var $non_atribue;

    var $precision_contribution_dev_asalee;



    function SuiviHebdomadaire(
        $cabinet = "",
        $date = "",
        $info_asalee = 0,
        $info_dossiermed = 0,

        $nb_contact_tel_patient = 0,
        $tps_contact_tel_patient = 0,

        $autoformation = 0,
        $formation = 0,
        $stagiaires = 0,
        $nb_reunion_medecin = 0,
        $tps_reunion_medecin = 0,
        $nb_reunion_infirmiere = 0,
        $tps_reunion_infirmiere = 0,
        $tps_passe_cabinet = 0,
        $non_atribue = 0,
        $precision_contribution_dev_asalee = ""){
        $this->cabinet=$cabinet;
        $this->date = $date;
        $this->info_asalee = $info_asalee;
        $this->info_dossiermed = $inf_dossiermed;

        $this->nb_contact_tel_patient = $nb_contact_tel_patient;
        $this->tps_contact_tel_patient = $tps_contact_tel_patient;

        $this->autoformation = $autoformation;
        $this->formation = $formation;
        $this->stagiaires = $stagiaires;
        $this->nb_reunion_medecin = $nb_reunion_medecin;
        $this->tps_reunion_medecin = $tps_reunion_medecin;
        $this->nb_reunion_infirmiere = $nb_reunion_infirmiere;
        $this->tps_reunion_infirmiere = $tps_reunion_infirmiere;
        $this->tps_passe_cabinet = $tps_passe_cabinet;
        $this->non_atribue = $non_atribue;
        $this->precision_contribution_dev_asalee = $precision_contribution_dev_asalee;

    }

    function toString(){
        return
            $this->cabinet." ".
            $this->date." ".
            $this->info_asalee." ".
            $this->info_dossiermed." ".

            $this->nb_contact_tel_patient." ".
            $this->tps_contact_tel_patient." ".

            $this->autoformation." ".
            $this->formation." ".
            $this->stagiaires." ".
            $this->nb_reunion_medecin." ".
            $this->tps_reunion_medecin." ".
            $this->nb_reunion_infirmiere." ".
            $this->tps_reunion_infirmiere." ".
            $this->tps_passe_cabinet." ".
            $this->non_atribue;
    }

    function getTotal(){
        return
            $this->info_asalee +
            $this->info_dossiermed +

            $this->tps_contact_tel_patient +

            $this->autoformation +
            $this->formation +
            $this->stagiaires +
            $this->tps_reunion_medecin +
            $this->tps_reunion_infirmiere;

    }

    function beforeSerialisation($account){
        $clone = clone $this;
        $clone->date = dateToMysqlDate($clone->date);
        return $clone;
    }

    function afterDeserialisation($account){
        $clone = clone $this;
        $clone->date = mysqlDateTodate($clone->date);
        return $clone;
    }

    function check(){
        $errors = array();
        $i = 0;

        if(!isValidDate($this->date)) $errors[$i++] = "La date du suivi est invalide";

        if((!empty($this->info_asalee))&&(!is_numeric($this->info_asalee)))$errors[$i++]="Le nombre de minutes de travail informatique sur Asal&eacute;e est invalide";

        #if((!empty($this->info_dossiermed))&&(!is_numeric($this->info_dossiermed))) $errors[$i++]="Le nombre de minutes de travail informatique sur les dossiers m&eacute;dicaux est invalide";

        #if((!empty($this->tps_contact_tel_patient))&&(!is_numeric($this->tps_contact_tel_patient))) $errors[$i++]="Le nombre de minutes de \"contact t&eacute;l&eacute;phonique avec des patients\" est invalide";

        // if((!empty($this->ecg))&&(!is_numeric($this->ecg))) $errors[$i++]="Le nombre de minutes d'ECG est invalide";

        if((!empty($this->autoformation))&&(!is_numeric($this->autoformation))) $errors[$i++]="Le nombre de minutes d'autoformation est invalide";

        if((!empty($this->formation))&&(!is_numeric($this->formation))) $errors[$i++]="Le nombre de minutes de formation est invalide";

        if((!empty($this->stagiaires))&&(!is_numeric($this->stagiaires))) $errors[$i++]="Le nombre de minutes d'encadrement de stagiaires est invalide";

        if((!empty($this->tps_reunion_medecin))&&(!is_numeric($this->tps_reunion_medecin))) $errors[$i++]="Le nombre de minutes de r&eacute;union avec les m&eacute;decins est invalide";

        if((!empty($this->tps_reunion_infirmiere))&&(!is_numeric($this->tps_reunion_infirmiere))) $errors[$i++]="Le nombre de minutes de r&eacute;union avec les infirmi&egrave;res est invalide";

        // if((!empty($this->telephone))&&(!is_numeric($this->telephone))) $errors[$i++]="Le nombre de minutes de t&eacute;l&eacute;phone est invalide";

        // if((!empty($this->autres))&&(!is_numeric($this->autres))) $errors[$i++]="Le nombre de minutes \"autres\" est invalide";


        // if(!empty($this->tps_contact_tel_patient) && (!is_numeric($this->tps_contact_tel_patient)))
        // 		$errors[$i++]="Le nombre de minutes d'autoformation est invalide";

        // if(empty($this->nb_contact_tel_patient) && (!empty($this->tps_contact_tel_patient)))
        // 		$errors[$i++]="S'il y a des contacts t&eacute;l&eacute;phonique avec des patients, pr&eacute;cisez leur nombre";

        return $errors;
    }
}
?>

