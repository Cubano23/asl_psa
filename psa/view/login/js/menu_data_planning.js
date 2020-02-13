fixMozillaZIndex=true; //Fixes Z-Index problem  with Mozilla browsers but causes odd scrolling problem, toggle to see if it helps
_menuCloseDelay=500;
_menuOpenDelay=150;
_subOffsetTop=2;
_subOffsetLeft=-2;




with(AllImagesStyle=new mm_style()){
styleid=1;
bordercolor="#000000";
borderstyle="solid";
fontstyle="normal";
fontweight="normal";
padding=0;
}

with(menuStyle=new mm_style()){
bordercolor="#EC9C0E";
borderstyle="solid";
borderwidth=1;
fontfamily="Verdana, Tahoma, Arial";
fontsize="11px";
fontweight="bold";
fontstyle="normal";
headerbgcolor="#FFF";
headercolor="#EC9C0E";
offbgcolor="#FFE887";
offcolor="#EC9C0E";
onbgcolor="#FEDE58";
oncolor="#EC9C0E";
outfilter="";
overfilter="";
padding=5;
pagebgcolor="#EC9C0E";
pagecolor="white";
separatorcolor="#EC9C0E";
separatorsize=1;
subimage="../../view/login/img/navigation/fleche.gif";
subimagepadding=2;
}

with(menuStyleBis=new mm_style()){
bordercolor="#EC9C0E";
borderstyle="solid";
borderwidth=1;
fontfamily="Verdana, Tahoma, Arial";
fontsize="10px";
fontstyle="normal";
headerbgcolor="#FFF";
headercolor="#EC9C0E";
offbgcolor="#FFEFAC";
offcolor="#EC9C0E";
onbgcolor="#FEDE58";
oncolor="#EC9C0E";
outfilter="";
overfilter="";
padding=5;
pagebgcolor="#EC9C0E";
pagecolor="white";
separatorcolor="#EC9C0E";
separatorsize=1;
subimage="../../view/login/img/navigation/fleche.gif";
subimagepadding=2;
}

/*
with(milonic=new menuname("Main Menu")){
alwaysvisible=1;
position="relative";
left=0;
top=0;
style=AllImagesStyle;
orientation="horizontal";
overfilter="";
aI("image=img/navigation/serv_administration.gif;overimage=img/navigation/serv_administration_over.gif;showmenu=Administratif;");
aI("image=img/navigation/serv_epp.gif;overimage=img/navigation/serv_epp_over.gif;showmenu=Epp;");
aI("image=img/navigation/serv_informer.gif;overimage=img/navigation/serv_informer_over.gif;showmenu=Informer;");
aI("image=img/navigation/serv_gds.gif;overimage=img/navigation/serv_gds_over.gif;showmenu=Gds;");
aI("image=img/navigation/serv_vigilance.gif;overimage=img/navigation/serv_vigilance_over.gif;showmenu=Vigilance;");
}
*/
with(milonic=new menuname("Patients")){
style=menuStyle;
aI("text=Cr&eacute;ation/Maintenance de dossier;url=../../controler/ActionControler.php?controlerparams:param:controler=DossierControler&controlerparams:param:action=AM;");
aI("showmenu=Alertes;text=Alertes;");
aI("text=Visualiser les &eacute;v&eacute;nements d'un dossier;url=../../controler/ActionControler.php?controlerparams:param:controler=DossierControler&controlerparams:param:action=AMLE;");
aI("text=Visualiser les examens d'un dossier;url=../../controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AM;");
aI("text=Visualiser les dossiers d'un protocole;url=../../controler/ActionControler.php?controlerparams:param:controler=UtilityControler&controlerparams:param:action=ACONSULT;");
aI("text=Protocole de mise en place et mont&eacute;e en charge;url=../../view/docs/protocole_mise_en_place_infirmiere.pdf;target=_blank;");
aI("text=Protocole Cadre HAS – partie 1;url=../view/docs/protocole_cadre_HAS_partie_1_janvier_2012.pdf;target=_blank;");
aI("text=Protocole Cadre HAS – part 2;url=../view/docs/protocole_cadre_partie_2_janvier_2012.pdf;target=_blank;");
}


