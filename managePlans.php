<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\plan\planAppService;
use TheBalance\plan\planDTO;
use TheBalance\plan\showManagePlanTable;
use TheBalance\application;
use TheBalance\utils\utilsFactory;

$titlePage = "Gestionar planes";
$mainContent = "";

$app = application::getInstance();

if(!$app->isCurrentUserLogged()) {
    $mainContent .= utilsFactory::createAlert("No has iniciado sesión. Por favor, inicia sesión para gestionar productos.", "danger");
}
else {
    // Comprobar si el usuario es proveedor o administrador
    if (!$app->isCurrentUserTrainer() && !$app->isCurrentUserAdmin()) { 
        $mainContent .= utilsFactory::createAlert("No tienes permisos para gestionar planes. Solo los entrenadores y administradores pueden hacerlo.", "danger");
    }
    else {
        // Obtenemos la instancia del servicio de planes
        $planAppService = planAppService::GetSingleton();

        // Manejar las acciones sobre productos
        if (isset($_GET['action']) && isset($_GET['planId'])) {
            $planId = (int)$_GET['planId'];
            $action = $_GET['action'];
            
            switch ($action) {
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

require_once BASE_PATH.'/includes/views/template/template.php';