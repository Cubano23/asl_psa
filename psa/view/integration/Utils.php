<?php
 function slugify($text)
{
    // replace non letter or digits by -
    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
 
    // trim
    $text = trim($text, '-');

    $text=str_replace(" ©", "e", $text);
    $text=str_replace(" ®", "e", $text);
    $text=str_replace(" †", "a", $text);

    // replace
    $text = str_replace('-c8','e', $text);
 
    // transliterate
    // if (function_exists('iconv'))
    // {
    //     $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // }
 
    // lowercase
    $text = strtolower($text);
 
    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
 
    if (empty($text))
    {
        return 'n-a';
    }
 
    return $text;
}

function synonymes(){

    $mots=array(
                // "albumine"=>"albu",
                // "albumine-a-ou-a"=>"albu",
                // "microalbuminurie"=>"albu",
                // "albumine-plasmatique"=>"albu",
                // "calcium-corrige-albumine"=>"albu",
                // "microalb"=>"albu",
                // "microalbumine"=>"albu",
                // "microalbuminurie"=>"albu",
                // "microallb"=>"albu",

                "cholesterol-total"=>"Chol",
                "cholesterol"=>"Chol",
                "chol"=>"Chol",
                "chol-total"=>"Chol",
                "cholesterol-a-jeun"=>"Chol",
                "cholesterolemie-totale"=>"Chol",

                "hdl-cholesterol"=>"HDL",
                "cholesterol-hdl"=>"HDL",
                "hdl"=>"HDL",
                "hdlcholest"=>"HDL",
                "hdl-chol"=>"HDL",
                "cholesterol-h-d-l"=>"HDL",
                "cholesterolemie-hdl"=>"HDL",

                "ldl-cholesterolcalcul"=>"LDL",
                "cholesterol-l-d-l"=>"LDL",
                "ldl"=>"LDL",
                "ldlcholest"=>"LDL",
                "ldl-cholesterol"=>"LDL",
                "ldl-cholesterolmesur"=>"LDL",
                "ldl-chol"=>"LDL",
                "ldl-calcule"=>"LDL",
                "cholesterolemie-ldl"=>"LDL",

                "creatinine"=>"creat",
                "cratinine"=>"creat",
                "creatinine-enzymatique"=>"creat",
                "creatinine-plasmatique"=>"creat",
                "creatininemie"=>"creat",
                "creatinine-serique-mg-l"=>"creat",
                "creat"=>"creat",
                "creatinine-g-l"=>"creat",
                "creatinine-sanguine"=>"creat",

                "glycemie"=>"glycemie",
                "glycemie-a-jeun"=>"glycemie",
                "glycemie1hapr"=>"glycemie",
                "glycemieapr"=>"glycemie",
                "gly"=>"glycemie",
                "glycemie1hapr"=>"glycemie",
                "glycemie2hapr"=>"glycemie",
                "glycemieapr"=>"glycemie",
                "glycemie-post-prandiale"=>"glycemie",

                "hemoglobine-hba1c"=>"HBA1c",
                "hemoglobine-glyquee-hba1c"=>"HBA1c",
                "hemoglobine-glyquee"=>"HBA1c",
                "hba1c"=>"HBA1c",
                "hb-a1c"=>"HBA1c",
                "hemoglobine-glycosylee"=>"HBA1c",
                "hemoglobine-a1c"=>"HBA1c",
                "hb-glyquee-a1c"=>"HBA1c",
                "hemoglobine-glycosylee-h-p-l-c"=>"HBA1c",
                "hba1c-unit-uc0-u711-ngsp"=>"HBA1c",
                "glycemie-87-jeun"=>"HBA1c",
                "glycemie-uc0-u711-jeun"=>"HBA1c",
                "hba1c-unit-uc0-u711-ifcc"=>"HBA1c",
                "potassium"=>"kaliemie",
                "kaliemie"=>"kaliemie",

                "triglyceridemie"=>"triglycerides",
                "triglycerides"=>"triglycerides");
    return $mots;
}


function fauxamis(){
    $fauxamis=array("albuminemie","prealbuminemie","creatinine-urinaire", "creatinine-urinaire-enz", "microalbumine-creatinine",  "clairance-de-la-creatinine", "creatinurie","Cr atininurie", "Creatinine urinaire sur echantillon", "clairance-creatinine", "clearance-creat",
        "rapportcholest", "rapport-cholesterol-hdl", "cholesterol-total-hdl", "chol-tot-chol-hdl", "rapport-ldl-hdl", "rapport-ct-hdl", "rapport-chol-total-hdl","rapport-cholesterol-total-hdl",
        "albumine-24h", "microalbuminurie-des-24h","microalbuminurie-g-creat","microalbumine-creatinine", "albumine-serique","creatine-phospho-kinase", "creatininurie-kg-24h","hemoglobine","glycemie-apr-cbs-charge",
        "glycemie-mmol-l", "gly-14h", "glycemie-non-a-jeun", "glycemie-post-prandiale", "glycemie-p-p", "glycosurie", "glycosurie-miction", "gly-pp", "gly-sans-heure", "glyurie", 
        "potassium-urinaire");
    return $fauxamis;
}





function nettoie_type_biologie($var){
    $var=strtolower($var);
    $var=str_replace(" ","_",$var);
    $var=str_replace("\'e0","a",$var);
    $var=strtr($var,' † ° ¢ £ § ß ® © ™ ¿ o ≠ Æ ª                         Ä Å Ç É Ñ á à â ä ã å ç é è ë í ì î ï ñ ô ö õ ú ù',
'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    return $var;

}


// EA dateaxisante en YYYY-MM-DD
function dateaxisantetomysql($date)
{

	$date=explode("/", $date);
	if($date[0]<10)
			$date[0]="0".$date[0];
	if($date[1]<10)
			$date[1]="0".$date[1];
	if($date[2]<10)
			$date[2]="200".$date[2];
	elseif($date[2]<45)
	{  //EA 29-10-2013  15 en 45
				$date[2]="20".$date[2];
	}
	else
	{
		$date[2]="19".$date[2];
	}
		
	$date=$date[2]."-".$date[1]."-".$date[0];
	return $date;
}
 function wd_remove_accents($str, $charset='iso-8859-1')
{
    $str = htmlentities($str, ENT_NOQUOTES, $charset);
    
    $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractáres
    
    return $str;
}

 function canonize($text,  $charset='iso-8859-1')
{

    $text = wd_remove_accents($text, $charset);
    // replace non letter or digits by -
    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
 
    // trim
    $text = trim($text, '-');

    $text=str_replace(" ©", "e", $text);
    $text=str_replace(" ®", "e", $text);
    $text=str_replace(" †", "a", $text);

    // replace
    $text = str_replace('-c8','e', $text);
 
    // transliterate
    // if (function_exists('iconv'))
    // {
    //     $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // }
 
    // lowercase
    $text = strtolower($text);
 
    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
 
    if (empty($text))
    {
        return 'n-a';
    }
 
    return $text;
}