with(milonic=new menuname("Alertes")){
style=menuStyle;
aI("text=Suivi du diab&egrave;te type2;url=../../controler/ActionControler.php?controlerparams:param:controler=SuiviDiabeteControler&controlerparams:param:action=AM&controlerparams:param:param1=POUTD;");
aI("text=D&eacute;pistage du diab&egrave;te;url=../../controler/ActionControler.php?controlerparams:param:controler=DepistageDiabeteControler&controlerparams:param:action=AM&controlerparams:param:param1=POUTD;");
aI("text=D&eacute;pistage colon;url=../../controler/ActionControler.php?controlerparams:param:controler=DepistageCancerColonControler&controlerparams:param:action=AM&controlerparams:param:param1=POUTD;");
aI("text=D&eacute;pistage h&eacute;moccult;url=../controler/ActionControler.php?controlerparams:param:controler=HemocultControler&controlerparams:param:action=AM&controlerparams:param:param1=POUTD;");
aI("text=D&eacute;pistage sein;url=../../controler/ActionControler.php?controlerparams:param:controler=DepistageCancerSeinControler&controlerparams:param:action=AM&controlerparams:param:param1=POUTD;");
aI("text=D&eacute;pistage col de l'ut&eacute;rus;url=../../controler/ActionControler.php?controlerparams:param:controler=DepistageCancerUterusControler&controlerparams:param:action=AM&controlerparams:param:param1=POUTD;");
aI("text=Rep&eacute;rage Troubles cognitifs;url=../../controler/ActionControler.php?controlerparams:param:controler=TroubleCognitifControler&controlerparams:param:action=AM&controlerparams:param:param1=POUTD;");
aI("text=Suivi RCVA;url=../../controler/ActionControler.php?controlerparams:param:controler=CardioVasculaireDepartControler&controlerparams:param:action=AM&controlerparams:param:param1=POUTD;");
}


with(milonic=new menuname("Diabete")){
style=menuStyle;
aI("text=Questionnaire de d&eacute;pistage du diab&egrave;te;url=../../controler/ActionControler.php?controlerparams:param:controler=DepistageDiabeteControler&controlerparams:param:action=AM;");
aI("text=Questionnaire de suivi du diab&eacute;tique de type 2;url=../../controler/ActionControler.php?controlerparams:param:controler=SuiviDiabeteControler&controlerparams:param:action=AM&controlerparams:param:param1=PPCREAT;");
aI("text=Consultation de suivi infirmière;url=../../controler/ActionControler.php?controlerparams:param:controler=ConsultDiabeteControler&controlerparams:param:action=AM;");
aI("text=Liste des suivis d'un dossier;url=../../controler/ActionControler.php?controlerparams:param:controler=SuiviDiabeteControler&controlerparams:param:action=AM&controlerparams:param:param1=PANY;");
aI("text=Liste des suivis complets ou non;url=../../controler/ActionControler.php?controlerparams:param:controler=SuiviDiabeteControler&controlerparams:param:action=AM&controlerparams:param:param1=PINC;");
aI("text=T&eacute;l&eacute;charger une image de fond d'oeil;url=../../controler/ActionControler.php?controlerparams:param:controler=FondOeilControler&controlerparams:param:action=AN;");
aI("text=Protocole de d&eacute;pistage du diab&egrave;te;url=../../view/docs/protocole_depistage_diabete.pdf;target=_blank;");
aI("text=Protocole de suivi du diab&egrave;te de type 2;url=../../view/docs/protocole_suivi_diabete.pdf;target=_blank;");
aI("text=Protocole HAS d&eacute;pistage et suivi diab&egrave;te type 2;url=../view/docs/depistage_et_suivi_patient_maladie_chronique_diabète_type_2_-_janvier_2012.pdf;target=_blank;");
aI("showmenu=Doc_diabete;text=Documentation pour les patients;");
//aI("showmenu=Doc_infirmier;text=Documentation pour les infirmi&egrave;res;");
}

with(milonic=new menuname("Doc_diabete")){
style=menuStyle;
aI("text=Pense-bête nutrition;url=../view/cardiovasculaire/docs/ATT02699.pdf;target=_blank;");
aI("text=Les effets du tabac;url=../view/cardiovasculaire/docs/risque_tabac.pdf;target=_blank;");
aI("text=La dépendance au tabac;url=../view/cardiovasculaire/docs/ATT02697.pdf;target=_blank;");
aI("text=Bouger c'est la santé;url=../view/cardiovasculaire/docs/ATT02695.pdf;target=_blank;");
aI("text=Les effets de l'alcool;url=../view/cardiovasculaire/docs/alcool2.pdf;target=_blank;");
aI("text=La rosace du sucre;url=../view/cardiovasculaire/docs/rosace_du_sucre.pdf;target=_blank;");
}

