<?php
    include __DIR__ . "/../views/common/baseForm.php";
    include __DIR__ . "/eventAppService.php";

    class registerEventForm extends baseForm
    {
        public function __construct()
        {
            parent::__construct('registerEventForm');
        }

        protected function CreateFields($initialData)
        {
            // Creamos el formulario de registro de eventos
            $html = <<<EOF
                <fieldset>
                    <legend>Registro de Evento</legend>

                    <label for="name">Nombre del evento:</label>
                    <input type="text" name="name" id="name" required placeholder="Ej: Torneo de Futbol" value="
            EOF;
            
            $html .= htmlspecialchars($initialData['name'] ?? '') . '"><br>';

            $html .= <<<EOF
                    <label for="description">Descripción:</label>
                    <input type="text" name="description" id="description" required placeholder="Escribe una breve descripcion" value="
            EOF;
            
            $html .= htmlspecialchars($initialData['description'] ?? '') . '"><br>';

            $html .= <<<EOF
                    <label for="date">Fecha:</label>
                    <input type="date" name="date" id="date" required value="
            EOF;
            
            $html .= htmlspecialchars($initialData['date'] ?? '') . '"><br>';

            $html .= <<<EOF
                    <label for="location">Localización:</label>
                    <input type="text" name="location" id="location" required placeholder="Escribe aquí tu localización" value="
            EOF;
            
            $html .= htmlspecialchars($initialData['location'] ?? '') . '"><br>';

            $html .= <<<EOF
                    <label for="price">Precio (€):</label>
                    <input type="number" name="price" id="price" step="0.01" min="0" required placeholder="Ej: 25.99" value="
            EOF;
            
            $html .= htmlspecialchars($initialData['price'] ?? '') . '"><br>';

            $html .= <<<EOF
                    <label for="capacity">Capacidad:</label>
                    <input type="number" name="capacity" id="capacity" required placeholder="Ej: 100" value="
            EOF;
            
            $html .= htmlspecialchars($initialData['capacity'] ?? '') . '"><br>';

            $html .= <<<EOF
                    <label for="category">Categoría:</label>
                    <input type="text" name="category" id="category" required placeholder="Ej: Deportes" value="
            EOF;
            
            $html .= htmlspecialchars($initialData['category'] ?? '') . '"><br>';

            $html .= <<<EOF
                    <button type="submit" name="botonRegisterEvent">Registrar Evento</button>
                </fieldset>
            EOF;

            return $html;
        }

        // Este método ahora procesa el registro de un nuevo evento,
        // mostrando un mensaje de confirmación en caso de éxito.
        protected function Process($data)
        {
            // Array para almacenar mensajes de error
            $result = array();

            // Filtrado y sanitización de los datos recibidos
            $eventName = trim($data['name'] ?? '');
            $eventName = filter_var($eventName, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $description = trim($data['description'] ?? '');
            $description = filter_var($description, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $date = trim($data['date'] ?? '');
            $location = trim($data['location'] ?? '');
            $location = filter_var($location, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $price = trim($data['price'] ?? '');
            $capacity = trim($data['capacity'] ?? '');
            $category = trim($data['category'] ?? '');
            $category = filter_var($category, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Validación de campos obligatorios
            if(empty($eventName)) {
                $result[] = 'El nombre del evento es obligatorio.';
            }
            if(empty($description)) {
                $result[] = 'La descripción es obligatoria.';
            }
            if(empty($date)) {
                $result[] = 'La fecha es obligatoria.';
            }
            if(empty($location)) {
                $result[] = 'La localización es obligatoria.';
            }
            if(empty($price)) {
                $result[] = 'El precio es obligatorio.';
            }
            if(empty($capacity)) {
                $result[] = 'La capacidad es obligatoria.';
            }
            if(empty($category)) {
                $result[] = 'La categoría es obligatoria.';
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

                // Obtenemos la instancia del servicio de eventos
                $eventAppService = eventAppService::GetSingleton();

                // Intentamos registrar el nuevo evento
                $registrationResult = $eventAppService->register($eventData);

                if(!$registrationResult)
                {
                    $result[] = 'Los datos introducidos no son válidos';
                }

                $_SESSION["register"] = true;

                $result = 'registerEvents.php';
            }

            return $result;
        }
    }
?>