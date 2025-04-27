<?php

require_once '../config.php';

use TheBalance\application;
use TheBalance\order\orderDTO;
use TheBalance\order\orderAppService;
use TheBalance\order\showOrderTable;
use TheBalance\utils\utilsFactory;
use TheBalance\order\orderModal;

$titlePage = "Mis Pedidos";
$mainContent = "";

$app = application::getInstance();

if (!$app->isCurrentUserLogged()) 
{
    echo utilsFactory::createAlert("No has iniciado sesión. Por favor, inicia sesión para ver tus pedidos.", "danger");
} 
else if (!$app->isCurrentUserClient()) 
{
    echo utilsFactory::createAlert("No tienes permisos para ver pedidos. Solo los clientes pueden hacerlo.", "danger");
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
    $ordersTable = new showOrderTable($orders, $columns);
    $html = $ordersTable->generateTable();

    echo <<<EOS
        <h2>Mis Pedidos</h2>
        $html
    EOS;

    // Agregar el modal al contenido
    echo orderModal::generateDetailsModal(); 
}