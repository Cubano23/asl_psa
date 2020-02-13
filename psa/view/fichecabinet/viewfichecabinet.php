<?php 

error_reporting(E_ERROR); // EA. Les script ne traite pas des valeurs initiales ce qui génère les Notices 22-12-2014
require_once("bean/beanparser/htmltags.php");
require_once("view/jsgenerator/jsgenerator.php");
require_once("view/common/vars.php");
 ?>


<script type="text/javascript" >


</script>
<?php
	global $rowsList;
$mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
			'08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');


echo "<b>Données au ".date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

	$req="SELECT region from account WHERE region!='' GROUP by region order by region";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
	
	$reg=array();
?>

  <table border='1'>
  <tr>
    <td width='20%'><b>Nombre total de patient(e)s éligibles</b></td>
	<td align= 'center'><b>&nbsp;Total&nbsp;</b></td>
	
	<?php 
	while(list($region)=mysql_fetch_row($res)){
		if($region!=""){
			$reg[]=$region;
			
			echo "<td align='center'><b>&nbsp;Total $region&nbsp;</b></td>";
			$total_pat[$region]=$total_sein[$region]=$total_cogni[$region]=$total_colon[$region]=$total_uterus[$region]=0;
			$total_diab[$region]=$total_HTA[$region]=0;
		}
	}

		$total_pat["total"]=$total_sein["total"]=$total_cogni["total"]=$total_colon["total"]=$total_uterus["total"]=0;
		$total_diab["total"]=$total_HTA["total"]=0;

        foreach($rowsList as $rows)
        {
			if($rows["region"]!=""){
				if($_SESSION["national"]==1){
					echo "<td align='center' width='0'><b>&nbsp;".$rows['nom_cab']."&nbsp;</b></td>";
				}
				elseif($_SESSION["region"]==1){
					if($rows["region"]==$_SESSION["nom_region"]){
						echo "<td align='center' width='0'><b>&nbsp;".$rows['nom_cab']."&nbsp;</b></td>";
					}
				}
			}

			if($rows["region"]!=""){
				$total_pat[$rows["region"]]+=$rows['total_pat'];
				$total_sein[$rows["region"]]+=$rows['total_sein'];
				$total_cogni[$rows["region"]]+=$rows['total_cogni'];
				$total_colon[$rows["region"]]+=$rows['total_colon'];
				$total_uterus[$rows["region"]]+=$rows['total_uterus'];
				$total_diab[$rows["region"]]+=$rows['total_diab2'];
				$total_HTA[$rows["region"]]+=$rows['total_HTA'];

				$total_pat["total"]+=$rows['total_pat'];
				$total_sein["total"]+=$rows['total_sein'];
				$total_cogni["total"]+=$rows['total_cogni'];
				$total_colon["total"]+=$rows['total_colon'];
				$total_uterus["total"]+=$rows['total_uterus'];
				$total_diab["total"]+=$rows['total_diab2'];
				$total_HTA["total"]+=$rows['total_HTA'];
			}

        }
    ?>
  </tr>
    <tr>
      <td>Nombre total de patients <sup>1</sup></td>
      <td align='right' nowrap><?php echo number_format($total_pat["total"], 0, '.', ' '); ?></td>
	  
	  <?php 
	  
	foreach($reg as $region){
	    echo "<td align='right' nowrap>".number_format($total_pat[$region], 0, '.', ' ')."</td>";
	}
		  
	  foreach($rowsList as $rows)
        {
			if($_SESSION["national"]==1){
				if($rows["region"]!=""){
					echo "<td align='right' nowrap>";
					if($rows['total_pat']>0)
					{
						echo number_format($rows['total_pat'], 0, '.', ' ')."</td>";
					}
					else
					{
						echo "ND</td>";
					}
				}
			}
			elseif($_SESSION["region"]==1){
				if($rows["region"]==$_SESSION["nom_region"]){
					echo "<td align='right' nowrap>";
					if($rows['total_pat']>0)
					{
						echo number_format($rows['total_pat'], 0, '.', ' ')."</td>";
					}
					else
					{
						echo "ND</td>";
					}
				}
			}
        }
    ?>
    </tr>
    <tr>
      <td>Dépistage du cancer du sein <sup>2</sup></td>
      <td align='right' nowrap><?php echo number_format($total_sein["total"], 0, '.', ' '); ?></td>
	  
	  <?php
	  
	foreach($reg as $region){
		echo "<td align='right' nowrap>".number_format($total_sein[$region], 0, '.', ' ')."</td>";
	}

	foreach($rowsList as $rows)
        {
			if($_SESSION["national"]==1){
				if($rows["region"]!=""){
		            echo "<td align='right' nowrap>";
					if($rows['total_sein']>0)
					{
						echo number_format($rows['total_sein'], 0, '.', ' ')."</td>";
					}
					else
					{
					    echo "ND</td>";
					}
				}
			}
			elseif($_SESSION["region"]==1){
				if($rows["region"]==$_SESSION["nom_region"]){
		            echo "<td align='right' nowrap>";
					if($rows['total_sein']>0)
					{
						echo number_format($rows['total_sein'], 0, '.', ' ')."</td>";
					}
					else
					{
					    echo "ND</td>";
					}
				}
			}
        }
    ?>
    </tr>
    <tr>
      <td>Dépistage des troubles cognitifs <sup>3</sup></td>
      <td align='right' nowrap><?php echo number_format($total_cogni["total"], 0, '.', ' '); ?></td>
	<?php
	foreach($reg as $region){
		echo "<td align='right' nowrap>".number_format($total_cogni[$region], 0, '.', ' ')."</td>";
	}

	  foreach($rowsList as $rows)
        {
			if($_SESSION["national"]==1){
				if($rows["region"]!=""){
		            echo "<td align='right' nowrap>";
					if($rows['total_cogni']>0)
					{
						echo number_format($rows['total_cogni'], 0, '.', ' ')."</td>";
					}
					else
					{
					    echo "ND</td>";
					}
				}
			}
			elseif($_SESSION["region"]==1){
				if($rows["region"]==$_SESSION["nom_region"]){
		            echo "<td align='right' nowrap>";
					if($rows['total_cogni']>0)
					{
						echo number_format($rows['total_cogni'], 0, '.', ' ')."</td>";
					}
					else
					{
					    echo "ND</td>";
					}
				}
			}
        }
    ?>
    </tr>
    <tr>
      <td>Dépistage du cancer du colon <sup>4</sup></td>
      <td align='right' nowrap><?php echo number_format($total_colon["total"], 0, '.', ' '); ?></td>
	  
	<?php
	foreach($reg as $region){
	    echo "<td align='right' nowrap>".number_format($total_colon[$region], 0, '.', ' ')."</td>";
	}

        foreach($rowsList as $rows)
        {
			if($_SESSION["national"]==1){
				if($rows["region"]!=""){
		            echo "<td align='right' nowrap>";
					if($rows['total_colon']>0)
					{
						echo number_format($rows['total_colon'], 0, '.', ' ')."</td>";
					}
					else
					{
					    echo "ND</td>";
					}
				}
			}
			elseif($_SESSION["region"]==1){
				if($rows["region"]==$_SESSION["nom_region"]){
		            echo "<td align='right' nowrap>";
					if($rows['total_colon']>0)
					{
						echo number_format($rows['total_colon'], 0, '.', ' ')."</td>";
					}
					else
					{
					    echo "ND</td>";
					}
				}
			}
        }
    ?>
    </tr>
    <tr>
      <td>Dépistage du cancer du col de l'utérus <sup>5</sup></td>
      <td align='right' nowrap><?php echo number_format($total_uterus["total"], 0, '.', ' '); ?></td>
	  
	<?php
	
	foreach($reg as $region){
		echo "<td align='right' nowrap>".number_format($total_uterus[$region], 0, '.', ' ')."</td>";
	}

	foreach($rowsList as $rows)
        {
			if($_SESSION["national"]==1){
				if($rows["region"]!=""){
		            echo "<td align='right' nowrap>";
					if($rows['total_uterus']>0)
					{
						echo number_format($rows['total_uterus'], 0, '.', ' ')."</td>";
					}
					else
					{
					    echo "ND</td>";
					}
				}
			}
			elseif($_SESSION["region"]==1){
				if($rows["region"]==$_SESSION["nom_region"]){
		            echo "<td align='right' nowrap>";
					if($rows['total_uterus']>0)
					{
						echo number_format($rows['total_uterus'], 0, '.', ' ')."</td>";
					}
					else
					{
					    echo "ND</td>";
					}
				}
			}
        }
    ?>
    </tr>
    <tr>
      <td>Diabétiques de type 2 <sup>6</sup></td>
      <td align='right' nowrap><?php echo number_format($total_diab["total"], 0, '.', ' '); ?></td>
	<?php
	
	foreach($reg as $region){
		echo "<td align='right' nowrap>".number_format($total_diab[$region], 0, '.', ' ')."</td>";
	}

	foreach($rowsList as $rows)
        {
			if($_SESSION["national"]==1){
				if($rows["region"]!=""){
		            echo "<td align='right' nowrap>";
					if($rows['total_diab2']>0)
					{
						echo number_format($rows['total_diab2'], 0, '.', ' ')."</td>";
					}
					else
					{
					    echo "ND</td>";
					}
				}
			}
			elseif($_SESSION["region"]==1){
				if($rows["region"]==$_SESSION["nom_region"]){
		            echo "<td align='right' nowrap>";
					if($rows['total_diab2']>0)
					{
						echo number_format($rows['total_diab2'], 0, '.', ' ')."</td>";
					}
					else
					{
					    echo "ND</td>";
					}
				}
			}
        }
    ?>
    </tr>
    <tr>
      <td>RCVA<sup>7</sup></td>
      <td align='right' nowrap><?php echo number_format($total_HTA["total"], 0, '.', ' '); ?></td>
	  
	<?php
	foreach($reg as $region){
		echo "<td align='right' nowrap>".number_format($total_HTA[$region], 0, '.', ' ')."</td>";
	}

	foreach($rowsList as $rows)
        {
			if($_SESSION["national"]==1){
				if($rows["region"]!=""){
		            echo "<td align='right' nowrap>";
					if($rows['total_diab2']>0)
					{
						echo number_format($rows['total_HTA'], 0, '.', ' ')."</td>";
					}
					else
					{
					    echo "ND</td>";
					}
				}
			}
			elseif($_SESSION["region"]==1){
				if($rows["region"]==$_SESSION["nom_region"]){
		            echo "<td align='right' nowrap>";
					if($rows['total_diab2']>0)
					{
						echo number_format($rows['total_HTA'], 0, '.', ' ')."</td>";
					}
					else
					{
					    echo "ND</td>";
					}
				}
			}
        }
    ?>
    </tr>
  </table>

