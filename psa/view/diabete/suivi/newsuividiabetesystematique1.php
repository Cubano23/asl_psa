<script language="javascript">

	function insuffCoro(){
		<?php 
		if(in_array("a",$suiviDiabete->suivi_type)){ ?>
		var LDL = document.getElementById("LDL");
		var iLDL = document.getElementById("iLDL");
		var LDLc = document.getElementById("LDLc");
		var ic = document.getElementById("coro");

		LDL.value=remplacevirgule(LDL.value);

		if(ic.checked == true){
			LDLc.innerHTML = "Valeur limite : 1 g/l";/* <!--<font size='-1'><i>(1.30 ou 1 si insuf. coro.)</i></font>-->*/
			if(LDL.value > 1) iLDL.checked = true;
			else iLDL.checked = false;
		}
		else{
			LDLc.innerHTML = "Valeur limite : 1.30 g/l ";/*<!--<font size='-1'><i>(1.30 ou 1 si insuf. coro.)</i></font>-->"*/
			if(LDL.value > 1.3) iLDL.checked = true;
			else iLDL.checked = false;
		}
		<?php }?>
	}
	
	function checkSystematique(aForm){
		var i;
		var submitOk = 1;
		var submitpart;
		<?php
		$js = new JSValidation();		
		?>
//		submitpart = validDateValuePair(document.getElementById("dPoids").value,document.getElementById("poids").value,"poids","date du poids");
		var dpoids=document.getElementById('dPoids');
		var poids=document.getElementById('poids');
		if((dpoids.value!="")||(poids.value!="")){
		<?php
			$js->dateInRange("poids:date_exam","Date Poids");
			$js->validateRange("poids:resultat1",0,200,"Poids");
		?>
		}
/*		else{
			if(submitpart==0){
				submitOk=0;
			}
		}*/
		
		var TA_modeMan = document.getElementById('TA_modeMan').checked;
		var TA_modeAuto = document.getElementById('TA_modeAuto').checked;
		var TA_modeMesure = document.getElementById('TA_modeMesure').checked;
		var dtension = document.getElementById('dtension');
		var TaDia = document.getElementById('TaDia');
		var TaSys = document.getElementById('TaSys');
		var nbrtabac = document.getElementById('nbrtabac');
		
		if((TaDia.value!="")||(TaSys.value!="")||(dtension.value!="")|| TA_modeMan ||TA_modeAuto || TA_modeMesure){
		
		<?php		
			$js->validateRange("systole:resultat1",50,300,"Systole");
			$js->validateRange("diastole:resultat1",15,150,"Diastole"); 
		?>
			
			if(!TA_modeMan && !TA_modeAuto && !TA_modeMesure){
				 submitOk = 0;
				 alert("Selectionnez le type de la tension artérielle");
			}
		<?php
				$js->dateInRange("systole:date_exam","Date Tension");
		?>		
		}

		if(nbrtabac.value!=""){
			<?php $js->validateRange("suiviDiabete:nbrtabac",1,100,"Nbr de paquets");?>
		}

		var Regime = document.getElementById("Regime").checked;
		var InsulReq = document.getElementById("InsulReq").checked;
		var ADO = document.getElementById("ADO");
		var ADOSelection = false;
		
		if(ADO.selectedIndex == 0 || ADO.selectedIndex == -1) ADOSelection = false;
		else ADOSelection = true;
		// if(!Regime && !InsulReq && !ADOSelection){
		// 	submitOk = 0;
		// 	alert("Selectionnez un traitement");
		// }
		
		var date_debut = document.getElementById("date_debut");
		if(date_debut.value !=""){
			now=new Date();
			var annee_debut=date_debut.value.split("/");
			var mois_debut=annee_debut[0];
			annee_debut=annee_debut[1];
			if((annee_debut<1900)||(annee_debut>now.getFullYear())||(mois_debut<"01")||(mois_debut>12)){
				alert("Date de début de diabète doit être au format jj/mm/aaaa");
				submitOk = 0;
			}
		<?php
		
//		$js->dateInRange("suiviDiabete:date_debut","Date de début de diabète invalide");															
		?>
		} 

		if((document.getElementById("diabete1").checked==false)&&(document.getElementById("diabete2").checked==false)){
			alert("Veuillez indiquer s'il s'agit d'un diabétique type 1 ou d'un diabétique type 2");
			
		}
		
		return submitOk;
	}
	
	function displayIMC(taille,poids){
		if(taille == 0) 
			document.getElementById("imc").innerHTML = "&nbspLa taille est invalide IMC ne peut etre calculée";
		else{	
			poidsValue = parseFloat(document.getElementById("poids").value);		
			if(isNaN(poidsValue) || poidsValue <30 || poidsValue >200){
				document.getElementById("imc").innerHTML = "&nbspLe poids doit etre compris entre 30 et 200";
				return;
			}
			var imc = Math.round(poidsValue/Math.pow(taille/100, 2));		
			obj = document.getElementById("imc").innerHTML="IMC : "+imc+" kg/m<sup>2</sup>";
		}
		<?php 
		if(in_array("a",$suiviDiabete->suivi_type)){ ?>
		computeCleanrance('<?php echo($dossier->sexe) ?>', '<?php echo($dossier->getAge()); ?>');
		<?php } ?>
	}
	
	 function pas_ADO() { // sélectionne l'option 'aucun' et elle seule
     var i;
		 ADO = document.getElementById("ADO");
	     ADO.options[0].selected=true;
		 for(i = 1 ; i < ADO.length ; i++) 
		    ADO.options[i].selected=false;
	 }
	 
     function verifie_traitement(choix) { // vérifie la cohérence des choix de traitement
     var i, Regime, InsulReq, ADO;
	 
	 Regime = document.getElementById("Regime");
	 InsulReq = document.getElementById("InsulReq");
	 ADO = document.getElementById("ADO");
 
     // Regime incompatible avec les deux autres	 
     	 if((choix==1) && (Regime.checked)) { 
      	    InsulReq.checked=false;
       	    pas_ADO(ADO);
       	    return;
		 }	
     	 if((choix==2) && (InsulReq.checked)) {
      	    Regime.checked=false;
      	    return;
		 }
		 if(choix==3) {
		    for(i = 1 ; i < ADO.length ; i++) {
	 	       if(ADO.options[i].selected==true) {
      	          Regime.checked=false;
      	          ADO.options[0].selected=false;
				  break;
			   }
	 	    }
		    if(ADO.options[0].selected==true) {
       	       var i;
			   ADO.options[0].selected=true;
	           for(i = 1 ; i < ADO.length ; i++) 
		    		ADO.options[i].selected=false;
		    }
	     }
     }

