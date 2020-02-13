<?php
/**
 * Created by PhpStorm.
 * User: gisgo
 * Date: 16/10/18
 * Time: 12:01
 */

require_once "persistence/ConnectionInformedPDO.php";


class ActivitePhysique
{
    private $con; //variable de connexion
    public $id;
    public $dossier_id;
    public $dossier_numero;
    public $infAjout;
    public $cabinet;
    public $estActif = 1;

    
    public $evaluation_marche;
    public $poids;
    public $contour_du_ventre;

    public $distance_parcourue_en_metres;   
    public $patient_sortant_du_protocole;
    public $essoufflement;
    public $douleurs_eva;
    public $motivation;    
    public $fatigue;
    public $tps_activ_sept_jrs;
    public $tps_sed_sept_jrs;
    public $nombre_de_pas_24h;
    public $utilisation_d_un_compteur_de_pas;
    public $utilisation_d_un_compteur_description;
    public $qualite_de_vie;
    public $qualite_sommeil;
    public $modification_alimentaire;
    public $bien_etre;
    public $confiance;
    public $isolement_social_ressenti;
    public $activites_physiques_annexes;
    public $activites_physiques_annexes_description;
    public $patient_sortant_du_protocole_description;
    public $atteinte_des_objectifs;
    public $lien_sur_le_territoire;
    public $lien_sur_le_territoire_description;

    public $date_maj;
    public $dateAjout;
  

    public function __construct()
    {
        $db = ConnectionInformedPDO::getInstance();
        $this->con = $db->getDbh();
      
    }

    function ActivitePhysique(
        $id = NULL,
        $dossier_id = NULL,
        $dossier_numero = NULL,
        $evaluation_marche = NULL,
        $poids = NULL,
        $contour_du_ventre = NULL,       
        $distance_parcourue_en_metres = NULL,      
        $patient_sortant_du_protocole = NULL,      
        $essoufflement = NULL,
        $douleurs_eva = NULL,
        $motivation = NULL,
        $atteinte_des_objectifs = NULL,
        $fatigue = NULL,
        $tps_activ_sept_jrs = NULL,
        $tps_sed_sept_jrs= NULL,
        $nombre_de_pas_24h = NULL,
        $utilisation_d_un_compteur_de_pas = NULL,
        $utilisation_d_un_compteur_description = NULL,
        $qualite_de_vie = NULL,
        $qualite_sommeil = NULL,
        $modification_alimentaire = NULL,
        $bien_etre = NULL,
        $confiance = NULL,
        $isolement_social_ressenti = NULL,
        $activites_physiques_annexes = NULL,
        $activites_physiques_annexes_description = NULL,
        $patient_sortant_du_protocole_description = NULL,
        $lien_sur_le_territoire = NULL,
        $lien_sur_le_territoire_description = NULL,
        $date_maj = NULL,
        $dateAjout = NULL

    )
    {
        $this->id = $id;
        $this->dossier_id = $dossier_id;
        $this->dossier_numero = $dossier_numero;
        $this->date_maj = $date_maj;
        $this->dateAjout = $dateAjout;
    
        $this->evaluation_marche = $evaluation_marche;
        $this->poids = $poids;
        $this->contour_du_ventre = $contour_du_ventre;       
        $this->distance_parcourue_en_metres = $distance_parcourue_en_metres;     
        $this->patient_sortant_du_protocole = $patient_sortant_du_protocole;      
        $this->essoufflement = $essoufflement;
        $this->douleurs_eva = $douleurs_eva; 
        $this->motivation = $motivation;
        $this->atteinte_des_objectifs = $atteinte_des_objectifs;
        $this->fatigue = $fatigue;
        $this->tps_activ_sept_jrs = $tps_activ_sept_jrs;
        $this->tps_sed_sept_jrs = $tps_sed_sept_jrs;
        $this->nombre_de_pas_24h = $nombre_de_pas_24h;
        $this->utilisation_d_un_compteur_de_pas = $utilisation_d_un_compteur_de_pas;
        $this->utilisation_d_un_compteur_description = $utilisation_d_un_compteur_description;
        $this->qualite_de_vie = $qualite_de_vie;
        $this->qualite_sommeil = $qualite_sommeil;
        $this->modification_alimentaire = $modification_alimentaire;
        $this->bien_etre = $bien_etre;
        $this->confiance = $confiance;
        $this->isolement_social_ressenti = $isolement_social_ressenti;
        $this->activites_physiques_annexes = $activites_physiques_annexes;
        $this->activites_physiques_annexes_description = $activites_physiques_annexes_description;
        $this->patient_sortant_du_protocole_description = $patient_sortant_du_protocole_description;
        $this->lien_sur_le_territoire = $lien_sur_le_territoire;
        $this->lien_sur_le_territoire_description = $lien_sur_le_territoire_description;
    }

        


