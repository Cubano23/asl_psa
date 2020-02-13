<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("view/common/vars.php");
require_once("Config.php");
require_once("lib/ComposerLibs/vendor/autoload.php");

global $account;
global $dossier;
global $TroubleCognitif;
$config = new Config();
require($config->app_path . $config->psa_path . '/lib/fpdf/fpdf.php');

$pdf=new FPDF();

$pdf->SetFont('Arial', '', 16);
$pdf->AddPage();
$pdf->Cell(0, 16,'DEPISTAGE DES TROUBLES COGNITIFS', 0, 0, 'C');
$pdf->Ln();
$pdf->Ln();
$pdf->SetFontSize(12);
$pdf->Cell(35, 12,'Suivis effectu�s : ',0 , 0, 'L');

if(in_array('mmse',$TroubleCognitif->suivi_type)) {
    $pdf->Cell(15, 12, 'MMSE, ', 0, 0, 'L');
}

if(in_array('gds',$TroubleCognitif->suivi_type)) {
    $pdf->Cell(13, 12, 'GDS, ', 0, 0, 'L');
}

if(in_array('iadl',$TroubleCognitif->suivi_type)) {
    $pdf->Cell(14, 12, 'IADL, ', 0, 0, 'L');
}

if(in_array('horl',$TroubleCognitif->suivi_type)) {
    $pdf->Cell(15, 12, 'Horloge, ', 0, 0, 'L');
}

if(in_array('dubois',$TroubleCognitif->suivi_type)) {
    $pdf->Cell(25, 12, '  Les 5 mots de Dubois ', 0, 0, 'L');
}

$pdf->Ln();
$pdf->Ln();

$pdf->Cell(55, 12, 'Type de d�pistage : ', 0, 1, 'L');



if($TroubleCognitif->dep_type=='coll')
{
    $pdf->Cell(70, 12, 'D�pistage collectif, Date de Rappel : ', 0, 0, 'L');
    $pdf->Cell(55, 12, $TroubleCognitif->date_rappel, 0, 1, 'L');
}

else
{

    $pdf->Cell(55, 12, 'D�pistage individuel ', 0, 1, 'L');
    $pdf->Cell(55, 12, 'Raison du d�pistage : ', 0, 0, 'L');
    $pdf->MultiCell(50, 10, $TroubleCognitif->raison_dep, 0, 'L');
    $pdf->Cell(55, 12, 'Date Rappel : '.$TroubleCognitif->date_rappel, 0, 1, 'L');
}


