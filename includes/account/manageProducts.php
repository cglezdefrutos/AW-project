<?php

require_once '../config.php';

use TheBalance\product\productAppService;
use TheBalance\product\manageProductsTable;
use TheBalance\product\productModal;
use TheBalance\application;
use TheBalance\utils\utilsFactory;

$app = application::getInstance();

if (!$app->isCurrentUserLogged()) {
    echo utilsFactory::createAlert("No has iniciado sesión. Por favor, inicia sesión para gestionar productos.", "danger");
} else {
    if (!$app->isCurrentUserProvider() && !$app->isCurrentUserAdmin()) {
        echo utilsFactory::createAlert("No tienes permisos para gestionar productos. Solo los proveedores y administradores pueden hacerlo.", "danger");
    } else {
        // Obtener los productos según el tipo de usuario
        $productAppService = productAppService::GetSingleton();
        $productDTO = $productAppService->getProductsByUserType();

        // Generar la tabla de productos
        $columns = ['Imagen', 'Nombre', 'Descripción', 'Precio', 'Stock', 'Categoría', 'Activo', 'Fecha de creación', 'Acciones'];
        $productTable = new manageProductsTable($productDTO, $columns);
        $html = $productTable->generateTable();

        echo <<<EOS
            <h2>Gestión de productos</h2>
            $html
        EOS;

        // Agregar el modal al contenido
        echo productModal::generateEditModal();
    }
}