<?php

require_once __DIR__ . '/includes/config.php';

use TheBalance\cart\shippingDataForm;
use TheBalance\application;
use TheBalance\utils\utilsFactory;

$titlePage = "Dirección de envío";

if (!application::getInstance()->isCurrentUserClient()) {
    // Alerta de error si el usuario no ha iniciado sesión
    $mainContent .= utilsFactory::createAlert("No has iniciado sesión. Por favor, inicia sesión para continuar con el proceso de pago.", "danger");
}
else if (isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    // Alerta de error si el carrito está vacío
    $mainContent .= utilsFactory::createAlert("El carrito está vacío. Por favor, añade productos al carrito antes de continuar.", "danger");
}
else
{
    // Crear una instancia del formulario
    $form = new shippingDataForm();
    $htmlShippingDataForm = $form->Manage();

    // Generar el contenido principal con el formulario
    $mainContent = <<<EOF
        <div class="container mt-5">
            <h2 class="mb-4">Introduce tu dirección de envío</h2>
            $htmlShippingDataForm
        </div>
    EOF;
}

require_once __DIR__ . '/includes/views/template/template.php';