function  verifie_ta(){
	var TaSys, OBJ_tension;

	 TaSys = document.getElementById("TaSys").value;
	 OBJ_tension = document.getElementById("OBJ_tension");
	 
	 if((TaSys<=300) && (TaSys>50))
		 {
		 	OBJ_tension.checked=true;
		 }
	 else
		 {
		    OBJ_tension.checked=false;
		    alert('La Systole doit être comprise entre 50 et 300');
		 }
}

function  verifie_ta_2(){
	var TaDia, OBJ_tension;

	 TaDia = document.getElementById("TaDia").value;
	 OBJ_tension = document.getElementById("OBJ_tension");
	 
 	if((TaDia<=150) && (TaDia>35))
		{
		    OBJ_tension.checked=true;
		}
	else
		{
		   OBJ_tension.checked=false;
		   alert('La Diastole doit être comprise entre 15 et 150');
		}
}

//  function  verifie_nbta(){
// 	var nbrtabac, OBJ_tabac;

// 	 nbrtabac = document.getElementById("nbrtabac").value;
// 	 OBJ_tabac = document.getElementById("OBJ_tabac");
	 
// 	 if((nbrtabac<=100) && (nbrtabac>1))
// 		 {
// 		 	OBJ_tabac.checked=true;
// 		 }
// 	 else
// 		 {
// 		    OBJ_tabac.checked=false;
// 		    alert('Le nombre de paquets doit être compris entre 1 et 101');
// 		 }
// }
	 	 
</script>



