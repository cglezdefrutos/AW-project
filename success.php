<?php

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use TheBalance\order\orderAppService;
use TheBalance\order\orderDetailAppService;
use TheBalance\order\orderDTO;
use TheBalance\order\orderDetailDTO;
use TheBalance\product\ProductAppService;
use TheBalance\product\ProductDTO;
use TheBalance\application;

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
    $lineItems = Session::allLineItems($sessionId)->data;

    // Recuperar los metadatos desde la sesión de Stripe
    $metadata = $session->metadata;

    // Extraer los datos del pedido desde los metadatos
    $subtotal = $metadata->subtotal;
    $shippingCost = $metadata->shipping_cost;
    $total = $metadata->total;

    // Reconstruir la dirección de envío desde los metadatos
    $address = $metadata->shipping_address;

    // Tomamos la instancia del servicio de pedidos
    $orderAppService = orderAppService::GetSingleton();

    // Tomar el id de usuario
    $customer_id = application::GetSingleton()->getCurrentUserId();

    // Crear un nuevo pedido
    $orderDTO = new orderDTO(null, $customer_id, $total, "En preparación", $address, date('Y-m-d H:i:s'));
    $orderId = $orderAppService->createOrder($orderDTO);

    if ($orderId) 
    {
        // Crear detalles del pedido
        foreach ($lineItems as $item) {
            // Si son los gastos de envío, no crear un detalle de pedido
            if ($item->description === 'Gastos de envío') {
                continue;
            }

            $productId = $item->price->product_data->metadata->product_id;
            $productPrice = $item->price->unit_amount / 100; // Convertir de céntimos a euros
            $quantity = $item->quantity;
            $size = $item->price->product_data->metadata->size ?? 'N/A'; // Obtener la talla desde los metadatos

            // Crear un nuevo detalle de pedido
            $orderDetailDTO = new orderDetailDTO($orderId, $productName, $quantity, $productPrice, $size);
            orderDetailAppService::GetSingleton()->createOrderDetail($orderDetailDTO);

            // Actualizar el stock del producto en la base de datos
/*             $productAppService = ProductAppService::GetSingleton();
            $product = $productAppService->getProductByName($productName);
            if ($product) {
                $sizesDTO = $product->getSizesDTO();
                $currentStock = $sizesDTO->getSizes()[$size] ?? 0;
                $newStock = max(0, $currentStock - $quantity);
                $sizesDTO->updateSizeStock($size, $newStock);
                $productAppService->updateProductSizes($product->getId(), $sizesDTO);
            } */
        }

        // Limpiar el carrito de compras y la dirección de envío
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