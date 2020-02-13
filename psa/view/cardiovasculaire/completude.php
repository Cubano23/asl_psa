<?php


# boucle principale
do {
   $repete=false;

	# fenêtre glissante:
	if (isset($_GET['mois']) && isset($_GET['annee']))
	{
	    etape_2($repete);
	    exit;
	}
	
   # étape 1 : identification du patient et de la date
   if (!isset($_POST['etape'])) {
       etape_1($repete);
   	   exit;
   }

   if (isset($_POST['etape'])) {
      switch($_POST['etape']) {
      
      case 1:
        etape_1($repete);
        break;

      # étape 2  : saisie des détails
      case 2:
         etape_2($repete);
		 break;

      # étape 3  : validation des données et màj base
	  case 3:
         etape_3($repete);
		 break;
	  }
   }
} while($repete);

# fin de traitement principal


function etape_1(&$repete) {
global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_diab;

$nb_dossiers["tot"]=$nb_dossiers["cab"]=0;

$req="SELECT dossier.id from cardio_vasculaire_depart, dossier ".
	 "where dossier.id=cardio_vasculaire_depart.id and cabinet='".
	 $_SESSION["account"]->cabinet."' and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

$nb_dossiers["cab"]=mysql_num_rows($res);

$req="SELECT dossier.id from cardio_vasculaire_depart, dossier, account ".
	 "where dossier.id=cardio_vasculaire_depart.id and ".
	 "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
	 "and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

$nb_dossiers["tot"]=mysql_num_rows($res);

$date1an=date("Y");
$date1an--;
$date1an=$date1an."-".date("m")."-".date("d");

$date3ans=date("Y");
$date3ans=$date3ans-3;
$date3ans=$date3ans."-".date("m")."-".date("d");

echo "<table border='1'><tr><td></td><td>Taux moyen Asalée</td><td>Taux moyen Cabinet</td></tr>";

echo "<tr><td width='150'>Antécédents familiaux du premier degré <sup>1</sup></td>";

$req="SELECT dossier.id from cardio_vasculaire_depart, dossier, account ".
	 "where dossier.id=cardio_vasculaire_depart.id and ".
	 "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
	 "and (antecedants='oui' or antecedants='non') and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["tot"]*100)." %</td>";
$req="SELECT dossier.id ".
	 "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
	 "and (antecedants='oui' or antecedants='non') and actif='oui' and cabinet='".$_SESSION["account"]->cabinet."'".
	 "group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["cab"]*100)." %</td></Tr>";

echo "<tr><td>Tabagisme <sup>2</sup></td>";

$req="SELECT dossier.id from cardio_vasculaire_depart, dossier, account ".
	 "where dossier.id=cardio_vasculaire_depart.id and ".
	 "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
	 "and (tabac='oui' or tabac='non') and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["tot"]*100)." %</td>";
$req="SELECT dossier.id ".
	 "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
	 "and (tabac='oui' or tabac='non') and actif='oui' and cabinet='".$_SESSION["account"]->cabinet."'".
	 "group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["cab"]*100)." %</td></Tr>";

echo "<tr><td>Poids <sup>3</sup></td>";

$req="SELECT dossier.id from cardio_vasculaire_depart, dossier, account ".
	 "where dossier.id=cardio_vasculaire_depart.id and ".
	 "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
	 "and dpoids>='$date1an' and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["tot"]*100)." %</td>";
$req="SELECT dossier.id ".
	 "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
	 "and dpoids>='$date1an' and cabinet='".$_SESSION["account"]->cabinet."'".
	 " and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["cab"]*100)." %</td></Tr>";

echo "<tr><td>Alcool <sup>4</sup></td>";

$req="SELECT dossier.id from cardio_vasculaire_depart, dossier, account ".
	 "where dossier.id=cardio_vasculaire_depart.id and ".
	 "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
	 "and (alcool='oui' or alcool='non') and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["tot"]*100)." %</td>";
$req="SELECT dossier.id ".
	 "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
	 "and (alcool='oui' or alcool='non') and cabinet='".$_SESSION["account"]->cabinet."'".
	 " and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["cab"]*100)." %</td></Tr>";

echo "<tr><td>Cholestérol total <sup>5</sup></td>";

$req="SELECT dossier.id from cardio_vasculaire_depart, dossier, account ".
	 "where dossier.id=cardio_vasculaire_depart.id and ".
	 "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
	 "and dChol>='$date1an' and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["tot"]*100)." %</td>";
$req="SELECT dossier.id ".
	 "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
	 "and dChol>='$date1an' and cabinet='".$_SESSION["account"]->cabinet."'".
	 " and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["cab"]*100)." %</td></Tr>";

echo "<tr><td>HDL <sup>6</sup></td>";

$req="SELECT dossier.id from cardio_vasculaire_depart, dossier, account ".
	 "where dossier.id=cardio_vasculaire_depart.id and ".
	 "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
	 "and dHDL>='$date1an' and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["tot"]*100)." %</td>";
$req="SELECT dossier.id ".
	 "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
	 "and dHDL>='$date1an' and cabinet='".$_SESSION["account"]->cabinet."'".
	 " and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["cab"]*100)." %</td></Tr>";

echo "<tr><td>LDL <sup>7</sup></td>";

$req="SELECT dossier.id from cardio_vasculaire_depart, dossier, account ".
	 "where dossier.id=cardio_vasculaire_depart.id and ".
	 "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
	 "and dLDL>='$date1an' and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["tot"]*100)." %</td>";
$req="SELECT dossier.id ".
	 "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
	 "and dLDL>='$date1an' and cabinet='".$_SESSION["account"]->cabinet."'".
	 " and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["cab"]*100)." %</td></Tr>";

echo "<tr><td>Triglycérides <sup>8</sup></td>";

$req="SELECT dossier.id from cardio_vasculaire_depart, dossier, account ".
	 "where dossier.id=cardio_vasculaire_depart.id and ".
	 "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
	 "and dtriglycerides>='$date1an' and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["tot"]*100)." %</td>";
$req="SELECT dossier.id ".
	 "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
	 "and dtriglycerides>='$date1an' and cabinet='".$_SESSION["account"]->cabinet."'".
	 " and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["cab"]*100)." %</td></Tr>";

echo "<tr><td>Glycémie <sup>9</sup></td>";

$req="SELECT dossier.id from cardio_vasculaire_depart, dossier, account ".
	 "where dossier.id=cardio_vasculaire_depart.id and ".
	 "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
	 "and dgly>='$date1an' and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["tot"]*100)." %</td>";
$req="SELECT dossier.id ".
	 "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
	 "and dgly>='$date1an' and cabinet='".$_SESSION["account"]->cabinet."'".
	 " and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["cab"]*100)." %</td></Tr>";

echo "<tr><td>Créatinine <sup>10</sup></td>";

$req="SELECT dossier.id from cardio_vasculaire_depart, dossier, account ".
	 "where dossier.id=cardio_vasculaire_depart.id and ".
	 "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
	 "and cardio_vasculaire_depart.dCreat>='$date1an' and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["tot"]*100)." %</td>";
$req="SELECT dossier.id ".
	 "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
	 "and cardio_vasculaire_depart.dCreat>='$date1an' and cabinet='".$_SESSION["account"]->cabinet."'".
	 " and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["cab"]*100)." %</td></Tr>";

echo "<tr><td>Kaliémie <sup>11</sup></td>";

$req="SELECT dossier.id from cardio_vasculaire_depart, dossier, account ".
	 "where dossier.id=cardio_vasculaire_depart.id and ".
	 "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
	 "and dkaliemie>='$date1an' and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["tot"]*100)." %</td>";
$req="SELECT dossier.id ".
	 "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
	 "and dkaliemie>='$date1an' and cabinet='".$_SESSION["account"]->cabinet."'".
	 " and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["cab"]*100)." %</td></Tr>";

echo "<tr><td>Protéinurie <sup>12</sup></td>";

$req="SELECT dossier.id from cardio_vasculaire_depart, dossier, account ".
	 "where dossier.id=cardio_vasculaire_depart.id and ".
	 "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
	 "and dproteinurie>='$date1an' and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["tot"]*100)." %</td>";
$req="SELECT dossier.id ".
	 "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
	 "and dproteinurie>='$date1an' and cabinet='".$_SESSION["account"]->cabinet."'".
	 " and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["cab"]*100)." %</td></Tr>";

echo "<tr><td>Fond d'oeil <sup>13</sup></td>";

$req="SELECT dossier.id from cardio_vasculaire_depart, dossier, account ".
	 "where dossier.id=cardio_vasculaire_depart.id and ".
	 "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
	 "and dfond>='$date3ans' and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["tot"]*100)." %</td>";
$req="SELECT dossier.id ".
	 "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
	 "and dfond>='$date3ans' and cabinet='".$_SESSION["account"]->cabinet."'".
	 " and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["cab"]*100)." %</td></Tr>";

echo "<tr><td>Fréquence cardiaque <sup>14</sup></td>";

$req="SELECT dossier.id from cardio_vasculaire_depart, dossier, account ".
	 "where dossier.id=cardio_vasculaire_depart.id and ".
	 "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
	 "and dpouls>='$date1an' and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["tot"]*100)." %</td>";
$req="SELECT dossier.id ".
	 "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
	 "and dpouls>='$date1an' and cabinet='".$_SESSION["account"]->cabinet."'".
	 " and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["cab"]*100)." %</td></Tr>";

echo "<tr><td>Tension <sup>15</sup></td>";

$req="SELECT dossier.id from cardio_vasculaire_depart, dossier, account ".
	 "where dossier.id=cardio_vasculaire_depart.id and ".
	 "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
	 "and dTA>='$date1an' and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["tot"]*100)." %</td>";
$req="SELECT dossier.id ".
	 "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
	 "and dTA>='$date1an' and cabinet='".$_SESSION["account"]->cabinet."'".
	 " and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["cab"]*100)." %</td></Tr>";

echo "<tr><td>Echocardiogramme Hypertrophie Ventriculaire Gauche <sup>16</sup></td>";

$req="SELECT dossier.id from cardio_vasculaire_depart, dossier, account ".
	 "where dossier.id=cardio_vasculaire_depart.id and ".
	 "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
	 "and (HVG='oui' or HVG='non') and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["tot"]*100)." %</td>";
$req="SELECT dossier.id ".
	 "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
	 "and (HVG='oui' or HVG='non') and cabinet='".$_SESSION["account"]->cabinet."'".
	 " and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["cab"]*100)." %</td></Tr>";

echo "<tr><td>ECG <sup>17</sup></td>";

$req="SELECT dossier.id from cardio_vasculaire_depart, dossier, account ".
	 "where dossier.id=cardio_vasculaire_depart.id and ".
	 "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
	 "and dECG>='$date3ans' and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["tot"]*100)." %</td>";
$req="SELECT dossier.id ".
	 "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
	 "and dECG>='$date3ans' and cabinet='".$_SESSION["account"]->cabinet."'".
	 " and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["cab"]*100)." %</td></Tr>";

echo "<tr><td>A défaut Surcharge ventriculaire gauche <sup>18</sup></td>";

$req="SELECT dossier.id from cardio_vasculaire_depart, dossier, account ".
	 "where dossier.id=cardio_vasculaire_depart.id and ".
	 "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
	 "and (surcharge_ventricule='oui' or surcharge_ventricule='non')  and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["tot"]*100)." %</td>";
$req="SELECT dossier.id ".
	 "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
	 "and (surcharge_ventricule='oui' or surcharge_ventricule='non') and cabinet='".$_SESSION["account"]->cabinet."'".
	 " and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["cab"]*100)." %</td></Tr>";

echo "<tr><td>Sokolov <sup>19</sup></td>";

$req="SELECT dossier.id from cardio_vasculaire_depart, dossier, account ".
	 "where dossier.id=cardio_vasculaire_depart.id and ".
	 "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
	 "and dsokolov>='$date1an' and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["tot"]*100)." %</td>";
$req="SELECT dossier.id ".
	 "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
	 "and dsokolov>='$date1an' and cabinet='".$_SESSION["account"]->cabinet."'".
	 " and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["cab"]*100)." %</td></Tr>";

echo "<tr><td>Examen cardio-vasculaire <sup>20</sup></td>";

$req="SELECT dossier.id from cardio_vasculaire_depart, dossier, account ".
	 "where dossier.id=cardio_vasculaire_depart.id and ".
	 "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
	 "and exam_cardio>='$date1an' and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["tot"]*100)." %</td>";
$req="SELECT dossier.id ".
	 "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
	 "and exam_cardio>='$date1an' and cabinet='".$_SESSION["account"]->cabinet."'".
	 " and actif='oui' group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

echo "<td align='right'>".round(mysql_num_rows($res)/$nb_dossiers["cab"]*100)." %</td></Tr>";

echo "</table><br><br>";
echo "<sup>1</sup> Taux de patients pour lesquels les antécédents familiaux ont été renseignés à la date du jour<br>".
	 "<sup>2</sup> Taux de patients pour lesquels le tabagisme a été renseigné à la date du jour<br>".
	 "<sup>3</sup> Taux de patients pour lesquels le poids date de moins d'un an<br>".
	 "<sup>4</sup> Taux de patients pour lesquels la consommation d'alcool a été renseignée<br>".
	 "<sup>5</sup> Taux de patients pour lesquels le cholestérol total date de moins d'un an<br>".
	 "<sup>6</sup> Taux de patients pour lesquels le HDL date de moins d'un an<br>".
	 "<sup>7</sup> Taux de patients pour lesquels le LDL date de moins d'un an<br>".
	 "<sup>8</sup> Taux de patients pour lesquels les triglycérides datent de moins d'un an<br>".
	 "<sup>9</sup> Taux de patients pour lesquels la glycémie date de moins d'un an<br>".
	 "<sup>10</sup> Taux de patients pour lesquels la créatinine date de moins d'un an<br>".
	 "<sup>11</sup> Taux de patients pour lesquels la kaliémie date de moins d'un an<br>".
	 "<sup>12</sup> Taux de patients pour lesquels la protéinurie date de moins d'un an<br>".
	 "<sup>13</sup> Taux de patients pour lesquels le fond d'oeil date de moins de 3 ans<br>".
	 "<sup>14</sup> Taux de patients pour lesquels la fréquence cardiaque date de moins d'un an<br>".
	 "<sup>15</sup> Taux de patients pour lesquels la tension artérielle date de moins d'un an<br>".
	 "<sup>16</sup> Taux de patients pour lesquels l'échocardiogramme hypertrophie ventriculaire gauche a été renseigné<br>".
	 "<sup>17</sup> Taux de patients pour lesquels l'ECG date de moins de 3 ans<br>".
	 "<sup>18</sup> Taux de patients pour lesquels la surcharge ventriculaire gauche a été renseignée<br>".
	 "<sup>19</sup> Taux de patients pour lesquels le sokolov date de moins d'un an<br>".
	 "<sup>20</sup> Taux de patients pour lesquels l'examen cardio-vasculaire date de moins d'un an";

die;
$annee0=2004;
$mois0=3;

$annee=date('Y');
$mois=date('m');

$mois--;


if($mois<3)
{
	$annee--;
	$mois=12;
}
elseif(($mois>=3)&&($mois<6))
{
	$mois=3;
}
elseif(($mois>=6)&&($mois<9))
{
	$mois=6;
}
elseif(($mois>=9)&&($mois<12))
{
	$mois=9;
}

$jour[3]=$jour[12]=31;
$jour[6]=$jour[9]=30;

while(($annee>$annee0)||(($annee==$annee0)&&($mois>=$mois0)))
{
	    if($mois<10)
	    {
	        $date=$annee.'-0'.$mois.'-'.$jour[$mois];
	    }
	    else
	    {
	        $date=$annee.'-'.$mois.'-'.$jour[$mois];
	    }
		tableau($date, $regions);

		$mois=$mois-3;

		if($mois<=0)
		{
		    $mois=$mois+12;
		    $annee--;
		}
}
}

