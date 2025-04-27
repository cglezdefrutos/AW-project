<?php

namespace TheBalance\product;

use TheBalance\views\common\baseForm;
use TheBalance\application;

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
     * Email del proveedor
     * 
     * @var string
     */
    private $provider_email;

    /**
     * Constructor
     * 
     * @param int $provider_id ID del proveedor
     * @param string $provider_email Email del proveedor
     */
    public function __construct($provider_id, $provider_email)
    {
        parent::__construct('registerProductForm', array('enctype' => 'multipart/form-data'));
        $this->provider_id = $provider_id;
        $this->provider_email = $provider_email;
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

        // Creamos el campo categoría en funcion del tipo de usuario
        $app = application::getInstance();
        $categoriesHtml = '';
        if ($app->isCurrentUserProvider()) 
        {
            $categories = productAppService::GetSingleton()->getCategories();
            $categoriesHtml = '<select name="category_id" id="category_id" class="form-control" required>';
            foreach ($categories as $category) {
                // Acceder al nombre de la categoría usando el método getName()
                $categoryName = $category->getName();
                $selected = ($initialData['category_id'] ?? '') === $categoryName ? 'selected' : '';
                $categoriesHtml .= '<option value="' . htmlspecialchars($categoryName) . '" ' . $selected . '>' . htmlspecialchars($categoryName) . '</option>';
            }
            $categoriesHtml .= '</select>';
        }
        else
        {
            // Si es administrador, permitimos un input de texto
            $categoriesHtml = <<<EOF
                <input type="text" name="category_id" id="category_id" class="form-control" min="1" placeholder="Ej: Fútbol, Baloncesto" value="
            EOF;
            $categoriesHtml .= htmlspecialchars($initialData['category_id'] ?? '') . '" required>';
        }
        
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
                    <label for="category_id" class="form-label">Categoría:</label>
                    $categoriesHtml
                </div>

                <!--Imagen -->
                <div class="mb-3">
                    <label for="image" class="form-label">Imagen del producto:</label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                    <small class="form-text text-muted">Formatos permitidos: JPEG, JPG o PNG.</small>
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

        $category = trim($data['category_id'] ?? '');
        $category = filter_var($category, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($category) || strlen($category) > 50) {
            $result[] = 'La categoría es obligatoria y no debe exceder los 50 caracteres.';
        }

        // Validación del campo de imagen
        $image = $_FILES['image'] ?? null;

        if (!isset($image) || $image['error'] === UPLOAD_ERR_NO_FILE) {
            $result[] = 'La imagen del producto es obligatoria.';
        } elseif ($image['error'] !== UPLOAD_ERR_OK) {
            $result[] = 'Error al subir la imagen: ' . $this->getUploadError($_FILES['image']['error']);
        } else {
            // Validar tipo de archivo
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($fileInfo, $image['tmp_name']);
            finfo_close($fileInfo);
            
            if (!in_array($mimeType, $allowedTypes)) {
                $result[] = 'El archivo debe ser una imagen (JPEG, JPG o PNG).';
            }
        }

        // Procesamiento del stock por tallas
        $stock = $data['stock'] ?? [];
        $validSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        $sizeData = [];
        
        foreach ($validSizes as $size) {
            $quantity = isset($stock[$size]) ? trim($stock[$size]) : 0;
            $quantity = filter_var($quantity, FILTER_SANITIZE_NUMBER_INT);
            if ($quantity < 0) {
                $result[] = "El stock para la talla $size debe ser un número positivo.";
            }
            $sizeData[$size] = $quantity;
        }

        // Si no hay errores, pasar los datos al servicio
        if (count($result) === 0) {
            // Creamos un array con los datos del nuevo producto
            $productData = [
                'provider_id' => $this->provider_id,
                'provider_email' => $this->provider_email,
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'category' => $category,
                'image' => $image, // Pasar la imagen al servicio
                'stock' => $sizeData,
                'created_at' => date('Y-m-d H:i:s'),
                'active' => true,
            ];

            // Obtenemos la instancia del servicio de productos
            $productAppService = productAppService::GetSingleton();

            // Intentamos registrar el nuevo producto
            $productId = $productAppService->registerProduct($productData);
            
            if (!$productId) {
                $result[] = 'Error al registrar el producto. Por favor, verifica los datos.';
            } else {
                $result = 'registerProducts.php?registered=true';
            }
        }

        return $result;
    }   
}