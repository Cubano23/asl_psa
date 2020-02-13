<?php
/**
 * Created by PhpStorm.
 * User: gisgo
 * Date: 02/07/18
 * Time: 20:05
 */

require_once ("bean/EntretienAnnuel.php");
//require_once ("persistence/DossierMapper.php");

class EntretienAnnuelControler
{
    var $mappingTable;
    private $entretienAnnuel;

    function __construct()
    {
        $this->entretienAnnuel = new EntretienAnnuel();
        $this->mappingTable =
            //TODO : Update below and create new view classes
            array
            (
                "URL_LIST"  => "listeentretienAnnuel",
                "URL_NEW"   => "manageentretienAnnuel",
                "URL_FORM"  => "entretienAnnuelformulaire"
            );
    }

    function start()
    {
        global $account;
        global $objects;
        global $param;
        global $entretienAnnuel;
//        global $dossier;
        global $listeEntretienAnnuel;
//        global $dossierId;
//        global $dossierNumero;

        if(array_key_exists("EntretienAnnuel",$objects))
            $entretienAnnuel = $objects["EntretienAnnuel"];

//        if(array_key_exists("Dossier",$objects))
//            $dossier = $objects["Dossier"];

//        //Create connection factory
//        $cfactory = new ConnectionFactory();
//
//        //create mappers
//        $dossierMapper = new DossierMapper($cfactory->getConnection());

        switch($param->action)
        {
            case ACTION_MANAGE:
                $listeEntretienAnnuel = $this->entretienAnnuel->getListeEntretiens();
                forward($this->mappingTable["URL_LIST"]);
                break;

            case ACTION_MAIN:
                forward($this->mappingTable["URL_NEW"]);
                break;

            case ACTION_NEW:
                if (!empty($entretienAnnuel->id))
                {
                    $entretienAnnuel = $this->entretienAnnuel->getEntretienAnnuelById($entretienAnnuel->id);
                }

                forward($this->mappingTable["URL_FORM"]);
                break;

            //Formulaire EntretienAnnuel où le patient n'existe pas dans PSA
            case ACTION_CREATE:
//                $dossierId = -1;
//                $dossierNumero = -1;
                forward($this->mappingTable["URL_FORM"]);
                break;

            case ACTION_SAVE:
                $entretienAnnuel->aidants = $_SESSION['aidants'];
                $entretienAnnuel->autre_aidants = $_SESSION['autre_aidants'];
                $entretienAnnuel->res_externes = $_SESSION['res_externes'];
                $entretienAnnuel->save();
                $param->action = "AM";
                $this->start();
                break;

            case ACTION_UPDATE:
                $entretienAnnuel->aidants = $_SESSION['aidants'];
                $entretienAnnuel->autre_aidants = $_SESSION['autre_aidants'];
                $entretienAnnuel->res_externes = $_SESSION['res_externes'];
                $entretienAnnuel->update();
                $param->action = "AM";
                $this->start();
                break;

            case ACTION_HARD:
                if (!empty($entretienAnnuel->id))
                {
                    $this->entretienAnnuel->delete($entretienAnnuel->id);
                }

                $param->action = "AM";
                $this->start();
                break;

            default:
                break;
        }
    }
}
