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
            new productDTO(1, 1, "Camiseta Nike", "Camiseta Nike para hacer deporte", 29.99, 10, 1, "Camisetas", "/AW-project/img/camiseta_nike.png", "2021-01-01", array("S", "M", "L", "XL")),
            new productDTO(2, 1, "Camiseta Adidas", "Camiseta Adidas para hacer deporte", 39.99, 20, 1, "Camisetas", "/AW-project/img/camiseta_nike.png", "2021-01-01", array("S", "M", "L", "XL")),
            new productDTO(3, 1, "Camiseta Puma", "Camiseta Puma para hacer deporte", 49.99, 30, 1, "Camisetas", "/AW-project/img/camiseta_nike.png", "2021-01-01", array("S", "M", "L", "XL")),
            new productDTO(4, 1, "Camiseta Reebok", "Camiseta Reebok para hacer deporte", 59.99, 40, 1, "Camisetas", "/AW-project/img/camiseta_nike.png", "2021-01-01", array("S", "M", "L", "XL")),
            new productDTO(5, 1, "Camiseta Under Armour", "Camiseta Under Armour para hacer deporte", 69.99, 50, 1, "Camisetas", "/AW-project/img/camiseta_nike.png", "2021-01-01", array("S", "M", "L", "XL")),
            new productDTO(6, 1, "Camiseta New Balance", "Camiseta New Balance para hacer deporte", 79.99, 60, 1, "Camisetas", "/AW-project/img/camiseta_nike.png", "2021-01-01", array("S", "M", "L", "XL")),
            new productDTO(7, 1, "Camiseta Asics", "Camiseta Asics para hacer deporte", 89.99, 70, 1, "Camisetas", "/AW-project/img/camiseta_nike.png", "2021-01-01", array("S", "M", "L", "XL")),
            new productDTO(8, 1, "Camiseta Mizuno", "Camiseta Mizuno para hacer deporte", 99.99, 80, 1, "Camisetas", "/AW-project/img/camiseta_nike.png", "2021-01-01", array("S", "M", "L", "XL")),
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
        return new productDTO($id, 1, "Camiseta Nike", "Camiseta Nike para hacer deporte", 29.99, 10, 1, "Camisetas", "/AW-project/img/camiseta_nike.png", "2021-01-01", array("S", "M", "L", "XL"));
    }

    /**
     * Get category name by ID
     * 
     * @param int $id
     * @return string Categoria del producto
     */
    public function getCategoryNameById($id)
    {
        return "Camisetas";
    }
}