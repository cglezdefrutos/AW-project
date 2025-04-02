<?php

namespace TheBalance\order;

/**
 * @file orderMock.php
 * @brief Mock de la clase orderDAO para simular el acceso a la base de datos.
 * 
 * Este archivo contiene una implementación simulada de la clase orderDAO, que se utiliza para
 * interactuar con la base de datos de pedidos. Esta implementación es útil para pruebas y desarrollo,
 * ya que permite simular el comportamiento de la base de datos sin necesidad de una conexión real.
 * 
 * @author TheBalance Team
 * @date 2023-10-01
 */
class orderMock implements IOrder
{
    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * Simula devolver el pedido asociado a ese id
     * 
     * @param string $orderId ID del pedido
     * 
     * @return orderDTO Resultado de la búsqueda
     */
    public function getOrderById($orderId)
    {
        return new orderDTO($orderId, 1, 100.00, "En preparación", date('Y-m-d H:i:s')); 
    }

    /**
     * Obtiene todos los Orders
     * 
     * @param 
     * @return array de orders
     */
    public function getAllOrders() 
    {
        $orders = array(
            new orderDTO(1, 1, 100.00, "En preparación", date('Y-m-d H:i:s')),
            new orderDTO(2, 2, 200.00, "Enviado", date('Y-m-d H:i:s')),
        );
        return $orders;
    }

    /**
     * Optiene los Orders de un usuario
     * 
     * @param int id del usuario
     * @return array de orders del usuario
     */
    public function getOrdersByUserId($userId)
    {
        $orders = array(
            new orderDTO(1, $userId, 100.00, "En preparación", date('Y-m-d H:i:s')),
            new orderDTO(2, $userId, 200.00, "Enviado", date('Y-m-d H:i:s')),
        );
        return $orders;
    }

    /**
     * Actualiza un pedido
     * 
     * @param orderDTO $order el dto del pedido a eliminar
     * @return bool True si se eliminó correctamente
     */
    public function updateOrder($order)
    {
        return true;
    }

    /**
     * Elimina un pedido por su ID
     * 
     * @param int $orderId ID del pedido a eliminar
     * @return bool True si se eliminó correctamente, False si falló
     */
    public function deleteOrder($orderId)
    {
        return true;
    }

    /**
     * Simula la creación de un pedido en la base de datos.
     * 
     * @param orderDTO $order Objeto que contiene los datos del pedido a crear.
     * 
     * @return int ID del nuevo pedido creado.
     */
    public function createOrder($order)
    {
        return 1;
    }
    
}