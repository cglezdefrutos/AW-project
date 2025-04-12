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
            $active = $product->getActive();
            $activeText = $active ? 'Sí' : 'No';
            $activeClass = $active ? 'text-success' : 'text-danger';

            // Botón Editar (siempre visible)
            $editButton = <<<EOF
                <button class="btn btn-primary btn-sm edit-product" data-id="{$product->getId()}">
                    Editar
                </button>
            EOF;

            // Botón Eliminar (solo para productos activos)
            $deleteButton = '';
            if ($active) {
                $deleteButton = <<<EOF
                    <button class="btn btn-danger btn-sm delete-product" data-id="{$product->getId()}">
                        Eliminar
                    </button>
                EOF;
            }

            // Botón Activar (solo para productos inactivos)
            $activateButton = '';
            if (!$active) {
                $activateButton = <<<EOF
                    <button class="btn btn-success btn-sm activate-product" data-id="{$product->getId()}">
                        Activar
                    </button>
                EOF;
            }

            // Construir URL de la imagen
            $imageUrl = productAppService::GetSingleton()->getProductImagePath($product->getImageGuid());

            $html .= <<<EOF
                <tr>
                    <td class="text-center align-middle">
                        <img src="{$imageUrl}" alt="{$product->getName()}" class="img-fluid table-image">
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