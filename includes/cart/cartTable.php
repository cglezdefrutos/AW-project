<?php
// filepath: c:\xampp\htdocs\AW-project\includes\cart\cartTable.php

namespace TheBalance\cart;

use TheBalance\views\common\baseTable;
use TheBalance\product\productAppService;

class cartTable extends baseTable
{
    private $productAppService;

    public function __construct(array $cart, array $columns)
    {
        parent::__construct($cart, $columns);
        $this->productAppService = productAppService::GetSingleton();
    }

    /**
     * Genera el contenido de las filas de la tabla.
     * 
     * @return string Contenido HTML de las filas.
     */
    protected function generateTableContent()
    {
        $html = '';
        foreach ($this->data as $cartKey => $quantity) {
            // Separar el product_id y la talla desde la clave del carrito
            [$productId, $size] = explode('|', $cartKey);

            // Obtener el producto usando el product_id
            $product = $this->productAppService->getProductById($productId);
            if (!$product) {
                continue;
            }

            // Calcular el subtotal
            $subtotal = $product->getPrice() * $quantity;

            $html .= <<<EOF
                <tr>
                    <td class="text-center align-middle">
                        <img src="{$product->getImageUrl()}" alt="{$product->getName()}" class="img-fluid" style="max-width: 80px; max-height: 80px;">
                    </td>
                    
                    <td class="text-center align-middle">{$product->getName()}</td>

                    <td class="text-center align-middle">{$product->getPrice()} €</td>

                    <td class="text-center align-middle">{$size}</td> 

                    <td class="text-center align-middle w-25">
                        <form action="cart.php" method="POST" class="d-flex flex-column flex-md-row align-items-center">
                            <input type="hidden" name="cart_key" value="{$cartKey}">
                            <input type="number" name="quantity" value="{$quantity}" min="1" class="form-control mb-2 mb-md-0 me-md-2" style="width: 80px;">
                            <button type="submit" name="update_quantity" class="btn btn-primary">Actualizar</button>
                        </form>
                    </td>

                    <td class="text-center align-middle">{$subtotal} €</td>

                    <td class="text-center align-middle">
                        <form action="cart.php" method="POST" class="d-inline align-items-center w-20">
                            <input type="hidden" name="cart_key" value="{$cartKey}">
                            <button type="submit" name="remove_product" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            EOF;
        }

        return $html;
    }
}