<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\application;
use TheBalance\order\orderDetailDTO;
use TheBalance\order\orderAppService;
use TheBalance\order\showOrderDetailTable;
use TheBalance\utils\utilsFactory;

$titlePage = "Detalles del Pedido";
$mainContent = "";

$app = application::getInstance();

if (!$app->isCurrentUserLogged()) 
{
    $mainContent .= utilsFactory::createAlert("No has iniciado sesión. Por favor, inicia sesión para ver los detalles del pedido.", "danger");
} 
else if (!$app->isCurrentUserClient() && !$app->isCurrentUserAdmin()) 
{
    $mainContent .= utilsFactory::createAlert("No tienes permisos para ver los detalles del pedido. Solo los clientes y administradores pueden hacerlo.", "danger");
} 
else 
{
    $orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    $orderAppService = orderAppService::GetSingleton();
    $detailsData = $orderAppService->getDetailsByOrderId($orderId);

    $order = $orderAppService->getOrderById($orderId);

    if ($orderId <= 0) {
        $mainContent .= utilsFactory::createAlert("No se ha proporcionado un ID de pedido válido.", "danger");
    } 
    else if ($app->isCurrentUserClient() && ($order->getUserId() != $app->getCurrentUserId())) 
    {
            $mainContent .= utilsFactory::createAlert("No tienes permisos para ver los detalles de este pedido. Solo puedes ver tus propios pedidos.", "danger");
    }
    else 
    {
        // Convertir los datos en objetos DTO
        $details = array_map(function($detailData) {
            return new orderDetailDTO(
                $detailData->getOrderId(),
                $detailData->getProductId(),
                $detailData->getImageGuid(),
                $detailData->getQuantity(),
                $detailData->getPrice(),
                $detailData->getSize()
            );
        }, $detailsData);

        $columns = ['Nombre Producto', 'Imagen Producto', 'Talla', 'Cantidad', 'Precio Unitario', 'Subtotal'];

        if (empty($details)) 
        {
            $mainContent .= utilsFactory::createAlert("No hay detalles disponibles para este pedido.", "info");
        } 
        else 
        {
            $detailsTable = new showOrderDetailTable($details, $columns);
            $html = $detailsTable->generateTable();

            $mainContent = <<<EOS
                <h1>Detalles del Pedido</h1>
                $html
                <button onclick="window.history.back()" class="btn btn-secondary">
                    ← Volver a Mis Pedidos
                </button>
            EOS;
        }
    }
}

require_once BASE_PATH.'/includes/views/template/template.php';