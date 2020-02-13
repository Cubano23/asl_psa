<?php 
  error_reporting(E_ERROR);//EA 05-01-2015
  require_once("bean/beanparser/htmltags.php"); 
  require_once("view/common/vars.php"); 

    global $account; 
    global $url_depart; 
    global $parametre_redirect;
    $controler_redirect=$parametre_redirect->controler;
	  $action_redirect=$parametre_redirect->action;
	  $param1_redirect=$parametre_redirect->param1;
	  $param2_redirect=$parametre_redirect->param2;
	  $param3_redirect=$parametre_redirect->param3;
?>

	  <form action="<?php echo("$path/controler/LoginControler.php"); ?>" method="post" name="manage">
<?php hiddenControler("LoginControler"); ?>
<?php hiddenAction(ACTION_FIND); ?>
<?php echo "<input type='hidden' name='controler_redirect' value='$controler_redirect'>";?>
<?php echo "<input type='hidden' name='action_redirect' value='$action_redirect'>";?>
<?php echo "<input type='hidden' name='param1_redirect' value='$param1_redirect'>";?>
<?php echo "<input type='hidden' name='param2_redirect' value='$param2_redirect'>";?>
<?php echo "<input type='hidden' name='param3_redirect' value='$param3_redirect'>";?>
<script type="text/javascript">
	function select_id(){
		document.getElementById("UserID").focus();
	}
</script>

		<div class="valid">
			<input type="image" title="Se connecter au PSV" src="<?php echo $path;?>/view/login/img/boutons/btn_login_ok.gif" alt="OK" width="34" height="41" border="0"/>
		</div>
		<p><label>Nom d'utilisateur<br>
			    <img onload="select_id();" src="<?php echo $path;?>/view/login/img/puces/cartouche.gif" width="5" height="17" align="absbottom">
							<?php text("class='champs' id='UserID' tabindex='1'","account:cabinet"); ?>
		</label></p>
		<p><label>Mot de passe<br>
			    <img src="<?php echo $path;?>/view/login/img/puces/cartouche.gif" width="5" height="17" align="absbottom">
				<?php password("class='champs' id='UserPWD' tabindex='2'","account:password"); ?>
		</label></p>
		</form>
		<p class="nouveau"><a href='' onclick='javascript:window.open("<?php echo $path;?>/view/login/demande_pass.html", "_blank", "height=150, width=850, resizable=yes, scrollbars=yes")'>Obtenir votre mot de passe</a></p>
<!--		<p class="aide"><a href="#">Mot de passe oubli&eacute;</a></p>-->
<!--		<p class="nouveau"><a href="#">Cr&eacute;er un nouveau compte</a></p>-->

