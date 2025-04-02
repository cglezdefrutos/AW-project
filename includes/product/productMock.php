<?php

namespace TheBalance\product;

/**
 * Mock de producto
 */
class productMock implements IProduct
{
    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * Search products
     * 
     * @param array $filters
     * @return array
     */
    public function searchProducts($filters)
    {
        // Return a list of DTOProducts
        return array(
            new productDTO(1, "nike@gmail.com", "Camiseta Nike", "Camiseta Nike para hacer deporte", 29.99,  new productCategoryDTO(1, "Camisetas"), "/AW-project/img/camiseta_nike.png", "2021-01-01", null, 1),
            new productDTO(2, "adidas@gmail.com", "Camiseta Adidas", "Camiseta Adidas para hacer deporte", 39.99, new productCategoryDTO(1, "Camisetas"), "/AW-project/img/camiseta_nike.png", "2021-01-01", null, 1),
            new productDTO(3, "puma@gmail.com", "Camiseta Puma", "Camiseta Puma para hacer deporte", 24.99, new productCategoryDTO(1, "Camisetas"), "/AW-project/img/camiseta_nike.png", "2021-01-01", null, 1),
            new productDTO(4, "reebok@gmail.com", "Camiseta Reebok", "Camiseta Reebok para hacer deporte", 34.99, new productCategoryDTO(1, "Camisetas"), "/AW-project/img/camiseta_nike.png", "2021-01-01", null, 1),
            new productDTO(5, "underarmour@gmail.com", "Camiseta Under Armour", "Camiseta Under Armour para hacer deporte", 44.99, new productCategoryDTO(1, "Camisetas"), "/AW-project/img/camiseta_nike.png", "2021-01-01", null, 1)
        );
    }

    /**
     * Get product by ID
     * 
     * @param int $id
     * @return productDTO
     */
    public function getProductById($id)
    {
        // Return a product DTO
        return new productDTO(1, "nike@gmail.com", "Camiseta Nike", "Camiseta Nike para hacer deporte", 29.99, new productCategoryDTO(1, "Camisetas"), "/AW-project/img/camiseta_nike.png", "2021-01-01", null, 1);
    }

    /**
     * Busca las tallas de un producto por su ID
     * 
     * @param int $id ID del producto
     * 
     * @return productSizesDTO Tallas del producto
     */
    public function getProductSizes($productId)
    {
        // Retorna un objeto productSizesDTO con las tallas del producto
        return new productSizesDTO($productId, array("S" => 0, "M" => 3, "L" => 4, "XL" => 5));
    }

    /**
     * Busca las categorías de productos
     * 
     * @return array Categorías de productos
     */
    public function getCategories()
    {
        return array(
            new productCategoryDTO(1, "Camisetas"),
            new productCategoryDTO(2, "Pantalones"),
            new productCategoryDTO(3, "Zapatillas"),
            new productCategoryDTO(4, "Accesorios"),
        );
    }
}