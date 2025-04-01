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
     * Devuelve el pedido asociado a ese id
     * 
     * @param string $orderId ID del pedido
     * 
     * @return orderDTO Resultado de la búsqueda
     */
    public function getOrderById($orderId)
    {
        $IOrderDAO = orderFactory::CreateOrder();

        return $IOrderDAO->getOrderById($orderId);
    }

    /**
     * Devuelve los orders asociados al tipo de usuario
     * 
     * @param string $user_type Tipo de usuario
     * 
     * @return array Resultado de la búsqueda
     */
    public function getAllOrdersWithEmail()
    {
        $IOrderDAO = orderFactory::CreateOrder();
        $ordersWithEmailDTO = null;

        $app = application::getInstance();

        $ordersWithEmailDTO = $IOrderDAO->getAllOrdersWithEmail();

        return $ordersWithEmailDTO;
    }

    /**
     * Devuelve los orders asociados al tipo de usuario
     * 
     * @param string $user_type Tipo de usuario
     * 
     * @return array Resultado de la búsqueda
     */
    public function getClientOrders()
    {
        $IOrderDAO = orderFactory::CreateOrder();
        $ordersDTO = null;

        $app = application::getInstance();

        // Tomamos el id del cliente
        $userId = htmlspecialchars($app->getCurrentUserId());

        // Pasamos como filtro el id del cliente
        $ordersDTO = $IOrderDAO->getOrdersByUserId($userId);

        return $ordersDTO;
    }

    public function updateOrder($order)
    {
        $IOrderDAO = orderFactory::CreateOrder();

        try {

        // Intentar actualizar el pedido en la base de datos
        return $IOrderDAO->updateOrder($order);

        } catch (\Exception $e) {
            error_log("Error actualizando el pedido: " . $e->getMessage());
            return false;
        }

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

    /**
     * Devuelve los detalles de un pedido
     *
     * @param int $orderId ID del pedido
     * @return orderDetailDTO[] Array de objetos orderDetailDTO
     * @throws \Exception Si ocurre un error
     */
    public function getDetailsByOrderId($orderId)
    {
        $IOrderDetailDAO = orderDetailFactory::createOrderDetail();
        $detailsDTO = null;


        $app = application::getInstance();


        try {
            $detailsDTO = $IOrderDetailDAO->getDetailsByOrderId($orderId);
        } catch (\Exception $e) {
            error_log("Error obteniendo detalles del pedido ID $orderId: " . $e->getMessage());
            throw $e;
        }


        return $detailsDTO;
    }

}