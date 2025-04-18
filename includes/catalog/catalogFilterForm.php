<?php

namespace TheBalance\catalog;

use TheBalance\views\common\baseForm;
use TheBalance\product\productAppService;

/**
 * Formulario para filtrar productos en el catálogo.
 */
class catalogFilterForm extends baseForm
{
    public function __construct()
    {
        parent::__construct('catalogFilterForm');
    }

    /**
     * Crea los campos del formulario.
     * 
     * @param array $initialData Datos iniciales del formulario.
     * 
     * @return string Campos del formulario.
     */
    protected function CreateFields($initialData)
    {
        // Obtener las categorías desde el servicio de productos
        $productAppService = productAppService::GetSingleton();
        $categories = $productAppService->getCategories();

        $html = <<<EOF
            <fieldset class="border p-4 rounded">
                <legend class="w-auto">Filtrar Productos</legend>

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre:</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Buscar por nombre" value="
        EOF;

        $html .= htmlspecialchars($initialData['name'] ?? '') . '">';

        $html .= <<<EOF
                </div>

                <div class="mb-3">
                    <label for="category" class="form-label">Categoría:</label>
                    <select name="category" id="category" class="form-control">
                        <option value="">Seleccionar categoría</option>
        EOF;

        // Añadir las categorías como opciones
        foreach ($categories as $category) {
            // Acceder a las propiedades del objeto productCategoryDTO
            $categoryId = htmlspecialchars($category->getId());
            $categoryName = htmlspecialchars($category->getName());
            $selected = (isset($initialData['category']) && $initialData['category'] == $categoryName) ? 'selected' : '';
            $html .= '<option value="' . $categoryName . '" ' . $selected . '>' . $categoryName . '</option>';
        }

        $html .= <<<EOF
                    </select>
                </div>

                <div class="mb-3">
                    <label for="minPrice" class="form-label">Precio mínimo:</label>
                    <input type="number" name="minPrice" id="minPrice" class="form-control" step="0.50" min="0" placeholder="Ej: 0" value="0">
                </div>

                <div class="mb-3">
                    <label for="maxPrice" class="form-label">Precio máximo:</label>
                    <input type="number" name="maxPrice" id="maxPrice" class="form-control" step="0.50" placeholder="Ej: 100" value="1000">
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </fieldset>
        EOF;

        return $html;
    }

    /**
     * Procesa los datos del formulario.
     * 
     * @param array $data Datos del formulario.
     * 
     * @return array|string Errores de procesamiento o redirección.
     */
    protected function Process($data)
    {
        // Array para almacenar mensajes de error
        $result = array();

        // Filtrado y sanitización de los datos recibidos
        $name = trim($data['name'] ?? '');
        $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (strlen($name) > 50) {
            $result[] = 'El nombre del producto no puede estar vacío ni superar los 50 caracteres.';
        }

        $category = trim($data['category'] ?? '');
        $category = filter_var($category, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (strlen($category) > 50) {
            $result[] = 'La categoría del producto no puede estar vacía ni superar los 50 caracteres.';
        }

        $minPrice = filter_var($data['minPrice'] ?? 0.0, FILTER_VALIDATE_FLOAT);
        if (!is_numeric($minPrice ) || $minPrice  < 0) {
            $result[] = 'El precio debe ser un número positivo.';
        }

        $maxPrice = filter_var($data['maxPrice'] ?? 1000.0, FILTER_VALIDATE_FLOAT);
        if (!is_numeric($maxPrice ) || $maxPrice  < 0) {
            $result[] = 'El precio debe ser un número positivo.';
        }

        if(count($result) === 0)
        {
            // Crear un diccionario con los filtros seleccionados
            $filters = array();
            $filters['name'] = $name;
            $filters['category'] = $category;
            $filters['minPrice'] = $minPrice;
            $filters['maxPrice'] = $maxPrice;
            $filters['active'] = 1;

            // Llamamos a la instancia de SA de productos
            $productAppService = productAppService::GetSingleton();

            // Buscamos los productos con los filtros seleccionados
            $foundedProductsDTO = $productAppService->searchProducts($filters);

            // Array de productos en formato JSON
            $foundedProductsJSON = json_encode($foundedProductsDTO);

            // Almacenar el JSON en una variable de sesión
            $_SESSION["foundedProductsJSON"] = $foundedProductsJSON;

            // Volvemos al catálogo con los productos filtrados
            $result = 'catalog.php?search=true';
        }

        return $result;
    }
}