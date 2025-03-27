<?php

namespace TheBalance\event;

use TheBalance\views\common\baseForm;

/**
 * Formulario para registrar otro evento o volver al inicio
 */
class registerAnotherEventForm extends baseForm
{
    public function __construct()
    {
        parent::__construct('registerAnotherEventForm');
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
        $html = <<<EOS
            <fieldset class="border p-4 rounded">
                <legend class="w-auto">¿Dónde desea ir?</legend>

                <!-- Botón para registrar otro evento -->
                <div class="mb-3">
                    <button type="submit" name="registerAnother" class="btn btn-primary w-100">Registrar otro evento</button>
                </div>

                <!-- Botón para volver al inicio -->
                <div class="mb-3">
                    <a href="index.php" class="btn btn-secondary w-100">Volver a inicio</a>
                </div>
            </fieldset>
        EOS;

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
        // Redirigir a la misma página para mostrar el formulario de nuevo
        return 'registerEvents.php';
    }
}