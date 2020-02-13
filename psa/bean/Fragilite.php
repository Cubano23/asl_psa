<?php
/**
 * Created by PhpStorm.
 * User: gisgo
 * Date: 17/04/18
 * Time: 16:31
 */

require_once "persistence/ConnectionInformedPDO.php";


class Fragilite
{
    private $con; //variable de connexion
    public $id;
    public $dossier_id;
    public $dossier_numero;
    public $lieuVisite;
    public $estSeul;
    public $animaldeCompagnie;

        public $aidants;
        public $arrayAidant = array();
        public $aidantActuel;

        public $autre_aidants;
        public $arrayAutreAidant = array();
        public $autreAidantActuel;

    public $ressourcesFamilial;
    public $ressourcesAmical;
    public $logementAdapte;
    public $insecuriteFinanciere;
    public $niveauScolaire;
    public $frEcrit;
    public $frParle;
    public $couvertureSociale;
    public $pathChronique;
    public $medPrescSupCinq;
    public $niveauObservance;
    public $hospitalisationRecenteProg;
    public $hospitalisationRecenteNonProg;
    public $nombreTotalHospit;
    public $dateSortieDerniereHospit;
    public $fragPsych;
    public $fragEco;
    public $fragSoc;
    public $fragSom;
    public $trblCogn;
    public $iadl;
    public $gds;
    public $evalGS;
    public $epices;
    public $activitePhy;
    public $perimetreMarche;
    public $vitesseMarche;
    public $vitesseMarche4m4s;
    public $arretConduite;
    public $diffVieQuot;
    public $diffIntel;
    public $protectionJudiciaire;
    public $diminutionCapSens;
    public $diminutionCapSensInterne;
    public $diminutionCapSensExterne ;
    public $perturbationSommeil;
    public $variationPoids;
    public $imc;
    public $dureedepuisdouleur;
    public $dureedepuisperturbationsommeil;
    public $dureedepuisdiminutionCapSensInterne;
    public $dureedepuisdiminutionCapSensExterne;
    public $douleur;
    public $addictAlcool;
    public $addictTabac;
    public $addictMed;
    public $addictCanabis;
    public $autreAddiction;
    public $emotionLimitante;
    public $incapExpression;
    public $isolementPhy;
    public $abandon;
    public $submerge;
    public $epuisement;
    public $maintenanceFM;

        public $res_externes;
        public $arrayResExternes = array();
        public $ressourceExternes;

    public $subjectiviteInf;
    public $autresStrategies;

    public $autresOutils;


    public $infAjout;
    public $cabinet;
    public $dateAjout;
    public $estActif = 1;

    public function __construct()
    {
        $db = ConnectionInformedPDO::getInstance();
        $this->con = $db->getDbh();
    }

