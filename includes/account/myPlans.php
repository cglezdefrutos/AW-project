<?php

require_once '../config.php';

use TheBalance\application;
use TheBalance\plan\planDTO;
use TheBalance\plan\planAppService;
use TheBalance\plan\showPlanTable;
use TheBalance\utils\utilsFactory;
use TheBalance\plan\planModal;


$titlePage = "Mis Planes";
$mainContent = "";

$app = application::getInstance();

if (!$app->isCurrentUserLogged()) 
{
    echo utilsFactory::createAlert("No has iniciado sesión. Por favor, inicia sesión para ver tus pedidos.", "danger");
} 
else if (!$app->isCurrentUserClient()) 
{
    echo utilsFactory::createAlert("No tienes permisos para ver planes. Solo los clientes pueden hacerlo.", "danger");
} 
else 
{
    // Obtenemos la instancia del servicio de pedidos
    $planAppService = planAppService::GetSingleton();
    $plansData = $planAppService->getClientPlans();

    // Convertir los datos en objetos DTO
    $plans = array_map(function($planData) {
        return new planDTO(
            $planData->getId(),
            $planData->getTrainerId(),
            $planData->getName(),  
            $planData->getDescription(),
            $planData->getDifficulty(),
            $planData->getDuration(),
            $planData->getPrice(),
            $planData->getImageGuid(),
            $planData->getPdfPath(),
            $planData->getCreatedAt(),
            $planData->getIsActive()
        );
    }, $plansData);

    // Definir las columnas de la tabla
    $columns = ['Nombre', 'Descripcion', 'Dificultad', 'Duracion', 'Precio total', 'Fecha', 'Acciones'];
    $plansTable = new showPlanTable($plans, $columns);
    $html = $plansTable->generateTable();

    echo <<<EOS
        <h2>Mis Planes</h2>
        $html
    EOS;

    // Agregar el modal al contenido
    echo planModal::generateDetailsModal(); 
}