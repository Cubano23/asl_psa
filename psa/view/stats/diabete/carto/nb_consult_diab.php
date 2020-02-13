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
    <title>Nombre de patients ayant eu 1, 2, 3, etc consultations</title>
</head>
<body bgcolor=#FFE887>
<?php
require_once "Config.php";
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");

# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
die("Impossible de se connecter à la base");


$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
require("../../global/entete.php");
//echo $loc;

$titre="Nombre de patients ayant eu 1, 2, 3, etc consultations";


entete_asalee($titre);
//echo $loc;
?>
<br><br>
<?

# boucle principale
do {
    $repete=false;


    # étape 1 : affichage tableau
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

            //affichage tableau
            case 1:
                etape_1($repete);
                break;

        }
    }
} while($repete);

# fin de traitement principal

//affichage tableau
function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet;



    $req="SELECT account.cabinet, count(*), nom_cab ".
        "FROM dossier, account ".
        "WHERE account.cabinet!='zTest' and account.cabinet!='irdes'   and account.cabinet!='ergo' ".
        "and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' ".
        "AND dossier.cabinet=account.cabinet ".
        "AND actif='oui' ".
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab, numero ";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if (mysql_num_rows($res)==0) {
        exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }
    $tcabinet=array();


    while(list($cab, $pat, $ville) = mysql_fetch_row($res)) {
        $tcabinet[] = $cab;
        $tville[]=$ville;
        $nb_consult[$cab]=array();
//	 $tpat[$cab] = $pat;
    }

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');


    ?>
    <br>
    <br>

    <table border=1 width='100%'>
        <tr>
            <td></td><td><b>Total</b></td>
            <?php
            foreach($tville as $cab) {
                ?>
                <td align='center'><b><?php echo $cab; ?></b></td>
                <?php
            }
            ?>
        </tr>


        <?
        //Mise dans un tableau des lignes comprenant, pour chaque consultation, la liste des HBA.
        //Classement par id, dHBA pour pouvoir garder le dernier HBA avant consultation, et le premier après
        //Le tri sera fait ensuite.

        $req= "SELECT cabinet, dossier_id, `date` as date_consult ".
            "FROM dossier, suivi_diabete, evaluation_infirmier ".
            "WHERE dossier_id=dossier.id AND evaluation_infirmier.id=dossier_id and cabinet!='ztest' and ".
            "dossier.cabinet!='sbirault' and ".
            "cabinet!='irdes' and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
            "GROUP BY cabinet, dossier_id, date_consult ".
            "ORDER BY cabinet, dossier_id, date_consult";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        $id_prec="";
        $nb_max=0;

        while(list($cabinet, $dossier_id, $date_consult)=mysql_fetch_row($res)){
            if($dossier_id!=$id_prec){//On regarde ce qu'il se passe pour un nouveau dossier
                if($id_prec!=""){
                    if(!isset($nb_consult[$cab_prec][$nb])){
                        $nb_consult[$cab_prec][$nb]=1;
                    }
                    else{
                        $nb_consult[$cab_prec][$nb]=$nb_consult[$cab_prec][$nb]+1;
                    }

                    if(!isset($nb_consult['tot'][$nb])){
                        $nb_consult['tot'][$nb]=1;
                    }
                    else{
                        $nb_consult['tot'][$nb]=$nb_consult['tot'][$nb]+1;
                    }

                    if($nb>$nb_max){
                        $nb_max=$nb;
                    }
                }
                $nb=1;
                $cab_prec=$cabinet;
                $id_prec=$dossier_id;
            }
            else{//On est sur le même dossier
                $nb++;
            }
        }

        if(!isset($nb_consult[$cab_prec][$nb])){
            $nb_consult[$cab_prec][$nb]=1;
        }
        else{
            $nb_consult[$cab_prec][$nb]=$nb_consult[$cab_prec][$nb]+1;
        }

        if(!isset($nb_consult['tot'][$nb])){
            $nb_consult['tot'][$nb]=1;
        }
        else{
            $nb_consult['tot'][$nb]=$nb_consult['tot'][$nb]+1;
        }


        ?>
        <?
        for($i=1;$i<=$nb_max;$i++){
            if($i>1){
                $s="s";
            }
            else{
                $s="";
            }
            echo "<tr>
				<td nowrap>$i consultation$s</td><td align='right'>";
            if(isset($nb_consult['tot'][$i])){
                echo $nb_consult['tot'][$i];
            }
            else{
                echo "0";
            }
            echo "</td>";

            foreach($tcabinet as $cab){
                if(isset($nb_consult[$cab][$i])){
                    echo "<td align='right'>".$nb_consult[$cab][$i]."</td>";
                }
                else{
                    echo "<td align='right'>0</td>";
                }
            }
            echo "</tr>";

        }

        echo "<tr><td>Total</td>";

        $somme=0;
        foreach($nb_consult["tot"] as $nb=>$val){
            $somme=$somme+$nb*$val;
        }

        echo "<td align='right'>$somme</td>";

        foreach($tcabinet as $cab){
            if(isset($nb_consult[$cab])){
                $somme=0;
                foreach($nb_consult[$cab] as $nb=>$val){
                    $somme=$somme+$nb*$val;
                }
                echo "<td align='right'>$somme</td>";
            }
            else{
                echo "<td align='right'>0</td>";
            }
        }
        echo "</tr>";
        ?>

    </table>
    <?

}


?>
</body>
</html>
