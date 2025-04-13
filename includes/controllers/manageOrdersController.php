<?php

require_once '../config.php';
use TheBalance\order\orderAppService;
use TheBalance\utils\utilsFactory;
use TheBalance\order\showOrderDetailTable;

$action = $_POST['action'] ?? null;

if ($action) {
    $orderAppService = orderAppService::GetSingleton();

    switch ($action) {
        case 'getOrders':
            $orders = $orderAppService->getAllOrders();
            if ($orders) {
                echo json_encode(['success' => true, 'data' => $orders]);
            } else {
                $alert = utilsFactory::createAlert('No se encontraron pedidos.', 'info');
                echo json_encode(['success' => false, 'alert' => $alert]);
            }
            break;

        case 'getOrderDetails':
            $orderId = $_POST['orderId'];
            $orderDetails = $orderAppService->getOrderDetailsById($orderId);

            if ($orderDetails) {
                $columns = ['Nombre Producto', 'Imagen Producto', 'Talla', 'Cantidad', 'Precio Unitario', 'Subtotal'];
                $table = new showOrderDetailTable($orderDetails, $columns);
                $tableContent = $table->generateTable();

                echo json_encode(['success' => true, 'data' => $tableContent]);
            } else {
                $alert = utilsFactory::createAlert('Detalles del pedido no encontrados.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
            }
            break;

        case 'updateOrderStatus':
            $orderId = $_POST['orderId'];
            $status = $_POST['status'];

            $updated = $orderAppService->updateOrderStatus($orderId, $status);

            if ($updated) {
                $alert = utilsFactory::createAlert('Estado del pedido actualizado correctamente.', 'success');
                echo json_encode(['success' => true, 'alert' => $alert]);
            } else {
                $alert = utilsFactory::createAlert('Error al actualizar el estado del pedido.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
            }
            break;

        case 'deleteOrder':
            $orderId = $_POST['orderId'];
            $deleted = $orderAppService->deleteOrderById($orderId);

            if ($deleted) {
                $alert = utilsFactory::createAlert('Pedido eliminado correctamente.', 'success');
                echo json_encode(['success' => true, 'alert' => $alert]);
            } else {
                $alert = utilsFactory::createAlert('Error al eliminar el pedido.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
            }
            break;

        default:
            $alert = utilsFactory::createAlert('Acción no válida.', 'danger');
            echo json_encode(['success' => false, 'alert' => $alert]);
            break;
    }
}