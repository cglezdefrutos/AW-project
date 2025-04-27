<?php

require_once '../config.php';
use TheBalance\event\eventAppService;
use TheBalance\utils\utilsFactory;
use TheBalance\event\eventDTO;
use TheBalance\event\eventCategoryDTO;
use TheBalance\application;

$action = $_POST['action'] ?? null;

if ($action) {
    $eventAppService = eventAppService::GetSingleton();

    switch ($action) {
        case 'getEvent':
            $eventId = $_POST['eventId'];
            $event = $eventAppService->getEventById($eventId);
            $formattedDateTime = date('Y-m-d\TH:i', strtotime($event->getDate()));

            // Si no es admin, tomamos las categorias disponibles
            $isAdmin = application::getInstance()->isCurrentUserAdmin();
            $categories = [];
            if (!$isAdmin) {
                $categories = $eventAppService->getEventCategories();
            }

            if ($event) {
                echo json_encode([
                    'success' => true,
                    'data' => [
                        'id' => $event->getId(),
                        'name' => $event->getName(),
                        'description' => $event->getDesc(),
                        'date' => $formattedDateTime,
                        'location' => $event->getLocation(),
                        'price' => $event->getPrice(),
                        'capacity' => $event->getCapacity(),
                        'category' => $event->getCategoryName(),
                        'categories' => $categories,
                        'isAdmin' => $isAdmin
                    ]
                ]);
            } else {
                $alert = utilsFactory::createAlert('Evento no encontrado.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
            }
            break;

        case 'updateEvent':

            // Validar y limpiar los datos recibidos
            $eventId = $_POST['eventId'] ?? null;
            $eventId = filter_var($eventId, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if (empty($eventId)) {
                $alert = utilsFactory::createAlert('ID de evento no válido.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }

            $eventName = trim($_POST['name'] ?? '');
            $eventName = filter_var($eventName, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if (empty($eventName) || strlen($eventName) > 50) {
                $alert = utilsFactory::createAlert('El nombre del evento no puede estar vacío ni superar los 50 caracteres.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }

            $eventDescription = trim($_POST['description'] ?? '');
            $eventDescription = filter_var($eventDescription, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $len = strlen($eventDescription);
            if (empty($eventDescription) || strlen($eventDescription) > 500) {
                $alert = utilsFactory::createAlert('La descripción del evento no puede estar vacía ni superar los 300 caracteres.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }

            $eventDate = $_POST['date'] ?? null;
            $eventDate = filter_var($eventDate, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if (empty($eventDate)) {
                $alert = utilsFactory::createAlert('La fecha del evento no puede estar vacía.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }

            $eventLocation = trim($_POST['location'] ?? '');
            $eventLocation = filter_var($eventLocation, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if (empty($eventLocation) || strlen($eventLocation) > 100) {
                $alert = utilsFactory::createAlert('El lugar del evento no puede estar vacío ni superar los 100 caracteres.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }

            $eventPrice = $_POST['price'] ?? null;
            $eventPrice = filter_var($eventPrice, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if (empty($eventPrice) || !is_numeric($eventPrice)) {
                $alert = utilsFactory::createAlert('El precio del evento no puede estar vacío y debe ser un número.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }

            $eventCapacity = $_POST['capacity'] ?? null;
            $eventCapacity = filter_var($eventCapacity, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if (empty($eventCapacity) || !is_numeric($eventCapacity)) {
                $alert = utilsFactory::createAlert('La capacidad del evento no puede estar vacía y debe ser un número.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }

            $eventCategoryName = trim($_POST['category'] ?? '');
            $eventCategoryName = filter_var($eventCategoryName, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if (empty($eventCategoryName) || strlen($eventCategoryName) > 50) {
                $alert = utilsFactory::createAlert('La categoría del evento no puede estar vacía ni superar los 50 caracteres.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }

            // Creamos el DTO
            $eventDTO = new eventDTO(
                $eventId, 
                $eventName, 
                $eventDescription, 
                $eventDate, 
                $eventPrice, 
                $eventLocation, 
                $eventCapacity, 
                new eventCategoryDTO(null, $eventCategoryName),
                null
            );

            $updated = $eventAppService->updateEvent($eventDTO);

            if ($updated) {
                $alert = utilsFactory::createAlert('Evento actualizado correctamente.', 'success');
                echo json_encode(['success' => true, 'alert' => $alert]);
            } else {
                $alert = utilsFactory::createAlert('Error al actualizar el evento.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
            }
            break;

        case 'deleteEvent':
            $eventId = $_POST['eventId'];
            $deleted = $eventAppService->deleteEvent($eventId);

            if ($deleted) {
                $alert = utilsFactory::createAlert('Evento eliminado correctamente.', 'success');
                echo json_encode(['success' => true, 'alert' => $alert]);
            } else {
                $alert = utilsFactory::createAlert('Error al eliminar el evento.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
            }
            break;

        default:
            $alert = utilsFactory::createAlert('Acción no válida.', 'danger');
            echo json_encode(['success' => false, 'alert' => $alert]);
            break;
    }
}