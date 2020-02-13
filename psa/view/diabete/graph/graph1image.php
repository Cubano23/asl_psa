<?php
	global $totalArray;
	global $averageArray;
	global $selectedtCabinetsArray;	
	
	$tableau = $totalArray;
	$tcourbe = $averageArray;
   $libcourtmois = array (1=>"Janv","Fév","Mars","Avr","Mai","Juin","Juil","Août","Sept","Oct","Nov","Déc");

   $largeur = 640;
   $hauteur = 375;
   $image = imagecreate($largeur, $hauteur);

   # couleurs
   $blanc = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
   $bleu = imagecolorallocate($image, 0x00, 0x00, 0x80);
   $noir = imagecolorallocate($image, 0x00, 0x00, 0x00);
   $gris = imagecolorallocate($image, 0xC0, 0xC0, 0xC0);
   $rouge= imagecolorallocate($image, 0xFF, 0x00, 0x00);

   $maxval = max($tableau);   
   $nval   = sizeof($tableau); 

   $vmargin = 40; // top (bottom) vertical margin for title (x-labels)
   $hmargin = 38; // left horizontal margin for y-labels
   $base = floor(($largeur - $hmargin) / $nval); // distance between columns

   $ysize = $hauteur - 2 * $vmargin; // y-size of plot
   $xsize = $nval * $base; // x-size of plot

   // title
   $titlefont = 3;
   $title = "Evolution du HBA1c durant le suivi du diabete de type 2";

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
   $dydat = 1; // data units between grid lines
   
   # calcul complique pour déterminer l'échelle $dydat (sous forme 1/n, n entier)
   # et le nombre de lignes intercalaires $ngrid (ne devant pas être supérieur à 6)
   if($maxval>0) {
      $dydat=floor($maxval/($ngrid+1))+1;
      $ngrid=floor($maxval/$dydat);
      $dy2dat=floor(max($tcourbe)/($ngrid+1))+1; # échelle pour la courbe des médianes
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

       # les ordonnées pour la courbe
       $y2dat = (string) round($i * $dy2dat, 2); 
       $txtsz = imagefontwidth($labelfont) * strlen($y2dat);
       $xpos = (int)($largeur - $txtsz - 8 ) ;
       $xpos = max(1, $xpos);
       imagestring($image, $labelfont, $xpos, $ypos - $txtht, $y2dat, $rouge);

       if (!($i == 0) && !($i > $ngrid))
           imageline($image, $hmargin - 3, $ypos, $hmargin + $xsize, $ypos, $gris);
           // don't draw at Y=0 and top
   }

   // columns and x labels
   $padding = max(1, round($base/3)); // half of spacing between columns
   $yscale = $ysize / (($ngrid+1) * $dydat); // pixels per data unit
   $yscale2 = $ysize / (($ngrid+1) * $dy2dat);
   $tot=0;
   $courb0='';
   for ($i = 0; list($xval, $yval) = each($tableau); $i++) {

       // vertical columns
       $ymax = $vmargin + $ysize;
       $ymin = $ymax - (int)($yval*$yscale);
       $xmin = $hmargin + $i*$base + $padding;
       $xmax = $hmargin + ($i+1)*$base - $padding;

       imagefilledrectangle($image, $xmin, $ymin, $xmax, $ymax, $bleu);
       $tot+=$yval;

       // surimpression de la courbe des moyennes
       if(isset($tcourbe[$xval])) {
      	                
       	$xfin = $hmargin + (($i+1)*$base) - ($base/2);
        $yfin = $ymax - (int)($tcourbe[$xval]*$yscale2);
	
       	if($courb0<>'') {
              $xdeb = $hmargin + ($i*$base) - ($base/2);
              $ydeb = $ymax - (int)($courb0*$yscale2);
 
              # imageline($image, $xdeb, $ydeb, $xfin, $yfin, $noir);
              imagefilledpolygon($image, array($xdeb, $ydeb-1, $xdeb, $ydeb+1, $xfin, $yfin+1, $xfin, $yfin-1), 4, $rouge);
        }
        $courb0=$tcourbe[$xval];

        # etiquette de valeur
        $etiq=(string) round($tcourbe[$xval],2); 
        $txthe = imagefontheight($labelfont);
        $txtsz = imagefontwidth($labelfont) * strlen($etiq);
        $xpos =  (int) $xfin - ($txtsz/2) ;
        $ypos =  $yfin-$txthe-2;
        imagestring($image, $labelfont, $xpos, $ypos, $etiq, $rouge);          
       }
       else $courb0='';

       // x labels
          list($dummy,$an,$mois)=sscanf($xval,"%02d%02d%02d");
          $xval=$libcourtmois[$mois]."-".sprintf("%02d",$an);
          $txtsz = imagefontwidth($labelfont) * strlen($xval);
          $xpos = ($xmin+$xmax)/2 - ($txtsz/2) ;
          $xpos = min(max(0,$xpos),$largeur-$txtsz);
          $ypos = $ymax + 3; // distance from x axis
          imagestring($image, $labelfont, $xpos, $ypos, $xval, $noir);
   }

   // texte
   $ypos=45; $titlefont = 2;
   $title="$tot mesures au total";

   // pixel-largeur of title
   $txtsz = imagefontwidth($titlefont) * strlen($title);
   $xpos = (int)($xsize - $txtsz - 10); // right just.
   $xpos = max(1, $xpos); // force positive coordinates
   imagestring($image, $titlefont, $xpos, $ypos, $title , $noir);

   // plot frame
   imagerectangle($image, $hmargin, $vmargin,
      $hmargin + $xsize, $vmargin + $ysize, $noir);

    // flush image
    //header("Content-type: image/png");
    imagepng($image);
    imagedestroy($image);
?>