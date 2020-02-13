
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
   <b>Capacit� � utiliser le t�l�phone:</b><br>
   <table border='1'  width='100%'>
  <tr> 
   <?php if($TroubleCognitif->iadl_telephone=='tout'){  ?>
    <td width='98%'>Je me sers du t�l�phone de ma propre initiative, cherche et compose les num�ros</td>
    
 <?php }

 	elseif($TroubleCognitif->iadl_telephone=="qq_no"){  ?>
    <td>Je compose un petit nombre de num�ros bien connus</td>

<?php }

	elseif($TroubleCognitif->iadl_telephone=="repond"){  ?>
    <td>Je r�ponds au t�l�phone mais n'appelle pas</td>

<?php }
	elseif($TroubleCognitif->iadl_telephone=="rien"){  ?>
    <td>Je suis incapable d'utiliser le t�l�phone</td>
<?php }
?>
  </tr>
  </table>
   <b>Moyen de transport</b><br>

   <table border='1'  width='100%'>
  <tr>
 <?php if($TroubleCognitif->iadl_transport=="tout"){  ?>
 	 <td width='98%'>Je peux voyager seul(e) de fa�on ind�pendante (par les transports en commun ou avec ma propre voiture)</td>

<?php }
	elseif($TroubleCognitif->iadl_transport=="taxi_seul"){  ?>

    <td>Je peux voyager seul(e) en taxi, pas en autobus</td>

 <?php }
 	elseif($TroubleCognitif->iadl_transport=="commun_acc"){  ?>
    <td>Je peux prendre les transports en commun si je suis accompagn�(e)</td>

 <?php }

 	elseif($TroubleCognitif->iadl_transport=="voiture_acc"){  ?>
    <td>Transport limit� au taxi ou � la voiture en �tant accompagn�(e)</td>

 <?php }
 	elseif($TroubleCognitif->iadl_transport=="rien"){  ?>
    <td>Je ne me deplace pas du tout</td>
<?php }?>

  </tr>
  </table>
  
   <b>Responsabilit� pour la prise des m�dicaments</b><br>

   <table border='1'  width='100%'>
  <tr>
   <?php if($TroubleCognitif->iadl_med=="tout"){  ?>
    <td width='98%'>Je m'occupe moi-m�me de la prise : dosage et horaire</td>

	<?php }

	elseif($TroubleCognitif->iadl_med=="prend_seul"){  ?>
    <td>Je peux les prendre moi-m�me s'ils sont pr�par�s et dos�s</td>

 	<?php }
	 	elseif($TroubleCognitif->iadl_med=="rien"){  ?>
    <td>Je suis incapable de les prendre moi-m�me</td>
<?php }
?>
  </tr>
  </table>
     <b>Capacit� � g�rer son budget</b><br>

   <table border='1'  width='100%'>
  <tr>
  <?php if($TroubleCognitif->iadl_budget=="tout"){  ?>
    <td width='98%' colspan="2">Je suis totalement autonome (g�rer le budget, faire des ch�ques, payer des factures)</td>
    
    <?php }

	elseif($TroubleCognitif->iadl_budget=="jour"){  ?>
    <td colspan="2">Je me d�brouille pour les d�penses au jour le jour, mais j'ai besoin d'aide pour g�rer mon budget � long terme (pour planifier les grosses d�penses)</td>

	<?php }
		elseif($TroubleCognitif->iadl_budget=="rien"){  ?>
    <td colspan="2">Je suis incapable de g�rer l'argent n�cessaire � payer mes d�penses au jour le jour</td>
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
