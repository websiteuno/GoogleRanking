<!-- ****************************************************************************************************************************************************************-->
<!--un commentaire general vous allez remarquer que chaque requette presente ici est exécutée selon le moteur choisi-->
<?php
session_start(); // On démarre la session AVANT toute chose
include("connexion.php");// on inclue une connexion	
?>
<!-- ****************************************************************************************************************************************************************-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Plate Forme de Référencement</title>
<link href="Style/Style.css" rel="stylesheet" type="text/css" />
<script language="javascript" >
<!--cette fonction est enclenché lors d'un click sur le lien affichage par date-->

function affichage_site()
{
// peut être google a été selectionné
if (document.forms[0].moteur[0].checked)
	{
	 if ( document.getElementById('date_debut').options[document.getElementById('date_debut').selectedIndex].value < document.getElementById('date_fin').options[document.getElementById('date_fin').selectedIndex].value  && document.getElementById('date_debut').options[document.getElementById('date_debut').selectedIndex].value!=0) 
		self.location.href="variation.php?type_affichage=2&moteur=1&date_debut="+document.getElementById('date_debut').options[document.getElementById('date_debut').selectedIndex].value+"&date_fin="+document.getElementById('date_fin').options[document.getElementById('date_fin').selectedIndex].value;
	 else alert ("veuillez choisir un date debut et une date fin à condition que date debut soit inférieur à date fin");		
	}	
//alors peut être yahoo a été selectionné
else if (document.forms[0].moteur[1].checked)
	{
	 if ( document.getElementById('date_debut').options[document.getElementById('date_debut').selectedIndex].value < document.getElementById('date_fin').options[document.getElementById('date_fin').selectedIndex].value && document.getElementById('date_debut').options[document.getElementById('date_debut').selectedIndex].value!=0) 
		self.location.href="variation.php?type_affichage=2&moteur=2&date_debut="+document.getElementById('date_debut').options[document.getElementById('date_debut').selectedIndex].value+"&date_fin="+document.getElementById('date_fin').options[document.getElementById('date_fin').selectedIndex].value;
	 else alert ("veuillez choisir un date debut et une date fin à condition que date debut soit inférieur à date fin");		
	}	
//afficher une alert pour dire qu'aucun des moteur a ètè selectionner
else alert("selectionner un ou plusieurs moteurs de recherche");
}

//c'est une fonction du on click du lien execution par mot clé
//envoyer vers la page execution l'id du mot clé selectionné et le numéro du moteur selectionné 1:google, 2:yahoo,3:les 2 moteurs
function affichage_mot_cle()
{

//peut être google a été selectionné
if (document.forms[0].moteur[0].checked)
	{
	 if (document.getElementById('date_debut').options[document.getElementById('date_debut').selectedIndex].value < document.getElementById('date_fin').options[document.getElementById('date_fin').selectedIndex].value && document.getElementById('date_debut').options[document.getElementById('date_debut').selectedIndex].value!=0 ) 
		self.location.href="variation.php?type_affichage=3&moteur=1&date_debut="+document.getElementById('date_debut').options[document.getElementById('date_debut').selectedIndex].value+"&date_fin="+document.getElementById('date_fin').options[document.getElementById('date_fin').selectedIndex].value;
	 else alert ("veuillez choisir un date debut et une date fin à condition que date debut soit inférieur à date fin");		
	}	
//alors peut être yahoo a été selectionné
else if (document.forms[0].moteur[1].checked)
	{
	 if ( document.getElementById('date_debut').options[document.getElementById('date_debut').selectedIndex].value < document.getElementById('date_fin').options[document.getElementById('date_fin').selectedIndex].value && document.getElementById('date_debut').options[document.getElementById('date_debut').selectedIndex].value!=0 ) 
		self.location.href="variation.php?type_affichage=3&moteur=2&date_debut="+document.getElementById('date_debut').options[document.getElementById('date_debut').selectedIndex].value+"&date_fin="+document.getElementById('date_fin').options[document.getElementById('date_fin').selectedIndex].value;
	 else alert ("veuillez choisir un date debut et une date fin à condition que date debut soit inférieur à date fin");		
	}	
//afficher une alert pour dire qu'aucun des moteur a ètè selectionner
else alert("selectionner un ou plusieurs moteurs de recherche ");
}
</script>
</head>
<!-- ****************************************************************************************************************************************************************-->
<body>
<?php include ("squelette/titre.php"); ?>
<?php include ("squelette/menuGlobal.php");?>
<?php include ("squelette/menuContextuel.php");?>
<div id="corps">
  <form action="variation.php" method="post">
    <!--**********************************************************************Affichage Principal*************************************************************-->
    <?php 
