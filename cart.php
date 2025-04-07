<?php

require_once __DIR__ . '/includes/config.php';

use TheBalance\cart\cartTable;
use TheBalance\cart\orderSummary;
use TheBalance\product\productAppService;
use TheBalance\utils\utilsFactory;

$titlePage = "Carrito";
$mainContent = "";
$alertMessage = '';

// Procesar acciones del carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Mensaje de alerta
    $alert = "";

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
                $alert = "danger|No hay suficiente stock disponible para {$productDTO->getName()} en talla {$size}. Stock disponible: {$stock}.";
            } elseif ($quantity <= 0) {
                $alert = "danger|La cantidad debe ser al menos 1.";
            } else {
                $_SESSION['cart'][$cartKey] = $quantity;
            }
        }
    }

    if (isset($_POST['remove_product'])) {
        $cartKey = $_POST['cart_key'];
        unset($_SESSION['cart'][$cartKey]);
        $alert = "success|Producto eliminado correctamente del carrito.";
    }

    // Generar las potenciales alertas
    if (!empty($alert)) {
        [$type, $message] = explode('|', $alert);
        $alertMessage = utilsFactory::createAlert($message, $type);
    }
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
        {$alertMessage}
    </div>

    <div class="row">
        <!-- Tabla del carrito -->
        <div class="col-md-8">
            {$cartTable->generateTable()}
        </div>

        <!-- Resumen del pedido -->
        <div class="col-md-4 mt-4 mt-md-0">
            {$orderSummary->generateContent()}
        </div>
    </div>
EOF;

require_once BASE_PATH.'/includes/views/template/template.php';