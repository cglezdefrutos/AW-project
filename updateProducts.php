<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\product\productAppService;
use TheBalance\product\updateProductForm;
use TheBalance\application;
use TheBalance\utils\utilsFactory;

$titlePage = "Actualizar producto";
$mainContent = "";

$app = application::getInstance();

if (!$app->isCurrentUserLogged())
{
    // Alerta de que no se puede actualizar el producto si no se ha iniciado sesión
    $mainContent .= utilsFactory::createAlert("No se puede actualizar el producto si no has iniciado sesión.", "danger");
} 
else 
{
    // Comprobar si el usuario es proveedor o administrador
    if ( ! $app->isCurrentUserProvider() && ! $app->isCurrentUserAdmin() )
    {
        $mainContent .= utilsFactory::createAlert("No tienes permisos para actualizar productos. Solo los proveedores y administradores pueden hacerlo.", "danger");
    } 
    else 
    {
        // Manejar la actualización del producto si se proporciona un productId en la URL o al enviar el formulario
        $productId = $_GET['productId'] ?? $_POST['productId'] ?? null;

        if ($productId) 
        {
            // Obtenemos la instancia del servicio de productos
            $productAppService = productAppService::GetSingleton();
            $product = $productAppService->getProductById($productId);

            // Verificar que el producto pertenece al proveedor (a menos que sea admin)
            if (!$app->isCurrentUserAdmin() && $product->getProviderEmail() !== $app->getCurrentUserEmail()) 
            {
                $mainContent .= utilsFactory::createAlert("No tienes permisos para actualizar este producto. Solo el proveedor que lo creó puede hacerlo.", "danger");
            } 
            else 
            {
                // Creamos el formulario
                $form = new updateProductForm($product);
                $htmlUpdateProductForm = $form->Manage();

                // Mostrar el formulario con los datos del producto
                $mainContent = <<<EOS
                    <h1>Actualizar producto</h1>
                    $htmlUpdateProductForm
                EOS;
            }
        } 
        else 
        {
            $mainContent .= utilsFactory::createAlert("No se ha proporcionado un ID de producto válido.", "danger");
        }
    }
}

require_once BASE_PATH.'/includes/views/template/template.php';