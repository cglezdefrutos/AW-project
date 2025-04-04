<?php

namespace TheBalance\product;

/**
 * Clase que contiene la lógica de la aplicación de productos
 */
class productAppService
{
    // Patrón Singleton
    /**
     * @var productAppService Instancia de la clase
     */
    private static $instance;

    /**
     * Devuelve una instancia de {@see productAppService}.
     * 
     * @return productAppService Obtiene la única instancia de la <code>productAppService</code>
     */
    public static function GetSingleton()
    {
        if (!self::$instance instanceof self)
        {
            self::$instance = new self;
        }

        return self::$instance;
    }
    
    /**
     * Evita que se pueda instanciar la clase directamente.
     */
    private function __construct()
    {
        
    } 

    /**
     * Busca productos
     * 
     * @param array $filters Filtros de búsqueda
     * 
     * @return array Resultado de la búsqueda
     */
    public function searchProducts($filters)
    {
        $IProduct = productFactory::CreateProduct();

        // Creamos el array de productDTOs
        $productDTOs = $IProduct->searchProducts($filters);

        // Para cada producto, tomamos sus tallas
        foreach ($productDTOs as $productDTO) 
        {
            $productSizesDTO = $IProduct->getProductSizes($productDTO->getId());

            // Asignamos las tallas al producto
            $productDTO->setSizesDTO($productSizesDTO);
        }

        // Devolvemos el array de productDTOs
        return $productDTOs;
    }

    /**
     * Busca un producto por su ID
     * 
     * @param int $id ID del producto
     * 
     * @return productDTO Detalles del producto
     */
    public function getProductById($id)
    {
        $IProduct = productFactory::CreateProduct();

        $productDTO = $IProduct->getProductById($id);

        // Tomamos las tallas del producto
        $productSizesDTO = $IProduct->getProductSizes($id);

        $productDTO->setSizesDTO($productSizesDTO);

        // Retornamos el productDTO
        return $productDTO;
    }

    /**
     * Busca las categorías de productos
     * 
     * @return array Categorías de productos
     */
    public function getCategories()
    {
        $IProduct = productFactory::CreateProduct();

        return $IProduct->getCategories();
    }

    /**
     * Actualiza el stock de un producto
     * 
     * @param int $id ID del producto 
     * @param int $quantity Cantidad a actualizar
     * @param string $size Talla del producto
     * 
     * @return bool Resultado de la operación
     */
    public function updateProductStock($productId, $quantity, $size)
    {
        $IProduct = productFactory::CreateProduct();

        // Tomamos el id de la talla
        $sizeId = $IProduct->getSizeId($size);

        // Actualizamos el stock del producto
        $result = $IProduct->updateProductStock($productId, $quantity, $sizeId);

        return $result;
    }
}
    

