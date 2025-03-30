<?php

namespace TheBalance\event;

use TheBalance\views\common\baseForm;

/**
 * Formulario de actualización de eventos
 */
class updateEventForm extends baseForm
{
    /**
     * Datos iniciales del evento
     * 
     * @var eventDTO
     */
    private $eventInitialData;
    
    /**
     * Constructor
     * 
     * @param eventDTO $eventInitialData Datos iniciales del evento
     */
    public function __construct($eventInitialData)
    {
        parent:: __construct('updateEventForm');
        $this->eventInitialData = $eventInitialData;
    }

    /**
     * Crea los campos del formulario
     * 
     * @return string Campos del formulario
     */
    protected function CreateFields($initialData)
    {
        // Formatear la fecha y hora en el formato YYYY-MM-DDTHH:MM
        $formattedDateTime = date('Y-m-d\TH:i', strtotime($this->eventInitialData->getDate()));

        // Creamos el formulario de actualización de eventos
        $html = <<<EOF
            <fieldset class="border p-4 rounded">
                <legend class="w-auto">Actualizar Evento</legend>

                <input type="hidden" name="eventId" id="eventId" value="
        EOF;

        $html .= htmlspecialchars($this->eventInitialData->getId()) . '">';

        $html .= <<<EOF
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre del evento:</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Ej: Torneo de Futbol" value="
        EOF;

        $html .= htmlspecialchars($this->eventInitialData->getName()) . '" required>';
        
        $html .= <<<EOF
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descripción:</label>
                    <textarea name="description" id="description" class="form-control" placeholder="Escribe una breve descripción" rows="4" required>
        EOF;

        $html .= htmlspecialchars($this->eventInitialData->getDesc()) . '</textarea>';
        
        $html .= <<<EOF
                </div>

                <div class="mb-3">
                    <label for="date" class="form-label">Fecha:</label>
                    <input type="datetime-local" name="date" id="date" class="form-control" value="
        EOF;

        $html .= htmlspecialchars($formattedDateTime) . '" required>';
        
        $html .= <<<EOF
                </div>

                <div class="mb-3">
                    <label for="location" class="form-label">Lugar:</label>
                    <input type="text" name="location" id="location" class="form-control" placeholder="Ej: Estadio de futbol" value="
        EOF;

        $html .= htmlspecialchars($this->eventInitialData->getLocation()) . '" required>';
        
        $html .= <<<EOF
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Precio (€):</label>
                    <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" placeholder="Ej: 10" value="
        EOF;

        $html .= htmlspecialchars($this->eventInitialData->getPrice()) . '" required>';
        
        $html .= <<<EOF
                </div>

                <div class="mb-3">
                    <label for="capacity" class="form-label">Capacidad:</label>
                    <input type="number" name="capacity" id="capacity" class="form-control" min="0" placeholder="Ej: 100" value="
        EOF;

        $html .= htmlspecialchars($this->eventInitialData->getCapacity()) . '" required>';
        
        $html .= <<<EOF
                </div>

                <div class="mb-3">
                    <label for="category" class="form-label">Categoría:</label>
                    <input type="text" name="category" id="category" class="form-control" placeholder="Ej: Deportes" value="
        EOF;

        $html .= htmlspecialchars($this->eventInitialData->getCategory()) . '" required>';
        
        $html .= <<<EOF
                </div>

                <div class="mt-3">
                    <button type="submit" name="update_event" class="btn btn-primary w-100">Actualizar Evento</button>
                </div>
            </fieldset>
        EOF;
        
        return $html;
    }

    /**
     * Procesa los datos del formulario
     * 
     * @param array $data Datos del formulario
     * 
     * @return array|string Errores de procesamiento|Redirección
     */
    protected function Process($data)
    {
        // Array para almacenar mensajes de error
        $result = array();

        // Filtrado y sanitización de los datos recibidos
        $eventName = trim($data['name'] ?? '');
        $eventName = filter_var($eventName, FILTER_SANITIZE_STRING);
        if (empty($eventName) || strlen($eventName) > 50) {
            $result[] = 'El nombre del evento no puede estar vacío ni superar los 50 caracteres.';
        }

        $description = trim($data['description'] ?? '');
        $description = filter_var($description, FILTER_SANITIZE_STRING);
        if (empty($description) || strlen($description) > 1000) {
            $result[] = 'La descripción no puede estar vacía ni superar los 1000 caracteres.';
        }

        $date = trim($data['date'] ?? '');
        if (empty($date) || $date < date('Y-m-d')) {
            $result[] = 'La fecha no puede estar vacía ni ser anterior a la fecha actual.';
        }

        $location = trim($data['location'] ?? '');
        $location = filter_var($location, FILTER_SANITIZE_STRING);
        if (empty($location) || strlen($location) > 100) {
            $result[] = 'La localización no puede estar vacía ni superar los 100 caracteres.';
        }

        $price = trim($data['price'] ?? '');
        if (empty($price) || $price < 0) {
            $result[] = 'El precio no puede estar vacío ni ser negativo.';
        }

        $capacity = trim($data['capacity'] ?? '');
        if (empty($capacity) || $capacity < 0) {
            $result[] = 'La capacidad no puede estar vacía ni ser negativa.';
        }

        $category = trim($data['category'] ?? '');
        $category = filter_var($category, FILTER_SANITIZE_STRING);
        if (empty($category) || strlen($category) > 50) {
            $result[] = 'La categoría no puede estar vacía ni superar los 50 caracteres.';
        }

        if (count($result) === 0) {
            // Crear un DTO con los datos actualizados del formulario
            $updatedEventDTO = new eventDTO($this->eventInitialData->getId(), $eventName, $description, $date, $price, $location, $capacity, $category, $this->eventInitialData->getEmailProvider());
            
            // Actualizar el evento
            $eventAppService = eventAppService::GetSingleton();
            $updateResult = $eventAppService->updateEvent($updatedEventDTO);

            // Comprobamos
            if(!$updateResult)
            {
                $result[] = 'No se ha podido actualizar el evento.';
            }

            // Redirigir a manageEvents.php después de actualizar
            $result = 'manageEvents.php';
        } 

        return $result;
    }
}