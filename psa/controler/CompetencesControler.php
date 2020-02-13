<?php

// tout ce qui concerne les planning infirmi�res


class CompetencesControler{
	

	/**
	 * affichage en toute lettre de la comp�tence en fonction de son code en base
	 * @param  [type] $code [description]
	 * @return [type]       [description]
	 */
	static function getCompetenceByCode($code){

		switch($code){
			  case 'diabete' : $reponse = 'Diab�te' ; break;
			  case 'rcva' : $reponse = 'RCV' ; break;
			  case 'bpco_spiro' : $reponse = 'BPCO / Spirom�trie' ; break;
			  case 'cognitif' : $reponse = 'Troubles cognitifs' ; break;
			  case 'cancer' : $reponse = 'D�pistage du cancer' ; break;
			  case 'pied_diabetique' : $reponse = 'Examen du pied diab�tique' ; break;
			  case 'mms' : $reponse = 'R�alisation et interpr�tation du MMS' ; break;
			  case 'rea_spiro' : $reponse = 'R�alisation de la spirom�trie' ; break;
			  case 'nutrition' : $reponse = 'Nutrition' ; break;
			  case 'act_physique' : $reponse = 'Activit� physique' ; break;
			  case 'vigilance2' : $reponse = 'Vigilance 2 (t�l�surveillance)' ; break;
			  case 'obesite' : $reponse = 'Ob�sit�' ; break;
			  case 'apnee_sommeil' : $reponse = 'Apn�e du sommeil' ; break;
			  case 'tabac_addict' : $reponse = 'Tabactologie, Addictologie' ; break;
			  case 'coord_geronto' : $reponse = 'Coordination - �valuation g�rontologique � domicile' ; break;
			  case 'retinographie' : $reponse = 'R�tinographie' ; break;
			  case 'autre_domaine' : $reponse = 'Autre domaine m�dical' ; break;
			  case 'evaluer_pps' : $reponse = 'N�gocier-construire-�valuer le PPS avec le patient' ; break;
			  case 'anim_etp_collec' : $reponse = 'Animation de s�ances ETP collectives' ; break;
			  case 'programme_etp_collec' : $reponse = 'Elaboration de programmes ETP collectifs' ; break;
			  case 'formation_etp' : $reponse = 'Animation de formations ETP aupr�s de soignants' ; break;
			  case 'amelioration_formation_etp' : $reponse = 'Elaboration/am�lioration de formations ETP aupr�s des soignants' ; break;
			  case 'entretien_etp' : $reponse = 'Entretien motivationnel' ; break;
			  case 'coord_reu_secteur' : $reponse = 'Coordination des r�unions de secteur' ; break;
			  case 'orga_reu_secteur' : $reponse = 'Organisation/animation d\'une r�union de secteur' ; break;
			  case 'coord_compagnonnage' : $reponse = 'Coordination des compagnonnages' ; break;
			  case 'rea_compagnonnage' : $reponse = 'R�alisation de compagnonnages' ; break;
			  case 'recrutement' : $reponse = 'Recrutement' ; break;
			  case 'elaboration_analyse_pratiques' : $reponse = 'Elaboration analyse des pratiques' ; break;
			  case 'animation_analyse_pratiques' : $reponse = 'Animation analyse des pratiques' ; break;
			  case 'aide_installation' : $reponse = 'Aide � l\'installation' ; break;
			  case 'suport_exercice_mixte' : $reponse = 'Support exercice mixte Asal�e/lib�ral' ; break;
			  case 'utilisation_portail_psa' : $reponse = 'Utilisation du portail PSA' ; break;
			  case 'integration_donnees' : $reponse = 'Int�gration des donn�es' ; break;
			  case 'informatique' : $reponse = 'Informatique (certificats, identifiants, adresses email...)' ; break;
			  case 'bureautique' : $reponse = 'Bureautique (Excel, Word, PowerPoint)' ; break;
			  case 'communication' : $reponse = 'Communication (Skype, TeamMeeting)' ; break;
			  case 'almapro' : $reponse = 'Almapro' ; break;
			  case 'axisante4' : $reponse = 'Axisant� 4' ; break;
			  case 'axisante5' : $reponse = 'Axisant� 5' ; break;
			  case 'clinidoc' : $reponse = 'Clinidoc' ; break;
			  case 'crossway' : $reponse = 'Crossway' ; break;
			  case 'dbmed' : $reponse = 'DBmed' ; break;
			  case 'docware' : $reponse = 'Docware' ; break;
			  case 'easyprat' : $reponse = 'Easyprat' ; break;
			  case 'eomed' : $reponse = 'Eomed' ; break;
			  case 'hellodoc' : $reponse = 'Hellodoc' ; break;
			  case 'hellodoc5_55' : $reponse = 'Hellodoc v5.55' ; break;
			  case 'hellodoc5_6' : $reponse = 'Hellodoc v5.6' ; break;
			  case 'hypermed' : $reponse = 'Hypermed' ; break;
			  case 'ict' : $reponse = 'ICT' ; break;
			  case 'maldis' : $reponse = 'Maldis' ; break;
			  case 'medi' : $reponse = 'Medi + 4000' ; break;
			  case 'medicawin' : $reponse = 'M�dicawin' ; break;
			  case 'mediclick' : $reponse = 'Mediclick' ; break;
			  case 'mediclick5' : $reponse = 'Mediclick 5' ; break;
			  case 'medimust' : $reponse = 'Medimust' ; break;
			  case 'medistory' : $reponse = 'Medistory' ; break;
			  case 'mediwin' : $reponse = 'MediWin' ; break;
			  case 'shaman' : $reponse = 'Shaman' ; break;
			  case 'weda' : $reponse = 'Weda' ; break;
			  case 'xmed' : $reponse = 'XMed' ; break;
			  case 'mlm' : $reponse = 'MLM' ; break;
			  case 'autre_logiciel' : $reponse = 'Autre logiciel' ; break;
				case 'transverses_compagnonnage' : $reponse = 'R�f�rents "Compagnonnage"'; break;
				case 'transverses_reunion' : $reponse = 'R�f�rents "R�union sectorielle"'; break;
				case 'transverses_contact' : $reponse = 'R�f�rents "Contacts/Candidatures spontan�es"'; break;
				case 'transverses_sevrage_tabac' : $reponse = 'R�f�rents "Sevrage tabagique"'; break;
				case 'transverses_apa' : $reponse = 'R�f�rents "Activit� Physique Adapt�e (APA)"'; break;
		}
			
	return $reponse;

	}


