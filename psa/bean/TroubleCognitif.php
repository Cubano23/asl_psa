<?php

require_once("tools/date.php");

class TroubleCognitif{
	  var $id;
	  var $date;
	  var $suivi_type;
	  var $date_rappel;
	  var $dep_type;
	  var $raison_dep;
	  var $sortir_rappel;
	  var $raison_sortie;
	  var $mmse_annee;
	  var $mmse_saison;
	  var $mmse_mois;
	  var $mmse_jour_mois;
	  var $mmse_jour_semaine;
	  var $mmse_nom_hop;
	  var $mmse_nom_ville;
	  var $mmse_nom_dep;
	  var $mmse_region;
	  var $mmse_etage;
	  var $mmse_cigare1;
	  var $mmse_fleur1;
	  var $mmse_porte1;
	  var $mmse_93;
	  var $mmse_86;
	  var $mmse_79;
	  var $mmse_72;
	  var $mmse_65;
	  var $mmse_monde;
	  var $mmse_cigare2;
	  var $mmse_fleur2;
	  var $mmse_porte2;
	  var $mmse_crayon;
	  var $mmse_montre;
	  var $mmse_repete_phrase;
	  var $mmse_feuille_prise;
	  var $mmse_feuille_pliee;
	  var $mmse_feuille_jetee;
	  var $mmse_fermer_yeux;
	  var $mmse_ecrit_phrase;
	  var $mmse_copie_dessin;
	  var $gds_satisf;
	  var $gds_renonce_act;
	  var $gds_vie_vide;
	  var $gds_ennui;
	  var $gds_avenir_opt;
	  var $gds_cata;
	  var $gds_bonne_humeur;
	  var $gds_besoin_aide;
	  var $gds_prefere_seul;
	  var $gds_mauvaise_mem;
	  var $gds_heureux_vivre;
	  var $gds_bon_rien;
	  var $gds_energie;
	  var $gds_desespere_sit;
	  var $gds_sit_autres_best;
	  var $iadl_telephone;
	  var $iadl_transport;
	  var $iadl_med;
	  var $iadl_budget;
	  var $horloge;
	  var $dubois_immediatsi;
	  var $dubois_immediatai;
	  var $dubois_diffsi;
	  var $dubois_diffai;

