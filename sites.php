<?php
session_start(); // On démarre la session AVANT toute chose
include("connexion.php"); // On inclue le code de la connexion



//Enregistrer un nouveau site
//Pour être sur que l'action est celle d'un enregistrement on verifie d'abord l'existance de ces variables
if ( isset($_POST['nom']) and isset($_POST['url']) )
{	
	//ensuite on verifie que l'utilisateur a bien ecrit quelque chose dans les champs d'ajout et qu'il n'a pas choisi entre suppression et modification
	if (strcmp($_POST['nom'],"")!=0 and strcmp($_POST['url'],"")!=0 )
	{
	//pour éviter le piratage
	$nom = mysql_real_escape_string(htmlspecialchars($_POST['nom']));
	$url = mysql_real_escape_string(htmlspecialchars($_POST['url']));	

	//ici on inserre dans la table sites notre site
	mysql_query("INSERT INTO sites VALUES('', '" . $nom . "', '" . $url . "')");
	//on cherche l'id avec lequel c'est enregistrer le site
	$reponse=mysql_query("select id from sites where nom='".$nom."' and url='".$url."'");
	$donnees=mysql_fetch_array($reponse);
	//ensuite on enregistre l'id du site dans la table correspondance pour savoir à quel utilisateurs appartient ce site
	mysql_query("INSERT INTO correspondance VALUES('".$donnees['id']."', '" . $_SESSION['id'] ."')");						
	}
}



//modifier un site
if (isset($_POST['execution']) and isset($_POST['nom_modifier']) and isset($_POST['url_modifier']) and isset($_POST['liste_sites']) and isset($_POST['tmp']))
{
	if  (strcmp($_POST['execution'],"modifier")==0 and $_POST['liste_sites']!=0 and strcmp($_POST['nom_modifier'],"")!=0 and strcmp($_POST['url_modifier'],"")!=0 and $_POST['liste_sites']==$_POST['tmp'])
    {
   //pour éviter le piratage
	$nom_modifier = mysql_real_escape_string(htmlspecialchars($_POST['nom_modifier']));
	$url_modifier = mysql_real_escape_string(htmlspecialchars($_POST['url_modifier']));
	$liste_sites = mysql_real_escape_string(htmlspecialchars($_POST['liste_sites']));
	
    mysql_query("UPDATE sites SET nom='" . $nom_modifier . "', url='" . $url_modifier . "' WHERE id='" . $liste_sites . "'");		
	}
}


//supprimer un site
if (isset($_POST['execution']) and isset($_POST['liste_sites']) and isset($_POST['tmp']))
{
	if (strcmp($_POST['execution'],"supprimer")==0 and $_POST['liste_sites']!=0 and $_POST['liste_sites']==$_POST['tmp'] )
	{
		//pour éviter le piratage	
		$liste_sites = mysql_real_escape_string(htmlspecialchars($_POST['liste_sites']));
		
		//effacer de la table correspondance les données qui relient le site à l'utilisateur
	    mysql_query("DELETE FROM correspondance WHERE id_site='" . $liste_sites . "'");
		//effacer de la table site le site correspondant
   	 	mysql_query("DELETE FROM sites WHERE id='" . $liste_sites . "'");
		
		//effacer de la table position chaqu'une des positions correspondantes à un mots clé du site qu'on veut effacer
		$reponse=mysql_query("SELECT * FROM mots_cles WHERE id_site='".$liste_sites."'");
		while($donnees=mysql_fetch_array($reponse))
		{
			mysql_query("DELETE FROM position WHERE id_mot_cle='".$donnees['id']."'");	
		}
		
		//finallement effacer tout les mots clés du site
		mysql_query("DELETE FROM mots_cles WHERE id_site='".$liste_sites."'");
		$_POST['liste_sites']=0;
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
  <h1> Gérer vos Sites </h1>

  <form method="post" action="sites.php" name="ajout">
    <fieldset>
    <legend>Ajouter un Site</legend>
    <table >
      <tr>
        <td class="td_1"><label for="nom">Nom :</label></td>
        <td class="td_1"><input type="text" name="nom" id="nom" tabindex="10" /></td>
      </tr>
      <tr>
        <td class="td_1"><label for="url">Url :</label></td>
        <td class="td_1"><input type="text" name="url" id="url"  tabindex="20" /></td>
      </tr>
      <tr>
        <td>
      </tr>
      <tr>
        <td class="td_1"><input type="reset"  tabindex="30"/></td>
        <td class="td_1"><input type="submit" value="Ajouter" tabindex="30"/></td>
      </tr>
    </table>
    </fieldset>
  </form>
  <form name="modi_supp" action="sites.php" method="post">
    <fieldset>
    <legend>Modifier ou supprimer un Site</legend>
    <table >
      <tr>
        <td class="td_1"><label for="liste_sites">Sites de <?php echo $_SESSION['prenom']; ?>:</label></td>
        <td class="td_1"><select name="liste_sites" id="liste_sites"  onchange="document.forms['modi_supp'].submit();" tabindex="40">
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
      <!--Ici on teste si l'utilisteur a selectionner ou non un site, pour ainsi inclure la partie modifier ou supprimer-->
      <?php if (isset($_POST['liste_sites']))
	  {
	  if ($_POST['liste_sites']!=0)
	  {
	  ?>
      <tr>
        <td class="td_1"><label for="nom_modifier">Nom :</label></td>
        <td class="td_1"><input type="text" name="nom_modifier" id="nom_modifier" tabindex="50" value="<?php $reponse=mysql_query("select nom from sites where id='".$_POST['liste_sites']."'"); $donnees=mysql_fetch_array($reponse); echo $donnees['nom'];?>" /></td>
      </tr>
      <tr>
        <td class="td_1"><label for="url_modifier">Url :</label></td>
        <td class="td_1"><input type="text" name="url_modifier" id="url_modifier"  tabindex="60" value="<?php $reponse=mysql_query("select url from sites where id='".$_POST['liste_sites']."'"); $donnees=mysql_fetch_array($reponse); echo $donnees['url']; ?>" /></td>
      </tr>
      <tr>
        <td class="td_1"><label>Choisissez une action :</label></td>
        <td class="td_1"><input type="radio" name="execution" value="modifier" id="modifier"  tabindex="70"/>
          <label for="modifier">Modifier</label>
          <br />
          <input type="radio" name="execution" value="supprimer" id="supprimer"  tabindex="80"/>
          <label for="supprimer">Supprimer</label>
          <br />
          <input type="radio" name="execution" value="rien" id="rien" checked="checked" tabindex="80"/>
          <label for="rien">Ne rien faire</label>
          <br />
        </td>
      </tr>
      <tr>
        <td>
        <?php if (isset($_POST['liste_sites']))
		echo '<input name="tmp" value="'.$_POST['liste_sites'].'" type="hidden"  />'; ?>
        </td>
      </tr>
      <tr>
        <td class="td_1"><input type="reset"  tabindex="100"/></td>
        <td class="td_1"><input type="submit"  value="Executer" tabindex="90"/></td>
      </tr>
      <?php  } }// fermer l'accolade du if 
	  ?>
    </table>
    </fieldset>
  </form>
</div>
<?php include("squelette/piedPage.php"); ?>
</body>
</html>
<?php mysql_close(); // On n'oublie pas de fermer la connexion à MySQL ;o) ?>
