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
            <button type="submit" name="registerAnother">Registrar otro evento</button>
            <a href="index.php">
                <button type="button">Volver a inicio</button>
            </a>
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