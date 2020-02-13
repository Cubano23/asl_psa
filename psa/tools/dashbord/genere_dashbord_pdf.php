<?php
set_time_limit(1000);
ini_set("memory_limit","512M");

require_once("Config.php");
$config = new Config();

require_once($config->app_path . $config->psa_path . '/lib/pdflib/fpdi.php');
#require('fpdf_protection.php');in
require_once($config->app_path . $config->psa_path . '/lib/pdflib/mc_table.php');

$bord = '0'; // affiche ou non la bordure
$modele='matrice_dashboard.pdf'; // version 2017 refaite a partir d'un .ia

if(date('d') >= 7)
{
    $month_to_traite = "-1 month";
}else{
    $month_to_traite = "-2 month";
}
$current_year = date("Y",strtotime($month_to_traite)); //'2013';
$current_month = date("m",strtotime($month_to_traite)); //'03';
$mois = $current_year.'_'.$current_month;//"2014_01";


function stripAccents($text)
{
    if(mb_detect_encoding($text)!="UTF-8"){
        $text = utf8_encode($text);
    }
    return $text;
}
function convert( $str ) {
    return iconv( "macintosh", "UTF-8", $str );
}

// creation du répertoire
//mkdir('./'.$mois, 0777);

//$handle = fopen("csv/".$mois.".csv", "r");
//$handle = fopen("csv/".$mois."_semestre.csv", "r");





$mois = "2018-12";




require_once("Config.php");
$config = new Config();


$rep = $config->files_path."/dashboard/pdf/".$mois;
if(!mkdir($rep, 0775)){
    echo 'impossible de créer '.$rep.'. Il doit exister...';
}

