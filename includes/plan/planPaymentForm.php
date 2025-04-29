<?php

namespace TheBalance\plan;

use TheBalance\views\common\baseForm;
use TheBalance\application;
use TheBalance\utils\utilsFactory;

class planPaymentForm extends baseForm
{
    public function __construct()
    {
        parent::__construct('planPaymentForm', ['action' => 'planCheckout.php']);
    }

    /**
     * Genera el contenido del formulario: solo botón si hay sesión, o alerta si no.
     */
    protected function CreateFields($initialData)
    {
        $app = application::getInstance();
        $html = '<div class="text-center mt-4">';

        if ($app->isCurrentUserLogged()) {
            $html .= <<<EOF
                <button type="submit" class="btn btn-primary btn-lg">
                    Contratar Plan
                </button>
            EOF;
        } else {
            $html .= utilsFactory::createAlert("Debes iniciar sesión como cliente para poder contratar un plan de entrenamiento.", "warning");
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Procesamiento del formulario (redirige si está logueado, sino muestra error).
     */
    protected function Process($data)
    {
        $app = application::getInstance();

        if (!$app->isCurrentUserLogged()) {
            return ["Debes iniciar sesión antes de poder pagar."];
        }

        return 'planCheckout.php';
    }
}
