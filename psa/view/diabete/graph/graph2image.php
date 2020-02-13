<?php
	global $resultats;
	global $selectedtCabinetsArray;
	global $mediane;
	global $nborne;
	global $texte;
	
	$tableau = $resultats;

   $largeur = 640;
   $hauteur = 375;
   $image = imagecreate($largeur, $hauteur);

   # couleurs
   $blanc = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
   $bleu = imagecolorallocate($image, 0x00, 0x00, 0x80);
   $noir = imagecolorallocate($image, 0x00, 0x00, 0x00);
   $gris = imagecolorallocate($image, 0xC0, 0xC0, 0xC0);
   $rouge= imagecolorallocate($image, 0xFF, 0x00, 0x00);

   $maxval = @max($tableau);
   $nval   = sizeof($tableau);

   $vmargin = 40; // top (bottom) vertical margin for title (x-labels)
   $hmargin = 38; // left horizontal margin for y-labels
   if ($nval>0)
        $base = floor(($largeur - $hmargin) / $nval); // distance between columns
   else $base=1;

   $ysize = $hauteur - 2 * $vmargin; // y-size of plot
   $xsize = $nval * $base; // x-size of plot

   // title
   $titlefont = 3;
   $title = "Mesures de HBA1c dans le suivi du diabete de type 2";

   // pixel-largeur of title
   $txtsz = imagefontwidth($titlefont) * strlen($title);

   $xpos = (int)($hmargin + ($xsize - $txtsz)/2); // center the title
   $xpos = max(1, $xpos); // force positive coordinates
   $ypos = 3; // distance from top

   imagestring($image, $titlefont, $xpos, $ypos, $title , $noir);

   $titlefont = 2;
   if(is_null($selectedtCabinetsArray))
	   $title = "tous cabinets";
   else{
    $title = "";
   	for($i=0;$i<count($selectedtCabinetsArray);$i++){
		$title = "$selectedtCabinetsArray[$i] $title";
	}
	
	if(count($selectedtCabinetsArray) == 1) $title = "Cabinet: ".$title;
	else $title = "Cabinets: ".$title;
   }

   $txtsz = imagefontwidth($titlefont) * strlen($title);
   $xpos = (int)($hmargin + ($xsize - $txtsz)/2); // center the title
   $xpos = max(1, $xpos); // force positive coordinates
   $ypos+=imagefontheight($titlefont)+2;

   imagestring($image, $titlefont, $xpos, $ypos, $title , $noir);

   // y labels and grid lines
   $labelfont = 2;
   $ngrid = 6; // number of grid lines
   $dydat = $maxval / $ngrid; // data units between grid lines

   # calcul complique pour déterminer l'échelle $dydat (sous forme 1/n, n entier)
   # et le nombre de lignes intercalaires $ngrid (ne devant pas être supérieur à 6)
   if($maxval>0) {
      $dydat=floor($maxval/($ngrid+1))+1;
      $ngrid=floor($maxval/$dydat);
   }

   $dypix = $ysize / ($ngrid + 1); // pixels between grid lines

   for ($i = 0; $i <= ($ngrid + 1); $i++) {
       // iterate over y ticks

       $ydat = (int)($i * $dydat); // height of grid line in units of data
       $ypos = $vmargin + $ysize - (int)($i*$dypix); // height of grid line in pixels

       $txtsz = imagefontwidth($labelfont) * strlen($ydat); // pixel-largeur of label
       $txtht = imagefontheight($labelfont); // pixel-height of label

       $xpos = (int)(($hmargin - $txtsz) / 2);
       $xpos = max(1, $xpos);

       imagestring($image, $labelfont, $xpos, $ypos - (int)($txtht/2), $ydat, $noir);

       if (!($i == 0) && !($i > $ngrid))
           imageline($image, $hmargin - 3, $ypos, $hmargin + $xsize, $ypos, $gris);
           // don't draw at Y=0 and top
   }

   // columns and x labels
   $padding = 2; // half of spacing between columns
   if ($dydat<>0)
        $yscale = $ysize / (($ngrid+1) * $dydat); // pixels per data unit
   else $yscale = 1;
   $tot=0;


   for ($i = 0; list($xval, $yval) = each($tableau); $i++) {

       // vertical columns
       $ymax = $vmargin + $ysize;
       $ymin = $ymax - (int)($yval*$yscale);
       $xmax = $hmargin + ($i+1)*$base - $padding;
       $xmin = $hmargin + $i*$base + $padding;

       imagefilledrectangle($image, $xmin, $ymin, $xmax, $ymax, $bleu);
       $tot+=$yval;

       // x labels
       if (!($i % floor($nval/10))) {
       	  $xval=$nborne[$i];
      #    $xval  = date("i:s",$xval);
          $txtsz = imagefontwidth($labelfont) * strlen($xval);
          $xpos = $xmin - ($txtsz/2) ;
          $xpos = min(max(0,$xpos),$largeur-$txtsz);
          $ypos = $ymax + 3; // distance from x axis

          imagestring($image, $labelfont, $xpos, $ypos, $xval, $noir);
          imageline($image, $xmin, $ymax-2, $xmin, $ymax+2, $gris);
       }
   }

   // valeur médiane
   if ((is_numeric($mediane)) and ($mediane<=max($nborne)) and ($mediane>=min($nborne))){
   	  $med=$mediane-min($nborne);
   	  $totm=max($nborne)-min($nborne);
      $posmed= floor($hmargin + (($largeur - $hmargin)/ $totm * $med));
      imageline($image, $posmed, $vmargin+($ysize*0.25), $posmed , $vmargin+$ysize, $rouge);
   }

   // texte
   $ypos=45; $titlefont = 2;
   $title="$tot mesures présentées au total";

   // pixel-largeur of title
   $txtsz = imagefontwidth($titlefont) * strlen($title);
   $xpos = (int)($xsize - $txtsz - 10); // right just.
   $xpos = max(1, $xpos); // force positive coordinates
   imagestring($image, $titlefont, $xpos, $ypos, $title , $noir);

   $ypos+=imagefontheight($titlefont)+4;
   $titlefont = 1;
   foreach($texte as $title) {
      // pixel-largeur of title
      $txtsz = imagefontwidth($titlefont) * strlen($title);
      $xpos = (int)($xsize - $txtsz - 10); // right just.
      $xpos = max(1, $xpos); // force positive coordinates
      imagestring($image, $titlefont, $xpos, $ypos, $title , $noir);
      $ypos+=imagefontheight($titlefont)+2;
   }

   // plot frame
   imagerectangle($image, $hmargin, $vmargin,
      $hmargin + $xsize, $vmargin + $ysize, $noir);

    // flush image
    header("Content-type: image/png");
    imagepng($image);
    imagedestroy($image);
?>