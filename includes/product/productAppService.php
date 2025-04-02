<?php

namespace TheBalance\product;

use TheBalance\application;
use TheBalance\product\productHasOrdersException;
use TheBalance\product\notProductOwnerException;
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

    public function getProductsByUserType()
    {
        $IProductDAO = productFactory::CreateProduct();
        $productsDTO = null;

        $app = application::getInstance();

        // Si es administrador, tomamos todos los productos
        if ($app->isCurrentUserAdmin())
        {
            $productsDTO = $IProductDAO->getProducts();
        }
        // Si es proveedor, tomamos SOLO los productos del proveedor
        else 
        {
            // Tomamos el email del proveedor
            $userId = htmlspecialchars($app->getCurrentUserId());

            // Pasamos como filtro un array con el email (así solo traerá los productos donde coincida ese email)
            $productsDTO = $IProductDAO->getProducts(array("provider_id" => $userId));
        }

        return $productsDTO;
    }

    public function deleteProduct($productId)
    {
        $IProductDAO = productFactory::CreateProduct();

        // Si el producto tiene pedidos asociados, no se puede eliminar
        $orders = $IProductDAO->getOrdersByProduct($productId);

        // Tomamos la instancia de la aplicación
        $app = application::getInstance();

        // Si es administrador, se permite eliminar cualquier producto
        if ($app->isCurrentUserAdmin())
        {
            return $IProductDAO->deleteProduct($productId);
        }
        // Si es proveedor, solo puede eliminar sus productos
        else 
        {
            // Tomamos el email del proveedor
            $userEmail = htmlspecialchars($app->getCurrentUserEmail());

            // Comprobamos si el producto pertenece al proveedor
            $owner = $IProductDAO->ownsProduct($productId, $userEmail);

            if ($owner)
            {
                return $IProductDAO->deleteProduct($productId);
            }
            else
            {
                throw new notProductOwnerException("No puedes eliminar un producto que no te pertenece.");
            }
        }
    }

    public function registerProduct($productData)
    {
        $IProductDAO = productFactory::CreateProduct();

        $productDTO = new productDTO(0, $productData['provider_id'], $productData['name'], $productData['description'], $productData['price'], $productData['stock'], $productData['category_id'], $productData['category_name'], $productData['image_url'], $productData['created_at'], $productData['sizes']);

        return $IProductDAO->registerProduct($productDTO);
    }

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
}
    

