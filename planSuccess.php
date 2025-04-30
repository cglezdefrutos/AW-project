<?php

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use TheBalance\application;
use TheBalance\plan\planAppService;
use TheBalance\plan\planDTO;
use TheBalance\utils\utilsFactory;

use Stripe\Stripe;
use Stripe\Checkout\Session;

Stripe::setApiKey(STRIPE_SECRET_KEY);

$titlePage = "Plan Contratado Exitosamente";
$mainContent = "";

// Obtener el ID de la sesión de Stripe desde la URL
$sessionId = $_GET['session_id'] ?? null;

if ($sessionId) {
    // Recuperar la sesión de Stripe
    $session = Session::retrieve($sessionId);

    // Recuperar los metadatos de la sesión
    $metadata = $session->metadata;
    $planId = $metadata->id;
    $planName = $metadata->name;
    $planDescription = $metadata->description;
    $planPrice = $metadata->price;
    
    // Obtener los datos del plan desde la sesión
    $plan = $_SESSION['plan_checkout'] ?? null;

    if ($plan) {
        //TODO meter la relación plan cliente en db

        if ($planId) {
            // Limpiar sesión del plan
            unset($_SESSION['plan_checkout']);

            $mainContent .= <<<EOS
                <div class="container success-page">
                    <div class="card shadow-lg success-card">
                        <div class="card-header bg-success text-white text-center">
                            <h3 class="mb-0">¡Plan Contratado Exitosamente!</h3>
                        </div>
                        <div class="card-body text-center">
                            <img src="/AW-project/img/logo_thebalance.png" alt="Plan contratado" class="img-fluid mb-4 success-logo">
                            <p class="lead">Has contratado el plan de entrenamiento {$planName} con éxito.</p>
                            <hr>
                            <p>Pronto recibirás un correo electrónico con los detalles del plan contratado.</p>
                            <p class="text-muted">Si tienes alguna pregunta, no dudes en <a href="index.php" class="text-decoration-none">contactarnos</a>.</p>
                        </div>
                        <div class="card-footer text-center">
                            <a href="catalog.php" class="btn btn-primary btn-lg">Volver al catálogo</a>
                        </div>
                    </div>
                </div>
            EOS;
        } else {
            $mainContent .= utilsFactory::createAlert("No se pudo procesar tu contratación del plan. Por favor, intenta nuevamente.", "danger");
        }
    } else {
        $mainContent .= utilsFactory::createAlert("No se encontraron los datos del plan. Asegúrate de que la sesión del plan esté activa.", "danger");
    }
} else {
    $mainContent .= utilsFactory::createAlert("No se ha creado la sesión de pago correctamente. Por favor, completa la pasarela de pagos para finalizar la contratación.", "danger");
}

require_once BASE_PATH.'/includes/views/template/template.php';

?>
