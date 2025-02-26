<?php

require_once("includes/config.php");

unset($_SESSION);

session_destroy(); 

$tituloPagina = 'Salir del sistema';

$contenidoPrincipal=<<<EOS
	<h1>Hasta pronto!</h1>
EOS;

require_once("includes/views/template/template.php");
?>