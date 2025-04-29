<?php

require_once '../config.php';

use TheBalance\application;
use TheBalance\event\myEventsTable;
use TheBalance\event\eventAppService;
use TheBalance\utils\utilsFactory;

$app = application::getInstance();

if (!$app->isCurrentUserClient()) {
    echo utilsFactory::createAlert("No tienes permisos para acceder a esta sección.", "danger");
}
else
{
    $eventAppService = eventAppService::GetSingleton();

    $userId = $app->getCurrentUserId();

    $eventsDTO = $eventAppService->getJoinedEvents($userId);

    $columns = ['Nombre del Evento', 'Descripcion', 'Categoria', 'Precio', 'Ubicación', 'Fecha'];
    $eventsTable = new myEventsTable($eventsDTO, $columns);
    $html = $eventsTable->generateTable();

    echo <<<EOS
        <div class="container mt-4">
            <h2>Mis Eventos</h2>
            $html
        </div>
    EOS;
}
