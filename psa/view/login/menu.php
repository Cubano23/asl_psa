<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">
body{
	font-family: 'Verdana';
	font-size: 13px;
}
#newmenu{
	width: 901px;
/* margin: 0 auto; */
	cursor: pointer;
	height: 41px;
	/*margin-left:8px;*/
}
a{
	text-decoration: none;
	color: #EC9C0E;
	display: block;
}
a:hover{
	text-decoration: none;
}

#newmenu ul li{
	float: left;
	list-style: none;
	color: #FFFFFF;
	background: #6e0037; /* For browsers that do not support gradients */
	background: -webkit-linear-gradient(#6e0037, #920049); /* For Safari 5.1 to 6.0 */
    background: -o-linear-gradient(#6e0037, #920049); /* For Opera 11.1 to 12.0 */
    background: -moz-linear-gradient(#6e0037, #920049); /* For Firefox 3.6 to 15 */
    background: linear-gradient(#6e0037, #920049); /* Standard syntax */
    border-left: solid 10px #660033;
    font-family: 'Open Sans';
    font-weight: 300;
    font-size: 14px;
    text-align: left;
    height: 41px;
    line-height: 16px;
    padding: 8px 0 0 10px;
    z-index: 10;
	position: relative;
	display: inline-block;
	width: 14%;
}
#newmenu ul li:hover {
	background: #ffcb12; /* For browsers that do not support gradients */
	background: -webkit-linear-gradient(#ffcb12, #ec9c0e); /* For Safari 5.1 to 6.0 */
    background: -o-linear-gradient(#ffcb12, #ec9c0e); /* For Opera 11.1 to 12.0 */
    background: -moz-linear-gradient(#ffcb12, #ec9c0e); /* For Firefox 3.6 to 15 */
    background: linear-gradient(#ffcb12, #ec9c0e); /* Standard syntax */
    border-left: solid 10px #ec9c0e;
}

#newmenu ul li i.fa {
	float: right;
	padding: 10px 7px 0 0;
	font-size: 20px;
	color: #f9e917;
	margin-left: 5px;
}
#newmenu ul li:hover i.fa {
	color: #660033;
}
#newmenu ul li.patients {
	width: 12.5%;
} 
#newmenu ul li.diabete {
	width: 10.4%;
} 
#newmenu ul li.rcva {
	width: 8.5%;
} 
#newmenu ul li.sevrage {
	width: 13%;
} 
#newmenu ul li.cancer {
	width: 12.5%;
} 
#newmenu ul li.cognitifs {
	width: 12.5%;
} 
#newmenu ul li.evaluation {
	width: 15%;
} 




#newmenu ul ul.nav_1, #newmenu ul ul.nav_2, #newmenu ul ul.nav_3{
	position: relative;
	top: 9px;
	left: -20px;
}
#newmenu ul ul.nav_1 li{
	position: relative;
	height: 15px;
	width:300px;
}
#newmenu ul ul.nav_2 li{
	position: relative;
	height: 15px;
	width:400px;
	float: right;
}

#newmenu ul ul.nav_2:hover{
	display:block;
	text-align: right;
}
#newmenu ul ul.nav_3 li{
	position: relative;
	height: 15px;
	width:350px;
}

#newmenu ul ul li{
	float:none;
	color: #EC9C0E;
	text-decoration: none;
	background: #FFE887;
	padding: 5px;
	border-bottom: 1px solid #EC9C0E;
	text-align: center;
}
#newmenu ul ul li:last-child{
	border-bottom: 0;
}
#newmenu ul ul li:hover{
	background: #fede58;
}
#newmenu ul ul{
	display:none;
    position: absolute;
    padding-left:0;
}
#newmenu ul ul.nav_1 ul.nav_11{
    position: relative;
    right:-305px;
    top: -22px;
    border: 1px solid #EC9C0E;
}
#newmenu ul ul.nav_3 ul.nav_12{
    position: absolute;
    right:-212px;
    top: 0px;
    border: 1px solid #EC9C0E;
}
#newmenu ul ul.nav_3 ul.nav_12 li{
    width: 200px;
}
#newmenu ul ul.nav_2 ul.nav_13{
    position: relative;
    left:-404px;
    top: -22px;
    border: 1px solid #EC9C0E;
}
#newmenu ul ul.nav_2 ul.nav_14{
    position: relative;
    left:-404px;
    top: -22px;
    border: 1px solid #EC9C0E;
}
#newmenu ul ul.nav_2 ul.nav_14 li{
    width: 520px;
}
#newmenu ul li:hover > ul{
	display:block;
}
.arrow-left{
	float: right;
	line-height: 20px;
	width: 0; 
	height: 0; 
	border-bottom: 5px solid transparent;  /* left arrow slant */
	border-top: 5px solid transparent; /* right arrow slant */
	border-left: 5px solid #EC9C0E; /* bottom, add background color here */
	font-size: 0;
	margin-top:3px;
}
</style>



