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
  <h1>Le diagramme</h1>
  <br />
  <form action="diagramme.php" method="post">
    <fieldset>
    <legend>Données du diagramme</legend>
    <table class="table_1" >
      <tr>
        <td class="td_1"><label for="liste_mots_cles">Mots cles de <?php echo $_SESSION['prenom']; ?> :</label></td>
        <td class="td_1"><select name="liste_mots_cles" id="liste_mots_cles" tabindex="50" onchange="document.forms[0].submit();">
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
      </tr>
    </table>
    </fieldset>
  </form>
<?php if (isset($_POST['liste_mots_cles']))
{
	$reponse = mysql_query ("SELECT position, date FROM position WHERE id_mot_cle='".$_POST['liste_mots_cles']."' GROUP BY date ORDER BY `position`.`date` ASC");
	if(mysql_num_rows($reponse))
	{
?>   
  <fieldset>
  <legend>Diagramme</legend>
  <table class="table_1">
    <tr>
      <td></td>
    </tr>
    <tr>
      <td><img src=<?php echo '"courbe.php?liste_mots_cles='.$_POST['liste_mots_cles'].'"'; ?> /> </td>
  </table>
  </fieldset>
<?php 
	}
}
?>  
</div>
<?php include("squelette/piedPage.php"); ?>
</body>
</html>
<?php mysql_close(); // On n'oublie pas de fermer la connexion à MySQL ;o) ?>
