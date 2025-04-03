<?php

require_once __DIR__ . '/includes/config.php';

use TheBalance\cart\cartTable;
use TheBalance\cart\orderSummary;
use TheBalance\product\productAppService;

$titlePage = "Carrito";
$mainContent = "";

// Procesar acciones del carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['update_quantity'])) {
        $cartKey = $_POST['cart_key'];
        $quantity = max(1, (int)$_POST['quantity']);

        // Separar el product_id y la talla desde la clave del carrito
        [$productId, $size] = explode('|', $cartKey);

        // Obtener el producto y su stock
        $productDTO = productAppService::GetSingleton()->getProductById($productId);

        if($productDTO) {
            // Miramos el stock de esa talla
            $sizesDTO = $productDTO->getSizesDTO();
            $stock = $sizesDTO->getSizes()[$size] ?? 0;

            // Validar si hay suficiente stock disponible para la cantidad solicitada
            if ($quantity > $stock) {
                $_SESSION['error_message'] = "No hay suficiente stock para la talla seleccionada. Stock disponible: {$stock}.";
            } else {
                $_SESSION['cart'][$cartKey] = $quantity;
            }
        } else {
            $_SESSION['error_message'] = "El producto no existe.";
        }
    }

    if (isset($_POST['remove_product'])) {
        $cartKey = $_POST['cart_key'];
        unset($_SESSION['cart'][$cartKey]);
    }
}

// Generar los mensajes de error
$errorMessages = '';
if (!empty($_SESSION['error_message'])) {
    $errorMessages .= '<div class="alert alert-danger" role="alert">';
    $errorMessages .= htmlspecialchars($_SESSION['error_message']);
    $errorMessages .= '</div>';
    unset($_SESSION['error_message']); // Limpiar el mensaje después de mostrarlo
}

$cart = $_SESSION['cart'] ?? [];

// Generar contenido del carrito
$columns = ['Imagen', 'Producto', 'Precio', 'Talla', 'Cantidad', 'Subtotal', 'Acciones'];
$cartTable = new cartTable($cart, $columns);

// Generar el resumen del pedido
$orderSummary = new orderSummary($cart);

// Generar el contenido principal
$mainContent = <<<EOF
    <div class="row px-3">
        {$errorMessages}
    </div>

    <div class="row">
        <!-- Tabla del carrito -->
        <div class="col-md-8">
            {$cartTable->generateTable()}
        </div>

        <!-- Resumen del pedido -->
        <div class="col-md-4">
            {$orderSummary->generateContent()}
        </div>
    </div>
EOF;

require_once __DIR__ . '/includes/views/template/template.php';