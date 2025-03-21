<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\event\eventAppService;
use TheBalance\event\eventDTO;
use TheBalance\event\manageEventTable;

$titlePage = "Gestionar eventos";
$mainContent = "";

if(!isset($_SESSION["user"]))
{
    $mainContent = <<<EOS
        <h1>No es posible gestionar eventos si no has iniciado sesión.</h1>
    EOS;
}
else
{
    $userDTO = json_decode($_SESSION["user"], true);
    $user_type = htmlspecialchars($userDTO["usertype"]);

    // Comprobar si el usuario es proveedor o administrador
    if ( $user_type != 2 &&  $user_type != 0)
    { 
        $mainContent = <<<EOS
        <h1>No es posible gestionar eventos si no se es proveedor o administrador.</h1>
    EOS;
    } 
    else 
    {
        // Obtenemos la instancia del servicio de eventos
        $eventAppService = eventAppService::GetSingleton();

        // Manejar la eliminación del evento si se proporciona un eventId en la URL
        if (isset($_GET['eventId'])) {
            $eventId = $_GET['eventId'];
            $eventAppService->deleteEvent($eventId);
            $mainContent .= <<<EOS
                <div class="alert-success">
                    Evento eliminado correctamente.
                </div>
            EOS;
        }

        // Cogemos los eventos correspondientes al usuario
        $eventsDTO = $eventAppService->getEventsByUserType($user_type);

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