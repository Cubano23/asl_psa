<?php
/**
 * Created by PhpStorm.
 * User: gisgo
 * Date: 25/06/18
 * Time: 17:15
 */

require_once ("bean/DepistageAOMI.php");
require_once ("bean/Dossier.php");
require_once ("persistence/DossierMapper.php");

class DepistageAOMIControler
{
    var $mappingTable;
    private $dep_aomi;

    function __construct()
    {
        $this->dep_aomi = new DepistageAOMI();
        $this->mappingTable =
            //TODO : Update below and create new view classes
            array
            (
                "URL_NEW"   => "managedepistageaomi",
                "URL_FORM"  => "newdepistageaomi"
            );
    }

    function start()
    {
        global $account;
        global $objects;
        global $param;
        global $dossier;
        global $depistageAOMI;
        global $depistage_aomi;
        global $liste_historique;
        global $dateSaisie;


        if(array_key_exists("Dossier",$objects))
            $dossier = $objects["Dossier"];

        //AOMI r?cup?re de la vue
        if(array_key_exists("DepistageAOMI",$objects))
            $depistageAOMI = $objects["DepistageAOMI"];

        //Create connection factory
        $cfactory = new ConnectionFactory();

        //create mappers
        $dossierMapper = new DossierMapper($cfactory->getConnection());

        switch($param->action)
        {
            case ACTION_MANAGE:

                switch ($param->param1)
                {
                    case "Enregistrement":
                        forward($this->mappingTable["URL_NEW"], "Enregistrement effectué");
                        //R?cup?ration historique des d?pistage aomi
                        $liste_historique = $this->dep_aomi->getHistoriqueDepistageSansFiltre($account->cabinet);
                        break;
                    default:
                        //R?cup?ration historique des d?pistage aomi
                        $liste_historique = $this->dep_aomi->getHistoriqueDepistageSansFiltre($account->cabinet);
                        forward($this->mappingTable["URL_NEW"]);
                        break;
                }
                break;

            case ACTION_NEW:
                $dossier->cabinet = $account->cabinet;
                $dossier = $dossierMapper->isValidNumber($dossier);
                if (!$dossier)
                    forward($this->mappingTable["URL_NEW"],"Dossier non trouvé");

                $depistage_aomi['dossier_id'] = $dossier[0]->id;
                $depistage_aomi['dossier_numero'] = $dossier[0]->numero;
                $dateSaisie = $depistageAOMI->dateSaisie ;

                //R?cup?ration historique des d?pistage aomi
                $liste_historique = $this->dep_aomi->getHistoriqueDepistage($dossier[0]->id, $account->cabinet);

                switch ($param->param1)
                {
                    case "Enregistrement":
                        forward($this->mappingTable["URL_FORM"], "Enregistrement effectué");
                        break;
                    default:
                        forward($this->mappingTable["URL_FORM"]);
                        break;
                }
                break;

            case ACTION_SAVE:
                $dossier = new Dossier(null, null, $depistageAOMI->dossier_numero, null, null, null, null, null);
                $objects["Dossier"] = $dossier;

//                $depistageAOMI->dateSaisie = new DateTime();

                $depistageAOMI->dateSaisie = new DateTime($depistageAOMI->dateSaisie);

                if (($depistageAOMI->ipsd != NULL) && ($depistageAOMI->ipsd != 0) && ($depistageAOMI->ipsg != NULL) && ($depistageAOMI->ipsg != 0) && ($depistageAOMI->eda != NULL))
                    $depistageAOMI->save();

                $param->action = "AM";
                $param->param1 = "Enregistrement";
                $this->start();
                break;

            /*case ACTION_UPDATE:
                $depistageAOMI->update();
                $param->action = "AN";
                $this->start();
                break;*/

            default:
                break;
        }
    }
}
