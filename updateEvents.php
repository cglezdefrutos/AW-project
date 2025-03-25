<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\event\eventAppService;
use TheBalance\event\updateEventForm;
use TheBalance\application;

$titlePage = "Actualizar evento";
$mainContent = "";

$app = application::getInstance();

if (!$app->isCurrentUserLogged())
{
    $mainContent = <<<EOS
        <h1>No es posible actualizar eventos si no has iniciado sesión.</h1>
    EOS;
} 
else 
{
    // Comprobar si el usuario es proveedor o administrador
    if ( ! $app->isCurrentUserProvider() && ! $app->isCurrentUserAdmin() )
    {
        $mainContent = <<<EOS
            <h1>No es posible actualizar eventos si no se es proveedor.</h1>
        EOS;
    } 
    else 
    {
        // Manejar la actualización del evento si se proporciona un eventId en la URL o al enviar el formulario
        $eventId = $_GET['eventId'] ?? $_POST['eventId'] ?? null;

        if ($eventId) 
        {
            // Obtenemos la instancia del servicio de eventos
            $eventAppService = eventAppService::GetSingleton();
            $event = $eventAppService->getEventById($eventId);

            // Creamos el formulario
            $form = new updateEventForm($event);
            $htmlUpdateEventForm = $form->Manage();

            // Mostrar el formulario con los datos del evento
            $mainContent = <<<EOS
                <h1>Actualice el evento</h1>
                $htmlUpdateEventForm
            EOS;
        } 
        else 
        {
            $mainContent = "<p>No se ha proporcionado un ID de evento válido.</p>";
        }
    }
}

require_once __DIR__.'/includes/views/template/template.php';