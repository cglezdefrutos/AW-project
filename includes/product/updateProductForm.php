<?php

namespace TheBalance\product;

use TheBalance\views\common\baseForm;

/**
 * Formulario de actualización de productos
 */
class updateProductForm extends baseForm
{
    /**
     * Datos iniciales del producto
     * 
     * @var productDTO
     */
    private $productInitialData;
    
    /**
     * Constructor
     * 
     * @param productDTO $productInitialData Datos iniciales del producto
     */
    public function __construct($productInitialData)
    {
        parent::__construct('updateProductForm', array('enctype' => 'multipart/form-data'));
        $this->productInitialData = $productInitialData;
    }

    /**
     * Crea los campos del formulario
     * 
     * @return string Campos del formulario
     */
    protected function CreateFields($initialData) 
    {
        // Construir la ruta de la imagen
        $imageUrl = productAppService::GetSingleton()->getProductImagePath($this->productInitialData->getImageGuid());


        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        
        $html = <<<HTML
            <fieldset class="border p-4 rounded">
            <legend class="w-auto">Registro de Producto</legend> <br>
                <!-- ID del producto -->
                <input type="hidden" name="productId" value="{$this->productInitialData->getId()}">
                
                <!-- Nombre -->
                <div class="mb-3">
                    <label class="form-label">Nombre del producto:</label>
                    <input type="text" name="name" class="form-control" 
                        value="{$this->productInitialData->getName()}" required>
                </div>
                
                <!-- Descripción -->
                <div class="mb-3">
                    <label class="form-label">Descripción:</label>
                    <textarea name="description" class="form-control" rows="3" required>{$this->productInitialData->getDescription()}</textarea>
                </div>
                
                <!-- Precio -->
                <div class="mb-3">
                    <label class="form-label">Precio (€):</label>
                    <input type="number" name="price" class="form-control" step="0.01"
                        value="{$this->productInitialData->getPrice()}" required>
                </div>
                
                <!-- Stock por tallas -->
                <div class="mb-3">
                    <label class="form-label">Stock por tallas:</label>
                    <div class="d-flex flex-wrap gap-3">
        HTML;

        foreach ($sizes as $size) {
            $stock = $this->productInitialData->getStockBySize($size);
            $html .= <<<HTML
                        <div style="min-width: 120px;">
                            <label>Talla {$size}:</label>
                            <input type="number" name="stock[{$size}]" class="form-control" 
                                value="{$stock}" min="0">
                        </div>
            HTML;
        }

        $html .= <<<HTML
                    </div>
                </div>
                    
                <!-- Imagen -->
                <div class="mb-3">
                    <label class="form-label">Imagen del producto:</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="form-text text-muted">Formatos permitidos: JPEG, JPG o PNG. Dejar vacío para mantener la imagen actual.</small>
                    <div class="mt-2">
                        <img src="{$imageUrl}" alt="Imagen actual" style="max-height: 200px;">
                    </div>
                </div>
                
                <!-- Categoría -->
                <div class="mb-3">
                    <label class="form-label">Categoría:</label>
                    <input type="text" name="category" class="form-control" 
                        value="{$this->productInitialData->getCategoryName()}" required>
                </div>
                
                <!-- Botón -->
                <div class="mt-4">
                    <button type="submit" name="update_product" 
                            class="btn btn-primary w-100 py-2">
                        Actualizar Producto
                    </button>
                </div>
            </fieldset>
        HTML;
        
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

        // Tomamos la instancia del servicio de aplicación de productos
        $productAppService = productAppService::GetSingleton();

        // Filtrado y sanitización de los datos recibidos
        $productName = trim($data['name'] ?? '');
        $productName = filter_var($productName, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($productName) || strlen($productName) > 50) {
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

        $category = trim($data['category'] ?? '');
        $category = filter_var($category, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($category) || strlen($category) > 50) {
            $result[] = 'La categoría es obligatoria y no debe exceder los 50 caracteres.';
        }

        // Comprobar si se ha subido una nueva imagen
        $imageGUID = null;
        
        // Si se ha subido una nueva imagen, procesarla
        if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES['image'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'image/webp'];
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($fileInfo, $image['tmp_name']);
            finfo_close($fileInfo);
            
            if (in_array($mimeType, $allowedTypes)) {
                // Generar un nuevo GUID para la imagen
                $imageGUID = $productAppService->saveImage($image);
            } else {
                $result[] = 'El archivo debe ser una imagen (JPEG, PNG o GIF).';
            }
        } else {
            // Si no se ha subido una nueva imagen, mantener la imagen actual
            $imageGUID = $this->productInitialData->getImageGuid();
        }

        // Procesamiento del stock por tallas
        $stock = $data['stock'] ?? [];
        $validSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        $sizeData = [];
        $totalStock = 0;
        
        foreach ($validSizes as $size) {
            $quantity = isset($stock[$size]) ? (int)$stock[$size] : 0;
            if ($quantity < 0) {
                $result[] = "El stock para la talla $size debe ser un número positivo.";
            }
            $sizeData[$size] = $quantity;
            $totalStock += $quantity;
        }

        if (count($result) === 0) {
            // 1. Crear el DTO de categoría (con ID null inicialmente)
            $categoryDTO = new productCategoryDTO(
                null,      // ID se establecerá más tarde
                $category   // Nombre de la categoría
            );
    
            // 2. Crear el DTO de tallas
            $idProduct = $this->productInitialData->getId();
            $sizesDTO = new productSizesDTO($idProduct, $sizeData);
    
            // 3. Crear el DTO del producto con todos los componentes
            $updatedProductDTO = new productDTO(
                $idProduct,
                $this->productInitialData->getProviderEmail(),
                $productName,
                $description,
                $price,
                $categoryDTO,
                $imageGUID,
                $this->productInitialData->getCreatedAt(),
                $sizesDTO,
                $this->productInitialData->getActive()
            );
            
            $updateResult = $productAppService->updateProduct($updatedProductDTO);
    
            if(!$updateResult) {
                $result[] = 'No se ha podido actualizar el producto.';
            } else {
                $result = 'manageProducts.php';
            }
        }
    
        return $result;
    }
}