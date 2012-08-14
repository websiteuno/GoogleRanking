<?php

//ouvir la session
session_start();
//inclure le fichier connexion.php pour se connecter à la base de données.
include("connexion.php");

//déclaration des bibliothéque
if ( isset($_SESSION['connexion']) and strcmp($_SESSION['connexion'],"proxy")==0)
require_once('classes/moteurs_proxy.php');
else
require_once('classes/moteurs_direct.php');

if ( isset($_SESSION['connexion']) and strcmp($_SESSION['connexion'],"proxy")==0)

//************************************************************Execution sous proxy***************************************************************************
{

//initialiser tout les variables de cette page ensuite leurs attribuer des anti slashes à fin d'éviter les erreurs
$prenom="";
$nom="";
$couriel="";
$url="";
$mot_cle="";
$nbr_lignes="100";






//verifier si le formulaire a été bien envoyer ensuite chercher la position avec la fonction getpos
if (isset($_POST['url']) and isset($_POST['mot_cle']) and isset ($_POST['nbr_lignes']) and isset($_POST['moteur']))
{
	//pour eviter le piratage
	$url = htmlspecialchars($_POST['url']);
	$mot_cle = htmlspecialchars($_POST['mot_cle']);
	$nbr_lignes = htmlspecialchars($_POST['nbr_lignes']);
	$moteur = htmlspecialchars($_POST['moteur']);		
    //verifier le moteur de recherche choisi pour utiliser la fonction spécifique à chaque moteur
	if (strcmp($moteur,"google")==0)
    $position=google_getpos($url,$mot_cle,$nbr_lignes,$_SESSION['http'],$_SESSION['port']);
	else
	$position=yahoo_getpos($url,$mot_cle,$_SESSION['http'],$_SESSION['port']);
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
  <!-- Ici on mettra le contenu principal de la page (tout le texte quoi) -->
  <h1>Positionnement Rapide</h1>

  <form method="post" action="positionnement_rapide.php">
    <fieldset>
    <legend> Recherche de Position</legend>
    <table>
      <tr>
        <td class="td_1"><label for="url">URL :</label></td>
        <td class="td_1"><input type="text" name="url" id="url" value="<?php echo "$url" ?>"  tabindex="50"/></td>
      </tr>
      <tr>
        <td class="td_1"><label for="mot_cle">Mot clé :</label></td>
        <td class="td_1"><input type="text" name="mot_cle" id="mot_cle" value="<?php echo "$mot_cle" ?>"  tabindex="60"/></td>
      </tr>
      <tr>
        <td class="td_1"><label for="nbr_lignes">Nombres de lignes :</label></td>
        <td class="td_1"><input type="text" name="nbr_lignes" id="nbr_lignes" value="<?php echo "$nbr_lignes" ?>"  tabindex="60"/></td>
      </tr>
      <tr>
        <td class="td_1"><label for="moteur">Moteur de Recherche :</label></td>
        <td class="td_1"><input type="radio" name="moteur" value="google" id="google"  tabindex="70" checked="checked"/>
          <label for="google">Google</label>
          <br />
          <input type="radio" name="moteur" value="yahoo" id="yahoo"  tabindex="80"/>
          <label for="yahoo">Yahoo</label>
          <br />
        </td>
      </tr>
      <tr>
        <td class="td_1"><input type="reset"  tabindex="100"/></td>
        <td class="td_1"><input type="submit" accesskey="R" value="Afficher" tabindex="90"/></td>     
      </tr>
    </table>
    </fieldset>
    <?php
//verifier si la page à générer ou pas la variable position, si oui l'afficher
if (isset($position))
{?>
    <fieldset >
    <legend> Affichage du Resultat</legend>
    <table class="table_1">
      <tr>
        <th>Url</th>
        <th>Mot Clé</th>
        <th>Moteur</th>
        <th>Position</th>
      </tr>
      <?php
	  	//afficher le résultat
		//ouvrir un fichier temporaire
	  	$monfichier = fopen('tmp.txt', 'a+');
		
		//verifier si l'utilisateur a taper un url, un mot clé et choisi un moteur
		if ( (isset($_POST['url'])) and (isset($_POST['mot_cle'])) and (isset($_POST['moteur'])) and (strcmp($_POST['url'],"")!=0) and (strcmp($_POST['mot_cle'],"")!=0) and (strcmp($_POST['moteur'],"")!=0) )
		{
			//creer la ligne à inserrer dans le fichier
	  		$ligne ="<tr><td class='td_2'>".$_POST['url']."</td><td class='td_2'>".$_POST['mot_cle']."</td><td class='td_2'>".$_POST['moteur']."</td><td class='td_2'>$position</td></tr>";  
	  		//ecrire dans le fichier en inserant un retour chariot
			fwrite($monfichier,$ligne);
	  		fwrite($monfichier,"\r\n");
	    }
		
		//initialisation 
	  	$i=0;
	  	fseek($monfichier, 0); // On remet le curseur au début du fichier
	  	
		//boucle pour mettre les données du fichier dans un tableau
		while (!feof($monfichier)) 
		   	{
			 	$ligne_vue = fgets($monfichier);
			 	$tableau_lignes[$i]=$ligne_vue;
				$i++;
			}
				
		//afficher le tableau ligne par ligne en commençons par la fin		
	  	for ($j=($i-1);$j>=0;$j--)
	  		{
	  		 	echo $tableau_lignes[$j];
	  		}		
	  
	  	fclose($monfichier);
	  
	  ?>
    </table>
    </fieldset>
    <?php //fermer l'accolade du if de vérification de la variable position 
} ?>
  </form>
</div>
<?php include("squelette/piedPage.php"); ?>
</body>
</html>
<?php mysql_close(); // On n'oublie pas de fermer la connexion à MySQL ;o) ?>


<?php
}
else
//****************************************************************Execution Normal*****************************************************************************
{


//initialiser tout les variables de cette page ensuite leurs attribuer des anti slashes à fin d'éviter les erreurs
$prenom="";
$nom="";
$couriel="";
$url="";
$mot_cle="";
$nbr_lignes="100";






//verifier si le formulaire a été bien envoyer ensuite chercher la position avec la fonction getpos
if (isset($_POST['url']) and isset($_POST['mot_cle']) and isset ($_POST['nbr_lignes']) and isset($_POST['moteur']))
{
	//pour eviter le piratage
	$url = htmlspecialchars($_POST['url']);
	$mot_cle = htmlspecialchars($_POST['mot_cle']);
	$nbr_lignes = htmlspecialchars($_POST['nbr_lignes']);
	$moteur = htmlspecialchars($_POST['moteur']);		
    //verifier le moteur de recherche choisi pour utiliser la fonction spécifique à chaque moteur
	if (strcmp($moteur,"google")==0)
    $position=google_getpos($url,$mot_cle,$nbr_lignes);
	else
	$position=yahoo_getpos($url,$mot_cle);
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
  <!-- Ici on mettra le contenu principal de la page (tout le texte quoi) -->
  <h1>Positionnement Rapide</h1>
  <form method="post" action="positionnement_rapide.php">
    <fieldset>
    <legend> Recherche de Position</legend>
    <table>
      <tr>
        <td class="td_1"><label for="url">URL :</label></td>
        <td class="td_1"><input type="text" name="url" id="url" value="<?php echo "$url" ?>"  tabindex="50"/></td>
      </tr>
      <tr>
        <td class="td_1"><label for="mot_cle">Mot clé :</label></td>
        <td class="td_1"><input type="text" name="mot_cle" id="mot_cle" value="<?php echo "$mot_cle" ?>"  tabindex="60"/></td>
      </tr>
      <tr>
        <td class="td_1"><label for="nbr_lignes">Nombres de lignes :</label></td>
        <td class="td_1"><input type="text" name="nbr_lignes" id="nbr_lignes" value="<?php echo "$nbr_lignes" ?>"  tabindex="60"/></td>
      </tr>
      <tr>
        <td class="td_1"><label for="moteur">Moteur de Recherche :</label></td>
        <td class="td_1"><input type="radio" name="moteur" value="google" id="google"  tabindex="70" checked="checked"/>
          <label for="google">Google</label>
          <br />
          <input type="radio" name="moteur" value="yahoo" id="yahoo"  tabindex="80"/>
          <label for="yahoo">Yahoo</label>
          <br />
        </td>
      </tr>
      <tr>
        <td class="td_1"><input type="reset"  tabindex="100"/></td>
        <td class="td_1"><input type="submit" accesskey="R" value="Afficher" tabindex="90"/></td>     
      </tr>
    </table>
    </fieldset>
    <?php
//verifier si la page à générer ou pas la variable position, si oui l'afficher
if (isset($position))
{?>
    <fieldset >
    <legend> Affichage du Resultat</legend>
    <table class="table_1">
      <tr>
        <th>Url</th>
        <th>Mot Clé</th>
        <th>Moteur</th>
        <th>Position</th>
      </tr>
      <?php
	  	//afficher le résultat
		//ouvrir un fichier temporaire
	  	$monfichier = fopen('tmp.txt', 'a+');
		
		//verifier si l'utilisateur a taper un url, un mot clé et choisi un moteur
		if ( (isset($_POST['url'])) and (isset($_POST['mot_cle'])) and (isset($_POST['moteur'])) and (strcmp($_POST['url'],"")!=0) and (strcmp($_POST['mot_cle'],"")!=0) and (strcmp($_POST['moteur'],"")!=0) )
		{
			//creer la ligne à inserrer dans le fichier
	  		$ligne ="<tr><td class='td_2'>".$_POST['url']."</td><td class='td_2'>".$_POST['mot_cle']."</td><td class='td_2'>".$_POST['moteur']."</td><td class='td_2'>$position</td></tr>";  
	  		//ecrire dans le fichier en inserant un retour chariot
			fwrite($monfichier,$ligne);
	  		fwrite($monfichier,"\r\n");
	    }
		
		//initialisation 
	  	$i=0;
	  	fseek($monfichier, 0); // On remet le curseur au début du fichier
	  	
		//boucle pour mettre les données du fichier dans un tableau
		while (!feof($monfichier)) 
		   	{
			 	$ligne_vue = fgets($monfichier);
			 	$tableau_lignes[$i]=$ligne_vue;
				$i++;
			}
				
		//afficher le tableau ligne par ligne en commençons par la fin		
	  	for ($j=($i-1);$j>=0;$j--)
	  		{
	  		 	echo $tableau_lignes[$j];
	  		}		
	  
	  	fclose($monfichier);
	  
	  ?>
    </table>
    </fieldset>
    <?php //fermer l'accolade du if de vérification de la variable position 
} ?>
  </form>
</div>
<?php include("squelette/piedPage.php"); ?>
</body>
</html>
<?php mysql_close(); // On n'oublie pas de fermer la connexion à MySQL ;o) ?>


<?php
}

?>