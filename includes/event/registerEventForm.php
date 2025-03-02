<?php
    include __DIR__ . "/../views/common/baseForm.php";
    include __DIR__ . "/eventAppService.php";

    class registerEventForm extends baseForm
    {
        public function __construct()
        {
            parent:: __construct('registerEventForm');
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
            
            $html .= htmlspecialchars($initialData['name'] ?? '') . '">';

            $html .= <<<EOF
                    <label for="date">Fecha:</label>
                    <input type="date" name="date" id="date" required value="
            EOF;
            
            $html .= htmlspecialchars($initialData['date'] ?? '') . '">';

            $html .= <<<EOF
                    <label for="time">Horario:</label>
                    <input type="time" name="time" id="time" required value="
            EOF;
            
            $html .= htmlspecialchars($initialData['time'] ?? '') . '">';

            $html .= <<<EOF
                    <label for="location">Localización:</label>
                    <input type="text" name="location" id="location" required placeholder="Ej: Estadio Santiago Bernabéu" value="
            EOF;
            
            $html .= htmlspecialchars($initialData['location'] ?? '') . '">';

            $html .= <<<EOF
                    <label for="price">Precio (€):</label>
                    <input type="number" name="price" id="price" step="0.01" min="0" required placeholder="Ej: 25.99" value="
            EOF;
            
            $html .= htmlspecialchars($initialData['price'] ?? '') . '">';

            // Campo proveedor modificado
            $html .= <<<EOF
                    <label for="provider">Proveedor:</label>
                    <input type="text" name="provider" id="provider" required 
                        placeholder="Ej: Mi Empresa S.L." value="
            EOF;
            
            $html .= htmlspecialchars($initialData['provider'] ?? '') . '">';

            $html .= <<<EOF
                    <button type="submit" name="botonRegisterEvent">Registrar Evento</button>
                </fieldset>
            EOF;

            return $html;
        }
//hacer el process de cara que cuando se registre el evento se muestre un mensaje de que se ha registrado correctamente
        protected function Process($data)
        {
            // Array de errores
            $result = array();

            // Filtrado y sanitización de los datos
            $eventName = trim($datos['name'] ?? '');
            $eventName = filter_var($eventName, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $startDate = trim($datos['start_date'] ?? '');
            $endDate = trim($datos['end_date'] ?? '');
            $minPrice = trim($datos['min_price'] ?? '');
            $maxPrice = trim($datos['max_price'] ?? '');

            $location = trim($datos['location'] ?? '');
            $location = filter_var($location, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $category = trim($datos['category'] ?? '');

            if(count($result) === 0)
            {
                // Crear un diccionario con los filtros seleccionados
                $filters = array();
                $filters['name'] = $eventName;
                $filters['start_date'] = $startDate;
                $filters['end_date'] = $endDate;
                $filters['min_price'] = $minPrice;
                $filters['max_price'] = $maxPrice;
                $filters['location'] = $location;
                $filters['category'] = $category;

                // Llamamos a la instancia de SA de eventos
                $eventAppService = eventAppService::GetSingleton();

                // Buscamos los eventos con los filtros seleccionados
                $foundedEventsDTO = $eventAppService->register($filters);

                // Manejamos el control de errores en función de lo que nos devuelva el SA
                if(count($foundedEventsDTO) === 0)
                {
                    $result[] = 'No se han encontrado eventos con los filtros seleccionados';
                }
                else
                {
                    $_SESSION["search"] = true;

                    // Array de eventos en formato JSON
                    $foundedEventsDTOJSON = array();

                    // Convertir el array de objetos eventDTO a un solo JSON
                    $foundedEventsDTOJSON = json_encode($foundedEventsDTO);

                    // Almacenar el JSON en una variable de sesión
                    $_SESSION["foundedEventsDTO"] = $foundedEventsDTOJSON;

                    $result = 'searchEvents.php';
                }
            }

            return $result;
        }
    }
?>
