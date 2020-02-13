<?php

require_once ("Config.php");
$config = new Config();

session_start();

$path = $config->psa_path;

require($config->inclus_path . "/accesbase.inc.php");

# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
   die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
   die("Impossible de se connecter à la base");

?>





 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Planning des infirmières</title>
<meta name="author" content="Informed79 Services SAS">
<meta name="keywords" content="PSA,Portail Services Asalée,Informed79 Services SAS">
<meta name="description" content="Portail Services Asalée, &agrave; votre service pour &ecirc;tre au service de vos patients.">
<meta name="robots" content="noindex,nofollow">
<link href="<?php echo $path;?>/view/login/css/psp5.css" rel="stylesheet" type="text/css">
<link href="<?php echo $path;?>/view/login/css/psp_nicetitle.css" rel="stylesheet" type="text/css">
</head>

<body>
<!-- Script pilotant la navigation -->
<script type="text/javascript" src="<?php echo $path;?>/view/login/js/milonic_src.js"></script>
<script	type="text/javascript">
if(ns4)_d.write("<scr"+"ipt type=text/javascript src=<?php echo $path;?>/view/login/js/mmenuns4.js><\/scr"+"ipt>");
  else _d.write("<scr"+"ipt type=text/javascript src=<?php echo $path;?>/view/login/js/mmenudom.js><\/scr"+"ipt>");
</script>
<script type="text/javascript" src="<?php echo $path;?>/view/login/js/menu_data_planning.js"></script>
<!-- PAGE -->
<div align="center">
	<div id="page">
<!-- ZONE IDENTITAIRE | Header -->
		<div id="header">
			<table width="929" border="0" cellspacing="0" cellpadding="0">
            	<tr>
            		<td width="355">
					<a href='<?php echo $path;?>/controler/ActionControler.php?controlerparams:param:controler=UtilityControler&controlerparams:param:action=AMEN' ><img src='<?php echo $path;?>/view/login/img/habillage/header_psa.gif' alt='Portail Services Asal&eacute;e' title='Retour &agrave; l\'accueil du Portail Services Asal&eacute;e' width='355' height='130' border='0' '></a>
            		<td width="564" align="right" bgcolor="white"><img src="<?php echo $path;?>/view/login/img/logos/LogoAsalee2013.png" alt="Services propos&eacute; par&hellip;" width="190" height="68" hspace="20" vspace="0"></td>
            		<td width="10"><img src="<?php echo $path;?>/view/login/img/habillage/header_right.gif" width="10" height="130"></td>
           		</tr>
           	</table>
		</div>
<!-- NAVIGATION -->
		<div id="navigation">
			<table width="921" border="0" cellspacing="0" cellpadding="0">
            	<tr>
            		<td background="<?php echo $path;?>/view/login/img/habillage/pattern_left.gif"></td>
            		<td bgcolor="white"><img src="<?php echo $path;?>/view/login/img/habillage/gray_top.gif" width="901" height="11"></td>
            		<td width="10" rowspan="3"><img src="<?php echo $path;?>/view/login/img/habillage/right_top_nav.gif" width="10" height="63"></td>
           		</tr>
            	<tr>
            		<td background="<?php echo $path;?>/view/login/img/habillage/pattern_left.gif"></td>
            		<td align="left" bgcolor="white">
					<!-- Script de description du menu -->
					 <script type="text/javascript" src="<?php echo $path;?>/view/login/js/menu_data.js"></script>
					<script>
					with(milonic=new menuname("Main Menu")){
						alwaysvisible=1;
						position="relative";
						left=200;
						top=155;
						style=AllImagesStyle;
						orientation="horizontal";
						overfilter="";
						aI("image=<?php echo $path;?>/view/login/img/navigation/serv_patients.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_patients_over.gif;showmenu=Patients;");
						aI("image=<?php echo $path;?>/view/login/img/navigation/serv_diabete.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_diabete_over.gif;showmenu=Diabete;");
						aI("image=<?php echo $path;?>/view/login/img/navigation/serv_rcva.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_rcva_over.gif;showmenu=RCVA;");
						aI("image=<?php echo $path;?>/view/login/img/navigation/serv_cancer.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_cancer_over.gif;showmenu=Cancer;");
						aI("image=<?php echo $path;?>/view/login/img/navigation/serv_cognitifs.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_cognitifs_over.gif;showmenu=Cognitifs;");
						aI("image=<?php echo $path;?>/view/login/img/navigation/serv_evaluation.gif;overimage=<?php echo $path;?>/view/login/img/navigation/serv_evaluation_over.gif;showmenu=Evaluation;");
					}
					drawMenus();
					</script>
					</td>
           		</tr>
            	<tr>
            		<td width="10" background="<?php echo $path;?>/view/login/img/habillage/pattern_left.gif"></td>
            		<td width="901" bgcolor="white"><img src="<?php echo $path;?>/view/login/img/habillage/gray_bottom.gif" width="901" height="12"></td>
           		</tr>
			</table>
		</div>
		
	</div>
