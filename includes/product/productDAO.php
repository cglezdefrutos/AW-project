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
            $stmt->bind_result($Id, $ProviderId, $Name, $Description, $Price, $Stock, $CategoryId, $ImageUrl, $CreatedAt);

            // Mientras haya resultados, los guardamos en el array
            while ($stmt->fetch())
            {
                $product = new productDTO($Id, $ProviderId, $Name, $Description, $Price, $Stock, $CategoryId, $ImageUrl, $CreatedAt);
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
     * Construye la consulta SQL para buscar productos en función de los filtros
     * 
     * @param array $filters Filtros de búsqueda
     * 
     * @return array Datos de la consulta
     */    
    public function buildSearchQuery($filters)
    {
        $query = "SELECT * FROM events WHERE ";
        $args = array();
        $types = '';

        foreach ($filters as $key => $value) 
        {
            if ($value !== '')
            {
                $query .= "$key = ? AND ";
                $args[] = $value;
                $types .= 's';
            }

            switch ($key) {
                case 'name':
                    $query .= "name LIKE ? AND ";
                    $args[] = '%' . $value . '%';
                    $types .= 's';
                    break;
                case 'description':
                    $query .= "description LIKE ? AND ";
                    $args[] = '%' . $value . '%';
                    $types .= 's';
                    break;
                case 'category':
                    $query .= "category LIKE ? AND ";
                    $args[] = '%' . $value . '%';
                    $types .= 's';
                    break;
                case 'provider':
                    $query .= "provider LIKE ? AND ";
                    $args[] = '%' . $value . '%';
                    $types .= 's';
                    break;
                case 'minStock':
                    $query .= "stock >= ? AND ";
                    $args[] = $value;
                    $types .= 'i';
                break;
                case 'maxStock':
                    $query .= "stock <= ? AND ";
                    $args[] = $value;
                    $types .= 'i';
                break;
                case 'minPrice':
                    $query .= "price >= ? AND ";
                    $args[] = $value;
                    $types .= 'd';
                    break;
                case 'maxPrice':
                    $query .= "price <= ? AND ";
                    $args[] = $value;
                    $types .= 'd';
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
            $query = "SELECT * FROM events";
        }

        return array('query' => $query, 'params' => $args, 'types' => $types);
    }

    public function getProducts($filters = array())
    {
        $products = array();

        try {
            // Tomamos la conexión a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Implementar la lógica de acceso a la base de datos para obtener los productos que cumplan con los filtros pasados como parámetro
            $queryData = $this->buildSearchQuery($filters);

            // Preparamos la consulta
            $stmt = $conn->prepare($queryData['query']);
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Asignamos los parámetros solo si hay parámetros para enlazar
            if (!empty($queryData['params'])) {
                $types = $queryData['types'];
                $params = $queryData['params'];
                $stmt->bind_param($types, ...$params);
            }

            // Ejecutamos la consulta
            if (!$stmt->execute()) {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            // Asignamos los resultados a variables
            $stmt->bind_result($id, $provider_id, $name, $description, $price, $stock, $category_id, $image_url, $created_at);

            // Mientras haya resultados, los guardamos en el array
            while ($stmt->fetch()) {
                $product = new productDTO($id, $provider_id, $name, $description, $price, $stock, $category_id, $image_url, $created_at);
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

    public function getOrdersByProduct($productId){
        
    }
    
}