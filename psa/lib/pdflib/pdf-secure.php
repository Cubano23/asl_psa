<?

require('fpdf_protection.php');

$pdf=new FPDF_Protection();
$pdf->SetProtection(array('copy'),'','rr');
$pdf->AddPage();
$pdf->SetFont('Arial');
$pdf->Write(10,"Vous pouvez m'imprimer mais pas copier mon texte.");
$pdf->Output();
?>