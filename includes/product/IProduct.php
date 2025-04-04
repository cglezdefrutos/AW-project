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
     * Desactiva productos por su ID
     * 
     * @param int $id ID del producto
     * 
     * @return productDTO Producto encontrado
     */
    public function deleteProduct($productId);

    /**
     * Busca el proveedor de productos por su ID y el email del proveedor
     * 
     * @param int $id ID del producto, $userEmail Email del proveedor
     * 
     * @return productDTO Producto encontrado
     */
    public function ownsProduct($productId, $userEmail);


    /**
     * Registra un producto
     * 
     * @param productDTO $productDTO Objeto que contiene los datos del producto
     * 
     * @return bool Resultado de la operación
     */
    public function registerProduct($productDTO, $providerId);

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

    /**
     * Busca la categoría de un producto por su ID
     * 
     * @param int $id ID de la categoría
     * 
     * @return productCategoryDTO Categoría encontrada
     */
    public function getCategoryId($id);

    /**
     * registra la categoría de un producto por su nombre
     * 
     * @param string $categoryName Nombre de la categoría
     * 
     * @return productCategoryDTO Categoría encontrada
     */
    public function registerCategory($categoryName);
}