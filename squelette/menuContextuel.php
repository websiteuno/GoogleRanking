<div id="menu_contextuel">
  <!-- Ici on mettra le menu -->
  <!-- Cadre englobant tous les sous-menus (en bleu marine sur le schéma) -->
<?php //verfier si l'utilisateur est connecté ou pas
if (isset($_SESSION['login']))
  { ?>
  <div class="element_menu">
    <!-- Cadre correspondant à un sous-menu -->
    <h4>Salut <?php echo $_SESSION['prenom']?></h4>
    <!-- Titre du sous-menu -->
    <ul>
      <li><a href="profil.php">Profil</a></li>
      <li><a href="sites.php">Mes Sites</a></li>
      <li><a href="mots_cles.php">Mes Mots Cles</a></li>
      <li><a href="options.php">Mes Options</a></li>
      <li><a href="page_execution.php">Execution</a></li>
    </ul>
  </div>

    <div class="element_menu">
    <!-- Cadre correspondant à un sous-menu -->
    <h4>Suivi du Référencement</h4>
    <!-- Titre du sous-menu -->
    <ul>
      <li><a href="positionnement_rapide.php">Pos Rapide</a></li>
      <li><a href="tableau.php">Tableau</a></li>
      <li><a href="variation.php">Variation</a></li>
      <li><a href="consul_perso.php">Consul Perso</a></li>
      <li><a href="suivi.php">Le Suivi</a></li>        
      <li><a href="diagramme.php">Diagramme</a></li>
      </ul>
  </div>
  <?php 
  } 
  else {
  ?>  
    <div class="element_menu">
    <!-- Cadre correspondant à un sous-menu -->
    <h4>Suivi du Référencement</h4>
    <!-- Titre du sous-menu -->
    <ul>
      <li><a href="page_connexion.php">Pos Rapide</a></li>
      <li><a href="page_connexion.php">Tableau</a></li>
      <li><a href="page_connexion.php">Variation</a></li>
      <li><a href="page_connexion.php">Consul Perso</a></li>
      <li><a href="page_connexion.php">Suivi</a></li>        
      <li><a href="page_connexion.php">Diagramme</a></li>
      </ul>
  </div>
  <?php 
  } 
  ?>
  <div class="element_menu">
    <h4>Référencement</h4>
    <!-- Titre du sous-menu -->
    <ul>
      <li><a href="ref_naturel.php">Ref Naturel</a></li>
      <li><a href="ref_resultat.php">Ref Resultat</a></li>
      <li><a href="ref_comport.php">Ref Comport</a></li>
    </ul>
  </div>
</div>
</div>