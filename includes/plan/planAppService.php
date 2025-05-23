<?php

namespace TheBalance\plan;

require_once __DIR__ . '/../../vendor/autoload.php';

use TheBalance\application;
use Ramsey\Uuid\Uuid;

/**
 * Clase que contiene la lógica de la aplicación de planes de entrenamiento
 */
class planAppService
{
    // Patrón Singleton
    private static $instance;

    public static function GetSingleton()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }
    
    private function __construct()
    {
    }

    /**
     * Busca planes de entrenamiento con filtros
     * 
     * @param array $filters Filtros de búsqueda (name, trainer_id, difficulty, minPrice, maxPrice)
     * @return array Lista de trainingPlanDTO
     */
    public function searchTrainingPlans($filters)
    {
        $ITrainingPlan = planFactory::CreateTrainingPlan();
        return $ITrainingPlan->searchTrainingPlans($filters);
    }

    /**
     * Obtiene la lista de entrenadores disponibles
     * 
     * @return array Lista de trainerDTO
     */
    public function getTrainers()
    {
        $ITrainingPlan = planFactory::CreateTrainingPlan();
        return $ITrainingPlan->getTrainers();
    }

    /**
     * Obtiene los niveles de dificultad disponibles
     * 
     * @return array Lista de dificultades
     */
    public function getDifficulties()
    {
        return ['Principiante', 'Intermedio', 'Avanzado', 'Experto'];
    }

    /**
     * Obtiene la ruta de la imagen del plan
     * 
     * @param string $imageGuid GUID de la imagen
     * @return string Ruta relativa de la imagen
     */
    public function getPlanImagePath($imageGuid)
    {
        // Definir las extensiones posibles
        $possibleExtensions = ['png', 'jpg', 'jpeg'];

        // Buscar el archivo con la extensión correcta
        foreach ($possibleExtensions as $extension) {
            $path = IMG_PATH . '/' . 'plans' . '/' . $imageGuid . '.' . $extension;
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {
                return $path;
            }
        }

        // Si no se encuentra, devolver una imagen por defecto
        return '/AW-project/img/default.png';
    }

    /**
     * Obtiene un plan específico por su ID
     * 
     * @param int $id ID del plan
     * @return planDTO Datos del plan
     */
    public function getPlanById($id)
    {
        $ITrainingPlan = planFactory::CreateTrainingPlan();
        return $ITrainingPlan->getPlanById($id);
    }

    /**
     * Registra un nuevo plan de entrenamiento
     * 
     * @param array $planData Datos del plan
     * @return int|false ID del plan registrado o false en caso de error
     */
    public function registerPlan($planData)
    {
        try {
            // Guardar archivos
            $imageGuid = $this->savePlanImage($planData['image_file']);
            $pdfPath = $this->savePlanPdf($planData['pdf_file']);
            
            // Crear DTO del plan
            $planDTO = new planDTO(
                null,
                $planData['trainer_id'],
                $planData['name'],
                $planData['description'],
                $planData['difficulty'],
                $planData['duration'],
                $planData['price'],
                $imageGuid,
                $pdfPath,
                $planData['created_at']
            );
            
            // Registrar en base de datos
            $ITrainingPlan = planFactory::CreateTrainingPlan();
            return $ITrainingPlan->registerPlan($planDTO);
            
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Guarda una imagen en el sistema de archivos
     * 
     * @param array $image Datos del archivo de imagen
     * 
     * @return string Nombre del archivo guardado
     */
    public function savePlanImage($image)
    {
        $guid = Uuid::uuid4()->toString();

        // Guardar la imagen en el sistema de archivos
        $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'plans' . DIRECTORY_SEPARATOR;
        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $filename = $guid . '.' . $extension;
        $uploadPath = $uploadDir . $filename;

        if (!move_uploaded_file($image['tmp_name'], $uploadPath)) {
            throw new \Exception('Error al subir la imagen del plan.');
        }

        // Quitar la extensión del nombre del archivo para el GUID
        // Esto es necesario para evitar problemas al guardar en la base de datos
        $filename = pathinfo($filename, PATHINFO_FILENAME);

        return $filename;
    }


    /**
     * Obtiene los planes de entrenamiento del cliente
     * 
     * @return array Lista de trainingPlanDTO
     */
    public function getClientPlans()
    {
        $IPlan = planFactory::CreateTrainingPlan();
        $plansDTO = null;

        $app = application::getInstance();

        // Tomamos el id del cliente
        $userId = htmlspecialchars($app->getCurrentUserId());

        // Pasamos como filtro el id del cliente
        $plansDTO = $IPlan->getPlansByUserId($userId);

        return $plansDTO;
    }

    public function getPlansByUserType()
    {
        $planDAO = planFactory::CreateTrainingPlan();
        $planDTO = null;

        $app = application::getInstance();

        // Si es administrador, tomamos todos los eventos
        if ($app->isCurrentUserAdmin())
        {
            $planDTO = $planDAO->searchTrainingPlans();
        }
        // Si es proveedor, tomamos SOLO los eventos del proveedor
        else 
        {
            // Tomamos el email del proveedor
            $trainerId = ($app->getCurrentUserId());

            // Pasamos como filtro un array con el email (así solo traerá los eventos donde coincida ese email)
            $planDTO = $planDAO->searchTrainingPlans(array("trainer_id" => $trainerId));
        }

        return $planDTO;
    }
    
    /**
     * Guarda el PDF del plan en el sistema de archivos
     * 
     * @param array $pdfFile Datos del archivo PDF
     * @return string Ruta relativa del PDF guardado
     */
    public function savePlanPdf($pdfFile)
    {
        $guid = Uuid::uuid4()->toString();

        // Guardar el pdf en el sistema de archivos
        $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR;
        $extension = pathinfo($pdfFile['name'], PATHINFO_EXTENSION);
        $filename = $guid . '.' . $extension;
        $uploadPath = $uploadDir . $filename;

        if (!move_uploaded_file($pdfFile['tmp_name'], $uploadPath)) {
            throw new \Exception('Error al subir el pdf del plan.');
        }

        return $filename;
    }

        /**
     * Elimina PLan de laa BBDD
     * 
     * @param int $id ID del plan
     * 
     * @return planDTO Plan encontrado
     */

    //FALTA COMPROBAR SI EL PLAAN ES DEL ENTRENADOR
    public function deletePlan($planId)
    {
        $planDAO = planFactory::CreateTrainingPlan();

        // Tomamos la instancia de la aplicación
        $app = application::getInstance();

        return $planDAO->deletePlan($planId);
    }

    public function updatePlan($planDTO)
    {
        $planDAO = planFactory::CreateTrainingPlan();
    
        // Actualizar el plan
        $updateResult = $planDAO->updatePlan($planDTO);
    
        return $updateResult;
    }

    /**
     * Registra la compra de un plan por parte de un cliente
     * 
     * @param planPurchaseDTO $purchaseData Datos de la compra (plan_id, client_id, purchase_date, status)
     * @return int|false ID de la compra registrada o false en caso de error
     */
    public function createPlanPurchase(planPurchaseDTO $purchaseData)
    {
        try {
            $IPurchase = planFactory::CreatePlanPurchase();
            return $IPurchase->createPurchase($purchaseData);
        } catch (\Exception $e) {
            error_log("Error en createPlanPurchase: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene una compra específica de plan por su ID
     * 
     * @param int $id ID de la compra
     * @return planPurchaseDTO|null
     */
    public function getPlanPurchaseById($id)
    {
        try {
            $IPurchase = planFactory::CreatePlanPurchase();
            return $IPurchase->getPurchaseById($id);
        } catch (\Exception $e) {
            error_log("Error en getPlanPurchaseById: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtiene una compra de plan según el ID del plan y del cliente
     * 
     * @param int $planId
     * @param int $clientId
     * @return planPurchaseDTO|null
     */
    public function getPlanPurchaseByPlanAndClient($planId, $clientId)
    {
        try {
            $IPurchase = planFactory::CreatePlanPurchase();
            return $IPurchase->getPurchaseByPlanAndClient($planId, $clientId);
        } catch (\Exception $e) {
            error_log("Error en getPlanPurchaseByPlanAndClient: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Actualiza el estado de un plan
     * 
     * @param int $planId ID del plan
     * @param string $newStatus Nuevo estado del plan
     * @return bool true si se actualizó correctamente, false en caso contrario
     */
    public function updatePlanStatus($planId, $newStatus)
    {
        $planPurchaseDAO = planFactory::CreatePlanPurchase();
        return $planPurchaseDAO->updatePlanStatus($planId, $newStatus);
    }

    /**
     * Obtiene la ruta del PDF de un plan
     * 
     * @param int $planId ID del plan
     * @return string Ruta del PDF
     */

    public function getPlanPdfPath($planId)
    {
        $ITrainingPlan = planFactory::CreateTrainingPlan();
        return $ITrainingPlan->getPlanPdfPath($planId);
    }
    
}