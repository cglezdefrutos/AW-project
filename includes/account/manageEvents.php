<?php

require_once '../config.php';

use TheBalance\event\eventAppService;
use TheBalance\event\eventDTO;
use TheBalance\event\manageEventTable;
use TheBalance\event\eventModal;
use TheBalance\application;
use TheBalance\utils\utilsFactory;

$titlePage = "Gestionar eventos";
$mainContent = "";

$app = application::getInstance();


if(!$app->isCurrentUserLogged())
{
    echo utilsFactory::createAlert("No tienes permisos para gestionar eventos. Por favor, inicia sesión.", "danger");
}
else
{
    // Comprobar si el usuario es proveedor o administrador
    if ( ! $app->isCurrentUserProvider() && ! $app->isCurrentUserAdmin() )
    { 
        echo utilsFactory::createAlert("No tienes permisos para gestionar eventos. Por favor, inicia sesión como proveedor o administrador.", "danger");
    }
    else
    {
        // Cogemos los eventos correspondientes al usuario
        $eventAppService = eventAppService::GetSingleton();
        $eventsDTO = $eventAppService->getEventsByUserType();

        // Definir las columnas que se mostrarán en la tabla
        $columns = ['Nombre', 'Descripción', 'Fecha', 'Lugar', 'Precio', 'Capacidad', 'Categoría', 'Acciones'];
        $eventTable = new manageEventTable($eventsDTO, $columns);
        $html = $eventTable->generateTable();

        echo <<<EOS
            <h1>Gestión de eventos</h1>
            $html
        EOS;

        // Agregar el modal al contenido
        echo eventModal::generateEditModal();
    }
}