<?


mysql_connect('localhost','informed','no11iugX') or
   die("Impossible de se connecter au SGBD");
mysql_select_db('informed3') or
   die("Impossible de se connecter à la base");


$req="SELECT id, date, date_resultat, rappel, dmaj FROM hemocult";

$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

while (list($id, $date, $date_resultat, $rappel, $dmaj)=mysql_fetch_row($res)){
	$drappel=explode("-", $date_resultat);
	$annee=$drappel[0];
	$mois=$drappel[1];
	$jour=$drappel[2];
	
	$annee=$annee+$rappel;
	
	
	if($rappel==0){
		$date_rappel="NULL";
		$req2="UPDATE hemocult SET date_rappel=$date_rappel, dmaj='$dmaj' WHERE ".
			  "id='$id' and date='$date'";
	}
	else{
		$date_rappel="$annee-$mois-$jour";
		$req2="UPDATE hemocult SET date_rappel='$date_rappel', dmaj='$dmaj' WHERE ".
			  "id='$id' and date='$date'";
	}
		  // echo $req2;die;
	$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br />$req2");
} 

echo "fin maj";

?>

