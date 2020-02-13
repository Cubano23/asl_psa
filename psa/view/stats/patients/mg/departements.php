<?php
// présentation 3
$depts = array();
$depts["01"] = "Ain";
$depts["02"] = "Aisne";
$depts["03"] = "Allier";
$depts["04"] = "Alpes de Haute Provence";
$depts["05"] = "Hautes Alpes";
$depts["06"] = "Alpes Maritimes";
$depts["07"] = "Ardèche";
$depts["08"] = "Ardennes";
$depts["09"] = "Ariège";
$depts["10"] = "Aube";
$depts["11"] = "Aude";
$depts["12"] = "Aveyron";
$depts["13"] = "Bouches du Rhône";
$depts["14"] = "Calvados";
$depts["15"] = "Cantal";
$depts["16"] = "Charente";
$depts["17"] = "Charente Maritime";
$depts["18"] = "Cher";
$depts["19"] = "Corrèze";
$depts["2A"] = "Corse du Sud";
$depts["2B"] = "Haute Corse";
$depts["21"] = "Côte d'Or";
$depts["22"] = "Côtes d'Armor";
$depts["23"] = "Creuse";
$depts["24"] = "Dordogne";
$depts["25"] = "Doubs";
$depts["26"] = "Drôme";
$depts["27"] = "Eure";
$depts["28"] = "Eure et Loir";
$depts["29"] = "Finistère";
$depts["30"] = "Gard";
$depts["31"] = "Haute Garonne";
$depts["32"] = "Gers";
$depts["33"] = "Gironde";
$depts["34"] = "Hérault";
$depts["35"] = "Ille et Vilaine";
$depts["36"] = "Indre";
$depts["37"] = "Indre et Loire";
$depts["38"] = "Isère";
$depts["39"] = "Jura";
$depts["40"] = "Landes";
$depts["41"] = "Loir et Cher";
$depts["42"] = "Loire";
$depts["43"] = "Haute Loire";
$depts["44"] = "Loire Atlantique";
$depts["45"] = "Loiret";
$depts["46"] = "Lot";
$depts["47"] = "Lot et Garonne";
$depts["48"] = "Lozère";
$depts["49"] = "Maine et Loire";
$depts["50"] = "Manche";
$depts["51"] = "Marne";
$depts["52"] = "Haute Marne";
$depts["53"] = "Mayenne";
$depts["54"] = "Meurthe et Moselle";
$depts["55"] = "Meuse";
$depts["56"] = "Morbihan";
$depts["57"] = "Moselle";
$depts["58"] = "Nièvre";
$depts["59"] = "Nord";
$depts["60"] = "Oise";
$depts["61"] = "Orne";
$depts["62"] = "Pas de Calais";
$depts["63"] = "Puy de Dôme";
$depts["64"] = "Pyrénées Atlantiques";
$depts["65"] = "Hautes Pyrénées";
$depts["66"] = "Pyrénées Orientales";
$depts["67"] = "Bas Rhin";
$depts["68"] = "Haut Rhin";
$depts["69"] = "Rhône";
$depts["70"] = "Haute Saône";
$depts["71"] = "Saône et Loire";
$depts["72"] = "Sarthe";
$depts["73"] = "Savoie";
$depts["74"] = "Haute Savoie";
$depts["75"] = "Paris";
$depts["76"] = "Seine Maritime";
$depts["77"] = "Seine et Marne";
$depts["78"] = "Yvelines";
$depts["79"] = "Deux Sèvres";
$depts["80"] = "Somme";
$depts["81"] = "Tarn";
$depts["82"] = "Tarn et Garonne";
$depts["83"] = "Var";
$depts["84"] = "Vaucluse";
$depts["85"] = "Vendée";
$depts["86"] = "Vienne";
$depts["87"] = "Haute Vienne";
$depts["88"] = "Vosges";
$depts["89"] = "Yonne";
$depts["90"] = "Territoire de Belfort";
$depts["91"] = "Essonne";
$depts["92"] = "Hauts de Seine";
$depts["93"] = "Seine St Denis";
$depts["94"] = "Val de Marne";
$depts["95"] = "Val d'Oise";
$depts["97"] = "DOM";
$depts["971"] = "Guadeloupe";
$depts["972"] = "Martinique";
$depts["973"] = "Guyane";
$depts["974"] = "La Réunion";




$t_regions = array();

$t_regions["Alsace"] = "67,68";
$t_regions["Aquitaine"] = "24,33,40,47,64";
$t_regions["Auvergne"] = "03,15,43,63";
$t_regions["Basse-Normandie"] = "14,50,61";
$t_regions["Bourgogne"] = "21,58,71,89";
$t_regions["Bretagne"] = "22,29,35,56";
$t_regions["Centre"] = "18,28,36,37,41,45";
$t_regions["Champagne-Ardenne"] = "08,10,51,52";
$t_regions["Corse"] = "2A,2B";
$t_regions["Franche-Comté"] = "25,39,70,90";
$t_regions["Haute-Normandie"] = "27,76";
$t_regions["Ile-de-France"] = "75,77,78,91,92,93,94,95";
$t_regions["Languedoc-Roussillon"] = "11,30,34,48,66";
$t_regions["Limousin"] = "19,23,87";
$t_regions["Lorraine"] = "54,55,57,88";
$t_regions["Midi-Pyrénées"] = "09,12,31,32,46,65,81,82";
$t_regions["Nord-Pas-de-Calais"] = "59,62";
$t_regions["Pays de la Loire"] = "44,49,53,72,85";
$t_regions["Picardie"] = "02,60,80";
$t_regions["Poitou-Charentes"] = "16,17,79,86";
$t_regions["Provence-Alpes-Côte-d\"Azur"] = "04,05,06,13,83,84";
$t_regions["Rhône-Alpes"] = "01,07,26,38,42,69,73,74";
$t_regions["DOM"] = "971,972,973,974";


$xregions = array();
$regions= array();

foreach($t_regions as $cle=>$valeur)
{
   
	$xregions = explode(",", $valeur);
	foreach($xregions as $key=>$value)
	{
		$regions[(string)$value] = (string)$cle;
	}
}

?>