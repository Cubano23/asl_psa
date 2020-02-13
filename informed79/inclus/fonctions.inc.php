<?php
# fichier inclus pour scripts informed
#

session_start();

$self=$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF']; 
$self=$_SERVER['PHP_SELF'];                       # GIGIGI

# emplacement des scripts communs pour informed79 (hors Asalée)
$inf79_communs = '/informed/communs';

# contrôle des dates  
function controle_date_suivi($date_e, &$date_s, &$message, $optionnel=False, $echu=True) {

   if(($date_e=='') and ($optionnel===True))
       return True;
	   
   if(!preg_match('`^([0-9]{1,2})(/|-)([0-9]{1,2})(/|-)([0-9]{2}|[0-9]{4})$`',$date_e, $reg)) {
      $message[]="La date $date_e doit être au format jj/mm/aaaa";
	  return false;
   }
   if($reg[5]<100) { # année sur deux chiffres
      $reg[5] += 1900;
   }
   if (!checkdate($reg[3],$reg[1],$reg[5])) {
      $message[]="La date $date_e est invalide";
	  return false;
   }
   if( $reg[5] <= 1880) {
      $message[]="La date $date_e doit être supérieure à 1880";
	  return false;
   }
   $date_s = sprintf("%04d%02d%02d", $reg[5], $reg[3], $reg[1]); # date au format aaaammjj
   if ($echu and ($date_s > date('Ymd'))) {
      $message[]="La date $date_e n'est pas échue";
	  return false;
   }
   return true;
}

# sélection du cabinet dans un formulaire
$lcab = array("Chatillon","Niort","Brioux", "zTest", "Lucquin", "Dominault", "Chizé", "Paquereau") ;
function select_cabinet($ncab) {
global $$ncab;  
  if(isset($_SESSION['nom']) and ($_SESSION['admin']==0)) { # le nom du cabinet est fixe
     echo '<input type="hidden" name="'.$ncab.'" value="'.$_SESSION['nom'].'">';
     echo $_SESSION['nom']."\n";
  }
  else {   
     # liste déroulante des cabinets participants
     $req="select nom from ".PREF."cabinet order by admin, nom";
     $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
     echo "<select name=\"$ncab\">\n";
     while(list($lenom)=mysql_fetch_row($res))  {
       echo '<option';
       if ($$ncab==$lenom) echo ' selected'; 
	   echo ">$lenom</option>\n";   
     }
     echo "</select>\n";
  }
}

# sélection du médecin dans un formulaire
function select_medecin($nmed) {
global $$nmed;  
  if(isset($_SESSION['nom']) and ($_SESSION['admin']==0)) { # le nom du cabinet est fixe
     echo '<input type="hidden" name="'.$nmed.'" value="'.$_SESSION['nom'].'">';
     echo $_SESSION['nom']."\n";
  }
  else {   
     # liste déroulante des médecins participants
     $req="select professionnel, nom from ".PREF."aap_medecin order by admin, nom";
     $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
     echo "<select name=\"$nmed\">\n";
     while(list($lecode, $lenom)=mysql_fetch_row($res))  {
       echo "<option value='$lecode'";
       if ($$nmed==$lecode) echo ' selected'; 
	   echo ">$lenom</option>\n";   
     }
     echo "</select>\n";
  }
}

#recherche des codes questionnaires existants en base
function select_questionnaire($questionnaire) {
global $$questionnaire; 

   $req="SELECT DISTINCT r.doc, libelle FROM ".PREF."reponses r ".
        "LEFT JOIN ".PREF."questions q ON q.doc=r.doc and q.ordre=0 ".
        "ORDER BY r.doc"; 
   $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req"); 
   if (mysql_num_rows($res)==0) {
      echo "aucune réponse à questionnaire saisi";
	  return;
   }
 
  echo "<select name=\"$questionnaire\">\n";
  while(list($quest,$lib)=mysql_fetch_row($res)) {
  	 if(is_null($lib)) $lib=$quest;
     echo "<option value=\"$quest\"";
     if ($$questionnaire==$quest) echo ' selected';
     echo ">$lib</option>\n";
  }
  echo "</select>\n";
}

# recherche le libellé affichable d'un code questionnaire et son script
function quest_affiche($questionnaire, &$libelle, &$script) {
	$req="SELECT libelle, complement FROM ".PREF."questions ".
	     "WHERE doc='$questionnaire' AND ordre=0";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req"); 
	if(mysql_num_rows($res)>0) {
		list($libelle, $script)=mysql_fetch_row($res);
	}	
}

# calcul de l'age à partir d'un timestamp MySQL ou date au format j/m/a
function calcage($date, $jma=false) {
  if(!$jma)  # format MySQL
        list($a,$m,$j)= explode('-',$date,3);
  else  # format jj/mm/aa
        list($j,$m,$a)= split('-|/',$date,3);
	 
  $age = date('Y') - $a;
  if(date('m') < $m) $age--;
  if((date('m') == $m) and (date('d') < $j)) $age--;
  return $age;
}

# entête Asalée (informed + urml)

