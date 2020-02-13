<?php
/**
 * Created by PhpStorm.
 * User: gisgo
 * Date: 20/09/18
 * Time: 17:16
 */

require_once 'Config.php';

class ConnectionAnnuairePDO
{
    private static $instance;
    private $config;


    private $type;

    /**
     * Adresse du serveur h�te.
     * @access private
     * @var string
     * @see __construct
     */
    private $host;

    /**
     * Nom de la base de donn�e.
     * @access private
     * @var string
     * @see __construct
     */
    private $dbname;

    /**
     * Nom d'utilisateur pour la connexion � la base de donn�es
     * @access private
     * @var string
     * @see __construct
     */
    private $username;

    /**
     * Mot de passe pour la connexion � la base de donn�e
     * @access private
     * @var string
     * @see __construct
     */
    private $password;

    private $dbh;

    /**
     * Lance la connexion � la base de donn�e en le mettant
     * dans un objet PDO qui est stock� dans la variable $dbh
     * @access private
     */
    private function __construct()
    {
        $this->config = new Config();

        $this->type = $this->config->db_type_annuaire;
        $this->host = $this->config->host_annuaire;
        $this->dbname = $this->config->db_annuaire;
        $this->username = $this->config->username_annuaire;
        $this->password = $this->config->password_annuaire;

        try
        {
            $this->dbh = new PDO(
                $this->type.':host='.$this->host.'; dbname='.$this->dbname,
                $this->username,
                $this->password,
                array(PDO::ATTR_PERSISTENT => true)
            );

//            $req = "SET NAMES UTF8";
            $req = "SET NAMES latin1";
            $result = $this->dbh->prepare($req);
            $result->execute();
        }
        catch(PDOException $e)
        {
            echo "<div class=\"error\">Erreur !: ".$e->getMessage()."</div>";
            die();
        }
    }

    /**
     * Regarde si un objet connexion a d�j� �t� instancier,
     * si c'est le cas alors il retourne l'objet d�j� existant
     * sinon il en cr�er un autre.
     * @return $instance
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof self)
        {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Permet de r�cuprer l'objet PDO permettant de manipuler la base de donn�e
     * @return $dbh
     */
    public function getDbh()
    {
        return $this->dbh;
    }
}