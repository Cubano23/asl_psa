

<?php #var_dump($_SERVER);
#$path_scripts = "/var/data/home/informed/www/psa/";
require_once("SelfManagedMapper.php");
#require_once($_SERVER['DOCUMENT_ROOT']."/PSA/bean/SuiviReunionMedecin.php");

class SuiviReunionMedecinMapper extends SelfManagedMapper{

    function getForeignKey(){
        return "cabinet";
    }

    function getKeysMap(){
        return array("cabinet"=>"cabinet","date"=>"date");
    }

    function getTableName(){
        return "suivi_reunion_medecin";
    }

    function getLedgerName(){
        return "SuiviReunionMedecinMapper";
    }

    function getObject(){
        return new SuiviReunionMedecin();
    }

    function getObjectsByCabinet($cabinet)
    {
        $query =  "select * from ".$this->getTableName()." where cabinet='$cabinet' ".
            "ORDER BY date DESC";

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

    function getMedecinsOfthisCabinet($cabinet){
        $query =  "select  * from medecin where cabinet='$cabinet' AND recordstatus='0'";
        $result = $this->findAnyRows($query);

        if($result == false) return false;

        while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
            $rowsList[] = $row;
        }

        return $rowsList;
    }

    function getObjectsByCabinetAndDate($cabinet, $date){// on affiche toutes les consultations de chaque semaine en prenant le lundi comme référence
        $query =  "select  * from ".$this->getTableName()." where cabinet='$cabinet' and ".
            "date='$date'";
        #"date_reunion >='$date' AND date_reunion < DATE_ADD('$date', INTERVAL 7 DAY)";
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

    function getObjectsByCabinetAndDateForHebdo($cabinet, $date){// on affiche toutes les consultations de chaque semaine en prenant le lundi comme référence
        $query =  "select  * from ".$this->getTableName()." where cabinet='$cabinet' and ".
            #"date='$date'";
            "date_reunion >='$date' AND date_reunion < DATE_ADD('$date', INTERVAL 7 DAY)";
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
    function getObjectsByCabinetBetweenDate($cabinet, $date_d, $date_f)
    {
        $query =  "select  * from ".$this->getTableName()." where cabinet='$cabinet' and ".
            "date_reunion BETWEEN '".$date_d."' AND '".$date_f."'";
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
    function getObjectsByCabinetsBetweenDate($str_cabinet, $date_d, $date_f)
    {
        $query =  "select  * from ".$this->getTableName()." where cabinet IN ('".$str_cabinet."') and ".
            "date_reunion BETWEEN '".$date_d."' AND '".$date_f."'";

        #var_dump($query);
        $result = $this->findAnyRows($query);
        #var_dump($result);exit;
        if($result == false) return false;
        $rowsList = "";
        $count = 0;
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
            $rowsList[$count] = $row;
            $count = $count + 1;
        }

        return $rowsList;
    }

    function getObjectsByCabinetAndDateDistinct($cabinet){// on affiche toutes les dates evaluation infirmiere(suivi hebdo temps suivi) des semaines commençant le lundi

        $query =  "SELECT SUBDATE(date, INTERVAL WEEKDAY(date) DAY ) as date, WEEKDAY(date) as 'Jour de la Semaine', WEEK(date) as Semaine FROM ".$this->getTableName()." where cabinet='$cabinet' and ".
            "date>'2012-07-01' GROUP BY WEEK(date) ORDER BY date DESC ";

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

    function listeDesLundisDepuis02072012(){

        $currentDate = strtotime('2012-07-02');
        $endDate     = time();

        $mondays     = array();

        while( $currentDate < $endDate )
        {
            $mondays[] = date( 'd/m/Y', $currentDate );
            $currentDate = strtotime( "+1 week", $currentDate );
        }

        return array_reverse($mondays);

    }

    // function insertListReunion($reunion, $cabinet, $date){
    // 	#$data = array_merge($reunion, $cabinet, $date);
    // 	#echo '<pre>';var_dump($reunion);echo '</pre>';exit;
    // 	$query =  "INSERT INTO ".$this->getTableName()."(cabinet, date, date_reunion, duree, medecin, motif)"."
    // 	"VALUES($cabinet, $date, $reunion['date_reunion'], $reunion['duree'], $reunion['medecin'], $reunion['motif'])"";
    // 	echo $query;

    // }



    function getMedecinById($id){
        // print_r($account);die;
        $query =  "select * from medecin where id='$id'";
        $result = $this->findAnyRows($query);
        if($result == false) return false;
        $count = 0;
        #echo $query;exit;
        $row = mysql_fetch_array($result, MYSQL_ASSOC);
        // print_r($row);die;
        return $row;
    }


}
?>
