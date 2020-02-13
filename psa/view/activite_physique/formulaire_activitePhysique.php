<?php
/**
 * Created by PhpStorm.
 * User: gisgo
 * Date: 16/10/18
 * Time: 12:01
 */

require_once("bean/beanparser/htmltags.php");
require_once("view/jsgenerator/jsgenerator.php");
//require_once("view/common/vars.php");



global $dossier;
global $param;
global $activitePhysique;
global $dossierId;
global $dossierNumero;
global $currentObjectName;



?>
<script type="text/javascript" src="<?php echo $path;?>/view/login/_js/dynCont/ajax.js"></script>

<script type="text/javascript" src="<?php echo $path;?>/view/login/_js/tooltip/ajax-dynamic-content.js"></script>

<script type="text/javascript" src="<?php echo $path;?>/view/login/_js/tooltip/ajax-tooltip2.js"></script>

<div style="margin-left: 20px">
    <form action="<?php echo "$path/controler/ActionControler.php"?>" method="post" name="formulaireActivitePhysique">

        <?php
            hiddenControler("ActivitePhysiqueControler");

            if (empty($activitePhysique))
                hiddenAction(ACTION_SAVE);
            else
                hiddenAction(ACTION_UPDATE);

            hidden("","ActivitePhysique:id")
        ?>
        <br />

        <input type="hidden" name="ActivitePhysique:ActivitePhysique:id" value="<?= $activitePhysique['id'] ?>">

        <?php
            if (empty($activitePhysique)) {
                if (!empty($dossierId)) {
                ?>
                    <input type="hidden" name="ActivitePhysique:ActivitePhysique:dossier_id" value="<?= $dossierId ?>">
                    <input type="hidden" name="ActivitePhysique:ActivitePhysique:dossier_numero" value="<?= $dossierNumero ?>">
        <?php   }
                else
                {
        ?>
                    <input type="hidden" name="ActivitePhysique:ActivitePhysique:dossier_id" value="<?= $dossier[0]->id ?>">
                    <input type="hidden" name="ActivitePhysique:ActivitePhysique:dossier_numero" value="<?= $dossier[0]->numero ?>">

        <?php
                }
            }
            else
            {
        ?>

             <input type="hidden" name="ActivitePhysique:ActivitePhysique:dossier_id" value="<?= $activitePhysique['dossier_id'] ?>">
             <input type="hidden" name="ActivitePhysique:ActivitePhysique:dossier_numero" value="<?= $activitePhysique['dossier_numero'] ?>">

        <?php } ?>

        <h3>Formulaire Provisoire pour l'experimentation sur l'Activité Physique</h3>

        <h4> Ce formulaire est provisoire pour test </h4>
        <section id="sec_1">
            <table cellspacing="30">

                <tr>
                    <div>
                        <td>
                            <span>Date de enregistrement</span>
                            <?php
                                $date_en = $activitePhysique['date_maj'];
                                $tabDate = explode('-' , $date_en);
                                
                                if($date_en == "")
                                    $date_fr = "";
                                else
                                    $date_fr  = $tabDate[2].'/'.$tabDate[1].'/'.$tabDate[0];

                                    $js = new JSValidation();
                                    $js->dateInRange("$currentObjectName:date","Date du dépistage");
                            ?>
                            <?php text("size='10' id='input_fm_activite' name='ActivitePhysique:ActivitePhysique:date_maj' pattern=\"0*([1-9]|[12][0-9]|3[01])/0*([1-9]|1[0-2])/(19[0-8][0-9]|199[0-9]|20[0-9]{2}|2100)\"  value=\"$date_fr\"  placeholder='jj/mm/aaaa'  onkeyup='formate_date(this)' ","$currentObjectName:date" ); ?>
                            
                        </td>
                    </div>
                </tr>
                <tr>
                    <td>
                        <div >
                            <label> Poids (en kg)</label>
                            <input type="number" id='input_fm_activite' name="ActivitePhysique:ActivitePhysique:poids" min="0" max="250" step="0.1" placeholder="1" value="<?= $activitePhysique['poids']; ?>">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div >
                            <label> Périmètre abdo  (en cm) </label>
                            <input type="number" id='input_fm_activite' name="ActivitePhysique:ActivitePhysique:contour_du_ventre" min="0" max="2000" step="0.1" placeholder="1" value="<?= $activitePhysique['contour_du_ventre']; ?>">
                        </div>
                    </td>
                </tr>             
                <tr>
                    <td>
                        <div >
                            <label> Périmètre de marche </label>
                            <input type="number" id='input_fm_activite' name="ActivitePhysique:ActivitePhysique:distance_parcourue_en_metres" min="0" max="10000" step="1" placeholder="1" value="<?= $activitePhysique['distance_parcourue_en_metres']; ?>">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div >
                            <label> Essoufflement à l'effort entre 0 et 10 </label>
                            <input type="number" id='input_fm_activite' name="ActivitePhysique:ActivitePhysique:essoufflement" min="0" max="10" step="1" placeholder="1" value="<?= $activitePhysique['essoufflement']; ?>">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div >
                            <label>Douleurs EVA entre 0 et 10 </label>
                            <input type="number" id='input_fm_activite' name="ActivitePhysique:ActivitePhysique:douleurs_eva" min="0" max="10" step="1" placeholder="1" value="<?= $activitePhysique['douleurs_eva']; ?>">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div >
                            <label> Motivation  entre 0 et 10 </label>
                            <input type="number" id='input_fm_activite' name="ActivitePhysique:ActivitePhysique:motivation" min="0" max="10" step="1" placeholder="1" value="<?= $activitePhysique['motivation']; ?>">
                        </div>
                    </td>
                </tr>
                </table>
            </section>
          
            <section id="sec_2">
                <table cellspacing="30">
                <tr>
                    <td>
                        <div >
                            <label> Atteinte des objectifs entre 0 et 10 </label>
                            <input type="number" id='input_fm_activite' name="ActivitePhysique:ActivitePhysique:atteinte_des_objectifs" min="0" max="10" step="1" placeholder="1" value="<?= $activitePhysique['atteinte_des_objectifs']; ?>">
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div >
                            <label> Fatigue entre 0 et 10 </label>
                            <input type="number" id='input_fm_activite' name="ActivitePhysique:ActivitePhysique:fatigue" min="0" max="10" step="1" placeholder="1" value="<?= $activitePhysique['fatigue']; ?>">
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div >
                            <label> Temps d'activité / 7 jours  en Heures </label>
                            <input type="number" id='input_fm_activite' name="ActivitePhysique:ActivitePhysique:tps_activ_sept_jrs" min="0" max="168" step="0.1" placeholder="1" value="<?= $activitePhysique['tps_activ_sept_jrs']; ?>">
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div >
                            <label>Temps de sédentarité / 7 jours  en Heures </label>
                            <input type="number" id='input_fm_activite' name="ActivitePhysique:ActivitePhysique:tps_sed_sept_jrs" min="0" max="168" step="0.1" placeholder="1" value="<?= $activitePhysique['tps_sed_sept_jrs']; ?>">
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <div >
                                        
                            <label> Evaluer sa façon de marcher </label><br><br>
                            <input type="radio" class="class_radio" name="ActivitePhysique:ActivitePhysique:evaluation_marche" value="Amélioration" <?php if ($activitePhysique['evaluation_marche'] == 'Amélioration') echo "checked"; ?> required>
                            <label class="class_radio">Amélioration</label>
                            
                            <input type="radio" class="class_radio" name="ActivitePhysique:ActivitePhysique:evaluation_marche" value="Stabilisation" <?php if ($activitePhysique['evaluation_marche'] == 'Stabilisation') echo "checked"; ?> required>
                            <label class="class_radio">Stabilisation</label>

                            <input type="radio" class="class_radio" name="ActivitePhysique:ActivitePhysique:evaluation_marche" value="Régression" <?php if ($activitePhysique['evaluation_marche'] == 'Régression') echo "checked"; ?> required>
                            <label class="class_radio">Régression</label>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div >
                            <label>Nombre de pas /24h </label>
                            <input type="number" id='input_fm_activite' name="ActivitePhysique:ActivitePhysique:nombre_de_pas_24h" min="0" max="10" step="1" placeholder="1" value="<?= $activitePhysique['nombre_de_pas_24h']; ?>">
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div>
                            <label>Utilisation d'un compteur de pas? </label><br /><br />
                            <input type="radio" class="class_radio" name="ActivitePhysique:ActivitePhysique:utilisation_d_un_compteur_de_pas" value="oui" <?php if ($activitePhysique['utilisation_d_un_compteur_de_pas'] == 'oui') echo "checked"; ?> required>
                            <label class="class_radio">Oui</label>
                            
                            <input type="radio" class="class_radio" name="ActivitePhysique:ActivitePhysique:utilisation_d_un_compteur_de_pas" value="non" <?php if ($activitePhysique['utilisation_d_un_compteur_de_pas'] == 'non') echo "checked"; ?> required>
                            <label class="class_radio">Non</label><br><br>
                            <input type="text" id='input_fm_activite' name="ActivitePhysique:ActivitePhysique:utilisation_d_un_compteur_description" value="<?= $activitePhysique['utilisation_d_un_compteur_description']; ?>">
                        </div>
                    </td>
                </tr>
                </table>
            </section>
            
            <section id="sec_3">
                <table cellspacing="30">
                <tr>
                    <td>                   
                        <div>
                            <h3>Qualité de vie</h3><br><br>
                            <textarea name="ActivitePhysique:ActivitePhysique:qualite_de_vie" cols="100" rows="5"><?= $activitePhysique['qualite_de_vie'] ?></textarea>
                        </div>    
                    </td>
                </tr>

                <tr>
                    <td>
                        <div >
                            <label>Qualité sommeil  entre 0 et 10 </label>
                            <input type="number" id='input_fm_activite' name="ActivitePhysique:ActivitePhysique:qualite_sommeil" min="0" max="10" step="1" placeholder="1" value="<?= $activitePhysique['qualite_sommeil']; ?>">
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                    <label>Modification alimentaire </label><br><br>
                        <div >
                            <input type="radio" class="class_radio" name="ActivitePhysique:ActivitePhysique:modification_alimentaire" value="oui" <?php if ($activitePhysique['modification_alimentaire'] == 'oui') echo "checked"; ?> required>
                            <label class="class_radio">Oui</label>
                            <input type="radio" class="class_radio" name="ActivitePhysique:ActivitePhysique:modification_alimentaire" value="non" <?php if ($activitePhysique['modification_alimentaire'] == 'non') echo "checked"; ?> required>
                            <label class="class_radio">Non</label>
                            <input type="radio" class="class_radio" name="ActivitePhysique:ActivitePhysique:modification_alimentaire" value="nsp" <?php if ($activitePhysique['modification_alimentaire'] == 'nsp') echo "checked"; ?> required>
                            <label class="class_radio">NSP</label>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div >
                            <label>Bien être entre 0 et 10 </label>
                            <input type="number" id='input_fm_activite' name="ActivitePhysique:ActivitePhysique:bien_etre" min="0" max="10" step="1" placeholder="1" value="<?= $activitePhysique['bien_etre']; ?>">
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div >
                            <label>confiance (ou estime) en soi 0 et 10 </label>
                            <input type="number" id='input_fm_activite' name="ActivitePhysique:ActivitePhysique:confiance" min="0" max="10" step="1" placeholder="1" value="<?= $activitePhysique['confiance']; ?>">
                        </div>
                    </td>
                </tr>
        
        
           
                <tr>
                    <td>
                    <label>Isolement social ressenti </label><br><br>
                        <div >
                            <input type="radio" class="class_radio" name="ActivitePhysique:ActivitePhysique:isolement_social_ressenti" value="oui" <?php if ($activitePhysique['isolement_social_ressenti'] == 'oui') echo "checked"; ?> required>
                            <label class="class_radio">Oui</label>
                            <input type="radio" class="class_radio" name="ActivitePhysique:ActivitePhysique:isolement_social_ressenti" value="non" <?php if ($activitePhysique['isolement_social_ressenti'] == 'non') echo "checked"; ?> required>
                            <label class="class_radio">Non</label>
                            <input type="radio" class="class_radio" name="ActivitePhysique:ActivitePhysique:isolement_social_ressenti" value="nsp" <?php if ($activitePhysique['isolement_social_ressenti'] == 'nsp') echo "checked"; ?> required>
                            <label class="class_radio">NSP</label>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                    <label>Activités physiques annexes </label><br><br>
                        <div >
                            <input type="radio" class="class_radio" name="ActivitePhysique:ActivitePhysique:activites_physiques_annexes" value="oui" <?php if ($activitePhysique['activites_physiques_annexes'] == 'oui') echo "checked"; ?> required>
                            <label class="class_radio">Oui</label>
                            <input type="radio" class="class_radio" name="ActivitePhysique:ActivitePhysique:activites_physiques_annexes" value="non" <?php if ($activitePhysique['activites_physiques_annexes'] == 'non') echo "checked"; ?> required>
                            <label class="class_radio">Non</label>
                            <input type="radio" class="class_radio" name="ActivitePhysique:ActivitePhysique:activites_physiques_annexes" value="nsp" <?php if ($activitePhysique['activites_physiques_annexes'] == 'nsp') echo "checked"; ?> required>
                            <label class="class_radio">NSP</label><br><br>
                            <input type="text" id='input_fm_activite' name="ActivitePhysique:ActivitePhysique:activites_physiques_annexes_description" value="<?= $activitePhysique['activites_physiques_annexes_description']; ?>">
                        </div>
                    </td>
                </tr>

            <tr>
                <td>
                    <div>
                        <label>Patient sortant du protocole? </label><br /><br />
                        <input type="radio" class="class_radio" name="ActivitePhysique:ActivitePhysique:patient_sortant_du_protocole" value="oui" <?php if ($activitePhysique['patient_sortant_du_protocole'] == 'oui') echo "checked"; ?> required>
                        <label class="class_radio">Oui</label>
                        
                        <input type="radio" class="class_radio" name="ActivitePhysique:ActivitePhysique:patient_sortant_du_protocole" value="non" <?php if ($activitePhysique['patient_sortant_du_protocole'] == 'non' || $activitePhysique['Patient_sortant_du_protocole'] == "") echo "checked"; ?> required>
                        <label class="class_radio">Non</label>

                        <input type="radio" class="class_radio" name="ActivitePhysique:ActivitePhysique:patient_sortant_du_protocole" value="nsp" <?php if ($activitePhysique['patient_sortant_du_protocole'] == 'nsp') echo "checked"; ?> required>
                        <label class="class_radio">NSP</label><br><br>
                        <input type="text" id='input_fm_activite' name="ActivitePhysique:ActivitePhysique:patient_sortant_du_protocole_description" value="<?= $activitePhysique['patient_sortant_du_protocole_description']; ?>">
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div>
                        <label>Lien sur le territoire? </label><br /><br />
                        <input type="radio" class="class_radio" name="ActivitePhysique:ActivitePhysique:lien_sur_le_territoire" value="oui" <?php if ($activitePhysique['lien_sur_le_territoire'] == 'oui') echo "checked"; ?> required>
                        <label class="class_radio">Oui</label>
                        
                        <input type="radio" class="class_radio" name="ActivitePhysique:ActivitePhysique:lien_sur_le_territoire" value="non" <?php if ($activitePhysique['lien_sur_le_territoire'] == 'non') echo "checked"; ?> required>
                        <label class="class_radio">Non</label>

                        <input type="radio" class="class_radio" name="ActivitePhysique:ActivitePhysique:lien_sur_le_territoire" value="nsp" <?php if ($activitePhysique['lien_sur_le_territoire'] == 'nsp') echo "checked"; ?> required>
                        <label class="class_radio">NSP</label><br><br>
                        <input type="text" id='input_fm_activite' name="ActivitePhysique:ActivitePhysique:lien_sur_le_territoire_description" value="<?= $activitePhysique['lien_sur_le_territoire_description']; ?>">
                    </div>
                </td>
            </tr>

        </table>
    </section>
        
        

        <p> Ce formulaire est provisoire pour test </p>

        <input type="submit" class="btn_enregistrer" value="Enregistrer">
        <?php
            if(!empty($activitePhysique))
            {
        ?>
        <button class="btn_annuler" onclick="goBack()">Annuler</button>
        <?php
            }
        ?>
    </form>
</div>

        <script>    
        function goBack() {
        window.history.back();
        }
        </script> 