<?php   $adoArray = array("aucun"=>"aucun",
                "Acarbose"=>"Acarbose",
   //             "Benfluorex"=>"Benfluorex chlorhydrate",
                "Carbutamide"=>"Carbutamide",
                "Dulaglutide_injectable" => "Dulaglutide injectable",
				"Exénatide"=>"Exénatide",
				"Exénatide_injection"=>"Exénatide (injection hebdomadaire)",
                "Glibenclamide"=>"Glibenclamide",
                "Gliclazide"=>"Gliclazide",
                "Glimepiride"=>"Glimepiride",
                "Glipizide"=>"Glipizide",
                'Insuline_degludec' => "Insuline Degludec",
                "Liraglutide"=>"Liraglutide",
                "Metformine"=>"Metformine",
                "MetformineSitagliptine"=>"Metformine Sitagliptine",
                "MetformineVildagliptine"=>"Metformine Vildagliptine",              
                "Miglitol"=>"Miglitol",
                "Repaglinide"=>"Repaglinide",
				"Saxagliptine"=>"Saxagliptine",
				"SaxagliptineMetformine"=>"Saxagliptine Metformine",
				"Sitagliptine"=>"Sitagliptine",
				"Vildagliptine"=>"Vildagliptine"); ?>
				

  <table border='1'> 
    <caption> 
    <big><b>Suivi systématique:</b></big> 
    </caption> 
	<tr><td>Type de diabète</Td>
		<td nowrap>
        <?php radioButton("id='diabete1'","suiviDiabete:type","1"); ?> 
        Type 1
        <?php radioButton("id='diabete2'","suiviDiabete:type","2"); ?>
        Type 2
		</td>
	</Tr>
    <tr> 
      <td>Poids </td> 
      <td><?php text("id='poids' size='3' onkeyup=\"displayIMC('$dossier->taille','poids')\"","poids:resultat1"); ?>kg</td> 
	  <td>Le : <?php text("id='dPoids' size='10'  onkeyup='formate_date(this)'","poids:date_exam"); ?></td>
      <td id='imc'>IMC:&nbsp;<?php if($suiviDiabete->poids=="") echo ""; else echo($suiviDiabete->getIMC($dossier->taille)==0?"Le poids doit etre compris entre 30 et 200":$suiviDiabete->getIMC($dossier->taille)); ?></td> 
	  <td> <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=poids&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Résultats passés</a>";?></td>
    </tr> 
    <tr> 
      <td valign='top' width='200'>Traitement<br><br>
		<i>Pour sélectionner/désélectionner une ou plusieurs molécules, maintenir la touche contrôle (ctrl) enfoncée</i></td> 
      <td nowrap>
	  	<?php checkBox("id='Regime' onClick='verifie_traitement(1)'","suiviDiabete:Regime","1"); ?> Régime seul<br> 
        <?php checkBox("id='InsulReq' onClick='verifie_traitement(2)'","suiviDiabete:InsulReq","1"); ?> Insulino réquerant
	  </td> 
      <td><table border="0"> 
          <tr> 
            <td>Anti diabétiques oraux: <br/>             
				 <?php selectv("id='ADO' onChange='verifie_traitement(3)' multiple size='18'","suiviDiabete:ADO",$adoArray) ?> 
			</td> 
            <td align='left' name='liste_ADO'><a href='#liste_ADO' OnClick="javascript:window.open('<?php echo($path)?>/view/diabete/suivi/liste_ADO.html?20170530','','width=350,height=550,top=100,left=500,scrollbars=yes,resizable=yes')"><img alt='Correspondance médicament / molécule' border=0 height="31" src='<?php echo($path)?>/view/images/loupe.gif' width="31"></a></td>
          </tr> 
      </table></td> 
      <td colspan='2'> <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=ADO&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Résultats passés</a>";?></td>
  
    </tr> 
    <tr> 
      <td valign='top'>Tension artérielle<br> 
        prise</td> 
      <td colspan='2'>
        <?php text("id='TaSys' size='4' onchange='verifie_ta()'","systole:resultat1");?>/
        <?php text("id='TaDia' size='4' onchange='verifie_ta_2()'","diastole:resultat1");?>
Le : <?php text("id='dtension' size='10'  onkeyup='formate_date(this)'","systole:date_exam"); ?>
		<br>
        <?php radioButton("id='TA_modeMan'","type_tension:resultat1","manuel"); ?> 
        manuel
        <?php radioButton("id='TA_modeAuto'","type_tension:resultat1","automatique"); ?>
        automatique
        <?php radioButton("id='TA_modeMesure'","type_tension:resultat1","automesure"); ?>
        automesure</td> 
		<td colspan='2'> <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=systole&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Résultats passés</a>";?></td>
    </tr> 
    <tr> 
      <td>Facteur de risque associé</td> 
      <td><?php checkBox("","suiviDiabete:risques","1"); ?> Tabac</td> 
	  <td>Nbre de paquets-années<?php text("id='nbrtabac' size='4' ","suiviDiabete:nbrtabac");?></td><!--  onblur='verifie_nbta()' -->
    </tr> 

  </table> 
  <br> 
  <br> 
  
  <table border='1'  width='70%'> 
    <caption> 
    <big><b>Co-pathologies ou complications:</b></big> 
    </caption> 
    <tr> 
      <td><?php checkBox("","suiviDiabete:hta","1"); ?>
        Hypertension artérielle</td> 
      <td><?php checkBox("","suiviDiabete:arte","1"); ?>
        Artérite</td> 
      <td><?php checkBox("","suiviDiabete:neph","1"); ?>
        Nephropathie</td> 
    </tr> 
    <tr> 
      <td><?php checkBox("id='coro' onclick='insuffCoro()' ","suiviDiabete:coro","1"); ?>
        Insuffisance coronarienne</td> 
      <td><?php checkBox("","suiviDiabete:reti","1"); ?>
        Rétinopathie diabétique</td> 
      <td><?php checkBox("","suiviDiabete:neur","1"); ?>
        Neuropathie périphérique</td> 
    </tr> 
  </table> 

</body>
</html>
