<?php

    include 'AES.php';
    
    
   
    
    $inputText = "Compte-rendu chatillonv2lazzzzz 10-02-2014";
    //$imputKey = "My Key to encrypt";
    $blockSize = 128;
    $inputKey = pack("H*","E49F211F72FDA17B3420DEADEA99ADF5");
    $iv= pack("H*","00000000000000000000000000000000");
    $aes = new AES($inputText, $inputKey, $blockSize);
    $aes->setMode(AES::M_CBC);
    $aes->setIV($iv);
    $enc = $aes->encrypt();
    $aes->setData($enc);
    $dec=$aes->decrypt();
    echo "After encryption: ".$enc."\n";
    echo "After decryption: ".$dec."\n";
    echo "After hash:       ".hash_hmac ( "md5" , $enc, $inputKey )."\n";
    echo "After hash:       ".hash_hmac ( "md5" , $dec, $inputKey )."\n";




?>