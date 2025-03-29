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
        $productId = $_POST['product_id'];
        $quantity = max(1, (int)$_POST['quantity']);
        $_SESSION['cart'][$productId] = $quantity;
    }

    if (isset($_POST['remove_product'])) {
        $productId = $_POST['product_id'];
        unset($_SESSION['cart'][$productId]);
    }
}

// Generar contenido del carrito
$cart = $_SESSION['cart'] ?? [];
$columns = ['Imagen', 'Producto', 'Precio', 'Cantidad', 'Subtotal', 'Acciones'];
$cartTable = new cartTable($cart, $columns);

// Calcular el total del carrito
$total = 0;
foreach ($cart as $productId => $quantity) {
    $product = productAppService::GetSingleton()->getProductById($productId);
    if ($product) {
        $total += $product->getPrice() * $quantity;
    }
}

// Generar el resumen del pedido
$orderSummary = new orderSummary($total);

// Generar el contenido principal
$mainContent = <<<EOF
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