<?php

require_once __DIR__ . '/includes/config.php';

use TheBalance\cart\shippingDataForm;

$titlePage = "Dirección de envío";

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

require_once __DIR__ . '/includes/views/template/template.php';