	function TroubleCognitif(
	  $id=NULL,
	  $date=NULL,
	  $suivi_type=array(),
	  $date_rappel=NULL,
	  $dep_type=NULL,
	  $raison_dep=NULL,
	  $sortir_rappel=NULL,
	  $raison_sortie=NULL,
	  $mmse_annee=NULL,
	  $mmse_saison=NULL,
	  $mmse_mois=NULL,
	  $mmse_jour_mois=NULL,
	  $mmse_jour_semaine=NULL,
	  $mmse_nom_hop=NULL,
	  $mmse_nom_ville=NULL,
	  $mmse_nom_dep=NULL,
	  $mmse_region=NULL,
	  $mmse_etage=NULL,
	  $mmse_cigare1=NULL,
	  $mmse_fleur1=NULL,
	  $mmse_porte1=NULL,
	  $mmse_93=NULL,
	  $mmse_86=NULL,
	  $mmse_79=NULL,
	  $mmse_72=NULL,
	  $mmse_65=NULL,
	  $mmse_monde=NULL,
	  $mmse_cigare2=NULL,
	  $mmse_fleur2=NULL,
	  $mmse_porte2=NULL,
	  $mmse_crayon=NULL,
	  $mmse_montre=NULL,
	  $mmse_repete_phrase=NULL,
	  $mmse_feuille_prise=NULL,
	  $mmse_feuille_pliee=NULL,
	  $mmse_feuille_jetee=NULL,
	  $mmse_fermer_yeux=NULL,
	  $mmse_ecrit_phrase=NULL,
	  $mmse_copie_dessin=NULL,
	  $gds_satisf=NULL,
	  $gds_renonce_act=NULL,
	  $gds_vie_vide=NULL,
	  $gds_ennui=NULL,
	  $gds_avenir_opt=NULL,
	  $gds_cata=NULL,
	  $gds_bonne_humeur=NULL,
	  $gds_besoin_aide=NULL,
	  $gds_prefere_seul=NULL,
	  $gds_mauvaise_mem=NULL,
	  $gds_heureux_vivre=NULL,
	  $gds_bon_rien=NULL,
	  $gds_energie=NULL,
	  $gds_desespere_sit=NULL,
	  $gds_sit_autres_best=NULL,
	  $iadl_telephone=NULL,
	  $iadl_transport=NULL,
	  $iadl_med=NULL,
	  $iadl_budget=NULL,
	  $horloge=NULL,
	  $dubois_immediatsi=NULL,
	  $dubois_immediatai=NULL,
	  $dubois_diffsi=NULL,
	  $dubois_diffai=NULL
		){
			$this->id = $id;
			$this->date = $date;
			$this->suivi_type=$suivi_type;
			$this->date_rappel=$date_rappel;
			$this->dep_type=$dep_type;
			$this->raison_dep=$raison_dep;
	  		$this->sortir_rappel=$sortir_rappel;
	  		$this->raison_sortie=$raison_sortie;
			$this->mmse_annee=$mmse_annee;
			$this->mmse_saison=$mmse_saison;
			$this->mmse_mois=$mmse_mois;
			$this->mmse_jour_mois=$mmse_jour_mois;
			$this->mmse_jour_semaine=$mmse_jour_semaine;
			$this->mmse_nom_hop=$mmse_nom_hop;
			$this->mmse_nom_ville=$mmse_nom_ville;
			$this->mmse_nom_dep=$mmse_nom_dep;
			$this->mmse_region=$mmse_region;
			$this->mmse_etage=$mmse_etage;
			$this->mmse_cigare1=$mmse_cigare1;
			$this->mmse_fleur1=$mmse_fleur1;
			$this->mmse_porte1=$mmse_porte1;
			$this->mmse_93=$mmse_93;
			$this->mmse_86=$mmse_86;
			$this->mmse_79=$mmse_79;
			$this->mmse_72=$mmse_72;
			$this->mmse_65=$mmse_65;
			$this->mmse_monde=$mmse_monde;
			$this->mmse_cigare2=$mmse_cigare2;
			$this->mmse_fleur2=$mmse_fleur2;
			$this->mmse_porte2=$mmse_porte2;
			$this->mmse_crayon=$mmse_crayon;
			$this->mmse_montre=$mmse_montre;
			$this->mmse_repete_phrase=$mmse_repete_phrase;
			$this->mmse_feuille_prise=$mmse_feuille_prise;
			$this->mmse_feuille_pliee=$mmse_feuille_pliee;
			$this->mmse_feuille_jetee=$mmse_feuille_jetee;
			$this->mmse_fermer_yeux=$mmse_fermer_yeux;
			$this->mmse_ecrit_phrase=$mmse_ecrit_phrase;
			$this->mmse_copie_dessin=$mmse_copie_dessin;
			$this->gds_satisf=$gds_satisf;
			$this->gds_renonce_act=$gds_renonce_act;
			$this->gds_vie_vide=$gds_vie_vide;
			$this->gds_ennui=$gds_ennui;
			$this->gds_avenir_opt=$gds_avenir_opt;
			$this->gds_cata=$gds_cata;
			$this->gds_bonne_humeur=$gds_bonne_humeur;
			$this->gds_besoin_aide=$gds_besoin_aide;
			$this->gds_prefere_seul=$gds_prefere_seul;
			$this->gds_mauvaise_mem=$gds_mauvaise_mem;
			$this->gds_heureux_vivre=$gds_heureux_vivre;
			$this->gds_bon_rien=$gds_bon_rien;
			$this->gds_energie=$gds_energie;
			$this->gds_desespere_sit=$gds_desespere_sit;
			$this->gds_sit_autres_best=$gds_sit_autres_best;
			$this->iadl_telephone=$iadl_telephone;
			$this->iadl_transport=$iadl_transport;
			$this->iadl_med=$iadl_med;
			$this->iadl_budget=$iadl_budget;
			$this->horloge=$horloge;
			$this->dubois_immediatsi=$dubois_immediatsi;
			$this->dubois_immediatai=$dubois_immediatai;
			$this->dubois_diffsi=$dubois_diffsi;
			$this->dubois_diffai=$dubois_diffai;
	}

