/** Scripts Dreamweaver **/
	
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
/** **/
navvers = navigator.appVersion.substring(0,1);
if (navvers > 3)
	navok = true;
else
	navok = false;

today = new Date;
jour = today.getDay();
numero = today.getDate();
if (numero<10)
	numero = "0"+numero;
mois = today.getMonth();
if (navok)
	annee = today.getFullYear();
else
	annee = today.getYear();
TabJour = new Array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
TabMois = new Array("janvier","f&eacute;vrier","mars","avril","mai","juin","juillet","aout","septembre","octobre","novembre","d&eacute;cembre");
messageDate = TabJour[jour] + " " + numero + " " + TabMois[mois] + " " + annee;

/** **/
function adjustIFrameSize (iframeWindow) {
  if (iframeWindow.document.height) {
    var iframeElement = document.getElementById
(iframeWindow.name);
    iframeElement.style.height = iframeWindow.document.height + 50 + 'px';
    iframeElement.style.width = iframeWindow.document.width + 'px';
  }
  else if (document.all) {
    var iframeElement = document.all[iframeWindow.name];
    if (iframeWindow.document.compatMode &&
        iframeWindow.document.compatMode != 'BackCompat') 
    {
      iframeElement.style.height = 
iframeWindow.document.documentElement.scrollHeight + 50 + 'px';
      iframeElement.style.width = 
iframeWindow.document.documentElement.scrollWidth + 5 + 'px';
    }
    else {
      iframeElement.style.height = 
iframeWindow.document.body.scrollHeight + 50 + 'px';
      iframeElement.style.width = 
iframeWindow.document.body.scrollWidth + 5 + 'px';
    }
  }
}

/** **/
function multiClass(eltId) {
	arrLinkId = new Array('_0','_1','_2','_3');
	intNbLinkElt = new Number(arrLinkId.length);
	arrClassLink = new Array('current','ghost');
	strContent = new String()
	for (i=0; i<intNbLinkElt; i++) {
		strContent = "menu"+arrLinkId[i];
		if ( arrLinkId[i] == eltId ) {
			document.getElementById(arrLinkId[i]).className = arrClassLink[0];
		} else {
			document.getElementById(arrLinkId[i]).className = arrClassLink[1];
		}
	}	
}
function multiClass2(eltId) {
	arrLinkId = new Array('_4','_5','_6','_7');
	intNbLinkElt = new Number(arrLinkId.length);
	arrClassLink = new Array('current','ghost');
	strContent = new String()
	for (i=0; i<intNbLinkElt; i++) {
		strContent = "menu"+arrLinkId[i];
		if ( arrLinkId[i] == eltId ) {
			document.getElementById(arrLinkId[i]).className = arrClassLink[0];
		} else {
			document.getElementById(arrLinkId[i]).className = arrClassLink[1];
		}
	}	
}function multiClass3(eltId) {
	arrLinkId = new Array('_8','_9','_10','_11');
	intNbLinkElt = new Number(arrLinkId.length);
	arrClassLink = new Array('current','ghost');
	strContent = new String()
	for (i=0; i<intNbLinkElt; i++) {
		strContent = "menu"+arrLinkId[i];
		if ( arrLinkId[i] == eltId ) {
			document.getElementById(arrLinkId[i]).className = arrClassLink[0];
		} else {
			document.getElementById(arrLinkId[i]).className = arrClassLink[1];
		}
	}	
}

function choisir(idCase){
	if (document.getElementById('caseMess'+idCase).checked == true ){
		document.getElementById('caseMess'+idCase).checked = false;
	}else{
		document.getElementById('caseMess'+idCase).checked = true;
	}
}

function toutCocher(){
	for(i=0; i<1000; i++){
		if(document.getElementById('caseMess'+i)!=null){
				document.getElementById('caseMess'+i).checked = true;
				document.getElementById('select2').style.display= 'block';
				document.getElementById('select1').style.display= 'none';
		}
	}
}

function toutdeCocher(){
	for(i=0; i<1000; i++){
		if(document.getElementById('caseMess'+i)!=null){
				document.getElementById('caseMess'+i).checked = false;
				document.getElementById('select1').style.display= 'block';
				document.getElementById('select2').style.display= 'none';
		}
	}
}

function marquercommelu(){
	
	for(i=0; i<1000; i++){
		if(document.getElementById('caseMess'+i)!=null){
				if(document.getElementById('caseMess'+i).checked==true){
					if(document.getElementById('titmess'+i).className=="nonlu2"){
							document.getElementById('titmess'+i).className="lu";
					}else{
						document.getElementById('titmess'+i).className="nonlu2"
					}
				}
		}
	}
}

function afficheOnglet(num){
	if(num==1){
		document.getElementById('blocForum').style.display="block";
		document.getElementById('blocPerso').style.display="block";
		document.getElementById('blocTableau').style.display="none";
		document.getElementById('blocCarte').style.display="none";
		document.getElementById('onglet1').className="profil";
		document.getElementById('onglet2').className="etablissement";
	}else{
		document.getElementById('blocForum').style.display="none";
		document.getElementById('blocPerso').style.display="none";
		document.getElementById('blocTableau').style.display="block";
		document.getElementById('blocCarte').style.display="block";
		document.getElementById('onglet1').className="profilb";
		document.getElementById('onglet2').className="etablissementb";
	}
}