//verifier si l'utilsateur n'a rien choisi
if (    ( !isset($_GET['type_affichage']) and !isset($_POST['liste_dates']) and !isset($_POST['liste_sites'])  and !isset($_POST['liste_mots_cles']) ) or  ( isset($_GET['type_affichage']) and $_GET['type_affichage']==0 )   )
{
?>
    <h1>Variation de Positionnement</h1>


    <fieldset>
    <legend>Données</legend>
    <table>
      <tr>
        <td class="td_1"><label for="moteur">Moteur de Recherche :</label></td>
        <td class="td_1"><input type="radio" name="moteur" value="google" id="google"  tabindex="10" checked="checked"/>
          <label for="google">Google</label>
          <br />
          <input type="radio" name="moteur" value="yahoo" id="yahoo"  tabindex="20"/>
          <label for="yahoo">Yahoo</label>
          <br />
        </td>
      </tr>
    </table>
    <table class="table_1">
      <tr>
        <td class="td_1"><label class="label" for="date_debut">Date Debut :</label></td>
        <td class="td_1"><label class="label" for="date_fin">Date fin :</label></td>
      </tr>
      <tr>
        <td class="td_1"><select class="select_1" name="date_debut" id="date_debut" tabindex="50" >
            <option value="0" selected="selected"></option>
            <?php 
							//afficher la liste des dates depuis la base de donnees de l'utilisateur connecté
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
									echo '<option value="'.$tab[$j].'"';
									if (isset($_POST['date_debut']) and ($_POST['date_fin']==$tab[$j])) echo 'selected="selected"';
									echo '>'.$date;
									echo "</option>";
                    		    }  
								
							}
							?>
          </select>
        </td>
        <td class="td_1"><select class="select_1" name="date_fin" id="date_fin" tabindex="50" >
            <option value="0" selected="selected"></option>
            <?php 
							//afficher la liste des dates depuis la base de donnees de l'utilisateur connecté
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
									echo '<option value="'.$tab[$j].'"';
									if (isset($_POST['date_fin']) and ($_POST['date_fin']==$tab[$j])) echo 'selected="selected"';
									echo '>'.$date;
									echo "</option>";
                    		    }  
							}
							?>
          </select>
        </td>
      </tr>
    </table>
    </fieldset>
    <table class="table_1">
      <tr>
        <td><a class="a1" onclick="affichage_site();">Variation Par Site</a></td>
        <td><a class="a1" onclick="affichage_mot_cle();">Variation Par Mot Clé</a></td> 
      </tr>
    </table>
    <?php 
//fermeture de l'accolade du if de verification si on a encore rien choisi
}
?>
    <!-- *******************************************************************Affichage par site*******************************************************-->
    <?php 
