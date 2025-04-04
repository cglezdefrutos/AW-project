<?php

namespace TheBalance\product;

use TheBalance\views\common\baseDAO;
use TheBalance\application;

/**
 * Data Access Object de productos
 */
class productDAO extends baseDAO implements IProduct
{
    /**
     * Busca productos
     * 
     * @param array $filters Filtros de búsqueda
     * 
     * @return array Resultado de la búsqueda
     */
    public function searchProducts($filters = array())
    {
        $products = array();

        try {
            // Tomamos la conexion a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Construimos la consulta SQL
            $queryData = $this->buildSearchQuery($filters);

            // Preparamos la consulta
            $stmt = $conn->prepare($queryData['query']);
            if(!$stmt)
            {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Asignamos los parametros solo si hay parámetros para enlazar
            if (!empty($queryData['params'])) 
            {
                $types = $queryData['types'];
                $params = $queryData['params'];
                $stmt->bind_param($types, ...$params);
            }

            // Ejecutamos la consulta
            if(!$stmt->execute())
            {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            // Asignamos los resultados a variables
            $stmt->bind_result(
                $Id, 
                $ProviderId, 
                $Name, 
                $Description, 
                $Price, 
                $CategoryId, 
                $ImageGuid, 
                $CreatedAt, 
                $Active,
                $ProviderEmail,
                $CategoryName, 
            );

            // Mientras haya resultados, los guardamos en el array
            while ($stmt->fetch())
            {
                $ProductCategoryDTO = new productCategoryDTO($CategoryId, $CategoryName);
                $product = new productDTO($Id, $ProviderId, $Name, $Description, $Price, $ProductCategoryDTO, $ImageGuid, $CreatedAt, null, $Active);
                $products[] = $product;
            }

            // Cerramos la consulta
            $stmt->close();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }

        // Devolvemos el array de productos
        return $products;
    }

    /**
     * Busca un producto por su ID
     * 
     * @param int $id ID del producto
     * 
     * @return productDTO Detalles del producto (sin tallas)
     */
    public function getProductById($id)
    {
        $productDTO = null;

        try {
            // Tomamos la conexion a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Preparamos la consulta SQL
            $query = "SELECT p.*, u.email AS provider_email, 
                        c.name AS category_name 
                      FROM products p INNER JOIN users u ON p.provider_id = u.id 
                      INNER JOIN product_categories c ON p.category_id = c.id 
                      WHERE p.id = ?";
            $stmt = $conn->prepare($query);
            if(!$stmt)
            {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Enlazamos el parámetro
            $stmt->bind_param('i', $id);

            // Ejecutamos la consulta
            if(!$stmt->execute())
            {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            // Asignamos los resultados a variables
            $stmt->bind_result($Id, $ProviderId, $Name, $Description, $Price, $CategoryId, $ImageUrl, $CreatedAt, $Active, $ProviderEmail, $CategoryName);

            // Si hay resultados, los guardamos en el objeto productDTO
            if ($stmt->fetch())
            {
                $ProductCategoryDTO = new productCategoryDTO($CategoryId, $CategoryName);
                $productDTO = new productDTO($Id, $ProviderEmail, $Name, $Description, $Price, $ProductCategoryDTO, $ImageUrl, $CreatedAt, null, $Active);
            }

            // Cerramos la consulta
            $stmt->close();
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

        return $productDTO;
    }

    /**
     * Busca las tallas de un producto por su ID
     * 
     * @param int $id ID del producto
     * 
     * @return array Tallas del producto
     */
    public function getProductSizes($productId)
    {
        $productSizesDTO = null;
        $sizes = array();

        try {
            // Tomamos la conexion a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Preparamos la consulta SQL
            $query = "SELECT s.name, ps.stock 
                      FROM product_sizes ps 
                      INNER JOIN sizes s ON ps.size_id = s.id 
                      WHERE ps.product_id = ?";
            $stmt = $conn->prepare($query);
            if(!$stmt)
            {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Enlazamos el parámetro
            $stmt->bind_param('i', $productId);

            // Ejecutamos la consulta
            if(!$stmt->execute())
            {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            // Asignamos los resultados a variables
            $stmt->bind_result($sizeName, $stock);

            // Mientras haya resultados, los guardamos en el array
            while ($stmt->fetch())
            {
                $sizes[$sizeName] = $stock;
            }

            // Creamos el DTO de tallas del producto
            $productSizesDTO = new productSizesDTO($productId, $sizes);

            // Cerramos la consulta
            $stmt->close();

        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

        return $productSizesDTO;
    }

    /**
     * Busca las categorías de productos
     * 
     * @return array Categorías de productos
     */
    public function getCategories()
    {
        $categories = array();

        try {
            // Tomamos la conexión a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Preparamos la consulta SQL
            $query = "SELECT id, name FROM product_categories";
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Ejecutamos la consulta
            if (!$stmt->execute()) {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            // Asignamos los resultados a variables
            $stmt->bind_result($id, $name);

            // Guardamos las categorías en el array de productCategoryDTOs
            while ($stmt->fetch()) {
                $categoryDTO = new productCategoryDTO($id, $name);
                $categories[] = $categoryDTO;
            }

            // Cerramos la consulta
            $stmt->close();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }

        return $categories;
    }

    public function deleteProduct($productId)
    {
        try {
            // Obtener conexión a la base de datos
            $conn = application::getInstance()->getConnectionDb();
    
            // Preparar consulta para desactivar el producto (actualizar active a 0)
            $stmt = $conn->prepare("UPDATE products SET active = 0 WHERE id = ?");
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }
    
            // Enlazar parámetro
            $stmt->bind_param('i', $productId);
    
            // Ejecutar la actualización
            if (!$stmt->execute()) {
                throw new \Exception("Error al desactivar el producto: " . $stmt->error);
            }
    
            // Verificar si realmente se actualizó algún registro
            if ($stmt->affected_rows === 0) {
                throw new \Exception("No se encontró el producto con ID: " . $productId);
            }
    
            // Cerrar statement
            $stmt->close();
            
            return true; // Indicar éxito en la operación
            
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }

    public function activateProduct($productId)
    {
        try {
            $conn = application::getInstance()->getConnectionDb();
            $stmt = $conn->prepare("UPDATE products SET active = 1 WHERE id = ?");
            
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            $stmt->bind_param('i', $productId);
            
            if (!$stmt->execute()) {
                throw new \Exception("Error al activar el producto: " . $stmt->error);
            }

            return $stmt->affected_rows > 0;
            
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }

    public function ownsProduct($productId, $userEmail)
    {
        try {
            // Tomamos la conexión a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Preparamos la consulta SQL para verificar si el producto pertenece al proveedor
            $stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE id = ? AND provider_id = (SELECT id FROM users WHERE email = ?)");
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Enlazamos los parámetros
            $stmt->bind_param('is', $productId, $userEmail);

            // Ejecutamos la consulta
            if (!$stmt->execute()) {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            // Obtenemos el resultado
            $stmt->bind_result($count);
            $stmt->fetch();

            // Cerramos la consulta
            $stmt->close();

            return $count > 0;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }

    public function registerProduct($productDTO, $provider_id) 
    {
        $product_id = null;
        
        try {
            // Tomamos la conexión a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Preparamos la consulta SQL para insertar el producto
            $stmt = $conn->prepare("INSERT INTO products (provider_id, name, description, price, category_id, image_guid, created_at, active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Extraer y escapar valores
            $name = $this->realEscapeString($productDTO->getName());
            $description = $this->realEscapeString($productDTO->getDescription());
            $price = $this->realEscapeString($productDTO->getPrice());
            $category_id = $this->realEscapeString($productDTO->getCategoryId());
            $image_guid = $this->realEscapeString($productDTO->getImageGuid()); 
            $createdAt = $this->realEscapeString($productDTO->getCreatedAt());
            $active = $productDTO->getActive() ? 1 : 0;
            $provider_id = $this->realEscapeString($provider_id);

            // Enlazamos los parámetros
            $stmt->bind_param("issdsssi", 
                $provider_id,    // i (integer)
                $name,          // s (string)
                $description,   // s (string)
                $price,         // d (double)
                $category_id,   // s (string)
                $image_guid,    // s (string)
                $createdAt,     // s (string)
                $active        // i (integer)
            );

            // Ejecutamos la consulta
            if (!$stmt->execute()) {
                throw new \Exception("Error al registrar producto: " . $productStmt->error);
            }

            // Obtener ID del producto insertado
            $product_id = $stmt->insert_id;

            // Cerrar statement
            $stmt->close();
        } catch (\Exception $e) {
            error_log("Error en registerProduct: " . $e->getMessage());
            throw $e;
        } 

        return $product_id;
    }

    public function updateProduct(productDTO $productDTO) 
    {
        $conn = null;
        $stmt = null;
        
        try {
            $conn = application::getInstance()->getConnectionDb();
            $conn->begin_transaction();

            // 1. Preparar variables escapadas
            $name = $this->realEscapeString($productDTO->getName());
            $description = $this->realEscapeString($productDTO->getDescription());
            $price = (float)$this->realEscapeString($productDTO->getPrice());
            $category_id = (int)$this->realEscapeString($productDTO->getCategoryId());
            $image_url = $this->realEscapeString($productDTO->getImageUrl());
            $id = (int)$this->realEscapeString($productDTO->getId());

            // 2. Actualizar producto principal
            $stmt = $conn->prepare("UPDATE products SET 
                                name = ?, 
                                description = ?, 
                                price = ?, 
                                category_id = ?, 
                                image_url = ? 
                                WHERE id = ?");
            
            $stmt->bind_param("ssdisi", 
                $name,
                $description,
                $price,
                $category_id,
                $image_url,
                $id
            );

            if (!$stmt->execute()) {
                throw new \Exception("Error al actualizar producto: " . $stmt->error);
            }

            // 3. Actualizar tallas
            if ($productDTO->getSizesDTO() !== null) {
                $this->updateProductSizes($conn, $id, $productDTO->getSizesDTO());
            }

            $conn->commit();
            return true;

        } catch (\Exception $e) {
            if ($conn !== null) $conn->rollback();
            error_log("Error en updateProduct: " . $e->getMessage());
            throw $e;
        } finally {
            if ($stmt !== null) $stmt->close();
        }
    }
   
    public function getCategoryId($categoryName)
    {
        $categoryId = null;

        try {
            // Get database connection
            $conn = application::getInstance()->getConnectionDb();

            // Prepare SQL query to get product category ID by name
            $stmt = $conn->prepare("SELECT id FROM product_categories WHERE name = ?");
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Sanitize and bind parameters
            $escCategoryName = $this->realEscapeString($categoryName);
            $stmt->bind_param("s", $escCategoryName);

            // Execute query
            if (!$stmt->execute()) {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            // Bind result variable
            $stmt->bind_result($categoryId);

            // Store result to check number of rows
            $stmt->store_result();

            // If no rows found, category doesn't exist
            if ($stmt->num_rows === 0) {
                $categoryId = -1;
            } 
            // If found, get the ID
            else {
                $stmt->fetch();
            }

            $stmt->close();            

        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }

        return $categoryId;
    }

    public function registerCategory($categoryName)
    {
        $categoryId = null;

        try {
            // Get database connection
            $conn = application::getInstance()->getConnectionDb();

            // Prepare SQL to insert new product category
            $stmt = $conn->prepare("INSERT INTO product_categories (name) VALUES (?)");
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Sanitize and bind parameters
            $escCategoryName = $this->realEscapeString($categoryName);
            $stmt->bind_param("s", $escCategoryName);

            // Execute query
            if (!$stmt->execute()) {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            // Get the ID of the newly created category
            $categoryId = $stmt->insert_id;

            // Close statement
            $stmt->close();

        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }

        return $categoryId;
    }

    public function registerProductSizes($product_id, $sizesDTO)
    {        
        try {
            // Tomamos la conexión a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Tomamos las tallas registradas del producto
            $sizes = $sizesDTO->getSizes();
            
            foreach ($sizes as $size => $stock) {
                // Escapar el nombre de la talla antes de buscar el ID
                $escapedSize = $this->realEscapeString($size);

                // Buscar el ID de la talla en la base de datos
                $size_id = $this->getSizeId($escapedSize);
                
                if ($size_id === null) {
                    throw new \Exception("Talla {$size} no encontrada en la base de datos");
                }
                
                // Escapar y validar el stock
                $escapedStock = $this->realEscapeString($stock);
                $escapedProductId = $this->realEscapeString($product_id);
                $escapedSizeId = $this->realEscapeString($size_id);

                // Preparamos la consulta SQL para insertar las tallas
                $stmt = $conn->prepare("INSERT INTO product_sizes (product_id, size_id, stock) VALUES (?, ?, ?)");
                if (!$stmt) {
                    throw new \Exception("Error al preparar la consulta de tallas: " . $conn->error);
                }
                
                $stmt->bind_param("iii", 
                    $escapedProductId,  // product_id (integer)
                    $escapedSizeId,     // size_id (integer)
                    $escapedStock       // stock (integer)
                );
                
                if (!$stmt->execute()) {
                    throw new \Exception("Error al insertar talla $size: " . $sizeStmt->error);
                }

                // Cerrar statement
                $stmt->close();
            }
        } catch (\Exception $e) {
            error_log("Error en registerProductSizes: " . $e->getMessage());
            throw $e;
        }

        return true;
    }

    public function getSizeId($sizeName)
    {
        $sizeId = null;

        try {
            // Tomamos la conexión a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Preparamos la consulta SQL para buscar el ID de la talla
            $stmt = $conn->prepare("SELECT id FROM sizes WHERE name = ?");
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Escapar y enlazar el parámetro
            $escapedSizeName = $this->realEscapeString($sizeName);
            $stmt->bind_param("s", $escapedSizeName);

            // Ejecutamos la consulta
            if (!$stmt->execute()) {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            // Asignamos el resultado a una variable
            $stmt->bind_result($sizeId);
            $stmt->fetch();

            $stmt->close();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }
        
        return $sizeId;
    }

     /**
     * Construye la consulta SQL para buscar productos en función de los filtros
     * 
     * @param array $filters Filtros de búsqueda
     * 
     * @return array Datos de la consulta
     */    
    private function buildSearchQuery($filters)
    {
        // Inicializamos la consulta SQL y los parámetros
        $query = "SELECT p.*, u.email AS provider_email, 
                    c.name AS category_name 
                  FROM products p 
                  INNER JOIN users u ON p.provider_id = u.id 
                  INNER JOIN product_categories c ON p.category_id = c.id 
                  LEFT JOIN product_sizes ps ON p.id = ps.product_id 
                  WHERE ";
        $args = array();
        $types = '';

        foreach ($filters as $key => $value) 
        {
            if($value == '')
            {
                continue;
            }

            switch ($key) {
                case 'name':
                    $query .= "p.name LIKE ? AND ";
                    $args[] = "%" . $this->realEscapeString($value) . "%";
                    $types .= 's';
                    break;
                case 'category':
                    $query .= "c.name = ? AND ";
                    $args[] = $this->realEscapeString($value);
                    $types .= 's';
                    break;
                case 'provider':
                    $query .= "u.email = ? AND ";
                    $args[] = $this->realEscapeString($value);
                    $types .= 's';
                    break;
                case 'minStock':
                    $query .= "SUM(ps.stock) >= ? AND ";
                    $args[] = $value;
                    $types .= 'i';
                break;
                case 'maxStock':
                    $query .= "SUM(ps.stock) <= ? AND ";
                    $args[] = $value;
                    $types .= 'i';
                break;
                case 'minPrice':
                    $query .= "p.price >= ? AND ";
                    $args[] = $value;
                    $types .= 'd';
                    break;
                case 'maxPrice':
                    $query .= "p.price <= ? AND ";
                    $args[] = $value;
                    $types .= 'd';
                    break;
                case 'active':
                    $query .= "p.active = ? AND ";
                    $args[] = $value;
                    $types .= 'i';
                    break;
                default:
                    // Si el filtro no es válido, lo ignoramos
                    break;
            }
        }

        // Eliminar el último " AND " si hay filtros
        if (!empty($args)) 
        {
            $query = substr($query, 0, -4);
        } 
        // Si no hay filtros, eliminar la cláusula WHERE
        else 
        {
            // Si no hay filtros, eliminar la cláusula WHERE
            $query = "SELECT p.*, u.email AS provider_email, 
                        c.name AS category_name
                      FROM products p 
                      INNER JOIN users u ON p.provider_id = u.id 
                      INNER JOIN product_categories c ON p.category_id = c.id 
                      LEFT JOIN product_sizes ps ON p.id = ps.product_id";
        }

        // Añadir GROUP BY para agrupar por producto
        $query .= " GROUP BY p.id";

        return array('query' => $query, 'params' => $args, 'types' => $types);
    }

    /**
     * Actualiza las tallas de un producto
     * 
     * @param mysqli $conn Conexión a la base de datos
     * @param int $product_id ID del producto
     * @param productSizesDTO $sizesDTO DTO con las tallas y stock
     */
    private function updateProductSizes($conn, $product_id, productSizesDTO $sizesDTO)
    {
        $deleteStmt = null;
        $insertStmt = null;
        
        try {
            // 1. Eliminar tallas existentes para este producto
            $deleteStmt = $conn->prepare("DELETE FROM product_sizes WHERE product_id = ?");
            $escapedProductId = (int)$this->realEscapeString($product_id);
            $deleteStmt->bind_param("i", $escapedProductId);
            
            if (!$deleteStmt->execute()) {
                throw new \Exception("Error al eliminar tallas existentes: " . $deleteStmt->error);
            }
    
            // 2. Insertar las nuevas tallas
            $sizes = $sizesDTO->getSizes();
            $insertStmt = $conn->prepare("INSERT INTO product_sizes (product_id, size_id, stock) VALUES (?, ?, ?)");
            
            foreach ($sizes as $size => $stock) {
                // Escapar y validar parámetros
                $escapedSize = $this->realEscapeString($size);
                $size_id = $this->getSizeId($escapedSize);
                
                if ($size_id === null) {
                    throw new \Exception("Talla '$size' no encontrada en la base de datos");
                }
                
                $escapedSizeId = (int)$this->realEscapeString($size_id);
                $escapedStockValue = (int)$this->realEscapeString($stock);
                $escapedCurrentProductId = (int)$this->realEscapeString($product_id);
    
                $insertStmt->bind_param("iii", 
                    $escapedCurrentProductId,  // product_id (integer)
                    $escapedSizeId,          // size_id (integer)
                    $escapedStockValue       // stock (integer)
                );
                
                if (!$insertStmt->execute()) {
                    throw new \Exception("Error al insertar talla $size: " . $insertStmt->error);
                }
            }
        } catch (\Exception $e) {
            error_log("Error en updateProductSizes: " . $e->getMessage());
            throw $e;
        } finally {
            if ($deleteStmt !== null) $deleteStmt->close();
            if ($insertStmt !== null) $insertStmt->close();
        }
    }

}