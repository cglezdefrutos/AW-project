<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\event\registerEventForm;
use TheBalance\event\registerAnotherEventForm;

$titlePage = "Registrar eventos";
$mainContent = "";

if(!isset($_SESSION["user"])) 
{
    $mainContent = <<<EOS
        <h1>No es posible registrar un evento si no has iniciado sesión.</h1>
    EOS;
} 
else 
{
    $userDTO = json_decode($_SESSION["user"], true);
    $user_email = htmlspecialchars($userDTO["email"]);
    $user_type = htmlspecialchars($userDTO["usertype"]);

    // Comprobar si el usuario es proveedor o administrador
    if($user_type != 2 && $user_type != 0)
    {
        $mainContent = <<<EOS
            <h1>No es posible registrar un evento si no se es proveedor o administrador.</h1>
        EOS;
    }
    else
    {
        // Comprobar si el evento ya ha sido registrado
        if (isset($_GET["registered"]) && $_GET["registered"] === "true") 
        {
            $form = new registerAnotherEventForm();
            $htmlRegisterAnotherEventForm = $form->Manage();

            $mainContent = <<<EOS
                <h1>Evento registrado correctamente.</h1>
                $htmlRegisterAnotherEventForm
            EOS;
        }
        else
        {
            $form = new registerEventForm($user_email);
            $htmlRegisterEventForm = $form->Manage();

            $mainContent = <<<EOS
                <h1>Registre un evento</h1>
                $htmlRegisterEventForm
            EOS;
        }
    }
}

require_once __DIR__.'/includes/views/template/template.php';