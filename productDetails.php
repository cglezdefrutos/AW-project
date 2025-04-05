<?php

require_once __DIR__ . '/includes/config.php';

use TheBalance\product\productAppService;
use TheBalance\product\productDetailsContent;
use TheBalance\application;

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
if ($productDTO == null) 
{
    throw new \Exception("No se ha encontrado el producto.");
}

// Generamos el contenido del producto
$productDetailsContent = new productDetailsContent($productDTO);
$mainContent .= $productDetailsContent->generateContent();

require_once __DIR__.'/includes/views/template/template.php';