	/**
	 * on �crit correctement les libell�s des comp�tences qui seront affich�es dans les r�sultats
	 * @param  [type] $datas [description]
	 * @return [type]        [description]
	 */
	static function formatColonne($datas){
		$retour='';
		#var_dump($datas);exit;
		
			foreach($datas as $key=>$value){
				#echo $key.'->'.$value.'<br>';
				if($value=='1'){
					$retour .=utf8_encode('- '.self::getCompetenceByCode($key).'<br>');
				}
				
			}
			#$retour = substr($retour,0,-2);
		

		return $retour;
	}

	/**
	 * g�n�ration des conditions pour la requete dans le moteur de recherche 
	 * @return [type] [description]
	 */
	static function getFiltreFromPost(){

		foreach($_POST['crit'] as $value){
			#var_dump($value);
			$filtre .=" and $value = '1' ";

		}

		return $filtre;
	}

	/**
	 * traitement des infos r�cup�r�es dans le formulaire de mise � jour des comp�tences saisies par les infirmi�res
	 * @param  string $login id.login de l'infirmi�re identifi�e
	 * @return [type]       [description]
	 */
	static function updateCompetences($user){

		// on checke si d�j� y'a un record qui existe


		$datas = new stdClass();
		$datas->login = $user;
		$datas->email = $_POST['email'];
		$datas->diabete = $_POST['diabete'];
		$datas->rcva = $_POST['rcva'];
		$datas->bpco_spiro = $_POST['bpco_spiro'];
		$datas->cognitif = $_POST['cognitif'];
		$datas->cancer = $_POST['cancer'];
		$datas->pied_diabetique = $_POST['pied_diabetique'];
		$datas->mms = $_POST['mms'];
		$datas->rea_spiro = $_POST['rea_spiro'];
		$datas->nutrition = $_POST['nutrition'];
		$datas->act_physique = $_POST['act_physique'];
		$datas->vigilance2 = $_POST['vigilance2'];
		$datas->obesite = $_POST['obesite'];
		$datas->apnee_sommeil = $_POST['apnee_sommeil'];
		$datas->tabac_addict = $_POST['tabac_addict'];
		$datas->coord_geronto = $_POST['coord_geronto'];
		$datas->retinographie = $_POST['retinographie'];
		$datas->autre_domaine = addslashes(utf8_decode($_POST['autre_domaine']));
		$datas->evaluer_pps = $_POST['evaluer_pps'];
		$datas->anim_etp_collec = $_POST['anim_etp_collec'];
		$datas->programme_etp_collec = $_POST['programme_etp_collec'];
		$datas->formation_etp = $_POST['formation_etp'];
		$datas->amelioration_formation_etp = $_POST['amelioration_formation_etp'];
		$datas->entretien_etp = $_POST['entretien_etp'];
		$datas->coord_reu_secteur = $_POST['coord_reu_secteur'];
		$datas->orga_reu_secteur = $_POST['orga_reu_secteur'];
		$datas->coord_compagnonnage = $_POST['coord_compagnonnage'];
		$datas->rea_compagnonnage = $_POST['rea_compagnonnage'];
		$datas->recrutement = $_POST['recrutement'];
		$datas->elaboration_analyse_pratiques = $_POST['elaboration_analyse_pratiques'];
		$datas->animation_analyse_pratiques = $_POST['animation_analyse_pratiques'];
		$datas->aide_installation = $_POST['aide_installation'];
		$datas->suport_exercice_mixte = $_POST['suport_exercice_mixte'];
		$datas->utilisation_portail_psa = $_POST['utilisation_portail_psa'];
		$datas->integration_donnees = $_POST['integration_donnees'];
		$datas->informatique = $_POST['informatique'];
		$datas->bureautique = $_POST['bureautique'];
		$datas->communication = $_POST['communication'];
		$datas->almapro = $_POST['almapro'];
		$datas->axisante4 = $_POST['axisante4'];
		$datas->axisante5 = $_POST['axisante5'];
		$datas->clinidoc = $_POST['clinidoc'];
		$datas->crossway = $_POST['crossway'];
		$datas->dbmed = $_POST['dbmed'];
		$datas->docware = $_POST['docware'];
		$datas->easyprat = $_POST['easyprat'];
		$datas->eomed = $_POST['eomed'];
		$datas->hellodoc = $_POST['hellodoc'];
		$datas->hellodoc5_55 = $_POST['hellodoc5_55'];
		$datas->hellodoc5_6 = $_POST['hellodoc5_6'];
		$datas->hypermed = $_POST['hypermed'];
		$datas->ict = $_POST['ict'];
		$datas->maldis = $_POST['maldis'];
		$datas->medi = $_POST['medi'];
		$datas->medicawin = $_POST['medicawin'];
		$datas->mediclick = $_POST['mediclick'];
		$datas->mediclick5 = $_POST['mediclick5'];
		$datas->medimust = $_POST['medimust'];
		$datas->medistory = $_POST['medistory'];
		$datas->mediwin = $_POST['mediwin'];
		$datas->shaman = $_POST['shaman'];
		$datas->weda = $_POST['weda'];
		$datas->xmed = $_POST['xmed'];
		$datas->mlm = $_POST['mlm'];
		$datas->autre_logiciel = addslashes(utf8_decode($_POST['autre_logiciel']));
		$datas->transverses_compagnonnage = $_POST['transverses_compagnonnage'];
		$datas->transverses_reunion = $_POST['transverses_reunion'];
		$datas->transverses_contact = $_POST['transverses_contact'];
		$datas->transverses_sevrage_tabac = $_POST['transverses_sevrage_tabac'];
		$datas->transverses_apa = $_POST['transverses_apa'];

		$datasExist = CompetencesInfirmieres::getCompetencesByLogin($user);

		if($datasExist){
			CompetencesInfirmieres::updateDatas($datas);
		}
		else{
			CompetencesInfirmieres::addDatas($datas);
		}


	}



}