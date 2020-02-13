<?


mysql_connect('localhost','informed','no11iugX') or
   die("Impossible de se connecter au SGBD");
mysql_select_db('informed3') or
   die("Impossible de se connecter à la base");


$req="SELECT id, date, degre_satisfaction, duree, ".
	 "points_positifs, points_ameliorations, dmaj FROM cardio_autre_consult";

$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

$nb_mauvais=0;
$nb_bon=0;
while (list($id, $date, $degre_satisfaction, $duree,
	 $points_positifs, $points_ameliorations, $dmaj )=mysql_fetch_row($res)){
$req="SELECT * from evaluation_infirmier where id='$id' and date='$date'";
$res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

if(mysql_num_rows($res2)==1){//Consult existante => on n'intègre pas mais message d'erreur
	$nb_mauvais++;
	echo "erreur consult id='$id' and date='$date'<br>";
}
else{
	$req="INSERT INTO evaluation_infirmier SET id='$id', date='$date', ".
		 "degre_satisfaction='$degre_satisfaction', duree='$duree', ".
		 "points_positifs='".addslashes(stripslashes($points_positifs))."', ".
		 "points_ameliorations='".addslashes(stripslashes($points_ameliorations))."', ".
		 "dmaj='$dmaj', type_consultation='rcva'";
	$res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
	$nb_bon++;
}
	
} 

echo "fin maj : $nb_bon consults intégrées, $nb_mauvais consult erreur";

?>

