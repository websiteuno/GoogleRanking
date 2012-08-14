<?php
session_start(); // On démarre la session AVANT toute chose
include("connexion.php");

 
$reponse=mysql_query("select distinct(date) from position,mots_cles,sites,correspondance where mots_cles.id_site=sites.id AND mots_cles.id=position.id_mot_cle AND correspondance.id_site=sites.id AND correspondance.id_utilisateur='".$_SESSION['id']."'");
if (mysql_num_rows($reponse))
	{
	 while ($donnees=mysql_fetch_array($reponse))
		{	
			if (round(time()/100000)==round($donnees['date']/100000)) $alert=1;
		}
	}	
	  
  
   

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Plate Forme de Référencement</title>
<link href="Style/Style.css" rel="stylesheet" type="text/css" />
<script language="javascript">

//c'est une fonction du on click du lien execution par site
//envoyer vers la page execution l'id du site selectionner et le numéro du moteur selectionner 1:google, 2:yahoo,3:les 2 moteurs
function passage_site()
{
//verifier si on a choisi un element de la liste 
if (document.forms[0].liste_sites.value==0)
alert("selectionner un site de la liste");
//verifier si les 2 moteurs de recherche ont été selectionné
else if ((document.forms[0].google.checked) && (document.forms[0].yahoo.checked))
self.location.href="execution.php?site="+document.forms[0].liste_sites.value+"&moteur=3";
//sinon peut être google a été selectionné
else if (document.forms[0].google.checked)
self.location.href="execution.php?site="+document.forms[0].liste_sites.value+"&moteur=1";
//alors peut être yahoo a été selectionné
else if (document.forms[0].yahoo.checked)
self.location.href="execution.php?site="+document.forms[0].liste_sites.value+"&moteur=2";
//afficher une alert pour dire qu'aucun des moteur a ètè selectionner
else alert("selectionner un ou plusieurs moteurs de recherche");
}

//c'est une fonction du on click du lien execution par mot clé
//envoyer vers la page execution l'id du mot clé selectionné et le numéro du moteur selectionné 1:google, 2:yahoo,3:les 2 moteurs
function passage_mot_cle()
{
//verifier si on a choisi un element de la liste 
if (document.forms[0].liste_mots_cles.value==0)
alert("selectionner un mot clé de la liste");
//verifier si les 2 moteurs de recherche ont été selectionné
else if ((document.forms[0].google.checked) && (document.forms[0].yahoo.checked))
self.location.href="execution.php?mot_cle="+document.forms[0].liste_mots_cles.value+"&moteur=3";
//sinon peut être google a été selectionné
else if (document.forms[0].google.checked)
self.location.href="execution.php?mot_cle="+document.forms[0].liste_mots_cles.value+"&moteur=1";
//alors peut être yahoo a été selectionné
else if (document.forms[0].yahoo.checked)
self.location.href="execution.php?mot_cle="+document.forms[0].liste_mots_cles.value+"&moteur=2";
//afficher une alert pour dire qu'aucun des moteur a ètè selectionner
else alert("selectionner un ou plusieurs moteurs de recherche ");
}

//c'est une fonction du on click du lien execution global
//envoyer vers la page execution le numéro du moteur selectionné 1:google, 2:yahoo,3:les 2 moteurs
function passage_global()
{
//verifier si les 2 moteurs de recherche ont été selectionné
if ((document.forms[0].google_global.checked) && (document.forms[0].yahoo_global.checked))
self.location.href="execution.php?execution_global=1&moteur=3";
//sinon peut être google a été selectionné
else if (document.forms[0].google_global.checked)
self.location.href="execution.php?execution_global=1&moteur=1";
//alors peut être yahoo a été selectionné
else if (document.forms[0].yahoo_global.checked)
self.location.href="execution.php?execution_global=1&moteur=2";
//afficher une alert pour dire qu'aucun des moteur a ètè selectionner
else alert("selectionner un ou plusieurs moteurs de recherche");

}