function tableau($date, $regions){
global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_diab;


$mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
			'08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

$tab_date=split('-', $date);

echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";



$req="SELECT cabinet, total_diab2, nom_cab, region ".
		 "FROM account ".
		 "WHERE region!='' and infirmiere!='' ".
		 "GROUP BY nom_cab ".
		 "ORDER BY nom_cab ";

$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

$reg=array();
while(list($cab, $total_diab2, $ville, $region) = mysql_fetch_row($res)) {

	$t_diab[$cab]=0;

	$tville[$cab]=$ville;
	
	$regions[$cab]=$region;
	$nb_dossiers[$cab]=0;
	$rcva[$cab]=0;
	$nb_dossiers[$region]=0;
	$rcva[$region]=0;
	 
	if(!in_array($region, $reg)){
		$reg[]=$region;
	}
}

$rcva["tot"]=$nb_dossiers["tot"]=0;
	
	$date1an=$tab_date[0];
	$date1an--;
	$date1an=$date1an."-".$tab_date[1]."-".$tab_date[2];

$req="SELECT dossier.id, cabinet, sexe, dnaiss, max(dTA), max(dChol), max(dHDL) ".
	 "FROM cardio_vasculaire_depart, dossier WHERE dossier.id=cardio_vasculaire_depart.id ".
	 "and dTA>='$date1an' and dChol>='$date1an' and dHDL>='$date1an' ".
	 "and dTA<='$date' and dChol<='$date' and dHDL<='$date' ".
	 "group by dossier.id";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

while(list($id, $cabinet, $sexe, $dnaiss, $dTA, $dChol, $dHDL)=mysql_fetch_row($res)){
	if(isset($tville[$cabinet])){
		$tension=$choltot=$hdl="";
		if(($dTA>'0000-00-00')&&($dChol>'0000-00-00')&&($dHDL>'0000-00-00')){
			$req2="SELECT TaSys FROM cardio_vasculaire_depart WHERE id='$id' and dTA='$dTA'";
			$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
			
			list($tension)=mysql_fetch_row($res2);

			$req2="SELECT Chol FROM cardio_vasculaire_depart WHERE id='$id' and dChol='$dChol'";
			$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
			
			list($choltot)=mysql_fetch_row($res2);
			
			$req2="SELECT HDL FROM cardio_vasculaire_depart WHERE id='$id' and dHDL='$dHDL'";
			$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
			
			list($hdl)=mysql_fetch_row($res2);
		}
		
		$req2="SELECT HVG from cardio_vasculaire_depart WHERE id='$id' and (HVG='oui' or HVG='non') ".
			  "ORDER by date DESC limit 0,1";
		$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
		
		list($ventricule)=mysql_fetch_row($res2);
		
		$req2="SELECT surcharge_ventricule from cardio_vasculaire_depart WHERE id='$id' and ".
			  "(surcharge_ventricule='oui' or surcharge_ventricule='non') ".
			  "ORDER by date DESC limit 0,1";
		$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
		
		list($surcharge_ventricule)=mysql_fetch_row($res2);
		
		$req2="SELECT tabac from cardio_vasculaire_depart WHERE id='$id' and ".
			  "(tabac='oui' or tabac='non') ".
			  "ORDER by date DESC limit 0,1";
		$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
		
		list($tab)=mysql_fetch_row($res2);
		
		$calcul=1;
		if(($tab!="oui")&&($tab!="non")){
			$calcul=0;
		}
		if($tension==''){
			$calcul=0;
		}
		if($choltot==''){
			$calcul=0;
		}
		if($hdl==''){
			$calcul=0;
		}
		if(($ventricule!="oui")&&($ventricule!="non")&&($surcharge_ventricule!="oui")&&($surcharge_ventricule!="non")){
			$calcul=0;
		}
		
		if($calcul==1){
			$age=get_age($dnaiss, date("Y-m-d"));
			
			$req2="SELECT dossier_id from suivi_diabete WHERE dossier_id='$id' ";
			$res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
			if(mysql_num_rows($res2)>0){
				$diab=1;
			}
			else{
				$diab=0;
			}
			$rcv=get_rcva($sexe, $age, $diab, $tab, $tension, $choltot, $hdl, $ventricule, $surcharge_ventricule);
			$rcva[$cabinet]=$rcva[$cabinet]+$rcv;
			$nb_dossiers[$cabinet]=$nb_dossiers[$cabinet]+1;

			$rcva["tot"]=$rcva["tot"]+$rcv;
			$nb_dossiers["tot"]=$nb_dossiers["tot"]+1;

			$nb_dossiers[$regions[$cabinet]]=$nb_dossiers[$regions[$cabinet]]+1;
			$rcva[$regions[$cabinet]]=$rcva[$regions[$cabinet]]+$rcv;
		}

	}
}


echo "<br><br><table border='1'><td></td><th>Moyenne</th>";

foreach($reg as $region){
	echo "<th>$region</th>";
}

foreach($tville as $ville){
	echo "<th>$ville</th>";
}


echo "</tr><tr><td>RCV moyen sur l'ensemble du cabinet</td>";


$rcv_moy=round($rcva["tot"]/$nb_dossiers["tot"], 2);
echo "<td align='right' nowrap>$rcv_moy %</td>";

foreach($reg as $region){
	if($nb_dossiers[$region]==0){
		$rcv_moy="ND";
	}
	else{
		$rcv_moy=round($rcva[$region]/$nb_dossiers[$region], 2);
	}
	echo "<td align='right' nowrap>$rcv_moy %</td>";
}

foreach($tville as $cab=>$ville){
	if($nb_dossiers[$cab]==0){
		$rcv_moy="ND";
	}
	else{
		$rcv_moy=round($rcva[$cab]/$nb_dossiers[$cab], 2);
	}
	echo "<td align='right' nowrap>$rcv_moy %</td>";
}

echo "</tr><tr><td>Nombre de patients</td><td align='right'>".$nb_dossiers["tot"]."</td>";
foreach($reg as $region){
	echo "<td align='right'>".$nb_dossiers[$region]."</td>";
}

foreach($tville as $cab=>$ville){
	echo "<td align='right'>".$nb_dossiers[$cab]."</td>";
}

echo "</tr><tr><td>RCV total</td><td align='right'>".$rcva["tot"]."</td>";
foreach($reg as $region){
	echo "<td align='right'>".$rcva[$region]."</td>";
}

foreach($tville as $cab=>$ville){
	echo "<td align='right'>".$rcva[$cab]."</td>";
}

echo "</table><br><br>";


}