    function Fragilite(
        $id = NULL,
        $dossier_id = NULL,
        $dossier_numero = NULL,
        $lieuVisite = NULL,
        $estSeul = NULL,
        $animaldeCompagnie = NULL,
        $aidantActuel = NULL,
        $autreAidantActuel = NULL,
        $ressourcesFamilial = NULL,
        $ressourcesAmical = NULL,
        $logementAdapte = NULL,
        $insecuriteFinanciere = NULL,
        $niveauScolaire = NULL,
        $frEcrit = NULL,
        $frParle = NULL,
        $couvertureSociale = NULL,
        $pathChronique = NULL,
        $medPrescSupCinq = NULL,
        $niveauObservance = NULL,
        $hospitalisationRecenteProg = NULL,
        $hospitalisationRecenteNonProg = NULL,
        $nombreTotalHospit = NULL,
        $dateSortieDerniereHospit = NULL,
        $fragPsych = NULL,
        $fragEco = NULL,
        $fragSoc = NULL,
        $fragSom = NULL,
        $trblCogn = NULL,
        $iadl = NULL,
        $gds = NULL,
        $evalGS = NULL,
        $epices = NULL,
        $activitePhy = NULL,
        $perimetreMarche = NULL,
        $vitesseMarche = NULL,
        $vitesseMarche4m4s = NULL,
        $arretConduite = NULL,
        $diffVieQuot = NULL,
        $diffIntel = NULL,
        $protectionJudiciaire = NULL,
        $diminutionCapSensInterne= NULL,
        $diminutionCapSensExterne = NULL,
        $perturbationSommeil = NULL,
        $variationPoids = NULL,
        $imc = NULL,
        $dureedepuisdouleur = NULL,
        $dureedepuisperturbationsommeil = NULL,
        $dureedepuisdiminutionCapSensInterne = NULL,
        $dureedepuisdiminutionCapSensExterne = NULL,
        $douleur = NULL,
        $addictAlcool = NULL,
        $addictTabac = NULL,
        $addictMed = NULL,
        $addictCanabis = NULL,
        $autreAddiction = NULL,
        $emotionLimitante = NULL,
        $incapExpression = NULL,
        $isolementPhy = NULL,
        $abandon = NULL,
        $submerge = NULL,
        $epuisement = NULL,
        $maintenanceFM = NULL,
        $ressourceExternes = NULL,
        $subjectiviteInf = NULL,
        $autresStrategies = NULL,
        $autresOutils = NULL
    )
    {
        $this->id = $id;
        $this->dossier_id = $dossier_id;
        $this->dossier_numero = $dossier_numero;
        $this->lieuVisite = $lieuVisite;
        $this->estSeul = $estSeul;
        $this->animaldeCompagnie = $animaldeCompagnie;
        $this->aidantActuel = $aidantActuel;
        $this->autreAidantActuel = $autreAidantActuel;
        $this->ressourcesFamilial = $ressourcesFamilial;
        $this->ressourcesAmical = $ressourcesAmical;
        $this->logementAdapte = $logementAdapte;
        $this->insecuriteFinanciere = $insecuriteFinanciere;
        $this->niveauScolaire = $niveauScolaire;
        $this->frEcrit = $frEcrit;
        $this->frParle = $frParle;
        $this->couvertureSociale = $couvertureSociale;
        $this->pathChronique = $pathChronique;
        $this->medPrescSupCinq = $medPrescSupCinq;
        $this->niveauObservance = $niveauObservance;
        $this->hospitalisationRecenteProg = $hospitalisationRecenteProg;
        $this->hospitalisationRecenteNonProg = $hospitalisationRecenteNonProg;
        $this->nombreTotalHospit = $nombreTotalHospit;
        $this->dateSortieDerniereHospit = $dateSortieDerniereHospit;
        $this->fragPsych = $fragPsych;
        $this->fragEco = $fragEco;
        $this->fragSoc = $fragSoc;
        $this->fragSom = $fragSom;
        $this->trblCogn = $trblCogn;
        $this->iadl = $iadl;
        $this->gds = $gds;
        $this->evalGS = $evalGS;
        $this->epices = $epices;
        $this->activitePhy = $activitePhy;
        $this->perimetreMarche = $perimetreMarche;
        $this->vitesseMarche = $vitesseMarche;
        $this->vitesseMarche4m4s = $vitesseMarche4m4s;
        $this->arretConduite = $arretConduite;
        $this->diffVieQuot = $diffVieQuot;
        $this->diffIntel = $diffIntel;
        $this->protectionJudiciaire = $protectionJudiciaire;
        $this->diminutionCapSensInterne = $diminutionCapSensInterne;
        $this->diminutionCapSensExterne = $diminutionCapSensExterne;
        $this->perturbationSommeil = $perturbationSommeil;
        $this->variationPoids = $variationPoids;
        $this->imc = $imc;
        $this->dureedepuisdouleur = $dureedepuisdouleur;
        $this->dureedepuisperturbationsommeil = $dureedepuisperturbationsommeil;
        $this->dureedepuisdiminutionCapSensInterne = $dureedepuisdiminutionCapSensInterne;
        $this->dureedepuisdiminutionCapSensExterne = $dureedepuisdiminutionCapSensExterne;
        $this->douleur = $douleur;
        $this->addictAlcool = $addictAlcool;
        $this->addictTabac = $addictTabac;
        $this->addictMed = $addictMed;
        $this->addictCanabis = $addictCanabis;
        $this->autreAddiction = $autreAddiction;
        $this->emotionLimitante = $emotionLimitante;
        $this->incapExpression = $incapExpression;
        $this->isolementPhy = $isolementPhy;
        $this->abandon = $abandon;
        $this->submerge = $submerge;
        $this->epuisement = $epuisement;
        $this->maintenanceFM = $maintenanceFM;
        $this->ressourceExternes = $ressourceExternes;
        $this->subjectiviteInf = $subjectiviteInf;
        $this->autresStrategies = $autresStrategies;
        $this->autresOutils = $autresOutils;

    }

    public function getArrayAidant()
    {
        foreach ($this->aidants as $aidant => $valeur)
            $this->arrayAidant[] = $aidant;
    }

    public function getListeAidant()
    {
        $res = "";
        foreach ($this->arrayAidant as $aidant)
            $res .= $aidant. ', ';
        return $res;
    }

    public function getListeParamsAidant()
    {
        $res = "";
        foreach ($this->arrayAidant as $aidant)
            $res .= ':' . $aidant . ', ';
        return $res;
    }

    public function getListeAidantWithParams()
    {
        $res = "";
        foreach ($this->arrayAidant as $aidant)
            $res .= $aidant . ' = :' . $aidant . ', ';
        return $res;
    }

