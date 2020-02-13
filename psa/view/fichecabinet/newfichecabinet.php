<?php 

error_reporting(E_ERROR); // EA. Les script ne traite pas des valeurs initiales ce qui génère les Notices 22-12-2014
require_once("bean/beanparser/htmltags.php");
require_once("view/jsgenerator/jsgenerator.php");
require_once("view/common/vars.php");
 ?>

<?php global $account;?>
<?php global $FicheCabinet; ?>
<?php global $param;?>

<script type="text/javascript" >
<?php

	validatePositiveInteger();

	$js = new JSValidation();
	$js->startCheckFunction("validateInput","saveForm");
	$js->validatePositiveInteger("FicheCabinet:total_pat","Nombre total de patients");
	$js->validatePositiveInteger("FicheCabinet:total_sein","Nombre total de patientes éligibles au cancer du sein");
	$js->validatePositiveInteger("FicheCabinet:total_cogni","Nombre total de patients éligibles pour les troubles cognitifs");
	$js->validatePositiveInteger("FicheCabinet:total_colon","Nombre total de patients éligibles pour le cancer du colon") ;
	$js->validatePositiveInteger("FicheCabinet:total_uterus","Nombre total de patientes éligibles pour le cancer de l'utérus");
	$js->validatePositiveInteger("FicheCabinet:total_diab2","Nombre total de patients diabétiques de type 2");
	$js->validatePositiveInteger("FicheCabinet:total_HTA","Nombre total de patients éligibles au suivi HTA");
	$js->endCheckFunction();
	
?>

</script>

<br> 
<form action=<?php echo("'$path/controler/ActionControler.php'"); ?> method='post' name='saveForm'> 
  <?php hiddenControler("FicheCabinetControler"); ?>
  <?php hiddenAction(ACTION_SAVE); ?>
  <?php hidden("","FicheCabinet:cabinet"); ?>
  <?php hidden("","FicheCabinet:region"); //Pierre ajout région hidden pour éviter de suppr les datas en bdd ?>
  <?php hidden("","FicheCabinet:infirmiere"); //Pierre ajout infirnière hidden pour éviter de suppr les datas en bdd ?>
  <?php hiddenParamN($param->param3,3); ?>

<?php
/*	$req="SELECT dmaj, total_pat, total_sein, total_cogni, total_colon, total_uterus, total_diab2 ".
		 "FROM histo_account WHERE cabinet='$FicheCabinet->cabinet' AND dmaj<'2006-04-01' ORDER BY dmaj";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

//echo $req;

	while(list($dmaj2, $total_pat_anc2, $total_sein_anc2, $total_cogni_anc2, $total_colon_anc2, $total_uterus_anc2,
		 $total_diab2_anc2)=mysql_fetch_row($res))
		 {
		    $dmaj=$dmaj2;
			$total_pat_anc=$total_pat_anc2;
			$total_sein_anc=$total_sein_anc2;
			$total_cogni_anc=$total_cogni_anc2;
			$total_colon_anc=$total_colon_anc2;
			$total_uterus_anc=$total_uterus_anc2;
			$total_diab2_anc=$total_diab2_anc2;
		 }
		 */
//		 print_r($FicheCabinet);
?>
	
  <b>Renseignements administratifs1</b>
  <table border=1> 
    <tr> 
      <td>Cabinet</td>
      <td><?php typePropertyValue("FicheCabinet:cabinet"); ?></td>
	</tr>
	<tr>
      <td>Nom complet</td>
      <td><?php text("size='50' ","FicheCabinet:nom_complet"); ?></td>
	</tr>
	<tr>
      <td>Ville</td>
      <td><?php text("size='20' ","FicheCabinet:ville"); ?></td>
	</tr>
	<tr>
      <td>Contact</td>
      <td><?php text("size='30' ","FicheCabinet:contact"); ?></td>
	</tr>
	<tr>
      <td>Téléphone</td>
      <td><?php text("size='15' ","FicheCabinet:telephone"); ?></td>
	</tr>
	<tr>
      <td>Courriel</td>
      <td><?php text("size='30' ","FicheCabinet:courriel"); ?></td>
	</tr>
  </table>
	<br>
  <b>Données de connexion</b>
  <table border=1>
	<tr>
      <td>Mot de passe</td>
      <td><?php password("size='15' ","FicheCabinet:password"); ?></td>
	</tr>
	<tr>
      <td>Retaper mot de passe</td>
      <td><?php password("size='15' ","FicheCabinet:password"); ?></td>
    </tr>
  </table> 
  <br> 
  <b>Informations sur le cabinet</b><br>
  
<?php
$annee0=2006;
$mois0=3;

$annee=date('Y');
$mois=date('m');

$mois--;


$tab_mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
			'08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');


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

$i=0;

