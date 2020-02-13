<?php
    $to_email       = "dup.asalee@gmail.com"; // "eaouad@free.fr" ; //"aderville@isas.fr"; //Recipient email, Replace with own email here
   
  
    //Sanitize input data using PHP filter_var().
    $user_name      = $_REQUEST["nom"] ; //filter_var($_REQUEST["nom"], FILTER_SANITIZE_STRING);
    $user_email     = filter_var($_REQUEST["email"], FILTER_SANITIZE_EMAIL);
    $subject        = $_REQUEST["sujet"] ; //filter_var($_REQUEST["sujet"], FILTER_SANITIZE_STRING);
    $message        = $_REQUEST["message"] ; //filter_var($_REQUEST["message"], FILTER_SANITIZE_STRING);
/*   
    //additional php validation
    if(strlen($user_name)<4){ // If length is less than 4 it will output JSON error.
        $output = json_encode(array('type'=>'error', 'text' => 'Name is too short or empty!'));
        die($output);
    }
    if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)){ //email validation
        $output = json_encode(array('type'=>'error', 'text' => 'Please enter a valid email!'));
        die($output);
    }
    if(!filter_var($country_code, FILTER_VALIDATE_INT)){ //check for valid numbers in country code field
        $output = json_encode(array('type'=>'error', 'text' => 'Enter only digits in country code'));
        die($output);
    }
    if(!filter_var($phone_number, FILTER_SANITIZE_NUMBER_FLOAT)){ //check for valid numbers in phone number field
        $output = json_encode(array('type'=>'error', 'text' => 'Enter only digits in phone number'));
        die($output);
    }
    if(strlen($subject)<3){ //check emtpy subject
        $output = json_encode(array('type'=>'error', 'text' => 'Subject is required'));
        die($output);
    }
    if(strlen($message)<3){ //check emtpy message
        $output = json_encode(array('type'=>'error', 'text' => 'Too short message! Please enter something.'));
        die($output);
    }
*/   

    
    //email body
    $message_body = $message."\r\n\r\n";
   
    //proceed with PHP email.
    $headers = 'From: '.$user_name.'<'.$user_email.'>' . "\r\n" .
    'Reply-To: '.$user_email.'' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
//    error_log($headers);
    $send_mail = mail($to_email, $subject, $message_body, $headers);
   
    if(!$send_mail)
    {
            echo json_encode(array('msg'=>'Erreur Envoi Email'));
    }else{
        echo json_encode(array('success'=>true));
    }

?>