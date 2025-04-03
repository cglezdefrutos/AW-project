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
        parent::__construct('updateProductForm');
        $this->productInitialData = $productInitialData;
    }

    /**
     * Crea los campos del formulario
     * 
     * @return string Campos del formulario
     */
    protected function CreateFields($initialData) 
    {
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        
        $html = <<<HTML
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Actualizar Producto</h3>
            </div>
            <div class="card-body">
                <form method="post">
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
                    
                    <!-- URL Imagen -->
                    <div class="mb-3">
                        <label class="form-label">URL de la imagen:</label>
                        <input type="url" name="imageUrl" class="form-control" 
                            value="{$this->productInitialData->getImageUrl()}" required>
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
                </form>
            </div>
        </div>
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

        $imageUrl = trim($data['imageUrl'] ?? '');
        $imageUrl = filter_var($imageUrl, FILTER_SANITIZE_URL);
        if (empty($imageUrl) || !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            $result[] = 'La URL de la imagen no es válida.';
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
                $categoryDTO,   // Pasamos el DTO de categoría completo
                $imageUrl,
                $this->productInitialData->getCreatedAt(),
                $sizesDTO,      // Pasamos el DTO de tallas
                $this->productInitialData->getActive()
            );
            
            // Resto del código permanece igual...
            $productAppService = productAppService::GetSingleton();
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