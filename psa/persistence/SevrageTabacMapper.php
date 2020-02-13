<?php 
	require_once("SelfManagedMapper.php");
	#require_once("bean/SevrageTabac.php");
	
	class SevrageTabacMapper extends SelfManagedMapper{
	
		function getForeignKey(){
			return "numero";
		}
	
		function getKeysMap(){
			return array("id"=>"id","date"=>"date");
		}
		
		function getLedgerName(){
			return 'SevrageTabacMapper';
		}

		function getTableName(){
			return "sevrage_tabac";
		}
		

		/**
		 * insertion des infos sevrage dans la table sevrage_tabac
		 * @param  [type] $sevrage [description]
		 * @return [type]          [description]
		 */
		function createSevrageTabac($sevrage){

			$query =  "INSERT INTO sevrage_tabac set
				numero = '$sevrage->numero',
				date = '$sevrage->date',
				tabac = '$sevrage->tabac',
				nbrtabac = '$sevrage->nbrtabac',
				type_tabac = '$sevrage->type_tabac',
				ddebut = '$sevrage->ddebut',
				darret ='$sevrage->darret',
				spirometrie_date ='$sevrage->spirometrie_date',
				spirometrie_CVF ='$sevrage->spirometrie_CVF',
				resultat1 = '$sevrage->resultat1',
				spirometrie_VEMS = '$sevrage->spirometrie_VEMS',
				spirometrie_DEP = '$sevrage->spirometrie_DEP',
				spirometrie_status = '$sevrage->spirometrie_status',
				spirometrie_rapport_VEMS_CVF = '$sevrage->spirometrie_rapport_VEMS_CVF',
				dco_test = '$sevrage->dco_test',
				co_ppm = '$sevrage->co_ppm',
				fagerstrom = '$sevrage->fagerstrom',
				horn_stimulation = '$sevrage->horn_stimulation',
				horn_plaisir = '$sevrage->horn_plaisir',
				horn_relaxation = '$sevrage->horn_relaxation',
				horn_anxiete = '$sevrage->horn_anxiete',
				horn_besoin = '$sevrage->horn_besoin',
				horn_habitude = '$sevrage->horn_habitude',
				had_anxiete = '$sevrage->had_anxiete',
				had_depression = '$sevrage->had_depression',
				echelle_analogique = '$sevrage->echelle_analogique',
				echelle_confiance = '$sevrage->echelle_confiance',
				stade_motivationnel = '$sevrage->stade_motivationnel',
				poids = '$sevrage->poids',
				dpoids = '$sevrage->dpoids',
				activite = '$sevrage->activite',
				alcool = '$sevrage->alcool',
				aspects_limitants = '$sevrage->aspects_limitants',
				aspects_facilitants = '$sevrage->aspects_facilitants',
				objectifs_patient = '$sevrage->objectifs_patient',
				dmaj = now()

			";
			#echo $query;
			$res = mysql_query($query);
			$id = mysql_insert_id();
			// print_r($row);die;
			return $id;
		}

		/**
		 * mise à jour des infos sevrage tabagique dans la table
		 * @param  [type] $sevrage [description]
		 * @return [type]          [description]
		 */
		function updateSevrageTabac($sevrage){

			$query =  "UPDATE sevrage_tabac set
				tabac = '$sevrage->tabac',
				nbrtabac = '$sevrage->nbrtabac',
				type_tabac = '$sevrage->type_tabac',
				ddebut = '$sevrage->ddebut',
				darret ='$sevrage->darret',
				spirometrie_date ='$sevrage->spirometrie_date',
				spirometrie_CVF ='$sevrage->spirometrie_CVF',
				resultat1 = '$sevrage->resultat1',
				spirometrie_VEMS = '$sevrage->spirometrie_VEMS',
				spirometrie_DEP = '$sevrage->spirometrie_DEP',
				spirometrie_status = '$sevrage->spirometrie_status',
				spirometrie_rapport_VEMS_CVF = '$sevrage->spirometrie_rapport_VEMS_CVF',
				dco_test = '$sevrage->dco_test',
				co_ppm = '$sevrage->co_ppm',
				fagerstrom = '$sevrage->fagerstrom',
				horn_stimulation = '$sevrage->horn_stimulation',
				horn_plaisir = '$sevrage->horn_plaisir',
				horn_relaxation = '$sevrage->horn_relaxation',
				horn_anxiete = '$sevrage->horn_anxiete',
				horn_besoin = '$sevrage->horn_besoin',
				horn_habitude = '$sevrage->horn_habitude',
				had_anxiete = '$sevrage->had_anxiete',
				had_depression = '$sevrage->had_depression',
				echelle_analogique = '$sevrage->echelle_analogique',
				echelle_confiance = '$sevrage->echelle_confiance',
				stade_motivationnel = '$sevrage->stade_motivationnel',
				poids = '$sevrage->poids',
				dpoids = '$sevrage->dpoids',
				activite = '$sevrage->activite',
				alcool = '$sevrage->alcool',
				aspects_limitants = '$sevrage->aspects_limitants',
				aspects_facilitants = '$sevrage->aspects_facilitants',
				objectifs_patient = '$sevrage->objectifs_patient',
				dmaj = now()
				where id='$sevrage->id' LIMIT 1
			";
			#echo $query;
			$res = mysql_query($query);
			

		}

		/**
		 * récupération d'un sevrage avec l'id
		 * @return [type] [description]
		 */
		function getObject(){
			return new SevrageTabac();
		}
		
		/**
		 * vérification si un sevrage existe avec une date de suivi
		 * @param  [type] $date [description]
		 * @return [type]       [description]
		 */
		function sevrageExist($date,$numero){

			$query = "SELECT * from sevrage_tabac where date='$date' and numero='$numero' ";
			$res = mysql_query($query);
			$row = mysql_fetch_object($res);
			#echo $query;
			return $row;
		}

        /**
         * suppression d'une sevrage une date de suivi
         * @param  [type] $date [description]
         * @return [type]       [description]
         */
        function deleteSevrage($id)
		{
            $query = "DELETE FROM sevrage_tabac where id = $id";
            $res = mysql_query($query);
            #echo $query;
            return $res;
        }

		/**
		 * liste des sevrage d'un dossier en particulier
		 * @param  [type] $numero [description]
		 * @return [type]         [description]
		 */
		function listSevragesByDossier($numero){
			
			$query = "SELECT * from sevrage_tabac where numero='$numero' order by date";
			$res = mysql_query($query);
			while ($row = mysql_fetch_object($res)){
				$rows[] = $row;
			}
			return $rows;
			echo $query;

		}


	}
?>
