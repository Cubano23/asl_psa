
<table border='1'  width='70%'> 
  <caption> 
  <big><b><font color='blue'>MMSE</font></b></big>
  </caption> 
  <tr>
    <td>Question</td>
        <td>réponse</td>
  </tr>
  <tr> 
    <td>En quelle année sommes-nous?</td>
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
    <td>Quel est le nom de l'hôpital où nous sommes (ou Quel est le nom de l'hôpital de la ville d'où vous venez ou Quel le nom du médecin que vous avez vu ?)</td>
    <td><?php echo $TroubleCognitif->mmse_nom_hop=='1'?"oui":"non" ?></td>
  </tr>
  <tr>
    <td>Dans quelle ville se trouve t-il ?</td>
    <td><?php echo $TroubleCognitif->mmse_nom_ville=='1'?"oui":"non" ?></td>
  </tr>
  <tr>
    <td>Quel est le nom du Département dans lequel est située cette ville ?</td>
    <td><?php echo $TroubleCognitif->mmse_nom_dep=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Dans quelle Région est située ce Département (si le nom de la région est identique à celui du département) Dans quel Pays est situé ce Département ?</td>
    <td><?php echo $TroubleCognitif->mmse_region=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>A quel étage sommes-nous ici ?</td>
    <td><?php echo $TroubleCognitif->mmse_etage=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Répétez Cigare</td>
    <td><?php echo $TroubleCognitif->mmse_cigare1=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Répétez Fleur</td>
    <td><?php echo $TroubleCognitif->mmse_fleur1=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Répétez Porte</td>
    <td><?php echo $TroubleCognitif->mmse_porte1=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Comptez à partir de 100 en retranchant 7 à chaque fois (93)</td>
    <td><?php echo $TroubleCognitif->mmse_93=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Comptez à partir de 100 en retranchant 7 à chaque fois (86)</td>
    <td><?php echo $TroubleCognitif->mmse_86=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Comptez à partir de 100 en retranchant 7 à chaque fois (79)</td>
    <td><?php echo $TroubleCognitif->mmse_79=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Comptez à partir de 100 en retranchant 7 à chaque fois (72)</td>
    <td><?php echo $TroubleCognitif->mmse_72=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Comptez à partir de 100 en retranchant 7 à chaque fois (65)</td>
    <td><?php echo $TroubleCognitif->mmse_65=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Epelez le mot MONDE à l'envers (EDNOM) (indiquez les lettres épelées)</td>
    <td><?php echo $TroubleCognitif->mmse_monde; ?></td>
  </tr>
  <tr>
    <td>Retrouvez le mot répété auparavant (CIGARE)</td>
    <td><?php echo $TroubleCognitif->mmse_cigare2=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Retrouvez le mot répété auparavant (FLEUR)</td>
    <td><?php echo $TroubleCognitif->mmse_fleur2=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Retrouvez le mot répété auparavant (PORTE)</td>
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
    <td>Ecoutez bien et répétez après moi : "pas de mais, de si, ni de et"</td>
    <td><?php echo $TroubleCognitif->mmse_repete_phrase=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Ecoutez bien et faites ce que je vais vous dire "Prenez cette feuille de papier avec la main droite"</td>
    <td><?php echo $TroubleCognitif->mmse_feuille_prise=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Pliez-là en deux</td>
    <td><?php echo $TroubleCognitif->mmse_feuille_pliee=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Et jetez-là par terre</td>
    <td><?php echo $TroubleCognitif->mmse_feuille_jetee=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Faites ce qui est écrit (feuille sur laquelle est écrit "fermez les yeux")</td>
    <td><?php echo $TroubleCognitif->mmse_fermer_yeux=='1'?"oui":"non"; ?></td>
  </tr>
  <tr>
    <td>Voulez-vous m'écrire une phrase, ce que vous voulez mais une phrase entière. Cette phrase doit avoir un sens ?</td>
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
