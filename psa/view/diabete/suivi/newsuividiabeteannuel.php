<script type="text/javascript">
	function computeCleanrance(sexe,age, ancien_poids){
		var clearance = document.getElementById("clearance");
		var poids = document.getElementById("poids").value;
		var creatininemie = document.getElementById("Creat").value;
		var clearanceVal;
		
		if(poids==""){
			poids=ancien_poids;
		}

		creatininemie=remplacevirgule(creatininemie);
		document.getElementById("Creat").value=creatininemie;

		var objRegExp  =  /(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)/

		if(creatininemie == "0" || !objRegExp.test(creatininemie)){
			clearanceVal = "";
		}
		else{
			clearanceVal = (140-parseInt(age))*parseInt(poids)/(7.2*parseInt(creatininemie));
			if(sexe == "F") clearanceVal *= 0.85;
			if(isNaN(clearanceVal)) clearanceVal = "";
		}

		clearance.innerHTML = Math.round(clearanceVal) + " ml/mn";
	}
	
	function cholesterolHDL(){
		// var HDLcVal = <?php echo($suiviDiabete->getHDLc($dossier)."\n"); ?>;
		// var HDL = document.getElementById("HDL");
		// var iChol = document.getElementById("iChol");
		
		// HDL.value=remplacevirgule(HDL.value);
		
		// if(HDL.value < HDLcVal){
		// 	iChol.checked = true;
		// }
		// else iChol.checked = false;		
		<?php 
			if(in_array("a",$suiviDiabete->suivi_type)){ ?>
			var HDL = document.getElementById("HDL");
			var iChol = document.getElementById("iChol");
			var HDLc = document.getElementById("HDLc");
			var ic = document.getElementById("coro");

			HDL.value=remplacevirgule(HDL.value);

			if(ic.checked == true){
				HDLc.innerHTML = "Valeur limite : 0.40 g/l";/* <!--<font size='-1'><i>(1.30 ou 1 si insuf. coro.)</i></font>-->*/
				if(HDL.value < 0.40) iChol.checked = true;
				else iChol.checked = false;
			}
			else{
				HDLc.innerHTML = "Valeur limite : 0.40 g/l ";/*<!--<font size='-1'><i>(1.30 ou 1 si insuf. coro.)</i></font>-->"*/
				if(HDL.value < 0.4) iChol.checked = true;
				else iChol.checked = false;
			}
		<?php }?>
	}
	
	
	
</script>
<script language="javascript">

	
	function controlLDL(){

		var LDL = document.getElementById("LDL").value;

		
		if(LDL < 0){
			alert('la valeur LDL est incorrecte ( '+LDL+' ) vérifiez votre saisie');
			document.getElementById("LDL").value='';
			iLDL.checked = false;
		}
		if(LDL > 10){
			alert('la valeur LDL est incorrecte ( '+LDL+' ) vérifiez votre saisie');
			document.getElementById("LDL").value='';
			iLDL.checked = false;
		}
	

	}

	function checkAnnuel(aForm){
		var i;
		var submitOk = 1;
		var sOk;
		<?php
			$js = new JSValidation();		
			?>
			sOk = validDateValuePair(document.getElementById("dChol").value,document.getElementById("HDL").value,"Date du cholestérol HDL","Cholestérol HDL");
			if(sOk == 1){
			<?php				
				$js->dateInRange("HDL:date_exam","Date du Cholestérol HDL");		
				$js->validatePositiveNumeric("HDL:resultat1","Cholestérol HDL");						
			?>
			}
			if(sOk == 0) return 0;			

			sOk = validDateValuePair(document.getElementById("dLDL").value,document.getElementById("LDL").value,"Date du LDL","LDL");
			if(sOk ==1){
			<?php
				$js->dateInRange("LDL:date_exam","Date de la mesure LDL invalide");					
				$js->validatePositiveNumeric("LDL:resultat1","LDL");									
			?>	
			} 
			if(sOk == 0) return 0;			
			sOk = validDateValuePair(document.getElementById("dCreat").value,document.getElementById("Creat").value,"Date de la Créatininémie","Créatininémie");
			if(sOk == 1){
			<?php			
				$js->dateInRange("creat:date_exam","Date de la mesure Créatininémie invalide");				
				$js->validatePositiveNumeric("creat:resultat1","Créatininémie");
			?>
			} 
			if(sOk == 0) return 0;
			
			sOk = validDateValuePair(document.getElementById("dAlbu").value,document.getElementById("iAlbu").checked,"Date de l'albuminurie","albuminurie");
			if(sOk ==1){
			<?php
			$js->dateInRange("albu:date_exam","Date de la mesure de micro albuminurie invalide");	
			?>} 
			if(sOk == 0) return 0;
			
			sOk = validDateValuePair(document.getElementById("dFond").value,document.getElementById("iFond").checked,"Date fond d'oeil","fond d'oeil");
			if(sOk ==1){
			<?php
			$js->dateInRange("fond:date_exam","Date du fond d'oeil invalide");				
			?>}
			if(sOk == 0) return 0;
			
			sOk = validDateValuePair(document.getElementById("dECG").value,document.getElementById("iECG").checked,"Date ECG","ECG");
			if(sOk ==1){
			<?php
			$js->dateInRange("ECG:date_exam","Date ECG de repos invalide");															
			?>
			} 
			if(sOk == 0) return 0;
		
			if(document.getElementById("dentiste").value!=''){
				<?php
				$js->dateInRange("dent:date_exam","Date dentiste invalide");															
				?>
			}
		return submitOk;
		}
		