	 function toString(){
		 return 
			$this->id." ".
			$this->date." ".
			$this->suivi_type." ".
			$this->date_rappel." ".
			$this->dep_type." ".
			$this->raison_dep." ".
			$this->sortir_rappel." ".
			$this->raison_sortie." ".
			$this->mmse_annee." ".
			$this->mmse_saison." ".
			$this->mmse_mois." ".
			$this->mmse_jour_mois." ".
			$this->mmse_jour_semaine." ".
			$this->mmse_nom_hop." ".
			$this->mmse_nom_ville." ".
			$this->mmse_nom_dep." ".
			$this->mmse_region." ".
			$this->mmse_etage." ".
			$this->mmse_cigare1." ".
			$this->mmse_fleur1." ".
			$this->mmse_porte1." ".
			$this->mmse_93." ".
			$this->mmse_86." ".
			$this->mmse_79." ".
			$this->mmse_72." ".
			$this->mmse_65." ".
			$this->mmse_monde." ".
			$this->mmse_cigare2." ".
			$this->mmse_fleur2." ".
			$this->mmse_porte2." ".
			$this->mmse_crayon." ".
			$this->mmse_montre." ".
			$this->mmse_repete_phrase." ".
			$this->mmse_feuille_prise." ".
			$this->mmse_feuille_pliee." ".
			$this->mmse_feuille_jetee." ".
			$this->mmse_fermer_yeux." ".
			$this->mmse_ecrit_phrase." ".
			$this->mmse_copie_dessin." ".
			$this->gds_satisf." ".
			$this->gds_renonce_act." ".
			$this->gds_vie_vide." ".
			$this->gds_ennui." ".
			$this->gds_avenir_opt." ".
			$this->gds_cata." ".
			$this->gds_bonne_humeur." ".
			$this->gds_besoin_aide." ".
			$this->gds_prefere_seul." ".
			$this->gds_mauvaise_mem." ".
			$this->gds_heureux_vivre." ".
			$this->gds_bon_rien." ".
			$this->gds_energie." ".
			$this->gds_desespere_sit." ".
			$this->gds_sit_autres_best." ".
			$this->iadl_telephone." ".
			$this->iadl_transport." ".
			$this->iadl_med." ".
			$this->iadl_budget." ".
			$this->horloge." ".
			$this->dubois_immediatsi." ".
			$this->dubois_immediatai." ".
			$this->dubois_diffsi." ".
			$this->dubois_diffai;
	}

	function get_iadl(){
		$iadl=4;
	   	if($this->iadl_telephone == 'tout'){
		    $iadl--;;
		}

		if($this->iadl_transport == 'tout'){
		  	$iadl--;
		}

		if($this->iadl_med == 'tout'){
		    $iadl--;
		}

		if($this->iadl_budget == 'tout'){
		    $iadl--;
		}


	   	if(($this->iadl_telephone == NULL)&&($this->iadl_transport == NULL)&&($this->iadl_med == NULL)
		   &&($this->iadl_budget == NULL))
		    	$iadl='';

		return $iadl;
	}

	function get_mmse(){
		$mmse=0;
		
		$mmse= $this->mmse_annee+$this->mmse_saison+ $this->mmse_mois+$this->mmse_jour_mois
				+$this->mmse_jour_semaine+$this->mmse_nom_hop+$this->mmse_nom_ville+
				$this->mmse_nom_dep+$this->mmse_region+$this->mmse_etage+$this->mmse_cigare1+
				$this->mmse_fleur1+$this->mmse_porte1+$this->mmse_93+$this->mmse_86+$this->mmse_79+
				$this->mmse_72+$this->mmse_65+$this->mmse_cigare2+$this->mmse_fleur2+
				$this->mmse_porte2+$this->mmse_crayon+$this->mmse_montre+$this->mmse_repete_phrase+
				$this->mmse_feuille_prise+$this->mmse_feuille_pliee+$this->mmse_feuille_jetee+
				$this->mmse_fermer_yeux+$this->mmse_ecrit_phrase+$this->mmse_copie_dessin;


		if(($this->mmse_annee==NULL)&&($this->mmse_saison==NULL)&& ($this->mmse_mois==NULL)&&
			($this->mmse_jour_mois==NULL)&&($this->mmse_jour_semaine==NULL)&&($this->mmse_nom_hop==NULL)&&
			($this->mmse_nom_ville==NULL)&&($this->mmse_nom_dep==NULL)&&($this->mmse_region==NULL)&&
			($this->mmse_etage==NULL)&&($this->mmse_cigare1==NULL)&&($this->mmse_fleur1==NULL)&&
			($this->mmse_porte1==NULL)&&($this->mmse_93==NULL)&&($this->mmse_86==NULL)&&($this->mmse_79==NULL)&&
			($this->mmse_72==NULL)&&($this->mmse_65==NULL)&&($this->mmse_cigare2==NULL)&&($this->mmse_fleur2==NULL)&&
			($this->mmse_porte2==NULL)&&($this->mmse_crayon==NULL)&&($this->mmse_montre==NULL)&&
			($this->mmse_repete_phrase==NULL)&&($this->mmse_feuille_prise==NULL)&&($this->mmse_feuille_pliee==NULL)&&
			($this->mmse_feuille_jetee==NULL)&&($this->mmse_fermer_yeux==NULL)&&($this->mmse_ecrit_phrase==NULL)&&
			($this->mmse_copie_dessin==NULL))
			    $mmse="";

		return $mmse;
	}