while(($annee>$annee0)||(($annee==$annee0)&&($mois>=$mois0)))
{
     $i++;
	    if($mois<10)
	    {
	        $date=$annee.'-0'.$mois.'-'.$jour[$mois];
	    }
	    else
	    {
	        $date=$annee.'-'.$mois.'-'.$jour[$mois];
	    }
//		tableau($date);

	$req="SELECT dmaj, total_pat, total_sein, total_cogni, total_colon, total_uterus, total_diab2, total_HTA ".
		 "FROM histo_account WHERE cabinet='$FicheCabinet->cabinet' AND dmaj<='$date 23:59:59' ORDER BY dmaj";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

//echo $req;

	$tab_date=explode('-', $date); //EA 22-04-2014

	while(list($dmaj2, $total_pat_anc2, $total_sein_anc2, $total_cogni_anc2, $total_colon_anc2, $total_uterus_anc2,
		 $total_diab2_anc2, $total_HTA_anc2)=mysql_fetch_row($res))
		 {
		    $dmaj=$dmaj2;
			$total_pat_anc[$i]=$total_pat_anc2;
			$total_sein_anc[$i]=$total_sein_anc2;
			$total_cogni_anc[$i]=$total_cogni_anc2;
			$total_colon_anc[$i]=$total_colon_anc2;
			$total_uterus_anc[$i]=$total_uterus_anc2;
			$total_diab2_anc[$i]=$total_diab2_anc2;
			$total_HTA_anc[$i]=$total_HTA_anc2;
		 }

		$libelle[$i]="Fin ".$tab_mois[$tab_date[1]]." ".$annee;

		$mois=$mois-3;

		if($mois<=0)
		{
		    $mois=$mois+12;
		    $annee--;
		}

}

?>
  <table border=1>
  <tr>
    <td></td><td></td>
	<?php
	    for($j=1; $j<=$i;$j++)
	    {
			echo "<td><b>".$libelle[$j]."</b></td>";
	    }
	    ?>
  </tr>
    <tr> 
      <td>Nombre total de patients <sup>1</sup></td>
      <td><?php text("size='15' ","FicheCabinet:total_pat"); ?></td><?php for($j=1;$j<=$i;$j++)
	  																	{ echo "<td>".$total_pat_anc[$j]."</td>";
	  																	}
	  																	?>
    </tr>
    <tr>
      <td>Nombre total de patientes éligibles pour le dépistage du cancer du sein <sup>2</sup></td>
      <td><?php text("size='15' ","FicheCabinet:total_sein"); ?></td><?php for($j=1;$j<=$i;$j++)
	  																	{ echo "<td>".$total_sein_anc[$j]."</td>";
	  																	}
	  																	?>
    </tr>
    <tr>
      <td>Nombre total de patients éligibles pour le dépistage des troubles cognitifs <sup>3</sup></td>
      <td><?php text("size='15' ","FicheCabinet:total_cogni"); ?></td><?php for($j=1;$j<=$i;$j++)
	  																	{ echo "<td>".$total_cogni_anc[$j]."</td>";
	  																	}
	  																	?>
    </tr>
    <tr>
      <td>Nombre total de patients éligibles pour le dépistage du cancer du colon <sup>4</sup></td>
      <td><?php text("size='15' ","FicheCabinet:total_colon"); ?></td><?php for($j=1;$j<=$i;$j++)
	  																	{ echo "<td>".$total_colon_anc[$j]."</td>";
	  																	}
	  																	?>
    </tr>
    <tr>
      <td>Nombre total de patientes éligibles pour le dépistage du cancer du col de l'utérus <sup>5</sup></td>
      <td><?php text("size='15' ","FicheCabinet:total_uterus"); ?></td><?php for($j=1;$j<=$i;$j++)
	  																	{ echo "<td>".$total_uterus_anc[$j]."</td>";
	  																	}
	  																	?>
    </tr>
    <tr>
      <td>Nombre total de patients diabétiques de type 2 <sup>6</sup></td>
      <td><?php text("size='15' ","FicheCabinet:total_diab2"); ?></td><?php for($j=1;$j<=$i;$j++)
	  																	{ echo "<td>".$total_diab2_anc[$j]."</td>";
	  																	}
	  																	?>
    </tr>
    <tr>
      <td>Nombre total de patients éligibles au suivi RCVA <sup>7</sup></td>
      <td><?php text("size='15' ","FicheCabinet:total_HTA"); ?></td><?php for($j=1;$j<=$i;$j++)
	  																	{ echo "<td>".$total_HTA_anc[$j]."</td>";
	  																	}
	  																	?>
    </tr>
  </table>

  <table border=0>
  <tr>
    <td><br></td>
  </tr>
    <tr>
      <td><sup>1</sup> Nombre de patients dont un des médecins du cabinet est médecin traitant</td>
    </tr>
    <tr>
      <td><sup>2</sup> Femmes de 50 à 74 ans sans facteur de risque et dont un des médecins du cabinet est médecin traitant</td>
    </tr>
    <tr>
      <td><sup>3</sup> Patients de plus de 75 ans vivant à domicile + patients proposés par les médecins</td>
    </tr>
    <tr>
      <td><sup>4</sup> Patients entre 50 et 74 ans sans facteur de risques</td>
    </tr>
    <tr>
      <td><sup>5</sup> Patientes de 20 à 65 ans sauf patientes à haut risque ni hystérectomisées ni vierges</td>
    </tr>
    <tr>
      <td><sup>6</sup> Nombre de diabétiques de type 2 dont un des médecins est médecin traitant</td>
    </tr>
    <tr>
      <td><sup>7</sup> Nombre de patients dont un des m&eacute;decins est m&eacute;decin traitant ayant au  moins 2 facteurs de risque cardio-vasculaire dont 1 modifiable</td>
    </tr>
  </table>
  <br>

  <input type='button' value='Enregistrer' onClick="validateInput()">
  <input type='reset' value='Recommencer'> 
</form> 
