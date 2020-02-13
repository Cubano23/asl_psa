<?php
	class Account{
		var $cabinet;
		var $password;
		var $nom;

		function Account($cabinet="",$password="", $nom=""){
			$this->cabinet = $cabinet;
			$this->password = $password;
			$this->nom = $nom;
		}
		
		function toString(){
			return $this->cabinet;
		}
	}
?>
