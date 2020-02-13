<?php


$serveur = 'localhost';

$idDB = 'root';
$mdpDB = 'root';
$DB = 'isas';

$idDB = 'informed';
$mdpDB = 'no11iugX';
$DB = 'informed3';



mysql_connect($serveur,$idDB,$mdpDB) or
   die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
   die("Impossible de se connecter &agrave; la base");


$cpt = 0;

$req_rcva = "SELECT id, date, degre_satisfaction, duree, consult_domicile, consult_tel, consult_collective, points_positifs, points_ameliorations, type_consultation, ecg_seul, ecg, monofil, exapied, hba, tension, spirometre_seul, spirometre, t_cognitif, autre, prec_autre, aspects_limitant, aspects_facilitant, objectifs_patient, dmaj FROM cardio_autre_consult";
$res_rcva = mysql_query($req_rcva);
while($tab_rcva = mysql_fetch_array($res_rcva))
{
	//echo "<br>#RCVA#".$tab_rcva['id'].' - '.$tab_rcva['date'].' => '.$tab_rcva['type_consultation'];
	$req_eval_inf = "SELECT * FROM evaluation_infirmier WHERE date='".$tab_rcva['date']."' AND id='".$tab_rcva['id']."' AND type_consultation='".$tab_rcva['type_consultation']."'";
	$res_eval_inf = mysql_query($req_eval_inf);
	while($tab_eval_inf = mysql_fetch_array($res_eval_inf))
	{
		if($tab_eval_inf['dmaj'] > $tab_rcva['dmaj'])
		{
			if($tab_eval_inf['duree'] != $tab_rcva['duree'])
			{
				echo "<br>#EVAL#".$tab_eval_inf['id'].' - '.$tab_eval_inf['date'].' => '.$tab_eval_inf['type_consultation'].' / maj: '.$tab_eval_inf['dmaj'].' | '.$tab_rcva['dmaj'].' || '.$tab_eval_inf['duree'].' au lieu de '.$tab_rcva['duree'];
				// $req_maj = "UPDATE xx SET duree='', dmaj=NOW() WHERE date='".$tab_rcva['date']."' AND id='".$tab_rcva['id']."' AND type_consultation='".$tab_rcva['type_consultation']."'";
				$cpt++;
			}
			
		}
		else if($tab_eval_inf['dmaj'] < $tab_rcva['dmaj'])
		{
			if($tab_eval_inf['duree'] != $tab_rcva['duree'])
			{
				echo "<br>#RCVA#".$tab_rcva['id'].' - '.$tab_rcva['date'].' => '.$tab_rcva['type_consultation'].' / maj: '.$tab_rcva['dmaj'].' | '.$tab_eval_inf['dmaj'].' || '.$tab_rcva['duree'].' au lieu de '.$tab_eval_inf['duree'];
				//$req_maj = "UPDATE xx SET duree='', dmaj=NOW() WHERE date='".$tab_rcva['date']."' AND id='".$tab_rcva['id']."' AND type_consultation='".$tab_rcva['type_consultation']."'";
				$cpt++;
			}
		}
		else
		{
			if($tab_eval_inf['duree'] != $tab_rcva['duree'])
				echo "<br>#### dmaj egale : ".$tab_rcva['duree'].' | '.$tab_eval_inf['duree'];
// echo "<br />#";
// echo "<br />".$tab_rcva['id'].' - '.$tab_rcva['date']./*' - '.$tab_rcva['degre_satisfaction'].*/' - '.$tab_rcva['duree'].' - '.$tab_rcva['consult_domicile'].' - '.$tab_rcva['consult_tel'].' - '.$tab_rcva['consult_collective'].' - './*$tab_rcva['points_positifs'].' - '.$tab_rcva['points_ameliorations'].' - '.*/$tab_rcva['type_consultation'].' - '.$tab_rcva['ecg_seul'].' - '.$tab_rcva['ecg'].' - '.$tab_rcva['monofil'].' - '.$tab_rcva['exapied'].' - '.$tab_rcva['hba'].' - '.$tab_rcva['tension'].' - '.$tab_rcva['spirometre_seul'].' - '.$tab_rcva['spirometre'].' - '.$tab_rcva['t_cognitif'].' - '.$tab_rcva['autre'].' - '.$tab_rcva['prec_autre']/*.' - '.$tab_rcva['aspects_limitant'].' - '.$tab_rcva['aspects_facilitant'].' - '.$tab_rcva['objectifs_patient']*/.' - '.$tab_rcva['dmaj'];
// echo "<br />".$tab_eval_inf['id'].' - '.$tab_eval_inf['date']./*' - '.$tab_eval_inf['degre_satisfaction'].*/' - '.$tab_eval_inf['duree'].' - '.$tab_eval_inf['consult_domicile'].' - '.$tab_eval_inf['consult_tel'].' - '.$tab_eval_inf['consult_collective'].' - './*$tab_eval_inf['points_positifs'].' - '.$tab_eval_inf['points_ameliorations'].' - '.*/$tab_eval_inf['type_consultation'].' - '.$tab_eval_inf['ecg_seul'].' - '.$tab_eval_inf['ecg'].' - '.$tab_eval_inf['monofil'].' - '.$tab_eval_inf['exapied'].' - '.$tab_eval_inf['hba'].' - '.$tab_eval_inf['tension'].' - '.$tab_eval_inf['spirometre_seul'].' - '.$tab_eval_inf['spirometre'].' - '.$tab_eval_inf['t_cognitif'].' - '.$tab_eval_inf['autre'].' - '.$tab_eval_inf['prec_autre']/*.' - '.$tab_eval_inf['aspects_limitant'].' - '.$tab_eval_inf['aspects_facilitant'].' - '.$tab_eval_inf['objectifs_patient']*/.' - '.$tab_eval_inf['dmaj'];
// echo "<br />#";
		}
		
	}
}
echo '<hr />'.$cpt.'<hr />';
// for($i = 0; $i < sizeof($aEqual['rcva']); $i++)
// {
// 	echo "<br />".$aEqual['rcva']['id'].' - '.$aEqual['rcva']['date'].' - '.$aEqual['rcva']['degre_satisfaction'].' - '.$aEqual['rcva']['duree'].' - '.$aEqual['rcva']['consult_domicile'].' - '.$aEqual['rcva']['consult_tel'].' - '.$aEqual['rcva']['consult_collective'].' - '.$aEqual['rcva']['points_positifs'].' - '.$aEqual['rcva']['points_ameliorations'].' - '.$aEqual['rcva']['type_consultation'].' - '.$aEqual['rcva']['ecg_seul'].' - '.$aEqual['rcva']['ecg'].' - '.$aEqual['rcva']['monofil'].' - '.$aEqual['rcva']['exapied'].' - '.$aEqual['rcva']['hba'].' - '.$aEqual['rcva']['tension'].' - '.$aEqual['rcva']['spirometre_seul'].' - '.$aEqual['rcva']['spirometre'].' - '.$aEqual['rcva']['t_cognitif'].' - '.$aEqual['rcva']['autre'].' - '.$aEqual['rcva']['prec_autre'].' - '.$aEqual['rcva']['aspects_limitant'].' - '.$aEqual['rcva']['aspects_facilitant'].' - '.$aEqual['rcva']['objectifs_patient'].' - '.$aEqual['rcva']['dmaj'];
// 	echo "<br />".$aEqual['eval']['id'].' - '.$aEqual['eval']['date'].' - '.$aEqual['eval']['degre_satisfaction'].' - '.$aEqual['eval']['duree'].' - '.$aEqual['eval']['consult_domicile'].' - '.$aEqual['eval']['consult_tel'].' - '.$aEqual['eval']['consult_collective'].' - '.$aEqual['eval']['points_positifs'].' - '.$aEqual['eval']['points_ameliorations'].' - '.$aEqual['eval']['type_consultation'].' - '.$aEqual['eval']['ecg_seul'].' - '.$aEqual['eval']['ecg'].' - '.$aEqual['eval']['monofil'].' - '.$aEqual['eval']['exapied'].' - '.$aEqual['eval']['hba'].' - '.$aEqual['eval']['tension'].' - '.$aEqual['eval']['spirometre_seul'].' - '.$aEqual['eval']['spirometre'].' - '.$aEqual['eval']['t_cognitif'].' - '.$aEqual['eval']['autre'].' - '.$aEqual['eval']['prec_autre'].' - '.$aEqual['eval']['aspects_limitant'].' - '.$aEqual['eval']['aspects_facilitant'].' - '.$aEqual['eval']['objectifs_patient'].' - '.$aEqual['eval']['dmaj'];
// 	echo '<br />';
// }


mysql_close();

?>