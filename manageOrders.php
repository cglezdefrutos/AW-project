<?php

require_once __DIR__.'/includes/config.php';

use TheBalance\order\orderAppService;
use TheBalance\order\OrderWithUserDTO;
use TheBalance\order\manageOrderTable;
use TheBalance\application;

$titlePage = "Gestionar pedidos";
$mainContent = "";

$app = application::getInstance();


if(!$app->isCurrentUserLogged())
{
    $mainContent = <<<EOS
        <h1>No es posible gestionar pedidos si no has iniciado sesión.</h1>
    EOS;
}
else
{
    // Comprobar si el usuario es administrador
    if (! $app->isCurrentUserAdmin() )
    { 
        $mainContent = <<<EOS
            <h1>No es posible gestionar pedidos si no se es administrador.</h1>
        EOS;
    }
    else
    {
        // Obtenemos la instancia del servicio de pedidos
        $orderAppService = orderAppService::GetSingleton();

        // Manejar la eliminación del pedido si se proporciona un orderId en la URL
        if (isset($_GET['orderId'])) {
            $orderId = $_GET['orderId'];
            $orderAppService->deleteOrderById($orderId);
            $mainContent .= <<<EOS
                <div class="alert-success">
                    Pedido eliminado correctamente.
                </div>
            EOS;
        }

        $ordersData = $orderAppService->getAllOrdersWithEmail();

        // Convertir los datos en objetos DTO
        $orders = array_map(function($orderData) {
            return new OrderWithUserDTO(
                $orderData->getId(),
                $orderData->getUserId(),
                $orderData->getEmail(),
                $orderData->getAddressId(),
                $orderData->getTotalPrice(),
                $orderData->getStatus(),
                $orderData->getCreatedAt()
            );
        }, $ordersData);

        // Definir las columnas de la tabla
        $columns = ['Email del Usuario', 'Precio total', 'Estado', 'Fecha', 'Acciones'];

        if (empty($orders)) {
            $mainContent = "<h1>No hay pedidos registrados.</h1>";
        } else {
            $ordersTable = new manageOrderTable($orders, $columns);
            $html = $ordersTable->generateTable();

            $mainContent = <<<EOS
                <h1>Gestión de pedidos</h1>
                $html
            EOS;
        }    
    }
}

require_once __DIR__.'/includes/views/template/template.php';