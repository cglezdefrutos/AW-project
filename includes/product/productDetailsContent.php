<?php

namespace TheBalance\product;

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
        // Verificar si se debe mostrar el Offcanvas
        $showOffcanvas = isset($_SESSION['show_offcanvas']) ? 'show' : ''; 

        // Limpiar la sesión para que el Offcanvas no se muestre en la siguiente recarga
        unset($_SESSION['show_offcanvas']);

        // Obtener las tallas del producto
        $sizes = $this->productDTO->getSizes();
        $sizeOptions = '';

        // Generar las opciones del select para las tallas
        foreach ($sizes as $size) {
            $sizeOptions .= "<option value=\"{$size}\">{$size}</option>";
        }

        $html = <<<EOF
            <div class="row">
                <div class="col-md-6 d-flex justify-content-center align-items-center">
                    <img src="{$this->productDTO->getImageUrl()}" class="img-fluid rounded" alt="{$this->productDTO->getName()}">
                </div>

                <div class="col-md-6 mt-5 px-5">
                    <h1 class="mb-3">{$this->productDTO->getName()}</h1>
                    <p class="text-muted">{$this->productDTO->getDescription()}</p>
                    <h3 class="text-success mb-4">{$this->productDTO->getPrice()} €</h3>

                    <form action="" method="POST">
                        <input type="hidden" name="product_id" value="{$this->productDTO->getId()}">

                        <div class="mb-3">
                            <label for="product_size" class="form-label">Selecciona tu talla:</label>
                            <select class="form-select" id="product_size" name="product_size" required>
                                <option value="" disabled selected>Elige una talla</option>
                                {$sizeOptions}
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">Añadir al carrito</button>
                    </form>
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