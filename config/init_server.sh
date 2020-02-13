#!/usr/bin/env bash

if [ $# != 1 ]
then
	echo "Nombre d'arguments passé en paramètre incorrecte"
	exit
fi

if [ "$1" = "local" ]
then
    echo ""
elif [ "$1" = "preprod" ]
then
    echo ""
elif [ "$1" = "prod" ]
then
    check_apache = `dpkg --get-selections | grep apache`
    check_httpd = `dpkg --get-selections | grep httpd`
    if [ -n "$check_apache" ]; then
        if grep -q "Alias /psa " /etc/httpd/conf/httpd.conf; then
            echo "Une configuration existe déjà sous /etc/httpd/conf/httpd.conf"
        elif [ -e /etc/httpd/conf.d/psa.conf ]; then
            echo "Une configuration existe déjà sous le fichier /etc/httpd/conf.d/psa.conf"
        else
            touch /etc/httpd/conf.d/psa.conf
            cat server_config_prod.txt > /etc/httpd/conf.d/psa.conf
            echo "Initialisation du serveur selon l'environnement prod terminée"
        fi
    elif [ -n "$check_httpd" ]; then
        echo "Initialisation du serveur selon l'environnement prod terminée"
    else
        echo "Aucun serveur web trouvé"
    fi
else
	echo "L'environnement stipulé est inconnu"
fi
