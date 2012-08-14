<?php
session_start(); // On démarre la session AVANT toute chose
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
  <div class="livreor">
    <form method="post" action="livreor.php">
      <fieldset>
      <legend>Mon site vous plait ? Laissez-moi un message !</legend>
      <table class="table_1">
        <tr>
          <td><label for="pseudo">Pseudo :</label></td>
          <td><input name="pseudo" id="pseudo" /></td>
        </tr>
        <tr>
          <td></td>
          <td><label for="message">Message :</label></td>
        </tr>
        <tr>
          <td></td>
          <td><textarea name="message" id="message" rows="8" cols="35"></textarea></td>
        </tr>
        <td></td>
          <td><input type="submit" value="Envoyer" />
          </td>
        </tr>
      </table>
      </fieldset>
    </form>
    <p class="pages">
      <?php
include("connexion.php");
 
// --------------- Etape 1 -----------------
// Si un message est envoyé, on l'enregistre
// -----------------------------------------
 
if (isset($_POST['pseudo']) AND isset($_POST['message']))
{
    $pseudo = mysql_real_escape_string(htmlspecialchars($_POST['pseudo'])); // On utilise mysql_real_escape_string et htmlspecialchars par mesure de sécurité
    $message = mysql_real_escape_string(htmlspecialchars($_POST['message'])); // De même pour le message
    $message = nl2br($message); // Pour le message, comme on utilise un textarea, il faut remplacer les Entrées par des <br />
 
    // On peut enfin enregistrer :o)
    mysql_query("INSERT INTO livreor VALUES('', '" . $pseudo . "', '" . $message . "')");
}
 
// --------------- Etape 2 -----------------
// On écrit les liens vers chacune des pages
// -----------------------------------------
 
// On met dans une variable le nombre de messages qu'on veut par page
$nombreDeMessagesParPage = 10; // Essayez de changer ce nombre pour voir :o)
// On récupère le nombre total de messages
$retour = mysql_query('SELECT COUNT(*) AS nb_messages FROM livreor');
$donnees = mysql_fetch_array($retour);
$totalDesMessages = $donnees['nb_messages'];
// On calcule le nombre de pages à créer
$nombreDePages  = ceil($totalDesMessages / $nombreDeMessagesParPage);
// Puis on fait une boucle pour écrire les liens vers chacune des pages
echo 'Page : ';
for ($i = 1 ; $i <= $nombreDePages ; $i++)
{
    echo '<a href="livreor.php?page=' . $i . '">' . $i . '</a> ';
}
?>
    </p>
    <?php
 
 
// --------------- Etape 3 ---------------
// Maintenant, on va afficher les messages
// ---------------------------------------
 
if (isset($_GET['page']))
{
        $page = $_GET['page']; // On récupère le numéro de la page indiqué dans l'adresse (livreor.php?page=4)
}
else // La variable n'existe pas, c'est la première fois qu'on charge la page
{
        $page = 1; // On se met sur la page 1 (par défaut)
}
 
// On calcule le numéro du premier message qu'on prend pour le LIMIT de MySQL
$premierMessageAafficher = ($page - 1) * $nombreDeMessagesParPage;
 
$reponse = mysql_query('SELECT * FROM livreor ORDER BY id DESC LIMIT ' . $premierMessageAafficher . ', ' . $nombreDeMessagesParPage);
 
while ($donnees = mysql_fetch_array($reponse))
{
        echo '<p><strong>' . $donnees['pseudo'] . '</strong> a écrit :<br />' . $donnees['message'] . '</p>';
}
 
mysql_close(); // On n'oublie pas de fermer la connexion à MySQL ;o)
?>
  </div>
</div>
<?php include("squelette/piedPage.php"); ?>
</body>
</html>
