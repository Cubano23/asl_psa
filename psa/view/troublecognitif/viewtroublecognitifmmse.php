
<table border='1'  width='70%'> 
  <caption> 
  <big><b><font color='blue'>MMSE</font></b></big>
  </caption> 
  <tr>
    <td>Question</td>
        <td>r�ponse</td>
  </tr>
  <tr> 
    <td>En quelle ann�e sommes-nous?</td>
    <td><?php echo $TroubleCognitif->mmse_annee=='1'?"oui":"non" ?></td>
  </tr> 
  <tr>
    <td>En quelle saison ?</td>
    <td><?php echo $TroubleCognitif->mmse_saison=="1"?"oui":"non" ?></td>
  </tr>
  <tr>
    <td>En quel mois ?</td>
    <td><?php echo $TroubleCognitif->mmse_mois=="1"?"oui":"non" ?></td>
  </tr>
  <tr>
    <td>Quel jour du mois</td>
    <td><?php echo $TroubleCognitif->mmse_jour_mois=="1"?'oui':'non' ?></td>
  </tr>
  <tr>
    <td>Quel jour de la semaine</td>
    <td><?php echo $TroubleCognitif->mmse_jour_semaine=='1'?"oui":"non"?></td>
  </tr>
  <tr>
    <td>Quel est le nom de l'h�pital o� nous sommes (ou Quel est le nom de l'h�pital de la ville d'o� vous venez ou Quel le nom du m�decin que vous avez vu ?)</td>
    <td><?php echo $TroubleCognitif->mmse_nom_hop=='1'?"oui":"non" ?></td>
  </tr>
  <tr>
    <td>Dans quelle ville se trouve t-il ?</td>
    <td><?php echo $TroubleCognitif->mmse_nom_ville=='1'?"oui":"non" ?></td>
  </tr>
  <tr>
    <td>Quel est le nom du D�partement dans lequel est situ�e cette ville ?</td>
    <td><?php echo $TroubleCognitif->mmse_nom_dep=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Dans quelle R�gion est situ�e ce D�partement (si le nom de la r�gion est identique � celui du d�partement) Dans quel Pays est situ� ce D�partement ?</td>
    <td><?php echo $TroubleCognitif->mmse_region=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>A quel �tage sommes-nous ici ?</td>
    <td><?php echo $TroubleCognitif->mmse_etage=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>R�p�tez Cigare</td>
    <td><?php echo $TroubleCognitif->mmse_cigare1=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>R�p�tez Fleur</td>
    <td><?php echo $TroubleCognitif->mmse_fleur1=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>R�p�tez Porte</td>
    <td><?php echo $TroubleCognitif->mmse_porte1=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Comptez � partir de 100 en retranchant 7 � chaque fois (93)</td>
    <td><?php echo $TroubleCognitif->mmse_93=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Comptez � partir de 100 en retranchant 7 � chaque fois (86)</td>
    <td><?php echo $TroubleCognitif->mmse_86=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Comptez � partir de 100 en retranchant 7 � chaque fois (79)</td>
    <td><?php echo $TroubleCognitif->mmse_79=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Comptez � partir de 100 en retranchant 7 � chaque fois (72)</td>
    <td><?php echo $TroubleCognitif->mmse_72=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Comptez � partir de 100 en retranchant 7 � chaque fois (65)</td>
    <td><?php echo $TroubleCognitif->mmse_65=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Epelez le mot MONDE � l'envers (EDNOM) (indiquez les lettres �pel�es)</td>
    <td><?php echo $TroubleCognitif->mmse_monde; ?></td>
  </tr>
  <tr>
    <td>Retrouvez le mot r�p�t� auparavant (CIGARE)</td>
    <td><?php echo $TroubleCognitif->mmse_cigare2=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Retrouvez le mot r�p�t� auparavant (FLEUR)</td>
    <td><?php echo $TroubleCognitif->mmse_fleur2=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Retrouvez le mot r�p�t� auparavant (PORTE)</td>
    <td><?php echo $TroubleCognitif->mmse_porte2=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Nom de cet objet (un crayon)?</td>
    <td><?php echo $TroubleCognitif->mmse_crayon=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Nom de cet objet (montre) ?</td>
    <td><?php echo $TroubleCognitif->mmse_montre=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Ecoutez bien et r�p�tez apr�s moi : "pas de mais, de si, ni de et"</td>
    <td><?php echo $TroubleCognitif->mmse_repete_phrase=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Ecoutez bien et faites ce que je vais vous dire "Prenez cette feuille de papier avec la main droite"</td>
    <td><?php echo $TroubleCognitif->mmse_feuille_prise=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Pliez-l� en deux</td>
    <td><?php echo $TroubleCognitif->mmse_feuille_pliee=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Et jetez-l� par terre</td>
    <td><?php echo $TroubleCognitif->mmse_feuille_jetee=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Faites ce qui est �crit (feuille sur laquelle est �crit "fermez les yeux")</td>
    <td><?php echo $TroubleCognitif->mmse_fermer_yeux=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Voulez-vous m'�crire une phrase, ce que vous voulez mais une phrase enti�re. Cette phrase doit avoir un sens ?</td>
    <td><?php echo $TroubleCognitif->mmse_ecrit_phrase=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Voulez-vous recopier ce dessin?</td>
    <td><?php echo $TroubleCognitif->mmse_copie_dessin=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Score : </td>
    <td><?php echo $TroubleCognitif->get_mmse(); ?></td>
  </tr>

</table>

<br><br>
