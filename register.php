<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\login\registerForm;

$titlePage = 'Registro en el sistema';

$form = new registerForm(); 

$htmlFormRegistro = $form->Manage();

$mainContent = <<<EOS
    <h1>Registro de usuario</h1>
    $htmlFormRegistro
EOS;

require_once __DIR__.'/includes/views/template/template.php';