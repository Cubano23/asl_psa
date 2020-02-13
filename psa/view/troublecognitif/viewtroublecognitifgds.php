
<table border='1'  width='70%'> 
  <caption> 
  <big><b><font color='green'>GDS</font></b></big>
  </caption> 
  <tr>
  <td>Question
  </td>
    <td>Réponse</td>
  </tr>
  <tr> 
    <td>Etes-vous satisfait(e) de votre vie</td>
    <td> <?php echo $TroubleCognitif->gds_satisf=='oui'?"oui":"non"; ?> </td>
  </tr>
  <tr>
    <td>Avez-vous renoncé à un grand nombre d'activités?</td>
    <td> <?php echo $TroubleCognitif->gds_renonce_act=='oui'?"oui":"non";   ?> </td>
  </tr>
  <tr>
    <td>Avez-vous le sentiment que votre vie soit vide?</td>
    <td> <?php echo $TroubleCognitif->gds_vie_vide=='oui'?"oui":"non";   ?> </td>
  </tr>
  <tr>
    <td>Vous ennuyez-vous souvent?</td>
    <td> <?php echo $TroubleCognitif->gds_ennui=='oui'?"oui":"non";   ?> </td>
  </tr>
  <tr>
    <td>Envisagez-vous l'avenir avec optimisme?</td>
    <td> <?php echo $TroubleCognitif->gds_avenir_opt=='oui'?"oui":"non";   ?> </td>
  </tr>
  <tr>
    <td>Craignez-vous une catastrophe pour l'avenir </td>
    <td> <?php echo $TroubleCognitif->gds_cata=='oui'?"oui":"non";   ?> </td>
  </tr>
  <tr>
    <td>Etes-vous de bonne humeur la plupart du temps </td>
    <td> <?php echo $TroubleCognitif->gds_bonne_humeur=='oui'?"oui":"non";   ?> </td>
  </tr>
  <tr>
    <td>Avez-vous besoin d'aide dans vos activités ?</td>
    <td> <?php echo $TroubleCognitif->gds_besoin_aide=='oui'?"oui":"non";   ?> </td>
  </tr>
  <tr>
    <td>Préférez-vous rester seul (e) dans votre chambre (ou à la maison) plutôt que d'en sortir ?</td>
    <td> <?php echo $TroubleCognitif->gds_prefere_seul=='oui'?"oui":"non";   ?> </td>
  </tr>
  <tr>
    <td>Pensez-vous que votre mémoire est moins bonne que celle de la plupart des gens ? </td>
    <td> <?php echo $TroubleCognitif->gds_mauvaise_mem=='oui'?"oui":"non";   ?> </td>
  </tr>
  <tr>
    <td>Etes-vous heureux (se) de vivre actuellement ? </td>
    <td> <?php echo $TroubleCognitif->gds_heureux_vivre=='oui'?"oui":"non";   ?> </td>
  </tr>
  <tr>
    <td>Avez-vous l'impression de n'être plus bon (ne) à rien ?</td>
    <td> <?php echo $TroubleCognitif->gds_bon_rien=='oui'?"oui":"non";   ?> </td>
  </tr>
  <tr>
    <td>Avez-vous beaucoup d'énergie ?</td>
    <td> <?php echo $TroubleCognitif->gds_energie=='oui'?"oui":"non";   ?> </td>
  </tr>
  <tr>
    <td>Désespérez-vous de votre situation présente ?</td>
    <td> <?php echo $TroubleCognitif->gds_desespere_sit=='oui'?"oui":"non";   ?> </td>
  </tr>
  <tr>
    <td>Pensez-vous que la situation des autres est meilleure que la votre, que les autres ont plus de chance que vous ? </td>
    <td> <?php echo $TroubleCognitif->gds_sit_autres_best=='oui'?"oui":"non";   ?> </td>
  </tr>
  <tr>
    <td>Score : </td>
    <td><?php echo $TroubleCognitif->get_gds(); ?></td>
  </tr>

</table>

<br><br>
