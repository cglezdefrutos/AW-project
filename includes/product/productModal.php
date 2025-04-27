<?php

namespace TheBalance\product;

/**
 * Clase para generar el modal de edición de productos
 */
class productModal
{
    /**
     * Genera el HTML del modal
     *
     * @return string HTML del modal
     */
    public static function generateEditModal(): string
    {
        return <<<EOS
        <!-- Modal para editar producto -->
        <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProductModalLabel">Editar Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editProductForm" method="POST" enctype="multipart/form-data">
                            <!-- ID del producto -->
                            <input type="hidden" name="productId" id="productId">
    
                            <!-- Campo Nombre del producto -->
                            <div class="mb-3">
                                <label for="productName" class="form-label">Nombre del producto:</label>
                                <input type="text" name="name" id="productName" class="form-control" required>
                            </div>
    
                            <!-- Campo Descripción -->
                            <div class="mb-3">
                                <label for="productDescription" class="form-label">Descripción:</label>
                                <textarea name="description" id="productDescription" class="form-control" rows="3" required></textarea>
                            </div>
    
                            <!-- Campo Precio -->
                            <div class="mb-3">
                                <label for="productPrice" class="form-label">Precio (€):</label>
                                <input type="number" name="price" id="productPrice" class="form-control" step="0.01" min="0" required>
                            </div>
    
                            <!-- Stock por tallas -->
                            <div class="mb-3">
                                <label class="form-label">Stock por tallas:</label>
                                <div class="d-flex flex-wrap gap-3">
                                    <div style="min-width: 120px;">
                                        <label for="stock_XS" class="form-label">Talla XS:</label>
                                        <input type="number" name="stock[XS]" id="stock_XS" class="form-control" min="0">
                                    </div>
                                    <div style="min-width: 120px;">
                                        <label for="stock_S" class="form-label">Talla S:</label>
                                        <input type="number" name="stock[S]" id="stock_S" class="form-control" min="0">
                                    </div>
                                    <div style="min-width: 120px;">
                                        <label for="stock_M" class="form-label">Talla M:</label>
                                        <input type="number" name="stock[M]" id="stock_M" class="form-control" min="0">
                                    </div>
                                    <div style="min-width: 120px;">
                                        <label for="stock_L" class="form-label">Talla L:</label>
                                        <input type="number" name="stock[L]" id="stock_L" class="form-control" min="0">
                                    </div>
                                    <div style="min-width: 120px;">
                                        <label for="stock_XL" class="form-label">Talla XL:</label>
                                        <input type="number" name="stock[XL]" id="stock_XL" class="form-control" min="0">
                                    </div>
                                    <div style="min-width: 120px;">
                                        <label for="stock_XXL" class="form-label">Talla XXL:</label>
                                        <input type="number" name="stock[XXL]" id="stock_XXL" class="form-control" min="0">
                                    </div>
                                </div>
                            </div>
    
                            <!-- Imagen -->
                            <div class="mb-3">
                                <label for="productImage" class="form-label">Imagen del producto:</label>
                                <input type="file" name="image" id="productImage" class="form-control" accept="image/*">
                                <small class="form-text text-muted">Deja este campo vacío si no deseas cambiar la imagen.</small>
                                <div class="mt-2">
                                    <img id="currentProductImage" src="" alt="Imagen actual" style="max-height: 200px;">
                                </div>
                                <!-- Campo oculto para el GUID de la imagen actual -->
                                <input type="hidden" name="currentImageGUID" id="currentImageGUID" value="">
                            </div>
    
                            <!-- Categoría -->
                            <div class="mb-3">
                                <label for="productCategory" class="form-label">Categoría:</label>
                                <!-- <input type="text" name="category" id="productCategory" class="form-control" required> -->
                                <div id="categoryField"></div> <!-- Este div será dinámico -->
                            </div>

                            <!-- Campo oculto para el proveedor de email -->
                            <input type="hidden" name="productEmailProvider" id="productEmailProvider" value="">

                            <!-- Campo oculto para la fecha de creación -->
                            <input type="hidden" name="productCreatedAt" id="productCreatedAt" value="">

                            <!-- Campo oculto para el estado activo -->
                            <input type="hidden" name="productActive" id="productActive" value="">
    
                            <!-- Botón para guardar cambios -->
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary w-100 py-2">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        EOS;
    }
}