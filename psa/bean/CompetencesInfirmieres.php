<?php


class CompetencesInfirmieres {
	  

	/**
	 * listing de toute les comptences en base
	 * va chercher aussi les données cabinet 
	 * @param  [type] $infirmiere [description]
	 * @return [type]             [description]
	 */
	static function getAllCompetences($filtre = false){


	 	$sql = "select * from competences_infirmier where 1 $filtre ";
	 	$result = mysql_query($sql);

	 	while($row = mysql_fetch_assoc($result)){
				
				$infirmiere = GetInfosByLogin($row['login'], $status);
				$inf = current($infirmiere);

				#var_dump($infirmiere);exit;

				$row['nom'] = $inf['nom'];
				$row['prenom'] = $inf['prenom'];
				$row['telephone'] = $inf['telephone'];

				$rowsList[] = $row;
				#var_dump($rowsList);
			}
		#var_dump($rowsList);
		return $rowsList;
	 }
	
	static function getCompetencesByLogin($login){
		$sql = "select * from competences_infirmier where login='$login' ";
	 	$result = mysql_query($sql);
	 	$row = mysql_fetch_assoc($result);
				
		return $row;


	}
	

	/**
	 * ajout d'une information compétences infirmière
	 * @param [type] $data [description]
	 */
	static function addDatas($data){

	$updt = "INSERT INTO competences_infirmier set 
	  `login`= '$data->login',
	  `email`= '$data->email',
	  `diabete` = '$data->diabete',
	  `rcva` = '$data->rcva',
	  `bpco_spiro` = '$data->bpco_spiro',
	  `cognitif` = '$data->cognitif',
	  `cancer` = '$data->cancer',
	  `pied_diabetique` = '$data->pied_diabetique',
	  `mms` = '$data->mms',
	  `rea_spiro` = '$data->rea_spiro',
	  `nutrition` = '$data->nutrition',
	  `act_physique` = '$data->act_physique',
	  `vigilance2` = '$data->vigilance2',
	  `obesite` = '$data->obesite',
	  `apnee_sommeil` = '$data->apnee_sommeil',
	  `tabac_addict` = '$data->tabac_addict',
	  `coord_geronto` = '$data->coord_geronto',
	  `retinographie` = '$data->retinographie',
	  `autre_domaine` = '$data->autre_domaine',
	  `evaluer_pps` = '$data->evaluer_pps',
	  `anim_etp_collec` = '$data->anim_etp_collec',
	  `programme_etp_collec` = '$data->programme_etp_collec',
	  `formation_etp` = '$data->formation_etp',
	  `amelioration_formation_etp` = '$data->amelioration_formation_etp',
	  `entretien_etp` = '$data->entretien_etp',
	  `coord_reu_secteur` = '$data->coord_reu_secteur',
	  `orga_reu_secteur` = '$data->orga_reu_secteur',
	  `coord_compagnonnage` = '$data->coord_compagnonnage',
	  `rea_compagnonnage` = '$data->rea_compagnonnage',
	  `recrutement` = '$data->recrutement',
	  `elaboration_analyse_pratiques` = '$data->elaboration_analyse_pratiques',
	  `animation_analyse_pratiques` = '$data->animation_analyse_pratiques',
	  `aide_installation` = '$data->aide_installation',
	  `suport_exercice_mixte` = '$data->suport_exercice_mixte',
	  `utilisation_portail_psa` = '$data->utilisation_portail_psa',
	  `integration_donnees` = '$data->integration_donnees',
	  `informatique` = '$data->informatique',
	  `bureautique` = '$data->bureautique',
	  `communication` = '$data->communication',
	  `almapro` = '$data->almapro',
	  `axisante4` = '$data->axisante4',
	  `axisante5` = '$data->axisante5',
	  `clinidoc` = '$data->clinidoc',
	  `crossway` = '$data->crossway',
	  `dbmed` = '$data->dbmed',
	  `docware` = '$data->docware',
	  `easyprat` = '$data->easyprat',
	  `eomed` = '$data->eomed',
	  `hellodoc` = '$data->hellodoc',
	  `hellodoc5_55` = '$data->hellodoc5_55',
	  `hellodoc5_6` = '$data->hellodoc5_6',
	  `hypermed` = '$data->hypermed',
	  `ict` = '$data->ict',
	  `maldis` = '$data->maldis',
	  `medi` = '$data->medi',
	  `medicawin` = '$data->medicawin',
	  `mediclick` = '$data->mediclick',
	  `mediclick5` = '$data->mediclick5',
	  `medimust` = '$data->medimust',
	  `medistory` = '$data->medistory',
	  `mediwin` = '$data->mediwin',
	  `shaman` = '$data->shaman',
	  `weda` = '$data->weda',
	  `xmed` = '$data->xmed',
	  `mlm` = '$data->mlm',
	  `autre_logiciel` = '$data->autre_logiciel',
		`transverses_compagnonnage` = '$data->transverses_compagnonnage',
		`transverses_reunion` = '$data->transverses_reunion',
		`transverses_contact` = '$data->transverses_contact',
		`transverses_sevrage_tabac` = '$data->transverses_sevrage_tabac',
		`transverses_apa` = '$data->transverses_apa',
	  `datemaj` = now()
	  ";

	  mysql_query($updt);
	  #echo $updt;
	  #exit;
	}


