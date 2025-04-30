<?php

namespace TheBalance\plan;

use TheBalance\views\common\baseForm;
use TheBalance\application;
use TheBalance\utils\utilsFactory;

class planPaymentForm extends baseForm
{
    private $planDTO;

    public function __construct($planDTO)
    {
        $this->planDTO = $planDTO;
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
                <input type="hidden" name="plan_id" value="{$this->planDTO->getId()}">
                <button type="submit" class="btn btn-primary btn-lg w-100">
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

        $planId = $data['plan_id'] ?? null;
        if (!$planId || !is_numeric($planId)) {
            return ["Error: No se proporcionó un ID de plan válido."];
        }

        
        $planDTO = planAppService::GetSingleton()->getPlanById($planId);
        if (!$planDTO) {
            return ["Error: No se encontró el plan con ID $planId."];
        }

        // Inicializar si no existe
        if (!isset($_SESSION['plan_checkout'])) 
        {
                $_SESSION['plan_checkout'] = [];
        }        

        $_SESSION['plan_checkout'] = [
            'id' => $planDTO->getId(),
            'name' => $planDTO->getName(),
            'description' => $planDTO->getDescription(),
            'price' => $planDTO->getPrice(),
        ];

        return 'planCheckout.php';
    }

}