<?php
$annee0=2006;
$mois0=3;

$annee=date('Y');
$mois=date('m');

$mois--;


if($mois<3)
{
	$annee--;
	$mois=12;
}
elseif(($mois>=3)&&($mois<6))
{
	$mois=3;
}
elseif(($mois>=6)&&($mois<9))
{
	$mois=6;
}
elseif(($mois>=9)&&($mois<12))
{
	$mois=9;
}

$jour[3]=$jour[12]=31;
$jour[6]=$jour[9]=30;

while(($annee>$annee0)||(($annee==$annee0)&&($mois>=$mois0)))
{
	    if($mois<10)
	    {
	        $date=$annee.'-0'.$mois.'-'.$jour[$mois];
	    }
	    else
	    {
	        $date=$annee.'-'.$mois.'-'.$jour[$mois];
	    }
		tableau($date, $reg);

		$mois=$mois-3;

		if($mois<=0)
		{
		    $mois=$mois+12;
		    $annee--;
		}
}


function tableau($date, $reg)
{
	global $rowsList;

$mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
			'08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

$tab_date=split('-', $date);

echo "<br><br><b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";

	$req="SELECT histo_account.dmaj as dmaj, histo_account.total_pat as total_pat, histo_account.total_sein as total_sein, ".
		 "histo_account.total_cogni as total_cogni, histo_account.total_colon as total_colon, histo_account.total_uterus as total_uterus ".
		 ", histo_account.total_diab2 as total_diab2, histo_account.total_HTA as total_HTA, histo_account.cabinet as cabinet, region ".
		 "FROM histo_account, account WHERE histo_account.dmaj<='$date 23:59:59' AND account.cabinet=histo_account.cabinet ".
		 "and region!=''";

	$req.=" ORDER BY nom_cab, histo_account.dmaj";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");

//echo $req;

foreach ($rowsList as $rows)
{
    $tpat[$rows['cabinet']]=0;
    $tsein[$rows['cabinet']]=0;
    $tcogni[$rows['cabinet']]=0;
    $tcolon[$rows['cabinet']]=0;
    $tuterus[$rows['cabinet']]=0;
    $tdiab[$rows['cabinet']]=0;
    $tHTA[$rows['cabinet']]=0;
}

$cabinet_sauv="";

	while(list($dmaj, $total_pat_anc, $total_sein_anc, $total_cogni_anc, $total_colon_anc, $total_uterus_anc,
		 $total_diab2_anc, $total_HTA_anc, $cab, $region)=mysql_fetch_row($res))
		 {
			if($cabinet_sauv!=$cab)
			{
			    $cabinet_sauv=$cab;
			    $tcabinet[]=$cab;
			    $tpat[$cab]=$total_pat_anc;
			    $tsein[$cab]=$total_sein_anc;
			    $tcogni[$cab]=$total_cogni_anc;
			    $tcolon[$cab]=$total_colon_anc;
			    $tuterus[$cab]=$total_uterus_anc;
			    $tdiab[$cab]=$total_diab2_anc;
			    $tHTA[$cab]=$total_HTA_anc;
				
				if($region!=""){
					$total_pat[$region]=$total_sein[$region]=$total_cogni[$region]=$total_colon[$region]=$total_uterus[$region]=0;
					$total_diab[$region]=$total_HTA[$region]=0;
				}
				
			}
			else
			{
			    $tpat[$cab]=$total_pat_anc;
			    $tsein[$cab]=$total_sein_anc;
			    $tcogni[$cab]=$total_cogni_anc;
			    $tcolon[$cab]=$total_colon_anc;
			    $tuterus[$cab]=$total_uterus_anc;
			    $tdiab[$cab]=$total_diab2_anc;
			    $tHTA[$cab]=$total_HTA_anc;

			}
			
		 }

$total_pat["total"]=$total_sein["total"]=$total_cogni["total"]=$total_colon["total"]=$total_uterus["total"]=$total_diab["total"]=$total_HTA["total"]=0;

foreach ($rowsList as $rows)
{
	if($rows["region"]!=""){
		$total_pat[$rows["region"]]+=$tpat[$rows['cabinet']];
		$total_sein[$rows["region"]]+=$tsein[$rows['cabinet']];
		$total_cogni[$rows["region"]]+=$tcogni[$rows['cabinet']];
		$total_colon[$rows["region"]]+=$tcolon[$rows['cabinet']];
		$total_uterus[$rows["region"]]+=$tuterus[$rows['cabinet']];
		$total_diab[$rows["region"]]+=$tdiab[$rows['cabinet']];
		$total_HTA[$rows["region"]]+=$tHTA[$rows['cabinet']];

		$total_pat["total"]+=$tpat[$rows['cabinet']];
		$total_sein["total"]+=$tsein[$rows['cabinet']];
		$total_cogni["total"]+=$tcogni[$rows['cabinet']];
		$total_colon["total"]+=$tcolon[$rows['cabinet']];
		$total_uterus["total"]+=$tuterus[$rows['cabinet']];
		$total_diab["total"]+=$tdiab[$rows['cabinet']];
		$total_HTA["total"]+=$tHTA[$rows['cabinet']];
	}
}
?>
	

  <table border='1'>
  <tr>
    <td width="20%"><b>Nombre total de patient(e)s éligibles</b></td>
	<td align= 'center'><b>&nbsp;Total&nbsp;</b></td>
	
	<?php
	
	foreach($reg as $region){
		echo "<td align= 'center'><b>&nbsp;Total $region&nbsp;</b></td>";
	}

	foreach($rowsList as $rows)
	{
		if($_SESSION["national"]==1){
			if($rows["region"]!=""){
				echo "<td align='center' width='0'><b>&nbsp;".$rows['nom_cab']."&nbsp;</b></td>";
			}
		}
		elseif($_SESSION["region"]==1){
			if($rows["region"]==$_SESSION["nom_region"]){
				echo "<td align='center' width='0'><b>&nbsp;".$rows['nom_cab']."&nbsp;</b></td>";
			}
		}
	}
    ?>
  </tr>
    <tr> 
      <td>Nombre total de patients <sup>1</sup></td>
      <td align='right' nowrap><?php echo number_format($total_pat["total"], 0, '.', ' '); ?></td>
	  
	<?php
	
	foreach($reg as $region){
	    echo "<td align='right' nowrap>".number_format($total_pat[$region], 0, '.', ' ')."</td>";
	}

	foreach($rowsList as $rows)
        {
			if($_SESSION["national"]==1){
				if($rows["region"]!=""){
					echo "<td align='right' nowrap>";
					if($tpat[$rows['cabinet']]==0)
					{
						echo "ND";
					}
					else
					{
						echo number_format($tpat[$rows['cabinet']], 0, '.', ' ');
					}

					echo "</td>";
				}
			}
			elseif($_SESSION["region"]==1){
				if($rows["region"]==$_SESSION["nom_region"]){
					echo "<td align='right' nowrap>";
					if($tpat[$rows['cabinet']]==0)
					{
						echo "ND";
					}
					else
					{
						echo number_format($tpat[$rows['cabinet']], 0, '.', ' ');
					}

					echo "</td>";
				}
			}
        }
    ?>
    </tr>
    <tr>
      <td>Dépistage du cancer du sein <sup>2</sup></td>
      <td align='right' nowrap><?php echo number_format($total_sein["total"], 0, '.', ' '); ?></td>
	  
	<?php
	
	foreach($reg as $region){
		echo "<td align='right' nowrap>".number_format($total_sein[$region], 0, '.', ' ')."</td>";
	}

	foreach($rowsList as $rows)
	{
		if($_SESSION["national"]==1){
			if($rows["region"]!=""){
				echo "<td align='right' nowrap>";

				if($tsein[$rows['cabinet']]==0)
				{
					echo "ND";
				}
				else
				{
					echo number_format($tsein[$rows['cabinet']], 0, '.', ' ');
				}
				echo "</td>";
			}
		}
		elseif($_SESSION["region"]==1){
			if($rows["region"]==$_SESSION["nom_region"]){
				echo "<td align='right' nowrap>";

				if($tsein[$rows['cabinet']]==0)
				{
					echo "ND";
				}
				else
				{
					echo number_format($tsein[$rows['cabinet']], 0, '.', ' ');
				}
				echo "</td>";
			}
		}
    }
    ?>
    </tr>
    <tr>
      <td>Dépistage des troubles cognitifs <sup>3</sup></td>
      <td align='right' nowrap><?php echo number_format($total_cogni["total"], 0, '.', ' '); ?></td>
	  
	  <?php
	  
	foreach($reg as $region){
		echo "<td align='right' nowrap>".number_format($total_cogni[$region], 0, '.', ' ')."</td>";
	}

	foreach($rowsList as $rows)
	{
		if($_SESSION["national"]==1){
			if($rows["region"]!=""){
				echo "<td align='right' nowrap>";

				if($tcogni[$rows['cabinet']]==0)
				{
					echo "ND";
				}
				else
				{
					echo number_format($tcogni[$rows['cabinet']], 0, '.', ' ');
				}

				echo "</td>";
			}
		}
		elseif($_SESSION["region"]==1){
			if($rows["region"]==$_SESSION["nom_region"]){
				echo "<td align='right' nowrap>";

				if($tcogni[$rows['cabinet']]==0)
				{
					echo "ND";
				}
				else
				{
					echo number_format($tcogni[$rows['cabinet']], 0, '.', ' ');
				}

				echo "</td>";
			}
		}
	}
    ?>
    </tr>
    <tr>
      <td>Dépistage du cancer du colon <sup>4</sup></td>
      <td	  align='right' nowrap><?php echo number_format($total_colon["total"], 0, '.', ' '); ?></td>
	  
	<?php
	foreach($reg as $region){
		echo "<td align='right' nowrap>".number_format($total_colon[$region], 0, '.', ' ')."</td>";
	}

        foreach($rowsList as $rows)
        {
			if($_SESSION["national"]==1){
				if($rows["region"]!=""){
					echo "<td align='right' nowrap>";

					if($tcolon[$rows['cabinet']]==0)
					{
						echo "ND";
					}
					else
					{
						echo number_format($tcolon[$rows['cabinet']], 0, '.', ' ');
					}

					echo "</td>";
				}
			}
			elseif($_SESSION["region"]==1){
				if($rows["region"]==$_SESSION["nom_region"]){
					echo "<td align='right' nowrap>";

					if($tcolon[$rows['cabinet']]==0)
					{
						echo "ND";
					}
					else
					{
						echo number_format($tcolon[$rows['cabinet']], 0, '.', ' ');
					}

					echo "</td>";
				}
			}
        }
    ?>
    </tr>
    <tr>
      <td>Dépistage du cancer du col de l'utérus <sup>5</sup></td>
      <td align='right' nowrap><?php echo number_format($total_uterus["total"], 0, '.', ' '); ?></td>
	  
	<?php
	foreach($reg as $region){
		echo "<td align='right' nowrap>".number_format($total_uterus[$region], 0, '.', ' ')."</td>";
	}
	
        foreach($rowsList as $rows)
        {
			if($_SESSION["national"]==1){
				if($rows["region"]!=""){
					echo "<td align='right' nowrap>";

					if($tuterus[$rows['cabinet']]==0)
					{
						echo "ND";
					}
					else
					{
						echo number_format($tuterus[$rows['cabinet']], 0, '.', ' ');
					}

					echo "</td>";
				}
			}
			elseif($_SESSION["region"]==1){
				if($rows["region"]==$_SESSION["nom_region"]){
					echo "<td align='right' nowrap>";

					if($tuterus[$rows['cabinet']]==0)
					{
						echo "ND";
					}
					else
					{
						echo number_format($tuterus[$rows['cabinet']], 0, '.', ' ');
					}

					echo "</td>";
				}
			}
        }
    ?>
    </tr>
    <tr>
      <td>Diabétiques de type 2 <sup>6</sup></td>
      <td align='right' nowrap><?php echo number_format($total_diab["total"], 0, '.', ' '); ?></td>
	  
	<?php
	
	foreach($reg as $region){
		echo "<td align='right' nowrap>".number_format($total_diab[$region], 0, '.', ' ')."</td>";
	}

        foreach($rowsList as $rows)
        {
			if($_SESSION["national"]==1){
				if($rows["region"]!=""){
					echo "<td align='right' nowrap>";

					if($tdiab[$rows['cabinet']]==0)
					{
						echo "ND";
					}
					else
					{
						echo number_format($tdiab[$rows['cabinet']], 0, '.', ' ');
					}

					echo "</td>";
				}
			}
			elseif($_SESSION["region"]==1){
				if($rows["region"]==$_SESSION["nom_region"]){
					echo "<td align='right' nowrap>";

					if($tdiab[$rows['cabinet']]==0)
					{
						echo "ND";
					}
					else
					{
						echo number_format($tdiab[$rows['cabinet']], 0, '.', ' ');
					}

					echo "</td>";
				}
			}
        }
    ?>
    </tr>
    <tr>
      <td>RCVA <sup>7</sup></td>
      <td align='right' nowrap><?php echo number_format($total_HTA["total"], 0, '.', ' '); ?></td>

	<?php
	
	foreach($reg as $region){
		echo "<td align='right' nowrap>".number_format($total_HTA[$region], 0, '.', ' ')."</td>";
	}

	foreach($rowsList as $rows)
	{
		if($_SESSION["national"]==1){
			if($rows["region"]!=""){
				echo "<td align='right' nowrap>";

				if($tHTA[$rows['cabinet']]==0)
				{
					echo "ND";
				}
				else
				{
					echo number_format($tHTA[$rows['cabinet']], 0, '.', ' ');
				}

				echo "</td>";
			}
		}
		elseif($_SESSION["region"]==1){
			if($rows["region"]==$_SESSION["nom_region"]){
				echo "<td align='right' nowrap>";

				if($tHTA[$rows['cabinet']]==0)
				{
					echo "ND";
				}
				else
				{
					echo number_format($tHTA[$rows['cabinet']], 0, '.', ' ');
				}

				echo "</td>";
			}
		}
	}
    ?>
    </tr>
  </table>

<?php
}
?>

  <table border=0>
  <tr>
    <td><br></td>
  </tr>
    <tr>
      <td><sup>1</sup> Nombre de patients dont un des médecins du cabinet est médecin traitant</td>
    </tr>
    <tr>
      <td><sup>2</sup> Femmes de 50 à 74 ans sans facteur de risque et dont un des médecins du cabinet est médecin traitant</td>
    </tr>
    <tr>
      <td><sup>3</sup> Patients de plus de 75 ans vivant à domicile + patients proposés par les médecins</td>
    </tr>
    <tr>
      <td><sup>4</sup> Patients entre 50 et 74 ans sans facteur de risques</td>
    </tr>
    <tr>
      <td><sup>5</sup> Patientes de 20 à 65 ans sauf patientes à haut risque ni hystérectomisées ni vierges</td>
    </tr>
    <tr>
      <td><sup>6</sup> Nombre de diabétiques de type 2 dont un des médecins est médecin traitant</td>
    </tr>
    <tr>
      <td><sup>7</sup> Nombre de patients dont un des m&eacute;decins est m&eacute;decin traitant ayant au  moins 2 facteurs de risque cardio-vasculaire dont 1 modifiable</td>
    </tr>
  </table>

