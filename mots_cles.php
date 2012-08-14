<?php
session_start(); // On démarre la session AVANT toute chose
include("connexion.php");

if (isset($_POST['liste_sites'])) $_SESSION['liste_sites']=$_POST['liste_sites'];

//Enregistrer un nouveau mot cle
// pour être sur qu'on veut enregistrer il faut que ces variables existent
if ( isset($_POST['mot_cle']) and isset($_SESSION['liste_sites']) ) 
{
	//ensuite on verifie si les champs d'ajout sont belle est bien remplie qu'on a choisi un site
	if ( $_SESSION['liste_sites']!=0 and strcmp($_POST['mot_cle'],"")!=0 )
	{
	//pour éviter le piratage
	$mot_cle = mysql_real_escape_string(htmlspecialchars($_POST['mot_cle']));

	// on enregistre le mot cle 
	mysql_query("INSERT INTO mots_cles VALUES('', '" . $_POST['mot_cle'] . "', '" . $_SESSION['liste_sites'] . "')");			
	}
}

//modifier un mot clé
if ( isset($_POST['execution']) and isset($_SESSION['liste_sites']) and isset($_POST['liste_mots_cles']) and isset($_POST['tmp']) )
{
	if  ( $_SESSION['liste_sites']!=0 and strcmp($_POST['execution'],"modifier")==0 and strcmp($_POST['mot_cle_modifier'],"")!=0 and $_POST['liste_mots_cles']!=0  and $_POST['liste_mots_cles']==$_POST['tmp']  )         
    {
   //pour éviter le piratage
	$mot_cle_modifier = mysql_real_escape_string(htmlspecialchars($_POST['mot_cle_modifier']));
	$liste_mots_cles = mysql_real_escape_string(htmlspecialchars($_POST['liste_mots_cles']));
	
    $reponse=mysql_query("SELECT * FROM mots_cles WHERE valeur='".$mot_cle_modifier."' AND id_site='".$_SESSION['liste_sites']."'");
	if (!mysql_num_rows($reponse))
		{
			mysql_query("UPDATE mots_cles SET valeur='" . $mot_cle_modifier . "' WHERE id='" . $liste_mots_cles . "'");
			mysql_query("DELETE FROM position WHERE id_mot_cle='".$liste_mots_cles."'");	
		}
	}
}