<div id='newmenu'>
	<div id="identity" style="background-color:#FFFFFF;">
		<p style="text-align:right;color:grey"><i class="fa fa-user fa-2x" aria-hidden="true"></i>
		<?php 
		#($account);
		echo utf8_decode($_SESSION['id.prenom']).' '.utf8_decode($_SESSION['id.nom']).' <br>Cabinet : '.$_SESSION["account"]->cabinet; ?>
		</p>
	</div>
	<ul>
		<li class='patients'>
			<i class="fa fa-male"></i>
			GESTION<br />PATIENTS 
			<ul class='nav_1'>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=DossierControler&controlerparams:param:action=AM'>Cr&eacute;ation / Maintenance de dossier</a></li>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=TransfertDossierControler&controlerparams:param:action=AM'>Transfert de dossier</a></li>
        		<li><a target='_blank' href='../view/dossier/dossier_gerer.php'>Gestion de Dossier avec NIR</a></li>
				<li>Alertes <span class='arrow-left'></span>
					<ul class='nav_11'>
						<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=SuiviDiabeteControler&controlerparams:param:action=AM&controlerparams:param:param1=POUTD'>Suivi du diab&egrave;te type2</a></li>
						<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=DepistageDiabeteControler&controlerparams:param:action=AM&controlerparams:param:param1=POUTD'>D&eacute;pistage du diab&egrave;te</a></li>
						<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=DepistageCancerColonControler&controlerparams:param:action=AM&controlerparams:param:param1=POUTD'>D&eacute;pistage colon</a></li>
						<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=HemocultControler&controlerparams:param:action=AM&controlerparams:param:param1=POUTD'>D&eacute;pistage h&eacute;moccult</a></li>
						<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=DepistageCancerSeinControler&controlerparams:param:action=AM&controlerparams:param:param1=POUTD'>D&eacute;pistage sein</a></li>
						<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=DepistageCancerUterusControler&controlerparams:param:action=AM&controlerparams:param:param1=POUTD'>D&eacute;pistage col de l'ut&eacute;rus</a></li>
						<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=TroubleCognitifControler&controlerparams:param:action=AM&controlerparams:param:param1=POUTD'>Rep&eacute;rage Troubles cognitifs</a></li>
						<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=CardioVasculaireDepartControler&controlerparams:param:action=AM&controlerparams:param:param1=POUTD'>Suivi RCVA</a></li>
					</ul>
				</li>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=DossierControler&controlerparams:param:action=AMLE'>Visualiser les &eacute;v&eacute;nements d'un dossier</a></li>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AM'>Visualiser les examens d'un dossier</a></li>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=UtilityControler&controlerparams:param:action=ACONSULT'>Visualiser les dossiers d'un protocole</a></li>
				<li><a target='_blank' href='../view/docs/protocole_mise_en_place_infirmiere.pdf'>Protocole de mise en place et mont&eacute;e en charge</a></li>
				<li><a target='_blank' href='../view/docs/protocole_cadre_HAS_partie_1_janvier_2012.pdf'>Protocole Cadre HAS Partie 1</a></li>
				<li><a target='_blank' href='../view/docs/protocole_cadre_partie_2_janvier_2012.pdf'>Protocole Cadre HAS Partie 2</a></li>

                <!-- Ajout du lien vers les utilisateurs et les cabinets -->
                <!--li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=UtilisateurControler&controlerparams:param:action=AM'>Utilisateurs</a></li>
                <li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=CabinetControler&controlerparams:param:action=AM'>Cabinets</a></li-->
			</ul>
		</li>
		<li class='diabete'>
			<i class="fa fa-search"></i>
			DIAB&Egrave;TE
			<ul class='nav_3'>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=DepistageDiabeteControler&controlerparams:param:action=AM'>Questionnaire de d&eacute;pistage du diab&egrave;te</a></li>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=SuiviDiabeteControler&controlerparams:param:action=AM&controlerparams:param:param1=PPCREAT'>Questionnaire de suivi du diab&egrave;tique de type 2</a></li>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=ConsultDiabeteControler&controlerparams:param:action=AM'>Consultation de suivi infirmi&egrave;re</a></li>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=SuiviDiabeteControler&controlerparams:param:action=AM&controlerparams:param:param1=PANY'>Liste des suivis d'un dossier</a></li>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=SuiviDiabeteControler&controlerparams:param:action=AM&controlerparams:param:param1=PINC'>Liste des suivis complets ou non</a></li>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=FondOeilControler&controlerparams:param:action=AN'>T&eacute;l&eacute;charger une image de fond d'oeil</a></li>
				<li><a target='_blank' href='../view/docs/protocole_depistage_diabete.pdf'>Protocole de d&eacute;pistage du diab&egrave;te</a></li>
				<li><a target='_blank' href='../view/docs/protocole_suivi_diabete.pdf'>Protocole de suivi du diab&egrave;te de type 2</a></li>
				<li><a target='_blank' href='../view/docs/depistage_et_suivi_patient_maladie_chronique_diab%C3%A8te_type_2_-_janvier_2012.pdf'>Protocole HAS d&eacute;pistage du diab&egrave;te type 2</a></li>
				<li>Documentation pour les patients<span class='arrow-left'></span>
					<ul class='nav_12'>
						<li><a target='_blank' href='../view/docs/cardiovasculaire/ATT02699.pdf'>Pense-b&ecirc;te nutrition</a></li>
						<li><a target='_blank' href='../view/docs/cardiovasculaire/risque_tabac.pdf'>Les effets du tabac</a></li>
						<li><a target='_blank' href='../view/docs/cardiovasculaire/ATT02697.pdf'>La d&eacute;pendance du tabac</a></li>
						<li><a target='_blank' href='../view/docs/cardiovasculaire/ATT02695.pdf'>Bouger c'est la sant&eacute;</a></li>
						<li><a target='_blank' href='../view/docs/cardiovasculaire/alcool2.pdf'>Les effets de l'alcool</a></li>
						<li><a target='_blank' href='../view/docs/cardiovasculaire/rosace_du_sucre.pdf'>La rosace du sucre</a></li>
					</ul>
				</li>
			</ul>
		</li>

		<li class='rcva'>
			<i class="fa fa-search"></i>
			RCVA
			<ul class='nav_3'>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=CardioVasculaireDepartControler&controlerparams:param:action=AM&controlerparams:param:param1=PE'>Collecte des donn&eacute;es</a></li>
				<!--<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=diageducControler&controlerparams:param:action=AM'>Diagnostic &eacute;ducatif</a></li>-->
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=AutreConsultCardioControler&controlerparams:param:action=AM'>Consultation de suivi infirmi&egrave;re</a></li>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=SuiviINRControler&controlerparams:param:action=AM'>Suivi INR</a></li>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=TensionArterielleControler&controlerparams:param:action=AM'>Automesure tensionnelle</a></li>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=CardioVasculaireDepartControler&controlerparams:param:action=AGRAPH'>Taux de compl&eacute;tude des dossiers</a></li>
				<!--<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=DepistageDiabeteControler&controlerparams:param:action=AM#'>&Eacute;volution RCVA</a></li>-->
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=ListeDonneesControler&controlerparams:param:action=AN'>Liste des donn&eacute;es disponibles</a></li>
				<li><a target='_blank' href='../view/docs/protocole_version_courte_RCVA.pdf'>Protocole RCVA version courte</a></li>
				<li><a target='_blank' href='../view/docs/protocoleRCVAV4.pdf'>Protocole RCVA version longue</a></li>
				<li><a target='_blank' href='../view/docs/protocole_automesure_tensionnelle.pdf'>Protocole Automesure Tensionnelle</a></li>
				<li><a target='_blank' href='../view/docs/reperage_et_suivi_patient_maladie_chronique_RCV_-_janvier_2012.pdf'>Protocole HAS suivi patient RCV</a></li>
				<li><a target='_blank' href='../view/docs/reperage_et_suivi_patient_tabagique_BPCO_-_janvier_2012.pdf'>Protocole HAS rep&eacute;rage BPCO patient tabagique</a></li>
			</ul>
		</li>
		
		<li class='sevrage'>
			<i class="fa fa-search"></i>
			SEVRAGE<br />TABAGIQUE
			<ul class='nav_3'>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=SevrageTabacControler&controlerparams:param:action=AM'>Consultation de suivi infirmi&egrave;re</a></li>
				<!--<li><a target='_blank' href=''>Protocole sevrage tabagique</a></li>-->
				<li><a href='../controler/ActionControler.php?controlerparams:param:controler=SevrageTabacControler&controlerparams:param:action=HD&param1=CT1'>Document support &agrave; l'&eacute;change avec le patient</a></li>
			</ul>
		</li>
		<li class='cancer'>
			<i class="fa fa-search"></i>
			D&Eacute;PISTAGE<br />CANCER
			<ul class='nav_3'>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=DepistageCancerSeinControler&controlerparams:param:action=AM'>Questionnaire cancer du sein</a></li>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=DepistageCancerColonControler&controlerparams:param:action=AM'>Questionnaire cancer du colon</a></li>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=HemocultControler&controlerparams:param:action=AM'>Questionnaire d'h&eacute;mocult</a></li>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=DepistageCancerUterusControler&controlerparams:param:action=AM'>Questionnaire cancer du col de l'ut&eacute;rus</a></li>
				<li><a target='_blank' href='../view/docs/protocole_depistage_cancerdusein.pdf'>Protocole de d&eacute;pistage du cancer du sein</a></li>
				<li><a target='_blank' href='../view/docs/protocole_depistage_cancercolon.pdf'>Protocole de d&eacute;pistage du cancer du colon</a></li>
				<li><a target='_blank' href='../view/docs/protocole_depistage_cancercol.pdf'>Protocole de d&eacute;pistage du cancer du col de l'ut&eacute;rus</a></li>
			</ul>
		</li>
		<li class='cognitifs'>
			<i class="fa fa-search"></i>
			TROUBLES<br />COGNITIFS
			<ul class='nav_3'>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=TroubleCognitifControler&controlerparams:param:action=AM&controlerparams:param:param1=PPCREAT'>Questionnaire de rep&eacute;rage des troubles cognitifs</a></li>
				<li><a target='_blank' href='../view/docs/protocole_depistage_troubles_cognitifs.pdf'>Protocoles de rep&eacute;rage des troubles cognitifs</a></li>
				<li><a target='_blank' href='../view/docs/20120312%20cinq%20mots%20dubois%20descriptio.doc'>Protocole test des 5 mots - r&eacute;f&eacute;rence</a></li>
				<li><a target='_blank' href='../view/docs/20120312%20ndsa_cinq_mots.pdf'>Protocole test des 5 mots - d&eacute;roulement</a></li>
				<li><a target='_blank' href='../view/docs/20120312greco_cinqmots_feuille.pdf'>Protocole test des 5 mots - formulaire papier</a></li>
			</ul>
		</li>
		<li class='evaluation'>
			<i class="fa fa-bar-chart"></i>
			&Eacute;VALUATION
			<ul class='nav_2'>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=EvaluationInfirmierControler&controlerparams:param:action=AM'>Evaluation de consultation infirmi&egrave;re</a></li>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=SuiviReunionMedecinControler&controlerparams:param:action=AM'>Suivi des r&eacute;unions de concertation m&eacute;decin</a></li>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=SuiviHebdomadaireTempsPasseControler&controlerparams:param:action=AL'>Suivi hebdo du temps pass&eacute;</a></li>
				<li><a target='_blank' href='../view/planning/planning.php'>Planning infirmi&egrave;res</li>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=CongesControler&controlerparams:param:action=AN'>Demande de cong&eacute;s</a></li>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=FraisControler&controlerparams:param:action=AN'>Demande de remboursement de frais</a></li>

                <?php

                 if(strtolower($_SESSION['cabinet'])=="ztest") {
                    ?>
                    <li><a target='_blank' href='../view/gestion_demande/rib/gestion_rib.php'>Gestion des demandes de rib</a></li>
                    <?php
                }
                ?>
                <?php
                if(strtolower($_SESSION['cabinet'])=="ztest") {
                    ?>
                    <li><a target='_blank' href='../view/gestion_demande/frais/gestion_frais.php'>Gestion des demandes de remboursement de frais</a></li>
                    <?php
                }
                ?>
                <?php
                if(strtolower($_SESSION['cabinet'])=="ztest") {
                    ?>
                    <li><a target='_blank' href='../view/gestion_demande/carte_grise/gestion_demande_carte_grise.php'>Gestion des d&eacute;clarations de cartes grises</a></li>
                    <?php
                }
                ?>

                <?php
                if (strtolower($_SESSION['cabinet'])=="ztest" || in_array($_SESSION['nom'], $liste_inf_activite_physique)) {
                    ?>
                    <li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=ActivitePhysiqueControler&controlerparams:param:action=AM'>Activit&eacute; Physique</a></li>
                    <?php
                }
                ?>
				<li>Questionnaire Satisfaction patient - &agrave; imprimer<span class='arrow-left'></span>
					<ul class='nav_13'>
						<li><a target='_blank' href='../view/docs/satisfaction3.pdf'>Questionnaire sur une page - petits caract&egrave;res</a></li>
						<li><a target='_blank' href='../view/docs/satisfaction_gros.pdf'>Questionnaire sur deux pages - gros caract&egrave;res</a></li>
					</ul>
				</li>
				<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=SatisfactionPatientControler&controlerparams:param:action=AN'>Questionnaire Satisfaction patient 2009 - formulaire de saisie</a></li>
				<li><a href='../view/stats/autres/satisf_patient2009.php'>Statistiques questionnaire satisfaction patient 2009</a></li>
				<li>Protocoles &agrave; l'&eacute;tude<span class='arrow-left'></span>
					<ul class='nav_14'>
						<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=DepistageDiabeteControler&controlerparams:param:action=AM#'>Aide au s&eacute;vrage tabagique</a></li>
						<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=DepistageDiabeteControler&controlerparams:param:action=AM#'>Suivi de la clairance de la cr&eacute;atine et du poids (pour tous les patients) selon r&eacute;f&eacute;rentiel Anaes</a></li>
