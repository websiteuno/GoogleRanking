<?php
session_start(); // On démarre la session AVANT toute chose
include ("../connexion.php");

//-----------------------------------------------------
// Vrification 1 : est-ce qu'on veut poster une news ?
//-----------------------------------------------------
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
	$privilege = mysql_real_escape_string(htmlspecialchars($_POST['privilege']));	
	$mot_passe=md5($mot_passe);
	

	// mettre à jour la base 	

	mysql_query("INSERT INTO utilisateur VALUES ('', '".$nom."', '".$prenom."', '".$adresse."','".$tel."', '".$login."', '".$mot_passe."' ,'".$privilege."', '".$couriel."', '".$pays."' )");
}


 
//--------------------------------------------------------
// Vrification 2 : est-ce qu'on veut supprimer une news ?
//--------------------------------------------------------
if (isset($_POST['execution']) and isset($_POST['liste_utilisateurs']) and isset($_POST['tmp']))
{
	if (strcmp($_POST['execution'],"supprimer")==0 and $_POST['liste_utilisateurs']!=0 and $_POST['liste_utilisateurs']==$_POST['tmp'] )
	{ 
    // Alors on supprime la news correspondante
    // On protge la variable "id_news" pour viter une faille SQL
    $_POST['liste_utilisateurs'] = addslashes($_POST['liste_utilisateurs']);
    mysql_query('DELETE FROM utilisateur WHERE id=\'' . $_POST['liste_utilisateurs'] . '\'');
	$_POST['liste_utilisateurs']=0;
	}
}

//--------------------------------------------------------
// Vrification 2 : est-ce qu'on veut modifier une news ?
//--------------------------------------------------------

