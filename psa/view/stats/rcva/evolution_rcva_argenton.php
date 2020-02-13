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
    <title>Evolution du RCV moyen par cabinet</title>
</head>
<body bgcolor=#FFE887>
<?php
require_once ("Config.php");
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");
require_once "../writeexcel/class.writeexcel_workbookbig.inc.php";
require_once "../writeexcel/class.writeexcel_worksheet.inc.php";


# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
die("Impossible de se connecter à la base");


$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";

require("../global/entete.php");
//echo $loc;

entete_asalee("Evolution du RCV moyen par cabinet");
?>
<!--<table cellpadding="2" cellspacing="2" border="0"
 style="text-align: left; width: 100%;">
  <tbody>
    <tr>
      <td style="width: 20%; vertical-align: top;">
      <br>
      <img src="<?php echo $loc; ?>/images/inf79.gif" alt="logo informed79"><br>
        <a href="mailto:contact@asalee.fr"><font size="-1">contact</font></a>
      </td>
      <td style="text-align: center; vertical-align: top;">
	       <span style="font-family: arial; font-weight: bold;">
 <?php
echo "
<i><font face='times new roman' size='32'>Asalée</font><br>
<font face='times new roman'>Taux de patients disposant d'au moins une mise à jour dans l'année</font></i>";
?>
           </span><br>
 <?php if(isset($_SESSION['nom'])) echo '<font size="-1"><i>'.$_SESSION['nom'].'</i></font>'; ?>
      </td>
      <td style="width: 15%; text-align: right; vertical-align: middle;">
      <img src="<?php echo $loc; ?>/images/urml.jpg" alt="logo urml"><br>
      </td>
    </tr>
  </tbody>
</table>
-->
<br><br>
<?php

# boucle principale
do {
    $repete=false;

    # fenêtre glissante:
    if (isset($_GET['mois']) && isset($_GET['annee']))
    {
        etape_2($repete);
        exit;
    }

    # étape 1 : identification du patient et de la date
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

            case 1:
                etape_1($repete);
                break;

            # étape 2  : saisie des détails
            case 2:
                etape_2($repete);
                break;

            # étape 3  : validation des données et màj base
            case 3:
                etape_3($repete);
                break;
        }
    }
} while($repete);

# fin de traitement principal


function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_diab, $lignes, $l, $col, $colonnes, $worksheet, $workbook;

    $req="SELECT cabinet, total_diab2, nom_cab, region ".
        "FROM account ".
        "WHERE region!='' and infirmiere!='' ".
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $reg=array();
    while(list($cab, $total_diab2, $ville, $region) = mysql_fetch_row($res)) {

        $t_diab[$cab]=0;

        $tville[$cab]=$ville;

        $regions[$cab]=$region;
        $nb_dossiers[$cab]=0;
        $rcva[$cab]=0;
        $nb_dossiers[$region]=0;
        $rcva[$region]=0;

        if(!in_array($region, $reg)){
            $reg[]=$region;
        }
    }

    $rcva["tot"]=$nb_dossiers["tot"]=0;

    $date1an=date("Y");
    $date1an--;
    $date1an=$date1an."-".date("m")."-".date("d");

    $req="SELECT dossier.id, numero, cabinet, sexe, dnaiss, max(dTA), max(dChol), max(dHDL) ".
        "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
        "and dTA>='$date1an' and dChol>='$date1an' and dHDL>='$date1an' and cabinet='argenton' ".
        "group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $fichier="../export/Liste RCVA par dossier Argenton.xls";
    $workbook =& new writeexcel_workbookbig($fichier); // on lui passe en paramètre le chemin de notre fichier
    $worksheet =& $workbook->addworksheet("Liste RCV");
    $worksheet->write("A1", "Cabinet");
    $worksheet->write("B1", "numéro");
    $worksheet->write("C1", "id");
    $worksheet->write("D1", date("d/m/Y"));

    $colonnes=array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P",
        "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
    $l=1;
    $col=3;
    while(list($id, $numero, $cabinet, $sexe, $dnaiss, $dTA, $dChol, $dHDL)=mysql_fetch_row($res)){
        if(isset($tville[$cabinet])){
            $tension=$choltot=$hdl="";
            if(($dTA>'0000-00-00')&&($dChol>'0000-00-00')&&($dHDL>'0000-00-00')){
                $req2="SELECT TaSys FROM cardio_vasculaire_depart WHERE id='$id' and dTA='$dTA'";
                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                list($tension)=mysql_fetch_row($res2);

                $req2="SELECT Chol FROM cardio_vasculaire_depart WHERE id='$id' and dChol='$dChol'";
                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                list($choltot)=mysql_fetch_row($res2);

                $req2="SELECT HDL FROM cardio_vasculaire_depart WHERE id='$id' and dHDL='$dHDL'";
                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                list($hdl)=mysql_fetch_row($res2);
            }

            $req2="SELECT HVG from cardio_vasculaire_depart WHERE id='$id' and (HVG='oui' or HVG='non') ".
                "ORDER by date DESC limit 0,1";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            list($ventricule)=mysql_fetch_row($res2);

            $req2="SELECT surcharge_ventricule from cardio_vasculaire_depart WHERE id='$id' and ".
                "(surcharge_ventricule='oui' or surcharge_ventricule='non') ".
                "ORDER by date DESC limit 0,1";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            list($surcharge_ventricule)=mysql_fetch_row($res2);

            $req2="SELECT tabac from cardio_vasculaire_depart WHERE id='$id' and ".
                "(tabac='oui' or tabac='non') ".
                "ORDER by date DESC limit 0,1";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            list($tab)=mysql_fetch_row($res2);

            $calcul=1;
            if(($tab!="oui")&&($tab!="non")){
                $calcul=0;
            }
            if($tension==''){
                $calcul=0;
            }
            if($choltot==''){
                $calcul=0;
            }
            if($hdl==''){
                $calcul=0;
            }
            if(($ventricule!="oui")&&($ventricule!="non")&&($surcharge_ventricule!="oui")&&($surcharge_ventricule!="non")){
                $calcul=0;
            }

            if($calcul==1){
                $age=get_age($dnaiss, date("Y-m-d"));

                $req2="SELECT dossier_id from suivi_diabete WHERE dossier_id='$id' ";
                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
                if(mysql_num_rows($res2)>0){
                    $diab=1;
                }
                else{
                    $diab=0;
                }
                $rcv=get_rcva($sexe, $age, $diab, $tab, $tension, $choltot, $hdl, $ventricule, $surcharge_ventricule);
                if(!isset($lignes[$id])){
                    $l++;
                    $lignes[$id]=$l;
                    $worksheet->write("A$l", "Argenton");
                    $worksheet->write_string("B$l", $numero);
                    $worksheet->write("C$l", $id);
                }

                $worksheet->write($colonnes[$col].$lignes[$id], $rcv);

            }

        }
    }

