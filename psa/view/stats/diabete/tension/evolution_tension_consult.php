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
    <title>Evolution de la tension après 1, 2, 3 consultations</title>
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

entete_asalee("Evolution de la tension après 1, 2, 3 consultations");

//echo $loc;
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
<font face='times new roman'>Taux de diabète équilibré</font></i>";
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
<?

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
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $regions, $liste_reg;


    /*print_r($t_tot);echo "<br>";
    print_r($t_sein);echo "<br>";
    print_r($t_cogni);echo "<br>";
    print_r($t_colon);echo "<br>";
    print_r($t_uterus);echo "<br>";
    print_r($t_diab);echo "<br>";
    */

    $req="SELECT dossier.cabinet, count(*), nom_cab, region ".
        "FROM dossier, account ".
        "WHERE dossier.cabinet!='zTest' and dossier.cabinet!='irdes'  and dossier.cabinet!='ergo'  ".
        "and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' and region!='' ".
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
        $req="SELECT cabinet, dossier.id, dTA, TaSys, TaDia ".
            "FROM cardio_vasculaire_depart, dossier ".
            "WHERE actif='oui' ".
            "AND cardio_vasculaire_depart.id=dossier.id ".
            "and dTA>'1990-01-01' ".
            "ORDER BY cabinet, id, dTA ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        $id_prec="";

        while(list($cabinet, $id, $date, $TaSys, $TaDia)=mysql_fetch_row($res)){
            if(isset($regions[$cabinet])){
                $id_prec=$id;
                $dossiers[$cabinet][]=$id;
                $dossiers[$regions[$cabinet]][]=$id;
                $cabinets[$id]=$cabinet;
                $liste_tension[$id][$date]=array("TaSys"=>$TaSys, "TaDia"=>$TaDia);
            }
        }

        //Liste des tensions par patient en suivi diabète
        $req="SELECT cabinet, dossier.id, dtension, TaSys, TaDia ".
            "FROM suivi_diabete, dossier ".
            "WHERE actif='oui' ".
            "AND dossier_id=dossier.id ".
            "and dtension>'1990-01-01' ".
            "ORDER BY cabinet, id, dtension ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        $id_prec="";

        while(list($cabinet, $id, $date, $TaSys, $TaDia)=mysql_fetch_row($res)){
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
        /*
        echo "<tr><td>Nb dossiers sans tension avant 1ère consultation</td>";
        echo "<td align='right'>".$dossierspastension["tot"]."</td>";
        foreach($liste_reg as $reg){
            echo "<td align='right'>".$dossierspastension[$reg]."</td>";
        }
        foreach ($tcabinet as $cab)
        {
            echo "<td align='right'>".$dossierspastension[$cab]."</td>";
        }
        */
        for($i=1; $i<=4; $i++)
        {
            if($i>1){
                $s="s";
            }
            else{
                $s="";
            }
            /*	echo "<tr>
                    <td>Nb dossiers avec tension &lt; 140/90 avant $i consultation$s<sup>1</sup> </td>
                           <td align='right'>".$dossiersinf140["tot"][$i]."</Td>";

                foreach($liste_reg as $reg){
                    echo "<td align='right'>".$dossiersinf140[$reg][$i]."</Td>";
                }

                foreach ($tcabinet as $cab)
                {
                    echo "<td align='right'>".$dossiersinf140[$cab][$i]."</Td>";
                }

                echo "</tr>";
                */
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
    <sup>1</sup>Nb de dossiers pour lesquels la systole est &gt;=140 ou la diastole est &ht;=90 avant la 1ère consultation et une tension tension après la 1ère, 2ème, 3ème, 4ème consultation<br>
    <sup>2</sup>Nb de dossiers pour lesquels la tension était &gt;=140/90 avant la 1ère consultation et passant &lt;140/90 après la 1ère, 2ème, 3ème, 4ème consultation<br>
    <?
}


function tableau($date){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $regions, $liste_reg;


    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

    $tab_date=split('-', $date);

    echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";

    /*print_r($t_tot);echo "<br>";
    print_r($t_sein);echo "<br>";
    print_r($t_cogni);echo "<br>";
    print_r($t_colon);echo "<br>";
    print_r($t_uterus);echo "<br>";
    print_r($t_diab);echo "<br>";
    */



    ?>
    <br>
    <br>
    <table border=1 width='100%'>

        <?php

        //taux diab 2 suivis dans asalée
        $req="SELECT cabinet, id, dsuivi, TaSys, TaDia ".
            "FROM suivi_diabete, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "AND dsuivi<='$date' ".
            "ORDER BY cabinet, id, dsuivi ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        foreach ($tcabinet as $cab)
        {
            $tpat[$cab][0]=0;//pas de tension
            $tpat[$cab][1]=0;//Ta<140/90
            $tpat[$cab][2]=0;//Ta>=140/90
            $total[$cab]=0;
        }

        $tpat['tot'][0]=0;
        $tpat['tot'][1]=0;
        $tpat['tot'][2]=0;
        $total['tot']=0;

        foreach($liste_reg as $reg){
            $tpat[$reg][0]=0;
            $tpat[$reg][1]=0;
            $tpat[$reg][2]=0;
            $total[$reg]=0;
        }



        $id_prec='';
        $cab_prec="";
        $i=0;
        while(list($cab, $id, $dsuivi, $TaSys, $TaDia) = mysql_fetch_row($res)) {
            if(isset($regions[$cab_prec])){
                $i++;
                if($id_prec!=$id)
                {
                    if($id_prec!='')
                    {
                        if(($TaSys_prec<140)&&($TaDia_prec<90)&&($TaSys_prec>0)&&($TaDia_prec>0))
                        {
                            $tpat['tot'][1]=$tpat['tot'][1]+1;
                            $tpat[$cab_prec][1]=$tpat[$cab_prec][1]+1;

                            $tpat[$regions[$cab_prec]][1]=$tpat[$regions[$cab_prec]][1]+1;
                        }
                        elseif(($TaSys_prec>=140)||($TaDia_prec>=90))
                        {
                            $tpat['tot'][2]=$tpat['tot'][2]+1;
                            $tpat[$cab_prec][2]=$tpat[$cab_prec][2]+1;

                            $tpat[$regions[$cab_prec]][2]=$tpat[$regions[$cab_prec]][2]+1;
                        }
                        else //pas de mesure
                        {
                            $tpat['tot'][0]=$tpat['tot'][0]+1;
                            $tpat[$cab_prec][0]=$tpat[$cab_prec][0]+1;

                            $tpat[$regions[$cab_prec]][0]=$tpat[$regions[$cab_prec]][0]+1;
                        }


                        $total['tot']=$total['tot']+1;

                        $total[$regions[$cab_prec]]=$total[$regions[$cab_prec]]+1;

                        $total[$cab_prec]=$total[$cab_prec]+1;

                        $cab_prec=$cab;
                        $TaSys_prec=$TaSys;
                        $TaDia_prec=$TaDia;
                        $id_prec=$id;

                    }
                    else
                    {
                        $id_prec=$id;
                        $TaSys_prec=$TaSys;
                        $TaDia_prec=$TaDia;
                        $cab_prec=$cab;

                    }

                }
                else
                {
                    if(($TaSys!=0)&&($TaSys!='NULL')&&($TaDia!=0)&&($TaDia!='NULL'))
                    {
                        $TaSys_prec=$TaSys;
                        $TaDia_prec=$TaDia;
                    }
                }
            }
            else
            {
                $id_prec=$id;
                $TaSys_prec=$TaSys;
                $TaDia_prec=$TaDia;
                $cab_prec=$cab;

            }
        }

        if(isset($regions[$cab_prec])){
            if(($TaSys_prec<140)&&($TaDia_prec<90)&&($TaSys_prec>0)&&($TaDia_prec>0))
            {
                $tpat['tot'][1]=$tpat['tot'][1]+1;
                $tpat[$cab_prec][1]=$tpat[$cab_prec][1]+1;

                $tpat[$regions[$cab_prec]][1]=$tpat[$regions[$cab_prec]][1]+1;
            }
            elseif(($TaSys_prec>=140)||($TaDia_prec>=90))
            {
                $tpat['tot'][2]=$tpat['tot'][2]+1;
                $tpat[$cab_prec][2]=$tpat[$cab_prec][2]+1;

                $tpat[$regions[$cab_prec]][2]=$tpat[$regions[$cab_prec]][2]+1;
            }
            else //pas de mesure
            {
                $tpat['tot'][0]=$tpat['tot'][0]+1;
                $tpat[$cab_prec][0]=$tpat[$cab_prec][0]+1;

                $tpat[$regions[$cab_prec]][0]=$tpat[$regions[$cab_prec]][0]+1;
            }



            $total['tot']=$total['tot']+1;

            $total[$regions[$cab_prec]]=$total[$regions[$cab_prec]]+1;

            $total[$cab_prec]=$total[$cab_prec]+1;
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

        $intitule=array('Manquant', '<140/90<sup>1</sup>', '>=140/90<sup>2</sup>');


        for($i=1; $i<=2; $i++)
        {
            ?>
            <tr>
                <td><?php echo $intitule[$i]; ?></td>
                <td align="right"><?php echo round($tpat['tot'][$i]/$total['tot']*100);?>%</Td>

                <?php

                foreach($liste_reg as $reg){
                    echo "<td align='right'>".round($tpat[$reg][$i]/$total[$reg]*100)."%</Td>";
                }

                foreach ($tcabinet as $cab)
                {
                    ?>
                    <td align="right"><?php if($total[$cab]==0)
                {
                    echo "ND</td>";
                }
                else
                {
                    echo round($tpat[$cab][$i]/$total[$cab]*100);?>%</td>
                    <?
                }


                }
                ?>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td>Manquant<sup>3</sup></td>
            <td align='right'><?php echo round($tpat['tot'][0]/$total['tot']*100);?>%</td>

            <?php

            foreach($liste_reg as $reg){
                echo "<td align='right'>".round($tpat[$reg][0]/$total[$reg]*100)."%</td>";
            }

            foreach ($tcabinet as $cab)
            {
                ?>
                <td align="right"><?php if($total[$cab]==0)
            {
                echo "ND</td>";
            }
            else
            {
                echo round($tpat[$cab][0]/$total[$cab]*100);?>%</td>
                <?
            }

            }
            ?>

        </tr>



        <?
        $intitule2=array('Manquant', 'nb<140/90<sup>4</sup>', 'nb >=140/90<sup>5</sup>');

        for($i=1; $i<=2; $i++)
        {
            ?>
            <tr>
                <td><?php echo $intitule2[$i]; ?></td>
                <td align="right"><?php echo $tpat['tot'][$i];?></Td>

                <?php

                foreach($liste_reg as $reg){
                    echo "<td align='right'>".$tpat[$reg][$i]."</Td>";
                }

                foreach ($tcabinet as $cab)
                {
                    ?>
                    <td align="right"><?php if($total[$cab]==0)
                {
                    echo "ND</td>";
                }
                else
                {
                    echo $tpat[$cab][$i];?></td>
                    <?
                }


                }
                ?>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td>nb Manquant<sup>6</sup></td>
            <td align='right'><?php echo $tpat['tot'][0];?></td>

            <?php

            foreach($liste_reg as $reg){
                echo "<td align='right'>".$tpat[$reg][0]."</td>";
            }

            foreach ($tcabinet as $cab)
            {
                ?>
                <td align="right"><?php if($total[$cab]==0)
            {
                echo "ND</td>";
            }
            else
            {
                echo $tpat[$cab][0];?></td>
                <?
            }

            }
            ?>

        </tr>

    </table>
    <br><br>
    <?php

}


?>
</body>
</html>
