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

    /**
     * Elimina todos los detalles de un pedido por su order_id
     * 
     * @param int $orderId ID del pedido cuyos detalles serán eliminados
     * @return bool True si se eliminaron correctamente, False si falló
     */
    public function deleteOrderDetailsByOrderId($orderId);

    /**
    * Crea un nuevo detalle de pedido
    * 
    * @param orderDetailDTO $orderDetailDTO Objeto que contiene los datos del detalle de pedido a crear
    * @return bool resultado de la operación
    */
    public function createOrderDetail($orderDetailDTO);
}