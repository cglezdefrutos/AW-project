<?php
    require_once("includes/config.php");

    require_once("includes/login/registerForm.php");

    $titlePage = 'Registro en el sistema';

    $form = new registerForm(); 

    $htmlFormRegistro = $form->Manage();

    $mainContent = <<<EOS
        <h1>Registro de usuario</h1>
        $htmlFormRegistro
    EOS;

    require("includes/views/template/template.php");
?>