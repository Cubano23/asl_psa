<?php
session_start();
if(!isset($_SESSION['nom'])) {
    # pas passé par l'identification
    $debut=dirname($_SERVER['PHP_SELF']);
    $self=basename($_SERVER['PHP_SELF']);
    header("Location: $debut/ident_util.php?url=$self");
    echo "<a href='$debut/ident_util.php?url=$self'>cliquez ici</a>";
    exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="content-type"
          content="text/html; charset=ISO-8859-15">
    <title>Créer un cabinet</title>
</head>
<body bgcolor=#FFE887>
<?php
require_once "Config.php";
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");
//require('../../../connexion_local.php');
# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
die("Impossible de se connecter à la base");


$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
//echo $loc;
require("../global/entete.php");
//echo $loc;

entete_asalee("Créer un cabinet");


# boucle principale
do {
    $repete=false;

    # fenêtre glissante:

    # étape 1 : saisie des infos du cabinet
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

            case 1://saisie des infos du cabinet
                etape_1($repete);
                break;

            # étape 2  : enregistrement
            case 2:
                etape_2($repete);
                break;

        }
    }
} while($repete);

# fin de traitement principal

//Saisie des infos du cabinet
function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self;


    if(sizeof($message)==0){
        $cabinet=$password=$password2=$nom_complet=$ville=$contact=$telephone="";
        $email=$total_pat=$total_sein=$total_cogni=$total_colon=$total_uterus="";
        $total_diab2=$total_HTA=$infirmiere=$infirmiere2=$nom_cab=$portable="";
        $region=$region2=$logiciel=$log_ope="";
    }
    else{
        echo "<b><font style='color:red'>";

        foreach($message as $m){
            echo $m."<br>";
        }
        echo "</b></font>";
        extract($_POST);
    }

    $logiciels=array(""=>"",
        "axisante"=>"Axisanté",
        "axisante4"=>"Axisanté 4",
        "axisante5"=>"Axisanté 5",
        "crossway"=>"CrossWay",
        "dbmed"=>"DBmed",
        "easyprat"=>"EasyPrat",
        "hellodoc"=>"Hellodoc",
        "hellodoc_v5.6"=>"Hellodoc v5.6",
        "hellodoc_v5.55"=>"Hellodoc v5.55",
        "ict"=>"ICT",
        "medicawin"=>"Médicawin",
        "mediclic"=>"Médiclick",
        "mediclic3"=>"Médiclick 3",
        "mediclic4"=>"Médiclick 4",
        "mediclic5"=>"Médiclick 5",
        "medimust"=>"Medimust",
        "medistory"=>"Medistory",
        "mediwin"=>"MediWin",
        "shaman"=>"Shaman",
        "xmed"=>"XMed");

    echo "<form action=".$_SERVER['PHP_SELF']." method='post' enctype='multipart/form-data'>
