<?php

namespace TheBalance\event;

use TheBalance\views\common\baseForm;

class searchEventForm extends baseForm
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent:: __construct('searchEventForm');
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
        // Creamos el formulario de búsqueda de eventos
        $html = <<<EOF
            <fieldset class="border p-4 rounded">
                <legend class="w-auto">Buscar eventos</legend>

                <!-- Campo Nombre del evento -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre del evento:</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Ej: Fitness" value="
        EOF;

        $html .= htmlspecialchars($initialData['name'] ?? '') . '">';
        
        $html .= <<<EOF
                </div>

                <!-- Campo Fecha de inicio -->
                <div class="mb-3">
                    <label for="start_date" class="form-label">Desde:</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="
        EOF;

        $html .= htmlspecialchars($initialData['start_date'] ?? '') . '">';
        
        $html .= <<<EOF
                </div>

                <!-- Campo Fecha de fin -->
                <div class="mb-3">
                    <label for="end_date" class="form-label">Hasta:</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="
        EOF;

        $html .= htmlspecialchars($initialData['end_date'] ?? '') . '">';
        
        $html .= <<<EOF
                </div>

                <!-- Campo Precio mínimo -->
                <div class="mb-3">
                    <label for="min_price" class="form-label">Precio mínimo (€):</label>
                    <input type="number" name="min_price" id="min_price" class="form-control" step="0.01" placeholder="0" value="
        EOF;

        $html .= htmlspecialchars($initialData['min_price'] ?? '') . '">';
        
        $html .= <<<EOF
                </div>

                <!-- Campo Precio máximo -->
                <div class="mb-3">
                    <label for="max_price" class="form-label">Precio máximo (€):</label>
                    <input type="number" name="max_price" id="max_price" class="form-control" step="0.01" placeholder="1000" value="
        EOF;

        $html .= htmlspecialchars($initialData['max_price'] ?? '') . '">';
        
        $html .= <<<EOF
                </div>

                <!-- Campo Ubicación -->
                <div class="mb-3">
                    <label for="location" class="form-label">Ubicación:</label>
                    <input type="text" name="location" id="location" class="form-control" placeholder="Ej: Madrid" value="
        EOF;

        $html .= htmlspecialchars($initialData['location'] ?? '') . '">';
        
        $html .= <<<EOF
                </div>

                <!-- Campo Categoría -->
                <div class="mb-3">
                    <label for="category" class="form-label">Categoría:</label>
                    <select name="category" id="category" class="form-select">
                        <option value="">Todas</option>
                        <option value="Futbol" 
        EOF;

        $html .= ($initialData['category'] ?? '') == 'Futbol' ? 'selected' : '' . '>Futbol</option>';
        
        $html .= <<<EOF
                        <option value="Baloncesto" 
        EOF;

        $html .= ($initialData['category'] ?? '') == 'Baloncesto' ? 'selected' : '' . '>Baloncesto</option>';
        
        $html .= <<<EOF
                        <option value="Fitness" 
        EOF;

        $html .= ($initialData['category'] ?? '') == 'Fitness' ? 'selected' : '' . '>Fitness</option>';
        
        $html .= <<<EOF
                        <option value="Atletismo" 
        EOF;

        $html .= ($initialData['category'] ?? '') == 'Atletismo' ? 'selected' : '' . '>Atletismo</option>';
        
        $html .= <<<EOF
                    </select>
                </div>

                <!-- Botón de búsqueda -->
                <div class="mt-3">
                    <button type="submit" name="botonSearchEvents" class="btn btn-primary w-100">Buscar</button>
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
        // Array de errores
        $result = array();

        // Filtrado y sanitización de los datos
        $eventName = trim($data['name'] ?? '');
        $eventName = filter_var($eventName, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $startDate = trim($data['start_date'] ?? '');
        $endDate = trim($data['end_date'] ?? '');
        $minPrice = trim($data['min_price'] ?? '');
        $maxPrice = trim($data['max_price'] ?? '');

        $location = trim($data['location'] ?? '');
        $location = filter_var($location, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $category = trim($data['category'] ?? '');

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
            $foundedEventsDTO = $eventAppService->search($filters);

            // Manejamos el control de errores en función de lo que nos devuelva el SA
            if(count($foundedEventsDTO) === 0)
            {
                $result[] = 'No se han encontrado eventos con los filtros seleccionados';
            }
            else
            {
                // Array de eventos en formato JSON
                $foundedEventsDTOJSON = array();

                // Convertir el array de objetos eventDTO a un solo JSON
                $foundedEventsDTOJSON = json_encode($foundedEventsDTO);

                // Almacenar el JSON en una variable de sesión
                $_SESSION["foundedEventsDTO"] = $foundedEventsDTOJSON;

                $result = 'searchEvents.php?search=true';
            }
        }

        return $result;
    }
}
