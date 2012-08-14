<?php
include("connexion.php"); //inclure la connexion
session_start(); //ouvrir la session

//verifier si l'utilisateur existe et s'il a taper le mot de passe valide
if (isset($_POST['login'] ) and isset($_POST['mot_passe']))
	{$reponse = mysql_query("SELECT * from utilisateur where login='".$_POST['login']."' and mot_passe='".md5($_POST['mot_passe'])."'");
	 if (mysql_num_rows($reponse))
   		{$donnees = mysql_fetch_array($reponse);
		 //crée les variables de session dont on besoin		     		
		 $_SESSION['login'] = $donnees['login'];
		 $_SESSION['mot_passe'] = $donnees['mot_passe'];
		 $_SESSION['nom']=$donnees['nom'];
		 $_SESSION['prenom'] = $donnees['prenom'];
		 $_SESSION['id'] = $donnees['id'];
		 $_SESSION['adresse']=$donnees['adresse'];
		 $_SESSION['tel']=$donnees['tel'];
		 $_SESSION['pays']=$donnees['pays'];
		 $_SESSION['couriel']=$donnees['couriel'];		 
		 $_SESSION['privilege']=$donnees['privilege'];

		 
		 $reponse=mysql_query("SELECT * FROM options_utilisateurs WHERE id_utilisateur='".$_SESSION['id']."'");
		 $donnees=mysql_fetch_array($reponse);
		 $_SESSION['nbr_pages']=$donnees['nbr_pages'];
		 $_SESSION['connexion']=$donnees['connexion'];		
		 
		 if (strcmp($_SESSION['connexion'],"proxy")==0)
		 	{
				$_SESSION['port']=$donnees['port_proxy'];
				$_SESSION['http']=$donnees['http_proxy'];
		 	} 
		else
			{
				 $_SESSION['port']='';
				 $_SESSION['http']='';
			}
		 
		 //etre rediriger vers une page de validation
	 	header('Location: dialogue/validation.php');
		}
	 else
	 	//etre rediriger vers une page d'erreur
	 	header('Location: dialogue/erreur.php');
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
  <h1>Se connecter</h1>
  <p>
    Cette page vous permet de vous connecter en tant que membre de cette plate
      forme. Une fois connecté, vous pourrez suivre votre référencement, poster
      des messages sur le forum, proposer des news, commander etc.
  </p>
  <form method="post" action="page_connexion.php">
    <table>
      <tr>
        <td ><label for="login">Login :</label></td>
        <td ><input type="text" name="login"  id="login" tabindex="10"/>
        </td>
      </tr>
      <tr>
        <td ><label for="pass">Mot de Passe :</label></td>
        <td ><input type="password" name="mot_passe" id="mot_passe" tabindex="20"/>
        </td>
      </tr>
      <tr>
        <td><label for="connexion_automatique">Connexion Automatique</label>
          <input type="checkbox" name="connexion_automatique" id="connexion_automatique" tabindex="30" />
        </td>
      </tr>
      <tr>
        <td></td>
        <td><input type="submit" value="Se connecter" tabindex="40"/></td>
      </tr>
    </table>
    <h3>Un problème?</h3>
    <a class="a1" href="inscription.php">Je ne suis pas encore inscrit au site
    !</a><br />
    <a class="a1" href="oublier_pass.php">J'ai oublié mon mot de passe !</a><br />
  </form>
</div>
<?php include("squelette/piedPage.php"); ?>
</body>
</html>
<?php mysql_close(); // On n'oublie pas de fermer la connexion à MySQL ;o) ?>
