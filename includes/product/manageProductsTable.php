<?php

namespace TheBalance\product;

use TheBalance\views\common\baseTable;

class manageProductsTable extends baseTable
{
    protected function generateTableContent(): string
    {
        $html = '';

        // Generar filas de la tabla con productos activos
        foreach ($this->data as $product) {
            $html .= '<tr>';
            //$html .= '<td>' . htmlspecialchars($product->getProviderId()) . '</td>';
            $html .= '<td>' . htmlspecialchars($product->getName()) . '</td>';
            $html .= '<td>' . htmlspecialchars($product->getDescription()) . '</td>';
            $html .= '<td>' . htmlspecialchars($product->getPrice()) . '</td>';
            $html .= '<td>' . htmlspecialchars($product->getStock()) . '</td>';
            $html .= '<td>' . htmlspecialchars($product->getCategoryId()) . '</td>';
            //$html .= '<td>' . htmlspecialchars($product->getImageUrl()) . '</td>';
            $html .= '<td>' . htmlspecialchars($product->getActive()) . '</td>';
            //$html .= '<td>' . htmlspecialchars($product->getCreatedAt()) . '</td>';
            $html .= '<td>';
            $html .= '<a href="updateProducts.php?productId=' . htmlspecialchars($product->getId()) . '">Editar</a>';
            $html .= ' o ';
            $html .= '<a href="manageProducts.php?productId=' . htmlspecialchars($product->getId()) . '" onclick="return confirm(\'¿Estás seguro de que deseas eliminar este producto?\');">Eliminar</a>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        return $html;
    }

}
