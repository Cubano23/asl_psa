<?php
/**
 * Created by PhpStorm.
 * User: gisgo
 * Date: 20/07/18
 * Time: 17:40
 */

class Config
{
    // DATABASE INFORMATION for PSA
    // See psa/persistence/ConnectionFactory.php

    public $host_psa = 'localhost';
    public $username_psa = 'informed';
    public $password_psa = 'no11iugX';
    public $db_psa = 'informed3';

    public $host_annuaire = 'localhost';
    public $username_annuaire = 'informed';
    public $password_annuaire = 'no11iugX';
    public $db_annuaire = 'annuaire';
    public $db_type_annuaire = 'mysql';

    // DATABASE INFORMATION for ERP
    // See psa/persistence/ConnectionERP.php

    public $host_erp = 'localhost';
    public $username_erp = 'informed';
    public $password_erp = 'no11iugX';
    public $db_erp = 'erp';

    // APPLICATION INFORMATION
    // Application_env must be set to dev-herve
    // don't forget the ini file in /_files
    // don't also forget .htaccess file in /psa
    public $psa_path = "/psa";
    public $app_path = "/home/informed/asl_psa";
    public $webservice_path = "/home/informed/asl_psa/informed79/WebService";
    public $inclus_path = "/home/informed/asl_psa/informed79/inclus";
    public $rest_path = "/home/informed/asl_psa/rest";
    public $files_path = "/home/informed/asl_psa/_files";
    public $erp_path = "/home/informed/www/erp";
}
?>