if(in_array('mmse',$TroubleCognitif->suivi_type)) {

    $pdf->Ln();
    $pdf->Ln();
    $pdf->SetFontSize(14);

    $pdf->AddPage();

    $pdf->Cell(70, 14, '', 0, 0, 'C');
    $pdf->Cell(55, 14, 'MMSE', 1, 1, 'C');
    $pdf->SetFontSize(12);
    $pdf->Ln();


    $pdf->Cell(140, 12, 'Question ', 1, 0, 'L');
    $pdf->Cell(25, 12, 'R�ponse', 1, 1, 'L');

    $pdf->Cell(140, 12, 'En quelle ann�e sommes-nous ? ', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_annee, 1, 1, 'L');

    $pdf->Cell(140, 12, 'En quelle saison ? ', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_saison, 1, 1, 'L');

    $pdf->Cell(140, 12, 'En quel mois ? ', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_mois, 1, 1, 'L');

    $pdf->Cell(140, 12, 'Quel jour du mois ? ', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_jour_mois, 1, 1, 'L');

    $pdf->Cell(140, 12, 'Quel jour de la semaine ? ', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_jour_semaine, 1, 1, 'L');

    $pdf->Cell(140, 12, "Quel est le nom de l'h�pital o� nous sommes ? ", 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_nom_hop, 1, 1, 'L');

    $pdf->Cell(140, 12, 'Dans quelle ville se trouve-t-il ? ', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_nom_ville, 1, 1, 'L');

    $pdf->Cell(140, 12, 'Quel est le nom du D�partement dans lequel est situ?e cette ville ? ', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_nom_dep, 1, 1, 'L');

    $pdf->Cell(140, 12, 'Dans quelle r?gion est situ?e ce D�partement ? ', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_region, 1, 1, 'L');

    $pdf->Cell(140, 12, 'A quel �tage sommes-nous ici ? ', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_etage, 1, 1, 'L');

    $pdf->Cell(140, 12, 'R�p�tez Cigare ', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_cigare1, 1, 1, 'L');

    $pdf->Cell(140, 12, 'R�p�tez Fleur', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_fleur1, 1, 1, 'L');

    $pdf->Cell(140, 12, 'R�p�tez Porte', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_porte1, 1, 1, 'L');

    $pdf->Cell(140, 12, 'Comptez � partir de 100 en retranchant 7 � chaque fois (93)', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_93, 1, 1, 'L');

    $pdf->Cell(140, 12, 'Retranchez encore 7 (86)', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_86, 1, 1, 'L');

    $pdf->Cell(140, 12, 'Retranchez encore 7 (79)', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_79, 1, 1, 'L');

    $pdf->Cell(140, 12, 'Retranchez encore 7 (72)', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_72, 1, 1, 'L');

    $pdf->Cell(140, 12, 'Retranchez encore 7 (65)', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_65, 1, 1, 'L');

    $pdf->Cell(140, 12, "Epelez le mot MONDE � l'envers (EDNOM)", 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_monde, 1, 1, 'L');

    $pdf->Cell(165, 12, 'Rappel : quels �taient les 3 mots � retenir ? ', 1, 1, 'L');

    $pdf->Cell(140, 12, 'CIGARE ', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_cigare2, 1, 1, 'L');

    $pdf->Cell(140, 12, 'FLEUR ', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_fleur2, 1, 1, 'L');

    $pdf->Cell(140, 12, 'PORTE ', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_porte2, 1, 1, 'L');

    $pdf->Cell(140, 12, 'Nom de cet objet (un crayon) ? ', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_crayon, 1, 1, 'L');

    $pdf->Cell(140, 12, 'Nom de cet objet (montre) ? ', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_montre, 1, 1, 'L');

    $pdf->Cell(140, 12, 'Ecoutez bien et r�p�tez apr�s moi : "pas de mais, de si, ni de et" ', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_repete_phrase, 1, 1, 'L');

    $posY=$pdf->getY();
    $pdf->MultiCell(140, 10, 'Ecoutez bien et faites ce que je vous dire : "Prenez cette feuille de papier avec la main droite ? ', 1, 'L');
    $pdf->setY($posY);
    $pdf->setX(150);
    $pdf->Cell(25, 20, $TroubleCognitif->mmse_feuille_prise, 1, 1, 'L');

    $pdf->Cell(140, 12, 'Pliez-l� en deux ', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_feuille_pliee, 1, 1, 'L');

    $pdf->Cell(140, 12, 'Et jetez-l� par terre ? ', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_feuille_jetee, 1, 1, 'L');


    $posY=$pdf->getY();
    $pdf->MultiCell(140, 10, 'Faites ce qui est �crit (tendre une feuille sur laquelle est �crit "fermez les yeux")', 1, 'L');
    $pdf->setY($posY);
    $pdf->setX(150);
    $pdf->Cell(25, 20, $TroubleCognitif->mmse_fermer_yeux, 1, 1, 'L');


    $posY=$pdf->getY();
    $pdf->MultiCell(140, 10, "Voulez-vous m'�crire une phrase, ce que vous voulezmaisune phrase enti�re. Cette phrase doit avoir un sens ? ", 1, 'L');
    $pdf->setY($posY);
    $pdf->setX(150);
    $pdf->Cell(25, 20, $TroubleCognitif->mmse_ecrit_phrase, 1, 1, 'L');

    $pdf->Cell(140, 12, 'Voulez-vous recopier ce dessin ? ', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->mmse_copie_dessin, 1, 1, 'L');

    $pdf->Cell(140, 12, 'Score', 1, 0, 'L');
    $pdf->Cell(25, 12, $TroubleCognitif->get_mmse(), 1, 1, 'L');

}

