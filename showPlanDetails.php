<?php

require_once __DIR__ . '/includes/config.php';

use TheBalance\application;
use TheBalance\plan\planDTO;
use TheBalance\plan\planAppService;
use TheBalance\plan\showPlanDetailTable;
use TheBalance\utils\utilsFactory;

$titlePage = "Detalles del Plan";
$mainContent = "";

$app = application::getInstance();

if (!$app->isCurrentUserLogged()) 
{
    $mainContent .= utilsFactory::createAlert("No has iniciado sesión. Por favor, inicia sesión para ver los detalles del plan.", "danger");
} 
else if (!$app->isCurrentUserClient() && !$app->isCurrentUserAdmin()) 
{
    $mainContent .= utilsFactory::createAlert("No tienes permisos para ver los detalles del plan. Solo los clientes y administradores pueden hacerlo.", "danger");
} 
else 
{
    $planId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    $planAppService = planAppService::GetSingleton();
    $plan = $planAppService->getPlanById($planId);

    if ($planId <= 0 || !$plan) {
        $mainContent .= utilsFactory::createAlert("No se ha proporcionado un ID de plan válido o el plan no existe.", "danger");
    } 
    //TODO
    // else if ($app->isCurrentUserClient() && ($plan->getTrainerId() != $app->getCurrentUserId())) 
    // {
    //     $mainContent .= utilsFactory::createAlert("No tienes permisos para ver este plan. Solo puedes ver los planes que hayas adquirido o te pertenecen.", "danger");
    // }
    else 
    {
        // Envolvemos en array por compatibilidad con showPlanDetailTable
        $details = [$plan];

        $columns = ['Nombre', 'Dificultad', 'Duración', 'Fecha de Creación' , 'Imagen', 'Información'];

        if (empty($details)) 
        {
            $mainContent .= utilsFactory::createAlert("No hay detalles disponibles para este pedido.", "info");
        }
        else{

            $detailsTable = new showPlanDetailTable($details, $columns);
            $html = $detailsTable->generateTable();
    
            $mainContent = <<<EOS
                <h1>Detalles del Plan</h1>
                $html
                <button onclick="window.history.back()" class="btn btn-secondary">
                    ← Volver
                </button>
            EOS;
        }

    }
}

require_once BASE_PATH . '/includes/views/template/template.php';