<input type='hidden' name='etape' value='2'>";

    echo "<table border='1'>".
        "<tr><td>Nom d'utilisateur pour se connecter au PSA : </td>".
        "<td><input type='text' name='cabinet' value=\"$cabinet\"> <i>Ne pas saisir d'accents, apostrophe, slash, etc...</i></td></tr>".
        "<tr><td>Mot de passe</Td>".
        "<td><input type='password' name='password' value='$password'></td></tr>".
        "<tr><td>Saisir à nouveau le mot de passe</Td>".
        "<td><input type='password' name='password2' value='$password2'></td></tr>".
        "<tr><td>Nom complet : </Td>".
        "<td><input type='text' name='nom_complet' value=\"$nom_complet\"> <i>Par exemple cabinet des Dr Gautier, Dr Bandet, Dr Chevalier, Dr Salesse</i></td></tr>".
        "<tr><td>Ville : </Td>".
        "<td><input type='text' name='ville' value='$ville'></td></tr>".
        "<tr><td>Contact : </Td>".
        "<td><input type='text' name='contact' value=\"$contact\"> </i>Ce nom est affiché sur la 1ère page du PSA lorsque l'infirmière est connectée</i></td></tr>".
        "<tr><td>Téléphone : </Td>".
        "<td><input type='text' name='telephone' value='$telephone'></td></tr>".
        "<tr><td>Email : </Td>".
        "<td><input type='text' name='email' value='$email'></td></tr>".
        "<tr><td>Nombre total de patients du cabinet : </Td>".
        "<td><input type='text' name='total_pat' value='$total_pat'> <i>Environ 800 patients par médecin équivalent temps plein</i></td></tr>".
        "<tr><td>Nombre de patients éligibles au dépistage cancer du sein : </Td>".
        "<td><input type='text' name='total_sein' value='$total_sein'> <i>Environ 150 patients par médecin équivalent temps plein</i></td></tr>".
        "<tr><td>Nombre de patients éligibles au dépistage troubles cognitifs : </Td>".
        "<td><input type='text' name='total_cogni' value='$total_cogni'> <i>Environ 120 patients par médecin équivalent temps plein</i></td></tr>".
        "<tr><td>Nombre de patients éligibles au dépistage cancer du colon : </Td>".
        "<td><input type='text' name='total_colon' value='$total_colon'> <i>Environ 300 patients par médecin équivalent temps plein</i></td></tr>".
        "<tr><td>Nombre de patients éligibles au dépistage cancer du col de l'utérus : </Td>".
        "<td><input type='text' name='total_uterus' value='$total_uterus'> <i>Environ 180 patients par médecin équivalent temps plein</i></td></tr>".
        "<tr><td>Nombre de patients éligibles au suivi du diabète de type 2: </Td>".
        "<td><input type='text' name='total_diab2' value='$total_diab2'> <i>Environ 50 patients par médecin équivalent temps plein</i></td></tr>".
        "<tr><td>Nombre de patients éligibles au suivi RCVA: </Td>".
        "<td><input type='text' name='total_HTA' value='$total_HTA'> <i>Environ 150 patients par médecin équivalent temps plein</i></td></tr>".
        "<tr><td>Nom de l'infirmière affiché dans les statistiques: </Td>".
        "<td>Choisir dans la liste si l'infirmière exerce dans un cabinet déjà créé : ".
        "<SELECT name='infirmiere'><option value=''></option>";

    $req="SELECT infirmiere, count(*) ".
        "FROM account ".
        "WHERE infirmiere!='' ".
        "GROUP BY infirmiere ".
        "ORDER BY infirmiere";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($inf, $nb)=mysql_fetch_row($res)){
        echo "<option value=\"$inf\" ";
        if($inf==$infirmiere){
            echo "selected";
        }
        echo ">$inf</option>";
    }

    echo "</SELECT><br>".
        "ou saisir le nom d'une nouvelle infirmière : <input type='text' name='infirmiere2' value=\"$infirmiere2\"></td></Tr>";


    echo "<tr><td>Nom du/des médecin(s): </Td>";



    echo "<td>Saisir le nom du/des nouveau(x) médecin(s) : 

	<div style=\"margin-bottom:5px;\">
		Dr <input type='text' name='prenom_medecin2' placeholder='prenom' value=\"$prenom_medecin2\">
		<input type='text' name='nom_medecin2' placeholder='nom' value=\"$nom_medecin2\">
	</div>
	
	<div style=\"margin-bottom:5px;\">
		Dr <input type='text' name='prenom_medecin3' placeholder='prenom' value=\"$prenom_medecin3\">
		<input type='text' name='nom_medecin3' placeholder='nom' value=\"$nom_medecin3\"><br />
	</div>
	
	<div style=\"margin-bottom:5px;\">
		Dr <input type='text' name='prenom_medecin4' placeholder='prenom' value=\"$prenom_medecin4\">
		<input type='text' name='nom_medecin4' placeholder='nom' value=\"$nom_medecin4\"><br />
	</div>

	</td></Tr>
	";

    echo
        "<tr><td>Nom du cabinet affiché dans les statistiques : </Td>".
        "<td><input type='text' name='nom_cab' value='$nom_cab'></td></tr>".
        "<tr><td>Numéro de portable de l'infirmière : </Td>".
        "<td><input type='text' name='portable' value='$portable'></td></tr>".
        "<tr><td>Nom de la région dans laquelle se situe le cabinet: </Td>".
        "<td>Choisir dans la liste si un cabinet a déjà été créé dans cette région : ".
        "<SELECT name='region'><option value=''></option>";

    $req="SELECT region, count(*) ".
        "FROM account ".
        "WHERE region!='' ".
        "GROUP BY region ".
        "ORDER BY region";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($reg, $nb)=mysql_fetch_row($res)){
        echo "<option value=\"$reg\" ";
        if($reg==$region){
            echo "selected";
        }
        echo ">$reg</option>";
    }

    echo "</SELECT><br>".
        "ou saisir le nom d'une nouvelle région : <input type='text' name='region2' value=\"$region2\"></td></Tr>".
        "<tr><td>Logiciel de gestion installé dans le cabinet: </Td>".
        "<td><SELECT name='logiciel'>";

    foreach($logiciels as $log=>$libelle){
        echo "<option value=\"$log\" ";
        if($log==$logiciel){
            echo "selected";
        }
        echo ">$libelle</option>";
    }

    echo "</SELECT>".
        "";
    echo "<input type='checkbox' name='log_ope' ";
    if($_POST['log_ope']=="1"){echo "value='$log_ope' checked='checked'";
    }else{echo "value='1'";}

    echo "/>  ";
    echo "si coché, le logiciel d'intégration est opérationnel".
        "</td></tr></table>";

    echo"<br><br><input type='submit' value='Valider'>";

}




