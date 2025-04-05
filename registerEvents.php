<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\event\registerEventForm;
use TheBalance\event\registerAnotherEventForm;
use TheBalance\application;
use TheBalance\utils\utilsFactory;

$titlePage = "Registrar eventos";
$mainContent = "";

$app = application::getInstance();

if(!$app->isCurrentUserLogged())
{
    $mainContent = utilsFactory::createAlert("No se puede registrar un evento si no se ha iniciado sesión.", "danger");
} 
else 
{
    // Comprobar si el usuario es proveedor o administrador
    if(!$app->isCurrentUserProvider() && !$app->isCurrentUserAdmin())
    {
        $mainContent .= utilsFactory::createAlert("No se puede registrar un evento si no es proveedor o administrador.", "danger");
    }
    else
    {
        // Comprobar si el evento ya ha sido registrado
        if (isset($_GET["registered"]) && $_GET["registered"] === "true") 
        {
            $form = new registerAnotherEventForm();
            $htmlRegisterAnotherEventForm = $form->Manage();

            // Alerta de éxito
            $mainContent .= utilsFactory::createAlert("El evento ha sido registrado correctamente.", "success");

            $mainContent .= <<<EOS
                $htmlRegisterAnotherEventForm
            EOS;
        }
        else
        {
            $user_email = $app->getCurrentUserEmail();
            $form = new registerEventForm($user_email);
            $htmlRegisterEventForm = $form->Manage();

            $mainContent = <<<EOS
                <h1>Registre un evento</h1>
                $htmlRegisterEventForm
            EOS;
        }
    }
}

require_once BASE_PATH.'/includes/views/template/template.php';