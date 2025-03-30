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
     * Optiene todos los Orders
     * 
     * @param none
     * @return array de orders
     */
    public function getAllOrders() {
        $orders = array();
    
        try {
            // Tomamos la conexión a la base de datos
            $conn = application::getInstance()->getConnectionDb();
    
            // Consulta SQL para obtener todos los orders
            $stmt = $conn->prepare("SELECT * FROM orders");
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }
    
            // Ejecutamos la consulta
            if (!$stmt->execute()) {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
    
            // Asignamos los resultados a variables
            $stmt->bind_result($id, $user_id, $address_id, $total_price, $status, $created_at);
    
            // Mientras haya resultados, los guardamos en el array
            while ($stmt->fetch()) {
                $order = new orderDTO($id, $user_id, $address_id, $total_price, $status, $created_at);
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
            $stmt->bind_result($id, $user_id, $address_id, $total_price, $status, $created_at);

            // Mientras haya resultados, los guardamos en el array
            while ($stmt->fetch())
            {
                $order = new orderDTO($id, $user_id, $address_id, $total_price, $status, $created_at);
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
     * Elimina un pedido por su ID
     * 
     * @param int $orderId ID del pedido a eliminar
     * @return bool True si se eliminó correctamente, False si falló
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