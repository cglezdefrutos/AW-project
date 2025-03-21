<?php
require_once __DIR__.'/includes/config.php';

use TheBalance\event\searchEventForm;
use TheBalance\event\searchEventTable;
use TheBalance\event\eventDTO;

$titlePage = "Buscar eventos";
$mainContent = "";

if (!isset($_GET["search"]) || $_GET["search"] != "true") 
{
    // Limpiamos resultados anteriores
    unset($_SESSION["foundedEventsDTO"]);

    $form = new searchEventForm();

    $htmlSearchEventForm = $form->Manage();

    $mainContent = <<<EOS
        <h1>Eventos disponibles</h1>
        $htmlSearchEventForm
    EOS;
} 
else 
{
    // Verificar el contenido de $_SESSION["foundedEventsDTO"]
    if (!isset($_SESSION["foundedEventsDTO"])) {
        echo "No se encontraron eventos.";
        exit();
    }

    // Decodificar el JSON almacenado en la sesión
    $foundedEventsDTO = json_decode($_SESSION["foundedEventsDTO"], true);

    // Verificar que la decodificación fue exitosa y que es un array
    if (!is_array($foundedEventsDTO)) {
        echo "Error al decodificar los datos de eventos.";
        exit();
    }
    
    // Convertir los datos decodificados en objetos eventDTO
    $events = array_map(function($eventData) {
        return new eventDTO(
            $eventData['id'],
            $eventData['name'],
            $eventData['desc'],
            $eventData['date'],
            $eventData['price'],
            $eventData['location'],
            $eventData['capacity'],
            $eventData['category'],
            $eventData['email_provider']
        );
    }, $foundedEventsDTO);

    // Definir las columnas que se mostrarán en la tabla
    $columns = ['Nombre', 'Descripción', 'Fecha', 'Ubicación', 'Precio', 'Capacidad', 'Categoría', 'Proveedor', ''];

    // Generar la tabla de eventos
    $eventTable = new searchEventTable($events, $columns);
    $html = $eventTable->generateTable();

    $mainContent = <<<EOS
        <h1>Eventos disponibles</h1>
        $html
    EOS;
}

require_once __DIR__.'/includes/views/template/template.php';