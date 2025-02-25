<?php
    require_once("includes/config.php");

    require_once("includes/login/loginForm.php");

    $titlePage = 'Acceso al sistema';

    $form = new loginForm(); 

    $htmlFormLogin = $form->Manage();

    $mainContent = <<<EOS
        <h1>Inicio de Sesión</h1>
        $htmlFormLogin
    EOS;

    require("includes/views/template/template.php");
?>