    public function getArrayAutreAidant()
    {
        foreach ($this->autre_aidants as $autre_aidant => $valeur)
            $this->arrayAutreAidant[] = $autre_aidant;
    }

    public function getListeAutreAidants()
    {
        $res = "";
        foreach ($this->arrayAutreAidant as $autre_aidant)
            $res .= $autre_aidant. ', ';
        return $res;
    }

    public function getListeParamsAutreAidants()
    {
        $res = "";
        foreach ($this->arrayAutreAidant as $autre_aidant)
            $res .= ':' . $autre_aidant . ', ';
        return $res;
    }

    public function getListeAutreAidantsWithParams()
    {
        $res = "";
        foreach ($this->arrayAutreAidant as $autre_aidant)
            $res .= $autre_aidant . ' = :' . $autre_aidant . ', ';
        return $res;
    }

    public function getArrayResExternes()
    {
        foreach ($this->res_externes as $res_externe => $valeur)
            $this->arrayResExternes[] = $res_externe;
    }

    public function getListeResExternes()
    {
        $res = "";
        foreach ($this->arrayResExternes as $res_externe)
            $res .= $res_externe. ', ';
        return $res;
    }

    public function getListeParamsResExternes()
    {
        $res = "";
        foreach ($this->arrayResExternes as $res_externe)
            $res .= ':' . $res_externe . ', ';
        return $res;
    }

    public function getListeResExternesExternesWithParams()
    {
        $res = "";
        foreach ($this->arrayResExternes as $res_externe)
            $res .= $res_externe . ' = :' . $res_externe . ', ';
        return $res;
    }

    public function getListeFragilite()
    {
        $listeFrag = array();
        $sql = "SELECT   id, dossier_id, dossier_numero, infAjout, dateAjout 
                FROM     fragilite
                WHERE    infAJout = '" . $_SESSION['nom'] ."' AND cabinet = '" . $_SESSION['cabinet'] ."' AND estActif = 1
                ORDER BY id DESC 
                LIMIT    30";

        try
        {
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $res = $this->con->query($sql);

            $listeFrag = $res->fetchAll(PDO::FETCH_CLASS, 'Fragilite');
        }
        catch (PDOException $e)
        {
            exit($e->getMessage());
        }

        return $listeFrag;
    }

    public function getFragiliteById($fragiliteId)
    {
        $this->getArrayAidant();
        $this->getArrayAutreAidant();
        $this->getArrayResExternes();
        $listeAidant = $this->getListeAidant();
        $listeAutreAidants = $this->getListeAutreAidants();
        $listeResExternes = $this->getListeResExternes();
        $frag = array();
        $sql = "SELECT   id, dossier_id, dossier_numero, lieu_visite, estSeul,animaldeCompagnie, " . $listeAidant . $listeAutreAidants . " ressourcesFamSuff, ressourcesAmSuff, logementAdapte, insecFinanciere, niveauScolaire, frEcrit, frParle, couvSocActive, pathChronique, medPrescSupCinq, niveauObservance, hospitalisationRecenteProg,hospitalisationRecenteNonProg,nombreTotalHospit,dateSortieDerniereHospit, fragPsych, fragEco, fragSoc, fragSom, trblCogn, iadl, gds, evalGS, epices, activitePhy, perimetreMarche, vitesseMarche ,vitesseMarche4m4s, arretConduite, diffVieQuot, diffIntell, protectionJud, diminutionCapSensInterne, diminutionCapSensExterne, perturbationSommeil, variationPoids, imc, dureedepuisdouleur, dureedepuisperturbationsommeil , dureedepuisdiminutionCapSensInterne, dureedepuisdiminutionCapSensExterne, douleur, addictAlcool, addictTabac, addictMed, addictCanabis, autreAddiction, emotionLimitante, incapExpression, isolementPhy, abandon, submerge, epuisement, maintenanceFM, " . $listeResExternes . "subjectiviteInf, autresStrategies, autresOutils ,infAjout, dateAjout, estActif
                FROM     fragilite
                WHERE    id = " . $fragiliteId ." AND estActif = 1";

        try
        {
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $res = $this->con->query($sql);

            $frag = $res->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            exit($e->getMessage());
        }

        return $frag;
    }

