<?php

require_once("includes/config.php");

use TheBalance\event\eventAppService;
use TheBalance\event\eventDTO;

$titlePage = "Actualizar evento";
$mainContent = "";

if (!isset($_SESSION["user"])) {
    $mainContent = <<<EOS
        <h1>No es posible actualizar eventos si no has iniciado sesión.</h1>
    EOS;
} else {
    $userDTO = json_decode($_SESSION["user"], true);
    $user_type = htmlspecialchars($userDTO["usertype"]);

    // Comprobar si el usuario es proveedor o administrador
    if ($user_type != 2 && $user_type != 0) {
        $mainContent = <<<EOS
        <h1>No es posible actualizar eventos si no se es proveedor.</h1>
    EOS;
    } else {
        // Obtenemos la instancia del servicio de eventos
        $eventAppService = eventAppService::GetSingleton();

        // Manejar la actualización del evento si se proporciona un eventId en la URL
        if (isset($_GET['eventId'])) {
            $eventId = $_GET['eventId'];
            $event = $eventAppService->getEventById($eventId);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Actualizar el evento con los datos del formulario
                $updatedEventDTO = new eventDTO($eventId, $_POST['name'], $_POST['description'], $_POST['date'], $_POST['price'], $_POST['location'], $_POST['capacity'], $_POST['category'], htmlspecialchars($userDTO["email"]));

                $eventAppService->updateEvent($updatedEventDTO);

                // Redirigir a manageEvents.php después de actualizar
                header("Location: manageEvents.php");
                exit();
            }

            // Mostrar el formulario con los datos del evento
            $mainContent = <<<EOS
                <h1>Actualizar evento</h1>
                <form method="post" action="updateEvents.php?eventId={$event->getId()}">
                    <label for="name">Nombre:</label>
                    <input type="text" id="name" name="name" value="{$event->getName()}" required>
                    
                    <label for="description">Descripción:</label>
                    <input type="text" id="description" name="description" value="{$event->getDesc()}" required>
                    
                    <label for="date">Fecha:</label>
                    <input type="date" id="date" name="date" value="{$event->getDate()}" required>
                    
                    <label for="location">Lugar:</label>
                    <input type="text" id="location" name="location" value="{$event->getLocation()}" required>
                    
                    <label for="price">Precio:</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" value="{$event->getPrice()}" required>
                    
                    <label for="capacity">Capacidad:</label>
                    <input type="number" id="capacity" name="capacity" value="{$event->getCapacity()}" required>
                    
                    <label for="category">Categoría:</label>
                    <input type="text" id="category" name="category" value="{$event->getCategory()}" required>
                    
                    <input type="submit" value="Actualizar evento">
                </form>
            EOS;
        } else {
            $mainContent = "<p>No se ha proporcionado un ID de evento válido.</p>";
        }
    }
}

require_once("includes/views/template/template.php");