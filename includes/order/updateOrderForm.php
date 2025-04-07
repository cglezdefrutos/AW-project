<?php

namespace TheBalance\order;

use TheBalance\views\common\baseForm;

/**
 * Formulario para actualizar el estado de un pedido
 */
class updateOrderForm extends baseForm
{
    /**
     * Datos iniciales del pedido
     * 
     * @var orderDTO
     */
    private $orderInitialData;

    /**
     * Constructor
     * 
     * @param orderDTO $orderInitialData Datos iniciales del pedido
     */
    public function __construct($orderInitialData)
    {
        parent::__construct('updateOrderForm');
        $this->orderInitialData = $orderInitialData;
    }

    /**
     * Crea los campos del formulario
     * 
     * @return string Campos del formulario
     */
    protected function CreateFields($initialData)
    {
        $currentStatus = htmlspecialchars($this->orderInitialData->getStatus());

        // Estados disponibles
        $statuses = ['En preparación', 'Enviado', 'Entregado', 'Cancelado'];

        // Inicio del formulario
        $html = <<<EOF
            <fieldset class="border p-4 rounded">
                <input type="hidden" name="orderId" value="{$this->orderInitialData->getId()}">
                
                <legend class="w-auto">Actualizar Estado del Pedido</legend>

                <div class="mb-3">
                    <label class="form-label">Estado del pedido:</label>
        EOF;

        foreach ($statuses as $status) {
            $checked = ($status === $currentStatus) ? 'checked' : '';
            $statusEscaped = htmlspecialchars($status);
            $html .= <<<EOF
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" value="$statusEscaped" id="status_$statusEscaped" $checked required>
                    <label class="form-check-label" for="status_$statusEscaped">$statusEscaped</label>
                </div>
            EOF;
        }

        $html .= <<<EOF
                </div>

                <div class="mt-3">
                    <button type="submit" name="update_order_status" class="btn btn-primary w-100">Actualizar Estado</button>
                </div>
            </fieldset>
        EOF;
        
        return $html;
    }

    /**
     * Procesa los datos del formulario
     * 
     * @param array $data Datos del formulario
     * 
     * @return array|string Errores de procesamiento | Redirección
     */
    protected function Process($data)
    {
        $result = [];

        // Validar orderId
        $orderId = trim($data['orderId'] ?? '');
        $orderId = filter_var($orderId, FILTER_SANITIZE_NUMBER_INT);
        if (!is_numeric($orderId) || $orderId <= 0) {
            $result[] = 'ID de pedido inválido.';
        }

        // Validar estado seleccionado
        $states = ['En preparación', 'Enviado', 'Entregado', 'Cancelado'];
        $newStatus = trim($data['status'] ?? '');
        $newStatus = filter_var($newStatus, FILTER_SANITIZE_STRING);
        if (!in_array($newStatus, $states)) {
            $result[] = 'Estado seleccionado no válido.';
        }

        if (empty($result)) {
            // Crear el DTO actualizado
            $updatedOrder = new orderDTO(
                $this->orderInitialData->getId(),
                $this->orderInitialData->getUserId(),
                $this->orderInitialData->getTotalPrice(),
                $newStatus,
                $this->orderInitialData->getShippingAddress(),
                $this->orderInitialData->getCreatedAt()
            );

            // Actualizar el estado del pedido
            $orderService = orderAppService::GetSingleton();
            $updateResult = $orderService->updateOrder($updatedOrder);

            if (!$updateResult) {
                $result[] = 'No se pudo actualizar el estado del pedido.';
            } else {
                // Redirigir a la página de gestión de pedidos
                $result = 'manageOrders.php';
            }
        }

        return $result;
    }
}
