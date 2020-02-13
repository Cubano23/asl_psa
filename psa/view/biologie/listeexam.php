<?php global $liste_dates; ?>
<?php global $liste_exam; ?>
<?php 

foreach($liste_exam as $liste){
	global $$liste; 
}	

$libelle_exam=array("HBA1c"=>"HBA1c", "glycemie"=>"Glycémie", "Chol"=>"Cholestérol total", 
					"HDL"=>"HDL", "LDL"=>"LDL", "triglycerides"=>"Triglycérides", 
					"creat"=>"Créatinine", "kaliemie"=>"Kaliémie", "poids"=>"Poids", 
					"systole"=>"Systole", "diastole"=>"Diastole", 
					"albu"=>"Micro-albuminurie", "hematurie"=>"Hématurie", "proteinurie"=>"Protéinurie",
					"ECG"=>"ECG", "pied"=>"Examen des pieds", 
					"monofil"=>"Examen au monofilament", "pouls"=>"Pouls", "fond"=>"Fond d'oeil", 
					"dent"=>"Dentiste", "spirometrie"=>"Spirométrie"					 
				  	);

$date_seule=array("dent", "pied", "monofil");
$patho=array("albu", "fond", "proteinurie", "hematurie", "ECG");
?>
<?php require("view/common/dossierresume.php");?>
<br>
<input type='button' onclick='document.getElementById("annee").style.display="";document.getElementById("tout").style.display="none";' value='afficher les examens des 12 derniers mois'>&nbsp;&nbsp;
<input type='button' onclick='document.getElementById("tout").style.display="";document.getElementById("annee").style.display="none"' value='afficher tous les examens1'>
 <table border=1 STYLE='display:none' bgcolor=white id='tout'>
  <tr align='center'>
    <th>Date</td>
	<?php
	
	foreach($liste_dates as $date){
		$date=explode("-", $date);
		echo "<th>".$date[2]."/".$date[1]."/".$date[0]."</th>";
	}

	?>
  </tr>
  </tr>
  <?php
    foreach($libelle_exam as $code=>$libelle){
		$liste=$liste_exam[$code];
		$liste=$$liste;
		
		if(count($liste)>0){//La liste pour cet exam est non vide
			echo "<tr><td><b>$libelle</b></td>";
			
			foreach($liste_dates as $date){
				if(isset($liste[$date])){//L'exam a été fait à la date
					$exam=$liste[$date];
					if(in_array($code, $date_seule)){
						echo "<td><a href='ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AF&Dossier:dossier:id=$dossier->id&Biologie:Biologie:numero=$exam->numero' >Réalisé</A></td>";
					}
					elseif(in_array($code, $patho)){
						if($exam->resultat1==1){
							echo "<td><a href='ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AF&Dossier:dossier:id=$dossier->id&Biologie:Biologie:numero=$exam->numero' >Pathologique</a></td>";
						}
						else{
							echo "<td><a href='ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AF&Dossier:dossier:id=$dossier->id&Biologie:Biologie:numero=$exam->numero' >Non pathologique</a></td>";
						}
					}
					else{
						echo "<td><a href='ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AF&Dossier:dossier:id=$dossier->id&Biologie:Biologie:numero=$exam->numero'>";
							if($exam->type_exam=='spirometrie') echo $exam->resultat1.' %'; else echo $exam->resultat1; 
						echo "</a></td>";
					}
				}
				else{
					echo "<td>&nbsp;</td>";
				}
			}
			echo "</tr>";
		}
		
	}
    ?>
    </Table>

 <table border=1 id='annee' bgcolor=white>
  <tr align='center'>
    <th>Date</td>
	<?php
	
	$date1an=date("Y-m-d", mktime(1, 1, 1, date("m"), date("d"), date("Y")-1));
	foreach($liste_dates as $date){
		if($date>=$date1an){
			$date=explode("-", $date);
			echo "<th>".$date[2]."/".$date[1]."/".$date[0]."</th>";
		}
	}

	?>
  </tr>
  <?php

    foreach($libelle_exam as $code=>$libelle){
		$liste=$liste_exam[$code];
		$liste=$$liste;

		
		if(count($liste)>0){//La liste pour cet exam est non vide
			echo "<tr><td><b>$libelle</b></td>";
			
			foreach($liste_dates as $date){
				if($date>=$date1an){
					if(isset($liste[$date])){//L'exam a été fait à la date
						$exam=$liste[$date];
						if(in_array($code, $date_seule)){
							echo "<td><a href='ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AF&Dossier:dossier:id=$dossier->id&Biologie:Biologie:numero=$exam->numero' >Réalisé</A></td>";
						}
						elseif(in_array($code, $patho)){
							if($exam->resultat1==1){
								echo "<td><a href='ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AF&Dossier:dossier:id=$dossier->id&Biologie:Biologie:numero=$exam->numero' >Pathologique</a></td>";
							}
							else{
								echo "<td><a href='ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AF&Dossier:dossier:id=$dossier->id&Biologie:Biologie:numero=$exam->numero' >Non pathologique</a></td>";
							}
						}
						else{
							echo "<td><a href='ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AF&Dossier:dossier:id=$dossier->id&Biologie:Biologie:numero=$exam->numero'>";
										if($exam->type_exam=='spirometrie') echo $exam->resultat1.' %'; else echo $exam->resultat1; 
							echo "</a></td>";
						}
					}
					else{
						echo "<td>&nbsp;</td>";
					}
				}
			}
			echo "</tr>";
		}
		
	}
    ?>
    </Table>
<!--<br><br><a href="#" style='color:black' onclick="ajax_hideTooltip()">Fermer</a>-->