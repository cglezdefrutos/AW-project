<?php

namespace TheBalance\event;

use TheBalance\views\common\baseForm;

/**
 * Formulario de registro de eventos
 */
class registerEventForm extends baseForm
{
    /**
     * Email del usuario
     * 
     * @var string
     */
    private $user_email;

    /**
     * Constructor
     * 
     * @param string $user_email Email del usuario
     */
    public function __construct($user_email)
    {
        parent::__construct('registerEventForm');
        $this->user_email = $user_email;
    }

    /**
     * Crea los campos del formulario
     * 
     * @param array $initialData Datos iniciales del formulario
     * 
     * @return string Campos del formulario
     */
    protected function CreateFields($initialData)
    {
        // Creamos el formulario de registro de eventos
        $html = <<<EOF
            <fieldset class="border p-4 rounded">
                <legend class="w-auto">Registro de Evento</legend>

                <!-- Campo Nombre del evento -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre del evento:</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Ej: Torneo de Futbol" value="
        EOF;

        $html .= htmlspecialchars($initialData['name'] ?? '') . '" required>';
        
        $html .= <<<EOF
                </div>

                <!-- Campo Descripción -->
                <div class="mb-3">
                    <label for="description" class="form-label">Descripción:</label>
                    <textarea name="description" id="description" class="form-control" placeholder="Escribe una breve descripción" rows="4" required>
        EOF;

        $html .= htmlspecialchars($initialData['description'] ?? '') . '</textarea>';
        
        $html .= <<<EOF
                </div>

                <!-- Campo Fecha y hora -->
                <div class="mb-3">
                    <label for="date" class="form-label">Fecha y hora:</label>
                    <input type="datetime-local" name="date" id="date" class="form-control" value="
        EOF;

        $html .= htmlspecialchars($initialData['date'] ?? '') . '" required>';
        
        $html .= <<<EOF
                </div>

                <!-- Campo Localización -->
                <div class="mb-3">
                    <label for="location" class="form-label">Localización:</label>
                    <input type="text" name="location" id="location" class="form-control" placeholder="Escribe aquí tu localización" value="
        EOF;

        $html .= htmlspecialchars($initialData['location'] ?? '') . '" required>';
        
        $html .= <<<EOF
                </div>

                <!-- Campo Precio -->
                <div class="mb-3">
                    <label for="price" class="form-label">Precio (€):</label>
                    <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" placeholder="Ej: 25.99" value="
        EOF;

        $html .= htmlspecialchars($initialData['price'] ?? '') . '" required>';
        
        $html .= <<<EOF
                </div>

                <!-- Campo Capacidad -->
                <div class="mb-3">
                    <label for="capacity" class="form-label">Capacidad:</label>
                    <input type="number" name="capacity" id="capacity" class="form-control" min="0" placeholder="Ej: 100" value="
        EOF;

        $html .= htmlspecialchars($initialData['capacity'] ?? '') . '" required>';
        
        $html .= <<<EOF
                </div>

                <!-- Campo Categoría -->
                <div class="mb-3">
                    <label for="category" class="form-label">Categoría:</label>
                    <input type="text" name="category" id="category" class="form-control" placeholder="Ej: Deportes" value="
        EOF;

        $html .= htmlspecialchars($initialData['category'] ?? '') . '" required>';
        
        $html .= <<<EOF
                </div>

                <!-- Botón de registro -->
                <div class="mt-3">
                    <button type="submit" name="botonRegisterEvent" class="btn btn-primary w-100">Registrar Evento</button>
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
        $eventName = filter_var($eventName, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($eventName) || strlen($eventName) > 50) {
            $result[] = 'El nombre del evento es obligatorio y no debe exceder los 50 caracteres.';
        }

        $description = trim($data['description'] ?? '');
        $description = filter_var($description, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($description) || strlen($description) > 1000) {
            $result[] = 'La descripción es obligatoria y no debe exceder los 1000 caracteres.';
        }

        $date = trim($data['date'] ?? '');

        $location = trim($data['location'] ?? '');
        $location = filter_var($location, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($location) || strlen($location) > 100) {
            $result[] = 'La localización es obligatoria y no debe exceder los 100 caracteres.';
        }

        $price = trim($data['price'] ?? '');
        if (!is_numeric($price) || $price < 0) {
            $result[] = 'El precio debe ser un número positivo.';
        }

        $capacity = trim($data['capacity'] ?? '');
        if (!is_numeric($capacity) || $capacity < 0) {
            $result[] = 'La capacidad debe ser un número positivo.';
        }

        $category = trim($data['category'] ?? '');
        $category = filter_var($category, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($category) || strlen($category) > 50) {
            $result[] = 'La categoría es obligatoria y no debe exceder los 50 caracteres.';
        }

        if(count($result) === 0)
        {
            // Creamos un array con los datos del nuevo evento
            $eventData = array();
            $eventData['name']       = $eventName;
            $eventData['description']= $description;
            $eventData['date']       = $date;
            $eventData['location']   = $location;
            $eventData['price']      = $price;
            $eventData['capacity']   = $capacity;
            $eventData['category']   = $category;
            $eventData['provider']   = $this->user_email;

            // Obtenemos la instancia del servicio de eventos
            $eventAppService = eventAppService::GetSingleton();

            // Intentamos registrar el nuevo evento
            $registrationResult = $eventAppService->register($eventData);

            if(!$registrationResult)
            {
                $result[] = 'Los datos introducidos no son válidos';
            }

            $result = 'registerEvents.php?registered=true';
        }

        return $result;
    }
}