    public function getListeActivitePhysique()
    {
        $listeActivitePhysique = array();
        $sql = "SELECT   id, dossier_id, dossier_numero, infAjout, date_maj 
                FROM     activite_physique
                WHERE    infAJout = '" . $_SESSION['nom'] ."' AND cabinet = '" . $_SESSION['cabinet'] ."' AND estActif = 1
                ORDER BY id DESC 
                LIMIT    30";

        try
        {
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $res = $this->con->query($sql);

            $listeActivitePhysique = $res->fetchAll(PDO::FETCH_CLASS, 'ActivitePhysique');
            
        }
        catch (PDOException $e)
        {
            exit($e->getMessage());
        }
     
        return $listeActivitePhysique;
    }

    public function getActivitePhysiqueById($activitePhysiqueId)
    {
//        $this->getArrayAidant();
//        $this->getArrayAutreAidant();
//        $this->getArrayResExternes();
//        $listeAidant = $this->getListeAidant();
//        $listeAutreAidants = $this->getListeAutreAidants();
//        $listeResExternes = $this->getListeResExternes();
        $activitePhysisque = array();
        $sql = "SELECT  id,dossier_id,dossier_numero,evaluation_marche,poids,contour_du_ventre,
                        distance_parcourue_en_metres,infAjout,date_maj,estActif,patient_sortant_du_protocole,  
                        essoufflement,douleurs_eva,motivation,atteinte_des_objectifs,fatigue, 
                        tps_activ_sept_jrs,tps_sed_sept_jrs,nombre_de_pas_24h, 
                        utilisation_d_un_compteur_de_pas,utilisation_d_un_compteur_description,
                        qualite_de_vie,qualite_sommeil,modification_alimentaire,bien_etre, 
                        confiance,isolement_social_ressenti,activites_physiques_annexes,
                        activites_physiques_annexes_description,patient_sortant_du_protocole_description,
                        lien_sur_le_territoire, lien_sur_le_territoire_description

                FROM     activite_physique

                WHERE    id = " . $activitePhysiqueId ." AND estActif = 1";

        try
        {
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $res = $this->con->query($sql);

            $activitePhysisque = $res->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            exit($e->getMessage());
        }

        return $activitePhysisque;
    }

    public function save()
    {
        $tabDate = explode('/' , $this->date_maj);
        $this->date_maj  = $tabDate[2].'-'.$tabDate[1].'-'.$tabDate[0];

                $sql = 'INSERT INTO activite_physique (dossier_id,dossier_numero,evaluation_marche,poids,contour_du_ventre,
                                    distance_parcourue_en_metres,infAjout,date_maj,estActif,patient_sortant_du_protocole,  
                                    essoufflement,douleurs_eva,motivation,atteinte_des_objectifs,fatigue, 
                                    tps_activ_sept_jrs,tps_sed_sept_jrs,nombre_de_pas_24h, 
                                    utilisation_d_un_compteur_de_pas,utilisation_d_un_compteur_description,
                                    qualite_de_vie,qualite_sommeil,modification_alimentaire,bien_etre, 
                                    confiance,isolement_social_ressenti,activites_physiques_annexes,
                                    activites_physiques_annexes_description,patient_sortant_du_protocole_description,
                                    lien_sur_le_territoire, lien_sur_le_territoire_description,cabinet, dateAjout)

                VALUES (:dossier_id,:dossier_numero,:evaluation_marche,:poids,:contour_du_ventre,
                        :distance_parcourue_en_metres,:infAjout,:date_maj,:estActif,:patient_sortant_du_protocole,  
                        :essoufflement,:douleurs_eva,:motivation,:atteinte_des_objectifs,:fatigue, 
                        :tps_activ_sept_jrs,:tps_sed_sept_jrs,:nombre_de_pas_24h, 
                        :utilisation_d_un_compteur_de_pas,:utilisation_d_un_compteur_description,
                        :qualite_de_vie,:qualite_sommeil,:modification_alimentaire,:bien_etre, 
                        :confiance,:isolement_social_ressenti,:activites_physiques_annexes,
                        :activites_physiques_annexes_description,:patient_sortant_du_protocole_description,
                        :lien_sur_le_territoire, :lien_sur_le_territoire_description,:cabinet, :dateAjout )';

