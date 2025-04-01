<?php

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use Stripe\Stripe;
use Stripe\Checkout\Session;
use TheBalance\product\productAppService;

$titlePage = "Checkout";
$mainContent = "";

// Configurar la clave secreta de Stripe
Stripe::setApiKey(STRIPE_SECRET_KEY);

// Obtener los productos del carrito
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    // Mostrar una alerta y redirigir al catálogo
    $mainContent .= <<<EOF
        <div class="alert alert-warning text-center" role="alert">
            <h4 class="alert-heading">¡Tu carrito está vacío!</h4>
            <p>No puedes proceder al pago porque no tienes productos en el carrito.</p>
            <hr>
            <a href="catalog.php" class="btn btn-primary">Volver al catálogo</a>
        </div>
    EOF;

    require_once __DIR__ . '/includes/views/template/template.php';
    exit();
}

$lineItems = [];

foreach ($cart as $cartKey => $quantity) 
{
    // Separar el product_id y la talla desde la clave del carrito
    [$productId, $size] = explode('|', $cartKey);

    // Obtener el producto usando el product_id
    $product = productAppService::GetSingleton()->getProductById($productId);
    if ($product) 
    {
        $lineItems[] = [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $product->getName(),
                    'description' => $product->getDescription(),
                    'metadata' => [
                        'product_id' => $productId,
                        'category' => $product->getCategoryName(),
                        'size' => $size,
                    ],
                ],
                'unit_amount' => $product->getPrice() * 100,
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
    'success_url' => 'http://localhost/AW-project/success.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => 'http://localhost/AW-project/success.php',
]);

// Redirigir al usuario a la página de pago de Stripe
header('Location: ' . $checkoutSession->url);
exit();