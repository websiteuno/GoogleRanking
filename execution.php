<?php 

//ouvir la session
session_start();
//inclure le fichier connexion.php pour se connecter à la base de données.
include("connexion.php");
//déclaration des bibliothéque
if (strcmp($_SESSION['connexion'],"proxy")==0)
require_once('classes/moteurs_proxy.php');
else
require_once('classes/moteurs_direct.php');


//***************************************************************Execution à travers un serveur Proxy*********************************************************************
if (strcmp($_SESSION['connexion'],"proxy")==0)
{
//------------------------------------------------------------------------------------------------------------------------------------



//Execution Global
//on verifie tout dabort qu'on a choisi une execution par site et que les 2 variables dont on a besoin sont presents
if (isset($_GET['execution_global']))
{
	//on commence par extraire tout les mots clés de l'utilisateur
	$reponse=mysql_query("select sites.url as url,mots_cles.id as id, mots_cles.valeur as valeur from mots_cles,sites,correspondance where mots_cles.id_site=sites.id AND correspondance.id_site=sites.id AND correspondance.id_utilisateur='".$_SESSION['id']."'");
	$date=time();

	//si le moteur choisi par l'utilisateur est Google
	if ($_GET['moteur']==1)
	{	
		//tant qu'il y'aura des mots clés à traiter executer le fonction de positionnement ensuite enregistrer dans la table position
		while ($donnees=mysql_fetch_array($reponse))
		{
			$position=google_getpos($donnees['url'],$donnees['valeur'],$_SESSION['nbr_pages'],$_SESSION['http'],$_SESSION['port']);
			mysql_query("INSERT INTO position VALUES('','".$date."', '" . $donnees['id'] ."','google','".$position."')");	
		}
		//Redirection vers la page precedente
		header('location:./page_execution.php');
	}
	
	//si le moteur choisi par l'utilisateur est yahoo
	if ($_GET['moteur']==2)
	{
		while ($donnees=mysql_fetch_array($reponse))
		{	
			//tant qu'il y'aura des mots clés à traiter executer le fonction de positionnement ensuite enregistrer dans la table position
			$position=yahoo_getpos($donnees['url'],$donnees['valeur'],$_SESSION['http'],$_SESSION['port']);
			mysql_query("INSERT INTO position VALUES('','".$date."', '" . $donnees['id'] ."','yahoo','".$position."')");	
		}
		//Redirection vers la page precedente
		 header('location:./page_execution.php');
	
	}
	

	//si l'utilisateur a choisi google et yahoo
	if ($_GET['moteur']==3)
	{	
		//tant qu'il y'aura des mots clés à traiter executer le fonction de positionnement ensuite enregistrer dans la table position
		while ($donnees=mysql_fetch_array($reponse))
		{
			$position=google_getpos($donnees['url'],$donnees['valeur'],$_SESSION['nbr_pages'],$_SESSION['http'],$_SESSION['port']);
			mysql_query("INSERT INTO position VALUES('','".$date."', '" . $donnees['id'] ."','google','".$position."')");	

			$position=yahoo_getpos($donnees['url'],$donnees['valeur'],$_SESSION['http'],$_SESSION['port']);
			mysql_query("INSERT INTO position VALUES('','".$date."', '" . $donnees['id'] ."','yahoo','".$position."')");	
		}
		//Redirection vers la page precedente
	 	header('location:./page_execution.php');

	}
}








//-----------------------------------------------------------------------------------------------------------------------------------










//Execution par site
//on verifie tout dabort qu'on a choisi une execution par site et que les 2 variables dont on a besoin sont presents
if (isset($_GET['site']))
{	
		
	//pour eviter le piratage
	$site=mysql_real_escape_string(htmlspecialchars($_GET['site']));
	//on commence par extraire les mots clés du site choisi
	$reponse=mysql_query("SELECT url,valeur,mots_cles.id as id  FROM sites,mots_cles WHERE sites.id=mots_cles.id_site AND sites.id='".$site."'");
	
	$date=time();	


	//si le moteur choisi par l'utilisateur est Google
	if ($_GET['moteur']==1)
	{	
		//tant qu'il y'aura des mots clés à traiter executer le fonction de positionnement ensuite enregistrer dans la table position
		while ($donnees=mysql_fetch_array($reponse))
		{
			$position=google_getpos($donnees['url'],$donnees['valeur'],$_SESSION['nbr_pages'],$_SESSION['http'],$_SESSION['port']);
			mysql_query("INSERT INTO position VALUES('','".$date."', '" . $donnees['id'] ."','google','".$position."')");	
		}
		//Redirection vers la page precedente
		header('location:./page_execution.php');
	}


	
	//si le moteur choisi par l'utilisateur est yahoo
	if ($_GET['moteur']==2)
	{
		while ($donnees=mysql_fetch_array($reponse))
		{	
			//tant qu'il y'aura des mots clés à traiter executer le fonction de positionnement ensuite enregistrer dans la table position
			$position=yahoo_getpos($donnees['url'],$donnees['valeur'],$_SESSION['http'],$_SESSION['port']);
			mysql_query("INSERT INTO position VALUES('','".$date."', '" . $donnees['id'] ."','yahoo','".$position."')");	
		}
		//Redirection vers la page precedente
		 header('location:./page_execution.php');
	
	}

	

	//si l'utilisateur a choisi google et yahoo
	if ($_GET['moteur']==3)
	{	
		//tant qu'il y'aura des mots clés à traiter executer le fonction de positionnement ensuite enregistrer dans la table position
		while ($donnees=mysql_fetch_array($reponse))
		{
			$position=google_getpos($donnees['url'],$donnees['valeur'],$_SESSION['nbr_pages'],$_SESSION['http'],$_SESSION['port']);
			mysql_query("INSERT INTO position VALUES('','".$date."', '" . $donnees['id'] ."','google','".$position."')");	

			$position=yahoo_getpos($donnees['url'],$donnees['valeur'],$_SESSION['http'],$_SESSION['port']);
			mysql_query("INSERT INTO position VALUES('','".$date."', '" . $donnees['id'] ."','yahoo','".$position."')");	
		}
		//Redirection vers la page precedente
	 	header('location:./page_execution.php');

	}

}	











//-------------------------------------------------------------------------------------------------------------------------------------










//Execution par mot clé
//on verifie tout dabort qu'on a choisi une execution par mot clé et que les 2 variables dont on a besoin sont presents
if (isset($_GET['mot_cle']))
{	
	//pour eviter le piratage
	$mot_cle=mysql_real_escape_string(htmlspecialchars($_GET['mot_cle']));
    //on commence par extraire les mots clés du site choisi
	$reponse=mysql_query("SELECT * FROM mots_cles,sites WHERE sites.id=mots_cles.id_site AND mots_cles.id='".$mot_cle."'");
	
	$date=time();
	
	if ($_GET['moteur']==1)
	{	
		//tant qu'il y'aura des mots clés à traiter executer le fonction de positionnement ensuite enregistrer dans la table position
		while ($donnees=mysql_fetch_array($reponse))
		{
			$position=google_getpos($donnees['url'],$donnees['valeur'],$_SESSION['nbr_pages'],$_SESSION['http'],$_SESSION['port']);
			mysql_query("INSERT INTO position VALUES('','".$date."', '" . $mot_cle ."','google','".$position."')");	
		}

		//Redirection vers la page precedente
		header('location:./page_execution.php');
	}

	if ($_GET['moteur']==2)
	{	
		//tant qu'il y'aura des mots clés à traiter executer le fonction de positionnement ensuite enregistrer dans la table position
		while ($donnees=mysql_fetch_array($reponse))
		{
			$position=yahoo_getpos($donnees['url'],$donnees['valeur'],$_SESSION['http'],$_SESSION['port']);
			mysql_query("INSERT INTO position VALUES('','".$date."', '" . $mot_cle ."','yahoo','".$position."')");	
		}

		 //Redirection vers la page precedente
		 header('location:./page_execution.php');
	
	}

	if ($_GET['moteur']==3)
	{
		//tant qu'il y'aura des mots clés à traiter executer le fonction de positionnement ensuite enregistrer dans la table position
		while ($donnees=mysql_fetch_array($reponse))
		{
			$position=google_getpos($donnees['url'],$donnees['valeur'],$_SESSION['nbr_pages'],$_SESSION['http'],$_SESSION['port']);
			mysql_query("INSERT INTO position VALUES('','".$date."', '" . $mot_cle ."','google','".$position."')");	

			$position=yahoo_getpos($donnees['url'],$donnees['valeur'],$_SESSION['http'],$_SESSION['port']);
			mysql_query("INSERT INTO position VALUES('','".$date."', '" . $mot_cle ."','yahoo','".$position."')");	
		}

	 	//Redirection vers la page precedente
		header('location:./page_execution.php');
	}
	
}	



//------------------------------------------------------------------------------------------------------------------------------------------------------------------

}

