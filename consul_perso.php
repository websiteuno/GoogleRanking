<?php
session_start(); // On démarre la session AVANT toute chose
include("connexion.php");

if ( isset($_POST['liste_dates_1']) and isset($_POST['liste_mots_cles_1']) and isset($_POST['moteur_1']) and $_POST['liste_dates_1']!=0 and $_POST['liste_mots_cles_1']!=0)
{
	$reponse=mysql_query("SELECT position FROM position WHERE id_mot_cle='".$_POST['liste_mots_cles_1']."' AND date='".$_POST['liste_dates_1']."' AND moteur='".$_POST['moteur_1']."'");
	if (mysql_num_rows($reponse)==0) $position="position introuvable";
	else {
			$donnees=mysql_fetch_array($reponse);
			$position=$donnees['position'];
		 }
}




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
  <h1>Consultation Personnalisé</h1>
  <!--**************************************************Chercher une position**********************************************************-->
  <form name="chercher_postion" action="consul_perso.php" method="post">
    <fieldset>
    <legend>Chercher une Position</legend>
    <table>
      <tr>
        <td class="td_1"><label for="moteur">Moteur de Recherche :</label></td>
        <td class="td_1"><input type="radio" name="moteur_1" value="google" id="google_1"  tabindex="2" checked="checked"/>
          <label for="google_1">Google</label>
          <br />
          <input type="radio" name="moteur_1" value="yahoo" id="yahoo_1"  tabindex="6"/>
          <label for="yahoo_1">Yahoo</label>
          <br />
        </td>
      </tr>
      <tr>
        <td class="td_1"><label for="liste_dates_1">Les Dates d'execution de <?php echo $_SESSION['prenom']; ?> :</label></td>
        <td class="td_1"><select class="select_1" name="liste_dates_1" id="liste_dates_1" tabindex="10" >
            <option value="0" selected="selected"></option>
            <?php 
							//afficher la liste des mots cles depuis la base de donnees de l'utilisateur connecté
							$reponse=mysql_query("select distinct(date) from position,mots_cles,sites,correspondance where mots_cles.id_site=sites.id AND mots_cles.id=position.id_mot_cle AND correspondance.id_site=sites.id AND correspondance.id_utilisateur='".$_SESSION['id']."'");
							if (mysql_num_rows($reponse))
							{
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
								
								//afficher le tableau trié    
                      			for($j=0;$j < $i;$j++)
                  			    {													
									$date=date('d/m/Y', $tab[$j]);
									$heure=date('H\h i\m\i\n s\s', $tab[$j]);
									echo '<option value="'.$tab[$j].'"';
									if (isset($_POST['liste_dates_1']) and ($_POST['liste_dates_1']==$tab[$j])) echo 'selected="selected"';
									echo '>'.$date.' à '.$heure;
									echo "</option>";
                    		    }    
							}	
	
							?>
          </select>
        </td>
      </tr>
      <tr>
        <td class="td_1"><label for="liste_mots_cles_1">Mots cles de <?php echo $_SESSION['prenom']; ?> :</label></td>
        <td class="td_1"><select name="liste_mots_cles_1" class="select_1" id="liste_mots_cles_1"  tabindex="20">
            <option value="0" selected="selected"></option>
            <?php 
							//afficher la liste des mots cles depuis la base de donnees de l'utilisateur connecté
							$reponse=mysql_query("select  nom,mots_cles.id as id, mots_cles.valeur as valeur from mots_cles,sites,correspondance where mots_cles.id_site=sites.id AND correspondance.id_site=sites.id AND correspondance.id_utilisateur='".$_SESSION['id']."'");
							if (mysql_num_rows($reponse))
							{
								while ($donnees=mysql_fetch_array($reponse))
								{
									echo '<option value="'.$donnees['id'].'"';
									if (isset($_POST['liste_mots_cles_1']) and $_POST['liste_mots_cles_1'] == $donnees['id']) echo 'selected="selected"';
									echo '>'.$donnees['valeur'].' du site '.$donnees['nom'] ; 
									echo "</option>";
								}
							}
							?>
          </select>
        </td>
      </tr>
      <tr>
        <td></td>
      </tr>      
      <tr>
        <td class="td_1"><input type="reset"  tabindex="30"/></td>
        <td class="td_1"><input type="submit" value="Rechercher" tabindex="40"/></td>
      </tr>
    </table>
    <table class="table_resultat">
      <?php 
	   if (isset($position)) 
		{ 
				echo "<h3>Affichage du résultat</h3>";
        	echo '<tr><td class="td_6">Postion :</td><td class="td_6">'.$position.'</td></tr>';
		}
		?>
    </table>
    </fieldset>
  </form>
  <!--**************************************************Chercher des dates**********************************************************-->
  <form name="chercher_dates" action="consul_perso.php" method="post" >
    <fieldset>
    <legend>Chercher des Dates</legend>
    <table>
      <tr>
        <td class="td_1"><label for="moteur">Moteur de Recherche :</label></td>
        <td class="td_1"><input type="radio" name="moteur_2" value="google" id="google_2"  tabindex="2" checked="checked"/>
          <label for="google_2">Google</label>
          <br />
          <input type="radio" name="moteur_2" value="yahoo" id="yahoo_2"  tabindex="6"/>
          <label for="yahoo_2">Yahoo</label>
          <br />
        </td>
      </tr>
      <tr>
        <td class="td_1"><label for="liste_mots_cles_2">Mots cles de <?php echo $_SESSION['prenom']; ?> :</label></td>
        <td class="td_1"><select name="liste_mots_cles_2" class="select_1" id="liste_mots_cles_2"  tabindex="20">
            <option value="0" selected="selected"></option>
            <?php 
							//afficher la liste des mots cles depuis la base de donnees de l'utilisateur connecté
							$reponse=mysql_query("select  nom,mots_cles.id as id, mots_cles.valeur as valeur from mots_cles,sites,correspondance where mots_cles.id_site=sites.id AND correspondance.id_site=sites.id AND correspondance.id_utilisateur='".$_SESSION['id']."'");
							if (mysql_num_rows($reponse))
							{
								while ($donnees=mysql_fetch_array($reponse))
								{
									echo '<option value="'.$donnees['id'].'"';
									if (isset($_POST['liste_mots_cles_2']) and $_POST['liste_mots_cles_2'] == $donnees['id']) echo 'selected="selected"';
									echo '>'.$donnees['valeur'].' du site '.$donnees['nom'] ; 
									echo "</option>";
								}
							}
							?>
          </select>
        </td>
      </tr>
      
      <tr>
        <td><label for="position_2" >Position :</label></td>
        <?php 
	if (isset($_POST['position_2']) )
	echo '<td><input class="select_1" name="position_2" type="text" id="position_2" value="'.$_POST['position_2'].'"/></td>';
    else
	echo '<td><input class="select_1" name="position_2" type="text" id="position_2" /></td>';
	?>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td class="td_1"><input type="reset"  tabindex="30"/></td>
        <td class="td_1"><input type="submit" value="Rechercher" tabindex="40"/></td>
      </tr>
    </table>
    <table class="table_resultat">
      <?php 
