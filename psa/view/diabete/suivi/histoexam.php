<?php global $liste_resultats; ?>
<?php global $affiche_resultat; ?>


 <table border=1>
  <tr align='center'>
    <th>Date</td>
	<?php
	if($affiche_resultat==1){
		echo "<th>Valeurs</td>";
	}
	?>
  </tr>
  </tr>
  <?php

    for($j=0;$j<count($liste_resultats);$j++){

  		$tmphisto = $liste_resultats[$j]; ?>

	  <tr>
	    <td>
	        <?php echo $tmphisto->date;?>
		</td>
	<?php
	if($affiche_resultat==1){
		echo "<td>".htmlentities($tmphisto->valeur)."</td>";
	}	
	?>
		</Tr>
<?php
    }
    ?>
    </Table>
