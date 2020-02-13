<?php
require_once("bean/FicheCabinet.php");
require_once("SelfManagedMapper.php");

class FicheCabinetMapper extends SelfManagedMapper{

    function getLedgerName(){
        return "FicheCabinetMapper";
    }

    function getTableName(){
        return "account";
    }

    function getKeysMap(){
        return array("cabinet"=>"cabinet");
    }

    function getForeignKey(){
        return "cabinet";
    }

    function getObject(){
        return new FicheCabinet();
    }

    function getFindQuery($FicheCabinet){
        return "select * from account ".
            "where cabinet='$FicheCabinet->cabinet'";
    }


    function getUpdateQuery($FicheCabinet){

//		    $query_ancienne_donnees="SELECT total_pat, total_sein, total_cogni, ".
//								"total_colon, total_uterus, total_diab2, date_format(dmaj, '%Y-%m-%d') FROM ".
//								$this->getTableName()." WHERE cabinet='$FicheCabinet->cabinet'";
//       		$result = mysql_query($query_ancienne_donnees,$this->connection);

//       		echo $query_ancienne_donnees;

        //      		list($total_pat_anc, $total_sein_anc, $total_cogni_anc, $total_colon_anc, $total_uterus_anc,
//			   			$total_diab2_anc, $dmaj)=mysql_fetch_row($result);

        $query_sauv_donnees="REPLACE INTO histo_account SET cabinet='$FicheCabinet->cabinet', ".
            "d_modif='".date('Y-m-d')."', total_pat='$FicheCabinet->total_pat', ".
            "total_sein='$FicheCabinet->total_sein',".
            " total_cogni='$FicheCabinet->total_cogni', total_colon='$FicheCabinet->total_colon', ".
            "total_uterus='$FicheCabinet->total_uterus', total_diab2='$FicheCabinet->total_diab2', ".
            "total_HTA='$FicheCabinet->total_HTA'";



        $result = mysql_query($query_sauv_donnees,$this->connection);
        //    		echo $query_sauv_donnees;
        //     		die;
        $query = "update ".$this->getTableName()." set ";
        $keysMap = $this->getKeysMap();

        $propertiesArray = get_object_vars($FicheCabinet);
        if(is_null($propertiesArray)) return false;
        foreach($propertiesArray as $propName=>$propVal){
            if(array_key_exists($propName,$keysMap)) continue;
            if(is_array($propVal)) $propVal = arrayToSet($propVal);
            if($propVal == NULL) $tmpPropVal = "NULL";
            else {$tmpPropVal = "'".addslashes(stripslashes($propVal))."'";}
            $query = $query.$propName."= $tmpPropVal ,";

        }

        $query = substr($query,0,strlen($query)-1);

        $query=$query.$this->getWhereClause($propertiesArray);

        //		echo $query;

        return $query;
    }

    function getTousCabinets(){
        $query =  "select * from ".$this->getTableName()." WHERE cabinet!='ztest' and cabinet!='irdes' ".
            "and cabinet!='ergo'  and cabinet!='jgomes' and cabinet!='sbirault' ORDER by nom_cab";

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


//		function getFindListQUery($dossier){
//		}

    /*		function doLoadObject($row){
                print_r($row);
                $FicheCabinet = new FicheCabinet($row["cabinet"]);
                return $FicheCabinet;
            }*/
}
?>
