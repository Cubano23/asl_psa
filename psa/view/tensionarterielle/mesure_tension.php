<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta content="text/html; charset=ISO-8859-15" http-equiv="content-type">
	<title>Formulaire Asal&eacute;e - mesure de tension art&eacute;rielle moyenne</title>
	  <script language="JavaScript" type="text/javascript">
  <!-- 
    function verif_date(date) { // vérifie le format de date d'un champ
	  if ((date.value.length>0) &&
         (!date.value.match(/[0-9]{1,2}(\/|-)[0-9]{1,2}(\/|-)[0-9]{4}/))) {
	      alert("la date doit être au format jj/mm/aaaa !");
	      date.value='';
		  date.focus();
	  }
    }
  -->  
  </script>
</head>
<body>
<?php
# paramétrage
$base=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."infornew";
require("$base/inclus/accesbase.inc.php");
error_reporting(E_ALL);

entete("Mesure de tension artérielle moyenne");

# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or 
   die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or 
   die("Impossible de se connecter à la base");

if(isset($_REQUEST['raz'])) $_POST = array();
if(isset($_REQUEST['debug'])) {
   echo '<pre>';
   print_r($_POST);
   echo '</pre>';
}

# initialisations
$message=array();
$champerr=array();
$mesures=array();
$dossier=0;
$cabinet='';
$datedeb = date('d/m/Y');
$nbjours = 5;
$param=array('cabinet','dossier','datedeb','nbjours');


# boucle principale
do {
   $repete=false;

   # étape 1 : identification du patient et de la date
   if (!isset($_POST['etape'])) {
       etape_1($repete);
   	   exit;
   }
	   
   if (isset($_POST['etape'])) {
      switch($_POST['etape']) {

      # étape 2  : saisie des détails
      case 2: 
         etape_2($repete);
		 break;

      # étape 3  : validation des données et màj base 
	  case 3:
         etape_3($repete);
		 break;
		 
      # étape 8  : liste des tensions moyennes
	  case 8:
         etape_8($repete);
		 break;

      # étape 9  : listes des mesures détaillées
	  case 9:
         etape_9($repete);
		 break;

	  }
   }
} while($repete);

# fin de traitement principal


# premiere étape du formulaire : saisie des identifiants et de la date
function etape_1(&$repete) {
global $message, $param, $self;

# récupération éventuelle des données de l'étape précédente, sinon valeurs par défaut
foreach($param as $val) {
  if(isset($_POST[$val]))
       $$val=stripslashes($_POST[$val]);
  else $$val=$GLOBALS[$val];
}

?>
<b>Identification du patient:</b><br />
<form action="<?php echo $self; ?>" method="post" name="form">
<input type="hidden" name="etape" value="2">
<table border="0">
<tr><td>Cabinet:</td><td><?php select_cabinet('cabinet'); ?></td></tr>
<tr><td>N&deg; de dossier:</td><td><input name="dossier" size="10" type="text" 
    value='<?php echo $dossier; ?>'></td></tr>
<tr><td>Date de début de mesure:</td><td><input name="datedeb" size="10" type="text" 
    value="<?php echo $datedeb; ?>" onchange="verif_date(this)"></td></tr>
<tr><td>Nombre de jours:</td><td><select name="nbjours">
<?php 
   for($i=3 ; $i<6 ; $i++) {
    echo "<option".($i==$nbjours ? " selected" : "").">$i</option>\n";
   }
?>
   </select></td>
<tr><td colspan=2>
<input type="submit" name="submit" value="Valider">  
<input type="button" value="Créer ou modifier un dossier" name="CreDos" 
onclick="window.open('../fiche_patient.php?<?php 
  echo "IdCabinet=".urlencode($IdCabinet)."&amp;NoDossier=".urlencode($dossier);?>',
  '', 'width=250,height=350,top=300,left=500,scrollbars=yes,resizable=yes')">
</td></tr></table>
</form>
<?php 
   if(sizeof($message)>0) 
      echo "<br><br><font color='red'><b>".implode('<br>',$message)."</b></font>";

} # fin de l'étape 1

