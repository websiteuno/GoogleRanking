<?php
session_start(); // On démarre la session AVANT toute chose
include("connexion.php");
//on modifie les données

if (isset($_POST['nom']) and isset($_POST['prenom']) and isset($_POST['pays']) and isset($_POST['adresse']) and isset($_POST['tel']) and isset($_POST['couriel']) and isset($_POST['login']) and isset($_POST['mot_passe']))
{
	//pour éviter le piratage
	$nom = mysql_real_escape_string(htmlspecialchars($_POST['nom']));
	$prenom = mysql_real_escape_string(htmlspecialchars($_POST['prenom']));
	$pays = mysql_real_escape_string(htmlspecialchars($_POST['pays']));
	$couriel = mysql_real_escape_string(htmlspecialchars($_POST['couriel']));
	$adresse = mysql_real_escape_string(htmlspecialchars($_POST['adresse']));
	$tel = mysql_real_escape_string(htmlspecialchars($_POST['tel']));
	$login = mysql_real_escape_string(htmlspecialchars($_POST['login']));
	$mot_passe = mysql_real_escape_string(htmlspecialchars($_POST['mot_passe']));
	$mot_passe=md5($mot_passe);

	// mettre à jour la base 	

	mysql_query("UPDATE utilisateur SET nom='".$nom."', prenom='".$prenom."', pays='".$pays."', couriel='".$couriel."', adresse='".$adresse."', tel='".$tel."', login='".$login."', mot_passe='".$mot_passe."' WHERE id=".$_SESSION['id']);
	
	//mettre à jour les variables de session
		 $_SESSION['login'] = $login;
		 $_SESSION['mot_passe'] = $mot_passe;
		 $_SESSION['nom']=$nom;
		 $_SESSION['prenom'] = $prenom;
		 $_SESSION['adresse']=$adresse;
		 $_SESSION['tel']=$tel;
		 $_SESSION['pays']=$pays;
		 $_SESSION['couriel']=$couriel;

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
  <h1>Profil de <?php echo $_SESSION['prenom'] ?> </h1>
  <form method="post" action="profil.php">
    <fieldset>
    <legend>Paramètres de connexion</legend>
    <table>
      <tr>
        <td class="td_1"><label for="tel">Login</label></td>
        <td class="td_1"><input type="text" name="login" id="login" value="<?php echo $_SESSION['login'] ?>"  tabindex="1" /></td>
      </tr>
      <tr>
        <td class="td_1"><label for="mot_passe">Mot de Passe</label></td>
        <td class="td_1"><input type="password" name="mot_passe" id="mot_passe"  tabindex="5" /></td>
      </tr>
    </table>
    </fieldset>
    <fieldset>
    <legend> Vos Coordonnées</legend>
    <table >
      <tr>
        <td class="td_1"><label for="nom">Nom :</label></td>
        <td class="td_1"><input type="text" name="nom" id="nom"   value="<?php echo $_SESSION['nom']  ?> " tabindex="10" /></td>
      </tr>
      <tr>
        <td class="td_1"><label for="prenom">Prénom :</label></td>
        <td class="td_1"><input type="text" name="prenom" id="prenom" value="<?php echo $_SESSION['prenom'] ?>" tabindex="20" /></td>
      </tr>
      <tr>
        <td class="td_1"><label for="pays">Pays :</label>
        </td>
        <td class="td_1"><select name="pays" id="pays" tabindex="30">
            <optgroup label="Europe">
            <option value="france" <?php if (strcmp($_SESSION['pays'],"france")==0) echo 'selected="selected"'?>  >France</option>
            <option value="espagne" <?php if (strcmp($_SESSION['pays'],"espagne")==0) echo 'selected="selected"'?> >Espagne</option>
            <option value="italie" <?php if (strcmp($_SESSION['pays'],"italie")==0) echo 'selected="selected"'?> >Italie</option>
            <option value="royaume-uni" <?php if (strcmp($_SESSION['pays'],"royaume-uni")==0) echo 'selected="selected"'?> >Royaume-Uni</option>
            </optgroup>
            <optgroup label="Amérique">
            <option value="canada" <?php if (strcmp($_SESSION['pays'],"canada")==0) echo 'selected="selected"'?> >Canada</option>
            <option value="etats-unis" <?php if (strcmp($_SESSION['pays'],"etats-unis")==0) echo 'selected="selected"'?> >Etats-Unis</option>
            </optgroup>
            <optgroup label="Asie">
            <option value="chine" <?php if (strcmp($_SESSION['pays'],"chine")==0) echo 'selected="selected"'?> >Chine</option>
            <option value="japon" <?php if (strcmp($_SESSION['pays'],"japon")==0) echo 'selected="selected"'?> >Japon</option>
            </optgroup>
            <optgroup label="Afrique">
            <option value="tunisie" <?php if (strcmp($_SESSION['pays'],"tunisie")==0) echo 'selected="selected"'?> >Tunisie</option>
            <option value="maroc" <?php if (strcmp($_SESSION['pays'],"maroc")==0) echo 'selected="selected"'?> >Maroc</option>
            </optgroup>                 
          </select>
        </td>
      </tr>
      <tr>
        <td class="td_1"><label for="adresse">Adresse</label></td>
        <td class="td_1"><textarea name="adresse" id="adresse"  tabindex="40"><?php echo $_SESSION['adresse'] ?> </textarea></td>
      </tr>
      <tr>
        <td class="td_1"><label for="tel">Tel</label></td>
        <td class="td_1"><input type="text" name="tel" id="tel" value="<?php echo $_SESSION['tel'] ?>"  tabindex="50" /></td>
      </tr>
      <tr>
        <td class="td_1"><label for="couriel">Couriel</label></td>
        <td class="td_1"><input type="text" name="couriel" id="couriel" value="<?php echo $_SESSION['couriel'] ?>"  tabindex="60" /></td>
      </tr>
      <tr>
        <td></td>
      </tr>

    </table>
    </fieldset>
    <table class="table_1">
          <tr>
        <td class="td_1"><input type="reset"  tabindex="100"/></td>
        <td class="td_1"><input type="submit"  accesskey="R" value="Modifier mon Profil" tabindex="90"/></td>
      </tr>
      </table>
  </form>
</div>
<?php include("squelette/piedPage.php"); ?>
</body>
</html>
<?php mysql_close(); // On n'oublie pas de fermer la connexion à MySQL ;o) ?>