</script>
</head>
<body>
<?php include ("squelette/titre.php"); ?>
<?php include ("squelette/menuGlobal.php");?>
<?php include ("squelette/menuContextuel.php");?>
<div id="corps">
<?php 
if (isset($alert)) echo '<h1>Execution Impossible</h1><p>vous avez dèja executer une fois aujourd\'hui vous n\'avez plus la possiblité d\'exectuer une autre fois.</p>';
else
{
?>

  <h1> Execution </h1>
   

  <form action="execution.php" method="post">
    <fieldset>
    <legend>Execution Global</legend>
    <table>
      <tr>
        <td class="td_1"><label class="label">Moteur de Recherche :</label></td>
        <td class="td_1"><input type="checkbox" name="google_global" id="google_global" tabindex="70" />
          <label class="label" for="google_global">Google</label>
          <br />
          <input type="checkbox" name="yahoo_global" id="yahoo_global"  tabindex="80"/>
          <label class="label" for="yahoo_global">Yahoo</label>
          <br />
        </td>
      </tr>
      <tr>
        <td class="td_1">Nombre Total de sites :</td>
        <td class="td_1"><?php
$reponse=mysql_query("SELECT COUNT(*) AS nbr_sites FROM sites,correspondance WHERE sites.id=correspondance.id_site AND id_utilisateur='".$_SESSION['id']."'");
$donnees=mysql_fetch_array($reponse);
$nbr_sites=$donnees['nbr_sites']; 
echo $nbr_sites;
?>
        </td>
      </tr>
      <tr>
        <td class="td_1">Nombre Total de mots clés :</td>
        <td class="td_1"><?php
$reponse=mysql_query("SELECT COUNT(*) AS nbr_mots_cles FROM sites,correspondance,mots_cles WHERE sites.id=correspondance.id_site AND mots_cles.id_site=sites.id AND id_utilisateur='".$_SESSION['id']."'");
$donnees=mysql_fetch_array($reponse);
$nbr_mots_cles=$donnees['nbr_mots_cles']; 
echo $nbr_mots_cles;
?>
        </td>
      </tr>
      <tr>
        <td class="td_1">Nombre de pages à executer:</td>
        <td class="td_1"><?php
$nbr_pages=$_SESSION['nbr_pages'];
echo $nbr_pages; 
?>
        </td>
      </tr>
      <tr>
        <td class="td_1">Estimation du temps d'execution en sec :</td>
        <td class="td_1"><?php
$temps = round($nbr_pages/65 * $nbr_mots_cles)*2;
echo $temps;
?>
        </td>
      </tr>
      <tr>
        <td class="td_1"></td>
        <td class="td_1"></td>
        <td class="td_1"><a class="a1" onClick="passage_global();">Execution
            Global</a> </td>
      </tr>
    </table>
    </fieldset>
    <fieldset>
    <legend>Execution Personnalisé</legend>
    <table>
      
      <tr>
        <td class="td_1"><label for="moteur">Moteur de Recherche :</label></td>
        <td class="td_1"><input type="checkbox" name="google" id="google" tabindex="70" />
          <label for="google">Google</label>
          <br />
          <input type="checkbox" name="yahoo" id="yahoo"  tabindex="80"/>
          <label for="yahoo">Yahoo</label>
          <br />
        </td>
      </tr>
      <tr>
        <td class="td_1"><label for="liste_sites">Sites de <?php echo $_SESSION['prenom']; ?>:</label></td>
        <td class="td_1"><select name="liste_sites" id="liste_sites" tabindex="10">
            <option value="0" selected="selected"></option>
            <?php 
							//afficher la liste des sites depuis la base de donnees de l'utilisateur connecté
							$reponse=mysql_query("select * from sites,correspondance where sites.id=correspondance.id_site and id_utilisateur='".$_SESSION['id']."'");
							if (mysql_num_rows($reponse))
							{
								while ($donnees=mysql_fetch_array($reponse))
								{
									echo '<option value="'.$donnees['id'].'"';
									if (isset($_POST['liste_sites']) and $_POST['liste_sites'] == $donnees['id']) echo 'selected="selected"';
									echo '>'.$donnees['nom'];
									echo "</option>";									
								}								
					   		} 
			?>
          </select>
        </td>
        <td class="td_1"><a class="a1" onClick="passage_site();">Execution par
            Site</a> </td>
      </tr>
      <tr>
        <td class="td_1"><label for="liste_mots_cles">Mots cles de <?php echo $_SESSION['prenom']; ?> :</label></td>
        <td class="td_1"><select name="liste_mots_cles" id="liste_mots_cles" tabindex="50">
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
        <td class="td_1"><a class="a1" onClick="passage_mot_cle();">Execution
            par Mot Clé</a> </td>
         </tr>   
    </table>
    </fieldset>
  </form>
<?php }//fermeture de l'accolade de plus qu'une exection par jour ?>  
</div>
<?php include("squelette/piedPage.php"); ?>
</body>
</html>
<?php mysql_close(); // On n'oublie pas de fermer la connexion à MySQL ;o) ?>
