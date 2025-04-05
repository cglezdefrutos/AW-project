<?php

namespace TheBalance\cart;

use TheBalance\views\common\baseForm;

class shippingDataForm extends baseForm
{
    /**
     * Constructor de la clase.
     */
    public function __construct()
    {
        parent::__construct('shippingData.php');
    }

    /**
     * Genera los campos del formulario.
     * 
     * @param array $initialData Datos iniciales para los campos.
     * @return string HTML de los campos del formulario.
     */
    protected function CreateFields($initialData)
    {
        $city = htmlspecialchars($initialData['city'] ?? '');
        $postalCode = htmlspecialchars($initialData['postal_code'] ?? '');
        $street = htmlspecialchars($initialData['street'] ?? '');
        $floor = htmlspecialchars($initialData['floor'] ?? '');

        // Obtener los valores de subtotal, shipping_cost y total del formulario enviado
        $subtotal = htmlspecialchars($_POST['subtotal'] ?? '0');
        $shippingCost = htmlspecialchars($_POST['shipping_cost'] ?? '0');
        $total = htmlspecialchars($_POST['total'] ?? '0');
        

        $html = <<<EOF
            <fieldset class="border p-4 rounded">
                <legend class="w-auto">Datos de envío</legend>
                <div class="mb-3">
                    <label for="city" class="form-label">Ciudad:</label>
                    <input type="text" name="city" id="city" class="form-control" value="{$city}" required>
                </div>
                <div class="mb-3">
                    <label for="postal_code" class="form-label">Código postal:</label>
                    <input type="text" name="postal_code" id="postal_code" class="form-control" value="{$postalCode}" required>
                </div>
                <div class="mb-3">
                    <label for="street" class="form-label">Calle:</label>
                    <input type="text" name="street" id="street" class="form-control" value="{$street}" required>
                </div>
                <div class="mb-3">
                    <label for="floor" class="form-label">Piso (opcional):</label>
                    <input type="text" name="floor" id="floor" class="form-control" value="{$floor}">
                </div>
                <input type="hidden" name="subtotal" value="{$subtotal}">
                <input type="hidden" name="shipping_cost" value="{$shippingCost}">
                <input type="hidden" name="total" value="{$total}">
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Continuar al pago</button>
                </div>
            </fieldset>
        EOF;

        return $html;
    }

    /**
     * Procesa los datos del formulario.
     * 
     * @param array $data Datos enviados por el formulario.
     * @return string|array Redirección o errores.
     */
    protected function Process($data)
    {
        // Array para almacenar mensajes de error
        $result = array();

        // Filtrado y sanitización de los datos recibidos
        $city = trim($data['city'] ?? '');
        $city = filter_var($city, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($city) || strlen($city) > 50) {
            $result[] = 'La ciudad es obligatoria y no debe exceder los 50 caracteres.';
        }

        $postalCode = trim($data['postal_code'] ?? '');
        $postalCode = filter_var($postalCode, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($postalCode) || !preg_match('/^\d{5}$/', $postalCode)) {
            $result[] = 'El código postal es obligatorio y debe tener 5 dígitos.';
        }

        $street = trim($data['street'] ?? '');
        $street = filter_var($street, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($street) || strlen($street) > 100) {
            $result[] = 'La calle es obligatoria y no debe exceder los 100 caracteres.';
        }

        $floor = trim($data['floor'] ?? '');
        $floor = filter_var($floor, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!empty($floor) && strlen($floor) > 10) {
            $result[] = 'El piso no debe exceder los 10 caracteres.';
        }

        // Si no hay errores en el formulario, redirigir a la página de pago
        if (count($result) === 0) 
        {
            // Guardar los datos de la dirección en la sesión
            $_SESSION['shipping_address'] = [
                'city' => $city,
                'postal_code' => $postalCode,
                'street' => $street,
                'floor' => $floor,
            ];

            // Pasar subtotal, shipping_cost y total a checkout.php
            $_SESSION['order_details'] = [
                'subtotal' => $data['subtotal'],
                'shipping_cost' => $data['shipping_cost'],
                'total' => $data['total'],
            ];

            $result = 'checkout.php';
        }

        return $result;
    }
}