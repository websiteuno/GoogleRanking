<?php
session_start(); // On démarre la session AVANT toute chose
include ("../connexion.php");

//-----------------------------------------------------
// Vrification 1 : est-ce qu'on veut poster une news ?
//-----------------------------------------------------
if (isset($_POST['titre']) AND isset($_POST['contenu']))
{
    $titre = addslashes($_POST['titre']);
    $contenu = addslashes($_POST['contenu']);

        // Ce n'est pas une modification, on creé une nouvelle entre dans la table
        mysql_query("INSERT INTO news VALUES('', '" . $titre . "', '" . $contenu . "', '" . time() . "')");
 
}


 
//--------------------------------------------------------
// Vrification 2 : est-ce qu'on veut supprimer une news ?
//--------------------------------------------------------
if (isset($_POST['execution']) and isset($_POST['liste_news']) and isset($_POST['tmp']))
{
	if (strcmp($_POST['execution'],"supprimer")==0 and $_POST['liste_news']!=0 and $_POST['liste_news']==$_POST['tmp'] )
	{ 
    // Alors on supprime la news correspondante
    // On protge la variable "id_news" pour viter une faille SQL
    $_POST['liste_news'] = addslashes($_POST['liste_news']);
    mysql_query('DELETE FROM news WHERE id=\'' . $_POST['liste_news'] . '\'');
	$_POST['liste_news']=0;
	}
}

//--------------------------------------------------------
// Vrification 2 : est-ce qu'on veut modifier une news ?
//--------------------------------------------------------

if (isset($_POST['execution']) and isset($_POST['titre_modifier']) and isset($_POST['contenu_modifier']) and isset($_POST['liste_news']) and isset($_POST['tmp']))
{
	if  (strcmp($_POST['execution'],"modifier")==0 and $_POST['liste_news']!=0 and strcmp($_POST['titre_modifier'],"")!=0 and strcmp($_POST['contenu_modifier'],"")!=0 and $_POST['liste_news']==$_POST['tmp'])
    {
	echo "ok";
    // On protège la variable "modifier_news" pour éviter une faille SQL
    $_POST['liste_news'] = mysql_real_escape_string(htmlspecialchars($_POST['liste_news']));
	$_POST['contenu_modifier'] = mysql_real_escape_string(htmlspecialchars($_POST['contenu_modifier']));
	$_POST['titre_modifier'] = mysql_real_escape_string(htmlspecialchars($_POST['titre_modifier']));
    // On récupère les infos de la news correspondante
	 mysql_query("UPDATE news SET titre='" . $_POST['titre_modifier'] . "', contenu='" . $_POST['contenu_modifier'] . "' WHERE id='" . $_POST['liste_news'] . "'");
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Plate Forme de Référencement</title>
<link href="../Style/Style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include ("../squelette_admin/titre.php"); ?>
<?php include ("../squelette_admin/menuGlobal.php");?>
<?php include ("../squelette_admin/menuContextuel.php");?>
<div id="corps">
  <form name="ajout" action="gestion_news.php" method="post">
    <fieldset>
    <legend>Ajouter une news</legend>
    <table>
      <tr>
        <td class="td_1"><label for="titre">Titre :</label></td>
        <td class="td_1"><input class="select_1" type="text" size="30" name="titre" id="titre"  />
        </td>
      </tr>
      <tr>
        <td class="td_1"><label for="contenu">Contenu :</label></td>
        <td class="td_1"><textarea class="select_1" name="contenu" id="contenu" cols="50" rows="10">
    </textarea></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td class="td_1"><input type="reset"  tabindex="100"/></td>
        <td class="td_1"><input type="submit"  value="Ajouter" tabindex="90"/></td>
      </tr>
    </table>
    </fieldset>
  </form>
  <form name="modi_supp" action="gestion_news.php" method="post">
    <fieldset>
    <legend>Modifier ou supprimer une news</legend>
    <table>
      <tr>
        <td class="td_1"><label for="liste_news">Liste des News :</label></td>
        <td class="td_1"><select name="liste_news" class="select_1" id="liste_news"  onchange="document.forms['modi_supp'].submit();" tabindex="40">
            <option value="0" selected="selected"></option>
            <?php 
							//afficher la liste des sites depuis la base de donnees de l'utilisateur connecté
							$reponse=mysql_query("select * from news");
							if (mysql_num_rows($reponse))
							{
								while ($donnees=mysql_fetch_array($reponse))
								{
									echo '<option value="'.$donnees['id'].'"';
									if (isset($_POST['liste_news']) and $_POST['liste_news'] == $donnees['id']) echo 'selected="selected"';
									echo '>'.$donnees['titre'];
									echo "</option>";
								}
							}
							?>
          </select>
        </td>
      </tr>
      <!--Ici on teste si l'utilisteur a selectionner ou non une news, pour ainsi inclure la partie modifier ou supprimer-->
      <?php if (isset($_POST['liste_news']))
	  {
	  if ($_POST['liste_news']!=0)
	  	{
	  ?>
      <tr>
        <td class="td_1"><label for="titre_modifier">Titre :</label></td>
        <td class="td_1"><input class="select_1" type="text" size="30" name="titre_modifier" id="titre_modifier" value="<?php $reponse=mysql_query("select titre from news where id='".$_POST['liste_news']."'"); $donnees=mysql_fetch_array($reponse); echo $donnees['titre'];?>" />
        </td>
      </tr>
      <tr>
        <td class="td_1"><label for="contenu_modifier">Contenu :</label></td>
        <td class="td_1"><textarea class="select_1" name="contenu_modifier" id="contenu_modifier" cols="50" rows="10"><?php $reponse=mysql_query("select contenu from news where id='".$_POST['liste_news']."'"); $donnees=mysql_fetch_array($reponse); echo $donnees['contenu'];?>
    </textarea></td>
      </tr>
      <tr>
        <td></td>
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
        <td><?php if (isset($_POST['liste_news']))
		echo '<input name="tmp" value="'.$_POST['liste_news'].'" type="hidden"  />'; ?>
        </td>
      </tr>
      <tr>
        <td class="td_1"><input type="reset"  tabindex="100"/></td>
        <td class="td_1"><input type="submit"  value="Executer" tabindex="90"/></td>
      </tr>
      <?php 
	 	}
	 }?>
    </table>
    </fieldset>
  </form>
</div>
<?php include("../squelette_admin/piedPage.php"); ?>
</body>
</html>
<?php mysql_close(); // On n'oublie pas de fermer la connexion à MySQL ;o) ?>
