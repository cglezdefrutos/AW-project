<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\product\productAppService;
use TheBalance\product\productDTO;
use TheBalance\product\manageProductsTable;
use TheBalance\application;

$titlePage = "Gestionar productos";
$mainContent = "";

$app = application::getInstance();


if(!$app->isCurrentUserLogged())
{
    $mainContent = <<<EOS
        <h1>No es posible gestionar productos si no has iniciado sesión.</h1>
    EOS;
}
else
{
    // Comprobar si el usuario es proveedor o administrador
    if ( ! $app->isCurrentUserProvider() && ! $app->isCurrentUserAdmin() )
    { 
        $mainContent = <<<EOS
            <h1>No es posible gestionar productos si no se es proveedor o administrador.</h1>
        EOS;
    }
    else
    {
        // Obtenemos la instancia del servicio de eventos
        $productAppService = productAppService::GetSingleton();

        // Manejar la eliminación del evento si se proporciona un eventId en la URL
        if (isset($_GET['productId'])) {
            $productId = $_GET['productId'];
            $productAppService->deleteProduct($productId);
            $mainContent .= <<<EOS
                <div class="alert-success">
                    Producto eliminado correctamente.
                </div>
            EOS;
        }

        // Cogemos los eventos correspondientes al usuario
        $productDTO = $productAppService->getProductsByUserType();


        //CAMBIAR LAS COLUMNAS
        // Definir las columnas que se mostrarán en la tabla
        $columns = ['Nombre', 'Descripción', 'Precio', 'Stock', 'Categoría', 'Acciones', 'Activo'];

        // Generar la tabla de gestión de eventos
        $productable = new manageProductsTable($productDTO, $columns);
        $html = $productable->generateTable();

        $mainContent .= <<<EOS
            <h1>Gestión de productos</h1>
            $html
        EOS;        
    }
}

require_once __DIR__.'/includes/views/template/template.php';