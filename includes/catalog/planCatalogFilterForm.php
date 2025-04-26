<?php

namespace TheBalance\catalog;

use TheBalance\views\common\baseForm;
use TheBalance\plan\planAppService;

/**
 * Formulario para filtrar planes de entrenamiento en el catálogo.
 */
class planCatalogFilterForm extends baseForm
{
    public function __construct()
    {
        parent::__construct('planCatalogFilterForm');
    }

    /**
     * Crea los campos del formulario.
     * 
     * @param array $initialData Datos iniciales del formulario.
     * 
     * @return string Campos del formulario.
     */
    protected function CreateFields($initialData)
    {
        // Obtener los entrenadores y dificultades desde el servicio de planes
        $planAppService = planAppService::GetSingleton();
        $trainers = $planAppService->getTrainers();
        $difficulties = $planAppService->getDifficulties();

        $html = <<<EOF
            <fieldset class="border p-4 rounded">
                <legend class="w-auto">Filtrar Planes</legend>

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre:</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Buscar por nombre" value="
        EOF;

        $html .= htmlspecialchars($initialData['name'] ?? '') . '">';

        $html .= <<<EOF
                    </select>
                </div>

                <div class="mb-3">
                    <label for="difficulty" class="form-label">Dificultad:</label>
                    <select name="difficulty" id="difficulty" class="form-control">
                        <option value="">Seleccionar dificultad</option>
        EOF;

        // Añadir las dificultades como opciones
        foreach ($difficulties as $difficulty) {
            $difficultyValue = htmlspecialchars($difficulty);
            $selected = (isset($initialData['difficulty']) && $initialData['difficulty'] == $difficultyValue) ? 'selected' : '';
            $html .= '<option value="' . $difficultyValue . '" ' . $selected . '>' . $difficultyValue . '</option>';
        }

        $html .= <<<EOF
                    </select>
                </div>

                <div class="mb-3">
                    <label for="minPrice" class="form-label">Precio mínimo:</label>
                    <input type="number" name="minPrice" id="minPrice" class="form-control" step="0.50" min="0" placeholder="Ej: 0" value="
        EOF;

        $html .= htmlspecialchars($initialData['minPrice'] ?? '0') . '">';

        $html .= <<<EOF
                </div>

                <div class="mb-3">
                    <label for="maxPrice" class="form-label">Precio máximo:</label>
                    <input type="number" name="maxPrice" id="maxPrice" class="form-control" step="0.50" placeholder="Ej: 100" value="
        EOF;

        $html .= htmlspecialchars($initialData['maxPrice'] ?? '1000') . '">';

        $html .= <<<EOF
                </div>
                <div class="mb-3">
                        <label for="minDuration" class="form-label">Duración mínima (días):</label>
                        <input type="number" name="minDuration" id="minDuration" class="form-control" min="1" placeholder="Ej: 1" value="
        EOF;

        $html .= htmlspecialchars($initialData['minDuration'] ?? '1') . '">';

        $html .= <<<EOF
                    </div>
                    <div class="mb-3">
                        <label for="maxDuration" class="form-label">Duración máxima (días):</label>
                        <input type="number" name="maxDuration" id="maxDuration" class="form-control" min="1" placeholder="Ej: 30" value="
        EOF;

        $html .= htmlspecialchars($initialData['maxDuration'] ?? '30') . '">';

        $html .= <<<EOF
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </fieldset>
        EOF;

        return $html;
    }

    /**
     * Procesa los datos del formulario.
     * 
     * @param array $data Datos del formulario.
     * 
     * @return array|string Errores de procesamiento o redirección.
     */
    protected function Process($data)
    {
        // Array para almacenar mensajes de error
        $result = array();

        // Filtrado y sanitización de los datos recibidos
        $name = trim($data['name'] ?? '');
        $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (strlen($name) > 100) {
            $result[] = 'El nombre del plan no puede superar los 100 caracteres.';
        }

        $trainer = trim($data['trainer'] ?? '');
        $trainer = filter_var($trainer, FILTER_SANITIZE_NUMBER_INT);
        if ($trainer !== '' && !filter_var($trainer, FILTER_VALIDATE_INT)) {
            $result[] = 'El ID del entrenador debe ser un número entero válido.';
        }

        $difficulty = trim($data['difficulty'] ?? '');
        $difficulty = filter_var($difficulty, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (strlen($difficulty) > 50) {
            $result[] = 'La dificultad no puede superar los 50 caracteres.';
        }

        $minPrice = filter_var($data['minPrice'] ?? 0.0, FILTER_VALIDATE_FLOAT);
        if (!is_numeric($minPrice) || $minPrice < 0) {
            $result[] = 'El precio mínimo debe ser un número positivo.';
        }

        $maxPrice = filter_var($data['maxPrice'] ?? 1000.0, FILTER_VALIDATE_FLOAT);
        if (!is_numeric($maxPrice) || $maxPrice < 0) {
            $result[] = 'El precio máximo debe ser un número positivo.';
        }

        if ($minPrice > $maxPrice) {
            $result[] = 'El precio mínimo no puede ser mayor que el precio máximo.';
        }

        // Validación de la duración
        $minDuration = filter_var($data['minDuration'] ?? 1, FILTER_VALIDATE_INT);
        if (!is_numeric($minDuration) || $minDuration < 1) {
            $result[] = 'La duración mínima debe ser un número entero positivo.';
        }

        $maxDuration = filter_var($data['maxDuration'] ?? 30, FILTER_VALIDATE_INT);
        if (!is_numeric($maxDuration) || $maxDuration < 1) {
            $result[] = 'La duración máxima debe ser un número entero positivo.';
        }

        if ($minDuration > $maxDuration) {
            $result[] = 'La duración mínima no puede ser mayor que la duración máxima.';
        }

        if(count($result) === 0) {
            // Crear un diccionario con los filtros seleccionados
            $filters = array();
            $filters['name'] = $name;
            $filters['trainer_id'] = $trainer !== '' ? (int)$trainer : null;
            $filters['difficulty'] = $difficulty;
            $filters['minPrice'] = $minPrice;
            $filters['maxPrice'] = $maxPrice;
            $filters['minDuration'] = $minDuration;
            $filters['maxDuration'] = $maxDuration;

            // Llamamos a la instancia de SA de planes de entrenamiento
            $planAppService = planAppService::GetSingleton();

            // Buscamos los planes con los filtros seleccionados
            $foundedPlansDTO = $planAppService->searchTrainingPlans($filters);

            // Array de planes en formato JSON
            $foundedPlansJSON = json_encode($foundedPlansDTO);

            // Almacenar el JSON en una variable de sesión
            $_SESSION["foundedPlansJSON"] = $foundedPlansJSON;

            // Volvemos al catálogo con los planes filtrados
            $result = 'catalogPlan.php?search=true';
        }

        return $result;
    }
}