<div id="menu_global">
  <table class="table_menu">
    <tr>

      <td class="td_4"><a  href="index.php">Acceuil</a></td>
      <?php
if (isset($_SESSION['privilege']) and (strcmp($_SESSION['privilege'],"admin")==0))
{?>
      <td class="td_4"><a  href="admin/page_admin.php">Administrateur </a></td>
      <?php 
}    
?>      
      <td class="td_4"><a  href="forum.php">Forum</a></td>
      <td class="td_4"><a  href="equipe.php">L'équipe</a></td>
      <td class="td_4"><a  href="livreor.php">Livre d'or</a></td>
      <td></td>
      <?php
if (isset($_SESSION['login']))
{?>
      <td class="td_4" ><a  href="dialogue/deconnexion.php">Déconnexion</a></td>
      <?php 
}
else
{
?>
      <td class="td_4" ><a  href="page_connexion.php">Se Connecter</a></td>
      <?php
}
?>
    </tr>
  </table>
</div>