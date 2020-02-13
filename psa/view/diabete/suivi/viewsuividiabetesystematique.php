<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php global $account ?>
<?php global $dossier; ?>
<?php global $suiviDiabete; ?>
<?php global $param; ?>
<?php global $liste_historique; ?>
 
<form action='<?php echo("$path/controler/ActionControler.php");?>' method="post" name="manage">
    <?php hiddenControler(""); ?>
    <?php hiddenAction(""); ?>
    <?php hiddenParam1(""); ?>
    <?php hidden("","dossier:numero"); ?>
    <?php hidden("","suiviDiabete:dsuivi"); ?>

    <!-- Information concernant les dépistages saisies à partir de ce suivi -->
    <input type="hidden" name="DepistageAOMI:DepistageAOMI:provenance" value="SuiviDiabete">
    <input type="hidden" name="DepistageAOMI:DepistageAOMI:dateSaisie" value="<?= $suiviDiabete->dsuivi; ?>">

    <?php require("view/common/dossierresume.php");?>

    <table border='0' width='80%' align='center'>
      <tr>
        <td valign='top'> </td>
        <td><table border='1' width='100%' align='center'>
            <tr>
              <td valign='top'>poids</td>
              <td>&nbsp;<?php typePropertyValue("suiviDiabete:poids"); ?></td>
            </tr>
            <tr>
              <td valign='top'>Indice de masse pondérale</td>
              <td><?php echo($suiviDiabete->getIMC($dossier->taille)); ?></td>
            </tr>
            <tr>
              <td colspan=2><b>Traitements</b></td>
            </tr>
            <tr>
              <td>
                <?php if($suiviDiabete->Regime != false) echo("Régime seul"); ?><br>
                <?php  if($suiviDiabete->InsulReq != false) echo("Insulino requerant"); ?><br>&nbsp;
                  </td>
              <td>
                Anti diabétiques oraux:<br>
                    <?php
                        for($i=0;$i<count($suiviDiabete->ADO);$i++){
                            echo($suiviDiabete->ADO[$i]);
                            echo("<br>");
                        }
                    ?>	&nbsp;
              </td>
            </tr>
            <tr>
              <td valign='top'>Tension arterielle prise</td>
              <td><?php typePropertyValue("suiviDiabete:TaSys"); ?>/<?php typePropertyValue("suiviDiabete:TaDia"); ?>&nbsp;</td>
            </tr>
            <tr>
              <td valign='top'>&nbsp;</td>
              <td>&nbsp;<?php typePropertyValue("suiviDiabete:TA_mode"); ?></td>
            </tr>
            <tr>
              <th scope="row" colspan="2">Co-pathologies</th>
            </tr>
            <tr>
                <td colspan="2">
                    <?php if($suiviDiabete->hta) { echo("Hypertension artérielle"); ?> <br> <?php }?>
                    <?php if($suiviDiabete->arte){ echo("Artèrite"); ?> <br> <?php }?>
                    <?php if($suiviDiabete->neph){ echo("Nephropathie"); ?> <br> <?php }?>
                    <?php if($suiviDiabete->coro){ echo("Insuffisance Coronarienne"); ?> <br> <?php }?>
                    <?php if($suiviDiabete->reti){ echo("Rétinopathie diabétique"); ?> <br> <?php }?>
                    <?php if($suiviDiabete->neur){ echo("Neuropathie périphèrique"); ?> <br> <?php }?>&nbsp;
                </td>
            </tr>
            <tr>
              <th colspan=2>Objectifs</th>
            </tr>
            <tr>
                <td colspan="2">
                    <?php if($suiviDiabete->equilib){ echo("Diabete équilibre");?> <br> <?php }?>
                    <?php if($suiviDiabete->tension){ echo("Objectif tensionnel (135/80)"); ?> <br> <?php }?>&nbsp;
                </td>
            </tr>
            <tr>
              <th colspan=2>Mesures à prendre</th>
            </tr>
            <tr>
              <td colspan="2">
                <?php if($suiviDiabete->mesure_ADO){ echo("Modification traitement antidiabétique oraux"); ?> <br> <?php }?>
                <?php if($suiviDiabete->insuline){ echo("Modification ou mise à l'insuline"); ?> <br> <?php }?>
                <?php if($suiviDiabete->mesure_hta){ echo("Correction HTA"); ?> <br> <?php }?>
                <?php if($suiviDiabete->hypl){ echo("Prise en charge hyperlipidémie"); ?> <br> <?php }?>&nbsp;
              </td>
            </tr>
            <tr>
              <th  colspan="2">Coaching infirmière</th>
            </tr>
            <tr>
              <td colspan="2">
                    <?php if($suiviDiabete->phys){ echo("Exercice physique"); ?> <br> <?php }?>
                    <?php if($suiviDiabete->diet){ echo("Mesures diététiques"); ?> <br> <?php }?>
                    <?php if($suiviDiabete->taba){ echo("Arrêt du tabac"); ?> <br> <?php }?>&nbsp;
                    <?php if($suiviDiabete->etp){ echo("ETP de groupe"); ?> <br> <?php }?>&nbsp;
              </td>
            </tr>
            <tr>
              <th >bilans:</th>
              <td>
                    <?php $additionalParams = array("Dossier:dossier:id"=>$suiviDiabete->dossier_id,
                            "SuiviDiabete:suiviDiabete:dsuivi"=>$suiviDiabete->dsuivi); ?>
                    <?php if(in_array("4",$suiviDiabete->suivi_type)){?><font color='blue'><b><?php buildLink("","4 mois","$path/controler/ActionControler.php",$param->controler,ACTION_FIND,array(PARAM_VIEW,PARAM_4MOIS),$additionalParams)?></b></font>&nbsp;<?php }?>
                    <?php if(in_array("s",$suiviDiabete->suivi_type) || /*){?><font color='green'><b><?php buildLink("","semestriel","$path/controler/ActionControler.php",$param->controler,ACTION_FIND,array(PARAM_VIEW,PARAM_SEMESTRIEL),$additionalParams)?></b></font>&nbsp;<?php }?>*/
                             in_array("a",$suiviDiabete->suivi_type)){?><font color='brown'><b><?php buildLink("","annuel","$path/controler/ActionControler.php",$param->controler,ACTION_FIND,array(PARAM_VIEW,PARAM_ANNUEL),$additionalParams)?></b></font>&nbsp;<?php }?>
              </td>
            </tr>
          </table></td>
      </tr>
    </table>

    <br><br>

    <table>
        <tr>
            <td>
                <?php customSubmitWithAlert("value='Supprimer ce suivi'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous réellement supprimer ce suivi ?"); ?>
            </td>
            <td>
                <?php customSubmit("value='Modifier ce suivi'",ACTION_FIND,array(PARAM_EDIT),$param->controler); ?>
            </td>
        </tr>
    </table>
</form>