if(in_array('gds',$TroubleCognitif->suivi_type)) {
    $pdf->AddPage();

    $pdf->SetFontSize(14);

    $pdf->Cell(70, 14, '', 0, 0, 'C');
    $pdf->Cell(55, 14, 'GDS', 1, 1, 'C');
    $pdf->SetFontSize(12);

    $pdf->Ln();
    $pdf->Cell(140, 12, 'Question ', 1, 0, 'L');
    $pdf->Cell(15, 12, 'oui', 1, 0, 'C');
    $pdf->Cell(15, 12, 'non', 1, 1, 'C');

    $pdf->SetFillColor(220);
    $pdf->Cell(140, 12, 'Etes-vous satisfait(e) de votre vie ? ', 1, 0, 'L');
    if($TroubleCognitif->gds_satisf=='oui')
    {
        $pdf->Cell(15, 12, 'X', 1, 0, 'C');
        $pdf->Cell(15, 12, '', 1, 1, 'C', 1);

    }
    else
    {
        $pdf->Cell(15, 12, '', 1, 0, 'C');
        $pdf->Cell(15, 12, 'X', 1, 1, 'C', 1);
    }

    $pdf->Cell(140, 12, "Avez-vous renonc� � un grand nombre d'activit�s ? ", 1, 0, 'L');
    if($TroubleCognitif->gds_renonce_act=='oui')
    {
        $pdf->Cell(15, 12, 'X', 1, 0, 'C', 1);
        $pdf->Cell(15, 12, '', 1, 1, 'C');

    }
    else
    {
        $pdf->Cell(15, 12, '', 1, 0, 'C', 1);
        $pdf->Cell(15, 12, 'X', 1, 1, 'C');
    }

    $pdf->Cell(140, 12, "Avez-vous le sentiment que votre vie soit vide ? ", 1, 0, 'L');
    if($TroubleCognitif->gds_vie_vide=='oui')
    {
        $pdf->Cell(15, 12, 'X', 1, 0, 'C', 1);
        $pdf->Cell(15, 12, '', 1, 1, 'C');

    }
    else
    {
        $pdf->Cell(15, 12, '', 1, 0, 'C', 1);
        $pdf->Cell(15, 12, 'X', 1, 1, 'C');
    }

    $pdf->Cell(140, 12, "Vous ennuyez-vous souvent ? ", 1, 0, 'L');
    if($TroubleCognitif->gds_ennui=='oui')
    {
        $pdf->Cell(15, 12, 'X', 1, 0, 'C', 1);
        $pdf->Cell(15, 12, '', 1, 1, 'C');

    }
    else
    {
        $pdf->Cell(15, 12, '', 1, 0, 'C', 1);
        $pdf->Cell(15, 12, 'X', 1, 1, 'C');
    }

    $pdf->Cell(140, 12, "Envisagez-vous l'avenir avec optimisme ? ", 1, 0, 'L');
    if($TroubleCognitif->gds_avenir_opt=='oui')
    {
        $pdf->Cell(15, 12, 'X', 1, 0, 'C');
        $pdf->Cell(15, 12, '', 1, 1, 'C', 1);

    }
    else
    {
        $pdf->Cell(15, 12, '', 1, 0, 'C');
        $pdf->Cell(15, 12, 'X', 1, 1, 'C', 1);
    }

    $pdf->Cell(140, 12, "Craignez-vous une catastrophe pour l'avenir ? ", 1, 0, 'L');
    if($TroubleCognitif->gds_cata=='oui')
    {
        $pdf->Cell(15, 12, 'X', 1, 0, 'C', 1);
        $pdf->Cell(15, 12, '', 1, 1, 'C');

    }
    else
    {
        $pdf->Cell(15, 12, '', 1, 0, 'C', 1);
        $pdf->Cell(15, 12, 'X', 1, 1, 'C');
    }

    $pdf->Cell(140, 12, "Etes-vous de bonne humeur la plupart du temps ? ", 1, 0, 'L');
    if($TroubleCognitif->gds_bonne_humeur=='oui')
    {
        $pdf->Cell(15, 12, 'X', 1, 0, 'C');
        $pdf->Cell(15, 12, '', 1, 1, 'C', 1);

    }
    else
    {
        $pdf->Cell(15, 12, '', 1, 0, 'C');
        $pdf->Cell(15, 12, 'X', 1, 1, 'C', 0);
    }

    $pdf->Cell(140, 12, "Avez-vous besoin d'aide dans vos activit�s ? ", 1, 0, 'L');
    if($TroubleCognitif->gds_besoin_aide=='oui')
    {
        $pdf->Cell(15, 12, 'X', 1, 0, 'C', 1);
        $pdf->Cell(15, 12, '', 1, 1, 'C');

    }
    else
    {
        $pdf->Cell(15, 12, '', 1, 0, 'C', 1);
        $pdf->Cell(15, 12, 'X', 1, 1, 'C');
    }

    $posY=$pdf->getY();
    $pdf->MultiCell(140, 10, "Pr�f�rez-vous rester seul(e) dans votre chambre (ou � la maison) plut�t que d'en sortir ? ", 1, 'L');
    $pdf->SetY($posY);
    $pdf->SetX(150);
    if($TroubleCognitif->gds_prefere_seul=='oui')
    {
        $pdf->Cell(15, 20, 'X', 1, 0, 'C', 1);
        $pdf->Cell(15, 20, '', 1, 1, 'C');

    }
    else
    {
        $pdf->Cell(15, 20, '', 1, 0, 'C', 1);
        $pdf->Cell(15, 20, 'X', 1, 1, 'C');
    }

    $posY=$pdf->getY();
    $pdf->MultiCell(140, 10, "Pensez-vous que votre m�moire est moins bonne que celle de la plupart des gens ? ", 1, 'L');
    $pdf->setY($posY);
    $pdf->SetX(150);
    if($TroubleCognitif->gds_mauvaise_mem=='oui')
    {
        $pdf->Cell(15, 20, 'X', 1, 0, 'C', 1);
        $pdf->Cell(15, 20, '', 1, 1, 'C');

    }
    else
    {
        $pdf->Cell(15, 20, '', 1, 0, 'C', 1);
        $pdf->Cell(15, 20, 'X', 1, 1, 'C');
    }


    $pdf->Cell(140, 12, "Etes-vous heureux(se) de vivre actuellement ? ", 1, 0, 'L');
    if($TroubleCognitif->gds_heureux_vivre=='oui')
    {
        $pdf->Cell(15, 12, 'X', 1, 0, 'C');
        $pdf->Cell(15, 12, '', 1, 1, 'C', 1);

    }
    else
    {
        $pdf->Cell(15, 12, '', 1, 0, 'C');
        $pdf->Cell(15, 12, 'X', 1, 1, 'C', 1);
    }


    $pdf->Cell(140, 12, "Avez-vous l'impression de n'�tre plus bon(ne) � rien ? ", 1, 0, 'L');
    if($TroubleCognitif->gds_bon_rien=='oui')
    {
        $pdf->Cell(15, 12, 'X', 1, 0, 'C', 1);
        $pdf->Cell(15, 12, '', 1, 1, 'C');

    }
    else
    {
        $pdf->Cell(15, 12, '', 1, 0, 'C', 1);
        $pdf->Cell(15, 12, 'X', 1, 1, 'C');
    }


    $pdf->Cell(140, 12, "Avez-vous beaucoup d'?nergie � ", 1, 0, 'L');
    if($TroubleCognitif->gds_energie=='oui')
    {
        $pdf->Cell(15, 12, 'X', 1, 0, 'C');
        $pdf->Cell(15, 12, '', 1, 1, 'C',1);

    }
    else
    {
        $pdf->Cell(15, 12, '', 1, 0, 'C');
        $pdf->Cell(15, 12, 'X', 1, 1, 'C',1);
    }


    $pdf->Cell(140, 12, "D�sesp�rez-vous de votre situation pr�sente ? ", 1, 0, 'L');
    if($TroubleCognitif->gds_desespere_sit=='oui')
    {
        $pdf->Cell(15, 12, 'X', 1, 0, 'C', 1);
        $pdf->Cell(15, 12, '', 1, 1, 'C');

    }
    else
    {
        $pdf->Cell(15, 12, '', 1, 0, 'C', 1);
        $pdf->Cell(15, 12, 'X', 1, 1, 'C');
    }

    $posY=$pdf->getY();
    $pdf->MultiCell(140, 10, "Pensez-vous que la situation des autres est meilleure que la votre, que les autres ont plus de chance que vous? ", 1, 'L');
    $pdf->setY($posY);
    $pdf->setX(150);
    if($TroubleCognitif->gds_sit_autres_best=='oui')
    {
        $pdf->Cell(15, 20, 'X', 1, 0, 'C', 1);
        $pdf->Cell(15, 20, '', 1, 1, 'C');

    }
    else
    {
        $pdf->Cell(15, 20, '', 1, 0, 'C', 1);
        $pdf->Cell(15, 20, 'X', 1, 1, 'C');
    }

    $pdf->Cell(140, 12, "Score ", 1, 0, 'L');
    $pdf->Cell(30, 12, $TroubleCognitif->get_gds(), 1, 1, 'C');

}