//*********************************************************Execution normal*****************************************************************************************

else 
{

//------------------------------------------------------------------------------------------------------------------------------------



//Execution Global
//on verifie tout dabort qu'on a choisi une execution par site et que les 2 variables dont on a besoin sont presents
if (isset($_GET['execution_global']))
{
	//on commence par extraire tout les mots clés de l'utilisateur
	$reponse=mysql_query("select sites.url as url,mots_cles.id as id, mots_cles.valeur as valeur from mots_cles,sites,correspondance where mots_cles.id_site=sites.id AND correspondance.id_site=sites.id AND correspondance.id_utilisateur='".$_SESSION['id']."'");
	$date=time();

	//si le moteur choisi par l'utilisateur est Google
	if ($_GET['moteur']==1)
	{	
		//tant qu'il y'aura des mots clés à traiter executer le fonction de positionnement ensuite enregistrer dans la table position
		while ($donnees=mysql_fetch_array($reponse))
		{
			$position=google_getpos($donnees['url'],$donnees['valeur'],$_SESSION['nbr_pages']);
			mysql_query("INSERT INTO position VALUES('','".$date."', '" . $donnees['id'] ."','google','".$position."')");	
		}
		//Redirection vers la page precedente
		header('location:./page_execution.php');
	}
	
	//si le moteur choisi par l'utilisateur est yahoo
	if ($_GET['moteur']==2)
	{
		while ($donnees=mysql_fetch_array($reponse))
		{	
			//tant qu'il y'aura des mots clés à traiter executer le fonction de positionnement ensuite enregistrer dans la table position
			$position=yahoo_getpos($donnees['url'],$donnees['valeur']);
			mysql_query("INSERT INTO position VALUES('','".$date."', '" . $donnees['id'] ."','yahoo','".$position."')");	
		}
		//Redirection vers la page precedente
		 header('location:./page_execution.php');
	
	}
	

	//si l'utilisateur a choisi google et yahoo
	if ($_GET['moteur']==3)
	{	
		//tant qu'il y'aura des mots clés à traiter executer le fonction de positionnement ensuite enregistrer dans la table position
		while ($donnees=mysql_fetch_array($reponse))
		{
			$position=google_getpos($donnees['url'],$donnees['valeur'],$_SESSION['nbr_pages']);
			mysql_query("INSERT INTO position VALUES('','".$date."', '" . $donnees['id'] ."','google','".$position."')");	

			$position=yahoo_getpos($donnees['url'],$donnees['valeur']);
			mysql_query("INSERT INTO position VALUES('','".$date."', '" . $donnees['id'] ."','yahoo','".$position."')");	
		}
		//Redirection vers la page precedente
	 	header('location:./page_execution.php');

	}
}








//-----------------------------------------------------------------------------------------------------------------------------------










//Execution par site
//on verifie tout dabort qu'on a choisi une execution par site et que les 2 variables dont on a besoin sont presents
if (isset($_GET['site']))
{	
		
	//pour eviter le piratage
	$site=mysql_real_escape_string(htmlspecialchars($_GET['site']));
	//on commence par extraire les mots clés du site choisi
	$reponse=mysql_query("SELECT url,valeur,mots_cles.id as id  FROM sites,mots_cles WHERE sites.id=mots_cles.id_site AND sites.id='".$site."'");
	
	$date=time();	


	//si le moteur choisi par l'utilisateur est Google
	if ($_GET['moteur']==1)
	{	
		//tant qu'il y'aura des mots clés à traiter executer le fonction de positionnement ensuite enregistrer dans la table position
		while ($donnees=mysql_fetch_array($reponse))
		{
			$position=google_getpos($donnees['url'],$donnees['valeur'],$_SESSION['nbr_pages']);
			mysql_query("INSERT INTO position VALUES('','".$date."', '" . $donnees['id'] ."','google','".$position."')");	
		}
		//Redirection vers la page precedente
		header('location:./page_execution.php');
	}


	
	//si le moteur choisi par l'utilisateur est yahoo
	if ($_GET['moteur']==2)
	{
		while ($donnees=mysql_fetch_array($reponse))
		{	
			//tant qu'il y'aura des mots clés à traiter executer le fonction de positionnement ensuite enregistrer dans la table position
			$position=yahoo_getpos($donnees['url'],$donnees['valeur']);
			mysql_query("INSERT INTO position VALUES('','".$date."', '" . $donnees['id'] ."','yahoo','".$position."')");	
		}
		//Redirection vers la page precedente
		 header('location:./page_execution.php');
	
	}

	

	//si l'utilisateur a choisi google et yahoo
	if ($_GET['moteur']==3)
	{	
		//tant qu'il y'aura des mots clés à traiter executer le fonction de positionnement ensuite enregistrer dans la table position
		while ($donnees=mysql_fetch_array($reponse))
		{
			$position=google_getpos($donnees['url'],$donnees['valeur'],$_SESSION['nbr_pages']);
			mysql_query("INSERT INTO position VALUES('','".$date."', '" . $donnees['id'] ."','google','".$position."')");	

			$position=yahoo_getpos($donnees['url'],$donnees['valeur']);
			mysql_query("INSERT INTO position VALUES('','".$date."', '" . $donnees['id'] ."','yahoo','".$position."')");	
		}
		//Redirection vers la page precedente
	 	header('location:./page_execution.php');

	}

}	











//-------------------------------------------------------------------------------------------------------------------------------------










//Execution par mot clé
//on verifie tout dabort qu'on a choisi une execution par mot clé et que les 2 variables dont on a besoin sont presents
if (isset($_GET['mot_cle']))
{	
	//pour eviter le piratage
	$mot_cle=mysql_real_escape_string(htmlspecialchars($_GET['mot_cle']));
    //on commence par extraire les mots clés du site choisi
	$reponse=mysql_query("SELECT * FROM mots_cles,sites WHERE sites.id=mots_cles.id_site AND mots_cles.id='".$mot_cle."'");
	
	$date=time();
	
	if ($_GET['moteur']==1)
	{	
		//tant qu'il y'aura des mots clés à traiter executer le fonction de positionnement ensuite enregistrer dans la table position
		while ($donnees=mysql_fetch_array($reponse))
		{
			$position=google_getpos($donnees['url'],$donnees['valeur'],$_SESSION['nbr_pages']);
			mysql_query("INSERT INTO position VALUES('','".$date."', '" . $mot_cle ."','google','".$position."')");	
		}

		//Redirection vers la page precedente
		header('location:./page_execution.php');
	}

	if ($_GET['moteur']==2)
	{	
		//tant qu'il y'aura des mots clés à traiter executer le fonction de positionnement ensuite enregistrer dans la table position
		while ($donnees=mysql_fetch_array($reponse))
		{
			$position=yahoo_getpos($donnees['url'],$donnees['valeur']);
			mysql_query("INSERT INTO position VALUES('','".$date."', '" . $mot_cle ."','yahoo','".$position."')");	
		}

		 //Redirection vers la page precedente
		 header('location:./page_execution.php');
	
	}

	if ($_GET['moteur']==3)
	{
		//tant qu'il y'aura des mots clés à traiter executer le fonction de positionnement ensuite enregistrer dans la table position
		while ($donnees=mysql_fetch_array($reponse))
		{
			$position=google_getpos($donnees['url'],$donnees['valeur'],$_SESSION['nbr_pages']);
			mysql_query("INSERT INTO position VALUES('','".$date."', '" . $mot_cle ."','google','".$position."')");	

			$position=yahoo_getpos($donnees['url'],$donnees['valeur']);
			mysql_query("INSERT INTO position VALUES('','".$date."', '" . $mot_cle ."','yahoo','".$position."')");	
		}

	 	//Redirection vers la page precedente
		header('location:./page_execution.php');
	}
	
}	



//------------------------------------------------------------------------------------------------------------------------------------------------------------------



}


mysql_close(); // On n'oublie pas de fermer la connexion à MySQL ;o) 
?>