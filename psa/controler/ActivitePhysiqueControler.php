<?php
/**
 * Created by PhpStorm.
 * User: gisgo
 * Date: 16/10/18
 * Time: 12:01
 */

require_once ("bean/ActivitePhysique.php");
require_once ("persistence/DossierMapper.php");

class ActivitePhysiqueControler
{
    var $mappingTable;
    private $activitePhysique;

    function __construct()
    {
        $this->activitePhysique = new ActivitePhysique();
        $this->mappingTable =
            //TODO : Update below and create new view classes
            array
            (
                "URL_LIST"  => "listeactivitephysique",
                "URL_NEW"   => "manageactivitephysique",
                "URL_FORM"  => "activitephysiqueformulaire"
            );
    }

    function start()
    {
        global $account;
        global $objects;
        global $param;
        global $activitePhysique;
        global $dossier;
        global $listeActivitePhysique;
        global $dossierId;
        global $dossierNumero;

        if(array_key_exists("ActivitePhysique",$objects))
            $activitePhysique = $objects["ActivitePhysique"];

        if(array_key_exists("Dossier",$objects))
            $dossier = $objects["Dossier"];

        //Create connection factory
        $cfactory = new ConnectionFactory();

        //create mappers
        $dossierMapper = new DossierMapper($cfactory->getConnection());

        switch($param->action)
        {
            case ACTION_MANAGE:
                $listeActivitePhysique = $this->activitePhysique->getListeActivitePhysique();
                forward($this->mappingTable["URL_LIST"]);
                break;

            case ACTION_MAIN:
                forward($this->mappingTable["URL_NEW"]);
                break;

            case ACTION_NEW:
                if (!empty($activitePhysique->id))
                {
                    $activitePhysique = $this->activitePhysique->getActivitePhysiqueById($activitePhysique->id);
                }
                else
                {
                    $dossier->cabinet = $account->cabinet;
                    $dossier = $dossierMapper->isValidNumber($dossier);
                    if (!$dossier)
                        forward($this->mappingTable["URL_NEW"],"Dossier non trouvé");
                }

                forward($this->mappingTable["URL_FORM"]);
                break;

            //Formulaire Activite Physique où le patient n'existe pas dans PSA
            case ACTION_CREATE:
                $dossierId = -1;
                $dossierNumero = -1;
                forward($this->mappingTable["URL_FORM"]);
                break;

            case ACTION_SAVE:
                $activitePhysique->save();
                $param->action = "AM";
                $this->start();
                break;

            case ACTION_UPDATE:
                $activitePhysique->update();
                $param->action = "AM";
                $this->start();
                break;

            case ACTION_HARD:
                if (!empty($activitePhysique->id))
                {
                    $this->activitePhysique->delete($activitePhysique->id);
                }

                $param->action = "AM";
                $this->start();
                break;

            default:
                break;
        }
    }
}