function entete($titre) {
global $slash;

if(!isset($_SESSION['nom'])) {
	# pas passé par l'identification
	$debut=dirname($_SERVER['PHP_SELF']);
	$self=basename($_SERVER['PHP_SELF']);
    header("Location: $debut/ident_util.php?url=".$_SERVER['PHP_SELF']);
	exit;
}
$loc=substr(__FILE__, strlen(stripslashes($_SERVER["DOCUMENT_ROOT"]))); 	# GIGIGI 
$loc='/'.substr($loc, 1, strrpos(dirname($loc),$slash)-1);     # GIGIGI 

?>
<table cellpadding="2" cellspacing="2" border="0"	
 style="text-align: left; width: 100%;">
  <tbody>
    <tr>
      <td style="width: 20%; vertical-align: top;">
      <a href="<?php echo $loc; ?>/">
      <img src="<?php echo $loc; ?>/images/maison.gif" alt="retour à l'accueil" border="0"></a>    
      <a href="javascript:history.back()">
      <img src="<?php echo $loc; ?>/images/back.gif" alt="page précédente" border="0"></a>
      <br>
      <img src="<?php echo $loc; ?>/images/inf79.gif" alt="logo informed79"><br>
        <a href="mailto:informed79@cc-parthenay.fr"><font size="-1">contact</font></a>
      </td>
      <td style="text-align: center; vertical-align: top;">
	       <span style="font-family: arial; font-weight: bold;">
 <?php echo $titre; ?>
           </span><br>
 <?php if(isset($_SESSION['nom'])) echo '<font size="-1"><i>'.$_SESSION['nom'].'</i></font>'; ?>          
      </td>
      <td style="width: 15%; text-align: right; vertical-align: middle;">
      <img src="<?php echo $loc; ?>/images/urml.jpg" alt="logo urml"><br>
      </td>
    </tr>
  </tbody>
</table>
<?php
}

# entête armoire & pédiatrie (informed sans urml)

function inf79_entete($titre, $maison = 'riendutout', $identifier = true) {
global $slash, $inf79_communs;

$loc=substr(__FILE__, strlen(stripslashes($_SERVER["DOCUMENT_ROOT"]))); 	# GIGIGI 
$loc='/'.substr($loc, 1, strrpos(dirname($loc),$slash)-1);     # GIGIGI 

if(!isset($_SESSION['nom']) and $identifier) {
    # pas passé par l'identification
    if(strlen($_SERVER['QUERY_STRING'])>0) 
         $queri= '&queri='.urlencode($_SERVER['QUERY_STRING']);
    else $queri= '';
    header("Location: $inf79_communs/inf79_ident_util.php?url=".$_SERVER['PHP_SELF'].$queri);
	exit;
}
if($maison == 'riendutout') 
   $maison = dirname($_SERVER['PHP_SELF']);
elseif(strlen($maison)==0) 
      $maison='.';

  
$soustitre = ( isset($_SESSION['nom_complet']) ? $_SESSION['nom_complet'] : (isset($_SESSION['nom']) ? $_SESSION['nom'] : ''));

?>
<table cellpadding="2" cellspacing="2" border="0"	
 style="text-align: left; width: 100%;">
  <tbody>
    <tr>
      <td style="width: 20%; vertical-align: top;">
      <a href="<?php echo $maison ; ?>/">
      <img src="<?php echo $loc; ?>/images/maison.gif" alt="retour à l'accueil" border="0"></a>    
      <a href="javascript:history.back()">
      <img src="<?php echo $loc; ?>/images/back.gif" alt="page précédente" border="0"></a>
      </td>
      <td style="text-align: center; vertical-align: top;">
	       <span style="font-family: arial; font-weight: bold;">
 <?php echo $titre; ?>
           </span><br>
 <?php echo '<font size="-1"><i>'.$soustitre.'</i></font>'; ?>          
      </td>
      <td style="width: 15%; text-align: right; vertical-align: middle;">
      <img src="<?php echo $loc; ?>/images/inf79.gif" alt="logo informed79"><br>
        <a href="mailto:informed79@cc-parthenay.fr"><font size="-1">contact</font></a>
      </td>
    </tr>
  </tbody>
</table>
<?php
}
# extrait les réponses stockées en base de données et les renvoit sous forme de tableau 
# nb: temporairement il y a une pattern alternative 
function extrait_reponses($colonne) {
	$ret=preg_match_all('`\$(\w+) = "(.*)"; #`Us', $colonne, $match, PREG_SET_ORDER);
	if ($ret===false) return array();
      
       # TEMPO TEMPO GIGI
       if(sizeof($match)==0) {
 	    $ret=preg_match_all('`(\w+) = "(.*)",`Us', $colonne, $match, PREG_SET_ORDER);
	    if ($ret===false) return array();
       }
       # TEMPO TEMPO GIGI

       echo "<!--trouvé: $colonne \nextrait: ";
	foreach ($match as $variable) {
		$retour[$variable[1]]=$variable[2];
              print_r($variable);
              echo "\n";
	}
       echo "-->\n";
       return $retour;
}

# pour les bilans de diabète
$tbildia=array(
   "4" => array('delai'=>4, 'coul'=>'blue', 'lib'=>'4 mois'),
   "s" => array('delai'=>6, 'coul'=>'green','lib'=>'semestriel'),
   "a" => array('delai'=>12,'coul'=>'brown','lib'=>'annuel')
);

# mois en français
$mois_FR=array(1=>"Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre",
      "Octobre","Novembre","Décembre");

# affiche des listes de sélection de période mensuelle
function select_periode($mois,$annee) {
global $$mois, $$annee, $mois_FR;  

  echo "<select name=\"$mois\">\n";
  foreach($mois_FR as $m => $mFR) {
     echo "<option value=\"$m\"";
	 if ($$mois==$m) echo ' selected' ;
	 echo ">$mFR</option>\n";
  }
  echo "</select>\n";
  echo "/";
  echo "<select name=\"$annee\">\n";  
  for ($an=date("Y")-2; $an<=date("Y"); $an++) {
     echo "<option value=\"$an\"";
	 if ($$annee==$an) echo ' selected' ;
	 echo ">$an</option>\n";  	
  }
  echo "</select>\n";    
}   
   
