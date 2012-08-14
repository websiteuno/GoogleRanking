<?php
include("connexion.php");
include ("jpgraph/src/jpgraph.php");
include ("jpgraph/src/jpgraph_line.php");

$tableauAnnees = array();
$tableauNombreVentes = array();
$moisFr = array();

$reponse = mysql_query("SELECT position, date FROM position WHERE id_mot_cle='".$_GET['liste_mots_cles']."' GROUP BY date ORDER BY `position`.`date` ASC");
while ($donnees = mysql_fetch_array($reponse)) 
	{
	 $date=date('d/m/Y', $donnees['date']);
 	 $moisFr[]= $date;
	}
// *********************
// Production de données
// *********************

$sql_ventes_par_mois = "SELECT position, date FROM position WHERE id_mot_cle='".$_GET['liste_mots_cles']."' GROUP BY date ORDER BY `position`.`date` ASC";


// Initialiser le tableau à 0 pour chaques mois ***********************

$mysqlQuery = @mysql_query($sql_ventes_par_mois) or die('Pb de requête');

while ($row_mois = mysql_fetch_array($mysqlQuery,  MYSQL_ASSOC)) {
	$tableauVentes2006[] = $row_mois['position']; 
}
//print_r($tableauVentes2006);
// Contrôler les valeurs du tableau
// printf('<pre>%s</pre>', print_r($tableauVentes2006,1));

// ***********************
// Création du graphique
// ***********************

// Création du conteneur
$graph = new Graph(500,300);

// Fixer les marges
$graph->img->SetMargin(40,30,50,40);    


// Lissage sur fond blanc (évite la pixellisation)
$graph->img->SetAntiAliasing("white");

// A détailler
$graph->SetScale("textlin");

// Ajouter une ombre
$graph->SetShadow();

// Ajouter le titre du graphique
$graph->title->Set("Courbe du Suivi du Référencement");

// Afficher la grille de l'axe des ordonnées
$graph->ygrid->Show();
// Fixer la couleur de l'axe (bleu avec transparence : @0.7)
$graph->ygrid->SetColor('blue@0.7');
// Des tirets pour les lignes
$graph->ygrid->SetLineStyle('dashed');

// Afficher la grille de l'axe des abscisses
$graph->xgrid->Show();
// Fixer la couleur de l'axe (rouge avec transparence : @0.7)
$graph->xgrid->SetColor('red@0.7');
// Des tirets pour les lignes
$graph->xgrid->SetLineStyle('dashed');

// Apparence de la police
$graph->title->SetFont(FF_ARIAL,FS_BOLD,11);

// Créer une courbes
$courbe = new LinePlot($tableauVentes2006);

// Afficher les valeurs pour chaque point
$courbe->value->Show();

// Valeurs: Apparence de la police
$courbe->value->SetFont(FF_ARIAL,FS_NORMAL,9);
$courbe->value->SetFormat('%d');
$courbe->value->SetColor("red");

// Chaque point de la courbe ****
// Type de point
$courbe->mark->SetType(MARK_FILLEDCIRCLE);
// Couleur de remplissage
$courbe->mark->SetFillColor("green");
// Taille
$courbe->mark->SetWidth(5);

// Couleur de la courbe
$courbe->SetColor("blue");
$courbe->SetCenter();

// Paramétrage des axes
$graph->xaxis->title->Set("Dates");
$graph->yaxis->title->Set("Positions");
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->SetTickLabels($moisFr);

// Ajouter la courbe au conteneur
$graph->Add($courbe);

$graph->Stroke();

?>
