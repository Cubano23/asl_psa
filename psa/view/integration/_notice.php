<?php

session_start();
// echo $_GET["logiciel"];die;

$base=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
require("$base/inclus/accesbase.inc.php"); 


# connexion aux donn�es
mysql_connect($serveur,$idDB,$mdpDB) or
   die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
   die("Impossible de se connecter � la base");


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
	// echo htmlentities("Ici sera affich� un guide pour l'extraction du fichier, adaptable en fonction du logiciel (ici Axisant�)");

	echo htmlentities("L'extraction du fichier � partir d'Axisant� devra se faire suivant la marche suivante :")."<br><br>".
		 htmlentities("1- ouvrir Axisant�")."<br><br>".
		 htmlentities("2- aller dans fichier/statistiques et cartes de visite")."<br>".
		 htmlentities("un nouvel �cran s'ouvre")."<br><br>".
		 htmlentities("3- aller dans statistiques/recherche multi crit�re")."<br>".
		 htmlentities("La liste des mod�les de recherche s'affiche")."<br><br>".
		 htmlentities("4- Cliquer sur \"+\" ou s�lectionner la requ�te si elle a �t� enregistr�e")."<br>".
		 htmlentities("Une nouvelle fen�tre s'ouvre, dans laquelle les crit�res de recherches peuvent �tre s�lectionn�s. S�lectionner dans �tat civil/contrat m�decin traitant : n'est pas vide et d�c�d� est vide")."<br><br>".
		 htmlentities("5- S'assurer que la case statistique global soit coch�e, sauf si tous les m�decins du cabinet ne sont pas dans asal�e")."<br><br>".
		 htmlentities("6- Lancer la recherche")."<br>".
		 htmlentities("A l'issue de la recherche, la liste des patients est affich�e en bas d'�cran")."<br><br>".
		 htmlentities("7- Cliquer sur Gestion des r�sultats")."<br>".
		 htmlentities("Le syst�me propose alors de cr�er 4 fichiers diff�rents. Cliquer sur \"Export sur 4 fichiers\". Accepter la cr�ation des fichiers en cliquant sur Ok")."<br><br>".
		 htmlentities("8- A la fin de l'int�gration, cliquer sur le bouton Parcourir de la page d'int�gration automatique du portail PSA")."<br><br>".
		 htmlentities("9- aller dans poste de travail, double cliquer sur disque local c:/, double cliquer sur axisante2000, double cliquer sur application/")."<br>".
		 htmlentities("4 fichiers sont cr��s par le logiciel : ")."<br>".
		 htmlentities("- VarCheminUserData]kSuivi")."<br>".
		 htmlentities("- VarCheminUserData]kPatient")."<br>".
		 htmlentities("- VarCheminUserData]kConsult")."<br>".
		 htmlentities("- VarCheminUserData]kAntecedant")."<br>";
		 if(strcasecmp($_SESSION["cabinet"], "chatillon")==0){
			echo htmlentities("Zipper le fichier \"VarCheminUserData]kSuivi\". Si un fichier VarCheminUserData]kSuivi.zip existe d�j�, le supprimer. Pour cr�er le fichier zip, cliquer bouton droit sur le fichier, puis \"Envoyer Vers\" puis \"dossier compress�\"")."<br>";
		 }
	echo "<br>";
	echo htmlentities("10- S�lectionner le fichier VarCheminUserData]kSuivi, puis cliquer sur Ouvrir, puis cliquer Int�grer")."<br><br><br>";
