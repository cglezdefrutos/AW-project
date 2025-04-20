<?php

require_once '../config.php';

use TheBalance\product\productAppService;
use TheBalance\product\productDTO;
use TheBalance\product\productCategoryDTO;
use TheBalance\product\productSizesDTO;
use TheBalance\utils\utilsFactory;

$action = $_POST['action'] ?? null;

if ($action) {
    $productAppService = productAppService::GetSingleton();

    switch ($action) {
        case 'getProduct':
            $productId = $_POST['productId'];
            $product = $productAppService->getProductById($productId);

            // Tomar la URL de la imagen del producto
            $imageUrl = $productAppService->getProductImagePath($product->getImageGuid());

            // Tomar las tallas del producto
            $sizes = $product->getSizesDTO()->getSizes();

            if ($product) {
                echo json_encode([
                    'success' => true,
                    'data' => [
                        'id' => $product->getId(),
                        'name' => $product->getName(),
                        'description' => $product->getDescription(),
                        'price' => $product->getPrice(),
                        'category' => $product->getCategoryName(),
                        'sizes' => $sizes,
                        'image' => $imageUrl,
                        'imageGUID' => $product->getImageGuid(),
                        'createdAt' => $product->getCreatedAt(),
                        'active' => $product->getActive(),
                        'providerEmail' => $product->getProviderEmail()
                    ]
                ]);
            } else {
                $alert = utilsFactory::createAlert('Producto no encontrado.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
            }
            break;

        case 'updateProduct':
            // Filtrado y sanitización de los datos recibidos
            $productName = trim($_POST['name'] ?? '');
            $productName = filter_var($productName, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if (empty($productName) || strlen($productName) > 50) {
                $alert = utilsFactory::createAlert('El nombre del producto es obligatorio y no debe exceder los 50 caracteres.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }

            $description = trim($_POST['description'] ?? '');
            $description = filter_var($description, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if (empty($description) || strlen($description) > 1000) {
                $alert = utilsFactory::createAlert('La descripción es obligatoria y no debe exceder los 1000 caracteres.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }

            $price = trim($_POST['price'] ?? '');
            if (!is_numeric($price) || $price < 0) {
                $alert = utilsFactory::createAlert('El precio debe ser un número positivo.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }

            $category = trim($_POST['category'] ?? '');
            $category = filter_var($category, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if (empty($category) || strlen($category) > 50) {
                $alert = utilsFactory::createAlert('La categoría es obligatoria y no debe exceder los 50 caracteres.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
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
                    $alert = utilsFactory::createAlert('Tipo de imagen no permitido. Solo se permiten JPEG, PNG, GIF y WEBP.', 'danger');
                    echo json_encode(['success' => false, 'alert' => $alert]);
                    exit;
                }
            } else {
                // Si no se ha subido una nueva imagen, mantener la imagen actual
                $imageGUID = $_POST['currentImageGUID'];
            }

            // Procesamiento del stock por tallas
            $stock = $_POST['stock'] ?? [];
            $validSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
            $sizeData = [];
            $totalStock = 0;
            
            foreach ($validSizes as $size) {
                $quantity = isset($stock[$size]) ? (int)$stock[$size] : 0;
                if ($quantity < 0) {
                    $alert = utilsFactory::createAlert('La cantidad de stock no puede ser negativa.', 'danger');
                    echo json_encode(['success' => false, 'alert' => $alert]);
                    exit;
                }
                $sizeData[$size] = $quantity;
                $totalStock += $quantity;
            }

            // Crear el DTO de categoría (con ID null inicialmente)
            $categoryDTO = new productCategoryDTO(
                null,      // ID se establecerá más tarde
                $category   // Nombre de la categoría
            );
    
            // Crear el DTO de tallas
            $idProduct = $_POST['productId'] ?? null;
            $sizesDTO = new productSizesDTO($idProduct, $sizeData);

            // Email del proveedor
            $providerEmail = $_POST['productEmailProvider'] ?? null;

            // Created At del producto
            $createdAt = $_POST['productCreatedAt'] ?? null;

            // Campo active del producto
            $active = $_POST['productActive'] ?? null;
    
            // Crear el DTO del producto con todos los componentes
            $updatedProductDTO = new productDTO(
                $idProduct,
                $providerEmail,
                $productName,
                $description,
                $price,
                $categoryDTO,
                $imageGUID,
                $createdAt,
                $sizesDTO,
                $active
            );
           
            $updateResult = $productAppService->updateProduct($updatedProductDTO);

            if ($updateResult) {
                $alert = utilsFactory::createAlert('Producto actualizado correctamente.', 'success');
                echo json_encode(['success' => true, 'alert' => $alert]);
            } else {
                $alert = utilsFactory::createAlert('Error al actualizar el producto.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
            }
            break;

        case 'deleteProduct':
            $productId = $_POST['productId'];
            $deleted = $productAppService->deleteProduct($productId);
            if ($deleted) {
                $alert = utilsFactory::createAlert('Producto eliminado correctamente.', 'success');
                echo json_encode(['success' => true, 'alert' => $alert]);
            } else {
                $alert = utilsFactory::createAlert('Error al eliminar el producto.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
            }
            break;

        case 'activateProduct':
            $productId = $_POST['productId'];
            $activated = $productAppService->activateProduct($productId);
            if ($activated) {
                $alert = utilsFactory::createAlert('Producto activado correctamente.', 'success');
                echo json_encode(['success' => true, 'alert' => $alert]);
            } else {
                $alert = utilsFactory::createAlert('Error al activar el producto.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
            }
            break;

        default:
            $alert = utilsFactory::createAlert('Acción no válida.', 'danger');
            echo json_encode(['success' => false, 'alert' => $alert]);
            break;
    }
}