# deuxième étape du formulaire : contrôle des identifiant et saisie/modif des données
function etape_2(&$repete) {
global $message, $mesures, $champerr, $self, $param, $mois_FR;

	# récupération des données de l'étape précédente
	foreach($param as $val)
	   $$val=$_POST[$val];
	   
	$recycle=(sizeof($message)>0);   

    # contrôle et transformation de la date de départ
    controle_date_suivi($datedeb, $ddeb, $message); 
    
    if((strlen($nbjours)<>1) or (strpos('345',$nbjours)===false)) {
       $message[]="le nombre de jours doit être compris entre 3 et 5";	
    }
    # dates de mesure
    $dt=preg_split('`[-/]`', $datedeb, 3);
    if(sizeof($dt)<>3) {
       $message[]="la date $datedeb est d'un format imprévu";	
    } else {
       for($i=1; $i<=$nbjours ; $i++) {
          $dp=mktime(0,0,0,$dt[1],$dt[0]+$i-1,$dt[2]);
          $dateM[$i] = date("j ", $dp).$mois_FR[date("n", $dp)];          
       }
       $date_debut=$dateM[1];
       $date_fin = $dateM[$nbjours];
    }
    
    # retour au formulaire initial en cas d'erreur		   
	if(!$recycle and (sizeof($message)>0)) {
          unset($_POST['etape']);
		  $repete=true;
		  return;
    }
    
    #recherche des données patient
    $req="select date_format(dnaiss,'%d/%m/%Y') as datnaiss, dnaiss, sexe ".
         " from ".PREF."patient where cabinet = '$cabinet' and dossier = '$dossier'";
      $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
      #echo "$req<br>";
      if (mysql_num_rows($res)<>1) {
          $message[]="Patient $dossier non trouvé pour le cabinet $cabinet";
          unset($_POST['etape']);
		  $repete=true;
		  return;
      }
      list($datnaiss,$dnaiss,$sexe) = mysql_fetch_row($res);
      $e = ($sexe == 'F' ? 'e' :'');
      $age=calcage($dnaiss);
      $l='500'; 

      echo "<table border='0' width='$l'><tr><td>\n"; 

      # affichage données patient
      echo "<table border=0><tr><td>";
      echo "<table border='1' rules='none' cellpadding='0'><tr><td>Cabinet:</td><td>$cabinet</td></tr>";
      echo "<tr><td>N&deg; dossier:</td><td>$dossier</td></tr>";
      echo "<tr><td>Sexe:</td><td>$sexe</td></tr>";
      echo "<tr><td>Né$e le:</td><td>$datnaiss</td></tr>";
      echo "<tr><td>Age:</td><td>$age ans</td></tr>";
      echo "<tr><td>Mesures:</td><td>du $date_debut au $date_fin</td></tr>";
      echo "</table>\n";
      
      # bouton de liste des mesures moyennes du patient
      echo "</td><td>";
      echo "<form action='$self' method='post'>\n";
      echo "<input type='hidden' name='cabinet' value='$cabinet'>\n";
      echo "<input type='hidden' name='dossier' value='$dossier'>\n";
      echo "<input type='hidden' name='age' value='$age'>\n";
      echo "<input type='hidden' name='sexe' value='$sexe'>\n";      
      echo "<input type='hidden' name='etape' value='8'>\n";
      echo "<input type='submit' name='submit' value='TA\nantérieures'></form>\n";
      echo "</td></tr></table><br>\n";
	 
      # formulaire principal
      echo "<form action='$self' method='post' name='form'>\n";
      echo "<input type='hidden' name='cabinet' value='$cabinet'>\n";
      echo "<input type='hidden' name='dossier' value='$dossier'>\n";
      echo "<input type='hidden' name='age' value='$age'>\n";
      echo "<input type='hidden' name='etape' value='3'>\n";
      echo "<input type='hidden' name='nbjours' value='$nbjours'>\n";
      echo "<input type='hidden' name='ddeb' value='$ddeb'>\n";
      echo "<input type='hidden' name='datedeb' value='$datedeb'>\n";      
      if($recycle) {
          echo "<i>(reprise de la saisie en erreur)</i>\n";
          extract($mesures);
      }
?>

<table border="1" rules="none" width="<?php echo $l;?>"  cellpadding='3'>
<caption><b><?php echo ($recycle ? 'Corrigez les mesures en rouge' : 'Saisissez les mesures');?></b> (en mmHg)</caption>
<tr><th rowspan=2>jour</th><th rowspan=2>moment</th>
<th colspan=2>1&deg; mesure</th>
<th colspan=2>2&deg; mesure</th>
<th colspan=2>3&deg; mesure</th>
</tr><tr>
<th>Sys</th><th>Dia</th>
<th>Sys</th><th>Dia</th>
<th>Sys</th><th>Dia</th>
</tr>
<?php 

   for($i=1 ; $i <= $nbjours; $i++) {
      echo "<tr><td rowspan=2>".$dateM[$i]."</td><td>matin</td>\n";
      for($j=1 ; $j < 4 ; $j++) {
      	 $champ="Matin_".$i."_".$j."_Sys";
      	 $valeur=(isset($$champ) ? $$champ : 0);
         echo "<td";
         if(isset($champerr[$champ])) echo " bgcolor='#FF0000' "; # en rouge si la valeur est erronée
         echo "><input type='text' name='$champ' size=2 value='$valeur'></td>\n"; 	  
      	 $champ="Matin_".$i."_".$j."_Dia";
      	 $valeur=(isset($$champ) ? $$champ : 0);
         echo "<td";
         if(isset($champerr[$champ])) echo " bgcolor='#FF0000' "; # en rouge si la valeur est erronée
         echo "><input type='text' name='$champ' size=2 value='$valeur'></td>\n"; 	
      }	
      echo "</tr><td>soir</td>\n";
      for($j=1 ; $j < 4 ; $j++) {
      	 $champ="Soir_".$i."_".$j."_Sys";
      	 $valeur=(isset($$champ) ? $$champ : 0);
         echo "<td";
         if(isset($champerr[$champ])) echo " bgcolor='#FF0000' "; # en rouge si la valeur est erronée
         echo "><input type='text' name='$champ' size=2 value='$valeur'></td>\n"; 	
      	 $champ="Soir_".$i."_".$j."_Dia";
      	 $valeur=(isset($$champ) ? $$champ : 0);
         echo "<td";
         if(isset($champerr[$champ])) echo " bgcolor='#FF0000' "; # en rouge si la valeur est erronée
         echo "><input type='text' name='$champ' size=2 value='$valeur'></td>\n"; 	
      }	
      echo "</tr>\n";
   }
   echo "</table>\n";
   
   if(sizeof($message)>0) 
      echo "<br><font color='red'><b>".implode('<br>',$message)."</b></font>";

?>   	
<br>
<table border="1" rules="none" width="<?php echo $l; ?>">
<caption><b>Valider la saisie:</b></caption>
<tr><td align='center'><input type='submit' value='Valider la saisie'> 
<input type='reset' value='Recommencer'>
</td></tr></table>
</form>

<?php
} # fin de l'étape 2 

