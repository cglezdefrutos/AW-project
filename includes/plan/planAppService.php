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
}