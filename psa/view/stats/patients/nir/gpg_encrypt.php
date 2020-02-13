<?php

putenv("GNUPGHOME=/tmp");


$enc = (null);
$res = gnupg_init();

//
// Charger la clé publique du fichier
$pubkey = file_get_contents( 'ASALEE_PUB.asc');
//importer la clé 
$rtv = gnupg_import($res, $pubkey);
$fingerprint =  $rtv['fingerprint'];
//var_dump($rtv);
$rtv = gnupg_addencryptkey($res, $fingerprint);
//echo "gnupg_addencryptkey RTV = <br /><pre>\n";
//var_dump($rtv);
//echo "</pre>\n";
$enc = gnupg_encrypt($res, "just a test to see if anything works");
echo "Encrypted Data: " . $enc . "<br/>";

$plain = null;

$res2 = gnupg_init();
$privkey = file_get_contents( 'ASALEE_SEC.gpg');
$rtv2 = gnupg_import($res2, $privkey);
$fingerprint2 =  $rtv2['fingerprint'];
$rtv2 = gnupg_adddecryptkey($res2, $fingerprint2,"TEST_ASA" );
$plain = gnupg_decrypt($res2,$enc);
echo "Clear Data: " . $plain . "<br/>";

?>