<!--						<li>Evaluation du risque d'ost&eacute;oporose (&acirc;ge, apport calcique, ant&eacute;c&eacute;dent familiaux...)</li>-->
<!--						<li>Suivi des enfants ob&egrave;ses</li>-->
<!--						<li>Prise en charge des hyperlipid&eacute;mies</li>-->
<!--						<li>Education du patient asthmatique</li>-->
<!--						<li>D&eacute;pistage et suivi des BPCO</li>-->
<!--						<li>Evaluation nutrition et personnes &acirc;g&eacute;es type MNA</li>-->
<!--						<li>Pr&eacute;vention des chutes de la personne &acirc;g&eacute;e</li>-->
<!--						<li>Organisation du retour &agrave; domicile suite passage en &eacute;tablissement hospitalier</li>-->
					</ul>
				</li>
				<!--<li><a target='_blank' href='../view/actualite/DEMANDE_INDEMNITES.doc'>Demande d'ind&eacute;mnit&eacute;s</a></li>-->
				<!--<li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=PoserQuestionControler&controlerparams:param:action=AL'>Liste des questions au support</a></li>-->
				<li><a target='_blank' href='../view/integration/integration.php'>Int&eacute;gration automatique de donn&eacute;es</a></li>
                <li><a target='_blank' href='../view/integration/integration_logs.php'>Traces Int&eacute;grations</a></li>
                <li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=FragiliteControler&controlerparams:param:action=AM'>Patient Fragile</a></li>
                <?php
                if(strtolower($_SESSION['cabinet'])=="ztest") {
                ?>
                    <li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=EntretienAnnuelControler&controlerparams:param:action=AM'>Entretien Annuel</a></li>
                <?php
                }
                ?>
                <?php
                if (in_array($_SESSION['cabinet'], $liste_cabs_aut)) {
                ?>
                    <li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=DepistageAOMIControler&controlerparams:param:action=AM'>D�pistage de l'AOMI</a></li>
                <?php
                }
                ?>
                <?php
                if(strtolower($_SESSION['cabinet'])=="ztest" && (strtolower($_SESSION['nom'])=="arizk" || strtolower($_SESSION['nom'])=="pdargenlieu"  )  ) {

                    ?>
                    <!-- <li><a target='_blank' href='../controler/ActionControler.php?controlerparams:param:controler=FragiliteControler&controlerparams:param:action=AM'>Patient Fragile</a></li>
                    -->
                    <?php
                }
                /*
                       if(strtolower($_SESSION['cabinet'])=="ztest")
                             echo "<li><a target='_blank' href='../view/idslogs/list_logs.php'>Traces IDS</a></li>";
                  */
                ?>







                <?php
                /*
                       if(strtolower($_SESSION['cabinet'])=="ztest")
                             echo "<li><a target='_blank' href='../view/idslogs/list_logs.php'>Traces IDS</a></li>";
                  */
                ?>

            </ul>
		</li>
	</ul>

</div>