if ( isset($_POST['position_2']) and isset($_POST['liste_mots_cles_2']) and isset($_POST['moteur_2']) and strcmp($_POST['position_2'],"")!=0 and $_POST['liste_mots_cles_2']!=0)
{
	echo "<h3>Affichage du résultat</h3>";
	$reponse=mysql_query("SELECT date FROM position WHERE id_mot_cle='".$_POST['liste_mots_cles_2']."' AND position='".$_POST['position_2']."'");
	if (mysql_num_rows($reponse)==0) echo '<tr><td class="td_6">Dates introuvables</td></tr>';
	else {
			$i=0;
			while($donnees=mysql_fetch_array($reponse))
			{
				$i++;
				$date_afficher=date('d/m/Y', $donnees['date']);	
				$heure_afficher=date('H\h i\m\i\n s\s', $donnees['date']);
           	 	echo '<tr><td class="td_6">Date '.$i.' :</td><td class="td_6">'.$date_afficher.' à '.$heure_afficher.'</td></tr>';
			}
		}
}

		?>
    </table>
    </fieldset>
  </form>
<!--*******************************************************************chercher des mots clés******************************************************************-->  
  <form name="chercher_mots_cles" action="consul_perso.php" method="post">
    <fieldset>
    <legend>Chercher des mots clés</legend>
    <table>
      <tr>
        <td class="td_1"><label for="moteur">Moteur de Recherche :</label></td>
        <td class="td_1"><input type="radio" name="moteur_3" value="google" id="google_3"  tabindex="2" checked="checked"/>
          <label for="google_3">Google</label>
          <br />
          <input type="radio" name="moteur_3" value="yahoo" id="yahoo_3"  tabindex="6"/>
          <label for="yahoo_3">Yahoo</label>
          <br />
        </td>
      </tr>
      <tr>
        <td class="td_1"><label for="liste_dates_3">Les Dates d'execution de <?php echo $_SESSION['prenom']; ?> :</label></td>
        <td class="td_1" ><select class="select_1" name="liste_dates_3" id="liste_dates_3" tabindex="10" >
            <option value="0" selected="selected"></option>
            <?php 
							//afficher la liste des mots cles depuis la base de donnees de l'utilisateur connecté
							$reponse=mysql_query("select distinct(date) from position,mots_cles,sites,correspondance where mots_cles.id_site=sites.id AND mots_cles.id=position.id_mot_cle AND correspondance.id_site=sites.id AND correspondance.id_utilisateur='".$_SESSION['id']."'");
							if (mysql_num_rows($reponse))
							{
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
								
								//afficher le tableau trié    
                      			for($j=0;$j < $i;$j++)
                  			    {													
									$date=date('d/m/Y', $tab[$j]);
									$heure=date('H\h i\m\i\n s\s', $tab[$j]);
									echo '<option value="'.$tab[$j].'"';
									if (isset($_POST['liste_dates_3']) and ($_POST['liste_dates_3']==$tab[$j])) echo 'selected="selected"';
									echo '>'.$date.' à '.$heure;
									echo "</option>";
                    		    }    
							}	
	
							?>
          </select>
        </td>
      </tr>
      <tr>
        <td class="td_1"><label for="liste_sites">Sites de <?php echo $_SESSION['prenom']; ?>:</label></td>
        <td class="td_1"><select name="liste_sites" class="select_1" id="liste_sites"  onchange="document.forms['modi_supp'].submit();" tabindex="40">
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
      <tr><td></td></tr>
      <tr>
        <td><label for="position_3" >Position :</label></td>
        <?php 
	if (isset($_POST['position_3']) )
	echo '<td><input class="select_1" name="position_3" type="text" id="position_3" value="'.$_POST['position_3'].'"/></td>';
    else
	echo '<td><input class="select_1" name="position_3" type="text" id="position_3" /></td>';
	?>
      </tr>    
      <tr>
        <td class="td_1"><input type="reset"  tabindex="30"/></td>
        <td class="td_1"><input type="submit" value="Rechercher" tabindex="40"/></td>
      </tr>  
    <table class="table_resultat">
      <?php 
