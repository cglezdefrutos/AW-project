<?php

require_once '../config.php';
use TheBalance\order\orderAppService;
use TheBalance\utils\utilsFactory;
use TheBalance\order\showOrderDetailTable;
use TheBalance\order\orderDTO;

$action = $_POST['action'] ?? null;

if ($action) {
    $orderAppService = orderAppService::GetSingleton();

    switch ($action) {
        case 'getOrder':
            $orderId = $_POST['orderId'];
            $order = $orderAppService->getOrderById($orderId);
            if ($order) {
                // Devolver los datos del pedido como JSON
                echo json_encode([
                    'success' => true,
                    'data' => [
                        'id' => $order->getId(),
                        'userId' => $order->getUserId(),
                        'totalPrice' => $order->getTotalPrice(),
                        'shippingAddress' => $order->getShippingAddress(),
                        'createdAt' => $order->getCreatedAt(),
                        'status' => $order->getStatus(), // Incluir el estado actual
                    ]
                ]);
            } else {
                $alert = utilsFactory::createAlert('Pedido no encontrado.', 'danger');
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
            $orderId = trim($_POST['orderId'] ?? '');
            $orderId = filter_var($orderId, FILTER_SANITIZE_NUMBER_INT);
            if (!is_numeric($orderId) || $orderId <= 0) {
                $alert = utilsFactory::createAlert('ID de pedido inválido.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }

            $status = trim($_POST['status'] ?? '');
            $status = filter_var($status, FILTER_SANITIZE_STRING);
            $validStatuses = ['En preparación', 'Enviado', 'Entregado', 'Cancelado'];
            if (!in_array($status, $validStatuses)) {
                $alert = utilsFactory::createAlert('Estado seleccionado no válido.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }

            // Obetener el resto de datos hidden del formulario
            $userId = trim($_POST['userId'] ?? '');
            $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
            if (!is_numeric($userId) || $userId <= 0) {
                $alert = utilsFactory::createAlert('ID de usuario inválido.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }

            $totalPrice = trim($_POST['totalPrice'] ?? '');
            $totalPrice = filter_var($totalPrice, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            if (!is_numeric($totalPrice) || $totalPrice < 0) {
                $alert = utilsFactory::createAlert('Precio total inválido.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }

            $shippingAddress = trim($_POST['shippingAddress'] ?? '');
            $shippingAddress = filter_var($shippingAddress, FILTER_SANITIZE_STRING);
            if (empty($shippingAddress)) {
                $alert = utilsFactory::createAlert('Dirección de envío inválida.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }

            $createdAt = trim($_POST['createdAt'] ?? '');
            $createdAt = filter_var($createdAt, FILTER_SANITIZE_STRING);
            if (empty($createdAt)) {
                $alert = utilsFactory::createAlert('Fecha de creación inválida.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }

            // Crear el DTO actualizado
            $updatedOrder = new orderDTO(
                $orderId,
                $userId,
                $totalPrice,
                $status,
                $shippingAddress,
                $createdAt
            );

            $updated = $orderAppService->updateOrder($updatedOrder);

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
            $orderId = filter_var($orderId, FILTER_SANITIZE_NUMBER_INT);
            if (!is_numeric($orderId) || $orderId <= 0) {
                $alert = utilsFactory::createAlert('ID de pedido inválido.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }

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