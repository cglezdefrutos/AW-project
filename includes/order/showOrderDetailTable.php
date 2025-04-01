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
            
            $html .= '<tr>';
            $html .= '<td class="align-middle text-center">' . htmlspecialchars($detail->getProductName()) . '</td>'; //antes getProductId()
            //$html .= '<td>' . htmlspecialchars($detail->getImageUrl()) . '</td>';
            //$html .= '<img src="'.htmlspecialchars($detail->getImageUrl()).'" class="product-thumb me-3">';
            $html .= '<td class="align-middle text-center"><img src="'.htmlspecialchars($detail->getImageUrl()).'" class="product-image"></td>';
            $html .= '<td class="align-middle text-center">' . htmlspecialchars($detail->getQuantity()) . '</td>';
            $html .= '<td class="align-middle text-center">' . htmlspecialchars(number_format($detail->getPrice(), 2)) . ' €</td>';
            $html .= '<td class="align-middle text-center">' . htmlspecialchars(number_format($subtotal, 2)) . ' €</td>';
            $html .= '</tr>';
        }

        // Fila de total
        $html .= '<tr class="table-active">';
        $html .= '<td colspan="4"></td>';
        $html .= '<td class="text-center"><strong>'.number_format($total, 2).' €</strong></td>';
        $html .= '</tr>';

        return $html;
    }
}