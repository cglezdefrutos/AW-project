<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\login\loginForm;

$titlePage = 'Acceso al sistema';

$form = new loginForm(); 

$htmlFormLogin = $form->Manage();

$mainContent = <<<EOS
    <h1>Inicio de Sesión</h1>
    $htmlFormLogin
EOS;

require_once BASE_PATH.'/includes/views/template/template.php';