//verifier si l'utilsateur a choisi l'affichage par site
if (     ( isset($_GET['type_affichage']) and $_GET['type_affichage']==2 ) or isset($_POST['liste_sites'])    )
{
//c'est pour garder la valeur de moteur
if (isset($_GET['moteur'])) echo '<input type="hidden" name="moteur" value='.$_GET['moteur'].'/>';
if (isset($_POST['moteur'])) echo '<input type="hidden" name="moteur" value='.$_POST['moteur'].'/>';
if (isset($_GET['date_debut'])) echo '<input type="hidden" name="date_debut" value="'.$_GET['date_debut'].'"/>';
if (isset($_POST['date_debut'])) echo '<input type="hidden" name="date_debut" value="'.$_POST['date_debut'].'"/>';
if (isset($_GET['date_fin'])) echo '<input type="hidden" name="date_fin" value="'.$_GET['date_fin'].'"/>';
if (isset($_POST['date_fin'])) echo '<input type="hidden" name="date_fin" value="'.$_POST['date_fin'].'"/>';
?>
	    <h1>Variation de Positionnement</h1>
    <fieldset>
    <legend>Variation par site</legend>
    <table class="table_1">
      <tr>
        <td class="td_1"><label for="liste_sites">Les Sites de <?php echo $_SESSION['prenom']; ?>:</label></td>
        <td class="td_1"><select name="liste_sites" class="select_1"  id="liste_sites"  onchange="document.forms[0].submit();" tabindex="40">
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
    </table>
    <br />
    <?php

// verfier si on a bien choisi un site    
if (isset($_POST['liste_sites']) and $_POST['liste_sites']!=0)  
{	


// On met dans une variable le nombre de lignes qu'on veut par page
$nbr_lignes_page = 10; 
// On récupère le nombre total de lignes
	
if ((isset($_POST['moteur']) and $_POST['moteur']==2) or (isset($_GET['moteur']) and $_GET['moteur']==2) )  
	$reponse=mysql_query('SELECT  distinct(mots_cles.id) as id,valeur,moteur,nom  FROM sites,mots_cles,position WHERE position.id_mot_cle=mots_cles.id AND sites.id=mots_cles.id_site AND sites.id="'.$_POST['liste_sites'].'"  AND moteur="yahoo" ' );  		
 else
	$reponse=mysql_query('SELECT distinct(mots_cles.id) as id,valeur,moteur,nom FROM sites,mots_cles,position WHERE position.id_mot_cle=mots_cles.id AND sites.id=mots_cles.id_site AND sites.id="'.$_POST['liste_sites'].'" AND moteur="google" ');  
	
	$total_lignes=0;
	while ($donnees=mysql_fetch_array($reponse))
	{		
		$reponse_1=mysql_query("SELECT * FROM position WHERE date='".$_POST['date_debut']."' AND id_mot_cle='".$donnees['id']."' AND moteur='".$donnees['moteur']."'");  		
		$reponse_2=mysql_query("SELECT * FROM position WHERE date='".$_POST['date_fin']."' AND id_mot_cle='".$donnees['id']."' AND moteur='".$donnees['moteur']."'");  			
	
		if (mysql_num_rows($reponse_1)!=0 and mysql_num_rows($reponse_2)!=0)
			{	
			$donnees_1=mysql_fetch_array($reponse_1);
			$donnees_2=mysql_fetch_array($reponse_2);	
			$total_lignes=$total_lignes+1;							 
			}
	}

// On calcule le nombre de pages à créer
$nbr_pages  = ceil($total_lignes / $nbr_lignes_page);

echo '<label for="page_site">Numéros de Pages : </label>';
echo '<select name="page_site" id="page_site" onchange="document.forms[0].submit();" ><option value="1" selected="selected">Page 1</option>';
// Puis on fait une boucle pour écrire les liens vers chacune des pages
for ($i = 2 ; $i <= $nbr_pages ; $i++)
	{	
	echo '<option value="'.$i.'"';
	if (isset($_POST['page_site']) and $_POST['page_site'] == $i) echo 'selected="selected"';
	echo '> Page '.$i ; 
    echo "</option>";	
	
	}
echo "</select> <p></br></p>";


if (isset($_POST['page_site']))
	{	
        $page = $_POST['page_site']; // On récupère le numéro de la page indiqué dans l'adresse (livreor.php?page_date=4)
	}
else // La variable n'existe pas, c'est la première fois qu'on charge la page
	{
        $page = 1; // On se met sur la page 1 (par défaut)
	}
 
 
// On calcule le numéro du premier message qu'on prend pour le LIMIT de MySQL
$premiere_ligne_afficher = ($page - 1) * $nbr_lignes_page;  
 
 
	//verifier s'il y'aura un affichage ou non
 if ((isset($_POST['moteur']) and $_POST['moteur']==2) or (isset($_GET['moteur']) and $_GET['moteur']==2) )  
	$reponse=mysql_query('SELECT distinct(mots_cles.id) as id,valeur,moteur,nom FROM position,sites,mots_cles WHERE position.id_mot_cle=mots_cles.id AND sites.id=mots_cles.id_site AND sites.id="'.$_POST['liste_sites'].'"  AND moteur="yahoo" ' );  		
 else
	$reponse=mysql_query('SELECT  distinct(mots_cles.id) as id,valeur,moteur,nom  FROM sites,mots_cles,position WHERE position.id_mot_cle=mots_cles.id AND  sites.id=mots_cles.id_site AND sites.id="'.$_POST['liste_sites'].'" AND moteur="google" ');  	
	$verif=0;
	while ($donnees=mysql_fetch_array($reponse))
	{		
		$reponse_1=mysql_query("SELECT * FROM position WHERE date='".$_POST['date_debut']."' AND id_mot_cle='".$donnees['id']."' AND moteur='".$donnees['moteur']."'");  		
		$reponse_2=mysql_query("SELECT * FROM position WHERE date='".$_POST['date_fin']."' AND id_mot_cle='".$donnees['id']."' AND moteur='".$donnees['moteur']."'");  		
			
	
		if (mysql_num_rows($reponse_1)!=0 and mysql_num_rows($reponse_2)!=0)
				$verif=1;
		
	}	
if ($verif==1)
{
?>
    <table class="table_1">
      <tr>
        <th>Mot Clé</th>
        <th>Site</th>
        <th>Moteur</th>
        <?php 		
		$date_1=date('d/m/Y', $_POST['date_debut']);
		$date_2=date('d/m/Y', $_POST['date_fin']);
		echo '<th>Position du '.$date_1.'</th> <th>Position du '.$date_2.'</th>'; ?>
        <th>Variation</th>
      </tr>
      </tr>
      
      <?php
 if ((isset($_POST['moteur']) and $_POST['moteur']==2) or (isset($_GET['moteur']) and $_GET['moteur']==2) )  
	$reponse=mysql_query('SELECT distinct(mots_cles.id) as id,valeur,moteur,nom FROM sites,mots_cles,position WHERE position.id_mot_cle=mots_cles.id AND sites.id=mots_cles.id_site AND sites.id="'.$_POST['liste_sites'].'"  AND moteur="yahoo" LIMIT ' . $premiere_ligne_afficher . ', ' . $nbr_lignes_page );  		
 else
	$reponse=mysql_query('SELECT distinct(mots_cles.id) as id,valeur,moteur,nom  FROM sites,mots_cles,position WHERE position.id_mot_cle=mots_cles.id AND sites.id=mots_cles.id_site AND sites.id="'.$_POST['liste_sites'].'" AND moteur="google" LIMIT ' . $premiere_ligne_afficher . ', ' . $nbr_lignes_page );  			


	while ($donnees=mysql_fetch_array($reponse))
	{		
		$reponse_1=mysql_query("SELECT * FROM position WHERE date='".$_POST['date_debut']."' AND id_mot_cle='".$donnees['id']."' AND moteur='".$donnees['moteur']."'");  		
		$reponse_2=mysql_query("SELECT * FROM position WHERE date='".$_POST['date_fin']."' AND id_mot_cle='".$donnees['id']."' AND moteur='".$donnees['moteur']."'");  		
			
	
		if (mysql_num_rows($reponse_1)!=0 and mysql_num_rows($reponse_2)!=0)
			{
			 $donnees_2=mysql_fetch_array($reponse_2);	
			 $donnees_1=mysql_fetch_array($reponse_1);
			 $variation=($donnees_1['position'] - $donnees_2['position']);			
			 echo '<tr><td class="td_2">'.$donnees['valeur'].'</td><td class="td_2">'.$donnees['nom'].'</td><td class="td_2">'.$donnees['moteur'].'</td><td class="td_2">'.$donnees_1['position'].'</td> <td class="td_2">'.$donnees_2['position'].'</td><td class="td_2">'.$variation.'</td></tr>';
			}
	}
 
}
?>
    </table>
    <?php
//fermeture de l'accolade du if de verification	si on a bien choisi un site
}

?>
    <br />
    <!--Lien de retour vers choix du type d'affichage-->
    <a class="a2" href="variation.php?type_affichage=0">Retour</a>
    </fieldset>
    <?php 
//fermeture de l'accolade du if de verification si on est bien dans affichage par site
}

