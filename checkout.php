<?php
// filepath: c:\xampp\htdocs\AW-project\checkout.php

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use Stripe\Stripe;
use Stripe\Checkout\Session;
use TheBalance\product\productAppService;

// Configurar la clave secreta de Stripe
Stripe::setApiKey('sk_test_51R7x6U9DyzOhuTL3kJyeV8AMQCRPV9MLDaYRlaQxE403NLLUq9HpEMoPjQqRVrA8SOeAGKlA8l7AkqYdtd15lbCS00KQYgmwIq');

// Obtener los productos del carrito
$cart = $_SESSION['cart'] ?? [];
$lineItems = [];
foreach ($cart as $productId => $quantity) {
    $product = productAppService::GetSingleton()->getProductById($productId);
    if ($product) {
        $lineItems[] = [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $product->getName(),
                ],
                'unit_amount' => $product->getPrice() * 100, // Stripe usa céntimos
            ],
            'quantity' => $quantity,
        ];
    }
}

// Crear una sesión de pago
$checkoutSession = Session::create([
    'payment_method_types' => ['card'],
    'line_items' => $lineItems,
    'mode' => 'payment',
    'success_url' => 'success.php',
    'cancel_url' => 'cart.php',
]);

// Redirigir al usuario a la página de pago de Stripe
header('Location: ' . $checkoutSession->url);
exit;