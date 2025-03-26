<?php

namespace TheBalance\order;

/**
 * Interfaz para los orders
 */
interface IOrder
{

    /**
     * Optiene los Orders de un usuario
     * 
     * @param int id del usuario
     * @return array de orders del usuario
     */
    public function getOrdersByUserId($userId);

    /**
     * Actualiza un order
     * 
     * @param array $orderDTO Datos del order
     * 
     * @return bool Resultado de la operación
     */
    public function updateOrder($orderDTO);
    
    /**
     * Elimina un order
     * 
     * @param int $orderId Id del order
     * 
     * @return bool Resultado de la operación
     */
    public function deleteOrder($orderId);

}