<?php

  $dbase="plate_forme";
  $host="localhost";
  $login="root";
  $pass="";
  $db = mysql_connect($host, $login, $pass) or die("Un probl�me de connection avec la base de donn�es");
  mysql_select_db($dbase);

?>