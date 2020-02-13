<?php



	require "departements.php";




	$items = array();
	$row =   array();

	foreach($t_regions as $cle=>$valeur)
	{
   		$value = $cle;
		$value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);
		$row["reg"] = $value;
		$row["reg_t"] = $value;
		array_push($items, $row);
	}

	echo json_encode($items);

?>
