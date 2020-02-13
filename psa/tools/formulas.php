<?php
	function getIMC($poids,$taille){
		if(is_null($taille) or !is_numeric($taille) or $taille == 0)
			return "La taille n'est pas saisie, L'imc ne peut etre calculée";		
		return round($poids/pow($taille/100, 2),1);
	}
	
	function getClearance($sexe,$poids,$age,$creatininemie){
	    
		if(is_null($creatininemie)) return NULL;
		
		if(!is_numeric($creatininemie)) return NULL;
		if($creatininemie == 0) return NULL;

		$clearance = (140-$age)*$poids/(7.2*$creatininemie);
		
		if($sexe == "F") $clearance *= 0.85;
		
		$clearance=round($clearance);
		return 	$clearance ;
	}
	
	
	function get_iadl($tab){
		$iadl=4;
	   	if($tab['iadl_telephone'] == 'tout'){
		    $iadl--;;
		}

		if($tab['iadl_transport'] == 'tout'){
		  	$iadl--;
		}

		if($tab['iadl_med'] == 'tout'){
		    $iadl--;
		}

		if($tab['iadl_budget'] == 'tout'){
		    $iadl--;
		}

		if(($tab['iadl_telephone']==NULL)&&($tab['iadl_transport']==NULL)&&($tab['iadl_med']==NULL)
		    &&($tab['iadl_budget']==NULL))
		    $iadl='';
		    
		return $iadl;
	}

	function get_mmse($tab){
		$mmse=0;


		$mmse= $tab['mmse_annee']+$tab['mmse_saison']+ $tab['mmse_mois']+$tab['mmse_jour_mois']
				+$tab['mmse_jour_semaine']+$tab['mmse_nom_hop']+$tab['mmse_nom_ville']+
				$tab['mmse_nom_dep']+$tab['mmse_region']+$tab['mmse_etage']+$tab['mmse_cigare1']+
				$tab['mmse_fleur1']+$tab['mmse_porte1']+$tab['mmse_93']+$tab['mmse_86']+$tab['mmse_79']+
				$tab['mmse_72']+$tab['mmse_65']+$tab['mmse_cigare2']+$tab['mmse_fleur2']+
				$tab['mmse_porte2']+$tab['mmse_crayon']+$tab['mmse_montre']+$tab['mmse_repete_phrase']+
				$tab['mmse_feuille_prise']+$tab['mmse_feuille_pliee']+$tab['mmse_feuille_jetee']+
				$tab['mmse_fermer_yeux']+$tab['mmse_ecrit_phrase']+$tab['mmse_copie_dessin'];

	if(($tab['mmse_annee']===NULL)&&($tab['mmse_saison']===NULL)&&($tab['mmse_mois']===NULL)&&
	  ($tab['mmse_jour_mois']===NULL)&&($tab['mmse_jour_semaine']===NULL)&&($tab['mmse_nom_hop']===NULL)&&
	  ($tab['mmse_nom_ville']===NULL)&&($tab['mmse_nom_dep']===NULL)&&($tab['mmse_region']===NULL)&&
	  ($tab['mmse_etage']===NULL)&&($tab['mmse_cigare1']===NULL)&&($tab['mmse_fleur1']===NULL)&&
	  ($tab['mmse_porte1']===NULL)&&($tab['mmse_93']===NULL)&&($tab['mmse_86']===NULL)&&($tab['mmse_79']===NULL)&&
	  ($tab['mmse_72']===NULL)&&($tab['mmse_65']===NULL)&&($tab['mmse_monde']===NULL)&&($tab['mmse_cigare2']===NULL)&&
	  ($tab['mmse_fleur2']===NULL)&&($tab['mmse_porte2']===NULL)&&($tab['mmse_crayon']===NULL)&&
	  ($tab['mmse_montre']===NULL)&&($tab['mmse_repete_phrase']===NULL)&&($tab['mmse_feuille_prise']===NULL)&&
	  ($tab['mmse_feuille_pliee']===NULL)&&($tab['mmse_feuille_jetee']===NULL)&&($tab['mmse_fermer_yeux']===NULL)&&
	  ($tab['mmse_ecrit_phrase']===NULL)&&($tab['mmse_copie_dessin']===NULL))
		  $mmse='';



		return $mmse;
	}


	function get_gds($tab){
		$gds=0;

			if($tab['gds_satisf']=='non')
			{
			    $gds++;
			}

			if($tab['gds_renonce_act']=='oui')
			{
			    $gds++;
			}

			if($tab['gds_vie_vide']=='oui')
			{
			    $gds++;
			}

			if($tab['gds_ennui']=='oui')
			{
			    $gds++;
			}

			if($tab['gds_avenir_opt']=='non')
			{
			    $gds++;
			}

			if($tab['gds_cata']=='oui')
			{
			    $gds++;
			}

			if($tab['gds_bonne_humeur']=='non')
			{
			    $gds++;
			}

			if($tab['gds_besoin_aide']=='oui')
			{
			    $gds++;
			}

			if($tab['gds_prefere_seul']=='oui')
			{
			    $gds++;
			}

			if($tab['gds_mauvaise_mem']=='oui')
			{
			    $gds++;
			}

			if($tab['gds_heureux_vivre']=='non')
			{
			    $gds++;
			}

			if($tab['gds_bon_rien']=='oui')
			{
			    $gds++;
			}

			if($tab['gds_energie']=='non')
			{
			    $gds++;
			}

			if($tab['gds_desespere_sit']=='oui')
			{
			    $gds++;
			}

			if($tab['gds_sit_autres_best']=='oui')
			{
			    $gds++;
			}



			if(($tab['gds_satisf']==NULL)&&($tab['gds_renonce_act']==NULL)&&($tab['gds_vie_vide']==NULL)&&
			  ($tab['gds_ennui']==NULL)&&($tab['gds_avenir_opt']==NULL)&&($tab['gds_cata']==NULL)&&
			  ($tab['gds_bonne_humeur']==NULL)&&($tab['gds_besoin_aide']==NULL)&&($tab['gds_prefere_seul']==NULL)&&
			  ($tab['gds_mauvaise_mem']==NULL)&&($tab['gds_heureux_vivre']==NULL)&&($tab['gds_bon_rien']==NULL)&&
			  ($tab['gds_energie']==NULL)&&($tab['gds_desespere_sit']==NULL)&&($tab['gds_sit_autres_best']==NULL))
					$gds='';


		return $gds;
	}

