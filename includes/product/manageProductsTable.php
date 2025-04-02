<?php

namespace TheBalance\product;

use TheBalance\views\common\baseTable;

class manageProductsTable extends baseTable
{
    protected function generateTableContent(): string
    {
        $html = '';

        foreach ($this->data as $product) {
            // Estado activo (Sí/No con colores)
            $activeText = $product->getActive() ? 'Sí' : 'No';
            $activeClass = $product->getActive() ? 'text-success' : 'text-danger';

            // Botón Editar (siempre visible)
            $editButton = <<<HTML
                <a href="?action=edit&productId={$product->getId()}" 
                   class="btn btn-primary btn-sm mr-2">
                   Editar
                </a>
            HTML;

            // Botón Eliminar (solo para productos activos)
            $deleteButton = '';
            if ($product->getActive()) {
                $deleteButton = <<<HTML
                    <a href="?action=delete&productId={$product->getId()}" 
                       class="btn btn-primary btn-sm mr-2" 
                       onclick="return confirm('¿Estás seguro de que deseas desactivar este producto?');">
                       Eliminar
                    </a>
                HTML;
            }

            // Botón Activar (solo para productos inactivos)
            $activateButton = '';
            if (!$product->getActive()) {
                $activateButton = <<<HTML
                    <a href="?action=activate&productId={$product->getId()}" 
                       class="btn btn-primary btn-sm" 
                       onclick="return confirm('¿Estás seguro de que deseas activar este producto?');">
                       Activar
                    </a>
                HTML;
            }

            $html .= <<<EOF
                <tr>
                    <td class="text-center align-middle">
                        <img src="{$product->getImageUrl()}" alt="{$product->getName()}" class="img-fluid" style="max-width: 80px; max-height: 80px;">
                    </td>
                    <td>{$product->getName()}</td>
                    <td>{$product->getDescription()}</td>
                    <td>{$product->getPrice()} €</td>
                    <td>{$product->getTotalStock()}</td>
                    <td>{$product->getCategoryName()}</td>
                    <td class="{$activeClass}">{$activeText}</td>
                    <td>{$product->getCreatedAt()}</td>
                    <td>
                        <div class="d-flex flex-wrap gap-2">
                            {$editButton}
                            {$deleteButton}
                            {$activateButton}
                        </div>
                    </td>
                </tr>
            EOF;
        }

        return $html;
    }
}