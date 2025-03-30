<?php

namespace TheBalance\order;

/**
 * Interfaz para los orders
 */
interface IOrder
{

    /**
     * Devuelve el pedido asociado a ese id
     * 
     * @param string $orderId ID del pedido
     * 
     * @return orderDAO Resultado de la búsqueda
     */
    public function getOrderById($orderId);

    /**
     * Optiene todos los Orders
     * 
     * @param 
     * @return array de orders
     */
    public function getAllOrders();

    /**
     * Optiene los Orders de un usuario
     * 
     * @param int id del usuario
     * @return array de orders del usuario
     */
    public function getOrdersByUserId($userId);

    /**
     * Actualiza un pedido
     * 
     * @param orderDTO $order el dto del pedido a eliminar
     * @return bool True si se eliminó correctamente
     */
    public function updateOrder($order);

    /**
     * Elimina un pedido por su ID
     * 
     * @param int $orderId ID del pedido a eliminar
     * @return bool True si se eliminó correctamente, False si falló
     */
    public function deleteOrder($orderId);

}