with(milonic=new menuname("Doc_infirmier")){
style=menuStyle;
aI("text=M&eacute;dicaments et diab&egrave;te;url=../../view/docs/medicaments_et_diabete.pdf;target=_blank;");
}

with(milonic=new menuname("RCVA")){
style=menuStyle;
aI("text=Collecte des données;url=../../controler/ActionControler.php?controlerparams:param:controler=CardioVasculaireDepartControler&controlerparams:param:action=AM&controlerparams:param:param1=PE;");
aI("text=Diagnostic &eacute;ducatif;url=../../controler/ActionControler.php?controlerparams:param:controler=diageducControler&controlerparams:param:action=AM;");
aI("text=Consultation de suivi infirmière;url=../../controler/ActionControler.php?controlerparams:param:controler=AutreConsultCardioControler&controlerparams:param:action=AM;");
aI("text=Suivi INR;url=../../controler/ActionControler.php?controlerparams:param:controler=SuiviINRControler&controlerparams:param:action=AM;");
aI("text=Automesure tensionnelle;url=../../controler/ActionControler.php?controlerparams:param:controler=TensionArterielleControler&controlerparams:param:action=AM;");
aI("text=Taux de complétude des dossiers;url=../controler/ActionControler.php?controlerparams:param:controler=CardioVasculaireDepartControler&controlerparams:param:action=AGRAPH");
aI("text=Evolution RCVA;url=#;");
aI("text=Liste des donn&eacute;es disponibles;url=../../controler/ActionControler.php?controlerparams:param:controler=ListeDonneesControler&controlerparams:param:action=AN;");
aI("text=Protocole RCVA version courte;url=../../view/docs/protocole_version_courte_RCVA.pdf;target=_blank;");
aI("text=Protocole RCVA version longue;url=../../view/docs/protocoleRCVAV4.pdf;target=_blank;");
aI("text=Protocole Automesure Tensionnelle;url=../../view/docs/protocole_automesure_tensionnelle.pdf;target=_blank;");
aI("text=Protocole HAS suivi patient RCV;url=../view/docs/reperage_et_suivi_patient_maladie_chronique_RCV_-_janvier_2012.pdf;target=_blank;");
aI("text=Protocole HAS rep&eacute;rage BPCO patient tabagique;url=../view/docs/reperage_et_suivi_patient_tabagique_BPCO_-_janvier_2012.pdf;target=_blank;");
}



with(milonic=new menuname("Cancer")){
style=menuStyle;
aI("text=Questionnaire cancer du sein;url=../../controler/ActionControler.php?controlerparams:param:controler=DepistageCancerSeinControler&controlerparams:param:action=AM;");
aI("text=Questionnaire cancer du colon;url=../../controler/ActionControler.php?controlerparams:param:controler=DepistageCancerColonControler&controlerparams:param:action=AM;");
aI("text=Questionnaire d'h&eacute;moccult;url=../../controler/ActionControler.php?controlerparams:param:controler=HemocultControler&controlerparams:param:action=AM;");
aI("text=Questionnaire cancer du col de l'ut&eacute;rus;url=../../controler/ActionControler.php?controlerparams:param:controler=DepistageCancerUterusControler&controlerparams:param:action=AM;");
aI("text=Protocole de d&eacute;pistage du cancer du sein;url=../../view/docs/protocole_depistage_cancerdusein.pdf;target=_blank;");
aI("text=Protocole de d&eacute;pistage du cancer du colon;url=../../view/docs/protocole_depistage_cancercolon.pdf;target=_blank;");
aI("text=Protocole de d&eacute;pistage du cancer du col de l'ut&eacute;rus;url=../../view/docs/protocole_depistage_cancercol.pdf;target=_blank;");
}

with(milonic=new menuname("Cognitifs")){
style=menuStyle;
aI("text=Questionnaire de rep&eacute;rage des troubles cognitifs;url=../../controler/ActionControler.php?controlerparams:param:controler=TroubleCognitifControler&controlerparams:param:action=AM&controlerparams:param:param1=PPCREAT;");
aI("text=Protocoles de rep&eacute;rage des troubles cognitifs;url=../../view/docs/protocole_depistage_troubles_cognitifs.pdf;target=_blank;");
aI("text=Protocole test des 5 mots - référence;url=../../view/docs/20120312 cinq mots dubois descriptio.doc;target=_blank;");
aI("text=Protocole test des 5 mots - déroulement;url=../../view/docs/20120312 ndsa_cinq_mots.pdf;target=_blank;");
aI("text=Protocole test des 5 mots - formulaire papier;url=../../view/docs/20120312greco_cinqmots_feuille.pdf;target=_blank;");
}

