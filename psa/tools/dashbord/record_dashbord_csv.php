<?php
 set_time_limit(1000);
 ini_set("memory_limit","512M");
  #require_once("../../bean/FicheCabinet.php");
  require_once("../../bean/dashboard.php");
  #require_once("../../bean/SuiviHebdomadaireTempsPasse.php");
  #require_once("../../bean/EvaluationInfirmier.php");
  #require_once("../../bean/SuiviReunionMedecin.php");
  
  /* persistence object */
  require_once("../../persistence/FicheCabinetMapper.php");
  #require_once("../../persistence/SuiviHebdomadaireTempsPasseMapper.php");
  #require_once("../../persistence/EvaluationInfirmierMapper.php");
  #require_once("../../persistence/SuiviReunionMedecinMapper.php");

  require_once("../../persistence/ConnectionFactory.php");
  //require_once("../../tools/date.php");
  #require_once("../../bean/beanparser/htmltags.php");
  //require_once("../../view/jsgenerator/jsgenerator.php");
  require_once("../../view/common/vars.php");



// connexion base
$is_local = false;
$serveur = 'localhost';

if($is_local){
  $idDB = 'root';
  $mdpDB = 'root';
  $DB = 'isas';
}
else{
  $idDB = 'informed';
  $mdpDB = 'no11iugX';
  $DB = 'informed3';
}

mysql_connect($serveur,$idDB,$mdpDB) or die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or die("Impossible de se connecter &agrave; la base");

########





/*
 *
 */
$type_tdb = "cabinet"; // region, cabinet

$region_consolide = "";



$filename = "2016_03";
  
  

  $ct = file('./csv/'.$filename.'.csv');

  $i=0;
  foreach($ct as $ligne){

  #while ( list( $numero_ligne, $ligne) = each( $ct ) ) {
      if($i > 0){
        $ligne = str_replace('"','',$ligne);
        $tab_csv = explode(";",$ligne);
        #var_dump($tab_csv);exit;

        Dashboard::record($tab_csv);
      }
      $i++;
    }
  
  


//sleep(60);
echo "<br>ok fin : ".$filename;

//} // fin while boucle cabinet
?>

