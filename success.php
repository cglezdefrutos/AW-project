<?php

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use TheBalance\application;
use TheBalance\order\orderAppService;
use TheBalance\order\orderDetailAppService;
use TheBalance\order\orderDTO;
use TheBalance\order\orderDetailDTO;
use TheBalance\product\productAppService;
use TheBalance\utils\utilsFactory;

use Stripe\Stripe;
use Stripe\Checkout\Session;

Stripe::setApiKey(STRIPE_SECRET_KEY);

$titlePage = "Compra Exitosa";
$mainContent = "";

// Obtener el ID de la sesión de Stripe desde la URL
$sessionId = $_GET['session_id'] ?? null;

if ($sessionId) {
    // Recuperar la sesión de Stripe
    $session = Session::retrieve($sessionId);

    // Recuperar los metadatos de la sesión
    $metadata = $session->metadata;
    $subtotal = $metadata->subtotal;
    $shippingCost = $metadata->shipping_cost;
    $total = $metadata->total;
    $address = $metadata->shipping_address;

    // Obtener el carrito desde la sesión del usuario
    $cart = $_SESSION['cart'] ?? [];

    if (!empty($cart)) {
        // Crear la orden
        $orderAppService = orderAppService::GetSingleton();
        $customer_id = application::getInstance()->getCurrentUserId();
        $orderDTO = new orderDTO(null, $customer_id, $total, "En preparación", $address, date('Y-m-d H:i:s'));
        $orderId = $orderAppService->createOrder($orderDTO);

        if ($orderId) {
            // Procesar cada producto en el carrito
            foreach ($cart as $cartKey => $quantity) {
                // Extraer product_id y talla del cartKey
                [$productId, $size] = explode('|', $cartKey);

                // Obtener información del producto desde la base de datos
                $productAppService = productAppService::GetSingleton();
                $product = $productAppService->getProductById($productId);

                if ($product) {
                    // Crear un nuevo detalle de pedido
                    $orderDetailDTO = new orderDetailDTO(
                        $orderId, 
                        $productId,
                        $product->getImageGuid(),
                        $quantity, 
                        $product->getPrice(), 
                        $size
                    );
                    orderDetailAppService::GetSingleton()->createOrderDetail($orderDetailDTO);

                    // Actualizar el stock
                    $productAppService->updateProductStock($productId, $quantity, $size);
                }
            }

            // Limpiar carrito y sesión del pedido
            unset($_SESSION['cart']);
            unset($_SESSION['order_details']);
            unset($_SESSION['shipping_address']);

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
                            <p class="text-muted">Si tienes alguna pregunta, no dudes en <a href="index.php" class="text-decoration-none">contactarnos</a>.</p>
                        </div>
                        <div class="card-footer text-center">
                            <a href="catalog.php" class="btn btn-primary btn-lg">Volver al catálogo</a>
                        </div>
                    </div>
                </div>
            EOS;
        } else {
            $mainContent .= utilsFactory::createAlert("No se pudo procesar tu pedido. Por favor, intenta nuevamente.", "danger");
        }
    }
} else {
    $mainContent .= utilsFactory::createAlert("No se ha creado la sesión de pago. Por favor, completa la pasarela de pagos para finalizar tu pedido", "danger");
}

require_once BASE_PATH.'/includes/views/template/template.php';