# troisième étape du formulaire 
function etape_3(&$repete) {
global $self, $mesures, $message, $champerr, $mois_FR;

   echo "<!-- vous avez saisi :<table border=0>";
   foreach ($_POST as $cle => $valeur) {
      echo "<tr><td valign='top'>$cle</td><td>";
      if(is_array($valeur)) {
          echo '<table border=0>';
          foreach ($valeur as $cle2 => $valeur2) 
             echo "<tr><td>$valeur2</td></tr>\n";
          echo "</table>";
      }
      else echo $valeur;
      echo "</td></tr>\n";
   } 
   echo "</table>-->";


	# récupération des identifiants 
	foreach(array('cabinet','dossier','age','ddeb','datedeb','nbjours') as $val) {
	   $$val=$_POST[$val];
    }
        
    $doc=substr(basename($_SERVER['PHP_SELF']),0,-4); # nom du script sans suffixe

    # élimination des paramètres inutiles
    unset($_POST['etape']);

    # dates de mesure
    $dt=preg_split('`[-/]`', $datedeb, 3);
    if(sizeof($dt)<>3) {
       $message[]="la date $datedeb est d'un format imprévu";	
    } else {
       for($i=1; $i<=$nbjours ; $i++) {
          $dp=mktime(0,0,0,$dt[1],$dt[0]+$i-1,$dt[2]);
          $dateM[$i] = date("j ", $dp).$mois_FR[date("n", $dp)];  
          $dateMy[$i] = date("Ymd", $dp);          
       }
       $date_debut=$dateM[1];
       $date_fin = $dateM[$nbjours];
    }
    $moyenne=array();
    
    # contrôle données de mesure
    $tlib=array(array('code'=>'Sys', 'lib'=>'systole', 'min'=>70, 'max'=>300),
                array('code'=>'Dia', 'lib'=>'diastole', 'min'=>35, 'max'=>150));
    foreach($tlib as $lib) {
       $ttot=0;	
       foreach(array('Matin', 'Soir') as $quand) {
          $tot=0;	
          for($i=1; $i<=$nbjours ; $i++) {      	
            for($j=1 ; $j < 4 ; $j++) {
      	       $champ=$quand."_".$i."_".$j."_".$lib['code'];
      	       $$champ=$_POST[$champ];
      	       $mesures[$champ]=$$champ;
      	       $texte="La $j&deg; mesure de ".$lib['lib']." du ".$dateM[$i]." au $quand";
           	   if(!is_numeric($$champ)) {
      	          $message[]="$texte n'est pas numérique"; 
      	          $champerr[$champ]=1;
          	   }
      	       elseif($$champ < $lib['min']) {
      	           $message[]="$texte est trop faible (&lt;".$lib['min'].")"; 
      	           $champerr[$champ]=1;
      	       }
      	       elseif($$champ > $lib['max']) {
      	           $message[]="$texte est trop élevée (&gt;".$lib['max'].")"; 
      	           $champerr[$champ]=1;
      	       }
      	       elseif($lib['code']=='Dia') {
      	          $champSys=$quand."_".$i."_".$j."_Sys";  
      	          if($$champ>$$champSys) {
      	           $message[]="$texte ne peut être supérieure à la mesure de systole (".$$champSys.")"; 
      	           $champerr[$champ]=1;
      	          }
      	       }
      	       if(empty($champerr[$champ])) {  # pas d'erreur
      	       	   $tot += $$champ;
      	       }
      	    } # boucle sur les 3 mesures
         } # boucle sur les 3 à 5 jours
         
         $moyenne[$quand."_".$lib['code']] = (int) round($tot/$nbjours/3);  # valeur moyenne    
         $ttot+=$tot;  
       } # boucle sur matin/soir
       
       $moyenne[$lib['code']] = (int) round($ttot/$nbjours/6);  # valeur moyenne      
    } # boucle sur systole/diastole
     
    # retour en phase 2 en cas d'erreur
    if(sizeof($message)>0) {
          $_POST['etape']=2;
		  $repete=true;
		  return;
    }

    # affichage données patient
    echo "<table border='1' rules='none' cellpadding='0'>\n";
    echo "<tr><td>Cabinet:</td><td>$cabinet</td></tr>\n";
    echo "<tr><td>N&deg; dossier:</td><td>$dossier</td></tr>\n";
    echo "<tr><td>Age:</td><td>$age ans</td></tr>\n";
    echo "<tr><td>Mesures:</td><td>du $date_debut au $date_fin</td></tr>\n";
    echo "</table><br><br>\n";
	 
    # affichage des moyennes
    echo "<!--table border='1' rules='none' cellpadding='4'><caption>Moyennes</caption>\n";
    echo "<tr><th>&nbsp;</th>";
    foreach($tlib as $lib) {
       echo "<th>".$lib['lib']."</th>";	
    }
    echo "</tr>\n";   
    foreach(array('Matin', 'Soir') as $quand) {
       echo "<tr><td>$quand</td>";
       foreach($tlib as $lib) {
       	  $champ=$quand."_".$lib['code'];
          echo "<td align='right'>".$moyenne[$champ]."</td>";	
       }
       echo "</tr>\n";
    }
    echo "<tr><td>moyenne</td>";
    foreach($tlib as $lib) {
   	  $champ=$lib['code'];
      echo "<td align='right'>".$moyenne[$champ]."</td>";	
    }
    echo "</tr>\n";
    echo '</table><br><br-->';  
    
    # tableau pour copie collage dans AxiSanté
    echo "tableau pour recopie dans AxiSanté:\n";
    ?>
<pre>    
moyenne du matin   <?php echo $moyenne['Matin_Sys']."/".$moyenne['Matin_Dia']; ?> 
moyenne du soir    <?php echo $moyenne['Soir_Sys']."/".$moyenne['Soir_Dia']; ?> 
moyenne générale   <?php echo $moyenne['Sys']."/".$moyenne['Dia']; ?> 
nombre de tensions <?php echo $nbjours*3*2; ?>
</pre>
<br>
    <?php

    $maj=0;   
    
    # insertion en base des données détaillées
    $req0="replace ".PREF."tension_detail set cabinet = '$cabinet', dossier = '$dossier', ";    
    foreach($mesures as $champ=>$valeur) {
    	list($quand, $jour, $num, $type) = explode('_', $champ);
    	$req=$req0." quand='$quand', date_mesure=".$dateMy[$jour].", num=$num, type='$type', mesure=$valeur";
    	#echo "$req<br>";
    	mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
    	$maj += mysql_affected_rows();
    }
    
    # insertion en base des moyennes
    $req ="replace ".PREF."tension_moyenne set cabinet = '$cabinet', dossier = '$dossier', date_debut=".$dateMy[1].", nbjours=$nbjours, ";  
    foreach($tlib as $lib) {
       foreach(array('', 'Matin_', 'Soir_') as $quand) {
       	  $cle = $quand.$lib['code'];
          $req .= " $cle=".$moyenne[$cle].",";
       }
    }
    $req=substr($req, 0, -1); # virer la virgule finale 
    #echo "$req<br>";
    mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
    $maj += mysql_affected_rows();

    
    if($maj)
          echo "La base a été mise à jour : $maj lignes ont été ajoutées ou modifiées";   
    else  echo "pas de mise à jour";  
    echo "<br><br>\n";

    echo "<form action='$self?raz=' method='post'>\n";
    echo "<input type='submit' value='Saisir un autre dépistage'>";
    echo "</form>\n";

} # fin de l'étape 3

