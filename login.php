<?php

require_once("includes/config.php");

use TheBalance\user\loginForm;

$titlePage = 'Acceso al sistema';

$form = new loginForm(); 

$htmlFormLogin = $form->Manage();

$mainContent = <<<EOS
    <h1>Inicio de Sesión</h1>
    $htmlFormLogin
EOS;

require_once("includes/views/template/template.php");