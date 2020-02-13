<?php require_once("bean/beanparser/htmltags.php");
require_once("view/common/vars.php");

if($_SERVER['APPLICATION_ENV']=='dev-herve'){
    #require_once($_SERVER['DOCUMENT_ROOT'].'/psa/WebService/GetUserId.php');
    session_start();
    $_SESSION['id.prenom'] = "Antoine";
    $_SESSION['id.nom'] = "Rizk";
    $_SESSION['id.login'] = "arizk";
    $_SESSION['nom'] = "arizk";
//       $_SESSION['allowedcabinets'] = 'ztest';
}
else{
    require_once ("Config.php");
    $config = new Config();
    require_once($config->webservice_path . '/GetUserId.php') ; //D�placer au d�but pour r�cup�rer sessions
    GetUserSessionVars();    //forcer la session
}

?>
<?php global $account ?>
<?php global $url_depart ?>
<?php global $parametre_redirect;?>
<?php $controler_redirect=$parametre_redirect->controler;
$action_redirect=$parametre_redirect->action;
$param1_redirect=$parametre_redirect->param1;
$param2_redirect=$parametre_redirect->param2;
$param3_redirect=$parametre_redirect->param3;

#var_dump($parametre_redirect);
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

    <p>
    <h2> Bonjour <?php echo utf8_decode($_SESSION['id.prenom'].' '. $_SESSION['id.nom']); ?>
    </h2>
    <!--     <h2> Veuillez choisir votre cabinet de la liste</h2>-->
    <h2>  Attention, il n'est plus n&eacute;cessaire d'entrer les identifiants et mot de passe du cabinet auquel vous souhaitez vous connecter, PSA vous ayant reconnu gr&acirc;ce &agrave; votre certificat et mot de passe, ou gr&acirc;ce &agrave; votre identifiant, votre mot de passe et le code re&ccedil;u par SMS si vous n'utilisez pas de certificat, il vous suffit de choisir le cabinet auquel vous souhaitez vous connecter puis cliquer sur OK </h2>
    </p>
    <p></p>
    <!--			    <img onload="select_id();" src="<?php echo $path;?>/view/login/img/puces/cartouche.gif" width="5" height="17" align="absbottom">-->
    <table>
        <tr>
            <td valign='center' >
                <label>Cabinet:
                    <?php
                    $a =  $_SESSION['allowedcabinets'];

                    /**
                     * Contournement local herve pour avoir la liste des cabinets en mode ADMIN en local
                     */
                    if($_SERVER['APPLICATION_ENV']=='dev-herve'){
                        $conn = new ConnectionFactory();
                        $conn->getConnection();
                        // on récupére tous les cabinets en forcé - mode local
                        #$a = GetAllCabinets();
                        #$a = array("zTest","millau","nueil");
                        $a = array('Selectionner un cabinet');
                        $cab = new AccountMapper();
                        $listeCabs = $cab->listeAllCabs(false,false,'cabinet');
                        foreach($listeCabs as $cab){
                            array_push($a,$cab['cabinet']);
                        }
                    }

                    if(in_array('*', $a )) {
                        $a = GetAllCabinets();
                    }

                    $viewMoisSansTabac = FALSE;
                    $aTemp = array();
                    for($i = 0; $i < count($a); $i++) {
                        if($a[$i] == "moissanstabac2018") {
                            $viewMoisSansTabac = TRUE;
                            continue;
                        }
                        if($a[$i] == "moissanstabac2017") {
                            $viewMoisSansTabac = TRUE;
                            continue;
                        }
                        else {
                            array_push($aTemp, $a[$i]);
                        }
                    }
                    if($viewMoisSansTabac) {
                        array_push($aTemp, "moissanstabac2017");
    	                array_push($aTemp, "moissanstabac2018");
                    }
                    $a = $aTemp;

                    selectArray("class='champs' id='UserID' tabindex='1'","account:cabinet", $a);
                    hidden("class='champs' id='UserPWD' ","account:password","x");
                    ?>
            </td>
            <td>
                <div class="valid">
                    <input type="image" title="Se connecter au PSA" src="<?php echo $path;?>/view/login/img/boutons/btn_login_ok.gif" alt="OK" width="34" height="41" border="0"/>
                </div>
            </td>
        </tr>
    </table>

    </label></p>

</form>
		


