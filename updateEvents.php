<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\event\eventAppService;
use TheBalance\event\updateEventForm;
use TheBalance\application;
use TheBalance\utils\utilsFactory;

$titlePage = "Actualizar evento";
$mainContent = "";

$app = application::getInstance();

if (!$app->isCurrentUserLogged())
{
    // Alerta de error si el usuario no ha iniciado sesión
    $mainContent .= utilsFactory::createAlert("No has iniciado sesión. Por favor, inicia sesión para actualizar eventos.", "danger");
} 
else 
{
    // Comprobar si el usuario es proveedor o administrador
    if ( ! $app->isCurrentUserProvider() && ! $app->isCurrentUserAdmin() )
    {
        $mainContent .= utilsFactory::createAlert("No tienes permisos para actualizar eventos. Solo los proveedores y administradores pueden hacerlo.", "danger");
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
            $mainContent .= utilsFactory::createAlert("No se ha proporcionado un ID de evento válido.", "danger");
        }
    }
}

require_once __DIR__.'/includes/views/template/template.php';