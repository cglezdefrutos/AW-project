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

// Recuperar los datos del pedido y la dirección desde la sesión
$orderDetails = $_SESSION['order_details'] ?? null;
$shippingAddress = $_SESSION['shipping_address'] ?? null;

if (!$orderDetails || !$shippingAddress) {
    throw new Exception('Error: No se encontraron los datos del pedido o la dirección de envío.');
}

// Extraer los datos del pedido
$subtotal = $orderDetails['subtotal'];
$shippingCost = $orderDetails['shipping_cost'];
$total = $orderDetails['total'];

// Crear los line_items para Stripe
$lineItems = [];

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
                'unit_amount' => $product->getPrice() * 100,
                'product_data' => [
                    'name' => $product->getName(),
                    'description' => $product->getDescription(),
                ],
            ],
            'quantity' => $quantity,
        ];
    }
}

// Añadir los gastos de envío como un elemento adicional en los line_items
if ($shippingCost > 0) {
    $lineItems[] = [
        'price_data' => [
            'currency' => 'eur',
            'product_data' => [
                'name' => 'Gastos de envío',
                'description' => 'Coste de envío estándar',
            ],
            'unit_amount' => $shippingCost * 100,   // Convertir a céntimos
        ],
        'quantity' => 1,
    ];
}

// Crear una sesión de pago
$checkoutSession = Session::create([
    'payment_method_types' => ['card'],
    'line_items' => $lineItems,
    'mode' => 'payment',
    'success_url' => 'https://vm012.containers.fdi.ucm.es/AW-project/success.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => 'https://vm012.containers.fdi.ucm.es/AW-project/cancel.php',
    'metadata' => [
        'shipping_address' => implode(', ', [
            $shippingAddress['street'],
            $shippingAddress['floor'],
            $shippingAddress['postal_code'],
            $shippingAddress['city'],
        ]),
        'subtotal' => $subtotal,
        'shipping_cost' => $shippingCost,
        'total' => $total,
    ],
]);

// Redirigir al usuario a la página de pago de Stripe
header('Location: ' . $checkoutSession->url);
exit();