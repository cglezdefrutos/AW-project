<?php

namespace TheBalance\cart;

use TheBalance\views\common\baseForm;
use TheBalance\application;
use TheBalance\product\productDTO;

class addToCartForm extends baseForm
{
    private $productDTO;

    public function __construct($productDTO)
    {
        $this->productDTO = $productDTO;
    }

    /**
     * Genera los campos del formulario de añadir al carrito.
     * 
     * @return string HTML del formulario.
     */
    protected function CreateFields($initialData)
    {
        // Obtener las tallas del producto desde productSizesDTO
        $sizesDTO = $this->productDTO->getSizesDTO();
        $sizes = $sizesDTO->getSizes(); // Obtener el array ['talla' => stock]
        $sizeOptions = '';

        // Generar las opciones del select para las tallas
        foreach ($sizes as $size => $stock) {
            $disabled = ($stock <= 0) ? 'disabled' : ''; // Deshabilitar si no hay stock
            $stockMessage = ($stock <= 0) ? ' (Sin stock)' : ''; // Mostrar mensaje si no hay stock
            $sizeOptions .= "<option value=\"{$size}\" {$disabled}>{$size}{$stockMessage}</option>";
        }

        // Generar el HTML del formulario
        $html = <<<EOF
            <input type="hidden" name="product_id" value="{$this->productDTO->getId()}">

            <label for="product_size" class="form-label">Selecciona tu talla:</label>
            <select class="form-select" id="product_size" name="product_size" required>
                <option value="" disabled selected>Elige una talla</option>
                {$sizeOptions}
            </select>

            <button type="submit" class="btn btn-primary btn-lg w-100">Añadir al carrito</button>
        EOF;

        return $html;
    }

    /**
     * Procesa los datos enviados por el formulario.
     * 
     * @return void
     */
    public function Process($data)
    {
        $result = array();

        $productId = trim($data['product_id'] ?? '');
        $productId = filter_var($productId, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($productId) || !is_numeric($productId)) {
            $result[] = 'ID de producto inválido.';
        }

        $selectedSize = trim($data['product_size'] ?? '');
        $selectedSize = filter_var($selectedSize, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($selectedSize)) {
            $result[] = 'Debes seleccionar una talla antes de añadir el producto al carrito.';
        }

        // Si no es cliente, mostrar una alerta de que no se puede añadir al carrito
        if (!application::getInstance()->isCurrentUserClient()) 
        {
            // Mostrar un mensaje de error si no se pudo crear el pedido y un botón para volver al carrito
            /* $mainContent .= <<<EOS
                <div class="alert alert-danger d-flex flex-column flex-md-row align-items-center justify-content-between" role="alert">
                    <div>
                        <strong>¡Atención!</strong> No puedes añadir productos al carrito porque no has iniciado sesión como cliente.
                    </div>
                    <a href="login.php" class="btn btn-primary mt-3 mt-md-0">Iniciar sesión</a>
                </div>
            EOS; */

            $result[] = 'No puedes añadir productos al carrito porque no has iniciado sesión como cliente.';
        } 

        if (count($result) === 0) 
        {
            // Inicializar el carrito si no existe
            if (!isset($_SESSION['cart'])) 
            {
                $_SESSION['cart'] = [];
            }

            // Generar una clave única para el producto y la talla
            $cartKey = "{$productId}|{$selectedSize}";

            // Añade el producto al carrito o incrementa la cantidad si ya existe
            if (!isset($_SESSION['cart'][$cartKey])) 
            {
                $_SESSION['cart'][$cartKey] = 1;
            } else {
                $_SESSION['cart'][$cartKey]++;
            }

            // Guardar en la sesión que se acaba de añadir un producto
            $_SESSION['show_offcanvas'] = true;

            // Redirigir para evitar reenvíos del formulario
            $result = "productDetails.php?id={$productId}";
        }

        return $result;
    }
}