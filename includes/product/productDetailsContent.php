<?php

namespace TheBalance\product;

use TheBalance\cart\addToCartForm;

class productDetailsContent
{
    private $productDTO;

    public function __construct($productDTO)
    {
        $this->productDTO = $productDTO;
    }

    /**
     * Genera el contenido HTML para los detalles del producto.
     * 
     * @return string Contenido HTML.
     */
    public function generateContent()
    {
        $html = '';
        
        // Verificar si se debe mostrar el Offcanvas
        $showOffcanvas = isset($_SESSION['show_offcanvas']) ? 'show' : ''; 

        // Limpiar la sesión para que el Offcanvas no se muestre en la siguiente recarga
        unset($_SESSION['show_offcanvas']);

        $html .= <<<EOF
            <div class="row">
                <div class="col-md-6 d-flex justify-content-center align-items-center">
                    <img src="{$this->productDTO->getImageUrl()}" class="img-fluid rounded" alt="{$this->productDTO->getName()}">
                </div>

                <div class="col-md-6 mt-5 px-5">
                    <h1 class="mb-3">{$this->productDTO->getName()}</h1>
                    <p class="text-muted">{$this->productDTO->getDescription()}</p>
                    <h3 class="text-success mb-4">{$this->productDTO->getPrice()} €</h3>
        EOF;

        // Generar el formulario de añadir al carrito
        $form = new addToCartForm($this->productDTO);
        $htmlAddToCartForm = $form->Manage();

        $html .= <<<EOF
                    $htmlAddToCartForm
                </div>
            </div>

            <div class="offcanvas offcanvas-end {$showOffcanvas}" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="cartOffcanvasLabel">Producto añadido al carrito</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <p>¿Qué deseas hacer ahora?</p>
                    <a href="cart.php" class="btn btn-success w-100 mb-2">Ir al carrito</a>
                    <a href="catalog.php" class="btn btn-secondary w-100">Seguir comprando</a>
                </div>
            </div>
        EOF;

        return $html;
    }
}