# étape 8 : liste des TA moyennes d'un patient 
function etape_8(&$repete) {
global $self;

	# récupération des identifiants 
	foreach(array('cabinet','dossier','age','sexe') as $val) {
	   $$val=$_POST[$val];
    }
    
    # affichage données patient
    echo "<table border='1' rules='none' cellpadding='0'>\n";
    echo "<tr><td>Cabinet:</td><td>$cabinet</td></tr>\n";
    echo "<tr><td>N&deg; dossier:</td><td>$dossier</td></tr>\n";
    echo "<tr><td>Sexe:</td><td>$sexe</td></tr>\n";
    echo "<tr><td>Age:</td><td>$age ans</td></tr>\n";
    echo "</table><br><br>\n";
    
    # récupération des données 
    $req="select date_debut, date_format(date_debut, '%d/%m/%Y') as date_aff, date_add(date_debut, interval (nbjours-1) day) as date_fin,".
         " nbjours, CONCAT_WS('/', Sys, Dia) as G, CONCAT_WS('/', Matin_Sys, Matin_Dia) as M, CONCAT_WS('/', Soir_Sys, Soir_Dia) as S ".
         " from ".PREF."tension_moyenne where cabinet = '$cabinet' and dossier = '$dossier' order by date_debut";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
    if(mysql_num_rows($res)==0) {
    	echo "aucune automesure antérieure de tension artérielle";
    }
    else{
       echo "<table border='1' rules='none' cellpadding='3'>\n";
       echo "<tr><th>début</th><th>durée</th><th>matin</th><th>soir</th><th>moyenne</th><th>détail</th></tr>\n";
       #$date0='';	
       while($ligne=mysql_fetch_assoc($res)) {
       	 # if($date0<>$ligne['date_debut']) {
             echo "<tr><td>".$ligne['date_aff']."</td><td align='right'>".$ligne['nbjours']."j</td><td align='center'>".
                  $ligne['M']."</td><td align='center'>".$ligne['S']."</td><td align='center'>".$ligne['G']."</td>";
         #    $date0=$ligne['date_debut'];
       	 # }
       	 # else {
             echo "<td valign='middle'>";
             # bouton de liste des mesures détaillées 
             echo "<form action='$self' method='post'>\n";
             echo "<input type='hidden' name='cabinet' value='$cabinet'>\n";
             echo "<input type='hidden' name='dossier' value='$dossier'>\n";
             echo "<input type='hidden' name='age' value='$age'>\n";
             echo "<input type='hidden' name='sexe' value='$sexe'>\n";      
             echo "<input type='hidden' name='date_debut' value='".$ligne['date_debut']."'>\n";      
             echo "<input type='hidden' name='date_fin' value='".$ligne['date_fin']."'>\n";      
             echo "<input type='hidden' name='etape' value='9'>\n";
             echo "<input type='submit' name='submit' value='détail'></form>\n";
             echo "</td></tr>";
       	 #  }         	
       }
       echo "</table>";	
    }
        
} # fin de l'étape 8

