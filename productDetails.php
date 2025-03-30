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
    throw new \Exception("No se ha encontrado el producto con ese ID.");
}

// Procesar el formulario de añadir al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) 
{

    // Si no es cliente, mostrar una alerta de que no se puede añadir al carrito
    if (!application::getInstance()->isCurrentUserClient()) 
    {
        // Mostrar un mensaje de error si no se pudo crear el pedido y un botón para volver al carrito
        $mainContent .= <<<EOS
            <div class="alert alert-danger d-flex flex-column flex-md-row align-items-center justify-content-between" role="alert">
                <div>
                    <strong>¡Atención!</strong> No puedes añadir productos al carrito porque no has iniciado sesión como cliente.
                </div>
                <a href="login.php" class="btn btn-primary mt-3 mt-md-0">Iniciar sesión</a>
            </div>
        EOS;
    } 
    else
    {
        // Tomamos los datos del formulario
        $addedProductId = $_POST['product_id'];
        $selectedSize = $_POST['product_size'];

        // Validar que se haya seleccionado una talla
        if (empty($selectedSize)) 
        {
            throw new \Exception("Debes seleccionar una talla antes de añadir el producto al carrito.");
        }

        // Inicializa el carrito si no existe
        if (!isset($_SESSION['cart'])) 
        {
            $_SESSION['cart'] = [];
        }

        // Generar una clave única para el producto y la talla
        $cartKey = "{$addedProductId}|{$selectedSize}";

        // Añade el producto al carrito o incrementa la cantidad si ya existe
        if (!isset($_SESSION['cart'][$cartKey])) 
        {
            $_SESSION['cart'][$cartKey] = 1;
        } else {
            $_SESSION['cart'][$cartKey]++;
        }

        // Guardamos en la sesión que se acaba de añadir un producto
        $_SESSION['show_offcanvas'] = true;

        // Redirigir a la misma página con el producto cargado para evitar reenvíos del formulario
        header("Location: productDetails.php?id=$addedProductId");
        exit();
    }
}

// Generamos el contenido del producto
$productDetailsContent = new productDetailsContent($productDTO);
$mainContent .= $productDetailsContent->generateContent();

require_once __DIR__.'/includes/views/template/template.php';
