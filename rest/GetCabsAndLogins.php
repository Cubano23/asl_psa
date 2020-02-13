<?php
  

  function GetCabsByLogin($login, &$status)
  {
        $answer=array();
        $status=-1;
        $jsonObj = file_get_contents('http://localhost/rest/getcabsbyallowed?login='.$login);
        $final_res = json_decode($jsonObj, true) ;
        if($final_res!=null)
        {
              $status = $final_res["status"]; 
//              echo $status."<br/>";
//              echo $final_res["msg"]."<br/>";
              if($status==0)
              {
                $array_expression = $final_res["items"];
                foreach ($array_expression as $value)
                { 
                  $val = $value["cabinet"]; 
//                  echo $val."<br/>";
                  array_push($answer, $val  );
                 }
              
              }
        }
        return $answer;
  
  }

  function GetLoginsByCab($cabinet, &$status)
  {
        $answer=array();
        $status=-1;
        //str_word_count
        $jsonObj = file_get_contents('http://localhost/rest/getallowedbycab?cabinet='.urlencode ($cabinet)); //EA 20-05-2015 urlencode
        $final_res = json_decode($jsonObj, true) ;
        if($final_res!=null)
        {
              $status = $final_res["status"]; 
  //            echo $status."<br/>";
//              echo $final_res["msg"]."<br/>";
              if($status==0)
              {
                $array_expression = $final_res["items"];
                foreach ($array_expression as $value)
                { 
                  array_push($answer, $value  );
                 }
              
              }
        }
        return $answer;
  
  }
  
  function GetInfosByLogin($login, &$status)
  {
        $answer=array();
        $status=-1;
        $jsonObj = file_get_contents('http://localhost/rest/getidentification?login='.$login);
        $final_res = json_decode($jsonObj, true) ;
        if($final_res!=null)
        {
              $status = $final_res["status"]; 
//              echo $status."<br/>";
//              echo $final_res["msg"]."<br/>";
              if($status==0)
              {
                $array_expression = $final_res["items"];
                foreach ($array_expression as $value)
                { 
                   
//                  echo $val."<br/>";
                  array_push($answer, $value  );
                 }
              
              }
        }
        return $answer;
  
  }

//Ceci est un exemple
//  $status=0;
//  $answer = GetCabsByLogin("eaouad",&$status);
//  var_dump( $answer );

//  $status=0;
//  $answer = GetLoginsByCab("ztest",&$status);
//  var_dump( $answer );

//  $status=0;
//  $answer = GetInfosByLogin("ztest",&$status);
//  var_dump( $answer );
//  echo $status;

?>