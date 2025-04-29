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
            $html .= '<td>' . htmlspecialchars($order->getEmail()) . '</td>';
            $html .= '<td>' . htmlspecialchars($order->getShippingAddress()) . '</td>';
            $html .= '<td>' . htmlspecialchars($order->getTotalPrice()) . ' €</td>';
            $html .= '<td>' . htmlspecialchars($order->getStatus()) . '</td>';
            $html .= '<td>' . htmlspecialchars($order->getCreatedAt()) . '</td>';
            $html .= '<td>';
            $html .= '<button class="btn btn-info view-order" data-id="' . htmlspecialchars($order->getId()) . '">Ver Detalles</button> ';
            $html .= '<button class="btn btn-primary edit-order mt-2" data-id="' . htmlspecialchars($order->getId()) . '">Cambiar Estado</button> ';
            $html .= '<button class="btn btn-danger delete-order mt-2" data-id="' . htmlspecialchars($order->getId()) . '">Eliminar</button>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        return $html;
    }   
}