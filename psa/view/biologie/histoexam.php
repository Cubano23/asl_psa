<?php global $liste_resultats; ?>
<?php global $affiche_resultat; ?>
<?php 

if($liste_resultats[0]->type_exam=='spirometrie')$affiche_resultat=2; else $affiche_resultat=1;

?>

 <table border=1>
  <tr align='center'>
    <th>Date</td>
	<?php
	if($affiche_resultat==1){
		echo "<th>Valeurs</th>";
	}
	elseif($affiche_resultat==2){
		echo "<th>VEMS/CVF</th>";
	}
	if(!empty($liste_resultats[0]->resultat2)){
		echo "<th>Interpr&eacute;tation</th>";
	}
	?>
  </tr>
  </tr>
  <?php

    for($j=0;$j<count($liste_resultats);$j++){

  		$tmphisto = $liste_resultats[$j]; ?>

	  <tr>
	    <td>
	        <?php echo $tmphisto->date_exam;?>
		</td>
	<?php
	if($affiche_resultat==1 ){
		echo "<td>".htmlentities($tmphisto->resultat1)."</td>";
	}
	if($affiche_resultat==2){
		echo "<td>".htmlentities(round($tmphisto->resultat1), 2)." %</td>";
	}	
	if(!empty($liste_resultats[0]->resultat2)){
		echo "<td>";
		if($tmphisto->resultat2=='a')echo "anormale";
		elseif($tmphisto->resultat2=='n')echo "normale";
		else echo $tmphisto->resultat2;
		echo "</td>";
	}
	?>
		</Tr>
<?php
    }
    ?>
    </Table>
