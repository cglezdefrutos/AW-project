<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\product\registerProductForm;
use TheBalance\product\registerAnotherProductForm;
use TheBalance\application;
use TheBalance\utils\utilsFactory;

$titlePage = "Registrar productos";
$mainContent = "";

$app = application::getInstance();

if(!$app->isCurrentUserLogged())
{
    $mainContent .= utilsFactory::createAlert("No has iniciado sesión. Por favor, inicia sesión para registrar productos.", "danger");
} 
else 
{
    // Comprobar si el usuario es proveedor o administrador
    if(!$app->isCurrentUserProvider() && !$app->isCurrentUserAdmin())
    {
        $mainContent .= utilsFactory::createAlert("No tienes permisos para registrar productos. Solo los proveedores y administradores pueden hacerlo.", "danger");
    }
    else
    {
        // Comprobar si el producto ya ha sido registrado
        if (isset($_GET["registered"]) && $_GET["registered"] === "true") 
        {
            $form = new registerAnotherProductForm();
            $htmlRegisterAnotherProductForm = $form->Manage();

            // Alerta de éxito
            $alert = utilsFactory::createAlert("El producto ha sido registrado correctamente.", "success");

            $mainContent = <<<EOS
                $alert
                $htmlRegisterAnotherProductForm
            EOS;
        }
        else
        {
            $provider_id = $app->getCurrentUserId();
            $provider_email = $app->getCurrentUserEmail();
            $form = new registerProductForm($provider_id, $provider_email);
            $htmlRegisterProductForm = $form->Manage();

            $mainContent = <<<EOS
                <h1 class="mb-4">Registrar nuevo producto</h1>
                $htmlRegisterProductForm
            EOS;
        }
    }
}

require_once BASE_PATH.'/includes/views/template/template.php';