//enregistrement des infos
function etape_2(&$repete) {
    global $message, $Dossier, $Cabinet, $deval, $self, $doc;

    extract($_POST);

    if($cabinet==""){
        $message[]="Veuillez préciser le nom d'utilisateur";
    }

    $req="SELECT cabinet from account where cabinet='$cabinet'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if(mysql_num_rows($res)==1){
        $message[]="Un autre cabinet a le même nom d'utilisateur, veuillez indiquer un autre nom d'utilisateur";
    }

    if($password!=$password2){
        $message[]="Les deux mots de passe sont différents";
    }

    if(($infirmiere=="")&&($infirmiere2=="")){
        $message[]="Veuillez préciser le nom de l'infirmière";
    }

    if($nom_cab==""){
        $message[]="Veuillez préciser le nom du cabinet à afficher dans les statistiques";
    }

    if(($region=="")&&($region2=="")){
        $message[]="Veuillez préciser la région dans laquelle se trouve le cabinet";
    }

    if(sizeof($message)>0){
        $_POST["etape"]=1;
        $repete=true;
        return;
    }

    if($infirmiere==""){
        $infirmiere=$infirmiere2;
    }

    if($region==""){
        $region=$region2;
    }

    if(($prenom_medecin2!='')||($nom_medecin2!='')){
        if(!medecinExist($cabinet, $prenom_medecin2, $nom_medecin2) ){
            //verif si le medecin n'existe pas déjà dans le cabinet
            $sql="INSERT INTO medecin SET cabinet='$cabinet', prenom = '$prenom_medecin2', nom='$nom_medecin2' ";
            $res=mysql_query($sql) or die("erreur SQL:".mysql_error()."<br>$sql");
        }
        else{
            $message[]="Ce médecin est déjà enregistré pour ce cabinet";
        }
    }

    if(($prenom_medecin3!='')||($nom_medecin3!='')){
        if(!medecinExist($cabinet, $prenom_medecin3, $nom_medecin3) ){
            //verif si le medecin n'existe pas déjà dans le cabinet
            $sql="INSERT INTO medecin SET cabinet='$cabinet', prenom = '$prenom_medecin3', nom='$nom_medecin3' ";
            $res=mysql_query($sql) or die("erreur SQL:".mysql_error()."<br>$sql");
        }
        else{
            $message[]=$prenom_medecin3." ".$nom_medecin3." est déjà enregistré pour ce cabinet";
        }

    }


    if(($prenom_medecin4!='')||($nom_medecin4!='')){
        if(!medecinExist($cabinet, $prenom_medecin4, $nom_medecin4) ){
            //verif si le medecin n'existe pas déjà dans le cabinet
            $sql="INSERT INTO medecin SET cabinet='$cabinet', prenom = '$prenom_medecin4', nom='$nom_medecin4' ";
            $res=mysql_query($sql) or die("erreur SQL:".mysql_error()."<br>$sql");
        }
        else{
            $message[]=$prenom_medecin4." ".$nom_medecin4." est déjà enregistré pour ce cabinet";
        }

    }




    $req="INSERT INTO account SET cabinet='$cabinet', password='$password', ".
        "nom_complet='".addslashes(stripslashes($nom_complet))."', ".
        "ville='".addslashes(stripslashes($ville))."', contact='".
        addslashes(stripslashes($contact))."', telephone='$telephone', ".
        "courriel='$email', total_pat='$total_pat', total_sein='$total_sein', ".
        "total_cogni='$total_cogni', total_colon='$total_colon', ".
        "total_uterus='$total_uterus', total_diab2='$total_diab2', ".
        "total_HTA='$total_HTA', infirmiere='".addslashes(stripslashes($infirmiere))."', ".
        "nom_cab='".addslashes(stripslashes($nom_cab))."', ".
        "portable='$portable', region='".addslashes(stripslashes($region))."', ".
        "log_ope='$log_ope',".
        "logiciel='".addslashes(stripslashes($logiciel))."'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $req="INSERT INTO histo_account SET cabinet='$cabinet', ".
        "d_modif='".date("Y-m-d H:i:s")."', ".
        "total_pat='$total_pat', total_sein='$total_sein', ".
        "total_cogni='$total_cogni', total_colon='$total_colon', ".
        "total_uterus='$total_uterus', total_diab2='$total_diab2', ".
        "total_HTA='$total_HTA'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    echo "Le cabinet a été  créé <br><br>";
    echo "<input type='button' value='Créer un autre cabinet' onclick='window.open(\"".$_SERVER['PHP_SELF']."\")'>";
}

function medecinExist($cabinet, $prenom, $nom){
    $req="SELECT * FROM medecin WHERE cabinet='$cabinet' AND prenom='$prenom' AND nom='$nom' ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $result = mysql_fetch_assoc($res);
    if($result)return true;
    else return false;

}
?>
</body>
</html>
