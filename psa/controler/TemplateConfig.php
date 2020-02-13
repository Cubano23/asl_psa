<?php	
	require_once("Template.php");
	$defaultTemplateURL = "view/template/defaulttemplate.php";


	
	$templateMap = array( 

		//Dashboard /* pierre */
		"view/dashboard/dashboard.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Dashboard",
												"bodyTitle"=>"Dashboard",
												"body"=>"view/dashboard/dashboard.php")),

		//Frais
		"view/frais/newfrais.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Demande de remboursement de frais",
												"bodyTitle"=>"demande de remboursement de frais",
												"body"=>"view/frais/newfrais.php")),
	
		"view/frais/viewfraisaftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Demande de remboursement de frais",
												"bodyTitle"=>"demande de remboursement de frais",
												"body"=>"view/frais/viewfraisaftercreate.php")),
	

		//----------------------------------------------------------------------------------------------------
		//cong&eacutes
		"view/conges/newconges.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Demande de cong&eacutes",
												"bodyTitle"=>"demande de cong&eacutes",
												"body"=>"view/conges/newconges.php")),
	
		"view/conges/viewcongesaftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Demande de cong&eacutes",
												"bodyTitle"=>"demande de cong&eacutes",
												"body"=>"view/conges/viewcongesaftercreate.php")),
	

		//----------------------------------------------------------------------------------------------------
	    // Visu RCVA
		"view/cardiovasculaire/newlistedonnees.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des donn&eacutes RCVA disponibles",
												"bodyTitle"=>"Liste des donn&eacutes RCVA disponibles",
												"body"=>"view/cardiovasculaire/newlistedonnees.php")),

		"view/cardiovasculaire/viewlistedonnees.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des donn&eacutes RCV disponibles dans les logiciels",
												"bodyTitle"=>"Liste des donn&eacutes RCV disponibles dans les logiciels",
												"body"=>"view/cardiovasculaire/viewlistedonnees.php")),

		"view/cardiovasculaire/viewlistedonneesaftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des donn&eacutes RCVA disponibles",
												"bodyTitle"=>"Liste des donn&eacutes RCVA disponibles",
												"body"=>"view/cardiovasculaire/viewlistedonneesaftercreate.php")),

		//--------------------------------------------------------------------------------------------------------
		// Visu exam
		"view/biologie/managebiologie.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Visualiser les examens d'un dossier",
												"bodyTitle"=>"Visualiser les examens d'un dossier",
												"body"=>"view/biologie/managebiologie.php")),

		"view/biologie/listeexam.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Visualiser les examens d'un dossier",
												"bodyTitle"=>"Visualiser les examens d'un dossier",
												"body"=>"view/biologie/listeexam.php")),

		"view/biologie/modifbiologie.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Modifier un examen",
												"bodyTitle"=>"Modifier un examen",
												"body"=>"view/biologie/newbiologie.php")),

		"view/biologie/viewbiologieaftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Cr&eacuteer un examen",
												"bodyTitle"=>"Cr&eacuteer un examen",
												"body"=>"view/biologie/viewbiologieaftercreate.php")),

		"view/biologie/viewbiologieafterupdate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Modifier un examen",
												"bodyTitle"=>"Modifier un examen",
												"body"=>"view/biologie/viewbiologieaftercreate.php")),


		//--------------------------------------------------------------------------------------------------------
		//Images de Fond d'Oeil

		"view/fondoeil/newfond.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"T&eacutel&eacutecharger une image de fond d'oeil",
												"bodyTitle"=>"T&eacutel&eacutecharger une image de fond d'oeil",
												"body"=>"view/fondoeil/newfond.php")),

		"view/fondoeil/viewfondaftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"T&eacutel&eacutecharger une image de fond d'oeil",
												"bodyTitle"=>"T&eacutel&eacutecharger une image de fond d'oeil",
												"body"=>"view/fondoeil/viewfondaftercreate.php")),

		"view/fondoeil/listfond.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des fonds d'oeils r&eacutealis&eacutes",
												"bodyTitle"=>"Liste des fonds d'oeils r&eacutealis&eacutes",
												"body"=>"view/fondoeil/listfond.php")),



		//---------------------------------------------------------------------------------------------
	    // FICHE CABINET
		"view/fichecabinet/newfichecabinet.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche cabinet",
												"bodyTitle"=>"Fiche cabinet",
												"body"=>"view/fichecabinet/newfichecabinet.php")),

		"view/fichecabinet/viewfichecabinet.php"=>
		new Template("view/template/templatesans_prec.php",array( "pageTitle"=>"Potentiel des cabinets",
												"bodyTitle"=>"Potentiel des cabinets",
												"body"=>"view/fichecabinet/viewfichecabinet.php")),

		"view/fichecabinet/viewfichecabinetaftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche cabinet",
												"bodyTitle"=>"Fiche cabinet",
												"body"=>"view/fichecabinet/viewfichecabinetaftercreate.php")),

		//-----------------------------------------------------------------------------------------------
	    // POSER UNE QUESTION
		"view/poserquestion/newposerquestion.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Contacter le support Asal&eacutee",
												"bodyTitle"=>"Contacter le support Asal&eacutee",
												"body"=>"view/poserquestion/newposerquestion.php")),


		"view/poserquestion/viewposerquestionaftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Contacter le support Asal&eacutee",
												"bodyTitle"=>"Contacter le support Asal&eacutee",
												"body"=>"view/poserquestion/viewposerquestionaftercreate.php")),

		"view/poserquestion/viewposerquestion.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Visualiser la liste des questions",
												"bodyTitle"=>"Visualiser la liste des questions",
												"body"=>"view/poserquestion/viewposerquestion.php")),

		//-----------------------------------------------------------------------------------------------
		// QUESTIONNAIRE SATISFACTION PATIENT
		"view/satisfactionpatient/newsatisfactionpatient.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Questionnaire de satisfaction patient",
												"bodyTitle"=>"Questionnaire de satisfaction patient",
												"body"=>"view/satisfactionpatient/newsatisfactionpatient.php")),

		"view/satisfactionpatient/viewfichecabinet.php"=>
		new Template("view/template/templatesans_prec.php",array( "pageTitle"=>"Potentiel des cabinets",
												"bodyTitle"=>"Potentiel des cabinets",
												"body"=>"view/fichecabinet/viewfichecabinet.php")),

		"view/satisfactionpatient/viewsatisfactionpatientaftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Questionnaire de satisfaction patient",
												"bodyTitle"=>"Questionnaire de satisfaction patient",
												"body"=>"view/satisfactionpatient/viewsatisfactionpatientaftercreate.php")),


		//-----------------------------------------------------------------------------------------------
		// QUESTIONNAIRE MEDECIN
		"view/questionnairemedecin/newquestionnairemedecin.php"=>
		new Template("view/template/templatemedecin.php",array( "pageTitle"=>"Action exp&eacuterimentation ASALEE",
												"bodyTitle"=>"Action exp&eacuterimentation ASALEE",
												"body"=>"view/questionnairemedecin/newquestionnairemedecin.php")),

		"view/satisfactionpatient/viewfichecabinet.php"=>
		new Template("view/template/templatesans_prec.php",array( "pageTitle"=>"Potentiel des cabinets",
												"bodyTitle"=>"Potentiel des cabinets",
												"body"=>"view/fichecabinet/viewfichecabinet.php")),

		"view/questionnairemedecin/viewquestionnairemedecinaftercreate.php"=>
		new Template("view/template/templatemedecin.php",array( "pageTitle"=>"Questionnaire enquete interne ASALEE",
												"bodyTitle"=>"Questionnaire enquete interne",
												"body"=>"view/questionnairemedecin/viewquestionnairemedecinaftercreate.php")),

		//-----------------------------------------------------------------------------------------------
		// DEPISTAGE DU CANCER DU COLON
		"view/cancercolon/managedepistagecolon.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Questionnaire de d&eacutepistage du cancer du colon",
												"bodyTitle"=>"Questionnaire de d&eacutepistage du cancer du colon",
												"body"=>"view/cancercolon/managedepistagecolon.php")),

		"view/cancercolon/newdepistagecolon.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche de d&eacutepistage du cancer du colon",
												"bodyTitle"=>"Fiche de d&eacutepistage du cancer du colon",
												"body"=>"view/cancercolon/newdepistagecolon.php")),

		"view/cancercolon/viewdepistagecolon.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche de d&eacutepistage du cancer du colon",
												"bodyTitle"=>"Fiche de d&eacutepistage du cancer du colon",
												"body"=>"view/cancercolon/viewdepistagecolon.php")),

		"view/cancercolon/viewdepistagecolonaftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Questionnaire de d&eacutepistage du cancer du colon",
												"bodyTitle"=>"Questionnaire de d&eacutepistage du cancer du colon",
												"body"=>"view/cancercolon/viewdepistagecolonaftercreate.php")),


		"view/cancercolon/listdepistagecolon.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des dossiers de d&eacutepistage du cancer du colon",
												"bodyTitle"=>"Liste des dossiers de d&eacutepistage du cancer du colon",
												"body"=>"view/cancercolon/listdepistagecolon.php")),

		"view/cancercolon/listdepistagecolonbydossier.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des d&eacutepistages du cancer du colon d'un dossier",
												"bodyTitle"=>"Liste des d&eacutepistages du cancer du colon d'un dossier",
												"body"=>"view/cancercolon/listdepistagecolonbydossier.php")),

		"view/cancercolon/managealertecancercolon.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Alertes du d&eacutepistage du cancer du colon",
												"bodyTitle"=>"Alertes du d&eacutepistage du cancer du colon",
												"body"=>"view/cancercolon/managealertecancercolon.php")),

		"view/cancercolon/listcancercolonalerte.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Alertes du d&eacutepistage du cancer du colon",
												"bodyTitle"=>"Alertes du d&eacutepistage du cancer du colon",
												"body"=>"view/cancercolon/listcancercolonalerte.php")),


		//-----------------------------------------------------------------------------------------------
		// TEST D'HEMOCULT
		"view/hemocult/managehemocult.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Questionnaire du test d'h&eacutemoccult",
												"bodyTitle"=>"Questionnaire du test d'h&eacutemoccult",
												"body"=>"view/hemocult/managehemocult.php")),

		"view/hemocult/newhemocult.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche de test d'h&eacutemoccult",
												"bodyTitle"=>"Fiche de test d'h&eacutemoccult",
												"body"=>"view/hemocult/newhemocult.php")),

		"view/hemocult/viewhemocult.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche de test d'h&eacutemoccult",
												"bodyTitle"=>"Fiche de test d'h&eacutemoccult",
												"body"=>"view/hemocult/viewhemocult.php")),

		"view/hemocult/viewhemocultaftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Questionnaire du test d'h&eacutemoccult",
												"bodyTitle"=>"Questionnaire du test d'h&eacutemoccult",
												"body"=>"view/hemocult/viewhemocultaftercreate.php")),


		"view/hemocult/listhemocult.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des dossiers de test d'h&eacutemoccult",
												"bodyTitle"=>"Liste des dossiers de test d'h&eacutemoccult",
												"body"=>"view/hemocult/listhemocult.php")),

		"view/hemocult/listhemocultbydossier.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des test d'h&eacutemoccult",
												"bodyTitle"=>"Liste des test d'h&eacutemoccult",
												"body"=>"view/hemocult/listhemocultbydossier.php")),

		"view/hemocult/managealertehemocult.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Alertes du test d'h&eacutemoccult",
												"bodyTitle"=>"Alertes du test d'h&eacutemoccult",
												"body"=>"view/hemocult/managealertehemocult.php")),

		"view/hemocult/listhemocultalerte.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Alertes du test d'h&eacutemoccult",
												"bodyTitle"=>"Alertes du test d'h&eacutemoccult",
												"body"=>"view/hemocult/listhemocultalerte.php")),


		//-----------------------------------------------------------------------------------------------
		// HYPER TENSION ARTERIELLE
		"view/hypertension/managehypertension.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Questionnaire de suivi de l'hypertension art&eacuterielle permanente",
												"bodyTitle"=>"Questionnaire de suivi de l'hypertension art&eacuterielle permanente<br>
																Document de travail - formulaire provisoire en test",
												"body"=>"view/hypertension/managehypertension.php")),

		"view/hypertension/newhypertension.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche de suivi de l'hypertension art&eacuterielle permanente",
												"bodyTitle"=>"Fiche de suivi de l'hypertension art&eacuterielle permanente<br>
																Document de travail - formulaire provisoire en test",
												"body"=>"view/hypertension/newhypertension.php")),

		"view/hypertension/viewhypertension.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche de suivi de l'hypertension art&eacuterielle permanente",
												"bodyTitle"=>"Fiche de suivi de l'hypertension art&eacuterielle permanente",
												"body"=>"view/hypertension/viewhypertension.php")),

		"view/hypertension/viewhypertensionaftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Questionnaire de suivi de l'hypertension art&eacuterielle permanente",
												"bodyTitle"=>"Questionnaire de suivi de l'hypertension art&eacuterielle permanente",
												"body"=>"view/hypertension/viewhypertensionaftercreate.php")),


		"view/hypertension/listhypertension.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des dossiers de suivi de l'hypertension art&eacuterielle permanente",
												"bodyTitle"=>"Liste des dossiers de suivi de l'hypertension art&eacuterielle permanente",
												"body"=>"view/hypertension/listhypertension.php")),

		"view/hypertension/listhypertensionbydossier.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des suivis de l'hypertension art&eacuterielle permanente d'un dossier",
												"bodyTitle"=>"Liste des suivis de l'hypertension art&eacuterielle permanente d'un dossier",
												"body"=>"view/hypertension/listhypertensionbydossier.php")),

		"view/hypertension/managealertehypertension.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Alertes du suivi de l'hypertension art&eacuterielle permanente",
												"bodyTitle"=>"Alertes du suivi de l'hypertension art&eacuterielle permanente",
												"body"=>"view/hypertension/managealertehypertension.php")),

		"view/hypertension/listhypertensionalerte.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Alertes du suivi de l'hypertension art&eacuterielle permanente",
												"bodyTitle"=>"Alertes du suivi de l'hypertension art&eacuterielle permanente",
												"body"=>"view/hypertension/listhypertensionalerte.php")),


		//-----------------------------------------------------------------------------------------------
		// CARDIO-VASCULAIRE
		"view/cardiovasculaire/managecardiovasculairedepart.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Formulaire collecte des donn&eacutes protocole RCVA",
												"bodyTitle"=>"Formulaire collecte des donn&eacutes protocole RCVA",
												"body"=>"view/cardiovasculaire/managecardiovasculairedepart.php")),

		"view/cardiovasculaire/managealertecardio.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Alertes protocole RCVA",
												"bodyTitle"=>"Alertes protocole RCVA",
												"body"=>"view/cardiovasculaire/managealertecardio.php")),

		"view/cardiovasculaire/listcardioalerte.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Alertes protocole RCVA",
												"bodyTitle"=>"Alertes protocole RCVA",
												"body"=>"view/cardiovasculaire/listcardioalerte.php")),

		"view/cardiovasculaire/newcardiovasculairedepart.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Formulaire collecte des donn&eacutes protocole RCVA",
												"bodyTitle"=>"Formulaire collecte des donn&eacutes protocole RCVA",
												"body"=>"view/cardiovasculaire/newcardiovasculairedepart.php")),

		"view/cardiovasculaire/newcardiovasculairedepartv2.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Formulaire collecte des donn&eacutes protocole RCVA",
												"bodyTitle"=>"Formulaire collecte des donn&eacutes protocole RCVA",
												"body"=>"view/cardiovasculaire/newcardiovasculairedepartv2.php")),

		"view/cardiovasculaire/managecardiovasculairedepartcomplement.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Formulaire collecte des donn&eacutes protocole RCVA",
												"bodyTitle"=>"Formulaire collecte des donn&eacutes protocole RCVA",
												"body"=>"view/cardiovasculaire/managecardiovasculairedepart.php")),

		"view/cardiovasculaire/newcardiovasculairedepartcomplement.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Formulaire collecte des donn&eacutes protocole RCVA",
												"bodyTitle"=>"Formulaire collecte des donn&eacutes protocole RCVA",
												"body"=>"view/cardiovasculaire/newcardiovasculairedepart.php")),

		"view/cardiovasculaire/newcardiovasculairedepartcomplementv2.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Formulaire collecte des donn&eacutes protocole RCVA",
												"bodyTitle"=>"Formulaire collecte des donn&eacutes protocole RCVA",
												"body"=>"view/cardiovasculaire/newcardiovasculairedepartv2.php")),

		"view/cardiovasculaire/managecardiovasculairedepartphoto.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Photo de d&eacutepart RCVA",
												"bodyTitle"=>"Photo de d&eacutepart RCVA",
												"body"=>"view/cardiovasculaire/managecardiovasculairedepartphoto.php")),

		"view/cardiovasculaire/viewcardiovasculairedepartphoto.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Photo de d&eacutepart RCVA",
												"bodyTitle"=>"Photo de d&eacutepart RCVA",
												"body"=>"view/cardiovasculaire/viewcardiovasculairedepartphoto.php")),

		"view/cardiovasculaire/viewcardiovasculairedepartaftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Formulaire collecte des donn&eacutes protocole RCVA",
												"bodyTitle"=>"Formulaire collecte des donn&eacutes protocole RCVA",
												"body"=>"view/cardiovasculaire/viewcardiovasculairedepartaftercreate.php")),

		"view/cardiovasculaire/listcardiovasculairedepart.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des dossiers ayant au moins un suivi RCVA",
												"bodyTitle"=>"Liste des dossiers ayant au moins un suivi RCVA",
												"body"=>"view/cardiovasculaire/listcardiovasculairedepart.php")),
		"view/cardiovasculaire/listcardiovasculairespiro.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des dossiers ayant au moins un suivi de Spirom&eacute;trie",
												"bodyTitle"=>"Liste des dossiers ayant au moins un suivi de Spirom&eacute;trie",
												"body"=>"view/cardiovasculaire/listcardiovasculairespiro.php")),

		"view/cardiovasculaire/listcardiovasculairedepartphoto.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des dossiers ayant au moins un suivi RCVA",
												"bodyTitle"=>"Liste des dossiers ayant au moins un suivi RCVA",
												"body"=>"view/cardiovasculaire/listcardiovasculairedepart.php")),

		"view/cardiovasculaire/listcardiovasculairedepartbydossier.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des suivis RCVA d'un dossier",
												"bodyTitle"=>"Liste des suivis RCVA d'un dossier",
												"body"=>"view/cardiovasculaire/listcardiovasculairedepartbydossier.php")),


		"view/cardiovasculaire/viewcardiovasculairedepart.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche de suivi RCVA",
												"bodyTitle"=>"Fiche de suivi RCVA",
												"body"=>"view/cardiovasculaire/viewcardiovasculairedepart.php")),

		"view/cardiovasculaire/diageduc/managediageduc.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Diagnostic &eacuteducatif",
												"bodyTitle"=>"Diagnostic &eacuteducatif",
												"body"=>"view/cardiovasculaire/diageduc/managediageduc.php")),

		"view/cardiovasculaire/diageduc/newdiageduc.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Diagnostic &eacuteducatif",
												"bodyTitle"=>"Diagnostic &eacuteducatif",
												"body"=>"view/cardiovasculaire/diageduc/newdiageduc.php")),

		"view/cardiovasculaire/diageduc/viewdiageducaftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Diagnostic &eacuteducatif",
												"bodyTitle"=>"Diagnostic &eacuteducatif",
												"body"=>"view/cardiovasculaire/diageduc/viewdiageducaftercreate.php")),

		"view/cardiovasculaire/premiereconsult/managepremiereconsult.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Premi&egravere consultation RCVA",
												"bodyTitle"=>"Premi&egravere consultation RCVA",
												"body"=>"view/cardiovasculaire/premiereconsult/managepremiereconsult.php")),

		"view/cardiovasculaire/premiereconsult/newpremiereconsult.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Premi&egravere consultation RCVA",
												"bodyTitle"=>"Premi&egravere consultation RCVA",
												"body"=>"view/cardiovasculaire/premiereconsult/newpremiereconsult.php")),

		"view/cardiovasculaire/premiereconsult/viewpremiereconsultcardioaftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Premi&egravere consultation RCVA",
												"bodyTitle"=>"Premi&egravere consultation RCVA",
												"body"=>"view/cardiovasculaire/premiereconsult/viewpremiereconsultcardioaftercreate.php")),

		"view/cardiovasculaire/autreconsult/manageautreconsult.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation infirmi&egravere de suivi RCVA",
												"bodyTitle"=>"Consultation infirmi&egravere de suivi RCVA",
												"body"=>"view/cardiovasculaire/autreconsult/manageautreconsult.php")),

		"view/cardiovasculaire/autreconsult/newautreconsult.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation infirmi&egravere de suivi RCVA",
												"bodyTitle"=>"Consultation infirmi&egravere de suivi RCVA",
												"body"=>"view/cardiovasculaire/autreconsult/newautreconsult.php")),

		"view/cardiovasculaire/autreconsult/viewautreconsultcardioaftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation infirmi&egravere de suivi RCVA",
												"bodyTitle"=>"Consultation infirmi&egravere de suivi RCVA",
												"body"=>"view/cardiovasculaire/autreconsult/viewautreconsultcardioaftercreate.php")),

		"view/cardiovasculaire/suiviinr/managesuiviinr.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Suivi INR",
												"bodyTitle"=>"Suivi INR",
												"body"=>"view/cardiovasculaire/suiviinr/managesuiviinr.php")),

		"view/cardiovasculaire/suiviinr/newsuiviinr.php"=>
		new Template("view/template/templatetableaularge.php",array( "pageTitle"=>"Suivi INR",
												"bodyTitle"=>"Suivi INR",
												"body"=>"view/cardiovasculaire/suiviinr/newsuiviinr.php")),

		"view/cardiovasculaire/suiviinr/viewsuiviinraftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Suivi INR",
												"bodyTitle"=>"Suivi INR",
												"body"=>"view/cardiovasculaire/suiviinr/viewsuiviinraftercreate.php")),


		"view/cardiovasculaire/completude.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Taux de compl&eacutetude des dossiers",
												"bodyTitle"=>"Taux de compl&eacutetude des dossiers",
												"body"=>"view/cardiovasculaire/completude.php")),

		//--------------------------------------------------------------------------------------------
		// DEPISTAGE DU CANCER DU SEIN
		"view/cancersein/managedepistagesein.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Questionnaire de d&eacutepistage du cancer du sein",
												"bodyTitle"=>"Questionnaire de d&eacutepistage du cancer du sein",
												"body"=>"view/cancersein/managedepistagesein.php")),

		"view/cancersein/newdepistagesein.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche de d&eacutepistage du cancer du sein",
												"bodyTitle"=>"Fiche de d&eacutepistage du cancer du sein",
												"body"=>"view/cancersein/newdepistagesein.php")),

		"view/cancersein/viewdepistagesein.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche de d&eacutepistage du cancer du sein",
												"bodyTitle"=>"Fiche de d&eacutepistage du cancer du sein",
												"body"=>"view/cancersein/viewdepistagesein.php")),

		"view/cancersein/viewdepistageseinaftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche de d&eacutepistage du cancer du sein",
												"bodyTitle"=>"Fiche de d&eacutepistage du cancer du sein",
												"body"=>"view/cancersein/viewdepistageseinaftercreate.php")),

		"view/cancersein/listdepistagesein.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des dossiers de d&eacutepistage du cancer du sein",
												"bodyTitle"=>"Liste des dossiers de d&eacutepistage du cancer du sein",
												"body"=>"view/cancersein/listdepistagesein.php")),

		"view/cancersein/listdepistageseinbydossier.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des d&eacutepistages du cancer du sein d'un dossier",
												"bodyTitle"=>"Liste des d&eacutepistages du cancer du sein d'un dossier",
												"body"=>"view/cancersein/listdepistageseinbydossier.php")),


		"view/cancersein/managealertecancersein.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Alertes du d&eacutepistage du cancer du sein",
												"bodyTitle"=>"Alertes du d&eacutepistage du cancer du sein",
												"body"=>"view/cancersein/managealertecancersein.php")),

		"view/cancersein/listcancerseinalerte.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Alertes du d&eacutepistage du cancer du sein",
												"bodyTitle"=>"Alertes du d&eacutepistage du cancer du sein",
												"body"=>"view/cancersein/listcancerseinalerte.php")),


		//--------------------------------------------------------------------------------------------
		// DEPISTAGE DU CANCER DE L'UTERUS
		"view/canceruterus/managedepistageuterus.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Questionnaire de d&eacutepistage du cancer du col de l'ut&eacuterus",
												"bodyTitle"=>"Questionnaire de d&eacutepistage du cancer du col de l'ut&eacuterus",
												"body"=>"view/canceruterus/managedepistageuterus.php")),

		"view/canceruterus/newdepistageuterus.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche de d&eacutepistage du cancer du col de l'ut&eacuterus",
												"bodyTitle"=>"Fiche de d&eacutepistage du cancer du col de l'ut&eacuterus",
												"body"=>"view/canceruterus/newdepistageuterus.php")),

		"view/canceruterus/viewdepistageuterus.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche de d&eacutepistage du cancer du col de l'ut&eacuterus",
												"bodyTitle"=>"Fiche de d&eacutepistage du cancer du col de l'ut&eacuterus",
												"body"=>"view/canceruterus/viewdepistageuterus.php")),

		"view/canceruterus/viewdepistageuterusaftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche de d&eacutepistage du cancer du col de l'ut&eacuterus",
												"bodyTitle"=>"Fiche de d&eacutepistage du cancer du col de l'ut&eacuterus",
												"body"=>"view/canceruterus/viewdepistageuterusaftercreate.php")),

		"view/canceruterus/listdepistageuterus.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des dossiers de d&eacutepistage du cancer du col de l'ut&eacuterus",
												"bodyTitle"=>"Liste des dossiers de d&eacutepistage du cancer du col de l'ut&eacuterus",
												"body"=>"view/canceruterus/listdepistageuterus.php")),

		"view/canceruterus/listdepistageuterusbydossier.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des d&eacutepistages du cancer du col de l'ut&eacuterus d'un dossier",
												"bodyTitle"=>"Liste des d&eacutepistages du cancer du col de l'ut&eacuterus d'un dossier",
												"body"=>"view/canceruterus/listdepistageuterusbydossier.php")),


		"view/canceruterus/managealertecanceruterus.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Alertes du d&eacutepistage du cancer du col de l'ut&eacuterus",
												"bodyTitle"=>"Alertes du d&eacutepistage du cancer du col de l'ut&eacuterus",
												"body"=>"view/canceruterus/managealertecanceruterus.php")),

		"view/canceruterus/listcanceruterusalerte.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Alertes du d&eacutepistage du cancer du col de l'ut&eacuterus",
												"bodyTitle"=>"Alertes du d&eacutepistage du cancer du col de l'ut&eacuterus",
												"body"=>"view/canceruterus/listcanceruterusalerte.php")),


		//--------------------------------------------------------------------------------------------
		// DEPISTAGE DU DIABETE
		"view/diabete/depistage/managedepistagediabete.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Questionnaire de d&eacutepistage du diab&egravete",
												"bodyTitle"=>"Questionnaire de d&eacutepistage du diab&egravete",
												"body"=>"view/diabete/depistage/managedepistagediabete.php")),
												
		"view/diabete/depistage/newdepistagediabete.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche de d&eacutepistage du diab&egravete",
												"bodyTitle"=>"Fiche de d&eacutepistage du diab&egravete",
												"body"=>"view/diabete/depistage/newdepistagediabete.php")),
												
		"view/diabete/depistage/viewdepistagediabete.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche de d&eacutepistage du diab&egravete",
												"bodyTitle"=>"Fiche de d&eacutepistage du diab&egravete",
												"body"=>"view/diabete/depistage/viewdepistagediabete.php")),
		
		"view/diabete/depistage/viewdepistagediabieteaftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Questionnaire de d&eacutepistage du diab&egravete",
												"bodyTitle"=>"Questionnaire de d&eacutepistage du diab&egravete",
												"body"=>"view/diabete/depistage/viewdepistagediabeteaftercreate.php")),

		"listdiabete"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des dossiers de d&eacutepistage du diab&egravete",
												"bodyTitle"=>"Liste des dossiers de d&eacutepistage du diab&egravete",
												"body"=>"view/common/list.php")),

		"listdiabetebydossier"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des dossiers de d&eacutepistage du diab&egravete",
												"bodyTitle"=>"Liste des dossiers de d&eacutepistage du diab&egravete",
												"body"=>"view/common/listbydossier.php")),

		"view/diabete/depistage/managealertedepistagediabete.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Alertes du d&eacutepistage du diab&egravete",
												"bodyTitle"=>"Alertes du d&eacutepistage du diab&egravete",
												"body"=>"view/diabete/depistage/managealertedepistagediabete.php")),

		"view/diabete/depistage/listdepistagediabete.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Alertes du d&eacutepistage du diab&egravete",
												"bodyTitle"=>"Alertes du d&eacutepistage du diab&egravete",
												"body"=>"view/diabete/depistage/listdepistagediabetealerte.php")),

		//--------------------------------------------------------------------------------------------
		
			// DEPISTAGE DU Touaregs
		"view/touaregs/depistage/managedepistagetouaregs.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Questionnaire de d&eacutepistage du diab&egravete",
												"bodyTitle"=>"Questionnaire de d&eacutepistage du diab&egravete",
												"body"=>"view/touaregs/depistage/managedepistagetouaregs.php")),
												
		"view/touaregs/depistage/newdepistagetouaregs.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche de d&eacutepistage du diab&egravete",
												"bodyTitle"=>"Fiche de d&eacutepistage du diab&egravete",
												"body"=>"view/touaregs/depistage/newdepistagetouaregs.php")),
												
		"view/touaregs/depistage/viewdepistagetouaregs.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche de d&eacutepistage du diab&egravete",
												"bodyTitle"=>"Fiche de d&eacutepistage du diab&egravete",
												"body"=>"view/touaregs/depistage/viewdepistagetouaregs.php")),
		
		"view/touaregs/depistage/viewdepistagediabieteaftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Questionnaire de d&eacutepistage du diab&egravete",
												"bodyTitle"=>"Questionnaire de d&eacutepistage du diab&egravete",
												"body"=>"view/touaregs/depistage/viewdepistagetouaregsaftercreate.php")),

		"listtouaregs"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des dossiers de d&eacutepistage du diab&egravete",
												"bodyTitle"=>"Liste des dossiers de d&eacutepistage du diab&egravete",
												"body"=>"view/common/list.php")),

		"listtouaregsbydossier"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des dossiers de d&eacutepistage du diab&egravete",
												"bodyTitle"=>"Liste des dossiers de d&eacutepistage du diab&egravete",
												"body"=>"view/common/listbydossier.php")),

		"view/touaregs/depistage/managealertedepistagetouaregs.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Alertes du d&eacutepistage du diab&egravete",
												"bodyTitle"=>"Alertes du d&eacutepistage du diab&egravete",
												"body"=>"view/touaregs/depistage/managealertedepistagetouaregs.php")),

		"view/touaregs/depistage/listdepistagetouaregs.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Alertes du d&eacutepistage du diab&egravete",
												"bodyTitle"=>"Alertes du d&eacutepistage du diab&egravete",
												"body"=>"view/touaregs/depistage/listdepistagetouaregsalerte.php")),

		//-------------------------------------------------------------------------------------------
		// Dossier
		"view/dossier/managedossier.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Cr&eacuteation/Maintenance de dossier",
												"bodyTitle"=>"Cr&eacuteation/Maintenance de dossier",
												"body"=>"view/dossier/managedossier.php")),

		"view/dossier/managelistexamdossier.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Visualisation des &eacutev&eacutenements d'un dossier",
												"bodyTitle"=>"Visualisation des &eacutev&eacutenements d'un dossier",
												"body"=>"view/dossier/managelistexamdossier.php")),
												
		"view/dossier/viewlistexamdossier.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Visualisation des &eacutev&eacutenements d'un dossier",
												"bodyTitle"=>"Visualisation des &eacutev&eacutenements d'un dossier",
												"body"=>"view/dossier/viewlistexamdossier.php")),


		"view/dossier/newdossier.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Cr&eacuteation d'un nouveau dossier",
												"bodyTitle"=>"Cr&eacuteation d'un nouveau dossier",
												"body"=>"view/dossier/newdossier.php")),
												
		"view/dossier/viewdossieraftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Cr&eacuteation d'un nouveau dossier",
												"bodyTitle"=>"Cr&eacuteation d'un nouveau dossier",
												"body"=>"view/dossier/viewdossieraftercreate.php")),

		"view/dossier/updatedossier.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Maintenance de dossier",
												"bodyTitle"=>"Maintenance de dossier",
												"body"=>"view/dossier/updatedossier.php")),
												
		"view/dossier/viewdossierafterupdate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Maintenance de dossier",
												"bodyTitle"=>"Maintenance de dossier",
												"body"=>"view/dossier/viewdossieraftercreate.php")),

		"view/dossier/listdossiers.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des dossiers",
												"bodyTitle"=>"Liste des dossiers",
												"body"=>"view/dossier/listdossiers.php")),
		"view/dossier/confirmupdate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Confirmation de modification",
												"bodyTitle"=>"Confirmation de modification",
												"body"=>"view/dossier/confirmupdate.php")),
												
		"managedossiersta"=>
		new Template("view/template/closebuttontemplate.php",array( "pageTitle"=>"Cr&eacuteation/Maintenance de dossier",
												"bodyTitle"=>"Cr&eacuteation/Maintenance de dossier",
												"body"=>"view/dossier/managedossier.php")),

		"newdossiersta"=>
		new Template("view/template/closebuttontemplate.php",array( "pageTitle"=>"Cr&eacuteation d'un nouveau dossier",
												"bodyTitle"=>"Cr&eacuteation d'un nouveau dossier",
												"body"=>"view/dossier/newdossier.php")),														
												
		"afterlistdossiersta"=>
		new Template("view/template/closebuttontemplate.php",array( "pageTitle"=>"Liste des dossiers",
												"bodyTitle"=>"Liste des dossiers",
												"body"=>"view/dossier/listdossiers.php")),
		"confirmdossiersta"=>
		new Template("view/template/closebuttontemplate.php",array( "pageTitle"=>"Confirmation de modification",
												"bodyTitle"=>"Confirmation de modification",
												"body"=>"view/dossier/confirmupdate.php")),

																																		
		//--------------------------------------------------------------------------------------------				
		// Evaluation Patient
		
		"manageevaluationpatient"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Evaluation par le patient",
												"bodyTitle"=>"Evaluation par le patient",
												"body"=>"view/evaluation/manageevaluation.php")),

		"view/evaluation/newevaluationpatient.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche d'&eacutevaluation par le patient",
												"bodyTitle"=>"Fiche d'&eacutevaluation par le patient",
												"body"=>"view/evaluation/newevaluationpatient.php")),
												
		"view/evaluation/viewevaluationpatient.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche d'&eacutevaluation par le patient",
												"bodyTitle"=>"Fiche d'&eacutevaluation par le patient",
												"body"=>"view/evaluation/viewevaluationpatient.php")),
												
		"listevaluationpatient"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des &eacutevaluations par le patient",
												"bodyTitle"=>"Liste des &eacutevaluations par le patient",
												"body"=>"view/common/list.php")),												
		
				
		//--------------------------------------------------------------------------------------------	
		// Evaluation Infirmi&egravere

		"manageevaluationinfirmier"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Evaluation par l'Infirmi&egravere",
												"bodyTitle"=>"Evaluation par l'Infirmi&egravere",
												"body"=>"view/evaluation/manageevaluation.php")),

		"view/evaluation/newevaluationinfirmier.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche d'&eacutevaluation par l'Infirmi&egravere",
												"bodyTitle"=>"Fiche d'&eacutevaluation par l'Infirmi&egravere",
												"body"=>"view/evaluation/newevaluationinfirmier.php")),
												
		"view/evaluation/viewevaluationinfirmieraftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche d'&eacutevaluation par l'Infirmi&egravere",
												"bodyTitle"=>"Fiche d'&eacutevaluation par l'Infirmi&egravere",
												"body"=>"view/evaluation/viewevaluationinfirmieraftercreate.php")),
												
		"view/evaluation/viewevaluationinfirmier.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche d'&eacutevaluation par l'Infirmi&egravere",
												"bodyTitle"=>"Fiche d'&eacutevaluation par l'Infirmi&egravere",
												"body"=>"view/evaluation/viewevaluationinfirmier.php")),
												
		"listevaluationinfirmier"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des derni&egraveres &eacutevaluations",
												"bodyTitle"=>"Liste des derni&egraveres &eactuealuations",
												//"body"=>"view/common/list.php"
												"body"=>"view/evaluation/listevaluationinfirmier.php")),
		
		"listevaluationinfirmierbydossier"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des &eacutevaluations par l'Infirmi&egravere",
												"bodyTitle"=>"Liste des &eacutevaluations par l'Infirmi&egravere",
												"body"=>"view/evaluation/listevaluationinfirmierbydossier.php")),

		//--------------------------------------------------------------------------------------------
		// Evaluation Medecin
		
		"manageevaluationmedecin"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Evaluation par le medecin",
												"bodyTitle"=>"Evaluation par le medecin",
												"body"=>"view/evaluation/manageevaluationmedecin.php")),

		"view/evaluation/newevaluationmedecin.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche d'&eacutevaluation par le medecin",
												"bodyTitle"=>"Fiche d'&eacutevaluation par le medecin",
												"body"=>"view/evaluation/newevaluationmdecin.php")),
												
		"view/evaluation/viewevaluationmedecin.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Fiche d'&eacutevaluation par le medecin",
												"bodyTitle"=>"Fiche d'&eacutevaluation par le medecin",
												"body"=>"view/evaluation/viewevaluationmedecin.php")),
												
		"listevaluationmedecin"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des &eacutevaluations par le medecin",
												"bodyTitle"=>"Liste des &eacutevaluations par le medecin",
												"body"=>"view/common/list.php")),
		

		//--------------------------------------------------------------------------------------------
		// Suivi Diabete

		"view/diabete/suivi/managesuividiabeteincomplet.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Donn&eacutes compl&egravetes ou incompl&egravetes dans un suivi diab&egravete",
												"bodyTitle"=>"Donn&eacutes compl&egravetes ou incompl&egravetes dans un suivi diab&egravete",
												"body"=>"view/diabete/suivi/managesuividiabeteincomplet.php")),

		"view/diabete/suivi/listsuividiabeteincompletsystematique.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Donn&eacutes incompl&egravetes dans un suivi diab&egravete",
												"bodyTitle"=>"Donn&eacutes incompl&egravetes dans un suivi diab&egravete",
												"body"=>"view/diabete/suivi/listsuividiabeteincompletsystematique.php")),

		"view/diabete/suivi/listsuividiabete4moisincomplet.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Donn&eacutes incompl&egravetes dans un suivi diab&egravete",
												"bodyTitle"=>"Donn&eacutes incompl&egravetes dans un suivi diab&egravete",
												"body"=>"view/diabete/suivi/listsuividiabete4moisincomplet.php")),

		"view/diabete/suivi/listsuividiabeteincompletsemestriel.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Donn&eacutes incompl&egravetes dans un suivi diab&egravete",
												"bodyTitle"=>"Donn&eacutes incompl&egravetes dans un suivi diab&egravete",
												"body"=>"view/diabete/suivi/listsuividiabeteincompletsemestriel.php")),

		"view/diabete/suivi/listsuividiabeteincompletannuel.php"=>
		new Template("view/template/templatetableaularge.php",array( "pageTitle"=>"Donn&eacutes incompl&egravetes dans un suivi diab&egravete",
												"bodyTitle"=>"Donn&eacutes incompl&egravetes dans un suivi diab&egravete",
												"body"=>"view/diabete/suivi/listsuividiabeteincompletannuel.php")),

		"view/diabete/suivi/managesuividiabete.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation de suivi de diab&egravete",
												"bodyTitle"=>"Consultation de suivi de diab&egravete",
												"body"=>"view/diabete/suivi/managesuividiabete.php")),

		"view/diabete/suivi/listpatientsuividiabete.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation de suivi de diab&egravete",
												"bodyTitle"=>"Consultation de suivi de diab&egravete",
												"body"=>"view/diabete/suivi/listpatientsuividiabete.php")),

		"view/diabete/suivi/listsuividiabetebycabinet.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation de suivi de diab&egravete",
												"bodyTitle"=>"Consultation de suivi de diab&egravete",
												"body"=>"view/diabete/suivi/listsuividiabetebycabinet.php")),

		"view/diabete/suivi/viewsuividiabetesystematique.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation de suivi de diab&egravete",
												"bodyTitle"=>"Consultation de suivi de diab&egravete",
												"body"=>"view/diabete/suivi/viewsuividiabetesystematique.php")),

		"view/diabete/suivi/viewsuividiabete4mois.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation de suivi de diab&egravete",
												"bodyTitle"=>"Consultation de suivi de diab&egravete",
												"body"=>"view/diabete/suivi/viewsuividiabete4mois.php")),

		"view/diabete/suivi/viewsuividiabetesemestriel.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation de suivi de diab&egravete",
												"bodyTitle"=>"Consultation de suivi de diab&egravete",
												"body"=>"view/diabete/suivi/viewsuividiabetesemestriel.php")),

		"view/diabete/suivi/viewsuividiabeteannuel.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation de suivi de diab&egravete",
												"bodyTitle"=>"Consultation de suivi de diab&egravete",
												"body"=>"view/diabete/suivi/viewsuividiabeteannuel.php")),

		"view/diabete/suivi/managesuividiabeteprecreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Cr&eacuteation de suivi de diab&egravete",
												"bodyTitle"=>"Cr&eacuteation de suivi de diab&egravete",
												"body"=>"view/diabete/suivi/managesuividiabeteprecreate.php")),


		"view/diabete/suivi/managesuividiabetecreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Cr&eacuteation de suivi de diab&egravete",
												"bodyTitle"=>"Cr&eacuteation de suivi de diab&egravete",
												"body"=>"view/diabete/suivi/managesuividiabetecreate.php")),

		"view/diabete/suivi/newsuividiabete.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Cr&eacuteation de suivi de diab&egravete",
												"bodyTitle"=>"Cr&eacuteation de suivi de diab&egravete",
												"body"=>"view/diabete/suivi/newsuividiabete.php")),

		"view/diabete/suivi/managealertesuividiabete.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Alertes du suivi de diab&egravete",
												"bodyTitle"=>"Alertes du suivi de diab&egravete",
												"body"=>"view/diabete/suivi/managealertesuividiabete.php")),

		"view/diabete/suivi/listsuividiabetealerte.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Alertes du suivi de diab&egravete",
												"bodyTitle"=>"Alertes du suivi de diab&egravete",
												"body"=>"view/diabete/suivi/listsuividiabetealerte.php")),

		"view/diabete/suivi/viewsuividiabeteaftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Cr&eacuteation de suivi de diab&egravete",
												"bodyTitle"=>"Cr&eacuteation de suivi de diab&egravete",
												"body"=>"view/diabete/suivi/viewsuividiabeteaftercreate.php")),

		"view/diabete/consult/manageconsultdiabete.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation de suivi infirmi&egravere",
												"bodyTitle"=>"Consultation de suivi infirmi&egravere",
												"body"=>"view/diabete/consult/manageconsultdiabete.php")),

		"view/diabete/consult/newconsultdiabete.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation de suivi infirmi&egravere",
												"bodyTitle"=>"Consultation de suivi infirmi&egravere",
												"body"=>"view/diabete/consult/newconsultdiabete.php")),

		"view/diabete/consult/viewconsultdiabeteaftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation de suivi infirmi&egravere",
												"bodyTitle"=>"Consultation de suivi infirmi&egravere",
												"body"=>"view/diabete/consult/viewconsultdiabeteaftercreate.php")),

		//--------------------------------------------------------------------------------------------
		
			// Suivi Touaregs

		"view/touaregs/suivi/managesuivitouaregsincomplet.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Donn&eacutes compl&egravetes ou incompl&egravetes dans un suivi diab&egravete",
												"bodyTitle"=>"Donn&eacutes compl&egravetes ou incompl&egravetes dans un suivi diab&egravete",
												"body"=>"view/touaregs/suivi/managesuivitouaregsincomplet.php")),

		"view/touaregs/suivi/listsuivitouaregsincompletsystematique.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Donn&eacutes incompl&egravetes dans un suivi diab&egravete",
												"bodyTitle"=>"Donn&eacutes incompl&egravetes dans un suivi diab&egravete",
												"body"=>"view/touaregs/suivi/listsuivitouaregsincompletsystematique.php")),

		"view/touaregs/suivi/listsuivitouaregs4moisincomplet.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Donn&eacutes incompl&egravetes dans un suivi diab&egravete",
												"bodyTitle"=>"Donn&eacutes incompl&egravetes dans un suivi diab&egravete",
												"body"=>"view/touaregs/suivi/listsuivitouaregs4moisincomplet.php")),

		"view/touaregs/suivi/listsuivitouaregsincompletsemestriel.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Donn&eacutes incompl&egravetes dans un suivi diab&egravete",
												"bodyTitle"=>"Donn&eacutes incompl&egravetes dans un suivi diab&egravete",
												"body"=>"view/touaregs/suivi/listsuivitouaregsincompletsemestriel.php")),

		"view/touaregs/suivi/listsuivitouaregsincompletannuel.php"=>
		new Template("view/template/templatetableaularge.php",array( "pageTitle"=>"Donn&eacutes incompl&egravetes dans un suivi diab&egravete",
												"bodyTitle"=>"Donn&eacutes incompl&egravetes dans un suivi diab&egravete",
												"body"=>"view/touaregs/suivi/listsuivitouaregsincompletannuel.php")),

		"view/touaregs/suivi/managesuivitouaregs.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation de suivi de diab&egravete",
												"bodyTitle"=>"Consultation de suivi de diab&egravete",
												"body"=>"view/touaregs/suivi/managesuivitouaregs.php")),

		"view/touaregs/suivi/listpatientsuivitouaregs.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation de suivi de diab&egravete",
												"bodyTitle"=>"Consultation de suivi de diab&egravete",
												"body"=>"view/touaregs/suivi/listpatientsuivitouaregs.php")),

		"view/touaregs/suivi/listsuivitouaregsbycabinet.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation de suivi de diab&egravete",
												"bodyTitle"=>"Consultation de suivi de diab&egravete",
												"body"=>"view/touaregs/suivi/listsuivitouaregsbycabinet.php")),

		"view/touaregs/suivi/viewsuivitouaregssystematique.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation de suivi de diab&egravete",
												"bodyTitle"=>"Consultation de suivi de diab&egravete",
												"body"=>"view/touaregs/suivi/viewsuivitouaregssystematique.php")),

		"view/touaregs/suivi/viewsuivitouaregs4mois.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation de suivi de diab&egravete",
												"bodyTitle"=>"Consultation de suivi de diab&egravete",
												"body"=>"view/touaregs/suivi/viewsuivitouaregs4mois.php")),

		"view/touaregs/suivi/viewsuivitouaregssemestriel.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation de suivi de diab&egravete",
												"bodyTitle"=>"Consultation de suivi de diab&egravete",
												"body"=>"view/touaregs/suivi/viewsuivitouaregssemestriel.php")),

		"view/touaregs/suivi/viewsuivitouaregsannuel.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation de suivi de diab&egravete",
												"bodyTitle"=>"Consultation de suivi de diab&egravete",
												"body"=>"view/touaregs/suivi/viewsuivitouaregsannuel.php")),

		"view/touaregs/suivi/managesuivitouaregsprecreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Cr&eacuteation de suivi de diab&egravete",
												"bodyTitle"=>"Cr&eacuteation de suivi de diab&egravete",
												"body"=>"view/touaregs/suivi/managesuivitouaregsprecreate.php")),


		"view/touaregs/suivi/managesuivitouaregscreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Cr&eacuteation de suivi de diab&egravete",
												"bodyTitle"=>"Cr&eacuteation de suivi de diab&egravete",
												"body"=>"view/touaregs/suivi/managesuivitouaregscreate.php")),

		"view/touaregs/suivi/newsuivitouaregs.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Cr&eacuteation de suivi de diab&egravete",
												"bodyTitle"=>"Cr&eacuteation de suivi de diab&egravete",
												"body"=>"view/touaregs/suivi/newsuivitouaregs.php")),

		"view/touaregs/suivi/managealertesuivitouaregs.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Alertes du suivi de diab&egravete",
												"bodyTitle"=>"Alertes du suivi de diab&egravete",
												"body"=>"view/touaregs/suivi/managealertesuivitouaregs.php")),

		"view/touaregs/suivi/listsuivitouaregsalerte.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Alertes du suivi de diab&egravete",
												"bodyTitle"=>"Alertes du suivi de diab&egravete",
												"body"=>"view/touaregs/suivi/listsuivitouaregsalerte.php")),

		"view/touaregs/suivi/viewsuivitouaregsaftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Cr&eacuteation de suivi de diab&egravete",
												"bodyTitle"=>"Cr&eacuteation de suivi de diab&egravete",
												"body"=>"view/touaregs/suivi/viewsuivitouaregsaftercreate.php")),

		"view/touaregs/consult/manageconsulttouaregs.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation infirmi&egravere de suivi Diab&egravetique de type 2",
												"bodyTitle"=>"Consultation infirmi&egravere de suivi Diab&egravetique de type 2",
												"body"=>"view/touaregs/consult/manageconsulttouaregs.php")),

		"view/touaregs/consult/newconsulttouaregs.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation infirmi&egravere de suivi Diab&egravetique de type 2",
												"bodyTitle"=>"Consultation infirmi&egravere de suivi Diab&egravetique de type 2",
												"body"=>"view/touaregs/consult/newconsulttouaregs.php")),

		"view/touaregs/consult/viewconsulttouaregsaftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation infirmi&egravere de suivi Diab&egravetique de type 2",
												"bodyTitle"=>"Consultation infirmi&egravere de suivi Diab&egravetique de type 2",
												"body"=>"view/touaregs/consult/viewconsultdiabeteaftercreate.php")),

		//--------------------------------------------------------------------------------------------
		// Troubles cognitifs


		"view/troublecognitif/managetroublecognitif.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation rep&egraverage de troubles cognitifs",
												"bodyTitle"=>"Consultation rep&egraverage de troubles cognitifs",
												"body"=>"view/troublecognitif/managetroublecognitif.php")),

		"view/troublecognitif/listtroublecognitif.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation de rep&egraverage des troubles cognitifs",
												"bodyTitle"=>"Consultation de rep&egraverage des troubles cognitifs",
												"body"=>"view/troublecognitif/listtroublecognitif.php")),

		"view/troublecognitif/listtroublecognitifbydossier.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation de rep&egraverage des troubles cognitifs",
												"bodyTitle"=>"Consultation de rep&egraverage des troubles cognitifs",
												"body"=>"view/troublecognitif/listtroublecognitifbydossier.php")),

		"view/troublecognitif/viewtroublecognitif.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation de rep&egraverage des troubles cognitifs",
												"bodyTitle"=>"Consultation de rep&egraverage des troubles cognitifs",
												"body"=>"view/troublecognitif/viewtroublecognitif.php")),

		"view/troublecognitif/managetroublecognitifprecreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Cr&eacuteation rep&egraverage de troubles cognitifs",
												"bodyTitle"=>"Cr&eacuteation rep&egraverage de troubles cognitifs",
												"body"=>"view/troublecognitif/managetroublecognitifprecreate.php")),


		"view/troublecognitif/managetroublecognitifcreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Cr&eacuteation rep&egraverage de troubles cognitifs",
												"bodyTitle"=>"Cr&eacuteation rep&egraverage de troubles cognitifs",
												"body"=>"view/troublecognitif/managetroublecognitifcreate.php")),

		"view/troublecognitif/newtroublecognitif.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Cr&eacuteation rep&egraverage de troubles cognitifs",
												"bodyTitle"=>"Cr&eacuteation rep&egraverage de troubles cognitifs",
												"body"=>"view/troublecognitif/newtroublecognitif.php")),

		"view/troublecognitif/managealertetroublecognitif.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Alertes rep&egraverage de troubles cognitifs",
												"bodyTitle"=>"Alertes rep&egraverage de troubles cognitifs",
												"body"=>"view/troublecognitif/managealertetroublecognitif.php")),

		"view/troublecognitif/listtroublecognitifalerte.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Alertes rep&egraverage de troubles cognitifs",
												"bodyTitle"=>"Alertes rep&egraverage de troubles cognitifs",
												"body"=>"view/troublecognitif/listtroublecognitifalerte.php")),

		"view/troublecognitif/viewtroublecognitifaftercreate.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Cr&eacuteation rep&egraverage de troubles cognitifs",
												"bodyTitle"=>"Cr&eacuteation rep&egraverage de troubles cognitifs",
												"body"=>"view/troublecognitif/viewtroublecognitifaftercreate.php")),

		//--------------------------------------------------------------------------------------------
		// Suivi Hebdomadaire
		
		"view/evaluation/managesuivihebdomadaire.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Suivi Hebdomadaire",
												"bodyTitle"=>"Suivi Hebdomadaire",
												"body"=>"view/evaluation/managesuivihebdomadaire.php")),

		"managesuivihebdomadaire"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Suivi Hebdomadaire",
												"bodyTitle"=>"Suivi Hebdomadaire",
												"body"=>"view/evaluation/manageevaluation.php")),

		"view/evaluation/newsuivihebdomadaire.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Suivi Hebdomadaire",
												"bodyTitle"=>"Suivi Hebdomadaire",
												"body"=>"view/evaluation/newsuivihebdomadaire.php")),	
												
		"view/evaluation/editsuivihebdomadaire.php"=>
		new Template($defaultTemplateURL, array( "pageTitle"=>"Suivi Hebdomadaire",
												"bodyTitle"=>"Suivi Hebdomadaire",
												"body"=>"view/evaluation/editsuivihebdomadaire.php")),
		"view/evaluation/viewsuivihebdomadaire.php"=>
		new Template($defaultTemplateURL, array( "pageTitle"=>"Suivi Hebdomadaire",
												"bodyTitle"=>"Suivi Hebdomadaire",
												"body"=>"view/evaluation/viewsuivihebdomadaire.php")),
																
		"listsuivihebdomadaire"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des suivis hebdomadaires",
												"bodyTitle"=>"Liste des suivis hebdomadaires",
												"body"=>"view/evaluation/listsuivihebdomadaire.php")),
												
		//--------------------------------------------------------------------------------------------
		// Suivi Hebdomadaire 2007

		"view/suivihebdomadaire2007/managesuivihebdomadaire.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Suivi Hebdomadaire 2007",
												"bodyTitle"=>"Suivi Hebdomadaire 2007",
												"body"=>"view/suivihebdomadaire2007/managesuivihebdomadaire.php")),

		"view/suivihebdomadaire2007/newsuivihebdomadaire.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Suivi Hebdomadaire 2007",
												"bodyTitle"=>"Suivi Hebdomadaire 2007",
												"body"=>"view/suivihebdomadaire2007/newsuivihebdomadaire.php")),

		"view/suivihebdomadaire2007/editsuivihebdomadaire.php"=>
		new Template($defaultTemplateURL, array( "pageTitle"=>"Suivi Hebdomadaire 2007",
												"bodyTitle"=>"Suivi Hebdomadaire 2007",
												"body"=>"view/suivihebdomadaire2007/editsuivihebdomadaire.php")),
												
		"view/suivihebdomadaire2007/viewsuivihebdomadaire.php"=>
		new Template($defaultTemplateURL, array( "pageTitle"=>"Suivi Hebdomadaire 2007",
												"bodyTitle"=>"Suivi Hebdomadaire 2007",
												"body"=>"view/suivihebdomadaire2007/viewsuivihebdomadaire.php")),

		"listsuivihebdomadaire2007"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des suivis hebdomadaires 2007",
												"bodyTitle"=>"Liste des suivis hebdomadaires 2007",
												"body"=>"view/suivihebdomadaire2007/listsuivihebdomadaire.php")),

				//--------------------------------------------------------------------------------------------
		// Suivi Hebdomadaire Temps Pass?

		"view/suivihebdomadaireTempsPasse/managesuivihebdomadaire.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Suivi Hebdomadaire Temps Pass&eacute;",
												"bodyTitle"=>"Suivi Hebdomadaire Temps Pass&eacute;",
												"body"=>"view/suivihebdomadaireTempsPasse/managesuivihebdomadaire.php")),

		"view/suivihebdomadaireTempsPasse/newsuivihebdomadaire.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Suivi Hebdomadaire Temps Pass&eacute;",
												"bodyTitle"=>"Suivi Hebdomadaire Temps Pass&eacute;",
												"body"=>"view/suivihebdomadaireTempsPasse/newsuivihebdomadaire.php")),

		"view/suivihebdomadaireTempsPasse/editsuivihebdomadaire.php"=>
		new Template($defaultTemplateURL, array( "pageTitle"=>"Suivi Hebdomadaire Temps Pass&eacute;",
												"bodyTitle"=>"Suivi Hebdomadaire Temps Pass&eacute;",
												"body"=>"view/suivihebdomadaireTempsPasse/editsuivihebdomadaire.php")),
												
		"view/suivihebdomadaireTempsPasse/viewsuivihebdomadaire.php"=>
		new Template($defaultTemplateURL, array( "pageTitle"=>"Suivi Hebdomadaire Temps Pass&eacute;",
												"bodyTitle"=>"Suivi Hebdomadaire Temps Pass&eacute;",
												"body"=>"view/suivihebdomadaireTempsPasse/viewsuivihebdomadaire.php")),

		"listsuivihebdomadaireTempsPasse"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des suivis hebdomadaires Temps Pass&eacute;",
												"bodyTitle"=>"Liste des suivis hebdomadaires Temps Pass&eacute;",
												"body"=>"view/suivihebdomadaireTempsPasse/listsuivihebdomadaire.php")),

		//--------------------------------------------------------------------------------------------
		// Suivi R?union Coordination M?decin

		"view/suiviReunionMedecin/managesuivi.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Suivi R&eacute;union Coordination M&eacute;decin",
												"bodyTitle"=>"Suivi R&eacute;union Coordination M&eacute;decin",
												"body"=>"view/suiviReunionMedecin/managesuivi.php")),

		"view/suiviReunionMedecin/newsuivi.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Suivi R&eacute;union Coordination M&eacute;decin",
												"bodyTitle"=>"Suivi R&eacute;union Coordination M&eacute;decin",
												"body"=>"view/suiviReunionMedecin/newsuivi.php")),

		"view/suiviReunionMedecin/editsuivi.php"=>
		new Template($defaultTemplateURL, array( "pageTitle"=>"Suivi R&eacute;union Coordination M&eacute;decin",
												"bodyTitle"=>"Suivi R&eacute;union Coordination M&eacute;decin",
												"body"=>"view/suiviReunionMedecin/editsuivi.php")),

												
		"view/suiviReunionMedecin/viewsuivi.php"=>
		new Template($defaultTemplateURL, array( "pageTitle"=>"Suivi R&eacute;union Coordination M&eacute;decin",
												"bodyTitle"=>"Suivi R&eacute;union Coordination M&eacute;decin",
												"body"=>"view/suiviReunionMedecin/viewsuivi.php")),

		"listsuiviReunionMedecin"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des suivis R&eacute;union Coordination M&eacute;decin",
												"bodyTitle"=>"Liste des suivis R&eacute;union Coordination M&eacute;decin",
												"body"=>"view/suiviReunionMedecin/listsuivi.php")),

		//--------------------------------------------------------------------------------------------
		// Login
		
		"view/login/login.php"=>
		new Template("view/template/logintemplate.php",array( "pageTitle"=>"Login",
												"bodyTitle"=>"Login",
												"body"=>"view/login/login.php")),		
												
		//--------------------------------------------------------------------------------------------
		// Consultation des suivis
		
		"view/utilities/consultation.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Consultation de r&eacuteponses au questionnaire asale&egrave",
												"bodyTitle"=>"Consultation de r&eacuteponses au questionnaire asale&egrave",
												"body"=>"view/utilities/consultation.php")),																						
												
		//--------------------------------------------------------------------------------------------
		// Graphs
		
		"view/diabete/graph/drawgraph1.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"HBA1 GRAPH",
												"bodyTitle"=>"HBA1 GRAPH",
												"body"=>"view/diabete/graph/drawgraph1.php")),	
												
		"view/diabete/graph/managegraph1.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"HBA1 GRAPH",
												"bodyTitle"=>"HBA1 GRAPH",
												"body"=>"view/diabete/graph/managegraph1.php")),													
												
		"view/diabete/graph/drawgraph2.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"HBA2 GRAPH",
												"bodyTitle"=>"HBA2 GRAPH",
												"body"=>"view/diabete/graph/drawgraph2.php")),				
														
		"view/diabete/graph/managegraph2.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"HBA2 GRAPH",
												"bodyTitle"=>"HBA2 GRAPH",
												"body"=>"view/diabete/graph/managegraph2.php")),
												
		//--------------------------------------------------------------------------------------------
		// Main
		
		"view/main.php"=>
		new Template("view/template/loginoktemplate.php",array( "pageTitle"=>"| PSA | Bienvenue",
												"bodyTitle"=>"",
												"body"=>"view/main.php")),			
												
													
		//--------------------------------------------------------------------------------------------
		// Tension Arterielle											
							
		// questionnaire d'automesure art&eacuterielle
		"view/tensionarterielle/managetensionarterielle.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Questionnaire d'automesure tensionnelle",
												"bodyTitle"=>"Questionnaire d'automesure tensionnelle",
												"body"=>"view/tensionarterielle/managetensionarterielle.php")),
							


		"view/tensionarterielle/newtensionarterielle.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Tension artérielle",
												"bodyTitle"=>"Tension artérielle",
												"body"=>"view/tensionarterielle/newtensionarterielle.php")),

		"view/tensionarterielle/tensionarteriellemoyenne.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Tension artérielle moyenne",
												"bodyTitle"=>"Tension artérielle moyenne",
												"body"=>"view/tensionarterielle/tensionarteriellemoyenne.php")),

		"view/tensionarterielle/listtensionarterielle.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Liste des TA antérieurs",
												"bodyTitle"=>"Tension artérielle moyenne",
												"body"=>"view/tensionarterielle/listtensionarterielle.php")),

		"view/tensionarterielle/viewtensionarterielle.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Mesures de tension artérielle",
												"bodyTitle"=>"Mesures de tension artérielle",
												"body"=>"view/tensionarterielle/viewtensionarterielle.php")),
		
		"view/tensionarterielle/listtensionarteriellebycabinet.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Tension artérielle",
												"bodyTitle"=>"Liste des patients",
												"body"=>"view/tensionarterielle/listtensionarteriellebycaninet.php")),

		//------------------------
		// SEVRAGE TABAC - ajout oct2016
		"view/sevragetabac/manageSevragetabac.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Sevrage Tabagique",
												"bodyTitle"=>"Sevrage Tabagique",
												"body"=>"view/sevragetabac/manageSevragetabac.php")),

		"view/sevragetabac/newSevragetabac.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Sevrage Tabagique",
												"bodyTitle"=>"Sevrage Tabagique",
												"body"=>"view/sevragetabac/newSevragetabac.php")),

		"view/sevragetabac/viewSevragetabac.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Sevrage Tabagique",
												"bodyTitle"=>"Sevrage Tabagique",
												"body"=>"view/sevragetabac/viewSevragetabac.php")),

		"view/sevragetabac/documents_support_sevragetabac.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Sevrage Tabagique",
												"bodyTitle"=>"Sevrage Tabagique",
												"body"=>"view/sevragetabac/documents_support_sevragetabac.php")),

		"view/sevragetabac/list_sevragetabac.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Sevrage Tabagique",
												"bodyTitle"=>"Liste des Sevrage Tabagique",
												"body"=>"view/sevragetabac/list_sevragetabac.php")),

        "view/sevragetabac/list_dossier_sevragetabac.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Sevrage Tabagique",
												"bodyTitle"=>"Liste des Sevrage Tabagique",
												"body"=>"view/sevragetabac/list_dossier_sevragetabac.php")),


		// gestion des groupes pour consultations collectives
		"view/dossier/manage_groupes.php"=>
		new Template($defaultTemplateURL,array( "pageTitle"=>"Gestion des groupes",
												"bodyTitle"=>"Gestion des groupes",
												"body"=>"view/dossier/manager_groupes.php")),





        //------------------------------------------------------------
        // FRAGILITE
        "fragiliteformulaire" =>
            new Template($defaultTemplateURL, array("pageTitle" => "Fragilite",
                    "bodyTitle" => "Patient Fragile",
                    "body"      => "view/fragilite/formulaire_fragilite.php"
                )
            ),

        "managefragilite" =>
            new Template($defaultTemplateURL, array(    "pageTitle" => "Fragilit&egrave",
                    "bodyTitle" => "Patient Fragile",
                    "body"      => "view/fragilite/managefragilite.php"
                )
            ),

        "listefragilite" =>
            new Template($defaultTemplateURL, array(
                    "pageTitle" => "Fragilit&egrave",
                    "bodyTitle" => "Patient Fragile",
                    "body"      => "view/fragilite/liste_fragilite.php"
                )
            ),



        //------------------------------------------------------------
        // Entretien Annuel
        "entretienAnnuelformulaire" =>
            new Template($defaultTemplateURL, array("pageTitle" => "Entretien Annuel",
                    "bodyTitle" => "Entretien Annuel",
                    "body"      => "view/entretienAnnuel/formulaire_entretien_annuel.php"
                )
            ),

        "manageentretienAnnuel" =>
            new Template($defaultTemplateURL, array(    "pageTitle" => "Entretien Annuel",
                    "bodyTitle" => "Entretien Annuel",
                    "body"      => "view/entretienAnnuel/manageentretienAnnuel.php"
                )
            ),

        "listeentretienAnnuel" =>
            new Template($defaultTemplateURL, array(
                    "pageTitle" => "Entretien Annuel",
                    "bodyTitle" => "Entretien Annuel",
                    "body"      => "view/entretienAnnuel/liste_entretien_annuel.php"
                )
            ),




        //------------------------------------------------------------
        // DEPISTAGE AOMI
        "managedepistageaomi" =>
            new Template($defaultTemplateURL, array(
                    "pageTitle" => "D&egravepistage AOMI",
                    "bodyTitle" => "D&egravepistage de l'AOMI",
                    "body"      => "view/depistage/managedepistageaomi.php"
                )
            ),

        "newdepistageaomi" =>
            new Template($defaultTemplateURL, array(
                    "pageTitle" => "D&egravepistage AOMI",
                    "bodyTitle" => "D&egravepistage de l'AOMI",
                    "body"      => "view/depistage/newdepistageaomi.php"
                )
            ),


        //------------------------------------------------------------
        // ACTIVITE PHYSIQUE
        "activitephysiqueformulaire" =>
            new Template($defaultTemplateURL, array("pageTitle" => "Activit&eacutePhysique",
                    "bodyTitle" => "Activit&egrave Physique",
                    "body"      => "view/activite_physique/formulaire_activitePhysique.php"
                )
            ),

        "manageactivitephysique" =>
            new Template($defaultTemplateURL, array(    "pageTitle" => "Activit&eacute Physique",
                    "bodyTitle" => "Activit&eacute Physique",
                    "body"      => "view/activite_physique/manage_activitePhysique.php"
                )
            ),

        "listeactivitephysique" =>
            new Template($defaultTemplateURL, array(
                    "pageTitle" => "Activit&eacute Physique",
                    "bodyTitle" => "Activit&eacute Physique",
                    "body"      => "view/activite_physique/liste_activitePhysique.php"
                )
			),
			
		//------------------------------------------------------------
        // TRANSFERT DOSSIER
        "view/dossier/transfert_dossier/viewtransfertdossier.php" =>
            new Template($defaultTemplateURL, array(
                    "pageTitle" => "Transfert de dossier",
                    "bodyTitle" => "Transfert de dossier",
                    "body"      => "view/dossier/transfert_dossier/viewtransfertdossier.php"
                )
            ),


		//------------------------------------------------------------
		 // TRANSFERT DOSSIER R�ussi
		 "view/dossier/transfert_dossier/transfertsuccess.php" =>
		 new Template($defaultTemplateURL, array(
				 "pageTitle" => "Transfert r�ussi",
				 "bodyTitle" => "Transfert de dossier r�ussi",
				 "body"      => "view/dossier/transfert_dossier/transfertsuccess.php"
			 )
		 ),


	 //------------------------------------------------------------
        // UTILISATEUR
        "utilisateurliste" =>
			new Template($defaultTemplateURL, array(
                    							"pageTitle" => "Utilisateur",
                    							"bodyTitle" => "Liste des utilisateurs",
                    							"body"      => "view/utilisateur/liste_utilisateur.php"
											  )
            ),

        "nouvelutilisateur" =>
            new Template($defaultTemplateURL, array(
                    							"pageTitle" => "Utilisateur",
                    							"bodyTitle" => "Cr&eacuteation d'un utilisateur",
                    							"body"      => "view/utilisateur/new_utilisateur.php"
                							  )
            ),



        //------------------------------------------------------------
        // CABINET
        "listecabinet" =>
            new Template($defaultTemplateURL, array(
                    							"pageTitle" => "Cabinet",
                    							"bodyTitle" => "Liste des cabinets",
                    							"body"      => "view/cabinet/liste_cabinet.php"
                							  )
            ),

		"nouveaucabinet" =>
            new Template($defaultTemplateURL, array(
                    							"pageTitle" => "Cabinet",
                    							"bodyTitle" => "Cr&eacuteation d'un cabinet",
                    							"body"      => "view/cabinet/new_cabinet.php"
                							  )
            )
	);
							
	
	function getTemplate($key){
		global $templateMap;
		if(!array_key_exists($key,$templateMap)) return false;
		return $templateMap[$key];
	}
?>

