<?php

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use TheBalance\order\orderAppService;
use TheBalance\order\orderDetailAppService;
use TheBalance\order\orderDTO;
use TheBalance\order\orderDetailDTO;

use Stripe\Stripe;
use Stripe\Checkout\Session;

Stripe::setApiKey(STRIPE_SECRET_KEY);

$titlePage = "Compra Exitosa";
$mainContent = "";

// Obtener el ID de la sesión desde la URL
$sessionId = $_GET['session_id'] ?? null;

if ($sessionId) {
    // Recuperar la sesión de Stripe
    $session = Session::retrieve($sessionId);

    // Obtener detalles del cliente y productos
    $customerEmail = $session->customer_details->email;

    $lineItems = \Stripe\Checkout\Session::allLineItems($sessionId)->data;

    $totalPrice = $session->amount_total / 100; // Convertir de céntimos a euros

    // Tomamos la instancia del servicio de pedidos
    $orderAppService = orderAppService::GetSingleton();

    // Crear un nuevo pedido
    $orderDTO = new orderDTO(null, $customerEmail, $totalPrice, "En preparación", date('Y-m-d H:i:s'));
    $orderId = $orderAppService->createOrder($orderDTO);

    if ($orderId) 
    {
        // Crear detalles del pedido
        foreach ($lineItems as $item) {
            $productName = $item->description;
            $productPrice = $item->amount / 100; // Convertir de céntimos a euros
            $quantity = $item->quantity;

            // Crear un nuevo detalle de pedido
            $orderDetailDTO = new orderDetailDTO($orderId, $productName, $quantity, $productPrice);
            orderDetailAppService::GetSingleton()->createOrderDetail($orderDetailDTO);
        }

        // Limpiar el carrito de compras
        unset($_SESSION['cart']);

        // Mostrar un diseño más profesional con Bootstrap
        $mainContent .= <<<EOS
            <div class="container success-page">
                <div class="card shadow-lg success-card">
                    <div class="card-header bg-success text-white text-center">
                        <h3 class="mb-0">¡Gracias por tu compra!</h3>
                    </div>
                    <div class="card-body text-center">
                        <img src="/AW-project/img/logo_thebalance.png" alt="Compra exitosa" class="img-fluid mb-4 success-logo">
                        <p class="lead">Tu pedido ha sido procesado con éxito.</p>
                        <hr>
                        <p>Pronto recibirás un correo electrónico con los detalles de tu pedido.</p>
                        <p class="text-muted">Si tienes alguna pregunta, no dudes en <a href="contact.php" class="text-decoration-none">contactarnos</a>.</p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="catalog.php" class="btn btn-primary btn-lg">Volver al catálogo</a>
                    </div>
                </div>
            </div>
        EOS;
    } 
    else 
    {
        // Mostrar un mensaje de error si no se pudo crear el pedido y un botón para volver al carrito
        $mainContent .= <<<EOS
            <div class="alert alert-danger" role="alert">
                No se pudo procesar tu pedido. Por favor, intenta nuevamente.
            </div>
            <a href="cart.php" class="btn btn-primary">Volver al carrito</a>
        EOS;
    }
}
else
{
    // Mostrar un mensaje de error si no se pudo crear el pedido y un botón para volver al carrito
    $mainContent .= <<<EOS
    <div class="alert alert-danger" role="alert">
        No se pudo procesar tu pedido. Por favor, intenta nuevamente.
    </div>
    <a href="cart.php" class="btn btn-primary">Volver al carrito</a>
    EOS;
}

require_once __DIR__ . '/includes/views/template/template.php';