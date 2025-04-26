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
        $possibleExtensions = ['png', 'jpg', 'jpeg', 'gif'];

        foreach ($possibleExtensions as $extension) {
            $path = '/img/plans/' . $imageGuid . '.' . $extension;
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {
                return $path;
            }
        }

        return '/img/default-plan.png';
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
                $planData['created_at'],
                $planData['is_active']
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
     * @param string $image Ruta de la imagen
     * 
     * @return string Nombre del archivo guardado
     */
    public function savePlanImage($image)
    {
        $guid = Uuid::uuid4()->toString();

        // Guardar la imagen en el sistema de archivos
        $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR;
        $extension = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
        $filename = $guid . '.' . $extension;
        $uploadPath = $uploadDir . $filename;

        if (!move_uploaded_file($imageFile['tmp_name'], $uploadPath)) {
            throw new \Exception('Error al subir la imagen del plan.');
        }

        return $filename;
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
        $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR;
        $extension = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
        $filename = $guid . '.' . $extension;
        $uploadPath = $uploadDir . $filename;

        if (!move_uploaded_file($imageFile['tmp_name'], $uploadPath)) {
            throw new \Exception('Error al subir el pdf del plan.');
        }

        return $filename;
    }
}