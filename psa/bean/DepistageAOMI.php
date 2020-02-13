<?php
/**
 * Created by PhpStorm.
 * User: gisgo
 * Date: 11/05/18
 * Time: 13:19
 */

require_once "persistence/ConnectionInformedPDO.php";

class DepistageAOMI
{
    private $con; //variable de connexion
    public $id;
    public $dossier_id;
    public $dossier_numero;
    public $ipsd;
    public $ipsg;
    public $SOASAveree;
    public $antecedantsFamiliaux;
    public $pathoCVASansAOMIAvere;
    public $dyslipidemies;
    public $htaPermanente;
    public $tabacActifOuCorrige;
    public $dt2;
    public $dt1plus20;

    public $eda;
    public $initiateurIPS;
    public $realisateurIPS;
    public $commentaires;
    public $provenance;
    public $cabinet;
    public $dateSaisie;
    public $infAjout;
    public $dateAjout;
    public $estActif = 1;

    public function __construct()
    {
        $db = ConnectionInformedPDO::getInstance();
        $this->con = $db->getDbh();
        $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    function DepistageAOMI(
        $id = NULL,
        $dossier_id = NULL,
        $dossier_numero = NULL,

        $ipsd = NULL,
        $ipsg = NULL,
        $SOASAveree = NULL,
        $antecedantsFamiliaux = NULL,
        $pathoCVASansAOMIAvere = NULL,
        $dyslipidemies = NULL,
        $htaPermanente = NULL,
        $tabacActifOuCorrige = NULL,
        $dt2 = NULL,
        $dt1plus20 = NULL,
        $eda = NULL,
        $initiateurIPS = NULL,
        $realisateurIPS = NULL,
        $commentaires = NULL,
        $provenance = NULL,
        $cabinet = NULL,
        $dateSaisie = NULL,
        $infAjout = NULL,
        $dateAjout = NULL,
        $estActif = 1
    ) {
        $this->id = $id;
        $this->dossier_id = $dossier_id;
        $this->dossier_numero = $dossier_numero;
        $this->ipsd = $ipsd;
        $this->ipsg = $ipsg;
        $this->SOASAveree = $SOASAveree;
        $this->antecedantsFamiliaux = $antecedantsFamiliaux;
        $this->pathoCVASansAOMIAvere = $pathoCVASansAOMIAvere;
        $this->dyslipidemies = $dyslipidemies;
        $this->htaPermanente = $htaPermanente;
        $this->tabacActifOuCorrige = $tabacActifOuCorrige;
        $this->dt2 = $dt2;
        $this->dt1plus20 = $dt1plus20;

        $this->eda = $eda;
        $this->initiateurIPS = $initiateurIPS;
        $this->realisateurIPS = $realisateurIPS;
        $this->commentaires = $commentaires;
        $this->provenance = $provenance;
        $this->cabinet = $cabinet;
        $this->dateSaisie = $dateSaisie;
        $this->infAjout = $infAjout;
        $this->dateAjout = $dateAjout;
        $this->estActif = $estActif;
    }

    public function getHistoriqueDepistage($dossierId, $cabinet)
    {
        $historiqueDepistage = array();
        $sql = "SELECT   id, dossier_id, dossier_numero, initiateurIPS, realisateurIPS,ipsd, ipsg, eda, provenance, dateSaisie, SOASAveree ,antecedantsFamiliaux ,pathoCVASansAOMIAvere ,dyslipidemies ,htaPermanente ,tabacActifOuCorrige ,dt2 ,dt1plus20 ,commentaires
                FROM     depistage_aomi
                WHERE    dossier_id = " . $dossierId . " AND cabinet = '" . $cabinet . "' AND estActif = 1
                ORDER BY dateSaisie DESC";

        try
        {
            $res = $this->con->query($sql);

            $historiqueDepistage = $res->fetchAll(PDO::FETCH_CLASS, 'DepistageAOMI');
        }
        catch (PDOException $e)
        {
            exit($e->getMessage());
        }

        return $historiqueDepistage;
    }

    public function getHistoriqueDepistageSansFiltre( $cabinet)
    {
        $historiqueDepistage = array();
        $sql = "SELECT   id, dossier_id, dossier_numero, initiateurIPS, realisateurIPS, ipsd, ipsg, eda, provenance, dateSaisie, SOASAveree ,antecedantsFamiliaux ,pathoCVASansAOMIAvere ,dyslipidemies ,htaPermanente ,tabacActifOuCorrige ,dt2 ,dt1plus20 ,commentaires
                FROM     depistage_aomi
                WHERE    cabinet = '" . $cabinet . "' AND estActif = 1
                ORDER BY dateSaisie DESC
                LIMIT 0,15 " ;


        try
        {
            $res = $this->con->query($sql);

            $historiqueDepistage = $res->fetchAll(PDO::FETCH_CLASS, 'DepistageAOMI');
        }
        catch (PDOException $e)
        {
            exit($e->getMessage());
        }

        return $historiqueDepistage;
    }




    public function getByDIdAndDate($dossierId, $date)
    {
        $sql = "SELECT   * 
                FROM     depistage_aomi 
                WHERE    dossier_id = " . $dossierId ." AND dateSaisie = '" . $date . "' AND estActif = 1
                ORDER BY dateSaisie DESC
                LIMIT    1";

        try
        {
            $res = $this->con->query($sql);

            $depistage = $res->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            exit($e->getMessage());
        }

        return $depistage;
    }

    public function save()
    {
        $sql = 'INSERT INTO depistage_aomi (dossier_id, dossier_numero, ipsd, ipsg,SOASAveree ,antecedantsFamiliaux ,pathoCVASansAOMIAvere ,dyslipidemies ,htaPermanente ,tabacActifOuCorrige ,dt2 ,dt1plus20 , eda, initiateurIPS, realisateurIPS, commentaires, provenance, cabinet, dateSaisie, infAjout, dateAjout, estActif) VALUES (:dossier_id, :dossier_numero, :ipsd, :ipsg, :SOASAveree, :antecedantsFamiliaux, :pathoCVASansAOMIAvere, :dyslipidemies, :htaPermanente, :tabacActifOuCorrige, :dt2, :dt1plus20, :eda, :initiateurIPS, :realisateurIPS, :commentaires, :provenance, :cabinet, :dateSaisie, :infAjout, :dateAjout, :estActif)';

        try
        {
            $res = $this->con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $res->bindParam(':dossier_id',$this->dossier_id, PDO::PARAM_INT);
            $res->bindParam(':dossier_numero',$this->dossier_numero, PDO::PARAM_STR);
            $res->bindParam(':ipsd',$this->ipsd, PDO::PARAM_STR);
            $res->bindParam(':ipsg',$this->ipsg, PDO::PARAM_STR);


            $res->bindParam(':SOASAveree', $this->getBooleanVal($this->SOASAveree),PDO::PARAM_INT);
            $res->bindParam(':antecedantsFamiliaux', $this->getBooleanVal($this->antecedantsFamiliaux),PDO::PARAM_INT);
            $res->bindParam(':pathoCVASansAOMIAvere', $this->getBooleanVal($this->pathoCVASansAOMIAvere),PDO::PARAM_INT);
            $res->bindParam(':dyslipidemies', $this->getBooleanVal($this->dyslipidemies),PDO::PARAM_INT);
            $res->bindParam(':htaPermanente', $this->getBooleanVal($this->htaPermanente),PDO::PARAM_INT);
            $res->bindParam(':tabacActifOuCorrige', $this->getBooleanVal($this->tabacActifOuCorrige),PDO::PARAM_INT);
            $res->bindParam(':dt2', $this->getBooleanVal($this->dt2),PDO::PARAM_INT);
            $res->bindParam(':dt1plus20', $this->getBooleanVal($this->dt1plus20),PDO::PARAM_INT);





            $res->bindParam(':eda',$this->eda, PDO::PARAM_STR);
            $res->bindParam(':initiateurIPS',$this->initiateurIPS, PDO::PARAM_STR);
            $res->bindParam(':realisateurIPS',$this->realisateurIPS, PDO::PARAM_STR);
            $res->bindParam(':commentaires',$this->commentaires, PDO::PARAM_STR);
            $res->bindParam(':provenance',$this->provenance, PDO::PARAM_STR);
            $res->bindParam(':cabinet',$_SESSION['cabinet']);

            $date =  $this->dateSaisie->format('Y-m-d');
            $res->bindParam(':dateSaisie',$date);

            $res->bindParam(':infAjout',$_SESSION['nom']);
            $date1 = date("Y-m-d H:i:s");
            $res->bindParam(':dateAjout',$date1);
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
        $sql = 'UPDATE depistage_aomi 
                SET dossier_id = :dossier_id, dossier_numero = :dossier_numero, ipsd = :ipsd, ipsg = :ipsg, SOASAveree = :SOASAveree , antecedantsFamiliaux = :antecedantsFamiliaux , pathoCVASansAOMIAvere = :pathoCVASansAOMIAvere , dyslipidemies = :dyslipidemies , htaPermanente = :htaPermanente , tabacActifOuCorrige = :tabacActifOuCorrige , dt2 = :dt2 , dt1plus20 = :dt1plus20 , eda = :eda, initiateurIPS = :initiateurIPS, realisateurIPS = :realisateurIPS, commentaires = :commentaires, estActif = :estActif
                WHERE id = :id';

        try
        {
            $res = $this->con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $res->bindParam(':id',$this->id, PDO::PARAM_INT);
            $res->bindParam(':dossier_id',$this->dossier_id, PDO::PARAM_INT);
            $res->bindParam(':dossier_numero',$this->dossier_numero, PDO::PARAM_STR);
            $res->bindParam(':ipsd',$this->ipsd, PDO::PARAM_STR);
            $res->bindParam(':ipsg',$this->ipsg, PDO::PARAM_STR);

            $res->bindParam(':SOASAveree', $this->getBooleanVal($this->SOASAveree),PDO::PARAM_INT);
            $res->bindParam(':antecedantsFamiliaux', $this->getBooleanVal($this->antecedantsFamiliaux),PDO::PARAM_INT);
            $res->bindParam(':pathoCVASansAOMIAvere', $this->getBooleanVal($this->pathoCVASansAOMIAvere),PDO::PARAM_INT);
            $res->bindParam(':dyslipidemies', $this->getBooleanVal($this->dyslipidemies),PDO::PARAM_INT);
            $res->bindParam(':htaPermanente', $this->getBooleanVal($this->htaPermanente),PDO::PARAM_INT);
            $res->bindParam(':tabacActifOuCorrige', $this->getBooleanVal($this->tabacActifOuCorrige),PDO::PARAM_INT);
            $res->bindParam(':dt2', $this->getBooleanVal($this->dt2),PDO::PARAM_INT);
            $res->bindParam(':dt1plus20', $this->getBooleanVal($this->dt1plus20),PDO::PARAM_INT);


            $res->bindParam(':eda',$this->eda, PDO::PARAM_INT);
            $res->bindParam(':initiateurIPS',$this->initiateurIPS, PDO::PARAM_STR);
            $res->bindParam(':realisateurIPS',$this->realisateurIPS, PDO::PARAM_STR);
            $res->bindParam(':commentaires',$this->commentaires, PDO::PARAM_STR);
            $res->bindParam(':estActif',$this->estActif, PDO::PARAM_INT);
            $res->execute();
        }
        catch (PDOException $exception)
        {
            echo $exception->getMessage();
        }
    }

    public function deleteById()
    {
        $sql = 'UPDATE depistage_aomi
                SET estActif = 0 
                WHERE id = :id';

        try
        {
            $res = $this->con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $res->bindParam(':id',$this->id);
            $res->execute();
        }
        catch (PDOException $exception)
        {
            echo $exception->getMessage();
        }
    }

    public function deleteByProvenanceAndDate()
    {
        $sql = 'UPDATE depistage_aomi
                SET estActif = 0 
                WHERE dossier_id = :dossier_id AND provenance = :provenance AND dateSaisie = :dateSaisie';

        try
        {
            $res = $this->con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $res->bindParam(':dossier_id',$this->dossier_id);
            $res->bindParam(':provenance',$this->provenance);
            $res->bindParam(':dateSaisie',$this->dateSaisie);
            $res->execute();
        }
        catch (PDOException $exception)
        {
            echo $exception->getMessage();
        }
    }
    public function getBooleanVal($val)
    {
        if (isset($val))
            return 1;
        else
            return 0;
    }
}