?>
    <!-- *****************************************************************************************Affichage par mot clé***************************************************************-->
    <?php
//verifier si l'utilsateur a choisi l'affichage par mot clé
if (     ( isset($_GET['type_affichage']) and $_GET['type_affichage']==3 ) or isset($_POST['liste_mots_cles'])    )
{
//c'est pour garder la valeur de moteur
if (isset($_GET['moteur'])) echo '<input type="hidden" name="moteur" value='.$_GET['moteur'].'/>';
if (isset($_POST['moteur'])) echo '<input type="hidden" name="moteur" value='.$_POST['moteur'].'/>';
if (isset($_GET['date_debut'])) echo '<input type="hidden" name="date_debut" value="'.$_GET['date_debut'].'"/>';
if (isset($_POST['date_debut'])) echo '<input type="hidden" name="date_debut" value="'.$_POST['date_debut'].'"/>';
if (isset($_GET['date_fin'])) echo '<input type="hidden" name="date_fin" value="'.$_GET['date_fin'].'"/>';
if (isset($_POST['date_fin'])) echo '<input type="hidden" name="date_fin" value="'.$_POST['date_fin'].'"/>';
?>
	    <h1>Variation de Positionnement</h1>
    <fieldset>
    <legend>Variation par Mot Clé</legend>
    <table class="table_1">
      <tr>
        <td class="td_1"><label for="liste_mots_cles">Mots cles de <?php echo $_SESSION['prenom']; ?> :</label></td>
        <td class="td_1"><select name="liste_mots_cles" class="select_1" id="liste_mots_cles" onchange="document.forms[0].submit();" tabindex="50">
            <option value="0" selected="selected"></option>
            <?php 
							//afficher la liste des mots cles depuis la base de donnees de l'utilisateur connecté
							$reponse=mysql_query("select  nom,mots_cles.id as id, mots_cles.valeur as valeur from mots_cles,sites,correspondance where mots_cles.id_site=sites.id AND correspondance.id_site=sites.id AND correspondance.id_utilisateur='".$_SESSION['id']."'");
							if (mysql_num_rows($reponse))
							{
								while ($donnees=mysql_fetch_array($reponse))
								{
									echo '<option value="'.$donnees['id'].'"';
									if (isset($_POST['liste_mots_cles']) and $_POST['liste_mots_cles'] == $donnees['id']) echo 'selected="selected"';
									echo '>'.$donnees['valeur'].' du site '.$donnees['nom'] ; 
									echo "</option>";
								}
							}
							?>
          </select>
        </td>
    </table>
    <br />
    <?php

// verfier si on a bien choisi un mot clé    
if (isset($_POST['liste_mots_cles']) and $_POST['liste_mots_cles']!=0  )  
{

	//verifier s'il y'aura un affichage ou non
  if ((isset($_POST['moteur']) and $_POST['moteur']==2) or (isset($_GET['moteur']) and $_GET['moteur']==2) )  
	$reponse=mysql_query('SELECT * FROM position,sites,mots_cles WHERE position.id_mot_cle=mots_cles.id AND sites.id=mots_cles.id_site AND mots_cles.id="'.$_POST['liste_mots_cles'].'"  AND moteur="yahoo" ' );  		
 else
	$reponse=mysql_query('SELECT * FROM position,sites,mots_cles WHERE position.id_mot_cle=mots_cles.id AND sites.id=mots_cles.id_site AND mots_cles.id="'.$_POST['liste_mots_cles'].'" AND moteur="google" ');  
	
	$verif=0;
	while ($donnees=mysql_fetch_array($reponse))
	{		
		$reponse_1=mysql_query("SELECT * FROM position WHERE date='".$_POST['date_debut']."' AND id_mot_cle='".$donnees['id']."' AND moteur='".$donnees['moteur']."'");  		
		$reponse_2=mysql_query("SELECT * FROM position WHERE date='".$_POST['date_fin']."' AND id_mot_cle='".$donnees['id']."' AND moteur='".$donnees['moteur']."'");  		
			
	
		if (mysql_num_rows($reponse_1)!=0 and mysql_num_rows($reponse_2)!=0)
				$verif=1;
		
	}	
if ($verif==1)
{
?>
    <table class="table_1">
      <tr>
        <th>Mot Clé</th>
        <th>Site</th>
        <th>Moteur</th>
        <?php 	
		$date_1=date('d/m/Y', $_POST['date_debut']);
		$date_2=date('d/m/Y', $_POST['date_fin']);
		echo '<th>Position du '.$date_1.'</th> <th>Position du '.$date_2.'</th>'; ?>
        <th>Variation</th>
      </tr>
      <?php
if ((isset($_POST['moteur']) and $_POST['moteur']==2) or (isset($_GET['moteur']) and $_GET['moteur']==2) )  
	$reponse=mysql_query('SELECT distinct(mots_cles.id) as id,valeur,moteur,nom FROM position,sites,mots_cles WHERE position.id_mot_cle=mots_cles.id AND sites.id=mots_cles.id_site AND mots_cles.id="'.$_POST['liste_mots_cles'].'"  AND moteur="yahoo" ' );  		
 else
	$reponse=mysql_query('SELECT distinct(mots_cles.id) as id,valeur,moteur,nom FROM position,sites,mots_cles WHERE position.id_mot_cle=mots_cles.id AND sites.id=mots_cles.id_site AND mots_cles.id="'.$_POST['liste_mots_cles'].'" AND moteur="google" ');  			
    	
	while ($donnees=mysql_fetch_array($reponse))
	{		
		$reponse_1=mysql_query("SELECT * FROM position WHERE date='".$_POST['date_debut']."' AND id_mot_cle='".$donnees['id']."' AND moteur='".$donnees['moteur']."'");  		
		$reponse_2=mysql_query("SELECT * FROM position WHERE date='".$_POST['date_fin']."' AND id_mot_cle='".$donnees['id']."' AND moteur='".$donnees['moteur']."'");  		
			
	
		if (mysql_num_rows($reponse_1)!=0 and mysql_num_rows($reponse_2)!=0)
			{
			 $donnees_2=mysql_fetch_array($reponse_2);	
			 $donnees_1=mysql_fetch_array($reponse_1);
			 $variation=($donnees_1['position'] - $donnees_2['position']);			
			 echo '<tr><td class="td_2">'.$donnees['valeur'].'</td><td class="td_2">'.$donnees['nom'].'</td><td class="td_2">'.$donnees['moteur'].'</td><td class="td_2">'.$donnees_1['position'].'</td> <td class="td_2">'.$donnees_2['position'].'</td><td class="td_2">'.$variation.'</td></tr>';
			}
	}
}
?>
    </table>
    <?php
//fermeture de l'accolade du if de verification	si on a bien choisi un mot clé
}

?>
    <!--Lien de retour vers choix du type d'affichage-->
    <br />
    <a class="a2" href="variation.php?type_affichage=0">Retour</a>
    </fieldset>
    <?php 
//fermeture de l'accolade du if de verification si on est bien dans affichage par mot Clé
}
?>
    <!-- ****************************************************************************************************************************************************************-->
    <br />
  </form>
</div>
<?php include("squelette/piedPage.php"); ?>
</body>
</html>
<?php mysql_close(); // On n'oublie pas de fermer la connexion à MySQL ;o) ?>
