<?php

namespace TheBalance\cart;

use TheBalance\product\productAppService;

class orderSummary
{
    private $cart;

    public function __construct($cart)
    {
        $this->cart = $cart;
    }

    /**
     * Genera el contenido HTML del resumen del pedido.
     * 
     * @return string Contenido HTML.
     */
    public function generateContent()
    {
        // Calcular el subtotal y los gastos de envío
        $subtotal = $this->getSubtotal();
        $shippingCost = $this->calculateShippingCost($subtotal);
        $total = $subtotal + $shippingCost;

        $html = <<<EOF
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Resumen del pedido</h5>
                    <p class="card-text">Subtotal: <strong>{$subtotal} €</strong></p>
                    <p class="card-text">Gastos de envío: <strong>{$shippingCost} €</strong></p>
                    <p class="card-text">Total: <strong>{$total} €</strong></p>
                    <form action="shippingData.php" method="POST">
                        <input type="hidden" name="subtotal" value="{$subtotal}">
                        <input type="hidden" name="shipping_cost" value="{$shippingCost}">
                        <input type="hidden" name="total" value="{$total}">
                        <button type="submit" class="btn btn-success w-100">Ir al pago</button>
                    </form>
                </div>
            </div>
        EOF;

        return $html;
    }

    /**
     * Calcula el total del carrito.
     * 
     * @return float Total del carrito.
     */
    private function getSubtotal()
    {
        $subtotal = 0;

        foreach ($this->cart as $cartKey => $quantity) 
        {
            // Separar el product_id y la talla desde la clave del carrito
            [$productId, $size] = explode('|', $cartKey);
            
            $product = productAppService::GetSingleton()->getProductById($productId);
            if ($product) {
                $subtotal += $product->getPrice() * $quantity;
            }
        }

        return $subtotal;
    }

    /**
     * Calcula los gastos de envío en función del subtotal.
     * 
     * @param float $subtotal Subtotal del carrito.
     * @return float Gastos de envío.
     */
    private function calculateShippingCost($subtotal)
    {
        // Envío gratuito a partir de 50 €
        if ($subtotal >= 50) {
            return 0.0;
        }

        // Coste fijo de envío si el subtotal es menor a 50 €
        return 5.0;
    }
}