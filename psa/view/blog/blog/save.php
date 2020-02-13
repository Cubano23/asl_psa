<?php


include 'conn.php';


$redacteur = $_SESSION["id.login"];
$sujet = clean($_REQUEST['sujet'], 1);
$file = clean($_FILES['file']['name'], 1);
$target_dir = "../../docs/blog/";
$base_file =basename($file);
$target_file = $target_dir . $base_file;

if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file))
{

    date_default_timezone_set('Europe/Paris');
    $dcreat=   $_REQUEST['dcreat'];
    error_log($dcreat);
    $type=   intval(isset($_REQUEST['type'])?$_REQUEST['type']: "0");

    $dcreat = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $dcreat);

    $sql = "INSERT INTO $table(`type`, `redacteur`, `dcreat`, `sujet`, `lien`) VALUES ($type, '$redacteur','$dcreat', '$sujet', '$base_file')";


    $result = @mysql_query($sql);

    // require_once("/home/informed/www/informed79/WebService/AsaleeLog.php");
    // LogAccess("", "dossier_save", $UserIDLog, 'na', $numero,  1, "Cabinet:".$cabinet." /".$result);

    error_log($sql);

    if ($result){
        echo json_encode(array('success'=>true));
    }
    else {
        echo json_encode(array('msg'=>'Erreur:'.mysql_error()));
    }
}
else
	{
		echo json_encode(array('msg'=>'Erreur Fichier'.$target_file));
	}
?>

