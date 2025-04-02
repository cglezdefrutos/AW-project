<?php

namespace TheBalance\order;

use TheBalance\views\common\baseDAO;
use TheBalance\application;

/**
 * Clase de acceso a datos para detalles de pedido
 */
class orderDetailDAO extends baseDAO implements IOrderDetail
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene detalles por ID de pedido
     * 
     * @param int $orderId
     * @return array de orderDetailDTO
     */
    public function getDetailsByOrderId($orderId)
    {
        $details = array();

        try {
            $conn = application::getInstance()->getConnectionDb();

            $stmt = $conn->prepare("SELECT od.order_id, p.name as product_name, p.image_url, od.quantity, od.price, od.size FROM order_details od JOIN products p ON od.product_id = p.id WHERE od.order_id = ?");
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta: " . $conn->error);
            }

            $escOrderId = $this->realEscapeString($orderId);
            $stmt->bind_param("i", $escOrderId);

            if (!$stmt->execute()) {
                throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            $stmt->bind_result($order_id, $product_name, $image_url, $quantity, $price, $size);

            while ($stmt->fetch()) {
                $details[] = new orderDetailDTO($order_id, $product_name, $image_url, $quantity, $price, $size);
            }

            $stmt->close();

        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }

        return $details;
    }

    /**
     * Elimina todos los detalles de un pedido por su order_id
     * 
     * @param int $orderId ID del pedido cuyos detalles serán eliminados
     * @return bool True si se eliminaron correctamente, False si falló
     */
    public function deleteOrderDetailsByOrderId($orderId)
    {
        try {
            // Obtener la conexión a la base de datos
            $conn = application::getInstance()->getConnectionDb();

            // Preparar la consulta SQL
            $stmt = $conn->prepare("DELETE FROM order_details WHERE order_id = ?");
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