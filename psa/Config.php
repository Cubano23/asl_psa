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
    public $username_psa = 'root';
    public $password_psa = '';
    public $db_psa = 'informed3';
    public $db_type_psa = 'mysql';

    // DATABASE INFORMATION for ERP
    // See psa/persistence/ConnectionERP.php

    public $host_erp = 'localhost';
    public $username_erp = 'root';
    public $password_erp = '';
    public $db_erp = 'erp';
    public $db_type_erp = 'mysql';

    public $host_annuaire = 'localhost';
    public $username_annuaire = 'root';
    public $password_annuaire = '';
    public $db_annuaire = 'annuaire';
    public $db_type_annuaire = 'mysql';

    // APPLICATION INFORMATION
    // Application_env must be set to dev-herve
    // don't forget the ini file in /_files
    // don't also forget .htaccess file in /psa
    public $psa_path = "/psa";
    public $app_path = "/localhost/asl_psa";
    public $webservice_path = "/localhost/asl_psa/informed79/WebService";
    public $inclus_path = "/localhost/asl_psa/informed79/inclus";
    public $rest_path = "/localhost/asl_psa/rest";
    public $files_path = "/localhost/asl_psa/_files";
    public $erp_path = "/localhost/erp";
}
?>
