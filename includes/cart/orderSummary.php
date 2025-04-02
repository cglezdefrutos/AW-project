<?php

namespace TheBalance\cart;

class orderSummary
{
    private $total;

    public function __construct($total)
    {
        $this->total = $total;
    }

    /**
     * Genera el contenido HTML del resumen del pedido.
     * 
     * @return string Contenido HTML.
     */
    public function generateContent()
    {
        $html = <<<EOF
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Resumen del pedido</h5>
                    <p class="card-text">Total: <strong>{$this->total} €</strong></p>
                    <form action="checkout.php" method="POST">
                        <button type="submit" class="btn btn-success w-100">Ir al pago</button>
                    </form>
                </div>
            </div>
        EOF;

        return $html;
    }
}