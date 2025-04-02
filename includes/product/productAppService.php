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

    /**
     * Busca productos por su ID
     * 
     * @param int $id ID del producto
     * 
     * @return productDTO Producto encontrado
     */

    public function getProductsByUserType()
    {
        $IProductDAO = productFactory::CreateProduct();
        $productsDTOs = null;

        $app = application::getInstance();

        // Si es administrador, tomamos todos los productos
        if ($app->isCurrentUserAdmin())
        {
            $productsDTOs = $IProductDAO->searchProducts();
        }
        // Si es proveedor, tomamos SOLO los productos del proveedor
        else 
        {
            // Tomamos el id del proveedor
            $userId = htmlspecialchars($app->getCurrentUserId());

            // Pasamos como filtro un array con el email (así solo traerá los productos donde coincida ese email)
            $productsDTOs = $IProductDAO->searchProducts(array("provider_id" => $userId));
        }

        // Para cada producto, tomamos sus tallas
        foreach ($productsDTOs as $productDTO) 
        {
            $productSizesDTO = $IProductDAO->getProductSizes($productDTO->getId());

            // Asignamos las tallas al producto
            $productDTO->setSizesDTO($productSizesDTO);
        }

        return $productsDTOs;
    }

    /**
     * Desactiva productos por su ID
     * 
     * @param int $id ID del producto
     * 
     * @return productDTO Producto encontrado
     */

    public function deleteProduct($productId)
    {
        $IProductDAO = productFactory::CreateProduct();

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

    /**
     * Activa productos por su ID
     * 
     * @param int $id ID del producto
     * 
     * @return productDTO Producto encontrado
     */
    public function activateProduct($productId)
    {
        $IProductDAO = productFactory::CreateProduct();

        // Tomamos la instancia de la aplicación
        $app = application::getInstance();

        // Si es administrador, se permite activar cualquier producto
        if ($app->isCurrentUserAdmin())
        {
            return $IProductDAO->activateProduct($productId);
        }
        // Si es proveedor, solo puede activar sus productos
        else 
        {
            // Tomamos el email del proveedor
            $userEmail = htmlspecialchars($app->getCurrentUserEmail());

            // Comprobamos si el producto pertenece al proveedor
            $owner = $IProductDAO->ownsProduct($productId, $userEmail);

            if ($owner)
            {
                return $IProductDAO->activateProduct($productId);
            
            }
            else
            {
                throw new notProductOwnerException("No puedes activar un producto que no te pertenece.");
            }
        }
    }
    /**
     * Actualiza un producto
     * 
     * @param array $productData Datos del producto
     * 
     * @return bool Resultado de la operación
     */
    public function updateProduct($productData)
    {
        $IProductDAO = productFactory::CreateProduct();

        
        // Obtener el ID de la categoría a partir del nombre de la categoría
        $categoryId = $IProductDAO->getCategoryId(($productData->getCategoryName()));

        if ($categoryId === -1)
       {
           // Si no existe, lo registramos
           $categoryId = $IProductDAO->registerCategory($productData->getCategoryName());
       }

        // Actualizamos el ID de la categoría en el DTO
        $productData->setCategoryId($categoryId);

        return $IProductDAO->updateProduct($productData);
    }  
    /**
     * Registra un producto
     * 
     * @param array $productData Datos del producto
     * 
     * @return bool Resultado de la operación
     */
    public function registerProduct($productData)
    {
        $IProductDAO = productFactory::CreateProduct();

         // Comprobamos si la categoria ya existe
         $categoryId = $IProductDAO->getCategoryId($productData['category']);

         if ($categoryId === -1)
        {
            // Si no existe, lo registramos
            $categoryId = $IProductDAO->registerCategory($productData['category']);
        }

        $productDTO = new productDTO(
            null,
            $productData['provider_email'],
            $productData['name'],
            $productData['description'],
            $productData['price'],
            new productCategoryDTO($categoryId, $productData['category']),
            $productData['image_url'],
            $productData['created_at'],
            new productSizesDTO(null, $productData['stock']),
            true
        );

        
        return $IProductDAO->registerProduct($productDTO, $productData['provider_id']);
        
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
    

