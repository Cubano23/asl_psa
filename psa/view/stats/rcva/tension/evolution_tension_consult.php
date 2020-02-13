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
set_time_limit(120);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="content-type"
          content="text/html; charset=ISO-8859-15">
    <title>Evolution de la tension après 1, 2, 3 consultations</title>
</head>
<body bgcolor=#FFE887>
<?php
require_once ("Config.php");
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

entete_asalee("Evolution de la tension après 1, 2, 3 consultations");

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
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $regions, $liste_reg;


    $req="SELECT dossier.cabinet, count(*), nom_cab, region ".
        "FROM dossier, account ".
        "WHERE infirmiere!='' and region!='' ".
        "AND actif='oui' ".
        "and dossier.cabinet=account.cabinet ".
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab, numero ";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if (mysql_num_rows($res)==0) {
        exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }
    $tcabinet=array();
    $liste_reg=array();
    $dossierssup140["tot"][1]=0;
    $dossierssup140["tot"][2]=0;
    $dossierssup140["tot"][3]=0;
    $dossierssup140["tot"][4]=0;
    $dossiersinf140["tot"][1]=0;
    $dossiersinf140["tot"][2]=0;
    $dossiersinf140["tot"][3]=0;
    $dossiersinf140["tot"][4]=0;
    $dossierspastension["tot"]=0;
    $change["tot"][1]=0;
    $change["tot"][2]=0;
    $change["tot"][3]=0;
    $change["tot"][4]=0;

    while(list($cab, $pat, $ville, $region) = mysql_fetch_row($res)) {
        $tcabinet[] = $cab;
        $tville[$cab]=$ville;
        $regions[$cab]=$region;

        if(!in_array($region, $liste_reg)){
            $liste_reg[]=$region;
            $dossiers[$region]=array();
            $dossierssup140[$region][1]=0;
            $dossierssup140[$region][2]=0;
            $dossierssup140[$region][3]=0;
            $dossierssup140[$region][4]=0;
            $dossiersinf140[$region][1]=0;
            $dossiersinf140[$region][2]=0;
            $dossiersinf140[$region][3]=0;
            $dossiersinf140[$region][4]=0;
            $dossierspastension[$region]=0;
            $change[$region][1]=0;
            $change[$region][2]=0;
            $change[$region][3]=0;
            $change[$region][4]=0;
        }

        $dossiers[$cab]=array();
        $dossierssup140[$cab][1]=0;
        $dossierssup140[$cab][2]=0;
        $dossierssup140[$cab][3]=0;
        $dossierssup140[$cab][4]=0;
        $dossiersinf140[$cab][1]=0;
        $dossiersinf140[$cab][2]=0;
        $dossiersinf140[$cab][3]=0;
        $dossiersinf140[$cab][4]=0;
        $dossierspastension[$cab]=0;
        $change[$cab][1]=0;
        $change[$cab][2]=0;
        $change[$cab][3]=0;
        $change[$cab][4]=0;

//	 $tpat[$cab] = $pat;
    }

    sort($liste_reg);
    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');

    echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

    ?>
    <br>
    <br>
    <table border=1 width='100%'>

        <?php

        //Liste des consults par patient
        $req="SELECT cabinet, dossier.id, date ".
            "FROM evaluation_infirmier, dossier ".
            "WHERE actif='oui' ".
            "AND evaluation_infirmier.id=dossier.id ".
            "ORDER BY cabinet, id, date ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        $id_prec="";

        while(list($cabinet, $id, $date)=mysql_fetch_row($res)){
            if(isset($regions[$cabinet])){
                if($id_prec!=$id){//Nouveau dossier=> 1ère consult
                    $consult[$id][1]=$date;
                    $id_prec=$id;
                    $nb_consult=1;
                }
                else{
                    $nb_consult++;
                    $consult[$id][$nb_consult]=$date;
                }
            }
        }

        //Liste des tensions par patient en RCVA
        $req="SELECT cabinet, dossier.id, date_exam, resultat1 ".
            "FROM cardio_vasculaire_depart, dossier, liste_exam ".
            "WHERE actif='oui' ".
            "AND cardio_vasculaire_depart.id=dossier.id and dossier.id=liste_exam.id ".
            "and type_exam='systole' ".
            "and date_exam>'1990-01-01' ".
            "GROUP BY cabinet, dossier.id, date_exam ";
        "ORDER BY cabinet, dossier.id, date_exam ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        $id_prec="";

        while(list($cabinet, $id, $date, $TaSys)=mysql_fetch_row($res)){
            $req2="SELECT resultat1 from liste_exam where id='$id' and type_exam='diastole' and ".
                "date_exam='$date'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            list($TaDia)=mysql_fetch_row($res2);

            if(isset($regions[$cabinet])){
                $id_prec=$id;
                $dossiers[$cabinet][]=$id;
                $dossiers[$regions[$cabinet]][]=$id;
                $cabinets[$id]=$cabinet;
                $liste_tension[$id][$date]=array("TaSys"=>$TaSys, "TaDia"=>$TaDia);
            }
        }

        //Liste des tensions par patient en suivi diabète
        $req="SELECT cabinet, dossier.id, date_exam, resultat1 ".
            "FROM suivi_diabete, dossier, liste_exam ".
            "WHERE actif='oui' ".
            "AND dossier_id=dossier.id and dossier.id=liste_exam.id ".
            "and date_exam>'1990-01-01' and type_exam='systole' ".
            "GROUP BY cabinet, dossier.id, date_exam ";
        "ORDER BY cabinet, dossier.id, date_exam ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        $id_prec="";

        while(list($cabinet, $id, $date, $TaSys)=mysql_fetch_row($res)){
            $req2="SELECT resultat1 from liste_exam where id='$id' and type_exam='diastole' and ".
                "date_exam='$date'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            list($TaDia)=mysql_fetch_row($res2);

            if(isset($regions[$cabinet])){
                $id_prec=$id;
                $cabinets[$id]=$cabinet;
                $liste_tension[$id][$date]=array("TaSys"=>$TaSys, "TaDia"=>$TaDia);
            }
        }


        foreach($liste_tension as $id => $tab){
            if(isset($consult[$id])){
                $consult1=$consult[$id][1];
                if(isset($consult[$id][2])){
                    $consult2=$consult[$id][2];
                }
                else{
                    $consult2="2100-01-01";
                }
                if(isset($consult[$id][3])){
                    $consult3=$consult[$id][3];
                }
                else{
                    $consult3="2100-01-01";
                }
                if(isset($consult[$id][4])){
                    $consult4=$consult[$id][4];
                }
                else{
                    $consult4="2100-01-01";
                }

                $valeur1=$valeur2=$valeur3=$valeur4=$valeur5="";

                foreach($tab as $date=>$valeurs){
                    if($date<$consult1){
                        $valeur1=$valeurs;
                    }
                    elseif(($date>=$consult1)&&($date<$consult2)){
                        if($valeur2==""){
                            $valeur2=$valeurs;
                        }
                    }
                    elseif(($date>=$consult2)&&($date<$consult3)){
                        if($valeur3==""){
                            $valeur3=$valeurs;
                        }
                    }
                    elseif(($date>=$consult3)&&($date<$consult4)){
                        if($valeur4==""){
                            $valeur4=$valeurs;
                        }
                    }
                    else{
                        if($valeur5==""){
                            $valeur5=$valeurs;
                        }
                    }
                }

                if($valeur1==""){
                    $dossierspastension[$cabinets[$id]]=$dossierspastension[$cabinets[$id]]+1;
                    $dossierspastension[$regions[$cabinets[$id]]]=$dossierspastension[$regions[$cabinets[$id]]]+1;
                    $dossierspastension["tot"]=$dossierspastension["tot"]+1;
                }
                elseif($valeur2!=""){
                    if(($valeurs["TaSys"]<140)&&($valeurs["TaDia"]<90)){//Dossier <140/90
                        $dossiersinf140[$cabinets[$id]][1]=$dossiersinf140[$cabinets[$id]][1]+1;
                        $dossiersinf140[$regions[$cabinets[$id]]][1]=$dossiersinf140[$regions[$cabinets[$id]]][1]+1;
                        $dossiersinf140["tot"][1]=$dossiersinf140["tot"][1]+1;
                    }
                    else{
                        $dossierssup140[$cabinets[$id]][1]=$dossierssup140[$cabinets[$id]][1]+1;
                        $dossierssup140[$regions[$cabinets[$id]]][1]=$dossierssup140[$regions[$cabinets[$id]]][1]+1;
                        $dossierssup140["tot"][1]=$dossierssup140["tot"][1]+1;

                        if(($valeur2["TaSys"]<140)&&($valeur2["TaDia"]<90)){
                            $change[$cabinets[$id]][1]=$change[$cabinets[$id]][1]+1;
                            $change[$regions[$cabinets[$id]]][1]=$change[$regions[$cabinets[$id]]][1]+1;
                            $change["tot"][1]=$change["tot"][1]+1;
                        }
                    }
                }
                elseif($valeur3!=""){
                    if(($valeurs["TaSys"]<140)&&($valeurs["TaDia"]<90)){//Dossier <140/90
                        $dossiersinf140[$cabinets[$id]][2]=$dossiersinf140[$cabinets[$id]][2]+1;
                        $dossiersinf140[$regions[$cabinets[$id]]][2]=$dossiersinf140[$regions[$cabinets[$id]]][2]+1;
                        $dossiersinf140["tot"][2]=$dossiersinf140["tot"][2]+1;
                    }
                    else{
                        $dossierssup140[$cabinets[$id]][2]=$dossierssup140[$cabinets[$id]][2]+1;
                        $dossierssup140[$regions[$cabinets[$id]]][2]=$dossierssup140[$regions[$cabinets[$id]]][2]+1;
                        $dossierssup140["tot"][2]=$dossierssup140["tot"][2]+1;

                        if(($valeur3["TaSys"]<140)&&($valeur3["TaDia"]<90)){
                            $change[$cabinets[$id]][2]=$change[$cabinets[$id]][2]+1;
                            $change[$regions[$cabinets[$id]]][2]=$change[$regions[$cabinets[$id]]][2]+1;
                            $change["tot"][2]=$change["tot"][2]+1;
                        }
                    }
                }
                elseif($valeur4!=""){
                    if(($valeurs["TaSys"]<140)&&($valeurs["TaDia"]<90)){//Dossier <140/90
                        $dossiersinf140[$cabinets[$id]][3]=$dossiersinf140[$cabinets[$id]][3]+1;
                        $dossiersinf140[$regions[$cabinets[$id]]][3]=$dossiersinf140[$regions[$cabinets[$id]]][3]+1;
                        $dossiersinf140["tot"][3]=$dossiersinf140["tot"][3]+1;
                    }
                    else{
                        $dossierssup140[$cabinets[$id]][3]=$dossierssup140[$cabinets[$id]][3]+1;
                        $dossierssup140[$regions[$cabinets[$id]]][3]=$dossierssup140[$regions[$cabinets[$id]]][3]+1;
                        $dossierssup140["tot"][3]=$dossierssup140["tot"][3]+1;

                        if(($valeur4["TaSys"]<140)&&($valeur4["TaDia"]<90)){
                            $change[$cabinets[$id]][3]=$change[$cabinets[$id]][3]+1;
                            $change[$regions[$cabinets[$id]]][3]=$change[$regions[$cabinets[$id]]][3]+1;
                            $change["tot"][3]=$change["tot"][3]+1;
                        }
                    }
                }
                elseif($valeur5!=""){
                    if(($valeurs["TaSys"]<140)&&($valeurs["TaDia"]<90)){//Dossier <140/90
                        $dossiersinf140[$cabinets[$id]][4]=$dossiersinf140[$cabinets[$id]][4]+1;
                        $dossiersinf140[$regions[$cabinets[$id]]][4]=$dossiersinf140[$regions[$cabinets[$id]]][4]+1;
                        $dossiersinf140["tot"][4]=$dossiersinf140["tot"][4]+1;
                    }
                    else{
                        $dossierssup140[$cabinets[$id]][4]=$dossierssup140[$cabinets[$id]][4]+1;
                        $dossierssup140[$regions[$cabinets[$id]]][4]=$dossierssup140[$regions[$cabinets[$id]]][4]+1;
                        $dossierssup140["tot"][4]=$dossierssup140["tot"][4]+1;

                        if(($valeur5["TaSys"]<140)&&($valeur5["TaDia"]<90)){
                            $change[$cabinets[$id]][4]=$change[$cabinets[$id]][4]+1;
                            $change[$regions[$cabinets[$id]]][4]=$change[$regions[$cabinets[$id]]][4]+1;
                            $change["tot"][4]=$change["tot"][4]+1;
                        }
                    }
                }
            }
        }

        ?>

        <tr>
            <td>Valeurs de la tension</td>
            <td align="center"><b>Moyenne</b></td>

            <?php

            foreach($liste_reg as $reg){
                echo "<td align='center'><b>moyenne $reg</b></td>";
            }

            foreach ($tcabinet as $cab)
            {
                ?>
                <td align="center"><b><?php echo $tville[$cab];?></b></td>
                <?php

            }
            ?>
        </tr>

        <?php

        for($i=1; $i<=4; $i++)
        {
            if($i>1){
                $s="s";
            }
            else{
                $s="";
            }

            echo "<tr>
		<td>Nb dossiers avec tension &gt; 140/90 avant $i consultation$s<sup>1</sup> </td>
   			<td align='right'>".$dossierssup140["tot"][$i]."</Td>";

            foreach($liste_reg as $reg){
                echo "<td align='right'>".$dossierssup140[$reg][$i]."</Td>";
            }

            foreach ($tcabinet as $cab)
            {
                echo "<td align='right'>".$dossierssup140[$cab][$i]."</Td>";
            }

            echo "</tr>";

            echo "<tr>
		<td>Taux dossiers avec tension &gt; 140/90 avant $i consultation$s et passant &lt;140/90<sup>2</sup> </td>
   			<td align='right'>".round($change["tot"][$i]/$dossierssup140["tot"][$i]*100)." %</Td>";

            foreach($liste_reg as $reg){
                if($dossierssup140[$reg][$i]==0){
                    echo "<td align='right'>ND</Td>";
                }
                else{
                    echo "<td align='right'>".round($change[$reg][$i]/$dossierssup140[$reg][$i]*100)." %</Td>";
                }
            }

            foreach ($tcabinet as $cab)
            {
                if($dossierssup140[$cab][$i]==0){
                    echo "<td align='right'>ND</Td>";
                }
                else{
                    echo "<td align='right'>".round($change[$cab][$i]/$dossierssup140[$cab][$i]*100)." %</Td>";
                }
            }

            echo "</tr>";
        }


        ?>
    </table>
    <!--<sup>1</sup>Nb de dossiers pour lesquels la systole est &lt; 140 et la diastole est &lt;90 avant la 1ère consultation<br>-->
    <sup>1</sup>Nb de dossiers pour lesquels la systole est &gt;=140 ou la diastole est &gt;=90 avant la 1ère consultation et une tension tension après la 1ère, 2ème, 3ème, 4ème consultation<br>
    <sup>2</sup>Nb de dossiers pour lesquels la tension était &gt;=140/90 avant la 1ère consultation et passant &lt;140/90 après la 1ère, 2ème, 3ème, 4ème consultation<br>
    <?
}


?>
</body>
</html>