    public function save()
    {
        $this->getArrayAidant();
        $this->getArrayAutreAidant();
        $this->getArrayResExternes();
        $listeAidant = $this->getListeAidant();
        $listeAutreAidants = $this->getListeAutreAidants();
        $listeResExternes = $this->getListeResExternes();
        $listeParamsAidant = $this->getListeParamsAidant();
        $listeParamsAutreAidants = $this->getListeParamsAutreAidants();
        $listeParamsResExternes = $this->getListeParamsResExternes();

        $sql = 'INSERT INTO fragilite (dossier_id, dossier_numero, lieu_visite, estSeul,animaldeCompagnie, ' . $listeAidant . $listeAutreAidants . 'ressourcesFamSuff, ressourcesAmSuff, logementAdapte, insecFinanciere, niveauScolaire, frEcrit, frParle, couvSocActive, pathChronique, medPrescSupCinq, niveauObservance, hospitalisationRecenteProg, hospitalisationRecenteNonProg,nombreTotalHospit,dateSortieDerniereHospit, fragPsych, fragEco, fragSoc, fragSom, trblCogn, iadl, gds, evalGS, epices, activitePhy, perimetreMarche, vitesseMarche ,vitesseMarche4m4s, arretConduite, diffVieQuot, diffIntell, protectionJud, diminutionCapSensInterne,diminutionCapSensExterne, perturbationSommeil, variationPoids, imc, dureedepuisdouleur, dureedepuisperturbationsommeil , dureedepuisdiminutionCapSensInterne, dureedepuisdiminutionCapSensExterne, douleur, addictAlcool, addictTabac, addictMed, addictCanabis, autreAddiction, emotionLimitante, incapExpression, isolementPhy, abandon, submerge, epuisement, maintenanceFM, ' . $listeResExternes . 'subjectiviteInf, autresStrategies, autresOutils, infAjout, cabinet, dateAjout, estActif) VALUES (:dossier_id, :dossier_numero, :lieuVisite, :estSeul, :animaldeCompagnie, ' . $listeParamsAidant . $listeParamsAutreAidants . ':ressourcesFamilial, :ressourcesAmical, :logementAdapte, :insecuriteFinanciere, :niveauScolaire, :frEcrit, :frParle, :couvertureSociale, :pathChronique, :medPrescSupCinq, :niveauObservance, :hospitalisationRecenteProg, :hospitalisationRecenteNonProg, :nombreTotalHospit, :dateSortieDerniereHospit, :fragPsych, :fragEco, :fragSoc, :fragSom, :trblCogn, :iadl, :gds, :evalGS, :epices, :activitePhy, :perimetreMarche, :vitesseMarche , :vitesseMarche4m4s ,:arretConduite, :diffVieQuot, :diffIntel, :protectionJudiciaire, :diminutionCapSensInterne, :diminutionCapSensExterne , :perturbationSommeil, :variationPoids, :imc, :dureedepuisdouleur, :dureedepuisperturbationsommeil , :dureedepuisdiminutionCapSensInterne, :dureedepuisdiminutionCapSensExterne,:douleur, :addictAlcool, :addictTabac, :addictMed, :addictCanabis, :autreAddiction, :emotionLimitante, :incapExpression, :isolementPhy, :abandon, :submerge, :epuisement, :maintenanceFM, ' . $listeParamsResExternes . ':subjectiviteInf, :autresStrategies, :autresOutils, :infAjout, :cabinet, :dateAjout, :estActif)';

        try
        {
            $res = $this->con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $res->bindParam(':dossier_id',$this->dossier_id, PDO::PARAM_INT);
            $res->bindParam(':dossier_numero',$this->dossier_numero, PDO::PARAM_STR);
            $res->bindParam(':lieuVisite',$this->lieuVisite, PDO::PARAM_STR);
            $res->bindParam(':estSeul',$this->estSeul, PDO::PARAM_INT);

            $res->bindParam(':animaldeCompagnie',$this->animaldeCompagnie, PDO::PARAM_INT);

            for ($i = 0; $i < count($this->arrayAidant); $i++)
            {
                $correspondance = false;
                for ($j = 0; $j < count($this->aidantActuel); $j++)
                {
                    if ($this->arrayAidant[$i] == $this->aidantActuel[$j])
                    {
                        $res->bindParam(':' . $this->arrayAidant[$i],$this->get(1), PDO::PARAM_INT);
                        $correspondance = true;
                    }
                }
                if ($correspondance == false)
                    $res->bindParam(':' . $this->arrayAidant[$i],$this->get(0), PDO::PARAM_INT);
            }

            for ($i = 0; $i < count($this->arrayAutreAidant); $i++)
            {
                $correspondance = false;
                for ($j = 0; $j < count($this->autreAidantActuel); $j++)
                {
                    if ($this->arrayAutreAidant[$i] == $this->autreAidantActuel[$j])
                    {
                        $res->bindParam(':' . $this->arrayAutreAidant[$i],$this->get(1), PDO::PARAM_INT);
                        $correspondance = true;
                    }
                }
                if ($correspondance == false)
                    $res->bindParam(':' . $this->arrayAutreAidant[$i],$this->get(0), PDO::PARAM_INT);
            }

            $res->bindParam(':ressourcesFamilial',$this->ressourcesFamilial, PDO::PARAM_INT);
            $res->bindParam(':ressourcesAmical',$this->ressourcesAmical, PDO::PARAM_INT);
            $res->bindParam(':logementAdapte',$this->logementAdapte, PDO::PARAM_INT);
            $res->bindParam(':insecuriteFinanciere',$this->insecuriteFinanciere, PDO::PARAM_INT);
            $res->bindParam(':niveauScolaire',$this->niveauScolaire);
            $res->bindParam(':frEcrit',$this->frEcrit, PDO::PARAM_INT);
            $res->bindParam(':frParle',$this->frParle, PDO::PARAM_INT);
            $res->bindParam(':couvertureSociale',$this->couvertureSociale, PDO::PARAM_INT);
            $res->bindParam(':pathChronique',$this->pathChronique, PDO::PARAM_INT);
            $res->bindParam(':medPrescSupCinq',$this->medPrescSupCinq, PDO::PARAM_INT);
            $res->bindParam(':niveauObservance',$this->niveauObservance, PDO::PARAM_INT);
            $res->bindParam(':hospitalisationRecenteProg',$this->hospitalisationRecenteProg, PDO::PARAM_INT);
            $res->bindParam(':hospitalisationRecenteNonProg',$this->hospitalisationRecenteNonProg, PDO::PARAM_INT);
            $res->bindParam(':nombreTotalHospit',$this->nombreTotalHospit, PDO::PARAM_INT);
            $res->bindParam(':dateSortieDerniereHospit',$this->dateSortieDerniereHospit, PDO::PARAM_INT);
            $res->bindParam(':fragPsych',$this->fragPsych, PDO::PARAM_INT);
            $res->bindParam(':fragEco',$this->fragEco, PDO::PARAM_INT);
            $res->bindParam(':fragSoc',$this->fragSoc, PDO::PARAM_INT);
            $res->bindParam(':fragSom',$this->fragSom, PDO::PARAM_INT);
            $res->bindParam(':trblCogn',$this->trblCogn, PDO::PARAM_INT);
            $res->bindParam(':iadl',$this->iadl, PDO::PARAM_INT);
            $res->bindParam(':gds',$this->gds, PDO::PARAM_INT);
            $res->bindParam(':evalGS',$this->evalGS, PDO::PARAM_INT);
            $res->bindParam(':epices',$this->epices, PDO::PARAM_INT);
            $res->bindParam(':activitePhy',$this->activitePhy, PDO::PARAM_INT);
            $res->bindParam(':perimetreMarche',$this->perimetreMarche, PDO::PARAM_INT);
            $res->bindParam(':vitesseMarche',$this->vitesseMarche, PDO::PARAM_INT);
            $res->bindParam(':vitesseMarche4m4s',$this->vitesseMarche4m4s, PDO::PARAM_INT);
            $res->bindParam(':arretConduite',$this->arretConduite, PDO::PARAM_INT);
            $res->bindParam(':diffVieQuot',$this->diffVieQuot, PDO::PARAM_INT);
            $res->bindParam(':diffIntel',$this->diffIntel, PDO::PARAM_INT);
            $res->bindParam(':protectionJudiciaire',$this->protectionJudiciaire, PDO::PARAM_INT);
            $res->bindParam(':diminutionCapSensInterne',$this->diminutionCapSensInterne, PDO::PARAM_INT);
            $res->bindParam(':diminutionCapSensExterne',$this->diminutionCapSensExterne, PDO::PARAM_INT);

            $res->bindParam(':perturbationSommeil',$this->perturbationSommeil, PDO::PARAM_INT);
            $res->bindParam(':variationPoids',$this->variationPoids, PDO::PARAM_INT);
            $res->bindParam(':imc',$this->imc, PDO::PARAM_STR);
            $res->bindParam(':dureedepuisdouleur',$this->dureedepuisdouleur, PDO::PARAM_STR);
            $res->bindParam(':dureedepuisperturbationsommeil',$this->dureedepuisperturbationsommeil, PDO::PARAM_STR);
            $res->bindParam(':dureedepuisdiminutionCapSensInterne',$this->dureedepuisdiminutionCapSensInterne, PDO::PARAM_STR);
            $res->bindParam(':dureedepuisdiminutionCapSensExterne',$this->dureedepuisdiminutionCapSensExterne, PDO::PARAM_STR);

            $res->bindParam(':douleur',$this->douleur, PDO::PARAM_INT);
            $res->bindParam(':addictAlcool',$this->addictAlcool, PDO::PARAM_INT);
            $res->bindParam(':addictTabac',$this->addictTabac, PDO::PARAM_INT);
            $res->bindParam(':addictMed',$this->addictMed, PDO::PARAM_INT);
            $res->bindParam(':addictCanabis',$this->addictCanabis, PDO::PARAM_INT);
            $res->bindParam(':autreAddiction',$this->getBooleanVal($this->autreAddiction), PDO::PARAM_INT);
            $res->bindParam(':emotionLimitante',$this->emotionLimitante, PDO::PARAM_INT);
            $res->bindParam(':incapExpression',$this->incapExpression, PDO::PARAM_INT);
            $res->bindParam(':isolementPhy',$this->isolementPhy, PDO::PARAM_INT);
            $res->bindParam(':abandon',$this->abandon, PDO::PARAM_INT);
            $res->bindParam(':submerge',$this->submerge, PDO::PARAM_INT);
            $res->bindParam(':epuisement',$this->epuisement, PDO::PARAM_INT);
            $res->bindParam(':maintenanceFM',$this->maintenanceFM, PDO::PARAM_INT);

            for ($i = 0; $i < count($this->arrayResExternes); $i++)
            {
                $correspondance = false;
                for ($j = 0; $j < count($this->ressourceExternes); $j++)
                {
                    if ($this->arrayResExternes[$i] == $this->ressourceExternes[$j])
                    {
                        $res->bindParam(':' . $this->arrayResExternes[$i],$this->get(1), PDO::PARAM_INT);
                        $correspondance = true;
                    }
                }
                if ($correspondance == false)
                    $res->bindParam(':' . $this->arrayResExternes[$i],$this->get(0), PDO::PARAM_INT);
            }


            $res->bindParam(':subjectiviteInf',$this->subjectiviteInf, PDO::PARAM_STR);
             $res->bindParam(':autresStrategies',$this->autresStrategies, PDO::PARAM_STR);
            $res->bindParam(':autresOutils',$this->autresOutils, PDO::PARAM_STR);

            $res->bindParam(':infAjout',$_SESSION['nom']);
            $res->bindParam(':cabinet',$_SESSION['cabinet']);
                $date = date('Y-m-d H:i:s');
            $res->bindParam(':dateAjout',$date);
            $res->bindParam(':estActif',$this->estActif);
            $res->execute();
        }
        catch (PDOException $exception)
        {
            echo $exception->getMessage();
        }
    }

