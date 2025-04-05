<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\event\eventAppService;
use TheBalance\event\eventDTO;
use TheBalance\event\manageEventTable;
use TheBalance\application;
use TheBalance\utils\utilsFactory;

$titlePage = "Gestionar eventos";
$mainContent = "";

$app = application::getInstance();


if(!$app->isCurrentUserLogged())
{
    $mainContent .= utilsFactory::createAlert("No tienes permisos para gestionar eventos. Por favor, inicia sesión.", "danger");
}
else
{
    // Comprobar si el usuario es proveedor o administrador
    if ( ! $app->isCurrentUserProvider() && ! $app->isCurrentUserAdmin() )
    { 
        $mainContent .= utilsFactory::createAlert("No tienes permisos para gestionar eventos. Por favor, inicia sesión como proveedor o administrador.", "danger");
    }
    else
    {
        // Obtenemos la instancia del servicio de eventos
        $eventAppService = eventAppService::GetSingleton();

        // Manejar la eliminación del evento si se proporciona un eventId en la URL
        if (isset($_GET['eventId'])) {
            $eventId = $_GET['eventId'];
            $eventAppService->deleteEvent($eventId);
            $mainContent .= utilsFactory::createAlert("Evento eliminado correctamente.", "success");
        }

        // Cogemos los eventos correspondientes al usuario
        $eventsDTO = $eventAppService->getEventsByUserType();

        // Definir las columnas que se mostrarán en la tabla
        $columns = ['Nombre', 'Descripción', 'Fecha', 'Lugar', 'Precio', 'Capacidad', 'Categoría', 'Acciones'];

        // Generar la tabla de gestión de eventos
        $eventTable = new manageEventTable($eventsDTO, $columns);
        $html = $eventTable->generateTable();

        $mainContent .= <<<EOS
            <h1>Gestión de eventos</h1>
            $html
        EOS;        
    }
}

require_once __DIR__.'/includes/views/template/template.php';