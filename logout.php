<?php

require_once __DIR__.'/includes/config.php';

unset($_SESSION);

session_destroy(); 

$titlePage = 'Salir del sistema';

$mainContent=<<<EOS
	<h1>Hasta pronto!</h1>
EOS;

require_once BASE_PATH.'/includes/views/template/template.php';