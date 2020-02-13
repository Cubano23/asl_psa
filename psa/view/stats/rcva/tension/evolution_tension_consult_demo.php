<?php
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
//$serveur = "localhost"; $user = "root"; $mdp = "root"; $db = "isas";
$serveur = "localhost"; $user = "informed"; $mdp = "no11iugX"; $db = "informed3";
mysql_connect($serveur,$user,$mdp) or die("Impossible de se connecter au SGBD");
mysql_select_db($db) or die("Impossible de se connecter à la base");
etape_1();
?>

<br><br>
<?

//affichage tableau
function etape_1() {
	
global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $regions, $liste_reg;


$req="SELECT dossier.cabinet, count(*), nom_cab, region ".
		 "FROM dossier, account ".
		 "WHERE infirmiere!='' and region!='' ".
		 "AND actif='oui' ".
		 "and dossier.cabinet=account.cabinet ".
		 "AND account.cabinet='bourgenbresse' ".
		 "GROUP BY nom_cab ".
		 "ORDER BY nom_cab, numero ";

$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

//if (mysql_num_rows($res)==0) exit ("<p align='center'>Aucun cabinet n'est actif</p>");

$tcabinet=array();
$liste_reg=array();

while(list($cab, $pat, $ville, $region) = mysql_fetch_row($res)) {
	
	$tcabinet[] = $cab;
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
	 
}
// echo '<br>initialisation des tableaux';
// echo '<br>$tcabinet:';
// echo '<pre>'; var_dump($tcabinet); echo '</pre>';
// echo '<br>----------------------------------------';
// echo '<br>$regions';
// echo '<pre>'; var_dump($regions); echo '</pre>';

sort($liste_reg);
$mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
			'11'=>'Novembre', '12'=>'Décembre');
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
		 "AND cabinet='bourgenbresse' ".
		 "ORDER BY cabinet, id, date ";

$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
// echo '<br>'.$req;
$id_prec="";

