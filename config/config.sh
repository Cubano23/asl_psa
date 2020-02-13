#!/bin/bash

if [ $# != 1 ]
then
	echo "Nombre d'arguments passé en paramètre incorrect"
	exit	
fi

if [ "$1" = "local" ]
then
	cat config_local.php > ./../psa/Config.php
	cat htaccess_local.txt > ./../psa/.htaccess
    rm -rf log integration_logs
	ln -s /home/aslApps/asl_psa/_files/integrations /home/aslApps/asl_psa/psa/view/integration/log
    ln -s /home/aslApps/asl_psa/psa/view/integration/log /home/aslApps/asl_psa/psa/view/integration/integration_logs
	echo "Initialisation de l'environnement local terminée"
elif [ "$1" = "preprod" ]
then
	cat config_preprod.php > ./../psa/Config.php
	cat htaccess_preprod.txt > ./../psa/.htaccess
	ln -s /home/informed/www/_files/integrations `pwd`/../psa/view/integration/log
	ln -s `pwd`/../psa/view/integration/log `pwd`/../psa/view/integration/integration_logs
    echo "Initialisation de l'environnement preprod terminée"
elif [ "$1" = "prod" ]
then
	cat config_prod.php > ./../psa/Config.php
	cat htaccess_prod.txt > ./../psa/.htaccess
	ln -s /home/informed/www/erp `pwd`/../erp
	ln -s /home/informed/www/_files/integrations `pwd`/../psa/view/integration/log
	ln -s `pwd`/../psa/view/integration/log `pwd`/../psa/view/integration/integration_logs
	echo "Initialisation de l'environnement prod terminée"

else
	echo "L'environnement stipulé est inconnu"
fi


### WIP : script to create the necessary files for the application to work
#mkdir _files
#mkdir _files/ars
#mkdir _files/dashboard
#mkdir _files/dashboard/pdf
#mkdir _files/dashboard/csv
#mkdir _files/exports
#mkdir _files/import
#mkdir _files/integrations
#mkdir _files/log
#mkdir _files/notes_de_frais
#mkdir _files/tc
#mkdir _files/psar
#
#touch _files/log/Controler.log
#touch _files/log/HTML HELPER.log
#touch _files/log/persistence.log