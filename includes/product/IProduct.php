<?php

namespace TheBalance\product;

/**
 * Interfaz de productos
 */
interface IProduct
{
    /**
     * Busca productos
     * 
     * @param array $filters Filtros de búsqueda
     * 
     * @return array Resultado de la búsqueda
     */
    public function searchProducts($filters);
}