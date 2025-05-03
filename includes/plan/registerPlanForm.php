<?php

namespace TheBalance\plan;

use TheBalance\views\common\baseForm;

/**
 * Formulario de registro de planes de entrenamiento
 */
class registerPlanForm extends baseForm
{
    /**
     * ID del trainer
     * 
     * @var int
     */
    private $trainer_id;
    
    /**
     * Constructor
     * 
     * @param int $trainer_id ID del trainer
     */
    public function __construct($trainer_id)
    {
        parent::__construct('registerPlanForm', array('enctype' => 'multipart/form-data'));
        $this->trainer_id = $trainer_id;
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
        // Definimos las dificultades disponibles
        $difficulties = ['Principiante', 'Intermedio', 'Avanzado', 'Experto'];
        
        // Creamos el formulario de registro de planes
        $html = <<<EOF
            <fieldset class="border p-4 rounded">
                <legend class="w-auto">Registro de Plan de Entrenamiento</legend>

                <!-- Campo Nombre del plan -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre del plan:</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Ej: Plan de fuerza avanzado" value="
        EOF;

        $html .= htmlspecialchars($initialData['name'] ?? '') . '" required>';
        
        $html .= <<<EOF
                </div>

                <!-- Campo Descripción -->
                <div class="mb-3">
                    <label for="description" class="form-label">Descripción:</label>
                    <textarea name="description" id="description" class="form-control" placeholder="Describe el plan de entrenamiento" rows="4" required>
        EOF;

        $html .= htmlspecialchars($initialData['description'] ?? '') . '</textarea>';

        $html .= <<<EOF
                </div>

                <!-- Campo Duración -->
                <div class="mb-3">
                    <label for="duration" class="form-label">Duración (días):</label>
                    <input type="number" name="duration" id="duration" class="form-control" min="1" placeholder="Ej: 30" value="
        EOF;

        $html .= htmlspecialchars($initialData['duration'] ?? '') . '" required>';
        
        $html .= <<<EOF
                </div>

                <!-- Campo Precio -->
                <div class="mb-3">
                    <label for="price" class="form-label">Precio (€):</label>
                    <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" placeholder="Ej: 24.99" value="
        EOF;

        $html .= htmlspecialchars($initialData['price'] ?? '') . '" required>';
             
        $html .= <<<EOF
                </div>

                <!--Imagen -->
                <div class="mb-3">
                    <label for="image" class="form-label">Imagen del plan:</label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                    <small class="form-text text-muted">Formatos permitidos: JPEG, JPG o PNG.</small>
                </div>

                <!-- Campo Dificultad -->
                <div class="mb-3">
                    <label for="difficulty" class="form-label">Dificultad:</label>
                    <select name="difficulty" id="difficulty" class="form-control" required>
                        <option value="">Seleccione una dificultad</option>
        EOF;

        foreach ($difficulties as $difficulty) {
            $selected = (isset($initialData['difficulty']) && $initialData['difficulty'] === $difficulty) ? 'selected' : '';
            $html .= '<option value="' . htmlspecialchars($difficulty) . '" ' . $selected . '>' . htmlspecialchars($difficulty) . '</option>';
        }

        $html .= <<<EOF
                </div>

                <!-- Campo PDF del plan -->
                <div class="mb-3">
                    <label for="pdf" class="form-label">Archivo PDF del plan:</label>
                    <input type="file" name="pdf" id="pdf" class="form-control" accept=".pdf" required>
                    <small class="form-text text-muted">Formato permitido: PDF.</small>
                </div>

                <!-- Botón de registro -->
                <div class="mt-3">
                    <button type="submit" name="botonRegisterPlan" class="btn btn-primary w-100">Registrar Plan</button>
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
        $name = trim($data['name'] ?? '');
        $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($name) || strlen($name) > 50) {
            $result[] = 'El nombre del plan es obligatorio y no debe exceder los 50 caracteres.';
        }

        $description = trim($data['description'] ?? '');
        $description = filter_var($description, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($description) || strlen($description) > 1000) {
            $result[] = 'La descripción es obligatoria y no debe exceder los 1000 caracteres.';
        }

        $duration = filter_var($data['duration'] ?? 0, FILTER_VALIDATE_INT);
        if ($duration === false || $duration < 1) {
            $result[] = 'La duración debe ser un número entero positivo.';
        }

        $price = trim($data['price'] ?? '');
        if (!is_numeric($price) || $price < 0) {
            $result[] = 'El precio debe ser un número positivo.';
        }

        // Validación del archivo PDF
        $pdf = $_FILES['pdf'] ?? null;
        if (!isset($pdf) || $pdf['error'] === UPLOAD_ERR_NO_FILE) {
            $result[] = 'El PDF del plan es obligatorio.';
        } elseif ($pdf['error'] !== UPLOAD_ERR_OK) {
            $result[] = 'Error al subir el PDF: ' . $this->getUploadError($pdf['error']);
        } else {
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($fileInfo, $pdf['tmp_name']);
            finfo_close($fileInfo);
            
            if ($mimeType !== 'application/pdf') {
                $result[] = 'El archivo debe ser un PDF.';
            }
        }

        $difficulty = trim($data['difficulty'] ?? '');
        if (empty($difficulty) || !in_array($difficulty, ['Principiante', 'Intermedio', 'Avanzado', 'Experto'])) {
            $result[] = 'Seleccione una dificultad válida.';
        }

        // Validación del campo de imagen
        $image = $_FILES['image'] ?? null;

        if (!isset($image) || $image['error'] === UPLOAD_ERR_NO_FILE) {
            $result[] = 'La imagen del plan es obligatoria.';
        } elseif ($image['error'] !== UPLOAD_ERR_OK) {
            $result[] = 'Error al subir la imagen: ' . $this->getUploadError($_FILES['image']['error']);
        } else {
            // Validar tipo de archivo
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($fileInfo, $image['tmp_name']);
            finfo_close($fileInfo);
            
            if (!in_array($mimeType, $allowedTypes)) {
                $result[] = 'El archivo debe ser una imagen (JPEG, JPG o PNG).';
            }
        }

        // Si no hay errores, pasar los datos al servicio
        if (count($result) === 0) {
            $planData = [
                'trainer_id' => $this->trainer_id,
                'name' => $name,
                'description' => $description,
                'difficulty' => $difficulty,
                'duration' => $duration,
                'price' => $price,
                'pdf_file' => $pdf,
                'image_file' => $image,
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Obtenemos la instancia del servicio de planes
            $planAppService = planAppService::GetSingleton();

            // Intentamos registrar el nuevo planes
            $planId = $planAppService->registerPlan($planData);
            
            if (!$planId) {
                $result[] = 'Error al registrar el plan de entrenamiento. Por favor, verifica los datos.';
            } else {
                $result = 'registerPlans.php?registered=true';
            }
        }

        return $result;
    }   
}