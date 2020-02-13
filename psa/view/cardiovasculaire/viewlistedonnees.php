<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $rowsList;?>

<script type="text/javascript" >

</SCRIPT>


<?php
$couleurs = array(""=>"",
				  "vert"=>"green",
				  "orange"=>"orange",
				  "rouge"=>"red"); 
				  
$valeurs = array(""=>"",
				  "vert"=>"La rubrique existe dans le logiciel et est renseign�e",
				  "orange"=>"La rubrique existe mais n'est pas ou peu renseign�e",
				  "rouge"=>"La rubrique n'existe pas"); 
				  ?> 
  
  <table border=0>
  	<tr>
  		<td width='50'>
  			<table border=1 width='100%'>
  				<tr>
  					<td width='100%' style='background:green'>&nbsp;</Td>
  				</Tr>
  			</Table>
  		</Td>
		<td>La rubrique existe dans le logiciel et est renseign�e</td>
  		<td width='50'>
  			<table border=1 width='100%'>
  				<tr>
  					<td width='100%' style='background:orange'>&nbsp;</Td>
  				</Tr>
  			</Table>
  		</Td>
		<td>La rubrique existe mais n'est pas ou peu renseign�e</td>
  		<td width='50'>
  			<table border=1 width='100%'>
  				<tr>
  					<td width='100%' style='background:red'>&nbsp;</Td>
  				</Tr>
  			</Table>
  		</Td>
		<td>La rubrique n'existe pas</td>
  		<td width='50'>
  			<table border=1 width='100%'>
  				<tr>
  					<td width='100%' >&nbsp;</Td>
  				</Tr>
  			</Table>
  		</Td>
		<td>Pas d'information</td>
  	</tr>
</table>
<br>				  
<table border='1'>
	<tr>
		<td></td>

<?php
	foreach($rowsList as $liste){
		echo "<td>".$liste['nom_cab'].'</td>';
	}				   
?>
	</tr>
  	<tr>
  		<td width='300'>Ant�c�dents familiaux du premier degr� </Td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['antecedants']]."'>&nbsp;</td>";
	}				   
?>

	</tr>
	<tr>
		<td colspan='19'><b>Bilan lipidique</b></td>
	</tr>
  	<tr>
  		<td width='300'>Date Cholest�rol total</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['dChol']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
  	<tr>
  		<td width='300'>R�sultat Cholest�rol total</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['Chol']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
	
  	<tr>
  		<td width='300'>Date HDL</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['dHDL']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
  	<tr>
  		<td width='300'>R�sultat HDL</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['HDL']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
	
  	<tr>
  		<td width='300'>Date LDL</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['dLDL']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
  	<tr>
  		<td width='300'>R�sultat LDL</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['LDL']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
  	<tr>
  		<td width='300'>Date triglyc�rides</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['dtriglycerides']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
  	<tr>
  		<td width='300'>R�sultat triglyc�rides</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['triglycerides']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
	<tr>
		<td width='300'>Traitement hypolipid�miant m�dicamenteux</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['traitement']]."'>&nbsp;</td>";
	}				   
?>
	</tr>

	<tr>
		<td width='300'>Dosage du traitement</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['dosage']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
	<tr>
		<td colspan='19'><b>Tension</b></td>
	</tr>

  	<tr>
  		<td width='300'>HTA </Td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['HTA']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
  	<tr>
  		<td width='300'>Systole </Td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['TaSys']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
  	<tr>
  		<td width='300'>Diastole </Td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['TaDia']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
  	<tr>
  		<td width='300'>Date de la tension </Td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['dTA']]."'>&nbsp;</td>";
	}				   
?>
	</tr>

	<tr>
		<td width='300'>Trois Traitements hypertenseurs ou plus ?</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['hypertenseur3']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
	<tr>
		<td width='300'>Pr�sence d'une automesure</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['automesure']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
	<tr>
		<td width='300'>Pr�sence d'un diur�tique</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['diuretique']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
	<tr>
		<td width='300'>Echocardiogramme Hypertrophie Ventriculaire Gauche</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['HVG']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
	<tr>
		<td width='300'>A d�faut surcharge ventriculaire Gauche</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['surcharge_ventricule']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
	<tr>
		<td width='300'>Sokolov</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['sokolov']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
	<tr>
		<td width='300'>Date du Sokolov</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['dsokolov']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
	<tr>
		<td colspan='19'><b>Examens compl�mentaires</b></td>
	</tr>
	<tr>
    <td width='300'>Cr�atinine</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['Creat']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
	<tr>
    <td width='300'>Date de la Cr�atinine</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['dCreat']]."'>&nbsp;</td>";
	}				   
?>
	</tr>

	<tr>
    <td width='300'>Kali�mie</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['kaliemie']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
	<tr>
    <td width='300'>Date kali�mie</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['dkaliemie']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
	<tr>
    <td width='300'>Prot�inurie (positif/n�gatif)</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['proteinurie']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
	<tr>
    <td width='300'>Date Prot�inurie</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['dproteinurie']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
	<tr>
    <td width='300'>H�maturie (positif/n�gatif)</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['hematurie']]."'>&nbsp;</td>";
	}				   
?>
	</tr>

	<tr>
    <td width='300'>Date H�maturie</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['dhematurie']]."'>&nbsp;</td>";
	}				   
?>
	</tr>

	<tr>
    <td width='300'>Fond d'&oelig;il</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['dFond']]."'>&nbsp;</td>";
	}				   
?>
	</tr>

	<tr>
    <td width='300'>ECG</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['dECG']]."'>&nbsp;</td>";
	}				   
?>
	</tr>

	<tr>
		<td colspan='19'><b>Mode de vie</b></td>
	</tr>

	<tr>
    <td width='300'>Tabagisme (oui/non)</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['tabac']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
	<tr>
    <td width='300'>Date d'arr�t du tabac</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['darret']]."'>&nbsp;</td>";
	}				   
?>
	</tr>


	<tr>
    <td width='300'>Poids</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['poids']]."'>&nbsp;</td>";
	}				   
?>
	</tr>

	<tr>
    <td width='300'>Date Poids</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['dpoids']]."'>&nbsp;</td>";
	}				   
?>
	</tr>

	<tr>
    <td width='300'>Activit� physique</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['activite']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
	<tr>
    <td width='300'>Fr�quence cardiaque</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['pouls']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
	<tr>
    <td width='300'>Date Fr�quence cardiaque</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['dpouls']]."'>&nbsp;</td>";
	}				   
?>
	</tr>

	<tr>
    <td width='300'>Alcool</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['alcool']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
	<tr>
		<td colspan='19'><b>Facteurs associ�s � prendre en charge</b></td>
	</tr>
	<tr>
    <td width='300'>Glyc�mie</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['glycemie']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
	<tr>
    <td width='300'>Date Glyc�mie</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['dgly']]."'>&nbsp;</td>";
	}				   
?>
	</tr>

	<tr>
    <td width='300'>Examen Cardio-Vasculaire</td>
<?php
	foreach($rowsList as $liste){
		echo "<td style='background:".$couleurs[$liste['exam_cardio']]."'>&nbsp;</td>";
	}				   
?>
	</tr>
  </table>
  <br>
