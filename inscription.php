<?php
session_start(); // On démarre la session AVANT toute chose
include("connexion.php");

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

	mysql_query("INSERT INTO utilisateur VALUES ('', '".$nom."', '".$prenom."', '".$adresse."','".$tel."', '".$login."', '".$mot_passe."' ,'client', '".$couriel."', '".$pays."' )");
	$reponse=mysql_query("SELECT id FROM utilisateur WHERE login='".$login."'");
	$donnees=mysql_fetch_array($reponse);
	mysql_query("INSERT INTO options_utilisateurs VALUES ('".$donnees['id']."', '50', 'direct', '', '')");
		 $_SESSION['id']= $donnees['id'];
		 $_SESSION['login'] = $login;
		 $_SESSION['mot_passe'] = $mot_passe;
		 $_SESSION['nom']=$nom;
		 $_SESSION['prenom'] = $prenom;
		 $_SESSION['adresse']=$adresse;
		 $_SESSION['tel']=$tel;
		 $_SESSION['pays']=$pays;
		 $_SESSION['couriel']=$couriel;
		 $_SESSION['connexion']='direct';
		 $_SESSION['nbr_pages']=50;
		 $_SESSION['port']='';
		 $_SESSION['http']='';
		 

		 //etre rediriger vers une page de validation
		 header('Location: dialogue/validation.php');
 
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Plate Forme de Référencement</title>
<link href="Style/Style.css" rel="stylesheet" type="text/css" />
<script language="javascript">
function verification()
{
	if (document.forms[0].login.value=='') alert("veuillez taper un login");
	else if (document.forms[0].mot_passe.value=='') alert("veuillez taper un mot_passe");
	else if (document.forms[0].mot_passe.value!=document.forms[0].confirmer_mot_passe.value) alert("mot de passe non valide");
	else if (document.forms[0].nom.value=='') alert("veuillez taper votre nom");
	else if (document.forms[0].prenom.value=='') alert("veuillez taper votre prenom");
	else if (document.forms[0].pays.value=='') alert("veuillez taper votre pays");
	else if (document.getElementById('adresse').value=='') alert("veuillez taper votre adresse");
	else if (document.forms[0].tel.value=='') alert("veuillez taper votre tel");
	else if (document.forms[0].couriel.value=='') alert("veuillez taper votre couriel");	
	else document.forms[0].submit();	
}
</script>
</head>
<body>
<?php include ("squelette/titre.php"); ?>
<?php include ("squelette/menuGlobal.php");?>
<?php include ("squelette/menuContextuel.php");?>
<div id="corps">
  <h1>Inscription</h1>
  <form method="post" action="inscription.php">
    <fieldset>
    <legend>Paramètres de connexion</legend>
    <table>
      <tr>
        <td class="td_1"><label for="login">Login</label></td>
        <td class="td_1"><input type="text" name="login" id="login"   tabindex="1" /></td>
      </tr>
      <tr>
        <td class="td_1"><label for="mot_passe">Mot de Passe</label></td>
        <td class="td_1"><input type="password" name="mot_passe" id="mot_passe"  tabindex="5" /></td>
      <tr>
        <td class="td_1"><label for="confirmer_mot_passe">Confirmer votre Mot de Passe</label></td>
        <td class="td_1"><input type="password" name="confirmer_mot_passe" id="confirmer_mot_passe"  tabindex="6" /></td>
      </tr>
      </tr>
      
    </table>
    </fieldset>
    <fieldset>
    <legend> Vos Coordonnées</legend>
    <table >
      <tr>
        <td class="td_1"><label for="nom">Nom :</label></td>
        <td class="td_1"><input type="text" name="nom" id="nom"   tabindex="10" /></td>
      </tr>
      <tr>
        <td class="td_1"><label for="prenom">Prénom :</label></td>
        <td class="td_1"><input type="text" name="prenom" id="prenom" tabindex="20" /></td>
      </tr>
      <tr>
        <td class="td_1"><label for="pays">Pays :</label>
        </td>
        <td class="td_1"><select name="pays" id="pays" tabindex="30">
            <optgroup label="Europe">
            <option value="france">France</option>
            <option value="espagne">Espagne</option>
            <option value="italie">Italie</option>
            <option value="royaume-uni">Royaume-Uni</option>
            </optgroup>
            <optgroup label="Amérique">
            <option value="canada">Canada</option>
            <option value="etats-unis">Etats-Unis</option>
            </optgroup>
            <optgroup label="Asie">
            <option value="chine">Chine</option>
            <option value="japon">Japon</option>
            </optgroup>
            <optgroup label="Afrique">
            <option value="tunisie">Tunisie</option>
            <option value="maroc">Maroc</option>
            </optgroup>
          </select>
        </td>
      </tr>
      <tr>
        <td class="td_1"><label for="adresse">Adresse</label></td>
        <td class="td_1"><textarea name="adresse" id="adresse"  tabindex="40"> </textarea></td>
      </tr>
      <tr>
        <td class="td_1"><label for="tel">Tel</label></td>
        <td class="td_1"><input type="text" name="tel" id="tel"  tabindex="50" /></td>
      </tr>
      <tr>
        <td class="td_1"><label for="couriel">Couriel</label></td>
        <td class="td_1"><input type="text" name="couriel" id="couriel"  tabindex="60" /></td>
      </tr>
      <tr>
        <td></td>
      </tr>
    </table>
    </fieldset>
    <table class="table_1">
      <tr>
        <td class="td_1"><input type="reset"  tabindex="100"/></td>
        <td class="td_1"><input type="button" onclick="verification();" accesskey="R" value="S'inscrire" tabindex="90"/></td>
      </tr>
    </table>
  </form>
</div>
<?php include("squelette/piedPage.php"); ?>
</body>
</html>
<?php mysql_close(); // On n'oublie pas de fermer la connexion à MySQL ;o) ?>
