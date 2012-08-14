<?php
session_start(); // On démarre la session AVANT toute chose
include("connexion.php");

//en cas de modification du formulaire options d'execution
if (isset($_POST['nbr_pages']) and isset($_POST['connexion']) )
{
	//pour éviter le piratage
	$nbr_pages = mysql_real_escape_string(htmlspecialchars($_POST['nbr_pages']));
	$connexion = mysql_real_escape_string(htmlspecialchars($_POST['connexion']));
	
	//mettre à jour la base
	mysql_query("UPDATE options_utilisateurs SET nbr_pages='".($nbr_pages*10)."', connexion='".$connexion."' WHERE id_utilisateur='".$_SESSION['id']."'");
	
	//mettre à jour les variables de session
	 $_SESSION['nbr_pages'] = $nbr_pages*10;
	 $_SESSION['connexion'] = $connexion;

}
	 		 
//en cas de modification du formulaire options du proxy
if ( isset($_POST['port']) and isset($_POST['http']) )
{
	mysql_query("UPDATE options_utilisateurs SET port_proxy='".$_POST['port']."', http_proxy='".$_POST['http']."' WHERE id_utilisateur='".$_SESSION['id']."'"); 
	$_SESSION['port']=$_POST['port'];
	$_SESSION['http']=$_POST['http'];
}
	 
//en cas d'un choix d'un effacement total du historique
if ( isset($_POST['confirmation']) and strcmp($_POST['confirmation'],"oui")==0 )
{
	$reponse=mysql_query("SELECT position.id as id FROM correspondance,sites,mots_cles,position WHERE correspondance.id_site=sites.id AND sites.id=mots_cles.id_site AND position.id_mot_cle=mots_cles.id AND correspondance.id_utilisateur=".$_SESSION['id']);
	while ($donnees=mysql_fetch_array($reponse))
	{
		mysql_query("DELETE FROM position WHERE id='".$donnees['id']."'");
	}
}

//en cas d'un choix d'un effacement de l'historique d'un mot clé
if (isset($_GET['mot_cle']))
{
	mysql_query("DELETE FROM position WHERE id_mot_cle='".$_GET['mot_cle']."'");
}

//en cas d'un choix d'un effacement de l'historique d'un site
if (isset($_GET['site']))
{
	$reponse=mysql_query("SELECT * FROM mots_cles WHERE id_site='".$_GET['site']."'");
	while($donnees=mysql_fetch_array($reponse))
	{
		mysql_query("DELETE FROM position WHERE id_mot_cle='".$donnees['id']."'");	
	}
}	

//en cas d'un choix d'un effacement de l'historique d'une execution
if (isset($_GET['date']))
{
	mysql_query("DELETE FROM position WHERE date='".$_GET['date']."'");
}	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Plate Forme de Référencement</title>
<link href="Style/Style.css" rel="stylesheet" type="text/css" />
<script language="javascript">
function effacer_tout()
{
  var bool;
  bool=confirm("Etes vous vraiment sûr de vouloir effacer tout votre historique?"); 
  if (bool==true) 
  document.forms['options_historique'].submit();
}

function effacer_date()
{
  if (document.forms['options_historique'].liste_dates.value!=0)
  {	
    var bool;
    bool=confirm("Etes vous vraiment sûr de vouloir effacer l'historique d'une éxecution?"); 
    if (bool==true)
    self.location.href="options.php?date="+document.forms['options_historique'].liste_dates.value;
  }
  else alert ("veuiller choisir un mot clé ");	
}

function effacer_site()
{
  if (document.forms['options_historique'].liste_sites.value!=0)
  {	
    var bool;
    bool=confirm("Etes vous vraiment sûr de vouloir effacer l'historique du site selectionné?"); 
    if (bool==true)
    self.location.href="options.php?site="+document.forms['options_historique'].liste_sites.value;
  }
  else alert ("veuiller choisir un site ");	
}

function effacer_mot_cle()
{
  if (document.forms['options_historique'].liste_mots_cles.value!=0)
  {	
    var bool;
    bool=confirm("Etes vous vraiment sûr de vouloir effacer l'historique du mot clé selectionné?"); 
    if (bool==true)
    self.location.href="options.php?mot_cle="+document.forms['options_historique'].liste_mots_cles.value;
  }
  else alert ("veuiller choisir un mot clé ");	
}
        
</script>
</head>
<body>
<?php include ("squelette/titre.php"); ?>
<?php include ("squelette/menuGlobal.php");?>
<?php include ("squelette/menuContextuel.php");?>
<div id="corps">
  <h1> Gérer vos Options </h1>
  <form name="options_execution" action="options.php" method="post">
    <fieldset>
    <legend>Options d'execution</legend>
    <table>
      <tr>
        <td class="td_1"><label for="nbr_pages">Nombre de Pages d'execution</label></td>
        <td class="td_1"><input type="text" name="nbr_pages" id="nbr_pages" value="<?php echo ($_SESSION['nbr_pages']/10) ?>"  tabindex="1" /></td>
      </tr>
      <tr>
        <td class="td_1"><label>Type de Connexion :</label></td>
        <td class="td_1"><?php
         echo '<input type="radio" name="connexion" value="direct" id="direct"  tabindex="3"'; 
         if (strcmp($_SESSION['connexion'],"direct")==0) echo 'checked="checked"/>';
		      else echo '/>'
		?>
          <label for="direct">Direct</label>
          <br />
          <?php
		 	  echo '<input type="radio" name="connexion" value="proxy" id="proxy"  tabindex="4"';
              if (strcmp($_SESSION['connexion'],"proxy")==0) echo 'checked="checked"/>';
		      else echo '/>'
		?>
          <label for="proxy">Proxy</label>
          <br />
        </td>
      </tr>
      <tr>
        <td class="td_1"><input type="reset"  tabindex="10"/></td>
        <td class="td_1"><input type="submit"  value="Executer" tabindex="20"/></td>
      </tr>
    </table>
    </fieldset>
  </form>

