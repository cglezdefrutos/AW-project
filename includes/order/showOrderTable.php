<?php

namespace TheBalance\order;

use TheBalance\views\common\baseTable;

class showOrderTable extends baseTable
{
    protected function generateTableContent()
    {
        $html = '';

        // Generar filas de la tabla
        foreach ($this->data as $order) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($order->getShippingAddress()) . '</td>';
            $html .= '<td>' . htmlspecialchars($order->getTotalPrice()) . ' €</td>';
            $html .= '<td>' . htmlspecialchars($order->getStatus()) . '</td>';
            $html .= '<td>' . htmlspecialchars($order->getCreatedAt()) . '</td>';
            $html .= '<td><a href="showOrderDetails.php?id=' . $order->getId() . '" class="btn-ver-detalle">Ver Detalle</a></td>';
            $html .= '</tr>';
        }

        return $html;
    }   
}