if(in_array('iadl',$TroubleCognitif->suivi_type)) {


    $pdf->AddPage();

    $pdf->SetFontSize(14);

    $pdf->Cell(70, 14, '', 0, 0, 'C');
    $pdf->Cell(55, 14, 'IADL', 1, 1, 'C');
    $pdf->SetFontSize(12);

    $pdf->Ln();

    $pdf->Cell(60, 10, 'Capacit� � utiliser le t�l�phone : ', 'B', 1, 'L');
    if($TroubleCognitif->iadl_telephone=='tout')
    {
        $pdf->Cell(55, 12, "Je me sers du t�l�phone de ma propre initiative, cherche et compose les num�ros", 0, 1, 'L');

    }
    elseif($TroubleCognitif->iadl_telephone=="qq_no")
    {
        $pdf->Cell(55, 12, "Je compose un petit nombre de num�ros bien connus", 0, 1, 'L');
    }
    elseif($TroubleCognitif->iadl_telephone=="repond"){
        $pdf->Cell(55, 12, "Je r�ponds au t�l�phone mais n'appelle pas", 0, 1, 'L');
    }
    elseif($TroubleCognitif->iadl_telephone=="rien"){
        $pdf->Cell(55, 12, "Je suis incapable d'utiliser le t�l�phone", 0, 1, 'L');
    }

    $pdf->Cell(45, 10, 'Moyen de transport : ', 'B', 1, 'L');
    if($TroubleCognitif->iadl_transport=='tout')
    {
        $pdf->MultiCell(160, 10, "Je peux voyager seul(e) de facon ind�pendante (par les transports en commun ou avec ma propre voiture)", 0, 'L');

    }
    elseif($TroubleCognitif->iadl_transport=="taxi_seul")
    {
        $pdf->Cell(55, 12, "Je peux voyager seul(e) en taxi, pas en autobus", 0, 1, 'L');
    }
    elseif($TroubleCognitif->iadl_transport=="commun_acc")
    {
        $pdf->Cell(55, 12, "Je peux prendre les transports en commun si je suis accompagn�(e)", 0, 1, 'L');
    }
    elseif($TroubleCognitif->iadl_transport=="voiture_acc")
    {
        $pdf->Cell(55, 12, "Transport limit� au taxi ou � la voiture en �tant accompagn�(e)", 0, 1, 'L');
    }
    elseif($TroubleCognitif->iadl_transport=="rien")
    {
        $pdf->Cell(55, 12, "Je ne me deplace pas du tout", 0, 1, 'L');
    }



    $pdf->Cell(90, 10, 'Responsabilit� pour la prise des m�dicaments : ', 'B', 1, 'L');
    if($TroubleCognitif->iadl_med=='tout')
    {
        $pdf->Cell(55, 12, "Je m'occupe moi-m�me de la prise : dosage et horaire", 0, 1, 'L');

    }
    elseif($TroubleCognitif->iadl_med=="prend_seul")
    {
        $pdf->Cell(55, 12, "Je peux les prendre moi-m�me s'ils sont pr�par�s et dos�s", 0, 1, 'L');
    }
    elseif($TroubleCognitif->iadl_med=="rien")
    {
        $pdf->Cell(55, 12, "Je suis incapable de les prendre moi-m�me", 0, 1, 'L');
    }


    $pdf->Cell(55, 10, 'Capacit� � g�rer son budget : ', 'B', 1, 'L');
    if($TroubleCognitif->iadl_budget=='tout')
    {
        $pdf->MultiCell(160, 10, "Je suis totalement autonome (g�rer le budget, faire des ch�ques, payer des factures)", 0, 'L');

    }
    elseif($TroubleCognitif->iadl_budget=="jour")
    {
        $pdf->MultiCell(160, 10, "Je me d�brouille pour les d�penses au jour le jour, mais j'ai besoin d'aide pour g�rer mon budget � long terme (pour planifier les grosses d�penses)", 0, 'L');
    }
    elseif($TroubleCognitif->iadl_budget=="rien")
    {
        $pdf->Cell(160, 12, "Je suis incapable de g�rer l'argent n�cessaire � payer mes d�penses au jour le jour", 0, 1, 'L');
    }

    $pdf->Cell(50, 12, 'Score : ', 0, 0, 'L');

    $pdf->Cell(5, 12, $TroubleCognitif->get_iadl(), 0, 0, 'L');

}

