<?php require_once("bean/beanparser/htmltags.php");
      require_once("view/common/vars.php");
      require_once("persistence/AccountMapper.php");
     
      session_start();
?>

<button onclick="goBack()">Retour</button>

<script>
function goBack() {
    window.history.back();
}
</script>