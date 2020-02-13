<?php 
	require_once("bean/QuestionnaireMedecin.php");
	require_once("SelfManagedMapper.php");

	class QuestionnaireMedecinMapper extends SelfManagedMapper{
	
		function getLedgerName(){
			return "QuestionnaireMedecinMapper";
		}

		function getTableName(){
			return "questionnaire_medecin";
		}

		function getKeysMap(){
			return array("medecin"=>"medecin");
		}

		function getForeignKey(){
			return "medecin";
		}

		function getObject(){
			return new QuestionnaireMedecin();
		}
		
		function getFindQuery($QuestionnaireMedecin){
			return "select * from questionnaire_medecin ".
				"where medecin='$QuestionnaireMedecin->medecin'";
		}

		function getCoordonnees(){
			$findMedecin = "select nom_complet, prenom, telephone, courriel, fax from account_psam ".
				"where medecin='".$_SESSION['login']."'";
				
			$result = mysql_query($findMedecin,$this->connection);
			if($result == false){
				$this->lastError = FIND_ERROR;
				$this->ledger->write(E,"Find Rows",FIND_ERROR.":".mysql_error());
				return false;
			}

			if((mysql_num_rows($result) == 0) || (mysql_num_rows($result)>1) ){
				$this->lastError = BAD_MATCH;
				$this->ledger->write(W,"Find Rows",BAD_MATCH);
				return false;
			}

			$row = mysql_fetch_array($result, MYSQL_ASSOC);

			return($row);

		}

		function getmail($QuestionnaireMedecin){
			
			$mail="

			<table border=1 width='800' >
			<CAPTION>
				<b>A quel stade vous êtes-vous impliqué dans ASALEE ?	</b>
			</CAPTION>
			<tr>
			    <td>&nbsp;</Td>
			        <td align='center'>A cocher</td>
			            <td align='center'>Commentaires</td>
			</tr>
			<tr>
				<td width='30%'>Initiation de la démarche</td>
					<td align='center'>";
					
			if($QuestionnaireMedecin->implic_initiation=="1"){
				$mail.="oui";
			}
			
			
			$mail.="</td>
						<td align='center'>".nl2br(stripslashes($QuestionnaireMedecin->commentaire_implic_initiation));
			
			$mail.="</td></tr>
			<tr>
				<td width='30%'>Conception du projet</td>
					<td align='center'>";
					
			if($QuestionnaireMedecin->implic_conception=="1"){
				$mail.="oui";
			}
			
			$mail.="</td>
						<td align='center'>".nl2br(stripslashes($QuestionnaireMedecin->commentaire_implic_conception));
											
			$mail.="</td></tr>
			<tr>
				<td width='30%'>Recueil des données</td>
					<td align='center'>";
					
			if($QuestionnaireMedecin->implic_recueil=="1"){
				$mail.="oui";
			}
			
			$mail.="</td>
						<td align='center'>".nl2br(stripslashes($QuestionnaireMedecin->commentaire_implic_recueil));
						
			$mail.="</td></tr>
			<tr>
				<td width='30%'>Analyse des données</td>
					<td align='center'>";
					
			if($QuestionnaireMedecin->implic_analyse=="1"){
				$mail.="oui";
			}
			
			$mail.="</td>
						<td align='center'>".nl2br(stripslashes($QuestionnaireMedecin->commentaire_implic_analyse));
						
			$mail.="</td></tr>
			<tr>
				<td width='30%'>Mise en oeuvre d'actions d'amélioration</td>
					<td align='center'>";
					
			if($QuestionnaireMedecin->implic_mise_oeuvre=="1"){
				$mail.="oui";
			}
			
			$mail.="</td>
						<td align='center'>".nl2br(stripslashes($QuestionnaireMedecin->commentaire_implic_mise_oeuvre));
						
			$mail.="</td></tr>
			<tr>
				<td width='30%'>Suivi des améliorations</td>
					<td align='center'>";
					
			if($QuestionnaireMedecin->implic_suivi=="1"){
				$mail.="oui";
			}
			
			$mail.="</td>
						<td align='center'>".nl2br(stripslashes($QuestionnaireMedecin->commentaire_implic_suivi));
						
			$mail.="</td></tr>
			</table>
			<br><br>
			<table border='1' width='800'>
			<CAPTION>
			<b>Qu'est ce que ce programme vous a apporté</b>
			</CAPTION>
				<tr>
					<td>En terme d'amélioration des pratiques professionnelles ?<br>";
					
			$mail.=nl2br(stripslashes($QuestionnaireMedecin->amelioration_pratique))."</Td>
						<td>";
						
			if($QuestionnaireMedecin->note_pratique=="nulle"){
				$mail.="Nulle";
			}
			elseif($QuestionnaireMedecin->note_pratique=="faible"){
				$mail.="Faible";
			}
			elseif($QuestionnaireMedecin->note_pratique=="moyenne"){
				$mail.="Moyenne";
			}
			elseif($QuestionnaireMedecin->note_pratique=="bonne"){
				$mail.="Bonne";
			}
			elseif($QuestionnaireMedecin->note_pratique=="tb"){
				$mail.="Très bonne";
			}

			$mail.="</td>
				</Tr>
				<tr>
					<td>En terme d'amélioration de l'organisation des soins ?<br>";
			$mail.=nl2br(stripslashes($QuestionnaireMedecin->organisation_soins))."</Td>
						<td>";
						
						
			if($QuestionnaireMedecin->note_soin=="nulle"){
				$mail.="Nulle";
			}
			elseif($QuestionnaireMedecin->note_soin=="faible"){
				$mail.="Faible";
			}
			elseif($QuestionnaireMedecin->note_soin=="moyenne"){
				$mail.="Moyenne";
			}
			elseif($QuestionnaireMedecin->note_soin=="bonne"){
				$mail.="Bonne";
			}
			elseif($QuestionnaireMedecin->note_soin=="tb"){
				$mail.="Très bonne";
			}


			$mail.="</td>
				</Tr>
				<tr>
					<td>En terme d'utilité pour le patient ?<br>";
					
			$mail.=nl2br(stripslashes($QuestionnaireMedecin->utilite_patient))."</Td>
						<td>";
						
						
			if($QuestionnaireMedecin->note_patient=="nulle"){
				$mail.="Nulle";
			}
			elseif($QuestionnaireMedecin->note_patient=="faible"){
				$mail.="Faible";
			}
			elseif($QuestionnaireMedecin->note_patient=="moyenne"){
				$mail.="Moyenne";
			}
			elseif($QuestionnaireMedecin->note_patient=="bonne"){
				$mail.="Bonne";
			}
			elseif($QuestionnaireMedecin->note_patient=="tb"){
				$mail.="Très bonne";
			}


			$mail.="</td>
				</Tr>
			</table>
			<br><br>
		
			<table border=1 width='800' >
			<tr>
				<td colspan='2'>
					<b>Quels sont vos principaux points de satisfaction ?</b><br>";
					
			$mail.=nl2br(stripslashes($QuestionnaireMedecin->satisfaction))."
				</td>
			</tr>
			<tr>
				<td colspan='2'>
					<b>Principales difficultés rencontrées ?</b><br>";
			$mail.=nl2br(stripslashes($QuestionnaireMedecin->difficultes))."
				</td>
			</tr>
			<tr>
				<td colspan='2'>
					<b>Avez-vous repéré des améliorations possibles d'ASALEE ? Lesquelles ? Comment ?</b><br>";
					
			$mail.=nl2br(stripslashes($QuestionnaireMedecin->ameliorations))."
				</td>
			</tr></table></body></html>";
					

			return($mail);

		}

	}

?>
