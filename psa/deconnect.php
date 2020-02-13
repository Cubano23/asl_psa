<?php

$requester=$_SERVER['HTTP_IDS_USER'];
echo '<body onLoad=document.disconnect.submit();>'; 
echo '<form name="disconnect" method="post" id="disconnect" action="/disconnect">';
echo '<input id="requester" name="requester" type="hidden" value="'. $requester . '"/>';
?>


<?php
echo '</form></body>';

//echo '<a href="javascript:document.getElementById(\'disconnect\').submit()">Déconnexion</a>';
?>

