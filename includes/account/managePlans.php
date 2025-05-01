<?php

require_once '../config.php';

use TheBalance\plan\planAppService;
use TheBalance\plan\planModal;
use TheBalance\application;
use TheBalance\utils\utilsFactory;
use TheBalance\plan\showManagePlanTable;

$app = application::getInstance();

if (!$app->isCurrentUserLogged()) {
    echo utilsFactory::createAlert("No has iniciado sesión. Por favor, inicia sesión para gestionar productos.", "danger");
} else {
    if (!$app->isCurrentUserTrainer() && !$app->isCurrentUserAdmin()) {
        echo utilsFactory::createAlert("No tienes permisos para gestionar productos. Solo los proveedores y administradores pueden hacerlo.", "danger");
    } else {
        // Obtener los productos según el tipo de usuario
        $planAppService = planAppService::GetSingleton();
        $planDTO = $planAppService->getTrainerPlans();

        // Generar la tabla de productos
        $columns = ['Imagen', 'Nombre', 'Descripción', 'Dificultad', 'Duracion', 'Precio', 'Fecha de creación', 'Acciones'];
        $planTable = new showManagePlanTable($planDTO, $columns);
        $html = $planTable->generateTable();

        echo <<<EOS
            <div class="container mt-4">
                <h2>Gestión de planes</h2>
                $html
            </div>
        EOS;

        // Agregar el modal al contenido
       echo planModal::generateEditModal();
    }
}