	/**
	 * mise à jour des infos sur les compétences
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	static function updateDatas($data){

	$updt = "UPDATE competences_infirmier set 
	  `diabete` = '$data->diabete',
	  `rcva` = '$data->rcva',
	  `bpco_spiro` = '$data->bpco_spiro',
	  `cognitif` = '$data->cognitif',
	  `cancer` = '$data->cancer',
	  `pied_diabetique` = '$data->pied_diabetique',
	  `mms` = '$data->mms',
	  `rea_spiro` = '$data->rea_spiro',
	  `nutrition` = '$data->nutrition',
	  `act_physique` = '$data->act_physique',
	  `vigilance2` = '$data->vigilance2',
	  `obesite` = '$data->obesite',
	  `apnee_sommeil` = '$data->apnee_sommeil',
	  `tabac_addict` = '$data->tabac_addict',
	  `coord_geronto` = '$data->coord_geronto',
	  `retinographie` = '$data->retinographie',
	  `autre_domaine` = '$data->autre_domaine',
	  `evaluer_pps` = '$data->evaluer_pps',
	  `anim_etp_collec` = '$data->anim_etp_collec',
	  `programme_etp_collec` = '$data->programme_etp_collec',
	  `formation_etp` = '$data->formation_etp',
	  `amelioration_formation_etp` = '$data->amelioration_formation_etp',
	  `entretien_etp` = '$data->entretien_etp',
	  `coord_reu_secteur` = '$data->coord_reu_secteur',
	  `orga_reu_secteur` = '$data->orga_reu_secteur',
	  `coord_compagnonnage` = '$data->coord_compagnonnage',
	  `rea_compagnonnage` = '$data->rea_compagnonnage',
	  `recrutement` = '$data->recrutement',
	  `elaboration_analyse_pratiques` = '$data->elaboration_analyse_pratiques',
	  `animation_analyse_pratiques` = '$data->animation_analyse_pratiques',
	  `aide_installation` = '$data->aide_installation',
	  `suport_exercice_mixte` = '$data->suport_exercice_mixte',
	  `utilisation_portail_psa` = '$data->utilisation_portail_psa',
	  `integration_donnees` = '$data->integration_donnees',
	  `informatique` = '$data->informatique',
	  `bureautique` = '$data->bureautique',
	  `communication` = '$data->communication',
	  `almapro` = '$data->almapro',
	  `axisante4` = '$data->axisante4',
	  `axisante5` = '$data->axisante5',
	  `clinidoc` = '$data->clinidoc',
	  `crossway` = '$data->crossway',
	  `dbmed` = '$data->dbmed',
	  `docware` = '$data->docware',
	  `easyprat` = '$data->easyprat',
	  `eomed` = '$data->eomed',
	  `hellodoc` = '$data->hellodoc',
	  `hellodoc5_55` = '$data->hellodoc5_55',
	  `hellodoc5_6` = '$data->hellodoc5_6',
	  `hypermed` = '$data->hypermed',
	  `ict` = '$data->ict',
	  `maldis` = '$data->maldis',
	  `medi` = '$data->medi',
	  `medicawin` = '$data->medicawin',
	  `mediclick` = '$data->mediclick',
	  `mediclick5` = '$data->mediclick5',
	  `medimust` = '$data->medimust',
	  `medistory` = '$data->medistory',
	  `mediwin` = '$data->mediwin',
	  `shaman` = '$data->shaman',
	  `weda` = '$data->weda',
	  `xmed` = '$data->xmed',
	  `mlm` = '$data->mlm',
	  `autre_logiciel` = '$data->autre_logiciel',
	  `transverses_compagnonnage` = '$data->transverses_compagnonnage',
		`transverses_reunion` = '$data->transverses_reunion',
		`transverses_contact` = '$data->transverses_contact',
		`transverses_sevrage_tabac` = '$data->transverses_sevrage_tabac',
		`transverses_apa` = '$data->transverses_apa',
	  `datemaj` = now()
	  where `login`= '$data->login' LIMIT 1
	  ";

	  mysql_query($updt);
	  #echo $updt;
	  #exit;
	}


}
 ?>
