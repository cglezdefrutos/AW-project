<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\product\registerProductForm;
use TheBalance\product\registerAnotherProductForm;
use TheBalance\application;

$titlePage = "Registrar productos";
$mainContent = "";

$app = application::getInstance();

if(!$app->isCurrentUserLogged())
{
    $mainContent = <<<EOS
        <h1>No es posible registrar un producto si no has iniciado sesión.</h1>
    EOS;
} 
else 
{
    // Comprobar si el usuario es proveedor o administrador
    if(!$app->isCurrentUserProvider() && !$app->isCurrentUserAdmin())
    {
        $mainContent = <<<EOS
            <h1>No es posible registrar un producto si no se es proveedor o administrador.</h1>
        EOS;
    }
    else
    {
        // Comprobar si el producto ya ha sido registrado
        if (isset($_GET["registered"]) && $_GET["registered"] === "true") 
        {
            $form = new registerAnotherProductForm();
            $htmlRegisterAnotherProductForm = $form->Manage();

            $mainContent = <<<EOS
                <div class="alert alert-success">
                    <h1>Producto registrado correctamente.</h1>
                </div>
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

require_once __DIR__.'/includes/views/template/template.php';