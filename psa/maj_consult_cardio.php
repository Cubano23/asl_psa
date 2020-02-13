<?


mysql_connect('localhost','informed','no11iugX') or
   die("Impossible de se connecter au SGBD");
mysql_select_db('informed3') or
   die("Impossible de se connecter à la base");


$req="SELECT id, date, commentaire_obj_poids, commentaire_obj_alcool, commentaire_obj_tabac, ".
	 "commentaire_obj_tension, brochure_sel1, brochure_sel2, commentaire_sel, brochure_alcool1, ".
	 "brochure_alcool2, commentaire_alcool, brochure_activite1, brochure_activite2, ".
	 "commentaire_activite, brochure_tabac1, brochure_tabac2, commentaire_tabac, brochure_poids1, ".
	 "brochure_poids2, commentaire_poids, brochure_alim1, brochure_alim2, commentaire_alim, ".
	 "brochure_cafe1, brochure_cafe2, commentaire_cafe, degre_satisfaction, duree, ".
	 "points_positifs, points_ameliorations, dmaj FROM cardio_premiere_consult";

$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

while (list($id, $date, $commentaire_obj_poids, $commentaire_obj_alcool, $commentaire_obj_tabac,
	 $commentaire_obj_tension, $brochure_sel1, $brochure_sel2, $commentaire_sel, $brochure_alcool1,
	 $brochure_alcool2, $commentaire_alcool, $brochure_activite1, $brochure_activite2,
	 $commentaire_activite, $brochure_tabac1, $brochure_tabac2, $commentaire_tabac, $brochure_poids1,
	 $brochure_poids2, $commentaire_poids, $brochure_alim1, $brochure_alim2, $commentaire_alim,
	 $brochure_cafe1, $brochure_cafe2, $commentaire_cafe, $degre_satisfaction, $duree,
	 $points_positifs, $points_ameliorations, $dmaj )=mysql_fetch_row($res)){
$req="INSERT INTO cardio_autre_consult SET id='$id', date='$date', obj_poids='".
	 addslashes(stripslashes($commentaire_obj_poids))."', obj_alcool='".
	 addslashes(stripslashes($commentaire_obj_alcool))."', obj_tabac='".
	 addslashes(stripslashes($commentaire_obj_tabac))."', obj_tension='".
	 addslashes(stripslashes($commentaire_obj_tension))."', brochure_sel1='$brochure_sel1', ".
	 "brochure_sel2='$brochure_sel2', commentaire_sel='".addslashes(stripslashes($commentaire_sel))."', ".
	 "brochure_alcool1='$brochure_alcool1', brochure_alcool2='$brochure_alcool2', ".
	 "commentaire_alcool='".addslashes(stripslashes($commentaire_alcool))."', ".
	 "brochure_activite1='$brochure_activite1', brochure_activite2='$brochure_activite2', ".
	 "commentaire_activite='".addslashes(stripslashes($commentaire_activite))."', ".
	 "brochure_tabac1='$brochure_tabac1', brochure_tabac2='$brochure_tabac2', ".
	 "commentaire_tabac='".addslashes(stripslashes($commentaire_tabac))."', ".
	 "brochure_poids1='$brochure_poids1', brochure_poids2='$brochure_poids2', ".
	 "commentaire_poids='".addslashes(stripslashes($commentaire_poids))."', ".
	 "brochure_alim1='$brochure_alim1', brochure_alim2='$brochure_alim2', ".
	 "commentaire_alim='".addslashes(stripslashes($commentaire_alim))."', ".
	 "brochure_cafe1='$brochure_cafe1', brochure_cafe2='$brochure_cafe2', ".
	 "commentaire_cafe='".addslashes(stripslashes($commentaire_cafe))."', ".
	 "degre_satisfaction='$degre_satisfaction', duree='$duree', ".
	 "points_positifs='".addslashes(stripslashes($points_positifs))."', ".
	 "points_ameliorations='".addslashes(stripslashes($points_ameliorations))."', ".
	 "dmaj='$dmaj'";

$res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
	
} 

echo "fin maj";

?>

