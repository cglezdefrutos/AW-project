<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\product\productAppService;
use TheBalance\product\updateProductForm;
use TheBalance\application;

$titlePage = "Actualizar producto";
$mainContent = "";

$app = application::getInstance();

if (!$app->isCurrentUserLogged())
{
    $mainContent = <<<EOS
        <h1>No es posible actualizar productos si no has iniciado sesión.</h1>
    EOS;
} 
else 
{
    // Comprobar si el usuario es proveedor o administrador
    if ( ! $app->isCurrentUserProvider() && ! $app->isCurrentUserAdmin() )
    {
        $mainContent = <<<EOS
            <h1>No es posible actualizar productos si no se es proveedor o administrador.</h1>
        EOS;
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
            if (!$app->isCurrentUserAdmin() && $product->getProviderEmail() !== $app->getCurrentUserEmail()) {
                $mainContent = <<<EOS
                    <h1>No tienes permisos para actualizar este producto.</h1>
                EOS;
            } else {
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
            $mainContent = "<p>No se ha proporcionado un ID de producto válido.</p>";
        }
    }
}

require_once __DIR__.'/includes/views/template/template.php';