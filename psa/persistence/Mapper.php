<?php

require_once("tools.php");
require_once("errorcodes.php");
require_once("log/Ledger.php");
require_once("log/LedgerFactory.php");

if(!isset($_SERVER['APPLICATION_ENV']) || $_SERVER['APPLICATION_ENV']!='dev-herve')
{
    require_once ("Config.php");
    $config = new Config();
    require_once($config->webservice_path ."/LogAccess.php");
    require_once($config->webservice_path ."/GetUserId.php");
}

class Mapper{

    var $connection;
    var $lastError;
    var $ledger;

    //
    //EA 18-12-2014
    var $idslogCabinet;
    var $idslogPage;
    var $idslogUser;
    var $idslogControler;
    var $idslogDossier;
    var $idslogOperation;

    function Mapper($connection = NULL){
        $this->connection = $connection;
        $lf = new LedgerFactory();
        $this->ledger = $lf->getLedger("persistence",$this->getLedgerName());
        $this->idslogCabinet = isset($_SESSION['cabinet'])?$_SESSION['cabinet']:"";
        $this->idslogPage = $this->getLedgerName();
        $answerLog="OK";


        if($_SERVER['APPLICATION_ENV']!='dev-herve'){
            $authLog = GetUserId( $answerLog);
            $this->idslogUser = $authLog->Authentifier;

            $this->idslogControler = "";
            $this->idslogDossier = "";
            $this->idslogOperation = -1;


            foreach($_REQUEST as $key => $value) {

                if(strtolower($key)=="controlerparams:param:controler")
                    $this->idslogControler = $value;

                if(strtolower($key)=="dossier:dossier:numero")
                    $this->idslogDossier = $value;

            }
        }


    }

    function IdsLogAccess($op,  $extra )
    {
        if($this->idslogDossier=="") return false;
        if($this->idslogUser=="") return false;


        /*      $str="Dump session:";
              $str = $str.":".$op." ".$extra." ". $this->idslogControler."/".$this->idslogDossier;*/
        if($op!=$this->idslogOperation)
        {
            $this->idslogOperation = $op;
//          error_log($str);
            LogAccess("", $this->idslogControler, $this->idslogUser, 'na', $this->idslogDossier, $op,"Cabinet:" . $this->idslogCabinet." Objet:".$this->idslogPage);
        }

    }



