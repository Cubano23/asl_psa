<?php
# fichier inclus pour scripts informed
#
error_reporting(E_ALL);
$serveur="localhost";
$idDB="informed";
$mdpDB="no11iugX";
$serveur_web="informed79.fr";
$DB_connexion="informed";

define("PREF","inf79_");
$slash='/';

# bases de donn�es selon le r�pertoire de d�part
$database=array('parthenay'=>'informed', 
                'informed79'=>'informed', 
                'infornew'=>'informed3', 
                'mellois'=>'informed2',
                'test' => 'informed3',
                'psa' => 'informed3',
                'psatest' => 'asaleetest',
                'psam' => 'informed3',
                'psem' => 'informed2',
                'psemellois' => 'informed2',
                'psae' => 'informed3',
                'psaetest' => 'asaleetest',
                'psaettest' => 'asaleetest',
                'psaet' => 'informed3',
                'pseruffec' => 'informed4',
                'psevaldesevres' => 'informed7',
                'thouars' => 'informed4',
                'bressuirais'=>'informed5',
                'hautesaintonge'=>'informed6',
                'psm'=>'informed6',
                'erp' => 'informed3',                      // EAOAUD 30-03-2018
		 'parthenay2'=>'test');

$repertoire=dirname($_SERVER['PHP_SELF']);

//essai
if (strpos($repertoire, '/', 1)===false)
	$repertoire=substr($repertoire,1);

else
	$repertoire=substr($repertoire,1, strpos($repertoire, '/', 1)-1);

//$repertoire=substr($repertoire,1, strpos($repertoire, '/', 1)-1);

if(isset($database[$repertoire]))
     $DB=$database[$repertoire];
else $DB="informed";

require dirname(__FILE__)."/fonctions.inc.php";
