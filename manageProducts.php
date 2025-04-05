<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\product\productAppService;
use TheBalance\product\productDTO;
use TheBalance\product\manageProductsTable;
use TheBalance\application;
use TheBalance\utils\utilsFactory;

$titlePage = "Gestionar productos";
$mainContent = "";

$app = application::getInstance();

if(!$app->isCurrentUserLogged()) {
    $mainContent .= utilsFactory::createAlert("No has iniciado sesión. Por favor, inicia sesión para gestionar productos.", "danger");
}
else {
    // Comprobar si el usuario es proveedor o administrador
    if (!$app->isCurrentUserProvider() && !$app->isCurrentUserAdmin()) { 
        $mainContent .= utilsFactory::createAlert("No tienes permisos para gestionar productos. Solo los proveedores y administradores pueden hacerlo.", "danger");
    }
    else {
        // Obtenemos la instancia del servicio de productos
        $productAppService = productAppService::GetSingleton();

        // Manejar las acciones sobre productos
        if (isset($_GET['action']) && isset($_GET['productId'])) {
            $productId = (int)$_GET['productId'];
            $action = $_GET['action'];
            
            switch ($action) {
                case 'activate':
                    $productAppService->activateProduct($productId);
                    $mainContent .= utilsFactory::createAlert("Producto activado correctamente.", "success");
                    break;
                case 'delete':
                    $productAppService->deleteProduct($productId);
                    $mainContent .= utilsFactory::createAlert("Producto eliminado correctamente.", "success");
                    break;
                default:
                    $mainContent .= utilsFactory::createAlert("Acción no válida.", "danger");
                    break;
            }
        }

        // Obtener los productos según el tipo de usuario
        $productDTO = $productAppService->getProductsByUserType();

        // Definir las columnas de la tabla
        $columns = ['Imagen', 'Nombre', 'Descripción', 'Precio', 'Stock', 'Categoría', 'Activo', 'Fecha de creación', 'Acciones'];

        // Generar la tabla de gestión de productos
        $productTable = new manageProductsTable($productDTO, $columns);
        $html = $productTable->generateTable();

        $mainContent .= <<<EOS
            <h1>Gestión de productos</h1>
            $html
        EOS;        
    }
}

require_once __DIR__.'/includes/views/template/template.php';