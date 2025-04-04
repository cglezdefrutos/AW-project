<?php

namespace TheBalance\product;

use TheBalance\views\common\baseForm;

/**
 * Formulario para registrar otro producto o volver al inicio
 */
class registerAnotherProductForm extends baseForm
{
    public function __construct()
    {
        parent::__construct('registerAnotherProductForm');
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
            <div class="container mt-4">
                <fieldset class="border p-4 rounded">
                    <legend class="w-auto">Opciones</legend>

                    <!-- Botón para registrar otro producto -->
                    <div class="mb-3">
                        <button type="submit" name="registerAnother" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle"></i> Registrar otro producto
                        </button>
                    </div>

                    <!-- Botón para ver mis productos -->
                    <div class="mb-3">
                        <a href="manageProducts.php" class="btn btn-info w-100">
                            <i class="bi bi-list-ul"></i> Ver mis productos
                        </a>
                    </div>

                    <!-- Botón para volver al inicio -->
                    <div class="mb-3">
                        <a href="index.php" class="btn btn-secondary w-100">
                            <i class="bi bi-house"></i> Volver a inicio
                        </a>
                    </div>
                </fieldset>
            </div>
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
        // Redirigir a la misma página para mostrar el formulario de registro de nuevo
        return 'registerProducts.php';
    }
}