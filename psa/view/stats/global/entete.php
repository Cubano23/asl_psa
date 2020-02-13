<?php

require_once ("Config.php");

function entete_asalee($titre)
{
    //$base=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
    $config = new Config();
    $base = $config->psa_path;
    date_default_timezone_set('Europe/Paris');  //EA 23-01-2015
    error_reporting(E_ERROR);    //EA 23-01-2015
    ?>
    <table cellpadding="2" cellspacing="2" border="0"
           style="text-align: left; width: 100%;">
        <tbody>
        <tr>
            <td style="width: 20%; vertical-align: top;">
                <br>
            </td>
            <td style="text-align: center; vertical-align: top;">
	       <span style="font-family: arial; font-weight: bold;">
			<i><font face='times new roman' size='32'>Asalée</font><br>
			<font face='times new roman'><?php echo $titre; ?></font></i>
           </span><br>
                <?php if(isset($_SESSION['nom'])) echo '<font size="-1"><i>'.$_SESSION['nom'].'</i></font>'; ?>
            </td>
            <td style="width: 15%; text-align: right; vertical-align: middle;">
                <a href="javascript:window.close()">
                    <img src="<?php echo "$base/view";?>/images/close.gif" alt="Fermer la fenêtre" border=0 alt="fermer" width=13 height=12></a><br>
                <img src="<?php echo "$base/view";?>/images/LogoAsalee2013.png" alt="logo asalée"><br>
                <a href="mailto:contact@asalee.fr"><font size="-1">contact</font></a>
            </td>
        </tr>
        </tbody>
    </table>



    <?php
}
