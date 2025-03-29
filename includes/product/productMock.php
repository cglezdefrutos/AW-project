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
            new productDTO(1, 1, "Camiseta Nike", "Camiseta Nike para hacer deporte", 29.99, 10, 1, "/AW-project/img/camiseta_nike.png", "2021-01-01"),
            new productDTO(2, 1, "Producto 2", "Descripción del producto 2", 200.0, 20, 1, "/AW-project/img/logo_thebalance.png", "2021-01-01"),
            new productDTO(3, 1, "Producto 3", "Descripción del producto 3", 300.0, 30, 1, "/AW-project/img/logo_thebalance.png", "2021-01-01"),
            new productDTO(4, 1, "Producto 4", "Descripción del producto 4", 400.0, 40, 1, "/AW-project/img/logo_thebalance.png", "2021-01-01"),
            new productDTO(5, 1, "Producto 5", "Descripción del producto 5", 500.0, 50, 1, "/AW-project/img/logo_thebalance.png", "2021-01-01"),
            new productDTO(6, 1, "Producto 6", "Descripción del producto 6", 600.0, 60, 1, "/AW-project/img/logo_thebalance.png", "2021-01-01"),
            new productDTO(7, 1, "Producto 7", "Descripción del producto 7", 700.0, 70, 1, "/AW-project/img/logo_thebalance.png", "2021-01-01"),
            new productDTO(8, 1, "Producto 8", "Descripción del producto 8", 800.0, 80, 1, "/AW-project/img/logo_thebalance.png", "2021-01-01"),
            new productDTO(9, 1, "Producto 9", "Descripción del producto 9", 900.0, 90, 1, "/AW-project/img/logo_thebalance.png", "2021-01-01"),
            new productDTO(10, 1, "Producto 10", "Descripción del producto 10", 1000.0, 100, 1, "/AW-project/img/logo_thebalance.png", "2021-01-01")
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
        return new productDTO($id, 1, "Camiseta Nike", "Camiseta Nike para hacer deporte", 29.99, 10, 1, "/AW-project/img/camiseta_nike.png", "2021-01-01");
    }
}