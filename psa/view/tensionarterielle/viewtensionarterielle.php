<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier;  ?>
<?php global $tensionArterielleManagement; echo "<br/>";  ?>

<?php 

	//date_add(date_debut, interval (nbjours-1) day)
    $req="SELECT date_format(`date`, '%d/%m/%Y') as `date`, momment_journee, indice, systole, diastole ".
         "FROM tension_arterielle WHERE id = '$dossier->id' ".
         "AND date BETWEEN '".$tensionArterielleManagement->dateDebut."' AND '".$tensionArterielleManagement->dateFin."' ORDER BY `date`, momment_journee, indice";
   // echo "<br/>$req<br>";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
//echo $req;
 ?> 
      <table border="1" width="500"  cellpadding='3'> 
        <caption> 
        <b>mesures saisies</b> (en mmHg) 
        </caption> 
        <tr> 
          <th rowspan=2>jour</th> 
          <th rowspan=2>moment</th> 
          <th colspan=2>1&deg; mesure</th> 
          <th colspan=2>2&deg; mesure</th> 
          <th colspan=2>3&deg; mesure</th> 
        </tr> 
        <tr> 
          <th>Sys</th> 
          <th>Dia</th> 
          <th>Sys</th> 
          <th>Dia</th> 
          <th>Sys</th> 
          <th>Dia</th> 
        </tr> 
<?php	

       $date0=$moment0='';	       
       while($ligne=mysql_fetch_assoc($res)) //ligne[date, momment_journee , indice, systole, diastole]
       {
       		if ($date0<>$ligne['date'])
       		{
?>
	       	     <tr>
	       		<td rowspan=2><?php echo $ligne['date']; ?></td>
	       		<td>matin</td>
	       		<td><?php echo $ligne['systole']."</td><td>". $ligne['diastole']."</td>";
	       		
	       		$date0=$ligne['date'];
	       		$moment0=$ligne['momment_journee'];
	       		
       		}
       		
       		elseif ($moment0<>$ligne['momment_journee'])
       		{
       			   ?>
       		     <tr>
       		     	<td>soir</td>
       		     	<td><?php echo $ligne['systole']."</td><td>". $ligne['diastole']."</td>";
	       		
	       		$date0=$ligne['date'];
	       		$moment0=$ligne['momment_journee'];
	       		
	       	}
	       	elseif ($ligne['indice']=='2')
	       	{
	       		?>
	       		
	       		<td><?php echo $ligne['systole']."</td><td>". $ligne['diastole'];?> </td> 
	       	     </tr>
	       	     <?php
	       	}
	       	
	       	elseif ($ligne['indice']=='1')    
       		{
	       		?>
	       		
	       		<td><?php echo $ligne['systole']."</td><td>". $ligne['diastole']."</td>";
	       	   
	       	}      
       	
       	
       	  
       }
 ?>
		</table> 
      <br> 
	  
	
	  
