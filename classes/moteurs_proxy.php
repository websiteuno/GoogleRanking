<?php



function google_getpos($url, $motCle, $nbr_lignes, $http, $port)
{
$url = "http://".$url."/*";
$motCle = urlencode( $motCle );
$ggquery = "search?hl=fr";

$position = 1; 
$serveur = "www.google.com"; 
$google = "http://$serveur/$ggquery&start=0&num=$nbr_lignes&q=$motCle"; 

if ( !preg_match("!^http://!",$url) )      $url = "http://$url"; 

if ( preg_match("!^http://[^/]+$!",$url) )   $url .= '/'; 

$url = str_replace 
( 
   array( '.', '+', '?', '(', ')', '[', ']', '*' ), 
   array( '\.', '\+', '\?', '\(', '\)', '\[', '\]', '.*?' ), 
   $url 
); 


$aContext = array(
    'http' => array(
        'proxy' => 'tcp://'.$http.':'.$port, // This needs to be the server and the port of the NTLM Authentication Proxy Server.
        'request_fulluri' => True,
        ),
    );
$cxContext = stream_context_create($aContext);
$html = file_get_contents($google, False, $cxContext ) or die("Pas de connexion à internet"); 


	foreach ( split('<br>',$html) as $ligne ) 
	{ 
		if ( preg_match("!<a href=\"http://[^\"]+\" class=l!",$ligne) ) 
		{ 
 			if ( preg_match("!<a href=\"$url\" class=l!",$ligne) ) 
 				{return $position;
 				}
			$position++;	   
		}
	}
return -1;
}










function yahoo_getpos($url, $motCle,$port,$http)
{
$url = "http://".$url."/*";
$motCle = urlencode( $motCle );
$yahoo_query = "search";

$position = 1; 
$serveur = "fr.search.yahoo.com";
$yahoo = "http://$serveur/$yahoo_query?p=$motCle&pstart=0";  


if ( !preg_match("!^http://!",$url) )      $url = "http://$url"; 

if ( preg_match("!^http://[^/]+$!",$url) )   $url .= '/'; 

$url = str_replace 
( 
   array( '.', '+', '?', '(', ')', '[', ']', '*' ), 
   array( '\.', '\+', '\?', '\(', '\)', '\[', '\]', '.*?' ), 
   $url 
); 

$aContext = array(
    'http' => array(
        'proxy' => 'tcp://'.$http.':'.$port, // This needs to be the server and the port of the NTLM Authentication Proxy Server.
        'request_fulluri' => True,
        ),
    );
$cxContext = stream_context_create($aContext);
$html = file_get_contents($yahoo, False, $cxContext ) or die("Pas de connexion à internet"); 


	foreach ( split('<h3>',$html) as $ligne ) 
	{ 
		if ( preg_match("!<a class=\"yschttl\" href=\"http://[^\"]+\"!",$ligne) ) 
		{ 
 			if ( preg_match("!<a class=\"yschttl\" href=\"$url\"!",$ligne) ) 
 				{return $position;
 				}
			$position++;	   
		}
	}
return -1;
}


?>
