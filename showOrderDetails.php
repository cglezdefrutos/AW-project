<?php


require_once __DIR__.'/includes/config.php';


use TheBalance\application;
use TheBalance\order\orderDetailDTO;
use TheBalance\order\orderAppService;
use TheBalance\order\showOrderDetailTable;


$titlePage = "Detalles del Pedido";
$mainContent = "";


$app = application::getInstance();


if (!$app->isCurrentUserLogged()) {
    $mainContent = <<<EOS
        <h1>No es posible ver los detalles del pedido si no has iniciado sesión.</h1>
    EOS;
} else if (!$app->isCurrentUserClient() && !$app->isCurrentUserAdmin()) {
    $mainContent = <<<EOS
        <h1>No es posible ver los detalles del pedido si no eres cliente o admin.</h1>
    EOS;
} else {
    $orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;


    if ($orderId <= 0) {
        $mainContent = <<<EOS
            <h1>Pedido no especificado o inválido.</h1>
        EOS;
    } else {
        $orderAppService = orderAppService::GetSingleton();
        $detailsData = $orderAppService->getDetailsByOrderId($orderId);




        // Convertir los datos en objetos DTO
        $details = array_map(function($detailData) {
            return new orderDetailDTO(
                $detailData->getOrderId(),
                $detailData->getProductName(),
                $detailData->getImageUrl(),
                $detailData->getQuantity(),
                $detailData->getPrice(),
                $detailData->getSize()
            );
        }, $detailsData);


        $columns = ['Nombre Producto', 'Imagen Producto', 'Talla', 'Cantidad', 'Precio Unitario', 'Subtotal'];


        if (empty($details)) {
            $mainContent = "<h1>No se encontraron detalles para este pedido.</h1>";
        } else {
            $detailsTable = new showOrderDetailTable($details, $columns);
            $html = $detailsTable->generateTable();


            $mainContent = <<<EOS
                <h1>Detalles del Pedido</h1>
                $html


                <button onclick="window.history.back()" class="btn btn-secondary">
                ← Volver atrás
                </button>
            EOS;
        }
    }
}


require_once __DIR__.'/includes/views/template/template.php';