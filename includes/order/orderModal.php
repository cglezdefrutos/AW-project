<?php

namespace TheBalance\order;

/**
 * Clase para generar los modales relacionados con pedidos
 */
class orderModal
{
    /**
     * Genera el HTML del modal para editar el estado del pedido
     *
     * @return string HTML del modal
     */
    public static function generateEditModal(): string
    {
        return <<<EOS
            <!-- Modal para editar el estado del pedido -->
            <div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editOrderModalLabel">Editar Estado del Pedido</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editOrderForm" method="POST">
                                <input type="hidden" name="orderId" id="orderId">
                                
                                <div class="mb-3">
                                    <label class="form-label">Estado del Pedido</label>
                                    <div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" id="statusPreparation" value="En preparación">
                                            <label class="form-check-label" for="statusPreparation">En preparación</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" id="statusShipped" value="Enviado">
                                            <label class="form-check-label" for="statusShipped">Enviado</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" id="statusDelivered" value="Entregado">
                                            <label class="form-check-label" for="statusDelivered">Entregado</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" id="statusCancelled" value="Cancelado">
                                            <label class="form-check-label" for="statusCancelled">Cancelado</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        EOS;
    }

    /**
     * Genera el HTML del modal para mostrar los detalles del pedido
     *
     * @param string $tableContent Contenido de la tabla de detalles del pedido
     * @return string HTML del modal
     */
    public static function generateDetailsModal(string $tableContent = '<p>Cargando detalles...</p>'): string
    {
        return <<<EOS
            <!-- Modal para mostrar los detalles del pedido -->
            <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="orderDetailsModalLabel">Detalles del Pedido</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            {$tableContent}
                        </div>
                    </div>
                </div>
            </div>
        EOS;
    }
}