<?php
/**
 * Created by PhpStorm.
 * User: gisgo
 * Date: 16/04/18
 * Time: 11:11
 */

require_once("bean/beanparser/htmltags.php");
require_once("view/jsgenerator/jsgenerator.php");
require_once("view/common/vars.php");

global $dossier;
global $param;
global $fragilite;
global $dossierId;
global $dossierNumero;
?>
<script type="text/javascript" src="<?php echo $path;?>/view/login/_js/dynCont/ajax.js"></script>

<script type="text/javascript" src="<?php echo $path;?>/view/login/_js/tooltip/ajax-dynamic-content.js"></script>

<script type="text/javascript" src="<?php echo $path;?>/view/login/_js/tooltip/ajax-tooltip2.js"></script>

<div style="margin-left: 20px">
    <form action="<?php echo "$path/controler/ActionControler.php"?>" method="post" name="formulaireFragilite">

        <?php
            hiddenControler("FragiliteControler");

            if (empty($fragilite))
                hiddenAction(ACTION_SAVE);
            else
                hiddenAction(ACTION_UPDATE);

            hidden("","Fragilite:id")
        ?>

        <br />

        <input type="hidden" name="Fragilite:Fragilite:id" value="<?= $fragilite['id'] ?>">

        <?php
            if (empty($fragilite)) {
                if (!empty($dossierId)) {
                ?>
                    <input type="hidden" name="Fragilite:Fragilite:dossier_id" value="<?= $dossierId ?>">
                    <input type="hidden" name="Fragilite:Fragilite:dossier_numero" value="<?= $dossierNumero ?>">
        <?php   }
                else
                {
        ?>
                    <input type="hidden" name="Fragilite:Fragilite:dossier_id" value="<?= $dossier[0]->id ?>">
                    <input type="hidden" name="Fragilite:Fragilite:dossier_numero" value="<?= $dossier[0]->numero ?>">

        <?php
                }
            }
            else
            {
        ?>

             <input type="hidden" name="Fragilite:Fragilite:dossier_id" value="<?= $fragilite['dossier_id'] ?>">
             <input type="hidden" name="Fragilite:Fragilite:dossier_numero" value="<?= $fragilite['dossier_numero'] ?>">

        <?php } ?>

        <h3>Dans quel cadre ont eu lieu les rencontres : </h3>
        <table cellspacing="30">
            <tr>
                <td>
                    <input type="radio" name="Fragilite:Fragilite:lieuVisite" value="cabinet" <?php if ($fragilite['lieu_visite'] == 'cabinet') echo "checked"; ?> required>
                    <label>Cabinet</label>
                </td>
                <td>
                    <input type="radio" name="Fragilite:Fragilite:lieuVisite" value="domicile" <?php if ($fragilite['lieu_visite'] == 'domicile') echo "checked"; ?> required>
                    <label>Domicile</label>
                </td>
                <td>
                    <input type="radio" name="Fragilite:Fragilite:lieuVisite" value="cabinetEtDomicile" <?php if ($fragilite['lieu_visite'] == 'cabinetEtDomicile') echo "checked"; ?> required>
                    <label>Cabinet et Domicile</label>
                </td>
            </tr>
        </table>

        <br />
        <br />

        <h3>Pouvez vous d�crire le contexte de vie de la personne � laquelle vous faites r�f�rence?</h3>
        <table cellspacing="30">
            <tr>
                <td>
                    Vit Seul : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:estSeul" value="1" <?php if ($fragilite['estSeul'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:estSeul" value="0" <?php if ($fragilite['estSeul'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:estSeul" value="-1" <?php if ($fragilite['estSeul'] == '-1') echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
                <td>
                    <label>Aidant institutionnel actuel</label><br />
                    <select name="Fragilite:Fragilite:aidantActuel[]" multiple size="11">
                        <?php foreach ($aidants as $cle => $aidant) {
                        ?>

                            <option value="<?= $cle ?>" <?php if ($fragilite[$cle] == 1) echo "selected"; ?> ><?= $aidant ?></option>

                        <?php
                            }
                        ?>
                    </select>
                </td>
                <td>
                    <label>Autre aidant actuel</label><br />
                    <select name="Fragilite:Fragilite:autreAidantActuel[]" multiple size="3">
                        <?php foreach ($autre_aidants as $cle => $autre_aidant) {
                        ?>

                            <option value="<?= $cle ?>" <?php if ($fragilite[$cle] == 1) echo "selected"; ?> ><?= $autre_aidant ?></option>

                        <?php
                            }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
            <table cellspacing="30">
            <tr>
                <td>
                    Ressources familiales suffisantes : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:ressourcesFamilial" value="1" <?php if ($fragilite['ressourcesFamSuff'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:ressourcesFamilial" value="0" <?php if ($fragilite['ressourcesFamSuff'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:ressourcesFamilial" value="-1" <?php if ($fragilite['ressourcesFamSuff'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
                <td>
                    Ressources amicales suffisantes : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:ressourcesAmical" value="1" <?php if ($fragilite['ressourcesAmSuff'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:ressourcesAmical" value="0" <?php if ($fragilite['ressourcesAmSuff'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:ressourcesAmical" value="-1" <?php if ($fragilite['ressourcesAmSuff'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
                <td>
                    Animal de compagnie : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:animaldeCompagnie" value="1" <?php if ($fragilite['animaldeCompagnie'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:animaldeCompagnie" value="0" <?php if ($fragilite['animaldeCompagnie'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:animaldeCompagnie" value="-1" <?php if ($fragilite['animaldeCompagnie'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
                <td>
                    Logement adapt� : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:logementAdapte" value="1" <?php if ($fragilite['logementAdapte'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:logementAdapte" value="0" <?php if ($fragilite['logementAdapte'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:logementAdapte" value="-1" <?php if ($fragilite['logementAdapte'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
            </tr>
        </table>

        <br />
        <br />

        <h3>Quel est le niveau socio-culturel de la personne � laquelle vous faites r�f�rence ?</h3>
        <table cellspacing="30">
            <tr>
                <td>
                    Ins�curit� financi�re : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:insecuriteFinanciere" value="1" <?php if ($fragilite['insecFinanciere'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:insecuriteFinanciere" value="0" <?php if ($fragilite['insecFinanciere'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:insecuriteFinanciere" value="-1" <?php if ($fragilite['insecFinanciere'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
                <td colspan="2">
                    <label>Niveau scolaire maximal atteint �valu�</label><br />
                    <select name="Fragilite:Fragilite:niveauScolaire">
                        <option value=""></option>
                        <option value="bdc" <?php if ($fragilite['niveauScolaire'] == 'bdc') echo "selected"; ?> >Brevet des coll�ges</option>
                        <option value="bac" <?php if ($fragilite['niveauScolaire'] == 'bac') echo "selected"; ?> >Bac</option>
                        <option value="etudes_sup" <?php if ($fragilite['niveauScolaire'] == 'etudes_sup') echo "selected"; ?> >�tudes sup�rieurs</option>
                        <option value="autre_niveau_et" <?php if ($fragilite['niveauScolaire'] == 'autre_niveau_et') echo "selected"; ?> >Autre</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    Langue fran�aise �crite : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:frEcrit" value="1" <?php if ($fragilite['frEcrit'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:frEcrit" value="0" <?php if ($fragilite['frEcrit'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:frEcrit" value="-1" <?php if ($fragilite['frEcrit'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
                <td>
                    langue fran�aise parl�e : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:frParle" value="1" <?php if ($fragilite['frParle'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:frParle" value="0" <?php if ($fragilite['frParle'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:frParle" value="-1" <?php if ($fragilite['frParle'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
                <td>
                    Couverture sociale active : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:couvertureSociale" value="1" <?php if ($fragilite['couvSocActive'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:couvertureSociale" value="0" <?php if ($fragilite['couvSocActive'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:couvertureSociale" value="-1" <?php if ($fragilite['couvSocActive'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
            </tr>
        </table>

        <br />
        <br />

        <h3>De combien de pathologies chroniques av�r�es la personnes est-elle atteinte ?</h3>
        <table cellspacing="30">sa
            <tr>
                <td>
                    <input type="radio" name="Fragilite:Fragilite:pathChronique" value="0" <?php if ($fragilite['pathChronique'] == 0) echo "checked"; ?> >
                    <label>0</label>
                </td>
                <td>
                    <input type="radio" name="Fragilite:Fragilite:pathChronique" value="1" <?php if ($fragilite['pathChronique'] == 1) echo "checked"; ?> >
                    <label>1</label>
                </td>
                <td>
                    <input type="radio" name="Fragilite:Fragilite:pathChronique" value="2" <?php if ($fragilite['pathChronique'] == 2) echo "checked"; ?> >
                    <label>2</label>
                </td>
                <td>
                    <input type="radio" name="Fragilite:Fragilite:pathChronique" value="3 ou plus" <?php if ($fragilite['pathChronique'] == "3 ou plus") echo "checked"; ?> >
                    <label>3 ou plus</label>
                </td>
                <td>
                    <input type="radio" name="Fragilite:Fragilite:pathChronique" value="-1" <?php if ($fragilite['pathChronique'] == "-1") echo "checked"; ?> >
                    <label>NSP</label>
                </td>
            </tr>
        </table>

        <br />
        <br />

        <h3>Avez vous connaissance du nombre de m�dicaments que cette personne prend ? </h3>
        <table cellspacing="30">

            <tr>
                <tr>
                    <td colspan="2">
                        M�dicaments pr�scrits (hors presciption temporaire) sup�rieur � 5 :
                    </td>

                    <td>
                        <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:medPrescSupCinq" value="1" <?php if ($fragilite['medPrescSupCinq'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:medPrescSupCinq" value="0" <?php if ($fragilite['medPrescSupCinq'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:medPrescSupCinq" value="-1" <?php if ($fragilite['medPrescSupCinq'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        Quel niveau d'observance de la prise de m�dicaments constatez-vous chez la personne?
                    </td>
                </tr>
                <tr>


                    <td>
                        <input type="radio" name="Fragilite:Fragilite:niveauObservance" value="0" <?php if ($fragilite['niveauObservance'] == 0) echo "checked"; ?> required>
                        <label>Non observante</label>

                        <input type="radio" name="Fragilite:Fragilite:niveauObservance" value="1" <?php if ($fragilite['niveauObservance'] == 1) echo "checked"; ?> required>
                        <label>Peu observante</label>

                        <input type="radio" name="Fragilite:Fragilite:niveauObservance" value="2" <?php if ($fragilite['niveauObservance'] == 2) echo "checked"; ?> required>
                        <label>Tr�s observante</label>

                        <input type="radio" name="Fragilite:Fragilite:niveauObservance" value="-1" <?php if ($fragilite['niveauObservance'] == "-1") echo "checked"; ?> required>
                        <label>NSP</label>
                    </td>


                </tr>
            </tr>

        </table>

        <br />
        <br />

        <h3>Avez vous connaissance d'hospitalisation(s) dans les 12 derniers mois ?</h3>
        <table cellspacing="30">
            <tr>
                <td>
                    Hospitalisation(s) non programm�es r�centes : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:hospitalisationRecenteNonProg" value="1" <?php if ($fragilite['hospitalisationRecenteNonProg'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:hospitalisationRecenteNonProg" value="0" <?php if ($fragilite['hospitalisationRecenteNonProg'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:hospitalisationRecenteNonProg" value="-1" <?php if ($fragilite['hospitalisationRecenteNonProg'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    Hospitalisation(s) programm�es r�centes : <br /><br />
                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:hospitalisationRecenteProg" value="1" <?php if ($fragilite['hospitalisationRecenteProg'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:hospitalisationRecenteProg" value="0" <?php if ($fragilite['hospitalisationRecenteProg'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:hospitalisationRecenteProg" value="-1" <?php if ($fragilite['hospitalisationRecenteProg'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <label>Nombre total d'hospitalisations durant la derni�re ann�e</label>
                    <input type="number" name="Fragilite:Fragilite:nombreTotalHospit" min="0" max="100" step="1" placeholder="2" value="<?= $fragilite['nombreTotalHospit']; ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <label>Date de sortie de la derni�re hospitalisation</label>
                    <input type="date" name="Fragilite:Fragilite:dateSortieDerniereHospit" placeholder="Nombre total d'hospitalisations durant la derni�re ann�e" value="<?= $fragilite['dateSortieDerniereHospit']; ?>">
                </td>
            </tr>
        </table>
        <br />
        <br />

        <h3>En pensant � cette personne, � quels domaines de fragilit� faites vous r�f�rence? </h3>
        <table cellspacing="30">
            <tr>
                <td>
                    Fragilit� psychologique : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:fragPsych" value="1" <?php if ($fragilite['fragPsych'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:fragPsych" value="0" <?php if ($fragilite['fragPsych'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:fragPsych" value="-1" <?php if ($fragilite['fragPsych'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>

                <td>
                    Fragilit� �conomique : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:fragEco" value="1" <?php if ($fragilite['fragEco'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:fragEco" value="0" <?php if ($fragilite['fragEco'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:fragEco" value="-1" <?php if ($fragilite['fragEco'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
                <td>
                    Fragilit� sociale : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:fragSoc" value="1" <?php if ($fragilite['fragSoc'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:fragSoc" value="0" <?php if ($fragilite['fragSoc'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:fragSoc" value="-1" <?php if ($fragilite['fragSoc'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
                <td>
                    Fragilit� somatique : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:fragSom" value="1" <?php if ($fragilite['fragSom'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:fragSom" value="0" <?php if ($fragilite['fragSom'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:fragSom" value="-1" <?php if ($fragilite['fragSom'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
            </tr>
        </table>

        <br />
        <br />

        <h3>Avez vous connaissance de l'utilisation d'un outil de suivi r�f�renc�, chez cette personne?</h3>
        <table cellspacing="30">
            <tr>
                <td>
                    IADL (Instrumental Activities of Daily Living) : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:iadl" value="1" <?php if ($fragilite['iadl'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:iadl" value="0" <?php if ($fragilite['iadl'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:iadl" value="-1" <?php if ($fragilite['iadl'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
                <td>
                    GDS (Geriatric Depression Scale): <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:gds" value="1" <?php if ($fragilite['gds'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:gds" value="0" <?php if ($fragilite['gds'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:gds" value="-1" <?php if ($fragilite['gds'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
                <td>
                    EPICES (Evaluation de la Pr�carit� et des In�galit�s de sant� dans les Centres d'Examens de Sant�): <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:epices" value="1" <?php if ($fragilite['epices'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:epices" value="0" <?php if ($fragilite['epices'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:epices" value="-1" <?php if ($fragilite['epices'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    �valuation g�riatrique standardis�e : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:evalGS" value="1" <?php if ($fragilite['evalGS'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:evalGS" value="0" <?php if ($fragilite['evalGS'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:evalGS" value="-1" <?php if ($fragilite['evalGS'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
                <td>
                    Trouble cognitifs : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:trblCogn" value="1" <?php if ($fragilite['trblCogn'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:trblCogn" value="0" <?php if ($fragilite['trblCogn'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:trblCogn" value="-1" <?php if ($fragilite['trblCogn'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
                <td>
                    Autre(s) outil(s): <br /><br />
                    <textarea name="Fragilite:Fragilite:autresOutils" cols="45" rows="1"><?= $fragilite['autresOutils'] ?></textarea>
                </td>
            </tr>
        </table>

        <br />
        <br />

        <h3>Avez vous constat� chez la personne � laquelle vous faites r�f�rence : </h3>
        <table cellspacing="30">
            <tr>
                <tr>
                    <td>
                        Une r�duction de l'activit� physique : <br /><br />

                        <div style="margin-left: 15px">
                            <input type="radio" name="Fragilite:Fragilite:activitePhy" value="1" <?php if ($fragilite['activitePhy'] == '1') echo "checked"; ?> required>
                            <label>Oui</label>
                            <input type="radio" name="Fragilite:Fragilite:activitePhy" value="0" <?php if ($fragilite['activitePhy'] == '0') echo "checked"; ?> required>
                            <label>Non</label>
                            <input type="radio" name="Fragilite:Fragilite:activitePhy" value="-1" <?php if ($fragilite['activitePhy'] == '-1')echo "checked"; ?> required>
                            <label>NSP</label>
                        </div>
                    </td>

                    <td>
                        Un arr�t de la conduite automobile : <br /><br />

                        <div style="margin-left: 15px">
                            <input type="radio" name="Fragilite:Fragilite:arretConduite" value="1" <?php if ($fragilite['arretConduite'] == '1') echo "checked"; ?> required>
                            <label>Oui</label>
                            <input type="radio" name="Fragilite:Fragilite:arretConduite" value="0" <?php if ($fragilite['arretConduite'] == '0') echo "checked"; ?> required>
                            <label>Non</label>
                            <input type="radio" name="Fragilite:Fragilite:arretConduite" value="-1" <?php if ($fragilite['arretConduite'] == '-1')echo "checked"; ?> required>
                            <label>NSP</label>
                        </div>
                    </td>
                </tr>
            </tr>
        </table>
        <h3>Avez vous constat� chez la personne � laquelle vous faites r�f�rence  un changement li� � la marche : </h3>
        <table cellspacing="30">
            <tr>
                <tr>
                    <td>
                        Une r�duction du p�rim�tre de marche : <br /><br />

                        <div style="margin-left: 15px">
                            <input type="radio" name="Fragilite:Fragilite:perimetreMarche" value="1" <?php if ($fragilite['perimetreMarche'] == '1') echo "checked"; ?> required>
                            <label>Oui</label>
                            <input type="radio" name="Fragilite:Fragilite:perimetreMarche" value="0" <?php if ($fragilite['perimetreMarche'] == '0') echo "checked"; ?> required>
                            <label>Non</label>
                            <input type="radio" name="Fragilite:Fragilite:perimetreMarche" value="-1" <?php if ($fragilite['perimetreMarche'] == '-1')echo "checked"; ?> required>
                            <label>NSP</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        Un ralentissement de la vitesse de marche :
                        <!--<a href='javascript://' onmouseover="ajax_showTooltip('/psa/view/fragilite/aide_saisie_fragilite.html',this);return false" ><img src='/psa/view/login/img/puces/aide.gif'></a> -->
                        <br /><br />

                        <div style="margin-left: 15px">
                            <input type="radio" name="Fragilite:Fragilite:vitesseMarche" value="1" <?php if ($fragilite['vitesseMarche'] == '1') echo "checked"; ?> required>
                            <label>Oui</label>
                            <input type="radio" name="Fragilite:Fragilite:vitesseMarche" value="0" <?php if ($fragilite['vitesseMarche'] == '0') echo "checked"; ?> required>
                            <label>Non</label>
                            <input type="radio" name="Fragilite:Fragilite:vitesseMarche" value="-1" <?php if ($fragilite['vitesseMarche'] == '-1')echo "checked"; ?> required>
                            <label>NSP</label>
                        </div>
                    </td>
                    <td>
                        Est-ce que la personne se d�place de 4 m en 4 secondes <br /><br />
                        <div style="margin-left: 15px">
                            <input type="radio" name="Fragilite:Fragilite:vitesseMarche4m4s" value="1" <?php if ($fragilite['vitesseMarche4m4s'] == '1') echo "checked"; ?> required>
                            <label>Oui</label>
                            <input type="radio" name="Fragilite:Fragilite:vitesseMarche4m4s" value="0" <?php if ($fragilite['vitesseMarche4m4s'] == '0') echo "checked"; ?> required>
                            <label>Non</label>
                            <input type="radio" name="Fragilite:Fragilite:vitesseMarche4m4s" value="-1" <?php if ($fragilite['vitesseMarche4m4s'] == '-1')echo "checked"; ?> required>
                            <label>NSP</label>
                        </div>
                    </td>
                </tr>
            </tr>
        </table>

        <br />
        <br />

        <h3>A-t-il �t� constat� :</h3>
        <table cellspacing="30">
            <tr>
                <td>
                    Des difficult�s � effectuer les gestes de la vie quotidienne : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:diffVieQuot" value="1" <?php if ($fragilite['diffVieQuot'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:diffVieQuot" value="0" <?php if ($fragilite['diffVieQuot'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:diffVieQuot" value="-1" <?php if ($fragilite['diffVieQuot'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    Des difficult�s intellectuelles : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:diffIntel" value="1" <?php if ($fragilite['diffIntell'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:diffIntel" value="0" <?php if ($fragilite['diffIntell'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:diffIntel" value="-1" <?php if ($fragilite['diffIntell'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    Mesure de protection judiciaire active : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:protectionJudiciaire" value="1" <?php if ($fragilite['protectionJud'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:protectionJudiciaire" value="0" <?php if ($fragilite['protectionJud'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:protectionJudiciaire" value="-1" <?php if ($fragilite['protectionJud'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    Diminution r�cente (moins d'un an) des capacit�s sensorielles ayant un impact <b>sur le quotidien de la personne � son domicile</b> : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:diminutionCapSensInterne" value="1" <?php if ($fragilite['diminutionCapSensInterne'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:diminutionCapSensInterne" value="0" <?php if ($fragilite['diminutionCapSensInterne'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:diminutionCapSensInterne" value="-1" <?php if ($fragilite['diminutionCapSensInterne'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
                <td>
                    <label>Depuis combien de temps (mois) ? </label>
                    <input type="number" name="Fragilite:Fragilite:dureedepuisdiminutionCapSensInterne" min="0" max="1200" step=".01" placeholder="Dur�e depuis l'apparition de la perturbation du sommeil" value="<?= $fragilite['dureedepuisdiminutionCapSensInterne']; ?>">
                </td>
            </tr>
            <tr>
                <td>
                    Diminution r�cente (moins d'un an) des capacit�s sensorielles ayant un impact <b>sur les relations sociales de la personne </b>: <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:diminutionCapSensExterne" value="1" <?php if ($fragilite['diminutionCapSensExterne'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:diminutionCapSensExterne" value="0" <?php if ($fragilite['diminutionCapSensExterne'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:diminutionCapSensExterne" value="-1" <?php if ($fragilite['diminutionCapSensExterne'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
                <td>
                    <label>Depuis combien de temps (mois) ? </label>
                    <input type="number" name="Fragilite:Fragilite:dureedepuisdiminutionCapSensExterne" min="0" max="1200" step=".01" placeholder="Dur�e depuis l'apparition de la perturbation du sommeil" value="<?= $fragilite['dureedepuisdiminutionCapSensExterne']; ?>">
                </td>
            </tr>
            <tr>
                <td>
                    Des difficult�s � effectuer les gestes de la vie quotidienne : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:perturbationSommeil" value="1" <?php if ($fragilite['perturbationSommeil'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:perturbationSommeil" value="0" <?php if ($fragilite['perturbationSommeil'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:perturbationSommeil" value="-1" <?php if ($fragilite['perturbationSommeil'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
                <td>
                    <label>Depuis combien de temps (mois) ?</label>
                    <input type="number" name="Fragilite:Fragilite:dureedepuisperturbationsommeil" min="0" max="1200" step=".01" placeholder="Dur�e depuis l'apparition de la perturbation du sommeil" value="<?= $fragilite['dureedepuisperturbationsommeil']; ?>">
                </td>
            </tr>
        </table>

        <br />
        <br />

        <h3>Variation de poids involontaire sur les 6 derniers mois</h3>
        <table cellspacing="30">
            <tr>
                <td>
                    De 3kg ou plus : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:variationPoids" value="1" <?php if ($fragilite['variationPoids'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:variationPoids" value="0" <?php if ($fragilite['variationPoids'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:variationPoids" value="-1" <?php if ($fragilite['variationPoids'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
                <td>
                    <label>Valeur de l'IMC lors de la consultation</label>
                    <input type="number" name="Fragilite:Fragilite:imc" min="0" max="100" placeholder="IMC" value="<?= $fragilite['imc']; ?>">
                </td>
            </tr>
        </table>

        <br />
        <br />

        <h3>Douleur</h3>
        <table cellspacing="30">
            <tr>
                <td>
                    Pr�occupation douloureuse envahissante : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:douleur" value="1" <?php if ($fragilite['douleur'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:douleur" value="0" <?php if ($fragilite['douleur'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:douleur" value="-1" <?php if ($fragilite['douleur'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
                <td>
                    <label>Depuis combien de temps est apparue la douleur (mois) ? </label>
                    <input type="number" name="Fragilite:Fragilite:dureedepuisdouleur" min="0" max="1200" step=".01" placeholder="Dur�e depuis l'apparition de la douleur" value="<?= $fragilite['dureedepuisdouleur']; ?>">
                </td>
            </tr>
        </table>

        <br />
        <br />

        <h3>Avez vous connaissance d'une addiction concernant la personne � laquelle vous faites r�f�rence?</h3>
        <table cellspacing="30">
            <tr>
                <td>
                    Alcool : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:addictAlcool" value="1" <?php if ($fragilite['addictAlcool'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:addictAlcool" value="0" <?php if ($fragilite['addictAlcool'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:addictAlcool" value="-1" <?php if ($fragilite['addictAlcool'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
                <td>
                    Tabac : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:addictTabac" value="1" <?php if ($fragilite['addictTabac'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:addictTabac" value="0" <?php if ($fragilite['addictTabac'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:addictTabac" value="-1" <?php if ($fragilite['addictTabac'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
                <td>
                    M�dicaments : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:addictMed" value="1" <?php if ($fragilite['addictMed'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:addictMed" value="0" <?php if ($fragilite['addictMed'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:addictMed" value="-1" <?php if ($fragilite['addictMed'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
                <td>
                    Cannabis : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:addictCanabis" value="1" <?php if ($fragilite['addictCanabis'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:addictCanabis" value="0" <?php if ($fragilite['addictCanabis'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:addictCanabis" value="-1" <?php if ($fragilite['addictCanabis'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
                <td>
                    <input type="checkbox" name="Fragilite:Fragilite:autreAddiction" <?php if ($fragilite['autreAddiction'] == 1) echo "checked"; ?> >
                    <label>Autre</label>
                </td>
            </tr>
        </table>

        <br />
        <br />

        <h3>Constatez-vous chez la personne : </h3>
        <table cellspacing="30">
            <tr>
                <tr>
                    <td>
                        Une �motion limitante : <br /><br />

                        <div style="margin-left: 15px">
                            <input type="radio" name="Fragilite:Fragilite:emotionLimitante" value="1" <?php if ($fragilite['emotionLimitante'] == '1') echo "checked"; ?> required>
                            <label>Oui</label>
                            <input type="radio" name="Fragilite:Fragilite:emotionLimitante" value="0" <?php if ($fragilite['emotionLimitante'] == '0') echo "checked"; ?> required>
                            <label>Non</label>
                            <input type="radio" name="Fragilite:Fragilite:emotionLimitante" value="-1" <?php if ($fragilite['emotionLimitante'] == '-1')echo "checked"; ?> required>
                            <label>NSP</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        Une incapacit� � s'exprimer : <br /><br />

                        <div style="margin-left: 15px">
                            <input type="radio" name="Fragilite:Fragilite:incapExpression" value="1" <?php if ($fragilite['incapExpression'] == '1') echo "checked"; ?> required>
                            <label>Oui</label>
                            <input type="radio" name="Fragilite:Fragilite:incapExpression" value="0" <?php if ($fragilite['incapExpression'] == '0') echo "checked"; ?> required>
                            <label>Non</label>
                            <input type="radio" name="Fragilite:Fragilite:incapExpression" value="-1" <?php if ($fragilite['incapExpression'] == '-1')echo "checked"; ?> required>
                            <label>NSP</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        Un sentiment d'isolement physique ou social : <br /><br />

                        <div style="margin-left: 15px">
                            <input type="radio" name="Fragilite:Fragilite:isolementPhy" value="1" <?php if ($fragilite['isolementPhy'] == '1') echo "checked"; ?> required>
                            <label>Oui</label>
                            <input type="radio" name="Fragilite:Fragilite:isolementPhy" value="0" <?php if ($fragilite['isolementPhy'] == '0') echo "checked"; ?> required>
                            <label>Non</label>
                            <input type="radio" name="Fragilite:Fragilite:isolementPhy" value="-1" <?php if ($fragilite['isolementPhy'] == '-1')echo "checked"; ?> required>
                            <label>NSP</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        Un sentiment d'abandon : <br /><br />

                        <div style="margin-left: 15px">
                            <input type="radio" name="Fragilite:Fragilite:abandon" value="1" <?php if ($fragilite['abandon'] == '1') echo "checked"; ?> required>
                            <label>Oui</label>
                            <input type="radio" name="Fragilite:Fragilite:abandon" value="0" <?php if ($fragilite['abandon'] == '0') echo "checked"; ?> required>
                            <label>Non</label>
                            <input type="radio" name="Fragilite:Fragilite:abandon" value="-1" <?php if ($fragilite['abandon'] == '-1')echo "checked"; ?> required>
                            <label>NSP</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        Un sentiment d'�tre submerg� : <br /><br />

                        <div style="margin-left: 15px">
                            <input type="radio" name="Fragilite:Fragilite:submerge" value="1" <?php if ($fragilite['submerge'] == '1') echo "checked"; ?> required>
                            <label>Oui</label>
                            <input type="radio" name="Fragilite:Fragilite:submerge" value="0" <?php if ($fragilite['submerge'] == '0') echo "checked"; ?> required>
                            <label>Non</label>
                            <input type="radio" name="Fragilite:Fragilite:submerge" value="-1" <?php if ($fragilite['submerge'] == '-1')echo "checked"; ?> required>
                            <label>NSP</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        Un sentiment d'�puisement : <br /><br />

                        <div style="margin-left: 15px">
                            <input type="radio" name="Fragilite:Fragilite:epuisement" value="1" <?php if ($fragilite['epuisement'] == '1') echo "checked"; ?> required>
                            <label>Oui</label>
                            <input type="radio" name="Fragilite:Fragilite:epuisement" value="0" <?php if ($fragilite['epuisement'] == '0') echo "checked"; ?> required>
                            <label>Non</label>
                            <input type="radio" name="Fragilite:Fragilite:epuisement" value="-1" <?php if ($fragilite['epuisement'] == '-1')echo "checked"; ?> required>
                            <label>NSP</label>
                        </div>
                    </td>
                </tr>
            </tr>
        </table>

        <br />
        <br />


        <h3>Force musculaire</h3>
        <table cellspacing="30">
            <tr>
                <td>
                    La force musculaire de la personne concern�e est-elle maintenue ? <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="Fragilite:Fragilite:maintenanceFM" value="1" <?php if ($fragilite['maintenanceFM'] == '1') echo "checked"; ?> required>
                        <label>Oui</label>
                        <input type="radio" name="Fragilite:Fragilite:maintenanceFM" value="0" <?php if ($fragilite['maintenanceFM'] == '0') echo "checked"; ?> required>
                        <label>Non</label>
                        <input type="radio" name="Fragilite:Fragilite:maintenanceFM" value="-1" <?php if ($fragilite['maintenanceFM'] == '-1')echo "checked"; ?> required>
                        <label>NSP</label>
                    </div>
                </td>
            </tr>
        </table>

        <br />
        <br />

        <h3>En quoi cette situation vous parait-elle fragile ?</h3>
        <table cellspacing="30">
            <tr>
                <td>
                    <textarea name="Fragilite:Fragilite:subjectiviteInf" cols="100" rows="10"><?= $fragilite['subjectiviteInf'] ?></textarea>
                </td>
            </tr>
        </table>

        <br/>
        <br/>

        <h1>Au regard de la situation de la personne d�crite pr�c�demment, qu'avez vous eu comme action ?</h1>

        <h3>Mobilisation de ressources externe suite � votre rencontre</h3>
        <table cellspacing="30">
            <tr>
                <td>
                    <select name="Fragilite:Fragilite:ressourceExternes[]" multiple size="11">
                        <?php foreach ($res_externes as $cle => $res_externe) {
                        ?>

                            <option value="<?= $cle ?>" <?php if ($fragilite[$cle] == 1) echo "selected"; ?> ><?= $res_externe ?></option>

                        <?php
                            }
                        ?>
                    </select>
                </td>
            </tr>
        </table>

        <br />
        <br />

        <h3>Quelle(s) autre(s) strat�gie(s) avez vous employ� ? </h3>
        <table cellspacing="30">
            <tr>
                <td>
                    <textarea name="Fragilite:Fragilite:autresStrategies" cols="100" rows="10"><?= $fragilite['autresStrategies'] ?></textarea>
                </td>
            </tr>
        </table>

        <br/>
        <br/>


        <p>Vous pourrez renseigner ces informations jusqu'au 13 juillet 2018.  A l'issue de cette p�riode, un bilan sera r�alis�e par le Centre de Recherche, Innovation et D�veloppement Asal�e.
            Selon les r�sultats de ce bilan, ce formulaire pourra �tre p�rennis� et/ou am�lior� pour tenir compte des informations collect�es.</p>

        <input type="submit" value="Enregistrer">

    </form>
</div>