if(in_array('horl',$TroubleCognitif->suivi_type)) {



    $pdf->Ln();
    $pdf->Ln();


    $pdf->SetFontSize(14);

    $pdf->Cell(70, 14, "", 0, 0, 'C');
    $pdf->Cell(55, 14, "Test de l'Horloge", 1, 1, 'C');
    $pdf->SetFontSize(12);
    $pdf->Ln();

    $pdf->Cell(30, 12, "R�sultat : ", 0, 0, 'L');

    if($TroubleCognitif->horloge=='10')
    {
        $pdf->Cell(55, 12, "Aiguilles correctement positionn�es", 0, 1, 'L');

    }
    elseif($TroubleCognitif->horloge=='9')
    {
        $pdf->Cell(55, 12, "Erreurs minimes", 0, 1, 'L');

    }
    elseif($TroubleCognitif->horloge=='8')
    {
        $pdf->Cell(55, 12, "Erreurs importantes", 0, 1, 'L');

    }

    elseif($TroubleCognitif->horloge=='7')
    {
        $pdf->Cell(55, 12, "Confusion des aiguilles", 0, 1, 'L');

    }
    elseif($TroubleCognitif->horloge=='6')
    {
        $pdf->Cell(55, 12, "Mauvaise position des aiguilles", 0, 1, 'L');

    }
    elseif($TroubleCognitif->horloge=='5')
    {
        $pdf->Cell(55, 12, "Chiffres intervertis", 0, 1, 'L');

    }
    elseif($TroubleCognitif->horloge=='4')
    {
        $pdf->Cell(55, 12, "Oubli des chiffres", 0, 1, 'L');

    }
    elseif($TroubleCognitif->horloge=='3')
    {
        $pdf->Cell(55, 12, "Chiffres � l'ext�rieur du cadran", 0, 1, 'L');

    }
    elseif($TroubleCognitif->horloge=='2')
    {
        $pdf->Cell(55, 12, "Horloge vaguement reconnaissable", 0, 1, 'L');

    }
    elseif($TroubleCognitif->horloge=='1')
    {
        $pdf->Cell(55, 12, "Absence d'essai ou essai non interpr�table", 0, 1, 'L');

    }
    $pdf->Cell(30, 12, "Score : ", 0, 0, 'L');
    $pdf->Cell(5, 12, $TroubleCognitif->horloge, 0, 0, 'L');

}



