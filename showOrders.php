<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\application;
use TheBalance\order\orderDTO;
use TheBalance\order\orderAppService;
use TheBalance\order\showOrderTable;
use TheBalance\utils\utilsFactory;

$titlePage = "Mis Pedidos";
$mainContent = "";

$app = application::getInstance();

if (!$app->isCurrentUserLogged()) 
{
    $mainContent .= utilsFactory::createAlert("No has iniciado sesión. Por favor, inicia sesión para ver tus pedidos.", "danger");
} 
else if (!$app->isCurrentUserClient()) 
{
    $mainContent .= utilsFactory::createAlert("No tienes permisos para ver pedidos. Solo los clientes pueden hacerlo.", "danger");
} 
else 
{
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

    if (empty($orders)) 
    {
        $mainContent .= utilsFactory::createAlert("No tienes pedidos realizados.", "info");
    } 
    else 
    {
        $ordersTable = new showOrderTable($orders, $columns);
        $html = $ordersTable->generateTable();

        $mainContent = <<<EOS
            <h1>Mis Pedidos</h1>
            $html
        EOS;
    }
}


require_once __DIR__.'/includes/views/template/template.php';