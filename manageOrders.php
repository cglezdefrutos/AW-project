<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\order\orderAppService;
use TheBalance\order\OrderWithUserDTO;
use TheBalance\order\manageOrderTable;
use TheBalance\application;
use TheBalance\utils\utilsFactory;

$titlePage = "Gestionar pedidos";
$mainContent = "";

$app = application::getInstance();

if(!$app->isCurrentUserLogged())
{
    $mainContent .= utilsFactory::createAlert("No has iniciado sesión. Por favor, inicia sesión para gestionar pedidos.", "danger");
}
else
{
    // Comprobar si el usuario es administrador
    if (!$app->isCurrentUserAdmin())
    { 
        $mainContent .= utilsFactory::createAlert("No tienes permisos para gestionar pedidos. Solo los administradores pueden hacerlo.", "danger");
    }
    else
    {
        // Obtenemos la instancia del servicio de pedidos
        $orderAppService = orderAppService::GetSingleton();

        // Manejar la eliminación del pedido si se proporciona un orderId en la URL
        if (isset($_GET['orderId'])) 
        {
            $orderId = $_GET['orderId'];
            $orderAppService->deleteOrderById($orderId);
            $mainContent .= utilsFactory::createAlert("Pedido eliminado correctamente.", "success");
        }

        $ordersData = $orderAppService->getAllOrdersWithEmail();

        // Convertir los datos en objetos DTO
        $orders = array_map(function($orderData) {
            return new OrderWithUserDTO(
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

        if (empty($orders)) 
        {
            $mainContent .= utilsFactory::createAlert("No hay pedidos disponibles.", "warning");
        } else 
        {
            $ordersTable = new manageOrderTable($orders, $columns);
            $html = $ordersTable->generateTable();

            $mainContent = <<<EOS
                <h1>Gestión de pedidos</h1>
                $html
            EOS;
        }    
    }
}

require_once BASE_PATH.'/includes/views/template/template.php';