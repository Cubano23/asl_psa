<?php

  //Modif EA 19-05-2015 rendre le bon texte pour la combobox de cab_gerer

	require "logiciels.php";




	$items = array();
	$row =   array();

	foreach($logiciels as $cle=>$valeur)
	{
   		$value = $valeur;
		$value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);
		$key = $cle;
		$key = mb_check_encoding($cle, 'UTF-8') ? $key : utf8_encode($cle);
		$row["lgc"] = $key;
		$row["lgc_t"] = $value;
		array_push($items, $row);
	}

	echo json_encode($items);

?>
