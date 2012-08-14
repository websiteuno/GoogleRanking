<?php
session_start(); // On démarre la session AVANT toute chose
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
  <h1>Administrateur</h1>
  <table class="table_1">
    <tr>
      <td><fieldset>
        <legend>Gestion des utilisateurs</legend>
        <table class="table_1">
          <tr>
            <td ><a class="a3" href="gestion_utilisateurs.php">Ajout</a> </td>
            <td ><a class="a3" href="gestion_utilisateurs.php">Modification</a> </td>
          </tr>
          <tr>
            <td ><a class="a3" href="gestion_utilisateurs.php">Suppression</a> </td>
            <td ><a class="a3" href="gestion_utilisateurs.php">Validation</a> </td>
          </tr>
        </table>
        </fieldset></td>
      <td><fieldset>
        <legend>Gestion des Historiques</legend>
        <table class="table_1">
          <tr>
            <td ><a class="a3" href="">Ajout</a> </td>
            <td ><a class="a3" href="">Modification</a> </td>
          </tr>
          <tr>
            <td ><a class="a3" href="">Suppression</a> </td>
            <td ><a class="a3" href="">Affichage</a> </td>
          </tr>
        </table>
        </fieldset></td>
    </tr>
    <tr>
      <td><fieldset>
        <legend>Gestion des News</legend>
        <table class="table_1">
          <tr>
            <td ><a class="a3" href="gestion_news.php">Rédaction</a> </td>
            <td ><a class="a3" href="gestion_news.php">Modification</a> </td>
          </tr>
          <tr>
            <td ><a class="a3" href="gestion_news.php">Suppression</a> </td>
            <td ><a class="a3" href="gestion_news.php">Validation</a> </td>
          </tr>
        </table>
        </fieldset></td>
      <td><fieldset>
        <legend>Gestion du Forum</legend>
        <table class="table_1">
          <tr>
            <td ><a class="a3" href="">Ajout</a> </td>
            <td ><a class="a3" href="">Modification</a> </td>
          </tr>
          <tr>
            <td ><a class="a3" href="">Suppression</a> </td>
            <td ><a class="a3" href="">Contrôl</a> </td>
          </tr>
        </table>
        </fieldset></td>
    </tr>
  </table>
</div>
<?php include("../squelette_admin/piedPage.php"); ?>
</body>
</html>
