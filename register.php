<?php

require_once("includes/config.php");

use TheBalance\user\registerForm;

$titlePage = 'Registro en el sistema';

$form = new registerForm(); 

$htmlFormRegistro = $form->Manage();

$mainContent = <<<EOS
    <h1>Registro de usuario</h1>
    $htmlFormRegistro
EOS;

require_once("includes/views/template/template.php");