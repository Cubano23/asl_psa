<?php

include 'conn.php';

date_default_timezone_set("Europe/Paris");



$cabinet =    trim($_REQUEST['sms_to']," ");
$text =   addslashes( $_REQUEST['sms_text']);
$status = intval($_REQUEST['sms_status']);
$id = intval($_REQUEST['id']);
//  $from = $_REQUEST['sms_from'];
//  if ($from=="")
$from= $_SESSION['nom'];

if($id=='')
    $id = 0;

$date = date("Y-m-d H:i:s");

{
//send sms  
    require_once ("Config.php");
    $config = new Config();

    require($config->webservice_path . "/GetUserId.php");
    require($config->webservice_path . "/SendSms.php");
    $answer = "OK";
    $auth = GetUserId( $answer);
    $UserID = $auth->Authentifier;



    $rows = GetCabinetInfo($cabinet);

    foreach ($rows as $row)
    {
        $phone = $row["telephone"];
        $smsmes="";
        $smsid = "";

        SendSms("psaet.asalee.fr", $phone,  $UserID,  "Asalee",
//                      "Equipe Asalee:\n".  
            $text,
            &$smsmes , & $smsid);



    }


}

//    $con =  DoConnectInformed3(); 

if ($id==0)
    $sql="INSERT INTO $table  (sms_to,sms_text, sms_status, sms_date, sms_from) VALUES   ('$cabinet','$text', $status, '$date', '$from') ";
else
    $sql="UPDATE $table  SET sms_to='$cabinet', sms_text='$text', sms_status=$status, sms_date='$date', sms_from='$from'  where id=$id ";

//error_log($sql);

$result = @mysql_query($sql, $con);


/*
  $xLog=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
  require_once("$xLog/WebService/AsaleeLog.php");
  LogAccess("psaet.asalee.fr", "cab_save", $UserIDLog, 'na', $cabinet,  1, "Nouveau Cabinet: ".$answerLog."/".$result);  
*/

if ($result){
    echo json_encode(array('success'=>true));
}
else {
    echo json_encode(array('msg'=>'Erreur'));
}
?>
