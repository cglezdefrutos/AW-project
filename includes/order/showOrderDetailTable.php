<?php

namespace TheBalance\order;

use TheBalance\views\common\baseTable;

class showOrderDetailTable extends baseTable
{
    protected function generateTableContent()
    {
        $html = '';
        $total = 0;

        // Generar filas de la tabla
        foreach ($this->data as $detail) {
            $subtotal = $detail->getSubtotal();
            $total += $subtotal;
            $imageUrl = '/AW-project/img/' . $detail->getImageGuid() . '.png';
            
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($detail->getProductId()) . '</td>'; //antes getProductId()
            $html .= '<td class="align-middle text-center"><img src="'.htmlspecialchars($imageUrl).'" class="product-image"></td>';
            $html .= '<td>' . htmlspecialchars($detail->getSize()) . '</td>';
            $html .= '<td>' . htmlspecialchars($detail->getQuantity()) . '</td>';
            $html .= '<td>' . htmlspecialchars(number_format($detail->getPrice(), 2)) . ' €</td>';
            $html .= '<td>' . htmlspecialchars(number_format($subtotal, 2)) . ' €</td>';
            $html .= '</tr>';
        }

        // Fila de total
        $html .= '<tr class="table-active">';
        $html .= '<td colspan="5"></td>';
        $html .= '<td class="text-center"><strong>'.number_format($total, 2).' €</strong></td>';
        $html .= '</tr>';

        return $html;
    }
}