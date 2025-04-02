<?php

namespace TheBalance\order;

/**
 * Factoría de orders
 */
class orderFactory
{
    /**
     * Crea un DAO de order
     * 
     * @return IOrder DAO de Order creado
     */
    public static function CreateOrder() : IOrder
    {
        $orderDAO = false;
        $config = "DAO";

        if ($config === "DAO")
        {
            $orderDAO = new orderDAO();
        }
        else
        {
            $orderDAO = new orderMock();
        }
        
        return $orderDAO;
    }
}
