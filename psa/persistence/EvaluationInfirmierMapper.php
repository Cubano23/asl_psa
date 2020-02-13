<?php
require_once("SelfManagedMapper.php");
require_once("bean/EvaluationInfirmier.php");

class EvaluationInfirmierMapper extends SelfManagedMapper{

    function getForeignKey(){
        return "id";
    }

    function getKeysMap(){
        return array("id"=>"id","date"=>"date");
    }

    function getTableName(){
        return "evaluation_infirmier";
    }

    function getLedgerName(){
        return "EvaluationInfirmierMapper";
    }

    function getObject(){
        return new EvaluationInfirmier();
    }

    function getFindListQuery($object){
        $propertiesArray = get_object_vars($object);
        if(is_null($propertiesArray)) return false;
        return "select * from ".$this->getTableName()." where id=".$propertiesArray["id"];
    }

    function getObjectsByCabinet($cabinet){
        $query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
            $this->getTableName().".".$this->getForeignKey()." = dossier.id";
        $result = $this->findAnyRows($query);

        if($result == false) return false;
        $rowsList = "";
        $count = 0;
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
            $rowsList[$count] = $row;
            $count = $count + 1;
        }

        return $rowsList;
    }

    function getEvaluationsByCabinetAndDistinctDossier($cabinet, $orderby){
        //$query = "SELECT * FROM ".$this->getTableName()." AS e INNER JOIN dossier as d ON e.".$this->getForeignKey()."=d.id WHERE cabinet='".$cabinet."' ORDER BY e.date DESC";

        if($orderby == "date") {
            $orderstr = $this->getTableName().".date DESC";
        }
		elseif($orderby == "dossier") {
            $orderstr = $this->getTableName().".".$this->getForeignKey()." ASC";
        }
        else {
            die('erreur');
        }
        $query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
            $this->getTableName().".".$this->getForeignKey()." = dossier.id ORDER BY ".$orderstr;

        $result = $this->findAnyRows($query);

        if($result == false) return false;
        $rowsList = "";
        $count = 0;
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
            $rowsList[$count] = $row;
            $count = $count + 1;
        }
        $aDistinctDossiers = array();
        $aDistinctConsultations = array();
        for($i = 0; $i < count($rowsList); $i++) {
            if(!in_array($rowsList[$i]['id'], $aDistinctDossiers)) {
                array_push($aDistinctDossiers, $rowsList[$i]['id']);
                array_push($aDistinctConsultations, $rowsList[$i]);
            }
        }
        return $aDistinctConsultations;
    }


    function getObjectsByCabinetAndDateDistinct($cabinet){// on affiche toutes les dates evaluation infirmiere(suivi hebdo temps suivi) des semaines commençant le lundi

        $query =  "SELECT SUBDATE(date, INTERVAL WEEKDAY(date) DAY ) as date, WEEKDAY(date) as 'Jour de la Semaine', WEEK(date) as Semaine FROM ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
            $this->getTableName().".".$this->getForeignKey()." = dossier.id AND date>'2012-07-01' GROUP BY WEEK(date) ORDER BY date DESC ";

        $result = $this->findAnyRows($query);

        if($result == false) return false;
        $rowsList = "";
        $count = 0;
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
            $rowsList[$count] = $row;
            $count = $count + 1;
        }

        // if($rowsList[0]['Semaine']!= date('W')){
        // 	$query = "INSERT INTO ".$this->getTableName()." (id, date) VALUES(".$this->getForeignKey().", ".date('Y-m-d').")";
        // }
        return $rowsList;
    }

    function getObjectsByCabinetAndDate($cabinet, $date){// on affiche toutes les consultations de chaque semaine en prenant le lundi comme référence
        $query =  "select  * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
            $this->getTableName().".".$this->getForeignKey()." = dossier.id and ".
            #"date='$date'";
            "date >='$date' AND date < DATE_ADD('$date', INTERVAL 7 DAY)";
        $result = $this->findAnyRows($query);

        if($result == false) return false;
        $rowsList = "";
        $count = 0;
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
            $rowsList[$count] = $row;
            $count = $count + 1;
        }

        return $rowsList;
    }

    /*
     * Pierre Dashboard
     */
    function getObjectsByCabinetBetweenDate($cabinet, $date_d, $date_r)
    {
        $query =  "select  * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
            $this->getTableName().".".$this->getForeignKey()." = dossier.id and ".
            "date >= '".$date_d."' AND date < '".$date_r."'"; // version pierre

        #echo 'QQ: '.$query;exit;

        $result = $this->findAnyRows($query);

        if($result == false) return false;
        $rowsList = "";
        $count = 0;
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
            $rowsList[$count] = $row;
            $count = $count + 1;
        }

        return $rowsList;
    }

    // requete des TDB
    function getObjectsByCabinetsBetweenDate($str_cabinet, $date_d, $date_r)
    {
        $query =  "select  * from ".$this->getTableName().",dossier where cabinet IN ('".$str_cabinet."') and ".
            $this->getTableName().".".$this->getForeignKey()." = dossier.id and ".
            #"date >= '".$date_d."' AND  date < '".$date_r."'"; //version pierre
            "date >='$date_d' AND date < DATE_ADD('$date_d', INTERVAL 7 DAY)"; // test rv
        #echo "<br>req EvaluationInfirmierMapper : ".$query;
        $result = $this->findAnyRows($query);

        if($result == false) return false;
        $rowsList = "";
        $count = 0;
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
            $rowsList[$count] = $row;
            $count = $count + 1;
        }

        return $rowsList;
    }


    function getObjectsByDate($cabinet, $date){
        $query =  "select * from ".$this->getTableName()." where cabinet='$cabinet' and ".
            "date='$date'";
        $result = $this->findAnyRows($query);

        if($result == false) return false;
        $rowsList = "";
        $count = 0;
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
            $rowsList[$count] = $row;
            $count = $count + 1;
        }

        return $rowsList;
    }

    function getFindDernierExamQuery($object){
        return "select * from ".$this->getTableName()." where id ='$object->id' ORDER BY `date` DESC limit 0,1";

    }

    /**
     * récupere les informations evaluation infirmiere d'apres le dossier et la date de consultation
     * @param  [type] $date   [description]
     * @param  [type] $numero [description]
     * @return [type]         [description]
     */
    function getObjectByDateAndNumero($date,$numero){
        $query = "select * from evaluation_infirmier where id='$numero' and date='$date' LIMIT 1";
        #echo 'getObjectByDateAndNumero => '.$query;
        $res = mysql_query($query);
        $row = mysql_fetch_object($res);
        return $row;
    }


    static function getObjectToObject($obj){

        $evaluationInfirmier = new EvaluationInfirmier(
            $obj->id,
            $obj->date,
            $obj->id_utilisateur,
            $obj->id_cabinet,
            $obj->degre_satisfaction,
            $obj->duree,
            $obj->consult_domicile,
            $obj->consult_tel,
            $obj->consult_collective,
            $obj->points_positifs,
            $obj->points_ameliorations,
            $obj->type_consultation,
            $obj->ecg,
            $obj->ecg_seul,
            $obj->monofil,
            $obj->exapied,
            $obj->hba,
            $obj->tension,
            $obj->spirometre,
            $obj->spirometre_seul,
            $obj->t_cognitif,
            $obj->autre,
            $obj->prec_autre,
            $obj->aspect_limitant,
            $obj->aspects_facilitant,
            $obj->objectifs_patient
        );
        return $evaluationInfirmier;

    }

    /**
     * insertion en base des enregistrements "evaluation infirmiere, version 2016 en bypassant la cocquille asalée trop contraignante
     * l'insertion est unique (1 dossier) mais peux être appelée depuis l'insertion de consultations collectives.
     * @param object $evaluation object contenant toutes les informations sur la consultation
     */
    function add($evaluation){

        $req = "INSERT INTO evaluation_infirmier set
					id = '$evaluation->dossier_id',
					date = '$evaluation->date',
					id_utilisateur = '$evaluation->id_utilisateur',
					id_cabinet = '$evaluation->id_cabinet',
					degre_satisfaction = '$evaluation->degre_satisfaction',
					duree = '$evaluation->duree',
					consult_collective = '$evaluation->consult_collective',
					points_positifs = '$evaluation->points_positifs',
					points_ameliorations = '$evaluation->points_ameliorations',
					type_consultation = '$evaluation->type_consultation',
					ecg_seul = '$evaluation->ecg_seul',
					monofil = '$evaluation->monofil',
					exapied = '$evaluation->exapied',
					hba = '$evaluation->hba',
					tension = '$evaluation->tension',
					spirometre_seul = '$evaluation->spirometre_seul',
					spirometre = '$evaluation->spirometre',
					t_cognitif = '$evaluation->t_cognitif',
					autre = '$evaluation->autre',
					prec_autre = '$evaluation->prec_autre',
					aspects_limitant = '$evaluation->aspects_limitant',
					aspects_facilitant = '$evaluation->aspects_facilitant',
					objectifs_patient = '$evaluation->objectifs_patient',
					dmaj = now(),
					uuid_collectif = '$evaluation->uuid_collectif'
			";
        #echo $req;
        $res = mysql_query($req);
        return $res;
    }


    static function listConsultCollectiveByGroup($idgroupe){

        $cle = '@'.$idgroupe;
        $req = "SELECT * from evaluation_infirmier where uuid_collectif like '%$cle' group by uuid_collectif order by date DESC";
        $res = mysql_query($req);
        #echo $req.'<p>';
        while($row = mysql_fetch_object($res)){
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * listing de cheque consultation à la base collective associée à un uuid_collectif
     * @param  [type] $uuid_collectif [description]
     * @return [type]                 [description]
     */
    static function listeDossiersByConsultCollectives($uuid_collectif){

        $req = "SELECT * from evaluation_infirmier as E, dossier as D where E.id=D.id and uuid_collectif='$uuid_collectif'";
        $res = mysql_query($req);
        #echo $req.'<p>';exit;
        while($row = mysql_fetch_object($res)){
            $rows[] = $row;
        }
        return $rows;
    }

}
?>
