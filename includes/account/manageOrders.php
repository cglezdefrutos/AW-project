<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\order\orderAppService;
use TheBalance\order\orderWithUserDTO;
use TheBalance\order\manageOrderTable;
use TheBalance\application;
use TheBalance\utils\utilsFactory;
use TheBalance\order\orderModal;

$titlePage = "Gestionar pedidos";
$mainContent = "";

$app = application::getInstance();

if(!$app->isCurrentUserLogged())
{
    echo utilsFactory::createAlert("No has iniciado sesión. Por favor, inicia sesión para gestionar pedidos.", "danger");
}
else
{
    // Comprobar si el usuario es administrador
    if (!$app->isCurrentUserAdmin())
    { 
        echo utilsFactory::createAlert("No tienes permisos para gestionar pedidos. Solo los administradores pueden hacerlo.", "danger");
    }
    else
    {
        // Obtenemos la instancia del servicio de pedidos
        $orderAppService = orderAppService::GetSingleton();

        $ordersData = $orderAppService->getAllOrdersWithEmail();

        // Convertir los datos en objetos DTO
        $orders = array_map(function($orderData) {
            return new orderWithUserDTO(
                $orderData->getId(),
                $orderData->getUserId(),
                $orderData->getEmail(),
                $orderData->getTotalPrice(),
                $orderData->getStatus(),
                $orderData->getShippingAddress(),
                $orderData->getCreatedAt()
            );
        }, $ordersData);

        // Definir las columnas de la tabla
        $columns = ['Email del Usuario', 'Dirección de Envío','Precio total', 'Estado', 'Fecha', 'Acciones'];
        $ordersTable = new manageOrderTable($orders, $columns);
        $html = $ordersTable->generateTable();

        echo <<<EOS
            <h1>Gestión de pedidos</h1>
            $html
        EOS;

        // Agregar el modal al contenido
        echo orderModal::generateEditModal();
        echo orderModal::generateDetailsModal();
    }
}