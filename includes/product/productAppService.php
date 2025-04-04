<?php

namespace TheBalance\product;

require_once __DIR__ . '/../../vendor/autoload.php';

use TheBalance\application;
use TheBalance\product\productHasOrdersException;
use TheBalance\product\notProductOwnerException;
use Ramsey\Uuid\Uuid;

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
    public function updateProduct($productDTO)
    {
        $IProductDAO = productFactory::CreateProduct();
    
        // Obtener el ID de la categoría a partir del nombre de la categoría
        $categoryId = $IProductDAO->getCategoryId($productDTO->getCategoryName());
    
        if ($categoryId === -1) {
            // Si no existe, lo registramos
            $categoryId = $IProductDAO->registerCategory($productDTO->getCategoryName());
        }
    
        // Actualizamos el ID de la categoría en el DTO
        $productDTO->setCategoryId($categoryId);
    
        // Actualizar el producto
        $updateResult = $IProductDAO->updateProduct($productDTO);
    
        // Actualizar las tallas del producto
        if ($updateResult) {
            $IProductDAO->updateProductSizes($productDTO->getId(), $productDTO->getSizesDTO());
        }
    
        return $updateResult;
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

        // Validar y guardar la imagen
        $filename = $this->saveImage($productData['image']);

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
            $filename,
            $productData['created_at'],
            new productSizesDTO(null, $productData['stock']),
            true
        );

        // Registramos el producto
        $productId = $IProductDAO->registerProduct($productDTO, $productData['provider_id']);
        
        // Guardamos las tallas del producto
        $sizesDTO = $productDTO->getSizesDTO();
        $sizesDTO->setProductId($productId);
        $IProductDAO->registerProductSizes($productId, $sizesDTO);

        return $productId;
    }

    /**
     * Guarda una imagen en el sistema de archivos
     * 
     * @param string $image Ruta de la imagen
     * 
     * @return string Nombre del archivo guardado
     */
    public function saveImage($image)
    {
        $guid = Uuid::uuid4()->toString();

        // Guardar la imagen en el sistema de archivos
        $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR;
        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $filename = $guid . '.' . $extension;
        $uploadPath = $uploadDir . $filename;

        if (!move_uploaded_file($image['tmp_name'], $uploadPath)) {
            throw new \Exception('Error al subir la imagen del producto.');
        }

        return $filename;
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
    

