<?php

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use Stripe\Stripe;
use Stripe\Checkout\Session;

$titlePage = "Plan Checkout";
$mainContent = "";

// Configura tu clave secreta de Stripe
Stripe::setApiKey(STRIPE_SECRET_KEY);

// Asegúrate de que hay datos del plan en sesión
$plan = $_SESSION['plan_checkout'] ?? null;
if (!$plan) {
    throw new Exception('Error: No se encontraron los datos del plan.');
}

// Crear el line_item para el plan
$lineItems = [[
    'price_data' => [
        'currency' => 'eur',
        'unit_amount' => $plan['price'] * 100,
        'product_data' => [
            'name' => $plan['name'],
            'description' => $plan['description'],
        ],
    ],
    'quantity' => 1,
]];


// Crear sesión de Stripe Checkout
$checkoutSession = Session::create([
    'payment_method_types' => ['card'],
    'line_items' => $lineItems,
    'mode' => 'payment',
    'success_url' => 'https://vm012.containers.fdi.ucm.es/AW-project/planSuccess.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => 'https://vm012.containers.fdi.ucm.es/AW-project/planCancel.php',
    'metadata' => [
        'id' => $plan['id'],
        'name' => $plan['name']
    ],
]);

// // Crear sesión de Stripe Checkout local
// $checkoutSession = Session::create([
//     'payment_method_types' => ['card'],
//     'line_items' => $lineItems,
//     'mode' => 'payment',
//     'success_url' => 'http://localhost/AW-project/planSuccess.php?session_id={CHECKOUT_SESSION_ID}',
//     'cancel_url' => 'http://localhost/AW-project/planCancel.php',
//     'metadata' => [
//         'id' => $plan['id'],
//         'name' => $plan['name']
//     ],
// ]);

// Redirigir a Stripe
header('Location: ' . $checkoutSession->url);
exit();