/*		 
comme on ne peut pas enlever les noms au moment de l'extraction, au postage le fichier est chiffr� par https. A la r�ception, les premi�res colonnes ne sont pas exploit�es et ne sont exploit�es que des colonnes anonymes
*/
}
elseif($_GET["logiciel"]=="crossway"){
	echo htmlentities("1- Lancer Crossway � partir de l'ic�ne \"requeteur\" qui est sur le bureau.")."<br>".
		 htmlentities("Le logiciel demande de renseigner un login et un mot de passe")."<br><br>".
		 htmlentities("2- Lorsque le logiciel est d�marr�, sur le bandeau de gauche cliquer sur documents/nouveau si la requ�te n'a pas �t� d�j� enregistr�e. Sinon double cliquer sur la requ�te s�lectionn�e, puis passer � l'�tape 6 pour modifier les dates d'examens.")."<br>".
		 htmlentities("Une nouvelle fen�tre s'ouvre.")."<br><br>".
		 htmlentities("3- le champ \"nom de la requ�te\" permet d'enregistrer la requ�te sous ce nom pour la retrouver facilement. Ce champ est libre.")."<br><br>".
		 htmlentities("4- Un premier champ intitul� \"s�lection des tables\" fait apparaitre 2 listes de tables. Sont affich�es : ")."<br>".
		 htmlentities("- � gauche les tables s�lectionnables")."<br>".
		 htmlentities("- � droites les tables s�lectionn�es.")."<br><br>".
		 htmlentities("Diff�rents types de tables sont disponibles : biom�trie, biologie, patients, maladies, ant�c�dents, etc... S�lectionner la table \"Biologie\" et la table \"patients\"")."<br><br>".
		 htmlentities("5- dans la partie \"champs de sortie\" : ")."<br>".
		 htmlentities("cliquer sur \"+\" pour chaque nouvelle donn�e � extraire. A chaque fois, une ligne s'affiche.")."<br>".
		 htmlentities("dans colonne \"table\", s�lectionner la table dans laquelle la donn�e apparait (par ex \"patient\" pour le n� dossier)")."<br>".
		 htmlentities("dans la colonne \"champ\", s�lectionner le champ (par ex \"N� dossier\"). Il faut ajouter autant de ligne que de champs de sortie. ")."<br><br>".
		 htmlentities("6- dans \"crit�res de recherche\", cliquer sur \"+\" � chaque crit�re � rajouter")."<br>".
		 htmlentities("Indiquer un crit�re uniquement sur la date d'examen : apr�s le 01/07/2011 par exemple. Pour limiter le nombre de lignes de r�sultat et donc le temps d'extraction, il possible de s�lectionner une date correspondant � la derni�re extraction (de pr�f�rence la veille). ")."<br><br>".
		 htmlentities("7- cliquer sur l'icone en bas � gauche (triangle vert) pour lancer la recherche.")."<br>".
		 htmlentities("A la fin de la recherche, un tableau \"type excel\" avec les champs de sortie est affich�. ")."<br><br>".
		 htmlentities("8- Fermer la fen�tre de r�sultat, puis cliquer sur \"Valider\". La fen�tre de la requ�te se ferme.")."<br><br>".
		 htmlentities("9- Cliquer une fois sur le nom de la requ�te qui a �t� enregistr�e, puis cliquer sur l'ic�ne repr�sentant une fl�che rouge dirig�e vers \"X\". Une fen�tre s'ouvre.")."<br>".
		 htmlentities("Le logiciel propose d'exporter dans un fichier. Saisir un nom de fichier dans la zone pr�vue (par ex : export.xls), puis cliquer sur le dossier en bout de ligne pour choisir le dossier o� exporter. Cocher type d'exportation : Excel.")."<br>".
		 htmlentities("Cliquer sur \"exporter\".")."<br><br>".
		 htmlentities("10- Le fichier est alors sauvegard� dans le r�pertoire s�lectionn�. ")."<br><br>".
		 htmlentities("11- Ouvrir le fichier, puis l'enregistrer. Un message d'Excel doit indiquer que le fichier est enregistr� dans une ancienne version et demande s'il faut convertir le fichier. Accepter la conversion. ")."<br><br>".
		 htmlentities("12- Sur le portail asal�e, cliquer sur le bouton \"parcourir\" puis s�lectionn� le fichier cr��. Cliquer ensuite sur \"Int�grer\"")."<br><br>";
 
}
elseif($_GET["logiciel"]=="dbmed"){
	echo htmlentities("1- d�marrer access")."<br><br>".
		 htmlentities("2- Cr�er une base de donn�es, cliquer sur \"base de donn�es\" puis ok. Indique un nom de base de donn�es (par exemple \"Export 20110928\"), s�lectionner le r�pertoire o� l'enregistrer puis cliquer sur \"cr�er\"")."<br><br>".
		 htmlentities("3- aller fichier/donn�es externes/importer ")."<br>".
		 htmlentities("type de fichier : s�lectionner \"fichier dBASE 5\"")."<br>".
		 htmlentities("Selectionner le fichier c:/cabinet/dbmed/DBMDATA/param.dbf")."<br>".
		 htmlentities("cliquer sur \"importer\". Lorsque le message confirme l'importation, cliquer sur ok, puis sur annuler.")."<br><br>".
		 htmlentities("4- double cliquer sur le fichier \"param\" cr�� � l'�tape 3.")."<br><br>".
		 htmlentities("5- aller dans fichier/exporter")."<br>".
		 htmlentities("Type de fichier : s�lectionner \"Fichier texte (*.txt, *.csv)\"")."<br>".
		 htmlentities("S�lectionner le r�pertoire o� sauvegarder le fichier et le nommer param20110928. Cliquer sur suivant 2 fois puis sur terminer")."<br><br>".
		 htmlentities("6- Zipper le fichier cr�� � l'�tape 5. Pour cela, cliquer bouton droit sur le fichier, puis \"Envoyer vers\" puis \"dossier compress�\"")."<br><br>".
		 htmlentities("7- A partir de la page int�gration automatiques du portail asal�e, cliquer sur \"parcourir\" et s�lectionner le fichier cr�� � l'�tape 6. Cliquer ensuite sur \"int�grer\"")."<br><br>";
}
elseif($_GET["logiciel"]=="easyprat"){
	echo htmlentities("Ici sera affich� un guide pour l'extraction du fichier, adaptable en fonction du logiciel (ici EasyPrat)");
}
elseif($_GET["logiciel"]=="hellodoc" || $_GET["logiciel"]=="hellodoc_v5.6" || $_GET["logiciel"]=="hellodoc_v5.55"){
	echo htmlentities("1- Aller dans Fichier/Exporter/Exporter en ASCII.")."<br>".
		 htmlentities("Le logiciel propose un menu d�roulant � partir duquel il est possible de s�lectionner le fichier � exporter")."<br><br>".
		 htmlentities("2- S�lectionner \"Biologies des patients\" puis cliquer sur \"ok\"")."<br>".
		 htmlentities("Le logiciel demande de confirmer la cr�ation du fichier. Accepter la cr�ation")."<br>";
		 
		 if(strcasecmp($_SESSION["cabinet"], "ruelle")==0){
			echo htmlentities("Le fichier est enregist� sous \"z:/LARENTE/export_ASCII/biologies.asc\"")."<br>";
		 }
		 
		 if(strcasecmp($_SESSION["cabinet"], "amberieu")==0){
			echo htmlentities("Le fichier est enregist� sous \"x:/cabinets_v5.60/les allymes/export_ASCII/biologies.asc\"")."<br>";
		 }
		 
		 if(strcasecmp($_SESSION["cabinet"], "chambery1")==0){
			echo htmlentities("Le fichier se nomme \"biologie.asc\". Pour le retrouver : d�marrer l'explorateur windows, aller dans \"favoris r�seau\", puis \"imagine editions sur serveur\", puis \"hellodoc\" puis \"scm bertillet\", puis \"export_ASCII\"")."<br>";
		 }

		 // if(strcasecmp($_SESSION["cabinet"], "saulieu")==0){
			// echo htmlentities("Le fichier se nomme \"biologie.asc\". Pour le retrouver : d�marrer l'explorateur windows, aller dans \"favoris r�seau\", puis \"imagine editions sur serveur\", puis \"hellodoc\" puis \"scm bertillet\", puis \"export_ASCII\"")."<br>";
		 // }
		 
	echo "<br>".htmlentities("3- Aller dans le r�pertoire o� le fichier est enregistr� ")."<br><br>".
		 htmlentities("4- Cliquer sur le fichier cr�� avec le bouton droit de la souris, puis \"Envoyer vers\" puis \"Dossier compress�\"")."<br>".
		 htmlentities("Si un fichier nomm� \"Biologies.zip\" est d�j� pr�sent, le renommer avec sa date de cr�ation. Par exemple Biologies18102011.zip")."<br>".
		 htmlentities("Un fichier nomm� \"Biologies.zip\" se cr�e automatiquement")."<br><br>".
		 htmlentities("5- A partir de la page d'int�gration automatique des donn�es, cliquer sur \"Parcourir\" puis s�lectionner le fichier cr�� � l'�tape 4 (fichier Biologies.zip) puis cliquer sur \"Int�grer\"")."<br><br>";
}
elseif($_GET["logiciel"]=="ict"){
	echo htmlentities("Ici sera affich� un guide pour l'extraction du fichier, adaptable en fonction du logiciel (ici ICT)");
}
elseif($_GET["logiciel"]=="medicawin"){
	echo htmlentities("Ici sera affich� un guide pour l'extraction du fichier, adaptable en fonction du logiciel (ici M�dicawin)");
}
elseif($_GET["logiciel"]=="mediclic3" || $_GET["logiciel"]=="mediclic4" || $_GET["logiciel"]=="mediclic5"){
	echo htmlentities("1- Aller dans Mediclick / QuizzClick Studio")."<br><br>".
		 htmlentities("2- Aller dans l'onglet \"Dossier\". Sur la partie de gauche cliquer sur \"Divers\" puis double cliquer sur \"n� de dossier\". Une ligne n� de dossier doit apparaitre sur le cadre principal de la page. Sur les valeurs min et max indiquer 0 et 10000")."<br><br>".
		 htmlentities("3- aller dans l'onglet \"Biologie\". Dans la partie \"rubriques standard\", double cliquer sur date de l'examen et date de prescription. Pour date de l'examen indiquer un intervalle de date compris entre 2 semaines avant la date du jour et la date du jour. Pour date de prescription indiquer un intervalle entre 2 mois avant la date du jour et la date du jour")."<br><br>".
		 htmlentities("4- dans la partie \"nom de l'examen\" un examen � exporter. Par exemple HBA1c")."<br><br>".
		 htmlentities("5- cliquer sur \"lancer la recherche\". La liste des examens trouv�s apparait en bas de page")."<br><br>".
		 htmlentities("6- enregistrer le fichier en cliquant sur la disquette")."<br><br>".
		 htmlentities("7- Aller sur la page d'int�gration automatiques de donn�es et s�lectionner le fichier cr�� � l'�tape 6 puis cliquer sur int�grer")."<br><br>".
		 htmlentities("8- Si vous souhaitez int�grer d'autres examens, cliquer que l'icone poubelle de la ligne cr��e � l'�tape 4, puis ex�cuter � nouveau les �tapes 4, 5, 6, 7 en s�lectionnant l'examen d�sir� au moment de faire l'�tape 4")."<br><br>";
}
elseif($_GET["logiciel"]=="mediclic"){
	echo htmlentities("La proc�dure se passe en 3 parties. Les deux premi�res parties permettent de faire 2 extraits diff�rents � partir du logiciel m�dical. La troisi�me partie permet de faire l'int�gration automatique")."<br><br>".
		 "<b>".htmlentities("A- Export de la liste des examens dans un fichier au format nom/pr�nom/date/valeur")."</b><br><br>".
		 htmlentities("1- fermer la biologie si un dossier est d�j� ouvert")."<br><br>".
		 htmlentities("2- aller dans le menu m�diclic !/quizzclick ! studio")."<br>".
		 htmlentities("un fen�tre s'ouvre qui fait apparaitre 2 s�rie d'onglets en miroir: ")."<br>".
		 htmlentities("patient, ordonnance, biologie, examen, ant�c�dents, actes, multi")."<br>".
		 htmlentities("A gauche il y a la partie de recherche et � droite les r�sultats")."<br><br>".
		 htmlentities("3- cliquer sur biologie sur la partie de gauche. Les r�sultats d'une ancienne recherche s'affichent � droite")."<br><br>".
		 htmlentities("4- en haut d'�cran � gauche s'affiche \"examen biologique\" et une zone � remplir. Saisir HB. Le syst�me propose HBA1c. Faire entr�e sur clavier")."<br><br>".
		 htmlentities("5- \"examen\" apparait. Il s'agit des conditions � prendre en compte (une ligne avec 8 colonnes : Nom examen, mini, maxi, inf/sup/�gal, oui/non, du.., au... Rech")."<br>".
		 htmlentities("Cocher la case \"tous les praticiens\"")."<br><br>".
		 htmlentities("6- changer la date \"du\" en prenant 1 mois avant la date du jour. Par exemple \"04 06 2011\". Par d�faut, date de fin est la date du jour.")."<br><br>".
		 htmlentities("7- Effectuer � nouveau les �tapes 4, 5, 6 pour tous les examens de biologie disponibles dans asal�e : HDL, LDL, Cholest�rol total, Cr�atinine, etc... Pour chaque examen s�lectionn�, une ligne apparait � l'�tape 5.")."<br><br>".
		 htmlentities("8- dans \"options g�n�rales : patients de\" : cocher \"tous les praticiens\" si tous les praticiens sont dans asal�e, sinon faire la proc�dure pour chaque praticien qui est dans asal�e.")."<br><br>".
		 htmlentities("9- cliquer sur l'ic�ne montrant une personne courant (en haut � droite de la partie gauche). Un popup de recherche s'affiche")."<br><br>".
		 htmlentities("10- Les r�sultats de la recherche apparaissent sur la partie droite, sous le comptage.")."<br><br>".
		 htmlentities("11- sur la moiti� droite, bandeau en haut, les crit�res suivants apparaissent : \"tous les crit�res\", \"au moins un\", \"tout\", \"hba1c\"")."<b><u>".htmlentities(" Cliquer sur \"tout\"")."</u></b>".htmlentities(" pour faire appara�tre l'ensemble des examens.")."<br><br>".
		 htmlentities("Les r�sultats sont affich�s avec une ligne par examen, avec les informations suivantes : nom, pr�nom, date de naissance, date de l'examen, nature de l'examen, r�sultat")."<br><br>".
		 htmlentities("12- cliquer sur disquette tout en haut � gauche de la recherche. le message \"l'exportation de 'QuizzExport 110706 [10h26].xls' est situ� dans le dossier M:\MEDICLIC\Export Quizz\" est affich�")."<br><br>".
		 "<b>".htmlentities("B- Export de la liste des dossiers nom/pr�nom/N� dossier")."</b><br><br>".
		 htmlentities("1 - sur un dossier patient ouvert, aller sur la loupe qui est la sixi�me ic�ne en haut en partant de la droite")."<br><br>".
		 htmlentities("2 - on arrive � un �cran rechercher patient. Trois onglets nom pr�nom autres. Cliquer sur autres, mais avec le clic droit")."<br><br>".
		 htmlentities("3 - sur ce clic droit faire afficher tous les dossiers du fichier")."<br><br>".
		 htmlentities("4 - la recherche se lance et affiche tous les fichiers du dossier, et on a dans la derni�re des colonnes ou presque le n� du dossier")."<br><br>".
		 htmlentities("5 - en cliquant sur enregistrer, (ic�ne d'une disquette) il y a exportation de toutes ces donn�es")."<br><br>".
		 htmlentities("6 - et un fichier est cr�� dans le r�pertoire \"C:\Programfiles\CEGEDIM\Mediclick Studio 4\ qui se nomme Export_Activite.txt")."<br><br>".
		 htmlentities("6bis - Si tous les m�decins ne font pas partie d'asal�e, ouvrir Excel. Aller dans Fichier/ouvrir. Dans la zone \"Type de fichier\", s�lectionner \"Tous les fichiers\". S�lectionner le fichier cr�� � l'�tape 6.")."<br>".
		 htmlentities("Excel ouvre un �cran interm�diaire. Cliquer sur \"suivant\" 2 fois puis sur \"Terminer\"")."<br>".
		 htmlentities("Cliquer bouton droit sur la 1�re ligne puis sur \"Ins�rer\". Indiquer des titres de colonnes pour le nom (col A), pr�nom (col B), date de naissance(col F), n� de dossier ")."<u>(col N)</u>".htmlentities(", m�decin traitant (H).")."<br>".
		 htmlentities("Supprimer les colonnes pour lesquelles aucun titre n'a �t� donn�")."<br>".
		 htmlentities("Aller dans Donn�es/trier. dans la zone \"trier par\", s�lectionner m�decin puis cliquer sur OK ")."<br>".
		 htmlentities("Supprimer les lignes correspondant aux m�decins ne faisant pas partie d'Asal�e")."<br><br>".
		 htmlentities("Enregistrer le fichier en allant dans Fichier/Enregistrer sous. Dans Type de fichier, s�lectionner \"Texte (s�parateur: tabulation) (*.txt)")."<br><br>".
		 "<b>".htmlentities("C- S�lection des fichiers dans le portail asal�e")."</b><br><br>".
		 htmlentities("1 - Dans la zone \"Fichier des examens\", s�lectionner le fichier cr�� � l'�tape A")."<br><br>".
		 htmlentities("2 - Dans la zone \"Fichier patients\", s�lectionner le fichier cr�� � l'�tape B")."<br><br>".
		 htmlentities("3 - Cliquer sur \"Int�grer\"")."<br><br>";

}
elseif($_GET["logiciel"]=="medimust"){
	echo htmlentities("1- � partir du bureau, lancer l'ic�ne \"Etats pour m�dimust\"")."<br><br>".
		 htmlentities("2- Lorsque le logiciel propose de s�lectionner la base de donn�es, s�lectionner \"localhost\" puis cliquer sur \"ok\"")."<br><br>".
		 htmlentities("3- La liste des biologies se trouve dans la table \"courriers_bilan\". Cette table peut �tre s�lectionn�e par le menu d�roulant en haut � gauche")."<br><br>".
		 htmlentities("4-	Pour la table \"courriers_bilan\" : ")."<br>".
		 htmlentities("a-	cliquer sur \"chercher\". Sur le champ \"date_resultat\" indiquer \"sup�rieur �\" et indiquer la date (1 mois avant la date du jour), puis cliquer sur \"chercher\"")."<br>".
		 htmlentities("b-	cliquer sur \"exporter\" puis s�lectionner \"SQL\"")."<br><br>".
		 htmlentities("5-	La liste des patients se trouve dans la table \"patients\"")."<br><br>".
		 htmlentities("6-	Pour la table \"patients\" :")."<br>".
		 htmlentities("a-	cliquer sur \"chercher\". Sur le champ \"profession\" indiquer \"diff�rent  de\" et laisser la valeur vide, puis cliquer sur \"chercher\"")."<br>".
		 htmlentities("b-	cliquer sur \"exporter\" puis s�lectionner \"SQL\"")."<br><br>";

}
elseif($_GET["logiciel"]=="medistory"){
	echo htmlentities("Ici sera affich� un guide pour l'extraction du fichier, adaptable en fonction du logiciel (ici Medistory)");
}
elseif($_GET["logiciel"]=="mediwin"){
	echo htmlentities("1- Aller dans fichier/menu")."<br><br>".
		 htmlentities("2- Cliquer sur statistiques. Le mot de passe est demand�")."<br><br>".
		 htmlentities("3- Indiquer comme crit�re : consultation : date \"> 11/07/2011\" ")."<br>".
		 htmlentities("Modifier la date pour prendre en compte la date de derni�re extraction")."<br><br>".
		 htmlentities("4-Cliquer \"Chercher\"")."<br><br>".
		 htmlentities("5-Cliquer sur \"exploiter les r�sultats\" puis sur \"Export HPRIM\". Attendre que le sablier disparaisse.")."<br><br>".
		 htmlentities("6- A partir du portail PSA, sur la page d'int�gration automatique, cliquer sur \"Parcourir\" ou \"choisissez un fichier\" puis cliquer sur \"Poste de Travail\", double cliquer sur \"Disque Local C:\", double cliquer sur \"MediWin\", double cliquer sur \"Local\", double cliquer sur \"Resutext.txt\" ")."<br><br>".
		 htmlentities("Cliquer sur \"Int�grer\"")."<br><br>";
}
elseif($_GET["logiciel"]=="shaman"){
	echo htmlentities("Ici sera affich� un guide pour l'extraction du fichier, adaptable en fonction du logiciel (ici Shaman)");
}
elseif($_GET["logiciel"]=="xmed"){
	echo htmlentities("Ici sera affich� un guide pour l'extraction du fichier, adaptable en fonction du logiciel (ici XMed)");
}
else{
	echo htmlentities("Logiciel non reconnu actuellement");
}

echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript://' style='color:#000000' onclick=\"ajax_hideTooltip()\">Fermer l'aide</a><br><br>";
