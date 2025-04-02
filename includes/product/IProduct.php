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

    /**
     * Busca un producto por su ID
     * 
     * @param int $id ID del producto
     * 
     * @return productDTO Producto encontrado
     */
    public function getProductById($id);

    /**
     * Busca las tallas de un producto por su ID
     * 
     * @param int $id ID del producto
     * 
     * @return array Tallas del producto
     */
    public function getProductSizes($productId);

    /**
     * Busca las categorías de productos
     * 
     * @return array Categorías de productos
     */
    public function getCategories();
}