with(milonic=new menuname("Evaluation")){
style=menuStyle;
aI("text=&Eacute;valuation de consultation infirmi&egrave;re;url=../../controler/ActionControler.php?controlerparams:param:controler=EvaluationInfirmierControler&controlerparams:param:action=AM;");
aI("text=Suivi des r&eacute;unions de coordination m&eacute;decin ;url=../controler/ActionControler.php?controlerparams:param:controler=SuiviReunionMedecinControler&controlerparams:param:action=AM;");
//aI("text=Suivi du temps pass&eacute;;url=../../controler/ActionControler.php?controlerparams:param:controler=SuiviHebdomadaireControler&controlerparams:param:action=AM;");
// aI("text=Suivi hebdo 2007;url=../../controler/ActionControler.php?controlerparams:param:controler=SuiviHebdomadaire2007Controler&controlerparams:param:action=AM;");
// aI("text=Suivi hebdo hebdomadaire du temps pass&eacute;;url=../../controler/ActionControler.php?controlerparams:param:controler=SuiviHebdomadaireTempsPasseControler&controlerparams:param:action=AM;");
aI("text=Suivi hebdo du temps pass&eacute;;url=../../controler/ActionControler.php?controlerparams:param:controler=SuiviHebdomadaireTempsPasseControler&controlerparams:param:action=AL;target=_blank;");

aI("text=Planning infirmi&egrave;res;url=../../view/planning/planning.php;");
aI("text=Demande de congés;url=../../controler/ActionControler.php?controlerparams:param:controler=CongesControler&controlerparams:param:action=AN;");
aI("text=Demande de remboursement de frais;url=../../controler/ActionControler.php?controlerparams:param:controler=FraisControler&controlerparams:param:action=AN;");
aI("showmenu=satisfaction;text=Questionnaire Satisfaction patient - &agrave; imprimer;");
aI("text=Questionnaire Satisfaction patient 2009 - formulaire de saisie;url=../../controler/ActionControler.php?controlerparams:param:controler=SatisfactionPatientControler&controlerparams:param:action=AN;");
aI("text=Statistiques questionnaire satisfaction patient 2009;url=../../view/stats/autres/satisf_patient2009.php;target=_blank");
aI("showmenu=Protocoles;text=Protocoles &agrave; l'&eacute;tude;");
aI("text=Demande d'indemnités;url=../../view/actualite/DEMANDE_INDEMNITES.doc;target=_blank;");
aI("text=Liste des questions au support;url=../../controler/ActionControler.php?controlerparams:param:controler=PoserQuestionControler&controlerparams:param:action=AL;");
aI("text=Intégration automatique de données;url=../../view/integration/integration.php;");
}

with(milonic=new menuname("satisfaction")){
style=menuStyle;
aI("text=Questionnaire sur une page - petits caract&egrave;res;url=../../view/docs/satisfaction3.pdf;target=blank;");
aI("text=Questionnaire sur deux pages - gros caract&egrave;res;url=../../view/docs/satisfaction_gros.pdf;target=_blank;");
}

with(milonic=new menuname("Protocoles")){
style=menuStyle;
aI("text=aide au sevrage tabagique;url=#;");
aI("text=suivi de la clairance de la cr&eacute;atinine et du poids (pour tous les patients) selon r&eacute;f&eacute;rentiel Anaes;url=#");
aI("text=&eacute;valuation du risque d'ost&eacute;oporose (&acirc;ge, apport calcique, ant&eacute;c&eacute;dent familiaux....);url=#;");
aI("text=suivi des enfants ob&egrave;ses;url=#");
aI("text=prise en charge des hyperlipid&eacute;mies;url=#;");
aI("text=&eacute;ducation du patient asthmatique;url=#");
aI("text=d&eacute;pistage et suivi des bpco;url=#;");
aI("text=&eacute;valuation nutrition et personnes &acirc;g&eacute;es type MNA;url=#");
aI("text=pr&eacute;vention des chutes de la personne &acirc;g&eacute;e;url=#;");
aI("text=organisation du retour &agrave; domicile suite passage en &eacute;tablissement hospitalier;url=#");
}


drawMenus();
