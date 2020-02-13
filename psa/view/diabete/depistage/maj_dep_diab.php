<?php

mysql_connect('localhost','root','') or
   die("Impossible de se connecter au SGBD");
mysql_select_db('isas3') or
   die("Impossible de se connecter à la base");
$mysql_connecte=true;

set_time_limit(0);

$req="SELECT id, date, poids, surpoids, parent_diabetique_type2, ant_intolerance_glucose, hypertension_arterielle, ". 
	 "dyslipidemie_en_charge, hdl, bebe_sup_4kg, ant_diabete_gestationnel, corticotherapie, infection, ".
	 "intervention_chirugicale, autre, derniere_gly_date, derniere_gly_resultat, prescription_gly, ".
	 "nouvelle_gly_date, nouvelle_gly_resultat, note_gly, mesure_suivi_diabete, ".
	 "mesure_suivi_hygieno_dietetique, mesure_suivi_controle_annuel, date_format(dmaj, '%Y-%m-%d') ".
	 "FROM depistage_diabete WHERE nouvelle_gly_date>'0000-00-00'";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

while(list($id, $date, $poids, $surpoids, $parent_diabetique_type2, $ant_intolerance_glucose, $hypertension_arterielle,  
	 $dyslipidemie_en_charge, $hdl, $bebe_sup_4kg, $ant_diabete_gestationnel, $corticotherapie, $infection, 
	 $intervention_chirugicale, $autre, $derniere_gly_date, $derniere_gly_resultat, $prescription_gly, 
	 $nouvelle_gly_date, $nouvelle_gly_resultat, $note_gly, $mesure_suivi_diabete, 
	 $mesure_suivi_hygieno_dietetique, $mesure_suivi_controle_annuel, $dmaj)=mysql_fetch_row($res)){
	 	
	$req2="SELECT * FROM depistage_diabete WHERE id='$id' and `date`='$dmaj'";
	$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
	
	while(mysql_num_rows($res2)>0){
		list($annee, $mois,$jour)=explode("-", $dmaj);
		if($jour>15){
			$jour--;
		}
		else{
			$jour++;
		}
		$dmaj="$annee-$mois-$jour";

		$req2="SELECT * FROM depistage_diabete WHERE id='$id' and `date`='$dmaj'";
		$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
		
	}
	
	$req2="INSERT INTO depistage_diabete SET id='$id', date='$dmaj', poids='$poids', surpoids='$surpoids', ".
		  "parent_diabetique_type2='$parent_diabetique_type2', ant_intolerance_glucose='$ant_intolerance_glucose', ".
		  "hypertension_arterielle='$hypertension_arterielle', dyslipidemie_en_charge='$dyslipidemie_en_charge', ".
		  "hdl='$hdl', bebe_sup_4kg='$bebe_sup_4kg', ant_diabete_gestationnel='$ant_diabete_gestationnel', ".
		  "corticotherapie='$corticotherapie', infection='$infection', intervention_chirugicale='$intervention_chirugicale', ".
		  "autre='$autre', derniere_gly_date='$nouvelle_gly_date', derniere_gly_resultat='$nouvelle_gly_resultat', ".
		  "prescription_gly='$prescription_gly', note_gly='".addslashes(stripslashes($note_gly))."', mesure_suivi_diabete='$mesure_suivi_diabete', ". 
		  "mesure_suivi_hygieno_dietetique='$mesure_suivi_hygieno_dietetique', ".
		  "mesure_suivi_controle_annuel='$mesure_suivi_controle_annuel'";  
	mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
}
echo "terminé";
