
<table border='0'  width='70%'>
<tr>
<td width="100%">
  <caption> 
  <big><b><font color='#FF00FF'><b>Test IADL - INSTRUMENTAL ACTIVITIES OF DAILY LIVING</b></font></b></big>
  </caption>
  </Td>
  </tr>
  <tr>
  <td width="100%">
   <b>Capacité à utiliser le téléphone:</b><br>
   <table border='1'  width='100%'>
  <tr> 
   <?php if($TroubleCognitif->iadl_telephone=='tout'){  ?>
    <td width='98%'>Je me sers du téléphone de ma propre initiative, cherche et compose les numéros</td>
    
 <?php }

 	elseif($TroubleCognitif->iadl_telephone=="qq_no"){  ?>
    <td>Je compose un petit nombre de numéros bien connus</td>

<?php }

	elseif($TroubleCognitif->iadl_telephone=="repond"){  ?>
    <td>Je réponds au téléphone mais n'appelle pas</td>

<?php }
	elseif($TroubleCognitif->iadl_telephone=="rien"){  ?>
    <td>Je suis incapable d'utiliser le téléphone</td>
<?php }
?>
  </tr>
  </table>
   <b>Moyen de transport</b><br>

   <table border='1'  width='100%'>
  <tr>
 <?php if($TroubleCognitif->iadl_transport=="tout"){  ?>
 	 <td width='98%'>Je peux voyager seul(e) de façon indépendante (par les transports en commun ou avec ma propre voiture)</td>

<?php }
	elseif($TroubleCognitif->iadl_transport=="taxi_seul"){  ?>

    <td>Je peux voyager seul(e) en taxi, pas en autobus</td>

 <?php }
 	elseif($TroubleCognitif->iadl_transport=="commun_acc"){  ?>
    <td>Je peux prendre les transports en commun si je suis accompagné(e)</td>

 <?php }

 	elseif($TroubleCognitif->iadl_transport=="voiture_acc"){  ?>
    <td>Transport limité au taxi ou à la voiture en étant accompagné(e)</td>

 <?php }
 	elseif($TroubleCognitif->iadl_transport=="rien"){  ?>
    <td>Je ne me deplace pas du tout</td>
<?php }?>

  </tr>
  </table>
  
   <b>Responsabilité pour la prise des médicaments</b><br>

   <table border='1'  width='100%'>
  <tr>
   <?php if($TroubleCognitif->iadl_med=="tout"){  ?>
    <td width='98%'>Je m'occupe moi-même de la prise : dosage et horaire</td>

	<?php }

	elseif($TroubleCognitif->iadl_med=="prend_seul"){  ?>
    <td>Je peux les prendre moi-même s'ils sont préparés et dosés</td>

 	<?php }
	 	elseif($TroubleCognitif->iadl_med=="rien"){  ?>
    <td>Je suis incapable de les prendre moi-même</td>
<?php }
?>
  </tr>
  </table>
     <b>Capacité à gérer son budget</b><br>

   <table border='1'  width='100%'>
  <tr>
  <?php if($TroubleCognitif->iadl_budget=="tout"){  ?>
    <td width='98%' colspan="2">Je suis totalement autonome (gérer le budget, faire des chèques, payer des factures)</td>
    
    <?php }

	elseif($TroubleCognitif->iadl_budget=="jour"){  ?>
    <td colspan="2">Je me débrouille pour les dépenses au jour le jour, mais j'ai besoin d'aide pour gérer mon budget à long terme (pour planifier les grosses dépenses)</td>

	<?php }
		elseif($TroubleCognitif->iadl_budget=="rien"){  ?>
    <td colspan="2">Je suis incapable de gérer l'argent nécessaire à payer mes dépenses au jour le jour</td>
<?php }
?>
  </tr>

  <tr>
    <td width='98%'>Score : </td>
    <td><?php echo $TroubleCognitif->get_iadl();?></td>
  </tr>
</td>
</table>
</table>

<br><br>
