<?php

namespace TheBalance\order;

/*
 * @file orderDetailMock.php
 * @brief Mock de la clase orderDetailDAO para simular el acceso a la base de datos.
 * 
 * Este archivo contiene una implementación simulada de la clase orderDetailDAO, que se utiliza para
 * interactuar con la base de datos de detalles de pedidos. Esta implementación es útil para pruebas y desarrollo,
 * ya que permite simular el comportamiento de la base de datos sin necesidad de una conexión real.
 * 
 * @author TheBalance Team
 * @date 2023-10-01
 */
class orderDetailMock implements IOrderDetail
{
    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * Obtiene los detalles de un pedido
     * 
     * @param int $orderId ID del pedido
     * @return array de orderDetailDTO
     */
    public function getDetailsByOrderId($orderId)
    {
        $orderDetailDTO = new orderDetailDTO($orderId, "Producto Simulado", 2, 19.99);
        return [$orderDetailDTO]; // Retornamos un array con un solo detalle simulado
    }

    /**
     * Elimina todos los detalles de un pedido por su order_id
     * 
     * @param int $orderId ID del pedido cuyos detalles serán eliminados
     * @return bool True si se eliminaron correctamente, False si falló
     */
    public function deleteOrderDetailsByOrderId($orderId)
    {
        return true;
    }
    
    /**
     * Simula la creación de un detalle de pedido en la base de datos.
     * 
     * @param orderDetailDTO $orderDetail Objeto que contiene los datos del detalle de pedido a crear.
     * 
     * @return bool Resultado de la operación.
     */
    public function createOrderDetail($orderDetail)
    {
        return true; // Simulamos que se crea el detalle de pedido correctamente con ID 1
    }   
}