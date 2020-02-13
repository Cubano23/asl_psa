<?php
/**
 * Created by PhpStorm.
 * User: gisgo
 * Date: 16/04/18
 * Time: 14:30
 */

require_once ("bean/Fragilite.php");
require_once ("persistence/DossierMapper.php");

class FragiliteControler
{
    var $mappingTable;
    private $fragilite;

    function __construct()
    {
        $this->fragilite = new Fragilite();
        $this->mappingTable =
            //TODO : Update below and create new view classes
            array
            (
                "URL_LIST"  => "listefragilite",
                "URL_NEW"   => "managefragilite",
                "URL_FORM"  => "fragiliteformulaire"
            );
    }

    function start()
    {
        global $account;
        global $objects;
        global $param;
        global $fragilite;
        global $dossier;
        global $listeFrag;
        global $dossierId;
        global $dossierNumero;

        if(array_key_exists("Fragilite",$objects))
            $fragilite = $objects["Fragilite"];

        if(array_key_exists("Dossier",$objects))
            $dossier = $objects["Dossier"];

        //Create connection factory
        $cfactory = new ConnectionFactory();

        //create mappers
        $dossierMapper = new DossierMapper($cfactory->getConnection());

        switch($param->action)
        {
            case ACTION_MANAGE:
                $listeFrag = $this->fragilite->getListeFragilite();
                forward($this->mappingTable["URL_LIST"]);
                break;

            case ACTION_MAIN:
                forward($this->mappingTable["URL_NEW"]);
                break;

            case ACTION_NEW:
                if (!empty($fragilite->id))
                {
                    $this->fragilite->aidants = $_SESSION['aidants'];
                    $this->fragilite->autre_aidants = $_SESSION['autre_aidants'];
                    $this->fragilite->res_externes = $_SESSION['res_externes'];
                    $fragilite = $this->fragilite->getFragiliteById($fragilite->id);
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

            //Formulaire Fragilité où le patient n'existe pas dans PSA
            case ACTION_CREATE:
                $dossierId = -1;
                $dossierNumero = -1;
                forward($this->mappingTable["URL_FORM"]);
                break;

            case ACTION_SAVE:
                $fragilite->aidants = $_SESSION['aidants'];
                $fragilite->autre_aidants = $_SESSION['autre_aidants'];
                $fragilite->res_externes = $_SESSION['res_externes'];
                $fragilite->save();
                $param->action = "AM";
                $this->start();
                break;

            case ACTION_UPDATE:
                $fragilite->aidants = $_SESSION['aidants'];
                $fragilite->autre_aidants = $_SESSION['autre_aidants'];
                $fragilite->res_externes = $_SESSION['res_externes'];
                $fragilite->update();
                $param->action = "AM";
                $this->start();
                break;

            case ACTION_HARD:
                if (!empty($fragilite->id))
                {
                    $this->fragilite->delete($fragilite->id);
                }

                $param->action = "AM";
                $this->start();
                break;

            default:
                break;
        }
    }
}
