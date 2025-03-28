<?php

namespace TheBalance\product;

/**
 * Factory de productos
 */
class productFactory
{
    /**
     * Crea un DAO de producto
     * 
     * @return IProduct DAO de Evento creado
     */
    public static function CreateProduct() : IProduct
    {
        $productDAO = false;
        $config = "Mock";

        if ($config === "DAO")
        {
            $productDAO = new productDAO();
        }
        else
        {
            $productDAO = new productMock();
        }

        return $productDAO;
    }
}