if(in_array('dubois',$TroubleCognitif->suivi_type)) {


    $pdf->AddPage();


    $pdf->Cell(70, 14, "", 0, 0, 'C');
    $pdf->Cell(55, 14, "Les 5 mots de Dubois", 1, 1, 'C');
    $pdf->SetFontSize(12);
    $pdf->Ln();

    $pdf->Cell(140, 12, 'ETAPE RAPPEL IMMEDIAT  ', 0, 1, 'L');
    $pdf->Cell(80, 12, 'Nombre de mots trouv�s sans indice :  ', 0, 0, 'L');
    $pdf->Cell(80, 12, $TroubleCognitif->dubois_immediatsi, 0, 1, 'L');
    $pdf->Cell(80, 12, 'Nombre de mots trouv�s avec indice :  ', 0, 0, 'L');
    $pdf->Cell(80, 12, $TroubleCognitif->dubois_immediatai, 0, 1, 'L');
    $pdf->Cell(25, 12, 'ETAPE RAPPEL DIFFERE ', 0, 1, 'L');
    $pdf->Cell(80, 12, 'Nombre de mots trouv�s sans indice :  ', 0, 0, 'L');
    $pdf->Cell(80, 12, $TroubleCognitif->dubois_diffsi, 0, 1, 'L');
    $pdf->Cell(80, 12, 'Nombre de mots trouv�s avec indice :  ', 0, 0, 'L');
    $pdf->Cell(80, 12, $TroubleCognitif->dubois_diffai, 0, 0, 'L');

}


