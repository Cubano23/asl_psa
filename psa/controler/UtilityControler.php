<?php 
class UtilityControler {
	
	var $mappingTable;
	
	function UtilityControler(){
		$this->mappingTable = array(
		"URL_CONSULT" => "view/utilities/consultation.php",
		"URL_MAIN" => "view/main.php");
	}
	
	
	function start() {
			// variables inherited from ActionControler
			global $account;
			global $objects;
			global $param;			
			
			switch($param->action){
				case ACTION_CONSULT:					
					forward($this->mappingTable["URL_CONSULT"]);
					break;
					
				case ACTION_MAIN:					
					forward($this->mappingTable["URL_MAIN"]);
					break;
				
			}			
	}


	/**
	 * définition des régions pour la partie compétences infirmières
	 * @return [type] [description]
	 */
	static function getRegions(){

		$reg = array();
		$reg['Alsace'] =
		array(
			"67"=>'Bas-Rhin',
			"68"=>'Haut-Rhin'
			);
		$reg['Aquitaine'] =
		array(
			"24"=>'Dordogne',
			"33"=>'Gironde',
			"40"=>'Landes',
			"47"=>'Lot-et-Garonne',
			"64"=>'Pyrénées-Atlantiques'
			);
		$reg['Auvergne'] =
		array(
			"03"=>'Allier',
			"15"=>'Cantal',
			"43"=>'Haute-Loire',
			"63"=>'Puy-de-Dôme'
			);
		$reg['Basse-Normandie'] =
		array(
			"14"=>'Calvados',
			"50"=>'Manche',
			"61"=>'Orne'
			);
		$reg['Bourgogne'] =
		array(
			"21"=>'Côte-d\'Or',
			"58"=>'Nièvre',
			"71"=>'Saône-et-Loire',
			"89"=>'Yonne'
			);
		$reg['Bretagne'] =
		array(
			"22"=>'Côtes-d\'Armor',
			"29"=>'Finistère',
			"35"=>'Ille-et-Vilaine',
			"56"=>'Morbihan'
			);
		$reg['Centre'] =
		array(
			"18"=>'Cher',
			"28"=>'Eure-et-Loir',
			"36"=>'Indre',
			"37"=>'Indre-et-Loire',
			"41"=>'Loir-et-Cher',
			"45"=>'Loiret'
			);
		$reg['Champagne-Ardenne'] =
		array(
			"08"=>'Ardennes',
			"10"=>'Aube',
			"51"=>'Marne',
			"52"=>'Haute-Marne'
			);
		$reg['Corse'] =
		array(
			"2A" => "Corse-du-Sud",
			"2B" => "Haute-Corse",
			);
		$reg['Franche-Comté'] =
		array(
			"25" => "Doubs",
			"39" => "Jura",
			"70" => "Haute-Saône",
			"90" => "Territoire de Belfort"
			);
		$reg['Haute-Normandie'] =
		array(
			"27" => "Eure",
			"76" => "Seine-Maritime"
			);
		$reg['Ile-de-France'] =
		array(
			"75" => "Paris",
			"77" => "Seine-et-Marne",
			"78" => "Yvelines",
			"91" => "Essonne",
			"92" => "Hauts-de-Seine",
			"93" => "Seine-Saint-Denis",
			"94" => "Val-de-Marne",
			"95" => "Val-d'Oise"
			);
		$reg['Languedoc-Roussillon'] =
		array(
			"11" => "Aude",
			"30" => "Gard",
			"34" => "Hérault",
			"48" => "Lozère",
			"66" => "Pyrénées-Orientales"
			);
		$reg['Limousin'] =
		array(
			"19" => "Corrèze",
			"23" => "Creuse",
			"87" => "Haute-Vienne"
			);
		$reg['Lorraine'] =
		array(
			"54" => "Meurthe-et-Moselle",
			"55" => "Meuse",
			"57" => "Moselle",
			"88" => "Vosges"
			);
		$reg['Midi-Pyrénées'] =
		array(
			"09" => "Ariège",
			"12" => "Aveyron",
			"31" => "Haute-Garonne",
			"32" => "Gers",
			"46" => "Lot",
			"65" => "Hautes-Pyrénées",
			"81" => "Tarn",
			"82" => "Tarn-et-Garonne"
			);
		$reg['Nord-Pas-de-Calais'] =
		array(
			"59" => "Nord",
			"62" => "Pas-de-Calais"
			);
		$reg['Pays de la Loire'] =
		array(
			"44" => "Loire-Atlantique",
			"49" => "Maine-et-Loire",
			"53" => "Mayenne",
			"72" => "Sarthe",
			"85" => "Vendée"
			);
		$reg['Picardie'] =
		array(
			"02" => "Aisne",
			"60" => "Oise",
			"80" => "Somme"
			);
		$reg['Poitou-Charentes'] =
		array(
			"16" => "Charente",
			"17" => "Charente-Maritime",
			"79" => "Deux-Sèvres",
			"86" => "Vienne"
			);
		$reg['Provence-Alpes-Côte-d\'Azur'] =
		array(
			"04" => "Alpes-de-Haute-Provence",
			"05" => "Hautes-Alpes",
			"06" => "Alpes-Maritimes",
			"13" => "Bouches-du-Rhône",
			"83" => "Var",
			"84" => "Vaucluse"
			);
		$reg['Rhône-Alpes'] =
		array(
			"01" => "Ain",
			"07" => "Ardèche",
			"26" => "Drôme",
			"38" => "Isère",
			"42" => "Loire",
			"69" => "Rhône",
			"73" => "Savoie",
			"74" => "Haute-Savoie"
			); 
		$reg['Dom-Tom'] =
		array(
			"971" => "Guadeloupe",
			"972" => "Martinique",
			"973" => "Guyane",
			"974" => "Réunion",
			"976" => "Mayotte",
			);
		return $reg;
		}