<?php
// verfier l'option de connexion est le proxy 
if (isset($_SESSION['connexion']) and strcmp($_SESSION['connexion'],"proxy")==0  )
  {  
?> 
<form name="proxy" action="options.php" method="post">
<fieldset>
<legend>Configuration du Proxy</legend>
<table>
      <tr>
        <td class="td_1"><label for="port">Port du Serveur Proxy</label></td>
        <td class="td_1"><input type="text" name="port" id="port" value="<?php echo $_SESSION['port']; ?>"  tabindex="30" /></td>
      </tr>
      <tr>
        <td class="td_1"><label for="http">Http du Serveur Proxy</label></td>
        <td class="td_1"><input type="text" name="http" id="http" value="<?php echo $_SESSION['http']; ?>"  tabindex="40" /></td>
      </tr>
      <tr>
        <td class="td_1"><input type="reset"  tabindex="50"/></td>
        <td class="td_1"><input type="submit"  value="Executer" tabindex="60"/></td>
      </tr>      
</table>            
</fieldset>
</form>
<?php
}//fermeture de l'accolade de verification d'une connexion proxy ou pas
?> 
  <form name="options_historique" action="options.php" method="post">
    <fieldset>
    <legend>Options d'historique</legend>
<table>
      <tr>
        <td ><label for="liste_dates">Effacer l'historique d'une execution :</label></td>
        <td ><select class="select_1" name="liste_dates" id="liste_dates" tabindex="42" >
            <option value="0" selected="selected"></option>
            <?php 
							//afficher la liste des mots cles depuis la base de donnees de l'utilisateur connecté
							$reponse=mysql_query("select distinct(date) from position,mots_cles,sites,correspondance where mots_cles.id_site=sites.id AND mots_cles.id=position.id_mot_cle AND correspondance.id_site=sites.id AND correspondance.id_utilisateur='".$_SESSION['id']."'");
							if (mysql_num_rows($reponse))
							{
								//on va faire un affichage du select par date croissante
								
								//inserrer dans un tableau
								$i=0;
								while ($donnees=mysql_fetch_array($reponse))
								{	
                        			$tab[$i]=$donnees["date"];
                        			$i=$i+1;
                      			}
								
								//trier le tableau
                    		    for($j=0;$j < $i;$j++)
                        		for($h=$j+1;$h < $i;$h++)
                            	{if ($tab[$j] > $tab[$h]) 
                                  {
								   $tmp=$tab[$j];
                                   $tab[$j]= $tab[$h];
                                   $tab[$h]=$tmp;                                  
                                  }
                             	}
								
								//afficher le tableau trié    
                      			for($j=0;$j < $i;$j++)
                  			    {													
									$date=date('d/m/Y', $tab[$j]);
									echo '<option value="'.$tab[$j].'"';
									if (isset($_POST['liste_dates']) and ($_POST['liste_dates']==$tab[$j])) echo 'selected="selected"';
									echo '>'.$date;
									echo "</option>";
                    		    }    
							}	
	
							?>
          </select>
        </td>
        <td class="td_1"><td><a class="a1" onclick="effacer_date();">Valider</a></td>        
       </tr> 
      <tr>
        <td class="td_1"><label for="liste_sites">Effacer l'historique d'un site</label></td>
        <td class="td_1"><select name="liste_sites" class="select_1"  id="liste_sites"   tabindex="70">
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
        <td class="td_1"><td><a class="a1" onclick="effacer_site();">Valider</a></td>
      </tr>
      <tr>
        <td class="td_1"><label for="liste_mots_cles">Effacer l'historique d'un Mot Clé</label></td>
        <td class="td_1"><select name="liste_mots_cles" class="select_1" id="liste_mots_cles"  tabindex="90">
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
        <td class="td_1"><td><a class="a1" onclick="effacer_mot_cle();">Valider</a></td>         
      </tr>      
      <tr>
        <td class="td_1"><label>Effacer tout l'historique</label></td>
        <td class="td_1"><input type="radio" name="confirmation" value="oui" id="oui"  tabindex="100	" />
          <label for="oui">Oui</label>
          <br />
          <input type="radio" name="confirmation" value="non" id="non" checked="checked" tabindex="110"/>
          <label for="non">Non</label>
          <br />
        </td>
        <td class="td_1"><td><a class="a1" onclick="effacer_tout();">Valider</a></td>  
      </tr>      
</table>    
    
    </fieldset>
  </form>
</div>
<?php include("squelette/piedPage.php"); ?>
</body>
</html>
<?php mysql_close(); // On n'oublie pas de fermer la connexion à MySQL ;o) ?>
