<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\application;
use TheBalance\order\orderDTO;
use TheBalance\order\orderAppService;
use TheBalance\order\showOrderTable;

$titlePage = "Mis Pedidos";
$mainContent = "";

$app = application::getInstance();

if (!$app->isCurrentUserLogged()) {
    $mainContent = <<<EOS
        <h1>No es posible ver los pedidos si no has iniciado sesión.</h1>
    EOS;
} else if (!$app->isCurrentUserClient()) {
    $mainContent = <<<EOS
        <h1>No es posible ver los pedidos si no eres cliente.</h1>
    EOS;
} else {

    // Obtenemos la instancia del servicio de pedidos
    $orderAppService = orderAppService::GetSingleton();
    $ordersData = $orderAppService->getClientOrders();

    // Convertir los datos en objetos DTO
    $orders = array_map(function($orderData) {
        return new orderDTO(
            $orderData->getId(),
            $orderData->getUserId(),
            $orderData->getTotalPrice(),
            $orderData->getStatus(),
            $orderData->getShippingAddress(),
            $orderData->getCreatedAt()
        );
    }, $ordersData);

    // Definir las columnas de la tabla
    $columns = ['Dirección de Envío', 'Precio total', 'Estado', 'Fecha', 'Detalles'];

    if (empty($orders)) {
        $mainContent = "<h1>No tienes pedidos registrados.</h1>";
    } else {
        $ordersTable = new showOrderTable($orders, $columns);
        $html = $ordersTable->generateTable();

        $mainContent = <<<EOS
            <h1>Mis Pedidos</h1>
            $html
        EOS;
    }
}


require_once __DIR__.'/includes/views/template/template.php';