	function get_gds(){
		$gds=0;

			if($this->gds_satisf=='non')
			{
			    $gds++;
			}

			if($this->gds_renonce_act=='oui')
			{
			    $gds++;
			}

			if($this->gds_vie_vide=='oui')
			{
			    $gds++;
			}

			if($this->gds_ennui=='oui')
			{
			    $gds++;
			}

			if($this->gds_avenir_opt=='non')
			{
			    $gds++;
			}

			if($this->gds_cata=='oui')
			{
			    $gds++;
			}

			if($this->gds_bonne_humeur=='non')
			{
			    $gds++;
			}

			if($this->gds_besoin_aide=='oui')
			{
			    $gds++;
			}

			if($this->gds_prefere_seul=='oui')
			{
			    $gds++;
			}

			if($this->gds_mauvaise_mem=='oui')
			{
			    $gds++;
			}

			if($this->gds_heureux_vivre=='non')
			{
			    $gds++;
			}

			if($this->gds_bon_rien=='oui')
			{
			    $gds++;
			}

			if($this->gds_energie=='non')
			{
			    $gds++;
			}

			if($this->gds_desespere_sit=='oui')
			{
			    $gds++;
			}

			if($this->gds_sit_autres_best=='oui')
			{
			    $gds++;
			}



		if(($this->gds_satisf==NULL)&&($this->gds_renonce_act==NULL)&&($this->gds_vie_vide==NULL)&&
			($this->gds_ennui==NULL)&&($this->gds_avenir_opt==NULL)&&($this->gds_cata==NULL)&&
			($this->gds_bonne_humeur==NULL)&&($this->gds_besoin_aide==NULL)&&($this->gds_prefere_seul==NULL)&&
			($this->gds_mauvaise_mem==NULL)&&($this->gds_heureux_vivre==NULL)&&($this->gds_bon_rien==NULL)&&
			($this->gds_energie==NULL)&&($this->gds_desespere_sit==NULL)&&($this->gds_sit_autres_best==NULL))
			{
			    $gds='';
			}
			
			
		return $gds;
	}

