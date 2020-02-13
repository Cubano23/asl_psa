<?php require_once("bean/beanparser/htmltags.php");
      require_once("view/common/vars.php");
      require_once("persistence/AccountMapper.php");
     
      session_start();
?>

<form  action="<?php echo ("$path/controler/ActionControler.php"); ?>" method="post" >
<?php hiddenControler("TransfertDossierControler"); ?>
<?php hiddenAction(ACTION_UPDATE); ?>
<?php hiddenParamN($param->param3,3); ?>


  <fieldset>
    <legend>Dossier:</legend>
        Numéro patient initial: 
        <input type="text"  name="numeroIni">

        Cabinet patient initial: 
        <input type='text' name="cabIni"  readonly  value="<?= $_SESSION['cabinet']; ?>">  

        Numéro patient cible: 
        <input type="text" name="numeroCible">

        Cabinet patient cible:   
        <select name="cabCible">
          <?php
          $cab = new AccountMapper();
          $listeCabs = $cab->listeAllCabs(false,false,'cabinet');
          foreach($listeCabs as $cabinet)
            echo "<option  value='".$cabinet['cabinet']."'>".$cabinet['cabinet']."</option>"   
          ?>
        </select>
        <input type="submit" value="Soumettre"> 
            
  </fieldset>
</form>