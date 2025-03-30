<?php

namespace TheBalance\order;

/**
 * Factoría de detalles de pedido
 */
class orderDetailFactory
{
    /**
     * Crea una instancia de IOrderDetail
     * 
     * @return IOrderDetail
     */
    public static function createOrderDetail() : IOrderDetail
    {
        $orderDetailDAO = false;
        $config = ""; // Podría leerse de configuración

        if ($config === "DAO") {
            $orderDetailDAO = new orderDetailDAO();
        } else {
            $orderDetailDAO = new orderDetailMock();
        }
        
        return $orderDetailDAO;
    }
}