	function check(){
		$errors = array();
		$i = 0;

		if((($this->date_rappel!='')&&(!isDate($this->date_rappel))) || (($this->dep_type=="coll")&&(!isDate($this->date_rappel))))
			$errors[$i++] = "La date de rappel est invalide";

		if(($this->dep_type!='coll')&&($this->dep_type!='indiv')) $errors[$i++]="Indiquez le type de dépistage";
		
		//vérification mmse
		//rien à vérifier
		
		// vérification gds
		if(in_array('gds',$this->suivi_type)){
			if(($this->gds_satisf!='oui')&&($this->gds_satisf!='non'))
			{
			    $errors[$i++]="Répondez à la question 1 du test GDS";
			}
			
			if(($this->gds_renonce_act!='oui')&&($this->gds_renonce_act!='non'))
			{
			    $errors[$i++]="Répondez à la question 2 du test GDS";
			}
			
			if(($this->gds_vie_vide!='oui')&&($this->gds_vie_vide!='non'))
			{
			    $errors[$i++]="Répondez à la question 3 du test GDS";
			}
			
			if(($this->gds_ennui!='oui')&&($this->gds_ennui!='non'))
			{
			    $errors[$i++]="Répondez à la question 4 du test GDS";
			}
			
			if(($this->gds_avenir_opt!='oui')&&($this->gds_avenir_opt!='non'))
			{
			    $errors[$i++]="Répondez à la question 5 du test GDS";
			}
			
			if(($this->gds_cata!='oui')&&($this->gds_cata!='non'))
			{
			    $errors[$i++]="Répondez à la question 6 du test GDS";
			}
			
			if(($this->gds_bonne_humeur!='oui')&&($this->gds_bonne_humeur!='non'))
			{
			    $errors[$i++]="Répondez à la question 7 du test GDS";
			}
			
			if(($this->gds_besoin_aide!='oui')&&($this->gds_besoin_aide!='non'))
			{
			    $errors[$i++]="Répondez à la question 8 du test GDS";
			}

			if(($this->gds_prefere_seul!='oui')&&($this->gds_prefere_seul!='non'))
			{
			    $errors[$i++]="Répondez à la question 9 du test GDS";
			}
			
			if(($this->gds_mauvaise_mem!='oui')&&($this->gds_mauvaise_mem!='non'))
			{
			    $errors[$i++]="Répondez à la question 10 du test GDS";
			}
			
			if(($this->gds_heureux_vivre!='oui')&&($this->gds_heureux_vivre!='non'))
			{
			    $errors[$i++]="Répondez à la question 11 du test GDS";
			}
			
			if(($this->gds_bon_rien!='oui')&&($this->gds_bon_rien!='non'))
			{
			    $errors[$i++]="Répondez à la question 12 du test GDS";
			}
			
			if(($this->gds_energie!='oui')&&($this->gds_energie!='non'))
			{
			    $errors[$i++]="Répondez à la question 13 du test GDS";
			}
			
			if(($this->gds_desespere_sit!='oui')&&($this->gds_desespere_sit!='non'))
			{
			    $errors[$i++]="Répondez à la question 14 du test GDS";
			}
			
			if(($this->gds_sit_autres_best!='oui')&&($this->gds_sit_autres_best!='non'))
			{
			    $errors[$i++]="Répondez à la question 15 du test GDS";
			}

		}

		//vérification test iadl
		if(in_array("iadl",$this->suivi_type)){
		    	if(($this->iadl_telephone != 'tout')&& ($this->iadl_telephone != 'qq_no')&&($this->iadl_telephone != 'repond')&&
				($this->iadl_telephone != 'rien')){
				    $errors[$i++]="Indiquez l'autonomie pour utiliser le téléphone";
				}

				if(($this->iadl_transport != 'tout')&&($this->iadl_transport != 'taxi_seul')&&($this->iadl_transport != 'commun_acc')&&
				  ($this->iadl_transport != 'voiture_acc')&& ($this->iadl_transport != 'rien')){
				  	$errors[$i++]="Indiquez l'autonomie dans les transports";
				}

				if(($this->iadl_med != 'tout')&&($this->iadl_med != 'prend_seul')&&($this->iadl_med != 'rien')){
				    $errors[$i++]="Indiquez l'autonomie pour prendre les médicaments";
				}

				if(($this->iadl_budget != 'tout')&&($this->iadl_budget != 'jour')&&($this->iadl_budget != 'rien')){
				    $errors[$i++]="Indiquez l'autonomie pour gérer le budget";
				}

		}


		//verfication du test horloge
		if(in_array("horl",$this->suivi_type)){
            if(($this->horloge!=10)&&($this->horloge!=9)&&($this->horloge!=8)&&($this->horloge!=7)&&
				($this->horloge!=6)&&($this->horloge!=5)&&($this->horloge!=4)&&($this->horloge!=3)&&
				($this->horloge!=2)&&($this->horloge!=1)){
					$errors[$i++]="Indiquez le résultat du test de l'horloge";
			}
		}
		

		return $errors;
	}
	
	function beforeSerialisation($account){
		$clone = clone $this;
		$clone->date = dateToMysqlDate($clone->date);
		$clone->date_rappel = dateToMysqlDate($clone->date_rappel);
		return $clone;
	}

	function afterDeserialisation($account){
		$clone = clone $this;
		$clone->date = mysqlDateTodate($clone->date);
		$clone->date_rappel = mysqlDateTodate($clone->date_rappel);
		return $clone;
	}


	function isOutdated($month =0){
		if($this->sortir_rappel!='1'){
			$elderDate = $this->date_rappel;
			if(is_null($elderDate)) return false;
			if(($elderDate == "")||($elderDate=='00/00/0000')) return false;
			$refDate = increaseDateBy($elderDate,0,0,0);
			$actualDate = increaseDateBy(date("d/m/Y"),0,$month,0);
			if(compare($refDate,$actualDate)>0) return false;
	
	
			return $refDate;
		}
		else{
			return false;
		}
	}

}
 ?>
