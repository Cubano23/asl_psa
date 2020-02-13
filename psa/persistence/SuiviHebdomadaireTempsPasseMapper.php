

<?php
include_once("SelfManagedMapper.php");
include_once("bean/SuiviHebdomadaireTempsPasse.php");

class SuiviHebdomadaireTempsPasseMapper extends SelfManagedMapper{

    function getForeignKey(){
        return "cabinet";
    }

    function getKeysMap(){
        return array("cabinet"=>"cabinet","date"=>"date");
    }

    function getTableName(){
        return "suivi_hebdo_temps_passe";
    }

    function getLedgerName(){
        return "SuiviHebdomadaireTempsPasseMapper";
    }

    function getObject(){
        return new SuiviHebdomadaireTempsPasse();
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

    /*
     * Pierre dashboard
     */
    function getObjectsByCabinetBetweenDates($cabinet, $date_d, $date_f)
    {
        $query =  "select * from ".$this->getTableName()." where cabinet='$cabinet' AND date >= '".$date_d."' AND date < '".$date_f."' ORDER BY date ASC";

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
    function getObjectsByCabinetsBetweenDates($str_cabinet, $date_d, $date_f)
    {
        $query =  "select * from ".$this->getTableName()." where cabinet IN ('".$str_cabinet."') AND date >= '".$date_d."' AND date < '".$date_f."' ORDER BY date ASC";
        echo $query;
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

}
?>
