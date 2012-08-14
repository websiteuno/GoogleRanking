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
  <!-- Ici on mettra le contenu principal de la page (tout le texte quoi) -->
  <h1>Plate Forme de Référencement</h1>
  <p>
    Bienvenue sur mon super site !<br />
    Vous allez adorer ici, c'est un site génial de référencement totalement gratuit.
    C'est quoi un référencement gratuit? Un référencement gratuit c'est un service
    qu'on vous l'offre gratuitement pour que votre site soit dans les premières
    pages de recherches des 2 principaux moteurs de recherche Google et Yahoo.
  </p>
  <h2>A qui s'adresse ce site ?</h2>
  <p>
    Ce site s'adresse principalement au Webmasters (des gens qui possèdent un
      site web)<br />
    Vous n'êtes pas un Webmaster mais vous voulez en devenir un. Pas de problème,
    se site est aussi fait pour vous ! Si si ! Ce site peut vous aider à créer
    votre site web, cliquer ici pour vous diriger vers la section création de
    site web.
  </p>
  <h2>L'auteur</h2>
  <p>
    L'auteur du site ? Bah, c'est moi, quelle question :-p<br />
    Je vais essayer de faire le meilleur site du monde (ça doit pas être bien
    compliqué). Mon objectif est d'attirer un maximum de visiteurs, de les rendre
    accros à mon site, puis de les mettre en mon pouvoir.<br />
    Je prendrai ensuite le contrôle du Monde. Une fois que ce sera fait, j'irai
    explorer les confins de l'Univers à la recherche de nouveaux peuples à soumettre
    à ma terrible puissance. MooUUuUuuUAhahHaaAhAAaaah !!! (rire diabolique).
  </p>
  <p>
    Voici les dernières news :
  </p>
  <?php
include("connexion.php");
// On récupère les 5 dernières news
$retour = mysql_query('SELECT * FROM news ORDER BY id DESC LIMIT 0, 5');
while ($donnees = mysql_fetch_array($retour))
{
?>
  <div class="news">
    <h3> <?php echo $donnees['titre']; ?> <em>le <?php echo date('d/m/Y à H\hi', $donnees['timestamp']); ?></em> </h3>
    <p>
      <?php
    // On enlève les éventuels antislash PUIS on crée les entrées en HTML (<br />)
    $contenu = nl2br(stripslashes($donnees['contenu']));
    echo $contenu;
    ?>
    </p>
  </div>
  <?php
} // Fin de la boucle des news
?>
</div>
<?php include("squelette/piedPage.php"); ?>
</body>
</html>
<?php mysql_close(); // On n'oublie pas de fermer la connexion à MySQL ;o) ?>
