<?


mysql_connect('localhost','informed','no11iugX') or
   die("Impossible de se connecter au SGBD");
mysql_select_db('informed3') or
   die("Impossible de se connecter à la base");

$req="SELECT id, date_format(dmaj, '%Y-%m-%d') as dcreat, dmaj FROM dossier";
      
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
echo mysql_num_rows($res);
while (list($id, $dcreat, $dmaj)=mysql_fetch_row($res))
{

	$req="UPDATE dossier SET dcreat='$dcreat', dmaj='$dmaj' WHERE id='$id'";
$res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
echo $req."<br>";
}

      

?>

