<?php

require_once __DIR__ . '/includes/config.php';

use TheBalance\product\productAppService;
use TheBalance\product\productDetailsContent;

$titlePage = "Detalles del Producto";
$mainContent = "";

// Obtenemos la ID del producto de la URL
$productId = $_GET['id'] ?? null;
if ($productId == null) {
    throw new \Exception("No se ha especificado el producto.");
}

// Obtenemos el producto correspondiente a la ID
$productAppService = productAppService::GetSingleton();
$productDTO = $productAppService->getProductById($productId);
if ($productDTO == null) {
    throw new \Exception("No se ha encontrado el producto con ese ID.");
}

// Procesar el formulario de añadir al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $addedProductId = $_POST['product_id'];

    // Inicializa el carrito si no existe
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Añade el producto al carrito
    if (!isset($_SESSION['cart'][$addedProductId])) {
        $_SESSION['cart'][$addedProductId] = 1; // Cantidad inicial
    } else {
        $_SESSION['cart'][$addedProductId]++; // Incrementa la cantidad si ya existe
    }

    // Guardamos en la sesión que se acaba de añadir un producto
    $_SESSION['show_offcanvas'] = true;

    // Redirigir a la misma página con el producto cargado para evitar reenvíos del formulario
    header("Location: productDetails.php?id=$addedProductId");
    exit();
}

// Generamos el contenido del producto
$productDetailsContent = new productDetailsContent($productDTO);
$mainContent .= $productDetailsContent->generateContent();

require_once __DIR__.'/includes/views/template/template.php';