</script>

<br>
<table border='1' width='70%'> 

  <tr> 
  	<td width="174">&nbsp;</td> 
    <td width="46">date</td> 
    <td width="180">cocher si pathologique</td> 
	<td width="100">Valeur précédente</td>
  </tr> 
  <tr valign='top'> 
    <td>Cholestérol HDL<br/> 
      valeur mesurée et cible</td> 
    <td><?php
	
		if($dernier_suivi->isOutdatedHDL(0)){
		    $color='style="background:orange"';
		}
		else{
		    $color="";
		}

	text("id='dChol' $color size='10' onkeyup='formate_date(this)'","HDL:date_exam"); ?>      <br />
      <?php text("id='HDL' $color size='3' onkeyup='cholesterolHDL()'","HDL:resultat1"); ?> g/l</td>
    <td><input type="checkbox" id="iChol" name="iChol" value="1" disabled <?php echo($suiviDiabete->isHDLPathologic($dossier)?"checked":"") ?> /> 
      <br/>
      <div id="HDLc">Valeur limite : <?php echo($suiviDiabete->getHDLc($dossier)); ?> g/l<!--<font size='-1'><i>(1.30 ou 1 si insuf. coro.)</i></font>--></div>
      </td>
	  <td> <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=HDL&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Résultats passés</a>";?></td>
  </tr> 

  <tr> 
    <td valign='top'>LDL<br/> 
      valeurs mesurée et cible</td>     
    <td><?php text("id='dLDL' $color size='10' onkeyup='formate_date(this)'","LDL:date_exam"); ?>
      <br/>
      <?php text("id='LDL' $color size='3' onkeyup='insuffCoro()' onchange='controlLDL()'","LDL:resultat1"); ?>
      g/l</td>
    <td>
	  <div><input  type="checkbox" name="iLDL" id="iLDL" value="1" disabled <?php echo($suiviDiabete->isLDLPathologic()?"checked":""); ?> /></div>      
      <div id="LDLc">Valeur limite : <?php echo($suiviDiabete->getLDLc()); ?> g/l <!--<font size='-1'><i>(1.30 ou 1 si insuf. coro.)</i></font>--></div>
	</td>
	<td> <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=LDL&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Résultats passés</a>";?></td>
  </tr> 

  <tr>
    <td>Créatininémie<br/> 
      valeurs mesurée et cible</td>   
    <td><?php

		if($dernier_suivi->isOutdatedCreat(0)){
		    $color='style="background:orange"';
		}
		else{
		    $color="";
		}

	text("id='dCreat' $color size='10' onkeyup='formate_date(this)'","creat:date_exam"); ?>
	<br/>
		<span name='equiv_creat'><?php 
			$d1an=date("Y")-1;
			$d1an=$d1an."-".date("m")."-".date("d");
			if($dernier_suivi->dPoids!=""){
				$dpoids=explode("/", $dernier_suivi->dPoids);

				$dpoids=$dpoids[2]."-".$dpoids[1]."-".$dpoids[0];
			}
			else{
				$dpoids="";
			}

			if($dpoids>=$d1an){
				$ancien_poids=$dernier_suivi->poids;
			}
			else{
				$ancien_poids="";
			}
			text("id='Creat' $color size='3' onKeyUp ='computeCleanrance(\"$dossier->sexe\",".$dossier->getAge().", \"$ancien_poids\")' ","creat:resultat1"); ?>
    		mg 
		</span>
	</td>
    <td><a href='#equiv_creat' OnClick="javascript:window.open('<?php echo($path)?>/view/diabete/suivi/equivalence_creat.html','','width=250,height=200,top=100,left=500,scrollbars=yes,resizable=yes')">Equivalence µmol/mg</a></td>
	<td> <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=creat&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Résultats passés</a>";?></td>
	</tr> 

  <tr> 
    <td>Clearance calculée</td>     
    <td id='clearance'>&nbsp;<?php echo($suiviDiabete->getClearance($dossier)); ?>ml/mn</td> 
    <td><?php checkBox("id='iCreat' $color size='3'","creat:resultat2","1"); ?></td>
  </tr> 
  <tr> 
    <td>Micro Albuminurie</td>     
    <td><?php
		if($dernier_suivi->isOutdatedAlbu(0)){
		    $color='style="background:orange"';
		}
		else{
		    $color="";
		}

	text("id='dAlbu' $color size='10' onkeyup='formate_date(this)'","albu:date_exam"); ?></td>
    <td><?php checkBox("id='iAlbu' $color ","albu:resultat1","1"); ?></td>
	<td> <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=albu&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Résultats passés</a>";?></td>
	</tr> 
  <tr> 
    <td>Fond d'&oelig;il</td>     
    <td><?php 		if($dernier_suivi->isOutdatedFoeil(0)){
		    $color='style="background:orange"';
		}
		else{
		    $color="";
		}

		text("id='dFond' $color size='10' onkeyup='formate_date(this)'","fond:date_exam"); ?>
 </td>
   <td><?php checkBox("id='iFond' $color","fond:resultat1","1"); ?>&nbsp;&nbsp;
		<a href='#visuFoeil' OnClick="javascript:window.open('../controler/ActionControler.php?controlerparams:param:controler=FondOeilControler&controlerparams:param:action=AL&Dossier:dossier:numero=<?php echo $dossier->numero; ?>','','width=350,height=400,top=100,left=500,scrollbars=yes,resizable=yes')">
		Liste des fonds d'oeil</a>
		</td>
	<td> <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=fond&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Résultats passés</a>";?></td>
  </tr>
  <tr>
  	<td>ECG de repos</td>    
    <td><?php
		if($dernier_suivi->isOutdatedECG(0)){
		    $color='style="background:orange"';
		}
		else{
		    $color="";
		}

	text("id='dECG' $color size='10' onkeyup='formate_date(this)'","ECG:date_exam"); ?></td>
    <td><?php checkBox("id='iECG' $color","ECG:resultat1","1"); ?></td>
	<td> <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=ECG&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Résultats passés</a>";?></td>
  </tr>
  <tr>
  <td>Dentiste</td>    
    <td><?php
		if($dernier_suivi->isOutdatedDentiste(0)){
		    $color='style="background:orange"';
		}
		else{
		    $color="";
		}

	text("id='dentiste' $color size='10' onkeyup='formate_date(this)'","dent:date_exam"); ?></td>
    <td></td>
	<td> <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=dent&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Résultats passés</a>";?></td>
  </tr>
    <tr>
 <td>Triglycérides</td>    
   <td><?php
       if($dernier_suivi->isOutdatedTriglycerides(0)){
           $color='style="background:orange"';
       }
       else{
           $color="";
       }

   text("id='dtriglycerides' $color size='10' onkeyup='formate_date(this)'","triglycerides:date_exam"); ?></td>
   <td>Résultat <?php text("id='triglycerides' $color size='10' onkeyup=","triglycerides:resultat1"); ?></td>
   <td> <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=triglycerides&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Résultats passés</a>";?></td>
 </tr>
   <tr>
 <td>Kaliemie</td>    
   <td><?php
       if($dernier_suivi->isOutdatedKaliemie(0)){
           $color='style="background:orange"';
       }
       else{
           $color="";
       }

   text("id='dkaliemie' $color size='10' onkeyup='formate_date(this)'","kaliemie:date_exam"); ?></td>
   <td>Résultat <?php text("id='kaliemie' $color size='10' onkeyup=","kaliemie:resultat1"); ?></td>
   <td> <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=kaliemie &dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Résultats passés</a>";?></td>
 </tr>
  </table>
