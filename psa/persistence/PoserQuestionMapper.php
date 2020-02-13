<?php 
	require_once("bean/PoserQuestion.php");
	require_once("SelfManagedMapper.php");

	class PoserQuestionMapper extends SelfManagedMapper{
	
		function getLedgerName(){
			return "PoserQuestionMapper";
		}

		function getTableName(){
			return "support";
		}

		function getKeysMap(){
			return array();
		}

		function getForeignKey(){
			return "";
		}

		function getObject(){
			return new PoserQuestion();
		}
		
		function getFindQuery($FicheCabinet){
			return "select * from account ".
				"where cabinet='$FicheCabinet->cabinet'";
		}
	

		function get_expediteur($cabinet){
			$query = "SELECT infirmiere, courriel FROM account where cabinet='$cabinet'";

    		$result = $this->findAnyRows($query);

			if($result == false) return false;


			$row = mysql_fetch_array($result, MYSQL_ASSOC);

			if($row['courriel']!='')
			{
				$retour['nom']=$row['infirmiere'];
				$retour['mail']=$row['courriel'];
				$retour['copie']="\"".$row['infirmiere']."\" <".$row['courriel'].">";
			}
			else
			{
				$retour['nom']=$row['infirmiere'];
				$retour['mail']=$row['courriel'];
				$retour['copie']="";
			}

			return $retour;
		}
        function get_cc($loginInfirmiere){
            $query = "SELECT nom,prenom,email FROM annuaire.identifications where login='$loginInfirmiere'";



            $result = $this->findAnyRows($query);
            if($result == false) return false;
            $row = mysql_fetch_array($result, MYSQL_ASSOC);

            if($row['email']!='')
            {
                $retour['copie']="\"".$row['prenom'].' '.$row['nom']."\" <".$row['email'].">";
            }
            else
            {
				return false;
            }

            return $retour;
        }

        function get_email($loginInfirmiere)
		{
            $query = "SELECT email FROM annuaire.identifications where login='$loginInfirmiere'";
            $result = $this->findAnyRows($query);
            if($result == false) return false;
            $row = mysql_fetch_array($result, MYSQL_ASSOC);

            if($row['email']!='')
                return $row['email'];
            else
                return false;
        }

		function getQuestion(){
			$query =  "select * from support order by id desc";

			$result = $this->findAnyRows($query);
			if($result == false) return false;
			$rowsList = "";
			$count = 0;
			while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
				$rowsList[$count] = $row;
				$count = $count + 1;
			}

			return $rowsList;
		}

	}
?>
