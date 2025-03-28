<?php

namespace TheBalance\order;

/**
 * Interfaz para los orders
 */
interface IOrder
{


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

}