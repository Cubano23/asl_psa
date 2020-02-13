<?


mysql_connect('localhost','informed','no11iugX') or
   die("Impossible de se connecter au SGBD");
mysql_select_db('informed3') or
   die("Impossible de se connecter à la base");

/*
$req="SELECT id, date, poids, dpoids, TaSys, TaDia, dtension, dcoeur, Creat, dcreat, proteinurie, dproteinurie, ".
	 "hematurie, dhematurie, glycemie, dglycemie, kaliemie, dkaliemie, dChol, HDL, dLDL, LDL, dfond, dECG, ".
	 "tabac, alcool, hta_tritherapie FROM hyper_tension";


      
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

while (list($id, $date, $poids, $dpoids, $TaSys, $TaDia, $dtension, $dcoeur, $Creat, $dCreat, $proteinurie, $dproteinurie, 
	 $hematurie, $dhematurie, $glycemie, $dglycemie, $kaliemie, $dkaliemie, $dChol, $HDL, $dLDL, $LDL, $dFond, $dECG, 
	 $tabac, $alcool, $hta_tritherapie)=mysql_fetch_row($res))
{

$req="INSERT INTO cardio_vasculaire_depart set id='$id', date='$date', HDL='$HDL', dHDL='$dChol', LDL='$LDL', dLDL='$dLDL', ".
	 "TaSys='$TaSys', TaDia='$TaDia', dTA='$dtension', Creat='$Creat', dCreat='$dCreat', kaliemie='$kaliemie', ".
	 "dkaliemie='$dkaliemie', proteinurie='$proteinurie', dproteinurie='$dproteinurie',	hematurie='$hematurie', ".
	 "dhematurie='$dhematurie', dFond='$dFond', dECG='$dECG', tabac='$tabac', poids='$poids', dpoids='$dpoids', ".
	 "alcool='$alcool', glycemie='$glycemie', dgly='$dglycemie', exam_cardio='$dcoeur', hypertenseur3='$hta_tritherapie'";

$res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

}

      */

$req="SELECT id, date FROM hyper_tension";


      
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

while (list($id, $date)=mysql_fetch_row($res)){
$req="UPDATE cardio_vasculaire_depart set HTA='oui' WHERE id='$id' AND date='$date'";

$res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
	
} 


?>