while(list($cabinet, $id, $date)=mysql_fetch_row($res)){
	// echo '<br>list($cabinet, $id, $date)'.$cabinet.', '.$id.', '.$date;
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
// echo '<br><br>liste des consultations par patient';
// echo '<br>$consult:';
// echo '<pre>'; var_dump($consult); echo '</pre>';



//echo '<br><br>liste des tensions par patient en RCVA';
//Liste des tensions par patient en RCVA
$req="SELECT cabinet, dossier.id, date_exam, resultat1 ".
		 "FROM cardio_vasculaire_depart, dossier, liste_exam ".
		 "WHERE actif='oui' ".
		 "AND cardio_vasculaire_depart.id=dossier.id and dossier.id=liste_exam.id ".
		 "and type_exam='systole' ".
		 "and date_exam>'1990-01-01' ".
		 "AND cabinet='bourgenbresse' ".
		 "GROUP BY cabinet, dossier.id, date_exam ";
		 "ORDER BY cabinet, dossier.id, date_exam ";

$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
// echo '<br>'.$req;
$id_prec="";

while(list($cabinet, $id, $date, $TaSys)=mysql_fetch_row($res)){
	// echo '<br>list($cabinet, $id, $date, $TaSys)'.$cabinet.', '.$id.', '.$date.', '.$TaSys;

	$req2="SELECT resultat1 from liste_exam where id='$id' and type_exam='diastole' and ".
		  "date_exam='$date'";
	$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
	// echo '<br>'.$req2;

	list($TaDia)=mysql_fetch_row($res2);
	// echo '<br>list($TaDia)'.$TaDia;

	if(isset($regions[$cabinet])){
		$id_prec=$id;
		$dossiers[$cabinet][]=$id;
		$dossiers[$regions[$cabinet]][]=$id;
		$cabinets[$id]=$cabinet;
		$liste_tension[$id][$date]=array("TaSys"=>$TaSys, "TaDia"=>$TaDia);
	}
}
//echo '<br>$liste_tension:';
//echo '<pre>'; var_dump($liste_tension); echo '</pre>';


//echo '<br><br>liste des tensions par patient en suivi diabète';
//Liste des tensions par patient en suivi diabète
$req="SELECT cabinet, dossier.id, date_exam, resultat1 ".
		 "FROM suivi_diabete, dossier, liste_exam ".
		 "WHERE actif='oui' ".
		 "AND dossier_id=dossier.id and dossier.id=liste_exam.id ".
		 "and date_exam>'1990-01-01' and type_exam='systole' ".
		 "AND cabinet='bourgenbresse' ".
		 "GROUP BY cabinet, dossier.id, date_exam ";
		 "ORDER BY cabinet, dossier.id, date_exam ";

$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
// echo '<br>'.$req;
$id_prec="";

while(list($cabinet, $id, $date, $TaSys)=mysql_fetch_row($res)){
	// echo '<br>list($cabinet, $id, $date, $TaSys)'.$cabinet.', '.$id.', '.$date.', '.$TaSys;

	$req2="SELECT resultat1 from liste_exam where id='$id' and type_exam='diastole' and ".
		  "date_exam='$date'";
	$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
	// echo '<br>'.$req2;

	list($TaDia)=mysql_fetch_row($res2);
	// echo '<br>list($TaDia)'.$TaDia;

	if(isset($regions[$cabinet])){
		$id_prec=$id;
		$cabinets[$id]=$cabinet;
		$liste_tension[$id][$date]=array("TaSys"=>$TaSys, "TaDia"=>$TaDia);
	}
}
//echo '<br>$liste_tension:';
//echo '<pre>'; var_dump($liste_tension); echo '</pre>';


$array_temp = array();
//$id = '76286';
foreach($consult as $id => $tab_consult){

	//echo "## ID: ".$id;
	//echo "<pre>"; var_dump($consult[$id]); echo "</pre>"; 
	//echo '---------------';
	if(isset($tab_consult) && sizeof($liste_tension[$id]) > 1){
		//echo "<pre>"; var_dump($liste_tension[$id]); echo "</pre>"; 

	
		foreach($tab_consult as $num_consult => $date_consult){
			$array_temp[$id][$num_consult] = array('avant' => array(), 'consult' => $date_consult, 'apres' => array());
			$date_prec = "";
			$date_suiv = "";
			foreach($liste_tension[$id] as $date_tension => $valeur_tension){
				
				if($date_consult < $date_tension){
					$date_suiv = $date_tension;
					break;
				}
				elseif($date_tension > $date_consult){
					//echo '<br> date inférieur : '.$date_tension;
					break;
				}
				$date_prec = $date_tension;
			}
			$array_temp[$id][$num_consult]['avant'] = array('date_tension' => $date_prec, 'values' => $liste_tension[$id][$date_prec]);
			$array_temp[$id][$num_consult]['apres'] = array('date_tension' => $date_suiv, 'values' => $liste_tension[$id][$date_suiv]);

			if($num_consult > 3) break;  // si plus de 4 consultation, on sort, mais attention la dernière est la 5ième, c'est juste pour ranger la prochaine tension
		}
	}
	else{
		//echo "<br> non pris";
	}
	//echo '<hr />';
}
//
//
echo '<pre>'; var_dump($array_temp); echo '</pre>';

$denominateur[1] = 0;
$denominateur[2] = 0;
$denominateur[3] = 0;
$denominateur[4] = 0;
$numerateur[1] = 0;
$numerateur[2] = 0;
$numerateur[3] = 0;
$numerateur[4] = 0;


foreach ($array_temp as $id => $dossier) {
	
	foreach ($dossier as $num_consult => $value) {
		// echo "<br>".$num_consult;
		// echo "<br>avant: ".$value['avant']['values']['TaSys'];
		// echo "<br>après: ".$value['apres']['values']['TaSys'];

		if(intval($dossier[1]['avant']['values']['TaSys']) > 140  && isset($value['apres']['values']['TaSys'])){
			$denominateur[$num_consult]++;
			echo "<br> denominateur: ".$id;
		}

		if(isset($value['apres']['values']['TaSys']) && $value['apres']['values']['TaSys'] <= 140 && $dossier[1]['avant']['values']['TaSys'] > 140){
			$numerateur[$num_consult]++;	
			echo "<br> numerateur: ".$id;
		}
	}
}

$change_taux = array();
for($i = 1; $i <= sizeof($denominateur); $i++){
	$change_taux[$i] = round(($numerateur[$i] / $denominateur[$i]) * 100);
}
echo "<hr />";
echo '<pre>'; var_dump($denominateur); echo '</pre>';
echo '<pre>'; var_dump($numerateur); echo '</pre>';

echo "<hr />";
echo '<pre>'; var_dump($change_taux); echo '</pre>';

exit();

?>

<tr>
<td>Valeurs de la tension</td>
	
	<?php
	
	foreach($liste_reg as $reg){
		echo "<td align='center'><b>moyenne $reg</b></td>";
	}

	foreach ($tcabinet as $cab)
	{
	    ?>
	    <td align="center"><b><?php echo $cab ?></b></td>
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

	echo "<tr>";
	echo "<td>Nb dossiers avec tension &gt; 140/90 avant $i consultation$s<sup>1</sup> </td>";
			
	foreach($liste_reg as $reg){
		echo "<td align='right'>".$dossierssup140[$reg][$i]."</Td>";
	}
	
	foreach ($tcabinet as $cab)
	{
		echo "<td align='right'>".$aSup[$i - 1]."</Td>";
	}
	
	echo "</tr>";
	
	echo "<tr>
		<td>Taux dossiers avec tension &gt; 140/90 avant $i consultation$s et passant &lt;140/90<sup>2</sup> </td>";
			
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
		if($aSup[$i - 1] == 0){
			echo "<td align='right'>ND</Td>";
		}
		else{
			echo "<td align='right'>".round(($aInf[$i - 1]/$aSup[$i - 1])*100)."% (".$aInf[$i - 1].")</Td>";
		}
	}
	
	echo "</tr>";
}


?>
</table>
<sup>1</sup>Nb de dossiers pour lesquels la systole est &gt;=140 ou la diastole est &gt;=90 avant la 1ère consultation et une tension tension après la 1ère, 2ème, 3ème, 4ème consultation<br>
<sup>2</sup>Nb de dossiers pour lesquels la tension était &gt;=140/90 avant la 1ère consultation et passant &lt;140/90 après la 1ère, 2ème, 3ème, 4ème consultation<br>
<?
}


?>
</body>
</html>