if (isset($_POST['nom_modifier']) and isset($_POST['prenom_modifier']) and isset($_POST['pays_modifier']) and isset($_POST['adresse_modifier']) and isset($_POST['tel_modifier']) and isset($_POST['couriel_modifier']) and isset($_POST['login_modifier']) and isset($_POST['mot_passe_modifier']))
{
	if (strcmp($_POST['execution'],"modifier")==0 and $_POST['liste_utilisateurs']!=0 and $_POST['liste_utilisateurs']==$_POST['tmp'] )
	{ 
	//pour éviter le piratage
	$nom = mysql_real_escape_string(htmlspecialchars($_POST['nom_modifier']));
	$prenom = mysql_real_escape_string(htmlspecialchars($_POST['prenom_modifier']));
	$pays = mysql_real_escape_string(htmlspecialchars($_POST['pays_modifier']));
	$couriel = mysql_real_escape_string(htmlspecialchars($_POST['couriel_modifier']));
	$adresse = mysql_real_escape_string(htmlspecialchars($_POST['adresse_modifier']));
	$tel = mysql_real_escape_string(htmlspecialchars($_POST['tel_modifier']));
	$login = mysql_real_escape_string(htmlspecialchars($_POST['login_modifier']));
	$mot_passe = mysql_real_escape_string(htmlspecialchars($_POST['mot_passe_modifier']));
	$privilege = mysql_real_escape_string(htmlspecialchars($_POST['privilege_modifier']));		
	$mot_passe=md5($mot_passe);

	// mettre à jour la base 	

	mysql_query("UPDATE utilisateur SET nom='".$nom."', prenom='".$prenom."', pays='".$pays."', couriel='".$couriel."', adresse='".$adresse."', tel='".$tel."', login='".$login."', mot_passe='".$mot_passe."', privilege='".$privilege."'  WHERE id=".$_POST['liste_utilisateurs']);
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
  <!--**********************************************************************Ajout**************************************************************************-->
  <form name="ajout" action="gestion_utilisateurs.php" method="post">
    <fieldset>
    <legend>Ajouter un utilisateur</legend>
    <table>
      <tr>
        <td class="td_1"><label for="login">Login</label></td>
        <td class="td_1"><input type="text" name="login" id="login"   tabindex="1" /></td>
      </tr>
      <tr>
        <td class="td_1"><label for="mot_passe">Mot de Passe</label></td>
        <td class="td_1"><input type="password" name="mot_passe" id="mot_passe"  tabindex="5" /></td>
      <tr>
      <tr>
        <td class="td_1"><label>Privilèges :</label></td>
        <td class="td_1"><input type="radio" name="privilege" value="admin" id="admin"  tabindex="10" checked="checked"/>
          <label for="admin">Admin</label>
          <br />
          <input type="radio" name="privilege" value="client" id="client"  tabindex="20"/>
          <label for="client">Client</label>
          <br />
        </td>
      </tr>
      </tr>
      
      </tr>
      
      <tr>
        <td class="td_1"><label for="nom">Nom :</label></td>
        <td class="td_1"><input type="text" name="nom" id="nom"   tabindex="30" /></td>
      </tr>
      <tr>
        <td class="td_1"><label for="prenom">Prénom :</label></td>
        <td class="td_1"><input type="text" name="prenom" id="prenom" tabindex="40" /></td>
      </tr>
      <tr>
        <td class="td_1"><label for="pays">Pays :</label>
        </td>
        <td class="td_1"><select name="pays" id="pays" tabindex="50">
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
        <td class="td_1"><textarea name="adresse" id="adresse"  tabindex="60"> </textarea></td>
      </tr>
      <tr>
        <td class="td_1"><label for="tel">Tel</label></td>
        <td class="td_1"><input type="text" name="tel" id="tel"  tabindex="70" /></td>
      </tr>
      <tr>
        <td class="td_1"><label for="couriel">Couriel</label></td>
        <td class="td_1"><input type="text" name="couriel" id="couriel"  tabindex="80" /></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td class="td_1"><input type="reset"  tabindex="100"/></td>
        <td class="td_1"><input type="submit"  value="Executer" tabindex="90"/></td>
      </tr>
    </table>
    </fieldset>
  </form>
  <!--**********************************************************************modi supp**************************************************************************-->
  <form name="modi_supp" action="gestion_utilisateurs.php" method="post">
    <fieldset>
    <legend>Modifier ou supprimer un utlisateur</legend>
    <table>
      <tr>
        <td class="td_1"><label for="liste_utilisateurs">Liste des utlisateurs
            :</label></td>
        <td class="td_1"><select name="liste_utilisateurs" class="select_1" id="liste_utilisateurs"  onchange="document.forms['modi_supp'].submit();" tabindex="100">
            <option value="0" selected="selected"></option>
            <?php 
							//afficher la liste des sites depuis la base de donnees de l'utilisateur connecté
							$reponse=mysql_query("select * from utilisateur");
							if (mysql_num_rows($reponse))
							{
								while ($donnees=mysql_fetch_array($reponse))
								{
									echo '<option value="'.$donnees['id'].'"';
									if (isset($_POST['liste_utilisateurs']) and $_POST['liste_utilisateurs'] == $donnees['id']) echo 'selected="selected"';
									echo '>'.$donnees['login'];
									echo "</option>";
								}
							}
							?>
          </select>
        </td>
      </tr>
      <!--Ici on teste si l'utilisteur a selectionner ou non une news, pour ainsi inclure la partie modifier ou supprimer-->
      <?php if (isset($_POST['liste_utilisateurs']))
	  {
	  if ($_POST['liste_utilisateurs']!=0)
	  	{
		 $reponse=mysql_query("select * from utilisateur WHERE id='".$_POST['liste_utilisateurs']."'");
		 $donnees=mysql_fetch_array($reponse);
	  ?>
      <tr>
        <td class="td_1"><label for="login_modifier">Login</label></td>
        <td class="td_1"><input type="text" name="login_modifier" id="login_modifier" value="<?php echo $donnees['login'] ?>"  tabindex="110" /></td>
      </tr>
      <tr>
        <td class="td_1"><label for="mot_passe_modifier">Mot de Passe</label></td>
        <?php  $pwd = md5($donnees['mot_passe']);?>
        <td class="td_1"><input type="password" name="mot_passe_modifier" id="mot_passe_modifier"  tabindex="120" /></td>
      </tr>
      <tr>
        <td class="td_1"><label>Privilèges :</label></td>
        <td class="td_1"><input type="radio" name="privilege_modifier" value="admin" id="admin_modifier"  tabindex="130" <?php if (strcmp($donnees['privilege'],"admin")==0) echo 'checked="checked"';?> />
          <label for="admin_modifier">Admin</label>
          <br />
          <input type="radio" name="privilege_modifier" value="client" id="client_modifier"  tabindex="140" <?php if (strcmp($donnees['privilege'],"client")==0) echo 'checked="checked"';?>/>
          <label for="client_modifier">Client</label>
          <br />
        </td>
      </tr>
      <tr>
        <td class="td_1"><label for="nom_modifier">Nom :</label></td>
        <td class="td_1"><input type="text" name="nom_modifier" id="nom_modifier"   value="<?php echo $donnees['nom']  ?> " tabindex="150" /></td>
      </tr>
      <tr>
        <td class="td_1"><label for="prenom">Prénom :</label></td>
        <td class="td_1"><input type="text" name="prenom_modifier" id="prenom_modifier" value="<?php echo $donnees['prenom'] ?>" tabindex="160" /></td>
      </tr>
      <tr>
        <td class="td_1"><label for="pays_modifier">Pays :</label>
        </td>
        <td class="td_1"><select name="pays_modifier" id="pays_modifier" tabindex="170">
            <optgroup label="Europe">
            <option value="france" <?php if (strcmp($donnees['pays'],"france")==0) echo 'selected="selected"'?>  >France</option>
            <option value="espagne" <?php if (strcmp($donnees['pays'],"espagne")==0) echo 'selected="selected"'?> >Espagne</option>
            <option value="italie" <?php if (strcmp($donnees['pays'],"italie")==0) echo 'selected="selected"'?> >Italie</option>
            <option value="royaume-uni" <?php if (strcmp($donnees['pays'],"royaume-uni")==0) echo 'selected="selected"'?> >Royaume-Uni</option>
            </optgroup>
            <optgroup label="Amérique">
            <option value="canada" <?php if (strcmp($donnees['pays'],"canada")==0) echo 'selected="selected"'?> >Canada</option>
            <option value="etats-unis" <?php if (strcmp($donnees['pays'],"etats-unis")==0) echo 'selected="selected"'?> >Etats-Unis</option>
            </optgroup>
            <optgroup label="Asie">
            <option value="chine" <?php if (strcmp($donnees['pays'],"chine")==0) echo 'selected="selected"'?> >Chine</option>
            <option value="japon" <?php if (strcmp($donnees['pays'],"japon")==0) echo 'selected="selected"'?> >Japon</option>
            </optgroup>
            <optgroup label="Afrique">
            <option value="tunisie" <?php if (strcmp($donnees['pays'],"tunisie")==0) echo 'selected="selected"'?> >Tunisie</option>
            <option value="maroc" <?php if (strcmp($donnees['pays'],"maroc")==0) echo 'selected="selected"'?> >Maroc</option>
            </optgroup>
          </select>
        </td>
      </tr>
      <tr>
        <td class="td_1"><label for="adresse_modifier">Adresse</label></td>
        <td class="td_1"><textarea name="adresse_modifier" id="adresse_modifier"  tabindex="180"><?php echo $donnees['adresse'] ?> </textarea></td>
      </tr>
      <tr>
        <td class="td_1"><label for="tel_modifier">Tel</label></td>
        <td class="td_1"><input type="text" name="tel_modifier" id="tel_modifier" value="<?php echo $donnees['tel'] ?>"  tabindex="190" /></td>
      </tr>
      <tr>
        <td class="td_1"><label for="couriel_modifier">Couriel</label></td>
        <td class="td_1"><input type="text" name="couriel_modifier" id="couriel_modifier" value="<?php echo $donnees['couriel'] ?>"  tabindex="200" /></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td class="td_1"><label>Choisissez une action :</label></td>
        <td class="td_1"><input type="radio" name="execution" value="modifier" id="modifier"  tabindex="210"/>
          <label for="modifier">Modifier</label>
          <br />
          <input type="radio" name="execution" value="supprimer" id="supprimer"  tabindex="220"/>
          <label for="supprimer">Supprimer</label>
          <br />
          <input type="radio" name="execution" value="rien" id="rien" checked="checked" tabindex="230"/>
          <label for="rien">Ne rien faire</label>
          <br />
        </td>
      </tr>
      <tr>
        <td><?php if (isset($_POST['liste_utilisateurs']))
		echo '<input name="tmp" value="'.$_POST['liste_utilisateurs'].'" type="hidden"  />'; ?>
        </td>
      </tr>
      <tr>
        <td class="td_1"><input type="reset"  tabindex="240"/></td>
        <td class="td_1"><input type="submit"  value="Executer" tabindex="250"/></td>
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
