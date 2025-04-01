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

        return $IProduct->searchProducts($filters);
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
            $userEmail = htmlspecialchars($app->getCurrentUserEmail());

            // Pasamos como filtro un array con el email (así solo traerá los productos donde coincida ese email)
            $productsDTO = $IProductDAO->getProducts(array("email_provider" => $userEmail));
        }

        return $productsDTO;
    }

    public function deleteProduct($productId)
    {
        $IProductDAO = productFactory::CreateProduct();

        // Si el producto tiene pedidos asociados, no se puede eliminar
        $orders = $IProductDAO->getOrdersByProduct($productId);

        if (count($orders) > 0)
        {
            throw new productHasOrdersException("No puedes eliminar un producto que tiene pedidos asociados.");
        }

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
}

