<?php

namespace TheBalance\order;

use TheBalance\application;

/**
 * Clase que contiene la lógica de la aplicación para detalles de pedido
 */
class orderDetailAppService
{
    // Patrón Singleton
    /**
     * @var orderDetailAppService Instancia de la clase
     */
    private static $instance;

    /**
     * Devuelve una instancia de {@see orderDetailAppService}.
     * 
     * @return orderDetailAppService Obtiene la única instancia
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
     * Devuelve los detalles de un pedido con validación de permisos
     * 
     * @param int $orderId ID del pedido
     * @return array Resultado de la búsqueda
     * @throws \Exception Si no tiene permisos
     */
    public function getDetailsByOrderId($orderId)
    {
        $IOrderDetailDAO = orderDetailFactory::createOrderDetail();
        $detailsDTO = null;

        $app = application::getInstance();

        // Si es administrador, permitimos sin verificación adicional
        if ($app->isCurrentUserAdmin())
        {
            $detailsDTO = $IOrderDetailDAO->getDetailsByOrderId($orderId);
        }
        // Si es usuario normal, verificamos que el pedido sea suyo
        else
        {
            // Primero verificamos que el pedido pertenece al usuario
            $orderService = orderAppService::GetSingleton();
            $userOrders = $orderService->getOrdersByUserType();
            
            $orderExists = array_filter($userOrders, function($order) use ($orderId) {
                return $order->getId() == $orderId;
            });

            if (!empty($orderExists))
            {
                $detailsDTO = $IOrderDetailDAO->getDetailsByOrderId($orderId);
            }
            else
            {
                throw new \Exception("No tienes permisos para ver este pedido");
            }
        }

        return $detailsDTO;
    }

    /**
    * Crea un nuevo detalle de pedido
    * 
    * @param orderDetailDTO $orderDetailDTO Detalle del pedido a crear
    * @return bool Resultado de la creación
    */
    public function createOrderDetail($orderDetailDTO)
    {
        $IOrderDetailDAO = orderDetailFactory::createOrderDetail();
        try
        {
            $IOrderDetailDAO->createOrderDetail($orderDetailDTO);
        }
        catch (\Exception $e)
        {
            throw new \Exception("Error al crear el detalle del pedido: " . $e->getMessage());
        }
    }
}