function get_rcva($sexe, $age, $diab, $tab, $tension, $choltot, $hdl, $ventricule, $surcharge_ventricule){
	
	$e1 = -0.9119;
	$e2 = -0.2767;
	$e3 = -0.7181;
	$e4 = -0.5865;
	$l = 11.1122;
	$m0 = 4.4181 ;
	$s0 = -0.3155 ;
	$s1 = -0.2784;
	$c1 = -1.4792 ;
	$c2 = -0.1759 ;
	$d1 = -5.8549;
	$d2 = 1.8515 ;
	$d3 = -0.3758 ;
	$horizon=10;
	
	$pas=$tension;
	$tabac=0;
	$hvg=0;
	$chol=$choltot;
	$HDL=$hdl;
	
	if($tab=="oui"){
		$tabac=1;
	}
	if(($ventricule!="oui")&&($ventricule!="non")&&($surcharge_ventricule=="oui")){
		$hvg=1;
	}
	if($ventricule=="oui"){
		$hvg=1;
	}

	$a = $l + $e1*log($pas) + $e2*$tabac + $e3*log($chol/$HDL) + $e4*$hvg;

	if($sexe=="M"){
		$m = $a + $c1*log($age) + $c2*$diab*1; //;=> on considère que le patient n'est pas diabétique
	}
	if($sexe=='F'){
		$m = $a + $d1 + $d2*(log($age/74)*log($age/74)) + $d3*$diab; //on considère que la patient n'est pas diabétique
	}
	

	$m_calc = $m0 + $m;
	$s = exp($s0 + $s1*$m);

	$u = (log($horizon) - $m_calc ) / $s ;

	$pt = 1- exp(-exp($u));

	$rcva=round($pt*100, 2);
	// $rcva=$rcva."%";
	
	return $rcva;

}


function get_age($dnaiss, $date=false){
	if($date==false){
		$date=date("Y-m-d");
	}
	
	$dj=explode("-", $date);
	$dn=explode("-", $dnaiss);
	
	$age=$dj[0]-$dn[0];
	if($dj[1]<$dn[1]){
		$age--;
	}
	if(($dj[1]==$dn[1])&&($dj[2]<$dn[2])){
		$age--;
	}
	return $age;
}

?>