    public function update()
    {
        $this->getArrayAidant();
        $this->getArrayAutreAidant();
        $this->getArrayResExternes();
        $listeAidant = $this->getListeAidantWithParams();
        $listeAutreAidants = $this->getListeAutreAidantsWithParams();
        $listeResExternes = $this->getListeResExternesExternesWithParams();

        $sql = 'UPDATE fragilite SET dossier_id = :dossier_id, dossier_numero = :dossier_numero, lieu_visite = :lieu_visite, estSeul = :estSeul, animaldeCompagnie =:animaldeCompagnie, ' . $listeAidant . $listeAutreAidants . 'ressourcesFamSuff = :ressourcesFamSuff, ressourcesAmSuff = :ressourcesAmSuff, logementAdapte = :logementAdapte, insecFinanciere = :insecFinanciere, niveauScolaire = :niveauScolaire, frEcrit = :frEcrit, frParle = :frParle, couvSocActive = :couvSocActive, pathChronique = :pathChronique, medPrescSupCinq = :medPrescSupCinq, niveauObservance = :niveauObservance, hospitalisationRecenteProg = :hospitalisationRecenteProg, hospitalisationRecenteNonProg = :hospitalisationRecenteNonProg, nombreTotalHospit = :nombreTotalHospit, dateSortieDerniereHospit = :dateSortieDerniereHospit, fragPsych = :fragPsych, fragEco = :fragEco, fragSoc = :fragSoc, fragSom = :fragSom, trblCogn = :trblCogn, iadl = :iadl, gds = :gds, evalGS = :evalGS, epices = :epices, activitePhy = :activitePhy, perimetreMarche = :perimetreMarche, vitesseMarche = :vitesseMarche , vitesseMarche4m4s = :vitesseMarche4m4s,arretConduite = :arretConduite, diffVieQuot = :diffVieQuot, diffIntell = :diffIntell, protectionJud = :protectionJud, diminutionCapSensInterne = :diminutionCapSensInterne, diminutionCapSensExterne = :diminutionCapSensExterne, perturbationSommeil = :perturbationSommeil, variationPoids = :variationPoids, imc = :imc, dureedepuisdouleur = :dureedepuisdouleur, dureedepuisperturbationsommeil = :dureedepuisperturbationsommeil, dureedepuisdiminutionCapSensInterne = :dureedepuisdiminutionCapSensInterne, dureedepuisdiminutionCapSensExterne = :dureedepuisdiminutionCapSensExterne, douleur = :douleur, addictAlcool = :addictAlcool, addictTabac = :addictTabac, addictMed = :addictMed, addictCanabis = :addictCanabis, autreAddiction = :autreAddiction, emotionLimitante = :emotionLimitante, incapExpression = :incapExpression, isolementPhy = :isolementPhy, abandon = :abandon, submerge = :submerge, epuisement = :epuisement, maintenanceFM = :maintenanceFM, ' . $listeResExternes . 'subjectiviteInf = :subjectiviteInf, autresStrategies = :autresStrategies, autresOutils =:autresOutils, estActif = :estActif WHERE id = :id';

        try
        {
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $res = $this->con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $res->bindParam(':id',$this->id, PDO::PARAM_INT);
            $res->bindParam(':dossier_id',$this->dossier_id, PDO::PARAM_INT);
            $res->bindParam(':dossier_numero',$this->dossier_numero, PDO::PARAM_STR);
            $res->bindParam(':lieu_visite',$this->lieuVisite, PDO::PARAM_STR);
            $res->bindParam(':estSeul',$this->estSeul, PDO::PARAM_INT);

            $res->bindParam(':animaldeCompagnie',$this->animaldeCompagnie, PDO::PARAM_INT);


            for ($i = 0; $i < count($this->arrayAidant); $i++)
            {
                $correspondance = false;
                for ($j = 0; $j < count($this->aidantActuel); $j++)
                {
                    if ($this->arrayAidant[$i] == $this->aidantActuel[$j])
                    {
                        $res->bindParam(':' . $this->arrayAidant[$i],$this->get(1), PDO::PARAM_INT);
                        $correspondance = true;
                    }
                }
                if ($correspondance == false)
                    $res->bindParam(':' . $this->arrayAidant[$i],$this->get(0), PDO::PARAM_INT);
            }

            for ($i = 0; $i < count($this->arrayAutreAidant); $i++)
            {
                $correspondance = false;
                for ($j = 0; $j < count($this->autreAidantActuel); $j++)
                {
                    if ($this->arrayAutreAidant[$i] == $this->autreAidantActuel[$j])
                    {
                        $res->bindParam(':' . $this->arrayAutreAidant[$i],$this->get(1), PDO::PARAM_INT);
                        $correspondance = true;
                    }
                }
                if ($correspondance == false)
                    $res->bindParam(':' . $this->arrayAutreAidant[$i],$this->get(0), PDO::PARAM_INT);
            }

            $res->bindParam(':ressourcesFamSuff',$this->ressourcesFamilial, PDO::PARAM_INT);
            $res->bindParam(':ressourcesAmSuff',$this->ressourcesAmical, PDO::PARAM_INT);
            $res->bindParam(':logementAdapte',$this->logementAdapte, PDO::PARAM_INT);
            $res->bindParam(':insecFinanciere',$this->insecuriteFinanciere, PDO::PARAM_INT);
            $res->bindParam(':niveauScolaire',$this->niveauScolaire);
            $res->bindParam(':frEcrit',$this->frEcrit, PDO::PARAM_INT);
            $res->bindParam(':frParle',$this->frParle, PDO::PARAM_INT);
            $res->bindParam(':couvSocActive',$this->couvertureSociale, PDO::PARAM_INT);
            $res->bindParam(':pathChronique',$this->pathChronique, PDO::PARAM_INT);
            $res->bindParam(':medPrescSupCinq',$this->medPrescSupCinq, PDO::PARAM_INT);
            $res->bindParam(':niveauObservance',$this->niveauObservance, PDO::PARAM_INT);
            $res->bindParam(':hospitalisationRecenteProg',$this->hospitalisationRecenteProg, PDO::PARAM_INT);
            $res->bindParam(':hospitalisationRecenteNonProg',$this->hospitalisationRecenteNonProg, PDO::PARAM_INT);
            $res->bindParam(':nombreTotalHospit',$this->nombreTotalHospit, PDO::PARAM_INT);
            $res->bindParam(':dateSortieDerniereHospit',$this->dateSortieDerniereHospit, PDO::PARAM_INT);
            $res->bindParam(':fragPsych',$this->fragPsych, PDO::PARAM_INT);
            $res->bindParam(':fragEco',$this->fragEco, PDO::PARAM_INT);
            $res->bindParam(':fragSoc',$this->fragSoc, PDO::PARAM_INT);
            $res->bindParam(':fragSom',$this->fragSom, PDO::PARAM_INT);
            $res->bindParam(':trblCogn',$this->trblCogn, PDO::PARAM_INT);
            $res->bindParam(':iadl',$this->iadl, PDO::PARAM_INT);
            $res->bindParam(':gds',$this->gds, PDO::PARAM_INT);
            $res->bindParam(':evalGS',$this->evalGS, PDO::PARAM_INT);
            $res->bindParam(':epices',$this->epices, PDO::PARAM_INT);
            $res->bindParam(':activitePhy',$this->activitePhy, PDO::PARAM_INT);
            $res->bindParam(':perimetreMarche',$this->perimetreMarche, PDO::PARAM_INT);
            $res->bindParam(':vitesseMarche',$this->vitesseMarche, PDO::PARAM_INT);
            $res->bindParam(':vitesseMarche4m4s',$this->vitesseMarche4m4s, PDO::PARAM_INT);
            $res->bindParam(':arretConduite',$this->arretConduite, PDO::PARAM_INT);
            $res->bindParam(':diffVieQuot',$this->diffVieQuot, PDO::PARAM_INT);
            $res->bindParam(':diffIntell',$this->diffIntel, PDO::PARAM_INT);
            $res->bindParam(':protectionJud',$this->protectionJudiciaire, PDO::PARAM_INT);
            $res->bindParam(':diminutionCapSensInterne',$this->diminutionCapSensInterne, PDO::PARAM_INT);
            $res->bindParam(':diminutionCapSensExterne',$this->diminutionCapSensExterne, PDO::PARAM_INT);
            $res->bindParam(':perturbationSommeil',$this->perturbationSommeil, PDO::PARAM_INT);
            $res->bindParam(':variationPoids',$this->variationPoids, PDO::PARAM_INT);
            $res->bindParam(':imc',$this->imc, PDO::PARAM_STR);
            $res->bindParam(':dureedepuisdouleur',$this->dureedepuisdouleur, PDO::PARAM_STR);
            $res->bindParam(':dureedepuisperturbationsommeil',$this->dureedepuisperturbationsommeil, PDO::PARAM_STR);
            $res->bindParam(':dureedepuisdiminutionCapSensInterne',$this->dureedepuisdiminutionCapSensInterne, PDO::PARAM_STR);
            $res->bindParam(':dureedepuisdiminutionCapSensExterne',$this->dureedepuisdiminutionCapSensExterne, PDO::PARAM_STR);
            
            $res->bindParam(':douleur',$this->douleur, PDO::PARAM_INT);
            $res->bindParam(':addictAlcool',$this->addictAlcool, PDO::PARAM_INT);
            $res->bindParam(':addictTabac',$this->addictTabac, PDO::PARAM_INT);
            $res->bindParam(':addictMed',$this->addictMed, PDO::PARAM_INT);
            $res->bindParam(':addictCanabis',$this->addictCanabis, PDO::PARAM_INT);
            $res->bindParam(':autreAddiction',$this->getBooleanVal($this->autreAddiction), PDO::PARAM_INT);
            $res->bindParam(':emotionLimitante',$this->emotionLimitante, PDO::PARAM_INT);
            $res->bindParam(':incapExpression',$this->incapExpression, PDO::PARAM_INT);
            $res->bindParam(':isolementPhy',$this->isolementPhy, PDO::PARAM_INT);
            $res->bindParam(':abandon',$this->abandon, PDO::PARAM_INT);
            $res->bindParam(':submerge',$this->submerge, PDO::PARAM_INT);
            $res->bindParam(':epuisement',$this->epuisement, PDO::PARAM_INT);
            $res->bindParam(':maintenanceFM',$this->maintenanceFM, PDO::PARAM_INT);

            for ($i = 0; $i < count($this->arrayResExternes); $i++)
            {
                $correspondance = false;
                for ($j = 0; $j < count($this->ressourceExternes); $j++)
                {
                    if ($this->arrayResExternes[$i] == $this->ressourceExternes[$j])
                    {
                        $res->bindParam(':' . $this->arrayResExternes[$i],$this->get(1), PDO::PARAM_INT);
                        $correspondance = true;
                    }
                }
                if ($correspondance == false)
                    $res->bindParam(':' . $this->arrayResExternes[$i],$this->get(0), PDO::PARAM_INT);
            }

            $res->bindParam(':subjectiviteInf',$this->subjectiviteInf, PDO::PARAM_STR);
            $res->bindParam(':autresStrategies',$this->autresStrategies, PDO::PARAM_STR);

            $res->bindParam(':autresOutils',$this->autresOutils, PDO::PARAM_STR);


            $res->bindParam(':estActif',$this->estActif, PDO::PARAM_INT);
            $res->execute();
        }
        catch (PDOException $exception)
        {
            echo $exception->getMessage();
        }
    }

    public function delete($fragiliteId)
    {
        $sql = 'UPDATE fragilite SET estActif = 0 WHERE id = :id';

        try
        {
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $res = $this->con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $res->bindParam(':id',$fragiliteId, PDO::PARAM_INT);
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