# étape 9 : liste des TA détaillées d'un patient 
function etape_9(&$repete) {
global $self;

	# récupération des identifiants 
	foreach(array('cabinet','dossier','age','sexe','date_debut', 'date_fin') as $val) {
	   $$val=$_POST[$val];
    }
    
    # affichage données patient
    echo "<table border='1' rules='none' cellpadding='0'>\n";
    echo "<tr><td>Cabinet:</td><td>$cabinet</td></tr>\n";
    echo "<tr><td>N&deg; dossier:</td><td>$dossier</td></tr>\n";
    echo "<tr><td>Sexe:</td><td>$sexe</td></tr>\n";
    echo "<tr><td>Age:</td><td>$age ans</td></tr>\n";
    echo "</table><br><br>\n";
    
    # récupération des données détaillées
    $req="SELECT date_format(date_mesure, '%d/%m/%Y') as date, quand, num, type, mesure ".
         "FROM ".PREF."tension_detail WHERE cabinet = '$cabinet' AND dossier = '$dossier' ".
         "AND date_mesure BETWEEN '$date_debut' AND '$date_fin' ORDER BY date_mesure, quand, num, type DESC";
    #echo "$req<br>";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
    if(mysql_num_rows($res)==0) {
    	echo "aucune automesure détaillée n'a été trouvée";
    }
    else{
?>

<table border="1" rules="none" cellpadding='3'>
<tr><th>jour</th><th>moment</th>
<th>1&deg; mesure</th>
<th>2&deg; mesure</th>
<th>3&deg; mesure</th>
<td>
<?php     	   	
       $date0=$moment0=$num0='';	       
       while($ligne=mysql_fetch_assoc($res)) {
       	  if($date0<>$ligne['date']) {
             echo "</td></tr>\n<tr><td rowspan=2>".$ligne['date']."</td>\n<td>".$ligne['quand']."</td>\n<td align='center'>";
             $date0=$ligne['date'];
             $quand0=$ligne['quand'];
             $num0=$ligne['num'];
       	  }
       	  elseif($quand0<>$ligne['quand']) {
             echo "</td></tr>\n<tr><td>".$ligne['quand']."</td>\n<td align='center'>";
             $quand0=$ligne['quand'];
             $num0=$ligne['num'];
       	  }
       	  elseif($num0<>$ligne['num']) {
       	  	 echo "</td>\n<td align='center'>";
             $num0=$ligne['num'];
       	  }
       	  else {
       	  	echo " / ";
       	  }
       	  echo $ligne['mesure'];
       }
       echo "</td></tr></table>\n";
    }
        
} # fin de l'étape 9
?>
</body>
</html>