function get_rcva($sexe, $age, $diab, $tab, $tension, $choltot, $hdl, $ventricule, $surcharge_ventricule){
	
	$e1 = -0.9119;
	$e2 = -0.2767;
	$e3 = -0.7181;
	$e4 = -0.5865;
	$l = 11.1122;
	$m0 = 4.4181 ;
	$s0 = -0.3155 ;
	$s1 = -0.2784;
	$c1 = -1.4792 ;
	$c2 = -0.1759 ;
	$d1 = -5.8549;
	$d2 = 1.8515 ;
	$d3 = -0.3758 ;
	$horizon=10;
	
	$pas=$tension;
	$tabac=0;
	$hvg=0;
	$chol=$choltot;
	$HDL=$hdl;
	
	if($tab=="oui"){
		$tabac=1;
	}
	if(($ventricule!="oui")&&($ventricule!="non")&&($surcharge_ventricule=="oui")){
		$hvg=1;
	}
	if($ventricule=="oui"){
		$hvg=1;
	}

	$a = $l + $e1*log($pas) + $e2*$tabac + $e3*log($chol/$HDL) + $e4*$hvg;
	
	if($sexe=="M"){
		$m = $a + $c1*log($age) + $c2*$diab*1; //;=> on considère que le patient n'est pas diabétique
	}
	if($sexe=='F'){
		$m = $a + $d1 + $d2*(log($age/74)*log($age/74)) + $d3*$diab; //on considère que la patient n'est pas diabétique
	}
	

	$m_calc = $m0 + $m;
	$s = exp($s0 + $s1*$m);

	$u = (log($horizon) - $m_calc ) / $s ;

	$pt = 1- exp(-exp($u));

	$rcva=round($pt*100, 2);
	// $rcva=$rcva."%";
	
	return $rcva;

}


function get_age($dnaiss, $date=false){
	if($date==false){
		$date=date("Y-m-d");
	}
	
	$dj=explode("-", $date);
	$dn=explode("-", $dnaiss);
	
	$age=$dj[0]-$dn[0];
	if($dj[1]>$dn[1]){
		$age--;
	}
	if(($dj[1]==$dn[1])&&($dj[2]<$dn[2])){
		$age--;
	}
	return $age;
}
?>
</body>
</html>