        try
        {
            $res = $this->con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

            $res->bindParam(':dossier_id',$this->dossier_id,PDO::PARAM_INT);
            $res->bindParam(':dossier_numero',$this->dossier_numero,PDO::PARAM_STR);
            $res->bindParam(':infAjout',$_SESSION['nom']);
            $res->bindParam(':cabinet',$_SESSION['cabinet']);
            $res->bindParam(':estActif',$this->estActif);
            $this->dateAjout = date("Y-m-d H:i:s");
            $res->bindParam(':dateAjout',$this->dateAjout);


            $res->bindParam(':evaluation_marche',$this->evaluation_marche,PDO::PARAM_STR);
            $res->bindParam(':poids',$this->poids,PDO::PARAM_INT);
            $res->bindParam(':contour_du_ventre',$this->contour_du_ventre,PDO::PARAM_INT);           
            $res->bindParam(':distance_parcourue_en_metres',$this->distance_parcourue_en_metres,PDO::PARAM_INT); 
            $res->bindParam(':patient_sortant_du_protocole',$this->patient_sortant_du_protocole,PDO::PARAM_STR);                       
            $res->bindParam(':date_maj',$this->date_maj);                 
            $res->bindParam(':essoufflement',$this->essoufflement,PDO::PARAM_INT);            
            $res->bindParam(':douleurs_eva',$this->douleurs_eva,PDO::PARAM_INT);
            $res->bindParam(':motivation',$this->motivation,PDO::PARAM_INT);
            $res->bindParam(':atteinte_des_objectifs',$this->atteinte_des_objectifs,PDO::PARAM_INT);
            $res->bindParam(':fatigue',$this->fatigue,PDO::PARAM_INT);
            $res->bindParam(':tps_activ_sept_jrs',$this->tps_activ_sept_jrs,PDO::PARAM_INT);
            $res->bindParam(':tps_sed_sept_jrs',$this->tps_sed_sept_jrs,PDO::PARAM_INT);
            $res->bindParam(':nombre_de_pas_24h',$this->nombre_de_pas_24h,PDO::PARAM_INT);
            $res->bindParam(':utilisation_d_un_compteur_de_pas',$this->utilisation_d_un_compteur_de_pas,PDO::PARAM_STR);
            $res->bindParam(':utilisation_d_un_compteur_description',$this->utilisation_d_un_compteur_description,PDO::PARAM_STR);
            $res->bindParam(':qualite_de_vie',$this->qualite_de_vie,PDO::PARAM_STR);
            $res->bindParam(':qualite_sommeil',$this->qualite_sommeil,PDO::PARAM_INT);
            $res->bindParam(':modification_alimentaire',$this->modification_alimentaire,PDO::PARAM_STR);            
            $res->bindParam(':bien_etre',$this->bien_etre,PDO::PARAM_INT);
            $res->bindParam(':confiance',$this->confiance,PDO::PARAM_INT);
            $res->bindParam(':isolement_social_ressenti',$this->isolement_social_ressenti,PDO::PARAM_STR);
            $res->bindParam(':activites_physiques_annexes',$this->activites_physiques_annexes,PDO::PARAM_STR);
            $res->bindParam(':activites_physiques_annexes_description',$this->activites_physiques_annexes_description,PDO::PARAM_STR);
            $res->bindParam(':patient_sortant_du_protocole_description',$this->patient_sortant_du_protocole_description,PDO::PARAM_STR);
            $res->bindParam(':lien_sur_le_territoire',$this->lien_sur_le_territoire,PDO::PARAM_STR);
            $res->bindParam(':lien_sur_le_territoire_description',$this->lien_sur_le_territoire_description,PDO::PARAM_STR);
            
        
            $res->execute();
        }
        catch (PDOException $exception)
        {
            echo $exception->getMessage();
    
        }
    }

    public function update()
    {
        $tabDate = explode('/' , $this->date_maj);
        $this->date_maj  = $tabDate[2].'-'.$tabDate[1].'-'.$tabDate[0];

        $sql = 'UPDATE activite_physique

                SET dossier_id = :dossier_id, 
                    dossier_numero = :dossier_numero,
                    evaluation_marche = :evaluation_marche,
                    poids = :poids,
                    contour_du_ventre = :contour_du_ventre,
                    distance_parcourue_en_metres = :distance_parcourue_en_metres,
                    estActif = :estActif, 
                    date_maj = :date_maj, 
                    patient_sortant_du_protocole = :patient_sortant_du_protocole,  
                    essoufflement = :essoufflement, 
                    douleurs_eva = :douleurs_eva, 
                    motivation = :motivation, 
                    atteinte_des_objectifs = :atteinte_des_objectifs,
                    fatigue = :fatigue, 
                    tps_activ_sept_jrs = :tps_activ_sept_jrs, 
                    tps_sed_sept_jrs = :tps_sed_sept_jrs,
                    nombre_de_pas_24h = :nombre_de_pas_24h, 
                    utilisation_d_un_compteur_de_pas = :utilisation_d_un_compteur_de_pas, 
                    utilisation_d_un_compteur_description = :utilisation_d_un_compteur_description,
                    qualite_de_vie = :qualite_de_vie, 
                    qualite_sommeil = :qualite_sommeil, 
                    modification_alimentaire = :modification_alimentaire, 
                    bien_etre = :bien_etre, 
                    confiance = :confiance,
                    isolement_social_ressenti = :isolement_social_ressenti, 
                    activites_physiques_annexes = :activites_physiques_annexes,
                    activites_physiques_annexes_description = :activites_physiques_annexes_description, 
                    patient_sortant_du_protocole_description = :patient_sortant_du_protocole_description,
                    lien_sur_le_territoire = :lien_sur_le_territoire,
                    lien_sur_le_territoire_description = :lien_sur_le_territoire_description


                WHERE id = :id';

        try
        {
            $this->con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $res = $this->con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $res->bindParam(':id',$this->id,PDO::PARAM_INT);
            $res->bindParam(':dossier_id',$this->dossier_id,PDO::PARAM_INT);
            $res->bindParam(':dossier_numero',$this->dossier_numero,PDO::PARAM_STR);
            $res->bindParam(':estActif',$this->estActif,PDO::PARAM_INT);

           
            $res->bindParam(':evaluation_marche',$this->evaluation_marche,PDO::PARAM_STR);
            $res->bindParam(':poids',$this->poids,PDO::PARAM_INT);
            $res->bindParam(':contour_du_ventre',$this->contour_du_ventre,PDO::PARAM_INT);           
            $res->bindParam(':distance_parcourue_en_metres',$this->distance_parcourue_en_metres,PDO::PARAM_INT);           
            $res->bindParam(':patient_sortant_du_protocole',$this->patient_sortant_du_protocole,PDO::PARAM_STR);           
            $res->bindParam(':essoufflement',$this->essoufflement,PDO::PARAM_INT);
            $res->bindParam(':douleurs_eva',$this->douleurs_eva,PDO::PARAM_INT);
            $res->bindParam(':motivation',$this->motivation,PDO::PARAM_INT);
            $res->bindParam(':atteinte_des_objectifs',$this->atteinte_des_objectifs,PDO::PARAM_INT);
            $res->bindParam(':fatigue',$this->fatigue,PDO::PARAM_INT);
            $res->bindParam(':tps_activ_sept_jrs',$this->tps_activ_sept_jrs,PDO::PARAM_INT);
            $res->bindParam(':tps_sed_sept_jrs',$this->tps_sed_sept_jrs,PDO::PARAM_INT);
            $res->bindParam(':nombre_de_pas_24h',$this->nombre_de_pas_24h,PDO::PARAM_INT);
            $res->bindParam(':utilisation_d_un_compteur_de_pas',$this->utilisation_d_un_compteur_de_pas,PDO::PARAM_STR);
            $res->bindParam(':utilisation_d_un_compteur_description',$this->utilisation_d_un_compteur_description,PDO::PARAM_STR);
            $res->bindParam(':qualite_de_vie',$this->qualite_de_vie,PDO::PARAM_STR);            
            $res->bindParam(':bien_etre',$this->bien_etre,PDO::PARAM_INT);
            $res->bindParam(':confiance',$this->confiance,PDO::PARAM_INT);
            $res->bindParam(':isolement_social_ressenti',$this->isolement_social_ressenti,PDO::PARAM_STR);
            $res->bindParam(':activites_physiques_annexes',$this->activites_physiques_annexes,PDO::PARAM_STR);
            $res->bindParam(':activites_physiques_annexes_description',$this->activites_physiques_annexes_description,PDO::PARAM_STR);            
            $res->bindParam(':modification_alimentaire',$this->modification_alimentaire,PDO::PARAM_STR);
            $res->bindParam(':qualite_sommeil',$this->qualite_sommeil,PDO::PARAM_INT);
            $res->bindParam(':date_maj',$this->date_maj);
            $res->bindParam(':patient_sortant_du_protocole_description',$this->patient_sortant_du_protocole_description,PDO::PARAM_STR);            
            $res->bindParam(':lien_sur_le_territoire',$this->lien_sur_le_territoire,PDO::PARAM_STR);
            $res->bindParam(':lien_sur_le_territoire_description',$this->lien_sur_le_territoire_description,PDO::PARAM_STR); 
            
            $res->execute();
        }
        catch (PDOException $exception)
        {
            echo $exception->getMessage();
        }
    }

    public function delete($activitePhysiqueId)
    {
        $sql = 'UPDATE activite_physique SET estActif = 0 WHERE id = :id';

        try
        {
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $res = $this->con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $res->bindParam(':id',$activitePhysiqueId, PDO::PARAM_INT);
            $res->execute();
        }
        catch (PDOException $exception)
        {
            echo $exception->getMessage();
        }
    }

    public function get($val)
    {
        return $val;
    }

    public function getBooleanVal($val)
    {
        if (isset($val))
            return 1;
        else
            return 0;
    }
}

