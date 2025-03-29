<?php

namespace TheBalance\order;

/**
 * Interfaz para detalles de pedidos
 */
interface IOrderDetail
{
    /**
     * Obtiene los detalles de un pedido
     * 
     * @param int $orderId ID del pedido
     * @return array de orderDetailDTO
     */
    public function getDetailsByOrderId($orderId);
}