		/**
		 * fonction qui permet de réorh-ganiser un tableau multidimentionnel
		 * utlisé dans les planning infirmière
		 * @param  array $array tableau multidimensionnel
		 * @param  string $cols  la colonne que l'on veux classer
		 * @return [type]        [description]
		 */
	function array_msort($array, $cols)
	{
	    $colarr = array();
	    foreach ($cols as $col => $order) {
	        $colarr[$col] = array();
	        foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
	    }
	    $eval = 'array_multisort(';
	    foreach ($cols as $col => $order) {
	        $eval .= '$colarr[\''.$col.'\'],'.$order.',';
	    }
	    $eval = substr($eval,0,-1).');';
	    eval($eval);
	    $ret = array();
	    foreach ($colarr as $col => $arr) {
	        foreach ($arr as $k => $v) {
	            $k = substr($k,1);
	            if (!isset($ret[$k])) $ret[$k] = $array[$k];
	            $ret[$k][$col] = $array[$k][$col];
	        }
	    }
	    return $ret;

	}


	public function inverseDate($date,$format,$istime=false){
	
	#echo strlen($date);
	
	if(strlen($date) > 10){
		// alors y'a la time
		$ladate = substr($date,0,10); // FR ou UK
		$time = substr($date,11,8);
	}
	else{
		$ladate = substr($date,0,10);
	}

	$ladate = str_replace("\r","",$ladate);
	$ladate = str_replace("\n","",$ladate);
	
	if($format=="fr"){
		$dateFR=implode('/',array_reverse  (explode('-',$ladate)));
		if(!empty($time)){
			$renvoi = $dateFR.' '.$time;
		}
		else{
			$renvoi = $dateFR;
		}
	
	}
	elseif($format=="us"){	
		$dateUS=implode('-',array_reverse  (explode('/',$date)));
		if(!empty($time)){
			$renvoi = $dateUS.' '.$time;
		}
		else{
			$renvoi = $dateUS;
		}
		
	
	}
	
	return $renvoi;	
	
	} 
	
	/**
	 * corrige les accents en vérifiant avant l'encodage (utilisé dans les TDB)
	 * @param  [type] $text [description]
	 * @return [type]       [description]
	 */
	function stripAccents($text){
    if(mb_detect_encoding($text)!="UTF-8"){
      $text = utf8_encode($text);
    }
    return $text;
	}




	function date_diff2($date1, $date2)  
	{ 
	 $s = $date2-$date1; 
	 $d = intval($s/86400)+1;   
	 return $d; 
	}

	/**
	 * transformer un array php en vue tableau HTML pour visualiser des résultats par exple, pratique...
	 * @param  [type]  $array [description]
	 * @param  boolean $table [description]
	 * @return [type]         [description]
	 */
	function array2Html($array, $table = true)
	{
	    $out = '';
	    foreach ($array as $key => $value) {
	        if (is_array($value)) {
	            if (!isset($tableHeader)) {
	                $tableHeader =
	                    '<th>' .
	                    implode('</th><th>', array_keys($value)) .
	                    '</th>';
	            }
	            array_keys($value);
	            $out .= '<tr>';
	            $out .= array2Html($value, false);
	            $out .= '</tr>';
	        } else {
	            $out .= "<td>$value</td>";
	        }
	    }

	    if ($table) {
	        return '<table border="1">' . $tableHeader . $out . '</table>';
	    } else {
	        return $out;
	    }
	}


	static function cropVar($var,$taille){
	$long=strlen($var);
		if ($long > $taille){
			$var=substr($var,0,$taille);
			$pos=strrpos ( $var," ");
			$var=substr($var,0,$pos);
			return($var."...");
		}
		else{
			return($var);
		}

	}

	/**
	 * vérification si une date est bien valide
	 * @param  [type] $date [description]
	 * @return [type]       [description]
	 */
	static function validDate($date){

		// on check si FR ou US, si / c'ets FR sinon US
		$cle = "/";
		if(strpos($date,$cle)){
			//date = FR
			$dateTab = explode("/",$date); // dd/mm/aaaa
			$is_date = checkdate($dateTab[1], $dateTab[0], $dateTab[2]); // mm,dd,yyyy
		}
		else{
			// la date est US format DB
			$dateTab = explode("-",$date); //aaaa-mm-dd
			$is_date = checkdate($dateTab[1], $dateTab[2], $dateTab[0]); // mm,dd,yyyy
		}

		return $is_date;
	}

}	
?>