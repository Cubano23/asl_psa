<?php


mysql_connect('localhost','informed','no11iugX') or
   die("Impossible de se connecter au SGBD");
mysql_select_db('informed3') or
   die("Impossible de se connecter à la base");

$req="SELECT dossier_id, dsuivi, dmaj FROM suivi_diabete";
      
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
echo mysql_num_rows($res);
while (list($id, $dsuivi, $dmaj)=mysql_fetch_row($res))
{

	$req="UPDATE suivi_diabete SET dPoids='$dsuivi', dtension='$dsuivi', dmaj='$dmaj' WHERE dossier_id='$id' and dsuivi='$dsuivi'";
$res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
}

echo "fin";
?>