// $work=$worksheet;
    $annee0=2006;
    $mois0=12;

    $annee=date('Y');
    $mois=date('m');

    $mois--;


    if($mois<3)
    {
        $annee--;
        $mois=12;
    }
    elseif(($mois>=3)&&($mois<6))
    {
        $mois=3;
    }
    elseif(($mois>=6)&&($mois<9))
    {
        $mois=6;
    }
    elseif(($mois>=9)&&($mois<12))
    {
        $mois=9;
    }

    $jour[3]=$jour[12]=31;
    $jour[6]=$jour[9]=30;

    while(($annee>$annee0)||(($annee==$annee0)&&($mois>=$mois0)))
    {
        if($mois<10)
        {
            $date=$annee.'-0'.$mois.'-'.$jour[$mois];
        }
        else
        {
            $date=$annee.'-'.$mois.'-'.$jour[$mois];
        }
        $col++;


        $tab_date=split('-', $date);
        $worksheet->write($colonnes[$col]."1", $tab_date[2]."/".$tab_date[1]."/".$tab_date[0]);


        $date1an=$tab_date[0];
        $date1an--;
        $date1an=$date1an."-".$tab_date[1]."-".$tab_date[2];

        $req="SELECT dossier.id, numero, cabinet, sexe, dnaiss, max(dTA), max(dChol), max(dHDL) ".
            "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
            "and dTA>='$date1an' and dChol>='$date1an' and dHDL>='$date1an' and cabinet='Argenton' ".
            "and dTA<='$date' and dChol<='$date' and dHDL<='$date' ".
            "group by dossier.id";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        while(list($id, $numero, $cabinet, $sexe, $dnaiss, $dTA, $dChol, $dHDL)=mysql_fetch_row($res)){
            if(isset($tville[$cabinet])){
                $tension=$choltot=$hdl="";
                if(($dTA>'0000-00-00')&&($dChol>'0000-00-00')&&($dHDL>'0000-00-00')){
                    $req2="SELECT TaSys FROM cardio_vasculaire_depart WHERE id='$id' and dTA='$dTA'";
                    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                    list($tension)=mysql_fetch_row($res2);

                    $req2="SELECT Chol FROM cardio_vasculaire_depart WHERE id='$id' and dChol='$dChol'";
                    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                    list($choltot)=mysql_fetch_row($res2);

                    $req2="SELECT HDL FROM cardio_vasculaire_depart WHERE id='$id' and dHDL='$dHDL'";
                    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                    list($hdl)=mysql_fetch_row($res2);
                }

                $req2="SELECT HVG from cardio_vasculaire_depart WHERE id='$id' and (HVG='oui' or HVG='non') ".
                    "ORDER by date DESC limit 0,1";
                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                list($ventricule)=mysql_fetch_row($res2);

                $req2="SELECT surcharge_ventricule from cardio_vasculaire_depart WHERE id='$id' and ".
                    "(surcharge_ventricule='oui' or surcharge_ventricule='non') ".
                    "ORDER by date DESC limit 0,1";
                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                list($surcharge_ventricule)=mysql_fetch_row($res2);

                $req2="SELECT tabac from cardio_vasculaire_depart WHERE id='$id' and ".
                    "(tabac='oui' or tabac='non') ".
                    "ORDER by date DESC limit 0,1";
                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                list($tab)=mysql_fetch_row($res2);

                $calcul=1;
                if(($tab!="oui")&&($tab!="non")){
                    $calcul=0;
                }
                if($tension==''){
                    $calcul=0;
                }
                if($choltot==''){
                    $calcul=0;
                }
                if($hdl==''){
                    $calcul=0;
                }
                if(($ventricule!="oui")&&($ventricule!="non")&&($surcharge_ventricule!="oui")&&($surcharge_ventricule!="non")){
                    $calcul=0;
                }

                if($calcul==1){
                    $age=get_age($dnaiss, date("Y-m-d"));

                    $req2="SELECT dossier_id from suivi_diabete WHERE dossier_id='$id' ";
                    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
                    if(mysql_num_rows($res2)>0){
                        $diab=1;
                    }
                    else{
                        $diab=0;
                    }
                    $rcv=get_rcva($sexe, $age, $diab, $tab, $tension, $choltot, $hdl, $ventricule, $surcharge_ventricule);
                    if(!isset($lignes[$id])){
                        $l++;
                        $lignes[$id]=$l;
                        $worksheet->write("A$l", "Argenton");
                        $worksheet->write_string("B$l", $numero);
                        $worksheet->write("C$l", $id);
                    }

                    $worksheet->write($colonnes[$col].$lignes[$id], $rcv);


                }

            }
        }

        $mois=$mois-3;

        if($mois<=0)
        {
            $mois=$mois+12;
            $annee--;
        }
    }

