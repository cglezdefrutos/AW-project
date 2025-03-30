<?php

namespace TheBalance\order;

use TheBalance\application;

/**
 * Clase que contiene la lógica de la aplicación de orders
 */
class orderAppService
{
    // Patrón Singleton
    /**
     * @var orderAppService Instancia de la clase
     */
    private static $instance;

    /**
     * Devuelve una instancia de {@see orderAppService}.
     * 
     * @return orderAppService Obtiene la única instancia de la <code>orderAppService</code>
     */
    public static function GetSingleton()
    {
        if (!self::$instance instanceof self)
        {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Evita que se pueda instanciar la clase directamente.
     */
    private function __construct()
    {
    } 

    /**
     * Devuelve los orders asociados al tipo de usuario
     * 
     * @param string $user_type Tipo de usuario
     * 
     * @return array Resultado de la búsqueda
     */
    public function getOrdersByUserType()
    {
        $IOrderDAO = orderFactory::CreateOrder();
        $ordersDTO = null;

        $app = application::getInstance();

        // Si es administrador, tomamos todos los orders
        if ($app->isCurrentUserAdmin())
        {
            $ordersDTO = $IOrderDAO->getAllOrders();
        }
        // Si es cliente, tomamos SOLO los orders del cliente
        else
        {
            // Tomamos el id del cliente
            $userId = htmlspecialchars($app->getCurrentUserId());

            // Pasamos como filtro el id del cliente
            $ordersDTO = $IOrderDAO->getOrdersByUserId($userId);
        }

        return $ordersDTO;
    }

    /**
     * Elimina un pedido y sus detalles asociados
     *
     * @param int $orderId ID del pedido a eliminar
     * @return bool True si se eliminó correctamente, False si hubo un error
     */
    public function deleteOrderById($orderId)
    {
        $IOrderDAO = orderFactory::CreateOrder();
        $IOrderDetailDAO = orderDetailFactory::createOrderDetail();

        try {

            // Primero, eliminar los detalles del pedido
            $orderDetailsDeleted = $IOrderDetailDAO->deleteOrderDetailsByOrderId($orderId);

            // Luego, eliminar el pedido en sí
            $orderDeleted = $IOrderDAO->deleteOrder($orderId);

            // Si ambos se eliminaron correctamente, retornar true
            return $orderDetailsDeleted && $orderDeleted;

        } catch (\Exception $e) {
            error_log("Error eliminando el pedido con ID $orderId: " . $e->getMessage());
            return false;
        }
    }


}