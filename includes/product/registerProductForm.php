<?php

namespace TheBalance\product;

use TheBalance\views\common\baseForm;

/**
 * Formulario de registro de productos
 */
class registerProductForm extends baseForm
{
    /**
     * ID del proveedor
     * 
     * @var int
     */
    private $provider_id;

    /**
     * Constructor
     * 
     * @param int $provider_id ID del proveedor
     */
    public function __construct($provider_id)
    {
        parent::__construct('registerProductForm');
        $this->provider_id = $provider_id;
    }

    /**
     * Crea los campos del formulario
     * 
     * @param array $initialData Datos iniciales del formulario
     * 
     * @return string Campos del formulario
     */
    protected function CreateFields($initialData)
    {
        // Definimos las tallas disponibles
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        
        // Creamos el formulario de registro de productos
        $html = <<<EOF
            <fieldset class="border p-4 rounded">
                <legend class="w-auto">Registro de Producto</legend>

                <!-- Campo Nombre del producto -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre del producto:</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Ej: Camiseta deportiva" value="
        EOF;

        $html .= htmlspecialchars($initialData['name'] ?? '') . '" required>';
        
        $html .= <<<EOF
                </div>

                <!-- Campo Descripción -->
                <div class="mb-3">
                    <label for="description" class="form-label">Descripción:</label>
                    <textarea name="description" id="description" class="form-control" placeholder="Describe el producto" rows="4" required>
        EOF;

        $html .= htmlspecialchars($initialData['description'] ?? '') . '</textarea>';
        
        $html .= <<<EOF
                </div>

                <!-- Campo Precio -->
                <div class="mb-3">
                    <label for="price" class="form-label">Precio (€):</label>
                    <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" placeholder="Ej: 24.99" value="
        EOF;

        $html .= htmlspecialchars($initialData['price'] ?? '') . '" required>';
        
        $html .= <<<EOF
                </div>

                <!-- Campo Categoría -->
                <div class="mb-3">
                    <label for="category_id" class="form-label">ID de Categoría:</label>
                    <input type="number" name="category_id" id="category_id" class="form-control" min="1" placeholder="Ej: 1 (Ropa), 2 (Accesorios)" value="
        EOF;

        $html .= htmlspecialchars($initialData['category_id'] ?? '') . '" required>';
        
        $html .= <<<EOF
                </div>

                <!-- Campo URL de la imagen -->
                <div class="mb-3">
                    <label for="image_url" class="form-label">URL de la imagen:</label>
                    <input type="url" name="image_url" id="image_url" class="form-control" placeholder="https://ejemplo.com/imagen.jpg" value="
        EOF;

        $html .= htmlspecialchars($initialData['image_url'] ?? '') . '" required>';
        
        $html .= <<<EOF
                </div>

                <!-- Sección de Stock por tallas -->
                <div class="mb-3">
                    <label class="form-label">Stock por tallas:</label>
                    <div class="row g-3">
        EOF;

        // Generamos los campos de stock para cada talla
        foreach ($sizes as $size) {
            $html .= <<<EOF
                        <div class="col-md-2">
                            <label for="stock_$size" class="form-label">Talla $size:</label>
                            <input type="number" name="stock[$size]" id="stock_$size" class="form-control" min="0" value="
            EOF;

            $html .= htmlspecialchars($initialData['stock'][$size] ?? '0') . '">';
            $html .= '</div>';
        }
        
        $html .= <<<EOF
                    </div>
                </div>

                <!-- Botón de registro -->
                <div class="mt-3">
                    <button type="submit" name="botonRegisterProduct" class="btn btn-primary w-100">Registrar Producto</button>
                </div>
            </fieldset>
        EOF;

        return $html;
    }

    /**
     * Procesa los datos del formulario
     * 
     * @param array $data Datos del formulario
     * 
     * @return array|string Errores de procesamiento|Redirección
     */
    protected function Process($data)
    {
        // Array para almacenar mensajes de error
        $result = array();

        // Filtrado y sanitización de los datos recibidos
        $name = trim($data['name'] ?? '');
        $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($name) || strlen($name) > 50) {
            $result[] = 'El nombre del producto es obligatorio y no debe exceder los 50 caracteres.';
        }

        $description = trim($data['description'] ?? '');
        $description = filter_var($description, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($description) || strlen($description) > 1000) {
            $result[] = 'La descripción es obligatoria y no debe exceder los 1000 caracteres.';
        }

        $price = trim($data['price'] ?? '');
        if (!is_numeric($price) || $price < 0) {
            $result[] = 'El precio debe ser un número positivo.';
        }

        $category_id = trim($data['category_id'] ?? '');
        if (!is_numeric($category_id) || $category_id < 1) {
            $result[] = 'La categoría debe ser un número válido.';
        }

        $image_url = trim($data['image_url'] ?? '');
        $image_url = filter_var($image_url, FILTER_SANITIZE_URL);
        if (empty($image_url) || !filter_var($image_url, FILTER_VALIDATE_URL)) {
            $result[] = 'La URL de la imagen no es válida.';
        }

        // Procesamiento del stock por tallas
        $stock = $data['stock'] ?? [];
        $validSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        $sizeData = [];
        
        foreach ($validSizes as $size) {
            $quantity = isset($stock[$size]) ? (int)$stock[$size] : 0;
            if ($quantity < 0) {
                $result[] = "El stock para la talla $size debe ser un número positivo.";
            }
            $sizeData[$size] = $quantity;
        }

        if (count($result) === 0) {
            // Creamos un array con los datos del nuevo producto
            $productData = array(
                'provider_id' => $this->provider_id,
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'category_id' => $category_id,
                'image_url' => $image_url,
                'sizes' => $sizeData
            );

            // Obtenemos la instancia del servicio de productos
            $productAppService = productAppService::GetSingleton();

            // Intentamos registrar el nuevo producto
            $registrationResult = $productAppService->registerProduct($productData);

            if (!$registrationResult) {
                $result[] = 'Error al registrar el producto. Por favor, verifica los datos.';
            } else {
                $result = 'registerProducts.php?registered=true';
            }
        }

        return $result;
    }
}