// $worksheet=$work;
    $workbook->close();
    echo "<a href='$fichier'>$fichier</a>";
}

function get_rcva($sexe, $age, $diab, $tab, $tension, $choltot, $hdl, $ventricule, $surcharge_ventricule){

    $e1 = -0.9119;
    $e2 = -0.2767;
    $e3 = -0.7181;
    $e4 = -0.5865;
    $l = 11.1122;
    $m0 = 4.4181 ;
    $s0 = -0.3155 ;
    $s1 = -0.2784;
    $c1 = -1.4792 ;
    $c2 = -0.1759 ;
    $d1 = -5.8549;
    $d2 = 1.8515 ;
    $d3 = -0.3758 ;
    $horizon=10;

    $pas=$tension;
    $tabac=0;
    $hvg=0;
    $chol=$choltot;
    $HDL=$hdl;

    if($tab=="oui"){
        $tabac=1;
    }
    if(($ventricule!="oui")&&($ventricule!="non")&&($surcharge_ventricule=="oui")){
        $hvg=1;
    }
    if($ventricule=="oui"){
        $hvg=1;
    }

    $a = $l + $e1*log($pas) + $e2*$tabac + $e3*log($chol/$HDL) + $e4*$hvg;

    if($sexe=="M"){
        $m = $a + $c1*log($age) + $c2*$diab*1; //;=> on considère que le patient n'est pas diabétique
    }
    if($sexe=='F'){
        $m = $a + $d1 + $d2*(log($age/74)*log($age/74)) + $d3*$diab; //on considère que la patient n'est pas diabétique
    }


    $m_calc = $m0 + $m;
    $s = exp($s0 + $s1*$m);

    $u = (log($horizon) - $m_calc ) / $s ;

    $pt = 1- exp(-exp($u));

    $rcva=round($pt*100, 2);
    // $rcva=$rcva."%";

    return $rcva;

}


function get_age($dnaiss, $date=false){
    if($date==false){
        $date=date("Y-m-d");
    }

    $dj=explode("-", $date);
    $dn=explode("-", $dnaiss);

    $age=$dj[0]-$dn[0];
    if($dj[1]>$dn[1]){
        $age--;
    }
    if(($dj[1]==$dn[1])&&($dj[2]<$dn[2])){
        $age--;
    }
    return $age;
}
?>
</body>
</html>
