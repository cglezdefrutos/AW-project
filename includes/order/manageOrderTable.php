<?php

namespace TheBalance\order;

use TheBalance\views\common\baseTable;

class manageOrderTable extends baseTable
{
    protected function generateTableContent()
    {
        $html = '';

        // Generar filas de la tabla
        foreach ($this->data as $order) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($order->getTotalPrice()) . '</td>';
            $html .= '<td>' . htmlspecialchars($order->getStatus()) . '</td>';
            $html .= '<td>' . htmlspecialchars($order->getCreatedAt()) . '</td>';
            $html .= '<td>';
            $html .= '<a href="updateOrders.php?orderId=' . htmlspecialchars($order->getId()) . '">Editar</a>';
            $html .= ' o ';
            $html .= '<a href="manageOrders.php?orderId=' . htmlspecialchars($order->getId()) . '" onclick="return confirm(\'¿Estás seguro de que deseas eliminar este pedido?\');">Eliminar</a>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        return $html;
    }   
}