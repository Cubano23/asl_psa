<?php

session_start();
// echo $_GET["logiciel"];die;

$base=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
require("$base/inclus/accesbase.inc.php"); 


# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
   die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
   die("Impossible de se connecter à la base");


   //Si logiciel non operationnel on alerte
   	$cabinet = $_SESSION["cabinet"];
   	$req="SELECT log_ope FROM account WHERE cabinet='$cabinet'";
 	$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
	$result=mysql_fetch_array($res);
	if($result['log_ope']!='1'){
		echo "Int&eacute;gration en cours de mise en oeuvre <br/>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript://' style='color:#000000' onclick=\"ajax_hideTooltip()\">Fermer l'aide</a><br><br>";
		exit;
	}

   


if($_GET["logiciel"]=="axisante" || $_GET["logiciel"]=="axisante4" || $_GET["logiciel"]=="axisante5"){
	// echo htmlentities("Ici sera affiché un guide pour l'extraction du fichier, adaptable en fonction du logiciel (ici Axisanté)");

	echo htmlentities("L'extraction du fichier à partir d'Axisanté devra se faire suivant la marche suivante :")."<br><br>".
		 htmlentities("1- ouvrir Axisanté")."<br><br>".
		 htmlentities("2- aller dans fichier/statistiques et cartes de visite")."<br>".
		 htmlentities("un nouvel écran s'ouvre")."<br><br>".
		 htmlentities("3- aller dans statistiques/recherche multi critère")."<br>".
		 htmlentities("La liste des modèles de recherche s'affiche")."<br><br>".
		 htmlentities("4- Cliquer sur \"+\" ou sélectionner la requête si elle a été enregistrée")."<br>".
		 htmlentities("Une nouvelle fenêtre s'ouvre, dans laquelle les critères de recherches peuvent être sélectionnés. Sélectionner dans état civil/contrat médecin traitant : n'est pas vide et décédé est vide")."<br><br>".
		 htmlentities("5- S'assurer que la case statistique global soit cochée, sauf si tous les médecins du cabinet ne sont pas dans asalée")."<br><br>".
		 htmlentities("6- Lancer la recherche")."<br>".
		 htmlentities("A l'issue de la recherche, la liste des patients est affichée en bas d'écran")."<br><br>".
		 htmlentities("7- Cliquer sur Gestion des résultats")."<br>".
		 htmlentities("Le système propose alors de créer 4 fichiers différents. Cliquer sur \"Export sur 4 fichiers\". Accepter la création des fichiers en cliquant sur Ok")."<br><br>".
		 htmlentities("8- A la fin de l'intégration, cliquer sur le bouton Parcourir de la page d'intégration automatique du portail PSA")."<br><br>".
		 htmlentities("9- aller dans poste de travail, double cliquer sur disque local c:/, double cliquer sur axisante2000, double cliquer sur application/")."<br>".
		 htmlentities("4 fichiers sont créés par le logiciel : ")."<br>".
		 htmlentities("- VarCheminUserData]kSuivi")."<br>".
		 htmlentities("- VarCheminUserData]kPatient")."<br>".
		 htmlentities("- VarCheminUserData]kConsult")."<br>".
		 htmlentities("- VarCheminUserData]kAntecedant")."<br>";
		 if(strcasecmp($_SESSION["cabinet"], "chatillon")==0){
			echo htmlentities("Zipper le fichier \"VarCheminUserData]kSuivi\". Si un fichier VarCheminUserData]kSuivi.zip existe déjà, le supprimer. Pour créer le fichier zip, cliquer bouton droit sur le fichier, puis \"Envoyer Vers\" puis \"dossier compressé\"")."<br>";
		 }
	echo "<br>";
	echo htmlentities("10- Sélectionner le fichier VarCheminUserData]kSuivi, puis cliquer sur Ouvrir, puis cliquer Intégrer")."<br><br><br>";
/*		 
comme on ne peut pas enlever les noms au moment de l'extraction, au postage le fichier est chiffré par https. A la réception, les premières colonnes ne sont pas exploitées et ne sont exploitées que des colonnes anonymes
*/
}
elseif($_GET["logiciel"]=="crossway"){
	echo htmlentities("1- Lancer Crossway à partir de l'icône \"requeteur\" qui est sur le bureau.")."<br>".
		 htmlentities("Le logiciel demande de renseigner un login et un mot de passe")."<br><br>".
		 htmlentities("2- Lorsque le logiciel est démarré, sur le bandeau de gauche cliquer sur documents/nouveau si la requête n'a pas été déjà enregistrée. Sinon double cliquer sur la requête sélectionnée, puis passer à l'étape 6 pour modifier les dates d'examens.")."<br>".
		 htmlentities("Une nouvelle fenêtre s'ouvre.")."<br><br>".
		 htmlentities("3- le champ \"nom de la requête\" permet d'enregistrer la requête sous ce nom pour la retrouver facilement. Ce champ est libre.")."<br><br>".
		 htmlentities("4- Un premier champ intitulé \"sélection des tables\" fait apparaitre 2 listes de tables. Sont affichées : ")."<br>".
		 htmlentities("- à gauche les tables sélectionnables")."<br>".
		 htmlentities("- à droites les tables sélectionnées.")."<br><br>".
		 htmlentities("Différents types de tables sont disponibles : biométrie, biologie, patients, maladies, antécédents, etc... Sélectionner la table \"Biologie\" et la table \"patients\"")."<br><br>".
		 htmlentities("5- dans la partie \"champs de sortie\" : ")."<br>".
		 htmlentities("cliquer sur \"+\" pour chaque nouvelle donnée à extraire. A chaque fois, une ligne s'affiche.")."<br>".
		 htmlentities("dans colonne \"table\", sélectionner la table dans laquelle la donnée apparait (par ex \"patient\" pour le n° dossier)")."<br>".
		 htmlentities("dans la colonne \"champ\", sélectionner le champ (par ex \"N° dossier\"). Il faut ajouter autant de ligne que de champs de sortie. ")."<br><br>".
		 htmlentities("6- dans \"critères de recherche\", cliquer sur \"+\" à chaque critère à rajouter")."<br>".
		 htmlentities("Indiquer un critère uniquement sur la date d'examen : après le 01/07/2011 par exemple. Pour limiter le nombre de lignes de résultat et donc le temps d'extraction, il possible de sélectionner une date correspondant à la dernière extraction (de préférence la veille). ")."<br><br>".
		 htmlentities("7- cliquer sur l'icone en bas à gauche (triangle vert) pour lancer la recherche.")."<br>".
		 htmlentities("A la fin de la recherche, un tableau \"type excel\" avec les champs de sortie est affiché. ")."<br><br>".
		 htmlentities("8- Fermer la fenêtre de résultat, puis cliquer sur \"Valider\". La fenêtre de la requête se ferme.")."<br><br>".
		 htmlentities("9- Cliquer une fois sur le nom de la requête qui a été enregistrée, puis cliquer sur l'icône représentant une flèche rouge dirigée vers \"X\". Une fenêtre s'ouvre.")."<br>".
		 htmlentities("Le logiciel propose d'exporter dans un fichier. Saisir un nom de fichier dans la zone prévue (par ex : export.xls), puis cliquer sur le dossier en bout de ligne pour choisir le dossier où exporter. Cocher type d'exportation : Excel.")."<br>".
		 htmlentities("Cliquer sur \"exporter\".")."<br><br>".
		 htmlentities("10- Le fichier est alors sauvegardé dans le répertoire sélectionné. ")."<br><br>".
		 htmlentities("11- Ouvrir le fichier, puis l'enregistrer. Un message d'Excel doit indiquer que le fichier est enregistré dans une ancienne version et demande s'il faut convertir le fichier. Accepter la conversion. ")."<br><br>".
		 htmlentities("12- Sur le portail asalée, cliquer sur le bouton \"parcourir\" puis sélectionné le fichier créé. Cliquer ensuite sur \"Intégrer\"")."<br><br>";
 
}
elseif($_GET["logiciel"]=="dbmed"){
	echo htmlentities("1- démarrer access")."<br><br>".
		 htmlentities("2- Créer une base de données, cliquer sur \"base de données\" puis ok. Indique un nom de base de données (par exemple \"Export 20110928\"), sélectionner le répertoire où l'enregistrer puis cliquer sur \"créer\"")."<br><br>".
		 htmlentities("3- aller fichier/données externes/importer ")."<br>".
		 htmlentities("type de fichier : sélectionner \"fichier dBASE 5\"")."<br>".
		 htmlentities("Selectionner le fichier c:/cabinet/dbmed/DBMDATA/param.dbf")."<br>".
		 htmlentities("cliquer sur \"importer\". Lorsque le message confirme l'importation, cliquer sur ok, puis sur annuler.")."<br><br>".
		 htmlentities("4- double cliquer sur le fichier \"param\" créé à l'étape 3.")."<br><br>".
		 htmlentities("5- aller dans fichier/exporter")."<br>".
		 htmlentities("Type de fichier : sélectionner \"Fichier texte (*.txt, *.csv)\"")."<br>".
		 htmlentities("Sélectionner le répertoire où sauvegarder le fichier et le nommer param20110928. Cliquer sur suivant 2 fois puis sur terminer")."<br><br>".
		 htmlentities("6- Zipper le fichier créé à l'étape 5. Pour cela, cliquer bouton droit sur le fichier, puis \"Envoyer vers\" puis \"dossier compressé\"")."<br><br>".
		 htmlentities("7- A partir de la page intégration automatiques du portail asalée, cliquer sur \"parcourir\" et sélectionner le fichier créé à l'étape 6. Cliquer ensuite sur \"intégrer\"")."<br><br>";
}
elseif($_GET["logiciel"]=="easyprat"){
	echo htmlentities("Ici sera affiché un guide pour l'extraction du fichier, adaptable en fonction du logiciel (ici EasyPrat)");
}
elseif($_GET["logiciel"]=="hellodoc" || $_GET["logiciel"]=="hellodoc_v5.6" || $_GET["logiciel"]=="hellodoc_v5.55"){
	echo htmlentities("1- Aller dans Fichier/Exporter/Exporter en ASCII.")."<br>".
		 htmlentities("Le logiciel propose un menu déroulant à partir duquel il est possible de sélectionner le fichier à exporter")."<br><br>".
		 htmlentities("2- Sélectionner \"Biologies des patients\" puis cliquer sur \"ok\"")."<br>".
		 htmlentities("Le logiciel demande de confirmer la création du fichier. Accepter la création")."<br>";
		 
		 if(strcasecmp($_SESSION["cabinet"], "ruelle")==0){
			echo htmlentities("Le fichier est enregisté sous \"z:/LARENTE/export_ASCII/biologies.asc\"")."<br>";
		 }
		 
		 if(strcasecmp($_SESSION["cabinet"], "amberieu")==0){
			echo htmlentities("Le fichier est enregisté sous \"x:/cabinets_v5.60/les allymes/export_ASCII/biologies.asc\"")."<br>";
		 }
		 
		 if(strcasecmp($_SESSION["cabinet"], "chambery1")==0){
			echo htmlentities("Le fichier se nomme \"biologie.asc\". Pour le retrouver : démarrer l'explorateur windows, aller dans \"favoris réseau\", puis \"imagine editions sur serveur\", puis \"hellodoc\" puis \"scm bertillet\", puis \"export_ASCII\"")."<br>";
		 }

		 // if(strcasecmp($_SESSION["cabinet"], "saulieu")==0){
			// echo htmlentities("Le fichier se nomme \"biologie.asc\". Pour le retrouver : démarrer l'explorateur windows, aller dans \"favoris réseau\", puis \"imagine editions sur serveur\", puis \"hellodoc\" puis \"scm bertillet\", puis \"export_ASCII\"")."<br>";
		 // }
		 
	echo "<br>".htmlentities("3- Aller dans le répertoire où le fichier est enregistré ")."<br><br>".
		 htmlentities("4- Cliquer sur le fichier créé avec le bouton droit de la souris, puis \"Envoyer vers\" puis \"Dossier compressé\"")."<br>".
		 htmlentities("Si un fichier nommé \"Biologies.zip\" est déjà présent, le renommer avec sa date de création. Par exemple Biologies18102011.zip")."<br>".
		 htmlentities("Un fichier nommé \"Biologies.zip\" se crée automatiquement")."<br><br>".
		 htmlentities("5- A partir de la page d'intégration automatique des données, cliquer sur \"Parcourir\" puis sélectionner le fichier créé à l'étape 4 (fichier Biologies.zip) puis cliquer sur \"Intégrer\"")."<br><br>";
}
elseif($_GET["logiciel"]=="ict"){
	echo htmlentities("Ici sera affiché un guide pour l'extraction du fichier, adaptable en fonction du logiciel (ici ICT)");
}
elseif($_GET["logiciel"]=="medicawin"){
	echo htmlentities("Ici sera affiché un guide pour l'extraction du fichier, adaptable en fonction du logiciel (ici Médicawin)");
}
elseif($_GET["logiciel"]=="mediclic3" || $_GET["logiciel"]=="mediclic4" || $_GET["logiciel"]=="mediclic5"){
	echo htmlentities("1- Aller dans Mediclick / QuizzClick Studio")."<br><br>".
		 htmlentities("2- Aller dans l'onglet \"Dossier\". Sur la partie de gauche cliquer sur \"Divers\" puis double cliquer sur \"n° de dossier\". Une ligne n° de dossier doit apparaitre sur le cadre principal de la page. Sur les valeurs min et max indiquer 0 et 10000")."<br><br>".
		 htmlentities("3- aller dans l'onglet \"Biologie\". Dans la partie \"rubriques standard\", double cliquer sur date de l'examen et date de prescription. Pour date de l'examen indiquer un intervalle de date compris entre 2 semaines avant la date du jour et la date du jour. Pour date de prescription indiquer un intervalle entre 2 mois avant la date du jour et la date du jour")."<br><br>".
		 htmlentities("4- dans la partie \"nom de l'examen\" un examen à exporter. Par exemple HBA1c")."<br><br>".
		 htmlentities("5- cliquer sur \"lancer la recherche\". La liste des examens trouvés apparait en bas de page")."<br><br>".
		 htmlentities("6- enregistrer le fichier en cliquant sur la disquette")."<br><br>".
		 htmlentities("7- Aller sur la page d'intégration automatiques de données et sélectionner le fichier créé à l'étape 6 puis cliquer sur intégrer")."<br><br>".
		 htmlentities("8- Si vous souhaitez intégrer d'autres examens, cliquer que l'icone poubelle de la ligne créée à l'étape 4, puis exécuter à nouveau les étapes 4, 5, 6, 7 en sélectionnant l'examen désiré au moment de faire l'étape 4")."<br><br>";
}
elseif($_GET["logiciel"]=="mediclic"){
	echo htmlentities("La procédure se passe en 3 parties. Les deux premières parties permettent de faire 2 extraits différents à partir du logiciel médical. La troisième partie permet de faire l'intégration automatique")."<br><br>".
		 "<b>".htmlentities("A- Export de la liste des examens dans un fichier au format nom/prénom/date/valeur")."</b><br><br>".
		 htmlentities("1- fermer la biologie si un dossier est déjà ouvert")."<br><br>".
		 htmlentities("2- aller dans le menu médiclic !/quizzclick ! studio")."<br>".
		 htmlentities("un fenêtre s'ouvre qui fait apparaitre 2 série d'onglets en miroir: ")."<br>".
		 htmlentities("patient, ordonnance, biologie, examen, antécédents, actes, multi")."<br>".
		 htmlentities("A gauche il y a la partie de recherche et à droite les résultats")."<br><br>".
		 htmlentities("3- cliquer sur biologie sur la partie de gauche. Les résultats d'une ancienne recherche s'affichent à droite")."<br><br>".
		 htmlentities("4- en haut d'écran à gauche s'affiche \"examen biologique\" et une zone à remplir. Saisir HB. Le système propose HBA1c. Faire entrée sur clavier")."<br><br>".
		 htmlentities("5- \"examen\" apparait. Il s'agit des conditions à prendre en compte (une ligne avec 8 colonnes : Nom examen, mini, maxi, inf/sup/égal, oui/non, du.., au... Rech")."<br>".
		 htmlentities("Cocher la case \"tous les praticiens\"")."<br><br>".
		 htmlentities("6- changer la date \"du\" en prenant 1 mois avant la date du jour. Par exemple \"04 06 2011\". Par défaut, date de fin est la date du jour.")."<br><br>".
		 htmlentities("7- Effectuer à nouveau les étapes 4, 5, 6 pour tous les examens de biologie disponibles dans asalée : HDL, LDL, Cholestérol total, Créatinine, etc... Pour chaque examen sélectionné, une ligne apparait à l'étape 5.")."<br><br>".
		 htmlentities("8- dans \"options générales : patients de\" : cocher \"tous les praticiens\" si tous les praticiens sont dans asalée, sinon faire la procédure pour chaque praticien qui est dans asalée.")."<br><br>".
		 htmlentities("9- cliquer sur l'icône montrant une personne courant (en haut à droite de la partie gauche). Un popup de recherche s'affiche")."<br><br>".
		 htmlentities("10- Les résultats de la recherche apparaissent sur la partie droite, sous le comptage.")."<br><br>".
		 htmlentities("11- sur la moitié droite, bandeau en haut, les critères suivants apparaissent : \"tous les critères\", \"au moins un\", \"tout\", \"hba1c\"")."<b><u>".htmlentities(" Cliquer sur \"tout\"")."</u></b>".htmlentities(" pour faire apparaître l'ensemble des examens.")."<br><br>".
		 htmlentities("Les résultats sont affichés avec une ligne par examen, avec les informations suivantes : nom, prénom, date de naissance, date de l'examen, nature de l'examen, résultat")."<br><br>".
		 htmlentities("12- cliquer sur disquette tout en haut à gauche de la recherche. le message \"l'exportation de 'QuizzExport 110706 [10h26].xls' est situé dans le dossier M:\MEDICLIC\Export Quizz\" est affiché")."<br><br>".
		 "<b>".htmlentities("B- Export de la liste des dossiers nom/prénom/N° dossier")."</b><br><br>".
		 htmlentities("1 - sur un dossier patient ouvert, aller sur la loupe qui est la sixième icône en haut en partant de la droite")."<br><br>".
		 htmlentities("2 - on arrive à un écran rechercher patient. Trois onglets nom prénom autres. Cliquer sur autres, mais avec le clic droit")."<br><br>".
		 htmlentities("3 - sur ce clic droit faire afficher tous les dossiers du fichier")."<br><br>".
		 htmlentities("4 - la recherche se lance et affiche tous les fichiers du dossier, et on a dans la dernière des colonnes ou presque le n° du dossier")."<br><br>".
		 htmlentities("5 - en cliquant sur enregistrer, (icône d'une disquette) il y a exportation de toutes ces données")."<br><br>".
		 htmlentities("6 - et un fichier est créé dans le répertoire \"C:\Programfiles\CEGEDIM\Mediclick Studio 4\ qui se nomme Export_Activite.txt")."<br><br>".
		 htmlentities("6bis - Si tous les médecins ne font pas partie d'asalée, ouvrir Excel. Aller dans Fichier/ouvrir. Dans la zone \"Type de fichier\", sélectionner \"Tous les fichiers\". Sélectionner le fichier créé à l'étape 6.")."<br>".
		 htmlentities("Excel ouvre un écran intermédiaire. Cliquer sur \"suivant\" 2 fois puis sur \"Terminer\"")."<br>".
		 htmlentities("Cliquer bouton droit sur la 1ère ligne puis sur \"Insérer\". Indiquer des titres de colonnes pour le nom (col A), prénom (col B), date de naissance(col F), n° de dossier ")."<u>(col N)</u>".htmlentities(", médecin traitant (H).")."<br>".
		 htmlentities("Supprimer les colonnes pour lesquelles aucun titre n'a été donné")."<br>".
		 htmlentities("Aller dans Données/trier. dans la zone \"trier par\", sélectionner médecin puis cliquer sur OK ")."<br>".
		 htmlentities("Supprimer les lignes correspondant aux médecins ne faisant pas partie d'Asalée")."<br><br>".
		 htmlentities("Enregistrer le fichier en allant dans Fichier/Enregistrer sous. Dans Type de fichier, sélectionner \"Texte (séparateur: tabulation) (*.txt)")."<br><br>".
		 "<b>".htmlentities("C- Sélection des fichiers dans le portail asalée")."</b><br><br>".
		 htmlentities("1 - Dans la zone \"Fichier des examens\", sélectionner le fichier créé à l'étape A")."<br><br>".
		 htmlentities("2 - Dans la zone \"Fichier patients\", sélectionner le fichier créé à l'étape B")."<br><br>".
		 htmlentities("3 - Cliquer sur \"Intégrer\"")."<br><br>";

}
elseif($_GET["logiciel"]=="medimust"){
	echo htmlentities("1- à partir du bureau, lancer l'icône \"Etats pour médimust\"")."<br><br>".
		 htmlentities("2- Lorsque le logiciel propose de sélectionner la base de données, sélectionner \"localhost\" puis cliquer sur \"ok\"")."<br><br>".
		 htmlentities("3- La liste des biologies se trouve dans la table \"courriers_bilan\". Cette table peut être sélectionnée par le menu déroulant en haut à gauche")."<br><br>".
		 htmlentities("4-	Pour la table \"courriers_bilan\" : ")."<br>".
		 htmlentities("a-	cliquer sur \"chercher\". Sur le champ \"date_resultat\" indiquer \"supérieur à\" et indiquer la date (1 mois avant la date du jour), puis cliquer sur \"chercher\"")."<br>".
		 htmlentities("b-	cliquer sur \"exporter\" puis sélectionner \"SQL\"")."<br><br>".
		 htmlentities("5-	La liste des patients se trouve dans la table \"patients\"")."<br><br>".
		 htmlentities("6-	Pour la table \"patients\" :")."<br>".
		 htmlentities("a-	cliquer sur \"chercher\". Sur le champ \"profession\" indiquer \"différent  de\" et laisser la valeur vide, puis cliquer sur \"chercher\"")."<br>".
		 htmlentities("b-	cliquer sur \"exporter\" puis sélectionner \"SQL\"")."<br><br>";

}
elseif($_GET["logiciel"]=="medistory"){
	echo htmlentities("Ici sera affiché un guide pour l'extraction du fichier, adaptable en fonction du logiciel (ici Medistory)");
}
elseif($_GET["logiciel"]=="mediwin"){
	echo htmlentities("1- Aller dans fichier/menu")."<br><br>".
		 htmlentities("2- Cliquer sur statistiques. Le mot de passe est demandé")."<br><br>".
		 htmlentities("3- Indiquer comme critère : consultation : date \"> 11/07/2011\" ")."<br>".
		 htmlentities("Modifier la date pour prendre en compte la date de dernière extraction")."<br><br>".
		 htmlentities("4-Cliquer \"Chercher\"")."<br><br>".
		 htmlentities("5-Cliquer sur \"exploiter les résultats\" puis sur \"Export HPRIM\". Attendre que le sablier disparaisse.")."<br><br>".
		 htmlentities("6- A partir du portail PSA, sur la page d'intégration automatique, cliquer sur \"Parcourir\" ou \"choisissez un fichier\" puis cliquer sur \"Poste de Travail\", double cliquer sur \"Disque Local C:\", double cliquer sur \"MediWin\", double cliquer sur \"Local\", double cliquer sur \"Resutext.txt\" ")."<br><br>".
		 htmlentities("Cliquer sur \"Intégrer\"")."<br><br>";
}
elseif($_GET["logiciel"]=="shaman"){
	echo htmlentities("Ici sera affiché un guide pour l'extraction du fichier, adaptable en fonction du logiciel (ici Shaman)");
}
elseif($_GET["logiciel"]=="xmed"){
	echo htmlentities("Ici sera affiché un guide pour l'extraction du fichier, adaptable en fonction du logiciel (ici XMed)");
}
else{
	echo htmlentities("Logiciel non reconnu actuellement");
}

echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript://' style='color:#000000' onclick=\"ajax_hideTooltip()\">Fermer l'aide</a><br><br>";
