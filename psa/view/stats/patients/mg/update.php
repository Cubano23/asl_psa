<?php

require_once ("Config.php");
$config = new Config();
require_once "persistence/ConnectionInformedPDO.php";

include 'conn.php';
include 'departements.php';

$db = ConnectionInformedPDO::getInstance();
$con = $db->getDbh();

$id = 	$_GET['id'];
//	$id = $_REQUEST['id'];
$cabinet = $_REQUEST['cabinet'];
$prenom = addslashes ($_REQUEST['prenom']);
$nom =    addslashes ($_REQUEST['nom']);
$adeli =  $_REQUEST['adeli'];
$rpps =   $_REQUEST['rpps'];
$courriel=   $_REQUEST['courriel'];
$adresse=   addslashes ($_REQUEST['adresse']);
$codepostal=   $_REQUEST['codepostal'];
$departement="";
$region="";
if(!is_null($codepostal))
{
    if(strlen($codepostal)==5)
    {
        $code2=substr($codepostal, 0, 2);
        $departement=addslashes ($depts[$code2]);
        $region=addslashes ($regions[$code2]);
    }
}

$ville=   addslashes ($_REQUEST['ville']);
//	$departement=   $_REQUEST['departement'];
//	$region=   $_REQUEST['region'];
$telephone=   $_REQUEST['telephone'];
$portable=   $_REQUEST['portable'];
//$sql = "update $table set  cabinet='$cabinet', nom='$nom', prenom='$prenom', courriel='$courriel',adeli='$adeli',rpps='$rpps', adresse='$adresse', codepostal='$codepostal',ville='$ville',departement='$departement',region='$region', telephone='$telephone', portable='$portable'  where id= $id";
//$result = @mysql_query($sql);


$record = false;
$sql = "UPDATE $table SET cabinet='$cabinet', nom='$nom', prenom='$prenom', courriel='$courriel',adeli='$adeli',rpps='$rpps', adresse='$adresse', codepostal='$codepostal',ville='$ville',departement='$departement',region='$region', telephone='$telephone', portable='$portable' where id= :id";

        try
        {

            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $res = $con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

            $res->bindParam(':id',$id, PDO::PARAM_INT);            
            $res->execute();
            $record = true;
        }
        catch (PDOException $exception)
        {
            echo $exception->getMessage();
        }

//require($config->erp_path . "/WebService/erp.php");

/* if($result)
{
    $rows = array();
    $rows["id_asalee"] = $id;
    $rows["cabinet"]=$cabinet;
    $rows["login_asalee"]=NULL;
    $rows["prenom"]=$prenom;
    $rows["nom"]=$nom;
    $rows["telephone"]=$telephone ;
    $rows["portable"]=$portable  ;
    $rows["email"]=$courriel ;
    $rows["codePostal"]=$codepostal;
    $rows["ville"]=$ville ;
    $rows["rpps"]=$rpps ;
    $rows["adeli"]=$adeli ;
    $result = Erp_Medecin_Update($rows);
}
 */
/*$xLog=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
require_once("$xLog/WebService/AsaleeLog.php");*/
/* if($_SERVER['APPLICATION_ENV'] != 'dev-herve')
{
    require_once($config->webservice_path . "/AsaleeLog.php");
    LogAccess("psaet.asalee.fr", "mg_update", $UserIDLog, 'na', $id.' '.'/'.$prenom.' '.$nom,  2, "Modification Medecin Traitant: ".$answerLog."/".$result);
}
 */
if ($res)
{
    echo json_encode(array('success'=>true));
}
else
{
    echo json_encode(array('msg'=>mysql_error()));
}
if($record == true){
    $actualStatus = 1;
    $sql = "UPDATE $table_historique_medecin 
            SET actualstatus= :actualstatus 
            where medid= :id";

            try
            {

                $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $res = $con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

                $res->bindParam(':id',$id, PDO::PARAM_INT);
                $res->bindParam(':actualstatus', $actualStatus, PDO::PARAM_INT);            
                $res->execute();
            }
            catch (PDOException $exception)
            {
                echo $exception->getMessage();
            }
    }
if($record == true){
    $actualStatus = 0;
    $sql = "INSERT INTO $table_historique_medecin(medid, cabinet, nom, prenom, actualstatus, dstatus)
            values(:medid, :cabinet, :nom, :prenom, :actualstatus, :dstatus) ";

            try
            {
                $res = $con->prepare($sql);

                $res->bindParam(':medid', $id, PDO::PARAM_INT );             
                $res->bindParam(':cabinet', $cabinet, PDO::PARAM_STR );
                $res->bindParam(':nom', $nom, PDO::PARAM_STR );
                $res->bindParam(':prenom', $prenom, PDO::PARAM_STR );
                $res->bindParam(':actualstatus', $actualStatus, PDO::PARAM_INT);
                $date = date('Y-m-d H:i:s');
                $res->bindParam(':dstatus', $dstatus);

                $res->execute();              
            }
            catch (Exception $exception)
            {
                error_log($exception->getMessage());
            }



}

?>
