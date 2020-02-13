

<?php 
    if (isset($_SERVER['HTTP_USER_AGENT']))
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];
    }
//    if (strlen(strstr($agent, 'MSIE')) > 0 || strlen(strstr($agent, 'Edge')) > 0)
    if (strlen(strstr($agent, 'MSIE')) > 0 )
    {
        echo "<script>alert('Nous rencontrons des difficultés pour charger la page, veuillez utiliser un autre navegateur web.')</script>";
        die;
    }
    if (isset($_SERVER['HTTPS']) or ($_SERVER['HTTP_HOST']=='localhost')) {   # accès https
    $_GET['action'] = 'login';
    $url = "controler/LoginControler.php";
//    echo $url;
    require $url;
    die;
    } 
?>




<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="refresh" content="2 ; URL=<?php
    $url = "https://".$_SERVER["SERVER_NAME"].dirname($_SERVER["PHP_SELF"])."/controler/LoginControler.php?action=login"; 
    
    echo $url; 
    ?>">

</head>
<body>
    <div align="center">
    <a href="<?php echo $url; ?>">Cliquez ici</a> 
    </div>


    
</body>
</html>