if ( isset($_POST['liste_sites']) and isset($_POST['position_3']) and isset($_POST['liste_dates_3']) and isset($_POST['moteur_3']) and strcmp($_POST['position_3'],"")!=0 and $_POST['liste_dates_3']!=0 and $_POST['liste_sites']!=0)
{
	echo "<h3>Affichage du résultat</h3>";
	$reponse=mysql_query("SELECT valeur FROM position,mots_cles WHERE position.id_mot_cle=mots_cles.id AND date='".$_POST['liste_dates_3']."' AND position='".$_POST['position_3']."' AND id_site='".$_POST['liste_sites']."'");
	if (mysql_num_rows($reponse)==0) echo '<tr><td class="td_6">Mots Clés introuvables</td></tr>';
	else {
			$i=0;
			while($donnees=mysql_fetch_array($reponse))
			{
				$i++;
           	 	echo '<tr><td class="td_6">Mot Clé '.$i.' :</td><td class="td_6">'.$donnees['valeur'].'</td></tr>';
			}
		}
}
		?>
    </table>          
    </table>
    </fieldset>
  </form>
</div>
<?php include("squelette/piedPage.php"); ?>
</body>
</html>
<?php mysql_close(); // On n'oublie pas de fermer la connexion à MySQL ;o) ?>
