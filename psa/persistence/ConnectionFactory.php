<?php
require_once 'Config.php';

$config = new Config();

$hostname_mysql = $config->host_psa;
$database_mysql = $config->db_psa;
#$database_mysql = "asaletest";
$DB_connexion = "informed";
$username_mysql = $config->username_psa;
$password_mysql = $config->password_psa;

class ConnectionFactory {
    var $mysqlConnection;

    function ConnectionFactory($objectReference = "") {
        static $mysqlConnection = NULL;
        $this->mysqlConnection = &$mysqlConnection;
    }

    function getConnection() {
        global $hostname_mysql;
        global $database_mysql;
        global $username_mysql;
        global $password_mysql;
        if(is_null($this->mysqlConnection)){
            $this->mysqlConnection = mysql_pconnect($hostname_mysql, $username_mysql, $password_mysql) ;
            mysql_select_db($database_mysql);
        }
        return $this->mysqlConnection;
    }
    function getDBConnexion(){
        global $DB_connexion;
        return $DB_connexion;
    }
}
?>