$rep = $config->files_path."/tc/".$account->cabinet;
if(!mkdir($rep, 0775)){
    //echo 'impossible de cr�er '.$rep.'. Il doit exister...';
}


$myDateTime = DateTime::createFromFormat('m/d/Y', $TroubleCognitif->date);
$newDateString = $myDateTime->format('Y-m-d');

$file= $rep.'/TroubleCognitif_'.$newDateString.'_Doss_'.$dossier->numero.'_cab_'.$account->cabinet.'.pdf';


//Sauvegarde du PDF dans le fichier

$pdf->Output($file);

/*
 * NEW MAILER
 */
$mail = new PHPMailer(true);
try
{
    //Recipients
    #@@todo herve@@
    $mail->setFrom('contact@asalee.fr', 'Portail PSA - questionnaire de troubles cognitifs');
    $mail->addAttachment($file, 'TroubleCognitifDoss'.$dossier->numero.'cab'.$account->cabinet.'.pdf', 'application/pdf');

    $courriel = $_SESSION['id.email'];
    if(($courriel!='')&& ($courriel!='NULL'))
    {
        $mail->addAddress($courriel);
        $result = $mail->send();
        echo $result ? "Le formulaire compl�t� a �t� transmis sur votre boite mail<br>" : "Impossible d'envoyer le fichier sur votre boite mail ";
    }
}
catch (Exception $e)
{
    error_log('Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
}

//unlink($file);
