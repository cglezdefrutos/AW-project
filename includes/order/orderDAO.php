<?php

namespace TheBalance\order;

use TheBalance\views\common\baseDAO;
use TheBalance\application;

/**
 * Clase que contiene la lógica de acceso a datos de usuarios
 */
class orderDAO extends baseDAO implements IOrder
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Devuelve el pedido asociado a ese id
     * 
     * @param string $orderId ID del pedido
     * 
     * @return orderDTO Resultado de la búsqueda
     */
    public function getOrderById($orderId)
    {
        $order = null;
    
        try {
            // Obtener la conexión a la base de datos
            $conn = application::getInstance()->getConnectionDb();
    
            // Preparar la consulta SQL
            $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }
    
            // Asignar el parámetro y ejecutar la consulta
            $stmt->bind_param("i", $orderId);
    
            if (!$stmt->execute()) {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
    
            // Asignar los resultados a variables
            $stmt->bind_result($id, $user_id, $total_price, $status, $shipping_address, $created_at);
    
            // Obtener el resultado
            if ($stmt->fetch()) {
                $order = new orderDTO($id, $user_id, $total_price, $status, $shipping_address, $created_at);
            }
    
            // Cerrar la consulta
            $stmt->close();
    
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    
        return $order;
    }

    /**
     * Optiene todos los Orders con los emails de los usuarios
     * 
     * @param none
     * @return array de orders
     */
    public function getAllOrdersWithEmail() {
        $orders = array();
    
        try {
            // Tomamos la conexión a la base de datos
            $conn = application::getInstance()->getConnectionDb();
    
            // Consulta SQL con JOIN para incluir el email del usuario
            $stmt = $conn->prepare("SELECT o.id, o.user_id, u.email, o.total_price, o.status, o.shipping_address, o.created_at
                                    FROM orders o
                                    JOIN users u ON o.user_id = u.id");
    
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }
    
            // Ejecutamos la consulta
            if (!$stmt->execute()) {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
    
            // Asignamos los resultados a variables
            $stmt->bind_result($id, $user_id, $email, $total_price, $status, $shipping_address, $created_at);
    
            // Guardamos los resultados en el array
            while ($stmt->fetch()) {
                $order = new OrderWithUserDTO($id, $user_id, $email, $total_price, $status, $shipping_address, $created_at);
                $orders[] = $order;
            }
    
            // Cerramos la consulta
            $stmt->close();
    
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }
    
        return $orders;
    }
    
    

    /**
     * Optiene los Orders de un usuario
     * 
     * @param int id del usuario
     * @return array de orders del usuario
     */
    public function getOrdersByUserId($userId){

        $orders = array();

        try{
            // Tomamos la conexion a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Implementar la logica de acceso a la base de datos para obtener los orders de un usuario
            $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ?");
            if(!$stmt)
            {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Asignamos los parametros
            $escUserId = $this->realEscapeString($userId);
            $stmt->bind_param("i", $escUserId);

            // Ejecutamos la consulta
            if(!$stmt->execute())
            {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            } 
            
            // Asignamos los resultados a variables
            $stmt->bind_result($id, $user_id, $total_price, $status, $shipping_address, $created_at);

            // Mientras haya resultados, los guardamos en el array
            while ($stmt->fetch())
            {
                $order = new orderDTO($id, $user_id, $total_price, $status, $shipping_address, $created_at);
                $orders[] = $order;
            }

            // Cerramos la consulta
            $stmt->close();

        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }

        return $orders;

    }

    /**
     * Actualiza un pedido
     * 
     * @param orderDTO $order el dto del pedido a eliminar
     * @return bool True si se eliminó correctamente
     */
    public function updateOrder($order)
    {
        try {
            $conn = application::getInstance()->getConnectionDb();

            $stmt = $conn->prepare("UPDATE orders SET user_id = ?, total_price = ?, status = ?, shipping_address = ?, created_at = ? WHERE id = ?");
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Obtener valores del DTO
            $userId = $order->getUserId();
            $totalPrice = $order->getTotalPrice();
            $status = $order->getStatus();
            $shipping_address = $order->getShippingAddress();
            $createdAt = $order->getCreatedAt();
            $orderId = $order->getId();

            $stmt->bind_param("idsssi", $userId, $totalPrice, $status, $shipping_address, $createdAt, $orderId);

            if (!$stmt->execute()) {
                throw new \Exception("Error al ejecutar la actualización: " . $stmt->error);
            }

            $stmt->close();


        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }

        return true;

    }


    /**
     * Elimina un pedido por su ID
     * 
     * @param int $orderId ID del pedido a eliminar
     * @return bool True si se eliminó correctamente
     */
    public function deleteOrder($orderId)
    {
        try {
            // Obtener la conexión a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Preparar la consulta SQL
            $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Asignar el parámetro y ejecutar la consulta
            $stmt->bind_param("i", $orderId);

            if (!$stmt->execute()) {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            $stmt->close();

           

        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }

        return true;
    }


}