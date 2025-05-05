<?php

require_once '../config.php';

use TheBalance\plan\planAppService;
use TheBalance\plan\planDTO;
use TheBalance\utils\utilsFactory;
use TheBalance\application;

$action = $_POST['action'] ?? null;

if ($action) {
    $planAppService = planAppService::GetSingleton();

    switch ($action) {
        case 'getPlan':
            $planId = $_POST['planId'];
            $plan = $planAppService->getPlanById($planId);

            // Tomar la URL de la imagen del producto
            $imageUrl = $planAppService->getPlanImagePath($plan->getImageGuid());

            if ($plan) {
                echo json_encode([
                    'success' => true,
                    'data' => [
                        'id' => $plan->getId(),
                        'name' => $plan->getName(),
                        'description' => $plan->getDescription(),
                        'difficulty' => $plan->getDifficulty(),
                        'duration' => $plan->getDuration(),
                        'price' => $plan->getPrice(),
                        'image' => $imageUrl,
                        'imageGUID' => $plan->getImageGuid(),
                        'createdAt' => $plan->getCreatedAt()
                    ]
                ]);
            } else {
                $alert = utilsFactory::createAlert('Plan no encontrado.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
            }
            break;

        case 'updatePlan':
            $planId = $_POST['planId'];
            // Filtrado y sanitización de los datos recibidos
            $trainerId = $app->getCurrentUserId();
            $planName = trim($_POST['name'] ?? '');
            $planName = filter_var($planName, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if (empty($planName) || strlen($planName) > 50) {
                $alert = utilsFactory::createAlert('El nombre del plan es obligatorio y no debe exceder los 50 caracteres.', 'danger');
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
        
            $difficulty = trim($_POST['difficulty'] ?? '');
            $difficulty = filter_var($difficulty, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if (empty($difficulty) || strlen($difficulty) > 50) {
                $alert = utilsFactory::createAlert('La dificultad es obligatoria y no debe exceder los 50 caracteres.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }
        
            $duration = trim($_POST['duration'] ?? '');
            if (!is_numeric($duration) || $duration <= 0 || $duration > 1000) {
                $alert = utilsFactory::createAlert('La duración debe ser un número positivo menor a 1000.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }
        
            $price = trim($_POST['price'] ?? '');
            if (!is_numeric($price) || $price < 0) {
                $alert = utilsFactory::createAlert('El precio debe ser un número positivo.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }
        
            // Comprobar si se ha subido una nueva imagen
            $imageGUID = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image = $_FILES['image'];
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'image/webp'];
                $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($fileInfo, $image['tmp_name']);
                finfo_close($fileInfo);
        
                if (in_array($mimeType, $allowedTypes)) {
                    $imageGUID = $planAppService->savePlanImage($image);
                } else {
                    $alert = utilsFactory::createAlert('Tipo de imagen no permitido. Solo se permiten JPEG, PNG, GIF y WEBP.', 'danger');
                    echo json_encode(['success' => false, 'alert' => $alert]);
                    exit;
                }
            } else {
                $imageGUID = $_POST['currentImageGUID'];
            }

            // ✅ Procesar PDF del plan
            $pdfGUID = null;
            if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
                $pdf = $_FILES['pdf'];
                $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($fileInfo, $pdf['tmp_name']);
                finfo_close($fileInfo);

                if ($mimeType === 'application/pdf') {
                    $pdfGUID = $planAppService->savePlanPdf($pdf);
                } else {
                    throw new Exception("Solo se permiten archivos PDF.");
                }
            } else {
                $pdfGUID = $_POST['currentPdfGUID'] ?? null;
            }

        
        
            // Crear el DTO con los nuevos datos
            $updatedPlanDTO = new planDTO(
                $planId,
                $trainerId,
                $planName,
                $description,
                $difficulty,
                $duration,
                $price,
                $imageGUID,
                $pdfGUID,
                null
            );
        
            $updateResult = $planAppService->updatePlan($updatedPlanDTO);
        
            if ($updateResult) {
                $alert = utilsFactory::createAlert('Plan actualizado correctamente.', 'success');
                echo json_encode(['success' => true, 'alert' => $alert]);
            } else {
                $alert = utilsFactory::createAlert('Error al actualizar el plan.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
            }
            break;
        

        case 'deletePlan':
            $planId = $_POST['planId'];
            $deleted = $planAppService->deletePlan($planId);
            if ($deleted) {
                $alert = utilsFactory::createAlert('Plan eliminado correctamente.', 'success');
                echo json_encode(['success' => true, 'alert' => $alert]);
            } else {
                $alert = utilsFactory::createAlert('Error al eliminar el plan.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
            }
            break;

        case 'getPlanStatus':
            $planId = $_POST['planId'];
            $plan = $planAppService->getPlanPurchaseById($planId); // Método para obtener el plan por su ID
            if ($plan) {
                // Devolver los datos del plan como JSON, solo el estado es necesario
                echo json_encode([
                    'success' => true,
                    'data' => [
                        'id' => $plan->getId(),
                        'status' => $plan->getStatus(), // Solo el estado
                    ]
                ]);
            } else {
                $alert = utilsFactory::createAlert('Plan no encontrado.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
            }
            break;
    

        case 'updatePlanStatus':
            $planId = trim($_POST['planId'] ?? '');
            $planId = filter_var($planId, FILTER_SANITIZE_NUMBER_INT);
            if (!is_numeric($planId) || $planId <= 0) {
                $alert = utilsFactory::createAlert('ID de plan inválido.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }
        
            $status = trim($_POST['status'] ?? '');
            $status = filter_var($status, FILTER_SANITIZE_STRING);
            $validStatuses = ['Completado', 'Pausado', 'Activo']; // Estados válidos para el plan
            if (!in_array($status, $validStatuses)) {
                $alert = utilsFactory::createAlert('Estado seleccionado no válido.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
                exit;
            }
        
            // Actualizar el estado del plan
            $updated = $planAppService->updatePlanStatus($planId, $status); // Método para actualizar solo el estado del plan
        
            if ($updated) {
                $alert = utilsFactory::createAlert('Estado del plan actualizado correctamente.', 'success');
                echo json_encode(['success' => true, 'alert' => $alert]);
            } else {
                $alert = utilsFactory::createAlert('Error al actualizar el estado del plan.', 'danger');
                echo json_encode(['success' => false, 'alert' => $alert]);
            }
            break;

            case 'getPdfPath':
                $planId = $_POST['planId'];
                $pdfPath = $planAppService->getPlanPdfPath($planId); // Método para obtener la ruta del PDF del plan
                
                if ($pdfPath !== false) {
                    // Concatenar la ruta completa del servidor
                    $fullPdfPath = 'http://localhost/AW-project/pdf/' . $pdfPath;
                    
                    echo json_encode([
                        'success' => true,
                        'data' => [
                            'pdfPath' => $fullPdfPath,  // Devolver la ruta completa al cliente
                        ]
                    ]);
                } else {
                    $alert = utilsFactory::createAlert('PDF no encontrado.', 'danger');
                    echo json_encode(['success' => false, 'alert' => $alert]);
                }
                break;
            
            
            
        default:
            $alert = utilsFactory::createAlert('Acción no válida.', 'danger');
            echo json_encode(['success' => false, 'alert' => $alert]);
            break;
    }
}