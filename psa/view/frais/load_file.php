<?php

require_once("persistence/ConnectionFactory.php");
require_once("controler/UtilityControler.php");
require_once("bean/dashboard.php");

#echo $path;
$conn = new ConnectionFactory();
$conn->getConnection();
session_start();
#var_dump($_POST);


$req="SELECT pj from frais where id = '".$_GET['id_file']."'";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
$res = mysql_fetch_array($res);
$fichier = $res['pj'];

$path = explode('/', $fichier);


//error_log("--------------");
//error_log("-------------- fichier ".$fichier);
//error_log("-------------- path ".$path);
//error_log("--------------");


if(is_file($fichier)){
  
  $nom_export = $path[(count($path) - 1)];

  header("Content-type: application/force-download");
    header("Content-Transfer-Encoding: Binary");
    header("Content-length: ".filesize($fichier));
    header("Content-disposition: attachment; filename=".$nom_export);

    // appeler une page en ajax ou un trus à la con pour générer des hits GA

    readfile("$fichier");


}
else{
  echo 'fichier '.$fichier.' existe pas';
}

?>







<?php 
// require_once("persistence/ConnectionFactory.php");
// require_once("controler/UtilityControler.php");
// require_once("view/common/vars.php") 

// $conn = new ConnectionFactory();
// $conn->getConnection();
// session_start();
// #var_dump($_POST);




// $req="SELECT pj from frais where id = '".$_GET['id_file']."'";
// $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
// $res = mysql_fetch_array($res);

// $fichier = $res['pj'];
// $fichier = '/var/data/home/informed/www/_files/dashboard/pdf/2016_01/01a5480f5021b4d8f231de3489e61c42_limon.pdf';
// $path = explode('/', $fichier);

// //echo "<pre>"; var_dump($path[(count($path) - 1)]); exit();
// if(file_exists($fichier)){
	
// 	//$nom_export = $cabinet.'_'.$rep.'.pdf';
// 	header("Content-type: application/force-download");
//     header("Content-Transfer-Encoding: Binary");
//     header("Content-length: ".filesize($fichier));
//     header("Content-disposition: attachment; filename=".$path[(count($path) - 1)]);

//     // appeler une page en ajax ou un trus à la con pour générer des hits GA

//   readfile("$fichier");


// }
// else{
// 	echo 'Fichier '.$fichier.' introuvable !';
// }



// $rep = $_GET['rep'];
// $path = $_SERVER['DOCUMENT_ROOT'];

// if($_SERVER['APPLICATION_ENV']=='dev-herve'){
//   $path = $_SERVER['DOCUMENT_ROOT'].'/_files/dashboard/pdf/';
// }
// else{
//   $path = '/var/data/home/informed/www/_files/dashboard/pdf/';
// }


// $fichier = $path.'2016_01'.'/01a5480f5021b4d8f231de3489e61c42_limon.pdf';

// if(is_file($fichier)){
  
//   $nom_export = '01a5480f5021b4d8f231de3489e61c42_limon.pdf';

//   header("Content-type: application/force-download");
//     header("Content-Transfer-Encoding: Binary");
//     header("Content-length: ".filesize($fichier));
//     header("Content-disposition: attachment; filename=".$nom_export);

//     // appeler une page en ajax ou un trus à la con pour générer des hits GA

//     readfile("$fichier");


// }
// else{
//   echo 'fichier '.$fichier.' existe pas';
// }

?>