//supprimer un mot clé
if (isset($_POST['execution']) and isset($_POST['liste_mots_cles']) and isset($_POST['tmp']))
{
	if (strcmp($_POST['execution'],"supprimer")==0 and $_POST['liste_mots_cles']!=0   and $_POST['liste_mots_cles']==$_POST['tmp'])
	{
		//pour éviter le piratage	
		$liste_mots_cles = mysql_real_escape_string(htmlspecialchars($_POST['liste_mots_cles']));
		//effacer de la table position chaqu'une des positions correspondantes au mot clé qu'on veut effacer
		mysql_query("DELETE FROM position WHERE id_mot_cle='".$_POST['liste_mots_cles']."'");		
    	mysql_query("DELETE FROM mots_cles WHERE id='" . $liste_mots_cles . "'");
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
  <h1> Gérer vos Mots Cles </h1>
  <form name="choisir_site" action="mots_cles.php" method="post">
    <fieldset>
    <legend>Choisir un Site</legend>
    <table >
      <tr>
        <td class="td_1"><label for="liste_sites">Sites de <?php echo $_SESSION['prenom']; ?>:</label></td>
        <td class="td_1"><select name="liste_sites" id="liste_sites"  onchange="document.forms['choisir_site'].submit();" tabindex="10">
            <option value="0" selected="selected"></option>
            <?php 
							//afficher la liste des sites depuis la base de donnees de l'utilisateur connecté
							$reponse=mysql_query("select * from sites,correspondance where sites.id=correspondance.id_site and id_utilisateur='".$_SESSION['id']."'");
							if (mysql_num_rows($reponse))
							{
								while ($donnees=mysql_fetch_array($reponse))
								{
									echo '<option value="'.$donnees['id'].'"';
									if (isset($_SESSION['liste_sites']) and $_SESSION['liste_sites'] == $donnees['id']) echo 'selected="selected"';
									echo '>'.$donnees['nom'];
									echo "</option>";									
								}								
					   		} 				
			?>
          </select>
          
        </td>
      </tr>
    </table>
    </fieldset>
  </form>
  <form name="ajouter" method="post" action="mots_cles.php">
    <fieldset>
    <legend>Ajouter un mot cle</legend>
    <table>
      <tr>
        <td class="td_1"><label for="mot_cle">Mot Cle :</label></td>
        <td class="td_1"><input type="text" name="mot_cle" id="mot_cle" tabindex="20" /></td>
      </tr>
      <tr>
        <td><input type="hidden" name="ajouter_site" />
      </tr>
      <tr>
        <td class="td_1"><input type="reset"  tabindex="40"/></td>
        <td class="td_1"><input type="submit"  value="Ajouter" tabindex="30"/></td>
      </tr>
    </table>
    <?php if (isset($_SESSION['liste_sites']))
{
 if ($_SESSION['liste_sites']!=0)
{
?>
    </fieldset>
  </form>
  <form name="modi_supp" action="mots_cles.php" method="post">
    <fieldset>
    <legend>Modifier ou supprimer un Mot clé</legend>
    <table >
      <tr>
        <td class="td_1"><label for="liste_mots_cles">Mots cles de <?php echo $_SESSION['prenom']; ?> :</label></td>
        <td class="td_1"><select name="liste_mots_cles" id="liste_mots_cles"  onchange="document.forms['modi_supp'].submit();" tabindex="50">
            <option value="0" selected="selected"></option>
            <?php 
							//afficher la liste des mots cles depuis la base de donnees de l'utilisateur connecté
							$reponse=mysql_query("select * from mots_cles where id_site='".$_SESSION['liste_sites']."'");
							if (mysql_num_rows($reponse))
							{
								while ($donnees=mysql_fetch_array($reponse))
								{
									echo '<option value="'.$donnees['id'].'"';
									if (isset($_POST['liste_mots_cles']) and $_POST['liste_mots_cles'] == $donnees['id']) echo 'selected="selected"';
									echo '>'.$donnees['valeur'];
									echo "</option>";
								}
							}
							?>
          </select>
        </td>
      </tr>
      <!--Ici on teste si l'utilisteur a selectionner ou non un mot cle, pour ainsi inclure la partie modifier ou supprimer-->
      <?php if (isset($_POST['liste_mots_cles']))
	  {	  
	  $reponse=mysql_query("select * from mots_cles where id='".$_POST['liste_mots_cles']."' and id_site='".$_SESSION['liste_sites']."'");
	  if (($_POST['liste_mots_cles']!=0) and mysql_num_rows($reponse))
	  {
	  ?>
      <tr>
        <td class="td_1"><label for="mot_cle_modifier">Nom :</label></td>
        <td class="td_1"><input type="text" name="mot_cle_modifier" id="mot_cle_modifier" tabindex="50" value="<?php $reponse=mysql_query("select valeur from mots_cles where id='".$_POST['liste_mots_cles']."'"); $donnees=mysql_fetch_array($reponse); echo $donnees['valeur'];?>" /></td>
      </tr>
      <tr>
        <td class="td_1"><label>Choisissez une action :</label></td>
        <td class="td_1"><input type="radio" name="execution" value="modifier" id="modifier"  tabindex="70"/>
          <label for="modifier">Modifier</label>
          <br />
          <input type="radio" name="execution" value="supprimer" id="supprimer"  tabindex="80"/>
          <label for="supprimer">Supprimer</label>
          <br />
          <input type="radio" name="execution" value="rien" id="rien" checked="checked"  tabindex="90"/>
          <label for="rien">Ne rien faire</label>
          <br />
        </td>
      </tr>
      <tr>
        <td><?php if (isset($_POST['liste_mots_cles']))
		echo '<input name="tmp" value="'.$_POST['liste_mots_cles'].'" type="hidden"  />'; ?></td>
      </tr>
      <tr>
        <td class="td_1"><input type="reset"  tabindex="110"/></td>
        <td class="td_1"><input type="submit"  value="Executer" tabindex="100"/></td>
      </tr>
      <?php  } }// fermer l'accolade du if de verification si on a selectionner un site 
	  ?>
    </table>
    </fieldset>
    <?php  } }// fermer l'accolade du if de verification si on a selectionner un mot cle
?>
  </form>
</div>
<?php include("squelette/piedPage.php"); ?>
</body>
</html>
<?php mysql_close(); // On n'oublie pas de fermer la connexion à MySQL ;o) ?>