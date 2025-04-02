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
                $ImageUrl, 
                $CreatedAt, 
                $Active,
                $ProviderEmail,
                $CategoryName, 
            );

            // Mientras haya resultados, los guardamos en el array
            while ($stmt->fetch())
            {
                $ProductCategoryDTO = new productCategoryDTO($CategoryId, $CategoryName);
                $product = new productDTO($Id, $ProviderId, $Name, $Description, $Price, $ProductCategoryDTO, $ImageUrl, $CreatedAt, null, $Active);
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

    public function registerProduct($productDTO, $provider_id) {
        $conn = null;
        $productStmt = null;
        
        try {
            $conn = application::getInstance()->getConnectionDb();
            $conn->begin_transaction();
    
            // 1. Extraer valores a variables individuales
            $name = $productDTO->getName();
            $description = $productDTO->getDescription();
            $price = $productDTO->getPrice();
            $category_id = $productDTO->getCategoryId();
            $image_url = $productDTO->getImageUrl();
            $createdAt = $productDTO->getCreatedAt();
            $active = $productDTO->getActive() ? 1 : 0;
    
            // 2. Insertar producto (sin especificar ID)
            $productStmt = $conn->prepare("INSERT INTO products 
                                        (provider_id, name, description, price, category_id, image_url, created_at, active) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            
            $productStmt->bind_param("issdsssi", 
                $provider_id,    // Variable
                $name,          // Variable
                $description,   // Variable
                $price,         // Variable
                $category_id,   // Variable
                $image_url,     // Variable
                $createdAt,     // Variable
                $active         // Variable
            );
    
            if (!$productStmt->execute()) {
                throw new \Exception("Error al registrar producto: " . $productStmt->error);
            }
    
            // 3. Obtener ID generado
            $product_id = $conn->insert_id;
            
            // 4. Registrar tallas
            if ($productDTO->getSizesDTO() !== null) {
                $sizesDTO = $productDTO->getSizesDTO();
                $sizesDTO->setProductId($product_id); // Actualizar el ID en el DTO
                
                $this->registerProductSizes($conn, $product_id, $sizesDTO);
            }
    
            $conn->commit();
            return $product_id;
    
        } catch (\Exception $e) {
            if ($conn !== null) $conn->rollback();
            error_log("Error en registerProduct: " . $e->getMessage());
            throw $e;
        } finally {
            if ($productStmt !== null) $productStmt->close();
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

    private function registerProductSizes($conn, $product_id, productSizesDTO $sizesDTO)
    {
        $sizeStmt = null;
        
        try {
            $sizes = $sizesDTO->getSizes();
            
            // Cambiar a size_id si es el nombre correcto en tu tabla
            $sizeStmt = $conn->prepare("INSERT INTO product_sizes (product_id, size_id, stock) VALUES (?, ?, ?)");
            
            if (!$sizeStmt) {
                throw new \Exception("Error al preparar la consulta de tallas: " . $conn->error);
            }
            
            foreach ($sizes as $size => $stock) {
                // Primero obtener el ID de la talla desde la tabla de tamaños
                $size_id = $this->getSizeIdByName($size);
                
                if ($size_id === null) {
                    throw new \Exception("Talla '$size' no encontrada en la base de datos");
                }
                
                $escapedStock = (int)$stock;
                
                $sizeStmt->bind_param("iii", $product_id, $size_id, $escapedStock);
                
                if (!$sizeStmt->execute()) {
                    throw new \Exception("Error al insertar talla $size: " . $sizeStmt->error);
                }
            }
        } finally {
            if ($sizeStmt !== null) {
                $sizeStmt->close();
            }
        }
    }

    private function getSizeIdByName($sizeName)
    {
        $conn = application::getInstance()->getConnectionDb();
        $stmt = $conn->prepare("SELECT id FROM sizes WHERE name = ?");
        
        if (!$stmt) {
            throw new \Exception("Error al preparar consulta de tallas: " . $conn->error);
        }
        
        $stmt->bind_param("s", $sizeName);
        $stmt->execute();
        $stmt->bind_result($size_id);
        $stmt->fetch();
        $stmt->close();
        
        return $size_id;
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

}