    function buildRowArray($result) {
        $rowsList ="";
        $count = 0;
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
            $rowsList[$count] = $row;
            $count = $count + 1;
        }
        return $rowsList;
    }

    function containsInvalidChars($object){
        $propertiesArray = get_object_vars($object);
        if(is_null($propertiesArray)) return true;
        foreach($propertiesArray as $propName=>$propVal){
            if(containSpecialCodes($propVal)) return true;
        }
        return false;
    }

    function ajoutslashes($object){
        $propertiesArray = get_object_vars($object);
        if(is_null($propertiesArray)) return false;
        foreach($propertiesArray as $propName=>$propVal){
            if(containSpecialCodes($propVal)) {
                $object->$propName=addslashes($propVal);
            }

        }
        return $object;
    }

    function enleveslashes($object){
        $propertiesArray = get_object_vars($object);
        if(is_null($propertiesArray)) return false;
        foreach($propertiesArray as $propName=>$propVal){
            if(containSpecialCodes($propVal)) {
                $object->$propName=Stripslashes($propVal);
            }

        }
        return $object;
    }

    function loadObject($row){
        $object = $this->doLoadObject($row);
        $this->ledger->write(I,"Populate Object",I_OBJECT_CONTENT.":".$object->toString());
        return $object;
    }

    function createObject($object){

        $this->ledger->write(I,"Create Object",I_OBJECT_CLASS."".get_class($object));
        $this->ledger->write(I,"Create Object",I_OBJECT_CONTENT."".$object->toString());

        if($this->containsInvalidChars($object)){
            $this->lastError = PARAM;
            $this->ledger->write(E,"Create Object",PARAM);
            $object=$this->enleveslashes($object);
            $object=$this->ajoutslashes($object);
//				return false;
        }


        $insertQuery = $this->getInsertQuery($object);

        $this->ledger->write(I,"Create Object",QUERY.":".$insertQuery);
        $result = mysql_query($insertQuery,$this->connection);





        if($result == false){print_r($object);echo "erreur mysql : $insertQuery : ".mysql_error()."<br>";
            $this->lastError = CREATE_ERROR;
            $this->ledger->write(E,"Create Object",CREATE_ERROR.":".mysql_error());
            return false;
        }
        $this->IdsLogAccess(1, "" );
        return true;
    }

    function updateObject($object){

        $this->ledger->write(I,"Update Object",I_OBJECT_CLASS."".get_class($object));
        $this->ledger->write(I,"Update Object",I_OBJECT_CONTENT."".$object->toString());

        if($this->containsInvalidChars($object)){
            $this->lastError = PARAM;
            $this->ledger->write(E,"Update Object",PARAM);
            $object=$this->enleveslashes($object);
            $object=$this->ajoutslashes($object);
//			return false;
        }
        $updateQuery = $this->getUpdateQuery($object);

        $this->ledger->write(I,"Update Object",QUERY.":".$updateQuery);

        $result = mysql_query($updateQuery,$this->connection);

        if($result == false){
            $this->lastError = UPDATE_ERROR;
            $this->ledger->write(E,"Update Object",UPDATE_ERROR.":".mysql_error());
            return false;
        }

        if(mysql_affected_rows() == 0){
            $this->lastError = NOTHING_UPDATED;
            $this->ledger->write(W,"Update Object",NOTHING_UPDATED);
            return false;
        }
        $this->IdsLogAccess(2, "" );
        return true;
    }

    function findRow($object){

        $this->ledger->write(I,"Find Row",I_OBJECT_CLASS."".get_class($object));
        $this->ledger->write(I,"Find Row",I_OBJECT_CONTENT."".$object->toString());

        if($this->containsInvalidChars($object)){
            $this->lastError = PARAM;
            $this->ledger->write(E,"Find Row",PARAM);
            $object=$this->enleveslashes($object);
//			return false;
        }

        $findQuery = $this->getFindQuery($object);
        #echo $findQuery;
        $this->ledger->write(I,"Find Row",QUERY.":".$findQuery);
        $result = mysql_query($findQuery,$this->connection);
        #var_dump($result);
        if($result == false){
            $this->lastError = FIND_ERROR;
            $this->ledger->write(E,"Find Row",FIND_ERROR.":".mysql_error());
            return false;
        }


        if(mysql_num_rows($result) == 0 or mysql_num_rows($result) > 1){
            $this->lastError = BAD_MATCH;
            $this->ledger->write(W,"Find Row",BAD_MATCH);
            return false;
        }

        $row = mysql_fetch_array($result, MYSQL_ASSOC);

        return $row;
    }

    function findObject($object){
        $row = $this->findRow($object);
        if($row == false) return FALSE;

        $object = $this->loadObject($row);
        $this->IdsLogAccess(0, "" );
        return $object;
    }

    function findRows($object){
        if($this->containsInvalidChars($object)){
            $this->lastError = PARAM;
            $this->ledger->write(E,"Find Rows",PARAM);
            $object=$this->enleveslashes($object);
//			return false;
        }

        $findListQuery = $this->getFindListQuery($object);

        $this->ledger->write(I,"Find Rows",QUERY.":".$findListQuery);

        $result = mysql_query($findListQuery,$this->connection);
        if($result == false){
            $this->lastError = FIND_ERROR;
            $this->ledger->write(E,"Find Rows",FIND_ERROR.":".mysql_error());
            return false;
        }

        if(mysql_num_rows($result) == 0){
            $this->lastError = BAD_MATCH;
            $this->ledger->write(W,"Find Rows",BAD_MATCH);
            return false;
        }


        $count = 0;
        return $result;
    }

    function findObjects($object){
        $result = $this->findRows($object);

        if($result == false) return false;
        $objectsList ="";
        $count = 0;
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
            $objectsList[$count] = $this->loadObject($row);
            $count = $count + 1;
        }

        return $objectsList;
    }


    function getDernierExam($object){

        $findDernierExamQuery = $this->getFindDernierExamQuery($object);

        $this->ledger->write(I,"Find Rows",QUERY.":".$findDernierExamQuery);

        $result = mysql_query($findDernierExamQuery,$this->connection);
        if($result == false){
            $this->lastError = FIND_ERROR;
            $this->ledger->write(E,"Find Rows",FIND_ERROR.":".mysql_error());
            return false;
        }

        if(mysql_num_rows($result) == 0){
            $this->lastError = BAD_MATCH;
            $this->ledger->write(W,"Find Rows",BAD_MATCH);
            return false;
        }


        $count = 0;


        return $result;
    }


    function findDernierExam($object){
        $result = $this->getDernierExam($object);

        if($result == false) return false;
        $objectsList ="";

        while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
            $objectList = $this->loadObject($row);
        }

        return $objectList;
    }


    function deleteObject($object){
        $this->ledger->write(I,"Delete Object",I_OBJECT_CLASS."".get_class($object));
        $this->ledger->write(I,"Delete Object",I_OBJECT_CONTENT."".$object->toString());

        if($this->containsInvalidChars($object)){
            $this->lastError = PARAM;
            $this->ledger->write(E,"Delete Object",PARAM);
            $object=$this->ajoutslashes($object);
//			return false;
        }

        $deleteQuery = $this->getDeleteQuery($object);

        $this->ledger->write(I,"Delete Object",QUERY.":".$deleteQuery);

        $result = mysql_query($deleteQuery,$this->connection);

        if($result == false){
            $this->lastError = DELETE_ERROR;
            $this->ledger->write(E,"Delete Object",DELETE_ERROR.":".mysql_error());
            return false;
        }

        if(mysql_affected_rows() == 0){
            $this->lastError = NOTHING_DELETED;
            $this->ledger->write(W,"Update Object",NOTHING_DELETED);
            return false;
        }
        $this->IdsLogAccess(3, "" );
        return true;
    }

    function findAnyRows($query){
        $this->ledger->write(I,"Custom Find Rows",QUERY."".$query);

        $result = mysql_query($query,$this->connection);

        if($result == false){
            echo mysql_error()."<br><br>";
            $this->lastError = QUERY_ERROR;
            $this->ledger->write(E,"Custom Find Rows",QUERY_ERROR.":".mysql_error());
            return false;
        }

        if(mysql_num_rows($result) == 0){
            $this->lastError = BAD_MATCH;
            $this->ledger->write(W,"Custom Find Rows",BAD_MATCH);
            return false;
        }

        return $result;
    }

    function findAnyObject($query){
        $result =  $this->findAnyRows($query);
        if($result == false) return false;
        $objectsList ="";
        $count = 0;
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
            $objectsList[$count] = $this->loadObject($row);
            $count = $count + 1;
        }
        return $objectsList;
    }

    function queryAny($query){
        $this->ledger->write(I,"Custom Query",QUERY."".$query);

        $result = mysql_query($query,$this->connection);

        if($result == false){
            $this->lastError = QUERY_ERROR;
            $this->ledger->write(E,"Custom Query",QUERY_ERROR.":".mysql_error());
            return false;
        }
        return true;
    }
}
?>
