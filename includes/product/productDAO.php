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
                $TotalStock // Stock total calculado con SUM(ps.stock)
            );

            // Mientras haya resultados, los guardamos en el array
            while ($stmt->fetch())
            {
                $product = new productDTO($Id, $ProviderId, $Name, $Description, $Price, $TotalStock, $CategoryId, $CategoryName, $ImageUrl, $CreatedAt, array(), $Active);
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
     * @return productDTO Detalles del producto
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
                $productDTO = new productDTO($Id, $ProviderEmail, $Name, $Description, $Price, 0, $CategoryId, $CategoryName, $ImageUrl, $CreatedAt, array(), $Active);
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
    public function getSizes($productId)
    {
        $productSizesDTO = null;
        $sizes = array();

        try {
            // Tomamos la conexion a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Preparamos la consulta SQL
            $query = "SELECT s.name, ps.stock FROM product_sizes ps INNER JOIN sizes s ON ps.size_id = s.id WHERE ps.product_id = ?";
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
            $stmt->bind_result($size, $stock);

            // Mientras haya resultados, los guardamos en el array
            while ($stmt->fetch())
            {
                $sizes[$size] = $stock;
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

    /**
     * Construye la consulta SQL para buscar productos en función de los filtros
     * 
     * @param array $filters Filtros de búsqueda
     * 
     * @return array Datos de la consulta
     */    
    private function buildSearchQuery($filters)
    {
        // Iniciar la consulta con un JOIN para incluir el email del proveedor
    // Iniciar la consulta con JOINs para incluir el email del proveedor y el nombre de la categoría
        $query = "SELECT p.*, u.email AS provider_email, 
                    c.name AS category_name, 
                    SUM(ps.stock) AS total_stock 
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
                        c.name AS category_name, 
                        SUM(ps.stock) AS total_stock 
                      FROM products p 
                      INNER JOIN users u ON p.provider_id = u.id 
                      INNER JOIN product_categories c ON p.category_id = c.id 
                      LEFT JOIN product_sizes ps ON p.id = ps.product_id";
        }

        // Añadir GROUP BY para agrupar por producto
        $query .= " GROUP BY p.id";

        return array('query' => $query, 'params' => $args, 'types' => $types);
    }

    public function deleteProduct($productId)
    {
        try {
            // Tomamos la conexión a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Preparamos la consulta SQL para eliminar el producto
            $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Enlazamos el parámetro
            $stmt->bind_param('i', $productId);

            // Ejecutamos la consulta
            if (!$stmt->execute()) {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            // Cerramos la consulta
            $stmt->close();
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

    public function registerProduct($productDTO)
    {
        try {
            // Obtener la conexión a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Preparar la consulta SQL para insertar un nuevo producto
            $stmt = $conn->prepare("INSERT INTO products (provider_id, name, description, price, category_id, image_url, created_at, active) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Asignar los parámetros
            $provider_id = $this->realEscapeString($productDTO->getProviderId());
            $name = $this->realEscapeString($productDTO->getName());
            $description = $this->realEscapeString($productDTO->getDescription());
            $price = $this->realEscapeString($productDTO->getPrice());
            $category_id = $this->realEscapeString($productDTO->getCategoryId());
            $image_url = $this->realEscapeString($productDTO->getImageUrl());
            $created_at = date('Y-m-d H:i:s'); // Fecha actual
            $active = $this->realEscapeString($productDTO->getActive() ?? 1); // Valor por defecto 1 si no se especifica

            $stmt->bind_param("issdisis", $provider_id, $name, $description, $price, $category_id, $image_url, $created_at, $active);

            // Ejecutar la consulta
            if (!$stmt->execute()) {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            // Obtener el ID del producto recién insertado
            //$product_id = $stmt->insert_id;

            // Cerrar la consulta
            $stmt->close();
            /*
            // Si hay tamaños asociados, insertarlos en product_sizes
            if (method_exists($productDTO, 'getSizes') && !empty($productDTO->getSizes())) {
                $this->registerProductSizes($product_id, $productDTO->getSizes());
            }
                */

            return true;

        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }

    private function registerProductSizes($product_id, $sizes)
    {
        try {
            $conn = application::getInstance()->getConnectionDb();

            // Preparar la consulta para insertar tamaños
            $stmt = $conn->prepare("INSERT INTO product_sizes (product_id, size_id, stock) VALUES (?, ?, ?)");
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta de tamaños: " . $conn->error);
            }

            foreach ($sizes as $size) {
                $size_id = $this->realEscapeString($size['size_id']);
                $stock = $this->realEscapeString($size['stock']);
                
                $stmt->bind_param("iii", $product_id, $size_id, $stock);
                
                if (!$stmt->execute()) {
                    throw new \Exception("Error al insertar tamaño: " . $stmt->error);
                }
            }

            $stmt->close();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }

}