</Div>
<div align='left'>
	<div id='page' style='margin:auto; width:1200px;'>
<!-- CONTENU -->
		<div id="contenu">
			<table width="921" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="10" valign="bottom" background="<?php echo $path;?>/view/login/img/habillage/pattern_left.gif"><img src="<?php echo $path;?>/view/login/img/habillage/left_bottom.gif" width="10" height="416"></td>
					<td width="901" valign="top" bgcolor="white">


<div class="mainlogin">

<br><br>
<h1>Portail Asal&eacute;e - Planning infirmi&egrave;re du cabinet <?php echo $_SESSION['cabinet'] ?></h1>

<table border='1' bgcolor='#FFFFFF'>
	<tr>
		<td></td>
		<th>Lundi</th>
		<th>Mardi</th>
		<th>Mercredi</th>
		<th>Jeudi</th>
		<th>Vendredi</th>
		<th>Samedi</th>
		<th>Cong&eacute;s / absences</th>
	</tr>
	<?php 
		$req="SELECT infirmiere, lundi, mardi, mercredi, jeudi, vendredi, samedi, date_format(debutconge1,'%d/%m/%Y') as debutconge1,  date_format(finconge1,'%d/%m/%Y') as finconge1,  date_format(debutconge2,'%d/%m/%Y') as debutconge2 ,  date_format(finconge2,'%d/%m/%Y') as finconge2 FROM planning order by infirmiere ";

		$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
		while($result = mysql_fetch_array($res)){
		//init dates	
		if($result['debutconge1']=='00/00/0000')
		{
			$result['debutconge1']='';
		}
		if($result['finconge1']=='00/00/0000')
		{
			$result['finconge1']='';
		}
		if($result['debutconge2']=='00/00/0000')
		{
			$result['debutconge2']='';
		}
		if($result['finconge2']=='00/00/0000')
		{
			$result['finconge2']='';
		}


		?>
	<tr>
		<th rowspan='2'><?php echo $result['infirmiere'].'<br/>'.$telinfirmiere.'<br/>
		<a href="mailto:'.$emailinfirmiere.'">'.$emailinfirmiere.'</a>';?></th>
		<td align='center' rowspan='2'><?php echo stripslashes($result['lundi']);?></td>
		<td align='center' rowspan='2'><?php echo stripslashes($result['mardi']);?></td>
		<td align='center' rowspan='2'><?php echo stripslashes($result['mercredi']);?></td>
		<td align='center' rowspan='2'><?php echo stripslashes($result['jeudi']);?></td>
		<td align='center' rowspan='2'><?php echo stripslashes($result['vendredi']);?></td>
		<td align='center' rowspan='2'><?php echo stripslashes($result['samedi']);?></td>
		<td align='center'>Du <?php echo $result['debutconge1'] ;?>
		<br>Au <?php echo $result['finconge1'] ;?></td>

	</tr>

	<tr>
		<td align='center'>Du <?php echo $result['debutconge2'] ;?>
		<br>Au <?php echo $result['finconge2'] ;?></td>
	</tr>

	<?php } ?>



</table>


<?php 
# contrôle des dates
function date_valide($date_e, &$date_s, &$message) {

	if($date_e=="")
	{
		return true;
	}

	if(!preg_match('`^([0-9]{1,2})(/|-)([0-9]{1,2})(/|-)([0-9]{2}|[0-9]{4})$`',$date_e, $reg)) {
		$message[]="La date $date_e doit &ecirc;tre au format jj/mm/aaaa";
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
		$message[]="La date $date_e doit &ecirc;tre sup&eacute;rieure à 1880";
		return false;
	}
	$date_s = sprintf("%04d%02d%02d", $reg[5], $reg[3], $reg[1]); # date au format aaaammjj
		return true;
}

function date_fr_to_us($date){

	$date = explode("/", $date);
	$newsdate=$date[2].'-'.$date[1].'-'.$date[0];
	return $newsdate;
}


 ?>


