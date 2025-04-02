<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\product\productAppService;
use TheBalance\product\productDTO;
use TheBalance\product\manageProductsTable;
use TheBalance\application;

$titlePage = "Gestionar productos";
$mainContent = "";

$app = application::getInstance();

if(!$app->isCurrentUserLogged()) {
    $mainContent = <<<EOS
        <h1>No es posible gestionar productos si no has iniciado sesión.</h1>
    EOS;
}
else {
    // Comprobar si el usuario es proveedor o administrador
    if (!$app->isCurrentUserProvider() && !$app->isCurrentUserAdmin()) { 
        $mainContent = <<<EOS
            <h1>No es posible gestionar productos si no se es proveedor o administrador.</h1>
        EOS;
    }
    else {
        // Obtenemos la instancia del servicio de productos
        $productAppService = productAppService::GetSingleton();

        // Manejar las acciones sobre productos
        if (isset($_GET['action']) && isset($_GET['productId'])) {
            $productId = (int)$_GET['productId'];
            $action = $_GET['action'];
            $successMessage = '';
            $errorMessage = '';

            try {
                switch ($action) {
                    case 'activate':
                        $productAppService->activateProduct($productId);
                        $successMessage = 'Producto activado correctamente.';
                        break;
                        
                        
                    case 'delete':
                        $productAppService->deleteProduct($productId);
                        $successMessage = 'Producto eliminado correctamente.';
                        break;
                        
                    default:
                        $errorMessage = 'Acción no válida.';
                        break;
                }
            } catch (Exception $e) {
                $errorMessage = 'Error al procesar la acción: ' . $e->getMessage();
            }

            // Mostrar mensajes de feedback
            if (!empty($successMessage)) {
                $mainContent .= <<<EOS
                    <div class="alert alert-success">
                        $successMessage
                    </div>
                EOS;
            }
            
            if (!empty($errorMessage)) {
                $mainContent .= <<<EOS
                    <div class="alert alert-danger">
                        $errorMessage
                    </div>
                EOS;
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