$handle = fopen($config->files_path."/dashboard/csv/".$mois.".csv", "r");
$i = 0;
while (($dh = fgetcsv($handle, 3000, ";")) !== FALSE)
{

    if($i == 0){ $i++; continue; }
    if($dh['17'] == "0.0"){ echo "<br>bad: ".$dh['17']; continue; }
//$dh = array_map( "convert", $dh );
// initiate FPDI
    $pdf =new FPDI();


    // add a page
    $pdf->AddPage();
    // set the sourcefile
    $pdf->setSourceFile($modele);
    $tplIdx = $pdf->importPage(1);
    $pdf->useTemplate($tplIdx, 1, 1, 207);
    $pdf->SetMargins('0','0','0','0');//left, top, right
    // now write some text above the imported page
    $pdf->SetFont('Arial');
    $pdf->SetFontSize(8);
    $pdf->SetTextColor(0,0,0);


    // infos cabinet
    $pdf->SetXY(30, 6);
    $pdf->MultiCell(120,4,stripAccents($dh['1']),$bord,'L');

    $pdf->SetXY(30, 11);
    $pdf->MultiCell(110,4,stripAccents($dh['2']),$bord,'L');

    $pdf->SetXY(30, 16);
    $pdf->MultiCell(110,4,stripAccents($dh['3']),$bord,'L');

    $pdf->SetXY(30, 20);
    $pdf->MultiCell(110,4,stripAccents($dh['4']),$bord,'L');

    // repartition activite
    $pdf->SetXY(69, 40);
    $pdf->MultiCell(13,4,$dh['5'],$bord,'R');
    $pdf->SetXY(91, 40);
    $pdf->MultiCell(14,4,$dh['6'],$bord,'R');

    $pdf->SetXY(69, 45);
    $pdf->MultiCell(13,4,$dh['7'],$bord,'R');
    $pdf->SetXY(91, 45);
    $pdf->MultiCell(14,4,$dh['8'],$bord,'R');

    $pdf->SetXY(69, 49);
    $pdf->MultiCell(13,4,$dh['9'],$bord,'R');
    $pdf->SetXY(91, 49);
    $pdf->MultiCell(14,4,$dh['10'],$bord,'R');

    $pdf->SetXY(69, 54);
    $pdf->MultiCell(13,4,$dh['11'],$bord,'R');
    $pdf->SetXY(91, 54);
    $pdf->MultiCell(14,4,$dh['12'],$bord,'R');

    $pdf->SetXY(69, 59);
    $pdf->MultiCell(13,4,$dh['13'],$bord,'R');
    $pdf->SetXY(91, 59);
    $pdf->MultiCell(14,4,$dh['14'],$bord,'R');

    $pdf->SetXY(69, 63);
    $pdf->MultiCell(13,4,$dh['15'],$bord,'R');
    $pdf->SetXY(91, 63);
    $pdf->MultiCell(14,4,$dh['16'],$bord,'R');

    $pdf->SetXY(69, 68);
    $pdf->MultiCell(13,4,$dh['17'],$bord,'R');
    $pdf->SetXY(91, 68);
    $pdf->MultiCell(14,4,$dh['18'],$bord,'R');


    // actes dérogatoires
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetXY(92, 80);
    $pdf->MultiCell(13,4,$dh['23'],$bord,'R');

    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(92, 85);
    $pdf->MultiCell(13,4,$dh['24'],$bord,'R');

    $pdf->SetXY(92, 90);
    $pdf->MultiCell(13,4,$dh['25'],$bord,'R');

    $pdf->SetXY(92, 94);
    $pdf->MultiCell(13,4,$dh['26'],$bord,'R');

    $pdf->SetXY(92, 99);
    $pdf->MultiCell(13,4,$dh['27'],$bord,'R');

    $pdf->SetXY(92, 104);
    $pdf->MultiCell(13,4,$dh['28'],$bord,'R');

    $pdf->SetXY(92, 109);
    $pdf->MultiCell(13,4,$dh['29'],$bord,'R');


    // nbre examens dans la période

    $pdf->SetXY(92, 120);
    $pdf->MultiCell(13,4,$dh['30'],$bord,'R');

    $pdf->SetXY(92, 124);
    $pdf->MultiCell(13,4,$dh['31'],$bord,'R');


    //Analyse activité consultation
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetXY(190, 35);
    $pdf->MultiCell(14,4,$dh['19'],$bord,'R');

    $pdf->SetXY(190, 45);
    $pdf->MultiCell(14,4,$dh['20'],$bord,'R');

    $pdf->SetXY(190, 53);
    $pdf->MultiCell(14,4,$dh['21'],$bord,'R');

    $pdf->SetXY(190, 60);
    $pdf->MultiCell(14,4,$dh['22'],$bord,'R');


    // patients vus par protocole
    $pdf->SetXY(190, 74);
    $pdf->MultiCell(13,4,$dh['32'],$bord,'R');

    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(190, 79);
    $pdf->MultiCell(13,4,$dh['33'],$bord,'R');

    $pdf->SetXY(190, 83);
    $pdf->MultiCell(13,4,$dh['34'],$bord,'R');

    $pdf->SetXY(190, 88);
    $pdf->MultiCell(13,4,$dh['35'],$bord,'R');

    $pdf->SetXY(190, 93);
    $pdf->MultiCell(13,4,$dh['36'],$bord,'R');

    $pdf->SetXY(190, 98);
    $pdf->MultiCell(13,4,$dh['37'],$bord,'R');

    $pdf->SetXY(190, 103);
    $pdf->MultiCell(13,4,$dh['38'],$bord,'R');// cancer

    $pdf->SetXY(190, 108);
    $pdf->MultiCell(13,4,$dh['39'],$bord,'R');// sevrage tabac

    $pdf->SetXY(190, 112);
    $pdf->MultiCell(13,4,$dh['40'],$bord,'R');// autres types

    $pdf->SetXY(190, 117);
    $pdf->MultiCell(13,4,$dh['41'],$bord,'R');// dont patients multiprotocoles

    // nouveaux patients
    $pdf->SetXY(190, 127);
    $pdf->MultiCell(13,4,$dh['42'],$bord,'R');

    $pdf->SetXY(190, 132);
    $pdf->MultiCell(13,4,$dh['43'],$bord,'R');


    //Evolution HB1C

    //1ere
    $pdf->SetXY(112, 148);
    $pdf->MultiCell(15,4,$dh['44'],$bord,'C');
    $pdf->SetXY(112, 153);
    $pdf->MultiCell(15,4,$dh['45'],$bord,'C');
    $pdf->SetXY(112, 157);
    $pdf->MultiCell(15,4,$dh['46'],$bord,'C');

    //2eme
    $pdf->SetXY(126, 148);
    $pdf->MultiCell(15,4,$dh['47'],$bord,'C');
    $pdf->SetXY(126, 153);
    $pdf->MultiCell(15,4,$dh['48'],$bord,'C');
    $pdf->SetXY(126, 157);
    $pdf->MultiCell(15,4,$dh['49'],$bord,'C');

    //3eme
    $pdf->SetXY(142, 148);
    $pdf->MultiCell(15,4,$dh['50'],$bord,'C');
    $pdf->SetXY(142, 153);
    $pdf->MultiCell(15,4,$dh['51'],$bord,'C');
    $pdf->SetXY(142, 157);
    $pdf->MultiCell(15,4,$dh['52'],$bord,'C');

    //4eme
    $pdf->SetXY(157, 148);
    $pdf->MultiCell(15,4,$dh['53'],$bord,'C');
    $pdf->SetXY(157, 153);
    $pdf->MultiCell(15,4,$dh['54'],$bord,'C');
    $pdf->SetXY(157, 157);
    $pdf->MultiCell(15,4,$dh['55'],$bord,'C');

    //5eme
    $pdf->SetXY(173, 148);
    $pdf->MultiCell(15,4,$dh['56'],$bord,'C');
    $pdf->SetXY(173, 153);
    $pdf->MultiCell(15,4,$dh['57'],$bord,'C');
    $pdf->SetXY(173, 157);
    $pdf->MultiCell(15,4,$dh['58'],$bord,'C');

    //6eme
    $pdf->SetXY(189, 148);
    $pdf->MultiCell(15,4,$dh['59'],$bord,'C');
    $pdf->SetXY(189, 153);
    $pdf->MultiCell(15,4,$dh['60'],$bord,'C');
    $pdf->SetXY(189, 157);
    $pdf->MultiCell(15,4,$dh['61'],$bord,'C');


    //Evolution LDL
    $pdf->SetXY(122, 173);
    $pdf->MultiCell(15,4,$dh['62'],$bord,'C');
    $pdf->SetXY(122, 178);
    $pdf->MultiCell(15,4,$dh['63'],$bord,'C');
    $pdf->SetXY(122, 182);
    $pdf->MultiCell(15,4,$dh['64'],$bord,'C');

    $pdf->SetXY(150, 173);
    $pdf->MultiCell(15,4,$dh['65'],$bord,'C');
    $pdf->SetXY(150, 178);
    $pdf->MultiCell(15,4,$dh['66'],$bord,'C');
    $pdf->SetXY(150, 182);
    $pdf->MultiCell(15,4,$dh['67'],$bord,'C');

    $pdf->SetXY(175, 173);
    $pdf->MultiCell(15,4,$dh['68'],$bord,'R');
    $pdf->SetXY(175, 178);
    $pdf->MultiCell(15,4,$dh['69'],$bord,'R');
    $pdf->SetXY(175, 182);
    $pdf->MultiCell(15,4,$dh['70'],$bord,'R');

    // Evolution tension
    $pdf->SetXY(180, 194);
    $pdf->MultiCell(13,4,$dh['71'],$bord,'R');

    $pdf->SetXY(180, 198);
    $pdf->MultiCell(13,4,$dh['72'],$bord,'R');

    $pdf->SetXY(180, 203);
    $pdf->MultiCell(13,4,$dh['73'],$bord,'R');

    $pdf->SetXY(180, 208);
    $pdf->MultiCell(13,4,$dh['74'],$bord,'R');


    // EFR
    $pdf->SetXY(92, 221);
    $pdf->MultiCell(13,4,$dh['75'],$bord,'R');
    $pdf->SetXY(92, 226);
    $pdf->MultiCell(13,4,$dh['76'],$bord,'R');


    // Troubles cognitifs
    $pdf->SetXY(188, 221);
    $pdf->MultiCell(13,4,$dh['77'],$bord,'R');
    $pdf->SetXY(188, 226);
    $pdf->MultiCell(13,4,$dh['78'],$bord,'R');

    // Potentiel du site
    $pdf->SetXY(188, 240);
    $pdf->MultiCell(13,4,$dh['79'],$bord,'R');

    $pdf->SetXY(188, 245);
    $pdf->MultiCell(13,4,$dh['80'],$bord,'R');

    $pdf->SetXY(188, 250);
    $pdf->MultiCell(13,4,$dh['81'],$bord,'R');

    $pdf->SetXY(188, 254);
    $pdf->MultiCell(13,4,$dh['82'],$bord,'R');

    $pdf->SetXY(188, 259);
    $pdf->MultiCell(13,4,$dh['83'],$bord,'R');


    //$filename = $mois.'/'.$mois.'_'.str_replace("'", "", stripAccents($dh['3'])).' - '.str_replace("'", "", stripAccents($dh['2'])).' - '.stripAccents($dh[0]).'.pdf';
    //$filename = $mois.'_semestre/'.$mois.'_'.str_replace("'", "", stripAccents($dh['3'])).' - '.str_replace("'", "", stripAccents($dh['2'])).' - '.stripAccents($dh[0]).'.pdf';
    //$filename = $mois.'/'.$mois.'_'.str_replace("'", "", stripAccents($dh['3'])).' - '.str_replace("'", "", stripAccents($dh['2'])).' - '.stripAccents($dh[0]).'.pdf';
    //$filename = $mois.'/'.$mois.'_'.$dh['0'].'.pdf';

#	$fichier = explode("_",$dh['83']);
#	$filename = $rep.'/'.$fichier['1'].'-'.$dh['3'].'.pdf';

    #echo $filename;exit;
    $filename = $rep.'/'.$dh['84'].'.pdf';

    $pdf->Output($filename, 'F');

#exit;

}
fclose($handle);
echo "ok - fin";

//$pdf->Output($fileout, 'D');
?>
