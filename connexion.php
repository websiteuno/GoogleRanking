<?php

  $dbase="plate_forme";
  $host="localhost";
  $login="root";
  $pass="";
  $db = mysql_connect($host, $login, $pass) or die("Un problème de connection avec la base de données");
  mysql_select_db($dbase);

?>