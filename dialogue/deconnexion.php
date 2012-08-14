<?php 
session_start();
$fichier = '../tmp.txt' ;
if (file_exists($fichier)) unlink($fichier) ;
session_destroy();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="3;url=../index.php">
<title>Site de Référencement Gratuit</title>
<link href="../Style/Style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="message_validation">
	Message n°7 :<br />
	<strong>Vous êtes bien déconnecté(e).</strong>

	<br />
	<br />
	
			Vous allez être redirigé dans 3 secondes<br /><br />
		
		<div id="pas_attendre">
		
		

		<a href="../index.php">Ne pas attendre</a></div>
	
</div>

</body>
</html>
