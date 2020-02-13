<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $rowsList;?>

<script type="text/javascript" >

</SCRIPT>


<?php
$cote=array("D"=>"Droit", "G"=>"Gauche");
  ?>
<b>Liste des Fonds d'Oeils réalisés<br><br><br></b>
<table border="0" rules="none">
<tr><th width='50'>Date</th><th width='200'>Visualiser en taille réelle</th></tr>
<?php
foreach($rowsList as $fond){
?>
	<tr><td align='center' width='50'><?php echo $fond["date"];?></td><td align='center' width='200'>
		<img src="<?php echo $fond["fichier"]?>" alt="Cliquez pour agrandir" width='40'
		onclick="javascript:window.open('<?php echo $fond["fichier"];?>','')"
	</tr>
<?php
}
?>

</table>

  <br>
