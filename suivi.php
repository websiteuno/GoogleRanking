<?php
session_start(); // On démarre la session AVANT toute chose
include("connexion.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Plate Forme de Référencement</title>
<link href="Style/Style.css" rel="stylesheet" type="text/css" />  
</head>
<body>
<?php include ("squelette/titre.php"); ?>
<?php include ("squelette/menuGlobal.php");?>
<?php include ("squelette/menuContextuel.php");?>
<div id="corps">
<h1>Le Suivi</h1>
<form action="suivi.php" method="post">
<fieldset>
<legend>Données du suivi</legend>
<table class="table_1">
      <tr>
        <td class="td_1"><label for="liste_sites">Sites de <?php echo $_SESSION['prenom']; ?>:</label></td>
        <td class="td_1"><select name="liste_sites" id="liste_sites"  onchange="document.forms[0].submit();" tabindex="40">
            <option value="0" selected="selected"></option>
            <?php 
							//afficher la liste des sites depuis la base de donnees de l'utilisateur connecté
							$reponse=mysql_query("select * from sites,correspondance where sites.id=correspondance.id_site and id_utilisateur='".$_SESSION['id']."'");
							if (mysql_num_rows($reponse))
							{
								while ($donnees=mysql_fetch_array($reponse))
								{
									echo '<option value="'.$donnees['id'].'"';
									if (isset($_POST['liste_sites']) and $_POST['liste_sites'] == $donnees['id']) echo 'selected="selected"';
									echo '>'.$donnees['nom'];
									echo "</option>";
								}
							}
							?>
          </select>
        </td>
      </tr>
<?php

if (isset ($_POST['liste_sites']))
{
	$reponse=mysql_query("select * from mots_cles where id_site='".$_POST['liste_sites']."'");
	if (mysql_num_rows($reponse))
		{
		 $i=1;		 
		 $j=1;
		 while ($donnees=mysql_fetch_array($reponse))
		 	{
			  if ($j==1) echo "<tr>";
		  	  echo '<td>M'.$i.' = '.$donnees['valeur'].'</td>';
			  $i++;
			  $j++;
			  if ($j==4) {echo "</tr>";$j=1;}
		 	}
    	}
}
?>		 
</table>
</fieldset>

      

<?php 
if (isset ($_POST['liste_sites']) and $_POST['liste_sites']!=0)
{

//ici on va mettre tout les executions de l'utilisateur connecté dans un tableau qu'on trira
$reponse=mysql_query("select distinct(date) from position,mots_cles,sites,correspondance where mots_cles.id_site=sites.id AND mots_cles.id=position.id_mot_cle AND correspondance.id_site=sites.id AND correspondance.id_utilisateur='".$_SESSION['id']."' AND sites.id='".$_POST['liste_sites']."'");
if (mysql_num_rows($reponse))
 {
	echo '<fieldset><legend>Le Suivi</legend><table class="table_1">';
	echo '<th>Dates</th>';
   	$i=1;
	$reponse_2=mysql_query("select * from mots_cles where id_site='".$_POST['liste_sites']."'");
	if (mysql_num_rows($reponse_2))
		{		 
		 while ($donnees_2=mysql_fetch_array($reponse_2))
		 	{
		  	  echo '<th>M'.$i.'</th>';
			  $i++;
		 	}
    	}
	


     //on va faire un affichage du select par date croissante
							
	 //inserrer dans un tableau
	 $i=0;
	 while ($donnees=mysql_fetch_array($reponse))
		{	
  			$tab[$i]=$donnees["date"];
   			$i=$i+1;
 		}
							
		//trier le tableau
      for($j=0;$j < $i;$j++)
   	  for($h=$j+1;$h < $i;$h++)
     	{if ($tab[$j] > $tab[$h]) 
          {
			$tmp=$tab[$j];
            $tab[$j]= $tab[$h];
            $tab[$h]=$tmp;                                  
           }
       	}


//on remplie mt le suivi ligne par ligne selon les dates d'exections

//initaliser le tableau des positions precedentes tous à 0
$reponse=mysql_query("select * from mots_cles where id_site='".$_POST['liste_sites']."'");
if (mysql_num_rows($reponse))
	{
	 $e=0;	
	 while ($donnees=mysql_fetch_array($reponse))		
		{
	 	  $tab_pos[$e]=9999;
		  $e++;
	 	}
	}	

for($j=0;$j < $i;$j++)
	{
	  echo '<tr>';	
      $date=date('d/m/Y', $tab[$j]);
      echo '<td class="td_11">'.$date.'</td>';
	  $reponse=mysql_query("select * from mots_cles where id_site='".$_POST['liste_sites']."'");
	  if (mysql_num_rows($reponse))
		{		 
		 $k=0;
		 while ($donnees=mysql_fetch_array($reponse))
		 	{					
				$reponse_1=mysql_query("SELECT position FROM position WHERE position.id_mot_cle='".$donnees['id']."' AND date='".$tab[$j]."'");
			    if (mysql_num_rows($reponse_1))
					{		
					 $donnees_1=mysql_fetch_array($reponse_1);
					 if ($donnees_1['position']==(-1))
					 	echo '<td class="inexistant" >'.$donnees_1['position'].'</td>';						 
					 else if ($tab_pos[$k]==9999)
					 	echo '<td class="td_10">'.$donnees_1['position'].'</td>';
					 else if ($tab_pos[$k] > $donnees_1['position'])
					 	echo '<td class="plus" >'.$donnees_1['position'].'</td>';
					 else if ($tab_pos[$k]<$donnees_1['position'])
					 	echo '<td class="moins" >'.$donnees_1['position'].'</td>';
					 else
					 echo '<td class="egal" >'.$donnees_1['position'].'</td>';	
					 $tab_pos[$k]=$donnees_1['position'];
					 $k++;
					}
				else echo '<td class="td_10"></td>';	
			}
		}	
	  echo '</tr>';
	}


 }	

}			
?>
</table>
</fieldset>
</form>
</div>
<?php include("squelette/piedPage.php"); ?>
</body>
</html>
<?php mysql_close(); // On n'oublie pas de fermer la connexion à MySQL ;o) ?>
